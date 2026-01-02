<?php 
require '../config.php'; 

// Get patients and medicines for dropdowns
$patients = $conn->query("SELECT id, name, phone FROM patients ORDER BY name");
$medicines = $conn->query("SELECT id, name, price, quantity, batch_no FROM medicines WHERE quantity > 0 ORDER BY id ASC");

$total = 0;
$bill_items = [];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Bill</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>💰 Create New Bill</h3>
        <a href="index.php" class="btn btn-primary">← Dashboard</a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card p-4 mb-4">
                <h5>👤 Select Patient</h5>
                <form id="billForm">
                    <select name="patient_id" id="patient_id" class="form-control mb-3" required>
                        <option value="">Choose Patient</option>
                        <?php while($p = $patients->fetch_assoc()): ?>
                        <option value="<?php echo $p['id']; ?>">
                            <?php echo htmlspecialchars($p['name']); ?> (<?php echo $p['phone']; ?>)
                        </option>
                        <?php endwhile; ?>
                    </select>
                    
                    <h5>💊 Add Medicines (FIFO Queue)</h5>
                    <select id="medicine_id" class="form-control mb-2">
                        <option value="">Select Medicine</option>
                        <?php while($m = $medicines->fetch_assoc()): ?>
                        <option value="<?php echo $m['id']; ?>" 
                                data-price="<?php echo $m['price']; ?>"
                                data-batch="<?php echo $m['batch_no']; ?>"
                                data-stock="<?php echo $m['quantity']; ?>">
                            <?php echo $m['name']; ?> (₹<?php echo $m['price']; ?> | <?php echo $m['batch_no']; ?> | Stock:<?php echo $m['quantity']; ?>)
                        </option>
                        <?php endwhile; ?>
                    </select>
                    <div class="input-group mb-3">
                        <input type="number" id="qty" class="form-control" placeholder="Quantity" min="1" value="1">
                        <button type="button" id="addItem" class="btn btn-success">➕ Add</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-4">
                <h5>📋 Bill Items</h5>
                <table class="table table-sm" id="itemsTable">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                    </tbody>
                </table>
                
                <div class="mt-3">
                    <h5>Summary</h5>
                    <p>Subtotal: ₹<span id="subtotal">0</span></p>
                    <p>Tax (18%): ₹<span id="tax">0</span></p>
                    <p class="fw-bold fs-4 text-success">Grand Total: ₹<span id="grandTotal">0</span></p>
                </div>
                
                <button id="../print_Bill.php" class="btn btn-success w-100 mt-3">💾 Save & Generate Bill</button>
            </div>
        </div>
    </div>
</div>

<script>
let items = [];
let subtotal = 0;

// Add item to cart
document.getElementById('addItem').onclick = function() {
    const medId = document.getElementById('medicine_id').value;
    const qty = parseInt(document.getElementById('qty').value);
    const medOption = document.querySelector(`#medicine_id option[value="${medId}"]`);
    
    if (!medId || !qty) return alert('Select medicine & quantity');
    
    const price = parseFloat(medOption.dataset.price);
    const batch = medOption.dataset.batch;
    const stock = parseInt(medOption.dataset.stock);
    
    if (qty > stock) {
        return alert(`Only ${stock} available in batch ${batch}`);
    }
    
    const item = {
        id: medId,
        name: medOption.text,
        batch: batch,
        qty: qty,
        price: price,
        total: qty * price
    };
    
    items.push(item);
    updateTable();
    updateSummary();
    document.getElementById('medicine_id').value = '';
    document.getElementById('qty').value = 1;
};

// Update items table
function updateTable() {
    const tbody = document.getElementById('itemsBody');
    tbody.innerHTML = '';
    
    items.forEach((item, index) => {
        const row = `
            <tr>
                <td>${item.name} <small class="badge bg-secondary">${item.batch}</small></td>
                <td>${item.qty}</td>
                <td>₹${item.price}</td>
                <td>₹${item.total.toFixed(2)}</td>
                <td><button onclick="removeItem(${index})" class="btn btn-sm btn-danger">×</button></td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

// Remove item
function removeItem(index) {
    items.splice(index, 1);
    updateTable();
    updateSummary();
}

// Update summary
function updateSummary() {
    subtotal = items.reduce((sum, item) => sum + item.total, 0);
    const tax = subtotal * 0.18;
    const grandTotal = subtotal + tax;
    
    document.getElementById('subtotal').textContent = subtotal.toFixed(2);
    document.getElementById('tax').textContent = tax.toFixed(2);
    document.getElementById('grandTotal').textContent = grandTotal.toFixed(2);
}

// Save bill
document.getElementById('saveBill').onclick = function() {
    if (!document.getElementById('patient_id').value || items.length === 0) {
        return alert('Select patient and add medicines');
    }
    
    const formData = new FormData();
    formData.append('patient_id', document.getElementById('patient_id').value);
    formData.append('items', JSON.stringify(items));
    
    fetch('save_bill.php', {
        method: 'POST',
        body: formData
    }).then(res => res.json()).then(data => {
        if (data.success) {
            window.location.href = `print_bill.php?id=${data.bill_id}`;
        } else {
            alert('Error: ' + data.error);
        }
    });
};
</script>
</body>
</html>

<?php
$db = new PDO('mysql:host=localhost;dbname=motorshop_db', 'root', '');

// Create restock_requests table
$sql = "CREATE TABLE IF NOT EXISTS restock_requests (
    request_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    product_id INT(11) NOT NULL,
    supplier_id INT(11) NOT NULL,
    quantity_requested INT(11) NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending',
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(supplier_id) ON DELETE CASCADE
)";
$db->exec($sql);
echo "restock_requests table created.\n";

// Check if supplier_id exists in products, if not, add it
$cols = $db->query("DESCRIBE products")->fetchAll(PDO::FETCH_ASSOC);
$hasSupplierId = false;
foreach ($cols as $col) {
    if ($col['Field'] === 'supplier_id') {
        $hasSupplierId = true;
    }
}

if (!$hasSupplierId) {
    $db->exec("ALTER TABLE products ADD COLUMN supplier_id INT(11) NULL AFTER category_id");
    $db->exec("ALTER TABLE products ADD CONSTRAINT fk_product_supplier FOREIGN KEY (supplier_id) REFERENCES suppliers(supplier_id) ON DELETE SET NULL");
    echo "Added supplier_id to products.\n";
}

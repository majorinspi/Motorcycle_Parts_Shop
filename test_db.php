<?php
$db = new PDO('mysql:host=localhost;dbname=majorinspi', 'root', '');
$stmt = $db->query('SHOW TABLES');
while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
    echo "TABLE: " . $row[0] . "\n";
    $cols = $db->query("DESCRIBE " . $row[0]);
    while ($col = $cols->fetch(PDO::FETCH_ASSOC)) {
        echo "  - " . $col['Field'] . " (" . $col['Type'] . ")\n";
    }
}

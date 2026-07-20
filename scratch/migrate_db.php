<?php
define('BASEPATH', 'true');
define('ENVIRONMENT', 'development');
require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$conn = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if type column exists
$result = $conn->query("SHOW COLUMNS FROM yayasan_candidates LIKE 'type'");
if ($result->num_rows == 0) {
    $alter = $conn->query("ALTER TABLE yayasan_candidates ADD COLUMN type ENUM('individu', 'rundayan') DEFAULT 'individu' AFTER ancestor_name");
    if ($alter) {
        echo "Column 'type' successfully added to yayasan_candidates.\n";
    } else {
        echo "Error adding column: " . $conn->error . "\n";
    }
} else {
    echo "Column 'type' already exists.\n";
}

$conn->close();

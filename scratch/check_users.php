<?php
define('BASEPATH', 'true');
define('ENVIRONMENT', 'development');
require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$conn = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$res = $conn->query("SHOW TABLES LIKE 'users'");
if ($res->num_rows > 0) {
    $res2 = $conn->query("SELECT id, username, full_name, role FROM users LIMIT 10");
    echo "--- USER ACCOUNTS ---\n";
    while ($row = $res2->fetch_assoc()) {
        echo "Username: {$row['username']} | Name: {$row['full_name']} | Role: {$row['role']}\n";
    }
} else {
    echo "Table 'users' not found.\n";
}

$conn->close();

<?php
$cfg = [
    'hostname' => '127.0.0.1',
    'username' => 'root',
    'password' => '',
    'database' => 'samhudi',
];

$conn = new mysqli($cfg['hostname'], $cfg['username'], $cfg['password'], $cfg['database']);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error . "\n");

$res = $conn->query("SHOW COLUMNS FROM users LIKE 'role'");
$row = $res->fetch_assoc();
echo "Current role column: " . $row['Type'] . PHP_EOL;
$conn->close();

<?php
$conn = new mysqli('localhost', 'root', '', 'samhudi');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error . "\n");

$stmt = $conn->prepare("UPDATE users SET email = ? WHERE username = 'dewan_pembina'");
$email = 'dewanpembina@samhudi.com';
$stmt->bind_param('s', $email);
$stmt->execute();

echo $stmt->affected_rows > 0 
    ? "✓ Email berhasil diupdate ke: dewanpembina@samhudi.com\n" 
    : "✗ Tidak ada perubahan / gagal: " . $conn->error . "\n";

$stmt->close();
$conn->close();

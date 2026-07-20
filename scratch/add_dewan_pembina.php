<?php
$conn = new mysqli('localhost', 'root', '', 'samhudi');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error . "\n");

// 1. Add dewan_pembina to ENUM
$res = $conn->query("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin','admin','member','dewan_pembina') NOT NULL DEFAULT 'member'");
echo $res ? "✓ Role 'dewan_pembina' berhasil ditambahkan ke kolom role\n" : "✗ Gagal: " . $conn->error . "\n";

// 2. Check if account already exists
$res2 = $conn->query("SELECT id FROM users WHERE username = 'dewan_pembina'");
if ($res2->num_rows > 0) {
    echo "✓ Akun 'dewan_pembina' sudah ada\n";
} else {
    // Create dewan_pembina account (password: DewanPembina2026)
    $password = password_hash('DewanPembina2026', PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, role) VALUES (?, ?, ?, 'Dewan Pembina', 'dewan_pembina')");
    $email = 'dewan_pembina@samhudi.com';
    $stmt->bind_param('sss', $uname, $email, $password);
    $uname = 'dewan_pembina';
    $stmt->execute();
    echo $stmt->affected_rows > 0 ? "✓ Akun 'dewan_pembina' berhasil dibuat\n" : "✗ Gagal buat akun: " . $conn->error . "\n";
    $stmt->close();
}

$conn->close();
echo "\nSelesai!\n";

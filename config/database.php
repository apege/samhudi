<?php 
$conn = mysqli_connect("localhost", "root", "", "my_database");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Connected successfully";
}
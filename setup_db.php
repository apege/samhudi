<?php
$mysqli = new mysqli("localhost", "root", "", "samhudi");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$sql = "CREATE TABLE IF NOT EXISTS `profile_banners` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `image_path` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($mysqli->query($sql) === TRUE) {
    echo "Table profile_banners created successfully.\n";
    
    // Insert some dummy banners if empty
    $res = $mysqli->query("SELECT COUNT(*) as count FROM profile_banners");
    $row = $res->fetch_assoc();
    if ($row['count'] == 0) {
        $mysqli->query("INSERT INTO profile_banners (image_path) VALUES ('assets/images/background.png')");
        $mysqli->query("INSERT INTO profile_banners (image_path) VALUES ('assets/images/background2.png')");
        echo "Dummy data inserted.\n";
    }
} else {
    echo "Error creating table: " . $mysqli->error . "\n";
}
$mysqli->close();

<?php
require 'db.php';

$admin_pass = 'admin123'; // your default admin password
$hash = password_hash($admin_pass, PASSWORD_DEFAULT);

$pdo->exec("UPDATE admins SET password='$hash' WHERE username='admin'");
echo "Admin password hashed!";

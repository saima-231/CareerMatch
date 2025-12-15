<?php
session_start();
require_once 'db.php';

// Check which user is logged in
if(isset($_SESSION['student_id'])){
    $id = $_SESSION['student_id'];
    $table = 'students';
    $col = 'id';
    session_unset();
    session_destroy();
    $redirect = "index.php?deleted=student";
} elseif(isset($_SESSION['company_id'])){
    $id = $_SESSION['company_id'];
    $table = 'companies';
    $col = 'id';
    session_unset();
    session_destroy();
    $redirect = "index.php?deleted=company";
} else {
    header("Location: index.php");
    exit;
}

// Delete account
$stmt = $pdo->prepare("DELETE FROM $table WHERE $col = ?");
$stmt->execute([$id]);

header("Location: $redirect");
exit;
?>

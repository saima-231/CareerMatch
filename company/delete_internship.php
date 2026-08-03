<?php
session_start();
include "../config/database.php";
if (!isset($_SESSION['company_id'])) {
    header("Location: ../login.php");
    exit();
}
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $stmt = $pdo->prepare("
        DELETE FROM internships
        WHERE internship_id = :id
        AND company_id = :company_id
    ");
    $stmt->execute([
        ":id"=>$id,
        ":company_id"=>$_SESSION['company_id']
    ]);
}
header("Location: manage_internships.php");
exit();
?>
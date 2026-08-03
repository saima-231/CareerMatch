<?php
include "../config/database.php";
if(isset($_GET['id']) && isset($_GET['status'])){
    $company_id = $_GET['id'];
    $status = $_GET['status'];
    $stmt = $pdo->prepare("
        UPDATE companies
        SET company_status = :status
        WHERE company_id = :id
    ");
    $stmt->execute([
        ":status" => $status,
        ":id" => $company_id
    ]);
    header("Location: ../dashboard/admin_dashboard.php");
    exit();
}
else{
    echo "Missing company id or status";
}
?>
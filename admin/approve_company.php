<?php

include "../config/database.php";

if (isset($_GET['id'])) {

    $company_id = $_GET['id'];

    $stmt = $pdo->prepare("
        UPDATE companies
        SET status = 'Approved'
        WHERE company_id = :id
    ");

    $stmt->execute([
        ":id" => $company_id
    ]);
}

header("Location: admin_dashboard.php");
exit();

<?php
session_start();
require_once '../db.php';

if(!isset($_SESSION['company_id'])){
    header('Location: ../index.php');
    exit;
}

if($_SERVER['REQUEST_METHOD']=='POST'){
    $app_id = intval($_POST['app_id']);
    $status = $_POST['status'];

    if(in_array($status,['Accepted','Rejected'])){
        $stmt = $pdo->prepare("UPDATE applications SET status=? WHERE id=?");
        $stmt->execute([$status,$app_id]);
    }
}

header('Location: dashboard.php');
exit;

<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['company_id'])) {
    header('Location: ../index.php');
    exit;
}

$company_id = $_SESSION['company_id'];
$internship_id = $_GET['id'] ?? null;

if($internship_id){
    // Verify that this internship belongs to this company
    $stmt = $pdo->prepare("SELECT * FROM internships WHERE id=? AND company_id=?");
    $stmt->execute([$internship_id, $company_id]);
    $internship = $stmt->fetch();

    if($internship){
        // Delete all applications for this internship first (optional)
        $pdo->prepare("DELETE FROM applications WHERE internship_id=?")->execute([$internship_id]);

        // Delete the internship itself
        $pdo->prepare("DELETE FROM internships WHERE id=?")->execute([$internship_id]);

        header("Location: dashboard.php?deleted=internship");
        exit;
    }
}
header("Location: dashboard.php");
exit;
?>

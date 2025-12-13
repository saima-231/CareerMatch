<?php
session_start();
require_once '../db.php';

if(!isset($_SESSION['role']) || $_SESSION['role']!=='student'){
    header('Location: ../index.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $student_id = $_SESSION['student_id'];
    $internship_id = (int)($_POST['internship_id']);

    // Insert application, ignore duplicate attempts
    $stmt = $pdo->prepare('INSERT INTO applications (student_id, internship_id) VALUES (?,?)');
    try {
        $stmt->execute([$student_id,$internship_id]);
    } catch(Exception $e){}
}

header('Location: internships.php');
exit;

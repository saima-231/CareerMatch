<?php
session_start();
require_once '../db.php';

if(!isset($_SESSION['student_id'])){
    header('Location: ../index.php');
    exit;
}

$student_id = $_SESSION['student_id'];

if(!isset($_GET['id'])){
    header('Location: internship.php');
    exit;
}

$internship_id = $_GET['id'];

// Check if student already applied
$stmt = $pdo->prepare("SELECT * FROM applications WHERE student_id=? AND internship_id=?");
$stmt->execute([$student_id, $internship_id]);
if($stmt->rowCount()>0){
    header('Location: internship.php');
    exit;
}

// Insert application
$stmt = $pdo->prepare("INSERT INTO applications (student_id, internship_id) VALUES (?,?)");
$stmt->execute([$student_id, $internship_id]);

header('Location: internship.php');
exit;

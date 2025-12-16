<?php
session_start();
require_once '../db.php';

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: ../index.php');
    exit;
}

$student_id = $_SESSION['student_id'];

// Check if internship ID is provided
if (!isset($_GET['id'])) {
    // Use absolute path for redirect
    header('Location: /CareerMatch/student/internship.php');
    exit;
}

$internship_id = $_GET['id'];

// Check if student already applied
$stmt = $pdo->prepare("SELECT * FROM applications WHERE student_id=? AND internship_id=?");
$stmt->execute([$student_id, $internship_id]);

if ($stmt->rowCount() > 0) {
    $_SESSION['message'] = "You have already applied for this internship.";
    header('Location: /CareerMatch/student/internship.php'); // Absolute path
    exit;
}

// Insert application with default status 'pending'
$stmt = $pdo->prepare("INSERT INTO applications (student_id, internship_id, status, applied_at) VALUES (?,?,?,NOW())");
$stmt->execute([$student_id, $internship_id, 'pending']);

// Set success message
$_SESSION['message'] = "You have applied successfully!";

// Redirect back to internship page
header('Location: /CareerMatch/student/internship.php'); // Absolute path
exit;

<?php
session_start();
include "../config/database.php";
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}
if (!isset($_GET['type']) || !isset($_GET['id'])) {
    header("Location: ../dashboard/admin_dashboard.php");
    exit();
}
$type = $_GET['type'];
$id = $_GET['id'];

// Delete Student
if ($type == "student") {

    // Delete student's applications first
    $delete_app = $pdo->prepare("
        DELETE FROM applications
        WHERE student_id = :id
    ");
    $delete_app->execute([
        ":id" => $id
    ]);

    // Delete student
    $stmt = $pdo->prepare("
        DELETE FROM students
        WHERE student_id = :id
    ");
}

// Delete Company
elseif ($type == "company") {

    // Delete applications for company's internships
    $delete_app = $pdo->prepare("
        DELETE applications
        FROM applications
        JOIN internships
        ON applications.internship_id = internships.internship_id
        WHERE internships.company_id = :id
    ");
    $delete_app->execute([
        ":id" => $id
    ]);

    // Delete internships
    $delete_internship = $pdo->prepare("
        DELETE FROM internships
        WHERE company_id = :id
    ");
    $delete_internship->execute([
        ":id" => $id
    ]);

    // Delete company
    $stmt = $pdo->prepare("
        DELETE FROM companies
        WHERE company_id = :id
    ");
}

// Invalid type
else {

    header("Location: ../dashboard/admin_dashboard.php");
    exit();
}
$stmt->execute([
    ":id" => $id
]);
header("Location: ../dashboard/admin_dashboard.php");
exit();
?>
<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: internships.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$internship_id = (int)$_GET['id'];

// Check if internship exists and is Active
$internship = $pdo->prepare("
SELECT internship_id
FROM internships
WHERE internship_id = :id
AND status = 'Active'
");

$internship->execute([
    ":id" => $internship_id
]);

if ($internship->rowCount() == 0) {
    echo "<script>
alert('Application submitted successfully.');
window.location='../dashboard/student_dashboard.php';
</script>";
    exit();
}

// Check if already applied
$check = $pdo->prepare("
SELECT application_id
FROM applications
WHERE student_id = :student_id
AND internship_id = :internship_id
");

$check->execute([
    ":student_id" => $student_id,
    ":internship_id" => $internship_id
]);

if ($check->rowCount() > 0) {
    echo "<script>
alert('You have already applied for this internship.');
window.location='../dashboard/student_dashboard.php';
</script>";
    exit();
}

// Insert application
$stmt = $pdo->prepare("
INSERT INTO applications
(
    student_id,
    internship_id,
    application_date,
    status
)
VALUES
(
    :student_id,
    :internship_id,
    NOW(),
    'Pending'
)
");

$stmt->execute([
    ":student_id" => $student_id,
    ":internship_id" => $internship_id
]);

echo "<script>
alert('Application submitted successfully.');
window.location='../internships.php';
</script>";
exit();

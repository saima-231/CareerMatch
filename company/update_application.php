<?php
session_start();
include "../config/database.php";
if (!isset($_SESSION['company_id'])) {
    header("Location: ../login.php");
    exit();
}
if (!isset($_GET['id']) || !isset($_GET['status'])) {
    header("Location: applicants.php");
    exit();
}
$application_id = $_GET['id'];
$status = $_GET['status'];

// Only allow these two statuses
if ($status != "Accepted" && $status != "Rejected") {
    header("Location: applicants.php");
    exit();
}

// Update application status
$stmt = $pdo->prepare("
UPDATE applications
SET status = :status
WHERE application_id = :id
");
$stmt->execute([
    ":status" => $status,
    ":id" => $application_id
]);
echo "
<script>
alert('Application status updated.');
window.location='applicants.php';
</script>
";
exit();

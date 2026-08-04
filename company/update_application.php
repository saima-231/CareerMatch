<?php

session_start();

include "../config/database.php";


// Check company login
if (!isset($_SESSION['company_id'])) {

    header("Location: ../login.php");
    exit();

}


// Check required data

if (!isset($_GET['id']) || !isset($_GET['status'])) {

    header("Location: applicants.php");
    exit();

}



$application_id = $_GET['id'];
$status = $_GET['status'];



// Only allow valid statuses

if ($status != "Approved" && $status != "Rejected") {

    header("Location: applicants.php");
    exit();

}



// Check that this application belongs to this company

$check = $pdo->prepare("

SELECT applications.application_id

FROM applications

JOIN internships

ON applications.internship_id = internships.internship_id

WHERE applications.application_id = :id

AND internships.company_id = :company_id

");


$check->execute([

    ":id" => $application_id,

    ":company_id" => $_SESSION['company_id']

]);



if ($check->rowCount() == 0) {

    echo "Unauthorized action";

    exit();

}




// Update status

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

alert('Application status updated successfully.');

window.location='applicants.php';

</script>

";


exit();

?>
<?php
session_start();
include "../config/database.php";

if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

if (!isset($_GET['id'])) {
    header("Location: applications.php");
    exit();
}

$internship_id = $_GET['id'];


$stmt = $pdo->prepare("
SELECT
    internships.*,
    companies.company_name
FROM internships
JOIN companies
ON internships.company_id = companies.company_id
WHERE internships.internship_id = :id
");

$stmt->execute([
    ":id" => $internship_id
]);

$internship = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Application Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>


<body class="bg-slate-950 text-white p-10">


    <div class="max-w-3xl mx-auto bg-slate-900 p-8 rounded-3xl">


        <h1 class="text-3xl font-bold text-cyan-400">
            <?php echo $internship['title']; ?>
        </h1>


        <p class="mt-3">
            Company:
            <?php echo $internship['company_name']; ?>
        </p>


        <p class="mt-3">
            Category:
            <?php echo $internship['category']; ?>
        </p>


        <p class="mt-3">
            Description:
            <br>
            <?php echo $internship['description']; ?>
        </p>


        <p class="mt-3">
            Required Skills:
            <br>
            <?php echo $internship['requirements']; ?>
        </p>


        <p class="mt-3">
            Duration:
            <?php echo $internship['duration']; ?>
        </p>


        <p class="mt-3">
            Stipend:
            <?php echo $internship['stipend']; ?>
        </p>


        <a href="applications.php"
            class="inline-block mt-6 bg-cyan-400 text-black px-5 py-3 rounded-xl">

            My Applications

        </a>


    </div>


</body>

</html>
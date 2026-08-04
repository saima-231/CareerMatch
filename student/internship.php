<?php
session_start();

include "../config/database.php";

include "../config/update_jobs.php";
include "../config/database.php";
if (!isset($_SESSION['student_id'])) {
    header("Location:../login.php");
    exit();
}
$stmt = $pdo->prepare("
SELECT 
internships.*,
companies.company_name
FROM internships
JOIN companies
ON internships.company_id = companies.company_id
");
$stmt->execute();
$internships = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Internships</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-900 text-white p-10">
    <h1 class="text-4xl font-bold mb-10">
        Available Internships
    </h1>
    <div class="grid md:grid-cols-3 gap-6">
        <?php foreach ($internships as $internship): ?>
            <div class="bg-slate-800 p-6 rounded-3xl">
                <h2 class="text-2xl font-bold">
                    <?= $internship['title']; ?>
                </h2>
                <p class="mt-2 text-cyan-400">
                    <?= $internship['company_name']; ?>
                </p>
                <p>
                    <?= $internship['category']; ?>
                </p>
                <p>
                    <?= $internship['location']; ?>
                </p>
                <p>
                    Duration:
                    <?= $internship['duration']; ?>
                </p>
                <a
                    href="apply.php?id=<?= $internship['internship_id']; ?>"
                    class="inline-block mt-5 bg-cyan-400 text-black px-5 py-2 rounded-xl">
                    Apply Now
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>
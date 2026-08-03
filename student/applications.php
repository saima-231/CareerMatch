<?php
session_start();
include "../config/database.php";
if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}
$student_id = $_SESSION['student_id'];
// Get student's applications
$stmt = $pdo->prepare("
SELECT 
    applications.application_id,
    applications.status,
    internships.title,
    companies.company_name
FROM applications
JOIN internships
ON applications.internship_id = internships.internship_id
JOIN companies
ON internships.company_id = companies.company_id
WHERE applications.student_id = :student_id
ORDER BY applications.application_id DESC
");
$stmt->execute([
    ":student_id" => $student_id
]);
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>

    <title>My Applications</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-slate-950 text-white min-h-screen">
    <div class="max-w-5xl mx-auto p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">
                My Applications
            </h1>
            <a href="/careermatch/dashboard/student_dashboard.php"
                class="bg-cyan-400 text-black px-5 py-3 rounded-xl font-bold">
                Dashboard
            </a>
        </div>
        <?php if (count($applications) > 0): ?>
            <div class="space-y-5">
                <?php foreach ($applications as $application): ?>
                    <div class="bg-slate-900 border border-cyan-900 rounded-3xl p-6">
                        <h2 class="text-xl font-bold text-cyan-400">
                            <?php echo $application['title']; ?>
                        </h2>
                        <p class="mt-2 text-gray-300">
                            Company:
                            <?php echo $application['company_name']; ?>
                        </p>
                        <p class="mt-3">
                            Status:
                            <span class="font-bold text-cyan-400">
                                <?php echo $application['status']; ?>
                            </span>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-slate-900 rounded-3xl p-8 text-center">
                <p class="text-gray-400">
                    You have not applied for any internship yet.
                </p>
                <a href="/careermatch/student/internship.php"
                    class="inline-block mt-5 bg-cyan-400 text-black px-5 py-3 rounded-xl font-bold">
                    Find Internship
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>
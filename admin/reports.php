<?php
session_start();
include "../config/database.php";
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}
// Counts
$total_students = $pdo
    ->query("SELECT COUNT(*) FROM students")
    ->fetchColumn();
$total_companies = $pdo
    ->query("SELECT COUNT(*) FROM companies")
    ->fetchColumn();
$total_internships = $pdo
    ->query("SELECT COUNT(*) FROM internships")
    ->fetchColumn();
$total_applications = $pdo
    ->query("SELECT COUNT(*) FROM applications")
    ->fetchColumn();
$accepted = $pdo->query("
SELECT COUNT(*)
FROM applications
WHERE status='Accepted'
")->fetchColumn();
$rejected = $pdo->query("
SELECT COUNT(*)
FROM applications
WHERE status='Rejected'
")->fetchColumn();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-950 text-white min-h-screen">
    <div class="max-w-6xl mx-auto p-8">
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-3xl font-bold">
                Reports
            </h1>
            <a href="/careermatch/dashboard/admin_dashboard.php"
                class="bg-cyan-400 text-black px-5 py-3 rounded-xl font-bold">
                Dashboard
            </a>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-slate-900 border border-cyan-900 rounded-3xl p-6">
                <h2 class="text-4xl font-bold text-cyan-400">
                    <?php echo $total_students; ?>
                </h2>
                <p class="text-slate-400">
                    Total Students
                </p>
            </div>
            <div class="bg-slate-900 border border-cyan-900 rounded-3xl p-6">
                <h2 class="text-4xl font-bold text-cyan-400">
                    <?php echo $total_companies; ?>
                </h2>
                <p class="text-slate-400">
                    Total Companies
                </p>
            </div>
            <div class="bg-slate-900 border border-cyan-900 rounded-3xl p-6">
                <h2 class="text-4xl font-bold text-cyan-400">
                    <?php echo $total_internships; ?>
                </h2>
                <p class="text-slate-400">
                    Total Internships
                </p>
            </div>
            <div class="bg-slate-900 border border-cyan-900 rounded-3xl p-6">
                <h2 class="text-4xl font-bold text-cyan-400">
                    <?php echo $total_applications; ?>
                </h2>
                <p class="text-slate-400">
                    Total Applications
                </p>
            </div>
            <div class="bg-slate-900 border border-cyan-900 rounded-3xl p-6">
                <h2 class="text-4xl font-bold text-green-400">
                    <?php echo $accepted; ?>
                </h2>
                <p class="text-slate-400">
                    Accepted Applications
                </p>
            </div>
            <div class="bg-slate-900 border border-cyan-900 rounded-3xl p-6">
                <h2 class="text-4xl font-bold text-red-400">
                    <?php echo $rejected; ?>
                </h2>
                <p class="text-slate-400">
                    Rejected Applications
                </p>
            </div>
        </div>
    </div>
</body>

</html>
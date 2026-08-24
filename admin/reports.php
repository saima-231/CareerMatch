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

    <head>

        <title>CareerMatch Admin</title>

        <script src="https://cdn.tailwindcss.com"></script>

        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primaryDark: "#091D3E",
                            primary: "#06B6D4",
                            secondary: "#67E8F9",
                            cream: "#F8FAFC",
                        },
                    },
                },
            };
        </script>

    </head>
</head>

<body class="bg-primaryDark text-cream min-h-screen">
    <div class="relative min-h-screen">
        <?php include "../includes/admin_sidebar.php"; ?>
        <main class="ml-72 min-h-screen p-6 md:p-10">
            <div class="flex justify-between items-center mb-10">
                <h1 class="text-3xl font-bold mb-8">
                    System
                    <span class="text-primary">
                        Reports
                    </span>
                    📊
                </h1>
                <a href="../dashboard/admin_dashboard.php"
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
    </main>
    </div>
</body>

</html>
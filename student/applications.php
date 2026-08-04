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
<html lang="en">
<head>
    <title>My Applications</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primaryDark: "#091D3E",
                        primary: "#06B6D4",
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-primaryDark text-white min-h-screen">
    <div class="relative flex min-h-screen">
        <!-- SIDEBAR -->
        <?php include "../includes/student_sidebar.php"; ?>
        <!-- MAIN CONTENT -->
        <main class="flex-1 p-6 md:p-10">
            <!-- TOP BAR -->
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h1 class="text-3xl font-bold">
                        My
                        <span class="text-primary">
                            Applications
                        </span>
                    </h1>
                    <p class="text-slate-400 mt-2">
                        Track your internship application status
                    </p>
                </div>
                <div class="w-14 h-14 rounded-full bg-primary/20 flex items-center justify-center text-3xl">
                    📄
                </div>
            </div>
            <!-- APPLICATION LIST -->
            <?php if (count($applications) > 0): ?>
                <div class="space-y-5">
                    <?php foreach ($applications as $application): ?>
                        <div class="bg-slate-900 border border-cyan-900 rounded-3xl p-6">
                            <h2 class="text-xl font-bold text-cyan-400">
                                <?php echo $application['title']; ?>
                            </h2>
                            <p class="mt-3 text-gray-300">
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
        </main>
    </div>
</body>

</html>
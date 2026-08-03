<?php
session_start();
include "../config/database.php";
if (!isset($_SESSION['company_id'])) {
    header("Location: ../login.php");
    exit();
}
$company_id = $_SESSION['company_id'];
$stmt = $pdo->prepare("
    SELECT *
    FROM internships
    WHERE company_id = :company_id
    ORDER BY created_at DESC
");
$stmt->execute([
    ":company_id" => $company_id
]);
$internships = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Internships - CareerMatch</title>
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
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-primaryDark text-cream min-h-screen">
    <div class="fixed w-96 h-96 bg-primary/20 blur-3xl rounded-full top-0 left-0"></div>
    <div class="fixed w-96 h-96 bg-secondary/10 blur-3xl rounded-full bottom-0 right-0"></div>
    <div class="relative p-6 md:p-10">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">
                My Internships
            </h1>
            <a href="post_internship.php"
                class="bg-primary text-primaryDark px-5 py-3 rounded-xl font-semibold">
                + Post Internship
            </a>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (count($internships) > 0): ?>
                <?php foreach ($internships as $internship): ?>
                    <div class="bg-slate-900/70 border border-cyan-900 rounded-3xl p-6">
                        <h2 class="text-xl font-bold text-primary">
                            <?php echo htmlspecialchars($internship['title']); ?>
                        </h2>
                        <p class="mt-3 text-slate-400">
                            Category:
                            <?php echo htmlspecialchars($internship['category']); ?>
                        </p>
                        <p class="mt-2">
                            📍 <?php echo htmlspecialchars($internship['location']); ?>
                        </p>
                        <p class="mt-2">
                            ⏳ <?php echo htmlspecialchars($internship['duration']); ?>
                        </p>
                        <p class="mt-2">
                            💻 <?php echo htmlspecialchars($internship['internship_type']); ?>
                        </p>
                        <p class="mt-3">
                            Status:
                            <span class="text-green-400">
                                <?php echo htmlspecialchars($internship['status']); ?>
                            </span>
                        </p>
                        <div class="flex gap-3 mt-5">
                            <a href="#"
                                class="bg-yellow-500/20 text-yellow-400 px-4 py-2 rounded-xl">
                                Edit
                            </a>
                            <a href="#"
                                class="bg-red-500/20 text-red-400 px-4 py-2 rounded-xl">
                                Delete
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="bg-slate-900/70 border border-cyan-900 rounded-3xl p-6">
                    <p class="text-slate-400">
                        No internships posted yet.
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
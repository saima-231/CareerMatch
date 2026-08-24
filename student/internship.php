<?php
session_start();
include "../config/database.php";
if (!isset($_SESSION['student_id'])) {
    header("Location:../login.php");
    exit();
}
// Fetch internships
$stmt = $pdo->prepare("
SELECT 
    internships.*,
    companies.company_name
FROM internships
JOIN companies
ON internships.company_id = companies.company_id
ORDER BY internships.internship_id DESC
");

$stmt->execute();
$internships = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Find Internships - CareerMatch</title>
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

<body class="bg-primaryDark text-white min-h-screen">
    <div class="relative min-h-screen">
        <!-- SIDEBAR -->

        <?php include "../includes/student_sidebar.php"; ?>
        <!-- MAIN -->
        <main class="ml-72 min-h-screen p-6 md:p-10">
            <!-- TOP BAR -->
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h2 class="text-3xl font-bold">
                        Available
                        <span class="text-primary">
                            Internships
                        </span>
                        💼
                    </h2>
                    <p class="text-slate-400 mt-2">
                        Find internships that match your skills
                    </p>
                </div>
                <div class="w-14 h-14 rounded-full bg-primary/20 flex items-center justify-center text-3xl">
                    🎓
                </div>
            </div>
            <!-- INTERNSHIP CARDS -->
            <?php if (count($internships) > 0): ?>
                <div class="grid md:grid-cols-3 gap-6">
                    <?php foreach ($internships as $internship): ?>
                        <div class="bg-slate-900/70 backdrop-blur-lg border border-cyan-900 p-6 rounded-3xl hover:border-primary transition">
                            <h2 class="text-2xl font-bold">
                                <?= htmlspecialchars($internship['title']); ?>
                            </h2>
                            <p class="mt-2 text-cyan-400">
                                <?= htmlspecialchars($internship['company_name']); ?>
                            </p>
                            <p class="mt-3">
                                <span class="font-bold">
                                    Required Skills:
                                </span>
                                <?= htmlspecialchars($internship['requirements'] ?? 'Not specified'); ?>
                            </p>
                            <p class="text-slate-300 mt-2">
                                Location:
                                <?= htmlspecialchars($internship['location']); ?>
                            </p>
                            <p class="text-slate-300">
                                Duration:
                                <?= htmlspecialchars($internship['duration']); ?>
                            </p>
                            <p class="text-slate-300">
                                Deadline:
                                <?= htmlspecialchars($internship['deadline']); ?>
                            </p>
                            <?php
                            $today = date("Y-m-d");
                            if ($internship['deadline'] >= $today):
                            ?>
                                <a href="apply.php?id=<?= $internship['internship_id']; ?>"
                                    class="inline-block mt-5 bg-cyan-400 text-black px-5 py-2 rounded-xl">
                                    Apply Now
                                </a>
                            <?php else: ?>
                                <div class="mt-5 bg-red-500/20 border border-red-500/40 text-red-300 px-4 py-3 rounded-xl">
                                    ⏰ Application time is over for this internship.
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-slate-900/70 border border-cyan-900 rounded-3xl p-10 text-center">
                    <h3 class="text-xl font-semibold">
                        No internships available
                    </h3>
                    <p class="text-slate-400 mt-2">
                        Please check again later.
                    </p>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>


</html>
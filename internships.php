<?php
session_start();

include "config/database.php";
include "config/update_jobs.php";

$user = null;
$profileLink = "#";

if (isset($_SESSION['student_id'])) {
    $user = $_SESSION['student_name'];
    $profileLink = "dashboard/student_dashboard.php";
} elseif (isset($_SESSION['company_id'])) {
    $user = $_SESSION['company_name'];
    $profileLink = "dashboard/company_dashboard.php";
} elseif (isset($_SESSION['admin_id'])) {
    $user = $_SESSION['admin_name'];
    $profileLink = "dashboard/admin_dashboard.php";
}


// Search and category filter
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

$sql = "
SELECT
    internships.*,
    companies.company_name
FROM internships
JOIN companies
ON internships.company_id = companies.company_id
WHERE internships.status = 'Active'
";

$params = [];

if ($search != '') {
    $sql .= "
    AND (
        internships.title LIKE :search
        OR internships.category LIKE :search
        OR companies.company_name LIKE :search
    )
    ";

    $params[':search'] = "%$search%";
}

if ($category != '') {
    $sql .= "
    AND internships.category = :category
    ";

    $params[':category'] = $category;
}

$sql .= " ORDER BY internships.internship_id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$internships = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CareerMatch - Internships</title>
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
    <!-- Background Glow -->
    <div class="fixed w-96 h-96 bg-primary/20 blur-3xl rounded-full top-0 left-0"></div>
    <div class="fixed w-96 h-96 bg-secondary/10 blur-3xl rounded-full bottom-0 right-0"></div>
    <div class="relative">
        <!-- NAVBAR -->
        <nav class="bg-slate-900/70 border-b border-cyan-900 px-6 py-5">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-5">

                <!-- Logo -->
                <h1 class="text-3xl font-bold whitespace-nowrap">
                    Career<span class="text-primary">Match</span>
                </h1>


                <!-- Navigation -->
                <div class="flex flex-wrap justify-center items-center gap-4 text-sm">

                    <a href="index.php"
                        class="hover:text-secondary whitespace-nowrap">
                        Home
                    </a>
                    <a href="internships.php"
                        class="text-secondary whitespace-nowrap">
                        Internships
                    </a>
                    <a href="companies.php"
                        class="hover:text-secondary whitespace-nowrap">
                        Companies
                    </a>
                    <?php if ($user): ?>
                        <a href="<?php echo $profileLink; ?>"
                            class="flex items-center gap-2 hover:text-secondary whitespace-nowrap">
                            👤
                            <?php echo htmlspecialchars($user); ?>
                        </a>
                        <a href="logout.php"
                            class="bg-red-500 text-white px-5 py-2 rounded-xl font-semibold whitespace-nowrap">
                            Logout
                        </a>


                    <?php else: ?>

                        <a href="login.php"
                            class="bg-primary text-primaryDark px-5 py-2 rounded-xl font-semibold whitespace-nowrap">
                            Login
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
        <main class="max-w-7xl mx-auto p-6 md:p-10">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold">
                    <?php
                    if ($category != '') {
                        echo htmlspecialchars($category) . " Internships";
                    } else {
                        echo "Available Internships";
                    }
                    ?>
                </h2>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <?php if (count($internships) > 0): ?>
                    <?php foreach ($internships as $internship): ?>
                        <div class="bg-slate-900/70 border border-cyan-900 rounded-3xl p-6">
                            <h3 class="text-xl font-bold text-primary">
                                <?php echo htmlspecialchars($internship['title']); ?>
                            </h3>
                            <p class="text-slate-400 mt-2">
                                🏢 <?php echo htmlspecialchars($internship['company_name']); ?>
                            </p>
                            <p class="mt-4">
                                📍 <?php echo htmlspecialchars($internship['location']); ?>
                            </p>
                            <p class="mt-2">
                                ⏳ <?php echo htmlspecialchars($internship['duration']); ?>
                            </p>
                            <p class="mt-2">
                                💰 Stipend:
                                <?php echo htmlspecialchars($internship['stipend']); ?>
                            </p>
                            <p class="mt-4 text-slate-400">
                                <?php echo htmlspecialchars($internship['description']); ?>
                            </p>
                            <div class="mt-4 space-y-2 text-sm">
                                <p>
                                    <b>Required Skills:</b>
                                    <?php echo htmlspecialchars($internship['requirements']); ?>
                                </p>
                                <p>
                                    <b>Internship Type:</b>
                                    <?php echo htmlspecialchars($internship['internship_type']); ?>
                                </p>
                                <?php if (!empty($internship['deadline'])): ?>
                                    <p>
                                        <b>Application Deadline:</b>
                                        <?php echo htmlspecialchars($internship['deadline']); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <?php if (isset($_SESSION['student_id'])): ?>
                                <a href="/student/apply.php?id=<?php echo $internship['internship_id']; ?>"
                                    class="inline-block mt-5 bg-primary text-primaryDark px-5 py-2 rounded-xl font-semibold">
                                    Apply
                                </a>
                            <?php else: ?>
                                <a href="login.php"
                                    class="inline-block mt-5 bg-primary text-primaryDark px-5 py-2 rounded-xl font-semibold">
                                    Apply
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="bg-slate-900/70 border border-cyan-900 rounded-3xl p-6">
                        <p class="text-slate-400">
                            No internships available currently.
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>

</html>
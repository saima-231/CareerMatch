<?php
session_start();

include "config/database.php";

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


// Get companies
$stmt = $pdo->prepare("
    SELECT 
        company_name,
        industry,
        company_size,
        website,
        address,
        logo
    FROM companies
    WHERE company_status = 'Approved'
    ORDER BY company_id DESC
");

$stmt->execute();

$companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CareerMatch - Companies</title>
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
                <!-- Menu -->
                <div class="flex flex-wrap justify-center items-center gap-4 text-sm">
                    <a href="index.php"
                        class="hover:text-secondary whitespace-nowrap">
                        Home
                    </a>
                    <a href="internships.php"
                        class="hover:text-secondary whitespace-nowrap">
                        Internships
                    </a>
                    <a href="companies.php"
                        class="text-secondary whitespace-nowrap">
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
        <!-- CONTENT -->
        <main class="max-w-7xl mx-auto p-6 md:p-10">
            <h2 class="text-3xl font-bold mb-8">
                Partner Companies
            </h2>
            <div class="grid md:grid-cols-3 gap-6">
                <?php if (count($companies) > 0): ?>
                    <?php foreach ($companies as $company): ?>
                        <div class="bg-slate-900/70 border border-cyan-900 rounded-3xl p-6">
                            <div class="w-16 h-16 rounded-full bg-primary/20 flex items-center justify-center text-3xl mb-5">
                                🏢
                            </div>
                            <h3 class="text-xl font-bold text-primary">
                                <?php echo htmlspecialchars($company['company_name']); ?>
                            </h3>
                            <p class="text-slate-400 mt-2">
                                <?php echo htmlspecialchars($company['industry'] ?? 'Industry not specified'); ?>
                            </p>
                            <div class="mt-4 space-y-2">
                                <p>
                                    Company Size:
                                    <span class="text-secondary">
                                        <?php echo htmlspecialchars($company['company_size'] ?? 'N/A'); ?>
                                    </span>
                                </p>
                                <p>
                                    Location:
                                    <span class="text-secondary">
                                        <?php echo htmlspecialchars($company['address'] ?? 'N/A'); ?>
                                    </span>
                                </p>
                                <?php if (!empty($company['website'])): ?>
                                    <p>
                                        Website:
                                        <a href="<?php echo htmlspecialchars($company['website']); ?>"
                                            target="_blank"
                                            class="text-primary hover:text-secondary">
                                            Visit
                                        </a>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="bg-slate-900/70 border border-cyan-900 rounded-3xl p-6">
                        <p class="text-slate-400">
                            No approved companies available yet.
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>

</html>
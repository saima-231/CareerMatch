<?php
session_start();
include "../config/database.php";
if (!isset($_SESSION['company_id'])) {
    header("Location: ../login.php");
    exit();
}
$company_id = $_SESSION['company_id'];

// Get company information
$stmt = $pdo->prepare(
    "SELECT * FROM companies WHERE company_id=:id"
);
$stmt->execute([
    ":id" => $company_id
]);
$company = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$company) {
    echo "Company not found";
    exit();
}

// Get company internships
$internship_stmt = $pdo->prepare("
SELECT *
FROM internships
WHERE company_id=:company_id
");
$internship_stmt->execute([
    ":company_id" => $company_id
]);
$internships = $internship_stmt->fetchAll(PDO::FETCH_ASSOC);

// Count applications received
$app_stmt = $pdo->prepare("
SELECT COUNT(*) 
FROM applications
JOIN internships
ON applications.internship_id = internships.internship_id
WHERE internships.company_id=:company_id
");
$app_stmt->execute([
    ":company_id" => $company_id
]);
$total_applications = $app_stmt->fetchColumn();
// Get applicants
$applicant_stmt = $pdo->prepare("
SELECT 
    applications.application_id,
    applications.status,
    students.full_name,
    students.email,
    internships.title
FROM applications
JOIN students
ON applications.student_id = students.student_id
JOIN internships
ON applications.internship_id = internships.internship_id
WHERE internships.company_id = :company_id
ORDER BY applications.application_id DESC
");
$applicant_stmt->execute([
    ":company_id" => $company_id
]);
$applicants = $applicant_stmt->fetchAll(PDO::FETCH_ASSOC);
// Count shortlisted applications
$shortlisted_stmt = $pdo->prepare("
SELECT COUNT(*)
FROM applications
JOIN internships
ON applications.internship_id = internships.internship_id
WHERE internships.company_id = :company_id
AND applications.status = 'Accepted'
");

$shortlisted_stmt->execute([
    ":company_id" => $company_id
]);

$total_shortlisted = $shortlisted_stmt->fetchColumn();
// Count selected applications
$selected_stmt = $pdo->prepare("
SELECT COUNT(*)
FROM applications
JOIN internships
ON applications.internship_id = internships.internship_id
WHERE internships.company_id = :company_id
AND applications.status = 'Selected'
");

$selected_stmt->execute([
    ":company_id" => $company_id
]);

$total_selected = $selected_stmt->fetchColumn();
// Recent Applicants
$recent_stmt = $pdo->prepare("
    SELECT 
        applications.application_id,
        applications.status,
        students.full_name,
        internships.title
    FROM applications
    JOIN students 
        ON applications.student_id = students.student_id
    JOIN internships 
        ON applications.internship_id = internships.internship_id
    WHERE internships.company_id = :company_id
    ORDER BY applications.application_id DESC
    LIMIT 5
");

$recent_stmt->execute([
    ":company_id" => $company_id
]);

$recent_applicants = $recent_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CareerMatch - Company Dashboard</title>
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

<body class="bg-primaryDark text-cream min-h-screen">
    <!-- Background Glow -->
    <div class="fixed w-96 h-96 bg-primary/20 blur-3xl rounded-full top-0 left-0"></div>
    <div class="fixed w-96 h-96 bg-secondary/10 blur-3xl rounded-full bottom-0 right-0"></div>
    <div class="relative flex min-h-screen">
        <!-- SIDEBAR -->
        <aside class="hidden md:flex w-72 bg-slate-900/70 backdrop-blur-lg border-r border-cyan-900 flex-col p-6">
            <!-- Logo -->
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold">
                    Career<span class="text-primary"> Match </span>
                </h1>
                <div class="w-16 h-[2px] bg-primary mx-auto mt-3"></div>
            </div>
            <!-- Navigation -->
            <nav class="space-y-4">
                <a href="../dashboard/company_dashboard.php"
                    class="block px-4 py-3 rounded-xl bg-primary text-primaryDark font-semibold">
                    🏢 Profile
                </a>
                <a href="/careermatch/company/post_internship.php"
                    class="block px-4 py-3 rounded-xl hover:bg-slate-800">
                    ➕ Post Internship
                </a>
                <a href="/careermatch/company/manage_internships.php"
                    class="block px-4 py-3 rounded-xl hover:bg-slate-800">
                    📋 My Internships
                </a>
                <a href="/careermatch/company/applicants.php"
                    class="block px-4 py-3 rounded-xl hover:bg-slate-800">
                    👥 Applicants
                </a>
                <a href=" ../index.php"
                    class="block px-4 py-3 rounded-xl bg-primary text-primaryDark font-semibold">
                    Back to Home
                </a>
            </nav>
            <!-- Logout -->
            <div class="mt-auto">
                <a href="../logout.php"
                    class="block text-center bg-red-500/20 border border-red-500/40 py-3 rounded-xl hover:bg-red-500/40">
                    Logout
                </a>
            </div>
        </aside>

        <!-- MAIN -->
        <main class="flex-1 p-6 md:p-10">
            <!-- HEADER -->
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h2 class="text-3xl font-bold">
                        Welcome,
                        <span class="text-primary">
                            <?php echo $company['company_name']; ?>
                        </span>
                        🏢
                    </h2>
                    <p class="text-slate-400 mt-2">
                        Manage internships and find talented students
                    </p>
                </div>
                <a href="../company/update_profile.php"
                    class="bg-primary text-primaryDark px-5 py-2 rounded-xl font-semibold">
                    ✏ Edit Profile
                </a>
            </div>
            <!-- STAT CARDS -->
            <div class="grid md:grid-cols-4 gap-5 mb-10">
                <div class="bg-slate-900/70 border border-cyan-900 rounded-3xl p-6">
                    <h3 class="text-3xl font-bold text-primary">
                        <?php echo count($internships); ?>
                    </h3>
                    <p class="text-slate-400">Active Internships</p>
                </div>
                <div class="bg-slate-900/70 border border-cyan-900 rounded-3xl p-6">
                    <h3 class="text-3xl font-bold text-primary">
                        <?php echo $total_applications; ?>
                    </h3>
                    <p class="text-slate-400">Applications</p>
                </div>
                <div class="bg-slate-900/70 border border-cyan-900 rounded-3xl p-6">
                    <h3 class="text-3xl font-bold text-primary">
                        <?php echo $total_shortlisted; ?>
                    </h3>
                    <p class="text-slate-400">Shortlisted</p>
                </div>
                <div class="bg-slate-900/70 border border-cyan-900 rounded-3xl p-6">
                    <h3 class="text-3xl font-bold text-primary">
                        <?php echo $total_selected; ?>
                    </h3>
                    <p class="text-slate-400">Selected</p>
                </div>
            </div>

            <!-- POST INTERNSHIP -->
            <section class="bg-slate-900/70 border border-cyan-900 rounded-3xl p-6 mb-8">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold">Post New Internship</h3>
                    <a href="/careermatch/company/post_internship.php"
                        class="bg-primary text-primaryDark px-5 py-2 rounded-xl font-semibold">
                        + Create
                    </a>
                </div>
            </section>
            <!-- INTERNSHIPS -->
            <?php foreach ($internships as $internship): ?>
                <div class="bg-slate-900/70 border border-cyan-900 rounded-3xl p-6 mb-8">
                    <h4 class="text-xl font-bold">
                        <?php echo $internship['title']; ?>
                    </h4>
                    <p class="text-slate-400 mt-2">
                        <?php echo $internship['category']; ?>
                    </p>
                    <p class="text-primary mt-3">
                        <?php echo $internship['location']; ?>
                        •
                        <?php echo $internship['duration']; ?>
                    </p>
                    <p class="mt-3">
                        Stipend:
                        <?php echo $internship['stipend']; ?>
                    </p>
                </div>
            <?php endforeach; ?>
            <!-- APPLICANTS -->
            <section class="bg-slate-900/70 border border-cyan-900 rounded-3xl p-6 mb-4">

                <h3 class="text-xl font-bold mb-5">
                    Recent Applicants
                </h3>


                <div class="space-y-4">

                    <?php foreach ($recent_applicants as $applicant): ?>

                        <div class="border-b border-slate-700 pb-4 mb-4">

                            <h4 class="text-lg font-semibold text-white">
                                <?= htmlspecialchars($applicant['full_name']); ?>
                            </h4>


                            <p class="text-slate-400">
                                Applied for:
                                <?= htmlspecialchars($applicant['title']); ?>
                            </p>


                            <div class="mt-2">

                                <?php if ($applicant['status'] == "Accepted"): ?>

                                    <span class="bg-green-500/20 border border-green-500/40 
                        text-green-400 px-3 py-1 rounded-lg text-sm">
                                        ✓ Accepted
                                    </span>


                                <?php elseif ($applicant['status'] == "Rejected"): ?>

                                    <span class="bg-red-500/20 border border-red-500/40 
                        text-red-400 px-3 py-1 rounded-lg text-sm">
                                        ✕ Rejected
                                    </span>


                                <?php else: ?>

                                    <span class="bg-yellow-500/20 border border-yellow-500/40 
                        text-yellow-400 px-3 py-1 rounded-lg text-sm">
                                        Pending
                                    </span>

                                <?php endif; ?>

                            </div>

                        </div>


                    <?php endforeach; ?>

                </div>

            </section>
        </main>
    </div>
</body>

</html>
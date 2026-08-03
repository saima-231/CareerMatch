<?php
session_start();
include "../config/database.php";
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

// Get all applications
$stmt = $pdo->prepare("
SELECT

    applications.application_id,
    applications.status,
    applications.application_date,

    students.full_name,
    students.email,

    internships.title,

    companies.company_name

FROM applications

JOIN students
ON applications.student_id = students.student_id

JOIN internships
ON applications.internship_id = internships.internship_id

JOIN companies
ON internships.company_id = companies.company_id

ORDER BY applications.application_id DESC
");
$stmt->execute();
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Applications Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-950 text-white min-h-screen">
    <div class="max-w-7xl mx-auto p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">
                Applications
            </h1>
            <a href="/careermatch/dashboard/admin_dashboard.php"
                class="bg-cyan-400 text-black px-5 py-3 rounded-xl font-bold">
                Dashboard
            </a>
        </div>
        <div class="bg-slate-900 rounded-3xl p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-slate-700 text-slate-400">
                            <th class="p-3">
                                Student
                            </th>
                            <th class="p-3">
                                Email
                            </th>
                            <th class="p-3">
                                Internship
                            </th>
                            <th class="p-3">
                                Company
                            </th>
                            <th class="p-3">
                                Status
                            </th>
                            <th class="p-3">
                                Date
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $application): ?>
                            <tr class="border-b border-slate-800">
                                <td class="p-3">
                                    <?php echo htmlspecialchars($application['full_name']); ?>
                                </td>
                                <td class="p-3">
                                    <?php echo htmlspecialchars($application['email']); ?>
                                </td>
                                <td class="p-3">
                                    <?php echo htmlspecialchars($application['title']); ?>
                                </td>
                                <td class="p-3">
                                    <?php echo htmlspecialchars($application['company_name']); ?>
                                </td>
                                <td class="p-3 text-cyan-400">
                                    <?php echo htmlspecialchars($application['status']); ?>
                                </td>
                                <td class="p-3">
                                    <?php echo htmlspecialchars($application['application_date']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
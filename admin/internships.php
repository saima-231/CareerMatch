<?php
session_start();
include "../config/database.php";
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['delete'])) {
    $internship_id = $_GET['delete'];
    // Delete applications first
    $delete_apps = $pdo->prepare("
DELETE FROM applications
WHERE internship_id = :id
");
    $delete_apps->execute([
        ":id" => $internship_id
    ]);
    // Delete internship
    $delete = $pdo->prepare("
DELETE FROM internships
WHERE internship_id = :id
");
    $delete->execute([
        ":id" => $internship_id
    ]);
    $delete->execute([
        ":id" => $internship_id
    ]);
    echo "
    <script>
    alert('Internship Deleted');
    window.location='internships.php';
    </script>
    ";
}

// Get all internships
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
<html>

<head>
    <title>Internships Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-950 text-white min-h-screen">
    <div class="max-w-6xl mx-auto p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">
                Internships
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
                                Title
                            </th>
                            <th class="p-3">
                                Company
                            </th>
                            <th class="p-3">
                                Location
                            </th>
                            <th class="p-3">
                                Duration
                            </th>
                            <th class="p-3">
                                Stipend
                            </th>
                            <th class="p-3">
                                Status
                            </th>
                            <th class="p-3">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($internships as $internship): ?>
                            <tr class="border-b border-slate-800">
                                <td class="p-3">
                                    <?php echo htmlspecialchars($internship['title']); ?>
                                </td>
                                <td class="p-3">
                                    <?php echo htmlspecialchars($internship['company_name']); ?>
                                </td>
                                <td class="p-3">
                                    <?php echo htmlspecialchars($internship['location']); ?>
                                </td>
                                <td class="p-3">
                                    <?php echo htmlspecialchars($internship['duration']); ?>
                                </td>
                                <td class="p-3">
                                    <?php echo htmlspecialchars($internship['stipend']); ?>
                                </td>
                                <td class="p-3">
                                    <?php if ($internship['status'] == 'Active'): ?>
                                        <span class="text-green-400">
                                            Active
                                        </span>
                                    <?php else: ?>
                                        <span class="text-red-400">
                                            Expired
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-3">
                                    <a href="internships.php?delete=<?php echo $internship['internship_id']; ?>"
                                        onclick="return confirm('Delete this internship?')"
                                        class="bg-red-500/20 text-red-400 px-4 py-2 rounded-lg">
                                        Delete
                                    </a>
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
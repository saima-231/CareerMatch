<?php
session_start();
include "../config/database.php";
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

// Get all students
$stmt = $pdo->prepare("
    SELECT *
    FROM students
    ORDER BY student_id DESC
");
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Students Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-950 text-white min-h-screen">
    <div class="max-w-6xl mx-auto p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">
                Students
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
                                Name
                            </th>
                            <th class="p-3">
                                Email
                            </th>
                            <th class="p-3">
                                Phone
                            </th>
                            <th class="p-3">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr class="border-b border-slate-800">
                                <td class="p-3">
                                    <?php echo htmlspecialchars($student['full_name']); ?>
                                </td>
                                <td class="p-3">
                                    <?php echo htmlspecialchars($student['email']); ?>
                                </td>
                                <td class="p-3">
                                    <?php echo htmlspecialchars($student['phone'] ?? 'N/A'); ?>
                                </td>
                                <td class="p-3">
                                    <button
                                        class="bg-red-500/20 text-red-400 px-4 py-2 rounded-lg">
                                        Remove
                                    </button>
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
<?php
session_start();
include "../config/database.php";

// Check admin login
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}
$admin_id = $_SESSION['admin_id'];

// Admin data
$stmt = $pdo->prepare(
    "SELECT * FROM admins WHERE admin_id = :id"
);
$stmt->execute([
    ":id" => $admin_id
]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Dashboard counts
$student_count = $pdo
    ->query("SELECT COUNT(*) FROM students")
    ->fetchColumn();
$company_count = $pdo
    ->query("SELECT COUNT(*) FROM companies")
    ->fetchColumn();
$internship_count = $pdo
    ->query("SELECT COUNT(*) FROM internships")
    ->fetchColumn();
$application_count = $pdo
    ->query("SELECT COUNT(*) FROM applications")
    ->fetchColumn();

// Pending companies
$pending_stmt = $pdo->prepare(
    "
    SELECT 
        company_id,
        company_name,
        industry
    FROM companies
    WHERE company_status='Pending'
    ORDER BY created_at DESC
    LIMIT 5
    "
);
$pending_stmt->execute();
$pending_companies = $pending_stmt->fetchAll(PDO::FETCH_ASSOC);

// Users list
$user_stmt = $pdo->prepare(
    "
    SELECT
        student_id AS id,
        full_name AS name,
        'Student' AS role,
        'Active' AS status
    FROM students
    UNION ALL
    SELECT
        company_id AS id,
        company_name AS name,
        'Company' AS role,
        company_status AS status
    FROM companies
    LIMIT 10
    "
);

$user_stmt->execute();
$users = $user_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>CareerMatch - Admin Dashboard</title>

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
    <!-- Glow Background -->
    <div class="fixed w-96 h-96 bg-primary/20 blur-3xl rounded-full top-0 left-0"></div>
    <div class="fixed w-96 h-96 bg-secondary/10 blur-3xl rounded-full bottom-0 right-0"></div>
    <div class="relative flex min-h-screen">
        <?php include "../includes/admin_sidebar.php"; ?>
        <!-- MAIN CONTENT -->
        <main class="flex-1 p-6 md:p-10">
            <!-- HEADER -->
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h2 class="text-3xl font-bold">
                        Welcome,
                        <span class="text-primary">
                            <?php echo htmlspecialchars($admin['full_name'] ?? 'Admin'); ?>
                        </span>
                        🔐
                    </h2>
                    <p class="text-slate-400 mt-2">Manage CareerMatch platform</p>
                </div>
            </div>
            <!-- STATISTICS -->
            <div class="grid md:grid-cols-4 gap-5 mb-10">
                <div class="bg-slate-900/70 border border-cyan-900 rounded-3xl p-6">
                    <h3 class="text-3xl font-bold text-primary">
                        <?php echo $student_count; ?>
                    </h3>
                    <p class="text-slate-400">Students</p>
                </div>
                <div class="bg-slate-900/70 border border-cyan-900 rounded-3xl p-6">
                    <h3 class="text-3xl font-bold text-primary">
                        <?php echo $company_count; ?>
                    </h3>
                    <p class="text-slate-400">Companies</p>
                </div>
                <div class="bg-slate-900/70 border border-cyan-900 rounded-3xl p-6">
                    <h3 class="text-3xl font-bold text-primary">
                        <?php echo $internship_count; ?>
                    </h3>
                    <p class="text-slate-400">Internships</p>
                </div>
                <div class="bg-slate-900/70 border border-cyan-900 rounded-3xl p-6">
                    <h3 class="text-3xl font-bold text-primary">
                        <?php echo $application_count; ?>
                    </h3>
                    <p class="text-slate-400">Applications</p>
                </div>
            </div>

            <!-- COMPANY APPROVAL -->
            <section class="bg-slate-900/70 border border-cyan-900 rounded-3xl p-6 mb-8">
                <h3 class="text-xl font-bold mb-5">
                    Pending Company Approvals
                </h3>
                <div class="space-y-4">
                    <?php if (count($pending_companies) > 0): ?>
                        <?php foreach ($pending_companies as $company): ?>
                            <div class="bg-slate-800 rounded-xl p-5 flex justify-between items-center">
                                <div>
                                    <h4 class="font-semibold">
                                        <?php echo htmlspecialchars($company['company_name']); ?>
                                    </h4>
                                    <p class="text-sm text-slate-400">
                                        <?php echo htmlspecialchars($company['industry']); ?>
                                    </p>
                                </div>
                                <div class="flex gap-3">
                                    <div class="flex gap-3">
                                        <a href="../admin/update_company.php?id=<?php echo $company['company_id']; ?>&status=Approved"
                                            class="bg-green-500/20 text-green-400 px-4 py-2 rounded-lg">
                                            Approve
                                        </a>
                                        <a href="../admin/update_company.php?id=<?php echo $company['company_id']; ?>&status=Rejected"
                                            class="bg-red-500/20 text-red-400 px-4 py-2 rounded-lg">
                                            Reject
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-slate-400">
                            No companies found.
                        </p>
                    <?php endif; ?>
                </div>
            </section>

            <!-- USER MANAGEMENT -->
            <section class="bg-slate-900/70 border border-cyan-900 rounded-3xl p-6">
                <h3 class="text-xl font-bold mb-5">User Management</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-slate-400 border-b border-slate-700">
                                <th class="p-3">Name</th>
                                <th class="p-3">Role</th>
                                <th class="p-3">Status</th>
                                <th class="p-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr class="border-b border-slate-800">
                                    <td class="p-3">
                                        <?php echo htmlspecialchars($user['name']); ?>
                                    </td>
                                    <td class="p-3">
                                        <?php echo $user['role']; ?>
                                    </td>
                                    <td class="p-3 text-green-400">
                                        <?php echo $user['status']; ?>
                                    </td>
                                    <td class="p-3">
                                        <a href="../admin/delete_user.php?type=<?php echo strtolower($user['role']); ?>&id=<?php echo $user['id']; ?>"
                                            onclick="return confirm('Remove this user?')"
                                            class="bg-red-500/20 text-red-400 px-4 py-2 rounded-lg">
                                            Remove
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>

</html>
<?php
session_start();
include "../config/database.php";
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

// Get all companies
$stmt = $pdo->prepare("
    SELECT *
    FROM companies
    ORDER BY company_id DESC
");
$stmt->execute();
$companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Companies Management</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <head>

        <title>CareerMatch Admin</title>

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
</head>

<body class="bg-primaryDark text-cream min-h-screen">
    <div class="relative flex min-h-screen">


        <?php include "../includes/admin_sidebar.php"; ?>


        <main class="flex-1 p-6 md:p-10">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold mb-8">

                    Company
                    <span class="text-primary">
                        Management
                    </span>

                    🏢

                </h1>
                <a href="../dashboard/admin_dashboard.php"
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
                                    Company Name
                                </th>
                                <th class="p-3">
                                    Industry
                                </th>
                                <th class="p-3">
                                    Email
                                </th>
                                <th class="p-3">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($companies as $company): ?>
                                <tr class="border-b border-slate-800">
                                    <td class="p-3">
                                        <?php echo htmlspecialchars($company['company_name']); ?>
                                    </td>
                                    <td class="p-3">
                                        <?php echo htmlspecialchars($company['industry'] ?? 'N/A'); ?>
                                    </td>
                                    <td class="p-3">
                                        <?php echo htmlspecialchars($company['email']); ?>
                                    </td>
                                    <td class="p-3">
                                        <?php if ($company['company_status'] == 'Pending'): ?>
                                            <a href="update_company.php?id=<?php echo $company['company_id']; ?>&status=Approved"
                                                class="bg-green-500/20 text-green-400 px-4 py-2 rounded-lg">
                                                Approve
                                            </a>
                                            <a href="update_company.php?id=<?php echo $company['company_id']; ?>&status=Rejected"
                                                class="bg-red-500/20 text-red-400 px-4 py-2 rounded-lg">
                                                Reject
                                            </a>
                                        <?php else: ?>

                                            <?php if ($company['company_status'] == 'Approved'): ?>

                                                <span class="text-green-400 font-semibold">
                                                    Approved
                                                </span>

                                            <?php elseif ($company['company_status'] == 'Rejected'): ?>

                                                <span class="text-red-400 font-semibold">
                                                    Rejected
                                                </span>

                                            <?php else: ?>

                                                <span class="text-yellow-400 font-semibold">
                                                    <?php echo $company['company_status']; ?>
                                                </span>

                                            <?php endif; ?>

                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
    </main>
    </div>
</body>

</html>
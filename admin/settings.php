<?php
session_start();
include "../config/database.php";
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}
$admin_id = $_SESSION['admin_id'];
// Get admin data
$stmt = $pdo->prepare("
    SELECT *
    FROM admins
    WHERE admin_id = :id
");
$stmt->execute([
    ":id" => $admin_id
]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Update profile
if (isset($_POST['update'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $update = $pdo->prepare("
        UPDATE admins
        SET full_name = :name,
            email = :email
        WHERE admin_id = :id
    ");
    $update->execute([
        ":name" => $full_name,
        ":email" => $email,
        ":id" => $admin_id
    ]);
    echo "
    <script>
    alert('Profile Updated');
    window.location='settings.php';
    </script>
    ";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Settings</title>
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

                    Admin
                    <span class="text-primary">
                        Settings
                    </span>

                    ⚙

                </h1>
                <a href="../dashboard/admin_dashboard.php"
                    class="bg-cyan-400 text-black px-5 py-3 rounded-xl font-bold">
                    Dashboard
                </a>
            </div>
            <div class="bg-slate-900 rounded-3xl p-8">
                <form method="POST" class="space-y-5">
                    <div>
                        <label class="text-slate-400">
                            Full Name
                        </label>
                        <input
                            type="text"
                            name="full_name"
                            value="<?php echo htmlspecialchars($admin['full_name']); ?>"
                            class="w-full mt-2 p-3 rounded-xl text-black"
                            required>
                    </div>
                    <div>
                        <label class="text-slate-400">
                            Email
                        </label>
                        <input
                            type="email"
                            name="email"
                            value="<?php echo htmlspecialchars($admin['email']); ?>"
                            class="w-full mt-2 p-3 rounded-xl text-black"
                            required>
                    </div>
                    <button
                        name="update"
                        class="bg-cyan-400 text-black px-6 py-3 rounded-xl font-bold">
                        Save Changes
                    </button>
                </form>
            </div>
    </div>
    </main>
    </div>
</body>

</html>
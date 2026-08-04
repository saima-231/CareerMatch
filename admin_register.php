<?php
session_start();
include "config/database.php";
// SECRET ADMIN CODE
$admin_code = "CAREERMATCH_ADMIN_2026";
// Protect page access
if (!isset($_GET['code']) || $_GET['code'] != $admin_code) {
    die("Access Denied");
}
// When form submitted
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $code = $_POST['admin_code'];
    // Check secret code
    if ($code != $admin_code) {
        echo "
        <script>
        alert('Invalid Admin Security Code');
        </script>
        ";
    } else {
        // Check existing admin email
        $check = $pdo->prepare(
            "SELECT * FROM admins WHERE email=:email"
        );
        $check->execute([
            ":email" => $email
        ]);
        if ($check->rowCount() > 0) {
            echo "
            <script>
            alert('Admin already exists');
            </script>
            ";
        } else {
            // Hash password
            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );
            $insert = $pdo->prepare(
                "
            INSERT INTO admins
            (
                full_name,
                email,
                password
            )
            VALUES
            (
                :name,
                :email,
                :password
            )
            "
            );
            $insert->execute([
                ":name" => $name,
                ":email" => $email,
                ":password" => $hashed_password
            ]);
            echo "
            <script>
            alert('Admin created successfully');
            window.location='admin_login.php';
            </script>
            ";
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        CareerMatch - Admin Registration
    </title>
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

<body class="bg-primaryDark text-cream min-h-screen flex items-center justify-center">
    <div class="absolute w-72 h-72 bg-primary/20 blur-3xl rounded-full top-10 left-10"></div>
    <div class="absolute w-72 h-72 bg-secondary/10 blur-3xl rounded-full bottom-10 right-10"></div>
    <div class="relative w-full max-w-3xl bg-slate-900/70 backdrop-blur-lg border border-cyan-900 rounded-3xl p-10">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold">
                Career<span class="text-primary">
                    Match
                </span>
            </h1>
            <div class="w-16 h-[2px] bg-primary mx-auto mt-3"></div>
            <h2 class="text-xl font-semibold mt-5">
                ⚙️ Admin Registration
            </h2>
            <p class="text-slate-400 text-sm mt-2">
                Restricted Access
            </p>
        </div>
        <form method="POST" class="space-y-5">
            <div>
                <label class="text-sm text-slate-300">
                    Admin Name
                </label>
                <input
                    name="name"
                    required
                    type="text"
                    placeholder="Enter admin name"
                    class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 text-white">
            </div>
            <div>
                <label class="text-sm text-slate-300">
                    Admin Email
                </label>
                <input
                    name="email"
                    required
                    type="email"
                    placeholder="admin@email.com"
                    class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 text-white">
            </div>
            <div>
                <label class="text-sm text-slate-300">
                    Password
                </label>
                <input
                    name="password"
                    required
                    type="password"
                    placeholder="Create password"
                    class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 text-white">
            </div>
            <div>
                <label class="text-sm text-slate-300">
                    Admin Security Code
                </label>
                <input
                    name="admin_code"
                    required
                    type="password"
                    placeholder="Enter secret code"
                    class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 text-white">
            </div>
            <button
                name="register"
                class="w-full bg-primary text-primaryDark font-semibold py-3 rounded-xl hover:bg-secondary transition">
                Create Admin Account
            </button>
        </form>
        <p class="text-center text-slate-400 text-sm mt-8">
            Already registered?
            <a href="login.php" class="text-primary hover:underline">
                Login
            </a>
        </p>
    </div>
</body>

</html>
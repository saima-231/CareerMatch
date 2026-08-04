<?php
session_start();
include "config/database.php";
if (isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    // STUDENT LOGIN
    if ($role == "student") {
        $stmt = $pdo->prepare(
            "SELECT * FROM students WHERE email=:email"
        );
        $stmt->execute([
            ":email" => $email
        ]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($student && $password == $student['password']) {
            $_SESSION['student_id'] = $student['student_id'];
            $_SESSION['student_name'] = $student['full_name'];
            header("Location: dashboard/student_dashboard.php");

            exit();
        } else {
            echo "<script>alert('Invalid Student Email or Password');</script>";
        }
    }

    // COMPANY LOGIN
    elseif ($role == "company") {

        $stmt = $pdo->prepare(
            "SELECT * FROM companies WHERE email = :email"
        );

        $stmt->execute([
            ":email" => $email
        ]);
        $company = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$company) {
            die("Company email not found.");
        }

        echo "<pre>";
        print_r($company);
        echo "</pre>";

        if ($password == $company['password']) {
            $_SESSION['company_id'] = $company['company_id'];
            $_SESSION['company_name'] = $company['company_name'];
            header("Location: dashboard/company_dashboard.php");
            exit();
        } else {
            die("Password does not match.");
        }
    }

    // ADMIN LOGIN
    elseif ($role == "admin") {
        $stmt = $pdo->prepare(
            "SELECT * FROM admins WHERE email=:email"
        );
        $stmt->execute([
            ":email" => $email
        ]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($admin && $password == $admin['password']) {
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            header("Location: dashboard/admin_dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid Admin Email or Password');</script>";
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>CareerMatch - Login</title>

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

<body class="bg-primaryDark text-cream min-h-screen flex items-center justify-center">
    <!-- Background Glow -->

    <div class="absolute w-72 h-72 bg-primary/20 blur-3xl rounded-full top-10 left-10"></div>

    <div class="absolute w-72 h-72 bg-secondary/10 blur-3xl rounded-full bottom-10 right-10"></div>

    <!-- LOGIN CARD -->

    <div class="relative w-full max-w-md bg-slate-900/70 backdrop-blur-lg border border-cyan-900 rounded-3xl p-10">
        <!-- HEADER -->

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold">
                Career<span class="text-primary">Match</span>
            </h1>

            <div class="w-16 h-[2px] bg-primary mx-auto mt-3"></div>

            <h2 class="text-xl font-semibold mt-5">
                👋 Welcome <span class="text-primary">Back</span>
            </h2>

            <p class="text-slate-400 mt-2 text-sm">
                Login to continue your journey
            </p>
        </div>

        <!-- LOGIN FORM -->
        <form action="login.php" method="POST" class="space-y-6">
            <!-- Role -->
            <div>
                <label class="text-sm text-slate-300"> Select Role </label>
                <select name="role"
                    class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 focus:border-primary outline-none">
                    <option value="student">Student</option>
                    <option value="company">Company</option>
                </select>
            </div>

            <!-- Email -->

            <div>
                <label class="text-sm text-slate-300"> Email </label>

                <input type="email" name="email" placeholder="Enter email" required
                    class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 focus:border-primary outline-none text-white" />
            </div>

            <!-- Password -->

            <div>
                <label class="text-sm text-slate-300"> Password </label>

                <input type="password" name="password" placeholder="Enter password" required
                    class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 focus:border-primary outline-none text-white" />
            </div>

            <!-- Options -->

            <div class="flex justify-between items-center text-sm text-slate-400">
                <label class="flex items-center gap-2">
                    <input type="checkbox" class="accent-primary" />

                    Remember Me
                </label>

                <a href="forgot_password.php">
                    Forgot Password?
                </a>
            </div>

            <!-- LOGIN BUTTON -->

            <button type="submit" name="login"
                class="w-full bg-primary text-primaryDark font-semibold py-3 rounded-xl hover:bg-secondary transition">
                Login
            </button>
        </form>

        <!-- Divider -->

        <div class="flex items-center my-6">
            <div class="flex-1 h-px bg-slate-700"></div>

            <span class="px-3 text-slate-500 text-sm"> OR </span>

            <div class="flex-1 h-px bg-slate-700"></div>
        </div>

        <!-- Google -->
        <button onclick="alert('Google login will be available soon')"
            class="...">
            Continue with Google
        </button>

        <!-- Register -->
        <p class="text-center text-slate-400 text-sm mt-6">
            Don’t have an account?

            <a href="register.php" class="text-primary hover:underline font-medium">
                Register
            </a>
        </p>
    </div>
</body>

</html>
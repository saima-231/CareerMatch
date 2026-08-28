<?php
include "config/database.php";
if (isset($_POST['register'])) {
    $sql = "INSERT INTO students
    (
        full_name,
        email,
        password,
        phone,
        university,
        department,
        graduation_year,
        location,
        skills
    )
    VALUES
    (
        :full_name,
        :email,
        :password,
        :phone,
        :university,
        :department,
        :graduation_year,
        :location,
        :skills
    )";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":full_name" => $_POST['full_name'],
        ":email" => $_POST['email'],
        ":password" => $_POST['password'],
        ":phone" => $_POST['phone'],
        ":university" => $_POST['university'],
        ":department" => $_POST['department'],
        ":graduation_year" => $_POST['graduation_year'],
        ":location" => $_POST['location'],
        ":skills" => $_POST['skills']
    ]);
    echo "
<script>
alert('Registration Successful');
window.location='login.php';
</script>
";
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Registration</title>
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
    <!-- CARD -->
    <div class="relative w-full max-w-4xl bg-slate-900/70 backdrop-blur-lg border border-cyan-900 rounded-3xl p-10">
        <!-- HEADER -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold">
                Career<span class="text-primary"> Match </span>
            </h1>
            <div class="w-16 h-[2px] bg-primary mx-auto mt-3"></div>
            <h2 class="text-xl font-semibold mt-5">🎓 Student Registration</h2>
            <p class="text-slate-400 text-sm mt-2">
                Create your account and start finding internships
            </p>
        </div>
        <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Name -->
            <div>
                <label class="text-sm text-slate-300"> Full Name </label>
                <input type="text" name="full_name" placeholder="Enter your name"
                    class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 focus:border-primary outline-none text-white" />
            </div>
            <!-- Email -->
            <div>
                <label class="text-sm text-slate-300"> Email </label>
                <input type="email" name="email" placeholder="Enter email" class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 focus:border-primary outline-none text-white" />
            </div>
            <!-- Password -->
            <div>
                <label class="text-sm text-slate-300"> Password </label>
                <input type="password" name="password" placeholder="Create password" class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 focus:border-primary outline-none text-white" />
            </div>
            <!-- Phone -->
            <div>
                <label class="text-sm text-slate-300"> Phone Number </label>
                <input type="tel" name="phone" placeholder="Enter phone number" class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 focus:border-primary outline-none text-white" />
            </div>
            <!-- University -->
            <div>
                <label class="text-sm text-slate-300"> University </label>
                <input type="text" name="university" placeholder="University name" class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 focus:border-primary outline-none text-white" />
            </div>
            <!-- Department -->
            <div>
                <label class="text-sm text-slate-300"> Department </label>
                <input type="text" name="department" placeholder="CSE, EEE, BBA..." class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 focus:border-primary outline-none text-white" />
            </div>
            <!-- Graduation Year -->
            <div>
                <label class="text-sm text-slate-300"> Graduation Year </label>
                <select name="graduation_year"
                    class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 focus:border-primary outline-none">
                    <option>2026</option>
                    <option>2027</option>
                    <option>2028</option>
                    <option>2029</option>
                </select>
            </div>
            <!-- Location -->
            <div>
                <label class="text-sm text-slate-300"> Location </label>
                <input type="text" name="location" placeholder="City" class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 focus:border-primary outline-none text-white" />
            </div>
            <!-- Skills -->
            <div class="md:col-span-2">
                <label class="text-sm text-slate-300"> Skills </label>
                <textarea name="skills" placeholder="Example: HTML, CSS, JavaScript, Python" class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 focus:border-primary outline-none text-white h-28">
          </textarea>
            </div>
            <!-- Resume -->
            <div class="md:col-span-2">
                <label class="text-sm text-slate-300"> Upload Resume </label>
                <input type="file"
                    class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 text-slate-300" />
            </div>
            <!-- BUTTON -->
            <button
                type="submit"
                name="register"
                class="md:col-span-2 w-full bg-primary text-primaryDark font-semibold py-3 rounded-xl">
                Create Student Account
            </button>
        </form>
        <p class="text-center text-slate-400 text-sm mt-8">
            Already registered?
            <a href="login.php" class="text-primary hover:underline"> Login </a>
        </p>
    </div>
</body>

</html>
<?php

session_start();

include "config/database.php";


if (isset($_POST['login'])) {


    $email = $_POST['email'];
    $password = $_POST['password'];



    $stmt = $pdo->prepare(
        "SELECT * FROM admins WHERE email=:email"
    );


    $stmt->execute([
        ":email" => $email
    ]);


    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($admin && password_verify($password, $admin['password'])) {


        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_name'] = $admin['full_name'];


        header("Location: dashboard/admin_dashboard.php");
        exit();
    } else {

        echo "
<script>
alert('Invalid Admin Credentials');
</script>
";
    }
}


?>

<!DOCTYPE html>
<html>

<head>

    <title>Admin Secure Login</title>

    <script src="https://cdn.tailwindcss.com"></script>


</head>


<body class="bg-slate-900 min-h-screen flex items-center justify-center text-white">


    <div class="bg-slate-800 p-10 rounded-3xl w-full max-w-md">


        <h1 class="text-3xl font-bold text-center mb-8">

            CareerMatch
            <br>

            <span class="text-cyan-400">
                Admin Access
            </span>

        </h1>



        <form method="POST" class="space-y-5">


            <input
                type="email"
                name="email"
                placeholder="Admin Email"
                required
                class="w-full p-3 rounded-xl bg-slate-700">


            <input
                type="password"
                name="password"
                placeholder="Password"
                required
                class="w-full p-3 rounded-xl bg-slate-700">



            <button
                name="login"
                class="w-full bg-cyan-400 text-black p-3 rounded-xl font-bold">

                Secure Login

            </button>


        </form>


    </div>


</body>

</html>
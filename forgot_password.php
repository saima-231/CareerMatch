<?php
session_start();
include "config/database.php";
$message = "";
if (isset($_POST['reset'])) {
    $email = $_POST['email'];
    $new_password = $_POST['password'];
    // Check student
    $stmt = $pdo->prepare("
    SELECT * FROM students 
    WHERE email = :email
    ");
    $stmt->execute([
        ":email" => $email
    ]);
    if ($stmt->rowCount() > 0) {
        $update = $pdo->prepare("
        UPDATE students 
        SET password=:password
        WHERE email=:email
        ");
        $update->execute([
            ":password" => $new_password,
            ":email" => $email
        ]);
        $message = "Password updated successfully";
    } else {
        // Check company
        $stmt = $pdo->prepare("
        SELECT * FROM companies
        WHERE email=:email
        ");
        $stmt->execute([
            ":email" => $email
        ]);
        if ($stmt->rowCount() > 0) {
            $update = $pdo->prepare("
            UPDATE companies
            SET password=:password
            WHERE email=:email
            ");
            $update->execute([
                ":password" => $new_password,
                ":email" => $email
            ]);
            $message = "Password updated successfully";
        } else {
            $message = "Email not found";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-950 text-white p-10">
    <div class="max-w-md mx-auto bg-slate-900 p-8 rounded-3xl">
        <h1 class="text-3xl font-bold mb-5">
            Reset Password
        </h1>
        <p class="text-cyan-400">
            <?php echo $message; ?>
        </p>
        <form method="POST">
            <input
                name="email"
                placeholder="Email"
                class="w-full p-3 rounded-xl text-black mb-4">
            <input
                name="password"
                placeholder="New Password"
                class="w-full p-3 rounded-xl text-black mb-4">
            <button
                name="reset"
                class="bg-cyan-400 text-black px-6 py-3 rounded-xl">
                Reset Password
            </button>
        </form>
    </div>
</body>

</html>
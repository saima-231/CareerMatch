<?php
session_start();
include "../config/database.php";
if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}
$student_id = $_SESSION['student_id'];
// Get student data
$stmt = $pdo->prepare("
SELECT * FROM students 
WHERE student_id = :student_id
");
$stmt->execute([
    ":student_id" => $student_id
]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $skills = $_POST['skills'];
    $update = $pdo->prepare("
    UPDATE students SET
    full_name = :full_name,
    email = :email,
    phone = :phone,
    skills = :skills
    WHERE student_id = :student_id
    ");
    $update->execute([
        ":full_name" => $full_name,
        ":email" => $email,
        ":phone" => $phone,
        ":skills" => $skills,
        ":student_id" => $student_id
    ]);
    header("Location: ../dashboard/student_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Update Profile</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primaryDark: "#091D3E",
                        primary: "#06B6D4"
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-primaryDark text-white min-h-screen">
    <div class="relative min-h-screen">
        <!-- SIDEBAR -->
        <?php include "../includes/student_sidebar.php"; ?>
        <!-- MAIN -->
        <main class="ml-72 min-h-screen p-6 md:p-10">
             <!-- TOP BAR -->
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h1 class="text-3xl font-bold">
                        Update
                        <span class="text-primary">
                            Profile
                        </span>
                    </h1>
                    <p class="text-slate-400 mt-2">
                        Manage your personal information
                    </p>
                </div>
                <div class="w-14 h-14 rounded-full bg-primary/20 flex items-center justify-center text-3xl">
                    👤
                </div>
            </div>

            <!-- FORM -->
            <div class="bg-slate-900 border border-cyan-900 rounded-3xl p-8 max-w-3xl">
                <form method="POST" class="space-y-5">
                    <div>
                        <label class="block mb-2">
                            Full Name
                        </label>
                        <input
                            type="text"
                            name="full_name"
                            value="<?= $student['full_name']; ?>"
                            class="w-full px-4 py-3 rounded-xl text-black outline-none">
                    </div>
                    <div>
                        <label class="block mb-2">
                            Email
                        </label>
                        <input
                            type="email"
                            name="email"
                            value="<?= $student['email']; ?>"
                            class="w-full px-4 py-3 rounded-xl text-black outline-none">
                    </div>
                    <div>
                        <label class="block mb-2">
                            Phone
                        </label>
                        <input
                            type="text"
                            name="phone"
                            value="<?= $student['phone']; ?>"
                            class="w-full px-4 py-3 rounded-xl text-black outline-none">
                    </div>
                    <div>
                        <label class="block mb-2">
                            Skills
                        </label>
                        <textarea
                            name="skills"
                            class="w-full px-4 py-3 rounded-xl text-black outline-none"><?= $student['skills']; ?></textarea>
                    </div>
                    <button
                        type="submit"
                        class="bg-primary text-black px-6 py-3 rounded-xl font-bold">
                        Save Change
                    </button>
                </form>
            </div>
        </main>
    </div>
</body>

</html>
<?php
session_start();
include "../config/database.php";
if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}
$student_id = $_SESSION['student_id'];

// Get current student data
$stmt = $pdo->prepare(
    "SELECT * FROM students WHERE student_id = :id"
);
$stmt->execute([
    ":id" => $student_id
]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$student) {
    echo "Student not found";
    exit();
}
// Update information
if (isset($_POST['update'])) {
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $university = $_POST['university'];
    $department = $_POST['department'];
    $graduation_year = $_POST['graduation_year'];
    $location = $_POST['location'];
    $skills = $_POST['skills'];
    $resume = $student['resume'];
    if (isset($_FILES['resume']) && $_FILES['resume']['name'] != "") {
        $file_name = $_FILES['resume']['name'];
        $tmp_name = $_FILES['resume']['tmp_name'];
        $upload_folder = "../uploads/";
        $resume = $upload_folder . $file_name;
        move_uploaded_file($tmp_name, $resume);
    }
    $update = $pdo->prepare("
    UPDATE students SET
    full_name = :full_name,
    phone = :phone,
    university = :university,
    department = :department,
    graduation_year = :graduation_year,
    location = :location,
    skills = :skills,
    resume = :resume
    WHERE student_id = :id
    ");
    $update->execute([
        ":full_name" => $full_name,
        ":phone" => $phone,
        ":university" => $university,
        ":department" => $department,
        ":graduation_year" => $graduation_year,
        ":location" => $location,
        ":skills" => $skills,
        ":resume" => $resume,
        ":id" => $student_id
    ]);
    header("Location: ../dashboard/student_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Update Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-900 text-white min-h-screen p-10">
    <div class="max-w-xl mx-auto bg-slate-800 p-8 rounded-3xl">
        <h2 class="text-3xl font-bold mb-6">
            Update Profile
        </h2>
        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <input
                name="full_name"
                value="<?php echo $student['full_name']; ?>"
                class="w-full p-3 rounded bg-slate-700"
                placeholder="Full Name">
            <input
                name="phone"
                value="<?php echo $student['phone']; ?>"
                class="w-full p-3 rounded bg-slate-700"
                placeholder="Phone">
            <input
                name="university"
                value="<?php echo $student['university']; ?>"
                class="w-full p-3 rounded bg-slate-700"
                placeholder="University">
            <input
                name="department"
                value="<?php echo $student['department']; ?>"
                class="w-full p-3 rounded bg-slate-700"
                placeholder="Department">
            <input
                name="graduation_year"
                value="<?php echo $student['graduation_year']; ?>"
                class="w-full p-3 rounded bg-slate-700"
                placeholder="Graduation Year">
            <input
                name="location"
                value="<?php echo $student['location']; ?>"
                class="w-full p-3 rounded bg-slate-700"
                placeholder="Location">
            <textarea
                name="skills"
                class="w-full p-3 rounded bg-slate-700"
                placeholder="Skills"><?php echo $student['skills']; ?></textarea>
            <input
                type="file"
                name="resume"
                class="w-full p-3 rounded bg-slate-700">
            <button
                name="update"
                class="bg-cyan-400 text-black px-6 py-3 rounded-xl font-bold">
                Update Profile
            </button>
        </form>
    </div>
</body>

</html>
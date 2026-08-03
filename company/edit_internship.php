<?php
session_start();
include "../config/database.php";
if (!isset($_SESSION['company_id'])) {
    header("Location: ../login.php");
    exit();
}
$company_id = $_SESSION['company_id'];

// Get internship data
if (!isset($_GET['id'])) {
    header("Location: manage_internships.php");
    exit();
}
$id = $_GET['id'];
$stmt = $pdo->prepare("
SELECT *
FROM internships
WHERE internship_id = :id
AND company_id = :company_id
");
$stmt->execute([
    ":id" => $id,
    ":company_id" => $company_id
]);
$internship = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$internship) {
    echo "Internship not found";
    exit();
}

// Update internship
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $update = $pdo->prepare("
    UPDATE internships SET
    title=:title,
    category=:category,
    description=:description,
    requirements=:requirements,
    duration=:duration,
    location=:location,
    deadline=:deadline,
    internship_type=:internship_type,
    stipend=:stipend
    WHERE internship_id=:id
    AND company_id=:company_id
    ");
    $update->execute([
        ":title" => $_POST['title'],
        ":category" => $_POST['category'],
        ":description" => $_POST['description'],
        ":requirements" => $_POST['requirements'],
        ":duration" => $_POST['duration'],
        ":location" => $_POST['location'],
        ":deadline" => $_POST['deadline'],
        ":internship_type" => $_POST['internship_type'],
        ":stipend" => $_POST['stipend'],
        ":id" => $id,
        ":company_id" => $company_id
    ]);
    header("Location: manage_internships.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Internship</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-slate-950 text-white min-h-screen flex items-center justify-center">

    <div class="bg-slate-900 p-8 rounded-3xl w-full max-w-xl">

        <h1 class="text-3xl font-bold mb-6">
            Edit Internship
        </h1>
        <form method="POST" class="space-y-4">
            <input
                type="text"
                name="title"
                value="<?php echo htmlspecialchars($internship['title']); ?>"
                class="w-full p-3 rounded-xl text-black"
                required>
            <input
                type="text"
                name="category"
                value="<?php echo htmlspecialchars($internship['category']); ?>"
                class="w-full p-3 rounded-xl text-black"
                required>
            <textarea
                name="description"
                class="w-full p-3 rounded-xl text-black"
                required><?php echo htmlspecialchars($internship['description']); ?></textarea>
            <textarea
                name="requirements"
                class="w-full p-3 rounded-xl text-black"
                required><?php echo htmlspecialchars($internship['requirements']); ?></textarea>
            <input
                type="text"
                name="duration"
                value="<?php echo htmlspecialchars($internship['duration']); ?>"
                class="w-full p-3 rounded-xl text-black"
                required>
            <input
                type="text"
                name="location"
                value="<?php echo htmlspecialchars($internship['location']); ?>"
                class="w-full p-3 rounded-xl text-black"
                required>
            <input
                type="date"
                name="deadline"
                value="<?php echo $internship['deadline']; ?>"
                class="w-full p-3 rounded-xl text-black"
                required>
            <input
                type="text"
                name="internship_type"
                value="<?php echo htmlspecialchars($internship['internship_type']); ?>"
                class="w-full p-3 rounded-xl text-black"
                required>
            <input
                type="text"
                name="stipend"
                value="<?php echo htmlspecialchars($internship['stipend']); ?>"
                class="w-full p-3 rounded-xl text-black"
                required>
            <button
                class="bg-cyan-400 text-black px-6 py-3 rounded-xl font-bold w-full">
                Update Internship
            </button>
            <a href="manage_internships.php"
                class="block text-center bg-slate-700 px-6 py-3 rounded-xl mt-3">
                Back to Internships
            </a>
        </form>
    </div>
</body>

</html>
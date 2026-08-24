<?php
session_start();
include "../config/database.php";
if (!isset($_SESSION['company_id'])) {
    header("Location: ../login.php");
    exit();
}
$company_id = $_SESSION['company_id'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $pdo->prepare("
        INSERT INTO internships
        (
            company_id,
            title,
            category,
            description,
            requirements,
            duration,
            location,
            deadline,
            internship_type,
            stipend,
            status
        )
        VALUES
        (
            :company_id,
            :title,
            :category,
            :description,
            :requirements,
            :duration,
            :location,
            :deadline,
            :internship_type,
            :stipend,
            'Active'
        )
    ");
    $stmt->execute([
        ":company_id" => $company_id,
        ":title" => htmlspecialchars($_POST['title']),
        ":category" => $_POST['category'],
        ":description" => $_POST['description'],
        ":requirements" => $_POST['requirements'],
        ":duration" => $_POST['duration'],
        ":location" => $_POST['location'],
        ":deadline" => $_POST['deadline'],
        ":internship_type" => $_POST['internship_type'],
        ":stipend" => $_POST['stipend']
    ]);
    header("Location: internships.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Post Internship</title>

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

<body class="bg-primaryDark text-white min-h-screen">
    <div class="relative min-h-screen">
        <?php include "../includes/company_sidebar.php"; ?>
        <main class="ml-72 min-h-screen p-6 md:p-10">
            <h1 class="text-3xl font-bold mb-6">
                Post Internship
            </h1>
            <form method="POST" class="space-y-4">
                <input
                    type="text"
                    name="title"
                    placeholder="Internship Title"
                    class="w-full p-3 rounded-xl text-black"
                    required>
                <select
                    name="category"
                    class="w-full p-3 rounded-xl text-black"
                    required>
                    <option value="">
                        Select Category
                    </option>
                    <option value="Web Development">
                        Web Development
                    </option>
                    <option value="Cloud & DevOps">
                        Cloud & DevOps
                    </option>
                    <option value="Data Science">
                        Data Science
                    </option>
                    <option value="AI">
                        Artificial Intelligence
                    </option>
                    <option value="UI/UX Design">
                        UI/UX Design
                    </option>
                    <option value="Networking">
                        Networking
                    </option>
                    <option value="Software Testing">
                        Software Testing
                    </option>
                    <option value="Graphic Design">
                        Graphic Design
                    </option>
                </select>
                <textarea
                    name="description"
                    placeholder="Internship Description"
                    class="w-full p-3 rounded-xl text-black"
                    required></textarea>
                <textarea
                    name="requirements"
                    placeholder="Required Skills (PHP, MySQL, JavaScript)"
                    class="w-full p-3 rounded-xl text-black"
                    required></textarea>
                <input
                    type="text"
                    name="duration"
                    placeholder="Duration (Example: 3 Months)"
                    class="w-full p-3 rounded-xl text-black"
                    required>
                <input
                    type="text"
                    name="location"
                    placeholder="Location"
                    class="w-full p-3 rounded-xl text-black"
                    required>
                <input
                    type="date"
                    name="deadline"
                    class="w-full p-3 rounded-xl text-black"
                    required>
                <select
                    name="internship_type"
                    class="w-full p-3 rounded-xl text-black"
                    required>
                    <option value="">
                        Select Internship Type
                    </option>
                    <option value="Remote">
                        Remote
                    </option>
                    <option value="On-site">
                        On-site
                    </option>
                    <option value="Hybrid">
                        Hybrid
                    </option>
                </select>
                <input
                    type="text"
                    name="stipend"
                    placeholder="Stipend (Example: 5000 BDT)"
                    class="w-full p-3 rounded-xl text-black"
                    required>
                <button
                    class="bg-primary text-primaryDark px-6 py-3 rounded-xl font-bold w-full">
                    Post Internship
                </button>
            </form>
        </main>
    </div>
</body>

</html>
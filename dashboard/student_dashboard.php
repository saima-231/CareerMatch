<?php
session_start();
include "../config/database.php";
if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}
$student_id = $_SESSION['student_id'];
// Get logged in student data
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
// Get internships applied by this student
$app_stmt = $pdo->prepare("
SELECT
    internships.title,
    internships.duration,
    internships.location,
    internships.stipend,
    companies.company_name,
    applications.status
FROM applications
INNER JOIN internships
ON applications.internship_id = internships.internship_id
INNER JOIN companies
ON internships.company_id = companies.company_id
WHERE applications.student_id = :student_id
");
$app_stmt->execute([
    ":student_id" => $student_id
]);
$applications = $app_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CareerMatch - Student Dashboard</title>
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
        }
    </script>
</head>

<body class="bg-primaryDark text-cream min-h-screen">
    <div class="fixed w-96 h-96 bg-primary/20 blur-3xl rounded-full top-0 left-0"></div>
    <div class="fixed w-96 h-96 bg-secondary/10 blur-3xl rounded-full bottom-0 right-0"></div>
    <div class="relative flex min-h-screen">
        <!-- SIDEBAR -->
        <aside class="hidden md:flex w-72 bg-slate-900/70 backdrop-blur-lg border-r border-cyan-900 flex-col p-6">
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold">
                    Career<span class="text-primary">Match</span>
                </h1>
                <div class="w-16 h-[2px] bg-primary mx-auto mt-3"></div>
            </div>
            <nav class="space-y-4">
                <a href="student_dashboard.php"
                    class="block px-4 py-3 rounded-xl bg-primary text-primaryDark font-semibold">
                    🏠 Profile
                </a>
                <a href="../student/internship.php"
                    class="block px-4 py-3 rounded-xl hover:bg-slate-800">
                    🔎 Find Internship
                </a>
                <a href="../student/applications.php"
                    class="block px-4 py-3 rounded-xl hover:bg-slate-800">
                    📄 Applications
                </a>
                <a href="../index.php"
                    class="block px-4 py-3 rounded-xl hover:bg-slate-800">
                    🏠 Back to Home
                </a>
            </nav>
            <div class="mt-auto">
                <a href="../logout.php"
                    class="block text-center bg-red-500/20 border border-red-500/40 px-4 py-3 rounded-xl hover:bg-red-500/40">
                    Logout
                </a>
            </div>
        </aside>
        <!-- MAIN -->
        <main class="flex-1 p-6 md:p-10">
            <!-- TOP BAR -->
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h2 class="text-3xl font-bold">
                        Welcome,
                        <span class="text-primary">
                            <?php echo $student['full_name']; ?>
                        </span>
                        👋
                    </h2>
                    <p class="text-slate-400 mt-2">
                        Find your dream internship today
                    </p>
                </div>
                <div class="w-14 h-14 rounded-full bg-primary/20 flex items-center justify-center text-3xl">
                    🎓
                </div>
            </div>

            <!-- PROFILE CARD -->
            <section class="bg-slate-900/70 border border-cyan-900 rounded-3xl p-6 mb-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">
                        Student Profile
                    </h3>
                    <a href="../student/update_profile.php"
                        class="bg-primary text-primaryDark px-5 py-2 rounded-xl font-semibold hover:opacity-80">
                        ✏ Edit Profile
                    </a>
                </div>
                <div class="grid md:grid-cols-3 gap-5">
                    <div>
                        <p class="text-slate-400 text-sm">
                            Name
                        </p>
                        <p class="font-semibold">
                            <?php echo $student['full_name']; ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-slate-400 text-sm">
                            Email
                        </p>
                        <p class="font-semibold">
                            <?php echo $student['email']; ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-slate-400 text-sm">
                            Skills
                        </p>
                        <p class="font-semibold">
                            <?php echo $student['skills']; ?>
                        </p>
                    </div>
                </div>
            </section>
            <!-- SEARCH -->
            <section class="bg-slate-900/70 border border-cyan-900 rounded-3xl p-6 mb-8">
                <h3 class="text-xl font-bold mb-5">
                    Search Internship
                </h3>
                <form action="/internships.php" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="relative flex-1">
                        <input
                            type="text"
                            id="searchBox"
                            name="search"
                            placeholder="Search internship..."
                            class="w-full bg-slate-800 border border-cyan-900 rounded-xl px-5 py-3 outline-none focus:border-primary"
                            autocomplete="off"
                            required>
                        <!-- Suggestions -->
                        <div id="suggestions"
                            class="absolute z-10 w-full bg-slate-800 border border-cyan-900 rounded-xl mt-2 hidden overflow-hidden">
                            <div class="suggestion px-5 py-3 hover:bg-cyan-900 cursor-pointer">
                                Web Development
                            </div>
                            <div class="suggestion px-5 py-3 hover:bg-cyan-900 cursor-pointer">
                                Cloud & DevOps
                            </div>
                            <div class="suggestion px-5 py-3 hover:bg-cyan-900 cursor-pointer">
                                Data Science
                            </div>
                            <div class="suggestion px-5 py-3 hover:bg-cyan-900 cursor-pointer">
                                Artificial Intelligence
                            </div>
                            <div class="suggestion px-5 py-3 hover:bg-cyan-900 cursor-pointer">
                                UI/UX Design
                            </div>
                            <div class="suggestion px-5 py-3 hover:bg-cyan-900 cursor-pointer">
                                Networking
                            </div>
                            <div class="suggestion px-5 py-3 hover:bg-cyan-900 cursor-pointer">
                                Software Testing
                            </div>
                            <div class="suggestion px-5 py-3 hover:bg-cyan-900 cursor-pointer">
                                Graphic Design
                            </div>
                        </div>
                    </div>
                    <button
                        type="submit"
                        class="bg-primary text-primaryDark px-8 py-3 rounded-xl font-semibold">
                        Search
                    </button>
                </form>
            </section>

            <!-- APPLIED INTERNSHIPS -->
            <section>
                <h3 class="text-2xl font-bold mb-5">
                    Your Applied Internships
                </h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <?php if (count($applications) > 0): ?>
                        <?php foreach ($applications as $app): ?>
                            <div class="bg-slate-900/70 border border-cyan-900 rounded-3xl p-6">
                                <h4 class="text-xl font-bold">
                                    <?php echo $app['title']; ?>
                                </h4>
                                <p class="text-slate-400 mt-2">
                                    <?php echo $app['company_name']; ?>
                                </p>
                                <p class="text-primary mt-3">
                                    <?php echo $app['location']; ?>
                                    •
                                    <?php echo $app['duration']; ?>
                                </p>
                                <p class="mt-3">
                                    Stipend:
                                    <?php echo $app['stipend']; ?>
                                </p>
                                <p class="mt-3">
                                    Status:
                                    <span class="text-yellow-400">
                                        <?php echo $app['status']; ?>
                                    </span>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="bg-slate-900/70 border border-cyan-900 rounded-3xl p-6">
                            <p class="text-slate-400">
                                You have not applied for any internship yet.
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>
    <script>
        const searchBox = document.getElementById("searchBox");
        const suggestions = document.getElementById("suggestions");
        const items = document.querySelectorAll(".suggestion");


        // Show all suggestions when clicking search box
        searchBox.addEventListener("focus", function() {

            items.forEach(item => {
                item.style.display = "block";
            });

            suggestions.classList.remove("hidden");

        });


        // Filter suggestions while typing
        searchBox.addEventListener("keyup", function() {

            let value = searchBox.value.toLowerCase();

            let found = false;

            items.forEach(item => {

                let text = item.innerText.toLowerCase();

                if (text.includes(value)) {
                    item.style.display = "block";
                    found = true;
                } else {
                    item.style.display = "none";
                }

            });


            if (found) {
                suggestions.classList.remove("hidden");
            } else {
                suggestions.classList.add("hidden");
            }

        });


        // Select suggestion
        items.forEach(item => {

            item.addEventListener("click", function() {

                searchBox.value = this.innerText;

                suggestions.classList.add("hidden");

            });

        });

        // Hide when clicking outside
        document.addEventListener("click", function(e) {
            if (!searchBox.contains(e.target) && !suggestions.contains(e.target)) {
                suggestions.classList.add("hidden");
            }
        });
    </script>
</body>

</html>
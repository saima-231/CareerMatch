<?php
session_start();
include "../config/database.php";
if (!isset($_SESSION['company_id'])) {
    header("Location: ../login.php");
    exit();
}
$company_id = $_SESSION['company_id'];

if (isset($_GET['delete'])) {

    $internship_id = $_GET['delete'];

    $update = $pdo->prepare("
        UPDATE internships
        SET status = 'Inactive'
        WHERE internship_id = :id
        AND company_id = :company_id
    ");

    $update->execute([
        ":id" => $internship_id,
        ":company_id" => $company_id
    ]);

    echo "
    <script>
        alert('Internship Removed');
        window.location='manage_internships.php';
    </script>";

    exit();
}
// Get company internships
$stmt = $pdo->prepare("
    SELECT *
    FROM internships
    WHERE company_id = :company_id
    ORDER BY internship_id DESC
");

$stmt->execute([
    ":company_id" => $company_id
]);

$internships = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Internships</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primaryDark: "#091D3E",
                        primary: "#06B6D4",
                        secondary: "#67E8F9",
                    },
                },
            },
        };
    </script>
</head>

<body class="bg-primaryDark text-white min-h-screen">

    <div class="relative flex min-h-screen">

        <?php include "../includes/company_sidebar.php"; ?>


        <main class="flex-1 p-6 md:p-10">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold">
                        My Internships 💼
                    </h1>

                    <p class="text-slate-400 mt-2">
                        Manage your internship postings
                    </p>
                </div>
                <a href="../company/post_internship.php"
                    class="bg-cyan-400 text-black px-5 py-3 rounded-xl font-bold">
                    + Post Internship
                </a>
            </div>
            <?php if (count($internships) > 0): ?>
                <div class="grid md:grid-cols-2 gap-6">
                    <?php foreach ($internships as $internship): ?>
                        <div class="bg-slate-900 border border-cyan-900 rounded-3xl p-6">
                            <div class="flex justify-between items-center">

                                <h2 class="text-2xl font-bold text-cyan-400">
                                    <?php echo $internship['title']; ?>
                                </h2>


                                <?php
                                $today = date("Y-m-d");

                                if ($internship['deadline'] < $today):
                                ?>

                                    <span class="bg-red-500/20 text-red-400 
    px-3 py-1 rounded-lg text-sm">
                                        Expired
                                    </span>

                                <?php else: ?>

                                    <span class="bg-green-500/20 text-green-400 
    px-3 py-1 rounded-lg text-sm">
                                        Active
                                    </span>

                                <?php endif; ?>

                            </div>
                            <p class="mt-3 text-gray-300">
                                <?php echo $internship['description']; ?>
                            </p>
                            <div class="mt-4 space-y-2">
                                <p>
                                    <b>Category:</b>
                                    <?php echo $internship['category']; ?>
                                </p>
                                <p>
                                    <b>Location:</b>
                                    <?php echo $internship['location']; ?>
                                </p>
                                <p>
                                    <b>Duration:</b>
                                    <?php echo $internship['duration']; ?>
                                </p>
                                <p>
                                    <b>Stipend:</b>
                                    <?php echo $internship['stipend']; ?>
                                </p>
                                <?php if (isset($internship['deadline'])): ?>
                                    <p>
                                        <b>Deadline:</b>
                                        <?php echo $internship['deadline']; ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="flex gap-3 mt-6">
                                <?php if ($internship['deadline'] >= date("Y-m-d")): ?>

                                    <a href="../company/edit_internship.php?id=<?php echo $internship['internship_id']; ?>"
                                        class="bg-yellow-500 text-black px-5 py-3 rounded-xl font-semibold">
                                        Edit
                                    </a>

                                <?php endif; ?>
                                <a href="../company/manage_internships.php?delete=<?php echo $internship['internship_id']; ?>" onclick="return confirm('Delete this internship?')"
                                    class="bg-red-500 text-white px-5 py-3 rounded-xl font-semibold">
                                    Delete
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-slate-900 p-8 rounded-3xl text-center">
                    <p class="text-gray-400">
                        No internships posted yet.
                    </p>
                    <a href="../company/post_internship.php"
                        class="inline-block mt-5 bg-cyan-400 text-black px-5 py-3 rounded-xl">
                        Create Internship
                    </a>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>
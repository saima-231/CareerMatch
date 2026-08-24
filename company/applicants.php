<?php
session_start();

include "../config/database.php";
if (!isset($_SESSION['company_id'])) {
    header("Location: ../login.php");
    exit();
}
$company_id = $_SESSION['company_id'];

// Get applicants for company's internships
$stmt = $pdo->prepare("
SELECT 
    applications.application_id,
    applications.status,
    students.full_name,
    students.email,
    students.phone,
    students.university,
    students.department,
    students.skills,
    students.resume,
    internships.title
FROM applications
JOIN students
ON applications.student_id = students.student_id
JOIN internships
ON applications.internship_id = internships.internship_id
WHERE internships.company_id = :company_id
ORDER BY applications.application_id DESC
");
$stmt->execute([
    ":company_id" => $company_id
]);
$applicants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Applicants</title>
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
    <div class="relative min-h-screen">
        <?php include "../includes/company_sidebar.php"; ?>
        <main class="ml-72 min-h-screen p-6 md:p-10"> <!-- TOP BAR -->
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h1 class="text-3xl font-bold">
                        Internship
                        <span class="text-primary">
                            Applicants
                        </span>
                        📄
                    </h1>
                    <p class="text-slate-400 mt-2">
                        Review students who applied for your internships
                    </p>
                </div>
                <div class="w-14 h-14 rounded-full bg-primary/20 flex items-center justify-center text-3xl">
                    🏢
                </div>
            </div>
            <?php if (count($applicants) > 0): ?>
                <div class="space-y-6">
                    <?php foreach ($applicants as $applicant): ?>
                        <div class="bg-slate-900 border border-cyan-900 rounded-3xl p-6">
                            <h2 class="text-2xl font-bold text-cyan-400">
                                <?= $applicant['full_name']; ?>
                            </h2>
                            <p class="mt-3 text-gray-300">
                                Internship:
                                <span class="text-white font-semibold">
                                    <?= $applicant['title']; ?>
                                </span>
                            </p>
                            <div class="mt-4 space-y-2 text-gray-300">
                                <p>
                                    <b>Email:</b>
                                    <?= $applicant['email']; ?>
                                </p>
                                <p>
                                    <b>Phone:</b>
                                    <?= $applicant['phone']; ?>
                                </p>
                                <p>
                                    <b>University:</b>
                                    <?= $applicant['university']; ?>
                                </p>
                                <p>
                                    <b>Department:</b>
                                    <?= $applicant['department']; ?>
                                </p>
                                <p>
                                    <b>Skills:</b>
                                    <?= $applicant['skills']; ?>
                                </p>
                            </div>
                            <div class="flex gap-3 mt-6">

                                <?php if (!empty($applicant['resume'])): ?>

                                    <a href="../uploads/resumes/<?= $applicant['resume']; ?>"
                                        target="_blank"
                                        class="bg-primary text-black px-5 py-3 rounded-xl font-semibold">
                                        View Resume
                                    </a>

                                <?php endif; ?>
                                <div class="flex gap-3 mt-6">

                                    <?php if (!empty($applicant['resume'])): ?>

                                        <a href="../uploads/resumes/<?= $applicant['resume']; ?>"
                                            target="_blank"
                                            class="bg-primary text-black px-5 py-3 rounded-xl font-semibold">
                                            View Resume
                                        </a>

                                    <?php endif; ?>


                                    <?php if ($applicant['status'] == "Pending"): ?>

                                        <a href="../company/update_application.php?id=<?= $applicant['application_id']; ?>&status=Approved"
                                            class="bg-green-500 text-white px-5 py-3 rounded-xl font-semibold">
                                            Approve
                                        </a>

                                        <a href="../company/update_application.php?id=<?= $applicant['application_id']; ?>&status=Rejected"
                                            class="bg-red-500 text-white px-5 py-3 rounded-xl font-semibold">
                                            Reject
                                        </a>

                                    <?php elseif ($applicant['status'] == "Accepted"): ?>

                                        <span class="bg-green-500/20 border border-green-500/40 
text-green-400 px-5 py-3 rounded-xl font-semibold">
                                            ✅ Accepted
                                        </span>


                                    <?php elseif ($applicant['status'] == "Rejected"): ?>

                                        <span class="bg-red-500/20 border border-red-500/40 text-red-400 px-5 py-3 rounded-xl font-semibold">
                                            ❌ Rejected
                                        </span>


                                    <?php endif; ?>

                                </div>


                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-slate-900 p-8 rounded-3xl text-center">
                    <p class="text-gray-400">
                        No students have applied yet.
                    </p>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>
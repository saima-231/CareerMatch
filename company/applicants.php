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
</head>

<body class="bg-slate-950 text-white min-h-screen">
    <div class="max-w-6xl mx-auto p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">
                Applicants
            </h1>
            <a href="/careermatch/dashboard/company_dashboard.php"
                class="bg-cyan-400 text-black px-5 py-3 rounded-xl font-bold">
                Dashboard
            </a>
        </div>
        <?php if (count($applicants) > 0): ?>
            <div class="space-y-5">
                <?php foreach ($applicants as $applicant): ?>
                    <div class="bg-slate-900 border border-cyan-900 rounded-3xl p-6">
                        <div class="flex justify-between">
                            <div>
                                <h2 class="text-xl font-bold text-cyan-400">
                                    <?php echo htmlspecialchars($applicant['full_name']); ?>
                                </h2>
                                <p class="text-gray-400">
                                    <?php echo htmlspecialchars($applicant['email']); ?>
                                </p>
                                <p class="text-gray-400 mt-2">
                                    University:
                                    <?php echo $applicant['university']; ?>
                                </p>
                                <p class="text-gray-400">
                                    Department:
                                    <?php echo $applicant['department']; ?>
                                </p>
                                <p class="text-gray-400">
                                    Skills:
                                    <?php echo $applicant['skills']; ?>
                                </p>
                                <p class="mt-3">
                                    Applied For:
                                    <span class="text-cyan-400">
                                        <?php echo htmlspecialchars($applicant['title']); ?>
                                    </span>
                                </p>
                                <p class="mt-2">
                                    Status:
                                    <span class="font-bold">
                                        <?php echo htmlspecialchars($applicant['status']); ?>
                                    </span>
                                </p>
                                <p class="mt-2 text-gray-400">
                                    Applied Date:
                                    <?php echo $applicant['application_date']; ?>
                                </p>
                            </div>
                            <div class="flex gap-3 items-center">
                                <?php if (!empty($application['resume'])): ?>
                                    <a href="../uploads/resumes/<?php echo $application['resume']; ?>"
                                        target="_blank"
                                        class="bg-cyan-400 text-black px-4 py-2 rounded-lg">
                                        View Resume
                                    </a>
                                <?php else: ?>
                                    <span class="text-gray-400">
                                        No Resume Uploaded
                                    </span>
                                <?php endif; ?>
                                <a href="update_application.php?id=<?php echo $applicant['application_id']; ?>&status=Accepted"
                                    class="bg-green-500/20 text-green-400 px-4 py-2 rounded-xl">
                                    Accept
                                </a>
                                <a href="update_application.php?id=<?php echo $applicant['application_id']; ?>&status=Rejected"
                                    class="bg-red-500/20 text-red-400 px-4 py-2 rounded-xl">
                                    Reject
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-slate-900 rounded-3xl p-8 text-center">
                <p class="text-gray-400">
                    No applicants yet.
                </p>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>
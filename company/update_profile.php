<?php
session_start();

include "../config/database.php";
if (!isset($_SESSION['company_id'])) {
    header("Location: ../login.php");
    exit();
}
$company_id = $_SESSION['company_id'];
// Get company data
$stmt = $pdo->prepare(
    "SELECT * FROM companies WHERE company_id = :id"
);
$stmt->execute([
    ":id" => $company_id
]);
$company = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$company) {
    echo "Company not found";
    exit();
}

// Update company information
if (isset($_POST['update'])) {
    $company_name = $_POST['company_name'];
    $email = $_POST['email'];
    $contact_person = $_POST['contact_person'];
    $phone = $_POST['phone'];
    $website = $_POST['website'];
    $industry = $_POST['industry'];
    $company_size = $_POST['company_size'];
    $address = $_POST['address'];
    $update = $pdo->prepare("
        UPDATE companies SET
        company_name = :company_name,
        email = :email,
        contact_person = :contact_person,
        phone = :phone,
        website = :website,
        industry = :industry,
        company_size = :company_size,
        company_address = :address
        WHERE company_id = :id
    ");
    $update = $pdo->prepare("
    UPDATE companies SET
    company_name = :company_name,
    email = :email,
    contact_person = :contact_person,
    phone = :phone,
    website = :website,
    industry = :industry,
    company_size = :company_size,
    address = :address
    WHERE company_id = :id
");
    header("Location: ../dashboard/company_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Update Company Profile</title>
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
        <!-- SIDEBAR -->
        <?php include "../includes/company_sidebar.php"; ?>
        <!-- MAIN -->
        <main class="flex-1 p-6 md:p-10">
            <!-- TOP BAR -->
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h1 class="text-3xl font-bold">
                        Update
                        <span class="text-primary">
                            Company Profile
                        </span>
                        🏢
                    </h1>
                    <p class="text-slate-400 mt-2">
                        Manage your company information
                    </p>
                </div>
                <div class="w-14 h-14 rounded-full bg-primary/20 flex items-center justify-center text-3xl">
                    🏢
                </div>
            </div>
            <!-- FORM -->
            <div class="bg-slate-900 border border-cyan-900 rounded-3xl p-8">
                <form method="POST" class="space-y-5">
                    <input
                        name="company_name"
                        value="<?php echo $company['company_name']; ?>"
                        class="w-full p-3 rounded-xl bg-slate-800 text-white"
                        placeholder="Company Name">
                    <input
                        name="email"
                        value="<?php echo $company['email']; ?>"
                        class="w-full p-3 rounded-xl bg-slate-800 text-white"
                        placeholder="Email">
                    <input
                        name="contact_person"
                        value="<?php echo $company['contact_person']; ?>"
                        class="w-full p-3 rounded-xl bg-slate-800 text-white"
                        placeholder="Contact Person">
                    <input
                        name="phone"
                        value="<?php echo $company['phone']; ?>"
                        class="w-full p-3 rounded-xl bg-slate-800 text-white"
                        placeholder="Phone">
                    <input
                        name="website"
                        value="<?php echo $company['website']; ?>"
                        class="w-full p-3 rounded-xl bg-slate-800 text-white"
                        placeholder="Website">
                    <input
                        name="industry"
                        value="<?php echo $company['industry']; ?>"
                        class="w-full p-3 rounded-xl bg-slate-800 text-white"
                        placeholder="Industry">
                    <input
                        name="company_size"
                        value="<?php echo $company['company_size']; ?>"
                        class="w-full p-3 rounded-xl bg-slate-800 text-white"
                        placeholder="Company Size">
                    <textarea
                        name="address"
                        class="w-full p-3 rounded-xl bg-slate-800 text-white"
                        placeholder="Company Address"><?php echo $company['address']; ?></textarea>
                    <button
                        name="update"
                        class="bg-cyan-400 text-black px-6 py-3 rounded-xl font-bold">
                        Update Profile
                    </button>
                    <a href="../dashboard/company_dashboard.php"
                        class="block text-center bg-slate-700 px-6 py-3 rounded-xl">
                        Back to Dashboard
                    </a>
                </form>
            </div>
        </main>
    </div>
</body>

</html>
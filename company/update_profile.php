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
    $update->execute([
        ":company_name" => $company_name,
        ":email" => $email,
        ":contact_person" => $contact_person,
        ":phone" => $phone,
        ":website" => $website,
        ":industry" => $industry,
        ":company_size" => $company_size,
        ":address" => $address,
        ":id" => $company_id
    ]);
    header("Location: ../dashboard/company_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Update Company Profile</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-slate-950 text-white min-h-screen p-10">
    <div class="max-w-xl mx-auto bg-slate-900 p-8 rounded-3xl">
        <h1 class="text-3xl font-bold mb-6">
            Update Company Profile
        </h1>
        <form method="POST" class="space-y-4">
            <input
                name="company_name"
                value="<?php echo $company['company_name']; ?>"
                class="w-full p-3 rounded bg-slate-800"
                placeholder="Company Name">
            <input
                name="email"
                value="<?php echo $company['email']; ?>"
                class="w-full p-3 rounded bg-slate-800"
                placeholder="Email">
            <input
                name="contact_person"
                value="<?php echo $company['contact_person']; ?>"
                class="w-full p-3 rounded bg-slate-800"
                placeholder="Contact Person">
            <input
                name="phone"
                value="<?php echo $company['phone']; ?>"
                class="w-full p-3 rounded bg-slate-800"
                placeholder="Phone">
            <input
                name="website"
                value="<?php echo $company['website']; ?>"
                class="w-full p-3 rounded bg-slate-800"
                placeholder="Website">
            <input
                name="industry"
                value="<?php echo $company['industry']; ?>"
                class="w-full p-3 rounded bg-slate-800"
                placeholder="Industry">
            <input
                name="company_size"
                value="<?php echo $company['company_size']; ?>"
                class="w-full p-3 rounded bg-slate-800"
                placeholder="Company Size">
            <textarea
                name="address"
                class="w-full p-3 rounded bg-slate-800"
                placeholder="Company Address"><?php echo $company['company_address']; ?></textarea>
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
</body>

</html>
<?php

include "config/database.php";
if (isset($_POST['register'])) {


    $company_name = $_POST['company_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $contact = $_POST['contact_person'];
    $phone = $_POST['phone'];
    $website = $_POST['website'];
    $industry = $_POST['industry'];
    $size = $_POST['company_size'];
    $address = $_POST['address'];

    // Check existing company email
    $check = $pdo->prepare(
        "SELECT email FROM companies WHERE email=:email"
    );

    $check->execute([
        ":email" => $email
    ]);


    if ($check->rowCount() > 0) {

        echo "
        <script>
        alert('Company email already exists');
        window.history.back();
        </script>";

        exit();
    }

    // INSERT COMPANY

    $sql = "INSERT INTO companies
    (
    company_name,
    email,
    password,
    contact_person,
    phone,
    website,
    industry,
    company_size,
    address
    )

    VALUES

    (
    :company_name,
    :email,
    :password,
    :contact_person,
    :phone,
    :website,
    :industry,
    :company_size,
    :address
    )";


    $sql = "INSERT INTO companies
    (
    company_name,
    email,
    password,
    contact_person,
    phone,
    website,
    industry,
    company_size,
    address
    )

    VALUES

    (
    :company_name,
    :email,
    :password,
    :contact_person,
    :phone,
    :website,
    :industry,
    :company_size,
    :address
    )";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([

        ":company_name" => $company_name,
        ":email" => $email,
        ":password" => $password,
        ":contact_person" => $contact,
        ":phone" => $phone,
        ":website" => $website,
        ":industry" => $industry,
        ":company_size" => $size,
        ":address" => $address
    ]);

    echo "
    <script>
        alert('Company Registered Successfully');
        window.location = 'login.php';
    </script>";
}


?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>CareerMatch - Company Registration</title>

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
        };
    </script>
</head>

<body class="bg-primaryDark text-cream min-h-screen flex items-center justify-center">
    <!-- Glow -->

    <div class="absolute w-72 h-72 bg-primary/20 blur-3xl rounded-full top-10 left-10"></div>

    <div class="absolute w-72 h-72 bg-secondary/10 blur-3xl rounded-full bottom-10 right-10"></div>

    <!-- CARD -->

    <div class="relative w-full max-w-4xl bg-slate-900/70 backdrop-blur-lg border border-cyan-900 rounded-3xl p-10">
        <!-- HEADER -->

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold">
                Career<span class="text-primary"> Match </span>
            </h1>

            <div class="w-16 h-[2px] bg-primary mx-auto mt-3"></div>

            <h2 class="text-xl font-semibold mt-5">🏢 Company Registration</h2>

            <p class="text-slate-400 text-sm mt-2">
                Create company account and find talented students
            </p>
        </div>

        <form action="company_register.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Company Name -->

            <div>
                <label class="text-sm text-slate-300"> Company Name </label>

                <input type="text" name="company_name" placeholder="Enter company name" required
                    class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 focus:border-primary outline-none text-white" />
            </div>

            <!-- Email -->

            <div>
                <label class="text-sm text-slate-300"> Company Email </label>

                <input type="email" name="email" placeholder="company@email.com" required
                    class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 focus:border-primary outline-none text-white" />
            </div>

            <!-- Password -->

            <div>
                <label class="text-sm text-slate-300"> Password </label>

                <input type="password" name="password" placeholder="Create password" required
                    class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 focus:border-primary outline-none text-white" />
            </div>

            <!-- Contact Person -->

            <div>
                <label class="text-sm text-slate-300"> Contact Person </label>

                <input type="text" name="contact_person" placeholder="HR / Recruiter Name" required
                    class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 focus:border-primary outline-none text-white" />
            </div>

            <!-- Phone -->

            <div>
                <label class="text-sm text-slate-300"> Phone Number </label>

                <input type="tel" name="phone" placeholder="Company contact number" required
                    class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 focus:border-primary outline-none text-white" />
            </div>

            <!-- Website -->

            <div>
                <label class="text-sm text-slate-300"> Website </label>

                <input type="url" name="website" placeholder="https://company.com"
                    class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 focus:border-primary outline-none text-white" />
            </div>

            <!-- Industry -->

            <div>
                <label class="text-sm text-slate-300"> Industry Type </label>
                <select name="industry"
                    class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 focus:border-primary outline-none">
                    <option value="">Select Industry Type</option>

                    <option value="Software Development">
                        Software Development
                    </option>

                    <option value="IT Services">
                        IT Services
                    </option>

                    <option value="Artificial Intelligence">
                        Artificial Intelligence
                    </option>

                    <option value="Data Science">
                        Data Science
                    </option>
                    <option value="Cloud Computing">
                        Cloud Computing
                    </option>
                    <option value="Cyber Security">
                        Cyber Security
                    </option>
                    <option value="UI/UX & Design">
                        UI/UX & Design
                    </option>
                    <option value="EdTech">
                        Education Technology
                    </option>
                    <option value="FinTech">
                        Financial Technology
                    </option>
                    <option value="Telecommunication">
                        Telecommunication
                    </option>
                </select>
            </div>

            <!-- Employee Size -->
            <div>
                <label class="text-sm text-slate-300"> Company Size </label>
                <select name="company_size"
                    class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 focus:border-primary outline-none">

                    <option value="1-10 Employees">1-10 Employees</option>
                    <option value="11-50 Employees">11-50 Employees</option>
                    <option value="51-200 Employees">51-200 Employees</option>
                    <option value="200+ Employees">200+ Employees</option>

                </select>
            </div>

            <!-- Address -->

            <div class="md:col-span-2">
                <label class="text-sm text-slate-300"> Company Address </label>

                <textarea
                    name="address"
                    placeholder="Enter company address"
                    required
                    class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 focus:border-primary outline-none text-white h-28"></textarea>
            </div>

            <!-- Company Logo -->
            <div class="md:col-span-2">
                <label class="text-sm text-slate-300"> Upload Company Logo </label>
                <input type="file"
                    class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 text-slate-300" />
            </div>

            <!-- BUTTON -->
            <button
                type="submit"
                name="register"
                class="md:col-span-2 w-full bg-primary text-primaryDark font-semibold py-3 rounded-xl hover:bg-secondary transition duration-300">
                Create Company Account
            </button>
        </form>
        <p class="text-center text-slate-400 text-sm mt-8">
            Already registered?
            <a href="login.php"
                class="text-primary hover:underline font-medium">

                Login

            </a>

        </p>
        </form>
    </div>
</body>

</html>
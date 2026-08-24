<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<aside class="hidden md:flex fixed left-0 top-0 h-screen w-72 bg-slate-900/70 backdrop-blur-lg border-r border-cyan-900 flex-col p-6 overflow-y-auto">
     <!-- LOGO -->
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-white">
            Career<span class="text-primary">Match</span>
        </h1>
        <div class="w-16 h-[2px] bg-primary mx-auto mt-3"></div>
    </div>
    <!-- MENU -->
    <nav class="space-y-4">
        <!-- Dashboard -->
        <a href="../dashboard/company_dashboard.php"
            class="block px-4 py-3 rounded-xl
<?= ($currentPage == 'company_dashboard.php')
    ? 'bg-primary text-primaryDark font-semibold'
    : 'hover:bg-slate-800 text-white' ?>">
            👤 Profile
        </a>
        <!-- Post Internship -->
        <a href="../company/post_internship.php"
            class="block px-4 py-3 rounded-xl
        <?= ($currentPage == 'post_internship.php')
            ? 'bg-primary text-primaryDark font-semibold'
            : 'hover:bg-slate-800 text-white' ?>">
            ➕ Post Internship
        </a>
        <!-- Manage Internship -->
        <a href="../company/manage_internships.php"
            class="block px-4 py-3 rounded-xl
        <?= ($currentPage == 'manage_internships.php')
            ? 'bg-primary text-primaryDark font-semibold'
            : 'hover:bg-slate-800 text-white' ?>">
            💼 Manage Internships
        </a>
        <!-- Applicants -->
        <a href="../company/applicants.php"
            class="block px-4 py-3 rounded-xl
        <?= ($currentPage == 'applicants.php')
            ? 'bg-primary text-primaryDark font-semibold'
            : 'hover:bg-slate-800 text-white' ?>">
            📄 Applicants
        </a>
        <!-- Home -->
        <a href="../index.php"
            class="block px-4 py-3 rounded-xl hover:bg-slate-800 text-white">
            🏠 Back to Home
        </a>
    </nav>
    <!-- LOGOUT -->
    <div class="mt-auto">
        <a href="../logout.php"
            class="block text-center bg-red-500/20 border border-red-500/40 
        px-4 py-3 rounded-xl hover:bg-red-500/40 text-white">
            Logout
        </a>
    </div>
</aside>
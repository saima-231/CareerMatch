<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<aside class="hidden md:flex w-72 bg-slate-900/70 backdrop-blur-lg border-r border-cyan-900 flex-col p-6">

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
        <a href="../dashboard/student_dashboard.php"
            class="block px-4 py-3 rounded-xl 
            <?= ($currentPage == 'student_dashboard.php')
                ? 'bg-primary text-primaryDark font-semibold'
                : 'hover:bg-slate-800 text-white' ?>">
            🏠 Profile
        </a>


        <!-- Internship -->
        <a href="../student/internship.php"
            class="block px-4 py-3 rounded-xl 
            <?= ($currentPage == 'internship.php')
                ? 'bg-primary text-primaryDark font-semibold'
                : 'hover:bg-slate-800 text-white' ?>">
            🔎 Find Internship
        </a>


        <!-- Applications -->
        <a href="../student/applications.php"
            class="block px-4 py-3 rounded-xl 
            <?= ($currentPage == 'applications.php')
                ? 'bg-primary text-primaryDark font-semibold'
                : 'hover:bg-slate-800 text-white' ?>">
            📄 Applications
        </a>


        <!-- Home -->
        <a href="../index.php"
            class="block px-4 py-3 rounded-xl 
            <?= ($currentPage == 'index.php')
                ? 'bg-primary text-primaryDark font-semibold'
                : 'hover:bg-slate-800 text-white' ?>">
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
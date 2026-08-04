<?php
session_start();

$user = null;
$profileLink = "";

if (isset($_SESSION['student_id'])) {
    $user = $_SESSION['student_name'];
    $profileLink = "dashboard/student_dashboard.php";
} elseif (isset($_SESSION['company_id'])) {
    $user = $_SESSION['company_name'];
    $profileLink = "dashboard/company_dashboard.php";
} elseif (isset($_SESSION['admin_id'])) {
    $user = $_SESSION['admin_name'];
    $profileLink = "dashboard/admin_dashboard.php";
}
?>

<!doctype html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CareerMatch</title>
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
    <style>
        .hero-bg {
            background: linear-gradient(-45deg, #091d3e, #133264, #234a7f, #375b95);
            background-size: 400% 400%;
            animation: gradientFlow 12s ease infinite;
        }

        @keyframes gradientFlow {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }
    </style>
</head>

<body class="bg-cream text-primaryDark antialiased">
    <!-- NAVBAR -->
    <header class="fixed top-0 left-0 right-0 z-50 backdrop-blur-md bg-primaryDark/40 border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between flex-wrap gap-4">
            <div class="text-center">
                <h1 class="text-white text-2xl sm:text-3xl md:text-4xl font-bold tracking-wide">
                    CareerMatch
                </h1>
                <div class="w-16 sm:w-20 h-[2px] bg-secondary mx-auto mt-2"></div>
                <p class="text-secondary text-[8px] sm:text-[10px] tracking-[0.2em] sm:tracking-[0.3em] uppercase mt-2">
                    Connecting talent with opportunity
                </p>
            </div>
            <nav class="flex items-center justify-center sm:justify-end gap-4 sm:gap-6 text-white text-xs sm:text-sm">
                <a href="index.php" class="hover:text-secondary">
                    Home
                </a>
                <a href="../internships.php" class="hover:text-secondary">
                    Internships
                </a>
                <a href="../companies.php" class="hover:text-secondary">
                    Companies
                </a>
                <?php if ($user): ?>
                    <a href="<?php echo $profileLink; ?>"
                        class="flex items-center gap-2 hover:text-secondary whitespace-nowrap">
                        <span class="text-xl">
                            👤
                        </span>
                        <?php echo $user; ?>
                    </a>
                    <a href="logout.php"
                        class="bg-red-500 text-white px-4 py-2 rounded-xl font-semibold whitespace-nowrap">
                        Logout
                    </a>
                <?php else: ?>
                    <a href="login.php" class="hover:text-secondary">
                        Login
                    </a>
                    <a href="register.php"
                        class="bg-secondary text-primaryDark px-4 py-2 rounded-xl font-semibold whitespace-nowrap">
                        Register
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <!-- HERO -->
    <section class="hero-bg min-h-screen flex items-center relative pt-28">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-12 items-center">


                <!-- LEFT SIDE -->
                <div>

                    <div class="inline-block bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full text-secondary text-sm">
                        Internship Management Platform
                    </div>


                    <h2 class="text-white text-4xl sm:text-5xl lg:text-6xl font-bold mt-6 leading-tight">
                        Launch Your
                        <span class="text-secondary">Career</span> Journey
                    </h2>


                    <p class="text-white/90 mt-6 text-base sm:text-lg max-w-xl">
                        Discover internships, connect with companies, and track every
                        application from one modern platform.
                    </p>


                    <!-- SEARCH -->
                    <div class="relative mt-8 max-w-xl">

                        <!-- SEARCH -->
                        <div class="relative mt-8 max-w-xl">

                            <form action="../internships.php" method="GET"
                                class="bg-white rounded-2xl p-2 flex flex-col sm:flex-row gap-2 shadow-2xl">

                                <input
                                    id="searchBox"
                                    type="text"
                                    name="search"
                                    placeholder="Search internships..."
                                    autocomplete="off"
                                    class="flex-1 px-5 py-3 outline-none rounded-xl w-full text-black placeholder-gray-500" />

                                <button
                                    type="submit"
                                    class="bg-primary text-white px-8 py-3 rounded-xl font-semibold hover:bg-cyan-500 transition">
                                    Search
                                </button>

                            </form>


                            <!-- Suggestions -->
                            <div id="suggestions"
                                class="absolute top-full left-0 right-0 mt-2 bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden hidden z-50">

                                <div class="suggestion px-5 py-3 text-gray-800 hover:bg-cyan-100 cursor-pointer transition">
                                    💻 Web Development
                                </div>

                                <div class="suggestion px-5 py-3 text-gray-800 hover:bg-cyan-100 cursor-pointer transition">
                                    ☁️ Cloud & DevOps
                                </div>

                                <div class="suggestion px-5 py-3 text-gray-800 hover:bg-cyan-100 cursor-pointer transition">
                                    📊 Data Science
                                </div>

                                <div class="suggestion px-5 py-3 text-gray-800 hover:bg-cyan-100 cursor-pointer transition">
                                    🤖 Artificial Intelligence
                                </div>

                                <div class="suggestion px-5 py-3 text-gray-800 hover:bg-cyan-100 cursor-pointer transition">
                                    🎨 UI/UX Design
                                </div>

                                <div class="suggestion px-5 py-3 text-gray-800 hover:bg-cyan-100 cursor-pointer transition">
                                    🌐 Networking
                                </div>

                                <div class="suggestion px-5 py-3 text-gray-800 hover:bg-cyan-100 cursor-pointer transition">
                                    🧪 Software Testing
                                </div>

                                <div class="suggestion px-5 py-3 text-gray-800 hover:bg-cyan-100 cursor-pointer transition">
                                    🖌️ Graphic Design
                                </div>

                            </div>

                        </div>

                    </div>


                </div>



                <!-- RIGHT SIDE -->
                <div>

                    <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-3xl p-6 sm:p-8">

                        <div class="grid grid-cols-2 gap-4">


                            <div class="bg-white rounded-2xl p-5">
                                <h3 class="text-2xl sm:text-3xl font-bold text-primary">
                                    500+
                                </h3>
                                <p class="mt-2 text-gray-600 text-sm">
                                    Active Internships
                                </p>
                            </div>


                            <div class="bg-white rounded-2xl p-5">
                                <h3 class="text-2xl sm:text-3xl font-bold text-primary">
                                    200+
                                </h3>
                                <p class="mt-2 text-gray-600 text-sm">
                                    Companies
                                </p>
                            </div>


                            <div class="bg-white rounded-2xl p-5">
                                <h3 class="text-2xl sm:text-3xl font-bold text-primary">
                                    5K+
                                </h3>
                                <p class="mt-2 text-gray-600 text-sm">
                                    Students
                                </p>
                            </div>


                            <div class="bg-white rounded-2xl p-5">
                                <h3 class="text-2xl sm:text-3xl font-bold text-primary">
                                    1K+
                                </h3>
                                <p class="mt-2 text-gray-600 text-sm">
                                    Placements
                                </p>
                            </div>


                        </div>

                    </div>

                </div>


            </div>
    </section>
    <!-- ABOUT -->
    <section id="about" class="py-16 md:py-24 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center">
                <h2 class="text-3xl sm:text-4xl font-bold text-primaryDark mt-6">
                    Connecting Students with Career Opportunities
                </h2>
                <p class="mt-4 text-gray-500 max-w-3xl mx-auto">
                    CareerMatch is an internship management platform that helps students
                    discover internships while enabling companies to recruit talented
                    candidates through one simple and modern platform.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-14">
                <!-- Students -->
                <div
                    class="group p-8 rounded-3xl border border-cyan-400/50 shadow-[0_0_20px_rgba(34,211,238,0.15)] hover:shadow-[0_0_30px_rgba(34,211,238,0.3)] hover:-translate-y-1 transition-all duration-300">

                    <div
                        class="w-14 h-14 bg-cyan-200/40 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition">
                        🎓
                    </div>

                    <h3 class="mt-5 font-semibold text-lg text-primaryDark">
                        For Students
                    </h3>

                    <p class="mt-3 text-gray-500">
                        Search internships, apply online, and track every application
                        from your personal dashboard.
                    </p>

                </div>

                <!-- Companies -->
                <div
                    class="group p-8 rounded-3xl border border-emerald-400/50 shadow-[0_0_20px_rgba(52,211,153,0.15)] hover:shadow-[0_0_30px_rgba(52,211,153,0.3)] hover:-translate-y-1 transition-all duration-300">

                    <div
                        class="w-14 h-14 bg-emerald-200/40 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition">
                        🏢
                    </div>

                    <h3 class="mt-5 font-semibold text-lg text-primaryDark">
                        For Companies
                    </h3>

                    <p class="mt-3 text-gray-500">
                        Post internship opportunities, review applications, and recruit
                        qualified students with ease.
                    </p>

                </div>

                <!-- Career Growth -->
                <div
                    class="group p-8 rounded-3xl border border-violet-400/50 shadow-[0_0_20px_rgba(167,139,250,0.15)] hover:shadow-[0_0_30px_rgba(167,139,250,0.3)] hover:-translate-y-1 transition-all duration-300">

                    <div
                        class="w-14 h-14 bg-violet-200/40 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition">
                        🚀
                    </div>

                    <h3 class="mt-5 font-semibold text-lg text-primaryDark">
                        Career Growth
                    </h3>

                    <p class="mt-3 text-gray-500">
                        Build professional experience, develop new skills, and prepare
                        for a successful future career.
                    </p>

                </div>

            </div>

        </div>

    </section>
    <!-- CATEGORIES -->
    <section class="py-16 md:py-24 lg:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center">
                <h2 class="text-3xl sm:text-4xl font-bold text-primaryDark">
                    Explore Categories
                </h2>
                <p class="mt-4 text-gray-500">
                    Find opportunities that match your interests.
                </p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mt-14">
                <a href="../internships.php?category=Web Development"
                    class="group block p-8 rounded-3xl border border-cyan-400/50 shadow-[0_0_20px_rgba(34,211,238,0.15)] hover:shadow-[0_0_30px_rgba(34,211,238,0.3)] hover:-translate-y-1 transition-all duration-300">
                    <div
                        class="w-14 h-14 bg-cyan-200/40 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition">
                        💻
                    </div>
                    <h3 class="mt-5 font-semibold text-lg">Web Development</h3>
                </a>
                <a href="../internships.php?category=UI/UX Design"
                    class="group p-8 rounded-3xl border border-emerald-400/50 shadow-[0_0_20px_rgba(52,211,153,0.15)] hover:shadow-[0_0_30px_rgba(52,211,153,0.3)] hover:-translate-y-1 transition-all duration-300">
                    <div
                        class="w-14 h-14 bg-emerald-200/40 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition">
                        🎨
                    </div>
                    <h3 class="mt-5 font-semibold text-lg">UI/UX Design</h3>
                </a>
                <a href="../internships.php?category=Data Science"
                    class="group p-8 rounded-3xl border border-violet-400/50 shadow-[0_0_20px_rgba(167,139,250,0.15)] hover:shadow-[0_0_30px_rgba(167,139,250,0.3)] hover:-translate-y-1 transition-all duration-300">
                    <div
                        class="w-14 h-14 bg-violet-200/40 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition">
                        📊
                    </div>
                    <h3 class="mt-5 font-semibold text-lg">Data Science</h3>
                </a>
                <a href="../internships.php?category=Cloud & DevOps"
                    class="group p-8 rounded-3xl border border-sky-400/50 shadow-[0_0_20px_rgba(56,189,248,0.15)] hover:shadow-[0_0_30px_rgba(56,189,248,0.3)] hover:-translate-y-1 transition-all duration-300">
                    <div
                        class="w-14 h-14 bg-sky-200/40 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition">
                        ☁️
                    </div>
                    <h3 class="mt-5 font-semibold text-lg">Cloud & DevOps</h3>
                </a>
                <a href="../internships.php?category=Software Testing"
                    class="group p-8 rounded-3xl border border-lime-400/50 shadow-[0_0_20px_rgba(163,230,53,0.15)] hover:shadow-[0_0_30px_rgba(163,230,53,0.3)] hover:-translate-y-1 transition-all duration-300">
                    <div
                        class="w-14 h-14 bg-lime-200/40 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition">
                        🧪
                    </div>
                    <h3 class="mt-5 font-semibold text-lg">Software Testing</h3>
                </a>
                <a href="../internships.php?category=Artificial Intelligence"
                    class="group p-8 rounded-3xl border border-indigo-400/50 shadow-[0_0_20px_rgba(129,140,248,0.15)] hover:shadow-[0_0_30px_rgba(129,140,248,0.3)] hover:-translate-y-1 transition-all duration-300">
                    <div
                        class="w-14 h-14 bg-indigo-200/40 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition">
                        🤖
                    </div>
                    <h3 class="mt-5 font-semibold text-lg">Artificial Intelligence</h3>
                </a>
                <a href="../internships.php?category=Networking"
                    class="group block p-8 rounded-3xl border border-blue-400/50 shadow-[0_0_20px_rgba(96,165,250,0.15)] hover:shadow-[0_0_30px_rgba(96,165,250,0.3)] hover:-translate-y-1 transition-all duration-300">

                    <div
                        class="w-14 h-14 bg-blue-200/40 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition">
                        🌐
                    </div>
                    <h3 class="mt-5 font-semibold text-lg">
                        Networking
                    </h3>
                </a>
                <a href="../internships.php?category=Graphic Design"
                    class="group block p-8 rounded-3xl border border-pink-400/50 shadow-[0_0_20px_rgba(244,114,182,0.15)] hover:shadow-[0_0_30px_rgba(244,114,182,0.3)] hover:-translate-y-1 transition-all duration-300">
                    <div
                        class="w-14 h-14 bg-pink-200/40 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition">
                        🖌️
                    </div>
                    <h3 class="mt-5 font-semibold text-lg">
                        Graphic Design
                    </h3>
                </a>
            </div>
        </div>
    </section>
    <!-- FEATURES SECTION -->
    <section class="py-12 bg-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-14">
                <h2 class="text-3xl sm:text-4xl font-bold text-primaryDark">
                    Why CareerMatch?
                </h2>
                <p class="mt-3 text-gray-600 text-base">
                    A complete internship system built for students and companies
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div
                    class="group p-8 rounded-3xl bg-white border border-cyan-300/60 shadow-[0_0_18px_rgba(34,211,238,0.18)] hover:shadow-[0_0_40px_rgba(34,211,238,0.35)] hover:-translate-y-2 transition">
                    <div class="text-4xl">🚀</div>
                    <h3 class="text-xl font-bold mt-4 text-primaryDark">
                        Faster Applications
                    </h3>
                    <p class="text-gray-700 mt-2 leading-relaxed">
                        Apply to internships instantly with a simple and smooth process.
                    </p>
                </div>
                <div
                    class="group p-8 rounded-3xl bg-white border border-emerald-300/60 shadow-[0_0_18px_rgba(52,211,153,0.18)] hover:shadow-[0_0_40px_rgba(52,211,153,0.35)] hover:-translate-y-2 transition">
                    <div class="text-4xl">🏢</div>
                    <h3 class="text-xl font-bold mt-4 text-primaryDark">
                        Company Dashboard
                    </h3>
                    <p class="text-gray-700 mt-2 leading-relaxed">
                        Manage internships, applicants, and hiring in one organized
                        system.
                    </p>
                </div>
                <div
                    class="group p-8 rounded-3xl bg-white border border-violet-300/60 shadow-[0_0_18px_rgba(167,139,250,0.18)] hover:shadow-[0_0_40px_rgba(167,139,250,0.35)] hover:-translate-y-2 transition">
                    <div class="text-4xl">📊</div>
                    <h3 class="text-xl font-bold mt-4 text-primaryDark">
                        Real-Time Tracking
                    </h3>
                    <p class="text-gray-700 mt-2 leading-relaxed">
                        Track application status instantly: pending, accepted, or
                        rejected.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- FOOTER -->
    <footer class="bg-primaryDark text-white py-12 md:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
            <h2 class="text-xl sm:text-2xl font-bold">CareerMatch</h2>
            <div class="w-16 h-[2px] bg-secondary mx-auto mt-2"></div>
            <p class="text-secondary mt-2">Connecting talent with opportunity</p>
            <p class="mt-6 text-gray-400 text-sm">
                © 2026 CareerMatch. All Rights Reserved.
            </p>
        </div>
    </footer>
    <script>
        const searchBox = document.getElementById("searchBox");
        const suggestions = document.getElementById("suggestions");
        const items = document.querySelectorAll(".suggestion");


        // Show suggestions when clicking search
        searchBox.addEventListener("focus", () => {
            suggestions.classList.remove("hidden");
        });


        // Select suggestion
        items.forEach(item => {

            item.addEventListener("click", () => {

                // remove emoji before sending search
                let value = item.innerText.replace(/^[^\w]+/, "");

                searchBox.value = value;

                suggestions.classList.add("hidden");

            });

        });


        // Hide when clicking outside
        document.addEventListener("click", (e) => {

            if (
                !searchBox.contains(e.target) &&
                !suggestions.contains(e.target)
            ) {
                suggestions.classList.add("hidden");
            }

        });
    </script>
</body>

</html>
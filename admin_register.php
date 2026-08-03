<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>CareerMatch - Admin Registration</title>

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
    <!-- Background Glow -->
    <div class="absolute w-72 h-72 bg-primary/20 blur-3xl rounded-full top-10 left-10"></div>
    <div class="absolute w-72 h-72 bg-secondary/10 blur-3xl rounded-full bottom-10 right-10"></div>
    <!-- ADMIN CARD -->
    <div class="relative w-full max-w-3xl bg-slate-900/70 backdrop-blur-lg border border-cyan-900 rounded-3xl p-10">
        <!-- HEADER -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold">
                Career<span class="text-primary"> Match </span>
            </h1>
            <div class="w-16 h-[2px] bg-primary mx-auto mt-3"></div>
            <h2 class="text-xl font-semibold mt-5">⚙️ Admin Registration</h2>
            <p class="text-slate-400 text-sm mt-2">Create administrator account</p>
        </div>
        <form class="space-y-5">
            <div>
                <label class="text-sm text-slate-300"> Admin Name </label>
                <input type="text" placeholder="Enter admin name"
                    class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 focus:border-primary outline-none text-white" />
            </div>
            <div>
                <label class="text-sm text-slate-300"> Admin Email </label>
                <input type="email" placeholder="admin@email.com"
                    class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 focus:border-primary outline-none text-white" />
            </div>
            <div>
                <label class="text-sm text-slate-300"> Password </label>
                <input type="password" placeholder="Create password"
                    class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 focus:border-primary outline-none text-white" />
            </div>

            <!-- Admin Code -->
            <div>
                <label class="text-sm text-slate-300"> Admin Security Code </label>
                <input type="password" placeholder="Enter admin code"
                    class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 focus:border-primary outline-none text-white" />
            </div>
            <!-- Permission -->
            <div>
                <label class="text-sm text-slate-300"> Admin Permission Level </label>
                <select
                    class="w-full mt-2 px-4 py-3 rounded-xl bg-slate-800 border border-cyan-900 focus:border-primary outline-none">
                    <option>Admin</option>
                </select>
            </div>
            <!-- Button -->
            <button
                class="w-full bg-primary text-primaryDark font-semibold py-3 rounded-xl hover:bg-secondary transition">
                Create Admin Account
            </button>
        </form>
        <p class="text-center text-slate-400 text-sm mt-8">
            Already registered?
            <a href="login.php" class="text-primary hover:underline"> Login </a>
        </p>
    </div>
</body>

</html>
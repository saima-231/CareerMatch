<?php
session_start();
require_once 'db.php'; // Ensure this file connects $pdo

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $role = $_POST['role'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $allowed_roles = ['student', 'company', 'admin'];
    if (!in_array($role, $allowed_roles)) {
        $error = "Invalid role selected.";
    } else {
        $table = ($role === 'student') ? 'students' : 
                 (($role === 'company') ? 'companies' : 'admins');
        $emailCol = ($role === 'admin') ? 'username' : 'email';

        $stmt = $pdo->prepare("SELECT * FROM $table WHERE $emailCol = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['role'] = $role;
            if ($role === 'student') $_SESSION['student_id'] = $user['id'];
            if ($role === 'company') $_SESSION['company_id'] = $user['id'];
            if ($role === 'admin') $_SESSION['admin_id'] = $user['id'];

            $redirect = ($role === 'student') ? 'student/dashboard.php' :
                        (($role === 'company') ? 'company/dashboard.php' : 'admin/dashboard.php');
            header("Location: $redirect");
            exit;
        } else {
            $error = "Invalid credentials";
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>CareerMatch — Login</title>
<style>
:root {
  --primary-dark: #062342ff;
  --secondary-dark: #100e77ff;
  --accent-muted: #305669;
  --neutral-base: #CDE8E5;
}
* { box-sizing: border-box; }
body { font-family: Arial, sans-serif; background: var(--neutral-base); margin:0; padding:0; }
.container { max-width: 500px; margin:60px auto; padding:24px; background:white; border-radius:12px; box-shadow:0 6px 18px rgba(0,0,0,0.06); }
.header { text-align:center; margin-bottom:20px; }
.header h1 { margin:0; font-size:2rem; }
.btn { padding:10px 16px; border-radius:8px; border:none; cursor:pointer; font-weight:600; text-decoration:none; display:inline-block; margin-right:5px; }
.btn-primary { background: var(--primary-dark); color:white; }
.btn-primary:hover { background: var(--secondary-dark); }
.btn-secondary { background: var(--secondary-dark); color:white; }
.btn-secondary:hover { background: var(--primary-dark); }
.btn-ghost { background:transparent; border:1px solid var(--accent-muted); color: var(--primary-dark); }
.form-row { margin-bottom:12px; }
.input, select { width:100%; padding:10px; border-radius:8px; border:1px solid var(--accent-muted); outline:none; }
.input:focus, select:focus { border-color: var(--secondary-dark); box-shadow:0 0 0 3px rgba(49,48,77,0.08); }
.card { padding:16px; border-radius:10px; border:1px solid var(--accent-muted); background:linear-gradient(180deg, rgba(240,236,229,0.6), #fff); margin-bottom:12px; }
.error-msg { border-left:4px solid #e74c3c; color:#e74c3c; margin-top:12px; padding:10px; }
.register-links { text-align:center; margin-top:12px; }
.register-links a { text-decoration:none; margin:0 5px; padding:6px 12px; border-radius:6px; border:1px solid var(--secondary-dark); color: var(--primary-dark); }
.register-links a:hover { background: var(--secondary-dark); color:white; }
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1><span style="color:#091d3e !important;">CareerMatch</span></h1>
        <h4><span style="color:#183B4E !important;">Connecting Talent With Opportunity</span></h4>
    </div>

    <?php if(!empty($error)): ?>
    <div class="card error-msg"><?=htmlspecialchars($error)?></div>
    <?php endif; ?>

    <div class="card">
        <h3>Login</h3>
        <form method="post">
            <div class="form-row">
                <label>Role</label>
                <select name="role" class="input" required>
                    <option value="">--Select Role--</option>
                    <option value="student">Student</option>
                    <option value="company">Company</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-row">
                <label>Email / Username</label>
                <input type="text" name="email" class="input" required>
            </div>
            <div class="form-row">
                <label>Password</label>
                <input type="password" name="password" class="input" required>
            </div>
            <div class="form-row">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>

    <div class="register-links">
        <a href="register_student.php">Register as Student</a>
        <a href="register_company.php">Register as Company</a>
    </div>
</div>
</body>
</html>

<?php
session_start();
require_once 'db.php';
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $course = trim($_POST['course'] ?? '');

    if ($name && $email && $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare('INSERT INTO students (name, email, password, course) VALUES (?, ?, ?, ?)');
        try {
            $stmt->execute([$name, $email, $hash, $course]);
            header('Location: index.php');
            exit;
        } catch (Exception $e) {
            $error = "Registration failed: " . $e->getMessage();
        }
    } else {
        $error = "Please fill all required fields.";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Student Registration</title>
<style>
:root {
  --primary-dark: #161a30; --secondary-dark: #31304d; --accent-muted: #b6bbc4; --neutral-base: #f0ece5;
}
* { box-sizing: border-box; }
body { font-family: Arial,sans-serif; background: var(--neutral-base); color: var(--primary-dark); margin:0; padding:0; }
.container { max-width:500px; margin:60px auto; padding:24px; background:white; border-radius:12px; box-shadow:0 6px 18px rgba(0,0,0,0.06); }
.header { text-align:center; margin-bottom:20px; }
.header h1 { margin:0; font-size:2rem; }
.form-row { margin-bottom:12px; }
.input { width:100%; padding:10px; border-radius:8px; border:1px solid var(--accent-muted); }
.input:focus { border-color: var(--secondary-dark); box-shadow:0 0 0 3px rgba(49,48,77,0.08); }
.btn { padding:10px 16px; border-radius:8px; border:none; cursor:pointer; font-weight:600; }
.btn-primary { background: var(--primary-dark); color:white; }
.btn-primary:hover { background: var(--secondary-dark); }
.card { padding:16px; border-radius:10px; border:1px solid var(--accent-muted); background:linear-gradient(180deg, rgba(240,236,229,0.6), #fff); }
.error-msg { border-left:4px solid #e74c3c; color:#e74c3c; margin-bottom:12px; padding-left:8px; }
</style>
</head>
<body>
<div class="container">
    <div class="header"><h1>Student Registration</h1></div>
    <?php if(!empty($error)): ?>
        <div class="card error-msg"><?=htmlspecialchars($error)?></div>
    <?php endif; ?>
    <form method="post" class="card">
        <div class="form-row"><label>Name</label><input class="input" name="name" required></div>
        <div class="form-row"><label>Email</label><input type="email" class="input" name="email" required></div>
        <div class="form-row"><label>Password</label><input type="password" class="input" name="password" required></div>
        <div class="form-row"><label>Course</label><input class="input" name="course"></div>
        <div class="form-row"><button class="btn btn-primary">Register</button></div>
        <div class="form-row"><a href="index.php" class="btn btn-primary" style="background:#555">Back to Login</a></div>
    </form>
</div>
</body>
</html>

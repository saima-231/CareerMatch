<?php
session_start();
require_once 'db.php';

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
<title>CareerMatch</title>

<style>
:root{
  --primary:#091d3e;
  --secondary:#4545a7d6;
  --accent:#d3e0ee;
  --bg:#e1e4e7;
}

*{
  box-sizing:border-box;
}

body{
  margin:0;
  font-family:Arial, sans-serif;
  background:var(--bg);
  color:#222;
  line-height:1.6;
}

/* ===== HEADER ===== */
header{
  background:var(--primary);
  color:white;
  padding:50px 20px;
  text-align:center;
  box-shadow:0 6px 20px rgba(43, 41, 41, 0.15);
}

header h1{
  margin:0;
  font-size:clamp(2rem, 5vw, 2.8rem);
}

header p{
  margin-top:10px;
  font-size:clamp(1rem, 3vw, 1.2rem);
  opacity:0.9;
}

/* ===== SECTIONS ===== */
.section{
  max-width:1100px;
  margin:60px auto;
  padding:40px 30px;
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:40px;
  align-items:center;
  border:1.5px solid var(--primary);
  border-radius:22px;
  background:white;
  overflow:hidden; 
}

.section.reverse .text{
  order:2;
}

.text h2{
  color:var(--primary);
  font-size:clamp(1.4rem, 4vw, 2rem);
}

.text ul{
  padding-left:18px;
}

.text li{
  margin-bottom:10px;
  font-size:1rem;
}

/* ===== Section Border Enhancement ===== */
.section{
  border:1.5px solid var(--primary);
  border-radius:22px;
  background:white;
  padding:40px 30px;
}

.section:hover{
  box-shadow:0 12px 28px rgba(9,29,62,0.15);
  transition:0.3s ease;
}

/* ===== Feature Images ===== */
.feature-box{
  background:var(--accent);
  padding:30px;
  border-radius:18px;
  box-shadow:0 8px 20px rgba(59, 62, 65, 0.06);
}

.feature-subtitle{
  margin-top:8px;
  margin-bottom:16px;
  color:#555;
  font-size:0.95rem;
}

.section:not(:last-child){
  border-bottom:1px solid rgba(0,0,0,0.05);
  padding-bottom:60px;
}

/* ===== Image Wrapper ===== */
.image-box{
  display:flex;
  justify-content:center;
  align-items:center;
  padding: 10px;
}

/* Balance image size */
.feature-img{
  max-width:100%;
  display:block;
  transition:transform 0.4s ease, box-shadow 0.4s ease;
}

/* Center image on mobile */
@media (max-width:768px){
  .feature-img{
    margin:auto;
  }
}

/* ===== LOGIN ===== */
.login-area{
  max-width:420px;
  margin:70px auto;
  background:white;
  padding:26px;
  border-radius:14px;
  box-shadow:0 10px 25px rgba(0,0,0,0.1);
}

.login-area h3{
  text-align:center;
  margin-bottom:20px;
  color:var(--primary);
}

.form-row{
  margin-bottom:14px;
}

label{
  font-weight:bold;
  font-size:0.9rem;
  display:block;
  margin-bottom:4px;
}

.input, select{
  width:100%;
  padding:12px;
  border-radius:8px;
  border:1px solid #ccc;
  font-size:1rem;
}

button{
  width:100%;
  padding:14px;
  border:none;
  border-radius:8px;
  background:var(--primary);
  color:white;
  font-weight:bold;
  cursor:pointer;
  font-size:1rem;
}

button:hover{
  background:var(--secondary);
}

.error-msg{
  background:#fdecea;
  border-left:4px solid #e74c3c;
  padding:10px;
  margin-bottom:12px;
  color:#c0392b;
}

/* ===== Register Buttons ===== */
.register-links{
  text-align:center;
  margin-top:16px;
  display:flex;
  justify-content:center;
  gap:12px;
  flex-wrap:wrap;
}

.reg-btn{
  padding:10px 16px;
  border-radius:8px;
  border:2px solid var(--primary);
  color:var(--primary);
  text-decoration:none;
  font-weight:bold;
  transition:all 0.3s ease;
}

.reg-btn:hover{
  background:var(--primary);
  color:white;
  transform:translateY(-2px);
}

/* ===== FOOTER ===== */
footer{
  margin-top:80px;
  background:var(--primary);
  color:#bbb;
  text-align:center;
  padding:22px 15px;
  font-size:0.9rem;
}

footer span{
  color:white;
}

.section:hover{
  box-shadow:0 12px 28px rgba(9,29,62,0.15);
  transition:0.3s ease;
}

/* ===== RESPONSIVE BREAKPOINTS ===== */
/* Tablets */
@media (max-width: 900px){
  .section{
    gap:30px;
  }
}

/* Mobile */
@media (max-width: 768px){
  .section{
    grid-template-columns:1fr;
    text-align:center;
  }

  .section.reverse .text{
    order:0;
  }

  .text ul{
    text-align:left;
    display:inline-block;
  }

  header{
    padding:40px 16px;
  }
}

/* Small phones */
@media (max-width: 420px){
  .login-area{
    margin:40px 15px;
    padding:20px;
  }
  footer{
    font-size:0.8rem;
  }
}
</style>
</head>
<body>
<!-- ===== HEADER ===== -->
<header>
  <h1>CareerMatch</h1>
  <p>Connecting Talent With Opportunity</p>
</header>
<!-- ===== STUDENT ===== -->
<section class="section">
  <div class="text feature-box">
    <h2>For Students</h2>
    <p class="feature-subtitle">
      Start your career journey with real-world opportunities.
    </p>
    <ul>
      <li>Discover internships easily</li>
      <li>Apply in one click</li>
      <li>Track application status</li>
      <li>Build your career early</li>
    </ul>
    <a href="register_student.php" class="reg-btn">Register as Student</a>
  </div>

  <div class="image-box">
    <img src="assets/images/student.png" alt="Students" class="feature-img">
  </div>
</section>

<!-- ===== COMPANY ===== -->
<section class="section reverse">
  <div class="text feature-box">
    <h2>For Companies</h2>
    <p class="feature-subtitle">
      Find the right talent to grow your business.
    </p>
    <ul>
      <li>Post internships quickly</li>
      <li>Manage applicants efficiently</li>
      <li>Discover skilled students</li>
      <li>Grow your talent pipeline</li>
    </ul>
    <a href="register_company.php" class="reg-btn">Register as Company</a>
  </div>

  <div class="image-box">
    <img src="assets/images/company.png" alt="Companies" class="feature-img">
  </div>
</section>

<!-- ===== LOGIN ===== -->
<div class="login-area">
  <?php if(!empty($error)): ?>
  <div class="error-msg"><?=htmlspecialchars($error)?></div>
  <?php endif; ?>

  <h3>Login</h3>
  <form method="post">
    <div class="form-row">
      <label>Role</label>
      <select name="role" required>
        <option value="">-- Select Role --</option>
        <option value="student">Student</option>
        <option value="company">Company</option>
        <option value="admin">Admin</option>
      </select>
    </div>

    <div class="form-row">
      <label>Email / Username</label>
      <input class="input" name="email" required>
    </div>

    <div class="form-row">
      <label>Password</label>
      <input type="password" class="input" name="password" required>
    </div>

    <button type="submit">Login</button>
  </form>
</div>


<!-- ===== FOOTER ===== -->
<footer>
  © <?=date('Y')?> <span>CareerMatch</span> | Internship Management System
</footer>

</body>
</html>

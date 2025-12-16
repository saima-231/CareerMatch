<?php
session_start();
require_once '../db.php';

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

if (!isset($_SESSION['student_id'])) {
    header('Location: ../index.php');
    exit;
}

$student_id = $_SESSION['student_id'];

// Fetch student info
$stmt = $pdo->prepare("SELECT * FROM students WHERE id=?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

// Fetch student applications with internship & company info (only active internships)
$applications = $pdo->prepare("
    SELECT a.*, i.title AS internship_title, c.name AS company_name
    FROM applications a
    JOIN internships i ON a.internship_id = i.id
    JOIN companies c ON i.company_id = c.id
    WHERE a.student_id = ? 
    ORDER BY a.applied_at DESC
");
$applications->execute([$student_id]);
$apps = $applications->fetchAll();
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Student Dashboard</title>
<style>
body{font-family:Arial;background:#e1e4e7;margin:0;padding:0;}
.container{max-width:900px;margin:40px auto;padding:20px;}
.header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;}
.header h2{margin:0;}
.btn{padding:8px 14px;border-radius:8px;background:#091d3e;color:white;text-decoration:none;margin-left:6px;}
.btn:hover{background:#183B4E;}
.card{background:white;border-radius:14px;padding:20px;box-shadow:0 8px 20px rgba(0,0,0,.08);margin-bottom:20px;}
table{width:100%;border-collapse:collapse;margin-top:10px;}
th, td{padding:10px;border-bottom:1px solid #ccc;text-align:left;}
.status-Pending{color:#f39c12;font-weight:bold;}
.status-Accepted{color:#27ae60;font-weight:bold;}
.status-Rejected{color:#e74c3c;font-weight:bold;}
</style>
</head>
<body>

<div class="container">
<div class="header">
  <h2>Welcome, <?=htmlspecialchars($student['name'])?></h2>
  <div style="display:flex; gap:10px; flex-wrap:wrap;">
    <a href="view_profile.php" class="btn">My Profile</a>
    <a href="edit_profile.php" class="btn">Edit Profile</a>
    <a href="internships.php" class="btn">Browse Internships</a>
    <a href="../logout.php" class="btn">Logout</a>
  </div>
</div>

<div class="card">
<h3>Your Applications</h3>
<?php if(count($apps) === 0): ?>
<p>You have not applied to any internships yet.</p>
<?php else: ?>
<table>
<tr>
<th>Internship</th>
<th>Company</th>
<th>Status</th>
<th>Applied At</th>
</tr>
<?php foreach($apps as $a): ?>
<tr>
<td><?=htmlspecialchars($a['internship_title'])?></td>
<td><?=htmlspecialchars($a['company_name'])?></td>
<td class="status-<?=$a['status']?>"><?=htmlspecialchars($a['status'])?></td>
<td><?=htmlspecialchars($a['applied_at'])?></td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>
</div>

<!-- Danger Zone: Delete Account -->
<div class="card" style="border-left:4px solid #e74c3c;">
  <h3 style="color:#e74c3c;">Danger Zone</h3>
  <a href="../delete_account.php"
     onclick="return confirm('This will permanently delete your account. Continue?')"
     style="color:#e74c3c;font-weight:bold;text-decoration:none;">
     Delete My Account
  </a>
  <p style="font-size:0.9rem;color:#666;margin-top:6px;">
    This action cannot be undone.
  </p>
</div>

</div>
</body>
</html>

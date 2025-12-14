<?php
session_start();
require_once '../db.php';

if(!isset($_SESSION['student_id'])){
    header('Location: ../index.php');
    exit;
}

$student_id = $_SESSION['student_id'];

// Fetch student info
$stmt = $pdo->prepare("SELECT * FROM students WHERE id=?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

// Fetch student applications with internship & company info
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
.header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;}
.header h2{margin:0;}
.btn{padding:8px 14px;border-radius:8px;background:#091d3e;color:white;text-decoration:none;}
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
<a href="../logout.php" class="btn">Logout</a>
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

</div>
</body>
</html>

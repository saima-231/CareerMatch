<?php
session_start();
require_once '../db.php';

if(!isset($_SESSION['role']) || $_SESSION['role']!=='admin'){
    header('Location: ../index.php');
    exit;
}

// Get basic counts
$students = $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
$companies = $pdo->query('SELECT COUNT(*) FROM companies')->fetchColumn();
$internships = $pdo->query('SELECT COUNT(*) FROM internships')->fetchColumn();
$apps = $pdo->query('SELECT COUNT(*) FROM applications')->fetchColumn();

// Get recent applications
$recent_apps = $pdo->query('
    SELECT a.*, s.name as student_name, i.title as internship_title
    FROM applications a
    JOIN students s ON a.student_id = s.id
    JOIN internships i ON a.internship_id = i.id
    ORDER BY a.applied_at DESC
    LIMIT 10
')->fetchAll();
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin Dashboard</title>
<style>
body{font-family:Arial;background:#f0ece5;margin:0;padding:0;}
.container{max-width:900px;margin:40px auto;background:#fff;padding:20px;border-radius:10px;box-shadow:0 4px 10px rgba(0,0,0,0.1);}
.header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;}
.header h2{margin:0;}
.btn{padding:8px 14px;border:none;border-radius:6px;background:#161a30;color:#fff;text-decoration:none;}
.btn:hover{background:#31304d;}
.card{padding:16px;border-radius:10px;border:1px solid #b6bbc4;background:#fff;margin-bottom:12px;}
.table{width:100%;border-collapse:collapse;}
.table th, .table td{padding:10px;border-bottom:1px solid #eee;text-align:left;}
.small{font-size:0.9rem;color:#31304d;}
.badge{padding:4px 8px;border-radius:6px;font-weight:600;}
.badge-pending{background:#fff3cd;color:#856404;}
.badge-accepted{background:#d4edda;color:#155724;}
.badge-rejected{background:#f8d7da;color:#721c24;}
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin-bottom:20px;}
</style>
</head>
<body>
<div class="container">
<div class="header">
<h2>Admin Dashboard</h2>
<a href="../logout.php" class="btn">Logout</a>
</div>

<div class="stats-grid">
<div class="card"><h4>Students</h4><div class="small"><?= $students ?></div></div>
<div class="card"><h4>Companies</h4><div class="small"><?= $companies ?></div></div>
<div class="card"><h4>Internships</h4><div class="small"><?= $internships ?></div></div>
<div class="card"><h4>Applications</h4><div class="small"><?= $apps ?></div></div>
</div>

<h3>Recent Applications</h3>
<div class="card">
<table class="table">
<thead><tr><th>Student</th><th>Internship</th><th>Status</th><th>Applied At</th></tr></thead>
<tbody>
<?php foreach($recent_apps as $r): ?>
<tr>
<td><?=htmlspecialchars($r['student_name'])?></td>
<td><?=htmlspecialchars($r['internship_title'])?></td>
<td>
<?php if($r['status']=='Pending'): ?><span class="badge badge-pending">Pending</span>
<?php elseif($r['status']=='Accepted'): ?><span class="badge badge-accepted">Accepted</span>
<?php else: ?><span class="badge badge-rejected">Rejected</span><?php endif; ?>
</td>
<td class="small"><?=htmlspecialchars($r['applied_at'])?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</div>
</body>
</html>

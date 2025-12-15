<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

require_once '../db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}


// Get counts
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
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<style>
:root{
  --primary:#091d3e;
  --secondary:#4545a7d6;
  --accent:#d3e0ee;
  --bg:#e1e4e7;
}
body{margin:0;font-family:Arial;background:var(--bg);color:#222;}
header{background:var(--primary);color:white;padding:30px;text-align:center;box-shadow:0 6px 20px rgba(0,0,0,0.15);}
header h1{margin:0;font-size:2rem;}
.container{max-width:1100px;margin:40px auto;padding:0 20px;}
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-bottom:30px;}
.card{background:white;padding:20px;border-radius:12px;box-shadow:0 8px 20px rgba(0,0,0,0.08);}
.card h3{margin:0;margin-bottom:10px;color:var(--primary);}
.table{width:100%;border-collapse:collapse;}
.table th, .table td{padding:10px;border-bottom:1px solid #eee;text-align:left;}
.badge{padding:4px 8px;border-radius:6px;font-weight:600;}
.badge-pending{background:#fff3cd;color:#856404;}
.badge-accepted{background:#d4edda;color:#155724;}
.badge-rejected{background:#f8d7da;color:#721c24;}
.btn-logout{padding:8px 14px;border:none;border-radius:6px;background:var(--primary);color:white;text-decoration:none;}
.btn-logout:hover{background:var(--secondary);}
@media(max-width:768px){.stats-grid{grid-template-columns:1fr;}}
</style>
</head>
<body>
<header>
<h1>Admin Dashboard</h1>
<a href="../logout.php" class="btn-logout">Logout</a>
</header>

<div class="container">
<div class="stats-grid">
  <div class="card"><h3>Students</h3><p><?=$students?></p></div>
  <div class="card"><h3>Companies</h3><p><?=$companies?></p></div>
  <div class="card"><h3>Internships</h3><p><?=$internships?></p></div>
  <div class="card"><h3>Applications</h3><p><?=$apps?></p></div>
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
<?php else:?><span class="badge badge-rejected">Rejected</span><?php endif;?>
</td>
<td><?=htmlspecialchars($r['applied_at'])?></td>
</tr>
<?php endforeach;?>
</tbody>
</table>
</div>
</div>
</body>
</html>

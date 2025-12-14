<?php
session_start();
require_once '../db.php';

if(!isset($_SESSION['role']) || $_SESSION['role']!=='student'){
    header('Location: ../index.php');
    exit;
}

$student_id = $_SESSION['student_id'];
$applied_internships = $pdo->prepare('
  SELECT i.title, i.company_id, a.status, a.applied_at 
  FROM applications a
  JOIN internships i ON a.internship_id=i.id
  WHERE a.student_id = ?
  ORDER BY a.applied_at DESC
');
$applied_internships->execute([$student_id]);
$apps = $applied_internships->fetchAll();
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Dashboard</title>
<style>
:root{--primary:#091d3e;--secondary:#4545a7d6;--bg:#e1e4e7;}
body{margin:0;font-family:Arial;background:var(--bg);color:#222;}
header{background:var(--primary);color:white;padding:30px;text-align:center;box-shadow:0 6px 20px rgba(0,0,0,0.15);}
header h1{margin:0;font-size:2rem;}
header a{color:white;text-decoration:none;padding:8px 14px;background:#222;border-radius:6px;margin-left:20px;}
header a:hover{background:#333;}
.container{max-width:900px;margin:40px auto;padding:0 20px;}
.card{background:white;padding:20px;border-radius:12px;box-shadow:0 8px 20px rgba(0,0,0,0.08);margin-bottom:20px;}
.table{width:100%;border-collapse:collapse;}
.table th, .table td{padding:10px;border-bottom:1px solid #eee;text-align:left;}
.badge{padding:4px 8px;border-radius:6px;font-weight:600;}
.badge-pending{background:#fff3cd;color:#856404;}
.badge-accepted{background:#d4edda;color:#155724;}
.badge-rejected{background:#f8d7da;color:#721c24;}
button{padding:10px 16px;border:none;border-radius:6px;background:var(--primary);color:white;cursor:pointer;}
button:hover{background:var(--secondary);}
@media(max-width:768px){.table, .table th, .table td{font-size:0.9rem;}}
</style>
</head>
<body>
<header>
<h1>Student Dashboard</h1>
<a href="../logout.php">Logout</a>
</header>

<div class="container">
<div class="card">
<h3>Your Applications</h3>
<?php if(!$apps): ?> <p>You haven’t applied to any internship yet.</p> <?php else: ?>
<table class="table">
<thead><tr><th>Internship</th><th>Status</th><th>Applied At</th></tr></thead>
<tbody>
<?php foreach($apps as $a): ?>
<tr>
<td><?=htmlspecialchars($a['title'])?></td>
<td>
<?php if($a['status']=='Pending'):?><span class="badge badge-pending">Pending</span>
<?php elseif($a['status']=='Accepted'):?><span class="badge badge-accepted">Accepted</span>
<?php else:?><span class="badge badge-rejected">Rejected</span><?php endif;?>
</td>
<td><?=htmlspecialchars($a['applied_at'])?></td>
</tr>
<?php endforeach;?>
</tbody>
</table>
<?php endif;?>
<button onclick="window.location.href='internships.php'">Browse Internships</button>
</div>
</div>
</body>
</html>

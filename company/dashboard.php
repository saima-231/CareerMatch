<?php
session_start();
require_once '../db.php';

if(!isset($_SESSION['role']) || $_SESSION['role']!=='company'){
    header('Location: ../index.php');
    exit;
}

$company_id = $_SESSION['company_id'];

// Get internships
$stmt = $pdo->prepare('SELECT * FROM internships WHERE company_id=?');
$stmt->execute([$company_id]);
$internships = $stmt->fetchAll();
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Company Dashboard</title>
<style>
:root{--primary:#091d3e;--secondary:#183B4E;--bg:#f4f6f8;}
body{margin:0;font-family:Arial;background:var(--bg);color:#222;}
header{background:linear-gradient(135deg,var(--primary),var(--secondary));color:white;padding:30px;text-align:center;box-shadow:0 6px 20px rgba(0,0,0,0.15);}
header h1{margin:0;font-size:2rem;}
header a{color:white;text-decoration:none;padding:8px 14px;background:#222;border-radius:6px;margin-left:20px;}
header a:hover{background:#333;}
.container{max-width:900px;margin:40px auto;padding:0 20px;}
.card{background:white;padding:20px;border-radius:12px;box-shadow:0 8px 20px rgba(0,0,0,0.08);margin-bottom:20px;}
.table{width:100%;border-collapse:collapse;}
.table th, .table td{padding:10px;border-bottom:1px solid #eee;text-align:left;}
button{padding:10px 16px;border:none;border-radius:6px;background:var(--primary);color:white;cursor:pointer;margin-right:6px;}
button:hover{background:var(--secondary);}
@media(max-width:768px){.table, .table th, .table td{font-size:0.9rem;}}
</style>
</head>
<body>
<header>
<h1>Company Dashboard</h1>
<a href="../logout.php">Logout</a>
</header>

<div class="container">
<div class="card">
<h3>Your Internships</h3>
<?php if(!$internships):?><p>You haven’t posted any internship yet.</p><?php else:?>
<table class="table">
<thead><tr><th>Title</th><th>Applicants</th><th>Actions</th></tr></thead>
<tbody>
<?php foreach($internships as $i): 
  $count = $pdo->prepare('SELECT COUNT(*) FROM applications WHERE internship_id=?');
  $count->execute([$i['id']]);
  $num = $count->fetchColumn();
?>
<tr>
<td><?=htmlspecialchars($i['title'])?></td>
<td><?=$num?></td>
<td>
<button onclick="window.location.href='edit_internship.php?id=<?=$i['id']?>'">Edit</button>
<button onclick="window.location.href='view_application.php?id=<?=$i['id']?>'">View Applications</button>
</td>
</tr>
<?php endforeach;?>
</tbody>
</table>
<?php endif;?>
<button onclick="window.location.href='post_internship.php'">Post New Internship</button>
</div>
</div>
</body>
</html>

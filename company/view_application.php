<?php
session_start();
require_once '../db.php';
if(!isset($_SESSION['role']) || $_SESSION['role']!=='company') header('Location: ../index.php');

$company_id = $_SESSION['company_id'];
$internship_id = (int)($_GET['id'] ?? 0);

// Ensure the internship belongs to company
$s = $pdo->prepare('SELECT * FROM internships WHERE id=? AND company_id=?'); 
$s->execute([$internship_id,$company_id]); 
$internship = $s->fetch(); 
if(!$internship) header('Location: dashboard.php');

// Handle status update
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['app_id'],$_POST['status'])){
    $stmt = $pdo->prepare('UPDATE applications SET status=? WHERE id=?');
    $stmt->execute([$_POST['status'], (int)$_POST['app_id']]);
}

// Fetch applications
$stmt = $pdo->prepare('SELECT a.*, s.name, s.email FROM applications a JOIN students s ON a.student_id=s.id WHERE a.internship_id=?');
$stmt->execute([$internship_id]);
$applications = $stmt->fetchAll();
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Applications</title>
<style>
body{font-family:Arial;background:#f0ece5;margin:0;padding:0;}
.container{max-width:800px;margin:50px auto;background:#fff;padding:20px;border-radius:10px;box-shadow:0 4px 10px rgba(0,0,0,0.1);}
table{width:100%;border-collapse:collapse;}
th, td{border-bottom:1px solid #eee;padding:10px;text-align:left;}
input, select{padding:6px;border-radius:6px;border:1px solid #b6bbc4;}
button{padding:6px 10px;border:none;border-radius:6px;background:#31304d;color:#fff;cursor:pointer;}
button:hover{background:#161a30;}
a{color:#161a30;text-decoration:none;}
</style>
</head>
<body>
<div class="container">
<h2>Applications — <?=htmlspecialchars($internship['title'])?></h2>
<table>
<thead><tr><th>Student</th><th>Email</th><th>Status</th><th>Action</th></tr></thead>
<tbody>
<?php foreach($applications as $a): ?>
<tr>
<td><?=htmlspecialchars($a['name'])?></td>
<td><?=htmlspecialchars($a['email'])?></td>
<td><?=htmlspecialchars($a['status'])?></td>
<td>
<form method="post">
<input type="hidden" name="app_id" value="<?=$a['id']?>">
<select name="status">
<option <?= $a['status']=='Pending'?'selected':''?>>Pending</option>
<option <?= $a['status']=='Accepted'?'selected':''?>>Accepted</option>
<option <?= $a['status']=='Rejected'?'selected':''?>>Rejected</option>
</select>
<button>Update</button>
</form>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<p><a href="dashboard.php">Back to Dashboard</a></p>
</div>
</body>
</html>

<?php
session_start();
require_once '../db.php';

if(!isset($_SESSION['company_id'])){
    header('Location: ../index.php');
    exit;
}

$company_id = $_SESSION['company_id'];

// Fetch company info
$stmt = $pdo->prepare("SELECT * FROM companies WHERE id=?");
$stmt->execute([$company_id]);
$company = $stmt->fetch();

// Fetch internships posted by the company
$internships = $pdo->prepare("
    SELECT i.*, 
           (SELECT COUNT(*) FROM applications a WHERE a.internship_id=i.id) AS total_applications,
           (SELECT COUNT(*) FROM applications a WHERE a.internship_id=i.id AND a.status='Accepted') AS accepted_applications,
           (SELECT COUNT(*) FROM applications a WHERE a.internship_id=i.id AND a.status='Pending') AS pending_applications
    FROM internships i
    WHERE i.company_id=?
    ORDER BY i.created_at DESC
");
$internships->execute([$company_id]);
$interns = $internships->fetchAll();
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Company Dashboard</title>
<style>
body{font-family:Arial;background:#e1e4e7;margin:0;padding:0;}
.container{max-width:1000px;margin:40px auto;padding:20px;}
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
<h2>Welcome, <?=htmlspecialchars($company['name'])?></h2>
<a href="../logout.php" class="btn">Logout</a>
</div>

<div class="card">
<h3>Your Internships</h3>
<?php if(count($interns)===0): ?>
<p>You haven't posted any internships yet. <a href="post_internship.php">Post one now</a>.</p>
<?php else: ?>
<table>
<tr>
<th>Title</th>
<th>Duration</th>
<th>Total Applications</th>
<th>Pending</th>
<th>Accepted</th>
<th>Actions</th>
</tr>
<?php foreach($interns as $i): ?>
<tr>
<td><?=htmlspecialchars($i['title'])?></td>
<td><?=htmlspecialchars($i['duration'])?></td>
<td><?=$i['total_applications']?></td>
<td class="status-Pending"><?=$i['pending_applications']?></td>
<td class="status-Accepted"><?=$i['accepted_applications']?></td>
<td>
<a href="view_application.php?id=<?=$i['id']?>" class="btn">View Applications</a>
<a href="edit_internship.php?id=<?=$i['id']?>" class="btn">Edit</a>
</td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>
</div>

</div>
</body>
</html>

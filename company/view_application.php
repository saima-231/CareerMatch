<?php
session_start();
require_once '../db.php';

if(!isset($_SESSION['company_id'])){
    header('Location: ../index.php');
    exit;
}

$company_id = $_SESSION['company_id'];

// Fetch applications for company internships
$stmt = $pdo->prepare("
    SELECT a.id AS app_id, s.name AS student_name, s.email, s.course, s.skills, s.linkedin_link, i.title AS internship_title, a.status
    FROM applications a
    JOIN students s ON a.student_id = s.id
    JOIN internships i ON a.internship_id = i.id
    WHERE i.company_id=?
    ORDER BY a.applied_at DESC
");
$stmt->execute([$company_id]);
$applications = $stmt->fetchAll();

// Handle status update
if(isset($_GET['action'], $_GET['id'])){
    $action = $_GET['action'];
    $app_id = $_GET['id'];

    if(in_array($action, ['Accepted','Rejected'])){
        $stmt = $pdo->prepare("UPDATE applications SET status=? WHERE id=?");
        $stmt->execute([$action, $app_id]);
        header("Location: view_application.php");
        exit;
    }
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Applications</title>
<style>
body{font-family:Arial;background:#f4f6f8;margin:0;padding:0;}
.container{max-width:900px;margin:40px auto;padding:20px;}
.table{width:100%;border-collapse:collapse;background:white;box-shadow:0 6px 20px rgba(0,0,0,.08);}
.table th, .table td{padding:10px;border-bottom:1px solid #eee;text-align:left;}
.btn{padding:6px 12px;border-radius:6px;text-decoration:none;color:white;}
.accept{background:#28a745;}
.reject{background:#e74c3c;}
.accept:hover{background:#218838;}
.reject:hover{background:#c82333;}
.header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;}
</style>
</head>
<body>
<div class="container">
<div class="header">
<h2>Applications</h2>
<a href="dashboard.php" class="btn" style="background:#091d3e;">Dashboard</a>
</div>

<table class="table">
<thead>
<tr>
<th>Student</th>
<th>Email</th>
<th>Course</th>
<th>Skills</th>
<th>LinkedIn</th>
<th>Internship</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php foreach($applications as $a): ?>
<tr>
<td><?=htmlspecialchars($a['student_name'])?></td>
<td><?=htmlspecialchars($a['email'])?></td>
<td><?=htmlspecialchars($a['course'])?></td>
<td><?=htmlspecialchars($a['skills'])?></td>
<td><a href="<?=htmlspecialchars($a['linkedin_link'])?>" target="_blank">Link</a></td>
<td><?=htmlspecialchars($a['internship_title'])?></td>
<td><?=htmlspecialchars($a['status'])?></td>
<td>
<a class="btn accept" href="?action=Accepted&id=<?=$a['app_id']?>">Accept</a>
<a class="btn reject" href="?action=Rejected&id=<?=$a['app_id']?>">Reject</a>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

</div>
</body>
</html>

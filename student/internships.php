<?php
session_start();
require_once '../db.php';

if(!isset($_SESSION['student_id'])){
    header('Location: ../index.php');
    exit;
}

$student_id = $_SESSION['student_id'];

// Fetch all internships
$internships = $pdo->query("
    SELECT i.*, c.name AS company_name
    FROM internships i
    JOIN companies c ON i.company_id = c.id
    ORDER BY i.created_at DESC
")->fetchAll();

// Fetch student applications to disable already applied internships
$stmt = $pdo->prepare("SELECT internship_id FROM applications WHERE student_id=?");
$stmt->execute([$student_id]);
$applied_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Available Internships</title>
<style>
body{font-family:Arial;background:#e1e4e7;margin:0;}
.container{max-width:1100px;margin:40px auto;padding:20px;}
.card{background:white;border-radius:14px;padding:20px;box-shadow:0 8px 20px rgba(0,0,0,.08);margin-bottom:20px;display:grid;grid-template-columns:120px 1fr;gap:20px;}
img{width:100px}
.btn{padding:8px 14px;border-radius:8px;background:#091d3e;color:white;text-decoration:none;}
.btn:hover{background:#183B4E;}
.btn.disabled{background:#ccc;cursor:not-allowed;}
.header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;}
</style>
</head>
<body>
<div class="container">
<div class="header">
<h2>Internships</h2>
<a href="dashboard.php" class="btn">Dashboard</a>
</div>

<?php foreach($internships as $i): ?>
<div class="card">
<img src="../assets/images/internship.png" alt="Internship">
<div>
<h3><?=htmlspecialchars($i['title'])?></h3>
<p><b>Company:</b> <?=htmlspecialchars($i['company_name'])?></p>
<p><?=htmlspecialchars($i['description'])?></p>

<?php if(in_array($i['id'], $applied_ids)): ?>
<a class="btn disabled">Applied</a>
<?php else: ?>
<a class="btn" href="apply.php?id=<?=$i['id']?>">Apply</a>
<?php endif; ?>
</div>
</div>
<?php endforeach; ?>

</div>
</body>
</html>

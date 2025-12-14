<?php
session_start();
require_once '../db.php';

if(!isset($_SESSION['student_id'])){
    header('Location: ../index.php');
    exit;
}

$internships = $pdo->query("
    SELECT internships.*, companies.name AS company_name
    FROM internships
    JOIN companies ON internships.company_id = companies.id
    ORDER BY internships.created_at DESC
")->fetchAll();
?>

<!doctype html>
<html>
<head>
<title>Available Internships</title>
<style>
body{font-family:Arial;background:#f4f6f8;margin:0}
.container{max-width:1100px;margin:40px auto;padding:20px}
.card{background:white;border-radius:14px;padding:20px;box-shadow:0 8px 20px rgba(0,0,0,.08);margin-bottom:20px;display:grid;grid-template-columns:120px 1fr;gap:20px}
img{width:100px}
.btn{padding:8px 14px;border-radius:8px;background:#091d3e;color:white;text-decoration:none}
.btn:hover{background:#183B4E}
.header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px}
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
<img src="../assets/images/internship.png">
<div>
<h3><?=htmlspecialchars($i['title'])?></h3>
<p><b>Company:</b> <?=htmlspecialchars($i['company_name'])?></p>
<p><?=htmlspecialchars($i['description'])?></p>
<a class="btn" href="apply.php?id=<?=$i['id']?>">Apply</a>
</div>
</div>
<?php endforeach; ?>

</div>
</body>
</html>

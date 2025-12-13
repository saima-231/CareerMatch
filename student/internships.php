<?php
session_start();
require_once '../db.php';

if(!isset($_SESSION['role']) || $_SESSION['role']!=='student'){
    header('Location: ../index.php');
    exit;
}

$student_id = $_SESSION['student_id'];

// Fetch all internships
$stmt = $pdo->query('SELECT i.*, c.name as company_name FROM internships i JOIN companies c ON i.company_id=c.id ORDER BY i.created_at DESC');
$internships = $stmt->fetchAll();

// Fetch internships the student already applied for
$sstmt = $pdo->prepare('SELECT internship_id FROM applications WHERE student_id=?');
$sstmt->execute([$student_id]);
$applied = $sstmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Available Internships</title>
<style>
body{font-family:Arial;background:#f0ece5;margin:0;padding:0;}
.container{max-width:800px;margin:40px auto;background:#fff;padding:20px;border-radius:10px;box-shadow:0 4px 10px rgba(0,0,0,0.1);}
.card{padding:16px;border-radius:10px;border:1px solid #b6bbc4;background:#fff;margin-bottom:12px;}
button{padding:8px 14px;border:none;border-radius:6px;background:#161a30;color:#fff;cursor:pointer;}
button:hover{background:#31304d;}
button[disabled]{background:#b6bbc4;cursor:not-allowed;}
.small{font-size:0.9rem;color:#31304d;}
a{color:#161a30;text-decoration:none;}
form{display:inline-block;margin:0;}
</style>
</head>
<body>
<div class="container">
<h2>Available Internships</h2>
<p><a href="dashboard.php">Back to Dashboard</a></p>

<?php foreach($internships as $i): ?>
<div class="card">
<h3><?=htmlspecialchars($i['title'])?></h3>
<div class="small">Company: <?=htmlspecialchars($i['company_name'])?> — Duration: <?=htmlspecialchars($i['duration'])?></div>
<p><?=nl2br(htmlspecialchars($i['description']))?></p>

<?php if(in_array($i['id'],$applied)): ?>
<button disabled>Already Applied</button>
<?php else: ?>
<form method="post" action="apply.php">
<input type="hidden" name="internship_id" value="<?=$i['id']?>">
<button>Apply</button>
</form>
<?php endif; ?>
</div>
<?php endforeach; ?>

</div>
</body>
</html>

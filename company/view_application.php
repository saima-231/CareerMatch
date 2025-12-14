<?php
session_start();
require_once '../db.php';

if(!isset($_SESSION['company_id'])){
    header('Location: ../index.php');
    exit;
}

$internship_id = $_GET['id'] ?? 0;

/* Handle status update */
if(isset($_GET['action'], $_GET['app_id'])){
    $status = ($_GET['action'] === 'accept') ? 'Accepted' : 'Rejected';
    $stmt = $pdo->prepare("UPDATE applications SET status=? WHERE id=?");
    $stmt->execute([$status, $_GET['app_id']]);
    header("Location: view_application.php?id=".$internship_id);
    exit;
}

/* Fetch applications */
$stmt = $pdo->prepare("
SELECT a.id, a.status, s.name, s.email
FROM applications a
JOIN students s ON a.student_id = s.id
WHERE a.internship_id = ?
");
$stmt->execute([$internship_id]);
$apps = $stmt->fetchAll();
?>

<!doctype html>
<html>
<head>
<title>Applications</title>
<style>
body{font-family:Arial;background:#f4f6f8}
.container{max-width:900px;margin:40px auto}
.card{background:white;padding:16px;border-radius:12px;margin-bottom:12px;display:flex;justify-content:space-between;align-items:center}
.btn{padding:6px 12px;border-radius:6px;text-decoration:none;color:white;font-size:0.9rem}
.accept{background:#28a745}
.reject{background:#dc3545}
.status{font-weight:bold}
</style>
</head>
<body>

<div class="container">
<h2>Applications</h2>

<?php foreach($apps as $a): ?>
<div class="card">
<div>
<b><?=htmlspecialchars($a['name'])?></b><br>
<small><?=htmlspecialchars($a['email'])?></small><br>
<span class="status"><?= $a['status'] ?></span>
</div>

<?php if($a['status']=='Pending'): ?>
<div>
<a class="btn accept" href="?id=<?=$internship_id?>&action=accept&app_id=<?=$a['id']?>">Accept</a>
<a class="btn reject" href="?id=<?=$internship_id?>&action=reject&app_id=<?=$a['id']?>">Reject</a>
</div>
<?php endif; ?>

</div>
<?php endforeach; ?>

</div>
</body>
</html>

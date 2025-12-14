<?php
session_start();
require_once '../db.php';

$id=$_GET['id'];
$stmt=$pdo->prepare("SELECT * FROM internships WHERE id=? AND company_id=?");
$stmt->execute([$id,$_SESSION['company_id']]);
$i=$stmt->fetch();

if($_SERVER['REQUEST_METHOD']=='POST'){
    $stmt=$pdo->prepare("UPDATE internships SET title=?,description=?,duration=? WHERE id=?");
    $stmt->execute([$_POST['title'],$_POST['description'],$_POST['duration'],$id]);
    header('Location: dashboard.php');
    exit;
}
?>

<!doctype html>
<html>
<head>
<title>Edit Internship</title>
<style>
body{font-family:Arial;background:#e1e4e7}
.container{max-width:600px;margin:50px auto;background:white;padding:24px;border-radius:14px}
input,textarea{width:100%;padding:10px;margin-bottom:12px}
button{background:#091d3e;color:white;padding:12px;border:none;border-radius:8px}
</style>
</head>
<body>

<div class="container">
<h2>Edit Internship</h2>
<form method="post">
<input name="title" value="<?=$i['title']?>" required>
<input name="duration" value="<?=$i['duration']?>">
<textarea name="description"><?=$i['description']?></textarea>
<button>Update</button>
</form>
</div>

</body>
</html>

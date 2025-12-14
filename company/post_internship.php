<?php
session_start();
require_once '../db.php';

if(!isset($_SESSION['company_id'])){
    header('Location: ../index.php');
    exit;
}

if($_SERVER['REQUEST_METHOD']=='POST'){
    $stmt=$pdo->prepare("INSERT INTO internships(title,description,duration,company_id) VALUES(?,?,?,?)");
    $stmt->execute([$_POST['title'],$_POST['description'],$_POST['duration'],$_SESSION['company_id']]);
    header('Location: dashboard.php');
    exit;
}
?>

<!doctype html>
<html>
<head>
<title>Post Internship</title>
<style>
body{font-family:Arial;background:#f4f6f8}
.container{max-width:600px;margin:50px auto;background:white;padding:24px;border-radius:14px;box-shadow:0 10px 25px rgba(0,0,0,.1)}
input,textarea{width:100%;padding:10px;margin-bottom:12px;border-radius:8px;border:1px solid #ccc}
button{background:#091d3e;color:white;border:none;padding:12px;border-radius:8px;width:100%}
button:hover{background:#183B4E}
img{display:block;margin:auto;width:120px}
</style>
</head>
<body>

<div class="container">
<img src="../assets/images/company.png">
<h2>Post Internship</h2>

<form method="post">
<input name="title" placeholder="Internship Title" required>
<textarea name="description" placeholder="Description" required></textarea>
<input name="duration" placeholder="Duration">
<button>Post Internship</button>
</form>
</div>

</body>
</html>

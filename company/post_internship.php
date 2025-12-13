<?php
session_start();
require_once '../db.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'company') {
    header('Location: ../index.php');
    exit;
}

$company_id = $_SESSION['company_id'];
$error = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = trim($_POST['title']);
    $duration = trim($_POST['duration']);
    $description = trim($_POST['description']);

    if($title && $description){
        $stmt = $pdo->prepare('INSERT INTO internships (title, description, duration, company_id) VALUES (?, ?, ?, ?)');
        $stmt->execute([$title, $description, $duration, $company_id]);
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Please fill in required fields.";
    }
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Post Internship</title>
<style>
body{font-family:Arial;background:#f0ece5;margin:0;padding:0;}
.container{max-width:600px;margin:50px auto;background:#fff;padding:20px;border-radius:10px;box-shadow:0 4px 10px rgba(0,0,0,0.1);}
input, textarea{width:100%;padding:10px;margin:6px 0;border-radius:6px;border:1px solid #b6bbc4;}
button{padding:10px 16px;border:none;border-radius:6px;background:#161a30;color:#fff;cursor:pointer;}
button:hover{background:#31304d;}
.card{padding:15px;border-radius:10px;border:1px solid #b6bbc4;background:#fff;}
.error{color:#e74c3c;border-left:4px solid #e74c3c;padding:6px;margin-bottom:10px;}
a{color:#161a30;text-decoration:none;}
</style>
</head>
<body>
<div class="container">
<h2>Post Internship</h2>
<?php if($error) echo "<div class='error'>".htmlspecialchars($error)."</div>"; ?>
<form method="post" class="card">
<label>Title</label>
<input name="title" required>
<label>Duration</label>
<input name="duration">
<label>Description</label>
<textarea name="description" required></textarea>
<button type="submit">Post Internship</button>
</form>
<p><a href="dashboard.php">Back to Dashboard</a></p>
</div>
</body>
</html>

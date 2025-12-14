<?php
session_start();
require_once '../db.php';

if(!isset($_SESSION['student_id'])){
    header('Location: ../index.php');
    exit;
}

$student_id = $_SESSION['student_id'];
$internship_id = $_GET['id'] ?? null;

if(!$internship_id){
    echo "No internship selected.";
    exit;
}
// Fetch internship details
$stmt = $pdo->prepare("SELECT i.*, c.name AS company_name FROM internships i JOIN companies c ON i.company_id=c.id WHERE i.id=?");
$stmt->execute([$internship_id]);
$internship = $stmt->fetch();

if(!$internship){
    echo "Internship not found.";
    exit;
}

// Handle form submission
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Check if student already applied
    $check = $pdo->prepare("SELECT * FROM applications WHERE student_id=? AND internship_id=?");
    $check->execute([$student_id, $internship_id]);
    if($check->rowCount() > 0){
        $message = "You have already applied for this internship.";
    } else {
        $insert = $pdo->prepare("INSERT INTO applications (student_id, internship_id) VALUES (?, ?)");
        $insert->execute([$student_id, $internship_id]);
        $message = "Application submitted successfully!";
    }
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Apply for Internship</title>
<style>
body{font-family:Arial;background:#e1e4e7;margin:0}
.container{max-width:600px;margin:50px auto;padding:20px;background:white;border-radius:14px;box-shadow:0 8px 20px rgba(0,0,0,.08);}
h2{color:#091d3e;margin-bottom:10px;}
p{margin:8px 0;}
button{padding:10px 16px;background:#091d3e;color:white;border:none;border-radius:8px;cursor:pointer;margin-top:10px;}
button:hover{background:#183B4E;}
.message{margin-top:12px;padding:10px;background:#d1f0d1;border-left:4px solid #27ae60;}
a{display:inline-block;margin-top:12px;text-decoration:none;color:#091d3e;}
a:hover{color:#183B4E;}
</style>
</head>
<body>

<div class="container">
<h2><?= htmlspecialchars($internship['title']) ?></h2>
<p><b>Company:</b> <?= htmlspecialchars($internship['company_name']) ?></p>
<p><?= htmlspecialchars($internship['description']) ?></p>

<form method="post">
    <button type="submit">Submit Application</button>
</form>

<?php if(!empty($message)): ?>
<div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<a href="internships.php">← Back to Internships</a>
</div>

</body>
</html>

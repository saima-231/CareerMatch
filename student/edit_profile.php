<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: ../index.php");
    exit;
}

$student_id = $_SESSION['student_id'];
$success = "";
$error = "";

// Fetch current data
$stmt = $pdo->prepare("SELECT name, course FROM students WHERE id=?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $course = trim($_POST['course']);

    if ($name === "" || $course === "") {
        $error = "All fields are required.";
    } else {
        $update = $pdo->prepare(
            "UPDATE students SET name=?, course=? WHERE id=?"
        );
        $update->execute([$name, $course, $student_id]);
        $success = "Profile updated successfully.";
    }
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Edit Profile</title>
<style>
body{font-family:Arial;background:#e1e4e7;}
.container{max-width:450px;margin:60px auto;background:white;padding:25px;border-radius:12px;}
h2{text-align:center;color:#091d3e;}
label{font-weight:bold;}
input{width:100%;padding:10px;margin:8px 0;border-radius:6px;border:1px solid #ccc;}
button{width:100%;padding:12px;background:#091d3e;color:white;border:none;border-radius:6px;}
.success{background:#d4edda;padding:10px;margin-bottom:10px;}
.error{background:#f8d7da;padding:10px;margin-bottom:10px;}
a{display:block;text-align:center;margin-top:15px;}
</style>
</head>
<body>

<div class="container">
<h2>Edit Profile</h2>

<?php if($success): ?><div class="success"><?=$success?></div><?php endif; ?>
<?php if($error): ?><div class="error"><?=$error?></div><?php endif; ?>

<form method="post">
<label>Name</label>
<input type="text" name="name" value="<?=htmlspecialchars($student['name'])?>" required>

<label>Course</label>
<input type="text" name="course" value="<?=htmlspecialchars($student['course'])?>" required>

<button type="submit">Save Changes</button>
</form>

<a href="dashboard.php">← Back to Dashboard</a>
</div>

</body>
</html>

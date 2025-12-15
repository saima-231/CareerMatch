<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

require_once '../db.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: ../index.php");
    exit;
}

$student_id = $_SESSION['student_id'];
$success = "";
$error = "";

/* Fetch student data */
$stmt = $pdo->prepare("SELECT name, email, course FROM students WHERE id=?");
$stmt->execute([$student_id]);
$student = $stmt->fetch();

if (!$student) {
    die("Student not found");
}

/* Handle profile update */
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

        // Refresh data
        $stmt->execute([$student_id]);
        $student = $stmt->fetch();
    }
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Profile</title>
<style>
body{
  font-family:Arial;
  background:#e1e4e7;
  margin:0;
}
.container{
  max-width:500px;
  margin:60px auto;
  background:white;
  padding:25px;
  border-radius:12px;
  box-shadow:0 10px 25px rgba(0,0,0,0.1);
}
h2{
  text-align:center;
  color:#091d3e;
}
.form-group{
  margin-bottom:15px;
}
label{
  font-weight:bold;
  display:block;
  margin-bottom:5px;
}
input{
  width:100%;
  padding:10px;
  border-radius:6px;
  border:1px solid #ccc;
}
button{
  width:100%;
  padding:12px;
  border:none;
  border-radius:6px;
  background:#091d3e;
  color:white;
  font-weight:bold;
  cursor:pointer;
}
button:hover{
  background:#4545a7d6;
}
.success{
  background:#d4edda;
  padding:10px;
  margin-bottom:15px;
  color:#155724;
  border-radius:6px;
}
.error{
  background:#f8d7da;
  padding:10px;
  margin-bottom:15px;
  color:#721c24;
  border-radius:6px;
}
.back{
  display:block;
  text-align:center;
  margin-top:15px;
  text-decoration:none;
  color:#091d3e;
  font-weight:bold;
}
</style>
</head>
<body>

<div class="container">
<h2>My Profile</h2>

<?php if ($success): ?><div class="success"><?=$success?></div><?php endif; ?>
<?php if ($error): ?><div class="error"><?=$error?></div><?php endif; ?>

<form method="post">
  <div class="form-group">
    <label>Name</label>
    <input type="text" name="name" value="<?=htmlspecialchars($student['name'])?>" required>
  </div>

  <div class="form-group">
    <label>Email (Read Only)</label>
    <input type="email" value="<?=htmlspecialchars($student['email'])?>" disabled>
  </div>

  <div class="form-group">
    <label>Course</label>
    <input type="text" name="course" value="<?=htmlspecialchars($student['course'])?>" required>
  </div>

  <button type="submit">Save Changes</button>
</form>

<a href="dashboard.php" class="back">← Back to Dashboard</a>
</div>

</body>
</html>

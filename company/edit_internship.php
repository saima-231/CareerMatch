<?php
session_start();
require_once '../db.php';

if(!isset($_SESSION['company_id'])){
    header('Location: ../index.php');
    exit;
}

$company_id = $_SESSION['company_id'];

if(!isset($_GET['id'])){
    header('Location: dashboard.php');
    exit;
}

$internship_id = $_GET['id'];

// Fetch internship
$stmt = $pdo->prepare("SELECT * FROM internships WHERE id=? AND company_id=?");
$stmt->execute([$internship_id, $company_id]);
$internship = $stmt->fetch();
if(!$internship){
    die("Internship not found or access denied.");
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $duration = trim($_POST['duration']);

    if($title==='' || $description===''){
        $error = "Title and Description are required.";
    } else {
        $stmt = $pdo->prepare("UPDATE internships SET title=?, description=?, duration=? WHERE id=? AND company_id=?");
        $stmt->execute([$title,$description,$duration,$internship_id,$company_id]);
        $success = "Internship updated successfully!";
        $internship['title'] = $title;
        $internship['description'] = $description;
        $internship['duration'] = $duration;
    }
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Edit Internship</title>
<style>
/* Same styles as post_internship.php */
body{font-family:Arial;background:#e1e4e7;margin:0;padding:0;}
.container{max-width:600px;margin:40px auto;padding:20px;background:white;border-radius:12px;box-shadow:0 6px 20px rgba(0,0,0,.08);}
h2{text-align:center;color:#091d3e;}
.form-row{margin-bottom:14px;}
label{display:block;font-weight:bold;margin-bottom:6px;}
input, textarea{width:100%;padding:10px;border-radius:8px;border:1px solid #ccc;}
button{background:#091d3e;color:white;padding:12px;border:none;border-radius:8px;width:100%;cursor:pointer;}
button:hover{background:#183B4E;}
.success{background:#d4edda;color:#155724;padding:10px;border-left:4px solid #28a745;margin-bottom:12px;}
.error{background:#fdecea;color:#c0392b;padding:10px;border-left:4px solid #e74c3c;margin-bottom:12px;}
</style>
</head>
<body>
<div class="container">
<h2>Edit Internship</h2>

<?php if($error) echo "<div class='error'>$error</div>"; ?>
<?php if($success) echo "<div class='success'>$success</div>"; ?>

<form method="post">
    <div class="form-row">
        <label>Title</label>
        <input type="text" name="title" value="<?=htmlspecialchars($internship['title'])?>" required>
    </div>
    <div class="form-row">
        <label>Description</label>
        <textarea name="description" rows="5" required><?=htmlspecialchars($internship['description'])?></textarea>
    </div>
    <div class="form-row">
        <label>Duration</label>
        <input type="text" name="duration" value="<?=htmlspecialchars($internship['duration'])?>">
    </div>
    <button type="submit">Update Internship</button>
</form>
</div>
</body>
</html>

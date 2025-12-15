<?php
session_start();
require_once '../db.php';

if(!isset($_SESSION['company_id'])){
    header('Location: ../index.php');
    exit;
}

$company_id = $_SESSION['company_id'];
$success = "";
$error = "";

// Get internship ID from query
if(!isset($_GET['id'])){
    die("Internship ID is required.");
}

$internship_id = $_GET['id'];

// Fetch internship info
$stmt = $pdo->prepare("SELECT * FROM internships WHERE id=? AND company_id=?");
$stmt->execute([$internship_id, $company_id]);
$internship = $stmt->fetch();

if(!$internship){
    die("Internship not found or you do not have permission.");
}

// Handle accept/reject actions
if(isset($_GET['action']) && isset($_GET['app_id'])){
    $app_id = $_GET['app_id'];
    $action = $_GET['action'];

    if($action === 'accept'){
        // Accept application
        $stmt = $pdo->prepare("UPDATE applications SET status='Accepted' WHERE id=?");
        $stmt->execute([$app_id]);

        // Deactivate internship
        $stmt = $pdo->prepare("UPDATE internships SET is_active=0 WHERE id=?");
        $stmt->execute([$internship_id]);

        $success = "Application accepted and internship closed.";
    } elseif($action === 'reject'){
        $stmt = $pdo->prepare("UPDATE applications SET status='Rejected' WHERE id=?");
        $stmt->execute([$app_id]);
        $success = "Application rejected.";
    }
}

// Fetch all applications for this internship
$stmt = $pdo->prepare("
    SELECT a.*, s.name AS student_name, s.email AS student_email
    FROM applications a
    JOIN students s ON a.student_id = s.id
    WHERE a.internship_id=?
    ORDER BY a.applied_at DESC
");
$stmt->execute([$internship_id]);
$applications = $stmt->fetchAll();

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>View Applications</title>
<style>
body{font-family:Arial;background:#e1e4e7;margin:0;padding:0;}
.container{max-width:900px;margin:40px auto;padding:20px;}
.header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;}
.btn{padding:6px 12px;border-radius:6px;color:white;text-decoration:none;}
.btn-accept{background:#27ae60;}
.btn-accept:hover{background:#2ecc71;}
.btn-reject{background:#e74c3c;}
.btn-reject:hover{background:#c0392b;}
.card{background:white;border-radius:12px;padding:20px;box-shadow:0 6px 20px rgba(0,0,0,.08);margin-bottom:20px;}
table{width:100%;border-collapse:collapse;margin-top:10px;}
th, td{padding:10px;border-bottom:1px solid #ccc;text-align:left;}
.status-Pending{color:#f39c12;font-weight:bold;}
.status-Accepted{color:#27ae60;font-weight:bold;}
.status-Rejected{color:#e74c3c;font-weight:bold;}
.success{background:#d4edda;color:#155724;padding:10px;border-left:4px solid #28a745;margin-bottom:12px;border-radius:6px;}
</style>
</head>
<body>

<div class="container">
<h2>Applications for: <?=htmlspecialchars($internship['title'])?></h2>
<a href="dashboard.php" class="btn" style="background:#091d3e;margin-bottom:10px;">← Back</a>

<?php if($success): ?>
<div class="success"><?=htmlspecialchars($success)?></div>
<?php endif; ?>

<?php if(count($applications)===0): ?>
<p>No applications yet for this internship.</p>
<?php else: ?>
<table>
<tr>
<th>Student Name</th>
<th>Email</th>
<th>Status</th>
<th>Applied At</th>
<th>Action</th>
</tr>
<?php foreach($applications as $a): ?>
<tr>
<td><?=htmlspecialchars($a['student_name'])?></td>
<td><?=htmlspecialchars($a['student_email'])?></td>
<td class="status-<?=$a['status']?>"><?=htmlspecialchars($a['status'])?></td>
<td><?=htmlspecialchars($a['applied_at'])?></td>
<td>
    <?php if($a['status'] === 'Pending'): ?>
        <a href="?id=<?=$internship_id?>&app_id=<?=$a['id']?>&action=accept" class="btn btn-accept"
           onclick="return confirm('Accept this application? Internship will close for new applications.')">Accept</a>
        <a href="?id=<?=$internship_id?>&app_id=<?=$a['id']?>&action=reject" class="btn btn-reject"
           onclick="return confirm('Reject this application?')">Reject</a>
    <?php else: ?>
        -
    <?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>

</div>
</body>
</html>

<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['company_id'])) {
    header('Location: ../index.php');
    exit;
}

$company_id = $_SESSION['company_id'];
$success = "";

/* ===== Validate Internship ID ===== */
if (!isset($_GET['id'])) {
    die("Internship ID is required.");
}

$internship_id = (int) $_GET['id'];

/* ===== Fetch Internship (ownership check) ===== */
$stmt = $pdo->prepare(
    "SELECT * FROM internships 
     WHERE id = ? AND company_id = ?"
);
$stmt->execute([$internship_id, $company_id]);
$internship = $stmt->fetch();

if (!$internship) {
    die("Internship not found or access denied.");
}

/* ===== Handle Accept / Reject ===== */
if (isset($_GET['action'], $_GET['app_id'])) {

    $app_id = (int) $_GET['app_id'];
    $action = $_GET['action'];

    if ($action === 'accept') {

        /* 1️⃣ Accept selected application */
        $stmt = $pdo->prepare("UPDATE applications SET status='Accepted' WHERE id=?");
        $stmt->execute([$app_id]);

        /* 2️⃣ Close internship (NO MORE APPLICATIONS) */
        $stmt = $pdo->prepare("UPDATE internships SET is_active = 0 WHERE id=?");
        $stmt->execute([$internship_id]);

        /* 3️⃣ Reject all other pending applications */
        $stmt = $pdo->prepare(
            "UPDATE applications 
             SET status='Rejected' 
             WHERE internship_id=? AND status='Pending'"
        );
        $stmt->execute([$internship_id]);

        $success = "Application accepted. Internship is now closed.";

    } elseif ($action === 'reject') {

        $stmt = $pdo->prepare("UPDATE applications SET status='Rejected' WHERE id=?");
        $stmt->execute([$app_id]);

        $success = "Application rejected.";
    }

    // Refetch internship info to update is_active
    $stmt = $pdo->prepare("SELECT * FROM internships WHERE id=? AND company_id=?");
    $stmt->execute([$internship_id, $company_id]);
    $internship = $stmt->fetch();
}

/* ===== Fetch Applications ===== */
$stmt = $pdo->prepare(
    "SELECT a.*, s.name AS student_name, s.email AS student_email
     FROM applications a
     JOIN students s ON a.student_id = s.id
     WHERE a.internship_id = ?
     ORDER BY a.applied_at DESC"
);
$stmt->execute([$internship_id]);
$applications = $stmt->fetchAll();
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>View Applications</title>

<style>
body{font-family:Arial;background:#e1e4e7;margin:0;}
.container{max-width:950px;margin:50px auto;padding:20px;}
.card{background:white;padding:20px;border-radius:14px;box-shadow:0 10px 25px rgba(0,0,0,.08);}
table{width:100%;border-collapse:collapse;margin-top:15px;}
th, td{padding:12px;border-bottom:1px solid #ddd;text-align:left;}
.btn{padding:6px 12px;border-radius:6px;color:white;text-decoration:none;font-size:14px;}
.btn-accept{background:#27ae60;}
.btn-reject{background:#e74c3c;}
.btn-back{background:#091d3e;}
.status-Pending{color:#f39c12;font-weight:bold;}
.status-Accepted{color:#27ae60;font-weight:bold;}
.status-Rejected{color:#e74c3c;font-weight:bold;}
.success{background:#d4edda;color:#155724;padding:10px;border-radius:6px;margin-bottom:15px;}
.closed{color:#e74c3c;font-weight:bold;}
</style>
</head>

<body>
<div class="container">
<div class="card">

<a href="dashboard.php" class="btn btn-back">← Back to Dashboard</a>

<h2>
Applications — <?=htmlspecialchars($internship['title'])?>
<?php if(!$internship['is_active']): ?>
    <span class="closed">(CLOSED)</span>
<?php endif; ?>
</h2>

<?php if($success): ?>
<div class="success"><?=htmlspecialchars($success)?></div>
<?php endif; ?>

<?php if(count($applications) === 0): ?>
<p>No applications yet.</p>
<?php else: ?>
<table>
<tr>
    <th>Student</th>
    <th>Email</th>
    <th>Status</th>
    <th>Applied At</th>
    <th>Action</th>
</tr>
<?php foreach($applications as $a): ?>
<tr>
<td><?=htmlspecialchars($a['student_name'])?></td>
<td><?=htmlspecialchars($a['student_email'])?></td>
<td class="status-<?=htmlspecialchars($a['status'])?>"><?=htmlspecialchars($a['status'])?></td>
<td><?=htmlspecialchars($a['applied_at'])?></td>
<td>
<?php if($a['status'] === 'Pending' && $internship['is_active']): ?>
    <a class="btn btn-accept"
       href="?id=<?=$internship_id?>&app_id=<?=$a['id']?>&action=accept"
       onclick="return confirm('Accept this student? Internship will close.')">
       Accept
    </a>
    <a class="btn btn-reject"
       href="?id=<?=$internship_id?>&app_id=<?=$a['id']?>&action=reject"
       onclick="return confirm('Reject this application?')">
       Reject
    </a>
<?php else: ?>
    —
<?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>

</div>
</div>
</body>
</html>

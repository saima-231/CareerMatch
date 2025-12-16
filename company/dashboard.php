<?php
session_start();
require_once '../db.php';
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

if (!isset($_SESSION['company_id'])) {
    header('Location: ../index.php');
    exit;
}

$company_id = $_SESSION['company_id'];

// Handle application acceptance
if(isset($_GET['accept_app']) && isset($_GET['internship_id'])){
    $application_id = $_GET['accept_app'];
    $internship_id = $_GET['internship_id'];

    // Accept the application
    $stmt = $pdo->prepare("UPDATE applications SET status='Accepted' WHERE id=?");
    $stmt->execute([$application_id]);

    // Deactivate the internship
    $stmt = $pdo->prepare("UPDATE internships SET is_active=0 WHERE id=?");
    $stmt->execute([$internship_id]);

    header("Location: dashboard.php?success=Application+Accepted");
    exit;
}

// Fetch company info
$stmt = $pdo->prepare("SELECT * FROM companies WHERE id=?");
$stmt->execute([$company_id]);
$company = $stmt->fetch();

// Fetch internships posted by company with total, pending, and accepted counts
$internships = $pdo->prepare("
    SELECT i.*, 
           (SELECT COUNT(*) FROM applications a WHERE a.internship_id=i.id) AS total_applications,
           (SELECT COUNT(*) FROM applications a WHERE a.internship_id=i.id AND a.status='Accepted') AS accepted_applications,
           (SELECT COUNT(*) FROM applications a WHERE a.internship_id=i.id AND a.status='Pending') AS pending_applications
    FROM internships i
    WHERE i.company_id=?
    ORDER BY i.created_at DESC
");
$internships->execute([$company_id]);
$interns = $internships->fetchAll();
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Company Dashboard</title>
<style>
body{font-family:Arial;background:#e1e4e7;margin:0;padding:0;}
.container{max-width:1000px;margin:40px auto;padding:20px;}
.header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;}
.header h2{margin:0;}
.btn{padding:8px 14px;border-radius:8px;background:#091d3e;color:white;text-decoration:none;}
.btn:hover{background:#183B4E;}
.card{background:white;border-radius:14px;padding:20px;box-shadow:0 8px 20px rgba(0,0,0,.08);margin-bottom:20px;}
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
<div class="header">
<h2>Welcome, <?=htmlspecialchars($company['name'])?></h2>
<a href="../logout.php" class="btn">Logout</a>
</div>

<?php if(isset($_GET['success'])): ?>
<div class="success"><?=htmlspecialchars($_GET['success'])?></div>
<?php endif; ?>

<div class="card">
<h3>Your Internships</h3>
<?php if(count($interns)===0): ?>
  <p>You haven't posted any internships yet. <a href="post_internship.php">Post one now</a>.</p>
<?php else: ?>
  <table>
    <tr>
      <th>Title</th>
      <th>Duration</th>
      <th>Total Applications</th>
      <th>Pending</th>
      <th>Accepted</th>
      <th>Actions</th>
    </tr>
    <?php foreach($interns as $i): ?>
      <tr>
        <td><?=htmlspecialchars($i['title'])?></td>
        <td><?=htmlspecialchars($i['duration'])?></td>
        <td><?=$i['total_applications']?></td>
        <td class="status-Pending"><?=$i['pending_applications']?></td>
        <td class="status-Accepted"><?=$i['accepted_applications']?></td>
        <td>
          <a href="view_application.php?id=<?=$i['id']?>" class="btn">View Applications</a>
          <a href="edit_internship.php?id=<?=$i['id']?>" class="btn">Edit</a>
          
          <?php if($i['accepted_applications'] > 0): ?>
            <a href="delete_internship.php?id=<?=$i['id']?>"
               onclick="return confirm('This internship has accepted students. Delete it?')"
               class="btn" style="background:#e74c3c;margin-left:4px;">Delete</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php endif; ?>
</div> 

<div class="card" style="border-left:4px solid #e74c3c;">
  <h3 style="color:#e74c3c;">Danger Zone</h3>
  <a href="../delete_account.php"
     onclick="return confirm('This will permanently delete your account. Continue?')"
     style="color:#e74c3c;font-weight:bold;text-decoration:none;">
     Delete My Account
  </a>
  <p style="font-size:0.9rem;color:#666;margin-top:6px;">
    This action cannot be undone.
  </p>
</div>

</div>
</body>
</html>

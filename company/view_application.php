<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['company_id'])) {
    header('Location: ../index.php');
    exit;
}

$company_id = $_SESSION['company_id'];

/* ===============================
   HANDLE ACCEPT / REJECT ACTION
================================ */
if (isset($_GET['action'], $_GET['id'])) {
    $action = $_GET['action'];
    $app_id = (int) $_GET['id'];

    if (in_array($action, ['Accepted', 'Rejected'])) {
        $stmt = $pdo->prepare(
            "UPDATE applications SET status = ? WHERE id = ?"
        );
        $stmt->execute([$action, $app_id]);

        header("Location: view_application.php");
        exit;
    }
}

/* ===============================
   FETCH APPLICATIONS
================================ */
$stmt = $pdo->prepare("
    SELECT 
        a.id AS app_id,
        a.status,
        s.name AS student_name,
        s.email,
        s.course,
        s.skills,
        s.linkedin_link,
        i.title AS internship_title
    FROM applications a
    JOIN students s ON a.student_id = s.id
    JOIN internships i ON a.internship_id = i.id
    WHERE i.company_id = ?
    ORDER BY a.applied_at DESC
");
$stmt->execute([$company_id]);
$applications = $stmt->fetchAll();
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Applications</title>

<style>
body{
    font-family:Arial;
    background:#f4f6f8;
    margin:0;
    padding:0;
}
.container{
    max-width:1000px;
    margin:40px auto;
    padding:20px;
}
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}
.table{
    width:100%;
    border-collapse:collapse;
    background:white;
    box-shadow:0 6px 20px rgba(0,0,0,.08);
}
.table th,
.table td{
    padding:12px;
    border-bottom:1px solid #eee;
    text-align:left;
    font-size:0.95rem;
}
.table th{
    background:#f9fafb;
}
.btn{
    padding:8px 14px;
    border-radius:8px;
    text-decoration:none;
    color:white;
    background:#091d3e;
}
.btn:hover{
    background:#183B4E;
}

/* Action Buttons */
.action-btns{
    display:flex;
    gap:12px;
}
.accept-btn{
    background:#2ecc71;
    padding:8px 14px;
    border-radius:6px;
    color:white;
    text-decoration:none;
}
.reject-btn{
    background:#e74c3c;
    padding:8px 14px;
    border-radius:6px;
    color:white;
    text-decoration:none;
}
.accept-btn:hover{background:#27ae60;}
.reject-btn:hover{background:#c0392b;}

/* Status Badge */
.status{
    font-weight:bold;
}
.status.Pending{color:#f39c12;}
.status.Accepted{color:#27ae60;}
.status.Rejected{color:#c0392b;}
</style>
</head>

<body>

<div class="container">

<div class="header">
    <h2>Internship Applications</h2>
    <a href="dashboard.php" class="btn">Dashboard</a>
</div>

<table class="table">
<thead>
<tr>
    <th>Student</th>
    <th>Email</th>
    <th>Course</th>
    <th>Skills</th>
    <th>LinkedIn</th>
    <th>Internship</th>
    <th>Status</th>
    <th>Action</th>
</tr>
</thead>

<tbody>
<?php if (count($applications) === 0): ?>
<tr>
    <td colspan="8">No applications found.</td>
</tr>
<?php endif; ?>

<?php foreach ($applications as $a): ?>
<tr>
    <td><?= htmlspecialchars($a['student_name']) ?></td>
    <td><?= htmlspecialchars($a['email']) ?></td>
    <td><?= htmlspecialchars($a['course']) ?></td>
    <td><?= htmlspecialchars($a['skills']) ?></td>
    <td>
        <?php if(!empty($a['linkedin_link'])): ?>
            <a href="<?= htmlspecialchars($a['linkedin_link']) ?>" target="_blank">
                View
            </a>
        <?php else: ?>
            —
        <?php endif; ?>
    </td>
    <td><?= htmlspecialchars($a['internship_title']) ?></td>
    <td class="status <?= $a['status'] ?>">
        <?= $a['status'] ?>
    </td>
    <td>
        <?php if ($a['status'] === 'Pending'): ?>
        <div class="action-btns">
            <a href="view_application.php?action=Accepted&id=<?= $a['app_id'] ?>" class="accept-btn">
                Accept
            </a>
            <a href="view_application.php?action=Rejected&id=<?= $a['app_id'] ?>" class="reject-btn">
                Reject
            </a>
        </div>
        <?php else: ?>
            —
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

</div>

</body>
</html>

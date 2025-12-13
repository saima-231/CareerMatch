<?php
session_start();
require_once '../db.php';
if(!isset($_SESSION['role']) || $_SESSION['role']!=='student') header('Location: ../index.php');

$student_id = $_SESSION['student_id'];
$student = $pdo->prepare('SELECT * FROM students WHERE id=?');
$student->execute([$student_id]);
$student = $student->fetch();

// fetch applications
$stmt = $pdo->prepare('SELECT a.*, i.title, c.name AS company_name 
                       FROM applications a 
                       JOIN internships i ON a.internship_id=i.id 
                       JOIN companies c ON i.company_id=c.id 
                       WHERE a.student_id=? ORDER BY a.applied_at DESC');
$stmt->execute([$student_id]);
$applications = $stmt->fetchAll();
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Student Dashboard</title>
<style>
:root {--primary-dark:#161a30;--secondary-dark:#31304d;--accent-muted:#b6bbc4;--neutral-base:#f0ece5;}
*{box-sizing:border-box;}
body{font-family:Arial,sans-serif;background:var(--neutral-base);margin:0;padding:0;color:var(--primary-dark);}
.container{max-width:900px;margin:40px auto;padding:24px;background:white;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,0.06);}
.header{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;}
.header h2{margin:0;}
.btn{padding:10px 16px;border-radius:8px;border:none;cursor:pointer;font-weight:600;text-decoration:none;margin-left:8px;}
.btn-primary{background:var(--primary-dark);color:white;}
.btn-primary:hover{background:var(--secondary-dark);}
.btn-ghost{background:transparent;border:1px solid var(--accent-muted);color:var(--primary-dark);}
.card{padding:16px;border-radius:10px;border:1px solid var(--accent-muted);background:linear-gradient(180deg, rgba(240,236,229,0.6), #fff);margin-bottom:12px;}
.table{width:100%;border-collapse:collapse;}
.table th,.table td{padding:10px;border-bottom:1px solid #eee;text-align:left;}
.badge{padding:5px 8px;border-radius:6px;font-weight:600;}
.badge-pending{background:#fff3cd;color:#856404;}
.badge-accepted{background:#d4edda;color:#155724;}
.badge-rejected{background:#f8d7da;color:#721c24;}
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>Welcome, <?=htmlspecialchars($student['name'])?></h2>
        <div>
            <a href="internships.php" class="btn btn-ghost">Browse Internships</a>
            <a href="../logout.php" class="btn btn-primary">Logout</a>
        </div>
    </div>

    <h3>Your Applications</h3>
    <div class="card">
        <table class="table">
            <thead><tr><th>Internship</th><th>Company</th><th>Status</th><th>Applied At</th></tr></thead>
            <tbody>
                <?php foreach($applications as $a): ?>
                    <tr>
                        <td><?=htmlspecialchars($a['title'])?></td>
                        <td><?=htmlspecialchars($a['company_name'])?></td>
                        <td>
                            <?php if($a['status']==='Pending'): ?>
                                <span class="badge badge-pending">Pending</span>
                            <?php elseif($a['status']==='Accepted'): ?>
                                <span class="badge badge-accepted">Accepted</span>
                            <?php else: ?>
                                <span class="badge badge-rejected">Rejected</span>
                            <?php endif; ?>
                        </td>
                        <td><?=htmlspecialchars($a['applied_at'])?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

<?php
session_start();
require_once '../db.php';
if(!isset($_SESSION['role']) || $_SESSION['role']!=='company') header('Location: ../index.php');

$company_id = $_SESSION['company_id'];
$company = $pdo->prepare('SELECT * FROM companies WHERE id=?'); 
$company->execute([$company_id]); 
$company = $company->fetch();

$stmt = $pdo->prepare('SELECT * FROM internships WHERE company_id=? ORDER BY created_at DESC');
$stmt->execute([$company_id]);
$internships = $stmt->fetchAll();
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Company Dashboard</title>
<style>
:root {--primary-dark:#161a30;--secondary-dark:#31304d;--accent-muted:#b6bbc4;--neutral-base:#f0ece5;}
body{font-family:Arial,sans-serif;background:var(--neutral-base);margin:0;padding:0;color:var(--primary-dark);}
.container{max-width:900px;margin:40px auto;padding:24px;background:white;border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,0.06);}
.header{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;}
.header h2{margin:0;}
.btn{padding:10px 16px;border-radius:8px;border:none;cursor:pointer;font-weight:600;text-decoration:none;margin-left:8px;}
.btn-primary{background:var(--primary-dark);color:white;}
.btn-primary:hover{background:var(--secondary-dark);}
.btn-ghost{background:transparent;border:1px solid var(--accent-muted);color:var(--primary-dark);}
.btn-secondary{background:var(--secondary-dark);color:white;}
.btn-secondary:hover{background:var(--primary-dark);}
.card{padding:16px;border-radius:10px;border:1px solid var(--accent-muted);background:linear-gradient(180deg, rgba(240,236,229,0.6), #fff);margin-bottom:12px;}
.table{width:100%;border-collapse:collapse;}
.table th,.table td{padding:10px;border-bottom:1px solid #eee;text-align:left;}
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2><?=htmlspecialchars($company['name'])?></h2>
        <div>
            <a href="post_internship.php" class="btn btn-primary">Post Internship</a>
            <a href="../logout.php" class="btn btn-ghost">Logout</a>
        </div>
    </div>

    <h3>Your Postings</h3>
    <div class="card">
        <table class="table">
            <thead><tr><th>Title</th><th>Duration</th><th>Posted</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach($internships as $it): ?>
                <tr>
                    <td><?=htmlspecialchars($it['title'])?></td>
                    <td><?=htmlspecialchars($it['duration'])?></td>
                    <td><?=htmlspecialchars($it['created_at'])?></td>
                    <td>
                        <a href="edit_internship.php?id=<?=$it['id']?>" class="btn btn-ghost">Edit</a>
                        <a href="view_applications.php?id=<?=$it['id']?>" class="btn btn-secondary">View Apps</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

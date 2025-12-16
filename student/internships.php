<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['student_id'])) {
    header('Location: ../index.php');
    exit;
}

$student_id = $_SESSION['student_id'];
$message = "";

// Handle internship apply action
if (isset($_GET['apply_id'])) {
    $internship_id = $_GET['apply_id'];

    // Check if student already applied
    $stmt = $pdo->prepare("SELECT * FROM applications WHERE student_id=? AND internship_id=?");
    $stmt->execute([$student_id, $internship_id]);

    if ($stmt->rowCount() === 0) {
        // Insert application
        $stmt = $pdo->prepare("INSERT INTO applications (student_id, internship_id, status, applied_at) VALUES (?,?,?,NOW())");
        $stmt->execute([$student_id, $internship_id, 'pending']);
        $message = "You have applied successfully!";
    } else {
        $message = "You have already applied for this internship.";
    }
}

// Fetch active internships
$stmt = $pdo->prepare("
    SELECT i.id, i.title, i.description, i.duration, c.name AS company_name
    FROM internships i
    JOIN companies c ON i.company_id = c.id
    WHERE i.is_active = 1
    ORDER BY i.created_at DESC
");
$stmt->execute();
$internships = $stmt->fetchAll();

// Fetch internships already applied by this student
$appliedStmt = $pdo->prepare("SELECT internship_id FROM applications WHERE student_id=?");
$appliedStmt->execute([$student_id]);
$applied_ids = $appliedStmt->fetchAll(PDO::FETCH_COLUMN);

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Browse Internships</title>
<style>
body{font-family:Arial;background:#e1e4e7;margin:0;padding:0;}
.container{max-width:900px;margin:40px auto;padding:20px;}
.card{background:white;border-radius:14px;padding:20px;box-shadow:0 8px 20px rgba(0,0,0,.08);margin-bottom:20px;}
.btn{padding:8px 14px;border-radius:8px;background:#091d3e;color:white;text-decoration:none;}
.btn:hover{background:#183B4E;}
.small{color:#666;font-size:0.9rem;}
.alert{padding:10px 15px;background:#4CAF50;color:white;border-radius:8px;margin-bottom:20px;}
.alert.error{background:#f44336;}
</style>
</head>
<body>

<div class="container">
<h2>Available Internships</h2>

<?php if(!empty($message)): ?>
    <div class="alert"><?=htmlspecialchars($message)?></div>
<?php endif; ?>

<?php if (count($internships) === 0): ?>
    <p>No internships available right now.</p>
<?php else: ?>
    <?php foreach ($internships as $i): ?>
        <div class="card">
            <h3><?=htmlspecialchars($i['title'])?></h3>
            <p class="small"><b>Company:</b> <?=htmlspecialchars($i['company_name'])?></p>
            <p><?=nl2br(htmlspecialchars($i['description']))?></p>
            <p class="small"><b>Duration:</b> <?=htmlspecialchars($i['duration'])?></p>

            <?php if (in_array($i['id'], $applied_ids)): ?>
                <span class="btn" style="background:#666;cursor:default;">Applied (Pending)</span>
            <?php else: ?>
                <a href="?apply_id=<?=$i['id']?>" 
                   class="btn"
                   onclick="return confirm('Apply for this internship?')">
                   Apply
                </a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<a href="dashboard.php" class="btn">← Back to Dashboard</a>
</div>

</body>
</html>

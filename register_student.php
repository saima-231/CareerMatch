<?php
session_start();
require_once 'db.php';

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $course = trim($_POST['course']);
    $phone = trim($_POST['phone']);
    $university = trim($_POST['university']);
    $year_of_study = intval($_POST['year_of_study']);
    $skills = trim($_POST['skills']);
    $resume_link = trim($_POST['resume_link']);
    $github_link = trim($_POST['github_link']);
    $linkedin_link = trim($_POST['linkedin_link']);
    $bio = trim($_POST['bio']);

    // Handle profile image
    $profile_img = null;
    if(isset($_FILES['profile_img']) && $_FILES['profile_img']['error']===0){
        $ext = pathinfo($_FILES['profile_img']['name'], PATHINFO_EXTENSION);
        $profile_img = 'uploads/students_'.time().'.'.$ext;
        move_uploaded_file($_FILES['profile_img']['tmp_name'], $profile_img);
    }

    if($name==='' || $email==='' || $password===''){
        $error = "Name, Email and Password are required.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO students 
            (name,email,password,course,phone,university,year_of_study,skills,resume_link,github_link,linkedin_link,bio) 
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$name,$email,$hash,$course,$phone,$university,$year_of_study,$skills,$resume_link,$github_link,$linkedin_link,$bio]);
        $success = "Registration successful! You can now login.";
    }
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Student Registration</title>
<style>
body{font-family:Arial;background:#f4f6f8;margin:0;padding:0;}
.container{max-width:600px;margin:40px auto;padding:20px;background:white;border-radius:12px;box-shadow:0 6px 20px rgba(0,0,0,.08);}
h2{text-align:center;color:#091d3e;margin-bottom:20px;}
.form-row{margin-bottom:12px;}
label{display:block;font-weight:bold;margin-bottom:4px;}
input, textarea, select{width:100%;padding:10px;border-radius:8px;border:1px solid #ccc;}
button{width:100%;padding:12px;background:#091d3e;color:white;border:none;border-radius:8px;cursor:pointer;}
button:hover{background:#183B4E;}
.error{background:#fdecea;color:#c0392b;padding:10px;border-left:4px solid #e74c3c;margin-bottom:12px;}
.success{background:#d4edda;color:#155724;padding:10px;border-left:4px solid #28a745;margin-bottom:12px;}
</style>
</head>
<body>
<div class="container">
<h2>Register as Student</h2>

<?php if($error) echo "<div class='error'>$error</div>"; ?>
<?php if($success) echo "<div class='success'>$success</div>"; ?>

<form method="post" enctype="multipart/form-data">
    <div class="form-row">
        <label>Name</label>
        <input type="text" name="name" required>
    </div>
    <div class="form-row">
        <label>Email</label>
        <input type="email" name="email" required>
    </div>
    <div class="form-row">
        <label>Password</label>
        <input type="password" name="password" required>
    </div>
    <div class="form-row">
        <label>Course</label>
        <input type="text" name="course">
    </div>
    <div class="form-row">
        <label>Phone</label>
        <input type="text" name="phone">
    </div>
    <div class="form-row">
        <label>University</label>
        <input type="text" name="university">
    </div>
    <div class="form-row">
        <label>Year of Study</label>
        <input type="number" name="year_of_study">
    </div>
    <div class="form-row">
        <label>Skills (comma separated)</label>
        <input type="text" name="skills">
    </div>
    <div class="form-row">
        <label>Resume Link</label>
        <input type="url" name="resume_link">
    </div>
    <div class="form-row">
        <label>GitHub Link</label>
        <input type="url" name="github_link">
    </div>
    <div class="form-row">
        <label>LinkedIn Link</label>
        <input type="url" name="linkedin_link">
    </div>
    <div class="form-row">
        <label>Short Bio</label>
        <textarea name="bio" rows="3"></textarea>
    </div>
    <div class="form-row">
        <label>Profile Picture</label>
        <input type="file" name="profile_img" accept="image/*">
    </div>
    <button type="submit">Register</button>
</form>
</div>
</body>
</html>

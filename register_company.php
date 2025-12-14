<?php
session_start();
require_once 'db.php';

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $description = trim($_POST['description']);
    $website = trim($_POST['website']);
    $phone = trim($_POST['phone']);
    $industry = trim($_POST['industry']);
    $location = trim($_POST['location']);

    // Handle logo
    $logo = null;
    if(isset($_FILES['logo']) && $_FILES['logo']['error']===0){
        $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $logo = 'uploads/company_'.time().'.'.$ext;
        move_uploaded_file($_FILES['logo']['tmp_name'], $logo);
    }

    if($name==='' || $email==='' || $password===''){
        $error = "Name, Email, and Password are required.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO companies 
            (name,email,password,description,website,phone,industry,location,logo) 
            VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$name,$email,$hash,$description,$website,$phone,$industry,$location,$logo]);
        $success = "Company registered successfully! You can now login.";
    }
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Company Registration</title>
<style>
body{font-family:Arial;background:#f4f6f8;margin:0;padding:0;}
.container{max-width:600px;margin:40px auto;padding:20px;background:white;border-radius:12px;box-shadow:0 6px 20px rgba(0,0,0,.08);}
h2{text-align:center;color:#091d3e;margin-bottom:20px;}
.form-row{margin-bottom:12px;}
label{display:block;font-weight:bold;margin-bottom:4px;}
input, textarea{width:100%;padding:10px;border-radius:8px;border:1px solid #ccc;}
button{width:100%;padding:12px;background:#091d3e;color:white;border:none;border-radius:8px;cursor:pointer;}
button:hover{background:#183B4E;}
.error{background:#fdecea;color:#c0392b;padding:10px;border-left:4px solid #e74c3c;margin-bottom:12px;}
.success{background:#d4edda;color:#155724;padding:10px;border-left:4px solid #28a745;margin-bottom:12px;}
</style>
</head>
<body>
<div class="container">
<h2>Register as Company</h2>

<?php if($error) echo "<div class='error'>$error</div>"; ?>
<?php if($success) echo "<div class='success'>$success</div>"; ?>

<form method="post" enctype="multipart/form-data">
    <div class="form-row">
        <label>Company Name</label>
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
        <label>Description</label>
        <textarea name="description" rows="4"></textarea>
    </div>
    <div class="form-row">
        <label>Website</label>
        <input type="url" name="website">
    </div>
    <div class="form-row">
        <label>Phone</label>
        <input type="text" name="phone">
    </div>
    <div class="form-row">
        <label>Industry</label>
        <input type="text" name="industry">
    </div>
    <div class="form-row">
        <label>Location</label>
        <input type="text" name="location">
    </div>
    <div class="form-row">
        <label>Logo</label>
        <input type="file" name="logo" accept="image/*">
    </div>
    <button type="submit">Register</button>
</form>
</div>
</body>
</html>

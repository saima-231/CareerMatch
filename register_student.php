<?php
require_once 'db.php';

$error="";

if($_SERVER['REQUEST_METHOD']=='POST'){
    $hash=password_hash($_POST['password'],PASSWORD_DEFAULT);
    try{
        $stmt=$pdo->prepare("INSERT INTO students(name,email,password,course) VALUES(?,?,?,?)");
        $stmt->execute([$_POST['name'],$_POST['email'],$hash,$_POST['course']]);
        header("Location: index.php");
        exit;
    }catch(Exception $e){
        $error="Email already exists";
    }
}
?>

<!doctype html>
<html>
<head>
<title>Student Registration</title>
<style>
body{font-family:Arial;background:#f4f6f8}
.container{max-width:500px;margin:50px auto;background:white;padding:24px;border-radius:14px;box-shadow:0 10px 25px rgba(0,0,0,.1)}
img{width:120px;display:block;margin:auto}
input{width:100%;padding:12px;margin-bottom:12px;border-radius:8px;border:1px solid #ccc}
button{background:#091d3e;color:white;padding:12px;border:none;border-radius:8px;width:100%}
button:hover{background:#183B4E}
</style>
</head>
<body>

<div class="container">
<img src="assets/images/student.png">
<h2>Student Registration</h2>

<?php if($error): ?><p style="color:red"><?=$error?></p><?php endif; ?>

<form method="post">
<input name="name" placeholder="Full Name" required>
<input name="email" type="email" placeholder="Email" required>
<input name="password" type="password" placeholder="Password" required>
<input name="course" placeholder="Course">
<button>Register</button>
</form>
</div>

</body>
</html>

<?php
session_start();
session_destroy();
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Logged Out</title>
<style>
body { font-family: Arial; text-align:center; padding:50px; background:#f4f6f8; }
img { width:150px; margin-top:20px; }
</style>
<!-- Auto redirect after 3 seconds -->
<meta http-equiv="refresh" content="3;url=index.php">
</head>
<body>
<h2>You have been logged out</h2>
<img src="assets/images/end.png" alt="Logout">
<p>Redirecting to home page...</p>
</body>
</html>

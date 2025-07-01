<?php session_start(); if (!isset($_SESSION['email'])) header("Location: login.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Welcome, <?php echo $_SESSION['email']; ?>!</h2>
    <a href="logout.php">Logout</a>
</div>
</body>
</html>

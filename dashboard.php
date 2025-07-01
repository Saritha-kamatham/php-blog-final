<?php
session_start();
require_once 'config/db.php';

// ✅ Only logged-in users can access
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

// ✅ Optional: Restrict dashboard to admin only
// Uncomment below if you want admin-only dashboard
/*
if ($_SESSION['role'] !== 'admin') {
    die("Access denied. Only admin users can view this page.");
}
*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>👋 Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
    <p class="lead">Your Role: <strong><?= $_SESSION['role'] ?></strong></p>

    <div class="mt-4">
        <a href="index.php" class="btn btn-outline-primary">View Blog Posts</a>
        <a href="posts/create.php" class="btn btn-outline-success">Create New Post</a>
        <a href="auth/logout.php" class="btn btn-outline-danger">Logout</a>
    </div>
</div>
</body>
</html>

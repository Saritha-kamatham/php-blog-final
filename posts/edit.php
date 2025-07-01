<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'];

// ✅ Secure fetch with prepared statement
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
    die("Post not found.");
}

// ✅ Only allow the owner or admin to edit
if ($_SESSION['user_id'] != $post['user_id'] && $_SESSION['role'] !== 'admin') {
    die("Access denied.");
}

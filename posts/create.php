<?php
session_start();
require_once '../config/db.php';

// ✅ Block access if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// ✅ Optional: Allow only 'admin' to create posts
// if ($_SESSION['role'] !== 'admin') {
//     die("Access denied.");
// }

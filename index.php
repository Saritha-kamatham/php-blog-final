<?php
session_start();
require_once 'config/db.php';

// Handle search query
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_safe = $conn->real_escape_string($search);

// Pagination setup
$posts_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $posts_per_page;

// Count total posts for pagination
$count_sql = "SELECT COUNT(*) as total FROM posts WHERE title LIKE '%$search_safe%' OR content LIKE '%$search_safe%'";
$count_result = $conn->query($count_sql);
$total_posts = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_posts / $posts_per_page);

// Fetch posts
$sql = "SELECT posts.*, users.username FROM posts 
        JOIN users ON posts.user_id = users.id 
        WHERE posts.title LIKE '%$search_safe%' OR posts.content LIKE '%$search_safe%'
        ORDER BY posts.created_at DESC 
        LIMIT $offset, $posts_per_page";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blog - Task 3</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between mb-4">
        <h2>📝 Blog Posts</h2>
        <?php if (isset($_SESSION['user_id'])): ?>
            <div>
                <a href="posts/create.php" class="btn btn-success">➕ New Post</a>
                <a href="auth/logout.php" class="btn btn-outline-danger">Logout</a>
            </div>
        <?php else: ?>
            <div>
                <a href="auth/login.php" class="btn btn-primary">Login</a>
                <a href="auth/register.php" class="btn btn-secondary">Register</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Search Form -->
    <form method="GET" class="input-group mb-4">
        <input type="text" name="search" class="form-control" placeholder="Search posts..." value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-outline-primary" type="submit">Search</button>
    </form>

    <!-- Posts -->
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                    <p class="card-text"><?= nl2br(htmlspecialchars($row['content'])) ?></p>
                    <small class="text-muted">By <?= htmlspecialchars($row['username']) ?> on <?= $row['created_at'] ?></small><br>
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']): ?>
                        <a href="posts/edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning mt-2">Edit</a>
                        <a href="posts/delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger mt-2" onclick="return confirm('Delete this post?')">Delete</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No posts found.</p>
    <?php endif; ?>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

</body>
</html>

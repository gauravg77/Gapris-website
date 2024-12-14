<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
?>

<h1>Welcome to the Shop, <?php echo htmlspecialchars($_SESSION['user']['name']); ?>!</h1>
<a href="logout.php">Logout</a>

<?php
include "config.php";
if($_SESSION['user_role'] == 0){

  header('Location: {$hostname}/admin/post.php');
}
if (!isset($_GET['id'])) {
    die("ID Not Set");
}

$user_id = $_GET['id'];

// Use prepared statements to prevent SQL injection
$sql = "DELETE FROM user WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    header("Location: {$hostname}/admin/users.php");
    exit();
} else {
    echo "<div class='alert alert-danger'>Delete Failed</div>";
}

$stmt->close();
$conn->close();
?>

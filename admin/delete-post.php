<?php
include "config.php";
$post_id = $_GET['id'];
$cat_if = $_GET['catid'];

$sql = "DELETE FROM post WHERE post_id = {$post_id}";
$sql1 = "UPDATE category SET post = post - 1 WHERE category_id = {$cat_if}";

if (mysqli_query($conn, $sql) && mysqli_query($conn, $sql1)) {
    header("Location: {$hostname}/admin/post.php");
}

?>
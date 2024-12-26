<?php


include "config.php";

if (isset($_FILES['fileToUpload'])) {
    $errors = array();

    $file_name = $_FILES['fileToUpload']['name'];
    $file_size = $_FILES['fileToUpload']['size'];
    $file_tmp = $_FILES['fileToUpload']['tmp_name'];
    $file_type = $_FILES['fileToUpload']['type'];
    
    // Fix the notice by assigning the result of explode to a variable
    $file_ext_array = explode('.', $file_name);
    $file_ext = strtolower(end($file_ext_array));
    
    $extensions = array("jpeg", "jpg", "png", "gif", "bmp", "webp");
    if (in_array($file_ext, $extensions) === false) {
        $errors[] = "Extension not allowed, please choose a JPEG or PNG file.";
    }
    if ($file_size > 2097152) {
        $errors[] = 'File size must be exactly 2 MB';
    }
    if (empty($errors)) {
        move_uploaded_file($file_tmp, "upload/" . $file_name);
    } else {
        print_r($errors);
        die();
    }
}

session_start();
$title = mysqli_real_escape_string($conn, $_POST['post_title']);
$desc = mysqli_real_escape_string($conn, $_POST['postdesc']);
$category = mysqli_real_escape_string($conn, $_POST['category']);
$date = date("d M, Y");
$author = $_SESSION["user_id"];

// Use separate queries for better error handling
$sql1 = "INSERT INTO post (title, description, category, post_date, author, post_img) VALUES ('$title', '$desc', '$category', '$date', '$author', '$file_name')";
$sql2 = "UPDATE category SET post = post + 1 WHERE category_id = {$category}";

if (mysqli_query($conn, $sql1)) {
    if (mysqli_query($conn, $sql2)) {
        header("Location: {$hostname}/admin/post.php");
        exit();
    } else {
        echo "Error updating category: " . mysqli_error($conn);
    }
} else {
    echo "Error inserting post: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
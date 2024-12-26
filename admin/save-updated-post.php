<?php
include "config.php";

if(isset($_POST['submit'])) {
    if(empty($_FILES['new-image']['name'])){
        // If no new image is selected, keep the old image
        $file_name = $_POST['old-image'];
    } else {
        // If new image is selected
        $errors = array();
        $file_name = $_FILES['new-image']['name'];
        $file_size = $_FILES['new-image']['size'];
        $file_tmp = $_FILES['new-image']['tmp_name'];
        $file_type = $_FILES['new-image']['type'];
        
        // Get file extension
        $file_parts = explode('.', $file_name);
        $file_ext = strtolower(end($file_parts));
        
        $extensions = array("jpeg","jpg","png");
        
        if(!in_array($file_ext, $extensions)){
            $errors[] = "Extension not allowed, please choose a JPEG or PNG file.";
        }
        
        if($file_size > 2097152){
            $errors[] = 'File size must be exactly 2 MB';
        }
        
        if(empty($errors)){
            // Remove old image if exists
            $old_image = $_POST['old-image'];
            if(file_exists($old_image)) {
                unlink($old_image);
            }
            
            // Upload new image
            move_uploaded_file($file_tmp, "upload/".$file_name);
            $file_name = "upload/".$file_name;
        } else {
            print_r($errors);
            die();
        }
    }

    // Update database
    $sql = "UPDATE post SET 
            title = '" . mysqli_real_escape_string($conn, $_POST['post_title']) . "',
            description = '" . mysqli_real_escape_string($conn, $_POST['postdesc']) . "',
            category = " . mysqli_real_escape_string($conn, $_POST['category']) . ",
            post_img = '" . mysqli_real_escape_string($conn, $file_name) . "'
            WHERE post_id = " . mysqli_real_escape_string($conn, $_POST['post_id']);
            
    $result = mysqli_query($conn, $sql) or die("Query Failed: " . mysqli_error($conn));
    
    if($result) {
        header("Location: {$hostname}/admin/post.php");
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

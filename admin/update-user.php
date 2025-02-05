<?php 
include "header.php"; 
include 'config.php';
if($_SESSION['user_role'] == 0){

  header("Location: {$hostname}/admin/post.php");
}

if(isset($_POST['submit'])){
    

    $userid = mysqli_real_escape_string($conn, $_POST['user_id']);
    $fname = mysqli_real_escape_string($conn, $_POST['f_name']);
    $lname = mysqli_real_escape_string($conn, $_POST['l_name']);
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
   
    $sql = "UPDATE user SET first_name = ?, last_name = ?, username = ?, role = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $fname, $lname, $user, $role, $userid);

    if ($stmt->execute()) {
        header("Location: {$hostname}/admin/users.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Update Failed</div>";
    }
    $stmt->close();
    $conn->close();
}
?>

<div id="admin-content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="admin-heading">Modify User Details</h1>
            </div>
            <div class="col-md-offset-4 col-md-4">
                <?php 
                include "config.php";
                if(!isset($_GET['id'])){
                    die("ID Not Set");
                }
                $user_id = $_GET['id'];
                $sql = "SELECT * FROM user WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                ?>

                <!-- Form Start -->
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <div class="form-group">
                        <input type="hidden" name="user_id" class="form-control" value="<?php echo $row['user_id']; ?>" placeholder="">
                    </div>
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="f_name" class="form-control" value="<?php echo $row['first_name']; ?>" placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="l_name" class="form-control" value="<?php echo $row['last_name']; ?>" placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label>User Name</label>
                        <input type="text" name="username" class="form-control" value="<?php echo $row['username']; ?>" placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label>User Role</label>
                        <select class="form-control" name="role">
                            <option value="0" <?php echo ($row['role'] == 0) ? 'selected' : ''; ?>>Normal User</option>
                            <option value="1" <?php echo ($row['role'] == 1) ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>
                    <input type="submit" name="submit" class="btn btn-primary" value="Update" />
                </form>
                <!-- /Form -->
                <?php 
                    }
                }
                $stmt->close();
                $conn->close();
                ?>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>

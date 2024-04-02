<?php
include("css/css/css.php");
include("include/db.php");
include("include/session.php");
include("include/sidebar.php");

?>
<style media="screen">
.custom-label {
display: inline-block;
background-color: #007bff;
color: #fff;
padding: 10px 15px;
cursor: pointer;
}

#imageInput {
display: none;
}

</style>
<?php 
$msg = "";
if(isset($_POST['submit0'])){
    $username = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $role = $_POST['role'];
    $totalPost = $_POST['total-post'];

    $stmt = $connect->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    if($stmt -> rowCount() > 0){
        $msg = '<div class="alert alert-danger" role="alert">Email already taken</div>';
    }else{
        $insert = "INSERT INTO `users`(`username`, `email`, `status`, `password`,`role`, `users_can_post`) VALUES ('$username','$email','OFF','$password','$role','$totalPost')";
        $insert = $connect->prepare($insert);
        $insert ->execute();
        if($insert){
            $msg = '<div class="alert alert-success" role="alert">Added user successfully</div>';
        }else{
            $msg = '<div class="alert alert-danger" role="alert">Failed to add user please try again later</div>';
        }
    }

}

?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-10 title">
			<h1><i class="fa fa-bars"></i> Add New Post</h1>
			<?php echo failed_alert(); echo success_alert(); echo add_post();?>
		</div>

		<div class="col-sm-12">
			<div class="row">
                <?php echo $msg; ?>
            <form action="add-new-user.php" method="POST">
                <div style="display:flex" class="row">
                    <div style="width: 45%;margin-left: 30px;" class="col">
                    <input autocomplete="off" type="text" name="name" class="form-control" placeholder="Username">
                    </div>
                    <div style="width: 45%;margin-left: 30px;"  class="col">
                    <input autocomplete="off" type="email" name="email" class="form-control" placeholder="Email">
                    </div>
                </div>
                <br>
                <div style="display:flex" class="row">
                    <div style="width: 45%;margin-left: 30px;" class="col">
                    <input autocomplete="off" name="password" type="password" class="form-control" placeholder="Password">
                    </div>
                    <div style="width: 45%;margin-left: 30px;"  class="col">
                    <select autocomplete="off" name="role" class="form-control">
                        <option disabled default selected>Select Role</option>
                        <option value="2">Editor</option>
                        <option value="3">Author</option>
                        <option value="4">Subscriber</option>
                        <option value="5">Visitor</option>
                    </select>
                    </div>
                </div>
                <br>
                <div style="display:flex" class="row">
                    <div style="width: 45%;margin-left: 30px;" class="col">
                    <select autocomplete="off" autocomplete="off" name="total-post" class="form-control" id="total-post">
                        <option default selected>Select Number of post</option>
                    </select>
                    </div>
                    <div style="width: 45%;margin-left: 30px;"  class="col">
                    <input autocomplete="off" name="skill" type="text" class="form-control" placeholder="Skill">
                    </div>
                </div>
                    <br>
                <div style="text-align: center;">
                    <button type="submit" name="submit0" class="btn btn-success">Add User</button>
                </div>
            </form>
			</div>
		</div>
	</div>
</div>

<script>
  const totalPost = document.getElementById('total-post');

  for (let i = 1; i <= 50; i++) {
    const option = document.createElement('option');
    option.value = i;
    option.text = i;
    totalPost.appendChild(option);
  }
</script>
<?php include("include/footer.php"); ?>

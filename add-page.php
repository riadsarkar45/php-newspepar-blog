<?php
include("css/css/css.php");
include("include/db.php");
include("include/session.php");
include("include/sidebar.php");
$msg = "";
if(isset($_POST['add'])){
  $title = $_POST['title'];
  $link = $_POST['link'];
  $insert = "INSERT INTO `pages`(`title`, `link`) VALUES ('$title','$link')";
  $insert = $connect->prepare($insert);
  $insert -> execute();
  if($insert){
    $msg = "Page created successfully";
  }else{
    $msg = "Failed to creat page. Please try again later";
  }
}

?>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-10 title">
			<!-- <h1><i class="fa fa-bars"></i> Add New Post</h1> -->
			<?php echo failed_alert(); echo success_alert(); echo creat_page();?>
		</div>
<br>
    <div class="form-control">Add Page</div>
		<div class="col-sm-12">
			<div class="row">
        <form action="add-page.php" method="POST">
            <div class="form-group row">
              <label for="colFormLabelLg" class="col-sm-2 col-form-label col-form-label-lg"></label>
              <div style="width: 68%;" class="col-sm-10">
              <br/>
              <br/>
              <br/>
              <br/>
              <?php echo $msg; ?>
              <form action="add-page.php">
                  <div class="form-group">
                    <label for="formGroupExampleInput">Page Title</label>
                    <input type="text" name="title" class="form-control" id="formGroupExampleInput" placeholder="Page Title" required>
                  </div>
                  <div class="form-group">
                    <label for="formGroupExampleInput2">Page Link</label>
                    <input type="text" name="link" class="form-control" id="formGroupExampleInput2" placeholder="Page Link" required>
                  </div>
                  <div style="text-align: center">
                    <button type="submit" name="add" class="btn btn-success">Submit</button>
                  </div>
              </form>
              </div>
            </div>
        </form>
			</div>
		</div>
	</div>
</div>
<?php include("include/footer.php"); ?>

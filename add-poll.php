<?php
include("css/css/css.php");
include("include/db.php");
include("include/session.php");
include("include/sidebar.php");

$msg = "";

if(isset($_POST['add'])){
  $question = $_POST['question'];
  $options = $_POST['inputField']; // Assuming inputField is the name attribute for your options field
  $insert = "INSERT INTO `poll_question`(`question`) VALUES ('$question')";
  $insert = $connect->prepare($insert);
  $insert->execute();
  $questionId = $connect->lastInsertId();
  if($insert){
    foreach ($options as $option){
        $insert = "INSERT INTO `polloptions`(`questionId`, `options`) VALUES ('$questionId', '$option')";
        $insert = $connect->prepare($insert);
        $insert->execute();
    }
    $msg = "Page created successfully";
  } else {
    $msg = "Failed to create page. Please try again later";
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
        <form action="add-poll.php" method="POST">
          <div class="form-group row">
            <label for="colFormLabelLg" class="col-sm-2 col-form-label col-form-label-lg"></label>
            <div style="width: 68%;" class="col-sm-10">
              <br/>
              <?php echo $msg; ?>
              <div class="form-group">
                <input type="text" name="question" class="form-control" id="formGroupExampleInput" placeholder="Page Title" required>
              </div>
              <div id="inputContainer">
                <div class="form-group flex gap-1">
                  <input type="text" name="inputField[]" class="form-control" placeholder="Page Options" required>
                  <button type="button" id="addInput" class="btn btn-default">+</button>
                </div>
              </div>
              <div style="text-align: center">
                <button type="submit" name="add" class="btn btn-success">Submit</button>
              </div>
            </div>
          </div>
        </form>
			</div>
		</div>
	</div>
</div>

<script>
document.getElementById('addInput').addEventListener('click', function() {
  var inputContainer = document.getElementById('inputContainer');
  var newInput = document.createElement('div');
  newInput.className = 'form-group flex gap-1';
  newInput.innerHTML = `<input type="text" name="inputField[]" class="form-control" placeholder="Page Options" required>
                        <button type="button" class="btn btn-default removeInput">-</button>`;
  inputContainer.appendChild(newInput);
});

document.addEventListener('click', function(event) {
  if (event.target && event.target.classList.contains('removeInput')) {
    event.target.parentNode.remove();
  }
});
</script>

<?php include("include/footer.php"); ?>

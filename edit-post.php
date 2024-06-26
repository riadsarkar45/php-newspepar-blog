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
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-10 title">
			<h1><i class="fa fa-bars"></i> Edit Post</h1>
			<?php
				echo failed_alert();
				echo success_alert();
			 ?>
		</div>

		<div class="col-sm-12">
			<div class="row">
				<?php

				if (isset($_GET["postId"])) {
					$postId = $_GET['postId'];
					$post = $_GET["postId"];
					$stmt = $connect->prepare("SELECT * from post where id = $postId order by id desc");
					$stmt->execute();
					$posts = $stmt->fetchAll();
					foreach ($posts as $rows) {
						$title = $rows['title'];

					}
				}
				?>
						<form method="post" action="edit-post.php?postId=<?php echo $postId ?>" enctype="multipart/form-data">
							<div class="col-sm-9">
								<div class="form-group">
									<input autocomplete="off" value="<?php echo $title ?>" id="textInput" type="text" name="title" class="form-control" placeholder="Enter title here">
									<span id="text-limitation-error"></span>
								</div>
								<div id="second-input" class="form-group ">
									<input autocomplete="off" id="textInput2" type="text" name="title" class="form-control hidden" placeholder="Enter title here">

								</div>
								<div class="form-group">
									<input autocomplete="off" value="<?php echo $rows['single_p_title'] ?>" type="text" name="single_title" class="form-control" placeholder="Enter single page title here">
								</div>
								<div class="form-group">
									<textarea value="<?php echo $rows['descs'] ?>" class="form-control" name="description" rows="15"></textarea>
									<div class="col-sm-12 word-count">Word count: 0</div>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="content publish-box">
									
									<div class="row">
										<div class="col-sm-12 main-button">
											<button id="publish-btn" name="update" class="btn btn-primary pull-right">Update</button>
										</div>
									</div>
								</div>

								<div class="content cat-content">
									<h4>Category <span class="pull-right"><i class="fa fa-chevron-down"></i></span></h4>
									<hr>

									<?php
									$count_cat = $connect->query("SELECT COUNT(*) FROM category")->fetchColumn();
									$totalRecords = $count_cat; // Retrieve the total number of records from your database

									// Step 2: Define the number of records per page and calculate the total number of pages
									$recordsPerPage = 6;
									$totalPages = ceil($totalRecords / $recordsPerPage);

									// Step 3: Determine the current page number
									$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

									// Step 4: Query the database to fetch records for the current page

									$offset = ($currentPage - 1) * $recordsPerPage;
									$stmt = $connect->prepare("select * from category order by date desc limit $offset, $recordsPerPage");
									$stmt->execute();
									$cat = $stmt->fetchAll();
									if ($stmt->rowCount() > 0) {
										foreach ($cat as $row) {

									?>
											<p><label for="cat6"><input type="checkbox" value="<?php echo $row['cat_n']; ?>" name="category[]" id="cat6"><?php echo $row['cat_n']; ?></label></p>
										<?php
										}
									} else {
										?>
										<p><label for="cat5">No category added</label></p>
									<?php } ?>
									<?php
									echo '<ul class="pagination">';
									for ($page = 1; $page <= $totalPages; $page++) {
										$activeClass = ($page == $currentPage) ? 'active' : '';
										echo '<li class="' . $activeClass . '"><a href="add-post.php?page=' . $page . '">' . $page . '</a></li>';
									}
									echo '</ul>';
									?>
								</div>
								<div class="content featured-image">
									<h4>Featured Image <span class="pull-right"><i class="fa fa-chevron-down"></i></span></h4>
									<hr>
									<input type="file" name="image" id="file" class="inputfile" style="display: none;">
									<label for="imageInput" class="custom-label">Select Image</label>
									<input type="file" id="imageInput" name="image" accept="image/*" onchange="handleImageChange(event)">
									<img id="displayImage" src="#" alt="Selected Image" style="max-width: 100%; display: none;">
								</div>
							</div>
						</form>
				
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function handleImageChange(event) {
		const selectedFile = event.target.files[0];
		const displayImage = document.getElementById('displayImage');

		if (selectedFile) {
			displayImage.style.display = 'block';
			displayImage.src = URL.createObjectURL(selectedFile);
		} else {
			displayImage.style.display = 'none';
			displayImage.src = '';
		}

		// Hide the label after selecting an image
		const label = document.querySelector('.custom-label');
		label.style.display = 'none';

	}

	// word limitation start here


	const textLimit = document.getElementById('text-limitation-error');
	const publishBtn = document.getElementById('publish-btn');
	let textInput2 = document.getElementById('textInput2');
	textInput.addEventListener('input', updateWordCount);

	// Function to update the word count
	function updateWordCount() {
		let text = textInput.value;
		console.log('texts', text)
		const words = text.trim().split(/\s+/); // Split by one or more whitespace characters
		const wordCount = words.length;
		console.log('wordcount', wordCount)
		if (wordCount >= 30) {
			textInput.style.background = 'rgb(255, 173, 173)';
			//textInput.setAttribute("disabled", "true");
			textLimit.innerText = `Please shorten the title to meet the character limit and try again.`;
			textLimit.style.color = 'red';
			publishBtn.setAttribute("disabled", "true")
		} else {
			textInput.style.background = 'white';
			publishBtn.removeAttribute("disabled")
			textLimit.innerText = '';
		}

		if (wordCount === 1) {
			textInput2.classList.add('hidden');
		} else {
			textInput2.classList.remove('hidden');
			textInput2.value = text.toLowerCase().replace(/\s+/g, '-');
			textInput2.setAttribute('disabled', "true")
		}
	}
	// Call the function initially to set the initial word count to 0
	updateWordCount();
</script>
<?php

if (isset($_POST["update"])) {
	$title = isset($_POST['title']) ? $_POST['title'] : "";
	$desc = isset($_POST["description"]) ? $_POST["description"] : "";
	$slugTitle = creatSlugTitles($title);
	$category = isset($_POST['category']) ? $_POST['category'] : "";
	$firstCategory = first_cat($category);
	$updatePost = "UPDATE `post` SET `title`= '$title', `post_slug` = '$slugTitle', `category` = '$firstCategory' WHERE id = $postId";
    $update = $connect->prepare($updatePost)-> execute();
    
	if($update){
		echo 'Update Successful';
	}else {
		echo 'Update Failed';
	}
}


include("include/footer.php");
?>
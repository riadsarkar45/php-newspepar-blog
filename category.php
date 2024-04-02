<?php
include("css/css/css.php");
include("include/db.php");
include("include/session.php");
include("include/sidebar.php");

?>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12 title">
			<h1><i class="fa fa-bars"></i> Categories</h1>
		</div>
<?php echo failed_alert(); echo success_alert(); echo add_category(); echo trashed();?>
		<div class="col-sm-4 cat-form">
			<h3>Add New Category</h3>
			<form action="category.php" method="post">
				<div class="form-group">
					<label>Name</label>
					<input type="text" name="category_name" id="category_name" class="form-control">
					<p>The name is how it appears on your site.</p>
				</div>

				<div class="form-group">
					<label>Slug</label>
					<input type="text" name="slug" id="slug" class="form-control" readonly="">
					<p>The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.</p>
				</div>

				<div class="form-group">
					<label>Parent Category</label>
					<select name="parent" class="form-control">
						<option>None</option>
					</select>
					<p>Categories, unlike tags, can have a hierarchy. You might have a Jazz category, and under that have children categories for Bebop and Big Band. Totally optional.</p>
				</div>
				<div class="form-group">
					<label>Description</label>
					<textarea class="form-control" name="dscription" rows="5"></textarea>
					<p>The description is not important and will not be displayed.</p>
				</div>
				<div class="form-group">
					<button name="add_cat" class="btn btn-primary">Add New Category</button>
				</div>
			</form>


		</div>

		<div class="col-sm-8 cat-view">
			<div class="row">
				<div class="col-sm-3">
				<form action="category.php" method="post">
					<select name="bulk-action" class="form-control">
						<option>Bulk Action</option>
						<option value="2">Move to Trash</option>
					</select>
				</div>
				<div class="col-sm-2">
					<button name="trash" class="btn btn-default">Apply</button>
				</div>
				<div class="col-sm-3 col-sm-offset-4">
					<input type="text" id="search" name="search" class="form-control" placeholder="Search Category">
				</div>
			</div>
			<div class="content">
					<table class="table table-striped">
						<thead>
							<tr>
								<th><input type="checkbox" id="select-all"> Name</th>
								<th>Description</th>
								<th>Slug</th>
								<th>Count</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$count_cat = $connect->query("SELECT COUNT(*) FROM category")->fetchColumn();
								$totalRecords = $count_cat; // Retrieve the total number of records from your database

								// Step 2: Define the number of records per page and calculate the total number of pages
								$recordsPerPage = 5;
								$totalPages = ceil($totalRecords / $recordsPerPage);

								// Step 3: Determine the current page number
								$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

								// Step 4: Query the database to fetch records for the current page
								$offset = ($currentPage - 1) * $recordsPerPage;
							 	$stmt = $connect->prepare("select * from category order by date desc limit $offset, $recordsPerPage");
								$stmt -> execute();
								$cat = $stmt -> fetchAll();
								foreach($cat as $row){
							 ?>
							<tr>
								<td>
									<input type="checkbox" value="<?php echo $row['id']; ?>" name="cat[]">
									<a href="#"><?php echo $row['cat_n']; ?></a>
								</td>
								<td><?php echo $row['description']; ?></td>
								<td><?php echo $row['slug']; ?></td>
								<td><?php echo $row['status']; ?></td>
							</tr>
						</tbody>
					<?php } ?>
					</table>
				</form>
			</div>
			<div class="row">
				<div class="col-sm-12">
			        <ul class="pagination">
								<?php
									 echo '<ul class="pagination">';
										for ($page = 1; $page <= $totalPages; $page++) {
										    $activeClass = ($page == $currentPage) ? 'active' : '';
										    echo '<li class="' . $activeClass . '"><a href="category.php?page=' . $page . '">' . $page . '</a></li>';
										}
										echo '</ul>';
								 ?>
			        </ul>
			    </div>
			</div>
		</div>
	</div>
</div>

<?php include("include/footer.php"); ?>

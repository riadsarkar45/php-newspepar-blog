<?php
include("css/css/css.php");
include("include/db.php");
include("include/session.php");
include("include/sidebar.php");
?>

<?php ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-10 title">
			<h1><i class="fa fa-bars"></i> DASHBOARD</h1>
		</div>

		<div class="col-sm-12">
			<div class="content">
				<h6> 
					<?php echo failed_alert();
						echo success_alert();
						echo  user_section();
					 ?>
				</h6>
				<form action="users.php" method="post">
					<div class="form-material">
						<div class="input-field">
							<select class="select-item1" name="item1">
								<option>Bulk Action</option>
								<option value="1">Delete</option>
							</select>
							<button class="btns" type="submit" name="apply">Apply</button>
						</div>
						<div class="second-form">
							<select class="select-item1" name="item2">
								<option selected disabled default>User Roles</option>
								<option value="1">Administrator</option>
								<option value="2">Editor</option>
								<option value="3">Author</option>
								<option value="4">Subscriber</option>
								<option value="5">Visitor</option>
							</select>
							<button class="btns" type="submit" name="change">Change</button>
						</div>
					</div>

					<table class="table table-dark">
						<thead>
							<tr>
								<th scope="col">SrNo</th>
								<th scope="col">Name</th>
								<th scope="col">Email</th>
								<th scope="col">Comments</th>
								<th scope="col">Status</th>
								<th scope="col">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$srno = 0;
							$stmt = $connect->prepare("select * from comment");
							$stmt->execute();
							$users = $stmt->fetchAll();
							foreach ($users as $row) {
								$id = $row['id'];
								$postId = $row['post_id'];
                                    $status = "Pending";
                                    $cls = "warning";
								if ($row['status'] == 'ON') {
									$status = "Approved";
                                    $cls = "success";
								}
								$srno++;
							?>
								<tr>
									<th scope="row"><?php echo $srno; ?></th>
									<td><?php echo $row['name']; ?></td>
									<td><?php echo $row['email']; ?></td>
									<td><?php echo $row['comment']; ?></td>
									<td> <span class="label label-<?php echo $cls; ?>"><?php echo $status; ?></span>  </span> </td>
									<td>
                                        <span><a class="label label-success" href="user-approv.php?cmtApprove=<?php echo $id; ?>&postId=<?php echo $postId ?>">Approve</a></span>
                                        |
                                        <span><a class="label label-warning" href="user-approv.php?cmtUnApprov=<?php echo $id; ?>&postId=<?php echo $postId ?>">UnApprove</a></span>
                                        |
                                        <span><a class="label label-danger" href="user-approv.php?cmtDelete=<?php echo $id; ?>&postId=<?php echo $postId ?>">Delete</a></span>
                                    </td>										
									
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</form>
			</div>
		</div>
	</div>
</div>
<?php include("include/footer.php"); ?>
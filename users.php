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
								<th scope="col">#</th>
								<th scope="col">Username</th>
								<th scope="col">Email</th>
								<th scope="col">Status</th>
								<th scope="col">Post</th>
								<th scope="col">Role</th>
								<th scope="col">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$srno = 0;
							$stmt = $connect->prepare("select * from users");
							$stmt->execute();
							$users = $stmt->fetchAll();
							foreach ($users as $row) {
								$id = $row['id'];
								if ($row['role'] == '1') {
									$role = "Administrator";
								}
								if ($row['role'] == '2') {
									$role = "Editor";
								}
								if ($row['role'] == '3') {
									$role = "Author";
								}
								if ($row['role'] == '4') {
									$role = "Subscriber";
								}
								if ($row['role'] == '5') {
									$role = "Visitor";
								}
								if ($row['status'] == 'ON') {
									$roles = "ON";
									$cls = "success";
									$clas = "warning";
									$btns = "UnApprove";
									$links = "user-approv.php?unappr=";
								}
								if ($row['status'] == 'OFF') {
									$roles = "OFF";
									$cls = "warning";
									$clas = "success";
									$btns = "Approve";
									$links = "user-approv.php?appr=";
								}
								$btn = "Suspend User";
								$lnk = "ban-user.php";
								$clss = "danger";
								if ($row['block'] == '2') {
									$roles = "Suspended Account";
									$cls = "danger";
									$btn = "Enable";
									$clss = "warning";
									$lnk = "enable-user.php";
								}

								// code...



								if ($row['users_can_post'] == NULL) {
									$formatted_percentage = "0";
								} else {
									$maximum_posts = $row['users_can_post'];
									$user_posts = $row['users_total_post'];

									$percentage = ($user_posts / $maximum_posts) * 100;
									$formatted_percentage = number_format($percentage, 0);
								}






								$srno++;
							?>
								<tr>
									<th scope="row"> <input type="checkbox" name="user[]" value="<?php echo $id; ?>"> <?php echo $srno; ?></th>
									<td><?php echo $row['username']; ?></td>
									<td><?php echo $row['email']; ?></td>
									<td> <span class="label label-<?php echo "$cls"; ?>"> <?php echo $roles; ?> </span> </td>
																																																			
									<td>
										<div class="progress">
											<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?php echo $formatted_percentage; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $formatted_percentage; ?>%">
											<?php echo $formatted_percentage; ?>% Complete
											</div>
										</div>
									<td>
									<td><?php echo $role; ?></td>
									<td>
										<span class="label label-<?php echo $clss; ?>"> <a style="color: white; font-weight: bolder;" href="<?php echo $lnk; ?>?user=<?php echo $row['id']; ?>&name=<?php echo $row['username']; ?>"><?php echo $btn; ?></a></span> |
										<span class="label label-<?php echo $clas; ?>"> <a style="color: white; font-weight: bolder;" href="<?php echo $links; ?><?php echo $row['id']; ?>&name=<?php echo $row['username']; ?>"><?php echo $btns; ?></a> </span>
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
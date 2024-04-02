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
			<h2> <?php echo failed_alert();
					echo success_alert(); ?></h2>
			<div class="content">

				<div style="display: flex; gap: 3px;">

					<div class="p-2 bg-green-500 bg-opacity-20" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); height: 10rem; width: 27rem;  align-items: center; justify-content: center;">
						<div class="p-3" style="display: flex; justify-content: space-between;">
							<?php

							$count_cat = $connect->query("SELECT COUNT(*) FROM users")->fetchColumn();
							$totalRecords = $count_cat;
							?>
							<h2 class="text-4xl">All Users</h2>
							<span class="text-4xl"><?php echo $totalRecords ?></span>
						</div>
					</div>

					<div class="p-2 bg-green-500 bg-opacity-30" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); height: 10rem; width: 27rem;  align-items: center; justify-content: center;">
						<div class="p-3" style="display: flex; justify-content: space-between;">
							<?php

							$count_cat = $connect->query("SELECT COUNT(*) FROM users WHERE status = 'ON'")->fetchColumn();
							$totalRecords = $count_cat;
							?>
							<h2 class="text-4xl">Active Users</h2>
							<span class="text-4xl"><?php echo $totalRecords ?></span>
						</div>
					</div>

					<div class="p-2 bg-red-500 bg-opacity-20" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); height: 10rem; width: 27rem;  align-items: center; justify-content: center;">
						<div class="p-3" style="display: flex; justify-content: space-between;">
							<?php

							$count_cat = $connect->query("SELECT COUNT(*) FROM users WHERE status = 'OFF'")->fetchColumn();
							$totalRecords = $count_cat;
							?>
							<h2 class="text-4xl">Inactive Users</h2>
							<span class="text-4xl"><?php echo $totalRecords ?></span>
						</div>
					</div>



					<div class="p-2 bg-green-500 bg-opacity-20" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); height: 10rem; width: 27rem;  align-items: center; justify-content: center;">
						<div class="p-3" style="display: flex; justify-content: space-between;">
							<?php

							$count_cat = $connect->query("SELECT COUNT(*) FROM post")->fetchColumn();
							$totalRecords = $count_cat;
							?>
							<h2 class="text-4xl">Total Posts</h2>
							<span class="text-4xl"><?php echo $totalRecords ?></span>
						</div>
					</div>


				</div>

				<div class="overflow-x-auto mt-10 bg-gray-200 bg-opacity-20 shadow-sm">
					<?php

					$limit = 5;
					$page = isset($_GET['user_page']) ? $_GET['user_page'] : 1;
					$offset = ($page - 1) * $limit;

					$total_rows_stmt = $connect->prepare('SELECT COUNT(*) AS total FROM users');
					$total_rows_stmt->execute();
					$total_rows = $total_rows_stmt->fetch(PDO::FETCH_ASSOC)['total'];
					$total_pages = ceil($total_rows / $limit);

					$stmt = $connect->prepare('SELECT * FROM users LIMIT :offset, :limit');
					$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
					$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
					$stmt->execute();
					$users = $stmt->fetchAll();


					?>
					<table class="table">
						<!-- head -->
						<thead>
							<tr>
								<th>
									<label>
										<input type="checkbox" class="checkbox" />
									</label>
								</th>
								<th>Name</th>
								<th>Role</th>
								<th>Status</th>
								<th>Online</th>
							</tr>
						</thead>
						<tbody id="usersIds">
							<?php
							foreach ($users as $rows) {
								$status = 'Pending';
								$class = 'warning';
								if ($rows['status'] === 'ON') {
									$status = 'Approved';
									$class = 'success';
								}

								if ($rows['role'] == '1') {
									$role = "Administrator";
								}
								if ($rows['role'] == '2') {
									$role = "Editor";
								}
								if ($rows['role'] == '3') {
									$role = "Author";
								}
								if ($rows['role'] == '4') {
									$role = "Subscriber";
								}
								if ($rows['role'] == '5') {
									$role = "Visitor";
								}

								$time = time();

								$isOnline = 'danger';
								$text = 'Offline';
								if ($rows['last_Logn'] > $time) {
									$isOnline = 'success';
									$text = 'Online';
								}


							?>

								<tr>
									<th>
										<label>
											<input type="checkbox" class="checkbox" />
										</label>
									</th>
									<td>
										<div>
											<div class="font-bold"><?php echo $rows['username'] ?></div>
										</div>
									</td>
									<td>
										<span class="badge badge-ghost badge-sm"><?php echo $role ?></span>
									</td>
									<td>
										<span class="label label-<?php echo $class ?>"><?php echo $status ?></span>
									</td>
									<th>
										<span class="label label-<?php echo $isOnline ?>"><?php echo $text ?></span>
									</th>
								</tr>
							<?php } ?>
						</tbody>
					</table>

					<!-- <div class="pagination">
								<?php
								for ($i = 1; $i <= $total_pages; $i++) {
									if ($i == $page) {
										echo '<span class="btn btn-secondary">' . $i . '</span>'; // Current page
									} else {
										echo '<a href="?user_page=' . $i . '" class="btn btn-primary">' . $i . '</a>'; // Other pages
									}
								}

								if ($page < $total_pages) {
									echo '<a href="?user_page=' . ($page + 1) . '" class="btn btn-primary">Next</a>';
								}

								?>
							</div> -->
				</div>
				<div class="flex mt-11">

					<div class=" bg-gray-200 bg-opacity-20 shadow-md w-2/3">
						<div class="p-3 text-4xl border-b mb-3 border-gray-300">
							<h2>All Posts</h2>
						</div>
						<div class="overflow-x-auto">
							<?php

							$limit = 5;

							$page = isset($_GET['page']) ? $_GET['page'] : 1;
							$offset = ($page - 1) * $limit;

							$total_rows_stmt = $connect->prepare("SELECT COUNT(*) AS total FROM post");
							$total_rows_stmt->execute();
							$total_rows = $total_rows_stmt->fetch(PDO::FETCH_ASSOC)['total'];

							$total_pages = ceil($total_rows / $limit);

							$stmt = $connect->prepare("SELECT * FROM post LIMIT :offset, :limit");
							$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
							$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
							$stmt->execute();
							$users = $stmt->fetchAll();



							?>
							<table class="table">
								<thead>
									<tr>
										<th>
											<label>
												<input type="checkbox" class="checkbox" />
											</label>
										</th>
										<th>Title</th>
										<th>Author</th>
										<th>Status</th>
										<th>Action</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($users as $rows) {
										$title = $rows['title'];
										$words = explode(" ", $title);
										$limitedText = implode(" ", array_slice($words, 0, 5));
										$status = $rows['status'];

										$class = 'success';
										$status = 'Approved';
										if ($rows['status'] === 'OFF') {
											$class = 'warning';
											$status = 'pending';
										}
									?>
										<tr>
											<th>
												<label>
													<input type="checkbox" class="checkbox" />
												</label>
											</th>
											<td>
												<div class="flex items-center gap-3">
													<div>
														<div class="font-bold"><?php echo $limitedText . '...'; ?></div>
													</div>
												</div>
											</td>
											<td>
												<?php echo $rows['author']; ?>
											</td>
											<td>
												<span class="label label-<?php echo $class ?>"><?php echo $status ?></span>
											</td>
											<td>
												<button class="btn btn-danger btn-sm">Delete</button>
											</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>

							<!-- Pagination -->
							<div class="pagination">
								<?php

								for ($i = 1; $i <= $total_pages; $i++) {
									if ($i == $page) {
										echo '<span class="btn btn-secondary">' . $i . '</span>'; // Current page
									} else {
										echo '<a href="?page=' . $i . '" class="btn btn-primary">' . $i . '</a>'; // Other pages
									}
								}

								// Next page link
								if ($page < $total_pages) {
									echo '<a href="?page=' . ($page + 1) . '" class="btn btn-primary">Next</a>';
								}
								?>
							</div>


						</div>
					</div>
					<div class="bg-gray-300 bg-opacity-20 w-5/12  shadow-md">
						<div id="chart"></div>
						<div>
							<div class="border-b border-gray-200">
								<h2 class="text-3xl mb-4">Total Traffics</h2>
							</div>
							<div class="overflow-x-auto mt-4">
								<table class="table">
									<!-- head -->
									<thead>
										<tr>
											<th>SrNo</th>
											<th>Date</th>
											<th>Visits</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$srno = 0;
										$stmt = $connect->prepare("select * from visite");
										$stmt->execute();
										$visits = $stmt->fetchAll();
										foreach ($visits as $visit) {
											$date = $visit['date'];
											$totalVisit = $visit['browser_ip'];
											$srno++;

										?>
											<tr>
												<th><?php echo $srno ?></th>
												<td><?php echo $date ?></td>
												<td><?php echo $totalVisit ?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>









				<!-- <?php

						$stmt = $connect->prepare("select * from post where drafted = 'YES'");
						$stmt->execute();
						$rows = $stmt->fetchAll();
						foreach ($rows as $row) {




						?>
					<div class="row">
						<div class="accordion" id="accordionExample">
							<div class="card">
								<div class="card-header" id="headingOne">
									<h2 class="mb-0">
										<button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
											Collapsible Group Item #1
										</button>
									</h2>
								</div>

								<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
									<div class="card-body">
										Some placeholder content for the first accordion panel. This panel is shown by default, thanks to the <code>.show</code> class.
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?> -->
			</div>

			<div class="content">
				<div class="col-sm-3">
					<img src="images/logo-big.png" width="100%">
				</div>
				<div class="col-sm-9">
					<p><b><a href="http://www.webtrickshome.com" target="_blank">Webtrickshome.com</a></b> The ultimate home for prospective web developers.</p>
					<div class="row">
						<ul class="nav navbar-nav">
							<li><a href="#">photoshop</a></li>
							<li><a href="#">html</a></li>
							<li><a href="#">css</a></li>
							<li><a href="#">jquery</a></li>
							<li><a href="#">php basics</a></li>
							<li><a href="#">procedural php</a></li>
							<li><a href="#">object oriented php</a></li>
							<li><a href="#">laravel</a></li>
							<li><a href="#">wordpress</a></li>
						</ul>
					</div>
					<p>
						<a href="https://www.facebook.com/webtrickshome/" target="_blank" class="btn btn-primary"><i class="fa fa-facebook"></i></a>
						<a href="https://www.youtube.com/channel/UCcfzunR364Vv1NUWTKk78QA" target="_blank" class="btn btn-danger"><i class="fa fa-youtube"></i></a>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>


<footer>
	<div class="col-sm-6">
		Copyright &copy; 2018 <a href="http://www.webtrickshome.com">Webtrickshome.com</a> All rights reserved.
	</div>
	<div class="col-sm-6">
		<span class="pull-right">Version 2.2.3</span>
	</div>
</footer>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/app.min.js"></script>
<script type="text/javascript" src="js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
	<?php
	$labels = ['Approved', 'Pending', 'Drafted'];
	$series = [];

	// Initialize counters for each status category
	$statusCounts = array(
		'ON' => 0,
		'OFF' => 0,
		'DRAFTED' => 0
	);

	$stmt = $connect->prepare("SELECT status FROM post");
	$stmt->execute();
	$rows = $stmt->fetchAll();

	foreach ($rows as $row) {
		$status = strtoupper($row['status']); // Convert status to uppercase

		// Increment counter for the corresponding status category
		switch ($status) {
			case 'ON':
				$statusCounts['ON']++;
				break;
			case 'OFF':
				$statusCounts['OFF']++;
				break;
			case 'DRAFTED':
				$statusCounts['DRAFTED']++;
				break;
			default:
				// Handle unknown status
				break;
		}
	}

	$series[] = $statusCounts['ON'];
	$series[] = $statusCounts['OFF'];
	$series[] = $statusCounts['DRAFTED'];
	?>

	var options = {
		chart: {
			width: 380,
			type: 'pie',
		},
		labels: <?php echo json_encode($labels); ?>,
		series: <?php echo json_encode($series); ?>
	};

	document.addEventListener("DOMContentLoaded", function() {
		var chart = new ApexCharts(document.querySelector("#chart"), options);
		chart.render();
	});
	console.log('Status Counts:', <?php echo json_encode($statusCounts); ?>);
</script>

<script>
	function updateUserStatus() {
		jQuery.ajax({
			url: 'updateUserStatus.php',
			success: function() {

			}
		})
	}

	function getUpdatedUsers() {
		jQuery.ajax({
			url: 'getUpdatedUsers.php',
			success: function(result) {
				jQuery('#usersIds').html(result)
			}
		})
	}
	setInterval(function() {
		updateUserStatus()
	}, 1000)
	setInterval(function() {
		getUpdatedUsers()
	}, 1000)
</script>






</body>

</html>
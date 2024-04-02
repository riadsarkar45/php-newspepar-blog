<?php
include("css/css/css.php");
include("include/db.php");
include("include/session.php");
Confirm_login();
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
				<h2> <?php echo failed_alert(); echo success_alert();  echo user_banned();  echo band_noti()?></h2>
        <?php
          $user_id = $_GET['user'];
          $stmt = $connect->prepare("select * from users where id = $user_id");
          $stmt ->execute();
          $users = $stmt->fetchAll();
          foreach ($users as $row) {
            $username = $row['username'];
          }
         ?>
        <form action="ban-user.php?user=<?php echo $user_id = $_GET['user']; ?>&name?=<?php echo $username; ?>" method="post">
          <div class="banned reason">
            <h2>Reason</h2>
            <textarea name="reason" rows="8" cols="80" class="form-control" placeholder="User banned reason"></textarea>
          </div>
          <div class="button">
            <span><button <?php echo band_notis(); ?> class="label label-danger" type="submit" name="ban">Suspend User</button></span>
          </div>
        </form>
			</div>
		</div>
	</div>
</div>
<?php include("include/footer.php"); ?>

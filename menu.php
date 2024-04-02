<?php
include("css/css/css.php");
include("include/db.php");
include("include/session.php");
include("include/sidebar.php");
$msg = "";
if(isset($_POST['submit#'])){
  $url = $_POST['url'];
  $linkText = $_POST['link-text'];
  $insert = "INSERT INTO `menu`(`menu`, `url`, `status`) VALUES ('$linkText','$url', '2')";
  $insert = $connect->prepare($insert);
  $insert ->execute();
  if($insert){
    $msg = "Menu Created";
  }else{
    $msg = "Failed to add menu. Please try again later";
  }
}
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12 title">
			<h1><i class="fa fa-bars"></i> MENU</h1>
		</div>
<?php  echo failed_alert(); echo success_alert(); ?>
		<div class="col-sm-4">
		  <div class="panel-group" id="accordion">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Posts <span class="pull-right"><i class="fa fa-chevron-down"></i></span></a>
            </h4>
          </div>
          <div id="collapse1" class="panel-collapse collapse in">
            <form action="menu.php" method="post">
							<div class="panel-body">
	              <div class="col-sm-12 panel-bordered-box">
									<?php
										$stmt = $connect->prepare("select * from post order by date");
										$stmt ->execute();
										$menus = $stmt ->fetchAll();
										foreach ($menus as $menu) {
											$title  = $menu['title'];

									 ?>
	                <p><input type="checkbox" name="postid[]" value="<?php echo $title; ?>"><?php echo $title; ?></p>
								<?php } ?>
	              </div>
	              <input type="checkbox" id="select-all" style="display: none;">
	              <label for="select-all">Select All</label> <button name="add_menu" class="btn btn-sm btn-default pull-right">Add to Menu</button>
	            </div>
            </form>
          </div>
        </div>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Pages <span class="pull-right"><i class="fa fa-chevron-down"></i></span></a>
            </h4>
          </div>
          <div id="collapse2" class="panel-collapse collapse">
            <form action="menu.php" method="post">
              <div class="panel-body">
                <div class="col-sm-12 panel-bordered-box">
                  <?php
                      $stmt = $connect->prepare("select * from pages");
                      $stmt ->execute();
                      $menus = $stmt ->fetchAll();
                      foreach ($menus as $menu) {
                        $title  = $menu['title'];

                  ?>
                  <p><input type="checkbox" value="<?php echo $title; ?>" name="page[]"><?php echo $title; ?></p>
                  <?php } ?>
                </div>
                <input type="checkbox" id="select-all" style="display: none;">
                <label for="select-all">Select All</label> <button name="addPage" class="btn btn-sm btn-default pull-right">Add to Menu</button>
              </div>
            </form>
          </div>
        </div>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Custom Links <span class="pull-right"><i class="fa fa-chevron-down"></i></span></a>
            </h4>
          </div>
          <div id="collapse3" class="panel-collapse collapse">
            <div class="panel-body">
              <form action="menu.php" class="form-horizontal" method="post">
                <div class="form-group">
                  <label class="control-label col-sm-4">URL</label>
                  <div class="col-sm-8">
                    <input name="url" type="text" class="form-control" placeholder="http://">
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-4">Link Text</label>
                  <div class="col-sm-8">
                    <input name="link-text" type="text" class="form-control">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <button name="submit#" class="btn btn-default pull-right">Add to Menu</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapse4">Categories <span class="pull-right"><i class="fa fa-chevron-down"></i></span></a>
            </h4>
          </div>
          <div id="collapse4" class="panel-collapse collapse">
            <form action="menu.php" method="post">
							<div class="panel-body">
	              <div class="col-sm-12 panel-bordered-box">
									<?php
										$stmt = $connect->prepare("select * from category");
										$stmt ->execute();
										$cat = $stmt->fetchAll();
										foreach($cat as $row){
											$cat = $row['cat_n'];

									 ?>
	                <p><input type="checkbox" value="<?php echo $cat; ?>" name="category[]"><?php echo $cat; ?></p>
								<?php } ?>
	              </div>
	              <input type="checkbox" id="select-all" style="display: none;">
	              <label for="select-all">Select All</label> <button name="add_cat" class="btn btn-sm btn-default pull-right">Add to Menu</button>
	            </div>
            </form>
          </div>
        </div>
      </div>
		</div>

    <div class="col-sm-8">
      <div class="content menu-structure">
          <h3>Menu Structure</h3>
          <p>Drag each item into the order you prefer. Click the arrow on the right of the item to reveal additional configuration options.</p>
          <?php echo $msg; ?>
          <form action="menu.php" method="post">
            <ul class="nav" id="my-ui-list">
              <?php
								$stmt = $connect->prepare("select * from menu where status = '2' order by date");
								$stmt ->execute();
								$menu = $stmt->fetchAll();
								foreach ($menu as $row) {
							 ?>
							 <li class="scrolable"><?php echo $row['menu']; ?></li>
						 <?php } ?>
              <?php
							 echo add_menu();
               echo  save_menu();
							 //echo add_cat();
							 //unset($_SESSION['post']); // Unset the session variable after displaying the selected options
							 //unset($_SESSION['postCat']); // Unset the session variable after displaying the selected options
							 ?>
            </ul>
            <button name="save_menu" class="btn btn-primary">Save Menu</button>
          </form>
      </div>
    </div>
	</div>
</div>

<?php include("include/footer.php"); ?>
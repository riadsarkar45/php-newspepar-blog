<?php
 include("functions.php");
 Confirm_login();
 $userId = $_SESSION["User_Id"];
 deleteAccountWhileOnline($userId, $connect);
 $menu = rolePermission($userId, $connect);
?>
<style media="screen">
.banned.reason {
    display: flex;
    justify-content: space-around;
    align-items: center;
    width: 85%;
    margin: 50px;
    gap: 25px;
}
textarea.form-control {
    border: 2px dotted lightgray;
    border-radius: 5px;
}
button.label.label-danger {
    margin-left: 49%;
    font-size: 25px;
    border: none;
}
button.label.label-primary {
    margin-left: 49%;
    font-size: 25px;
    border: none;
}
.form-material {
    display: flex;
    align-items: center;
    gap: 25px;
    margin: 13px;
}
select.select-item1 {
    width: 180px;
    padding: 6px;
    /* border: 1px dashed rgb(125 91 91 / 26%); */
}
button.btns {
    border: 1px solid blue;
    padding: 5px 21px 6px 22px;
    background: white;
    color: blue;
    margin-left: 10px;
    border-radius: 2px;
}
.btns:hover{
  color: blue;
  background-color: lightblue;
}

</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="sidebar">
	<ul class="sidebar-menu">
		<li><a href="index.php" class="dashboard"><i class="fa fa-tachometer"></i> <span>Dashboard</span></a></li>
		<li <?php echo $menu; ?> class="treeview">
            <a href="#">
              <i class="fa fa-bookmark-o"></i> <span>Posts</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="all-posts.php"><i class="fa fa-eye"></i>All Posts</a></li>
              <li><a href="add-post.php"><i class="fa fa-plus-circle"></i>Add Posts</a></li>
              <li><a href="category.php"><i class="fa fa-plus-circle"></i>Categories</a></li>
            </ul>
        </li>
        <li <?php echo $menu; ?> class="treeview">
            <a href="#">
              <i class="fa fa-image"></i> <span>Gallery</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="media-new.php"><i class="fa fa-plus-circle"></i>Add Images</a></li>
              <li><a href="add-new-media.php"><i class="fa fa-plus-circle"></i>Add Videos</a></li>
            </ul>
        </li>
        <li <?php echo $menu; ?> class="treeview">
            <a href="#">
              <i class="fa fa-file"></i> <span>Pages</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="#"><i class="fa fa-eye"></i>All Pages</a></li>
              <li><a href="add-page.php"><i class="fa fa-plus-circle"></i>Add Pages</a></li>
            </ul>
        </li>
        <li <?php echo $menu; ?> class="treeview">
            <a href="menu.php">
              <i class="fa fa-file"></i> <span>Menu</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
        </li>
        <li <?php echo $menu; ?> class="treeview">
            <a href="comments.php">
              <i class="fa fa-comment"></i> <span>Comments</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
        </li>
        <li <?php echo $menu; ?> class="treeview">
            <a href="#">
              <i class="fa fa-bar-chart"></i> <span>Reports</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="reports.php"><i class="fa fa-eye"></i>All Reports</a></li>
            </ul>
        </li>
        <li <?php echo $menu; ?> class="treeview">
            <a href="#">
              <i class="fa fa-user-plus"></i> <span>Users</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="users.php"><i class="fa fa-eye"></i>All Users</a></li>
              <li><a href="add-new-user.php"><i class="fa fa-plus-circle"></i>Add Users</a></li>
            </ul>
        </li>
        <li <?php echo $menu; ?> class="treeview">
            <a href="#">
              <i class="fa fa-user-plus"></i> <span>Add New Poll</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="add-poll.php"><i class="fa fa-eye"></i>Add Poll</a></li>
            </ul>
        </li>
        <li <?php echo $menu; ?> class="treeview">
            <a href="settings.php">
              <i class="fa fa-gear"></i> <span>Settings</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
        </li>
        <li <?php echo $menu; ?> class="treeview">
            <?php 
              $stmt = $connect->prepare("select * from themes where status = 2");
              $stmt ->execute();
              $thm = $stmt->fetchAll();
              foreach($thm as $row){
                $folder = $row["folder_name"];
            ?>
            <a href="../content/themes/<?php echo $folder; ?>">
              <i class="fa fa-gear"></i> <span>Themes</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <?php } ?>
            
            <!-- <a href="show-database-table.php">
              <i class="fa fa-database"></i> <span>Database</span>
            </a> -->

            <li><a href="logout.php"><i class="fa fa-power-off"></i>Log Out</a></li>
        </li>
        <!-- <li class="treeview">
            <a href="#">
              <i class="fa fa-address-book"></i> <span>Active User</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="#"><i class="fa fa-edit"></i>Edit Profile</a></li>
              <li><a href="logout.php"><i class="fa fa-power-off"></i>Log Out</a></li>
            </ul>
        </li> -->
	</ul>
</div>

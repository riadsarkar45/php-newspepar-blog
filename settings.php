<?php
include("css/css/css.php");
include("include/db.php");
include("include/session.php");
include("include/sidebar.php");

?>
<style>
    h4.title {
    font-size: 18px;
}
</style>
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12 title">
      <h1><i class="fa fa-bars"></i> Add New Posts <button class="btn btn-sm btn-default">Add New</button></h1>
    </div>
    <div class="search-div">
      <div class="col-sm-9">
        <?php
            $trashed_post = $connect->query("SELECT COUNT(*) FROM post where trash = '2'")->fetchColumn();
            $published_post = $connect->query("SELECT COUNT(*) FROM post where status = 'ON'")->fetchColumn();
         ?>
        All(6) | <a href="#">Published (<?php echo $published_post; ?>)</a> | <a href="#">Trashed Post (<?php echo $trashed_post; ?>)</a>
      </div>
<?php echo trashed(); ?>
      <div class="col-sm-3">
        <input type="text" id="search" name="search" class="form-control" placeholder="Search Posts">
      </div>
    </div>

    <div class="clearfix"></div>

    <div style="display: flex; align-items: center; gap: 36px; margin-left: 56px;" class="filter-div">
        <h4 class="title">
            Site Title
        </h4>
        <input style="margin-left: 270px; width: 33%;padding: 7px;border: 1px solid black;border-radius: 3px;" type="text" placeholder="Site Title">
    </div>
    <div style="display: flex; align-items: center; gap: 36px; margin-left: 56px;" class="filter-div">
        <h4 class="title">
            Tagline
        </h4>
        <input style="margin-left: 282px; width: 33%;padding: 7px;border: 1px solid black;border-radius: 3px;" type="email" placeholder="Site Title">
    </div>
    <div style="display: flex; align-items: center; gap: 36px; margin-left: 56px;" class="filter-div">
        <h4 class="title">
            Adminestrator Email Address
        </h4>
        <input style="margin-left: 115px; width: 33%;padding: 7px;border: 1px solid black;border-radius: 3px;" type="email" placeholder="Site Title">
    </div>
    <div style="display: flex; align-items: center; gap: 36px; margin-left: 56px;" class="filter-div">
        <h4 class="title">
            Membership
        </h4>
        <input style="margin-left: 77px; width: 33%;padding: 7px;border: 1px solid black;border-radius: 3px;" type="checkbox">
        <p>Anyone can register</p>
    </div>
    <div style="display: flex; align-items: center; gap: 36px; margin-left: 56px;" class="filter-div">
        <h4 class="title">
            New User Defauilt Role
        </h4>
        <input style="margin-left: 162px; width: 33%;padding: 7px;border: 1px solid black;border-radius: 3px;" type="email" placeholder="Site Title">
    </div>

    <div class="clearfix"></div>
  </div>
</div>

<?php include("include/footer.php"); ?>

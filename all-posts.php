<?php
include("css/css/css.php");
include("include/db.php");
include("include/session.php");
include("include/sidebar.php");

?>

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

    <div class="filter-div">


      <form action="all-posts.php" method="GET">
        <div class="col-sm-2">
          <select name="dates" class="form-control">
            <option disabled selected >All Dates</option>
            <?php
              $stmt = $connect->prepare("select * from post_date order by date desc");
              $stmt -> execute();
              $cat = $stmt ->fetchAll();
              if($stmt ->rowCount() > 0){
                foreach ($cat as $row) {

             ?>
             <option value="<?php echo $row['date']; ?>"><?php echo $row['date']; ?></option>
           <?php }}else{ ?>
             <option>No Dates Found</option>
          <?php } ?>
          </select>
        </div>
        <div class="col-sm-2">
          <select name="cats" class="form-control">
            <option disabled selected value="Cates">All Categories</option>
            <?php
              $stmt = $connect->prepare("select * from category where status = '1' order by date desc");
              $stmt -> execute();
              $cat = $stmt ->fetchAll();
              if($stmt ->rowCount() > 0){
                foreach ($cat as $row) {

             ?>
             <option value="<?php echo $row['cat_n']; ?>"><?php echo $row['cat_n']; ?></option>
             <?php
                 }
               }else{
              ?>
              <option>No Categories Found</option>
            <?php } ?>

          </select>
        </div>
        <div class="col-sm-2">
          <button name="filter" class="btn btn-default">Apply Filter</button>
        </div>
      </form>

      <form action="all-posts.php" method="post">
        <div class="col-sm-2">
          <select name="action" class="form-control">
            <option>Bulk Action</option>
            <option value="2">Move to Trash</option>
            <option value="3">Approve</option>
            <option value="4">UnApprove</option>
            <option value="5">Permanent Delete</option>
          </select>
        </div>

        <div class="col-sm-1">
          <div class="row">
            <button name="actions" class="btn btn-default">Apply</button>
          </div>
        </div>


    </div>

    <div class="col-sm-12">
      <div class="content">
        <table class="table table-striped" id="myTable">
          <thead>
            <tr>
              <th width="30%"><input type="checkbox" id="select-all"> Title</th>
              <th width="15%">Author</th>
              <th width="15%">Status</th>
              <th width="15%">Approved By</th>
              <th width="15%">Category</th>
              <th width="15%">Image</th>
              <th width="10%">Date</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
                $count_cat = $connect->query("SELECT COUNT(*) FROM post")->fetchColumn();
                $totalRecords = $count_cat; // Retrieve the total number of records from your database

                $recordsPerPage = 6;
                $totalPages = ceil($totalRecords / $recordsPerPage);

                $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

                $offset = ($currentPage - 1) * $recordsPerPage;
                if (isset($_GET['cats']) && isset($_GET['dates'])) {
                    $cat = $_GET['cats'];
                    $date = $_GET['dates'];
                    $stmt = $connect->prepare("SELECT * FROM post WHERE category = :cat AND date = :date AND trash = '1' ORDER BY date DESC limit $offset, $recordsPerPage");
                    $stmt->bindParam(':cat', $cat);
                    $stmt->bindParam(':date', $date);
                }elseif (isset($_GET['cats'])) {
                  $cat = $_GET['cats'];
                  $stmt = $connect->prepare("SELECT * FROM post WHERE category = :cat AND trash = '1' ORDER BY date DESC limit $offset, $recordsPerPage");
                  $stmt->bindParam(':cat', $cat);
                }elseif (isset($_GET['dates'])) {
                  $date = $_GET['dates'];
                  $stmt = $connect->prepare("SELECT * FROM post WHERE date = :date AND trash = '1' ORDER BY date DESC limit $offset, $recordsPerPage");
                  $stmt->bindParam(':date', $date);
                } else {
                    $stmt = $connect->prepare("SELECT * FROM post WHERE trash = '1' ORDER BY id DESC limit $offset, $recordsPerPage");
                }

                $stmt->execute();
                $upper_header = $stmt->fetchAll();
                if($stmt -> rowCount() >0){
                foreach ($upper_header as $row) {
                    //$writer_name = $header['writer_name'];
                    // Process each post here
                    // Example: echo $row['title'];
                    $status = "<span class='label label-warning'>Pending</span>";
                    if($row['status'] == 'ON'){
                      $status = "<span class='label label-success'>Approved</span>";
                    }
              ?>


            <tr>
              <td>
                <input type="checkbox" value="<?php echo $row['id']; ?>" name="select-cat[]">
                <a href="#"><?php echo $row['title'] ?></a>
              </td>
              <td><?php echo $row['author'] ?></td>
              <td><?php echo $status; ?></td>
              <td><?php echo $row['approved_by']; ?></td>
              <td><?php echo $row['category'] ?></td>
              <td> <img style="width: 50%;" src="images/<?php echo $row['image'] ?>" alt="<?php echo $row['image'] ?>"> </td>
              <td><?php echo $row['date'] ?></td>
              <td> <a href="edit-post.php?postId=<?php echo $row['id']  ?>"><span class="label label-warning">Edit</span></a> </td>
            </tr>
          <?php }}else{ ?>
            <tr class="alert alert-danger">
              <td>
                <a href="#">No post found</a>
              </td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
      </form>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="filter-div">


      <div class="col-sm-3 col-sm-offset-6">
        <ul class="pagination">
          <?php
             echo '<ul class="pagination">';
              for ($page = 1; $page <= $totalPages; $page++) {
                  $activeClass = ($page == $currentPage) ? 'active' : '';
                  echo '<li class="' . $activeClass . '"><a href="all-posts.php?page=' . $page . '">' . $page . '</a></li>';
              }
              echo '</ul>';
           ?>
        </ul>
      </div>
    </div>
  </div>
</div>

<?php include("include/footer.php"); ?>

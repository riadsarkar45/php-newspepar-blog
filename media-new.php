<?php
include("css/css/css.php");
include("include/db.php");
include("include/session.php");
include("include/sidebar.php");

?>
 <?php Confirm_Login(); ?>
<style>
      /* CSS styles for the table */
      table {
          width: 97%;
          border-collapse: collapse;
      }
      th, td {
          padding: 8px;
          text-align: left;
          border-bottom: 1px solid #ddd;
      }
      th {
          background-color: #f2f2f2;
      }
      tr:hover {
          background-color: #f5f5f5;
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
          $gallery_count = $connect->query("SELECT COUNT(*) FROM gallery")->fetchColumn();
          $pending = $connect->query("SELECT COUNT(*) FROM gallery WHERE status = 'OFF'")->fetchColumn();
          $published = $connect->query("SELECT COUNT(*) FROM gallery WHERE status = 'ON'")->fetchColumn();
          //$pending_count = $connect->query("SELECT COUNT(*) FROM gallery WHERE status = OFF")->fetchColumn();
          //$published_count = $connect->query("SELECT COUNT(*) FROM gallery WHERE status = ON")->fetchColumn();
         ?>
        Items(<?php echo $gallery_count; ?>) | <a href="#">Published (<?php echo $published; ?>)</a> | <a href="#">Pending Post (<?php echo $pending ?>)</a>
      </div>
<?php echo trashed(); echo headshot(); ?>
      <div class="col-sm-3">
        <input type="text" id="search" name="search" class="form-control" placeholder="Search Posts">
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="col-sm-12">
      <div class="content">
        <form style="float: right; margin-top: 2px;" action="media-new.php" method="GET">
          <select style="width: 139px; float: left;" class="form-control" name="filter1">
            <option selected disabled default>All Dates</option>
            <?php
              $stmt = $connect->prepare("select * from g_date");
              $stmt ->execute();
              $check = $stmt->fetchAll();
              foreach ($check as $row) {
             ?>
            <option value="<?php echo $row['g_date']; ?>"><?php echo $row['g_date']; ?></option>
          <?php } ?>
          </select>
            <select style="width: 139px; float: left;" class="form-control" name="filter">
              <option selected disabled default>All</option>
              <option value="1">Audio</option>
              <option value="mp4">video</option>
              <option value="jpg">Photo</option>
            </select>
          <button style="width: 139px; margin-left: 15rem;" type="submit" class="form-control" name="apply">Apply Filter</button>
        </form>
        <form action="media-new.php" method="post">
          <select style="width: 139px; float: left;" class="form-control" name="bulk_action">
            <option selected disabled default>Select Action</option>
            <option value="1">Approve</option>
            <option value="2">UnApprove</option>
            <option value="3">Permanent Delete</option>
          </select>
          <button style="width: 139px; margin-left: 15rem;" type="submit" class="form-control" name="headshot">Apply</button>

    </div>
  </div>
      <div style="margin-left: 10px;" class="nono">

              <!-- <div class="gallery">
                <a target="_blank" href="img_5terre.jpg">
                  <img src="../images/<?php echo $file; ?>" alt="<?php echo $row['file_type']; ?>" width="600" height="400">
                </a>
                <div class="desc">Add a description of the image here</div>
              </div> -->

            <?php //} ?>
              <!-- <div class="gallery">
                <a target="_blank" href="img_5terre.jpg">
                  <img src="../images/<?php echo $file; ?>" alt="<?php echo $row['file_type']; ?>" width="600" height="400">
                  <video style="width: 100%;" autoplay muted>
                    <source src="../images/<?php echo $row['file_type']; ?>" type="video/mp4">
                  </video>
                </a>
                <div class="desc">Add a description of the image here</div>
              </div> -->
              <table style="margin-top: 10rem; margin-left: 14px;">
                <thead>
                    <tr>
                        <th>File</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                      <?php
                      $count_cat = $connect->query("SELECT COUNT(*) FROM gallery")->fetchColumn();
                      $totalRecords = $count_cat; // Retrieve the total number of records from your database

                      // Step 2: Define the number of records per page and calculate the total number of pages
                      $recordsPerPage = 5;
                      $totalPages = ceil($totalRecords / $recordsPerPage);

                      // Step 3: Determine the current page number
                      $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

                      // Step 4: Query the database to fetch records for the current page
                      $offset = ($currentPage - 1) * $recordsPerPage;
                      if (isset($_GET['filter1']) && isset($_GET['filter'])) {
                        $filter1 = $_GET['filter1'];
                        $filter = $_GET['filter'];
                        $stmt = $connect->prepare("SELECT * FROM gallery WHERE date = :filter1 AND ext = :filter limit $offset, $recordsPerPage");
                        $stmt->bindParam(':filter1', $filter1);
                        $stmt->bindParam(':filter', $filter);
                      } elseif (isset($_GET['filter1'])) {
                        $filter1 = $_GET['filter1'];
                        $stmt = $connect->prepare("SELECT * FROM gallery WHERE date = :filter1 limit $offset, $recordsPerPage");
                        $stmt->bindParam(':filter1', $filter1);
                      } elseif (isset($_GET['filter'])) {
                        $filter = $_GET['filter'];
                        $stmt = $connect->prepare("SELECT * FROM gallery WHERE ext = :filter limit $offset, $recordsPerPage");
                        $stmt->bindParam(':filter', $filter);
                      } else {
                        $stmt = $connect->prepare("SELECT * FROM gallery ORDER BY date DESC limit $offset, $recordsPerPage");
                      }

                      $stmt->execute();
                      $media = $stmt->fetchAll();

                      if ($stmt->rowCount() > 0) {
                        foreach ($media as $row) {
                          $file = $row['file_type'];
                          //if ($row['ext'] == 'jpg') {
                          $status = "Published";
                          if($row['status'] == 'OFF'){
                            $status = "Pending";
                          }
                      ?>
                      <?php if($row['ext'] == 'jpg'){  ?>
                        <td>
                          <input type="checkbox" name="media[]" value="<?php echo $row['id']; ?>"><img style="width: 100px;" src="images/<?php echo $file; ?>" alt="<?php echo $file; ?>">&nbsp; &nbsp; &nbsp;
                          <?php echo $file; ?>
                        </td>
                      <?php } ?>
                      <?php if($row['ext'] == 'png'){  ?>
                        <td>
                          <input type="checkbox" name="media[]" value="<?php echo $row['id']; ?>"><img style="width: 100px;" src="images/<?php echo $file; ?>" alt="<?php echo $file; ?>">&nbsp; &nbsp; &nbsp;
                            <?php echo $file; ?>
                        </td>
                      <?php } ?>
                      <?php if($row['ext'] == 'mp4'){  ?>
                        <td><input type="checkbox" name="media[]" value="<?php echo $row['id']; ?>">
                          <video style="width: 100px;" muted autoplay>
                            <source src="images/<?php echo $file; ?>" type="video/mp4">
                         </video>&nbsp; &nbsp; &nbsp;
                         <?php echo $file; ?>
                      </td>
                      <?php } ?>
                        <!-- <td><img src="../images/<?php echo $file; ?>" alt="<?php echo $file; ?>"></td> -->
                        <td>Riad Sarkar</td>
                        <td><?php echo $status; ?></td>
                        <td><?php echo $row['date']; ?></td>
                    </tr>
                    <?php

                }
              } else {
              ?>
              <tr class="alert alert-danger">
                <td>
                  <a href="#">No post found</a>
                </td>
                <td></td>
                <td></td>
                <td></td>
              </tr>            <?php } ?>
                </tbody>
            </table>
            </form>
      </div>
  </div>

  <div class="col-sm-3 col-sm-offset-6">
    <ul style="margin-left: 36rem; width: 100%; margin-top: 10px;" class="pagination">
      <?php
         echo '<ul class="pagination">';
          for ($page = 1; $page <= $totalPages; $page++) {
              $activeClass = ($page == $currentPage) ? 'active' : '';
              echo '<li class="' . $activeClass . '"><a href="media-new.php?page=' . $page . '">' . $page . '</a></li>';
          }
          echo '</ul>';
       ?>
    </ul>
  </div>
  </div>
<!-- <img src="../images/323412.jpg" class="rounded mx-auto d-block" alt="..."> -->







<div class="clearfix"></div>




<img src="" alt="">


















<style>
li.left\; {
    float: left;
}
</style>

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


</body>
</html>

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

<div style="display: flex; align-items: center; gap: 36px; width: 43%; margin-left: 39px;" class="filter-div">

  <canvas width="54" height="40" id="postChart"></canvas>

<canvas width="54" height="40" id="postChart3"></canvas>
 
</div>

<div style="display: flex; align-items: center; gap: 36px; width: 43%; margin-left: 39px;" class="filter-div">

<canvas width="54" height="40" id="visitChart"></canvas>
 
</div>




<style>
.folder-icon {
    display: flex;
    align-items: center;
    margin: 5px 0;
}

.folder-icon img {
    width: 100%;
    margin-right: 5px;
}
</style>
    <div class="clearfix"></div>
  </div>
</div>

<?php
// Your database connection code here

// Calculate the start and end dates for the past week
$endDate = date('Y-m-d');
$startDate = date('Y-m-d', strtotime('-1 week', strtotime($endDate)));

// Fetch visit data for the past week
$stmtVisits = $connect->prepare("SELECT DATE(date) AS visit_date, COUNT(*) AS visit_count FROM visite WHERE date BETWEEN :start_date AND :end_date GROUP BY visit_date");
$stmtVisits->bindValue(':start_date', $startDate);
$stmtVisits->bindValue(':end_date', $endDate);
$stmtVisits->execute();
$visitData = $stmtVisits->fetchAll(PDO::FETCH_ASSOC);


$days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
$barColors = [];

foreach ($visitData as $row) {
    $count = $row['visit_count'];
    $barColors[] = $barColor;
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
        const days = <?php echo json_encode($days); ?>;
        const visitCounts = <?php echo json_encode(array_column($visitData, 'visit_count')); ?>;
        const barColors = <?php echo json_encode($barColors); ?>;
        
        const ctx = document.getElementById('visitChart').getContext('2d');
        const visitChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: days,
                datasets: [{
                    label: 'Visits',
                    data: visitCounts,
                    backgroundColor: barColors,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>





<?php
//$connect = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");

$stmt = $connect->prepare("SELECT * FROM users");
$stmt->execute();
$usersData = $stmt->fetchAll(PDO::FETCH_ASSOC);

$usernames = [];
$postCounts = [];
$postLimits = [];

foreach ($usersData as $userData) {
    $username = $userData["username"];
    $userPost = $userData["users_total_post"];
    $userPostLimit = $userData["users_can_post"]; // Assuming you have a column 'post_limit' in your users table
    
    $usernames[] = $username;
    $postCounts[] = $userPost;
    $postLimits[] = $userPostLimit;
}
?>




<script>
    var ctxs = document.getElementById('postChart').getContext('2d');
    var usernames = <?php echo json_encode($usernames); ?>;
    var postCounts = <?php echo json_encode($postCounts); ?>;
    var postLimits = <?php echo json_encode($postLimits); ?>;

    var datasetBackgroundColor = postCounts.map((count, index) => {
        if (postLimits[index] !== null && count === postLimits[index]) {
            return 'rgba(255, 0, 0, 0.6)'; // Red if post count equals post limit
        } else {
            return 'rgba(59, 99, 132, 0.6)'; // Blue for other cases
        }
    });

    var myChart = new Chart(ctxs, {
        type: 'bar',
        data: {
            labels: usernames,
            datasets: [{
                label: 'Total Post',
                data: postCounts,
                backgroundColor: datasetBackgroundColor,
                borderColor: 'rgba(34, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Posts'
                    }
                }
            }
        }
    });
</script>

<script>
    var ctx1 = document.getElementById('postChart1').getContext('2d');
    var usernames = <?php echo json_encode($usernames); ?>;
    var postCounts = <?php echo json_encode($postCounts); ?>;
    var postLimits = <?php echo json_encode($postLimits); ?>;

    var datasetBackgroundColor = postCounts.map((count, index) => {
        if (postLimits[index] !== null && count === postLimits[index]) {
            return 'rgba(255, 0, 0, 0.6)'; // Red if post count equals post limit
        } else {
            return 'rgba(59, 99, 132, 0.6)'; // Blue for other cases
        }
    });

    var myChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: usernames,
            datasets: [{
                label: 'Total Post',
                data: postCounts,
                backgroundColor: datasetBackgroundColor,
                borderColor: 'rgba(34, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Posts'
                    }
                }
            }
        }
    });
</script>






































<?php
//$connect = new PDO("mysql:host=localhost;dbname=your_database_name", "your_username", "your_password");

$stmt = $connect->prepare("SELECT * FROM post");
$stmt->execute();
$postsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

$usernames = [];
$statusCounts = ['Approved' => 0, 'Pending' => 0, 'Trashed' => 0];

foreach ($postsData as $postData) {
    //$username = $postData["username"]; // Assuming there is a username associated with each post
    $status = $postData["status"];
    $decline = $postData["trash"];
    
    $usernames[] = $username;
    
    if ($status === 'ON') {
        $statusCounts['Approved']++;
    } elseif ($status === 'OFF') {
        $statusCounts['Pending']++;
    }
    
    if ($decline === '2') {
        $statusCounts['Trashed']++;
    }
}
?>
    
    <script>
    var ctx2 = document.getElementById('postChart3').getContext('2d');
    var statuses = <?php echo json_encode(array_keys($statusCounts)); ?>;
    var statusCounts = <?php echo json_encode(array_values($statusCounts)); ?>;
    
    var datasetBackgroundColor = {
        'Approved': 'rgba(40, 167, 69, 0.6)', // Blue
        'Pending': 'rgba(255, 193, 7, 0.6)', // Yellow
        'Trashed': 'rgba(220, 53, 69, 0.6)' // Red
    };
    var statusLabels = {
        'Approved': 'Approved',
        'Pending': 'Pending',
        'Trashed': 'Trash'
    };

    var myChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: statuses,
            datasets: [{
                label: 'Post Status',
                data: statusCounts,
                backgroundColor: statuses.map(status => datasetBackgroundColor[status]),
                borderColor: 'rgba(34, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
          plugins: {
              tooltip: {
                  callbacks: {
                      label: function (context) {
                          var datasetLabel = context.label; // Get the label of the hovered data point
                          return statusLabels[datasetLabel] + ': ' + context.parsed.y;
                      }
                  }
              }
          },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Posts'
                    }
                }
            }
        }
    });
</script>












<?php include("include/footer.php"); ?>
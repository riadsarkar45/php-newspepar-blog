<?php
include("css/css/css.php");
include("include/db.php");
include("include/session.php");
include("include/sidebar.php");
include("include/functions.php");

$selectedDatabase = $dbname; // Replace with the actual selected database name
$tables = $connect->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

?>


<div class="container-fluid">
    <div class="row">
        <div class="col-sm-10 title">
            <h1><i class="fa fa-bars"></i> Structure</h1>
        </div>

        <div class="col-sm-12">
                <?php 
                    echo failed_alert();
					echo success_alert();
				?>
            <div class="content">
            <!-- ... rest of your HTML code ... -->
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Null</th>
                        <th>Key</th>
                        <th>Default</th>
                        <th>Extra</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $table = $_GET["table-name"];
                        $query = "DESCRIBE $table";
                        $result = $connect->query($query);
                        if($result){
                            while($row = $result->fetch(PDO::FETCH_ASSOC)){
                                echo "<tr>";
                                echo "<td>".$row['Field']."</td>";
                                echo "<td>".$row['Type']."</td>";
                                echo "<td>".$row['Null']."</td>";
                                echo "<td>".$row['Key']."</td>";
                                echo "<td>".$row['Default']."</td>";
                                echo "<td>".$row['Extra']."</td>";
                                echo "<td><a class='label label-danger' href='drop-column.php?column={$row['Field']}&table_name={$table}'>Drop</a></td>";
                                echo "</tr>";
                            }
                        }else{
                            echo "<tr>";
                            echo "<td colspan='6'>No Data Found</td>";
                            echo "</tr>";
                        }
                    ?>
                    <tr>
                    
                    </tr>
                    <tr>
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include("include/footer.php"); ?>

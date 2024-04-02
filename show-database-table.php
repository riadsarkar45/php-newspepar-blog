<?php
include("css/css/css.php");
include("include/db.php");
include("include/session.php");
include("include/sidebar.php");

$selectedDatabase = $dbname; // Replace with the actual selected database name
$tables = $connect->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-10 title">
            <h1><i class="fa fa-bars"></i> DASHBOARD</h1>
        </div>

        <div class="col-sm-12">
                <?php 
                    echo failed_alert();
					echo success_alert();
				?>
            <div class="content">
            <!-- ... rest of your HTML code ... -->
            <table class="table">
                <a class="label label-primary" href="creat-table.php">Creat New Table</a>
                <thead>
                    <tr>
                    <th scope="col">SrNo</th>
                    <th scope="col">Table Name</th>
                    <th scope="col">Status</th>
                    <th scope="col">Date Of Creation</th>
                    <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                        $i = 0;
                        foreach ($tables as $table) {
                            // Retrieve table creation time from INFORMATION_SCHEMA
                            $query = "SELECT CREATE_TIME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?";
                            $stmt = $connect->prepare($query);
                            $stmt->execute([$selectedDatabase, $table]);
                            $creationTime = $stmt->fetchColumn();

                            

                            $rowCount = $connect->query("SELECT COUNT(*) FROM $table")->fetchColumn();
                            if ($rowCount === '0') {
                                $status = " (Table is empty)";
                            } else {
                                $status =  " (Not Empty) ";
                            }
                        $i++;
                    ?>
                    <tr>
                    <th scope="row"><?php echo $i ?></th>
                    <td><?php echo $table ?></td>
                    <td><?php echo $status ?></td>
                    <td><?php echo $creationTime ?></td>
                    <td><a  class="btn btn-danger" href="table-drop.php?table-name=<?php echo $table ?>">Drop</a> | <a  class="btn btn-info" href="table-structer.php?table-name=<?php echo $table ?>">Structure</a></td>
                    <td></td>
                    </tr>
                    <tr>
                    <?php } ?>
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include("include/footer.php"); ?>

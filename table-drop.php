<?php
include("css/css/css.php");
include("include/db.php");
include("include/session.php");
include("include/functions.php");
Confirm_login();

if (isset($_GET["table-name"])) {
    $table_name = $_GET["table-name"];
    $tableName = $table_name;
    $sql = "DROP TABLE $tableName";
    $sql = $connect->prepare($sql);
    $sql ->execute();
    if($sql){
        $_SESSION['message'] = "Table droped successfully";
        header("Location: show-database-table.php");
    }else{
        $_SESSION['message'] = "Failed to drop table";
        header("Location: show-database-table.php");
    }
}
?>
<?php
include("css/css/css.php");
include("include/db.php");
include("include/session.php");
include("include/functions.php");
Confirm_login();

if (isset($_GET["column"]) && $_GET["table_name"]) {
    $column_name = $_GET["column"];
    $tableName = $_GET["table_name"];
    $sql = "ALTER TABLE $tableName DROP COLUMN $column_name";    
    $sql = $connect->prepare($sql);
    $sql ->execute();
    if($sql){
        $_SESSION['message'] = "Column droped";
        header("Location: table-structer.php?table-name={$tableName}");
    }else{
        $_SESSION['message'] = "Failed to drop column";
        header("Location: table-structer.php?table-name={$tableName}");
    }
}
?>
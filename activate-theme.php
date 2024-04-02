<?php
include("include/db.php");
include("include/session.php");
include("include/functions.php");
Confirm_login();

if(isset($_GET["theme"])){
    $thme = $_GET["theme"];
    $upd = "UPDATE `themes` SET `folder_name`='$thme',`status`='2'";
    $upd = $connect->prepare($upd);
    $upd -> execute();
    if($upd){
        echo $msg = "Theme changed successfully";
        header("Location: themes.php");
    }else{
        echo $msg = "Failed to changed theme. Please try again later";
        header("Location: themes.php");
    }
    
}
?>
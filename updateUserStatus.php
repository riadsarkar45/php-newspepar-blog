
<?php
include("include/session.php");
include("include/db.php");

$time = time() +10;
$userId = $_SESSION["User_Id"];
$update = "UPDATE `users` SET `last_Logn`='$time' WHERE id = $userId";
$update = $connect->prepare($update);
$update->execute();
?> 
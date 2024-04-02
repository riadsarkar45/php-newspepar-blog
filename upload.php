<?php
include("css/css/css.php");
include("include/db.php");
include("include/session.php");
include("include/functions.php");
Confirm_login();


$image = $_FILES['file']['name'];
$temp_name1 = $_FILES['file']['tmp_name'];
$extension = pathinfo($image, PATHINFO_EXTENSION);
$allow_ext = array('jpg', 'png', 'mp4');
if(in_array($extension, $allow_ext)){
  $date =  date('F Y');
  move_uploaded_file($temp_name1, "../images/$image");
  $insert = "INSERT INTO `gallery`(`date`, `cat`, `file_type`, `ext`) VALUES ('$date','cat','$image','$extension')";
  $insert = $connect->prepare("$insert");
  $insert->execute();
  if($insert){
    $date =  date('F Y');
    $stmt = $connect->prepare("select * from g_date where g_date = $date");
    $stmt ->execute();
    $check = $stmt->fetchAll();
    if($stmt -> rowCount() > 0){

    }else{
      $insert = "INSERT INTO `g_date`(`g_date`) VALUES ('$date')";
      $insert = $connect->prepare($insert);
      $insert ->execute();
    }
  }else{
    echo "<script>alert('Failed to upload.')</script>";
  }
}else{
  echo "<script>alert('Invalid file extension. Only JPG, JPEG, and PNG files are allowed.')</script>";
}


?>

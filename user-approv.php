<?php
include("css/css/css.php");
include("include/db.php");
include("include/session.php");
include("include/functions.php");
Confirm_login();
//include("include/sidebar.php");
if(isset($_GET['appr'])){
  $msg = "";
  $userId = $_GET['appr'];
  $stmt = $connect->prepare("select * from users where id = $userId");
  $stmt -> execute();
  $checkUsers = $stmt->fetchAll();
  foreach($checkUsers as $row){
    if($row["role"] == '1'){
      $_SESSION['message'] = "This account is unchangable. The adminestrator got the mesage.";
      header("Location: users.php");
    }else{
      $update = "UPDATE `users` SET `status`='ON' WHERE id = $userId";
      $update = $connect->prepare($update);
      $update ->execute();
      if($update){
        $_SESSION['Message'] = "User Approved Successfully";
        header("Location: users.php");
      }
    }
  }
}
if(isset($_GET['unappr'])){
  $userId = $_GET['unappr'];
  $stmt = $connect->prepare("select * from users where id = $userId");
  $stmt -> execute();
  $checkUsers = $stmt->fetchAll();
  foreach($checkUsers as $row){
    if($row["role"] == '1'){
      $_SESSION['message'] = "This account is unchangable. The adminestrator got the mesage.";
      header("Location: users.php");
    }else{
      $update = "UPDATE `users` SET `status`='OFF' WHERE id = $userId";
      $update = $connect->prepare($update);
      $update ->execute();
      if($update){
        $_SESSION['Message'] = "User UnApproved Successfully";
        header("Location: users.php");
      }
    }
  }
}
if(isset($_GET['unappr'])){
  $userId = $_GET['unappr'];
  $stmt = $connect->prepare("select * from users where id = $userId");
  $stmt -> execute();
  $checkUsers = $stmt->fetchAll();
  foreach($checkUsers as $row){
    if($row["role"] == '1'){
      $_SESSION['message'] = "This account is unchangable. The adminestrator got the mesage.";
      header("Location: users.php");
    }else{
      $update = "UPDATE `users` SET `status`='OFF' WHERE id = $userId";
      $update = $connect->prepare($update);
      $update ->execute();
      if($update){
        $_SESSION['Message'] = "User UnApproved Successfully";
        header("Location: users.php");
      }
    }
  }
}
if(isset($_GET['cmtApprove'])){
  $userId = $_GET['cmtApprove'];
  $postId = $_GET['postId'];
  $update = "UPDATE `comment` SET `status`='ON' WHERE id = $userId";
  $update = $connect->prepare($update);
  $update ->execute();
  if($update){
    $_SESSION['Message'] = "Comment Approved Successfully";
    header("Location: comments.php");
    $update = "UPDATE `post` SET cmt= cmt+1 WHERE id = $postId";
    $update = $connect->prepare($update);
    $update ->execute();
  }else{
    $_SESSION['message'] = "Something went wrong";
    header("Location: comments.php");
  }
}
if(isset($_GET['cmtUnApprov'])){
  $postId = $_GET['postId'];
  $userId = $_GET['cmtUnApprov'];
  $update = "UPDATE `comment` SET `status`='OFF' WHERE id = $userId";
  $update = $connect->prepare($update);
  $update ->execute();
  if($update){
    $update = "UPDATE `post` SET `cmt`= cmt -1 WHERE id = $postId";
    $update = $connect->prepare($update);
    $update ->execute();
    $_SESSION['Message'] = "Comment UnApproved Successfully";
    header("Location: comments.php");
  }else{
    $_SESSION['message'] = "Something went wrong";
    header("Location: comments.php");
  }
}

if(isset($_GET['cmtDelete'])){
  $userId = $_GET['cmtDelete'];
  $postId = $_GET['postId'];
  $update = "DELETE FROM `comment` WHERE id = $userId";
  $update = $connect->prepare($update);
  $update ->execute();
  if($update){
    $_SESSION['message'] = "Comment Deleted Successfully";
    header("Location: comments.php");
  }else{
    $_SESSION['message'] = "Something went wrong";
    header("Location: comments.php");
  }
}
?>

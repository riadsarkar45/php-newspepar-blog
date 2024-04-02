<?php
include("db.php");

function add_post()
{
  global $connect;
  $userRole = $_SESSION["Role"];
  if (isset($_POST["submit"])) {
    $title = isset($_POST['title']) ? $_POST['title'] : "";
    $PostSlug = creatSlugTitles($title);
    $description = isset($_POST['description']) ? $_POST['description'] : "";
    $category = isset($_POST['category']) ? $_POST['category'] : "";
    $stuffPick = isset($_POST["staffPick"]) ? $_POST["staffPick"] : "";
    if (isset($_POST['tags']) && is_array($_POST['tags']) && count($_POST['tags']) > 0) {
      $firstTag = $_POST['tags'][0];
      //echo "First tag: " . $firstTag;
    }
    $cat = first_cat($category);
    //$tag = first_cat($tag);
    $image = $_FILES['image']['name'];
    $temp_name1 = $_FILES['image']['tmp_name'];
    move_uploaded_file($temp_name1, "images/$image");
    $date =  date('F Y');
    $add_post = "INSERT INTO `post`(`date`, `title`, `image`, `author`, `publisher`, `status`, `post_slug`, `tag`, `descs`, `single_p_title`, `writer`, `category`, `staffPick`)
     VALUES ('$date', '$title','$image','Riad Sarkar','Riad Sarkar','OFF','$PostSlug','$firstTag','$description','single page title','Riad', '$cat', '$stuffPick')";
    $insert = $connect->prepare($add_post);
    $insert->execute();
    $last_id  = $connect->lastInsertId();
    if ($insert) {

      $stmt = $connect->prepare("select * from users where role = '$userRole'");
      $stmt->execute();
      $userRole = $stmt->fetchAll();
      foreach ($userRole as $row) {
        if ($row['role'] === '1') {
          $updatePost = "UPDATE `post` SET `status`= 'ON' WHERE id = $last_id";
          $update = $connect->prepare($updatePost);
          $update->execute();
        }
      }

      if (isset($_POST['category'])) {
        foreach ($_POST['category'] as $cat) {
          $insert = "INSERT INTO `post_cat`(`cat_name`, `post_id`) VALUES ('$cat','$last_id')";
          $insert = $connect->prepare($insert);
          $insert->execute();
        }
      }
      $tags = isset($_POST['tags']) ? $_POST['tags'] : array();
      $tags = implode(',', $tags);

      // Make sure $tags is not an empty string before proceeding
      if (!empty($tags)) {
        $tagArray = explode(',', $tags); // Convert the string back to an array
        foreach ($tagArray as $tag) {
          $insert = "INSERT INTO `tags`(`tag`, `post_id`) VALUES ('$tag','$last_id')";
          $insert = $connect->prepare($insert);
          $insert->execute();
        }
      }

      $stmt = $connect->prepare("select * from post_date where date = '$date'");
      $stmt->execute();
      $cat = $stmt->fetchAll();
      if ($stmt->rowCount() > 0) {
      } else {
        $insert = "INSERT INTO `post_date`(`date`, `post_id`) VALUES ('$date','$last_id')";
        $insert = $connect->prepare("$insert");
        $insert->execute();
      }
      $userId = $_SESSION["User_Id"];
      $updatePost = "UPDATE `users` SET `users_total_post`=users_total_post+1 WHERE id = $userId";
      $update = $connect->prepare($updatePost);
      $update->execute();
    }
  }

  if (isset($_POST["draft"])) {
    $title = isset($_POST['title']) ? $_POST['title'] : "";
    $PostSlug = creatSlugTitles($title);
    $description = isset($_POST['description']) ? $_POST['description'] : "";
    $category = isset($_POST['category']) ? $_POST['category'] : "";
    if (isset($_POST['tags']) && is_array($_POST['tags']) && count($_POST['tags']) > 0) {
      $firstTag = $_POST['tags'][0];
      //echo "First tag: " . $firstTag;
    }
    $cat = first_cat($category);
    //$tag = first_cat($tag);
    $image = $_FILES['image']['name'];
    $temp_name1 = $_FILES['image']['tmp_name'];
    move_uploaded_file($temp_name1, "images/$image");
    $date =  date('F Y');
    $add_post = "INSERT INTO `post`(`date`, `title`, `image`, `author`, `publisher`, `status`, `post_slug`, `tag`, `descs`, `single_p_title`, `writer`, `category`, `drafted`)
     VALUES ('$date', '$title','$image','Riad Sarkar','Riad Sarkar','OFF','$PostSlug','$firstTag','$description','single page title','Riad', '$cat', 'YES')";
    $insert = $connect->prepare($add_post);
    $insert->execute();
    $last_id  = $connect->lastInsertId();
    if ($insert) {

      if (isset($_POST['category'])) {
        foreach ($_POST['category'] as $cat) {
          $insert = "INSERT INTO `post_cat`(`cat_name`, `post_id`) VALUES ('$cat','$last_id')";
          $insert = $connect->prepare($insert);
          $insert->execute();
        }
      }
      $tags = isset($_POST['tags']) ? $_POST['tags'] : array();
      $tags = implode(',', $tags);

      // Make sure $tags is not an empty string before proceeding
      if (!empty($tags)) {
        $tagArray = explode(',', $tags); // Convert the string back to an array
        foreach ($tagArray as $tag) {
          $insert = "INSERT INTO `tags`(`tag`, `post_id`) VALUES ('$tag','$last_id')";
          $insert = $connect->prepare($insert);
          $insert->execute();
        }
      }

      $stmt = $connect->prepare("select * from post_date where date = '$date'");
      $stmt->execute();
      $cat = $stmt->fetchAll();
      if ($stmt->rowCount() > 0) {
      } else {
        $insert = "INSERT INTO `post_date`(`date`, `post_id`) VALUES ('$date','$last_id')";
        $insert = $connect->prepare("$insert");
        $insert->execute();
      }
      $userId = $_SESSION["User_Id"];
      $updatePost = "UPDATE `users` SET `users_total_post`=users_total_post+1 WHERE id = $userId";
      $update = $connect->prepare($updatePost);
      $update->execute();
    }
  }
}

function add_category()
{
  global $connect;
  if (isset($_POST['add_cat'])) {
    $category_name = $_POST['category_name'];
    $slug = $_POST['slug'];
    $parent_cat = $_POST['parent'];
    $description = $_POST['dscription'];
    if (strlen($description) > 60) {
      //$_SESSION['message'] = "Only 60 charcter allowed for the description";
    } else {
      $add_post = "INSERT INTO `category`(`cat_n`, `slug`, `parent_c`, `description`, `status`) VALUES ('$category_name','$slug','$parent_cat','$description','1')";
      $insert = $connect->prepare($add_post);
      $insert->execute();
      if ($insert) {
        //$_SESSION['Message'] = "Category uploaded successfully";
      } else {
        //$_SESSION["message"] = "Failed to add category";
      }
    }
  }
}

function trashed()
{
  global $connect;
  if (isset($_POST['trash'])) {
    $opt = $_POST['bulk-action'];
    if (isset($_POST['cat'])) {
      if ($opt = $_POST['bulk-action'] == '2') {
        foreach ($_POST['cat'] as $id) {
          $trash = "UPDATE `category` SET `status`='2' WHERE id = '$id'";
          $trash = $connect->prepare($trash);
          $trash->execute();
          if ($trash) {
            //$_SESSION['Message'] = "Category trashed successfully";
          } else {
            //$_SESSION['message'] = "Something went wrong. Try again later";
          }
        }
      }
    }
  }
}
if (isset($_POST['actions'])) {
  $action = $_POST['action'];
  if (isset($_POST['select-cat'])) {
    if ($action = $_POST['action'] == '2') {
      foreach ($_POST['select-cat'] as $ids) {
        $trash = "UPDATE `post` SET `trash`='2' WHERE id = '$ids'";
        $trash = $connect->prepare($trash);
        $trash->execute();
        if ($trash) {
          //$_SESSION['Message'] = "Post movied to trash successfully";
        } else {
          //$_SESSION['message'] = "Something went wrong. Try again later";
        }
      }
    } elseif ($opt = $_POST['action'] == '3') {
      $session = $_SESSION['User_Name'];
      foreach ($_POST['select-cat'] as $ids) {
        $trash = "UPDATE `post` SET `status`='ON', `trash`='1', `approved_by` = '$session' WHERE id = '$ids'";
        $trash = $connect->prepare($trash);
        $trash->execute();
        if ($trash) {
          //$_SESSION['Message'] = "Post movied to trash successfully";
        } else {
          //$_SESSION['message'] = "Something went wrong. Try again later";
        }
      }
    } elseif ($opt = $_POST['action'] == '4') {
      foreach ($_POST['select-cat'] as $ids) {
        $trash = "UPDATE `post` SET `status`='OFF', `trash`='1' WHERE id = '$ids'";
        $trash = $connect->prepare($trash);
        $trash->execute();
        if ($trash) {
          //$_SESSION['Message'] = "Post unapproved successfully";
        } else {
          //$_SESSION['message'] = "Something went wrong. Try again later";
        }
      }
    } elseif ($opt = $_POST['action'] == '5') {
      foreach ($_POST['select-cat'] as $ids) {
        $trash = "DELETE FROM `post` WHERE id = '$ids'";
        $trash = $connect->prepare($trash);
        $trash->execute();
        if ($trash) {
          //$_SESSION['Message'] = "Post movied to trash successfully";
        } else {
          //$_SESSION['message'] = "Something went wrong. Try again later";
        }
      }
    } elseif ($opt = $_POST['action'] == '6') {
      foreach ($_POST['select-cat'] as $ids) {
        $trash = "UPDATE `post` SET `status`='OFF', `trash`='1' WHERE id = '$ids'";
        $trash = $connect->prepare($trash);
        $trash->execute();
        if ($trash) {
          //$_SESSION['Message'] = "Post unapproved successfully";
        } else {
          //$_SESSION['message'] = "Something went wrong. Try again later";
        }
      }
    }
  }
}

function headshot()
{
  global $connect;
  if (isset($_POST['headshot'])) {
    if (isset($_POST['media'])) {
      if ($opt = $_POST['bulk_action'] == '1') {
        foreach ($_POST['media'] as $ids) {
          $update = "UPDATE `gallery` SET `status`='ON' WHERE id = $ids";
          $update = $connect->prepare($update);
          $update->execute();
        }
      } elseif ($opt = $_POST['bulk_action'] == '3') {
        foreach ($_POST['media'] as $ids) {
          $update = "DELETE FROM `gallery` WHERE id  = $ids";
          $update = $connect->prepare($update);
          $update->execute();
        }
      } elseif ($opt = $_POST['bulk_action'] == '2') {
        foreach ($_POST['media'] as $ids) {
          $update = "UPDATE `gallery` SET `status`='OFF' WHERE id = $ids";
          $update = $connect->prepare($update);
          $update->execute();
        }
      }
    }
  }
}


function first_cat($category)
{
  return $category[0];
}

function add_menu()
{
  global $connect;
  if (isset($_POST['add_menu'])) {
    if (isset($_POST['postid'])) {
      foreach ($_POST['postid'] as $menu) {
        echo "<input hidden type='checkbox' value='$menu' name='menu[]' checked><li>$menu</li>";
      }
    }
  }

  if (isset($_POST['add_cat'])) {
    if (isset($_POST['category'])) {
      foreach ($_POST['category'] as $cat) {
        echo "<input hidden type='checkbox' value='$cat' name='menu[]' checked><li>$cat</li>";
      }
    }
  }

  if (isset($_POST['addPage'])) {
    if (isset($_POST['page'])) {
      foreach ($_POST['page'] as $page) {
        echo "<input hidden type='checkbox' value='$page' name='menu[]' checked><li>$page</li>";
      }
    }
  }
  if (isset($_POST['submit'])) {
    $url = $_POST['url'];
    $text = $_POST['text'];
    $insert = "INSERT INTO `menu`(`menu`, `url`, `status`) VALUES ('$text','$url', '2')";
    $insert = $connect->prepare($insert);
    $insert->execute();
    if ($insert) {
      //$_SESSION['Message'] = "Menu Added";
    } else {
      //$_SESSION['message'] = "Failed to add menu. Try again later.";
    }
  }
}


function save_menu()
{
  global $connect;
  if (isset($_POST['save_menu'])) {
    if (isset($_POST['menu'])) {
      foreach ($_POST['menu'] as $menu) {
        $save = "INSERT INTO `menu`(`menu`, `url`, `c_menu`, `status`) VALUES ('$menu','[value-4]','[value-5]','2')";
        $save  = $connect->prepare($save);
        $save->execute();
      }
    }
  }
}

function creat_page()
{
  global $connect;
  if (isset($_POST['submit'])) {
    $page_title = $_POST['page_title'];
    $url = $_POST['url'];
    $insert = "INSERT INTO `page`(`p_title`, `url`) VALUES ('$page_title','$url')";
    $insert = $connect->prepare($insert);
    $insert->execute();
    if ($insert) {
      //$_SESSION['Message'] = "Page created";
    } else {
      //$_SESSION['message'] = "Something went wrong try again later";
    }
  }
}
function user_login()
{
  global $connect;
  if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (empty($email) || empty($password)) {
      echo '<div class="alert alert-danger" role="alert">Please fill out all fields correctly.</div>';
    } else {
      $slc = $connect->prepare("SELECT * FROM users WHERE email = ?");
      $slc->execute([$email]);
      if ($slc->rowCount() > 0) {
        $row = $slc->fetch(PDO::FETCH_ASSOC);
        $storedPassword = $row['password'];
        $status = $row['status'];
        $block = $row['block'];
        $_SESSION["User_Id"] = $row['id'];
        $_SESSION["User_Name"] = $row['username'];
        $_SESSION["User_Email"] = $row['email'];
        $_SESSION["User_Password"] = $row['password'];
        $_SESSION["Status"] = $row['status'];
        $_SESSION["Role"] = $row['role'];
        if ($status == 'OFF') {
          echo '<div class="alert alert-warning" role="alert">Wait until the admin approves your account</div>';
        } elseif ($block == '2') {
          $user_id = $_SESSION["User_Id"];
          $stmt = $connect->prepare("SELECT * FROM banned_users WHERE user_id = ?");
          $stmt->execute([$user_id]);
          $users = $stmt->fetchAll();
          foreach ($users as $user) {
            $reason = $user['reason'];
            echo "<div class=\"alert alert-danger\" role=\"alert\">{$reason}</div>";
          }
        } else {
          if (md5($password) === $storedPassword) {
            $_SESSION['Message'] = "Welcome to Dashboard {$_SESSION["User_Name"]}";
            header("location: index.php");
          } else {
            echo '<div class="alert alert-danger" role="alert">Incorrect Password</div>';
          }
        }
      } else {
        echo '<div class="alert alert-danger" role="alert">Incorrect password or email</div>';
      }
    }
  }
}



function login()
{
  if (isset($_SESSION["User_Id"]) || isset($_COOKIE["SettingEmail"])) {
    return true;
  }
}

function Confirm_login()
{
  if (!login()) {
    header("Location:login.php");
    exit(); // Terminate script execution after redirection
  }
}

function page_coustomization()
{
  $page = basename($_SERVER['PHP_SELF']);
  $varx = "../";
  if ($page == 'index.php') {
    $varx = "";
  }
}

function user_banned()
{
  global $connect;
  if (isset($_POST['ban'])) {
    $reason = $_POST['reason'];
    $user_id = $_GET['user'];
    $insert = "INSERT INTO `banned_users`(`user_id`, `reason`) VALUES ('$user_id','$reason')";
    $insert = $connect->prepare($insert);
    $insert->execute();
    if ($insert) {
      $insert = "UPDATE `users` SET `block`='2' WHERE id = $user_id";
      $insert = $connect->prepare($insert);
      $insert->execute();
    }
  }
}
function user_section()
{
  global $connect;
  if (isset($_POST['change'])) {
    $item2 = $_POST['item2'];
    if (isset($_POST['user'])) {
      foreach ($_POST['user'] as $id) {
        $stmt = $connect->prepare("select * from users where id = $id");
        $stmt->execute();
        $users = $stmt->fetchAll();
        foreach ($users as $rows) {
          if ($rows["role"] == '1') {
            echo $msg = "<div class='alert alert-danger' role='alert'><b>{$rows["username"]}</b> is the adminestrator here. You can't change anything.</div>
            ";
          } else {
            $insert = "UPDATE `users` SET `role`='$item2' WHERE id = $id";
            $insert = $connect->prepare($insert);
            $insert->execute();
            if ($insert) {
              if ($item2 == '1') {
                $role = "Administrator";
              }
              if ($item2  == '2') {
                $role = "Editor";
              }
              if ($item2  == '3') {
                $role = "Author";
              }
              if ($item2  == '4') {
                $role = "Subscriber";
              }
              if ($item2  == '5') {
                $role = "Visitor";
              }
              echo $msg = "<div class='alert alert-success' role='alert'><b>{$rows["username"]}</b> Role changed to <b>{$role}</b></div>";
            }
          }
        }
      }
    }
  }
}
if (isset($_POST['apply'])) {
  $item1 = $_POST['item1'];
  if (isset($_POST['user'])) {
    if ($_POST['item1'] == '1') {
      foreach ($_POST['user'] as $id) {
        $insert = "DELETE FROM `users` WHERE id = $id";
        $insert = $connect->prepare($insert);
        $insert->execute();
      }
    }
  }
}


// suspended account notification

function band_noti()
{
  global $connect;
  $user = $_GET['user'];
  $stmt = $connect->prepare("select * from users where id = $user");
  $stmt->execute();
  $user = $stmt->fetchAll();
  foreach ($user as $row) {
    if ($row['block'] == 2) {
      echo $noti = "<div class='alert alert-danger' role='alert'>This account already suspended.</div>";
    } else {
      echo null;
    }
  }
}

function band_notis()
{
  global $connect;
  $user = $_GET['user'];
  $stmt = $connect->prepare("select * from users where id = $user");
  $stmt->execute();
  $user = $stmt->fetchAll();
  foreach ($user as $row) {
    if ($row['block'] == 2) {
      echo $noti = "hidden";
    }
  }
}
function creatSlugTitles($text)
{
  $text = str_replace(' ', '-', $text);
  $text = strtolower($text);
  $text = preg_replace('/-+/', '-', $text);

  $text = trim($text, '-');

  return $text;
}
function rolePermission($userId, $connect)
{
  $stmt = $connect->prepare("select * from users where id = $userId");
  $stmt->execute();
  $users = $stmt->fetchAll();
  foreach ($users as $row) {
    if ($row['role'] === '1' || $row['role'] === '2') {
      $menu = " ";
      return $menu;
    } else {
      $menu = "hidden";
      return $menu;
    }
  }
}

function deleteAccountWhileOnline($user, $connect)
{
  $stmt = $connect->prepare("select * from users where id = $user");
  $stmt->execute();
  $users = $stmt->fetchAll();
  if ($stmt->rowCount() > 0) {
  } else {
    session_destroy();
    $_SESSION['message'] = "Something went wrong";
    header("Location: login.php");
  }
}

function limitWords($text, $limit)
{
  $words = explode(" ", $text);
  if (count($words) > $limit) {
    $words = array_slice($words, 0, $limit);
    $text = implode(" ", $words);
    $text .= '.........';
  }

  return $text;
}

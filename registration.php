
    <style type="text/css">
        @media screen {
            @font-face {
                font-family: 'Lato';
                font-style: normal;
                font-weight: 400;
                src: local('Lato Regular'), local('Lato-Regular'), url(https://fonts.gstatic.com/s/lato/v11/qIIYRU-oROkIk8vfvxw6QvesZW2xOQ-xsNqO47m55DA.woff) format('woff');
            }

            @font-face {
                font-family: 'Lato';
                font-style: normal;
                font-weight: 700;
                src: local('Lato Bold'), local('Lato-Bold'), url(https://fonts.gstatic.com/s/lato/v11/qdgUG4U09HnJwhYI-uK18wLUuEpTyoUstqEm5AMlJo4.woff) format('woff');
            }

            @font-face {
                font-family: 'Lato';
                font-style: italic;
                font-weight: 400;
                src: local('Lato Italic'), local('Lato-Italic'), url(https://fonts.gstatic.com/s/lato/v11/RYyZNoeFgb0l7W3Vu1aSWOvvDin1pK8aKteLpeZ5c0A.woff) format('woff');
            }

            @font-face {
                font-family: 'Lato';
                font-style: italic;
                font-weight: 700;
                src: local('Lato Bold Italic'), local('Lato-BoldItalic'), url(https://fonts.gstatic.com/s/lato/v11/HkF_qI1x_noxlxhrhMQYELO3LdcAZYWl9Si6vvxL-qU.woff) format('woff');
            }
        }

        /* CLIENT-SPECIFIC STYLES */
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        /* RESET STYLES */
        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        table {
            border-collapse: collapse !important;
        }

        body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        /* iOS BLUE LINKS */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* MOBILE STYLES */
        @media screen and (max-width:600px) {
            h1 {
                font-size: 32px !important;
                line-height: 32px !important;
            }
        }

        /* ANDROID CENTER FIX */
        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }
    </style>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <title>Signup</title>
</head>
<?php
include("include/db.php");
$error = "";
if(isset($_POST['register'])){
  $name = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];
  $dis_n = NULL;
  $pattern = "/^[a-z]+$/";
  if(preg_match($pattern, $name)){
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
      if($password  == $confirm_password){
          if(strlen($password > 4 && $confirm_password > 0)){
            $stmt = $connect->prepare("select * from users where email = ?");
            $stmt->execute([$email]);
            $check = $stmt->fetchAll();
            if($stmt->rowCount() > 0){
              $error = "<div class='alert alert-warning' role='alert'>Email already taken</div>";
            }else{
              $pass = md5($password);
              $reg = "INSERT INTO `users`(`username`, `email`, `display_name`, `register_date`, `status`, `password`) VALUES ('$name','$email','$dis_n','REGISTER-DATE','OFF', '$pass')";
              $registration = $connect->prepare($reg);
              $registration->execute();
              if($registration){
                $error = "<div class='alert alert-success' role='alert'>Registration Complete</div>";
              }else{
                $error = "<div class='alert alert-warning' role='alert'>Registration has stopped. Try again later.</div>";
              }
          }
        }else{
          $error = "<div class='alert alert-warning' role='alert'>Password is too short</div>";
        }
      }else{
        $error = "<div class='alert alert-warning' role='alert'>Password doesn't match</div>";
      }
    }else{
      $error = "<div class='alert alert-warning' role='alert'>Invalid Email</div>";
    }
  }else{
    $error = "<div class='alert alert-warning' role='alert'>Please enter a valid username</div>";
  }
}
?>

<section class="vh-100" style="background-color: #eee;">
    <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-12 col-xl-11">
                <div class="card text-black" style="border-radius: 25px;">
                    <div class="card-body p-md-5">
                        <div class="row justify-content-center">
                            <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                                <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign up</p>
                                 <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4"><?php echo $error; ?></p>
                                <form action="" method="POST" class="mx-1 mx-md-4">

                                    <div class="d-flex flex-row align-items-center mb-4">
                                        <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <input type="text" autocomplete="off" name="username" value="<?php if(isset($name)): echo $name; endif; ?>" id="form3Example1c" class="form-control" placeholder="Username" />
                                            <label class="form-label" for="form3Example1c"></label>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row align-items-center mb-4">
                                        <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <input autocomplete="off" name="email" value="<?php if(isset($email)): echo $email; endif; ?>" id="form3Example3c" class="form-control" placeholder="Email"  />
                                            <label class="form-label" for="form3Example3c"></label>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row align-items-center mb-4">
                                        <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <input autocomplete="off" type="password" value="<?php if(isset($password)): echo $password; endif; ?>" name="password" id="form3Example4c" class="form-control" placeholder="Password"  />
                                            <label class="form-label" name="password" for="form3Example4c"></label>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row align-items-center mb-4">
                                        <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                                        <div class="form-outline flex-fill mb-0">
                                            <input autocomplete="off" type="password" value="<?php if(isset($confirm_password)): echo $confirm_password; endif; ?>" name="confirm_password" id="form3Example4cd" class="form-control" placeholder="Confirm Password"  />
                                            <label class="form-label" for="form3Example4cd"></label>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                        <button type="submit" name="register" class="btn btn-primary btn-lg">Register</button>
                                    </div>

                                </form>

                            </div>
                            <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">

                                <img src="images/girl-putting-up-sign-for-plan-schedule-free-vector.jpg"
                                     class="img-fluid" alt="login_image">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

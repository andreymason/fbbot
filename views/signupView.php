<?php
session_start();
if(isset($_SESSION["UserEmail"]) || isset($_COOKIE["UserEmail"])){
  header("Location: /home");
}
 ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>TraffBraza - Sign Up</title>
    <link id="dynamic-favicon" rel="shortcut icon" href="../assets/imgs/tray_logo.ico"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../assets/css/stylesheet-main.css">
    <script src="../assets/js/SignIn.js" charset="utf-8"></script>

    <script>
      $(document).ready(function(){
        $(".cross-svg").on("click", function(){
          $(".message-box").fadeOut(700);
        });
      });
    </script>
  </head>
  <body>
    <div class="background-wrapper">

    </div>
    <div class="app-wrapper">
      <div class="signUp-wrapper">
        <div class="signInHeader-wrapper col-xs-12">
          <div class="col-xs-12">
            <span class="signInHeader">sign up</span>
          </div>
        </div>
          <div class="message-box col-xs-12" style="display:none;">
            <div class="col-xs-10 message-text zero-padding">

            </div>
            <div class="col-xs-2 message-cross zero-padding">
              <svg version="1.1" class="cross-svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="20px" viewBox="0 0 30 30" style="enable-background:new 0 0 30 30;" xml:space="preserve">
                <path class="cross" d="M29.5,27.9c0.4,0.3,0.5,0.9,0.2,1.3c-0.1,0.1-0.1,0.2-0.2,0.2c-0.3,0.4-0.9,0.5-1.3,0.2
                  c-0.1-0.1-0.1-0.1-0.2-0.2L15,16.4L2,29.4c-0.4,0.4-1.1,0.4-1.5,0s-0.4-1.1,0-1.5l13.1-13L0.5,1.9C0,1.6,0,1,0.2,0.6
                  c0.1-0.1,0.1-0.1,0.2-0.2C0.7,0,1.3-0.1,1.7,0.2C1.8,0.2,1.9,0.3,2,0.4l13,13.1l13-13c0.3-0.4,0.9-0.5,1.3-0.2
                  c0.1,0.1,0.2,0.1,0.2,0.2C30,0.7,30,1.3,29.8,1.7c-0.1,0.1-0.1,0.1-0.2,0.2l-13.1,13L29.5,27.9z"></path>
              </svg>
            </div>
          </div>

        <form class="signInForm" action="../app/controllers/signUpController.php" method="post">
          <input type="text" class="formInputFields inputName" name="signUpName" value="" placeholder="name..." maxlength="50" required>
          <input type="email" class="formInputFields inputEmail" name="signUpEmail" value="" placeholder="email..." maxlength="50" required>
          <input type="password" class="formInputFields inputPassword" name="signUpPassword" value="" placeholder="password..." maxlength="50" required>
          <input type="password" class="formInputFields inputPassword2" name="signUpPassword2" value="" placeholder="repeat password..." maxlength="50" required>
          <input type="submit" class="formActionButtons inputSubmit" name="" value="Sign Up" />
          <div class="signUpAlinea">
            Already have an account? <a href="/signin">Sign In!</a>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>

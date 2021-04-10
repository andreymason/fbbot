<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>TraffBraza</title>
    <link rel="stylesheet" href="./assets/css/stylesheet-main.css">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  </head>
  <body>
    <?php

     if(isset($_GET["msg"], $_GET["color"])){
       echo "<h3 style='color: ".$_GET["color"].";'>".$_GET["msg"]."</h3><br />";
     }
     ?>
     <h1>Home</h1>
     <a href="views/signinView.php">sign in</a>
     <a href="views/signupView.php">sign up</a>
  </body>
</html>

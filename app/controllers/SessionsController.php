<?php

//start session
session_start();

 //check if users is signed in or not.
 if(isset($_SESSION["userEmail"])){
   //if so, redirect to home page.
   header("Location: views/homeView.php");
   die("redirect to homepage");
 }

?>

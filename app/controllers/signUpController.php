<?php
session_start();
#####################################
include '../classes/Signup.php';
#####################################

$name = $_POST["signUpName"];
$email = $_POST["signUpEmail"];
$password = $_POST["signUpPassword"];
$password2 = $_POST["signUpPassword2"];

#####################################

$signUp = new Signup($name, $email, $password, $password2);

$result = $signUp->signUp();
// on success it will return "true" - TODO redirect
echo $result;

?>

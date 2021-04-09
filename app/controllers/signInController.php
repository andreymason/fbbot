<?php
session_start();
#####################################
include '../classes/Signin.php';
#####################################

$email = $_POST["signInEmail"];
$password = $_POST["signInPassword"];
$remember = $_POST["rememberMeCheckbox"];

$cookies = isset($remember) ? 1 : 0;

#####################################

$signin = new Signin($email, $password);

$signin->finish_auth($cookies);

?>

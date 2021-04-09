<?php
session_start();
include '../classes/Proxy.php';

$userId     =   (isset($_SESSION["UserId"]) ? $_SESSION["UserId"] : $_COOKIE["UserId"]);
$proxy      = new Proxy();
$response   = $proxy->doRequest($_POST);
echo $response;


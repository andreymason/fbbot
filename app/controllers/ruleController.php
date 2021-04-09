<?php
session_start();
include '../classes/MysqlConnection.php';

include '../classes/Rule.php';

$userId       =   (isset($_SESSION["UserId"]) ? $_SESSION["UserId"] : $_COOKIE["UserId"]);
$cmd            =   $_POST["cmd"];
$rule          =   new Rule();

switch ($cmd) {
    case 'create':
        create($userId, $rule);
        break;

    default:
        # code...
        break;
}

function create($uid, $rule)
{
    $data = $_POST["data"];
    $token = $_POST["token"];
    $q = $rule->saveRule($data, $uid, $token);
    if ($q["resp"]) {
        echo json_encode([
            "resp" => "true",
            "t"=>$token,
            "q"=>$q
        ]);
    } else {
        echo json_encode([
            "resp" => "error",
            "msg" => $q["resp"]
        ]);
    }
}

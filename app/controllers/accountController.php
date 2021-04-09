<?php
session_start();
include '../classes/MysqlConnection.php';
include '../classes/Account.php';

$userId       =   (isset($_SESSION["UserId"]) ? $_SESSION["UserId"] : $_COOKIE["UserId"]);
$cmd            =   $_POST["cmd"];
$account          =   new Account($userId);

switch ($cmd) {
  case 'create':
    create($userId, $account);
    break;
  case 'get_all_accounts_by_uid':
    get_all_accounts_by_uid($userId, $account);
    break;
  case 'get_account_info_by_id':
    get_account_info_by_id($_POST["id"], $account);
  default:
    # code...
    break;
}

function get_account_info_by_id($accId, $account)
{
  $AccountsResp = $account->getAccountSafe("id", $accId);
  echo json_encode($AccountsResp);
}

function get_all_accounts_by_uid($uid, $account)
{
  if (!isset($uid)) {
    echo json_encode(["resp" => "error", "msg" => "Вы не авторизованы"]);
    die();
  } else {
    $allAccountsResp = $account->getAccountsByUid($uid);
    echo json_encode($allAccountsResp);
  }
}

function create($uid, $account)
{
  if (!isset($uid)) {
    echo json_encode(["resp" => "error", "msg" => "Вы не авторизованы"]);
    die();
  } else {
    $createResp = $account->createAccount($_POST["data"]);

    switch ($createResp["resp"]) {
      case 'true':
        echo json_encode([
          "resp" => "success"
        ]);
        break;
      case 'token_exist':
        echo json_encode([
          "resp" => "error",
          "msg" => "Уже существует аккаунт с таким токеном - " . $createResp["data"]["name"] . " / " . $createResp["data"]["facebook_id"]
        ]);
        break;
      case 'id_exist':
        echo json_encode([
          "resp" => "warning",
          "msg" => "Уже существует аккаунт с данным айди - " . $createResp["data"]["name"] . " / " . $createResp["data"]["facebook_id"]
        ]);
        break;
      case 'updated':
        echo json_encode([
          "resp" => "updated",
          "name" => $createResp["name"]
        ]);
        break;

      default:
        var_dump($createResp);
        echo @json_encode(["resp" => "error", "msg" => "Не удалось добавить аккаунт в базу данных, ошибка : " . $createResp]);
        break;
    }
  }
}

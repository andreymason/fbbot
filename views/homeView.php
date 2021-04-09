<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>TraffBraza</title>
  <link id="dynamic-favicon" rel="shortcut icon" href="../assets/imgs/logo.ico" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <script src="../assets/js/form.js"></script>
  <script src="../assets/js/drawsvg/src/jquery.drawsvg.js"></script>
  <link rel="stylesheet" href="../assets/css/stylesheet-main.css" />

</head>
<style media="screen">
  .fileElementDivHeight {
    height: 100px;
  }
</style>

<body>
  <?php
  include("headerView.php");

  if (!isset($_SESSION["UserEmail"], $_SESSION["UserId"]) && !isset($_COOKIE["UserEmail"], $_COOKIE["UserId"])) {
    header("Location: /signin");
    die("redirecting...");
  }

  $userId       =   (isset($_SESSION["UserId"]) ? $_SESSION["UserId"] : $_COOKIE["UserId"]);
  $userEmail    =  (isset($_SESSION["UserEmail"]) ? $_SESSION["UserEmail"] : $_COOKIE["UserEmail"]);
  ?>

  <main class="main">
    <?php
    include '../app/classes/MysqlConnection.php';
    include '../app/classes/Account.php';

    $account = new Account($userId);
    $allAccounts = $account->getAccountsByUid($userId);

    ?>

    <div class="body_container">
      <div class="body_inner">
        <div class="page_header">
          аккаунты
        </div>
        <div class="tabel_filters">
          <a href="/createAccount"><input type="button" class="default_button" value="+ Добавить аккаунт" /></a>
          <!-- <input type="text" class="default_search_field" placeholder="Поиск" /> -->
        </div>
        <table cellpadding="10" cellspacing="0" class="def_table" border="1">
          <tr>
            <td class="td_header">Имя</td>
            <td class="td_header">facebook ID</td>
          </tr>
          <?php
          if ($allAccounts) {
            foreach ($allAccounts as $key => $value) {
          ?>

              <tr>
                <td><?= '<a href="/account?id=' . $value["id"] . '" class="user_name_link">' . $value["name"] . '</a>' ?></td>
                <td><?= $value["facebook_id"] ?></td>
              </tr>

          <?php
            }
          }

          ?>
        </table>
      </div>
    </div>
  </main>
</body>
<script src="../assets/js/fileActions.js" charset="utf-8"></script>

</html>
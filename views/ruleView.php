<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>TraffBraza - правило</title>
    <!-- place logo here  -->
    <link id="dynamic-favicon" rel="shortcut icon" href="../assets/imgs/toco_logo.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="../assets/js/rule.js"></script>
    <script src="../assets/js/helper.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="../assets/css/stylesheet-main.css" />

</head>

<body>
    <?php include("headerView.php");

    $userId       =   (isset($_SESSION["UserId"]) ? $_SESSION["UserId"] : $_COOKIE["UserId"]);
    $userEmail    =  (isset($_SESSION["UserEmail"]) ? $_SESSION["UserEmail"] : $_COOKIE["UserEmail"]);
    include '../app/classes/MysqlConnection.php';
    include '../app/classes/Rule.php';
    include '../app/classes/Account.php';
    include '../app/classes/Proxy.php';

    $account = new Account($userId);
    $allAccounts = $account->getAccountsByUid($userId);

    $rule = new Rule($userId);
    $ruleId = $_GET["id"];
    $accountFacebookId = $_GET["fid"];

    $accountData = $account->getAccountSafe("facebook_id", $accountFacebookId);
    $certainRuleRaw = $rule->getRuleInfoByIdFromFacebook($ruleId, $accountData);
    $certainRule = json_decode($certainRuleRaw);
    ?>

    <div class="body_container">
        <div class="body_inner">
            <div class="page_nav_back">
                <svg class="nav_back_svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black" width="18px" height="18px">
                    <path d="M0 0h24v24H0z" fill="none" />
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z" />
                </svg>
                <a href="/rules">К правилам</a>
            </div>
            <div class="page_header">
                <?= $certainRule->name ?>
            </div>
            <div class="rule_append_container">

            </div>
            Применить к :
            <div class="rules_select_wrapper">
                <select class="rule_action_select_account" onchange="getAllAdAccounts(this);">
                    <option value="">Выберите...</option>
                    <?php
                    for ($i = 0; $i < count($allAccounts); $i++) {
                        echo "<option value='" . $allAccounts[$i]["id"] . "'>" . $allAccounts[$i]["name"] . "</option>";
                    }
                    ?>
                </select>
                <div class="loadingCampaigns hidden">
                    <div class="loadingCircle"></div>
                </div>
            </div>
            <input type="button" onClick="submitRuleAdjust(); return false;" class="default_button default_button_inactive rules_adjust_submit_button" value="применить" />
            <table cellpadding="10" cellspacing="0" class="def_table rule_adset_table" border="1">

            </table>
            <div class="rule_data_container">
                <?= $certainRuleRaw ?>
            </div>

        </div>
    </div>
    <div class="hidden info_div"><?= json_encode($certainRule) ?></div>
</body>

</html>
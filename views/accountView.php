<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>TraffBraza - аккаунт</title>
    <!-- place logo here  -->
    <link id="dynamic-favicon" rel="shortcut icon" href="../assets/imgs/toco_logo.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="../assets/js/campaigns.js"></script>
    <script src="../assets/js/tab_switcher.js"></script>
    <script src="../assets/js/helper.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="../assets/css/stylesheet-main.css" />

</head>

<body>
    <?php include("headerView.php");

    $userId       =   (isset($_SESSION["UserId"]) ? $_SESSION["UserId"] : $_COOKIE["UserId"]);
    $userEmail    =  (isset($_SESSION["UserEmail"]) ? $_SESSION["UserEmail"] : $_COOKIE["UserEmail"]);
    include '../app/classes/MysqlConnection.php';

    include '../app/classes/Account.php';

    $account = new Account($userId);
    $accountId = $_GET["id"];
    $certainAccount = $account->getAccount("id",  $accountId);
    ?>

    <div class="body_container">
        <div class="body_inner">
            <div class="page_nav_back">
                <svg class="nav_back_svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black" width="18px" height="18px">
                    <path d="M0 0h24v24H0z" fill="none" />
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z" />
                </svg>
                <a href="/home">На главную</a>
            </div>
            <div class="page_header">
                <?= $certainAccount["name"] ?>
            </div>
            <div class="adAccountSelectWrapper">
                <select class="adAccountSelect"></select>
                <div class="loadingCampaigns hidden">
                    <div class="loadingCircle"></div>
                </div>
            </div>
            <div class="icon_container download_accounts_container" title="Скачать ID всех рекламных кабинетов" onclick="downloadAdAccounts()">
                <svg class="download_svg" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24" fill="black" width="30px" height="30px">
                    <g>
                        <rect fill="none" height="24" width="24" />
                    </g>
                    <g>
                        <path d="M18,15v3H6v-3H4v3c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2v-3H18z M17,11l-1.41-1.41L13,12.17V4h-2v8.17L8.41,9.59L7,11l5,5 L17,11z" />
                    </g>
                </svg>
            </div>
            <div class="tab_menu">
                <div class="tab campaign_tab selected_tab" data-for="campaign">Рекламные компании - <div class="amount_of_campaigns"></div></div>
                <div class="tab adsets_tab" data-for="adsets">Адсэты - <div class="amount_of_adsets"></div></div>
                <div class="tab ads_tab" data-for="ads">Адсы - <div class="amount_of_ads"></div></div>
            </div>
            <div class="sections_container">
                <div class="section_wrapper">
                    <div class="campaign_section">
                        <table cellpadding="10" cellspacing="0" class="def_table campaigns_table" border="1">
                            <tr>
                                <td class="td_header">ID</td>
                                <td class="td_header">Название</td>
                                <td class="td_header">Тип</td>
                            </tr>
                        </table>
                    </div>

                    <div class="adsets_section">
                        <table cellpadding="10" cellspacing="0" class="def_table adsets_table" border="1">
                            <tr>
                                <td class="td_header">ID</td>
                                <td class="td_header">Название</td>
                                <td class="td_header">Количество правил</td>
                            </tr>
                        </table>
                    </div>

                    <div class="ads_section">
                        <table cellpadding="10" cellspacing="0" class="def_table ads_table" border="1">
                        <tr>
                            <td class="td_header">ID</td>
                            <td class="td_header">Название</td>
                            <td class="td_header">spent</td>
                            <td class="td_header">цена за регистрацию</td>
                            <td class="td_header">количество регистраций</td>
                            <td class="td_header">цена за установку</td>
                            <td class="td_header">количество установок</td>
                            <td class="td_header">цена за покупку</td>
                        </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hidden info_div"><?= json_encode($certainAccount) ?></div>
</body>

</html>
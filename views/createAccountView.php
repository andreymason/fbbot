<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>TraffBraza - добавить аккаунт</title>
    <!-- place logo here  -->
    <link id="dynamic-favicon" rel="shortcut icon" href="../assets/imgs/toco_logo.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <link rel="stylesheet" href="../assets/css/stylesheet-main.css" />
    <script src="../assets/js/account.js"></script>
    <script src="../assets/js/helper.js"></script>

</head>

<body>
    <?php
    include("headerView.php");

    $userId       =   (isset($_SESSION["UserId"]) ? $_SESSION["UserId"] : $_COOKIE["UserId"]);
    $userEmail    =  (isset($_SESSION["UserEmail"]) ? $_SESSION["UserEmail"] : $_COOKIE["UserEmail"]);
    include '../app/classes/MysqlConnection.php';

    include '../app/classes/Account.php';

    ?>
    <div class="body_container">
        <div class="form_container">
            <h1>добавить аккаунт</h1>
            <hr>
            </hr>
            <div class="form_slide_1">
                <!-- <form action="../app/controllers/accountController.php" method="post" enctype="multipart/form-data"> -->

                <input type="hidden" name="cmd" value="createAccount" />
                <input type="hidden" name="uid" value="<?= $userId ?>" />

                <div class="form_row">
                    <div class="textfield_label textfield_w_100">
                        Токен
                    </div>
                    <textarea name="accountToken" cols="30" rows="10" class="form_text_field form_text_area"></textarea>
                </div>

                <div class="form_row">
                    <div class="form_row_cell">
                        <div class="textfield_label">
                            Прокси айпи / IP
                        </div>
                        <input type="text" name="proxyIp" class="form_text_field" placeholder="XXX.XXX.XXX.XXX" />
                    </div>
                    <div class="form_row_cell form_row_cell_2">
                        <div class="textfield_label">
                            Прокси порт
                        </div>
                        <input type="text" name="proxyPort" placeholder="XXXX(X)" class="form_text_field" />
                    </div>
                </div>

                <div class="form_row">
                    <div class="form_row_cell">
                        <div class="textfield_label">
                            Прокси логин
                        </div>
                        <input type="text" name="proxyUsername" class="form_text_field" />
                    </div>
                    <div class="form_row_cell form_row_cell_2">
                        <div class="textfield_label">
                            Прокси пароль
                        </div>
                        <input type="text" name="proxyPassword" class="form_text_field" />
                    </div>
                </div>

                <div class="form_row">
                    <div class="textfield_label">
                        пользовательский агент ( user agent )
                    </div>
                    <input type="text" name="proxyUserAgent" class="form_text_field" />
                </div>

                <div class="form_row">
                    <input type="button" value="Добавить" class="form_submit_button validate_account" />
                </div>
                <!-- </form> -->
            </div>
        </div>
        <div class="hidden info_div" data-user_id="<?= $userId ?>"></div>
</body>

</html>
<?php
session_start();

if (
    !isset(
        $_SESSION["UserEmail"],
        $_SESSION["UserId"]
    ) &&
    !isset(
        $_COOKIE["UserEmail"],
        $_COOKIE["UserId"]
    ) &&
    $_SERVER["REQUEST_URI"] !== "/signin" && $_SERVER["REQUEST_URI"] !== "/signup" && $_SERVER["REQUEST_URI"] !== "/password_reset"
) {
    header("Location: /signin");
    die("redirect");
}

?>
<div class="grey_background hidden" onclick="closePopupWindow()"></div>
<div class="popup_window hidden"></div>
<header class="header_container">
    <div class="header_inner">
        <div class="header_logo">
            <a href="/home">
                <h1>TraffBraza</h1><!-- <img class="header_logo" src="../assets/imgs/log.png" alt=""> -->
            </a>
        </div>
        <div class="header_hyperlinks">
        
            <a class="fix_m_r_30" href="/home"> Аккаунты</a>
            <a class="fix_m_r_30" href="/rules"> Правила</a>
            <a href="../app/controllers/signOutController.php">Выйти</a>
        </div>
    </div>
</header>
<div class="message_container">

</div>
<?php

session_start();
session_destroy();

setcookie("UserEmail", "", time()-(3600 * 24 * 61), "/");
setcookie("UserId", "", time()-(3600* 24 * 61), "/");

header("Location: /signin");

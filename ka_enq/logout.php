<?php

require('function.php');

debug('logout.php:ログアウトしました。');
// セッションを削除
session_destroy();
// マイページへ遷移
header("Location:login.php");

?>
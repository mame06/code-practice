<?php

//================================
// ログイン認証・自動ログアウト
//================================
// ログインしていたら(セッションを持っていたら）
if(!empty($_SESSION['uID'])){
  debug('auth.php:ログインユーザーです');
  debug('$_SESSIONの中身は：'.print_r($_SESSION,true));
  // ログイン期限を超えている場合(現在日時が最終ログイン日時+有効期限を超えていた場合)
  if(($_SESSION['login_date'] + $_SESSION['login_limit']) < time()){
    // セッションを削除してログインページへ
    session_destroy();
    header("Location:login.php");

  }else{
    //有効期限内の場合：最終ログイン日時を更新する
    $_SESSION['login_date'] = time();

    //現在実行中のスクリプトファイル名がlogin.phpの場合
    //$_SERVER['PHP_SELF']はドメインからのパスを返すため、今回だと「/webukatu_practice03/login.php」が返ってくるので、
    //さらにbasename関数を使うことでファイル名だけを取り出せる
    if(basename($_SERVER['PHP_SELF']) === 'login.php'){
      //マイページへ
      header("Location:mypage.php");//マイページに遷移するのはログイン画面のときだけ
    }
  }

}else{
  debug('auth.php:未ログインユーザーです');
  if(basename($_SERVER['PHP_SELF']) !== 'login.php'){
    header("Location:login.php");
  }
}

?>
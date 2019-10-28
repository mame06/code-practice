<?php

  //共通変数・関数ファイルを読込み
  require('function.php');

  // ログイン認証
    require('auth.php');

//================================
// ログイン画面処理
//================================
// post送信されていた場合
if(!empty($_POST)){

    $loginid = $_POST['loginid'];
    $pass = $_POST['pass'];

    // loginidの最大文字数チェック
    validMaxLen($loginid,'loginid');
    // パスワード最大文字数チェック
    validMaxLen($pass, 'pass');

    // 未入力チェック
    validRequired($loginid,'loginid');
    validRequired($pass, 'pass');

    if(empty($err_msg)){

      try {
        // DB接続
        $dbh = dbConnect();
        // SQL文作成
        $sql = 'SELECT id,loginid,password FROM tb_user WHERE loginid = :loginid AND active = 1';
        $data = array(':loginid' => $loginid);
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        // クエリ結果の値を取得
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        debug('$resultの中身は：'.print_r($result,true));

        // パスワード照会
        if(!empty($result) && $pass === $result['password']){
          // パスワードがあっていたら
          // 最終ログイン日時を現在日時に、user_idを指定する
          $_SESSION['login_date'] = time();
          $_SESSION['uID'] = $result['id'];

          // ログイン有効期限のデフォルト設定(1時間)
          $sesLimit = 60*60;
          $_SESSION['login_limit'] = $sesLimit;

          // パスワードが合っててすべて完了、マイページへ遷移
          header("Location:enquete.php");

        }else{
          // パスワードが合っていない
          $err_msg['common'] = MSG09;
        }

      } catch (Exception $e) {
        error_log('エラー発生：'.$e->getMessage());
        $err_msg['common'] = MSG07;
      }
    }
}

?>

<?php
  $subtitle = 'ログイン';
  require('head.php');
 ?>

  <body>

    <main>
      <form action="" method="post">
        <div class="msg_area">
          <?php echo getErrMsg('common'); ?>
        </div>
        <div class="msg_area">
          <?php echo getErrMsg('loginid'); ?>
        </div>
          <label class="<?php echo getErrClass('loginid'); ?>">
            ログインID
            <input type="text" name="loginid">
          </label>
        <div class="msg_area">
          <?php echo getErrMsg('pass'); ?>
        </div>
          <label class="<?php echo getErrClass('loginid'); ?>">
            パスワード
            <input type="password" name="pass">
          </label>
        <div class="submit_area">
          <input type="submit" name="submit" value="ログイン">
        </div>

      </form>
    </main>

    <script src="js/jquery-3.4.1.min.js"></script>
    <!-- <script src="js/main.js"></script> -->
  </body>
</html>
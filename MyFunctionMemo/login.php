<?php

  require('function.php');

  debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
  debug('「　ユーザー登録ページ　');
  debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
  debugLogStart();

    // post送信されていた場合
    if(!empty($_POST)){

      $email = $_POST['email'];
      $pass = $_POST['pass'];

      // 未入力チェック
      validRequired($email,'email');
      validRequired($pass,'pass');

      // Email形式チェック
      validEmail($email,'email');

      if(empty($err_msg)){

            // DBへ接続
            $dsn = 'mysql:dbname=foodinfo;host=localhost;charset=utf8';
            $user = 'root';
            $password = 'root';
            $options = array(
              // SQL実行失敗時に例外をスロー
              PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
              // デフォルトフェッチモードを連想配列形式に設定
              PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
              // バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
              // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
              PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
            );

            // PDOオブジェクト生成（DBへ接続）
            $dbh = new PDO($dsn, $user, $password, $options);

            //SQL文
            $sql = 'SELECT * FROM users WHERE email = :email AND password = :pass';
            $data = array(':email' => $email, ':pass' => $pass);
            // クエリ作成
            $stmt = $dbh->prepare($sql);
            // //プレースホルダに値をセットし、SQL文を実行
            $stmt->execute($data);
            //$resultに配列としてDBから取得した値を入れている。
            $result = 0;
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!empty($result)){
              session_start();
              $_SESSION['login'] = true;
              header("Location:mypage.php");
            }else{
              global $err_msg;
              $err_msg['common'] = MSG06;
            }


      }
    }

?>

<?php
  $subtitle = 'ログイン画面';
  require('head.php');
 ?>

  <body>

<?php
  require('header.php');
?>


<main class="site-width">


      <form class="" action="" method="post">
        <h2>ログイン</h2>
        <div class="msg_area">
          <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
        </div>
        <label>
        メールアドレス
        <input type="text" name="email" placeholder="メールアドレス" value="<?php if(!empty($_POST['email'])) echo h($_POST['email']); ?>">
        </label>
        <label>
        パスワード
        <input type="password" name="pass" placeholder="パスワード" value="<?php if(!empty($_POST['pass'])) echo h($_POST['pass']); ?>">
        </label>
        <input type="submit" name="submit" value="ログインする">
      </form>

    </main>

<?php
  require('footer.php');
?>
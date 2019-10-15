<?php

  require('function.php');


  $page_st = 0; //0は入力画面、1は確認画面

    //確認するボタンを押したとき
    if(!empty($_POST['confirm_btn'])){

      $email = $_POST['email'];
      $pass = $_POST['pass'];
      $pass_re = $_POST['pass_re'];

      // 未入力チェック
      validRequired($email,'email');
      validRequired($pass,'pass');
      validRequired($pass_re,'pass_re');

      if(empty($err_msg)){

        // Email形式チェック
        validEmail($email,'email');
        //同値チェック
        validMatch($pass,$pass_re,'pass_re');

        if(empty($err_msg)){
          $page_st = 1; //確認画面のページを表示させるため
        }
      }


    //送信するボタンを押したとき
    }elseif(!empty($_POST['submit_btn'])){

      $email = $_POST['email'];
      $pass = $_POST['pass'];
      $pass_re = $_POST['pass_re'];
      // 未入力チェック
      validRequired($email,'email');
      validRequired($pass,'pass');
      validRequired($pass_re,'pass_re');

      if(empty($err_msg)){
      // Email形式チェック
      validEmail($email,'email');
      //同値チェック
      validMatch($pass,$pass_re,'pass_re');

      // バリデーションOKだったら
      if(empty($err_msg)){
        try {
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
            $sql = 'INSERT INTO users (email,password,login_time) VALUES (:email,:pass,:login_time)';
            $data = array(':email' => $email, ':pass' => $pass, ':login_time' => date('Y-m-d H:i:s'));
            // クエリ作成
            $stmt = $dbh->prepare($sql);
            // //プレースホルダに値をセットし、SQL文を実行
            $stmt->execute($data);

            session_start();
            $_SESSION['login'] = true;
            header("Location:mypage.php");


          }catch(Exception $e) {
            echo $e->getMessage();
          }
      }
    }
  }

?>





<?php
  $subtitle = 'ユーザー登録';
  require('head.php');
 ?>

  <body>

<?php
  require('header.php');
?>

<?php echo h('<a href="#">サニタイズしたやつ</a>'); ?>
<?php echo '<a href="#">サニタイズしないやつ</a>'; ?>

<main class="site-width">

    <?php if($page_st === 0){
      echo '入力画面';
    ?>
      <form class="" action="" method="post">
      <h2>ユーザー登録</h2>
      <div class="area-msg">
        <?php if(!empty($err_msg['email'])) echo $err_msg['email'];  ?>
      </div>
      <label>
      メールアドレス
      <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo h($_POST['email']); ?>">
      </label>
      <div class="area-msg">
        <?php if(!empty($err_msg['pass'])) echo $err_msg['pass'];  ?>
      </div>
      <label>
      パスワード
      <input type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo h($_POST['pass']); ?>">
      </label>
      <div class="area-msg">
        <?php if(!empty($err_msg['pass_re'])) echo $err_msg['pass_re'];  ?>
      </div>
      <label>
      パスワード(確認用)※コピーせずにパスワードを同じものを入力
      <input type="password" name="pass_re" value="<?php if(!empty($_POST['pass_re'])) echo h($_POST['pass_re']); ?>">
      </label>
      <input type="submit" name="confirm_btn" value="確認する">

      </form>

    <?php  }elseif($page_st === 1) {
      echo "確認画面"; ?>

      <form action="" method="post">
        <table>
          <tr>
            <th>メールアドレス</th><td><?php if(!empty($_POST['email'])) echo h($_POST['email']); ?></td>
          </tr>
          <tr>
            <th>パスワード</th><td><?php if(!empty($_POST['pass'])) echo h($_POST['pass']); ?></td>
          </tr>
          <tr>
            <th>パスワード(確認用)</th><td><?php if(!empty($_POST['pass_re'])) echo h($_POST['pass_re']); ?></td>
          </tr>
        </table>
        <input type="submit" name="back" value="戻る">
        <input type="submit" name="submit_btn" value="送信する">

        <!-- 送信用の値を入れる場所、type属性hiddenで見えなくする -->
        <input type="hidden" name="email" value="<?php if(!empty($_POST['email'])) echo h($_POST['email']); ?>">
        <input type="hidden" name="pass" value="<?php if(!empty($_POST['pass'])) echo h($_POST['pass']); ?>">
        <input type="hidden" name="pass_re" value="<?php if(!empty($_POST['pass_re'])) echo h($_POST['pass_re']); ?>">
      </form>


    <?php  }elseif($page_st === 2) {
      echo "登録が完了しました"; ?>

    <?php } ?>

    </main>

<?php
  require('footer.php');
?>
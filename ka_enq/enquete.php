<?php

  //共通変数・関数ファイルを読込み
  require('function.php');

  // ログイン認証
    require('auth.php');

  if(!empty($_POST)){

    // radioボタンの未入力チェック
    $que1 = validRadioReq('que1');
    $que2 = validRadioReq('que2');
    $que3 = validRadioReq('que3');

    debug('$que1は：'.print_r($que1,true));

    debug('$_POSTの中身は：'.print_r($_POST,true));

    if(empty($err_msg)){

      try {
        $dbh = dbConnect();
        //SQL文
        $sql = 'INSERT INTO tb_enquete (userid, enq1, enq2, enq3, upddate) VALUES (:userid,:que1,:que2,:que3,:upddate)';
        $data = array(':userid' => $_SESSION['uID'], ':que1' => $que1, 'que2' => $que2, 'que3' => $que3, ':upddate' => date('Y-m-d H:i:s'));
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);

        if($stmt){
          debug('enquete.php:アンケート送信できました');
          header("Location:enqueteComp.php");
        }else{
          debug('enquete.php:アンケート失敗');
        }

      } catch (Exception $e) {
        error_log('エラー発生：'.$e->getMessage());
        $err_msg['common'] = MSG07;
      }
    }
  }

?>

<?php
  $subtitle = 'アンケートにお答え下さい';
  require('head.php');
 ?>

  <body>

    <main>
      <form action="" method="post">
        <h1>アンケートにご協力ください</h1>
        <div class="enq_area">
          <div class="msg_area">
            <?php echo getErrMsg('common'); ?>
          </div>
          <section>
            <h2>Q1.研修のわかりやすさ</h2>
            <div class="msg_area">
              <?php echo getErrMsg('que1'); ?>
            </div>
            <div class="<?php echo getErrRadioClass('que1');?>">
              <label><input type="radio" name="que1" value="an1" <?php echo radioCheck('que1','an1');?> >分かりやすい</label>
              <label><input type="radio" name="que1" value="an2" <?php echo radioCheck('que1','an2');?> >まぁまぁ</label>
              <label><input type="radio" name="que1" value="an3" <?php echo radioCheck('que1','an3');?> >ナニコレ？</label>
            </div>
          </section>
          <section>
            <h2>Q2.中丸先生の頑張り度</h2>
            <div class="msg_area">
              <?php echo getErrMsg('que2'); ?>
            </div>
            <div class="<?php if(!empty($err_msg['que2'])) echo 'err_ra'; ?>">
              <label><input type="radio" name="que2" value="an1" <?php echo radioCheck('que2','an1');?> >めちゃすごい</label>
              <label><input type="radio" name="que2" value="an2" <?php echo radioCheck('que2','an2');?> >すごい</label>
              <label><input type="radio" name="que2" value="an3" <?php echo radioCheck('que2','an3');?> >まぁまぁ</label>
              <label><input type="radio" name="que2" value="an4" <?php echo radioCheck('que2','an4');?> >もっとがんばれ</label>
              <label><input type="radio" name="que2" value="an5" <?php echo radioCheck('que2','an5');?> >(；ω；)</label>
            </div>
          </section>
          <section>
            <h2>Q3.研修の楽しさ</h2>
            <div class="msg_area">
              <?php echo getErrMsg('que3'); ?>
            </div>
            <div class="<?php if(!empty($err_msg['que3'])) echo 'err_ra'; ?>">
              <label><input type="radio" name="que3" value="an1" <?php echo radioCheck('que3','an1');?> >めちゃ楽しい</label>
              <label><input type="radio" name="que3" value="an2" <?php echo radioCheck('que3','an2');?> >楽しい</label>
              <label><input type="radio" name="que3" value="an3" <?php echo radioCheck('que3','an3');?> >普通じゃね？</label>
              <label><input type="radio" name="que3" value="an4" <?php echo radioCheck('que3','an4');?> >眠い…</label>
            </div>
          </section>
      </div>
        <div class="submit_area">
          <input type="submit" name="submit" value="回答内容を送信">
        </div>
      </form>
    </main>

    <script src="js/jquery-3.4.1.min.js"></script>
    <!-- <script src="js/main.js"></script> -->
  </body>
</html>
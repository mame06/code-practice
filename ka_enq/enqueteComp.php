<?php

  //共通変数・関数ファイルを読込み
  require('function.php');

  // ログイン認証
  require('auth.php');

?>

<?php
  $subtitle = 'アンケートにご協力ありがとうございました';
  require('head.php');
 ?>

  <body>

    <main>
      <p>アンケートにご協力ありがとうございました</p>
      <a href="logout.php">ログアウトする</a>
    </main>

    <script src="js/jquery-3.4.1.min.js"></script>
    <!-- <script src="js/main.js"></script> -->
  </body>
</html>
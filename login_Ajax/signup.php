<?php

  require('function.php');

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


        <h2>ログイン</h2>
        <form action="" method="post">

        <input id="email" type="text" name="email" placeholder="メールアドレス" value="">

        <input id="pass" type="password" name="pass" placeholder="パスワード"  value="">
        <button type="button" id="ajaxout">ログインする</button>
      </form>

        <div id="res">

        </div>

    </main>

<?php
  require('footer.php');
?>
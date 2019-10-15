<?php

  require('function.php');
  session_start();

  // if(empty($_SESSION['login'])) header("Location:login.php");

?>

<?php
  $subtitle = 'マイページ';
  require('head.php');
 ?>

  <body>

<?php
  require('header.php');

  if(!empty($_SESSION['login'])){

?>

    <main class="site-width">

      私のページです。


    </main>

<?php }else{ ?>
  <main class="site-width">

    ログインしないとみられません。


  </main>


<?php
  }

  require('footer.php');
?>
<?php


?>

<?php
  $subtitle = 'マイページ';
  require('head.php');
 ?>

  <body>

<?php
  require('header.php');
?>

  <main class="site-width">

    <div class="search-area">
      <input id="search" type="text" name="search" placeholder="名前で検索">
      <button id="search-btn" type="button" name="search-button">検索</button>
      <button id="sign-btn" type="button" name="sign-button">ユーザ登録</button>
    </div>

    <div id="search-result">
    </div>

    <!-- 新規追加画面 -->
    <div id="add-area" style="display:none;" >
        <p id="add_title" style="display:none;">ユーザー情報追加</p>
        <p id="edit_title" style="display:none;">編集</p>
        <div class="area-msg">
        </div>
          <label>
            dataID
            <input id="dataid" type="text" name="dataid" placeholder="data0000">
          </label>
        <div class="area-msg">
        </div>
        <label>
          USER ID※編集画面用表示：変更できません
          <p id="old_userid"></p>
        </label>
        <div class="area-msg">
        </div>
          <label>
            USER ID
            <input id="userid" type="text" name="userid" placeholder="USER0000">
          </label>
        <div class="area-msg">
        </div>
          <label>
            氏名
            <input id="username" type="text" name="username" placeholder="山田太郎">
          </label>
        <div class="area-msg">
        </div>
            性別
            <label><input type="radio" name="gender" value="男性">男性</label>
            <label><input type="radio" name="gender" value="女性">女性</label>
            <div class="area-msg">
        </div>
          <label>
            バス号車
            <input id="busnum" type="text" name="busnum" placeholder="0号車">
          </label>
        </div>

        <div class="add-btn-area" style="display:none;">
          <button id="add-btn" type="button" name="add-button">追加する</button>

          <button id="aback-btn" type="button" name="aback-btn">検索ページに戻る</button>
        </div>

        <div class="edit-btn-area" style="display:none;">
          <button id="edit-btn" type="button" name="edit-button">編集する</button>

          <button id="eback-btn" type="button" name="eback-btn">検索結果に戻る</button>
        </div>


  </main>

<?php
  require('footer.php');
?>
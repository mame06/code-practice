<?php

//================================
// ログ
//================================
//ログを取るか
ini_set('log_errors','on');
//ログの出力ファイルを指定
ini_set('error_log','php.log');

//================================
// デバッグ
//================================
//デバッグフラグ
$debug_flg = true;
//デバッグログ関数
function debug($str){
  global $debug_flg;
  if(!empty($debug_flg)){
    error_log('デバッグ：'.$str);
  }
}

//================================
// セッション準備・セッション有効期限を延ばす
//================================
//セッションファイルの置き場を変更する（/var/tmp/以下に置くと30日は削除されない）
session_save_path("c:/var/tmp");
//ガーベージコレクションが削除するセッションの有効期限を設定（30日以上経っているものに対してだけ１００分の１の確率で削除）
ini_set('session.gc_maxlifetime', 60*60*24*30);
//ブラウザを閉じても削除されないようにクッキー自体の有効期限を延ばす
ini_set('session.cookie_lifetime', 60*60*24*30);
//セッションを使う
session_start();
//現在のセッションIDを新しく生成したものと置き換える（なりすましのセキュリティ対策）
session_regenerate_id();

//================================
// 画面表示処理開始ログ吐き出し関数
//================================
function debugLogStart(){
  debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 画面表示処理開始');
  debug('セッションID：'.session_id());
  debug('セッション変数の中身：'.print_r($_SESSION,true));
  debug('現在日時タイムスタンプ：'.time());
  if(!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])){
    debug( 'ログイン期限日時タイムスタンプ：'.( $_SESSION['login_date'] + $_SESSION['login_limit'] ) );
  }
}

//================================
// 定数
//================================
define('MSG01','入力必須です');
define('MSG02','Emailの形式で入力してください');
define('MSG03','パスワード（再入力）が合っていません');
define('MSG04','半角英数字のみご利用いただけます');
define('MSG05','6文字以上で入力してください');
define('MSG06','255文字以内で入力してください');
define('MSG07','エラーが発生しました。しばらく経ってからやり直してください。');
//DB接続失敗用文言、エラーが発生しました。
define('MSG08', 'そのEmailは既に登録されています');
define('MSG09', 'メールアドレスまたはパスワードが違います');
define('MSG10', '電話番号の形式が違います');
define('MSG11', '郵便番号の形式が違います');
define('MSG12', '古いパスワードが違います');
define('MSG13', '古いパスワードと同じです');
define('MSG14', '文字で入力してください');
define('MSG15', '正しくありません');
define('MSG16', '有効期限が切れています');
define('MSG17', '半角数字のみご利用いただけます');

define('MSG18', '検索するキーワードを入力してください');
define('MSG19', '該当する項目はありません');
define('MSG20', 'DB接続失敗');
define('MSG21', 'ユーザー追加しました');
define('MSG22', '失敗しました');
define('MSG23', '編集ができました');
define('MSG24', '削除しました');
define('MSG25', '選択してください');


define('SUC01', 'パスワードを変更しました');
define('SUC02', 'プロフィールを変更しました');
define('SUC03', 'メールを送信しました');
define('SUC04', '登録しました');
define('SUC05', '購入しました！相手と連絡を取りましょう！');



//================================
// グローバル変数
//================================
// エラーメッセージ格納用の配列
$err_msg = array();


//================================
// エスケープ処理
//================================
function h($s){
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

//================================
// Ajax関連
//================================
// 終了処理
function exitAccess(){
  global $JSON_Array,$getType,$list;
  $JSON_Array += array($getType=>$list);
  echo json_encode($JSON_Array);
  exit;
}
// フラグとメッセージ表示
function inList($flg, $msg){
  $list[] = array(
    "flg" => $flg,
    "msg" => $msg,
  );
  return $list;
}

//================================
// バリデーション関数
//================================
// 未入力チェック(radioボタンは別にあり)
function validRequired($str,$key){
  if($str === ''){ //金額フォームなどを考えると数値の０はOKにし、空文字はダメにする
    global $err_msg;
    $err_msg[$key] = MSG01;
  }
}
//Email形式チェック
function validEmail($str, $key){
  if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)){
    global $err_msg;
    $err_msg[$key] = MSG02;
  }
}
//Email重複チェック
function validEmailDup($email){
  global $err_msg;
  // 例外処理
  try {
    // DB接続
    $dbh = dbConnect();
    // SQL文作成(count(*)は検索レコードの数を拾ってくる)
    $sql = 'SELECT count(*) FROM users WHERE email = :email AND delete_flg = 0';
    $data = array(':email' => $email);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    // クエリの検索結果を取得
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    /*array_shift関数は配列の先頭を取り出す関数です。クエリ結果は配列形式で入っているので、
     array_shiftで1つ目だけ取り出して判定します*/
     if(!empty(array_shift($result))){
       $err_msg['email'] = MSG08;
     }
  } catch (Exception $e) {
    error_log('エラー発生:'. $e->getMessage());
    $err_msg['common'] = MSG07;
  }
}
//同じ値かチェック
function validMatch($str1, $str2, $key){
  if($str1 !== $str2){
    global $err_msg;
    $err_msg[$key] = MSG03;
  }
}
// 最小文字数チェック
function validMinLen($str, $key, $min = 6){
  if(mb_strlen($str) < $min){
    global $err_msg;
    $err_msg[$key] = MSG05;
  }
}
// 最大文字数チェック($max=255は初期値、DBの最大文字数に一致する)
function validMaxLen($str, $key, $max = 255){
  if(mb_strlen($str) > $max){
    global $err_msg;
    $err_msg[$key] = MSG06;
  }
}
//バリデーション関数（半角チェック）
function validHalf($str, $key){
  if(!preg_match("/^[a-zA-Z0-9]+$/", $str)){
    global $err_msg;
    $err_msg[$key] = MSG04;
  }
}
//電話番号形式チェック
function validTel($str, $key){
  if(!preg_match("/0\d{1,4}\d{1,4}\d{4}/", $str)){
    global $err_msg;
    $err_msg[$key] = MSG10;
  }
}
//郵便番号形式チェック
function validZip($str, $key){
  if(!preg_match("/^\d{7}$/", $str)){
    global $err_msg;
    $err_msg[$key] = MSG11;
  }
}
//半角数字チェック
function validNumber($str, $key){
  if(!preg_match("/^[0-9]+$/", $str)){
    global $err_msg;
    $err_msg[$key] = MSG17;
  }
}
//パスワードチェック
function validPass($str, $key){
  // 半角英数字
  validHalf($str, $key);
  // 最大文字数
  validMaxLen($str, $key);
  // 最小文字数
  validMinLen($str, $key);
}
//================================
// エラーメッセージ表示
function getErrMsg($key){
  global $err_msg;
  if(!empty($err_msg[$key])){
    return $err_msg[$key];//echoは？
  }
}
// エラークラス
function getErrClass($key){
  global $err_msg;
  if(!empty($err_msg[$key])){
    return 'err';
  }
}
// radio用エラー
function getErrRadioClass($key){
  global $err_msg;
  if(!empty($err_msg[$key])){
    return 'err_ra';
  }
}
//================================
// ラジオボタン用未入力チェック
function validRadioReq($key){
  if(isset($_POST[$key])){
    return $_POST[$key];
  }else{
    global $err_msg;
    $err_msg[$key] = MSG25;
    return '';
  }
}
// ラジオボタンにチェック保持
function radioCheck($raName, $raValue){
  if(isset($_POST[$raName]) && $_POST[$raName] === $raValue){
    return 'checked';
  }
}

//================================
// DB接続関数
//================================
// maehara_nana@example.com
define("DB_User", "postgres");			// DBユーザ名
define("DB_Pass", "post");				// パスワード
define("DB_Name", "postgres");		// DB名
define("DB_Host", "localhost");	// ホスト名
define("DB_Port", "5432");				// ポート

function dbconnect(){
  $dsn = "pgsql:dbname=".DB_Name." host=".DB_Host." port=".DB_Port;
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
  $dbh = new PDO($dsn,DB_User,DB_Pass,$options);
  return $dbh;
}

function queryPost($dbh, $sql, $data){
  // クエリ―作成
  $stmt = $dbh->prepare($sql);//prepareにsql文をセットすることにより、プレースホルダーを使えるようにする
  //プレースホルダに値をセットし、SQL文を実行(excuteでsql文の虫食い部分に入れ込む)
  if(!$stmt->execute($data)){
    debug('クエリに失敗しました。');
    debug('失敗したSQL：'.print_r($stmt,true));
    $err_msg['common'] = MSG07;
    return 0; //なんだこれ
  }
  return $stmt;
}
//user_id情報取得
function getUser($u_id){
  // 例外処理
  try {
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'SELECT * FROM users WHERE id = :u_id AND delete_flg = 0';
    $data = array(':u_id' => $u_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    // クエリ結果のデータを１レコード返却
    if($stmt){
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
      return false;
    }
  } catch (Exception $e) {
    error_log('エラー発生：'. $e->getMessage());
  }

}

//================================
// メール送信
//================================
function sendMail($from, $to, $subject, $comment){
    if(!empty($to) && !empty($subject) && !empty($comment)){
      //文字化けしないように設定（お決まりパターン）
      mb_language("Japanese"); //現在使っている言語を設定する
      mb_internal_encoding("UTF-8"); //内部の日本語をどうエンコーディング（機械が分かる言葉へ変換）するかを設定

      // メール送信(送信結果はtrueかfalseで返ってくる)
    $result = mb_send_mail($to, $subject, $comment, /*"From: ".$from*/);

      if($result){
        debug('メール送信成功');
      }else{
        debug('【エラー】メール送信に失敗');
      }
    }
}

//================================
// その他
//================================
// フォーム入力保持
function getFormData($str, $flg = false){
  // postかgetを選択(初期値POST)
  if($flg){
    $method = $_GET;
  }else{
    $method = $_POST;
  }
  global $dbFormData;
  // 1.DBにユーザーデータがある場合
  if(!empty($dbFormData)){
    // 2.入力内容にエラーがある場合
    if(!empty($err_msg[$str])){
      // 3.POSTにデータがある場合
      if(isset($method[$str])){
        return h($method[$str]);
      }else {
        //3.POSTにデータがない場合(エラーの判定しているのでPOSTされているはず)はDB内容を表示
        return h($dbFormData[$str]);
      }
    }else{
      //2.4.入力にエラーがなければ、入力内容(POST)とDB内容を比較、異なる場合
      if(isset($method[$str]) && $method[$str] !== $dbFormData[$str]){
        return h($method[$str]);
      }else{
        // 4.入力内容とDBの情報が同じ(そもそも変更していない)
        return h($dbFormData[$str]);
      }
    }
  }else{
    // 1.DBにユーザーデータがない場合はpost内容を表示
    if(isset($method[$str])){
      return h($method[$str]);
    }
  }
}
// sessionを一回だけ取得できる
function getSessionFlash($key){
  if(!empty($_SESSION[$key])){
    $data = $_SESSION[$key]; //sessionの内容を移して、
    $_SESSION[$key] = ''; //session自体を空にする
    debug(print_r($data,true));
    return $data;
  }
}





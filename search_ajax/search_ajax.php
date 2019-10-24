<?php

require('function.php');



	//初期変数設定*****************************************************
	$JSON_Array = array(); 	//出力JSON全データで使用
	$FunctionID = "";		//使用するFunctionIDで使用
	$dbh = "";				//DB PDOアクセス
	$query = null;			//SQL文で使用
	$sql = null;			//SQL文で使用
	$stmt = null;			//SQL実行後処理格納
	$row = null;			//DB検索レコードで使用
	$row_result = null;		//DB検索結果の格納で使用
	$result = null;			//DBクエリ結果
	$sql_result = null;		//テストモード時　SQL文を格納
	$Session_result = null;	//Session情報格納
	$TestMode = 0;			//Testモード 1:有効 0:無効

	//Header宣言 jsonfileですよって意味
	header('Content-Type: application/json; charset=UTF-8'); //データをJSON形式で出力


  //================================
  // 検索機能
  //================================
	if(!empty($_POST['getType'] === 'getUserData')){
    debug('========検索機能の開始========');
    $getType = $_POST['getType']; //getUserDataの値を取得

    // 検索ワードが入力されていない場合
    if($_POST['seName'] === ''){
      $list[] = array(
        "flg" => 0,
        "msg" => '検索するキーワードを入力してください',
      );

    }else{

      $getType = $_POST['getType'];
      $name = '%'.$_POST['seName'].'%';

      try {
    		    $dbh = dbConnect();
            //SQL文とプレースホルダの準備
    				$sql = 'SELECT u.*, b.bus_number, b.userid, b.id AS b_id FROM tb_user AS u LEFT OUTER JOIN tb_bus AS b ON u.id = b.userid WHERE name like :name AND active = 1';
    				$data = array(':name' => $name);
            // クエリ実行
            $stmt = queryPost($dbh, $sql, $data);
            // クエリの検索結果を取得
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            debug('$resultの中身:'.print_r($result,true));

  // このNuLLがやっかいと思ったら、fetchAll使えばemptyで判定できる
    				if(!empty($result)){
              foreach ($result as $row ) {
                $list[] = array(
                  "ouID" => $row['id'],
                  "oudataID" => $row['b_id'],
                  "ouname" => $row['name'],
                  "ougender" => $row['gender'],
                  "oubus" => $row['bus_number'],
                  "flg" =>  2,
                );
              }
              debug('検索結果が見つかりました');
              debug('$listの中身：'.print_r($list,true));

            }else{
              // 検索結果がない場合
              $list[] = array(
                "flg" => 3,
                "msg" => '該当する項目はありません',
              );
              debug(print_r($list,true));
              debug('該当する項目はありません');
    				}

    			}catch(PDOException $e){
            $list[] = array(
              "flg" => 1,
              "msg" => 'DB接続失敗',
            );
            debug('DB接続失敗');
    			}

    }
    debug('========検索機能の終了========');
    exitAccess();
  }

  //================================
  // ユーザー情報追加
  //================================
  if(!empty($_POST['getType'] === 'addUserData')){
    debug('========ユーザー情報追加の開始========');
    $getType = $_POST['getType'];
    $dataid = $_POST['dataid'];
    $userid = $_POST['userid'];
    $busnum = $_POST['busnum'];
    $username = $_POST['username'];
    $gender = $_POST['gender'];

    try {
      // DB接続
      $dbh = dbConnect();
      //SQL文とプレースホルダの準備
      $sql1 = 'INSERT INTO tb_bus (id,userid,bus_number) VALUES (:dataid, :userid, :busnum)';
      $sql2 = 'INSERT INTO tb_user (id,name,gender) VALUES (:userid, :username, :gender)';
      // ここプレースホルダをまとめて一行にしたらできなかったっぽい(トランザクション処理？？)
      $data1 = array(':dataid' => $dataid, ':userid' => $userid, ':busnum' => $busnum);
      $data2 = array( ':userid' => $userid, ':username' => $username, ':gender' => $gender);
      // クエリ実行
      $stmt1 = queryPost($dbh, $sql1, $data1);
      $stmt2 = queryPost($dbh, $sql2, $data2);

      if($stmt1 && $stmt2){
        $list[] = array(
          "flg" => 2,
          "msg" => 'ユーザー追加しました',
        );
        debug('ユーザー追加しました');
      }else{
        // DBは接続できたがうまくインサートできなかったとき
        $list[] = array(
          "flg" => 3,
          "msg" => 'ユーザー情報追加に失敗',
        );
        debug('ユーザー情報追加に失敗');
      }

    } catch (Exception $e) {
      $list[] = array(
        "flg" => 1,
        "msg" => 'DB接続失敗',
      );
      debug('DB接続失敗');
    }

    debug('========ユーザー情報追加の終了========');
    exitAccess();
  }

  //================================
  // ユーザー編集
  //================================
  if(!empty($_POST['getType'] === 'editUserData')){
    debug('========編集の開始========');
    $getType = $_POST['getType'];
    $oldid = $_POST['old_userid'];
    $dataid = $_POST['e_dataid'];
    $userid = $_POST['e_userid'];
    $busnum = $_POST['e_busnum'];
    $username = $_POST['e_username'];
    $gender = $_POST['e_gender'];
    debug('POSTの中身は：'.print_r($_POST,true));

    try {
      // DB接続
      $dbh = dbConnect();
      //SQL文とプレースホルダの準備
      $sql1 = 'UPDATE tb_bus SET id = :dataid, bus_number = :busnum WHERE userid = :oldid';
      $sql2 = 'UPDATE tb_user SET name = :username, gender = :gender WHERE id = :oldid';
      // ここプレースホルダをまとめて一行にしたらできなかったっぽい(トランザクション処理？？)
      $data1 = array(':dataid' => $dataid, ':oldid' => $oldid, ':busnum' => $busnum);
      $data2 = array( ':oldid' => $oldid, ':username' => $username, ':gender' => $gender);
      // クエリ実行
      $stmt1 = queryPost($dbh, $sql1, $data1);
      $stmt2 = queryPost($dbh, $sql2, $data2);

      if($stmt1 && $stmt2){
        $list[] = array(
          "flg" => 2,
          "msg" => '編集ができました',
        );
        debug('編集ができました');
      }else{
        // DBは接続できたがうまくインサートできなかったとき
        $list[] = array(
          "flg" => 3,
          "msg" => '編集に失敗',
        );
        debug('編集に失敗');
      }

    } catch (Exception $e) {
      $list[] = array(
        "flg" => 1,
        "msg" => 'DB接続失敗',
      );
      debug('DB接続失敗');
    }

    debug('========編集の終了========');
    exitAccess();
  }


  //================================
  // ユーザー削除
  //================================
  if(!empty($_POST['getType'] === 'deleteUserData')){
    debug('========削除の開始========');
    $getType = $_POST['getType'];
    $userid = $_POST['d_userid'];
    $username = $_POST['d_username'];
    debug('POSTの中身は：'.print_r($_POST,true));

    try {
      // DB接続
      $dbh = dbConnect();
      //SQL文とプレースホルダの準備
      $sql = 'UPDATE tb_user SET active = 0 WHERE id = :id AND name = :username';
      $data = array( ':id' => $userid, ':username' => $username);
      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);

      if($stmt){
        $list[] = array(
          "flg" => 2,
          "msg" => '削除ができました',
        );
        debug('削除ができました');
      }else{
        // DBは接続できたがうまくインサートできなかったとき
        $list[] = array(
          "flg" => 3,
          "msg" => '削除に失敗',
        );
        debug('削除に失敗');
      }

    } catch (Exception $e) {
      $list[] = array(
        "flg" => 1,
        "msg" => 'DB接続失敗',
      );
      debug('DB接続失敗');
    }

    debug('========削除の終了========');
    exitAccess();
  }


?>

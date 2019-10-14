<?php

//ログを取るか
ini_set('log_errors','on');
//ログの出力ファイルを指定
ini_set('error_log','php.log');

	// maehara_nana@example.com
	define("DB_User", "postgres");			// DBユーザ名
	define("DB_Pass", "post");				// パスワード
	define("DB_Name", "postgres");		// DB名
	define("DB_Host", "localhost");	// ホスト名
	define("DB_Port", "5432");				// ポート


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

	if(!empty($_POST)){

	$email = $_POST['email'];
	$pass = $_POST['pass'];


	try {
		$dsn = "pgsql:dbname=".DB_Name." host=".DB_Host." port=".DB_Port;
		// データベースに接続
		$options = array(
			// SQL実行失敗時に例外をスロー
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			// デフォルトフェッチモードを連想配列形式に設定
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			// バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
			// SELECTで得た結果に対してもrowCountメソッドを使えるようにする
			PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
		);
		$pdo = new PDO($dsn,DB_User,DB_Pass,$options);


				$sql = 'SELECT * FROM tb_user WHERE mail = :email AND password = :pass';
				$data = array(':email' => $email, ':pass' => $pass);
				$stmt = $pdo->prepare($sql);
				$stmt->execute($data);
				//$resultに配列としてDBから取得した値を入れている。
				$result = 0;
				$result = $stmt->fetch(PDO::FETCH_ASSOC);


				if(!empty($result)){
					$list = array("email" => $email, "flg" => 'getUserData', "hoge" => $result['name'], );
					echo json_encode($list);
					exit;
				}else{
					$list = array("email" => '登録なし', "flg" => 2, "hoge" => "メールアドレスまたはパスワードが違います" );
					echo json_encode($list);
					exit;
				}

			}catch(PDOException $e){
				$list = array("email" => '', "flg" => 1, "hoge" => "DB接続失敗" );
				echo json_encode($list);
				exit;
			}
	}
	$list = array("email" => '', "flg" => 0, "hoge" => "POSTの中身はからです" );
	echo json_encode($list);
	exit;



/*

	try {
	    // データベースに接続
			$options = array(
				// SQL実行失敗時に例外をスロー
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				// デフォルトフェッチモードを連想配列形式に設定
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				// バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
				// SELECTで得た結果に対してもrowCountメソッドを使えるようにする
				PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
			);

	    $pdo = new PDO($dsn,DB_User,DB_Pass,$options);

		try {

			$sql = 'SELECT * FROM tb_user WHERE mail = :email';
			$data = array(':email' => $_POST['email']);
			$stmt = $pdo->prepare($sql);
			$stmt->execute($data);

			$result = 0;
			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			if(!empty($result)){
				global $flg;
				$flg =  0;
			}else{
				global $flg;
				$flg =  1;
			}


		} catch (PDOException $e) {
			// print_r($e);
			$result[] = array(
				'sts' 				=> '-1',
				'data' 				=> '',
				'Mess' 				=> ""
			);
		}

			// $JSON_Array = array_merge($JSON_Array,array("_getUserData"=>$row_result));

		/****PHP終了****/
/*		 _exitAccess();


		}catch(PDOException $e) {
		print_r($e);
	}


	function _exitAccess(){	//json終了Function
		global $Session_result,$JSON_Array,$sql_result,$TestMode,$row_result;

		//JSONデータ出力
		echo json_encode($result,JSON_UNESCAPED_UNICODE);
		//JSON.PHP終了
		exit;
	}



	// echo 'こんにちは';
*/
?>

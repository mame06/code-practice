$(document).ready(function(){
	//================================
	// 検索機能
	//================================
	$('#search-btn').on('click',function(){

		new	$.ajax({
			url: 'search_ajax.php',
			type: 'POST', //methodの部分
			dataType: 'json', //textやcsvなどデータタイプを指定できるjsonデータは配列っぽい感じで
			data: { 'seName' :  $('#search').val(), 'getType' : 'getUserData'},
		}).done(function(data){
			alert('ok');
			$('#search-result').empty();
			var src = '';
			src += '<table class="search-table">';
			src += '	<thead><th>No</th><th>ID</th><th>dataID</th><th>名前</th><th>性別</th><th>バス号車</th><th>編集</th><th>削除</th></thead>';
			src += '	<tbody>';
			if(data.getUserData[0].flg === 2){
					for (var i = 0; i < data.getUserData.length; i++) {
						src += '		<tr>';
						src += '			<td>' + (i + 1) + '</td>';
						src += '			<td>' + data.getUserData[i].ouID + '</td>';
						src += '			<td>' + data.getUserData[i].oudataID + '</td>';
						src += '			<td>' + data.getUserData[i].ouname + '</td>';
						src += '			<td>' + data.getUserData[i].ougender + '</td>';
						src += '			<td>' + data.getUserData[i].oubus + '</td>';
						src += '			<td><div class="edit_area"><button class="edit_arbtn" rel="' + i + '">編集</button></div></td>';
						src += '			<td><div class="delete_area"><button class="delete_btn" rel="' + i + '">削除</button></div></td>';
						src += '		</tr>';
					}
				}else{
					src += '		<tr>';
					src += '			<td colspan="8">' + data.getUserData[0].msg + '</td>';
					src += '		</tr>';
				}
				src += '	</tbody>';
				src += '</table>';

			$("#search-result").html(src);
			$('.edit_arbtn').off('click');
			$('.delete_btn').off('click');


			//================================
		  // 削除機能
		  //================================
			$('.delete_btn').on('click',function(){
				var rel = $(this).attr("rel");
				var jsonData = {
					'getType' : 'deleteUserData',
					'd_userid' : data.getUserData[rel].ouID,
					'd_username' : data.getUserData[rel].ouname,
				};
				new	$.ajax({
					url: 'search_ajax.php',
					type: 'POST',
					dataType: 'json',
					data: jsonData,
				}).done(function(data){
					if(data.deleteUserData[0].flg === 2){
						alert(data.deleteUserData[0].msg);
						location.reload(true);
					}else{
						alert(data.deleteUserData[0].msg);
					}
				}).fail(function(XMLHttpRequest, textStatus, error){
						alert('Ajax通信失敗');
				});

			});


			//================================
		  // ユーザー編集
		  //================================
			// 編集画面へデータを渡す
			$('.edit_arbtn').on('click', function(){
				$('.search-area').toggle();
				$('#search-result').toggle();
				$('#add-area').toggle();
				$('#edit_title').toggle();
				$('.edit-btn-area').toggle();

					var rel = $(this).attr("rel");
					var e_userid = $('#userid').val(data.getUserData[rel].ouID);
					var oldid = $('#old_userid').text(data.getUserData[rel].ouID);
					var e_dataid = $('#dataid').val(data.getUserData[rel].oudataID);
					var e_username = $('#username').val(data.getUserData[rel].ouname);

					if(data.getUserData[rel].ougender === '男性'){
						$('input[value="男性"]').prop('checked', true);
					}else if (data.getUserData[rel].ougender === '女性') {
						$('input[value="女性"]').prop('checked', true);
					}

					var e_busnum = $('#busnum').val(data.getUserData[rel].oubus);
			});

			// 編集画面から検索画面へ戻る
			$('#eback-btn').on('click', function(){
				$('#search-result').toggle();
				$('.search-area').toggle();
				$('#add-area').toggle();
				$('#edit_title').toggle();
				$('.edit-btn-area').toggle();
			});

			// 編集画面から送信する
			$('#edit-btn').on('click',function(){
				var jsonData = {
					'getType' : 'editUserData',
					'old_userid' : $('#old_userid').text(),
					'e_dataid' : $('#dataid').val(),
					'e_userid' : $('#userid').val(),
					'e_busnum' : $('#busnum').val(),
					'e_username' : $('#username').val(),
					'e_gender' : $('input[name="gender"]:checked').val(),
				};
				new	$.ajax({
					url: 'search_ajax.php',
					type: 'POST', //methodの部分
					dataType: 'json', //textやcsvなどデータタイプを指定できるjsonデータは配列っぽい感じで
					data: jsonData,
				}).done(function(data){
					if(data.editUserData[0].flg === 2){
						alert(data.editUserData[0].msg);
						location.reload(true);
					}else{
						alert(data.editUserData[0].msg);
					}
				}).fail(function(XMLHttpRequest, textStatus, error){
						alert('Ajax通信失敗');
				});

			});
		}).fail(function(XMLHttpRequest, textStatus, error){
				alert('Ajax通信失敗');
		});


	});

	//================================
	// 追加機能
	//================================
	$('#sign-btn').on('click', function(){
		$('.search-area').toggle();
		$('#search-result').toggle();
		$('#add-area').toggle();
		$('.add-btn-area').toggle();
	});
	$('#back-btn').on('click', function(){
		$('.search-area').toggle();
		$('#search-result').toggle();
		$('#add-area').toggle();
		$('.add-btn-area').toggle();
	});

	$('#add-btn').on('click', function(){
		var jsonData = {
			'getType' : 'addUserData',
			'dataid' : $('#dataid').val(),
			'userid' : $('#userid').val(),
			'busnum' : $('#busnum').val(),
			'username' : $('#username').val(),
			'gender' : $('input[name="gender"]:checked').val(),

		};
		console.log($('#gender').val());
		new	$.ajax({
			url: 'search_ajax.php',
			type: 'POST', //methodの部分
			dataType: 'json', //textやcsvなどデータタイプを指定できるjsonデータは配列っぽい感じで
			data: jsonData,
		}).done(function(data){
			if(data.addUserData[0].flg === 2){
				alert(data.addUserData[0].msg);
				location.reload(true);
			}else{
				alert(data.addUserData[0].msg);
			}
		}).fail(function(XMLHttpRequest, textStatus, error){
				alert('Ajax通信失敗');
		});
	});

});




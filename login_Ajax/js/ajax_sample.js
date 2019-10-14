$(document).ready(function(){

	$('#ajaxout').on('click',function(){

		new	$.ajax({
			url: 'test_ajax.php',
			type: 'POST', //methodの部分
			dataType: 'json', //textやcsvなどデータタイプを指定できるjsonデータは配列っぽい感じで
			data: { 'email' : $('#email').val(), 'pass' : $('#pass').val(), },
			// data: JSON.stringify(jsonData), //とばすデータを書く、emailとかpasswordなど
		}).done(function(data){
			alert('ok');
			$("#res").text(data.email + ':' + data.flg + ':' + data.hoge);
		}).fail(function(XMLHttpRequest, textStatus, error){
				alert(error);
				$("#res").text('Ajax通信失敗');
		});
	});
});


/* const ajaxTimeOutLng = 30000;	//ajaxタイムアウトLong 30秒
const ajaxTimeOutMdl = 10000;	//ajaxタイムアウトMiddle
const ajaxTimeOutSrt = 5000;	//ajaxタイムアウトShort
const ajaxTimeOutNon = 0;		//ajaxタイムアウト無し ずっと通信し続けて、エラーも返ってこない

var jsnModule = "test_ajax.php"; //ajaxデータ取得PHPファイル名 POSTを飛ばすところ

$(document).ready(function(){

	$('#ajaxout').on('click',function(){

		var fData="_fID=_getUserData";  //fID = _getUserData だよっていう意味
		fData += '&_email=' 	+ $('#email').val();
		fData += '&_val_2=' 	+ "田中";

		var jsonData ={
			'fID':'getUserData',
			'email': $('#email').val(),
		};

		console.log(jsonData);

		new	$.ajax({
			url: jsnModule,
			type: 'POST', //methodの部分
		 	dataType: 'json', //textやcsvなどデータタイプを指定できるjsonデータは配列っぽい感じで
			data: jsonData,
			// data: JSON.stringify(jsonData), //とばすデータを書く、emailとかpasswordなど
			timeout: ajaxTimeOutLng
		})
		.then(
			//通信成功時のコールバック
			function (data) {
				//var userdata = data._getUserData[0].data;
				alert('ok');
				if(flg = 0){
					alert('あった');
					$('#res').text(data.email);
				}else if(flg = 1){
					alert('なかった');
					console.log(data.email);
					console.log(data.fID);
				}

//				for (var i = 0; i < userdata.length; i++) {
//					alert(userdata[i].data2);
//				}
			},
			//通信失敗時のコールバック
			function (error) {
				alert("error");
				console.log(jsonData.email);
				console.log(jsonData.fID);
			}
		);
	});




});
*/


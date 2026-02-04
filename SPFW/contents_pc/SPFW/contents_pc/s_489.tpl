<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>リニューアル支援</title>

	<!-- BootstrapのCSS読み込み -->
	<link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<!-- jQuery読み込み -->
	<script src="./include/js/jquery-3.2.1.min.js"></script>

	<!-- BootstrapのJS読み込み -->
	<script src="./include/bootstrap/js/bootstrap.min.js"></script>

	<link rel="stylesheet" type="text/css" href="./css/rnsien.css">
	<script type="text/javascript" src="tools.js"></script>

	<!--datepicker-->
	<link href="css/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
	<link href="css/jquery-ui-1.12.1/jquery-ui.structure.min.css" rel="stylesheet">
	<link href="css/jquery-ui-1.12.1/jquery-ui.theme.min.css" rel="stylesheet">
	<script src="js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script src="js/jquery-ui/datepicker-ja.js"></script>

	<script>
		$(function () {
			$(".datepicker").datepicker({
				numberOfMonths: 2
			});
		});

		// 見積書ダウンロード
		function CheckAndEstimate(page) {

			var error_flg = false;
			var answer = document.getElementById("wAnswer").value; // 専有部工事日時変更受付方法
			var answer489 = document.getElementById("wAnswer489").value; // 予約センター作成資料
			var postingflg = document.getElementById("wPostingFlg").value; // ポスティング利用有無
			var Hansu = document.getElementById("wHansu").value; // 班数入力有無
			var ErrorStrings = "";

			if (answer == "") {
				error_flg = true;
				ErrorStrings += "専有部工事日時変更受付方法を選択してください。\r\n";
			}
			if (postingflg == "") {
				error_flg = true;
				ErrorStrings += "ポスティングの利用の有無を選択してください。\r\n";
			} else if (postingflg == 1) {
				var PostingBasho = document.getElementsByName("wPostingBasho");
				var PostingBashoFlg = false;
				for (var i = 0; i < PostingBasho.length; i++) {
					if (PostingBasho[i].checked == true) {
						PostingBashoFlg = true;
					}
				}
				if (PostingBashoFlg == false) {
					error_flg = true;
					ErrorStrings += "投函場所を選択してください。\r\n";
				}
				var PostType = document.getElementsByName('wPostType[]');
				var PostTypeFlg = false;
				for (var i = 0; i < PostType.length; i++) {
					if (PostType[i].checked == true) {
						PostTypeFlg = true;
					}
				}
				if (PostTypeFlg == false) {
					error_flg = true;
					ErrorStrings += "投函する案内を選択してください。\r\n";
				}
				var WanshoFlg = document.getElementsByName("wWanshoFlg");
				var WanshoFlgFlg = false;
				for (var i = 0; i < WanshoFlg.length; i++) {
					if (WanshoFlg[i].checked == true) {
						WanshoFlgFlg = true;
					}
				}
			}

			// if(error_flg){
			// 	alert(ErrorStrings);
			// }else{
			// 	var win;
			// 	if( !win || win.closed ) {
			// 	}else{
			// 		/*既にウィンドウが開かれている場合は*/
			// 		win.close();
			// 	}
			// 	win = window.open("","mitsumori","");
			// 	document.mainform.action = page;
			// 	document.mainform.target = "mitsumori";
			// 	document.mainform.submit();
			// }

			document.mainform.action = page;
			document.mainform.target = "mitsumori";
			document.mainform.submit();

		}

		function initOnload() {
			chkPic();
			//chkPos();
		}
		// 写真アプリ利用有無
		function chkPic() {
			var wPicStatus = document.getElementById("wPicStatus").value;
			if (wPicStatus === "0") { // 利用しないを選んだ場合
				document.getElementById("wPicAppSeko").disabled = true;
				document.getElementById("wPicAppKoji").disabled = true;
			} else {
				document.getElementById("wPicAppSeko").disabled = false;
				document.getElementById("wPicAppKoji").disabled = false;
			}
		}
		// ポスティング利用有無
		/*
		function chkPos() {
			var wPostingFlg = document.getElementById("wPostingFlg").value;
			if(wPostingFlg === "2"){ // 利用しないを選んだ場合
				document.getElementById("PostingBasho1").disabled = true;
				document.getElementById("PostingBasho2").disabled = true;
				document.getElementById("wPostType1").disabled = true;
				document.getElementById("wPostType2").disabled = true;
				document.getElementById("wPostType3").disabled = true;
				document.getElementById("KeijiBasho").disabled = true;
				document.getElementById("Wansho1").disabled = true;
				document.getElementById("Wansho2").disabled = true;
				//document.getElementById("PostingName").disabled = true;
				document.getElementById("PostingBiko").disabled = true;
			}else{
				document.getElementById("PostingBasho1").disabled = false;
				document.getElementById("PostingBasho2").disabled = false;
				document.getElementById("wPostType1").disabled = false;
				document.getElementById("wPostType2").disabled = false;
				document.getElementById("wPostType3").disabled = false;
				document.getElementById("KeijiBasho").disabled = false;
				document.getElementById("Wansho1").disabled = false;
				document.getElementById("Wansho2").disabled = false;
				//document.getElementById("PostingName").disabled = false;
				document.getElementById("PostingBiko").disabled = false;
			}
		}
		*/
		// 内容確認画面へボタン 入力チェック
		function checkInput() {
			var error_flg = false;
			document.getElementById("AnswerError").innerHTML = "";
			document.getElementById("Answer489Error").innerHTML = "";
			document.getElementById("WEBReceptError").innerHTML = "";
			document.getElementById("PicAppSekoError").innerHTML = "";
			document.getElementById("PicAppKojiError").innerHTML = "";
			document.getElementById("PicStatusError").innerHTML = "";
			document.getElementById("PostingError").innerHTML = "";
			document.getElementById("wHansu").innerHTML = "";
			//document.getElementById("SmartError").innerHTML = "";
			document.getElementById("Error").style = "";

			if (document.getElementById("wAnswer").value == "") {
				error_flg = true;
				document.getElementById("AnswerError").innerHTML = "※選択してください。";
			}
			if (document.getElementById("wAnswer489").value == "") {
				error_flg = true;
				document.getElementById("Answer489Error").innerHTML = "※選択してください。";
			}
			var elements = document.getElementsByName("wWEBRecept");
			for (var web = "", i = elements.length; i--;) {
				if (elements[i].checked) {
					var web = elements[i].value;
					break;
				}
			}
			if (web === "") {
				error_flg = true;
				document.getElementById("WEBReceptError").innerHTML = "※選択してください。";
			}
			if (document.getElementById("wPicAppSeko").value.length > 15) {
				error_flg = true;
				document.getElementById("PicAppSekoError").innerHTML = "15文字以内で設定してください。";
			}
			if (document.getElementById("wPicAppKoji").value.length > 30) {
				error_flg = true;
				document.getElementById("PicAppKojiError").innerHTML = "30文字以内で設定してください。";
			}
			if (document.getElementById("wPicStatus").value == "") {
				error_flg = true;
				document.getElementById("PicStatusError").innerHTML = "※選択してください。";
			}
			if (document.getElementById("wPostingFlg").value == "") {
				error_flg = true;
				document.getElementById("PostingError").innerHTML = "※選択してください。";
			}
			if (document.getElementById("wHansu").value == "") {
				error_flg = true;
				document.getElementById("HansuError").innerHTML = "※班数を入力してください。";

			}
			if (document.getElementById("wConstTime").value == "") {
				error_flg = true;
				document.getElementById("ConstTimeError").innerHTML = "※1戸あたりの作業時間を入力してください。";

			}
			/*
			if(document.getElementById("wSmartFlg").value==""){
				error_flg = true;
				document.getElementById("SmartError").innerHTML = "※選択してください。";
			}
			*/
			if (document.getElementById("wMaxWakuSu").value == "") {
				error_flg = true;
				document.getElementById("WakusuError").innerHTML = "※.最大枠数を入力してください。";

			}

			if (document.getElementById("wMaxWakuSu").value.match(/[^-0-9]+/)) {
				error_flg = true;
				document.getElementById("WakusuError").innerHTML = "※半角数字を入力してください";
			}



			var $wYoyakuEnd = $('input[name="wYoyakuEnd"]').val();
			var $wTekyoDate = $('input[name="wTekyoDate"]').val();
			var $wKeteiDate = $('input[name="wKeteiDate"]').val();
			var wYoyakuEnd = new Date($wYoyakuEnd);
			var wTekyoDate = new Date($wTekyoDate);
			var wKeteiDate = new Date($wKeteiDate);

			var checked = 1;
			var checked2 = 1;

			if (wYoyakuEnd > wTekyoDate) {
				checked = 2;
			}
			if (wYoyakuEnd > wKeteiDate) {
				checked2 = 2;
			}

			if (checked == 1 && checked2 == 1) {

			} else {
				error_flg = true;
				alert('決定案内配布日か決定案内提供日が受付締切日より以前の日付になっています');
				return false;
			}

			// if(wYoyakuEnd > wTekyoDate){
			// 	var a = confirm("締切日より案内提供日が以前の日付になっています");
			// 	if(a == true){
			// 		alert("true");
			// 	}else{
			// 		alert("false");
			// 	}
			// }


			if (error_flg) {
				document.getElementById("Error").style.backgroundColor = "yellow";
				window.scrollTo(0, 0);
				return false;
			} else {
				document.mainform.method = "POST";
				document.mainform.target = "_self";
				document.mainform.action = "s_489_confirm.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__";
				document.mainform.submit();
			}
		}

		function isNumber(numVal) {
			// チェック条件パターン
			var pattern = /^[-]?([1-9]\d*|0)(\.\d+)?$/;
			// 数値チェック
			return pattern.test(numVal);
		}

		function hankaku2Zenkaku(str) {
			return str.replace(/[Ａ-Ｚａ-ｚ０-９]/g, function (s) {
				return String.fromCharCode(s.charCodeAt(0) - 0xFEE0);
			});
		}

		function CheckNumber() {
			Hansu = document.getElementById("wHansu").value;
			Hansu = hankaku2Zenkaku(Hansu);
			if (isNumber(Hansu)) {
				document.getElementById("wHansu").value = Hansu;
			} else {
				document.getElementById("wHansu").value = "";
			}
		}
		// 一時保存ボタン
		function noCheckInput() {
			document.mainform.method = "POST";
			document.mainform.target = "_self";
			document.mainform.work.value = 1;
			document.mainform.action = "s_489_finish.php?ichijihozon=1";
			document.mainform.submit();
		}



		$(function () {
			var user_data = __user_data__;
			var gyosya_data = __gyosya_data__;
			var gyosya;
			$('#wGyosyaCD1').change(function () {
				gyosya = $(this).val(); // 支店CD
				// 削除
				$('select#wTantoCD1 option').remove();
				// 1行目セット
				$("#wTantoCD1").append("<option value=''>-</option>");

				var eig = [];
				// https://www.webopixel.net/javascript/91.html
				for (var i in user_data[gyosya]) {
					$("#wTantoCD1").append("<option value=" + user_data[gyosya][i].UserCD + ">" + user_data[gyosya][i].LastName + "</option>");
				}
			});

			$('#wGyosyaCD2').change(function () {
				gyosya = $(this).val(); // 支店CD
				// 削除
				$('select#wTantoCD2 option').remove();
				// 1行目セット
				$("#wTantoCD2").append("<option value=''>-</option>");

				var eig = [];
				// https://www.webopixel.net/javascript/91.html
				for (var i in user_data[gyosya]) {
					$("#wTantoCD2").append("<option value=" + user_data[gyosya][i].UserCD + ">" + user_data[gyosya][i].LastName + "</option>");
				}
			});

			$('#wGyosyaCD3').change(function () {
				gyosya = $(this).val(); // 支店CD
				// 削除
				$('select#wTantoCD3 option').remove();
				// 1行目セット
				$("#wTantoCD3").append("<option value=''>-</option>");

				var eig = [];
				// https://www.webopixel.net/javascript/91.html
				for (var i in user_data[gyosya]) {
					$("#wTantoCD3").append("<option value=" + user_data[gyosya][i].UserCD + ">" + user_data[gyosya][i].LastName + "</option>");
				}
			});

			$('#wGyosyaCD4').change(function () {
				gyosya = $(this).val(); // 支店CD
				// 削除
				$('select#wTantoCD4 option').remove();
				// 1行目セット
				$("#wTantoCD4").append("<option value=''>-</option>");

				var eig = [];
				// https://www.webopixel.net/javascript/91.html
				for (var i in user_data[gyosya]) {
					$("#wTantoCD4").append("<option value=" + user_data[gyosya][i].UserCD + ">" + user_data[gyosya][i].LastName + "</option>");
				}
			});

			$('#wSekoTantoCD1').change(function () {
				gyosya2 = $(this).val(); // 支店CD
				// 削除
				$('select#wGyosyaTantoCD1 option').remove();
				// 1行目セット
				$("#wGyosyaTantoCD1").append("<option value=''>-</option>");

				var eig = [];
				//console.log(user_data[siten]);
				// https://www.webopixel.net/javascript/91.html
				for (var i in user_data[gyosya2]) {
					$("#wGyosyaTantoCD1").append("<option value=" + user_data[gyosya2][i].UserCD + ">" + user_data[gyosya2][i].LastName + "</option>");
				}
			});

			$('#wSekoTantoCD2').change(function () {
				gyosya2 = $(this).val(); // 支店CD
				// 削除
				$('select#wGyosyaTantoCD2 option').remove();
				// 1行目セット
				$("#wGyosyaTantoCD2").append("<option value=''>-</option>");

				var eig = [];
				//console.log(user_data[siten]);
				// https://www.webopixel.net/javascript/91.html
				for (var i in user_data[gyosya2]) {
					$("#wGyosyaTantoCD2").append("<option value=" + user_data[gyosya2][i].UserCD + ">" + user_data[gyosya2][i].LastName + "</option>");
				}
			});

			$('#wSekoTantoCD3').change(function () {
				gyosya2 = $(this).val(); // 支店CD
				// 削除
				$('select#wGyosyaTantoCD3 option').remove();
				// 1行目セット
				$("#wGyosyaTantoCD3").append("<option value=''>-</option>");

				var eig = [];
				//console.log(user_data[siten]);
				// https://www.webopixel.net/javascript/91.html
				for (var i in user_data[gyosya2]) {
					$("#wGyosyaTantoCD3").append("<option value=" + user_data[gyosya2][i].UserCD + ">" + user_data[gyosya2][i].LastName + "</option>");
				}
			});


			$('#wSekoTantoCD4').change(function () {
				gyosya2 = $(this).val(); // 支店CD
				// 削除
				$('select#wGyosyaTantoCD4 option').remove();
				// 1行目セット
				$("#wGyosyaTantoCD4").append("<option value=''>-</option>");

				var eig = [];
				//console.log(user_data[siten]);
				// https://www.webopixel.net/javascript/91.html
				for (var i in user_data[gyosya2]) {
					$("#wGyosyaTantoCD4").append("<option value=" + user_data[gyosya2][i].UserCD + ">" + user_data[gyosya2][i].LastName + "</option>");
				}
			});

			$('#wSekoTantoCD5').change(function () {
				gyosya2 = $(this).val(); // 支店CD
				// 削除
				$('select#wGyosyaTantoCD5 option').remove();
				// 1行目セット
				$("#wGyosyaTantoCD5").append("<option value=''>-</option>");

				var eig = [];
				//console.log(user_data[siten]);
				// https://www.webopixel.net/javascript/91.html
				for (var i in user_data[gyosya2]) {
					$("#wGyosyaTantoCD5").append("<option value=" + user_data[gyosya2][i].UserCD + ">" + user_data[gyosya2][i].LastName + "</option>");
				}
			});
		});
	</script>
	<script type="text/javascript" src="js/tools_ajax.js"></script>
	<script type="text/javascript" src="js/ConnectedSelect.js"></script>
	<style>
		p.list {
			margin-bottom: 5px;
		}
	</style>
</head>

<body onload="initOnload();">
	__SHeader__

	<div class="content-all">
		<!--content-all-->

		<div class="left-yose">
			<a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜メニュー</a>
		</div>

		<div class="top-menu left-yose">
			<h5>__wBukkenName__</h5>
			<h6>予約センター受付依頼</h6>



			<form action="s_489_confirm.php" method="POST" name="mainform">
				<input type="hidden" name="work" value="1">

				__IfNew__
				<button type='button' onClick='moveWithKey( "s_489_copy.php?rKey=__rKey__" , __editBukkenCD__);' class='btn btn-info btn-xs' style='margin:5px 0px'>過去物件情報からコピー</button>
				__IfNew__
				<br>

				<!--
<p align="right"><font color="blue">参考フォーマット：</font>
<a href="/kotei/upfile/iraifile_tmp/Format_ver1.xls">▼予定案内・確定案内フォーマットver1.xls</a>
<a href="/kotei/upfile/iraifile_tmp/iraitejun.pdf">依頼手順書</a></p>
-->
				<span id="Error">
					<font color="red">※は入力必要項目です。</font>
				</span><br>

				<br>
				1．受付依頼ステータス
				<table class="table table-bordered table-sm">
					<colgroup>
						<col style='width:200px;'>
						<col style=''>
					</colgroup>
					<!--
<tr><th class="yb">管理No</th><td>__KenmeiNo__</td></tr>
-->
					<tr>
						<th class="yb">工事番号</th>
						<td>__KenmeiNo__</td>
					</tr>
					<tr>
						<th class="yb">物件CD</th>
						<td>__editBukkenCD__</td>
					</tr>
					<tr>
						<th class="yb">物件名</th>
						<td>__wBukkenName__</td>
					</tr>
					<!--<tr><th>所属支店</th><td>__wSitenName__</td></tr>-->
					<tr>
						<th class="yb">受付依頼ステータス</th>
						<td>__wwIraiRenkeiStatus__</td>
					</tr>
					<tr>
						<th class="yb">依頼受付日</th>
						<td>__wIraiDate__<input type="hidden" name="wIraiDate" value="__wIraiDate__">　　最終依頼日時:__wUpdated__　更新者:__wUpdaterName__</td>
					</tr>
				</table>

				2．物件基本情報
				<table class="table table-bordered table-sm">
					<colgroup>
						<col style='width:200px;'>
						<col style=''>
					</colgroup>
					<tr>
						<th class="yb">住所</th>
						<td>__wAddress__</td>
					</tr>
					<!-- <tr><th class="yb">施工主体</th>
		<td><input type="text" name="wSekoShutai" value="__wSekoShutai__" style="width:400px;"></td>

</tr> -->
					<!--<tr><th>マンション販売形態</th><td>__wwBunjyo__</td></tr>-->
					<tr>
						<th class="yb">総戸数</th>
						<td>__wKosu__</td>
					</tr>

					<tr>
						<th class="yb">管理会社名</th>
						<td>__wKanriGaisya__</td>
					</tr>

				</table>

				基本料金に含まれているもの
				<table class="table table-bordered table-sm">
					<colgroup>
						<col style='width:100px;'>
						<col style='width:200px;'>
						<col style=''>
					</colgroup>
					<tr>
						<th class="yb" colspan="2">専有部工事日時変更受付方法<font color="red">　※</font>
						</th>
						<td bgcolor="#FFF0F5">
							<select name="wAnswer" style="width:300px;" id="wAnswer">
								<option value="">-</option>
								<option value="A.日時変更住戸のみ返答" __Answer1Selected__>A.日時変更住戸のみ返答</option>
								<option value="B.全住戸返答" __Answer2Selected__>B.全住戸返答</option>
								<option value="C.全住戸返答+確定時未返事シート" __Answer3Selected__>C.全住戸返答+確定時未返事シート</option>
							</select><br>
							<font color="red"><b><span id="AnswerError" style="background-color:yellow"></span></b></font>
						</td>
					</tr>


					<tr>
						<th class="yb" colspan="2">予約センター作成資料<font color="red">　※</font>
						</th>
						<td bgcolor="#FFF0F5">
							<select name="wAnswer489" style="width:300px;" id="wAnswer489">
								<option value="">-</option>
								<option value="利用なし" __Answer4890Selected__>利用なし</option>
								<option value="予定・決定案内利用" __Answer4891Selected__>予定・決定案内利用</option>
								<option value="決定案内のみ利用" __Answer4892Selected__>決定案内のみ利用</option>
								<option value="予定案内のみ利用" __Answer4893Selected__>予定案内のみ利用</option>
							</select><br>
							<font color="red"><b><span id="Answer489Error" style="background-color:yellow"></span></b></font>
						</td>
					</tr>


					<tr>
						<th class="yb" colspan="2">WEB受付<font color="red">　※</font>
						</th>
						<td bgcolor="#FFF0F5">
							<input type="radio" name="wWEBRecept" value="1" __WEBRecept1Checked__>有　
							<input type="radio" name="wWEBRecept" value="0" __WEBRecept0Checked__>無　
							<font color="red"><b><span id="WEBReceptError" style="background-color:yellow"></span></b></font>
						</td>
					</tr>
					<!--
<tr><th class="yb" rowspan="3" >利用様式</th>
	<th>予定案内</th>
	<td>統一フォーマットとなります。<br>
		<select name="wYoshikiYotei">
		<option value="利用しない" __YoshikiYotei0Selected__>利用しない</option>
		<option value="予定１-A" __YoshikiYotei1Selected__>予定１-A</option>
		<option value="予定１-B" __YoshikiYotei2Selected__>予定１-B</option>
		<option value="予定２-A" __YoshikiYotei3Selected__>予定２-A</option>
		<option value="予定２-B" __YoshikiYotei4Selected__>予定２-B</option>
		<option value="予定３-A" __YoshikiYotei5Selected__>予定３-A</option>
		<option value="予定３-B" __YoshikiYotei6Selected__>予定３-B</option>
		<option value="独自書式" __YoshikiYotei7Selected__>独自書式</option>
		</select>
		<input type="hidden"  name="wYoshikiYotei" value="統一書式" >
		<font color="red" size="2">※独自書式の場合は要相談</font>
	</td></tr>
<tr><th>確定案内</th>
	<td>統一フォーマットとなります。<br>
		<select name="wYoshikiKakutei">
		<option value="利用しない" __YoshikiKakutei0Selected__>利用しない</option>
		<option value="確定１" __YoshikiKakutei1Selected__>確定１</option>
		<option value="確定２" __YoshikiKakutei2Selected__>確定２</option>
		<option value="独自書式" __YoshikiKakutei3Selected__>独自書式</option>
		</select>
		<input type="hidden"  name="wYoshikiKakutei" value="統一書式" >
	</td></tr>
<tr><th class="yb">オプション案内</th>
	<td><select name="wOPUketuke">
		<option value="">-</option>
		<option value="1" __OPUketuke1Selected__>必要</option>
		<option value="2" __OPUketuke2Selected__>不要</option>
		</select>
	</td></tr>
-->
					<!--20190925不要
<tr><th class="yb" colspan="2">確定案内 記載社名</th>
	<td><select name="wKakuteiCompanyName">
		<option value="アイホン株式会社" __KakuteiCompany1Selected__>アイホン株式会社</option>
		<option value="アイホン株式会社+管理会社" __KakuteiCompany2Selected__>アイホン株式会社+管理会社</option>
		<option value="資料記載に合わせる" __KakuteiCompany3Selected__>資料記載に合わせる</option>
		<option value="その他" __KakuteiCompany4Selected__>その他</option><br>
		</select>
		<br>
		<font size="2" color="gray">※その他の場合は「8．その他（注意事項等）」に入力して下さい</font><br>
		<font color="red" size="2">※ポスティングありの場合は記入必須</font>
	</td></tr>-->

					<tr>
						<th class="yb" colspan="2">予定・決定案内　記載社名</th>
						<td>
							<font size="2" color="gray">※資料には上から順に記載します。<br>　空欄の場合は工事資料に合わせて記載します。</font>


							<p class="list">①　<input type="text" name="wYoteiKetteiCompanyName1" value="__wYoteiKetteiCompanyName1__" style="width:250px;"></p>
							<p class="list">②　<input type="text" name="wYoteiKetteiCompanyName2" value="__wYoteiKetteiCompanyName2__" style="width:250px;"></p>
							<p class="list">③　<input type="text" name="wYoteiKetteiCompanyName3" value="__wYoteiKetteiCompanyName3__" style="width:250px;"></p>


							<!-- <p class="list">①　<input type="text" name="wYoteiKetteiCompanyName1" style="width:250px;"></p>
		<p class="list">②　<input type="text" name="wYoteiKetteiCompanyName2" style="width:250px;"></p>
		<p class="list">③　<input type="text" name="wYoteiKetteiCompanyName3" style="width:250px;"></p> -->

						</td>
					</tr>


				</table>

				オプション (基本料金に含まれていないメニュー）
				<table class="table table-bordered table-sm">
					<colgroup>
						<col style='width:150px;'>
						<col style='width:150px;'>
						<col style=''>
					</colgroup>
					<!--<tr><th class="yb" rowspan="3">写真アプリ<font color="red">　※</font></th>--->
					<tr>
						<th class="yb" rowspan="3">工事写真アプリ<font color="red">　※</font>
						</th>
						<th class="yb">利用有無</th>
						<td bgcolor="#FFF0F5">
							<select name="wPicStatus" id="wPicStatus" onchange="chkPic()">
								<option value="">-</option>
								<option value="0" __PicStatus0Selected__>利用しない</option>
								<option value="1" __PicStatus1Selected__>利用する</option>
							</select>
							<font color="red"><b><span id="PicStatusError" style="background-color:yellow"></span></b></font>
							<font size="2" color="red">利用する場合は下記の2項目へ入力をしてください。</font>
						</td>
					</tr>
					<tr>
						<th class="yb">電子看板アプリ用<br>工事名</th>
						<td>
							<font color="gray">※看板に表示する工事名(マンション名は含めないでください。)（全角30文字以内）</fonr><br>
								<input type="text" name="wPicAppKojiName" id="wPicAppKoji" value="__wPicAppKojiName__" style="width:460px;" placeholder="インターホン更新工事"><br>
								<font color="red"><b><span id="PicAppKojiError" style="background-color:yellow"></span></b></font>
						</td>
					<tr>
						<th class="yb">電子看板アプリ用<br>施工会社名</th>
						<td>
							<font color="gray">※看板に表示する施工会社名（15文字以内）</fonr><br>
								<input type="text" name="wPicAppSekoName" id="wPicAppSeko" value="__wPicAppSekoName__" style="width:300px;"><br>
								<font color="red"><b><span id="PicAppSekoError" style="background-color:yellow"></span></b></font>
						</td>
					</tr>
					<tr>
						<th class="yb">工事完了確認書アプリ</th>
						<th class="yb">利用有無</th>
						<td><select name="wKakuninFlg">
								<option value="0" __KakuninFlg0Selected__>利用しない</option>
								<option value="1" __KakuninFlg1Selected__>利用する</option>
							</select>
							<br>※利用する場合は、物件準備後、項目の確認をしてください。
						</td>
					</tr>

					<tr>
						<th class="yb" rowspan="1">ポスティング<font color="red">　※</font>
						</th>
						<th class="yb">利用有無</th>
						<!--
	<td bgcolor="#FFF0F5">
		<select name="wPostingFlg" id="wPostingFlg" onchange="chkPos()">
		<option value="">-</option>
		<option value="2" __PostingSelected2__>利用しない</option>
		<option value="1" __PostingSelected1__>利用する</option>
		</select>
		<font color="red"><b><span id="PostingError" style="background-color:yellow"></span></b></font>
	</td>
	-->
						<td bgcolor="#FFF0F5">
							<select name="wPostingFlg" id="wPostingFlg">
								<!--<option value="">-</option>-->
								<option value="0" __PostingSelected0__>利用しない</option>
								<option value="3" __PostingSelected3__>予定案内のみ</option>
								<option value="1" __PostingSelected1__>確定案内のみ</option>
								<option value="2" __PostingSelected2__>予定案内・確定案内</option>
							</select>
							<font color="red"><b><span id="PostingError" style="background-color:yellow"></span></b></font>
						</td>
					</tr>

					<!--
<tr><th class="yb">投函場所</th>
	<td>
		<input type="radio" name="wPostingBasho" value="1" id="PostingBasho1" __PostingBashoChecked1__ >集合ポスト
		<input type="radio" name="wPostingBasho" value="2" id="PostingBasho2" __PostingBashoChecked2__ >ドア前ポスト/ドア貼り付け
	</td>
</tr>
<tr><th class="yb">投函する案内</th>
	<td>
		<input type="checkbox" name="wYoteiPost">おまかせ東京パック（ヒアリング付き現調案内、予定・確定・催促）<br>
		<input type="checkbox" name="wPostType[]" id="wPostType1" value="1" __PostTypeChecked1__  >予定案内<br>
		<input type="checkbox" name="wPostType[]" id="wPostType2" value="2" __PostTypeChecked2__  >確定案内・未連絡住戸用催促案内<br>
		<input type="checkbox" name="wPostType[]" id="wPostType3" value="3" __PostTypeChecked3__  >東京おまかせパック（現調・予定・確定）<br>
		　掲示場所（エントランス、エレベータ、非常口）<br>
		　<input type="text" id="KeijiBasho" name="wKeijiBasho" value="__wKeijiBasho__"  >
	</td>
</tr>
<tr><th class="yb">腕章</th>
	<td>
		<input type="radio" name="wWanshoFlg" id="Wansho1" value="1" __WanshoFlgChecked1__ >腕章をして投函<br>
		<input type="radio" name="wWanshoFlg" id="Wansho2" value="2" __WanshoFlgChecked2__ >腕章しない<br>
		<span id="Wansho"></span>
	</td>
</tr>

<tr><th class="yb">管理員向け封書宛名</th>
	<td><select name="wPostingName" id="PostingName">
		<option value="東京総合" __PostingName1Selected__>東京総合エンジニアリング株式会社</option>
		<option value="東京総合+管理会社" __PostingName2Selected__>東京総合エンジニアリング株式会社+管理会社</option>
		<option value="資料記載に合わせる" __PostingName3Selected__>資料記載に合わせる</option>
		<option value="その他" __PostingName4Selected__>その他</option><br>
		</select>
		<br>管理員様封書の差出名
	</td>
</tr>
<tr><th class="yb">その他依頼事項</th>
	<td>
		専用封筒に入れ投函などご要望がありましたら記載お願いします。<br>
		<input type="text" name="wPostingBiko" id="PostingBiko" value="__wPostingBiko__" style="width:300px;">
	</td>
</tr>
-->

					<!--
<tr><th class="yb">スマートボード<font color="red">　※</font></th>
	<th class="yb">利用有無</th>
	<td bgcolor="#FFF0F5">
		<select name="wSmartFlg" id="wSmartFlg" >
			<option value="" __SmartFlgSelected__>-</option>
			<option value="0" __SmartFlg0Selected__>利用しない</option>
			<option value="1" __SmartFlg1Selected__>利用する</option>
		</select>
		<font color="red"><b><span id="SmartError" style="background-color:yellow"></span></b></font>
	</td></tr>
-->

				</table>

				__Ifnespeueda__
				<input type="button" value="見積書ダウンロード" onclick="CheckAndEstimate('s_489_mitsumori.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__');" class="btn btn-primary">
				__Ifnespeueda__

				<br><br>


				3．日程
				<table class="table table-bordered table-sm">
					<colgroup>
						<col style='width:200px;'>
						<col style='width:200px;'>
						<col style='width:400px;'>
					</colgroup>
					<tr>
						<th class="yb">予定案内提供日</th>
						<td><input type="text" name="wYoteTekyoDate" value="__wYoteTekyoDate__" class="datepicker" style="width:120px;"></td>
						<td class="yb">
							<font color="dimgray">ネスペ⇒担当者に資料を提供する日</font>
						</td>
					</tr>

					<tr>
						<th class="yb">工事説明資料配布日</th>
						<td><input type="text" name="wYoteDate" value="__wYoteDate__" class="datepicker" style="width:120px;"></td>
						<td class="yb">
							<font color="dimgray">予約システム受付サービス開始日</font>
						</td>
					</tr>

					<tr>
						<th class="yb">変更受付締切日</th>
						<td><input type="text" name="wYoyakuEnd" value="__wYoyakuEnd__" class="datepicker" style="width:120px;"></td>
						<td class="yb">
							<font color="dimgray">受付を一旦終了し、工事日時調整開始</font>
						</td>
					</tr>

					<tr>
						<th class="yb">専有部決定案内提供日</th>
						<td><input type="text" name="wTekyoDate" value="__wTekyoDate__" class="datepicker" style="width:120px;"></td>
						<td class="yb">
							<font color="dimgray">ネスペ⇒担当者に資料を提供する日</font>
						</td>
					</tr>

					<tr>
						<th class="yb">専有部決定案内配布日</th>
						<td><input type="text" name="wKeteiDate" value="__wKeteiDate__" class="datepicker" style="width:120px;"></td>
						<td class="yb">
							<font color="dimgray">以後の専有部工事日時変更は、メールにて報告</font>
						</td>
					</tr>

					<tr>
						<th class="yb">オプション締切日</th>
						<td><input type="text" name="wOpEndDate" value="__wOpEndDate__" class="datepicker" style="width:120px;"></td>
						<td class="yb">
							<font size="2" color="red">オプションの締切が日程の締切と異なる場合変更します</font>
						</td>
					</tr>

					__Optiontpl__

				</table>

				4．工事施工会社情報
				<table class="table table-bordered table-sm">
					<colgroup>
						<col style='width:200px;'>
					</colgroup>
					<tr>
						<th class="yb" colspan="2">担当者情報　<a href=./s_tanto_detail.php?rKey=__rKey__> <!--リストにない場合の登録はこちらから＞＞</a>--> </th> </tr> <tr>
					<tr>
						<th class="yb" style="color:red">担当者メモ</th>
						<td>
							<input type="text" name="wTantoMemo" value="__wTantoMemo__" style="width:200px;">
							<p style="color:red; display: inline-block;">当日連絡可能な担当者名を記入してください。</p>

						</td>
					</tr>
					<th class="yb">担当者１</th>
					<td>
						<select name="wTantoCD1">
							<option value="0">-</option>
							__UserTantoLoop__
							<option value="__UserTantoCD__" __TantoCD1Selected__>__UserGyosyaSitenName__ __UserTantoName__</option>
							__UserTantoLoop__
						</select>
					</td>
					</tr>
					<tr>
						<th class="yb">担当者２</th>
						<td>
							<select name="wTantoCD2">
								<option value="0">-</option>
								__UserTantoLoop__
								<option value="__UserTantoCD__" __TantoCD2Selected__>__UserGyosyaSitenName__ __UserTantoName__</option>
								__UserTantoLoop__
							</select>
						</td>
					</tr>

					<tr>
						<th class="yb">担当者3</th>
						<td>
							<select name="wTantoCD3">
								<option value="0">-</option>
								__UserTantoLoop__
								<option value="__UserTantoCD__" __TantoCD3Selected__>__UserGyosyaSitenName__ __UserTantoName__</option>
								__UserTantoLoop__
							</select>
						</td>
					</tr>
					<tr>
						<th class="yb">担当者4</th>
						<td>
							<select name="wTantoCD4">
								<option value="0">-</option>
								__UserTantoLoop__
								<option value="__UserTantoCD__" __TantoCD4Selected__>__UserGyosyaSitenName__ __UserTantoName__</option>
								__UserTantoLoop__
							</select>
						</td>
					</tr>




					<tr>
						<th class="yb" colspan="2">施工業者情報<br>
							<!--<font size="2" color="gray">※リストにない場合は「8．その他（注意事項等）」に会社名,担当者名,連絡先,メールアドレスをご記入お願いします。</font>-->
						</th>
					</tr>
					<tr>
						<th class="yb" style="color:red">担当者メモ</th>
						<td>
							<input type="text" name="wGyosyaTantoMemo" value="__wGyosyaTantoMemo__" style="width:200px;">
							<p style="color:red; display: inline-block;">当日連絡可能な担当者名を記入してください。</p>
						</td>
					</tr>
					<tr>
						<th class="yb">施工業者担当者１</th>

						<td>
							<select name="wSekoTantoCD1" id="wSekoTantoCD1">
								<option value="">-</option>
								__GyosyaLoop__<option value="__GyosyaCD__" __GyosyaSekoCD1Selected__>__GyosyaName__</option>__GyosyaLoop__
							</select><br>
							<select name="wGyosyaTantoCD1" id="wGyosyaTantoCD1">
								<option value="">-</option>
								__UserdataLoop__<option value="__UserdataCD__" __TantoSekoCD1Selected__>__UserSitenName__ __UserName__</option>__UserdataLoop__
							</select>
						</td>
					</tr>
					<tr>
						<th class="yb">施工業者担当者２</th>
						<td>
							<select name="wSekoTantoCD2" id="wSekoTantoCD2">
								<option value="">-</option>
								__GyosyaLoop__<option value="__GyosyaCD__" __GyosyaSekoCD2Selected__>__GyosyaName__</option>__GyosyaLoop__
							</select><br>
							<select name="wGyosyaTantoCD2" id="wGyosyaTantoCD2">
								<option value="">-</option>
								__UserdataLoop__<option value="__UserdataCD__" __TantoSekoCD2Selected__>__UserSitenName__ __UserName__</option>__UserdataLoop__
							</select>
						</td>
					</tr>

					<tr>
						<th class="yb">施工業者担当者３</th>
						<td>
							<select name="wSekoTantoCD3" id="wSekoTantoCD3">
								<option value="">-</option>
								__GyosyaLoop__<option value="__GyosyaCD__" __GyosyaSekoCD3Selected__>__GyosyaName__</option>__GyosyaLoop__
							</select><br>
							<select name="wGyosyaTantoCD3" id="wGyosyaTantoCD3">
								<option value="">-</option>
								__UserdataLoop__<option value="__UserdataCD__" __TantoSekoCD3Selected__>__UserSitenName__ __UserName__</option>__UserdataLoop__
							</select>
						</td>
					</tr>

					<tr>
						<th class="yb">施工業者担当者４</th>
						<td>
							<select name="wSekoTantoCD4" id="wSekoTantoCD4">
								<option value="">-</option>
								__GyosyaLoop__<option value="__GyosyaCD__" __GyosyaSekoCD4Selected__>__GyosyaName__</option>__GyosyaLoop__
							</select><br>
							<select name="wGyosyaTantoCD4" id="wGyosyaTantoCD4">
								<option value="">-</option>
								__UserdataLoop__<option value="__UserdataCD__" __TantoSekoCD4Selected__>__UserSitenName__ __UserName__</option>__UserdataLoop__
							</select>
						</td>
					</tr>

					<tr>
						<th class="yb">施工業者担当者５</th>
						<td>
							<select name="wSekoTantoCD5" id="wSekoTantoCD5">
								<option value="">-</option>
								__GyosyaLoop__<option value="__GyosyaCD__" __GyosyaSekoCD5Selected__>__GyosyaName__</option>__GyosyaLoop__
							</select><br>
							<select name="wGyosyaTantoCD5" id="wGyosyaTantoCD5">
								<option value="">-</option>
								__UserdataLoop__<option value="__UserdataCD__" __TantoSekoCD5Selected__>__UserSitenName__ __UserName__</option>__UserdataLoop__
							</select>
						</td>
					</tr>
				</table>

				5．工事可能戸数
				<table class="table table-bordered table-sm">
					<colgroup>
						<col style='width:150px;'>
						<col style='width:150px;'>
						<col style=''>
					</colgroup>


					<tr>
						<!--<th rowspan="3">作業時間帯区分</th>-->
						<th class="yb">午前</th>
						<td>__wTimeAStart__～__wTimeAEnd__ 　__wTimeASu__戸</td>
					</tr>
					<tr>
						<th class="yb">午後１</th>
						<td>__wTimeBStart__～__wTimeBEnd__ 　__wTimeBSu__戸</td>
					</tr>
					<tr>
						<th class="yb">午後２</th>
						<td>__wTimeCStart__～__wTimeCEnd__ 　__wTimeCSu__戸</td>
					</tr>


					<tr>
						<th class="yb">枠パターン</th>
						<!--<th class="yb"></th>-->
						<td>
							<select name="wWakuPattern" id="wakupattern" style="width:680px" ;>
								<option value="">-</option>
								__WakuPatternLoop__
								<option value="__WakuPattern__" __SelectedWakuPattern__>__WakuPatternName__</option>
								__WakuPatternLoop__
							</select>
						</td>
					</tr>

					<tr>
						<th class="yb">最大可能枠数<font color="red">　※</font>
						</th>
						<!--<th class="yb">(例:4-3-3)</th>-->
						<td><input type="text" id="wMaxWakuSu" name="wMaxWakuSu" value="__wMaxWakuSu__" style="width:120px;">(例:4-3-3)</td>
					</tr><span id="WakusuError" style="background-color:yellow"></span>

				</table>
				<input type="hidden" name="wTimeAStart" value="__wTimeAStart__">
				<input type="hidden" name="wTimeAEnd" value="__wTimeAEnd__">
				<input type="hidden" name="wTimeASu" value="__wTimeASu__">
				<input type="hidden" name="wTimeBStart" value="__wTimeBStart__">
				<input type="hidden" name="wTimeBEnd" value="__wTimeBEnd__">
				<input type="hidden" name="wTimeBSu" value="__wTimeBSu__">
				<input type="hidden" name="wTimeCStart" value="__wTimeCStart__">
				<input type="hidden" name="wTimeCEnd" value="__wTimeCEnd__">
				<input type="hidden" name="wTimeCSu" value="__wTimeCSu__">


				6．施工方法情報
				<table class="table table-bordered table-sm">
					<colgroup>
						<col style='width:200px;'>
						<col style=''>
					</colgroup>
					<tr>
						<th class="yb">居室親機型番</th>
						<td>__OyaKataban__ __OyaDeviceName__</td>
					</tr>
					<tr>
						<th class="yb">玄関子機型番</th>
						<td>__KokiKataban__ __KokiDeviceName__</td>
					</tr>
					<!--
<tr><th rowspan="10">オプション機器<br>販売価格</th>
	<td>01．__wOP1Name__ <input type="hidden" name="wOP1Name" value="__wOP1Name__" >
		　￥__wOPPrice1__<input type="hidden" name="wOPPrice1" value="__wOPPrice1__" >
	</td></tr>
<tr><td>02．__wOP2Name__ <input type="hidden" name="wOP2Name" value="__wOP2Name__" >
		　￥__wOPPrice2__<input type="hidden" name="wOPPrice2" value="__wOPPrice2__" >
	</td></tr>
<tr><td>03．__wOP3Name__ <input type="hidden" name="wOP3Name" value="__wOP3Name__" >
		　￥__wOPPrice3__<input type="hidden" name="wOPPrice3" value="__wOPPrice3__" >
	</td></tr>
<tr><td>04．__wOP4Name__ <input type="hidden" name="wOP4Name" value="__wOP4Name__" >
		　￥__wOPPrice4__<input type="hidden" name="wOPPrice4" value="__wOPPrice4__" >
	</td></tr>
<tr><td>05．__wOP5Name__ <input type="hidden" name="wOP5Name" value="__wOP5Name__" >
		　￥__wOPPrice5__<input type="hidden" name="wOPPrice5" value="__wOPPrice5__" >
	</td></tr>
<tr><td>06．__wOP6Name__ <input type="hidden" name="wOP6Name" value="__wOP6Name__" >
		　￥__wOPPrice6__<input type="hidden" name="wOPPrice6" value="__wOPPrice6__" >
	</td></tr>
<tr><td>07．__wOP7Name__ <input type="hidden" name="wOP7Name" value="__wOP7Name__" >
		　￥__wOPPrice7__<input type="hidden" name="wOPPrice7" value="__wOPPrice7__" >
	</td></tr>
<tr><td>08．__wOP8Name__ <input type="hidden" name="wOP8Name" value="__wOP8Name__" >
		　￥__wOPPrice8__<input type="hidden" name="wOPPrice8" value="__wOPPrice8__" >
	</td></tr>
<tr><td>09．__wOP9Name__ <input type="hidden" name="wOP9Name" value="__wOP9Name__" >
		　￥__wOPPrice9__<input type="hidden" name="wOPPrice9" value="__wOPPrice9__" >
	</td></tr>
<tr><td>10．__wOP10Name__<input type="hidden" name="wOP10Name" value="__wOP10Name__" >
		　￥__wOPPrice10__ <input type="hidden" name="wOPPrice10" value="__wOPPrice10__" >

<input type="hidden" name="wOPCategory1" value="__wOPCategory1__" >
<input type="hidden" name="wOPCategory2" value="__wOPCategory2__" >
<input type="hidden" name="wOPCategory3" value="__wOPCategory3__" >
<input type="hidden" name="wOPCategory4" value="__wOPCategory4__" >
<input type="hidden" name="wOPCategory5" value="__wOPCategory5__" >
<input type="hidden" name="wOPCategory6" value="__wOPCategory6__" >
<input type="hidden" name="wOPCategory7" value="__wOPCategory7__" >
<input type="hidden" name="wOPCategory8" value="__wOPCategory8__" >
<input type="hidden" name="wOPCategory9" value="__wOPCategory9__" >
<input type="hidden" name="wOPCategory10" value="__wOPCategory10__" >
	</td></tr>
-->
					<!--
<tr><th>オプション支払方法</th>
	<td>
		__wShiharai0__ __wShiharai1__ __wShiharai2__ __wShiharai3__ __wShiharai4__
		<input type="hidden" name="wShiharai" value="__wShiharai__">
	</td></tr>
-->
					<tr>
						<th class="yb">1日の基本班体制数<br>(半角数字のみ)<font color="red">※</font>
						</th>
						<td><input type="text" id="wHansu" name="wHansu" value="__wHansu__" onchange="CheckNumber()"> 班<font color="red"><b><span id="HansuError" style="background-color:yellow"></span></b></font>
						</td>
					</tr>


					<tr>
						<th class="yb">
							1戸あたりの標準作業時間<font color="red">※</font>
						</th>
						<td><input type="number" id="wConstTime" name="wConstTime" value="__wConstTime__" style="width:50px;">分
							<font color="red"><b><span id="ConstTimeError" style="background-color:yellow"></span></b></font>
						</td>
					</tr>
					<!--
					<tr>
						<th>住戸工事期間中の休工日</th>
						<td><input type="hidden" name="wKyukoDate" value="__wKyukoDate__">__wKyukoDate__</td>
					</tr>-->
					<tr>
						<th class="yb">工事期間中の集玄開錠方法</th>
						<td>
							<input type="checkbox" name="wKaijyo[]" value="鍵" __Kaijyo1Checked__>鍵　
							<input type="checkbox" name="wKaijyo[]" value="仮暗証番号" __Kaijyo2Checked__>仮暗証番号　
							<input type="checkbox" name="wKaijyo[]" value="暗証番号" __Kaijyo3Checked__>暗証番号　
							<input type="checkbox" name="wKaijyo[]" value="工事期間中は終日開放" __Kaijyo4Checked__>工事期間中は終日開放
						</td>
					</tr>
				</table>




				<table class="table table-bordered table-sm">
					<colgroup>
						<col style='width:200px;'>
						<col style='width:200px;'>
						<col style='width:400px;'>
					</colgroup>
					7．写真撮影方法 工事写真管理アプリ利用時のみご記入ください。
					<tr>
						<th class="yb">写真台帳提供日</th>
						<td><input type="text" name="wPhotoTekyoDate" value="__wPhotoTekyoDate__" class="datepicker" style="width:120px;"></td>
						<td class="yb">
							<font color="gray">写真台帳の提供日</font>
						</td>
					</tr>

					<tr>
						<th class="yb">台帳選択</th>
						<td><select name="wPhotoPattern">
								<option value="">-</option>
								<option value="a-1" __PhotoPattern1Selected__>a-1　施工中なし　</option>
								<!--<option value="a-2" __PhotoPattern2Selected__>a-2</option>-->
								<option value="b-1" __PhotoPattern3Selected__>b-1　施工中あり　</option>
								<!--<option value="b-2" __PhotoPattern4Selected__>b-2</option>-->
							</select>
						</td>
						<td class="yb">
							<font color="gray">写真台帳【選択】シートより確認<br>※残工事部屋がある場合その部屋の写真スペースはあけておきます。</font>
						</td>
					</tr>

					<tr>
						<th class="yb">専有部</th>
						<th class="yb">機器名　</th>
						<th class="yb">撮影シーン</th>
					</tr>
					<tr>
						<th class="yb">項目1</th>
						<td><select name="wPhotoSenyu1">
								<option value="1" __PhotoSenyu11Selected__>居室親機</option>
								<option value="2" __PhotoSenyu12Selected__>住宅情報盤</option>
								<option value="3" __PhotoSenyu13Selected__>玄関子機</option>
								<option value="4" __PhotoSenyu14Selected__>増設親機</option>

								<option value="0">-</option>
							</select>
						</td>
						<td>
							<select name="wPSScene1">
								<option value="1" __PSScene11Selected__>施工前/施工後</option>
								<option value="2" __PSScene12Selected__>施工前/施工中/施工後</option>
								<option value="3" __PSScene13Selected__>施工後のみ</option>
								<option value="0">-</option>


							</select>
						</td>
					</tr>
					<tr>
						<th class="yb">項目2</th>
						<td><select name="wPhotoSenyu2">
								<option value="1" __PhotoSenyu21Selected__>居室親機</option>
								<option value="2" __PhotoSenyu22Selected__>住宅情報盤</option>
								<option value="3" __PhotoSenyu23Selected__>玄関子機</option>
								<option value="4" __PhotoSenyu24Selected__>増設親機</option>

								<option value="0">-</option>
							</select>
						</td>
						<td><select name="wPSScene2">
								<option value="1" __PSScene21Selected__>施工前/施工後</option>
								<option value="2" __PSScene22Selected__>施工前/施工中/施工後</option>
								<option value="3" __PSScene23Selected__>施工後のみ</option>
								<option value="0">-</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="yb">項目3</th>
						<td><select name="wPhotoSenyu3">
								<option value="0">-</option>
								<option value="1" __PhotoSenyu31Selected__>居室親機</option>
								<option value="2" __PhotoSenyu32Selected__>住宅情報盤</option>
								<option value="3" __PhotoSenyu33Selected__>玄関子機</option>
								<option value="4" __PhotoSenyu34Selected__>増設親機</option>

							</select>
						</td>
						<td><select name="wPSScene3">
								<option value="0">-</option>
								<option value="1" __PSScene31Selected__>施工前/施工後</option>
								<option value="2" __PSScene32Selected__>施工前/施工中/施工後</option>
								<option value="3" __PSScene33Selected__>施工後のみ</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="yb">項目4</th>
						<td><select name="wPhotoSenyu4">
								<option value="0">-</option>
								<option value="1" __PhotoSenyu41Selected__>居室親機</option>
								<option value="2" __PhotoSenyu42Selected__>住宅情報盤</option>
								<option value="3" __PhotoSenyu43Selected__>玄関子機</option>
								<option value="4" __PhotoSenyu44Selected__>増設親機</option>

							</select>
						</td>
						<td><select name="wPSScene4">
								<option value="0">-</option>
								<option value="1" __PSScene41Selected__>施工前/施工後</option>
								<option value="2" __PSScene42Selected__>施工前/施工中/施工後</option>
								<option value="3" __PSScene43Selected__>施工後のみ</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="yb">項目5</th>
						<td><input type="text" name="wPhotoSenyu5" value="__wPhotoSenyu5__"></td>
						<td><select name="wPSScene5">
								<option value="0">-</option>
								<option value="1" __PSScene51Selected__>施工前/施工後</option>
								<option value="2" __PSScene52Selected__>施工前/施工中/施工後</option>
								<option value="3" __PSScene53Selected__>施工後のみ</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="yb">項目6</th>
						<td><input type="text" name="wPhotoSenyu6" value="__wPhotoSenyu6__"></td>
						<td><select name="wPSScene6">
								<option value="0">-</option>
								<option value="1" __PSScene61Selected__>施工前/施工後</option>
								<option value="2" __PSScene62Selected__>施工前/施工中/施工後</option>
								<option value="3" __PSScene63Selected__>施工後のみ</option>
							</select>
						</td>
					</tr>


					<tr>
						<th class="yb">共用部</th>
						<th class="yb">機器名</th>
						<th class="yb">撮影シーン</th>
					</tr>
					<tr>
						<th class="yb">項目1</th>
						<td><select name="wPhotoKyoyo1">
								<option value="1" __PhotoKyoyo11Selected__>制御装置</option>
								<option value="2" __PhotoKyoyo12Selected__>管理室親機</option>
								<option value="3" __PhotoKyoyo13Selected__>集合玄関機</option>
								<option value="4" __PhotoKyoyo14Selected__>映像増幅器</option>
								<option value="5" __PhotoKyoyo15Selected__>未成分納品状況</option>
								<option value="6" __PhotoKyoyo16Selected__>産業廃棄物搬出</option>

								<option value="0" __PhotoKyoyo47Selected__>-</option>
							</select>
						</td>
						<td><select name="wPKScene1">
								<option value="1" __PKScene11Selected__>施工前/施工後</option>
								<option value="2" __PKScene12Selected__>施工前/施工中/施工後</option>
								<option value="3" __PKScene13Selected__>施工後のみ</option>
								<option value="0" __PKScene94Selected__>-</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="yb">項目2</th>
						<td><select name="wPhotoKyoyo2">
								<option value="1" __PhotoKyoyo21Selected__>制御装置</option>
								<option value="2" __PhotoKyoyo22Selected__>管理室親機</option>
								<option value="3" __PhotoKyoyo23Selected__>集合玄関機</option>
								<option value="4" __PhotoKyoyo24Selected__>映像増幅器</option>
								<option value="5" __PhotoKyoyo25Selected__>未成分納品状況</option>
								<option value="6" __PhotoKyoyo26Selected__>産業廃棄物搬出</option>

								<option value="0" __PhotoKyoyo48Selected__>-</option>
							</select>
						</td>
						<td><select name="wPKScene2">
								<option value="1" __PKScene21Selected__>施工前/施工後</option>
								<option value="2" __PKScene22Selected__>施工前/施工中/施工後</option>
								<option value="3" __PKScene23Selected__>施工後のみ</option>
								<option value="0" __PKScene95Selected__>-</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="yb">項目3</th>
						<td><select name="wPhotoKyoyo3">
								<option value="1" __PhotoKyoyo31Selected__>制御装置</option>
								<option value="2" __PhotoKyoyo32Selected__>管理室親機</option>
								<option value="3" __PhotoKyoyo33Selected__>集合玄関機</option>
								<option value="4" __PhotoKyoyo34Selected__>映像増幅器</option>
								<option value="5" __PhotoKyoyo35Selected__>未成分納品状況</option>
								<option value="6" __PhotoKyoyo36Selected__>産業廃棄物搬出</option>

								<option value="0" __PhotoKyoyo49Selected__>-</option>
							</select>
						</td>
						<td><select name="wPKScene3">
								<option value="1" __PKScene31Selected__>施工前/施工後</option>
								<option value="2" __PKScene32Selected__>施工前/施工中/施工後</option>
								<option value="3" __PKScene33Selected__>施工後のみ</option>
								<option value="0" __PKScene96Selected__>-</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="yb">項目4</th>
						<td><select name="wPhotoKyoyo4">
								<option value="0">-</option>
								<option value="1" __PhotoKyoyo41Selected__>制御装置</option>
								<option value="2" __PhotoKyoyo42Selected__>管理室親機</option>
								<option value="3" __PhotoKyoyo43Selected__>集合玄関機</option>
								<option value="4" __PhotoKyoyo44Selected__>映像増幅器</option>
								<option value="5" __PhotoKyoyo45Selected__>未成分納品状況</option>
								<option value="6" __PhotoKyoyo46Selected__>産業廃棄物搬出</option>

							</select>
						</td>
						<td><select name="wPKScene4">
								<option value="0">-</option>
								<option value="1" __PKScene41Selected__>施工前/施工後</option>
								<option value="2" __PKScene42Selected__>施工前/施工中/施工後</option>
								<option value="3" __PKScene43Selected__>施工後のみ</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="yb">項目5</th>
						<td><input type="text" name="wPhotoKyoyo5" value="__wPhotoKyoyo5__"></td>
						<td><select name="wPKScene5">
								<option value="0">-</option>
								<option value="1" __PKScene51Selected__>施工前/施工後</option>
								<option value="2" __PKScene52Selected__>施工前/施工中/施工後</option>
								<option value="3" __PKScene53Selected__>施工後のみ</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="yb">項目6</th>
						<td><input type="text" name="wPhotoKyoyo6" value="__wPhotoKyoyo6__"></td>
						<td><select name="wPKScene6">
								<option value="0">-</option>
								<option value="1" __PKScene61Selected__>施工前/施工後</option>
								<option value="2" __PKScene62Selected__>施工前/施工中/施工後</option>
								<option value="3" __PKScene63Selected__>施工後のみ</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="yb">項目7</th>
						<td><input type="text" name="wPhotoKyoyo7" value="__wPhotoKyoyo7__"></td>
						<td><select name="wPKScene7">
								<option value="0">-</option>
								<option value="1" __PKScene71Selected__>施工前/施工後</option>
								<option value="2" __PKScene72Selected__>施工前/施工中/施工後</option>
								<option value="3" __PKScene73Selected__>施工後のみ</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="yb">項目8</th>
						<td><input type="text" name="wPhotoKyoyo8" value="__wPhotoKyoyo8__"></td>
						<td><select name="wPKScene8">
								<option value="0">-</option>
								<option value="1" __PKScene81Selected__>施工前/施工後</option>
								<option value="2" __PKScene82Selected__>施工前/施工中/施工後</option>
								<option value="3" __PKScene83Selected__>施工後のみ</option>
							</select>
						</td>
					</tr>
					<tr>
						<th class="yb">項目9</th>
						<td><input type="text" name="wPhotoKyoyo9" value="__wPhotoKyoyo9__"></td>
						<td><select name="wPKScene9">
								<option value="0">-</option>
								<option value="1" __PKScene91Selected__>施工前/施工後</option>
								<option value="2" __PKScene92Selected__>施工前/施工中/施工後</option>
								<option value="3" __PKScene93Selected__>施工後のみ</option>
							</select>
						</td>
					</tr>
				</table>


				<!--8．その他（注意事項等）-->
				<table class="table table-bordered table-sm">
					<tr>
						<th class="yb">備考</th>
					</tr>
					<tr>
						<td><textarea name="wNotes" cols="80" rows="7" style="width:700px">__wNotes__</textarea></td>
					</tr>

				</table>


				<!--9．人工表セット有無-->
				<!--<table class="table table-bordered table-sm">
					<tr>
						<th class="yb">人工表</th>
					</tr>
					<tr>
						<td><input type="checkbox" name="wIR01[]" value="1" __IR01checked__ ><font color="black"> 人工表を利用する（無料）</font></td>
					</tr>
				</table>
				-->


				8.関連資料（住人様ご案内資料、仮日程表など）
				<iframe src="s_489_file.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__" style="width:100%; height:200px;"></iframe>





				<br><br>
				<input type="hidden" name="rKey" value="__rKey__">
				<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
				<input type="hidden" name="wIraiRenkeiStatus" value="__wIraiRenkeiStatus__">
				<input type="hidden" name="editIraiRenkeiCD" value="__editIraiRenkeiCD__">

				<button type="button" onclick="checkInput()" class="btn btn-primary"> 内容確認画面へ </button>&nbsp
				__IfIchijiHozon__
				<button type="button" onclick="noCheckInput()" class="btn btn-success"> 一時保存 </button>
				__IfIchijiHozon__

				<br><br>
				※一時保存の場合、予約センターへ通知されません。<br>
				※内容確認後、予約センターへ依頼を行うと予約センターへ通知します。（依頼完了となります。）<br>
				※依頼後は、一時保存はできなくなります。<br>
			</form>
			<br>

			<hr>
			<input type="button" value="メニューへもどる" onclick="javascript:move('./s_menu.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__' )" class="btn btn-info"><br>


		</div>



	</div>
	<!--content-all-->



	__SFooter__
	__SCopyright__

</body>
</html>

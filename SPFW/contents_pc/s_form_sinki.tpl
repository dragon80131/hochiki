<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>__TITLENAME__</title>

	<!-- BootstrapのCSS読み込み -->
	<link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<!-- jQuery読み込み -->
	<script src="./include/js/jquery-3.2.1.min.js"></script>

	<!-- BootstrapのJS読み込み -->
	<script src="./include/bootstrap/js/bootstrap.min.js"></script>

	<link rel="stylesheet" type="text/css" href="./css/rnsien.css">
	<script type="text/javascript" src="tools.js"></script>
	<script type="text/javascript" src="js/ConnectedSelect.js"></script>

	<!--datepicker-->
	<link href="css/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
	<link href="css/jquery-ui-1.12.1/jquery-ui.structure.min.css" rel="stylesheet">
	<link href="css/jquery-ui-1.12.1/jquery-ui.theme.min.css" rel="stylesheet">
	<script src="js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script src="js/jquery-ui/datepicker-ja.js"></script>

	<!--select2-->
	<link href="css/select2.css" type="text/css" rel="stylesheet">
	<script src="js/select2.min.js" type="text/javascript"></script>

	<script type="text/javascript">
		$(function () {
			$("#wChosaDate").datepicker({});
		});
	</script>
	<style>
		/* 点滅 */
		.blinking {
			-webkit-animation: blink 1.5s ease-in-out infinite alternate;
			-moz-animation: blink 1.5s ease-in-out infinite alternate;
			animation: blink 1.5s ease-in-out infinite alternate;
		}

		@-webkit-keyframes blink {
			0% {
				opacity: 0;
			}

			100% {
				opacity: 1;
			}
		}

		@-moz-keyframes blink {
			0% {
				opacity: 0;
			}

			100% {
				opacity: 1;
			}
		}

		@keyframes blink {
			0% {
				opacity: 0;
			}

			100% {
				opacity: 1;
			}
		}

		p.mintitle {
			font-size: 1.5em;
			position: relative;
			padding: 0.25em 0;
		}

		p.mintitle:after {
			content: "";
			display: block;
			height: 4px;
			background: -webkit-linear-gradient(to right, rgb(230, 90, 90), transparent);
			background: linear-gradient(to right, rgb(230, 90, 90), transparent);
		}

		p.list {
			margin-bottom: 5px;
		}
	</style>
	<!--datepicker-->
	<link href="css/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
	<link href="css/jquery-ui-1.12.1/jquery-ui.structure.min.css" rel="stylesheet">
	<link href="css/jquery-ui-1.12.1/jquery-ui.theme.min.css" rel="stylesheet">
	<script src="js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script src="js/jquery-ui/datepicker-ja.js"></script>

</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__" 　onload="initOnload();">

	<div class="content-all">
		<!--content-all-->
		<div class="left-yose">
			<!--<a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜メニュー</a>-->
		</div>

		<div class="top-menu left-yose">
			<h6>新規物件登録</h6>

			<form action="s_finish_sinki.php" method="POST" name="mainform">

				<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
				<input type="hidden" name="rKey" value="__rKey__">
				<input type="hidden" name="work" value="1">

				<font color="red">※は入力必要項目です。</font><br>
				<span id="ErrorString" style="color:red"></span>
				__IfError____ErrorLoop__
				<font color="red">__ErrorStrings__<br></font>
				__ErrorLoop____IfError__
				<br>

				<p class="mintitle">物件基本情報</p>
				<font size="2" color="gray">　
					<table class="table table-bordered table-sm" style="width:800px">
						<colgroup>
							<col style='width:200px;'>
						</colgroup>
						<tr>
							<th class="yb">物件名<font color="red">　※</font>
							</th>
							<td><input type="text" name="wBukkenName" value="__wBukkenName__" style="width:400px;" placeholder="例：○○マンション"></td>
						</tr>

						<tr>
							<th class="yb">物件名(ふりがな)</th>
							<td><input type="text" name="wBukkenName_Hurigana" value="__wBukkenName_Hurigana__" style="width:400px;"></td>
						</tr>

						<tr>
							<th class="yb">住所<font color="red">　※</font>
							</th>
							<td><input type="text" name="wAddress" id="Addressid" value="__wAddress__" style="width:400px;"></td>
						</tr>
						<tr>
							<th class="yb">住戸数<font color="red">　※</font>
							</th>
							<td><input type="text" name="wKosu" value="__wKosu__" style="width:80px; ime-mode:disabled;"> 戸</td>
						</tr>
						<!-- <tr>
							<th class="yb">階高<font color="red">　※</font>
							</th>
							<td><input type="text" name="wKaidaka" value="__wKaidaka__" style="width:80px; ime-mode:disabled;"> 階</td>
						</tr> -->
						<tr>
							<th class="yb">施工依頼会社<font color="red">　※</font>
							</th>
							<td>
								<!-- <input type="search" name="wKojiShozokuName" value="__GyosyaName__" style="width:400px;" autocomplete="on" list="keywords"> -->
								<input type="search" name="wKojiShozokuName" value="__GyosyaName__" style="width:400px;" autocomplete="off" list="keywords">
								<datalist id="keywords">
									__GyosyaLoop__
									<option value="__GyosyaName2__">__GyosyaCD2__</option>
									__GyosyaLoop__
								</datalist>
								<input type="hidden" name="q" value="__GyosyaCD__">
							</td>
						</tr>
						<!-- <tr>
							<th class="yb">会社TEL<font color="red">　※</font>
							</th>
							<td><input type="text" name="wKojiShozokuTEL" value="" style="width:150px;"></td>
						</tr>
						<tr>
							<th class="yb">担当名<font color="red">　※</font>
							</th>
							<td><input type="text" name="wKojiTantoName" value="__LastName__" style="width:300px;"></td>
						</tr>
						<tr>
							<th class="yb">管理会社名</th>
							<td><input type="text" name="wKanriGaisya" value="__wKanriGaisya__" style="width:300px;"></td>
						</tr>
						<tr>
							<th class="yb">オーナー名(賃貸物件の場合)</th>
							<td><input type="text" name="wOwner_name" value="__wOwner_name__" style="width:300px;"></td>
						</tr> -->
						<tr>
							<th class="yb">物件メモ</th>
							<td><textarea name="wBukkenMemo" rows="3" cols="50">__wBukkenMemo__</textarea></td>
						</tr>
					</table>

					<br>
					<!--<input type="button" value="一時保存" onClick="required_check(1)" class="btn btn-warning">-->　　
					<input type="hidden" name="touroku_btn" id="touroku_btn" value="">
					<input type="button" value="登録する" onClick="required_check(2)" class="btn btn-primary">
					<!--登録ボタン押下後、エラーなしの場合に設定する-->
			</form>

			<br>
		</div>
	</div>
	<!--content-all-->

	<hr size="__HRSize__" color="__HRColor__">
	<a href="s_search.php__QUERY__">トップ</a>
	<hr size="__HRSize__" color="__HRColor__">
	__SFooter__
	__SCopyright__


	<script>
		// 必須項目チェック（1:一時保存 2:登録）
		function required_check(val) {

			var html = [];
			//if (!chk_input("wKenmeiNo")) html.push("管理Noが入力されていません。");
			if (!chk_input("wBukkenName")) html.push("物件名が入力されていません。");

			if (html.length > 0) {
				document.getElementById('ErrorString').innerHTML = "";
				for (let i = 0; i < html.length; i++) {
					document.getElementById('ErrorString').innerHTML += html[i] + "<br>";
				}
				window.scrollTo(0, 0);
			} else {
				// エラーなし かつ 一時保存ボタン
				if (html.length == 0 && val == 1) {
					//document.mainform.action = "s_kihon_finish.php";
					document.mainform.action = "s_finish_sinki.php";
					document.mainform.submit(true);

				} else if (val == 2) { // 登録ボタン
					form_check(val);
				}
			}
		}

		// 登録時必須項目チェック（2:登録）
		function form_check(val) {
			var html = [];
			if (html.length > 0) {
				document.getElementById('ErrorString').innerHTML = "";
				for (let i = 0; i < html.length; i++) {
					document.getElementById('ErrorString').innerHTML += html[i] + "<br>";
				}
				window.scrollTo(0, 0);
			} else {
				document.getElementById("touroku_btn").value = 1; // hiddenに値設定
				document.mainform.action = "s_finish_sinki.php";
				document.mainform.submit(true);
			}
		}

		// 必須項目の入力確認(text,selectbox)
		function chk_input(name) {
			if (document.getElementsByName(name)[0].value != "") {
				return true;
			}
			return false;
		}
	</script>
	</div>
	<!--content-all-->

</body>

</html>
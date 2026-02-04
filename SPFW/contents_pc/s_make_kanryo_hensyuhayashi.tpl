<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>リニューアル支援</title>

	<!-- BootstrapのCSS読み込み -->
	<link href="../include/bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<!-- jQuery読み込み -->
	<script src="../include/js/jquery-3.2.1.min.js"></script>

	<!-- BootstrapのJS読み込み -->
	<script src="../include/bootstrap/js/bootstrap.min.js"></script>

	<link rel="stylesheet" type="text/css" href="../css/rnsien.css">
	<script type="text/javascript" src="../tools.js"></script>

	<link href="../css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../js/tools_ajax.js"></script>
	<script type="text/javascript" src="../js/ConnectedSelect.js"></script>
	<script src="../js/jquery-1.4.2.js" type="text/javascript"></script>
	<script src="../js/jquery.ui.core.js" type="text/javascript"></script>
	<script src="../js/jquery.ui.datepicker.js" type="text/javascript"></script>
	<script src="../js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>

	<script type="text/javascript">
	</script>

	<script>
		function modorumove(val) {
			document.form1.action = val;
			document.form1.submit(true);
		}
		function moveandaki(value) {//空き押したとき
			document.mainform.action = "s_make_kanryo_hensyuhayashi.php?editBukkenCD=__editBukkenCD__&Aki=" + value + "&work=2";
			document.mainform.submit(true);
		}

		function moveandroom(num) {//部屋番号押したとき
			document.mainform.action = "s_make_kanryo_hensyuhayashi.php?editBukkenCD=__editBukkenCD__&Aki=" + num + "&work=3";
			document.mainform.submit(true);
		}

		//submit前の入力チェック
		function checkInput() {

			var error_flg = false;
			/*
			var answer = document.getElementById("wAnswer").value;
			var picappseko = document.getElementById("wPicAppSeko").value;
			var picappkoji = document.getElementById("wPicAppKoji").value;
			var postingflg = document.getElementById("wPostingFlg").value;
			var smartflg = document.getElementById("wSmartFlg").value;
			var Basho = "";

			if(answer==""){
				error_flg = true;
				document.getElementById("AnswerError").innerHTML = "※受付方法を選択してください。";
				Basho = 500;
			}else{
				document.getElementById("AnswerError").innerHTML = "";
			}
			if (picappseko.length > 15) {
				error_flg = true;
				document.getElementById("PicAppSekoError").innerHTML = "15文字以内で設定してください。";
				if(Basho == "") Basho = 900;
			} else {
				document.getElementById("PicAppSekoError").innerHTML = "";
			}
			if (picappkoji.length > 15) {
				error_flg = true;
				document.getElementById("PicAppKojiError").innerHTML = "15文字以内で設定してください。";
				if(Basho == "") Basho = 900;
			} else {
				document.getElementById("PicAppKojiError").innerHTML = "";
			}
			if(postingflg==""){
				error_flg = true;
				document.getElementById("PostingError").innerHTML = "※利用の有無を選択してください。";
				if(Basho == "") Basho = 1100;
			}else{
				document.getElementById("PostingError").innerHTML = "";
			}
			if(smartflg==""){
				error_flg = true;
				document.getElementById("SmartError").innerHTML = "※利用の有無を選択してください。";
				if(Basho == "") Basho = 1500;
			}else{
				document.getElementById("SmartError").innerHTML = "";
			}
			*/
			if (error_flg) {
				window.scrollTo(0, Basho);
				return false;
			} else {
				document.mainform.method = "POST";
				document.mainform.target = "_self";
				document.mainform.action = "s_make_kotei_EXCEL.php?hensyu=1";
				document.mainform.submit();
			}

			alert('ここになにかしかける');
		}

	</script>


</head>

<body>
	__SHeader__

	<div class="content-all">
		<!--content-all-->
		<form action="s_make_kanryohayashi.php" method="POST" name="form1">
			<!--form1-->
			__HiddenValues__
			<input type="hidden" name="backw" value="1">
			<input type="hidden" name="rKey" value="__rKey__">
			<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
			<div class="left-yose">
				<!--<a href="#" onclick="modorumove('s_make_kanryo.php');">＜戻る</a>-->
			</div>
		</form>
		<!--/form1-->

		<div class="top-menu left-yose">

			<h6>詳細工程表編集</h6>

			<form action="s_make_matrixhayashi.php" method="POST" name="mainform">
				<!--mainform-->
				<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
				<input type="hidden" name="rKey" value="__rKey__">


				<br>
				■不足している部屋<br>
				__IfNotShortage__
				不足している部屋はありません。<br>
				__IfNotShortage__
				__IfShortage__
				<font color=red size=5><b>__ShortageRoom__</b></font>
				__IfShortage__
				<br>
				<br>



				■詳細工程表<br>
				<br>削除する<font color="blue">空き</font>をクリックしてください。部屋をクリックすると空き枠にかわります。

				<br>__IfError__<font color=red>※すべての部屋を組み込むことができませんでした。</font>__IfError__<br>
				__Koteihyou__

				<br>
				<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
				<input type="hidden" name="rKey" value="__rKey__">
				<input type="hidden" name="wWakuPattern" value="__wWakuPattern__">
				<input type="hidden" name="wWakuAM" value="__wWakuAM__">
				<input type="hidden" name="wWakuAM2" value="__wWakuAM2__">
				<input type="hidden" name="wWakuAM3" value="__wWakuAM3__">
				<input type="hidden" name="wWakuAM4" value="__wWakuAM4__">
				<input type="hidden" name="wWakuPM1" value="__wWakuPM1__">
				<input type="hidden" name="wWakuPM2" value="__wWakuPM2__">
				<input type="hidden" name="wWakuPM3" value="__wWakuPM3__">
				<input type="hidden" name="wWakuPM4" value="__wWakuPM4__">
				<input type="hidden" name="wWakuPM5" value="__wWakuPM5__">
				<input type="hidden" name="wWakuPM6" value="__wWakuPM6__">
				<input type="hidden" name="wWakuAMcol" value="__wWakuAMcol__">
				<input type="hidden" name="wWakuAM2col" value="__wWakuAM2col__">
				<input type="hidden" name="wWakuAM3col" value="__wWakuAM3col__">
				<input type="hidden" name="wWakuAM4col" value="__wWakuAM4col__">
				<input type="hidden" name="wWakuPM1col" value="__wWakuPM1col__">
				<input type="hidden" name="wWakuPM2col" value="__wWakuPM2col__">
				<input type="hidden" name="wWakuPM3col" value="__wWakuPM3col__">
				<input type="hidden" name="wWakuPM4col" value="__wWakuPM4col__">
				<input type="hidden" name="wWakuPM5col" value="__wWakuPM5col__">
				<input type="hidden" name="wWakuPM6col" value="__wWakuPM6col__">
				<input type="hidden" name="wHansu" value="__wHansu__">
				<input type="hidden" name="RowsLoop" value="__RowsLoop__">
				<input type="hidden" name="holiday" value="__holiday__">
				<input type="hidden" name="wShukujitucolor" value="__wShukujitucolor__">
				<input type="hidden" name="wHoliday1" value="__wHoliday1__">
				<input type="hidden" name="wKyukobi" value="__wKyukobi__">
				<input type="hidden" name="wKaiRoom3" value="__wKaiRoom3__">
				<input type="submit" onclick="javascript:move('s_make_kotei_EXCEL.php?hensyu=1' )" class="btn btn-primary" value="詳細工程表作成" __SakuseiDisabled__>


				<!--<button type="button" onclick="checkInput()" class="btn btn-primary" >  内容確認  </button>-->

				<!--<input type="submit" onclick="javascript:move('../s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__' )" class="btn btn-primary" value="戻る" 　>-->



				<br><br>
				<font color="red" size="4"><b>
						※お願い※<br>
						作成済のエクセルの詳細工程表で、部屋の移動を行わないでください。<br>
						文字サイズの変更や、印刷範囲の修正は可能です。<br>
					</b></font>
				<!--→修正する場合は<a href="#" onclick="javascript:move('s_make_kojidate.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__' )">コチラ</a>-->
				<button type="button" onclick="javascript:move('s_make_kojidate.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__' )" class="btn btn-success">部屋ごとの修正</button>
				<br>
				<br>


			</form>
			<!--mainform-->

			　
			__IfNespe__
			<hr>
			<ネスぺ社員のみ表示><br>
				<input type="submit" onclick="javascript:move('s_make_yotei_EXCEL.php?editBukkenCD=__editBukkenCD__' )" value="予定案内">
				__IfNespe__




				<hr>
				<input type="button" value="メニューへもどる" onclick="javascript:move('../s_menu.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__' )" class="btn btn-info"><br>

		</div>
		<!--content-all-->



		__SFooter__
		__SCopyright__

</body>
</html>

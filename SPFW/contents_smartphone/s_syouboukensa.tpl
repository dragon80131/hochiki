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
<script src="../js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="../js/jquery.ui.core.js" type="text/javascript"></script>
<script src="../js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="../js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
<script src="../js/jquery.numberPicker.js" type="text/javascript"></script>
<script type="text/javascript">
	$(function() { 
		$("#JissiDate1").datepicker({});
	});
	function CheckandMove(url){

		var frm = document.mainform;
		var error_flg="";
		var SyoboType = "";
		var KaiRoom7 = "";
		if(frm.JissiDate.value==""||frm.JissiStart.value==""||frm.JissiFinish.value==""){
			error_flg=1;
			document.getElementById("JissiEmpty").innerHTML = "<br><font color='red'>※作業日時を入力してください</font>";
		}else{
			document.getElementById("JissiEmpty").innerHTML = "";
		}
		for(i=0;i<frm.format.length;i++){
			if(frm.format[i].checked){
				SyoboType = frm.format[i].value;
			}
		}
		for(i=0;i<__KaiRoomSuu__;i++){
			if(document.getElementById("KaiRoomID"+i).checked){
				KaiRoom7 = document.getElementById("KaiRoomID"+i).value;
			}
		}

		if(SyoboType==""){
			error_flg=1;
			document.getElementById("SyoboTypeEmpty").innerHTML = "<font color='red'>※選択してください</font><br>";
		}else if(SyoboType=="2"||SyoboType=="5"){
			if(KaiRoom7 == ""){
				error_flg = 1;
				document.getElementById("KaiRoom6").innerHTML = "<font color='red'>※資料が必要な部屋番号にチェックを入れてください。</font><br>";
				document.getElementById("SyoboTypeEmpty").innerHTML = "";
			}else{
				document.getElementById("SyoboTypeEmpty").innerHTML = "";
				document.getElementById("KaiRoom6").innerHTML = "資料が必要な部屋番号にチェックを入れてください。<br>";

			}
		}else{
				document.getElementById("SyoboTypeEmpty").innerHTML = "";
		}
		if(error_flg==""){
			document.mainform.action = url;
			document.mainform.submit(true);
		}
	}

	function RoomCheck(){

		var frm = document.mainform;

		for(i=0;i<frm.format.length;i++){
			if(frm.format[i].checked){
				SyoboType = frm.format[i].value;
			}
		}

		if(SyoboType == 2 || SyoboType == 5){
			document.getElementById("KaiRoom5").style.display = "table";
			document.getElementById("KaiRoom6").innerHTML = "資料が必要な部屋番号にチェックを入れてください。";
		}else{
			document.getElementById("KaiRoom5").style.display = "none";
			document.getElementById("KaiRoom6").innerHTML = "";

		}
	}

</script>
</head>

<body>
__SHeader__

<div class="content-all"><!--content-all-->

<div class="left-yose">
<a href="../s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜戻る</a>
</div>


<div class="top-menu left-yose">
<h5>__wBukkenName__</h5>
<h6>消防検査案内</h6>


<form action="#" method="POST" name="mainform" >
<input type="hidden" name="editBukkenCD" >


<table class="table table-bordered table-sm">
<tr><th>作業日時</th>
	<td>
		<input type="text" name="JissiDate" id="JissiDate1"  value="__JissiDate__" style="width:100px;">　
		<input type="number" name="JissiStart" value="__JissiStart__" min="0" max="24" style="width:50px;">時～
		<input type="number" name="JissiFinish" value="__JissiFinish__" min="0" max="24" style="width:50px;">時
		<span id="JissiEmpty"></span>
	</td>
</tr>
<tr><th>消防特例・試験内容</th>
	<td>
<!--		__If170__<input type="radio" name="format" value="1" onchange="RoomCheck();">170号・外部試験<br>
		<input type="radio" name="format" value="2" onchange="RoomCheck();">170号・火災感知器発報試験<br>
		<input type="radio" name="format" value="3" onchange="RoomCheck();">170号・火災感知器発報試験・外部試験<br>__If170__
		__If220Juko__<input type="radio" name="format" value="4" onchange="RoomCheck();" __Juko220Checked__>220号住戸用<br>__If220Juko__
		__If220Kyoju__<input type="radio" name="format" value="5" onchange="RoomCheck();" __Kyoju220Checked__>220号共住用<br>__If220Kyoju__--><!--部屋番号必要-->
		<input type="radio" name="format" value="1" onchange="RoomCheck();" __Disabled170__>170号・外部試験<br>
		<input type="radio" name="format" value="2" onchange="RoomCheck();" __Disabled170__>170号・火災感知器発報試験<br>
		<input type="radio" name="format" value="3" onchange="RoomCheck();" __Disabled170__>170号・火災感知器発報試験・外部試験<br>__If170__
		<input type="radio" name="format" value="4" onchange="RoomCheck();" __Juko220Checked__ __Juko220Disabled__>220号住戸用<br>__If220Juko__
		<input type="radio" name="format" value="5" onchange="RoomCheck();" __Kyoju220Checked__ __Kyoju220Disabled__>220号共同住宅用
		<span id="SyoboTypeEmpty"></span>
	</td>
</tr>
</table>
<br>
<span id="KaiRoom6">__If220Kyoju__資料が必要な部屋番号にチェックを入れてください。__If220Kyoju__</span>
__KaiRoom3__
<br>
<input type="button" value="資料作成" onclick="CheckandMove('./s_syouboukensa_Excel.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__');" class="btn btn-primary">

</form>

</div>






</div><!--content-all-->

<hr>

__SFooter__
__SCopyright__

</body>
</html>

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
<link href="./css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<script src="./js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="./js/jquery.ui.core.js" type="text/javascript"></script>
<script src="./js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="./js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
<script src="./js/jquery.numberPicker.js" type="text/javascript"></script>

<script type="text/javascript">
	$(function() { 
		$("#JissiDate1").datepicker({});
	});
	function CheckandMove(url){

		var frm = document.mainform;
		var error_flg="";
		var Nyusitu = "";
		if(frm.JissiDate.value==""||frm.JissiStart==""||frm.JissiFinish==""){
			error_flg=1;
			document.getElementById("JissiEmpty").innerHTML = "<br><font color='red'>※作業日時を入力してください</font>";
		}
		for(i=0;i<frm.format.length;i++){
			if(frm.format[i].checked){
				Nyusitu = frm.format[i].value;
			}
		}

		if(Nyusitu===""){
			error_flg=1;
			document.getElementById("NyusituEmpty").innerHTML = "<br><font color='red'>※入室の有無を選択してください</font>";
		}else if((Nyusitu==3 || Nyusitu==2)&& frm.SagyoJikan.value==""){
			error_flg=1;
			document.getElementById("SagyoEmpty").innerHTML = "<br><font color='red'>※作業時間を入力してください</font>";
			document.getElementById("NyusituEmpty").innerHTML = "";
		}else{
			document.getElementById("NyusituEmpty").innerHTML = "";
			document.getElementById("SagyoEmpty").innerHTML = "";
		}
		if(error_flg==""){
			document.mainform.action = url;
			document.mainform.submit(true);
		}
	}
</script>
</head>

<body>
__SHeader__

<div class="content-all"><!--content-all-->

<div class="left-yose">
<a href="./s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜戻る</a>
</div>


<div class="top-menu left-yose">
<h5>__wBukkenName__</h5>
<h6>事前調査案内</h6>


<form action="#" method="POST" name="mainform" >
<input type="hidden" name="editBukkenCD" >


<table class="table table-bordered table-sm">
<tr><th>作業日時</th>
	<td>
		<input type="text" name="JissiDate" id="JissiDate1" value="__JissiDate__" style="width:100px" readonly>　
		<input type="number" name="JissiStartH" value="9" min="0" max="24" style="width:50px">時　～
		<input type="number" name="JissiFinishH" value="17" min="0" max="24" style="width:50px">時
		<span id="JissiEmpty"></span>
	</td>
</tr>
<tr><th>入室</th>
	<td>
		<input type="radio" name="format" value="1">入室なし<br>
		<input type="radio" name="format" value="2">一部入室あり<br>
		<input type="radio" name="format" value="3">一部入室あり（1対1）<br>
		<input type="radio" name="format" value="4">場合により入室あり<br>
		<span id="NyusituEmpty"></span>
	</td>
<tr><th>一部屋の作業時間</th>
	<td>
		約<input type="number" name="SagyoJikan" value="10" min="0" max="999" style="width:80px">分
		<span id="SagyoEmpty"></span>
	</td>
</tr>
</table>
<br>
<input type="button" value="資料作成" onclick="CheckandMove('./doc/s_jizenchosa_Excel.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__');" class="btn btn-primary">

</form>

</div>






</div><!--content-all-->

<hr>

__SFooter__
__SCopyright__

</body>
</html>

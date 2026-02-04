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
		if(frm.JissiDate.value==""){
			error_flg=1;
			document.getElementById("JissiEmpty").innerHTML = "<br><font color='red'>※作業日を入力してください</font>";
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

<h6>給湯器外し</h6>


<form action="#" method="POST" name="mainform" >
<input type="hidden" name="editBukkenCD" >


<table class="table table-bordered table-sm">
<tr><th>作業日</th>
	<td>
		<input type="text" name="JissiDate" id="JissiDate1" value="__JissiDate__" style="width:100px">
		<span id="JissiEmpty"></span>
	</td>
</tr>
</table>
<br>
<input type="button" value="資料作成" onclick="CheckandMove('./doc/s_kyutokihazusi_Excel.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__');" class="btn btn-primary">

</form>

</div>






</div><!--content-all-->

<hr>

__SFooter__
__SCopyright__

</body>
</html>

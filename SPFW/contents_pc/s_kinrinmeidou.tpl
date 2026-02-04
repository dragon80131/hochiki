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
		$("#JissiDate_2").datepicker({});
	});
	function CheckandMove(url){

		var frm = document.mainform;
		var error_flg="";
		var SiryoType = "";

		if(frm.JissiDate.value==""||frm.JissiStart.value==""||frm.JissiFinish.value==""){
			error_flg=1;
			document.getElementById("JissiEmpty").innerHTML = "<br><font color='red'>※作業日時を入力してください</font>";
		}else{
			document.getElementById("JissiEmpty").innerHTML = "";
		}
		
		
		for(i=0;i<frm.format.length;i++){
			if(frm.format[i].checked){
				SiryoType = frm.format[i].value;
				
				if(i==2){
					//前後の場合のみ作業日２の入力もチェックする
					if(frm.JissiDate2.value==""||frm.JissiStart2.value==""||frm.JissiFinish2.value==""){
						error_flg=1;
						document.getElementById("JissiEmpty2").innerHTML = "<br><font color='red'>※作業日時を入力してください</font>";
					}else{
						document.getElementById("JissiEmpty2").innerHTML = "";
					}
				}
			}
		}

		if(SiryoType==""){
			error_flg=1;
			document.getElementById("SiryoTypeEmpty").innerHTML = "<font color='red'>※選択してください</font><br>";
		}else{
			document.getElementById("SiryoTypeEmpty").innerHTML = "";
		}
		if(error_flg==""){
			document.mainform.action = url;
			document.mainform.submit(true);
		}
	}
	
	function checkZENGO(){
        //ラジオボタンの値をチェック
        if(document.mainform.format[0].checked || document.mainform.format[1].checked){
            document.getElementById("JissiBefor").style.display="none";
            document.getElementById("JissiAfter").style.display="none";

        }else{	//前後の場合
            document.getElementById("JissiBefor").style.display="block";
            document.getElementById("JissiAfter").style.display="block";

        }
				
    }
	

/*	function PlusDate(){

		var frm = document.mainform;
		var SiryoType = "";

		for(i=0;i<frm.format.length;i++){
			if(frm.format[i].checked){
				SiryoType = frm.format[i].value;
			}
		}
		
		if(SiryoType == 3||SiryoType == 4){
			document.getElementById("TuikaDate").style.display = "table-row";
		}else{
			document.getElementById("TuikaDate").style.display = "none";
		}
	}
*/		
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
<h6>近隣鳴動案内</h6>


<form action="#" method="POST" name="mainform" >
<input type="hidden" name="editBukkenCD" >


<table class="table table-bordered table-sm">
<tr><th>資料選択</th>
	<td>
		<input type="radio" name="format" value="1" onchange="checkZENGO();">着工前<br>
		<input type="radio" name="format" value="2" onchange="checkZENGO();">着工後<br>
		<input type="radio" name="format" value="3" onchange="checkZENGO();">着工前/後<br>

		<span id="SiryoTypeEmpty"></span>
	</td>
</tr>
<tr><th rowspan="2">作業日時</th>
	<td><span id="JissiBefor" style="display:none">着工前</span>
		<input type="text" name="JissiDate" id="JissiDate1" value="__JissiDate__" style="width:100px">　
		<input type="number" name="JissiStart" value="9" min="0" max="24" style="width:50px">時～
		<input type="number" name="JissiFinish" value="10" min="0" max="24" style="width:50px">時
		<span id="JissiEmpty"></span>
	</td>
	<tr>
	<td>
		<span id="JissiAfter" style="display:none">着工後<br> 
		<input type="text" name="JissiDate2" id="JissiDate_2" value="__JissiDate2__" style="width:100px">　
		<input type="number" name="JissiStart2" value="9" min="0" max="24" style="width:50px">時～
		<input type="number" name="JissiFinish2" value="10" min="0" max="24" style="width:50px">時</span>
		<span id="JissiEmpty2"></span>
	</td>
	</tr>
</tr>
</table>
<br>

<input type="button" value="資料作成" onclick="CheckandMove('./s_kinrinmeidou_Excel.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__');" class="btn btn-primary">


<!--<a href="#" onclick="javascript:moveWithKey('./s_kinrinmeidou_Excel.php?rKey=__rKey__&format=1' , __editBukkenCD__ )" class="square_btn">【近隣鳴動案内】着工前</a>
<br><br>

<a href="#" onclick="javascript:moveWithKey('./s_kinrinmeidou_Excel.php?rKey=__rKey__&format=2' , __editBukkenCD__ )" class="square_btn">【近隣鳴動案内】着工後</a>
<br><br>

<a href="#" onclick="javascript:moveWithKey('./s_kinrinmeidou_Excel.php?rKey=__rKey__&format=3' , __editBukkenCD__ )" class="square_btn">【近隣鳴動案内】VIXUS1Pr</a>
<br><br>

<a href="#" onclick="javascript:moveWithKey('./s_kinrinmeidou_Excel.php?rKey=__rKey__&format=4' , __editBukkenCD__ )" class="square_btn">【近隣鳴動案内】らくタッチPlus</a>
<br><br>
-->

</form>

</div>


</div><!--content-all-->

<hr>

__SFooter__
__SCopyright__

</body>
</html>

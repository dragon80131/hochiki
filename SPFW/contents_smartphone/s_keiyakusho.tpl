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
<script type="text/javascript" src="./tools.js"></script>

<link href="css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="js/jquery.ui.core.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
<script src="js/jquery.numberPicker.js" type="text/javascript"></script>

<script type="text/javascript" src="js/tools_ajax.js"></script>
<script type="text/javascript" src="js/ConnectedSelect.js"></script>
<script type="text/javascript">
$(function() {
	$(".datepicker").datepicker({});
});

function MoveandCheck(page){
	var ErrorFlg = 0;
	frm = document.mainform;
	if(frm.wOrder.value == ""){
		ErrorFlg = 1;
		document.getElementById('OrderError').innerHTML="<br><font color='red'>※注文者を記入してください</font>";
	}else{
		document.getElementById('OrderError').innerHTML="";
	}
	if(frm.wKojiName.value == ""){
		ErrorFlg = 1;
		document.getElementById('KojiNameError').innerHTML="<br><font color='red'>※工事名を記入してください</font>";
	}else{
		document.getElementById('KojiNameError').innerHTML="";
	}
	if(frm.wAddress.value == ""){
		ErrorFlg = 1;
		document.getElementById('AddressError').innerHTML="<br><font color='red'>※住所を記入してください</font>";
	}else{
		document.getElementById('AddressError').innerHTML="";
	}
	if(frm.wKokiStart.value == ""){
		ErrorFlg = 1;
		document.getElementById('KokiStartError').innerHTML="<br><font color='red'>※工期：開始日を記入してください</font>";
	}else{
		document.getElementById('KokiStartError').innerHTML="";
		//工期：開始日の入力がある場合 且つ　契約日の入力がある場合　契約日が開始日以前であることをチェックする
/*		if(frm.wKeiyakuDate.value != ""){
	alert(3);
			var KeiyakuDate = frm.wKeiyakuDate.value;
			var kaisiDate = frm.wKokiStart.value;
	alert(keiyakuDate);
				var tmpKeiyakuY=parseInt( keiyakuDate.substr(0,4),10);
	alert(5);
			var tmpKeiyakuM=parseInt( keiyakuDate.substr(5,2),10);
	alert(6);
			var tmpKeiyakuD=parseInt( keiyakuDate.substr(8,2),10);
			var tmpKaisiY=parseInt( kaisiDate.substr(0,4),10);
			var tmpKaisiM=parseInt( kaisiDate.substr(5,2),10);
			var tmpKaisiD=parseInt( kaisiDate.substr(8,2),10);
			
		}
*/	}
	if(frm.wKokiEnd.value == ""){
		ErrorFlg = 1;
		document.getElementById('KokiEndError').innerHTML="<br><font color='red'>※工期：終了日を記入してください</font>";
	}else{
		document.getElementById('KokiEndError').innerHTML="";
	}
	if(frm.wUkeoiKingaku.value == ""){
		ErrorFlg = 1;
		document.getElementById('UkeoiKingakuError').innerHTML="<br><font color='red'>※請負金額を記入してください</font>";
	}else{
		document.getElementById('UkeoiKingakuError').innerHTML="";
	}
	if(frm.wPayPeriod.value == ""){
		ErrorFlg = 1;
		document.getElementById('PayPeriodError').innerHTML="<br><font color='red'>※支払い期限を記入してください</font>";
	}else{
		document.getElementById('PayPeriodError').innerHTML="";
	}
	if(frm.wMitumoriNo.value == ""){
		ErrorFlg = 1;
		document.getElementById('MitumoriNoError').innerHTML="<br><font color='red'>※見積番号を記入してください</font>";
	}else{
		document.getElementById('MitumoriNoError').innerHTML="";
	}
/*	if(frm.wKeiyakuDate.value == ""){
		ErrorFlg = 1;
		document.getElementById('KeiyakuDateError').innerHTML="<br><font color='red'>※契約日を記入してください</font>";
	}else{
		document.getElementById('KeiyakuDateError').innerHTML="";
	}
*/	
	if(ErrorFlg==0){
		document.mainform.action = page;
		document.mainform.submit(true);
	}
}
</script>
</head>

<body>
__SHeader__

<div class="content-all"><!--content-all-->

<div class="left-yose">
<a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜メニュー</a>
</div>


<div class="top-menu left-yose">
<h5>__wBukkenName__</h5>
<h6>契約書</h6>


件名システムから取り込んだ情報です。必要に応じて変更してください。<br>
変更した内容は、件名システムには反映されません。<br><br>
物件CD：__editBukkenCD__　物件名：__wBukkenName__<br>
所属：__editSitenName__<br><br>

<form action="./doc/s_keiyakusho_Excel.php" method="POST" name="mainform">
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__" >
<input type="hidden" name="rKey" value="__rKey__" >
<input type="hidden" name="work" value="1" >

薄ピンクのフォームは必須項目です。<br>

__IfError____ErrorLoop__
<font color="red">__ErrorStrings__<br></font>
__ErrorLoop____IfError__

<table class="table table-bordered table-sm" style="width:750px">
<tr><th>注文者</th>
	<td>
		<input type="text" name="wOrder" value="__wOrder__" style="width:400px; background-color: #FFF0F5;">
		<br>(例)〇〇マンション管理組合
		<span id="OrderError"></span>
	</td>
</tr>
<tr><th>工事名</th>
	<td>
		<input type="text" name="wKojiName" value="__wKojiName__" style="width:400px; background-color: #FFF0F5;">
		<br>(例)○○マンションインターホン改修工事
		<br>※正式名称を記入してください
		<span id="KojiNameError"></span>
	</td>
</tr>
<tr><th>住所</th>
	<td>
		<input type="text" name="wAddress"value="__wAddress__" style="width:300px;background-color: #FFF0F5;">
		<br>※都道府県名から正式名称を記入してください
		<span id="AddressError"></span>
	</td>
</tr>
<tr><th>工期：開始日</th>
	<td>
		<input type="text" name="wKokiStart" value="__wKokiStart__" size="8" style="background-color: #FFF0F5;" readonly class="datepicker">
		<span id="KokiStartError"></span>
	</td>
</tr>
<tr><th>　　　終了日</th>
	<td><input type="text" name="wKokiEnd" value="__wKokiEnd__" size="8" style="background-color: #FFF0F5;" readonly class="datepicker">
		<span id="KokiEndError"></span>
	</td>
</tr>
<tr><th>請負金額：<font color="red">税金（消費税は8％）</font></th>
	<td><input type="text" name="wUkeoiKingaku" value="__wUkeoiKingaku__" size="15" style="background-color: #FFF0F5;">円(税込)
		<br>※税込で記入する
		<span id="UkeoiKingakuError"></span>
	</td>
</tr>
<tr><th>支払期限</th>
	<td>
		<input type="text" name="wPayPeriod" value="__wPayPeriod__" size="8" style="background-color: #FFF0F5;" readonly class="datepicker">
		<span id="PayPeriodError"></span>
	</td></tr>
<tr><th>見積番号</th>
	<td><input type="text" name="wMitumoriNo" size="15" value="__wMitumoriNo__" style="background-color: #FFF0F5;">
		<br>※対象の見積番号を入力
		<span id="MitumoriNoError"></span>
	</td>
</tr>

<tr><th>契約日</th>
	<td>
		<input type="text" name="wKeiyakuDate" size="8" value="__wKeiyakuDate__" readonly class="datepicker">
		<br>※後日手入力も可
		<span id="KeiyakuDateError"></span>
	</td>
</tr>
<tr><th>（注文者の）住所</th>
	<td>
		<input type="text" name="wAddress2" value="__wAddress2__" style="width:400px;">
		<br>※組合の所在地
	</td>
</tr>
<tr><th>（注文者の）組織種類</th>
	<td>
		<input type="text" name="wSosikiType" list="wSosikiTypeList" size="15">
		<datalist id="wSosikiTypeList">
			<option value="-">-</option>
			<option value="組合">組合</option>
			<option value="病院">病院</option>
			<option value="会社">会社</option>
		</select>
	</td>
</tr>
<tr><th>（注文者の）組織名</th>
	<td>
		<input type="text" name="wSosiki" size="50" value="__wSosiki__">
		<br>※組合名など
	</td>
</tr>
<tr><th>（注文者の）代表者役職名・肩書き</th>
	<td>
		<input type="text" name="wDaihyouYakusyoku" size="20" value="__wDaihyouYakusyoku__">
		<br>※「理事長」など
	</td>
</tr>

<tr><th>（注文者の）代表者氏名</th>
	<td>
		<input type="text" name="wDaihyouName" size="20" value="__wDaihyouName__">
		<br>※理事長名など
	</td>
</tr>
</table>
<br>
<br>
ついでに捺印申請書も作成する場合はコチラも入力する。
<br>
<table border="1">
<tr><th>申請日</th>
	<td><input type="text" name="wSinseiDate" value="__wSinseiDate__" readonly class="datepicker"></td></tr>
<tr><th>所長名（フルネーム）</th>
	<td><input type="text" name="wSyotyouName" value="__wSyotyouName__"></td></tr>
<tr><th>担当名（フルネーム）</th>
	<td><input type="text" name="wTantou" value="__wTantou__"></td></tr>
<tr><th>営業所名</th>
	<td><input type="text" name="wEigyousyo" value="__wEigyousyo__"></td></tr>
<tr><th>得意先コード</th>
	<td>
		<select name="wTokuisakiCD" >
			<option value="">-</option>
			<option value="有">有</option>
			<option value="無">無</option>
		</select>
	</td>
</tr>

</table>
<br>
<input type="submit" value="契約書を作成する" class="btn btn-primary">

</form>

<br>








</div><!--content-all-->

<hr>

__SFooter__
__SCopyright__

</body>
</html>

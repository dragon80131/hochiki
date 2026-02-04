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

function initOnload(){
	radioChangeYoteiKoki();
	radioChangewZumenwatasi();
}

function radioChangeYoteiKoki(){
	if(document.getElementsByName('wYoteiKoki')[0].checked) {	//別途連絡	すべて無効化
		document.getElementById("idYoteiKokiMonth").style.color = '#CCCCCC'; 
		document.getElementsByName('wYoteiKokiMonth')[0].disabled = true; 
		document.getElementById("idYoteiKokiDateStart").style.color = '#CCCCCC'; 
		document.getElementsByName('wYoteiKokiDateStart')[0].disabled = true; 
		document.getElementById("idYoteiKokiDateEnd").style.color = '#CCCCCC'; 
		document.getElementsByName('wYoteiKokiDateEnd')[0].disabled = true; 
		
	}else if(document.getElementsByName('wYoteiKoki')[1].checked){	//月指定	日付指定を無効化
		document.getElementById("idYoteiKokiMonth").style.color = '#000000'; 
		document.getElementsByName('wYoteiKokiMonth')[0].disabled = false; 
		document.getElementById("idYoteiKokiDateStart").style.color = '#CCCCCC'; 
		document.getElementsByName('wYoteiKokiDateStart')[0].disabled = true; 
		document.getElementById("idYoteiKokiDateEnd").style.color = '#CCCCCC'; 
		document.getElementsByName('wYoteiKokiDateEnd')[0].disabled = true; 
		
	}else if(document.getElementsByName('wYoteiKoki')[2].checked){	//日付指定	月指定を無効化
		document.getElementById("idYoteiKokiMonth").style.color = '#CCCCCC'; 
		document.getElementsByName('wYoteiKokiMonth')[0].disabled = true; 
		document.getElementById("idYoteiKokiDateStart").style.color = '#000000'; 
		document.getElementsByName('wYoteiKokiDateStart')[0].disabled = false; 
		document.getElementById("idYoteiKokiDateEnd").style.color = '#000000'; 
		document.getElementsByName('wYoteiKokiDateEnd')[0].disabled = false; 
	}
}

function radioChangewZumenwatasi(){

	if(document.getElementsByName('wZumenwatasi')[0].checked) {
		document.getElementById("idZumenwatasiDate").style.color = '#CCCCCC'; 
		document.getElementsByName('wZumenwatasiDate')[0].disabled = true; 
		
	}else if(document.getElementsByName('wZumenwatasi')[1].checked){
		document.getElementById("idZumenwatasiDate").style.color = '#000000'; 
		document.getElementsByName('wZumenwatasiDate')[0].disabled = false; 	
	}
}


</script>
</head>

<body onload="initOnload();">
__SHeader__

<div class="content-all"><!--content-all-->

<div class="left-yose">
<a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜メニュー</a>
</div>


<div class="top-menu left-yose">
<h5>__wBukkenName__</h5>
<h6>見積依頼書</h6>


システムから取り込んだ情報です。必要に応じて変更してください。<br>
変更した内容は、件名システムには反映されません。<br><br>
物件CD：__editBukkenCD__　物件名：__wBukkenName__<br>

<form action="s_mitsumorisho_Excel.php" method="POST" name="mainform" >
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__" >
<input type="hidden" name="rKey" value="__rKey__" >
<input type="hidden" name="YoteiKoki" value="__YoteiKoki__" >
<input type="hidden" name="Zumenwatasi" value="__Zumenwatasi__" >
<input type="hidden" name="work" value="1" >

<table class="table table-bordered table-sm" style="width:750px">
<tr><th>①件名（工事名称）</th>
	<td> 
		<input type="text" name="wKojiFullName" value="__wKojiFullName__" style="width:550px;">
	</td>
</tr>
<tr><th>②工事場所</th>
	<td>
		<input type="text" name="wAddress"value="__wAddress__" style="width:550px">
	</td>
</tr>
<tr><th>③工事概要</th>
	<td>
		<input type="text" name="wKojigaiyo" list="wKojigaiyoList" value="__wKojigaiyo__" autocomplete="off" style="width:550px">
		<datalist id="wKojigaiyoList">
			<option >インターホン工事</option>
			<option >集合インターホンリニューアル工事</option>
			<option >集合インターホン新築工事</option>
			<option >感知器交換工事</option>
			<option >ナースコールリニューアル工事</option>
			<option >ナースコール新築工事</option>
			<option >ナースコール工事</option>
			<option >PHS基地局工事</option>
			<option >交換機工事</option>
			<option >オートドア工事</option>
		</datalist>
	</td>
</tr>
<tr><th>④予定工期</th>
	<td>
		<input type="radio" name="wYoteiKoki" value="0" __YoteiKokiChecked0__  onclick="radioChangeYoteiKoki();">別途連絡
		<input type="radio" name="wYoteiKoki" value="1"  __YoteiKokiChecked1__ onclick="radioChangeYoteiKoki();">月指定
		<input type="radio" name="wYoteiKoki" value="2" __YoteiKokiChecked2__ onclick="radioChangeYoteiKoki();">日付指定
	</td>
</tr>
<tr><th></th>
	<td id="idYoteiKokiMonth">
		予定工期     <input type="text" name="wYoteiKokiMonth" value="__wYoteiKokiMonth__" size="8" disabled>月ごろ
	</td>
</tr>

<tr>
	<th></th>
	<td id="idYoteiKokiDateStart">
		予定工期（日付指定：開始日）  <input type="text" name="wYoteiKokiDateStart" value="__wYoteiKokiDateStart__" size="8" class="datepicker" disabled>
	</td>
</tr>

<tr><th></th>
	<td id="idYoteiKokiDateEnd">
		予定工期（日付指定：終了日）  <input type="text" name="wYoteiKokiDateEnd" value="__wYoteiKokiDateEnd__" size="8" class="datepicker" disabled>
	</td>
</tr>
<tr><th>⑤工法</th>
	<td>
		<input type="text" name="wKoho" value="__wKoho__" style="width:550px">
		<datalist id="wKohoList">
			<option >特に指定無し</option>
			<option >既設配線の流用</option>
			<option >新規配線の敷設</option>
		</datalist>
	</td>
</tr>
<tr><th>⑥支給品</th>
	<td>
		<input type="text" name="wSikyuhin" list="wSikyuhinList" value="__wSikyuhin__" autocomplete="off" style="width:550px">
		<datalist id="wSikyuhinList">
			<option >有り</option>
			<option >無し</option>
		</datalist>
	</td>
</tr>
<tr><th>⑦施工条件・範囲</th>
	<td>
		<input type="text" name="wSekojyoukenHanni" list="wSekojyoukenHanniList" value="__wSekojyoukenHanni__" autocomplete="off" style="width:550px">
		<datalist id="wSekojyoukenHanniList">
			<option>工事中は既設停止</option>
			<option>工事中は既設ノンストップ</option>
			<option>他社設備との連動有り</option>
			<option>工事中は既設停止 / 他社設備との連動有り</option>
			<option>工事中は既設ノンストップ / 他社設備との連動有り</option>
		</datalist>
	</td>
</tr>
<tr><th>⑧支払い条件</th>
	<td>
		<input type="text" name="wSiharaijyoken" list="wSikyuhinList"  value="__wSiharaijyoken__"  autocomplete="off" style="width:550px">

		<datalist id="wSikyuhinList">
			<option>月末締め翌月20日振込</option>
		</datalist>
	</td>
</tr>
<tr><th>⑨図面渡しの日時場所</th>
	<td>
		<input type="radio" name="wZumenwatasi" value="0"  __ZumenwatasiChecked0__ onchange="radioChangewZumenwatasi()">現調時に現場で参照　
		<input type="radio" name="wZumenwatasi" value="1"  __ZumenwatasiChecked1__ onchange="radioChangewZumenwatasi()">日付指定
	</td>
</tr>
<tr><th></th>
	<td id="idZumenwatasiDate" style="color:#CCCCCC">
		図面渡しの日時（日付指定）  <input type="text" name="wZumenwatasiDate" value="__wZumenwatasiDate__" size="8" class="datepicker" disabled>
	</td>
</tr>
<tr><th>⑩見積提出期限</th>
	<td>
		<input type="text" name="wMitsumoriteisyutukigen" value="__wMitsumoriteisyutukigen__" size="15" class="datepicker">
		<br>※見積もりの予定金額によって、一定の提出期間を設ける。
		<br>・500万円未満（中1日以上） / 500万円以上5000万円未満（中10日以上）

	</td>
</tr>
<tr><th>⑪制約条件</th>
	<td>
		<input type="text" name="wSeiyakujyouken" list="wSeiyakujyoukenList" value="__wSeiyakujyouken__" autocomplete="off" style="width:550px">
		<datalist id="wSeiyakujyoukenList">
			<option>産廃処理は元請業者が行います</option>
		</datalist>
	</td>
</tr>


</table>
<br>
<input type="submit" value="見積依頼書を作成する" class="btn btn-primary">

</form>

<br>

</div><!--content-all-->

<hr>

__SFooter__
__SCopyright__

</body>
</html>

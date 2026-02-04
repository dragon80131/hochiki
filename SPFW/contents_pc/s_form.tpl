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

function moveandcheck(page){

	var error_flg = false;
	
	var kenmeino = document.getElementById('Kenmeiid').value;
	var bukkenname = document.getElementById('BukkenNameid').value;
	var kojiname = document.getElementById('KojiNameid').value;
	var address = document.getElementById('Addressid').value;
	var kosu = document.getElementById('Kosuid').value;
	var kaidaka = document.getElementById('Kaidakaid').value;
	document.getElementById('ErrorString').innerHTML = "<br>";

	if(kenmeino==""){
		error_flg = true;
		document.getElementById('ErrorString').innerHTML += "<font color='red'>管理Noが入力されていません</font><br>";
	}
	if(bukkenname==""){
		error_flg = true;
		document.getElementById('ErrorString').innerHTML += "<font color='red'>物件名が入力されていません</font><br>";
	}
	if(kojiname==""){
		error_flg = true;
		document.getElementById('ErrorString').innerHTML += "<font color='red'>工事名称が入力されていません</font><br>";
	}
	if(address==""){
		error_flg = true;
		document.getElementById('ErrorString').innerHTML += "<font color='red'>住所が入力されていません</font><br>";
	}
	if(kosu==""){
		error_flg = true;
		document.getElementById('ErrorString').innerHTML += "<font color='red'>住戸数が入力されていません</font><br>";
	}
	if(kaidaka==""){
		error_flg = true;
		document.getElementById('ErrorString').innerHTML += "<font color='red'>階高が入力されていません</font><br>";
	}


	if(error_flg==false){
		document.mainform.action = page;
		document.mainform.submit(true);
	}else{
		window.scrollTo(0,50);
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
<h6>物件基本情報登録</h6>


管理システムから取り込んだ情報です。必要に応じて変更してください。<br>
変更した内容は、管理システムには反映されません。<br><br>
物件CD：__editBukkenCD__　物件名：__wBukkenName__<br>
所属：__editSitenName__<br><br>

<form action="s_finish_kihon.php" method="POST" name="mainform">
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__" >
<input type="hidden" name="rKey" value="__rKey__" >
<input type="hidden" name="work" value="1" >

<font color="red">薄ピンクのフォームは必須項目です。</font><br>
<span id="ErrorString"></span>
__IfError____ErrorLoop__
<font color="red">__ErrorStrings__<br></font>
__ErrorLoop____IfError__

<table class="table table-bordered table-sm" style="width:700px">
<tr><th>管理No</th>
	<td><input type="text" name="wKenmeiNo" id="Kenmeiid" value="__KenmeiNo__" style="width:120px; background-color: #FFF0F5;"></td></tr>
<tr><th>物件担当者</th>
	<td>
<select name="wTantoCD" style="width:120px; background-color: #FFF0F5;">
<option value=""  >- </option>
__TantoLoop__
<option value="__TantoCD__" __TantoCDSelected__ >__TantoName__ </option>
__TantoLoop__
</select>
</td></tr>
<tr><th>物件名</th>
	<td><input type="text" name="wBukkenName" id="BukkenNameid" value="__wBukkenName__" style="width:400px; background-color: #FFF0F5;">
		<br><font size="2" color="gray">※物件名に工事名称が入っている場合は工事名称を削除してください。</font>
	</td></tr>
<tr><th>工事名称</th>
	<td><input type="text" name="wKojiName" id="KojiNameid" value="__wKojiName__" style="width:400px;background-color:#FFF0F5;">
		<br><font size="2" color="red">※工事名称は各工事案内に反映されます</font>
</td></tr>



<tr><th>住所</th>
	<td><input type="text" name="wAddress" id="Addressid" value="__wAddress__" style="width:400px; background-color: #FFF0F5;"></td></tr>
<tr><th>住戸数</th>
	<td><input type="text" name="wKosu" id="Kosuid" value="__wKosu__" size="4" style="background-color: #FFF0F5;"> 戸</td></tr>
<tr><th>棟数</th>
	<td><input type="text" name="wTosu"  value="__wTosu__" size="4" > 棟</td></tr>
<tr><th>階高</th>
	<td><input type="text" name="wKaidaka" id="Kaidakaid" value="__wKaidaka__" size="4" style="background-color: #FFF0F5;"> 階</td></tr>

<tr><th>分譲/賃貸</th>
	<td>
		<input type="radio" name="wBunjyo" value="1" __BunjyoChecked1__ >分譲　
		<input type="radio" name="wBunjyo" value="2" __BunjyoChecked2__ >賃貸
	</td></tr>
<tr><th>竣工(年月）</th>
	<td><input type="text" name="wShunko" size="8" value="__wShunko__"></td></tr>


   <tr><th >管理会社</th>
	<td><input type="text" name="wKanriGaisya" value="__wKanriGaisya__">
		<br><font size="2" >※正式名称を記載してください</font>
</td></tr>
	 </tr> <tr><th >管理会社TEL</th>
	<td><input type="text" name="wKanriGaisyaTEL" value="__wKanriGaisyaTEL__">
		<br><font size="2" >※間違いの無いよう記載してください</font>
</td></tr>
<tr>
	<th >管理会社担当者名</th>
	<td><input type="text" name="wKanriGaisyaTanto" value="__wKanriGaisyaTanto__">
		<br><font size="2" >※間違いの無いよう記載してください</font>
</td></tr>




<tr><th>受注金額</th><td>__wJyucyuKingaku__</td></tr>

<tr><th colspan="2" style="background-color: #e0ffff" >★工事案内表記</td></tr>
<tr><th>集合玄関機品番</th>
	<td><input type="text" name="wShuGenKataban" value="__wShuGenKataban__"></td></tr>
<tr><th>室内親機品番</th>
	<td><input type="text" name="wOyaKataban" value="__wOyaKataban__"></td></tr>
<tr><th>玄関子機品番</th>
	<td><input type="text" name="wKokiKataban" value="__wKokiKataban__"></td></tr>
</table>
<br>
<!--<input type="submit" value="登録する" class="btn btn-primary">-->
<input type="button" value="登録する" class="btn btn-primary" onclick="moveandcheck('s_finish_kihon.php')">

</form>

<br>


<table class="table table-bordered table-sm" style="width:700px">
<tr><th colspan="3" style="background-color: #e0ffff">★使用機器明細</td></tr>
<!--<tr><th>最新稟議書No</td><td>__RNG_SIN_NO__</td></tr>
<tr><th>NET価格合計（社内品）</th><td>__NET_NAI_GOKEI__</td></tr>
<tr><th>NET価格合計（他社品）</th><td>__NET_TAS_GOKEI__</td></tr>
<tr><th>NET価格合計（工事費）</th><td>__NET_KOJ_GOKEI__</td></tr>
<tr><th>NET価格合計（値引）</th><td>__NET_NBK_GOKEI__</td></tr>
-->
__MeisaiLoop__
<tr><th rowspan="3" align="center">__No__</th><th>品番</th><td>__HINBAN__</td></tr>
<!--<tr><th rowspan="5" align="center">__No__</th><th>機器区分</th><td>__MEISAI_KIKI_KBN__</td></tr>
<tr><th>商品ＣＤ</th><td>__SYHN_CD__</td></tr>
<tr><th>品番</th><td>__HINBAN__</td></tr>-->
<tr><th>品名・仕様</th><td>__HINMEI_SIYO__</td></tr>
<tr><th>受注数量</th><td>__JUC_SURYO__</td></tr>
__MeisaiLoop__
</table>


</div>




</div><!--content-all-->

<hr>

__SFooter__
__SCopyright__

</body>
</html>

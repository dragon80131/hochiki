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


件名システムから取り込んだ情報です。必要に応じて変更してください。<br>
変更した内容は、件名システムには反映されません。<br><br>
物件CD：__editBukkenCD__　物件名：__wBukkenName__<br>
所属：__editSitenName__<br><br>

<form action="s_finish_kihon.php" method="POST" name="mainform">
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__" >
<input type="hidden" name="rKey" value="__rKey__" >
<input type="hidden" name="work" value="1" >

薄ピンクのフォームは必須項目です。<br>

__IfError____ErrorLoop__
<font color="red">__ErrorStrings__<br></font>
__ErrorLoop____IfError__

<table class="table table-bordered table-sm" style="width:700px">
<tr><th>件名No</th><td>__KenmeiNo__</td></tr>
<tr><th>物件名</th>
	<td><input type="text" name="wBukkenName" value="__wBukkenName__" style="width:400px; background-color: #FFF0F5;">
		<br><font size="2" color="gray">※物件名に工事名称が入っている場合は工事名称を削除してください。</font>
	</td></tr>
<tr><th>工事名称</th>
	<td><input type="text" name="wKojiName"  value="__wKojiName__" style="width:400px;background-color:#FFF0F5;">
		<br><font size="2" color="red">※工事名称は各工事案内に反映されます</font>
</td></tr>

<!--<tr><th>主管担当部署</th><td>__wShozokuName__</td></tr>
<tr><th>担当者</th><td><input type="text" name="wTantoCD" value="__wTantoName__" ></td></tr>-->

<tr><th>住所</th>
	<td><input type="text" name="wAddress"value="__wAddress__" style="width:400px"></td></tr>
<tr><th>棟数</th>
	<td><input type="text" name="wTosu" value="__wTosu__" size="4" style="background-color: #ffffcc;"> 棟</td></tr>
<tr><th>住戸数</th>
	<td><input type="text" name="wKosu" value="__wKosu__" size="4" style="background-color: #FFF0F5;"> 戸</td></tr>
<tr><th>階高</th>
	<td><input type="text" name="wKaidaka" value="__wKaidaka__" size="4" style="background-color: #FFF0F5;"> 階</td></tr>

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
<tr><th>オートロック有無</td>
	<td>
		<input type="radio" name="wAutoLock" value="1" __AutoLockChecked1__ >有　
		<input type="radio" name="wAutoLock" value="2" __AutoLockChecked2__ >無
	</td></tr>
<tr><th>集合玄関機品番</th>
	<td><input type="text" name="wShuGenKataban" value="__wShuGenKataban__"></td></tr>
<tr><th>室内親機品番</th>
	<td><input type="text" name="wOyaKataban" value="__wOyaKataban__"></td></tr>
<tr><th>玄関子機品番</th>
	<td><input type="text" name="wKokiKataban" value="__wKokiKataban__"></td></tr>
</table>
<br>
<input type="submit" value="登録する" class="btn btn-primary">

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

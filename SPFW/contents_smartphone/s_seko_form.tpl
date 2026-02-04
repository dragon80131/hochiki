<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>リニューアル支援</title>

<!-- BootstrapのCSS読み込み -->
<link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="./css/rnsien.css">
<script type="text/javascript" src="tools.js"></script>

<!-- jQuery読み込み -->
<script src="./include/js/jquery-3.2.1.min.js"></script>

<!-- BootstrapのJS読み込み -->
<script src="./include/bootstrap/js/bootstrap.min.js"></script>

<!--datepicker-->
<link href="css/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<link href="css/jquery-ui-1.12.1/jquery-ui.structure.min.css" rel="stylesheet">
<link href="css/jquery-ui-1.12.1/jquery-ui.theme.min.css" rel="stylesheet">
<script src="js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
<script src="js/jquery-ui/datepicker-ja.js"></script>

<script>

function initOnload(){
	clickwSiyobuzaiOya();
	clickwSiyobuzaiKo();
	clickwSiyobuzaiSyugen();
	clickwSiyobuzaiKanOya();
	clickwSiyobuzaiSeigyoFlg();
}

/*
$(function() {
	$(".datepicker").datepicker({});

	// オプション工事
	var $children = $('.children'); //子要素を変数に入れます。
	var original = $children.html(); //オリジナルをとっておく

	original = original.replace("selected", " ");
	$('.parent').change(function() {
		var id = $(this).attr('id'); // 親のid
		var val1 = $(this).val(); // 親のvalue
		var $ko = $('.'+id+''); // 子のセレクタ

		//削除された要素をもとに戻すため.html(original)を入れておく
		//$children.html(original).find('option').each(function() {
		$ko.html(original).find('option').each(function() {
			var val2 = $(this).data('val'); //data-valの値を取得

			//valueと異なるdata-valを持つ要素を削除
			if (val1 != val2) {
				$(this).not(':first-child').remove();
			}
		});

		if ($(this).val() == "0") { // 親が未選択なら、子を全表示
			$children.html(original);
		}
	});
});
*/
function changeread(){
	if(document.mainform.wAnshoNo[0].checked){
		document.getElementById("number1").disabled = false;
		document.getElementById("number2").disabled = false;
	}else{
		document.getElementById("number1").disabled = true;
		document.getElementById("number2").disabled = true;
	}
}

//７．インターホン設備更新工事　使用部材 ラジオボタンハンドラ
function clickwSiyobuzaiOya(){
	if(document.mainform.wSiyobuzaiOyaFlg[0].checked){
		document.getElementsByName("wOyaPanel")[0].disabled = false;
		document.getElementsByName("wSiyobuzaiOyaBiko")[0].disabled = false;
		document.getElementById("SiyobuzaiOyaFont").style.color = "black";

	}else{
		document.getElementsByName("wOyaPanel")[0].disabled = true;
		document.getElementsByName("wSiyobuzaiOyaBiko")[0].disabled = true;
		document.getElementById("SiyobuzaiOyaFont").style.color = "lightgray";
	}
}

function clickwSiyobuzaiKo(){
	if(document.mainform.wSiyobuzaiKoFlg[0].checked){
		document.getElementsByName("wKoPanel")[0].disabled = false;
		document.getElementsByName("wSiyobuzaiKoBiko")[0].disabled = false;
		document.getElementById("SiyobuzaiKoFont").style.color = "black";

	}else{
		document.getElementsByName("wKoPanel")[0].disabled = true;
		document.getElementsByName("wSiyobuzaiKoBiko")[0].disabled = true;
		document.getElementById("SiyobuzaiKoFont").style.color = "lightgray";
	}
}


function clickwSiyobuzaiSyugen(){
	if(document.mainform.wSiyobuzaiSyugenFlg[0].checked){
		document.getElementsByName("wSiyobuzaiSyugenBiko")[0].disabled = false;
	}else{
		document.getElementsByName("wSiyobuzaiSyugenBiko")[0].disabled = true;
	}
}


function clickwSiyobuzaiKanOya(){

	if(document.mainform.wSiyobuzaiKanOyaFlg[0].checked){
		document.getElementsByName("wSiyobuzaiKanOyaBiko")[0].disabled = false;
	}else{
		document.getElementsByName("wSiyobuzaiKanOyaBiko")[0].disabled = true;
	}
}

function clickwSiyobuzaiSeigyoFlg(){

	if(document.mainform.wSiyobuzaiSeigyoFlg[0].checked){
		document.getElementsByName("wSiyobuzaiSeigyoBiko")[0].disabled = false;
	}else{
		document.getElementsByName("wSiyobuzaiSeigyoBiko")[0].disabled = true;
	}
}

function checkandmove(){
	var error_flg = false;
	document.getElementById('errorstrings').innerHTML="";
	if(document.mainform.wGasBaseFlg.value == ""){
		document.getElementById('errorstrings').innerHTML="※ガス漏れ警報ベースの有無が選択されていません。<br>";
		error_flg = true;
	}
	if(document.mainform.wKasaiChukeiRNFlg.value == ""){
		document.getElementById('errorstrings').innerHTML +="※遠隔試験機能付中継器交換の有無が選択されていません。<br>";
		error_flg = true;
	}
	if(document.mainform.wKasaiKyoyuRNFlg.value == ""){
		document.getElementById('errorstrings').innerHTML+="※住棟受信機交換の有無が選択されていません。<br>";
		error_flg = true;
	}

	if(error_flg == false){
		move("s_seko_finish.php");
	}else{
		window.scrollTo(0,50);
	}
}



</script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
<script type="text/javascript" src="js/ConnectedSelect.js"></script>

</head>

<body onload="initOnload();">
__SHeader__

<div class="content-all"><!--content-all-->

<div class="left-yose">
<a href="./s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜メニュー</a>
</div>


<div class="top-menu left-yose">
<h5>__wBukkenName__</h5>
<h6>施工情報登録</h6>

<form action="s_seko_finish.php" method="POST" name="mainform" >
<input type="hidden" name="rKey" value="__rKey__" >
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__" >


◆<a href="s_seko_copy.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">過去の案件の施工指示をコピーする</a>
<br><br>

<font color="red"><span id="errorstrings"></span></font>


◆１．基本情報
<table class="table table-bordered table-sm">
<!--<tr><th class="yb">工事管理番号</th>
	<td colspan="3">__wKojiCD__（システムで利用）</td></tr>-->
<tr><th class="yb">マンション名</th>
	<td colspan="3">__wBukkenName__</td></tr>
<tr><th class="yb">工事名称</th>
	<td colspan="3">__wKojiName__</td></tr>
<tr><th class="yb">住所</th><td>__wAddress__</td>
	<th class="yb">戸数</th><td>__wKosu__</td></tr>
<!--<tr><th class="yb">管理会社</th>
	<td>__wKanriGaisya__</td>
	<th class="yb">管理会社担当者名</th>
	<td>__wKanriGaisyaTanto__</td>
<tr><th class="yb">管理会社担当者メール</th>
	<td colspan="3"><input type="text" name="wKanriGaisyaTantoEMail" style="width:400px"  value="__wKanriGaisyaTantoEMail__"></td>
-->
<tr><th class="yb">消防特例</th>
	<td colspan="3">
__DispShoboTokurei__
<!--
		<input type="radio" name="wShoboTokurei" value="0" __ShoboTokureiChecked0__ >特例なし　
		<input type="radio" name="wShoboTokurei" value="1" __ShoboTokureiChecked1__ >170号　
		<input type="radio" name="wShoboTokurei" value="2" __ShoboTokureiChecked2__ >220号住戸用　
		<input type="radio" name="wShoboTokurei" value="3" __ShoboTokureiChecked3__ >220号共住用</td></tr>-->
<tr><th class="yb">管理員</th>
	<td><input type="text" name="wKanriinName" value="__wKanriinName__ "></td>
	<th class="yb">管理室TEL</th>
	<td><input type="text" name="wKanriTEL" value="__wKanriTEL__"></td></tr>
<tr><th class="yb">管理員勤務状況</th>
	<td colspan="3"><input type="text" name="wKanriKinmu" style="width:400px" value="__wKanriKinmu__"></td></tr>
<!--
<tr><th  class="yb">全体工期</th>
	<td colspan="3">
		__wZentaiStartDate__～__wZentaiEndDate__
	</td>
</tr>
<tr><th class="yb">共用部</th>
	<td colspan="3">
		__wKyoyoStartDate__～__wKyoyoEndDate__
	</td>
</tr>
<tr><th class="yb">専有部</th>
	<td colspan="3">
		__wSenyuStartDate__～__wSenyuEndDate__
	</td>
</tr>
-->
<tr><th class="yb">工事指示備考</th>
	<td colspan="5"><textarea name="wKojiShijiBiko" rows=3 style="width:100%;" >__wKojiShijiBiko__</textarea></td>
	</td>
</tr>
</table>


<br>
◆２．工事種別・工事概要
<table class="table table-bordered table-sm">
<tr><th colspan="2" class="yb">インターホン設備更新</th>
	<td><!--name="wRNstateFlg" -->有

	</td>
	<th colspan="2" class="yb">基本システム</th>
		<td>__DispRNsystem__
		</td>
	<!--<td bgcolor="lemonchiffon" colspan="2">カメラ</td>
	<td><input type="text" name="wCamera" value="">-->
</tr>
<tr><th colspan="2" class="yb">ガス漏れ警報器交換</th>
	<td>__DispGasKoji__
	</td>
	<th colspan="2" class="yb">ガス漏れ警報器ベース交換</th>
	<td bgcolor=#FFF0F5><input type="radio"  name="wGasBaseFlg" value="0" __GasBaseFlgChecked0__ >有　
		<input type="radio" name="wGasBaseFlg" value="1" __GasBaseFlgChecked1__ >無
	</td>
</tr>
<tr>
	<th  colspan="2" rowspan=3  class="yb">自動火災報知設備</th>
	<th colspan="1" class="yb">専有部感知器交換</th>
	<td colspan="3" >__DispJikaho__
	</td>
</tr>
<tr>
	<th colspan="1" class="yb">遠隔試験機能付中継器交換</th>
	<td  colspan="3" bgcolor=#FFF0F5>
		<input type="radio" name="wKasaiChukeiRNFlg" value="0" __KasaiChukeiRNFlgChecked0__ >有　
		<input type="radio" name="wKasaiChukeiRNFlg" value="1" __KasaiChukeiRNFlgChecked1__ >無
	</td>
</tr>
<tr>

	<th colspan="1" class="yb">住棟受信機交換</th>
	<td  colspan="3" bgcolor=#FFF0F5>
		<input type="radio" name="wKasaiKyoyuRNFlg" value="0" __KasaiKyoyuRNFlgChecked0__ >有　
		<input type="radio" name="wKasaiKyoyuRNFlg" value="1" __KasaiKyoyuRNFlgChecked1__ >無
	</td>

</tr>
<tr><th colspan="2" class="yb">その他工事</th>
	<td colspan="7">
		<!--<input type="radio" name="wSonotaKojiFlg" value="0" __SonotaKojiFlgChecked0__ >有　
		<input type="radio" name="wSonotaKojiFlg" value="1" __SonotaKojiFlgChecked1__ >無　
		<br>-->
	<textarea name="wSonotaKojiNaiyo" rows=3 style="width:100%;" >__wSonotaKojiNaiyo__</textarea>

	</td>
</tr>

</table>
<br>

◆３．設備
<table class="table table-bordered table-sm">
<tr><th colspan="2" class="yb"  bgcolor=#FFF0F5>資材置場</th>
	<td><input type="radio" name="wShizaiOkibaFlg" value="0" __ShizaiOkibaFlgChecked0__ >有　
		<input type="radio" name="wShizaiOkibaFlg" value="1" __ShizaiOkibaFlgChecked1__ >無
	</td><td colspan="7"><input type="text" name="wShizaiOkiba" value="__wShizaiOkiba__" style="width:500px"></td>
</tr>
<tr><th colspan="2" class="yb">道具置場</th>
	<td><input type="radio" name="wDoguOkibaFlg" value="0" __DoguOkibaFlgChecked0__ >有　
		<input type="radio" name="wDoguOkibaFlg" value="1" __DoguOkibaFlgChecked1__ >無
	</td><td colspan="7"><input type="text" name="wDoguOkiba" value="__wDoguOkiba__" style="width:500px"></td>
</tr>
<tr><th colspan="2" class="yb">産廃置場</th>
	<td><input type="radio" name="wSanpaiOkibaFlg" value="0" __SanpaiOkibaFlgChecked0__ >有　
		<input type="radio" name="wSanpaiOkibaFlg" value="1" __SanpaiOkibaFlgChecked1__ >無
	</td><td colspan="7"><input type="text" name="wSanpaiOkiba" value="__wSanpaiOkiba__" style="width:500px"></td>
</tr>
<tr><th colspan="2" class="yb">休憩所</th>
	<td><input type="radio" name="wKyukeiFlg" value="0" __KyukeiFlgChecked0__ >有　
		<input type="radio" name="wKyukeiFlg" value="1" __KyukeiFlgChecked1__ >無
	</td><td colspan="7"><input type="text" name="wKyukei" value="__wKyukei__" style="width:500px"></td>
</tr>
<tr><th colspan="2" class="yb">トイレ</th>
	<td><input type="radio" name="wToiletFlg" value="0" __ToiletFlgChecked0__ >有　
		<input type="radio" name="wToiletFlg" value="1" __ToiletFlgChecked1__ >無
	</td><td colspan="7"><input type="text" name="wToilet" value="__wToilet__" style="width:500px"></td>
</tr>
<tr><th colspan="2" class="yb">喫煙所</th>
	<td><input type="radio" name="wSmokingFlg" value="0" __SmokingFlgChecked0__ >有　
		<input type="radio" name="wSmokingFlg" value="1" __SmokingFlgChecked1__ >無
	</td><td colspan="7"><input type="text" name="wSmoking" value="__wSmoking__" style="width:500px"></td>
</tr>
<tr><th colspan="2" class="yb">鍵の借用</th>
	<td><input type="radio" name="wRentKeyFlg" value="0" __RentKeyFlgChecked0__ >有　
		<input type="radio" name="wRentKeyFlg" value="1" __RentKeyFlgChecked1__ >無
	</td><td colspan="7"><input type="text" name="wRentKey" value="__wRentKey__" style="width:500px"></td>
</tr>
<tr><th colspan="2" class="yb">駐車場</th>
	<td><input type="radio" name="wParkingFlg" value="0" __ParkingFlgChecked0__ >有　
		<input type="radio" name="wParkingFlg" value="1" __ParkingFlgChecked1__ >無
	</td><td colspan="7"><input type="text" name="wParking" value="__wParking__" style="width:500px"></td>
</tr>
<tr><th colspan="2" class="yb">設備備考</th>
	<td colspan="8"><textarea name="wSetubiBiko" rows=3 style="width:100%;" >__wSetubiBiko__</textarea></td>
</tr>
</table>
<br>

◆４．警報関係
<table class="table table-bordered table-sm">
<tr><th colspan="2" class="yb">警備会社</th>
	<td colspan="3"><input type="text" name="wKeibiCompany" value="__wKeibiCompany__"></td>
	<th colspan="2" class="yb">警備会社TEL</th>
	<td colspan="3"><input type="text" name="wKeibiCompanyTEL" value="__wKeibiCompanyTEL__"></td>
</tr>
<tr><th colspan="2" class="yb">非常</th>
	<td><input type="radio" name="wHijyo" value="0" __HijyoChecked0__ >有　
		<input type="radio" name="wHijyo" value="1" __HijyoChecked1__ >無</td>
	<th colspan="2" class="yb">移報</th>
	<td><input type="radio" name="wHijyoIho" value="0" __HijyoIhoChecked0__ >有　
		<input type="radio" name="wHijyoIho" value="1" __HijyoIhoChecked1__ >無</td>
	<th colspan="2" class="yb">移報種別</th>
	<td><input type="checkbox" name="wHijyoIhoShubetu[]" value="0" __HijyoIhoShubetuChecked0__ >A　
		<input type="checkbox" name="wHijyoIhoShubetu[]" value="1" __HijyoIhoShubetuChecked1__ >B　
		<input type="checkbox" name="wHijyoIhoShubetu[]" value="2" __HijyoIhoShubetuChecked2__ >データ</td>
</tr>
<tr><th colspan="2" class="yb">ガス漏れ</th>
	<td><input type="radio" name="wGas" value="0" __GasChecked0__ >有　
		<input type="radio" name="wGas" value="1" __GasChecked1__ >無</td>
	<th colspan="2" class="yb">移報</th>
	<td><input type="radio" name="wGasIho" value="0" __GasIhoChecked0__ >有　
		<input type="radio" name="wGasIho" value="1" __GasIhoChecked1__ >無</td>
	<th colspan="2" class="yb">移報種別</th>
	<td><input type="checkbox" name="wGasIhoShubetu[]" value="0" __GasIhoShubetuChecked0__ >A　
		<input type="checkbox" name="wGasIhoShubetu[]" value="1" __GasIhoShubetuChecked1__ >B　
		<input type="checkbox" name="wGasIhoShubetu[]" value="2" __GasIhoShubetuChecked2__ >データ</td>
</tr>
<tr><th colspan="2" class="yb">火災</th>
	<td><input type="radio" name="wKasai" value="0" __KasaiChecked0__ >有　
		<input type="radio" name="wKasai" value="1" __KasaiChecked1__ >無</td>
	<th colspan="2" class="yb">移報</th>
	<td><input type="radio" name="wKasaiIho" value="0" __KasaiIhoChecked0__ >有　
		<input type="radio" name="wKasaiIho" value="1" __KasaiIhoChecked1__ >無</td>
	<th colspan="2" class="yb">移報種別</th>
	<td><input type="checkbox" name="wKasaiIhoShubetu[]" value="0" __KasaiIhoShubetuChecked0__ >A　
		<input type="checkbox" name="wKasaiIhoShubetu[]" value="1" __KasaiIhoShubetuChecked1__ >B　
		<input type="checkbox" name="wKasaiIhoShubetu[]" value="2" __KasaiIhoShubetuChecked2__ >データ</td>
</tr>
<tr><th colspan="2" class="yb">障害</th>
	<td><input type="radio" name="wTrouble" value="0" __TroubleChecked0__ >有　
		<input type="radio" name="wTrouble" value="1" __TroubleChecked1__ >無</td>
	<th colspan="2" class="yb">移報</th>
	<td><input type="radio" name="wTroubleIho" value="0" __TroubleIhoChecked0__ >有　
		<input type="radio" name="wTroubleIho" value="1" __TroubleIhoChecked1__ >無</td>
	<th colspan="2" class="yb">移報種別</th>
	<td><input type="checkbox" name="wTroubleIhoShubetu[]" value="0" __TroubleIhoShubetuChecked0__ >A　
		<input type="checkbox" name="wTroubleIhoShubetu[]" value="1" __TroubleIhoShubetuChecked1__ >B　
		<input type="checkbox" name="wTroubleIhoShubetu[]" value="2" __TroubleIhoShubetuChecked2__ >データ</td>
</tr>
<tr><th colspan="2" class="yb">防犯</th>
	<td><input type="radio" name="wBohan" value="0" __BohanChecked0__ >有　
		<input type="radio" name="wBohan" value="1" __BohanChecked1__ >無</td>
	<th colspan="2" class="yb">移報</th>
	<td><input type="radio" name="wBohanIho" value="0" __BohanIhoChecked0__ >有　
		<input type="radio" name="wBohanIho" value="1" __BohanIhoChecked1__ >無</td>
	<th colspan="2" class="yb">移報種別</th>
	<td><input type="checkbox" name="wBohanIhoShubetu[]" value="0" __BohanIhoShubetuChecked0__ >A　
		<input type="checkbox" name="wBohanIhoShubetu[]" value="1" __BohanIhoShubetuChecked1__ >B　
		<input type="checkbox" name="wBohanIhoShubetu[]" value="2" __BohanIhoShubetuChecked2__ >データ</td>
</tr>
<tr><th colspan="2" class="yb">換気（追記）</th>
	<td><input type="radio" name="wKanki" value="0" __KankiChecked0__ >有　
		<input type="radio" name="wKanki" value="1" __KankiChecked1__ >無</td>
	<th colspan="2" class="yb">移報</th>
	<td><input type="radio" name="wKankiIho" value="0" __KankiIhoChecked0__ >有　
		<input type="radio" name="wKankiIho" value="1" __KankiIhoChecked1__ >無</td>
	<th colspan="2" class="yb">移報種別</th>
	<td><input type="checkbox" name="wKankiIhoShubetu[]" value="0" __KankiIhoShubetuChecked0__ >A　
		<input type="checkbox" name="wKankiIhoShubetu[]" value="1" __KankiIhoShubetuChecked1__ >B　
		<input type="checkbox" name="wKankiIhoShubetu[]" value="2" __KankiIhoShubetuChecked2__ >データ</td>
</tr>
<tr><th colspan="2" class="yb">その他1</th>
	<td><input type="radio" name="wSonota1" value="0" __Sonota1Checked0__ >有　
		<input type="radio" name="wSonota1" value="1" __Sonota1Checked1__ >無</td>
	<th colspan="2" class="yb">移報</th>
	<td><input type="radio" name="wSonota1Iho" value="0" __Sonota1IhoChecked0__ >有　
		<input type="radio" name="wSonota1Iho" value="1" __Sonota1IhoChecked1__ >無</td>
	<th colspan="2" class="yb">移報種別</th>
	<td><input type="checkbox" name="wSonota1IhoShubetu[]" value="0" __Sonota1IhoShubetuChecked0__ >A　
		<input type="checkbox" name="wSonota1IhoShubetu[]" value="1" __Sonota1IhoShubetuChecked1__ >B　
		<input type="checkbox" name="wSonota1IhoShubetu[]" value="2" __Sonota1IhoShubetuChecked2__ >データ</td>
</tr>
<tr><th colspan="2" class="yb">その他2</th>
	<td><input type="radio" name="wSonota2" value="0" __Sonota2Checked0__ >有　
		<input type="radio" name="wSonota2" value="1" __Sonota2Checked1__ >無</td>
	<th colspan="2" class="yb">移報</th>
	<td><input type="radio" name="wSonota2Iho" value="0" __Sonota2IhoChecked0__ >有　
		<input type="radio" name="wSonota2Iho" value="1" __Sonota2IhoChecked1__ >無</td>
	<th colspan="2" class="yb">移報種別</th>
	<td><input type="checkbox" name="wSonota2IhoShubetu[]" value="0" __Sonota2IhoShubetuChecked0__ >A　
		<input type="checkbox" name="wSonota2IhoShubetu[]" value="1" __Sonota2IhoShubetuChecked1__ >B　
		<input type="checkbox" name="wSonota2IhoShubetu[]" value="2" __Sonota2IhoShubetuChecked2__ >データ</td>
</tr>
<tr><th colspan="2" class="yb">その他3</th>
	<td><input type="radio" name="wSonota3" value="0" __Sonota3Checked0__ >有　
		<input type="radio" name="wSonota3" value="1" __Sonota3Checked1__ >無</td>
	<th colspan="2" class="yb">移報</th>
	<td><input type="radio" name="wSonota3Iho" value="0" __Sonota3IhoChecked0__ >有　
		<input type="radio" name="wSonota3Iho" value="1" __Sonota3IhoChecked1__ >無</td>
	<th colspan="2" class="yb">移報種別</th>
	<td><input type="checkbox" name="wSonota3IhoShubetu[]" value="0" __Sonota3IhoShubetuChecked0__ >A　
		<input type="checkbox" name="wSonota3IhoShubetu[]" value="1" __Sonota3IhoShubetuChecked1__ >B　
		<input type="checkbox" name="wSonota3IhoShubetu[]" value="2" __Sonota3IhoShubetuChecked2__ >データ</td>
</tr>
<tr><th colspan="2" class="yb">警報備考</th>
	<td colspan="8"><textarea name="wKeihoBiko" rows="3" style="width:100%;" >__wKeihoBiko__</textarea></td>
	</td>
</tr>

</table><br>

◆５．オプション工事
<table class="table table-bordered table-sm" style="width: 800px">
<tr><th class="yb" style="width:50px">No</th>
	<th class="yb" style="width:150px">型番</th>
	<th class="yb" style="width:150px">価格（税込）</th></tr>
<tr><th class="yb">1</th>
	<td>__wOP1Kataban__</td>
	<td>￥__wOPPrice1__</td>
</tr>

<tr><th class="yb">2</th>
	<td>__wOP2Kataban__</td>
	<td>￥__wOPPrice2__</td>
</tr>

<tr><th class="yb">3</th>
	<td>__wOP3Kataban__</td>
	<td>￥__wOPPrice3__</td>
</tr>

<tr><th class="yb">4</th>
	<td>__wOP4Kataban__</td>
	<td>￥__wOPPrice4__</td>
</tr>

<tr><th class="yb">5</th>
	<td>__wOP5Kataban__</td>
	<td>￥__wOPPrice5__</td>
</tr>

<tr><th class="yb">6</th>
	<td>__wOP6Kataban__</td>
	<td>￥__wOPPrice6__</td>
</tr>

<tr><th class="yb">7</th>
	<td>__wOP7Kataban__</td>
	<td>￥__wOPPrice7__</td>
</tr>

<tr><th class="yb">8</th>
	<td>__wOP8Kataban__</td>
	<td>￥__wOPPrice8__</td>
</tr>

<tr><th class="yb">9</th>
	<td>__wOP9Kataban__</td>
	<td>￥__wOPPrice9__</td>
</tr>

<tr><th class="yb">10</th>
	<td>__wOP10Kataban__</td>
	<td>￥__wOPPrice10__</td>
</tr>
<tr><th class="yb">備考</th>
	<td colspan="2"><textarea name="wOPBiko" rows="3" style="width:100%;" >__wOPBiko__</textarea></td>
	</td>
</tr>

</table>
<!--
<table class="table table-bordered table-sm" style="width: 800px">
<tr><th class="yb" style="width:50px">No</th>
	<th class="yb" style="width:150px">型番</th>
	<th class="yb" style="width:450px">備考</th>
	<th class="yb" style="width:150px">価格（税込）</th></tr>
<tr><th class="yb">1</th>
	<td>__wOP1Kataban__</td><td>
		<input type="text" name="wOPBiko1" value="__wOPBiko1__" style="width:500px" placeholder="備考">
	</td>
	<td>￥__wOPPrice1__</td>
</tr>

<tr><th class="yb">2</th>
	<td>__wOP2Kataban__</td><td>		<input type="text" name="wOPBiko2" value="__wOPBiko2__" style="width:500px" placeholder="備考">
	</td>
	<td>￥<input type="text" name="wOPPrice2" value="__wOPPrice2__" style="width:80px; ime-mode: disabled;"></td>
</tr>

<tr><th class="yb">3</th>
	<td>__wOP3Kataban__</td><td>		<input type="text" name="wOPBiko3" value="__wOPBiko3__" style="width:500px" placeholder="備考">
	</td>
	<td>￥<input type="text" name="wOPPrice3" value="__wOPPrice3__" style="width:80px; ime-mode: disabled;"></td>
</tr>

<tr><th class="yb">4</th>
	<td>__wOP4Kataban__</td><td>		<input type="text" name="wOPBiko4" value="__wOPBiko4__" style="width:500px" placeholder="備考">
	</td>
	<td>￥<input type="text" name="wOPPrice4" value="__wOPPrice4__" style="width:80px; ime-mode: disabled;"></td>
</tr>

<tr><th class="yb">5</th>
	<td>__wOP5Kataban__</td><td>		<input type="text" name="wOPBiko5" value="__wOPBiko5__" style="width:500px" placeholder="備考">
	</td>
	<td>￥<input type="text" name="wOPPrice5" value="__wOPPrice5__" style="width:80px; ime-mode: disabled;"></td>
</tr>

<tr><th class="yb">6</th>
	<td>__wOP6Kataban__</td><td>		<input type="text" name="wOPBiko6" value="__wOPBiko6__" style="width:500px" placeholder="備考">
	</td>
	<td>￥<input type="text" name="wOPPrice6" value="__wOPPrice6__" style="width:80px; ime-mode: disabled;"></td>
</tr>

<tr><th class="yb">7</th>
	<td>__wOP7Kataban__</td><td>		<input type="text" name="wOPBiko7" value="__wOPBiko7__" style="width:500px" placeholder="備考">
	</td>
	<td>￥<input type="text" name="wOPPrice7" value="__wOPPrice7__" style="width:80px; ime-mode: disabled;"></td>
</tr>

<tr><th class="yb">8</th>
	<td>__wOP8Kataban__</td><td>		<input type="text" name="wOPBiko8" value="__wOPBiko8__" style="width:500px" placeholder="備考">
	</td>
	<td>￥<input type="text" name="wOPPrice8" value="__wOPPrice8__" style="width:80px; ime-mode: disabled;"></td>
</tr>

<tr><th class="yb">9</th>
	<td>__wOP9Kataban__</td><td>		<input type="text" name="wOPBiko9" value="__wOPBiko9__" style="width:500px" placeholder="備考">
	</td>
	<td>￥<input type="text" name="wOPPrice9" value="__wOPPrice9__" style="width:80px; ime-mode: disabled;"></td>
</tr>

<tr><th class="yb">10</th>
	<td>__wOP10Kataban__</td><td>		<input type="text" name="wOPBiko10" value="__wOPBiko10__" style="width:500px" placeholder="備考">
	</td>
	<td>￥<input type="text" name="wOPPrice10" value="__wOPPrice10__" style="width:80px; ime-mode: disabled;"></td>
</tr>
</table>
-->
<br>

<br>

◆６．インターホン設備更新工事　使用部材
<table class="table table-bordered table-sm">
<tr><th class="yb">親機</th>
	<td><input type="radio" name="wSiyobuzaiOyaFlg" value="0" __SiyobuzaiOyaFlgChecked0__ onclick="clickwSiyobuzaiOya();" >有　
		<input type="radio" name="wSiyobuzaiOyaFlg" value="1" __SiyobuzaiOyaFlgChecked1__ onclick="clickwSiyobuzaiOya();" >無
	</td>
	<th class="yb"> <span  id="SiyobuzaiOyaFont">パネル<br>（備考）</span></th>
	<td><select name="wOyaPanel" >
		<option value=""  >その他</option>
		__PanelDeviceLoop__<option value="__PanelDeviceCD__" __OyaPanelDeviceCDSelected__ >__PanelDeviceName__</option>
		__PanelDeviceLoop__
		</select>
		<br><input type="text" name="wSiyobuzaiOyaBiko" value="__wSiyobuzaiOyaBiko__" style="width:200px" placeholder="パネル親機（備考）">
	</td>
</tr>
<tr><th class="yb">玄関子機</th>
	<td><input type="radio" name="wSiyobuzaiKoFlg" value="0" __SiyobuzaiKoFlgChecked0__ onclick="clickwSiyobuzaiKo();" >有　
		<input type="radio" name="wSiyobuzaiKoFlg" value="1" __SiyobuzaiKoFlgChecked1__ onclick="clickwSiyobuzaiKo();" >無
	</td>
	<th class="yb"> <span id="SiyobuzaiKoFont">パネル<br>（備考）</span></th>
	<td><select name="wKoPanel" >
		<option value=""  >その他</option>
		__PanelDeviceLoop__<option value="__PanelDeviceCD__" __KoPanelDeviceCDSelected__ >__PanelDeviceName__</option>
		__PanelDeviceLoop__
		</select>
		<br><input type="text" name="wSiyobuzaiKoBiko" value="__wSiyobuzaiKoBiko__" style="width:200px" placeholder="パネル子機（備考）">
	</td>
</tr>
<tr><th class="yb">集合玄関機</th>
	<td><input type="radio" name="wSiyobuzaiSyugenFlg" value="0" __SiyobuzaiSyugenFlgChecked0__ onclick="clickwSiyobuzaiSyugen();" >有　
		<input type="radio" name="wSiyobuzaiSyugenFlg" value="1" __SiyobuzaiSyugenFlgChecked1__ onclick="clickwSiyobuzaiSyugen();" >無
	</td>
	<td colspan="2"><input type="text" name="wSiyobuzaiSyugenBiko" value="__wSiyobuzaiSyugenBiko__" style="width:400px" placeholder="集合玄関機（備考）"></td>
</tr>
<tr><th class="yb">管理室親機</th>
	<td><input type="radio" name="wSiyobuzaiKanOyaFlg" value="0" __SiyobuzaiKanOyaFlgChecked0__ onclick="clickwSiyobuzaiKanOya();" >有　
		<input type="radio" name="wSiyobuzaiKanOyaFlg" value="1" __SiyobuzaiKanOyaFlgChecked1__ onclick="clickwSiyobuzaiKanOya();" >無
	</td>
	<td colspan="2"><input type="text" name="wSiyobuzaiKanOyaBiko" value="__wSiyobuzaiKanOyaBiko__" style="width:400px" placeholder="管理室親機（備考）"></td>
</tr>
<tr><th class="yb">制御装置</th>
	<td><input type="radio" name="wSiyobuzaiSeigyoFlg" value="0" __SiyobuzaiSeigyoFlgChecked0__ onclick="clickwSiyobuzaiSeigyoFlg();" >有　
		<input type="radio" name="wSiyobuzaiSeigyoFlg" value="1" __SiyobuzaiSeigyoFlgChecked1__ onclick="clickwSiyobuzaiSeigyoFlg();" >無
	</td>
	<td colspan="2"><input type="text" name="wSiyobuzaiSeigyoBiko" value="__wSiyobuzaiSeigyoBiko__" style="width:400px" placeholder="制御装置（備考）"></td>
</tr>

</table>
<br>

◆７．施工写真
<table class="table table-bordered table-sm">
<tr><th colspan="2" class="yb">共用部機器</th>
	<td><input type="radio" name="wSekoKyoyoKikiPicFlg" value="1" __SekoKyoyoKikiPicFlgChecked1__ >撮る　
		<!--<input type="radio" name="wSekoKyoyoKikiPicFlg" value="2" __SekoKyoyoKikiPicFlgChecked2__ >抜粋-->
		<input type="radio" name="wSekoKyoyoKikiPicFlg" value="3" __SekoKyoyoKikiPicFlgChecked3__ >不要

	</td><td colspan="1" class="yb">撮る場合は</td><td colspan="6">
		<input type="radio" name="wSekoKyoyoKikiPic" value="1" __SekoKyoyoKikiPicChecked1__ >前・中・後　
		<input type="radio" name="wSekoKyoyoKikiPic" value="2" __SekoKyoyoKikiPicChecked2__ >前・後
	</tr>
<tr><th colspan="2" class="yb">専有部機器</th>
	<td><input type="radio" name="wSekoSenyuKikiPicFlg" value="1" __SekoSenyuKikiPicFlgChecked1__ >撮る　
		<input type="radio" name="wSekoSenyuKikiPicFlg" value="2" __SekoSenyuKikiPicFlgChecked2__ >抜粋　
		<input type="radio" name="wSekoSenyuKikiPicFlg" value="3" __SekoSenyuKikiPicFlgChecked3__ >不要

	</td><td colspan="1" class="yb">撮る場合は</td><td colspan="6">
		<input type="radio" name="wSekoSenyuKikiPic" value="1" __SekoSenyuKikiPicChecked1__ >前・中・後　
		<input type="radio" name="wSekoSenyuKikiPic" value="2" __SekoSenyuKikiPicChecked2__ >前・後
	</tr>

<tr><th colspan="2" class="yb">配線工事</th>

	<td><input type="radio" name="wSekoHaisenPicFlg" value="0" __SekoHaisenPicFlgChecked0__ >撮る　
		<!--<input type="radio" name="wSekoHaisenPicFlg" value="1" __SekoHaisenPicFlgChecked1__ >抜粋-->
		<input type="radio" name="wSekoHaisenPicFlg" value="2" __SekoHaisenPicFlgChecked2__ >不要

	</td><td colspan="1" class="yb">撮る場合は</td><td colspan="6">
		<input type="radio" name="wSekoHaisenPic" value="1" __SekoHaisenPicChecked1__ >前・中・後　
		<input type="radio" name="wSekoHaisenPic" value="0" __SekoHaisenPicChecked0__ >前・後
	</tr>

</tr>
</table>
<br>
<br>
◆８．宅配
<table class="table table-bordered table-sm">
<tr><th class="yb">宅配連動</th>
	<td colspan="3"><input type="radio" name="wTakuhai" value="0" __TakuhaiChecked0__ >有　
		<input type="radio" name="wTakuhai" value="1" __TakuhaiChecked1__ >無</td>
</tr>
<tr>
	<th class="yb">宅配会社</th>
	<td>
		<select name="wTakuhaiCompany" >
		<option value="">-</option>
		__TakuhaiLoop__<option value="__TakuhaiCD__" __TakuhaiSelected__ >__TakuhaiName__</option>__TakuhaiLoop__
		</select>
	</td>
	<th class="yb">宅配会社TEL</th>
	<td><input type="text" name="wTakuhaiCompanyTEL" value="__wTakuhaiCompanyTEL__" ></td>
</tr>
<tr>
	<th class="yb">宅配接続</th>
	<td colspan="3"><input type="text" name="wTakuhaiSetuzoku" value="__wTakuhaiSetuzoku__" ></td>
</tr>
</table>

<br>
◆９．設定
<table class="table table-bordered table-sm">
<tr><th class="yb" style="width:150px">仮暗証番号</th>
	<td>__AnshoNoStr__</td>
	<th class="yb" style="width:200px">住民様ご案内用暗証番号</th>
	<td>__wAnshoNoKojichu__</td></tr>
<tr><th class="yb">施工様用暗証番号</th>
	<td colspan="3">
		<input type="text" name="wAnshoNoBiko" value="__wAnshoNoBiko__" style="width:500px"></td>
</table>

<!--
◆９．暗証番号
<table class="table table-bordered table-sm">
<tr><th class="yb">暗証番号</th>
	<td><input type="radio" name="wAnshoNo" value="0" onchange="changeread();" __AnshoNoChecked0__ >有
		<input type="text" name="wAnshoNoBiko" id="number1" value="__wAnshoNoBiko__" style="width:100px">　
		<input type="radio" name="wAnshoNo" value="1" onchange="changeread();" __AnshoNoChecked1__ >無 </td>
</tr>
<tr><th class="yb">住民様ご案内用暗証番号</th>
	<td><input type="text" name="wAnshoNoKojichu" id="number2" value="__wAnshoNoKojichu__" placeholder="呼出ボタン→1→1→1→1" style="width:300px">
		<br><font color="gray">記入例）呼出ボタン→[1]→[1]→[1]→[1]
		<br>※工事案内資料に記載されます。</font></td>
</tr>
</table>
<br>

◆　パネル
<table class="table table-bordered table-sm">
<tr><td colspan="2" class="yb">親機パネル</td>
	<td>　<input type="radio" name="wOyaPanel" value="0" __OyaPanelChecked0__ >有
		　<input type="radio" name="wOyaPanel" value="1" __OyaPanelChecked1__ >無</td>
</tr>
<tr><td colspan="2" class="yb">子機パネル</td>
	<td>　<input type="radio" name="wKoPanel" value="0" __KoPanelChecked0__ >有
		　<input type="radio" name="wKoPanel" value="1" __KoPanelChecked1__ >無</td>
</tr>
<tr><td colspan="2" class="yb">パネル業者</td>
	</td><td colspan="7"><input type="text" name="wPanelGyosya" value="__wPanelGyosya__" style="width:500px"></td>
	</td>
</tr>
</table>

◆１０．工事前準備
<table class="table table-bordered table-sm">
<tr><th class="yb">部屋情報取得（ポスト撮影）</th>
	<td><input type="radio" name="wGetHeyaNoFlg" value="0" __GetHeyaNoFlgChecked0__ >有　
		<input type="radio" name="wGetHeyaNoFlg" value="1" __GetHeyaNoFlgChecked1__ >無</td>
</tr>
<tr><th class="yb">管理会社専用封筒手配</th>
	<td><input type="radio" name="wOrderKanriFutoFlg" value="0" __OrderKanriFutoFlgChecked0__ >有　
		<input type="radio" name="wOrderKanriFutoFlg" value="1" __OrderKanriFutoFlgChecked1__ >無</td>
</tr>
</table>

<br>
◆１０．入館方法
<table class="table table-bordered table-sm">
<tr><th colspan="2" class="yb">従前の入館方法</th>
	<td><input type="checkbox" name="wCurrentNyukan[]" value="0" __CurrentNyukanChecked0__ >鍵　
		<input type="checkbox" name="wCurrentNyukan[]" value="1" __CurrentNyukanChecked1__ >暗証番号　
		<input type="checkbox" name="wCurrentNyukan[]" value="2" __CurrentNyukanChecked2__ >ノンタッチタグ</td>
	<th colspan="2" class="yb">従前 その他方法</th>
	<td><input type="text" name="wCurrentNyukanSonota" value="__wCurrentNyukanSonota__" ></td>
</tr>
<tr><th colspan="2" class="yb">RN後の入館方法</th>
	<td><input type="checkbox" name="wRNNyukan[]" value="0" __RNNyukanChecked0__ >鍵　
		<input type="checkbox" name="wRNNyukan[]" value="1" __RNNyukanChecked1__ >暗証番号　
		<input type="checkbox" name="wRNNyukan[]" value="2" __RNNyukanChecked2__ >ノンタッチタグ</td>
	<th colspan="2" class="yb">RN後 その他方法</th>
	<td><input type="text" name="wRNNyukanSonota" value="__wRNNyukanSonota__" ></td>
</tr>
</table>

<br>
◆１２．施工業者情報
<table class="table table-bordered table-sm">
<tr><td bgcolor="lemonchiffon" colspan="4">
	<font size="2">※リストにない場合はマスタ登録から追加してください。</font></td></tr>

<tr><th class="yb">施工業者担当者１</th>
	<td colspan="3">
		<select name="wGyosyaTantoCD1">
		<option value="0">-</option>
		__GyosyaTantoLoop__
		<option value="__GyosyaTantoCD__" __GyosyaTantoCD1Selected__>__GyosyaName__ __GyosyaTantoName__</option>
		__GyosyaTantoLoop__
		</select></td></tr>
<tr><th class="yb">施工業者担当者２</th>
	<td colspan="3">
		<select name=wGyosyaTantoCD2 >
		<option value="0">-</option>
		__GyosyaTantoLoop__
		<option value="__GyosyaTantoCD__" __GyosyaTantoCD2Selected__ > __GyosyaName__ __GyosyaTantoName__</option>
		__GyosyaTantoLoop__
		</select></td></tr>
</table>
-->
<br>

<input type="button" value="工事指示を登録する" class="btn btn-primary" onclick="checkandmove()">
</form>
<br><br>


<hr>
<input type="button" value="メニューへもどる" onclick="javascript:move('./s_menu.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__' )"  class="btn btn-info"><br>

</div><!--content-all-->

__SFooter__
__SCopyright__

</body>
</html>

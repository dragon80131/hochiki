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
<script type="text/javascript" >

function initOnload(){

	if(__EXECINIT__){ 
		changewShiharaiConveni();
	}
	changeKirikaeHeiko();
	changeDispCompanyBottom();
}

function changeDispCompanyBottom(){

	if(document.getElementsByName("DispCompanyBottomChk1")[0].checked){
		document.getElementsByName("DispCompanyBottomTEL1")[0].disabled=false;
	}else{
		document.getElementsByName("DispCompanyBottomTEL1")[0].disabled=true;
	}
	
	if(document.getElementsByName("DispCompanyBottomChk2")[0].checked){
		document.getElementsByName("DispCompanyBottomTEL2")[0].disabled=false;
	}else{
		document.getElementsByName("DispCompanyBottomTEL2")[0].disabled=true;
	}

	if(document.getElementsByName("DispCompanyBottomChk3")[0].checked){
		document.getElementsByName("DispCompanyBottomTEL3")[0].disabled=false;	
		document.getElementsByName("DispCompanyBottom3")[0].disabled=false;	
	}else{
		document.getElementsByName("DispCompanyBottomTEL3")[0].disabled=true;
		document.getElementsByName("DispCompanyBottom3")[0].disabled=true;	
	}

}

function checkInput(){
	if (document.getElementsByName("wTagSuu")[0].value 
			< document.getElementsByName("wOwnerTagSuu")[0].value){
		alert("外部オーナー渡しのタグ本数は標準本数以内に設定してください");
		return false;
		
	}else{
		return true;
	}
}

function changewShiharaiConveni(){
	//コンビニが選択されていたら
	if(document.getElementsByName("wShiharai[]")[3].checked){
		//ラジオボタンを有効化
		document.getElementsByName("wShiharaiConveni")[0].disabled = false;
		document.getElementsByName("wShiharaiConveni")[1].disabled = false;
		document.getElementById("wShiharaiConveniFont").style.color = "black";
	}else{
		//ラジオボタンを無効化
		document.getElementsByName("wShiharaiConveni")[0].disabled = true;
		document.getElementsByName("wShiharaiConveni")[1].disabled = true;
		document.getElementById("wShiharaiConveniFont").style.color = "lightgray";
	}
}

function changeKirikaeHeiko(){
	//並行稼働が選択されていたら
	if(document.getElementsByName("wKirikaehoho")[1].checked){
		//ラジオボタンを有効化
		document.getElementsByName("wKirikaeHeikoEizoriyo")[0].disabled = false;
		document.getElementsByName("wKirikaeHeikoEizoriyo")[1].disabled = false;
		document.getElementById("KirikaeHeikoEizoriyoFont").style.color = "black";
	}else{
		//ラジオボタンを無効化
		document.getElementsByName("wKirikaeHeikoEizoriyo")[0].disabled = true;
		document.getElementsByName("wKirikaeHeikoEizoriyo")[1].disabled = true;
		document.getElementById("KirikaeHeikoEizoriyoFont").style.color = "lightgray";
	}
}

</script>

</head>

<body onload="initOnload();">
__SHeader__

<div class="content-all"><!--content-all-->

<div class="left-yose">
<a href="../s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜戻る</a>
</div>


<div class="top-menu left-yose">
<h5>__wBukkenName__</h5>
<h6>工事案内作成</h6>


<form action="s_koji_annai_Excel.php" method="POST" name="mainform" onSubmit="return checkInput()">
<input type="hidden" name="rKey" value="__rKey__" >
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__" >

<table class="table table-bordered table-sm"> 
<tr><td  bgcolor="lemonchiffon">マンション名</td>
	<td>__wBukkenName__</td></tr>
<tr><td  bgcolor="lemonchiffon">特例</td>
	<td>__ShoboTokureiDisp__</td></tr>
<tr><td  bgcolor="lemonchiffon">オプション</td>
	<td>__OPDisp__</td></tr>
<tr><td  bgcolor="lemonchiffon">営業所</td><td>__KojiShozokuName__</td></tr>
<tr><td  bgcolor="lemonchiffon">担当</td><td>__KojiTantoName__</td></tr>
<tr><td  bgcolor="lemonchiffon">電話</td><td>__TantoTEL__</td></tr>
<tr><td  bgcolor="lemonchiffon">管理会社</td><td>__KanriGaisya__</td></tr>
</table>
<br>

◆資料の記載事項（工事基本情報の補足）
<table class="table table-bordered table-sm">
<tr> 
	<td  bgcolor="lemonchiffon" >1ページ目右上に記載する会社<br><font size="2">※資料には上から順に出力されます</font></td>
	<td>
		<table  class="table-none" >
				<tr><td> ①　<input type="text" name="DispCompanyTop1" value="__DispCompanyTop1__" size="25" > </td></tr>
				<tr><td> ②　<input type="text" name="DispCompanyTop2" value="__DispCompanyTop2__" size="25" > </td></tr>
				<tr><td> ③　<input type="text" name="DispCompanyTop3" value="__DispCompanyTop3__" size="25" > </td></tr>
		</table> 
	</td>
</tr>
<tr>
	<td  bgcolor="lemonchiffon" >工事に関するお問い合わせ先に<br>記載する会社（資料下部）
	</td>
	
	<td>
		<table  class="table-none" >
			<tr>
				<td><input type="checkbox" name="DispCompanyBottomChk1" value="1" __DispCompanyBottomChecked1__ onclick="changeDispCompanyBottom();"></td>
				<td>管理会社</td>
				<td style="text-align: right;">TEL:　<input type="text" name="DispCompanyBottomTEL1" value="__DispCompanyBottomTEL1__"  size="25" ></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="DispCompanyBottomChk2" value="2" __DispCompanyChecked2__ onclick="changeDispCompanyBottom();"></td>
				<td colspan="2">アイホン株式会社　__KojiShozokuName__
				</td>
			</tr>
			<tr>
				<td colspan="2"></td>
				<td style="text-align: right;">
					TEL:　<input type="text" name="DispCompanyBottomTEL2" value="__DispCompanyBottomTEL2__"  size="25" >
				</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="DispCompanyBottomChk3" value="3" __DispCompanyChecked2__ onclick="changeDispCompanyBottom();"></td>
				<td colspan="2" style="text-align: right;">その他　　
					<input type="text" name="DispCompanyBottom3" value="__DispCompanyBottom3__" size="40" >
				</td>
			</tr>
			<tr>
				<td colspan="2"></td>
				<td style="text-align: right;">
					TEL:　<input type="text" name="DispCompanyBottomTEL3" value="__DispCompanyBottomTEL3__"  size="25" >
				</td>
			</tr>
			<tr>
				<td colspan="3">※電話番号を資料に記載する場合はTEL欄に入力してください</td>
			</tr>
		</table>
	</td>
</tr>
<tr><td bgcolor="lemonchiffon" >案内資料上の作業時間</td>
	<td><input type="text" name="wConstTime"  style="width:50px" value="__wConstTime__" >分</td></tr>
<tr><td bgcolor="lemonchiffon" >集合玄関機有無</td>
	<td>
		<input type="radio" name="wAutoLock" value="1" __AutoLockChecked1__ >有　
		<input type="radio" name="wAutoLock" value="2" __AutoLockChecked2__ >無
	</td></tr>
	
<tr><td bgcolor="lemonchiffon" >切替方法</td>
	<td>
		<input type="radio" name="wKirikaehoho" value="0" __KirikaehohoChecked0__ onClick="changeKirikaeHeiko();">停止　
		<input type="radio" name="wKirikaehoho" value="1" __KirikaehohoChecked1__ onClick="changeKirikaeHeiko();">並行稼働
			<span id="KirikaeHeikoEizoriyoFont" style="color:lightgray;">
				（ 既設映像幹線流用：<input type="radio" name="wKirikaeHeikoEizoriyo" value="1" __KirikaeHeikoEizoriyoChecked1__ >する　
				<input type="radio" name="wKirikaeHeikoEizoriyo" value="0" __KirikaeHeikoEizoriyoChecked0__ >しない ）
			</span>
	</td></tr>
<tr><td bgcolor="lemonchiffon" >幹線ルート</td>
	<td>
		<input type="radio" name="wKansenKoji" value="0" __KansenKojiChecked0__ >パイプシャフト渡り　
		<input type="radio" name="wKansenKoji" value="1" __KansenKojiChecked1__ >玄関子機渡り　
		<input type="radio" name="wKansenKoji" value="2" __KansenKojiChecked2__ >部屋渡り　
		<input type="radio" name="wKansenKoji" value="3" __KansenKojiChecked3__ >1:1
	</td></tr>

<tr><td bgcolor="lemonchiffon" >自火報連動</td>
	<td>
		<input type="radio" name="wJikaho" value="0" __JikahoChecked0__ >なし　
		<input type="radio" name="wJikaho" value="1" __JikahoChecked1__ >有り（専有部感知器 既設流用）
		<input type="radio" name="wJikaho" value="2" __JikahoChecked2__ >有り（専有部感知器 交換）
	</td></tr>
<tr><td bgcolor="lemonchiffon" >火災抵抗器交換部屋立入り</td>
	<td>
		<input type="radio" name="wKasaiHeya" value="0" __KasaiHeyaChecked0__ >なし　
		<input type="radio" name="wKasaiHeya" value="1" __KasaiHeyaChecked1__ >有り
	</td></tr>
<tr><td bgcolor="lemonchiffon" >ガス漏れ警報器連動</td>
	<td>
		<input type="radio" name="wGasKoji" value="0" __GasKojiChecked0__ >なし　
		<input type="radio" name="wGasKoji" value="1" __GasKojiChecked1__ >有り（既設流用）
		<input type="radio" name="wGasKoji" value="2" __GasKojiChecked2__ >有り（交換）
	</td></tr>
<tr><td bgcolor="lemonchiffon" >防犯センサー連動</td>
	<td>
		<input type="radio" name="wBohanKoji" value="0" __BohanKojiChecked0__ >なし　
		<input type="radio" name="wBohanKoji" value="1" __BohanKojiChecked1__ >１階住戸のみ　
		<input type="radio" name="wBohanKoji" value="3" __BohanKojiChecked3__> 設置住戸のみ　<!--未着手-->
		<input type="radio" name="wBohanKoji" value="2" __BohanKojiChecked2__ >全住戸
	</td></tr>
<tr><td bgcolor="lemonchiffon" >漏水センサー連動</td>
	<td>
		<input type="radio" name="wRosuiKoji" value="0" __RosuiKojiChecked0__ >なし　
		<input type="radio" name="wRosuiKoji" value="1" __RosuiKojiChecked1__ >有り（既設流用）
		<input type="radio" name="wRosuiKoji" value="2" __RosuiKojiChecked2__ >有り（交換）
	</td></tr>
<tr><td bgcolor="lemonchiffon" >宅配連動</td>
	<td>
		<input type="radio" name="wTakuhai" value="0" __TakuhaiChecked0__ >なし　
		<input type="radio" name="wTakuhai" value="1" __TakuhaiChecked1__ >有り
	</td></tr>
<tr><th colspan="2">リニューアルパネルの有無</th></tr>
<tr>
	<td bgcolor="lemonchiffon" >
		親機
	</td>
	<td>
		<input type="radio" name="wOyakiPanel" value="1" __OyakiPanelChecked1__>なし　
		<input type="radio" name="wOyakiPanel" value="0" __OyakiPanelChecked0__>有り　
	</td>
</tr>
<tr>
	<td bgcolor="lemonchiffon" >
		子機
	</td>
	<td>
		<input type="radio" name="wKokiPanel" value="1" __KokiPanelChecked1__>なし　
		<input type="radio" name="wKokiPanel" value="0" __KokiPanelChecked0__>有り　
	</td>
</tr>

<tr><th colspan="2">__noOPSetFontColor__オプションありの場合__noOPspanEnd__</th></tr>
<tr><td bgcolor="lemonchiffon" >__noOPSetFontColor__オプション支払い方法__noOPspanEnd__</td>
	<td>
		__noOPSetFontColor__
			<input type="checkbox" name="wShiharai[]" value="現金" __ShiharaiSelected1__ __noOPSetDisabled__>現金　
			<input type="checkbox" name="wShiharai[]" value="振込" __ShiharaiSelected2__ __noOPSetDisabled__>振込　
			<input type="checkbox" name="wShiharai[]" value="NP" __ShiharaiSelected3__ __noOPSetDisabled__>コンビニ・郵便局・銀行(NP後払い）　
			<br><input type="checkbox" name="wShiharai[]" value="コンビニ" __ShiharaiSelected4__ onchange="changewShiharaiConveni();" __noOPSetDisabled__>コンビニ払込票支払
				<span id="wShiharaiConveniFont" style="color:lightgray;">
					( <input type="radio" name="wShiharaiConveni" value="0" __ShiharaiConveni0__ __noOPSetDisabled__>通常　
					<input type="radio" name="wShiharaiConveni" value="1" __ShiharaiConveni1__ __noOPSetDisabled__>上限54000円税込　）
				</span>
		__noOPspanEnd__
	</td></tr>
<tr><td bgcolor="lemonchiffon" >__noOPSetFontColor__オプション受付方法__noOPspanEnd__</td>
	<td>
		__noOPSetFontColor__
			<input type="radio" name="wOPUketuke" value="0" __OPUketukeChecked0__ __noOPSetDisabled__>日程受付と同じフリーダイヤル　
			<input type="radio" name="wOPUketuke" value="2" __OPUketukeChecked2__ __noOPSetDisabled__>フリーダイヤルとWEB受付　<!--未着手--><br>
			<input type="radio" name="wOPUketuke" value="1" __OPUketukeChecked1__ __noOPSetDisabled__>アンケート　
		__noOPspanEnd__
	</td></tr>

<tr><th colspan="2">__noTagSetFontColor__タグ　について（ノンタッチ導入の場合有効）__noTaSpanEnd__</th></tr>
<tr><td bgcolor="lemonchiffon" >__noTagSetFontColor__タグ本数__noTaSpanEnd__</td>
	<td>
		__noTagSetFontColor__標準<input type="text" name="wTagSuu"  style="width:50px" value="__wTagSuu__" __noTagSetDisabled__>本渡し
		　外部オーナー<input type="text" name="wOwnerTagSuu"  style="width:50px" value="__wOwnerTagSuu__" __noTagSetDisabled__>本渡し__noTaSpanEnd__
	</td></tr>
<tr><td bgcolor="lemonchiffon" >__noTagSetFontColor__切替タイミング__noTaSpanEnd__</td>
	<td>__noTagSetFontColor__
		<input type="radio" name="wTagKirikae" value="1" __TagKirikaeChecked1__ __noTagSetDisabled__>着工日　
		<input type="radio" name="wTagKirikae" value="2" __TagKirikaeChecked2__  __noTagSetDisabled__>工事終了日
	__noTaSpanEnd__</td>
</tr>

<table>

<br>
作成する案内状の種類を選択してください。<br>
<input type="radio" name="wAnnaijyoType" value="0" checked>通常版
<input type="radio" name="wAnnaijyoType" value="1" >簡易版
<br><br>

<input type="submit" value="登録＆ファイル出力" class="btn btn-primary"　>

<!--
<br><br>
<div style="border:#ff0000 solid 1px;">
2018/12/26 開発メモ<br>
オートロックなしの場合、オートロックなし用のフォーマット利用<br>
　通常版・簡易版の選択は無視<br><br>

オートロックありの場合、通常版・簡易版の選択によってフォーマットがかわる<br>
【通常版】<br>
　自火報交換ありなし<br>
　特例220かどうか<br>
　平行稼働ありか<br>
　各種センサー交換ありなし<br>
　オプションありなし<br><br>

【簡易版】<br>
　自火報交換ありなし<br>
　オプションありなし<br>
</div>-->


</form>

</div>






</div><!--content-all-->


__SFooter__
__SCopyright__

</body>
</html>

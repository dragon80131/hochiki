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
	changeDispCompanyBottom3();
	changeShiteiVest();
}

function changeDispCompanyBottom(){

	if(document.getElementsByName("DispCompanyBottomChk1")[0].checked){
		document.getElementById("DispCompanyBottom1Font").style.color = "black";
		document.getElementsByName("DispCompanyBottom1FLG")[0].disabled=false;
	}else{
		document.getElementById("DispCompanyBottom1Font").style.color = "lightgray";
		document.getElementsByName("DispCompanyBottom1FLG")[0].disabled=true;
	}

	if(document.getElementsByName("DispCompanyBottomChk2")[0].checked){
		document.getElementById("DispCompanyBottom2Font").style.color = "black";
		document.getElementsByName("DispCompanyBottom2FLG")[0].disabled=false;
	}else{
		document.getElementById("DispCompanyBottom2Font").style.color = "lightgray";
		document.getElementsByName("DispCompanyBottom2FLG")[0].disabled=true;
	}

	if(document.getElementsByName("DispCompanyBottomChk3")[0].checked){
		document.getElementById("DispCompanyBottom3Font").style.color = "black";
		document.getElementsByName("DispCompanyBottom3FLG")[0].disabled=false;
		document.getElementsByName("DispCompanyBottom3")[0].disabled=false;
		document.getElementsByName("DispCompanyBottomTEL3")[0].disabled=false;

		changeDispCompanyBottom3();


	}else{
		document.getElementById("DispCompanyBottom3Font").style.color = "lightgray";
		document.getElementsByName("DispCompanyBottom3FLG")[0].disabled=true;
		document.getElementsByName("DispCompanyBottom3")[0].disabled=true;
		document.getElementsByName("DispCompanyBottomTEL3")[0].disabled=true;

	}

}

function changeDispCompanyBottom3(){
	if(document.getElementsByName("DispCompanyBottom3FLG")[0].checked){
		document.getElementsByName("DispCompanyBottomTEL3")[0].disabled=false;
	}else{
		document.getElementsByName("DispCompanyBottomTEL3")[0].disabled=true;
	}
}

//submit前の入力チェック
function checkInput(){

	flg = true;;

	if(__EXCITETag__){
		//タグ本数
		if (document.getElementsByName("wTagSuu")[0].value
				< document.getElementsByName("wOwnerTagSuu")[0].value){
			alert("外部オーナー渡しのタグ本数は標準本数以内に設定してください");
			return false;

		}else{
			flg = true;
		}
	}
	//工事に関するお問い合わせ先
/*	if(document.getElementsByName("DispCompanyBottomChk1")[0].checked
		|| document.getElementsByName("DispCompanyBottomChk2")[0].checked
			|| document.getElementsByName("DispCompanyBottomChk3")[0].checked){
		flg = true;
	}else{
		alert("工事に関するお問い合わせ先を一つ以上選択してください");
		return false;
	}
*/

	//問合せ先　その他を表示するにチェックがある場合
	if(document.getElementsByName("DispCompanyBottomChk3")[0].checked){
		alertStr = "工事に関するお問い合わせ先\nその他:";
		//会社名に入力があるかチェック
		if(document.getElementsByName("DispCompanyBottom3")[0].value == ""){
			alertStr += " <会社名> ";
			flg = false;
		}

		//電話番号がONなら　入力があるかチェック
		if(document.getElementsByName("DispCompanyBottom3FLG")[0].checked){
			if(document.getElementsByName("DispCompanyBottomTEL3")[0].value == ""){
				alertStr += " <電話番号> ";
				flg = false;
			}
		}
		if (flg == false){
			alert(alertStr+" を入力して下さい");
			return false;
		}
	}

	if(__EXECINIT__){
		//オプション支払い方法
		if(document.getElementsByName("wShiharai[]")[0].checked
			|| document.getElementsByName("wShiharai[]")[1].checked
				|| document.getElementsByName("wShiharai[]")[2].checked
					||  document.getElementsByName("wShiharai[]")[3].checked){
			flg = true;
		}else{
			alert("オプション支払い方法は一つ以上を選択してください");
			return false;
		}
	}

	if(document.getElementsByName("wSagyoin[]")[0].checked ||
		document.getElementsByName("wSagyoin[]")[1].checked){
		flg = true;
	}else{
		alert("作業員の着用するものは一つ以上を選択してください");
		return false;
	}
	
	return true;
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

function changeShiteiVest(){
	if(document.getElementsByName("wShiteiVest")[0].selectedIndex == 8){
		document.getElementsByName("ShiteiVestOther")[0].disabled = false;
		document.getElementsByName("ShiteiVestOther")[0].value="__ShiteiVestOther__";
		document.getElementById("ShiteiVestOtherFont").style.color = "black";
	}else{
		document.getElementsByName("ShiteiVestOther")[0].disabled = true;
		document.getElementsByName("ShiteiVestOther")[0].value="";
		document.getElementById("ShiteiVestOtherFont").style.color = "lightgray";
	
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


<form action="s_koji_annai_word.php" method="POST" name="mainform" onSubmit="return checkInput()">
<input type="hidden" name="rKey" value="__rKey__" >
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__" >


<table class="table table-bordered table-sm">
<tr><td  bgcolor="lemonchiffon">マンション名</td>
	<td>__wBukkenName__</td></tr>
<tr><td  bgcolor="lemonchiffon">消防特例</td>
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
	<span id="DispCompanyBottomErr"></span>
	</td>

	<td>
		<table  class="table-none" >
			<tr>
				<td><input type="checkbox" name="DispCompanyBottomChk1" value="1" __DispCompanyChecked1__ onclick="changeDispCompanyBottom();"></td>
				<td>管理会社</td>
				<!--<td style="text-align: right;">TEL:　<input type="text" name="DispCompanyBottomTEL1" value="__DispCompanyBottomTEL1__"  size="25" ></td>-->

					<td><span id="DispCompanyBottom1Font" style="color:lightgray;">（　電話番号を記載　
						<input type="radio" name="DispCompanyBottom1FLG" value="1" __DispCompanyBottom1FLGchecked1__ >する　
						<input type="radio" name="DispCompanyBottom1FLG" value="0" __DispCompanyBottom1FLGchecked0__ >しない　
					）</span></td>

			</tr>
			<tr>
				<td><input type="checkbox" name="DispCompanyBottomChk2" value="1" __DispCompanyChecked2__ onclick="changeDispCompanyBottom();"></td>
				<td colspan="2">アイホン株式会社　__KojiShozokuName__
				</td>
			</tr>
			<tr>
				<td colspan="2"></td>
				<td><span id="DispCompanyBottom2Font" style="color:lightgray;">（　電話番号を記載　
					<input type="radio" name="DispCompanyBottom2FLG" value="1" __DispCompanyBottom2FLGchecked1__ >する　
					<input type="radio" name="DispCompanyBottom2FLG" value="0" __DispCompanyBottom2FLGchecked0__ >しない　
				）</span></td>


				<!--<td style="text-align: right;">
					TEL:　<input type="text" name="DispCompanyBottomTEL2" value="__DispCompanyBottomTEL2__"  size="25" >
				</td>-->
			</tr>
			<tr>
				<td><input type="checkbox" name="DispCompanyBottomChk3" value="1" __DispCompanyChecked3__ onclick="changeDispCompanyBottom();"></td>
				<td >その他　　</td>
				<td><input type="text" name="DispCompanyBottom3" value="__DispCompanyBottom3__" style="width:400px" >
				</td>
			</tr>
			<tr>
				<td colspan="3"><span id="DispCompanyBottom3Font" style="color:lightgray;">（電話番号を記載　
					<input type="radio" name="DispCompanyBottom3FLG" value="1" __DispCompanyBottom3FLGchecked1__ onChange="changeDispCompanyBottom3();">する
					TEL:<input type="text" name="DispCompanyBottomTEL3" value="__DispCompanyBottomTEL3__"  style="width:150px" >　
					<input type="radio" name="DispCompanyBottom3FLG" value="0" __DispCompanyBottom3FLGchecked0__  onChange="changeDispCompanyBottom3();">しない　
				）</span></td>
			</tr>
		</table>
	</td>
</tr>
<tr><td bgcolor="lemonchiffon" >作業員<br><font size="2">（着用するものを選択してください）</font></td>
	<td>
		<input type="checkbox" name="wSagyoin[]" value="ベスト" __wSagyoinChecked1__ >ベスト　
		<input type="checkbox" name="wSagyoin[]" value="腕章" __wSagyoinChecked2__ >腕章　　
	</td>
</tr>


<tr><td bgcolor="lemonchiffon" >施工主体指定の着用着<br><font size="2"></font></td>
	<td>
<select name="wShiteiVest" onChange="changeShiteiVest();">
__ShiteiVestLoop__
<option value="__ShiteiVestCD__" __SelectedShiteiVest__ >__ShiteiVest__</option>
__ShiteiVestLoop__
</select>
<br>
	<span id="ShiteiVestOtherFont" style="color:lightgray;">
		<font size="2">　その他の場合は組織名を入力してください　</font><input type="text" name="ShiteiVestOther" value="__ShiteiVestOther__" size="25" >
	</span>

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
<tr><th colspan="2">添付する予定・確定案内について</th></tr>
<tr>
	<td bgcolor="lemonchiffon">★専有部工事日時変更受付方法</td>
	<td>
		<select name="wAnswer"  style="width:300px;background-color:#FFF0F5;" id="wAnswer"  >
			<option value="">-</option>
			<option value="A.日時変更住戸のみ返答" __Answer1Selected__>A.日時変更住戸のみ返答</option>
			<option value="B.全住戸返答" __Answer2Selected__>B.全住戸返答</option>
			<option value="C.全住戸返答+確定時未返事シート" __Answer3Selected__>C.全住戸返答+確定時未返事シート</option>
		</select><br>
		<font color="red"><span id="AnswerError"></span></font>
	</td>
</tr>
<tr><td bgcolor="lemonchiffon">WEB受付</td>
	<td bgcolor="#FFF0F5">
		<input type="radio" name="wWEBRecept" value="1" checked __WEBRecept1Checked__ >有　
		<input type="radio" name="wWEBRecept" value="0" __WEBRecept0Checked__  >無　
	</td>
</tr>
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
<!--<input type="button" value="登録＆ファイル出力" onclick="ChechAndMove('./s_koji_annai_Excel.php');" class="btn btn-primary">-->

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





<hr>
<input type="button" value="メニューへもどる" onclick="javascript:move('../s_menu.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__' )"  class="btn btn-info"><br>


</div><!--content-all-->


__SFooter__
__SCopyright__

</body>
</html>

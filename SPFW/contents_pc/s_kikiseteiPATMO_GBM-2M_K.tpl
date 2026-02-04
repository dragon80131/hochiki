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

<style>
	.center_yose{
	    text-align: center;
	}
</style>

<script type="text/javascript" src="tools.js"></script>
<link href="css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.4.2.js" type="text/javascript"></script>
<!--<script src="js/jquery.ui.core.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
<script src="js/jquery.numberPicker.js" type="text/javascript"></script>-->
<script type="text/javascript">

function resetcheck(val,Volume ,max ,name) {
	
	if(Volume==0&&document.getElementById(name).checked){
		for (i = 1; i < max; i++) {
				document.getElementById(val+'val'+i).checked = false;
				document.getElementById("iro" + val +"val" + i).style.backgroundColor = "";
		 }
	}
	if(Volume!==0){
		document.getElementById(val+"val"+0).checked = false;
	}
}

function changecolor(val, Volume, max, initial, min ){

	max = min+max;

	for( i = min ; i < max ; i++){
		if(i !== initial){
			if(i == Volume){
				document.getElementById( "iro" + val +"val" + i ).style.backgroundColor = "#ffbab3";
			}else{
				document.getElementById( "iro" + val +"val" + i ).style.backgroundColor = "";
			}
		}
	}
}

function changeBGcolor(val, Volume, max, initial, name, min){

	if(Volume !== initial){
		if(document.getElementById(name).checked )
			document.getElementById( "iro" + val +"val" + Volume).style.backgroundColor = "#ffbab3";
		else
			document.getElementById( "iro" + val +"val" + Volume).style.backgroundColor = "";
	}
	
}



function changeread(order){
	if(order==30){
		if(document.form1.wOyakiSettei30[1].checked){
			document.getElementById("number1").disabled = false;
		}else{
			document.getElementById("number1").disabled = true;
		}
	}
	if(order==32){
		if(document.form1.wOyakiSettei32[1].checked){
			document.getElementById("number2").disabled = false;
		}else{
			document.getElementById("number2").disabled = true;
		}
	}
}
</script>


<script type="text/javascript" src="js/tools_ajax.js"></script>
<script type="text/javascript" src="js/ConnectedSelect.js"></script>

</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
<form name="form1" method="POST" action="./doc/s_kikiseteiPATMO_GBM-2M_K_finish.php?rKey=__rKey__" >
<h6>PATMO・GBM-2M（K）</h6>

<a href="s_kikisetei.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜戻る</a>
<br>
<br>


◆<a href="s_kikiseteiPATMO_GBM-2M_K_copy.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">過去の案件の機器情報をコピーする</a>




<br>
<br>
◆設定<span style="background-color:#A9F5A9">　　　</span>は初期設定値
<br>
◆設定<span style="background-color:#ffbab3">　　　</span>は変更値
<br><br>

◆　施工設定（集合玄関機設定指示書）
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
<table  border="1">
<tr>
<td bgcolor="#ebeeef" rowspan="5">　音の設定　
	<tr><td bgcolor="#ebeeef">　①⇒呼出　</td>
		<td bgcolor="#ebeeef">受話音量</td>
		<td colspan="4">
				　<input type="radio" name="wShugoSettei1" value="0" onchange="changecolor(1, '0', 2 ,__wShugoSettei1__ ,0);" __wShugoSettei1Checked0__><span id="iro1val0" __wShugoSettei1BG0__>　1：あり　</span>
				　<input type="radio" name="wShugoSettei1" value="1" onchange="changecolor(1, '1', 2 ,__wShugoSettei1__ ,0);" __wShugoSettei1Checked1__><span id="iro1val1" __wShugoSettei1BG1__>　2：なし　</span>
		</td>
	</tr>
	<tr><td bgcolor="#ebeeef">　②⇒呼出　</td>
		<td bgcolor="#ebeeef">電気錠プリトーン回数</td>
		<td colspan="4">
				　<input type="radio" name="wShugoSettei2" value="0" onchange="changecolor(2, 0, 5,__wShugoSettei2__ ,0);" __wShugoSettei2Checked0__><span id="iro2val0" __wShugoSettei2BG0__>　0：0回　</span>
				　<input type="radio" name="wShugoSettei2" value="1" onchange="changecolor(2, 1, 5,__wShugoSettei2__ ,0);" __wShugoSettei2Checked1__><span id="iro2val1" __wShugoSettei2BG1__>　1：1回　</span>
				　<input type="radio" name="wShugoSettei2" value="2" onchange="changecolor(2, 2, 5,__wShugoSettei2__ ,0);" __wShugoSettei2Checked2__><span id="iro2val2" __wShugoSettei2BG2__>　2：2回　</span>
				　<input type="radio" name="wShugoSettei2" value="3" onchange="changecolor(2, 3, 5,__wShugoSettei2__ ,0);" __wShugoSettei2Checked3__><span id="iro2val3" __wShugoSettei2BG3__>　3：3回　</span>
				　<input type="radio" name="wShugoSettei2" value="4" onchange="changecolor(2, 4, 5,__wShugoSettei2__ ,0);" __wShugoSettei2Checked4__><span id="iro2val4" __wShugoSettei2BG4__>　4：4回　</span>
		</td>
	</tr>
	<tr><td bgcolor="#ebeeef">　③⇒呼出　</td>
		<td bgcolor="#ebeeef">着荷時プリトーン回数</td>
		<td colspan="4">
				　<input type="radio" name="wShugoSettei3" value="0" onchange="changecolor(3, 0, 5,__wShugoSettei3__ ,0);" __wShugoSettei3Checked0__><span id="iro3val0" __wShugoSettei3BG0__>　0：0回　</span>
				　<input type="radio" name="wShugoSettei3" value="1" onchange="changecolor(3, 1, 5,__wShugoSettei3__ ,0);" __wShugoSettei3Checked1__><span id="iro3val1" __wShugoSettei3BG1__>　1：1回　</span>
				　<input type="radio" name="wShugoSettei3" value="2" onchange="changecolor(3, 2, 5,__wShugoSettei3__ ,0);" __wShugoSettei3Checked2__><span id="iro3val2" __wShugoSettei3BG2__>　2：2回　</span>
				　<input type="radio" name="wShugoSettei3" value="3" onchange="changecolor(3, 3, 5,__wShugoSettei3__ ,0);" __wShugoSettei3Checked3__><span id="iro3val3" __wShugoSettei3BG3__>　3：3回　</span>
				　<input type="radio" name="wShugoSettei3" value="4" onchange="changecolor(3, 4, 5,__wShugoSettei3__ ,0);" __wShugoSettei3Checked4__><span id="iro3val4" __wShugoSettei3BG4__>　4：4回　</span>
		</td>
	</tr>
	<tr><td bgcolor="#ebeeef">　④⇒呼出　</td>
		<td bgcolor="#ebeeef">プリトーン音量</td>
		<td colspan="4">
				　<input type="radio" name="wShugoSettei4" value="0" onchange="changecolor(4, 0, 8 ,__wShugoSettei4__ ,0);" __wShugoSettei4Checked0__><span id="iro4val0" __wShugoSettei4BG0__>　1：1　</span>
				　<input type="radio" name="wShugoSettei4" value="1" onchange="changecolor(4, 1, 8 ,__wShugoSettei4__ ,0);" __wShugoSettei4Checked1__><span id="iro4val1" __wShugoSettei4BG1__>　2：2　</span>
				　<input type="radio" name="wShugoSettei4" value="2" onchange="changecolor(4, 2, 8 ,__wShugoSettei4__ ,0);" __wShugoSettei4Checked2__><span id="iro4val2" __wShugoSettei4BG2__>　3：3　</span>
				　<input type="radio" name="wShugoSettei4" value="3" onchange="changecolor(4, 3, 8 ,__wShugoSettei4__ ,0);" __wShugoSettei4Checked3__><span id="iro4val3" __wShugoSettei4BG3__>　4：4　</span>
			<br>　<input type="radio" name="wShugoSettei4" value="4" onchange="changecolor(4, 4, 8 ,__wShugoSettei4__ ,0);" __wShugoSettei4Checked4__><span id="iro4val4" __wShugoSettei4BG4__>　5：5　</span>
				　<input type="radio" name="wShugoSettei4" value="5" onchange="changecolor(4, 5, 8 ,__wShugoSettei4__ ,0);" __wShugoSettei4Checked5__><span id="iro4val5" __wShugoSettei4BG5__>　6：6　</span>
				　<input type="radio" name="wShugoSettei4" value="6" onchange="changecolor(4, 6, 8 ,__wShugoSettei4__ ,0);" __wShugoSettei4Checked6__><span id="iro4val6" __wShugoSettei4BG6__>　7：7　</span>
				　<input type="radio" name="wShugoSettei4" value="7" onchange="changecolor(4, 7, 8 ,__wShugoSettei4__ ,0);" __wShugoSettei4Checked7__><span id="iro4val7" __wShugoSettei4BG7__>　8：8　</span>
		</td>
	</tr>

</td>
</tr>

<tr>
<td bgcolor="#ebeeef" rowspan="3">　表示の設定　
	<tr><td bgcolor="#ebeeef">　①⇒呼出　</td>
		<td bgcolor="#ebeeef">着荷表示時間</td>
		<td colspan="4">
				　<input type="radio" name="wShugoSettei5" value="0" onchange="changecolor(5, 0, 5 ,__wShugoSettei5__ ,0);" __wShugoSettei5Checked0__><span id="iro5val0" __wShugoSettei5BG0__>　1：5秒　</span>
				　<input type="radio" name="wShugoSettei5" value="1" onchange="changecolor(5, 1, 5 ,__wShugoSettei5__ ,0);" __wShugoSettei5Checked1__><span id="iro5val1" __wShugoSettei5BG1__>　2：10秒　</span>
				　<input type="radio" name="wShugoSettei5" value="2" onchange="changecolor(5, 2, 5 ,__wShugoSettei5__ ,0);" __wShugoSettei5Checked2__><span id="iro5val2" __wShugoSettei5BG2__>　3：20秒　</span>
				　<input type="radio" name="wShugoSettei5" value="3" onchange="changecolor(5, 3, 5 ,__wShugoSettei5__ ,0);" __wShugoSettei5Checked3__><span id="iro5val3" __wShugoSettei5BG3__>　4：30秒　</span>
				　<input type="radio" name="wShugoSettei5" value="4" onchange="changecolor(5, 4, 5 ,__wShugoSettei5__ ,0);" __wShugoSettei5Checked4__><span id="iro5val4" __wShugoSettei5BG4__>　5：60秒　</span>
		</td>
	</tr>
	<tr><td bgcolor="#ebeeef">　②⇒呼出　</td>
		<td bgcolor="#ebeeef">表示の明るさ</td>
		<td colspan="4">
				　<input type="radio" name="wShugoSettei6" value="0" onchange="changecolor(6, 0, 8 ,__wShugoSettei6__ ,0);" __wShugoSettei6Checked0__><span id="iro6val0" __wShugoSettei6BG0__>　1：1　</span>
				　<input type="radio" name="wShugoSettei6" value="1" onchange="changecolor(6, 1, 8 ,__wShugoSettei6__ ,0);" __wShugoSettei6Checked1__><span id="iro6val1" __wShugoSettei6BG1__>　2：2　</span>
				　<input type="radio" name="wShugoSettei6" value="2" onchange="changecolor(6, 2, 8 ,__wShugoSettei6__ ,0);" __wShugoSettei6Checked2__><span id="iro6val2" __wShugoSettei6BG2__>　3：3　</span>
				　<input type="radio" name="wShugoSettei6" value="3" onchange="changecolor(6, 3, 8 ,__wShugoSettei6__ ,0);" __wShugoSettei6Checked3__><span id="iro6val3" __wShugoSettei6BG3__>　4：4　</span>
			<br>　<input type="radio" name="wShugoSettei6" value="4" onchange="changecolor(6, 4, 8 ,__wShugoSettei6__ ,0);" __wShugoSettei6Checked4__><span id="iro6val4" __wShugoSettei6BG4__>　5：5　</span>
				　<input type="radio" name="wShugoSettei6" value="5" onchange="changecolor(6, 5, 8 ,__wShugoSettei6__ ,0);" __wShugoSettei6Checked5__><span id="iro6val5" __wShugoSettei6BG5__>　6：6　</span>
				　<input type="radio" name="wShugoSettei6" value="6" onchange="changecolor(6, 6, 8 ,__wShugoSettei6__ ,0);" __wShugoSettei6Checked6__><span id="iro6val6" __wShugoSettei6BG6__>　7：7　</span>
				　<input type="radio" name="wShugoSettei6" value="7" onchange="changecolor(6, 7, 8 ,__wShugoSettei6__ ,0);" __wShugoSettei6Checked7__><span id="iro6val7" __wShugoSettei6BG7__>　8：8　</span>
		</td>
	</tr>
</td>
</tr>


<tr>
<td bgcolor="#ebeeef" rowspan="4">　暗証番号の設定　
	<tr>
		<td bgcolor="#ebeeef">　①⇒呼出　</td>
		<td bgcolor="#ebeeef">暗証番号1</td>
		<td bgcolor="#ebeeef">　①⇒呼出　</td>
		<td >　暗証番号1入力　</td>
		<td >　呼出⇒解錠時間帯A入力　</td>
		<td >　呼出⇒解錠時間帯B入力　</td>
	</tr>
	<tr>
		<td bgcolor="#ebeeef">　①⇒呼出　</td>
		<td bgcolor="#ebeeef">暗証番号2</td>
		<td bgcolor="#ebeeef">　①⇒呼出　</td>
		<td >　暗証番号2入力　</td>
		<td >　呼出⇒解錠時間帯A入力　</td>
		<td >　呼出⇒解錠時間帯B入力　</td>
	</tr>
	<tr>
		<td bgcolor="#ebeeef">　①⇒呼出　</td>
		<td bgcolor="#ebeeef">暗証番号3</td>
		<td bgcolor="#ebeeef">　①⇒呼出　</td>
		<td >　暗証番号3入力　</td>
		<td >　呼出⇒解錠時間帯A入力　</td>
		<td >　呼出⇒解錠時間帯B入力　</td>
	</tr>
</td>
</tr>

<tr>

<td bgcolor="#ebeeef">　管理用番号の設定　
	<td bgcolor="#ebeeef">　①⇒呼出　</td>
	<td bgcolor="#ebeeef">管理用暗証番号</td>
	<td bgcolor="#ebeeef" colspan="2">　管理用番号入力　</td>
	<td  colspan="2">※初期設定（1111）</td>
</td>
</tr>



<tr>
<td bgcolor="#ebeeef" rowspan="16">　その他の設定　
	<tr>
		<td bgcolor="#ebeeef">　①⇒呼出　</td>
		<td bgcolor="#ebeeef">日時の設定</td>
		<td colspan="4">　※必ず設定（西暦は下2桁を入力）　</td>
	</tr>
	<tr>
		<td bgcolor="#ebeeef">　②⇒呼出　</td>
		<td bgcolor="#ebeeef">連続解錠時間帯の設定</td>
		<td colspan="2">　連続解錠時間帯1入力　</td>
		<td colspan="2">　連続解錠時間帯2入力　</td>
	</tr>
	<tr>
		<td bgcolor="#ebeeef" rowspan="10">　③⇒呼出　</td>
		<td bgcolor="#ebeeef" rowspan="10">移報接点出力設定</td>
		<td bgcolor="#ebeeef">　0⇒　</td>
		<td bgcolor="#ebeeef">　移報接点1　</td>

		<td colspan="2">
		__IhosettenLoop__
			　<input type="radio" name="wShugoSettei7" value="__Ihosetten__" onchange="changecolor(7,'__Ihosetten__' , 8, __wShugoSettei7__,0)" __wShugoSettei7Checked__><span id="iro7val__Ihosetten__" __wShugoSettei7BG__>__IHOUSETTEN__　</span>__BR__
		__IhosettenLoop__
		</td>
	</tr>
	<tr>
		<td bgcolor="#ebeeef">呼出</td>
		<td bgcolor="#ebeeef">　移報接点2　</td>
		<td colspan="2">
		__IhosettenLoop__
			　<input type="radio" name="wShugoSettei8" value="__Ihosetten__" onchange="changecolor(8,'__Ihosetten__' , 8, __wShugoSettei8__,0)" __wShugoSettei8Checked__><span id="iro8val__Ihosetten__" __wShugoSettei8BG__>__IHOUSETTEN__　</span>__BR__
		__IhosettenLoop__
		</td>
	</tr>
	<tr>
		<td bgcolor="#ebeeef">呼出</td>
		<td bgcolor="#ebeeef">　移報接点3　</td>
		<td colspan="2">
		__IhosettenLoop__
			　<input type="radio" name="wShugoSettei9" value="__Ihosetten__" onchange="changecolor(9,'__Ihosetten__' , 8, __wShugoSettei9__,0)" __wShugoSettei9Checked__><span id="iro9val__Ihosetten__" __wShugoSettei9BG__>__IHOUSETTEN__　</span>__BR__
		__IhosettenLoop__
		</td>
	</tr>
	<tr>
		<td bgcolor="#ebeeef">呼出</td>
		<td bgcolor="#ebeeef">　移報接点4　</td>
		<td colspan="2">
		__IhosettenLoop__
			　<input type="radio" name="wShugoSettei10" value="__Ihosetten__" onchange="changecolor(10,'__Ihosetten__' , 8, __wShugoSettei10__,0)" __wShugoSettei10Checked__><span id="iro10val__Ihosetten__" __wShugoSettei10BG__>__IHOUSETTEN__　</span>__BR__
		__IhosettenLoop__
		</td>
	</tr>
	<tr>
		<td bgcolor="#ebeeef">呼出</td>
		<td bgcolor="#ebeeef">　移報接点5　</td>
		<td colspan="2">
		__IhosettenLoop__
			　<input type="radio" name="wShugoSettei11" value="__Ihosetten__" onchange="changecolor(11,'__Ihosetten__' , 8, __wShugoSettei11__,0)" __wShugoSettei11Checked__><span id="iro11val__Ihosetten__" __wShugoSettei11BG__>__IHOUSETTEN__　</span>__BR__
		__IhosettenLoop__
		</td>
	</tr>
	<tr>
		<td bgcolor="#ebeeef">呼出</td>
		<td bgcolor="#ebeeef">　移報接点6　</td>
		<td colspan="2">
		__IhosettenLoop__
			　<input type="radio" name="wShugoSettei12" value="__Ihosetten__" onchange="changecolor(12,'__Ihosetten__' , 8, __wShugoSettei12__,0)" __wShugoSettei12Checked__><span id="iro12val__Ihosetten__" __wShugoSettei12BG__>__IHOUSETTEN__　</span>__BR__
		__IhosettenLoop__
		</td>
	</tr>
	<tr>
		<td bgcolor="#ebeeef">呼出</td>
		<td bgcolor="#ebeeef">　移報接点7　</td>
		<td colspan="2">
		__IhosettenLoop__
			　<input type="radio" name="wShugoSettei13" value="__Ihosetten__" onchange="changecolor(13,'__Ihosetten__' , 8, __wShugoSettei13__,0)" __SwhugoSettei13Checked__><span id="iro13val__Ihosetten__" __wShugoSettei13BG__>__IHOUSETTEN__　</span>__BR__
		__IhosettenLoop__
		</td>
	</tr>
	<tr>
		<td bgcolor="#ebeeef">呼出</td>
		<td bgcolor="#ebeeef">　移報接点8　</td>
		<td colspan="2">
		__IhosettenLoop__
			　<input type="radio" name="wShugoSettei14" value="__Ihosetten__" onchange="changecolor(14,'__Ihosetten__' , 8, __wShugoSettei14__,0)" __wShugoSettei14Checked__><span id="iro14val__Ihosetten__" __wShugoSettei14BG__>__IHOUSETTEN__　</span>__BR__
		__IhosettenLoop__
		</td>
	</tr>
	<tr>
		<td colspan="4">　※GBX-DLUの場合：「汎用1」「防犯1」「防犯2」は使用しない　</td>
	</tr>
	<tr>
		<td colspan="4">　※親機が「GBM-2M」「GBM-2A」の場合：汎用警報は「汎用2」で設定　</td>
	</tr>

	<tr>
		<td bgcolor="#ebeeef">　④⇒呼出　</td>
		<td bgcolor="#ebeeef" colspan="3">みやすさ設定<br>（GBX-DLMUのみ）</td>
		<td colspan="2">
			　<input type="radio" name="ShugoSettei15" value="0" onchange="changecolor(15, '0', 2 ,__wShugoSettei15__ ,0);" __wShugoSettei15Checked0__><span id="iro15val0" __wShugoSettei1BG0__>　1：あり　</span>
			　<input type="radio" name="ShugoSettei15" value="1" onchange="changecolor(15, '1', 2 ,__wShugoSettei15__ ,0);" __wShugoSettei15Checked1__><span id="iro15val1" __wShugoSettei1BG1__>　2：なし　</span>
		
		</td>
	</tr>
	<tr>
		<td bgcolor="#ebeeef">　⑤⇒呼出　</td>
		<td bgcolor="#ebeeef"colspan="3">バージョン確認</td>
		<td>　X:制御装置のVer.　</td>
		<td>　D:集合玄関機のVer.　</td>
	</tr>
	<tr>
		<td bgcolor="#ebeeef">　⑥⇒呼出　</td>
		<td bgcolor="#ebeeef"colspan="3">初期化</td>
	</tr>
</td>
</tr>
<tr>
	<td  bgcolor="#ebeeef" colspan="3">ズーム一の設定（カメラ付のみ）</td>
	<td colspan="2">現地で設定</td>
	<td colspan="2">※初期設定：中央</td>

</tr>
<tr>
	<td  bgcolor="#ebeeef" colspan="3">受話音量の設定</td>
	<td colspan="2">現地で設定</td>
	<td colspan="2">※初期設定：5</td>

</tr>

</table>

<br>
◆　施工設定（管理室親機設定指示書）

<table  border="1">
<tr>
	<td bgcolor="#ebeeef">　表示の設定　</td>
	<td bgcolor="#ebeeef">　①⇒呼出　</td>
	<td bgcolor="#ebeeef">表示の明るさ</td>
	<td colspan="4">
			　<input type="radio" name="wOyakiSettei1" value="0" onchange="changecolor('Oya1', 0, 8 ,__wOyakiSettei1__ ,0);" __wOyakiSettei1Checked0__><span id="iroOya1val0" __wOyakiSettei1BG0__>　1：1　</span>
			　<input type="radio" name="wOyakiSettei1" value="1" onchange="changecolor('Oya1', 1, 8 ,__wOyakiSettei1__ ,0);" __wOyakiSettei1Checked1__><span id="iroOya1val1" __wOyakiSettei1BG1__>　2：2　</span>
			　<input type="radio" name="wOyakiSettei1" value="2" onchange="changecolor('Oya1', 2, 8 ,__wOyakiSettei1__ ,0);" __wOyakiSettei1Checked2__><span id="iroOya1val2" __wOyakiSettei1BG2__>　3：3　</span>
			　<input type="radio" name="wOyakiSettei1" value="3" onchange="changecolor('Oya1', 3, 8 ,__wOyakiSettei1__ ,0);" __wOyakiSettei1Checked3__><span id="iroOya1val3" __wOyakiSettei1BG3__>　4：4　</span>
		<br>　<input type="radio" name="wOyakiSettei1" value="4" onchange="changecolor('Oya1', 4, 8 ,__wOyakiSettei1__ ,0);" __wOyakiSettei1Checked4__><span id="iroOya1val4" __wOyakiSettei1BG4__>　5：5　</span>
			　<input type="radio" name="wOyakiSettei1" value="5" onchange="changecolor('Oya1', 5, 8 ,__wOyakiSettei1__ ,0);" __wOyakiSettei1Checked5__><span id="iroOya1val5" __wOyakiSettei1BG5__>　6：6　</span>
			　<input type="radio" name="wOyakiSettei1" value="6" onchange="changecolor('Oya1', 6, 8 ,__wOyakiSettei1__ ,0);" __wOyakiSettei1Checked6__><span id="iroOya1val6" __wOyakiSettei1BG6__>　7：7　</span>
			　<input type="radio" name="wOyakiSettei1" value="7" onchange="changecolor('Oya1', 7, 8 ,__wOyakiSettei1__ ,0);" __wOyakiSettei1Checked7__><span id="iroOya1val7" __wOyakiSettei1BG7__>　8：8　</span>
	</td>
</tr>

<tr>
	<td bgcolor="#ebeeef">　管理用番号の設定　</td>
	<td bgcolor="#ebeeef">　②⇒呼出　</td>
	<td bgcolor="#ebeeef">管理用番号</td>
	<td bgcolor="#ebeeef" colspan="2">管理用番号入力</td>
	<td >※初期設定（1111）</td>
	
</tr>



<tr>
<td bgcolor="#ebeeef" rowspan="13">　その他の設定　
	<tr>
		<td bgcolor="#ebeeef">　①⇒呼出　</td>
		<td bgcolor="#ebeeef">日時の設定</td>
		<td colspan="4">　※必ず設定（西暦は下2桁を入力）　</td>
	</tr>
	<tr>
		<td bgcolor="#ebeeef" rowspan="9">　②⇒呼出　</td>
		<td bgcolor="#ebeeef" rowspan="9">移報接点出力設定0⇒</td>

		<td bgcolor="#ebeeef">0⇒</td>
		<td bgcolor="#ebeeef">　移報接点1　</td>
		<td colspan="2">
			__IhosettenLoop__
				　<input type="radio" name="wOyakiSettei2" value="__Ihosetten__" onchange="changecolor('Oya2','__Ihosetten__' , 8, __wOyakiSettei2__,0)" __wOyakiSettei2Checked__><span id="iroOya2val__Ihosetten__" __wOyakiSettei2BG__>__IHOUSETTEN__　</span>__BR__
			__IhosettenLoop__
		</td>
	</tr>
	<tr>
		<td bgcolor="#ebeeef">呼出</td>
		<td bgcolor="#ebeeef">　移報接点2　</td>
		<td colspan="2">
			__IhosettenLoop__
				　<input type="radio" name="wOyakiSettei3" value="__Ihosetten__" onchange="changecolor('Oya3','__Ihosetten__' , 8, __wOyakiSettei3__,0)" __wOyakiSettei3Checked__><span id="iroOya3val__Ihosetten__" __wOyakiSettei3BG__>__IHOUSETTEN__　</span>__BR__
			__IhosettenLoop__
		</td>
	</tr>
	<twOyakiSettei10r>
		<td bgcolor="#ebeeef">呼出</td>
		<td bgcolor="#ebeeef">　移報接点3　</td>
		<td colspan="2">
			__IhosettenLoop__
				　<input type="radio" name="wOyakiSettei4" value="__Ihosetten__" onchange="changecolor('Oya4','__Ihosetten__' , 8, __wOyakiSettei4__,0)" __wOyakiSettei4Checked__><span id="iroOya4val__Ihosetten__" __wOyakiSettei4BG__>__IHOUSETTEN__　</span>__BR__
			__IhosettenLoop__
		</td>
	</tr>
	<tr>
		<td bgcolor="#ebeeef">呼出</td>
		<td bgcolor="#ebeeef">　移報接点4　</td>
		<td colspan="2">
			__IhosettenLoop__
				　<input type="radio" name="wOyakiSettei5" value="__Ihosetten__" onchange="changecolor('Oya5','__Ihosetten__' , 8, __wOyakiSettei5__,0)" __wOyakiSettei5Checked__><span id="iroOya5val__Ihosetten__" __wOyakiSettei5BG__>__IHOUSETTEN__　</span>__BR__
			__IhosettenLoop__
		</td>
	</tr>
	<tr>
		<td bgcolor="#ebeeef">呼出</td>
		<td bgcolor="#ebeeef">　移報接点5　</td>
		<td colspan="2">
			__IhosettenLoop__
				　<input type="radio" name="wOyakiSettei6" value="__Ihosetten__" onchange="changecolor('Oya6','__Ihosetten__' , 8, __wOyakiSettei6__,0)" __wOyakiSettei6Checked__><span id="iroOya6val__Ihosetten__" __wOyakiSettei6BG__>__IHOUSETTEN__　</span>__BR__
			__IhosettenLoop__
		</td>
	</tr>
	<tr>
		<td bgcolor="#ebeeef">呼出</td>
		<td bgcolor="#ebeeef">　移報接点6　</td>
		<td colspan="2">
			__IhosettenLoop__
				　<input type="radio" name="wOyakiSettei7" value="__Ihosetten__" onchange="changecolor('Oya7','__Ihosetten__' , 8, __wOyakiSettei7__,0)" __wOyakiSettei7Checked__><span id="iroOya7val__Ihosetten__" __wOyakiSettei7BG__>__IHOUSETTEN__　</span>__BR__
			__IhosettenLoop__
		</td>
	</tr>
	<tr>
		<td bgcolor="#ebeeef">呼出</td>
		<td bgcolor="#ebeeef">　移報接点7　</td>
		<td colspan="2">
			__IhosettenLoop__
				　<input type="radio" name="wOyakiSettei8" value="__Ihosetten__" onchange="changecolor('Oya8','__Ihosetten__' , 8, __wOyakiSettei8__,0)" __wOyakiSettei8Checked__><span id="iroOya8val__Ihosetten__" __wOyakiSettei8BG__>__IHOUSETTEN__　</span>__BR__
			__IhosettenLoop__
		</td>
	</tr>
	<tr>
		<td bgcolor="#ebeeef">呼出</td>
		<td bgcolor="#ebeeef">　移報接点8　</td>
		<td colspan="2">
			__IhosettenLoop__
				　<input type="radio" name="wOyakiSettei9" value="__Ihosetten__" onchange="changecolor('Oya9','__Ihosetten__' , 8, __wOyakiSettei9__,0)" __wOyakiSettei9Checked__><span id="iroOya9val__Ihosetten__" __wOyakiSettei9BG__>__IHOUSETTEN__　</span>__BR__
			__IhosettenLoop__
		</td>
	</tr>
	<tr>
			<td colspan="5">　※「ガス漏れ」と「ガス換気」はパラ接続（同一）とする。　</td>
	</tr>

	<tr>
		<td bgcolor="#ebeeef">　③⇒呼出　</td>
		<td bgcolor="#ebeeef"colspan="3">待受時解錠設定</td>
		<td>
			　<input type="radio" name="wOyakiSettei10" value="0" onchange="changecolor('Oya10', 0, 2 ,__wOyakiSettei10__ ,0);" __wOyakiSettei10Checked0__><span id="iroOya10val0" __wOyakiSettei10BG0__>　1：あり　</span>
			　<input type="radio" name="wOyakiSettei10" value="1" onchange="changecolor('Oya10', 1, 2 ,__wOyakiSettei10__ ,0);" __wOyakiSettei10Checked1__><span id="iroOya10val1" __wOyakiSettei10BG1__>　2：なし</span>
		</td>
	</tr>
	<tr>
		<td bgcolor="#ebeeef">　④⇒呼出　</td>
		<td bgcolor="#ebeeef">初期化</td>
	</tr>

</td>
</tr>
</table>

<br>
<br>
◆メニュー画面からの設定（居室親機設定指示書）
<table border="1">
<tr>
	<td bgcolor="#ebeeef">日時の設定</td>
	<td>必ず設定</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">※防犯セット解除の暗証番号<br>設定（GBM-2MKのみ）暗証番号設定</td>
	<td>　<input type="radio" name="WJutakuMenuSettei1" value="0" id="JM1val0" onchange="changecolor('JM1',0,2,__wJutakuMenuSettei1__,0);" __wJutakuMenuSettei1Checked0__><span id="iroJM1val0" __wJutakuMenuSettei1BG0__>使用する　</span>
		　<input type="radio" name="wJutakuMenuSettei1" value="1" id="JM1val1" onchange="changecolor('JM1',1,2,__wJutakuMenuSettei1__,0);" __wJutakuMenuSettei1Checked1__><span id="iroJM1val1" __wJutakuMenuSettei1BG1__>使用しない　</span>
		　　※使用する場合、暗証番号設定方法を居住者様へ説明する。
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">※防犯セット時の警戒遅延時間<br>の設定（GBM-2MKのみ）</td>
	<td>　<input type="radio" name="wJutakuMenuSettei2" value="0" id="JM2val0" onchange="changecolor('JM2',0,7,__wJutakuMenuSettei2__,0);" __wJutakuMenuSettei2Checked0__><span id="iroJM2val0" __wJutakuMenuSettei2BG0__>0秒　</span>
		　<input type="radio" name="wJutakuMenuSettei2" value="1" id="JM2val1" onchange="changecolor('JM2',1,7,__wJutakuMenuSettei2__,0);" __wJutakuMenuSettei2Checked1__><span id="iroJM2val1" __wJutakuMenuSettei2BG1__>30秒　</span>
		　<input type="radio" name="wJutakuMenuSettei2" value="2" id="JM2val2" onchange="changecolor('JM2',2,7,__wJutakuMenuSettei2__,0);" __wJutakuMenuSettei2Checked2__><span id="iroJM2val2" __wJutakuMenuSettei2BG2__>60秒　</span>
		　<input type="radio" name="wJutakuMenuSettei2" value="3" id="JM2val3" onchange="changecolor('JM2',3,7,__wJutakuMenuSettei2__,0);" __wJutakuMenuSettei2Checked3__><span id="iroJM2val3" __wJutakuMenuSettei2BG3__>90秒　</span><br>
		　<input type="radio" name="wJutakuMenuSettei2" value="4" id="JM2val4" onchange="changecolor('JM2',4,7,__wJutakuMenuSettei2__,0);" __wJutakuMenuSettei2Checked4__><span id="iroJM2val4" __wJutakuMenuSettei2BG4__>2分　</span>
		　<input type="radio" name="wJutakuMenuSettei2" value="5" id="JM2val5" onchange="changecolor('JM2',5,7,__wJutakuMenuSettei2__,0);" __wJutakuMenuSettei2Checked5__><span id="iroJM2val5" __wJutakuMenuSettei2BG5__>5分　</span>
		　<input type="radio" name="wJutakuMenuSettei2" value="6" id="JM2val6" onchange="changecolor('JM2',6,7,__wJutakuMenuSettei2__,0);" __wJutakuMenuSettei2Checked6__><span id="iroJM2val6" __wJutakuMenuSettei2BG6__>10分　</span>
		　※変更方法を居住者様へ説明する。
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">※防犯セット時の発報遅延時間<br>の設定（GBM-2MKのみ）</td>
	<td>　<input type="radio" name="wJutakuMenuSettei3" value="0" id="JM3val0" onchange="changecolor('JM3',0,7,__wJutakuMenuSettei3__,0);" __wJutakuMenuSettei3Checked0__><span id="iroJM3val0" __wJutakuMenuSettei3BG0__>0秒　</span>
		　<input type="radio" name="wJutakuMenuSettei3" value="1" id="JM3val1" onchange="changecolor('JM3',1,7,__wJutakuMenuSettei3__,0);" __wJutakuMenuSettei3Checked1__><span id="iroJM3val1" __wJutakuMenuSettei3BG1__>30秒　</span>
		　<input type="radio" name="wJutakuMenuSettei3" value="2" id="JM3val2" onchange="changecolor('JM3',2,7,__wJutakuMenuSettei3__,0);" __wJutakuMenuSettei3Checked2__><span id="iroJM3val2" __wJutakuMenuSettei3BG2__>60秒　</span>
		　<input type="radio" name="wJutakuMenuSettei3" value="3" id="JM3val3" onchange="changecolor('JM3',3,7,__wJutakuMenuSettei3__,0);" __wJutakuMenuSettei3Checked3__><span id="iroJM3val3" __wJutakuMenuSettei3BG3__>90秒　</span><br>
		　<input type="radio" name="wJutakuMenuSettei3" value="4" id="JM3val4" onchange="changecolor('JM3',4,7,__wJutakuMenuSettei3__,0);" __wJutakuMenuSettei3Checked4__><span id="iroJM3val4" __wJutakuMenuSettei3BG4__>2分　</span>
		　<input type="radio" name="wJutakuMenuSettei3" value="5" id="JM3val5" onchange="changecolor('JM3',5,7,__wJutakuMenuSettei3__,0);" __wJutakuMenuSettei3Checked5__><span id="iroJM3val5" __wJutakuMenuSettei3BG5__>5分　</span>
		　<input type="radio" name="wJutakuMenuSettei3" value="6" id="JM3val6" onchange="changecolor('JM3',6,7,__wJutakuMenuSettei3__,0);" __wJutakuMenuSettei3Checked6__><span id="iroJM3val6" __wJutakuMenuSettei3BG6__>10分　</span>
		　※変更方法を居住者様へ説明する。
	</td>
</tr>

</table>


<br>
<br>
◆施工設定（居室親機設定指示書）
<table  border="1">
<tr>
<td bgcolor="#ebeeef" rowspan="2">　玄関子機設定　
	<td bgcolor="#ebeeef">　子機接続　</td>
		<td colspan="2">
				　<input type="radio" name="wJutakuServiceSettei5" value="0" onchange="changecolor('JS5', 0, 2 ,__wJutakuServiceSettei5__ ,0);" __wJutakuServiceSettei5Checked0__><span id="iroJS5val0" __wJutakuServiceSettei5BG0__>　有　</span>
				　<input type="radio" name="wJutakuServiceSettei5" value="1" onchange="changecolor('JS5', 1, 2 ,__wJutakuServiceSettei5__ ,0);" __wJutakuServiceSettei5Checked1__><span id="iroJS5val1" __wJutakuServiceSettei5BG1__>　無　</span>
		</td>
	<tr><td bgcolor="#ebeeef">　子機のみやすさ　</td>
		<td colspan="2">
				　<input type="radio" name="wJutakuServiceSettei6" value="0" onchange="changecolor('JS6', 0, 2 ,__wJutakuServiceSettei6__ ,0);" __wJutakuServiceSettei6Checked0__><span id="iroJS6val0" __wJutakuServiceSettei6BG0__>　有効　</span>
				　<input type="radio" name="wJutakuServiceSettei6" value="1" onchange="changecolor('JS6', 1, 2 ,__wJutakuServiceSettei6__ ,0);" __wJutakuServiceSettei6Checked1__><span id="iroJS6val1" __wJutakuServiceSettei6BG1__>　無効　</span>
		</td>
	</tr>
</td>
<tr>
	<td bgcolor="#ebeeef">　管理室親機設定　</td>
	<td bgcolor="#ebeeef">　子機接続　</td>
	<td colspan="2">
		　<input type="radio" name="wJutakuServiceSettei7" value="0" onchange="changecolor('JS7', 0, 2 ,__wJutakuServiceSettei7__ ,0);" __wJutakuServiceSettei7Checked0__><span id="iroJS7val0" __wJutakuServiceSettei7BG0__>　有効　</span>
		　<input type="radio" name="wJutakuServiceSettei7" value="1" onchange="changecolor('JS7', 1, 2 ,__wJutakuServiceSettei7__ ,0);" __wJutakuServiceSettei7Checked1__><span id="iroJS7val1" __wJutakuServiceSettei7BG1__>　無効　</span>
	</td>
</tr>
<tr>
<td bgcolor="#ebeeef" rowspan="3">　サービス設定　
	<td bgcolor="#ebeeef">　サービス1　</td>
		<td colspan="2">
				　<input type="radio" name="wJutakuServiceSettei8" value="0" onchange="changecolor('JS8', 0, 5 ,__wJutakuServiceSettei8__ ,0);" __wJutakuServiceSettei8Checked0__><span id="iroJS8val0" __wJutakuServiceSettei8BG0__>　非常　</span>
				　<input type="radio" name="wJutakuServiceSettei8" value="1" onchange="changecolor('JS8', 1, 5 ,__wJutakuServiceSettei8__ ,0);" __wJutakuServiceSettei8Checked1__><span id="iroJS8val1" __wJutakuServiceSettei8BG1__>　汎用1　</span>
				　<input type="radio" name="wJutakuServiceSettei8" value="2" onchange="changecolor('JS8', 2, 5 ,__wJutakuServiceSettei8__ ,0);" __wJutakuServiceSettei8Checked2__><span id="iroJS8val2" __wJutakuServiceSettei8BG2__>　汎用2　</span>
				　<input type="radio" name="wJutakuServiceSettei8" value="3" onchange="changecolor('JS8', 3, 5 ,__wJutakuServiceSettei8__ ,0);" __wJutakuServiceSettei8Checked3__><span id="iroJS8val3" __wJutakuServiceSettei8BG3__>　防犯1　</span>
				　<input type="radio" name="wJutakuServiceSettei8" value="4" onchange="changecolor('JS8', 4, 5 ,__wJutakuServiceSettei8__ ,0);" __wJutakuServiceSettei8Checked4__><span id="iroJS8val4" __wJutakuServiceSettei8BG4__>　使用しない　</span>
		</td>
	<tr><td bgcolor="#ebeeef">　サービス2　</td>
		<td colspan="2">
				　<input type="radio" name="wJutakuServiceSettei9" value="0" onchange="changecolor('JS9,' 0, 5 ,__wJutakuServiceSettei9__ ,0);" __wJutakuServiceSettei9Checked0__><span id="iroJS9val0" __wJutakuServiceSettei9BG0__>　非常　</span>
				　<input type="radio" name="wJutakuServiceSettei9" value="1" onchange="changecolor('JS9,' 1, 5 ,__wJutakuServiceSettei9__ ,0);" __wJutakuServiceSettei9Checked1__><span id="iroJS9val1" __wJutakuServiceSettei9BG1__>　汎用1　</span>
				　<input type="radio" name="wJutakuServiceSettei9" value="2" onchange="changecolor('JS9,' 2, 5 ,__wJutakuServiceSettei9__ ,0);" __wJutakuServiceSettei9Checked2__><span id="iroJS9val2" __wJutakuServiceSettei9BG2__>　汎用2　</span>
				　<input type="radio" name="wJutakuServiceSettei9" value="3" onchange="changecolor('JS9,' 3, 5 ,__wJutakuServiceSettei9__ ,0);" __wJutakuServiceSettei9Checked3__><span id="iroJS9val3" __wJutakuServiceSettei9BG3__>　防犯1　</span>
				　<input type="radio" name="wJutakuServiceSettei9" value="4" onchange="changecolor('JS9', 4, 5 ,__wJutakuServiceSettei9__ ,0);" __wJutakuServiceSettei9Checked4__><span id="iroJS9val4" __wJutakuServiceSettei9BG4__>　使用しない　</span>
		</td>
	</tr>
	<tr><td bgcolor="#ebeeef">　サービス3　</td>
		<td colspan="2">
				　<input type="radio" name="wJutakuServiceSettei10" value="0" onchange="changecolor('JS10', 0, 5 ,__wJutakuServiceSettei10__ ,0);" __wJutakuServiceSettei10Checked0__><span id="iroJS10val0" __wJutakuServiceSettei10BG0__>　非常　</span>
				　<input type="radio" name="wJutakuServiceSettei10" value="1" onchange="changecolor('JS10', 1, 5 ,__wJutakuServiceSettei10__ ,0);" __wJutakuServiceSettei10Checked1__><span id="iroJS10val1" __wJutakuServiceSettei10BG1__>　汎用1　</span>
				　<input type="radio" name="wJutakuServiceSettei10" value="2" onchange="changecolor('JS10', 2, 5 ,__wJutakuServiceSettei10__ ,0);" __wJutakuServiceSettei10Checked2__><span id="iroJS10val2" __wJutakuServiceSettei10BG2__>　汎用2　</span>
				　<input type="radio" name="wJutakuServiceSettei10" value="3" onchange="changecolor('JS10', 3, 5 ,__wJutakuServiceSettei10__ ,0);" __wJutakuServiceSettei10Checked3__><span id="iroJS10val3" __wJutakuServiceSettei10BG3__>　防犯1　</span>
				　<input type="radio" name="wJutakuServiceSettei10" value="4" onchange="changecolor('JS10', 4, 5 ,__wJutakuServiceSettei10__ ,0);" __wJutakuServiceSettei10Checked4__><span id="iroJS10val4" __wJutakuServiceSettei10BG4__>　使用しない　</span>
		</td>
	</tr>
</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="5">　非常　</td>
	<td bgcolor="#ebeeef" rowspan="5">　詳細設定　</td>
	
	<td bgcolor="#ebeeef">外部への警報移報遅延</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei11" value="0" onchange="changecolor('JS11', 0, 2 ,__wJutakuServiceSettei11__ ,0);" __wJutakuServiceSettei11Checked0__><span id="iroJS11val0" __wJutakuServiceSettei11BG0__>　0秒　</span>
		　<input type="radio" name="wJutakuServiceSettei11" value="1" onchange="changecolor('JS11', 1, 2 ,__wJutakuServiceSettei11__ ,0);" __wJutakuServiceSettei11Checked1__><span id="iroJS11val1" __wJutakuServiceSettei11BG1__>　30秒　</span>
	</td>
	<tr>
	<td bgcolor="#ebeeef">検出条件</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei12" value="0" onchange="changecolor('JS12', 0, 2 ,__wJutakuServiceSettei12__ ,0);" __wJutakuServiceSettei12Checked0__><span id="iroJS12val0" __wJutakuServiceSettei12BG0__>　メーク　</span>
		　<input type="radio" name="wJutakuServiceSettei12" value="1" onchange="changecolor('JS12', 1, 2 ,__wJutakuServiceSettei12__ ,0);" __wJutakuServiceSettei12Checked1__><span id="iroJS12val1" __wJutakuServiceSettei12BG1__>　ブレーク　</span>
	</td>
	</tr>
	<tr>
	<td bgcolor="#ebeeef">押しボタン方式</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei13" value="0" onchange="changecolor('JS13', 0, 2 ,__wJutakuServiceSettei13__ ,0);" __wJutakuServiceSettei13Checked0__><span id="iroJS13val0" __wJutakuServiceSettei13BG0__>　ワンショット式　</span>
		　<input type="radio" name="wJutakuServiceSettei13" value="1" onchange="changecolor('JS13', 1, 2 ,__wJutakuServiceSettei13__ ,0);" __wJutakuServiceSettei13Checked1__><span id="iroJS13val1" __wJutakuServiceSettei13BG1__>　ロック式　</span>
	</td>
	</tr>
	<tr>
	<td bgcolor="#ebeeef">玄関子機録画連動</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei14" value="0" onchange="changecolor('JS14', 0, 2,__wJutakuServiceSettei14__ ,0);" __wJutakuServiceSettei14Checked0__><span id="iroJS14val0" __wJutakuServiceSettei14BG0__>　有効　</span>
		　<input type="radio" name="wJutakuServiceSettei14" value="1" onchange="changecolor('JS14', 1, 2,__wJutakuServiceSettei14__ ,0);" __wJutakuServiceSettei14Checked1__><span id="iroJS14val1" __wJutakuServiceSettei14BG1__>　無効　</span>
	</td>
	</tr>
	<tr>
	<td bgcolor="#ebeeef">サイレントモード</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei15" value="0" onchange="changecolor('JS15', 0, 2,__wJutakuServiceSettei15__ ,0);" __wJutakuServiceSettei15Checked0__><span id="iroJS15val0" __wJutakuServiceSettei15BG0__>　有効　</span>
		　<input type="radio" name="wJutakuServiceSettei15" value="1" onchange="changecolor('JS15', 1, 2,__wJutakuServiceSettei15__ ,0);" __wJutakuServiceSettei15Checked1__><span id="iroJS15val1" __wJutakuServiceSettei15BG1__>　無効　</span>
	</td>
	</tr>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="4">　汎用1<br>GBM-2MKのみ　</td>
	<td bgcolor="#ebeeef" rowspan="4">　詳細設定　</td>
	
	<td bgcolor="#ebeeef">外部への警報移報遅延</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei16" value="0" onchange="changecolor('JS16', 0, 2,__wJutakuServiceSettei16__ ,0);" __wJutakuServiceSettei16Checked0__><span id="iroJS16val0" __wJutakuServiceSettei16BG0__>　0秒　</span>
		　<input type="radio" name="wJutakuServiceSettei16" value="1" onchange="changecolor('JS16', 1, 2,__wJutakuServiceSettei16__ ,0);" __wJutakuServiceSettei16Checked1__><span id="iroJS16val1" __wJutakuServiceSettei16BG1__>　30秒　</span>
	</td>
	<tr>
	<td bgcolor="#ebeeef">検出条件</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei17" value="0" onchange="changecolor('JS17', 0, 2,__wJutakuServiceSettei17__ ,0);" __wJutakuServiceSettei17Checked0__><span id="iroJS17val0" __wJutakuServiceSettei17BG0__>　メーク　</span>
		　<input type="radio" name="wJutakuServiceSettei17" value="1" onchange="changecolor('JS17', 1, 2,__wJutakuServiceSettei17__ ,0);" __wJutakuServiceSettei17Checked1__><span id="iroJS17val1" __wJutakuServiceSettei17BG1__>　ブレーク　</span>
	</td>
	</tr>
	<tr>
	<td bgcolor="#ebeeef">押しボタン方式</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei18" value="0" onchange="changecolor('JS18', 0, 2,__wJutakuServiceSettei18__ ,0);" __wJutakuServiceSettei18Checked0__><span id="iroJS18val0" __wJutakuServiceSettei18BG0__>　ワンショット式　</span>
		　<input type="radio" name="wJutakuServiceSettei18" value="1" onchange="changecolor('JS18', 1, 2,__wJutakuServiceSettei18__ ,0);" __wJutakuServiceSettei18Checked1__><span id="iroJS18val1" __wJutakuServiceSettei18BG1__>　ロック式　</span>
	</td>
	</tr>
	<tr>
	<td bgcolor="#ebeeef">玄関子機への出力</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei19" value="0" onchange="changecolor('JS19', 0, 3 ,__wJutakuServiceSettei19__ ,0);" __wJutakuServiceSettei19Checked0__><span id="iroJS19val0" __wJutakuServiceSettei19BG0__>　無　</span>
		　<input type="radio" name="wJutakuServiceSettei19" value="1" onchange="changecolor('JS19', 1, 3 ,__wJutakuServiceSettei19__ ,0);" __wJutakuServiceSettei19Checked1__><span id="iroJS19val1" __wJutakuServiceSettei19BG1__>　有：0秒　</span>
		　<input type="radio" name="wJutakuServiceSettei19" value="2" onchange="changecolor('JS19', 2, 3 ,__wJutakuServiceSettei19__ ,0);" __wJutakuServiceSettei19Checked2__><span id="iroJS19val2" __wJutakuServiceSettei19BG2__>　有：30秒　</span>
	</td>
	</tr>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="4">　汎用2　</td>
	<td bgcolor="#ebeeef" rowspan="4">　詳細設定　</td>
	
	<td bgcolor="#ebeeef">外部への警報移報遅延</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei20" value="0" onchange="changecolor('JS20', 0, 2,__wJutakuServiceSettei20__ ,0);" __wJutakuServiceSettei20Checked0__><span id="iroJS20val0" __wJutakuServiceSettei20BG0__>　0秒　</span>
		　<input type="radio" name="wJutakuServiceSettei20" value="1" onchange="changecolor('JS20', 1, 2,__wJutakuServiceSettei20__ ,0);" __wJutakuServiceSettei20Checked1__><span id="iroJS20val1" __wJutakuServiceSettei20BG1__>　30秒　</span>
	</td>
	<tr>
	<td bgcolor="#ebeeef">検出条件</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei21" value="0" onchange="changecolor('JS21', 0, 2,__wJutakuServiceSettei21__ ,0);" __wJutakuServiceSettei21Checked0__><span id="iroJS21val0" __wJutakuServiceSettei21BG0__>　メーク　</span>
		　<input type="radio" name="wJutakuServiceSettei21" value="1" onchange="changecolor('JS21', 1, 2,__wJutakuServiceSettei21__ ,0);" __wJutakuServiceSettei21Checked1__><span id="iroJS21val1" __wJutakuServiceSettei21BG1__>　ブレーク　</span>
	</td>
	</tr>
	<tr>
	<td bgcolor="#ebeeef">押しボタン方式</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei22" value="0" onchange="changecolor('JS22', 0, 2,__wJutakuServiceSettei22__ ,0);" __wJutakuServiceSettei22Checked0__><span id="iroJS22val0" __wJutakuServiceSettei22BG0__>　ワンショット式　</span>
		　<input type="radio" name="wJutakuServiceSettei22" value="1" onchange="changecolor('JS22', 1, 2,__wJutakuServiceSettei22__ ,0);" __wJutakuServiceSettei22Checked1__><span id="iroJS22val1" __wJutakuServiceSettei22BG1__>　ロック式　</span>
	</td>
	</tr>
	<tr>
	<td bgcolor="#ebeeef">玄関子機への出力</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei23" value="0" onchange="changecolor('JS23', 0, 3 ,__wJutakuServiceSettei23__ ,0);" __wJutakuServiceSettei23Checked0__><span id="iroJS23val0" __wJutakuServiceSettei23BG0__>　無　</span>
		　<input type="radio" name="wJutakuServiceSettei23" value="1" onchange="changecolor('JS23', 1, 3 ,__wJutakuServiceSettei23__ ,0);" __wJutakuServiceSettei23Checked1__><span id="iroJS23val1" __wJutakuServiceSettei23BG1__>　有：0秒　</span>
		　<input type="radio" name="wJutakuServiceSettei23" value="2" onchange="changecolor('JS23', 2, 3 ,__wJutakuServiceSettei23__ ,0);" __wJutakuServiceSettei23Checked2__><span id="iroJS23val2" __wJutakuServiceSettei23BG2__>　有：30秒　</span>
	</td>
	</tr>
</tr>

<tr>
	<td bgcolor="#ebeeef" rowspan="3">　防犯<br>GBM-2MKのみ　</td>
	<td bgcolor="#ebeeef" rowspan="3">　詳細設定　</td>
	
	<td bgcolor="#ebeeef">外部への警報移報遅延</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei24" value="0" onchange="changecolor('JS24', 0, 5 ,__wJutakuServiceSettei24__ ,0);" __wJutakuServiceSettei24Checked0__><span id="iroJS24val0" __wJutakuServiceSettei24BG0__>　0秒　</span>
		　<input type="radio" name="wJutakuServiceSettei24" value="1" onchange="changecolor('JS24', 1, 5 ,__wJutakuServiceSettei24__ ,0);" __wJutakuServiceSettei24Checked1__><span id="iroJS24val1" __wJutakuServiceSettei24BG1__>　30秒　</span>
		　<input type="radio" name="wJutakuServiceSettei24" value="2" onchange="changecolor('JS24', 2, 5 ,__wJutakuServiceSettei24__ ,0);" __wJutakuServiceSettei24Checked2__><span id="iroJS24val2" __wJutakuServiceSettei24BG2__>　60秒　</span>
		　<input type="radio" name="wJutakuServiceSettei24" value="3" onchange="changecolor('JS24', 3, 5 ,__wJutakuServiceSettei24__ ,0);" __wJutakuServiceSettei24Checked3__><span id="iroJS24val3" __wJutakuServiceSettei24BG3__>　90秒　</span>
		　<input type="radio" name="wJutakuServiceSettei24" value="4" onchange="changecolor('JS24', 4, 5 ,__wJutakuServiceSettei24__ ,0);" __wJutakuServiceSettei24Checked4__><span id="iroJS24val4" __wJutakuServiceSettei24BG4__>　120秒　</span>
	</td>
	<tr>
	<td bgcolor="#ebeeef">玄関子機への警報出力遅延</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei25" value="0" onchange="changecolor('JS25', 0, 3 ,__wJutakuServiceSettei25__ ,0);" __wJutakuServiceSettei25Checked0__><span id="iroJS25val0" __wJutakuServiceSettei25BG0__>　0秒　</span>
		　<input type="radio" name="wJutakuServiceSettei25" value="1" onchange="changecolor('JS25', 1, 3 ,__wJutakuServiceSettei25__ ,0);" __wJutakuServiceSettei25Checked1__><span id="iroJS25val1" __wJutakuServiceSettei25BG1__>　30秒　</span>
		　<input type="radio" name="wJutakuServiceSettei25" value="2" onchange="changecolor('JS25', 2, 3 ,__wJutakuServiceSettei25__ ,0);" __wJutakuServiceSettei25Checked2__><span id="iroJS25val2" __wJutakuServiceSettei25BG2__>　60秒　</span>
	</td>
	</tr>
	<tr>
	<td bgcolor="#ebeeef">管理用暗証番号の使用有無</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei26" value="0" onchange="changecolor('JS26', 0, 2,__wJutakuServiceSettei26__ ,0);" __wJutakuServiceSettei26Checked0__><span id="iroJS26val0" __wJutakuServiceSettei26BG0__>　25分後解除　</span>
		　<input type="radio" name="wJutakuServiceSettei26" value="1" onchange="changecolor('JS26', 1, 2,__wJutakuServiceSettei26__ ,0);" __wJutakuServiceSettei26Checked1__><span id="iroJS26val1" __wJutakuServiceSettei26BG1__>　使用しない　</span>
	</td>
	</tr>

</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="3" colspan="2">　非常ボタン設定<br>GBM-2MKのみ　</td>
	<td bgcolor="#ebeeef">外部への警報移報遅延</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei27" value="0" onchange="changecolor('JS27', 0, 2,__wJutakuServiceSettei27__ ,0);" __wJutakuServiceSettei27Checked0__><span id="iroJS27val0" __wJutakuServiceSettei27BG0__>　0秒　<span>
		　<input type="radio" name="wJutakuServiceSettei27" value="1" onchange="changecolor('JS27', 1, 2,__wJutakuServiceSettei27__ ,0);" __wJutakuServiceSettei27Checked1__><span id="iroJS27val1" __wJutakuServiceSettei27BG1__>　30秒　</span>
	</td>
	<tr>
	<td bgcolor="#ebeeef">玄関子機録画連動</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei28" value="0" onchange="changecolor('JS28', 0, 2,__wJutakuServiceSettei28__ ,0);" __wJutakuServiceSettei28Checked0__><span id="iroJS28val0" __wJutakuServiceSettei28BG0__>　有効　</span>
		　<input type="radio" name="wJutakuServiceSettei28" value="1" onchange="changecolor('JS28', 1, 2,__wJutakuServiceSettei28__ ,0);" __wJutakuServiceSettei28Checked1__><span id="iroJS28val1" __wJutakuServiceSettei28BG1__>　無効　</span>
	</td>
	</tr>
	<tr>
	<td bgcolor="#ebeeef">サイレントモード</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei29" value="0" onchange="changecolor('JS29', 0, 2,__wJutakuServiceSettei29__ ,0);" __wJutakuServiceSettei29Checked0__><span id="iroJS29val0" __wJutakuServiceSettei29BG0__>　有効　</span>
		　<input type="radio" name="wJutakuServiceSettei29" value="1" onchange="changecolor('JS29', 1, 2,__wJutakuServiceSettei29__ ,0);" __wJutakuServiceSettei29Checked1__><span id="iroJS29val1" __wJutakuServiceSettei29BG1__>　無効　</span>
	</td>
	</tr>

</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="2" colspan="2">　その他設定　</td>
	
	<td bgcolor="#ebeeef">代表移報設定</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei30" value="0" onchange="changecolor('JS30', 0, 2,__wJutakuServiceSettei30__ ,0);" __wJutakuServiceSettei30Checked0__><span id="iroJS30val0" __wJutakuServiceSettei30BG0__>　警報のみ　</span>
		　<input type="radio" name="wJutakuServiceSettei30" value="1" onchange="changecolor('JS30', 1, 2,__wJutakuServiceSettei30__ ,0);" __wJutakuServiceSettei30Checked1__><span id="iroJS30val1" __wJutakuServiceSettei30BG1__>　警報＋呼出　</span>
	</td>
	<tr>
	<td bgcolor="#ebeeef">防犯モードの設定<br>GBM-2MKのみ</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei31" value="0" onchange="changecolor('JS31', 0, 2,__wJutakuServiceSettei31__ ,0);" __wJutakuServiceSettei31Checked0__><span id="iroJS31val0" __wJutakuServiceSettei31BG0__>　外出/在宅防犯モード　</span>
		　<input type="radio" name="wJutakuServiceSettei31" value="1" onchange="changecolor('JS31', 1, 2,__wJutakuServiceSettei31__ ,0);" __wJutakuServiceSettei31Checked1__><span id="iroJS31val1" __wJutakuServiceSettei31BG1__>　シンプル防犯モード　</span>
	</td>
	</tr>
</tr>
</table>
<br>


<br>
<br>
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
<div class="center_yose">
<input type="submit" value="登録">
</div>

<br>
<br>
</form>
<a href="s_kikisetei.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜戻る</a>
<br>
<br>
<br>

<!--施工会社様向け依頼事項End-->
<!--
<font color="red" size=4><b>※「㈱、Ⅰ、Ⅱ、①、②」等の環境依存文字は文字化けします。<br>
							　「（株）、I、II、(1)、(2)」に変更お願いします。</b></font>

<br><br>
<input type="hidden" name="rKey" value="__rKey__">
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
<input type="hidden" name="wIraiRenkeiStatus" value="__wIraiRenkeiStatus__" >
<input type="hidden" name="editIraiRenkeiCD" value="__editIraiRenkeiCD__" > 
<input type="hidden" name="wBunjyo" value="__wBunjyo__" > 
<input type="hidden" name="wKosu" value="__wKosu__" > 
<input type="hidden" name="wKanriKinmu" value="__wKanriKinmu__" > 
->

<!-- ファイル名取得のため
<input type="submit" value="  内容確認  " >-->
<!--
<input type="hidden" name="hiddenfilenames" value="">
<input type="button" value="内容確認" onclick="javascript:FilesSubmit(this.form);">
</form>
<br>
-->
<!--
<hr size="__HRSize__" color="__HRColor__">
<a href="s_menu.php__QUERY__&editBukkenCD=__editBukkenCD__ ">メニュー</a><br><br>
<a href="s_search.php__QUERY__">トップ</a><br>
<hr size="__HRSize__" color="__HRColor__">
__SFooter__
__SCop-->

</body>
</html>


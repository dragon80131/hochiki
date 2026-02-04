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

function resetradio() {
    for (i = 0; i <= 26; i++) {
        document.getElementById('radio44' + i).checked = false;
    }
}
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
<form name="form1" method="POST" action="./doc/s_kikiseteiVIXUS1Pr_finish.php?rKey=__rKey__" >
<h6>VIXUS1Pr</h6>

<a href="s_kikisetei.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜戻る</a>
<br>
<br>


◆<a href="s_kikiseteiVIXUS1Pr_copy.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">過去の案件の機器情報をコピーする</a>




<br>
<br>
◆設定<span style="background-color:#A9F5A9">　　　</span>は初期設定値
<br>
◆設定<span style="background-color:#ffbab3">　　　</span>は変更値
<br><br>

◆　施工設定（集合玄関機設定指示書）
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
<table  border="1">

<tr><td bgcolor="#ebeeef">　1　</td>
	<td bgcolor="#ebeeef" colspan="2">
		受話音量<br>
		初期設定：4</td>
	<td>
		__VolumeLoop__
			　<input type="radio" name="wShugoSettei1" value="__Volume__" onchange="changecolor(1, '__Volume__', 10 ,__wShugoSettei1__ ,1);" __wShugoSettei1Checked__><span id="iro1val__Volume__" __wShugoSettei1BG__>　__Volume__　</span>__BR__
		__VolumeLoop__
	</td>
</tr>
<tr><td bgcolor="#ebeeef">　2　</td>
	<td bgcolor="#ebeeef" colspan="2">
		画面の明るさ<br>
		初期設定：6</td>
	<td>
		__VolumeLoop__
			　<input type="radio" name="wShugoSettei2" value="__Volume__" onchange="changecolor(2,'__Volume__' , 10, __wShugoSettei2__ ,1);" __wShugoSettei2Checked__><span id="iro2val__Volume__" __wShugoSettei2BG__>　__Volume__　</span>__BR__
		__VolumeLoop__
	</td>
</tr>
<tr><td bgcolor="#ebeeef">　3　</td>
	<td bgcolor="#ebeeef" colspan="2" >エントランスカメラの接続</td>
	<td colspan="2">
		　<input type="radio" name="wShugoSettei3" value="1" onchange="changecolor(3,'1' , 2, __wShugoSettei3__,0);" __wShugoSettei3Checked1__><span id="iro3val1" __wShugoSettei3BG1__>接続する　</span>
		　<input type="radio" name="wShugoSettei3" value="0" onchange="changecolor(3,'0' , 2, __wShugoSettei3__,0);" __wShugoSettei3Checked0__><span id="iro3val0" __wShugoSettei3BG0__>接続しない</span>
	</td>
</tr>
<tr><td bgcolor="#ebeeef">　4　</td>
	<td bgcolor="#ebeeef" colspan="2" >英語切替機能</td>
	<td colspan="2">
		　<input type="radio" name="wShugoSettei4" value="1" onchange="changecolor(4,'1' , 2, __wShugoSettei4__,0);" __wShugoSettei4Checked1__><span id="iro4val1" __wShugoSettei4BG1__>使用する　</span>
		　<input type="radio" name="wShugoSettei4" value="0" onchange="changecolor(4,'0' , 2, __wShugoSettei4__,0);" __wShugoSettei4Checked0__><span id="iro4val0" __wShugoSettei4BG0__>使用しない</span>
	</td>
</tr>
<tr><td bgcolor="#ebeeef">　5　</td>
	<td bgcolor="#ebeeef" colspan="2" >サービスメニュー</td>
	<td colspan="2">
		　<input type="radio" name="wShugoSettei5" value="1" onchange="changecolor(5,'1' , 2, __wShugoSettei5__,0);" __wShugoSettei5Checked1__><span id="iro5val1" __wShugoSettei5BG1__>使用する　</span>
		　<input type="radio" name="wShugoSettei5" value="0" onchange="changecolor(5,'0' , 2, __wShugoSettei5__,0);" __wShugoSettei5Checked0__><span id="iro5val0" __wShugoSettei5BG0__>使用しない</span>
	</td>
</tr>
<tr><td bgcolor="#ebeeef">　6　</td>
	<td bgcolor="#ebeeef" colspan="2" >管理室呼出機能</td>
	<td colspan="2">
		　<input type="radio" name="wShugoSettei6" value="2" onchange="changecolor(6,'2' , 3, __wShugoSettei6__,0);" __wShugoSettei6Checked2__><span id="iro6val2" __wShugoSettei6BG2__>あり／あり　</span>
		　<input type="radio" name="wShugoSettei6" value="1" onchange="changecolor(6,'1' , 3, __wShugoSettei6__,0);" __wShugoSettei6Checked1__><span id="iro6val1" __wShugoSettei6BG1__>あり／なし　</span>
		　<input type="radio" name="wShugoSettei6" value="0" onchange="changecolor(6,'0' , 3, __wShugoSettei6__,0);" __wShugoSettei6Checked0__><span id="iro6val0"__wShugoSettei6BG0__>なし／なし　</span>
	</td>
</tr>
<tr><td bgcolor="#ebeeef">　7　</td>
	<td bgcolor="#ebeeef" colspan="2" >自画像カメラ映像の表示</td>
	<td colspan="2">
		　<input type="radio" name="wShugoSettei7" value="1" onchange="changecolor(7,'1' , 2, __wShugoSettei7__,0);" __wShugoSettei7Checked1__><span id="iro7val1" __wShugoSettei7BG1__>表示する　</span>
		　<input type="radio" name="wShugoSettei7" value="0" onchange="changecolor(7,'0' , 2, __wShugoSettei7__,0);" __wShugoSettei7Checked0__><span id="iro7val0" __wShugoSettei7BG0__>表示しない</span>
	</td>
</tr>
<tr><td bgcolor="#ebeeef">　8　</td>
	<td bgcolor="#ebeeef"  >ズーム位置プリセット</td>
	<td bgcolor="#ebeeef"  >設定範囲：左右：-6～6　上下：-6～6
	<td colspan="2">
		設定：<select name="wShugoSettei8">
				<option value="0" __wShugoSettei8Selected0__>右</option>
				<option value="1" __wShugoSettei8Selected1__>左</option>
			</select>
			：<input type="number" name="wShugoSettei9" value="__dShugoSettei9__" max="6" min="-6" >　
			　<select name="wShugoSettei10">
				<option value="0" __wShugoSettei10Selected0__>上</option>
				<option value="1" __wShugoSettei10Selected1__>下</option>
			</select>
			：<input type="number" name="wShugoSettei11" value="__dShugoSettei11__" max="6" min="-6">
	</td>
</tr>
<tr><td bgcolor="#ebeeef">　9　</td>
	<td bgcolor="#ebeeef" colspan="2">お知らせの表示</td>
	<td colspan="2">
		　<input type="radio" name="wShugoSettei12" value="1" onchange="changecolor(12,'1' , 2, __wShugoSettei12__,0);" __wShugoSettei12Checked1__ ><span id="iro12val1" __wShugoSettei12BG1__>表示する　</span>
		　<input type="radio" name="wShugoSettei12" value="0" onchange="changecolor(12,'0' , 2, __wShugoSettei12__,0);" __wShugoSettei12Checked0__ ><span id="iro12val0" __wShugoSettei12BG0__>表示しない</span>
	</td>
</tr>
<tr><td bgcolor="#ebeeef">　10　</td>
	<td bgcolor="#ebeeef" colspan="2">お知らせの表示通知音鳴動回数</td>
	<td colspan="2">
		　<input type="radio" name="wShugoSettei13" value="0" onchange="changecolor(13,'0' , 4, __wShugoSettei13__,0);" __wShugoSettei13Checked0__><span id="iro13val0" __wShugoSettei13BG0__>なし　</span>
		　<input type="radio" name="wShugoSettei13" value="1" onchange="changecolor(13,'1' , 4, __wShugoSettei13__,0);" __wShugoSettei13Checked1__><span id="iro13val1" __wShugoSettei13BG1__>1回　</span>
		　<input type="radio" name="wShugoSettei13" value="2" onchange="changecolor(13,'2' , 4, __wShugoSettei13__,0);" __wShugoSettei13Checked2__><span id="iro13val2" __wShugoSettei13BG2__>2回　</span>
		　<input type="radio" name="wShugoSettei13" value="3" onchange="changecolor(13,'3' , 4, __wShugoSettei13__,0);" __wShugoSettei13Checked3__><span id="iro13val3" __wShugoSettei13BG3__>3回　</span>
	</td>
</tr>
<tr><td bgcolor="#ebeeef">　11　</td>
	<td bgcolor="#ebeeef">お知らせ表示時間</td>
	<td bgcolor="#ebeeef">設定範囲：5秒～60秒</td>
	<td>設定：　<input type="number" name="wShugoSettei14" value="__dShugoSettei14__" max="60" min="5">秒　　※初期設定：10秒
	</td>
</tr>
<tr><td bgcolor="#ebeeef">　12　</td>
	<td bgcolor="#ebeeef" colspan="2">電気錠の接続</td>
	<td colspan="4">
		　<input type="radio" name="wShugoSettei15"  value="1" onchange="changecolor(15,'1' , 2, __wShugoSettei15__,0);" __wShugoSettei15Checked1__><span id="iro15val1" __wShugoSettei15BG1__>接続する　</span>
		　<input type="radio" name="wShugoSettei15"  value="0" onchange="changecolor(15,'0' , 2, __wShugoSettei15__,0);" __wShugoSettei15Checked0__><span id="iro15val0" __wShugoSettei15BG0__>接続しない　</span>
	</td>
<tr><td bgcolor="#ebeeef">　13　</td>
	<td bgcolor="#ebeeef" colspan="2">電気錠の開錠通知音鳴動回数</td>
	<td colspan="2">
		　<input type="radio" name="wShugoSettei16" value="0" onchange="changecolor(16,'0' , 4, __wShugoSettei16__,0);" __wShugoSettei16Checked0__><span id="iro16val0" __wShugoSettei16BG0__>なし　</span>
		　<input type="radio" name="wShugoSettei16" value="1" onchange="changecolor(16,'1' , 4, __wShugoSettei16__,0);" __wShugoSettei16Checked1__><span id="iro16val1" __wShugoSettei16BG1__>1回　</span>
		　<input type="radio" name="wShugoSettei16" value="2" onchange="changecolor(16,'2' , 4, __wShugoSettei16__,0);" __wShugoSettei16Checked2__><span id="iro16val2" __wShugoSettei16BG2__>2回　</span>
		　<input type="radio" name="wShugoSettei16" value="3" onchange="changecolor(16,'3' , 4, __wShugoSettei16__,0);" __wShugoSettei16Checked3__><span id="iro16val3" __wShugoSettei16BG3__>3回　</span>
	</td>
</tr>
<tr><td bgcolor="#ebeeef">　14　</td>
	<td bgcolor="#ebeeef" colspan="2">通知音の音量
	<td colspan="2">　<input type="radio" name="wShugoSettei17" value="1" onchange="changecolor(17,'1' , 2, __wShugoSettei17__,0);" __wShugoSettei17Checked1__><span id="iro17val1" __wShugoSettei17BG1__>大　</span>
					　<input type="radio" name="wShugoSettei17" value="0" onchange="changecolor(17,'0' , 2, __wShugoSettei17__,0);" __wShugoSettei17Checked0__><span id="iro17val0" __wShugoSettei17BG0__>標準</span>
	</td>
</tr>
<tr><td bgcolor="#ebeeef">　15　</td>
	<td bgcolor="#ebeeef" colspan="2">キー入力音の鳴動</td>
	<td colspan="2">　<input type="radio" name="wShugoSettei18" value="1" onchange="changecolor(18,'1' , 2, __wShugoSettei18__,0);" __wShugoSettei18Checked1__><span id="iro18val1" __wShugoSettei18BG1__>鳴動する　</span>
					　<input type="radio" name="wShugoSettei18" value="0" onchange="changecolor(18,'0' , 2, __wShugoSettei18__,0);" __wShugoSettei18Checked0__><span id="iro18val0" __wShugoSettei18BG0__>鳴動しない</span>
	</td>
</tr>
<tr><td bgcolor="#ebeeef">　16　</td>
	<td bgcolor="#ebeeef" colspan="2">人体検出センサー調整</td>
	<td colspan="2">　※現場で調整</td></tr>
<tr><td bgcolor="#ebeeef">　17　</td>
	<td bgcolor="#ebeeef" colspan="2">システム日時変更機能</td>
	<td colspan="2">
		　<input type="radio" name="wShugoSettei19" value="1" onchange="changecolor(19,'1' , 2, __wShugoSettei19__,0);" __wShugoSettei19Checked1__><span id="iro19val1" __wShugoSettei19BG1__>使用する　</span>
		　<input type="radio" name="wShugoSettei19" value="0" onchange="changecolor(19,'0' , 2, __wShugoSettei19__,0);" __wShugoSettei19Checked0__><span id="iro19val0" __wShugoSettei19BG0__>使用しない</span>
	</td>
</tr>
<tr><td bgcolor="#ebeeef">　18　</td>
	<td bgcolor="#ebeeef" colspan="2">カメラ起動時の逆光補正</td>
	<td colspan="2">
		　<input type="radio" name="wShugoSettei20" value="1" onchange="changecolor(20,'1' , 2, __wShugoSettei20__,0);" __wShugoSettei20Checked1__><span id="iro20val1" __wShugoSettei20BG1__>使用する　</span>
		　<input type="radio" name="wShugoSettei20" value="0" onchange="changecolor(20,'0' , 2, __wShugoSettei20__,0);" __wShugoSettei20Checked0__><span id="iro20val0" __wShugoSettei20BG0__>使用しない</span>
	</td>
</tr>
<tr><td bgcolor="#ebeeef">　19　</td>
	<td bgcolor="#ebeeef" colspan="2">居室の自己診断</td>
	<td colspan="2">
		　<input type="radio" name="wShugoSettei21" value="1" onchange="changecolor(21,'1' , 2, __wShugoSettei21__,0);" __wShugoSettei21Checked1__><span id="iro21val1" __wShugoSettei21BG1__>使用する　</span>
		　<input type="radio" name="wShugoSettei21" value="0" onchange="changecolor(21,'0' , 2, __wShugoSettei21__,0);" __wShugoSettei21Checked0__><span id="iro21val0" __wShugoSettei21BG0__>使用しない</span>
	</td>
</tr>
</table>

<br>
◆　施工設定（管理室親機設定指示書）
<table  border="1">
<tr><td bgcolor="#ebeeef" rowspan="3">呼出機能</td>
	<td bgcolor="#ebeeef" colspan="2">管理室親機呼出</td>
	<td>
		　<input type="radio" name="wOyakiSettei1" value="0" onchange="changecolor('Oya1','0' , 2, __wOyakiSettei1__,0);" __wOyakiSettei1Checked0__><span id="iroOya1val0" __wOyakiSettei1BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei1" value="1" onchange="changecolor('Oya1','1' , 2, __wOyakiSettei1__,0);" __wOyakiSettei1Checked1__><span id="iroOya1val1" __wOyakiSettei1BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">集合玄関機呼出</td>
	<td>
		　<input type="radio" name="wOyakiSettei2" value="0" onchange="changecolor('Oya2','0' , 2, __wOyakiSettei2__,0);" __wOyakiSettei2Checked0__><span id="iroOya2val0" __wOyakiSettei2BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei2" value="1" onchange="changecolor('Oya2','1' , 2, __wOyakiSettei2__,0);" __wOyakiSettei2Checked1__><span id="iroOya2val1" __wOyakiSettei2BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">着信履歴の表示</td>
	<td>
		　<input type="radio" name="wOyakiSettei3" value="0" onchange="changecolor('Oya3','0' , 2, __wOyakiSettei3__,0);" __wOyakiSettei3Checked0__><span id="iroOya3val0" __wOyakiSettei3BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei3" value="1" onchange="changecolor('Oya3','1' , 2, __wOyakiSettei3__,0);" __wOyakiSettei3Checked1__><span id="iroOya3val1" __wOyakiSettei3BG1__>使用する　</span>
	</td>
</tr>
<tr><td bgcolor="#ebeeef" rowspan="4">放送機能
	<td bgcolor="#ebeeef" colspan="2">個別放送</td>
	<td>
		　<input type="radio" name="wOyakiSettei4" value="0" onchange="changecolor('Oya4','0' , 2, __wOyakiSettei4__,0);" __wOyakiSettei4Checked0__><span id="iroOya4val0" __wOyakiSettei4BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei4" value="1" onchange="changecolor('Oya4','1' , 2, __wOyakiSettei4__,0);" __wOyakiSettei4Checked1__><span id="iroOya4val1" __wOyakiSettei4BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">グループ放送</td>
	<td>
		　<input type="radio" name="wOyakiSettei5" value="0" onchange="changecolor('Oya5','0' , 2, __wOyakiSettei5__,0);" __wOyakiSettei5Checked0__><span id="iroOya5val0" __wOyakiSettei5BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei5" value="1" onchange="changecolor('Oya5','1' , 2, __wOyakiSettei5__,0);" __wOyakiSettei5Checked1__><span id="iroOya5val1" __wOyakiSettei5BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">フロア放送</td>
	<td>
		　<input type="radio" name="wOyakiSettei6" value="0" onchange="changecolor('Oya6','0' , 2, __wOyakiSettei6__,0);" __wOyakiSettei6Checked0__><span id="iroOya6val0" __wOyakiSettei6BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei6" value="1" onchange="changecolor('Oya6','1' , 2, __wOyakiSettei6__,0);" __wOyakiSettei6Checked1__><span id="iroOya6val1" __wOyakiSettei6BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">一斉放送</td>
	<td>
		　<input type="radio" name="wOyakiSettei7" value="0" onchange="changecolor('Oya7','0' , 2, __wOyakiSettei7__,0);" __wOyakiSettei7Checked0__><span id="iroOya7val0" __wOyakiSettei7BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei7" value="1" onchange="changecolor('Oya7','1' , 2, __wOyakiSettei7__,0);" __wOyakiSettei7Checked1__><sapn id="iroOya7val1" __wOyakiSettei7BG1__>使用する　</span>
	</td>
</tr>
<tr><td bgcolor="#ebeeef" rowspan="4">音声メッセージ機能
	<td bgcolor="#ebeeef" colspan="2">個別配信</td>
	<td>
		　<input type="radio" name="wOyakiSettei8" value="0" onchange="changecolor('Oya8','0' , 2, __wOyakiSettei8__,0);" __wOyakiSettei8Checked0__><span id="iroOya8val0" __wOyakiSettei8BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei8" value="1" onchange="changecolor('Oya8','1' , 2, __wOyakiSettei8__,0);" __wOyakiSettei8Checked1__><span id="iroOya8val1" __wOyakiSettei8BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">グループ配信</td>
	<td>
		　<input type="radio" name="wOyakiSettei9" value="0" onchange="changecolor('Oya9','0' , 2, __wOyakiSettei9__,0);" __wOyakiSettei9Checked0__><span id="iroOya9val0" __wOyakiSettei9BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei9" value="1" onchange="changecolor('Oya9','1' , 2, __wOyakiSettei9__,0);" __wOyakiSettei9Checked1__><span id="iroOya9val1" __wOyakiSettei9BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">フロア配信</td>
	<td>
		　<input type="radio" name="wOyakiSettei10" value="0" onchange="changecolor('Oya10','0' , 2, __wOyakiSettei10__,0);" __wOyakiSettei10Checked0__><span id="iroOya10val0" __wOyakiSettei10BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei10" value="1" onchange="changecolor('Oya10','1' , 2, __wOyakiSettei10__,0);" __wOyakiSettei10Checked1__><span id="iroOya10val1" __wOyakiSettei10BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">一斉配信</td>
	<td>
		　<input type="radio" name="wOyakiSettei11" value="0" onchange="changecolor('Oya11','0' , 2, __wOyakiSettei11__,0);" __wOyakiSettei11Checked0__><span id="iroOya11val0" __wOyakiSettei11BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei11" value="1" onchange="changecolor('Oya11','1' , 2, __wOyakiSettei11__,0);" __wOyakiSettei11Checked1__><span id="iroOya11val1" __wOyakiSettei11BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="4">画像メッセージ機能</td>
	<td bgcolor="#ebeeef" colspan="2">個別配信</td>
	<td>
		　<input type="radio" name="wOyakiSettei12" value="0" onchange="changecolor('Oya12','0' , 2, __wOyakiSettei12__,0);" __wOyakiSettei12Checked0__><span id="iroOya12val0" __wOyakiSettei12BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei12" value="1" onchange="changecolor('Oya12','1' , 2, __wOyakiSettei12__,0);" __wOyakiSettei12Checked1__><span id="iroOya12val1" __wOyakiSettei12BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">グループ配信</td>
	<td>
		　<input type="radio" name="wOyakiSettei13" value="0" onchange="changecolor('Oya13','0' , 2, __wOyakiSettei13__,0);" __wOyakiSettei13Checked0__><span id="iroOya13val0" __wOyakiSettei13BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei13" value="1" onchange="changecolor('Oya13','1' , 2, __wOyakiSettei13__,0);" __wOyakiSettei13Checked1__><span id="iroOya13val1" __wOyakiSettei13BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">フロア配信</td>
	<td>
		　<input type="radio" name="wOyakiSettei14" value="0" onchange="changecolor('Oya14','0' , 2, __wOyakiSettei14__,0);" __wOyakiSettei14Checked0__><span id="iroOya14val0" __wOyakiSettei14BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei14" value="1" onchange="changecolor('Oya14','1' , 2, __wOyakiSettei14__,0);" __wOyakiSettei14Checked1__><span id="iroOya14val1" __wOyakiSettei14BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">一斉配信</td>
	<td>
		　<input type="radio" name="wOyakiSettei15" value="0" onchange="changecolor('Oya15','0' , 2, __wOyakiSettei15__,0);" __wOyakiSettei15Checked0__><span id="iroOya15val0" __wOyakiSettei15BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei15" value="1" onchange="changecolor('Oya15','1' , 2, __wOyakiSettei15__,0);" __wOyakiSettei15Checked1__><span id="iroOya15val1" __wOyakiSettei15BG1__>使用する　</span>
	</td>
</tr>

<tr>
	<td bgcolor="#ebeeef" rowspan="4">居室お知らせ機能</td>
	<td bgcolor="#ebeeef" colspan="2">メモリーメッセージ</td>
	<td>
		　<input type="radio" name="wOyakiSettei16" value="0" onchange="changecolor('Oya16','0' , 2, __wOyakiSettei16__,0);" __wOyakiSettei16Checked0__><span id="iroOya16val0" __wOyakiSettei16BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei16" value="1" onchange="changecolor('Oya16','1' , 2, __wOyakiSettei16__,0);" __wOyakiSettei16Checked1__><span id="iroOya16val1" __wOyakiSettei16BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">預かり物情報</td>
	<td >
		　<input type="radio" name="wOyakiSettei17" value="0" onchange="changecolor('Oya17','0' , 2, __wOyakiSettei17__,0);" __wOyakiSettei17Checked0__><span id="iroOya17val0" __wOyakiSettei17BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei17" value="1" onchange="changecolor('Oya17','1' , 2, __wOyakiSettei17__,0);" __wOyakiSettei17Checked1__><span id="iroOya17val1" __wOyakiSettei17BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">管理室立ち寄り情報</td>
	<td>
		　<input type="radio" name="wOyakiSettei18" value="0" onchange="changecolor('Oya18','0' , 2, __wOyakiSettei18__,0);" __wOyakiSettei18Checked0__><span id="iroOya18val0" __wOyakiSettei18BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei18" value="1" onchange="changecolor('Oya18','1' , 2, __wOyakiSettei18__,0);" __wOyakiSettei18Checked1__><span id="iroOya18val1" __wOyakiSettei18BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">居室への通知停止時間帯の変更</td>
	<td>
		　<input type="radio" name="wOyakiSettei19" value="0" onchange="changecolor('Oya19','0' , 2, __wOyakiSettei19__,0);" __wOyakiSettei19Checked0__><span id="iroOya19val0" __wOyakiSettei19BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei19" value="1" onchange="changecolor('Oya19','1' , 2, __wOyakiSettei19__,0);" __wOyakiSettei19Checked1__><span id="iroOya19val1" __wOyakiSettei19BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="2">休止モード機能</td>
	<td bgcolor="#ebeeef" colspan="2">休止モード機能</td>
	<td>
		　<input type="radio" name="wOyakiSettei20" value="0" onchange="changecolor('Oya20','0' , 2, __wOyakiSettei20__,0);" __wOyakiSettei20Checked0__><span id="iroOya20val0" __wOyakiSettei20BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei20" value="1" onchange="changecolor('Oya20','1' , 2, __wOyakiSettei20__,0);" __wOyakiSettei20Checked1__><span id="iroOya20val1" __wOyakiSettei20BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">休止モード解除の操作回数</td>
	<td>
		__VolumeLoop__
			　<input type="radio" name="wOyakiSettei21" value="__Volume__" onchange="changecolor('Oya21','__Volume__' , 10, __wOyakiSettei21__,1);" __wOyakiSettei21Checked__><span id="iroOya21val__Volume__" __wOyakiSettei21BG__>　__Volume__　</span>__BR__
		__VolumeLoop__
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="4">警報音機能</td>
	<td bgcolor="#ebeeef" colspan="2">玄関子機での警報鳴動
	<td>
		　<input type="radio" name="wOyakiSettei22" value="0" onchange="changecolor('Oya22','0' , 2, __wOyakiSettei22__,0);" __wOyakiSettei22Checked0__><span id="iroOya22val0" __wOyakiSettei22BG0__>鳴動しない　</span>
		　<input type="radio" name="wOyakiSettei22" value="1" onchange="changecolor('Oya22','1' , 2, __wOyakiSettei22__,0);" __wOyakiSettei22Checked1__><span id="iroOya22val1" __wOyakiSettei22BG1__>鳴動する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">警報音ダンダントーン</td>
	<td>
		　<input type="radio" name="wOyakiSettei23" value="0" onchange="changecolor('Oya23','0' , 2, __wOyakiSettei23__,0);" __wOyakiSettei23Checked0__><span id="iroOya23val0" __wOyakiSettei23BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei23" value="1" onchange="changecolor('Oya23','1' , 2, __wOyakiSettei23__,0);" __wOyakiSettei23Checked1__><span id="iroOya23val1" __wOyakiSettei23BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">警報音声の種類</td>
	<td>
		　<input type="radio" name="wOyakiSettei24" value="0" onchange="changecolor('Oya24','0' , 4, __wOyakiSettei24__,0);" __wOyakiSettei24Checked0__><span id="iroOya24val0" __wOyakiSettei24BG0__>部屋番号　</span>　　　　　　　　　　　
		　<input type="radio" name="wOyakiSettei24" value="1" onchange="changecolor('Oya24','1' , 4, __wOyakiSettei24__,0);" __wOyakiSettei24Checked1__><span id="iroOya24val1" __wOyakiSettei24BG1__>棟（番号）と部屋番号</span>　<br>
		　<input type="radio" name="wOyakiSettei24" value="2" onchange="changecolor('Oya24','2' , 4, __wOyakiSettei24__,0);" __wOyakiSettei24Checked2__><span id="iroOya24val2" __wOyakiSettei24BG2__>棟（アルファベット）と部屋番号</span>　
		　<input type="radio" name="wOyakiSettei24" value="3" onchange="changecolor('Oya24','3' , 4, __wOyakiSettei24__,0);" __wOyakiSettei24Checked3__><span id="iroOya24val3" __wOyakiSettei24BG3__>音声なし（警報音のみ）</span>　
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">警報音抑止時間帯・音量の変更</td>
	<td>
		　<input type="radio" name="wOyakiSettei25" value="0" onchange="changecolor('Oya25','0' , 2, __wOyakiSettei25__,0);" __wOyakiSettei25Checked0__><span id="iroOya25val0" __wOyakiSettei25BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei25" value="1" onchange="changecolor('Oya25','1' , 2, __wOyakiSettei25__,0);" __wOyakiSettei25Checked1__><span id="iroOya25val1" __wOyakiSettei25BG1__>使用する　</span>
	</td>
</tr>

</table>

<br>
◆　施工設定（親機設定指示書2）
<table border="1">
<tr>
	<td bgcolor="#ebeeef" rowspan="4">警報時操作機能</td>
	<td bgcolor="#ebeeef" colspan="2">警報音消音機能</td>
	<td>
		　<input type="radio" name="wOyakiSettei26" value="0" onchange="changecolor('Oya26','0' , 2, __wOyakiSettei26__,0);" __wOyakiSettei26Checked0__><span id="iroOya26val0" __wOyakiSettei26BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei26" value="1" onchange="changecolor('Oya26','1' , 2, __wOyakiSettei26__,0);" __wOyakiSettei26Checked1__><span id="iroOya26val1" __wOyakiSettei26BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">居室の警報音停止機能</td>
	<td>
		　<input type="radio" name="wOyakiSettei27" value="0" onchange="changecolor('Oya27','0' , 2, __wOyakiSettei27__,0);" __wOyakiSettei27Checked0__><span id="iroOya27val0" __wOyakiSettei27BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei27" value="1" onchange="changecolor('Oya27','1' , 2, __wOyakiSettei27__,0);" __wOyakiSettei27Checked1__><span id="iroOya27val1" __wOyakiSettei27BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">緊急対応機能</td>
	<td>
		　<input type="radio" name="wOyakiSettei28" value="0" onchange="changecolor('Oya28','0' , 2, __wOyakiSettei28__,0);" __wOyakiSettei28Checked0__><span id="iroOya28val0" __wOyakiSettei28BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei28" value="1" onchange="changecolor('Oya28','1' , 2, __wOyakiSettei28__,0);" __wOyakiSettei28Checked1__><span id="iroOya28val1" __wOyakiSettei28BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">生活異動警報の居室復旧</td>
	<td>
		　<input type="radio" name="wOyakiSettei29" value="0" onchange="changecolor('Oya29','0' , 2, __wOyakiSettei29__,0);" __wOyakiSettei29Checked0__><span id="iroOya29val0" __wOyakiSettei29BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei29" value="1" onchange="changecolor('Oya29','1' , 2, __wOyakiSettei29__,0);" __wOyakiSettei29Checked1__><span id="iroOya29val1" __wOyakiSettei29BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="2">警報音停止機能</td>
	<td bgcolor="#ebeeef" colspan="2">警報音自動停止タイマー</td>
	<td>
		　<input type="radio" name="wOyakiSettei30" value="0" onchange="changeread(30)&changecolor('Oya30','0' , 2, __wOyakiSettei30__,0);" __wOyakiSettei30Checked0__><span id="iroOya30val0" __wOyakiSettei30BG0__>未使用　</span>
		　<input type="radio" name="wOyakiSettei30" value="1" onchange="changeread(30)&changecolor('Oya30','1' , 2, __wOyakiSettei30__,0);" __wOyakiSettei30Checked1__><span id="iroOya30val1" __wOyakiSettei30BG1__>
			<input type="number" name="wOyakiSettei31" id="number1" value="__dOyakiSettei31__" min="5" max="5940" __OyakiSettei31Disabled__>秒</span>
		　　設定範囲：5秒～5940秒
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">外部警報音停止スイッチ<br>操作可能タイマー</td>
	<td>
		　<input type="radio" name="wOyakiSettei32" value="0" onchange="changeread(32)&changecolor('Oya32','0' , 2, __wOyakiSettei32__,0);" __wOyakiSettei32Checked0__><span id="iroOya32val0" __wOyakiSettei32BG0__>600秒 　</span>
		　<input type="radio" name="wOyakiSettei32" value="1" onchange="changeread(32)&changecolor('Oya32','1' , 2, __wOyakiSettei32__,0);" __wOyakiSettei32Checked1__><span id="iroOya32val1" __wOyakiSettei32BG1__>
			<input type="number" name="wOyakiSettei33" id="number2" value="__dOyakiSettei33__" min="5" max="5940" __OyakiSettei33Disabled__>秒</span>
		　　設定範囲：5秒～5940秒
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="3">解除用の暗証番号機能
	<td bgcolor="#ebeeef" colspan="2">共通暗証番号の変更
	<td>
		　<input type="radio" name="wOyakiSettei34" value="0" onchange="changecolor('Oya34','0' , 2, __wOyakiSettei34__,0);" __wOyakiSettei34Checked0__><span id="iroOya34val0" __wOyakiSettei34BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei34" value="1" onchange="changecolor('Oya34','1' , 2, __wOyakiSettei34__,0);" __wOyakiSettei34Checked1__><span id="iroOya34val1" __wOyakiSettei34BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">個別暗証番号の変更
	<td>
		　<input type="radio" name="wOyakiSettei35" value="0" onchange="changecolor('Oya35','0' , 2, __wOyakiSettei35__,0)" __wOyakiSettei35Checked0__><span id="iroOya35val0" __wOyakiSettei34BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei35" value="1" onchange="changecolor('Oya35','1' , 2, __wOyakiSettei35__,0)" __wOyakiSettei35Checked1__><span id="iroOya35val1" __wOyakiSettei34BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">暗証番号有効時間帯の変更
	<td>
		　<input type="radio" name="wOyakiSettei36" value="0" onchange="changecolor('Oya36','0' , 2, __wOyakiSettei36__,0)" __wOyakiSettei36Checked0__><span id="iroOya36val0" __wOyakiSettei36BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei36" value="1" onchange="changecolor('Oya36','1' , 2, __wOyakiSettei36__,0)" __wOyakiSettei36Checked1__><span id="iroOya36val1" __wOyakiSettei36BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="2">連続・待受中解機能
	<td bgcolor="#ebeeef" colspan="2">連続解錠時間帯の変更
	<td>
		　<input type="radio" name="wOyakiSettei37" value="0" onchange="changecolor('Oya37','0' , 2, __wOyakiSettei37__,0)" __wOyakiSettei37Checked0__><span id="iroOya37val0" __wOyakiSettei37BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei37" value="1" onchange="changecolor('Oya37','1' , 2, __wOyakiSettei37__,0)" __wOyakiSettei37Checked1__><span id="iroOya37val1" __wOyakiSettei37BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">待受中解錠機能
	<td>
		　<input type="radio" name="wOyakiSettei38" value="0" onchange="changecolor('Oya38','0' , 2, __wOyakiSettei38__,0)" __wOyakiSettei38Checked0__><span id="iroOya38val0" __wOyakiSettei38BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei38" value="1" onchange="changecolor('Oya38','1' , 2, __wOyakiSettei38__,0)" __wOyakiSettei38Checked1__><span id="iroOya38val1" __wOyakiSettei38BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="3">居室制御機能
	<td bgcolor="#ebeeef" colspan="2">居室のグループ編成
	<td>
		　<input type="radio" name="wOyakiSettei39" value="0" onchange="changecolor('Oya39','0' , 2, __wOyakiSettei39__,0)" __wOyakiSettei39Checked0__><span id="iroOya39val0" __wOyakiSettei39BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei39" value="1" onchange="changecolor('Oya39','1' , 2, __wOyakiSettei39__,0)" __wOyakiSettei39Checked1__><span id="iroOya39val1" __wOyakiSettei39BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">居室の登録・解除
	<td>
		　<input type="radio" name="wOyakiSettei40" value="0" onchange="changecolor('Oya40','0' , 2, __wOyakiSettei40__,0)" __wOyakiSettei40Checked0__><span id="iroOya40val0" __wOyakiSettei40BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei40" value="1" onchange="changecolor('Oya40','1' , 2, __wOyakiSettei40__,0)" __wOyakiSettei40Checked1__><span id="iroOya40val1" __wOyakiSettei40BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">居室の録画録音消去
	<td>
		　<input type="radio" name="wOyakiSettei41" value="0" onchange="changecolor('Oya41','0' , 2, __wOyakiSettei41__,0)" __wOyakiSettei41Checked0__><span id="iroOya41val0" __wOyakiSettei41BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei41" value="1" onchange="changecolor('Oya41','1' , 2, __wOyakiSettei41__,0)" __wOyakiSettei41Checked1__><span id="iroOya41val1" __wOyakiSettei41BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" colspan="3">管理室親機名称
	<td>
		<table>
		__OyakiSettei42Loop__
		__TRStart1__<td>　<input type="radio" name="wOyakiSettei42" value="__wOyakiSettei42CD__" onchange="changecolor('Oya42','__wOyakiSettei42CD__' , 12, __wOyakiSettei42__,0)" __wOyakiSettei42Checked__><span id="iroOya42val__wOyakiSettei42CD__" __wOyakiSettei42BG__>__KANRISITUOYAKINAME__　</span></td>__TREnd1__
		__OyakiSettei42Loop__
		</table>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" >特殊機能
	<td bgcolor="#ebeeef" colspan="2">居室の自己診断
	<td>
		　<input type="radio" name="wOyakiSettei43" value="0" onchange="changecolor('Oya43','0' , 2, __wOyakiSettei43__,0)" __wOyakiSettei43Checked0__><span id="iroOya43val0" __wOyakiSettei43BG0__>使用しない　</span>
		　<input type="radio" name="wOyakiSettei43" value="1" onchange="changecolor('Oya43','1' , 2, __wOyakiSettei43__,0)" __wOyakiSettei43Checked1__><span id="iroOya43val1" __wOyakiSettei43BG1__>使用する　</span>
	</td>
</tr>
</table>
<br>
◆　施工設定（親機設定指示書3）
<table border="1">
<tr>
	<td bgcolor="#ebeeef" rowspan="8">移報端子割付</td>
	<td bgcolor="#ebeeef">T1</td>
	<td>
		<table>
			__IhouTansiLoop__
			__TRStart2__<td __Colspan__>　<input type="checkbox" name="wOyakiSettei44[]" value="__wOyakiSetteiCD__" id="Oya44val__wOyakiSetteiCD__" onchange="changeBGcolor('Oya44','__wOyakiSetteiCD__' , 27, '__wOyakiSettei44__','Oya44val__wOyakiSetteiCD__',0)" __wOyakiSettei44Checked__><span id="iroOya44val__wOyakiSetteiCD__" __wOyakiSettei44BG__>__IHOUTANSINAME__　</span></td>__TREnd2__
			__IhouTansiLoop__
		</table>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">T2</td>
	<td>
		<table>
			__IhouTansiLoop__
			__TRStart2__<td __Colspan__>　<input type="checkbox" name="wOyakiSettei45[]" value="__wOyakiSetteiCD__" id="Oya45val__wOyakiSetteiCD__" onchange="changeBGcolor('Oya45','__wOyakiSetteiCD__' , 27, '__wOyakiSettei45__','Oya45val__wOyakiSetteiCD__',0)" __wOyakiSettei45Checked__><span id="iroOya45val__wOyakiSetteiCD__" __wOyakiSettei45BG__>__IHOUTANSINAME__　</span></td>__TREnd2__
			__IhouTansiLoop__
		</table>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">T3</td>
	<td>
		<table>
			__IhouTansiLoop__
			__TRStart2__<td __Colspan__>　<input type="checkbox" name="wOyakiSettei46[]" value="__wOyakiSetteiCD__" id="Oya46val__wOyakiSetteiCD__" onchange="changeBGcolor('Oya46','__wOyakiSetteiCD__' , 27, '__wOyakiSettei46__','Oya46val__wOyakiSetteiCD__',0)" __wOyakiSettei46Checked__><span id="iroOya46val__wOyakiSetteiCD__" __wOyakiSettei46BG__>__IHOUTANSINAME__　</span></td>__TREnd2__
			__IhouTansiLoop__
		</table>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">T4</td>
	<td>
		<table>
			__IhouTansiLoop__
			__TRStart2__<td __Colspan__>　<input type="checkbox" name="wOyakiSettei47[]" value="__wOyakiSetteiCD__" id="Oya47val__wOyakiSetteiCD__" onchange="changeBGcolor('Oya47','__wOyakiSetteiCD__' , 27, '__wOyakiSettei47__','Oya47val__wOyakiSetteiCD__',0)" __wOyakiSettei47Checked__><span id="iroOya47val__wOyakiSetteiCD__" __wOyakiSettei47BG__>__IHOUTANSINAME__　</span></td>__TREnd2__
			__IhouTansiLoop__
		</table>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">T5</td>
	<td>
		<table>
			__IhouTansiLoop__
			__TRStart2__<td __Colspan__>　<input type="checkbox" name="wOyakiSettei48[]" value="__wOyakiSetteiCD__" id="Oya48val__wOyakiSetteiCD__" onchange="changeBGcolor('Oya48','__wOyakiSetteiCD__' , 27, '__wOyakiSettei48__','Oya48val__wOyakiSetteiCD__',0)" __wOyakiSettei48Checked__><span id="iroOya48val__wOyakiSetteiCD__" __wOyakiSettei48BG__>__IHOUTANSINAME__　</span></td>__TREnd2__
			__IhouTansiLoop__
		</table>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">T6</td>
	<td>
		<table>
			__IhouTansiLoop__
			__TRStart2__<td __Colspan__>　<input type="checkbox" name="wOyakiSettei49[]" value="__wOyakiSetteiCD__" id="Oya49val__wOyakiSetteiCD__" onchange="changeBGcolor('Oya49','__wOyakiSetteiCD__' , 27, '__wOyakiSettei49__','Oya49val__wOyakiSetteiCD__',0)" __wOyakiSettei49Checked__><span id="iroOya49val__wOyakiSetteiCD__" __wOyakiSettei49BG__>__IHOUTANSINAME__　</span></td>__TREnd2__
			__IhouTansiLoop__
		</table>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">T7</td>
	<td>
		<table>
			__IhouTansiLoop__
			__TRStart2__<td __Colspan__>　<input type="checkbox" name="wOyakiSettei50[]" value="__wOyakiSetteiCD__" id="Oya50val__wOyakiSetteiCD__" onchange="changeBGcolor('Oya50','__wOyakiSetteiCD__' , 27, '__wOyakiSettei50__','Oya50val__wOyakiSetteiCD__',0)" __wOyakiSettei50Checked__><span id="iroOya50val__wOyakiSetteiCD__" __wOyakiSettei50BG__>__IHOUTANSINAME__　</span></td>__TREnd2__
			__IhouTansiLoop__
		</table>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">T8</td>
	<td>
		<table>
			__IhouTansiLoop__
			__TRStart2__<td __Colspan__>　<input type="checkbox" name="wOyakiSettei51[]" value="__wOyakiSetteiCD__" id="Oya51val__wOyakiSetteiCD__" onchange="changeBGcolor('Oya51','__wOyakiSetteiCD__' , 27, '__wOyakiSettei51__','Oya51val__wOyakiSetteiCD__',0)" __wOyakiSettei51Checked__><span id="iroOya51val__wOyakiSetteiCD__" __wOyakiSettei51BG__>__IHOUTANSINAME__　</span></td>__TREnd2__
			__IhouTansiLoop__
		</table>
	</td>
</tr>
</table>
<br>

◆メニュー画面からの設定（住宅情報盤設定指示書）
<table border="1">
<tr>
	<td bgcolor="#ebeeef">日時の設定</td>
	<td>必ず設定</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">放送録音の設定</td>
	<td>　<input type="radio" name="wJutakuMenuSettei1" value="0"  onchange="changecolor('JM1','0',2,__wJutakuMenuSettei1__,0);" __wJutakuMenuSettei1Checked0__><span id="iroJM1val0" __wJutakuMenuSettei1BG0__>録音する　</span>
		　<input type="radio" name="wJutakuMenuSettei1" value="1"  onchange="changecolor('JM1','1',2,__wJutakuMenuSettei1__,0);" __wJutakuMenuSettei1Checked1__><span id="iroJM1val1" __wJutakuMenuSettei1BG1__>録音しない　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">集合玄関機の撮像範囲の設定</td>
	<td>　<input type="radio" name="wJutakuMenuSettei2" value="0"  onchange="changecolor('JM2','0',3,__wJutakuMenuSettei2__,0);" __wJutakuMenuSettei2Checked0__><span id="iroJM2val0" __wJutakuMenuSettei2BG0__>ワイド　</span>
		　<input type="radio" name="wJutakuMenuSettei2" value="1"  onchange="changecolor('JM2','1',3,__wJutakuMenuSettei2__,0);" __wJutakuMenuSettei2Checked1__><span id="iroJM2val1" __wJutakuMenuSettei2BG1__>ズーム　</span>
		　<input type="radio" name="wJutakuMenuSettei2" value="2"  onchange="changecolor('JM2','2',3,__wJutakuMenuSettei2__,0);" __wJutakuMenuSettei2Checked2__><span id="iroJM2val2" __wJutakuMenuSettei2BG2__>オートスクロール　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">玄関子機の撮像範囲の設定</td>
	<td>　<input type="radio" name="wJutakuMenuSettei3" value="0"  onchange="changecolor('JM3','0',3,__wJutakuMenuSettei3__,0);" __wJutakuMenuSettei3Checked0__><span id="iroJM3val0" __wJutakuMenuSettei3BG0__>ワイド　</span>
		　<input type="radio" name="wJutakuMenuSettei3" value="1"  onchange="changecolor('JM3','1',3,__wJutakuMenuSettei3__,0);" __wJutakuMenuSettei3Checked1__><span id="iroJM3val1" __wJutakuMenuSettei3BG1__>ズーム　</span>
		　<input type="radio" name="wJutakuMenuSettei3" value="2"  onchange="changecolor('JM3','2',3,__wJutakuMenuSettei3__,0);" __wJutakuMenuSettei3Checked2__><span id="iroJM3val2" __wJutakuMenuSettei3BG2__>オートスクロール　</span>
	<br>
		　※玄関子機がカメラ付きの場合<br>
		　　↑居住者様へ確認。<br>
		　　↑プリセット位置を設定する。</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">玄関子機からの呼出・通話時の照明の設定</td>
	<td>　<input type="radio" name="wJutakuMenuSettei4" value="0"  onchange="changecolor('JM4',0,2,__wJutakuMenuSettei4__,0);" __wJutakuMenuSettei4Checked0__><span id="iroJM4val0" __wJutakuMenuSettei4BG0__>手動　</span>
		　<input type="radio" name="wJutakuMenuSettei4" value="1"  onchange="changecolor('JM4',1,2,__wJutakuMenuSettei4__,0);" __wJutakuMenuSettei4Checked1__><span id="iroJM4val1" __wJutakuMenuSettei4BG1__>自動　</span>
		　　※玄関子機がカメラ付きの場合
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">モニター時の照明の設定</td>
	<td>　<input type="radio" name="wJutakuMenuSettei5" value="0" id="JM5val0" onchange="changecolor('JM5',0,2,__wJutakuMenuSettei5__,0);" __wJutakuMenuSettei5Checked0__><span id="iroJM5val0" __wJutakuMenuSettei5BG0__>手動　</span>
		　<input type="radio" name="wJutakuMenuSettei5" value="1" id="JM5val1" onchange="changecolor('JM5',1,2,__wJutakuMenuSettei5__,0);" __wJutakuMenuSettei5Checked1__><span id="iroJM5val1" __wJutakuMenuSettei5BG1__>自動　</span>
		　　※玄関子機がカメラ付きの場合
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">※防犯（セット解除・復旧）の<br>暗証番号設定</td>
	<td>　<input type="radio" name="wJutakuMenuSettei6" value="0" id="JM6val0" onchange="changecolor('JM9',0,2,__wJutakuMenuSettei6__,0);" __wJutakuMenuSettei6Checked0__><span id="iroJM9val0" __wJutakuMenuSettei6BG0__>使用する　</span>
		　<input type="radio" name="wJutakuMenuSettei6" value="1" id="JM6val1" onchange="changecolor('JM9',1,2,__wJutakuMenuSettei6__,0);" __wJutakuMenuSettei6Checked1__><span id="iroJM9val1" __wJutakuMenuSettei6BG1__>使用しない　</span>
		　　※使用する場合、暗証番号設定方法を居住者様へ説明する。
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">※外出防犯セット・解除時の<br>留守設定の連動設定</td>
	<td>　<input type="radio" name="wJutakuMenuSettei7" value="1" id="JM7val0" onchange="changecolor('JM7',1,2,__wJutakuMenuSettei7__,0);" __wJutakuMenuSettei7Checked1__><span id="iroJM7val1" __wJutakuMenuSettei7BG1__>連動させる　</span>
		　<input type="radio" name="wJutakuMenuSettei7" value="0" id="JM7val1" onchange="changecolor('JM7',0,2,__wJutakuMenuSettei7__,0);" __wJutakuMenuSettei7Checked0__><span id="iroJM7val0" __wJutakuMenuSettei7BG0__>連動させない　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">※外出防犯セット時の<br>遅延時間の設定</td>
	<td>
		__TienLoop__
			　<input type="radio" name="wJutakuMenuSettei8" value="__Tien__" onchange="changecolor('JM8','__Tien__' , 7, __wJutakuMenuSettei8__,0);" __wJutakuMenuSettei8Checked__><span id="iroJM8val__Tien__" __wJutakuMenuSettei8BG__>　__TIENJIKAN__　</span>__BR__
		__TienLoop__
		　※変更方法を居住者様へ説明する。
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">※外出防犯セット時の<br>帰宅遅延時間の設定</td>
	<td>
		__TienLoop__
			　<input type="radio" name="wJutakuMenuSettei9" value="__Tien__" onchange="changecolor('JM9','__Tien__' , 7, __wJutakuMenuSettei9__,0);" __wJutakuMenuSettei9Checked__><span id="iroJM9val__Tien__" __wJutakuMenuSettei9BG__>　__TIENJIKAN__　</span>__BR__
		__TienLoop__
		　※変更方法を居住者様へ説明する。
	</td>
</tr>

</table>
<br>
◆　施工設定（サービス）
<table border="1">
<tr>
	<td bgcolor="#ebeeef">サービス1　</td>
	<td>
		<table>
		__ServiceLoop1__
			__TRStart_Service__<td>　<input type="checkbox" name="wJutakuServiceTSettei1[]" value="__wJutakuServiceTSetteiCD1__" id="JST1val__wJutakuServiceTSetteiCD1__" onchange="changeBGcolor('JST1',__wJutakuServiceTSetteiCD1__,15,0,'JST1val__wJutakuServiceTSetteiCD1__')&resetcheck('JST1',__wJutakuServiceTSetteiCD1__,15,'JST1val__wJutakuServiceTSetteiCD1__');" __wJutakuServiceTSettei1Checked__><span id="iroJST1val__wJutakuServiceTSetteiCD1__" __wJutakuServiceTSettei1BG__ >__SERVICENAME_VIXUS1Pr__　 </span> </td> __TREnd_Service__
		__ServiceLoop1__
		</table>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">サービス2</td>
	<td>
		<table>
		__ServiceLoop1__
			__TRStart_Service__<td>　<input type="checkbox" name="wJutakuServiceTSettei2[]" value="__wJutakuServiceTSetteiCD1__" id="JST2val__wJutakuServiceTSetteiCD1__" onchange="changeBGcolor('JST2',__wJutakuServiceTSetteiCD1__,15,0,'JST2val__wJutakuServiceTSetteiCD1__')&resetcheck('JST2',__wJutakuServiceTSetteiCD1__,15,'JS2val__wJutakuServiceTSetteiCD1__');" __wJutakuServiceTSettei2Checked__><span id="iroJST2val__wJutakuServiceTSetteiCD1__" __wJutakuServiceTSettei2BG__ >__SERVICENAME_VIXUS1Pr__　</span></td>__TREnd_Service__
		__ServiceLoop1__
		</table>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">サービス3</td>
	<td>
		<table>
		__ServiceLoop1__
			__TRStart_Service__<td>　<input type="checkbox" name="wJutakuServiceTSettei3[]" value="__wJutakuServiceTSetteiCD1__" id="JST3val__wJutakuServiceTSetteiCD1__" onchange="changeBGcolor('JST3',__wJutakuServiceTSetteiCD1__,15,0,'JST3val__wJutakuServiceTSetteiCD1__')&resetcheck('JST3',__wJutakuServiceTSetteiCD1__,15,'JS3val__wJutakuServiceTSetteiCD1__');" __wJutakuServiceTSettei3Checked__><span id="iroJST3val__wJutakuServiceTSetteiCD1__" __wJutakuServiceTSettei3BG__ >__SERVICENAME_VIXUS1Pr__　</span></td>__TREnd_Service__
		__ServiceLoop1__
		</table>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">サービス4</td>
	<td>
		<table>
		__ServiceLoop_4__
			__TRStart5__<td>　<input type="checkbox" name="wJutakuServiceTSettei4[]" value="__wJutakuServiceTSetteiCD_4__"id="JST4val__wJutakuServiceTSetteiCD_4__" onchange="changeBGcolor('JST4',__wJutakuServiceTSetteiCD_4__,16,0,'JST4val__wJutakuServiceTSetteiCD_4__')&resetcheck('JST4',__wJutakuServiceTSetteiCD_4__,16,'JS4val__wJutakuServiceTSetteiCD_4__');" __wJutakuServiceTSettei4Checked__><span id="iroJST4val__wJutakuServiceTSetteiCD_4__" __wJutakuServiceTSettei4BG__ >__SERVICENAME_VIXUS1Pr_4__　</span></td>__TREnd_Service_4__
		__ServiceLoop_4__
		</table>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">サービス5</td>
	<td>
		<table>
		__ServiceLoop_5__
			__TRStart_Service_5__<td>　<input type="checkbox" name="wJutakuServiceTSettei5[]" value="__wJutakuServiceTSetteiCD_5__"id="JST5val__wJutakuServiceTSetteiCD_5__" onchange="changeBGcolor('JST5',__wJutakuServiceTSetteiCD_5__,16,0,'JST5val__wJutakuServiceTSetteiCD_5__')&resetcheck('JST5',__wJutakuServiceTSetteiCD_5__,16,'JS5val__wJutakuServiceTSetteiCD_5__');" __wJutakuServiceTSettei5Checked__><span id="iroJST5val__wJutakuServiceTSetteiCD_5__" __wJutakuServiceTSettei5BG__ >__tmpSERVICENAME_VIXUS1Pr1for5__　</span></td>__TREnd_Service_5__
		__ServiceLoop_5__
		</table>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">サービス6</td>
	<td>
		<table>
		__ServiceLoop_6__
			__TRStart_Service_6__<td>　<input type="checkbox" name="wJutakuServiceTSettei6[]" value="__wJutakuServiceTSetteiCD_6__"id="JST6val__wJutakuServiceTSetteiCD_6__" onchange="changeBGcolor('JST6',__wJutakuServiceTSetteiCD_6__,16,0,'JST6val__wJutakuServiceTSetteiCD_6__')&resetcheck('JST6',__wJutakuServiceTSetteiCD_6__,16,'JS6val__wJutakuServiceTSetteiCD_6__');" __wJutakuServiceTSettei6Checked__><span id="iroJST6val__wJutakuServiceTSetteiCD_6__" __wJutakuServiceTSettei6BG__ >__tmpSERVICENAME_VIXUS1Pr1for6__　</span></td>__TREnd_Service_6__
		__ServiceLoop_6__
		</table>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">サービス7</td>
	<td>
		<table>
		__ServiceLoop_7_8__
			__TRStart_Service_7_8__<td>　<input type="checkbox" name="wJutakuServiceTSettei7[]" value="__wJutakuServiceTSetteiCD_7_8__"id="JST7val__wJutakuServiceTSetteiCD_7_8__" onchange="changeBGcolor('JST7',__wJutakuServiceTSetteiCD_7_8__,16,0,'JST7val__wJutakuServiceTSetteiCD_7_8__')&resetcheck('JST7',__wJutakuServiceTSetteiCD_7_8__,16,'JS7val__wJutakuServiceTSetteiCD_7_8__');" __wJutakuServiceTSettei7Checked__><span id="iroJST7val__wJutakuServiceTSetteiCD_7_8__" __wJutakuServiceTSettei7BG__ >__SERVICENAME_VIXUS1Pr_7_8__　</span></td>__TREnd_Service_7_8__
		__ServiceLoop_7_8__
		</table>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">サービス8</td>
	<td>
		<table>
		__ServiceLoop_7_8__
			__TRStart_Service_7_8__<td>　<input type="checkbox" name="wJutakuServiceTSettei8[]" value="__wJutakuServiceTSetteiCD_7_8__"id="JST8val__wJutakuServiceTSetteiCD_7_8__" onchange="changeBGcolor('JST8',__wJutakuServiceTSetteiCD_7_8__,16,0,'JST8val__wJutakuServiceTSetteiCD_7_8__')&resetcheck('JST8',__wJutakuServiceTSetteiCD_7_8__,16,'JS8val__wJutakuServiceTSetteiCD_7_8__');" __wJutakuServiceTSettei8Checked__><span id="iroJST8val__wJutakuServiceTSetteiCD_7_8__" __wJutakuServiceTSettei8BG__ >__SERVICENAME_VIXUS1Pr_7_8__　</span></td>__TREnd_Service_7_8__
		__ServiceLoop_7_8__
		</table>
	</td>
</tr>
</table>
<br>
◆　施工設定（サービス2）
<table border="1">
<tr>
	<td bgcolor="#ebeeef" rowspan="2">トイレ</td>
	<td bgcolor="#ebeeef">検出条件</td>
	<td>　<input type="radio" name="wJutakuServiceSettei5" value="0"  onchange="changecolor('JS5',0,2,__wJutakuServiceSettei5__,0);" __wJutakuServiceSettei5Checked0__><span id="iroJS5val0" __wJutakuServiceSettei5BG0__>メーク</span>
		　<input type="radio" name="wJutakuServiceSettei5" value="1"  onchange="changecolor('JS5',1,2,__wJutakuServiceSettei5__,0);" __wJutakuServiceSettei5Checked1__><span id="iroJS5val1" __wJutakuServiceSettei5BG1__>ブレーク　</span>
	</td>
	<td bgcolor="#ebeeef">押しボタン方式</td>
	<td>　<input type="radio" name="wJutakuServiceSettei6" value="0"  onchange="changecolor('JS6',0,3,__wJutakuServiceSettei6__,0);" __wJutakuServiceSettei6Checked0__><span id="iroJS6val0" __wJutakuServiceSettei6BG0__>ノンロック式　</span>
		　<input type="radio" name="wJutakuServiceSettei6" value="1"  onchange="changecolor('JS6',1,3,__wJutakuServiceSettei6__,0);" __wJutakuServiceSettei6Checked1__><span id="iroJS6val1" __wJutakuServiceSettei6BG1__>ロック式　</span>
		　<input type="radio" name="wJutakuServiceSettei6" value="2"  onchange="changecolor('JS6',2,3,__wJutakuServiceSettei6__,0);" __wJutakuServiceSettei6Checked2__><span id="iroJS6val2" __wJutakuServiceSettei6BG2__>ワンショット</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">外部出力遅延</td>
	<td>　<input type="radio" name="wJutakuServiceSettei7" value="0"  onchange="changecolor('JS7',0,2,__wJutakuServiceSettei7__,0);" __wJutakuServiceSettei7Checked0__><span id="iroJS7val0" __wJutakuServiceSettei7BG0__>0秒　</span>
		　<input type="radio" name="wJutakuServiceSettei7" value="1"  onchange="changecolor('JS7',1,2,__wJutakuServiceSettei7__,0);" __wJutakuServiceSettei7Checked1__><span id="iroJS7val1" __wJutakuServiceSettei7BG1__>30秒　</span>
	</td>
	<td bgcolor="#ebeeef">玄関子機への出力</td>
	<td>　<input type="radio" name="wJutakuServiceSettei8" value="0"  onchange="changecolor('JS8',0,2,__wJutakuServiceSettei8__,0);" __wJutakuServiceSettei8Checked0__><span id="iroJS8val0" __wJutakuServiceSettei8BG0__>出力しない</span>
		　<input type="radio" name="wJutakuServiceSettei8" value="1"  onchange="changecolor('JS8',1,2,__wJutakuServiceSettei8__,0);" __wJutakuServiceSettei8Checked1__><span id="iroJS8val1" __wJutakuServiceSettei8BG1__>出力する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="2">バス</td>
	<td bgcolor="#ebeeef">検出条件</td>
	<td>　<input type="radio" name="wJutakuServiceSettei9" value="0"  onchange="changecolor('JS9',0,2,__wJutakuServiceSettei9__,0);" __wJutakuServiceSettei9Checked0__><span id="iroJS9val0" __wJutakuServiceSettei9BG0__>メーク</span>
		　<input type="radio" name="wJutakuServiceSettei9" value="1"  onchange="changecolor('JS9',1,2,__wJutakuServiceSettei9__,0);" __wJutakuServiceSettei9Checked1__><span id="iroJS9val1" __wJutakuServiceSettei9BG1__>ブレーク　</span>
	</td>
	<td bgcolor="#ebeeef">押しボタン方式</td>
	<td>　<input type="radio" name="wJutakuServiceSettei10" value="0" onchange="changecolor('JS10',0,3,__wJutakuServiceSettei10__,0);" __wJutakuServiceSettei10Checked0__><span id="iroJS10val0" __wJutakuServiceSettei10BG0__>ノンロック式　</span>
		　<input type="radio" name="wJutakuServiceSettei10" value="1" onchange="changecolor('JS10',1,3,__wJutakuServiceSettei10__,0);" __wJutakuServiceSettei10Checked1__><span id="iroJS10val1" __wJutakuServiceSettei10BG1__>ロック式　</span>
		　<input type="radio" name="wJutakuServiceSettei10" value="2" onchange="changecolor('JS10',2,3,__wJutakuServiceSettei10__,0);" __wJutakuServiceSettei10Checked2__><span id="iroJS10val2" __wJutakuServiceSettei10BG2__>ワンショット</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">外部出力遅延</td>
	<td>　<input type="radio" name="wJutakuServiceSettei11" value="0" onchange="changecolor('JS11',0,2,__wJutakuServiceSettei11__,0);" __wJutakuServiceSettei11Checked0__><span id="iroJS11val0" __wJutakuServiceSettei11BG0__>0秒　</span>
		　<input type="radio" name="wJutakuServiceSettei11" value="1" onchange="changecolor('JS11',1,2,__wJutakuServiceSettei11__,0);" __wJutakuServiceSettei11Checked1__><span id="iroJS11val1" __wJutakuServiceSettei11BG1__>30秒　</span>
	</td>
	<td bgcolor="#ebeeef">玄関子機への出力</td>
	<td>　<input type="radio" name="wJutakuServiceSettei12" value="0" onchange="changecolor('JS12',0,2,__wJutakuServiceSettei12__,0);" __wJutakuServiceSettei12Checked0__><span id="iroJS12val0" __wJutakuServiceSettei12BG0__>出力しない</span>
		　<input type="radio" name="wJutakuServiceSettei12" value="1" onchange="changecolor('JS12',1,2,__wJutakuServiceSettei12__,0);" __wJutakuServiceSettei12Checked1__><span id="iroJS12val1" __wJutakuServiceSettei12BG1__>出力する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="2">汎用</td>
	<td bgcolor="#ebeeef">検出条件</td>
	<td>　<input type="radio" name="wJutakuServiceSettei13" value="0" onchange="changecolor('JS13',0,2,__wJutakuServiceSettei13__,0);" __wJutakuServiceSettei13Checked0__><span id="iroJS13val0" __wJutakuServiceSettei13BG0__>メーク</span>
		　<input type="radio" name="wJutakuServiceSettei13" value="1" onchange="changecolor('JS13',1,2,__wJutakuServiceSettei13__,0);" __wJutakuServiceSettei13Checked1__><span id="iroJS13val1" __wJutakuServiceSettei13BG1__>ブレーク</span>
	</td>
	<td bgcolor="#ebeeef">押しボタン方式</td>
	<td>　<input type="radio" name="wJutakuServiceSettei14" value="0" onchange="changecolor('JS14',0,3,__wJutakuServiceSettei14__,0);" __wJutakuServiceSettei14Checked0__><span id="iroJS14val0" __wJutakuServiceSettei14BG0__>ノンロック式　</span>
		　<input type="radio" name="wJutakuServiceSettei14" value="1" onchange="changecolor('JS14',1,3,__wJutakuServiceSettei14__,0);" __wJutakuServiceSettei14Checked1__><span id="iroJS14val1" __wJutakuServiceSettei14BG1__>ロック式　</span>
		　<input type="radio" name="wJutakuServiceSettei14" value="2" onchange="changecolor('JS14',2,3,__wJutakuServiceSettei14__,0);" __wJutakuServiceSettei14Checked2__><span id="iroJS14val2" __wJutakuServiceSettei14BG2__>ワンショット</span>
	</td> 
</tr>
<tr>
	<td bgcolor="#ebeeef">外部出力遅延</td>
	<td>　<input type="radio" name="wJutakuServiceSettei15" value="0" onchange="changecolor('JS15',0,2,__wJutakuServiceSettei15__,0);" __wJutakuServiceSettei15Checked0__><span id="iroJS15val0" __wJutakuServiceSettei15BG0__>0秒　</span>
		　<input type="radio" name="wJutakuServiceSettei15" value="1" onchange="changecolor('JS15',1,2,__wJutakuServiceSettei15__,0);" __wJutakuServiceSettei15Checked1__><span id="iroJS15val1" __wJutakuServiceSettei15BG1__>30秒　</span>
	</td>
	<td bgcolor="#ebeeef">玄関子機への出力</td>
	<td>　<input type="radio" name="wJutakuServiceSettei16" value="0" onchange="changecolor('JS16',0,2,__wJutakuServiceSettei16__,0);" __wJutakuServiceSettei16Checked0__><span id="iroJS16val0" __wJutakuServiceSettei16BG0__>出力しない</span>
		　<input type="radio" name="wJutakuServiceSettei16" value="1" onchange="changecolor('JS16',1,2,__wJutakuServiceSettei16__,0);" __wJutakuServiceSettei16Checked1__><span id="iroJS16val1" __wJutakuServiceSettei16BG1__>出力する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">漏水</td>
	<td bgcolor="#ebeeef">外部出力遅延</td>
	<td>　<input type="radio" name="wJutakuServiceSettei17" value="0" onchange="changecolor('JS17',0,2,__wJutakuServiceSettei17__,0);" __wJutakuServiceSettei17Checked0__><span id="iroJS17val0" __wJutakuServiceSettei17BG0__>0秒　</span>
		　<input type="radio" name="wJutakuServiceSettei17" value="1" onchange="changecolor('JS17',1,2,__wJutakuServiceSettei17__,0);" __wJutakuServiceSettei17Checked1__><span id="iroJS17val1" __wJutakuServiceSettei17BG1__>30秒　</span>
	</td>
	<td bgcolor="#ebeeef">玄関子機への出力</td>
	<td>　<input type="radio" name="wJutakuServiceSettei18" value="0" onchange="changecolor('JS18',0,2,__wJutakuServiceSettei18__,0);" __wJutakuServiceSettei18Checked0__><span id="iroJS18val0" __wJutakuServiceSettei18BG0__>出力しない</span>
		　<input type="radio" name="wJutakuServiceSettei18" value="1" onchange="changecolor('JS18',1,2,__wJutakuServiceSettei18__,0);" __wJutakuServiceSettei18Checked1__><span id="iroJS18val1" __wJutakuServiceSettei18BG1__>出力する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="2">部屋</td>
	<td bgcolor="#ebeeef">検出条件</td>
	<td>　<input type="radio" name="wJutakuServiceSettei19" value="0" onchange="changecolor('JS19',0,2,__wJutakuServiceSettei19__,0);" __wJutakuServiceSettei19Checked0__><span id="iroJS19val0" __wJutakuServiceSettei19BG0__>メーク</span>
		　<input type="radio" name="wJutakuServiceSettei19" value="1" onchange="changecolor('JS19',1,2,__wJutakuServiceSettei19__,0);" __wJutakuServiceSettei19Checked1__><span id="iroJS19val1" __wJutakuServiceSettei19BG1__>ブレーク</span>
	</td>
	<td bgcolor="#ebeeef">押しボタン方式</td>
	<td>　<input type="radio" name="wJutakuServiceSettei20" value="0" onchange="changecolor('JS20',0,3,__wJutakuServiceSettei20__,0);" __wJutakuServiceSettei20Checked0__><span id="iroJS20val0" __wJutakuServiceSettei20BG0__>ノンロック式　</span>
		　<input type="radio" name="wJutakuServiceSettei20" value="1" onchange="changecolor('JS20',1,3,__wJutakuServiceSettei20__,0);" __wJutakuServiceSettei20Checked1__><span id="iroJS20val1" __wJutakuServiceSettei20BG1__>ロック式　</span>
		　<input type="radio" name="wJutakuServiceSettei20" value="2" onchange="changecolor('JS20',2,3,__wJutakuServiceSettei20__,0);" __wJutakuServiceSettei20Checked2__><span id="iroJS20val2" __wJutakuServiceSettei20BG2__>ワンショット</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">外部出力遅延</td>
	<td>　<input type="radio" name="wJutakuServiceSettei21" value="0" onchange="changecolor('JS21',0,2,__wJutakuServiceSettei21__,0);" __wJutakuServiceSettei21Checked0__><span id="iroJS21val0" __wJutakuServiceSettei21BG0__>0秒　</span>
		　<input type="radio" name="wJutakuServiceSettei21" value="1" onchange="changecolor('JS21',1,2,__wJutakuServiceSettei21__,0);" __wJutakuServiceSettei21Checked1__><span id="iroJS21val1" __wJutakuServiceSettei21BG1__>30秒　</span>
	</td>
	<td bgcolor="#ebeeef">玄関子機への出力</td>
	<td>　<input type="radio" name="wJutakuServiceSettei22" value="0" onchange="changecolor('JS22',0,2,__wJutakuServiceSettei22__,0);" __wJutakuServiceSettei22Checked0__><span id="iroJS22val0" __wJutakuServiceSettei22BG0__>出力しない</span>
		　<input type="radio" name="wJutakuServiceSettei22" value="1" onchange="changecolor('JS22',1,2,__wJutakuServiceSettei22__,0);" __wJutakuServiceSettei22Checked1__><span id="iroJS22val1" __wJutakuServiceSettei22BG1__>出力する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="2">窓</td>
	<td bgcolor="#ebeeef">検出条件</td>
	<td>　<input type="radio" name="wJutakuServiceSettei23" value="0" onchange="changecolor('JS23',0,2,__wJutakuServiceSettei23__,0);" __wJutakuServiceSettei23Checked0__><span id="iroJS23val0" __wJutakuServiceSettei23BG0__>メーク</span>
		　<input type="radio" name="wJutakuServiceSettei23" value="1" onchange="changecolor('JS23',1,2,__wJutakuServiceSettei23__,0);" __wJutakuServiceSettei23Checked1__><span id="iroJS23val1" __wJutakuServiceSettei23BG1__>ブレーク</span>
	</td>
	<td bgcolor="#ebeeef">押しボタン方式</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei24" value="1" __wJutakuServiceSettei24Checked1__><span id="iroJS24val1" __wJutakuServiceSettei24BG1__>ロック式　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">センサ異常時の動作</td>
	<td>　<input type="radio" name="wJutakuServiceSettei25" value="1" __wJutakuServiceSettei25Checked1__><span id="iroJS25val1" __wJutakuServiceSettei25BG1__>即発報　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="2">玄関</td>
	<td bgcolor="#ebeeef">検出条件</td>
	<td>　<input type="radio" name="wJutakuServiceSettei26" value="0" onchange="changecolor('JS26',0,2,__wJutakuServiceSettei26__,0);" __wJutakuServiceSettei26Checked0__><span id="iroJS26val0" __wJutakuServiceSettei26BG0__>メーク</span>
		　<input type="radio" name="wJutakuServiceSettei26" value="1" onchange="changecolor('JS26',1,2,__wJutakuServiceSettei26__,0);" __wJutakuServiceSettei26Checked1__><span id="iroJS26val1" __wJutakuServiceSettei26BG1__>ブレーク</span>
	</td>
	<td bgcolor="#ebeeef">押しボタン方式</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei27" value="1" __wJutakuServiceSettei27Checked1__><span id="iroJS27val1" __wJutakuServiceSettei27BG1__>ロック式　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">センサ異常時の動作</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei28" value="1" __wJutakuServiceSettei28Checked1__><span id="iroJS28val1" __wJutakuServiceSettei28BG1__>予備警報　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="2">防犯</td>
	<td bgcolor="#ebeeef">検出条件</td>
	<td>　<input type="radio" name="wJutakuServiceSettei29" value="0" onchange="changecolor('JS29',0,2,__wJutakuServiceSettei29__,0);" __wJutakuServiceSettei29Checked0__><span id="iroJS29val0" __wJutakuServiceSettei29BG0__>メーク</span>
		　<input type="radio" name="wJutakuServiceSettei29" value="1" onchange="changecolor('JS29',1,2,__wJutakuServiceSettei29__,0);" __wJutakuServiceSettei29Checked1__><span id="iroJS29val1" __wJutakuServiceSettei29BG1__>ブレーク</span>
	</td>
	<td bgcolor="#ebeeef">押しボタン方式</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei30" value="1" __wJutakuServiceSettei30Checked1__><span id="iroJS30val1" __wJutakuServiceSettei30BG1__>ロック式　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">センサ異常時の動作</td>
	<td>　<input type="radio" name="wJutakuServiceSettei31" value="0" onchange="changecolor('JS31',0,2,__wJutakuServiceSettei31__,0);" __wJutakuServiceSettei31Checked0__><span id="iroJS31val0" __wJutakuServiceSettei31BG0__>即発報　</span>
		　<input type="radio" name="wJutakuServiceSettei31" value="1" onchange="changecolor('JS31',1,2,__wJutakuServiceSettei31__,0);" __wJutakuServiceSettei31Checked1__><span id="iroJS31val1" __wJutakuServiceSettei31BG1__>予備警報</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="2">常時防犯</td>
	<td bgcolor="#ebeeef">検出条件</td>
	<td>　<input type="radio" name="wJutakuServiceSettei32" value="0" onchange="changecolor('JS32',0,2,__wJutakuServiceSettei32__,0);" __wJutakuServiceSettei32Checked0__><span id="iroJS32val0" __wJutakuServiceSettei32BG0__>メーク</span>
		　<input type="radio" name="wJutakuServiceSettei32" value="1" onchange="changecolor('JS32',1,2,__wJutakuServiceSettei32__,0);" __wJutakuServiceSettei32Checked1__><span id="iroJS32val1" __wJutakuServiceSettei32BG1__>ブレーク</span>
	</td>
	<td bgcolor="#ebeeef">押しボタン方式</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei33" value="1" onchange="changecolor('JS33',1,2,__wJutakuServiceSettei33__,1);" __wJutakuServiceSettei33Checked1__><span id="iroJS33val1" __wJutakuServiceSettei33BG1__>ロック式　</span>
		　<input type="radio" name="wJutakuServiceSettei33" value="2" onchange="changecolor('JS33',2,2,__wJutakuServiceSettei33__,1);" __wJutakuServiceSettei33Checked2__><span id="iroJS33val2" __wJutakuServiceSettei33BG2__>ワンショット</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">外部出力遅延</td>
	<td>　<input type="radio" name="wJutakuServiceSettei34" value="0" onchange="changecolor('JS34',0,2,__wJutakuServiceSettei34__,0);" __wJutakuServiceSettei34Checked0__><span id="iroJS34val0" __wJutakuServiceSettei34BG0__>0秒　</span>
		　<input type="radio" name="wJutakuServiceSettei34" value="1" onchange="changecolor('JS34',1,2,__wJutakuServiceSettei34__,0);" __wJutakuServiceSettei34Checked1__><span id="iroJS34val1" __wJutakuServiceSettei34BG1__>30秒　</span>
	</td>
	<td bgcolor="#ebeeef">玄関子機への出力</td>
	<td>　<input type="radio" name="wJutakuServiceSettei35" value="0" onchange="changecolor('JS35',0,2,__wJutakuServiceSettei35__,0);" __wJutakuServiceSettei35Checked0__><span id="iroJS35val0" __wJutakuServiceSettei35BG0__>出力しない　</span>
		　<input type="radio" name="wJutakuServiceSettei35" value="1" onchange="changecolor('JS35',1,2,__wJutakuServiceSettei35__,0);" __wJutakuServiceSettei35Checked1__><span id="iroJS35val1" __wJutakuServiceSettei35BG1__>出力する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="2">セット錠</td>
	<td bgcolor="#ebeeef">検出条件</td>
	<td>　<input type="radio" name="wJutakuServiceSettei36" value="0" onchange="changecolor('JS36',0,2,__wJutakuServiceSettei36__,0);" __wJutakuServiceSettei36Checked0__><span id="iroJS36val0" __wJutakuServiceSettei36BG0__>メーク</span>
		　<input type="radio" name="wJutakuServiceSettei36" value="1" onchange="changecolor('JS36',1,2,__wJutakuServiceSettei36__,0);" __wJutakuServiceSettei36Checked1__><span id="iroJS36val1" __wJutakuServiceSettei36BG1__>ブレーク</span>
	</td>
	<td bgcolor="#ebeeef">押しボタン方式</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei37" value="1" __wJutakuServiceSettei37Checked1__><span id="iroJS37val1" __wJutakuServiceSettei37BG1__>ロック式　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">玄関子機への出力</td>
	<td>　<input type="radio" name="wJutakuServiceSettei38" value="0" __wJutakuServiceSettei38Checked0__><span id="iroJS38val0" __wJutakuServiceSettei38BG0__>出力しない　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="2">ｽｲｯﾁｽﾄﾗｲｸ</td>
	<td bgcolor="#ebeeef">検出条件</td>
	<td>　<input type="radio" name="wJutakuServiceSettei39" value="0" onchange="changecolor('JS39',0,2,__wJutakuServiceSettei39__,0);" __wJutakuServiceSettei39Checked0__><span id="iroJS39val0" __wJutakuServiceSettei39BG0__>メーク</span>
		　<input type="radio" name="wJutakuServiceSettei39" value="1" onchange="changecolor('JS39',1,2,__wJutakuServiceSettei39__,0);" __wJutakuServiceSettei39Checked1__><span id="iroJS39val1" __wJutakuServiceSettei39BG1__>ブレーク</span>
	</td>
	<td bgcolor="#ebeeef">押しボタン方式</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei40" value="1" __wJutakuServiceSettei40Checked1__><span id="iroJS40val1" __wJutakuServiceSettei40BG1__>ロック式　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">玄関子機への出力</td>
	<td>　<input type="radio" name="wJutakuServiceSettei41" value="0" __wJutakuServiceSettei41Checked0__><span id="iroJS41val0" __wJutakuServiceSettei41BG0__>出力しない　</span>
	</td>
</tr>         
                                                                                                                           
<tr>
	<td bgcolor="#ebeeef" >非常</td>
	<td bgcolor="#ebeeef">検出条件</td>
	<td>　<input type="radio" name="wJutakuServiceSettei42" value="0" onchange="changecolor('JS42',0,2,__wJutakuServiceSettei42__,0);" __wJutakuServiceSettei42Checked0__><span id="iroJS42val0" __wJutakuServiceSettei42BG0__>メーク</span>
		　<input type="radio" name="wJutakuServiceSettei42" value="1" onchange="changecolor('JS42',1,2,__wJutakuServiceSettei42__,0);" __wJutakuServiceSettei42Checked1__><span id="iroJS42val1" __wJutakuServiceSettei42BG1__>ブレーク</span>
	</td>
	<td bgcolor="#ebeeef">押しボタン方式</td>
	<td>　<input type="radio" name="wJutakuServiceSettei43" value="0" onchange="changecolor('JS43',0,3,__wJutakuServiceSettei43__,0);" __wJutakuServiceSettei43Checked0__><span id="iroJS43val0" __wJutakuServiceSettei43BG0__>ノンロック式　</span>
		　<input type="radio" name="wJutakuServiceSettei43" value="1" onchange="changecolor('JS43',1,3,__wJutakuServiceSettei43__,0);" __wJutakuServiceSettei43Checked1__><span id="iroJS43val1" __wJutakuServiceSettei43BG1__>ロック式　</span>
		　<input type="radio" name="wJutakuServiceSettei43" value="2" onchange="changecolor('JS43',2,3,__wJutakuServiceSettei43__,0);" __wJutakuServiceSettei43Checked2__><span id="iroJS43val2" __wJutakuServiceSettei43BG2__>ワンショット</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="2">緊急コール</td>
	<td bgcolor="#ebeeef">検出条件</td>
	<td>　<input type="radio" name="wJutakuServiceSettei44" value="0" onchange="changecolor('JS44',0,2,__wJutakuServiceSettei44__,0);" __wJutakuServiceSettei44Checked0__><span id="iroJS44val0" __wJutakuServiceSettei44BG0__>メーク</span>
		　<input type="radio" name="wJutakuServiceSettei44" value="1" onchange="changecolor('JS44',1,2,__wJutakuServiceSettei44__,0);" __wJutakuServiceSettei44Checked1__><span id="iroJS44val1" __wJutakuServiceSettei44BG1__>ブレーク</span>
	</td>
	<td bgcolor="#ebeeef">押しボタン方式</td>
	<td>　<input type="radio" name="wJutakuServiceSettei45" value="0" onchange="changecolor('JS45',0,3,__wJutakuServiceSettei45__,0);" __wJutakuServiceSettei45Checked0__><span id="iroJS45val0" __wJutakuServiceSettei45BG0__>ノンロック式　</span>
		　<input type="radio" name="wJutakuServiceSettei45" value="1" onchange="changecolor('JS45',1,3,__wJutakuServiceSettei45__,0);" __wJutakuServiceSettei45Checked1__><span id="iroJS45val1" __wJutakuServiceSettei45BG1__>ロック式　</span>
		　<input type="radio" name="wJutakuServiceSettei45" value="2" onchange="changecolor('JS45',2,3,__wJutakuServiceSettei45__,0);" __wJutakuServiceSettei45Checked2__><span id="iroJS45val2" __wJutakuServiceSettei45BG2__>ワンショット</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">外部出力遅延</td>
	<td>　<input type="radio" name="wJutakuServiceSettei46" value="0" onchange="changecolor('JS46',0,2,__wJutakuServiceSettei46__,0);" __wJutakuServiceSettei46Checked0__><span id="iroJS46val0" __wJutakuServiceSettei46BG0__>0秒　</span>
		　<input type="radio" name="wJutakuServiceSettei46" value="1" onchange="changecolor('JS46',1,2,__wJutakuServiceSettei46__,0);" __wJutakuServiceSettei46Checked1__><span id="iroJS46val1" __wJutakuServiceSettei46BG1__>30秒　</span>
	</td>
	<td bgcolor="#ebeeef">玄関子機への出力</td>
	<td>　<input type="radio" name="wJutakuServiceSettei47" value="0" onchange="changecolor('JS47',0,2,__wJutakuServiceSettei47__,0);" __wJutakuServiceSettei47Checked0__><span id="iroJS47val0" __wJutakuServiceSettei47BG0__>出力しない　</span>
		　<input type="radio" name="wJutakuServiceSettei47" value="1" onchange="changecolor('JS47',1,2,__wJutakuServiceSettei47__,0);" __wJutakuServiceSettei47Checked1__><span id="iroJS47val1" __wJutakuServiceSettei47BG1__>出力する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="2">ワイヤレスコール</td>
	<td bgcolor="#ebeeef">検出条件</td>
	<td>　<input type="radio" name="wJutakuServiceSettei48" value="0" onchange="changecolor('JS48',0,2,__wJutakuServiceSettei48__,0);" __wJutakuServiceSettei48Checked0__><span id="iroJS48val0" __wJutakuServiceSettei48BG0__>メーク</span>
		　<input type="radio" name="wJutakuServiceSettei48" value="1" onchange="changecolor('JS48',1,2,__wJutakuServiceSettei48__,0);" __wJutakuServiceSettei48Checked1__><span id="iroJS48val1" __wJutakuServiceSettei48BG1__>ブレーク</span>
	</td>
	<td bgcolor="#ebeeef">押しボタン方式</td>
	<td>　<input type="radio" name="wJutakuServiceSettei49" value="0" onchange="changecolor('JS49',0,3,__wJutakuServiceSettei49__,0);" __wJutakuServiceSettei49Checked0__><span id="iroJS49val0" __wJutakuServiceSettei49BG0__>ノンロック式　</span>
		　<input type="radio" name="wJutakuServiceSettei49" value="1" onchange="changecolor('JS49',1,3,__wJutakuServiceSettei49__,0);" __wJutakuServiceSettei49Checked1__><span id="iroJS49val1" __wJutakuServiceSettei49BG1__>ロック式　</span>
		　<input type="radio" name="wJutakuServiceSettei49" value="2" onchange="changecolor('JS49',2,3,__wJutakuServiceSettei49__,0);" __wJutakuServiceSettei49Checked2__><span id="iroJS49val2" __wJutakuServiceSettei49BG2__>ワンショット</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">外部出力遅延</td>
	<td>　<input type="radio" name="wJutakuServiceSettei50" value="0" onchange="changecolor('JS50',0,2,__wJutakuServiceSettei50__,0);" __wJutakuServiceSettei50Checked0__><span id="iroJS50val0" __wJutakuServiceSettei50BG0__>0秒　</span>
		　<input type="radio" name="wJutakuServiceSettei50" value="1" onchange="changecolor('JS50',1,2,__wJutakuServiceSettei50__,0);" __wJutakuServiceSettei50Checked1__><span id="iroJS50val1" __wJutakuServiceSettei50BG1__>30秒　</span>
	</td>
	<td bgcolor="#ebeeef">玄関子機への出力</td>
	<td>　<input type="radio" name="wJutakuServiceSettei51" value="0" onchange="changecolor('JS51',0,2,__wJutakuServiceSettei51__,0);" __wJutakuServiceSettei51Checked0__><span id="iroJS51val0" __wJutakuServiceSettei51BG0__>出力しない　</span>
		　<input type="radio" name="wJutakuServiceSettei51" value="1" onchange="changecolor('JS51',1,2,__wJutakuServiceSettei51__,0);" __wJutakuServiceSettei51Checked1__><span id="iroJS51val1" __wJutakuServiceSettei51BG1__>出力する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="2">電池切れ</td>
	<td bgcolor="#ebeeef">検出条件</td>
	<td>　<input type="radio" name="wJutakuServiceSettei52" value="0" onchange="changecolor('JS52',0,2,__wJutakuServiceSettei52__,0);" __wJutakuServiceSettei52Checked0__><span id="iroJS52val0" __wJutakuServiceSettei52BG0__>メーク</span>
		　<input type="radio" name="wJutakuServiceSettei52" value="1" onchange="changecolor('JS52',1,2,__wJutakuServiceSettei52__,0);" __wJutakuServiceSettei52Checked1__><span id="iroJS52val1" __wJutakuServiceSettei52BG1__>ブレーク</span>
	</td>
	<td bgcolor="#ebeeef">押しボタン方式</td>
	<td>　<input type="radio" name="wJutakuServiceSettei53" value="0" onchange="changecolor('JS53',0,3,__wJutakuServiceSettei53__,0);" __wJutakuServiceSettei53Checked0__><span id="iroJS53val0" __wJutakuServiceSettei53BG0__>ノンロック式　</span>
		　<input type="radio" name="wJutakuServiceSettei53" value="1" onchange="changecolor('JS53',1,3,__wJutakuServiceSettei53__,0);" __wJutakuServiceSettei53Checked1__><span id="iroJS53val1" __wJutakuServiceSettei53BG1__>ロック式　</span>
		　<input type="radio" name="wJutakuServiceSettei53" value="2" onchange="changecolor('JS53',2,3,__wJutakuServiceSettei53__,0);" __wJutakuServiceSettei53Checked2__><span id="iroJS53val2" __wJutakuServiceSettei53BG2__>ワンショット</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">外部出力遅延</td>
	<td>　<input type="radio" name="wJutakuServiceSettei54" value="0" onchange="changecolor('JS54',0,2,__wJutakuServiceSettei54__,0);" __wJutakuServiceSettei54Checked0__><span id="iroJS54val0" __wJutakuServiceSettei54BG0__>0秒　</span>
		　<input type="radio" name="wJutakuServiceSettei54" value="1" onchange="changecolor('JS54',1,2,__wJutakuServiceSettei54__,0);" __wJutakuServiceSettei54Checked1__><span id="iroJS54val1" __wJutakuServiceSettei54BG1__>30秒　</span>
	</td>
	<td bgcolor="#ebeeef">玄関子機への出力</td>
	<td>　<input type="radio" name="wJutakuServiceSettei55" value="0" onchange="changecolor('JS55',0,1,__wJutakuServiceSettei55__,0);" __wJutakuServiceSettei55Checked0__><span id="iroJS55val0" __wJutakuServiceSettei55BG0__>出力しない　</span>
	</td>
</tr>
</table>
<br>
◆　施工設定（システム関連）
<table border="1">
<tr>
	<td bgcolor="#ebeeef">エントランスカメラ</td>
	<td>
		　<input type="radio" name="wJutakuSistemSettei1" value="0" onchange="changecolor('JSi1',0,2,__wJutakuSistemSettei1__,0);" __wJutakuSistemSettei1Checked0__><span id="iroJSi1val0" __wJutakuSistemSettei1BG0__>使用しない　</span>
		　<input type="radio" name="wJutakuSistemSettei1" value="1" onchange="changecolor('JSi1',1,2,__wJutakuSistemSettei1__,0);" __wJutakuSistemSettei1Checked1__><span id="iroJSi1val1" __wJutakuSistemSettei1BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">共用部カメラ</td>
	<td>
		　<input type="radio" name="wJutakuSistemSettei2" value="0" onchange="changecolor('JSi2',0,2,__wJutakuSistemSettei2__,0);" __wJutakuSistemSettei2Checked0__><span id="iroJSi2val0" __wJutakuSistemSettei2BG0__>使用しない　</span>
		　<input type="radio" name="wJutakuSistemSettei2" value="1" onchange="changecolor('JSi2',1,2,__wJutakuSistemSettei2__,0);" __wJutakuSistemSettei2Checked1__><span id="iroJSi2val1" __wJutakuSistemSettei2BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">エレベーターコール</td>
	<td>
		　<input type="radio" name="wJutakuSistemSettei3" value="0" onchange="changecolor('JSi3',0,2,__wJutakuSistemSettei3__,0);" __wJutakuSistemSettei3Checked0__><span id="iroJSi3val0" __wJutakuSistemSettei3BG0__>使用しない　</span>
		　<input type="radio" name="wJutakuSistemSettei3" value="1" onchange="changecolor('JSi3',1,2,__wJutakuSistemSettei3__,0);" __wJutakuSistemSettei3Checked1__><span id="iroJSi3val1" __wJutakuSistemSettei3BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">帰宅通知</td>
	<td>
		　<input type="radio" name="wJutakuSistemSettei4" value="0" onchange="changecolor('JSi4',0,2,__wJutakuSistemSettei4__,0);" __wJutakuSistemSettei4Checked0__><span id="iroJSi4val0" __wJutakuSistemSettei4BG0__>使用しない　</span>
		　<input type="radio" name="wJutakuSistemSettei4" value="1" onchange="changecolor('JSi4',1,2,__wJutakuSistemSettei4__,0);" __wJutakuSistemSettei4Checked1__><span id="iroJSi4val1" __wJutakuSistemSettei4BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">画像メッセージ受信</td>
	<td>
		　<input type="radio" name="wJutakuSistemSettei5" value="0" onchange="changecolor('JSi5',0,2,__wJutakuSistemSettei5__,0);" __wJutakuSistemSettei5Checked0__><span id="iroJSi5val0" __wJutakuSistemSettei5BG0__>受信しない　</span>
		　<input type="radio" name="wJutakuSistemSettei5" value="1" onchange="changecolor('JSi5',1,2,__wJutakuSistemSettei5__,0);" __wJutakuSistemSettei5Checked1__><span id="iroJSi5val1" __wJutakuSistemSettei5BG1__>受信する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">音声メッセージ受信</td>
	<td>
		　<input type="radio" name="wJutakuSistemSettei6" value="0" onchange="changecolor('JSi6',0,2,__wJutakuSistemSettei6__,0);" __wJutakuSistemSettei6Checked0__><span id="iroJSi6val0" __wJutakuSistemSettei6BG0__>受信しない　</span>
		　<input type="radio" name="wJutakuSistemSettei6" value="1" onchange="changecolor('JSi6',1,2,__wJutakuSistemSettei6__,0);" __wJutakuSistemSettei6Checked1__><span id="iroJSi6val1" __wJutakuSistemSettei6BG1__>受信する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">緊急地震速報・緊急放送</td>
	<td>
		　<input type="radio" name="wJutakuSistemSettei7" value="0" onchange="changecolor('JSi7',0,2,__wJutakuSistemSettei7__,0);" __wJutakuSistemSettei7Checked0__><span id="iroJSi7val0" __wJutakuSistemSettei7BG0__>受信しない　</span>
		　<input type="radio" name="wJutakuSistemSettei7" value="1" onchange="changecolor('JSi7',1,2,__wJutakuSistemSettei7__,0);" __wJutakuSistemSettei7Checked1__><span id="iroJSi7val1" __wJutakuSistemSettei7BG1__>受信する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">緊急地震速報・緊急放送の<br>玄関子機出力</td>
	<td>
		　<input type="radio" name="wJutakuSistemSettei8" value="0" onchange="changecolor('JSi8',0,2,__wJutakuSistemSettei8__,0);" __wJutakuSistemSettei8Checked0__><span id="iroJSi8val0" __wJutakuSistemSettei8BG0__>出力しない　</span>
		　<input type="radio" name="wJutakuSistemSettei8" value="1" onchange="changecolor('JSi8',1,2,__wJutakuSistemSettei8__,0);" __wJutakuSistemSettei8Checked1__><span id="iroJSi8val1" __wJutakuSistemSettei8BG1__>出力する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">緊急地震速報・緊急放送の<br>音量</td>
	<td>
		　<input type="radio" name="wJutakuSistemSettei9" value="0" onchange="changecolor('JSi9',0,4,__wJutakuSistemSettei9__,0);" __wJutakuSistemSettei9Checked0__><span id="iroJSi9val0" __wJutakuSistemSettei9BG0__>小　</span>
		　<input type="radio" name="wJutakuSistemSettei9" value="1" onchange="changecolor('JSi9',1,4,__wJutakuSistemSettei9__,0);" __wJutakuSistemSettei9Checked1__><span id="iroJSi9val1" __wJutakuSistemSettei9BG1__>中　</span>
		　<input type="radio" name="wJutakuSistemSettei9" value="2" onchange="changecolor('JSi9',2,4,__wJutakuSistemSettei9__,0);" __wJutakuSistemSettei9Checked2__><span id="iroJSi9val2" __wJutakuSistemSettei9BG2__>大　</span>
		　<input type="radio" name="wJutakuSistemSettei9" value="3" onchange="changecolor('JSi9',3,4,__wJutakuSistemSettei9__,0);" __wJutakuSistemSettei9Checked3__><span id="iroJSi9val3" __wJutakuSistemSettei9BG3__>特大</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">EV充電ボックス空き確認</td>
	<td>
		　<input type="radio" name="wJutakuSistemSettei10" value="0" onchange="changecolor('JSi10',0,2,__wJutakuSistemSettei10__,0);" __wJutakuSistemSettei10Checked0__><span id="iroJSi10val0" __wJutakuSistemSettei10BG0__>使用しない　</span>
		　<input type="radio" name="wJutakuSistemSettei10" value="1" onchange="changecolor('JSi10',1,2,__wJutakuSistemSettei10__,0);" __wJutakuSistemSettei10Checked1__><span id="iroJSi10val1" __wJutakuSistemSettei10BG1__>使用する　</span>
	</td>
</tr>

</table>

<br>
◆　施工設定（警報）
<table border="1">
<tr>
	<td bgcolor="#ebeeef">代表移報設定</td>
	<td>
		　<input type="radio" name="wJutakuKeihouSettei1" value="0" onchange="changecolor('JK1','0' , 2, __wJutakuKeihouSettei1__,0);" __wJutakuKeihouSettei1Checked0__><span id="iroJK1val0" __wJutakuKeihouSettei1BG0__>警報のみ　</span>
		　<input type="radio" name="wJutakuKeihouSettei1" value="1" onchange="changecolor('JK1','1' , 2, __wJutakuKeihouSettei1__,0);" __wJutakuKeihouSettei1Checked1__><span id="iroJK1val1" __wJutakuKeihouSettei1BG1__>警報と呼出　</span>
	</td>
<tr>
<tr>
	<td bgcolor="#ebeeef">警報音自動停止</td>
	<td>
		　<input type="radio" name="wJutakuKeihouSettei2" value="0" onchange="changecolor('JK2','0' , 2, __wJutakuKeihouSettei2__,0);" __wJutakuKeihouSettei2Checked0__><span id="iroJK2val0" __wJutakuKeihouSettei2BG0__>停止しない　</span>
		　<input type="radio" name="wJutakuKeihouSettei2" value="1" onchange="changecolor('JK2','1' , 2, __wJutakuKeihouSettei2__,0);" __wJutakuKeihouSettei2Checked1__><span id="iroJK2val1" __wJutakuKeihouSettei2BG1__>停止する　</span>
	</td>
<tr>
<tr>
	<td bgcolor="#ebeeef">非常警報表示</td>
	<td>
		　<input type="radio" name="wJutakuKeihouSettei3" value="0" onchange="changecolor('JK3','0' , 2, __wJutakuKeihouSettei3__,0);" __wJutakuKeihouSettei3Checked0__><span id="iroJK3val0" __wJutakuKeihouSettei3BG0__>通常発報　</span>
		　<input type="radio" name="wJutakuKeihouSettei3" value="1" onchange="changecolor('JK3','1' , 2, __wJutakuKeihouSettei3__,0);" __wJutakuKeihouSettei3Checked1__><span id="iroJK3val1" __wJutakuKeihouSettei3BG1__>ステルス表示</span>
	</td>
<tr>
<tr>
	<td bgcolor="#ebeeef">非常の外部出力遅延時間</td>
	<td>
		　<input type="radio" name="wJutakuKeihouSettei4" value="0" onchange="changecolor('JK4','0' , 2, __wJutakuKeihouSettei4__,0);" __wJutakuKeihouSettei4Checked0__><span id="iroJK4val0" __wJutakuKeihouSettei4BG0__>0秒　</span>
		　<input type="radio" name="wJutakuKeihouSettei4" value="1" onchange="changecolor('JK4','1' , 2, __wJutakuKeihouSettei4__,0);" __wJutakuKeihouSettei4Checked1__><span id="iroJK4val1" __wJutakuKeihouSettei4BG1__>30秒　</span>
	</td>
<tr>
<tr>
	<td bgcolor="#ebeeef">非常の玄関子機出力</td>
	<td>
		　<input type="radio" name="wJutakuKeihouSettei5" value="0" onchange="changecolor('JK5','0' , 2, __wJutakuKeihouSettei5__,0);" __wJutakuKeihouSettei5Checked0__><span id="iroJK5val0" __wJutakuKeihouSettei5BG0__>出力しない　</span>
		　<input type="radio" name="wJutakuKeihouSettei5" value="1" onchange="changecolor('JK5','1' , 2, __wJutakuKeihouSettei5__,0);" __wJutakuKeihouSettei5Checked1__><span id="iroJK5val1" __wJutakuKeihouSettei5BG1__>出力する　</span>
	</td>
<tr>

</table>
<br>
◆　施工設定（管理室）
<table border="1">
<tr>
	<td bgcolor="#ebeeef" rowspan="7">標準設定</td>
	<td bgcolor="#ebeeef" rowspan="5">管理室呼出<br>ボタン設定</td>
	<td bgcolor="#ebeeef">管理室1</td>
	<td>
		　<input type="radio" name="wJutakuKanriSettei1" value="0" onchange="changecolor('JKa1','0' , 3, __wJutakuKanriSettei1__,0);" __wJutakuKanriSettei1Checked0__><span id="iroJKa1val0" __wJutakuKanriSettei1BG0__>メモリ時のみ表示　</span>
		　<input type="radio" name="wJutakuKanriSettei1" value="1" onchange="changecolor('JKa1','1' , 3, __wJutakuKanriSettei1__,0);" __wJutakuKanriSettei1Checked1__><span id="iroJKa1val1" __wJutakuKanriSettei1BG1__>表示する　</span>
		　<input type="radio" name="wJutakuKanriSettei1" value="2" onchange="changecolor('JKa1','2' , 3, __wJutakuKanriSettei1__,0);" __wJutakuKanriSettei1Checked2__><span id="iroJKa1val2" __wJutakuKanriSettei1BG2__>表示しない　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">管理室2</td>
	<td>
		　<input type="radio" name="wJutakuKanriSettei2" value="0" onchange="changecolor('JKa2','0' , 3, __wJutakuKanriSettei2__,0);" __wJutakuKanriSettei2Checked0__><span id="iroJKa2val0" __wJutakuKanriSettei2BG0__>メモリ時のみ表示　</span>
		　<input type="radio" name="wJutakuKanriSettei2" value="1" onchange="changecolor('JKa2','1' , 3, __wJutakuKanriSettei2__,0);" __wJutakuKanriSettei2Checked1__><span id="iroJKa2val1" __wJutakuKanriSettei2BG1__>表示する　</span>
		　<input type="radio" name="wJutakuKanriSettei2" value="2" onchange="changecolor('JKa2','2' , 3, __wJutakuKanriSettei2__,0);" __wJutakuKanriSettei2Checked2__><span id="iroJKa2val2" __wJutakuKanriSettei2BG2__>表示しない　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">管理室3</td>
	<td>
		　<input type="radio" name="wJutakuKanriSettei3" value="0" onchange="changecolor('JKa3','0' , 3, __wJutakuKanriSettei3__,0);" __wJutakuKanriSettei3Checked0__><span id="iroJKa3val0" __wJutakuKanriSettei3BG0__>メモリ時のみ表示　</span>
		　<input type="radio" name="wJutakuKanriSettei3" value="1" onchange="changecolor('JKa3','1' , 3, __wJutakuKanriSettei3__,0);" __wJutakuKanriSettei3Checked1__><span id="iroJKa3val1" __wJutakuKanriSettei3BG1__>表示する　</span>
		　<input type="radio" name="wJutakuKanriSettei3" value="2" onchange="changecolor('JKa3','2' , 3, __wJutakuKanriSettei3__,0);" __wJutakuKanriSettei3Checked2__><span id="iroJKa3val2" __wJutakuKanriSettei3BG2__>表示しない　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">管理室4</td>
	<td>
		　<input type="radio" name="wJutakuKanriSettei4" value="0" onchange="changecolor('JKa4','0' , 3, __wJutakuKanriSettei4__,0);" __wJutakuKanriSettei4Checked0__><span id="iroJKa4val0" __wJutakuKanriSettei4BG0__>メモリ時のみ表示　</span>
		　<input type="radio" name="wJutakuKanriSettei4" value="1" onchange="changecolor('JKa4','1' , 3, __wJutakuKanriSettei4__,0);" __wJutakuKanriSettei4Checked1__><span id="iroJKa4val1" __wJutakuKanriSettei4BG1__>表示する　</span>
		　<input type="radio" name="wJutakuKanriSettei4" value="2" onchange="changecolor('JKa4','2' , 3, __wJutakuKanriSettei4__,0);" __wJutakuKanriSettei4Checked2__><span id="iroJKa4val2" __wJutakuKanriSettei4BG2__>表示しない　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">マンションコントローラー</td>
	<td>
		　<input type="radio" name="wJutakuKanriSettei5" value="0" onchange="changecolor('JKa5','0' , 3, __wJutakuKanriSettei5__,0);" __wJutakuKanriSettei5Checked0__><span id="iroJKa5val0" __wJutakuKanriSettei5BG0__>メモリ時のみ表示　</span>
		　<input type="radio" name="wJutakuKanriSettei5" value="1" onchange="changecolor('JKa5','1' , 3, __wJutakuKanriSettei5__,0);" __wJutakuKanriSettei5Checked1__><span id="iroJKa5val1" __wJutakuKanriSettei5BG1__>表示する　</span>
		　<input type="radio" name="wJutakuKanriSettei5" value="2" onchange="changecolor('JKa5','2' , 3, __wJutakuKanriSettei5__,0);" __wJutakuKanriSettei5Checked2__><span id="iroJKa5val2" __wJutakuKanriSettei5BG2__>表示しない　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="2">優先呼出<br>管理室</td>
	<td bgcolor="#ebeeef">優先呼出管理室</td>
	<td>
		　<input type="radio" name="wJutakuKanriSettei6" value="0" onchange="changecolor('JKa6','0' , 5, __wJutakuKanriSettei6__,0);" __wJutakuKanriSettei6Checked0__><span id="iroJKa6val0" __wJutakuKanriSettei6BG0__>管理室1　</span>
		　<input type="radio" name="wJutakuKanriSettei6" value="1" onchange="changecolor('JKa6','1' , 5, __wJutakuKanriSettei6__,0);" __wJutakuKanriSettei6Checked1__><span id="iroJKa6val1" __wJutakuKanriSettei6BG1__ >管理室2　</span>
		　<input type="radio" name="wJutakuKanriSettei6" value="2" onchange="changecolor('JKa6','2' , 5, __wJutakuKanriSettei6__,0);" __wJutakuKanriSettei6Checked2__><span id="iroJKa6val2" __wJutakuKanriSettei6BG2__>管理室3　</span><br>
		　<input type="radio" name="wJutakuKanriSettei6" value="3" onchange="changecolor('JKa6','3' , 5, __wJutakuKanriSettei6__,0);" __wJutakuKanriSettei6Checked3__><span id="iroJKa6val3" __wJutakuKanriSettei6BG3__>管理室4　</span>
		　<input type="radio" name="wJutakuKanriSettei6" value="4" onchange="changecolor('JKa6','4' , 5, __wJutakuKanriSettei6__,0);" __wJutakuKanriSettei6Checked4__><span id="iroJKa6val4" __wJutakuKanriSettei6BG4__>マンションコントローラー　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">管理室呼出のボタン名称</td>
	<td>
		　<input type="radio" name="wJutakuKanriSettei7" value="0" onchange="changecolor('JKa7','0' , 2, __wJutakuKanriSettei7__,0);" __wJutakuKanriSettei7Checked0__><span id="iroJKa7val0" __wJutakuKanriSettei7BG0__>システム名称指定　</span>
		　<input type="radio" name="wJutakuKanriSettei7" value="1" onchange="changecolor('JKa7','1' , 2, __wJutakuKanriSettei7__,0);" __wJutakuKanriSettei7Checked1__><span id="iroJKa7val1" __wJutakuKanriSettei7BG1__>相談　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="18">標準設定</td>
	<td bgcolor="#ebeeef" rowspan="13">管理室呼出<br>ボタン設定</td>
	<td bgcolor="#ebeeef">管理室C1</td>
	<td>
		　<input type="radio" name="wJutakuKanriSettei8" value="0" onchange="changecolor('JKa8','0' , 3, __wJutakuKanriSettei8__,0);" __wJutakuKanriSettei8Checked0__><span id="iroJKa8val0" __wJutakuKanriSettei8BG0__>メモリ時のみ表示　</span>
		　<input type="radio" name="wJutakuKanriSettei8" value="1" onchange="changecolor('JKa8','1' , 3, __wJutakuKanriSettei8__,0);" __wJutakuKanriSettei8Checked1__><span id="iroJKa8val1" __wJutakuKanriSettei8BG1__>表示する　</span>
		　<input type="radio" name="wJutakuKanriSettei8" value="2" onchange="changecolor('JKa8','2' , 3, __wJutakuKanriSettei8__,0);" __wJutakuKanriSettei8Checked2__><span id="iroJKa8val2" __wJutakuKanriSettei8BG2__>表示しない　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">管理室C2</td>
	<td>
		　<input type="radio" name="wJutakuKanriSettei9" value="0" onchange="changecolor('JKa9','0' , 3, __wJutakuKanriSettei9__,0);" __wJutakuKanriSettei9Checked0__><span id="iroJKa9val0" __wJutakuKanriSettei9BG0__>メモリ時のみ表示　</span>
		　<input type="radio" name="wJutakuKanriSettei9" value="1" onchange="changecolor('JKa9','1' , 3, __wJutakuKanriSettei9__,0);" __wJutakuKanriSettei9Checked1__><span id="iroJKa9val1" __wJutakuKanriSettei9BG1__>表示する　</span>
		　<input type="radio" name="wJutakuKanriSettei9" value="2" onchange="changecolor('JKa9','2' , 3, __wJutakuKanriSettei9__,0);" __wJutakuKanriSettei9Checked2__><span id="iroJKa9val2" __wJutakuKanriSettei9BG2__>表示しない　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">管理室C3</td>
	<td>
		　<input type="radio" name="wJutakuKanriSettei10" value="0" onchange="changecolor('JKa10','0' , 3, __wJutakuKanriSettei10__,0);" __wJutakuKanriSettei10Checked0__><span id="iroJKa10val0" __wJutakuKanriSettei10BG0__>メモリ時のみ表示　</span>
		　<input type="radio" name="wJutakuKanriSettei10" value="1" onchange="changecolor('JKa10','1' , 3, __wJutakuKanriSettei10__,0);" __wJutakuKanriSettei10Checked1__><span id="iroJKa10val1" __wJutakuKanriSettei10BG1__>表示する　</span>
		　<input type="radio" name="wJutakuKanriSettei10" value="2" onchange="changecolor('JKa10','2' , 3, __wJutakuKanriSettei10__,0);" __wJutakuKanriSettei10Checked2__><span id="iroJKa10val2" __wJutakuKanriSettei10BG2__>表示しない　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">管理室C4</td>
	<td>
		　<input type="radio" name="wJutakuKanriSettei11" value="0" onchange="changecolor('JKa11','0' , 3, __wJutakuKanriSettei11__,0);" __wJutakuKanriSettei11Checked0__><span id="iroJKa11val0" __wJutakuKanriSettei11BG0__>メモリ時のみ表示　</span>
		　<input type="radio" name="wJutakuKanriSettei11" value="1" onchange="changecolor('JKa11','1' , 3, __wJutakuKanriSettei11__,0);" __wJutakuKanriSettei11Checked1__><span id="iroJKa11val1" __wJutakuKanriSettei11BG1__>表示する　</span>
		　<input type="radio" name="wJutakuKanriSettei11" value="2" onchange="changecolor('JKa11','2' , 3, __wJutakuKanriSettei11__,0);" __wJutakuKanriSettei11Checked2__><span id="iroJKa11val2" __wJutakuKanriSettei11BG2__>表示しない　</span>
	</td>
</tr>
	<td bgcolor="#ebeeef">管理室C5</td>
	<td>
		　<input type="radio" name="wJutakuKanriSettei12" value="0" onchange="changecolor('JKa12','0' , 3, __wJutakuKanriSettei12__,0);" __wJutakuKanriSettei12Checked0__><span id="iroJKa12val0" __wJutakuKanriSettei12BG0__>メモリ時のみ表示　</span>
		　<input type="radio" name="wJutakuKanriSettei12" value="1" onchange="changecolor('JKa12','1' , 3, __wJutakuKanriSettei12__,0);" __wJutakuKanriSettei12Checked1__><span id="iroJKa12val1" __wJutakuKanriSettei12BG1__>表示する　</span>
		　<input type="radio" name="wJutakuKanriSettei12" value="2" onchange="changecolor('JKa12','2' , 3, __wJutakuKanriSettei12__,0);" __wJutakuKanriSettei12Checked2__><span id="iroJKa12val2" __wJutakuKanriSettei12BG2__>表示しない　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">管理室C6</td>
	<td>
		　<input type="radio" name="wJutakuKanriSettei13" value="0" onchange="changecolor('JKa13','0' , 3, __wJutakuKanriSettei13__,0);" __wJutakuKanriSettei13Checked0__><span id="iroJKa13val0" __wJutakuKanriSettei13BG0__>メモリ時のみ表示　</span>
		　<input type="radio" name="wJutakuKanriSettei13" value="1" onchange="changecolor('JKa13','1' , 3, __wJutakuKanriSettei13__,0);" __wJutakuKanriSettei13Checked1__><span id="iroJKa13val1" __wJutakuKanriSettei13BG1__>表示する　</span>
		　<input type="radio" name="wJutakuKanriSettei13" value="2" onchange="changecolor('JKa13','2' , 3, __wJutakuKanriSettei13__,0);" __wJutakuKanriSettei13Checked2__><span id="iroJKa13val2" __wJutakuKanriSettei13BG2__>表示しない　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">管理室C7</td>
	<td>
		　<input type="radio" name="wJutakuKanriSettei14" value="0" onchange="changecolor('JKa14','0' , 3, __wJutakuKanriSettei14__,0);" __wJutakuKanriSettei14Checked0__><span id="iroJKa14val0" __wJutakuKanriSettei14BG0__>メモリ時のみ表示　</span>
		　<input type="radio" name="wJutakuKanriSettei14" value="1" onchange="changecolor('JKa14','1' , 3, __wJutakuKanriSettei14__,0);" __wJutakuKanriSettei14Checked1__><span id="iroJKa14val1" __wJutakuKanriSettei14BG1__>表示する　</span>
		　<input type="radio" name="wJutakuKanriSettei14" value="2" onchange="changecolor('JKa14','2' , 3, __wJutakuKanriSettei14__,0);" __wJutakuKanriSettei14Checked2__><span id="iroJKa14val2" __wJutakuKanriSettei14BG2__>表示しない　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">管理室C8</td>
	<td>
		　<input type="radio" name="wJutakuKanriSettei15" value="0" onchange="changecolor('JKa15','0' , 3, __wJutakuKanriSettei15__,0);" __wJutakuKanriSettei15Checked0__><span id="iroJKa15val0" __wJutakuKanriSettei15BG0__>メモリ時のみ表示　</span>
		　<input type="radio" name="wJutakuKanriSettei15" value="1" onchange="changecolor('JKa15','1' , 3, __wJutakuKanriSettei15__,0);" __wJutakuKanriSettei15Checked1__><span id="iroJKa15val1" __wJutakuKanriSettei15BG1__>表示する　</span>
		　<input type="radio" name="wJutakuKanriSettei15" value="2" onchange="changecolor('JKa15','2' , 3, __wJutakuKanriSettei15__,0);" __wJutakuKanriSettei15Checked2__><span id="iroJKa15val2" __wJutakuKanriSettei15BG2__>表示しない　</span>
	</td>
</tr>
	<td bgcolor="#ebeeef">管理室1</td>
	<td>
		　<input type="radio" name="wJutakuKanriSettei16" value="0" onchange="changecolor('JKa16','0' , 3, __wJutakuKanriSettei16__,0);" __wJutakuKanriSettei16Checked0__><span id="iroJKa16val0" __wJutakuKanriSettei16BG0__>メモリ時のみ表示　</span>
		　<input type="radio" name="wJutakuKanriSettei16" value="1" onchange="changecolor('JKa16','1' , 3, __wJutakuKanriSettei16__,0);" __wJutakuKanriSettei16Checked1__><span id="iroJKa16val1" __wJutakuKanriSettei16BG1__>表示する　</span>
		　<input type="radio" name="wJutakuKanriSettei16" value="2" onchange="changecolor('JKa16','2' , 3, __wJutakuKanriSettei16__,0);" __wJutakuKanriSettei16Checked2__><span id="iroJKa16val2" __wJutakuKanriSettei16BG2__>表示しない　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">管理室2</td>
	<td>
		　<input type="radio" name="wJutakuKanriSettei17" value="0" onchange="changecolor('JKa17','0' , 3, __wJutakuKanriSettei17__,0);" __wJutakuKanriSettei17Checked0__><span id="iroJKa17val0" __wJutakuKanriSettei17BG0__>メモリ時のみ表示　</span>
		　<input type="radio" name="wJutakuKanriSettei17" value="1" onchange="changecolor('JKa17','1' , 3, __wJutakuKanriSettei17__,0);" __wJutakuKanriSettei17Checked1__><span id="iroJKa17val1" __wJutakuKanriSettei17BG1__>表示する　</span>
		　<input type="radio" name="wJutakuKanriSettei17" value="2" onchange="changecolor('JKa17','2' , 3, __wJutakuKanriSettei17__,0);" __wJutakuKanriSettei17Checked2__><span id="iroJKa17val2" __wJutakuKanriSettei17BG2__>表示しない　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">管理室3</td>
	<td>
		　<input type="radio" name="wJutakuKanriSettei18" value="0" onchange="changecolor('JKa18','0' , 3, __wJutakuKanriSettei18__,0);" __wJutakuKanriSettei18Checked0__><span id="iroJKa18val0" __wJutakuKanriSettei18BG0__>メモリ時のみ表示　</span>
		　<input type="radio" name="wJutakuKanriSettei18" value="1" onchange="changecolor('JKa18','1' , 3, __wJutakuKanriSettei18__,0);" __wJutakuKanriSettei18Checked1__><span id="iroJKa18val1" __wJutakuKanriSettei18BG1__>表示する　</span>
		　<input type="radio" name="wJutakuKanriSettei18" value="2" onchange="changecolor('JKa18','2' , 3, __wJutakuKanriSettei18__,0);" __wJutakuKanriSettei18Checked2__><span id="iroJKa18val2" __wJutakuKanriSettei18BG2__>表示しない　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">管理室4</td>
	<td>
		　<input type="radio" name="wJutakuKanriSettei19" value="0" onchange="changecolor('JKa19','0' , 3, __wJutakuKanriSettei19__,0);" __wJutakuKanriSettei19Checked0__><span id="iroJKa19val0" __wJutakuKanriSettei19BG0__>メモリ時のみ表示　</span>
		　<input type="radio" name="wJutakuKanriSettei19" value="1" onchange="changecolor('JKa19','1' , 3, __wJutakuKanriSettei19__,0);" __wJutakuKanriSettei19Checked1__><span id="iroJKa19val1" __wJutakuKanriSettei19BG1__>表示する　</span>
		　<input type="radio" name="wJutakuKanriSettei19" value="2" onchange="changecolor('JKa19','2' , 3, __wJutakuKanriSettei19__,0);" __wJutakuKanriSettei19Checked2__><span id="iroJKa19val2" __wJutakuKanriSettei19BG2__>表示しない　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">マンションコントローラー</td>
	<td>
		　<input type="radio" name="wJutakuKanriSettei20" value="0" onchange="changecolor('JKa20','0' , 3, __wJutakuKanriSettei20__,0);" __wJutakuKanriSettei20Checked0__><span id="iroJKa20val0" __wJutakuKanriSettei20BG0__>メモリ時のみ表示　</span>
		　<input type="radio" name="wJutakuKanriSettei20" value="1" onchange="changecolor('JKa20','1' , 3, __wJutakuKanriSettei20__,0);" __wJutakuKanriSettei20Checked1__><span id="iroJKa20val1" __wJutakuKanriSettei20BG1__>表示する　</span>
		　<input type="radio" name="wJutakuKanriSettei20" value="2" onchange="changecolor('JKa20','2' , 3, __wJutakuKanriSettei20__,0);" __wJutakuKanriSettei20Checked2__><span id="iroJKa20val2" __wJutakuKanriSettei20BG2__>表示しない　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="4">優先呼出<br>管理室</td>
	<td bgcolor="#ebeeef">優先呼出管理室</td>
	<td>
		　<input type="radio" name="wJutakuKanriSettei21" value="0" onchange="changecolor('JKa21','0' , 13, __wJutakuKanriSettei21__,0);" __wJutakuKanriSettei21Checked0__><span id="iroJKa21val0" __wJutakuKanriSettei21BG0__>管理室C1　</span>
		　<input type="radio" name="wJutakuKanriSettei21" value="1" onchange="changecolor('JKa21','1' , 13, __wJutakuKanriSettei21__,0);" __wJutakuKanriSettei21Checked1__><span id="iroJKa21val1" __wJutakuKanriSettei21BG1__>管理室C2　</span>
		　<input type="radio" name="wJutakuKanriSettei21" value="2" onchange="changecolor('JKa21','2' , 13, __wJutakuKanriSettei21__,0);" __wJutakuKanriSettei21Checked2__><span id="iroJKa21val2" __wJutakuKanriSettei21BG2__>管理室C3　</span>
		　<input type="radio" name="wJutakuKanriSettei21" value="3" onchange="changecolor('JKa21','3' , 13, __wJutakuKanriSettei21__,0);" __wJutakuKanriSettei21Checked3__><span id="iroJKa21val3" __wJutakuKanriSettei21BG3__>管理室C4</span><br>
		　<input type="radio" name="wJutakuKanriSettei21" value="4" onchange="changecolor('JKa21','4' , 13, __wJutakuKanriSettei21__,0);" __wJutakuKanriSettei21Checked4__><span id="iroJKa21val4" __wJutakuKanriSettei21BG4__>管理室C5　</span>
		　<input type="radio" name="wJutakuKanriSettei21" value="5" onchange="changecolor('JKa21','5' , 13, __wJutakuKanriSettei21__,0);" __wJutakuKanriSettei21Checked5__><span id="iroJKa21val5" __wJutakuKanriSettei21BG5__>管理室C6　</span>
		　<input type="radio" name="wJutakuKanriSettei21" value="6" onchange="changecolor('JKa21','6' , 13, __wJutakuKanriSettei21__,0);" __wJutakuKanriSettei21Checked6__><span id="iroJKa21val6" __wJutakuKanriSettei21BG6__>管理室C7　</span>
		　<input type="radio" name="wJutakuKanriSettei21" value="7" onchange="changecolor('JKa21','7' , 13, __wJutakuKanriSettei21__,0);" __wJutakuKanriSettei21Checked7__><span id="iroJKa21val7" __wJutakuKanriSettei21BG7__>管理室C8</span><br>
		　<input type="radio" name="wJutakuKanriSettei21" value="8" onchange="changecolor('JKa21','8' , 13, __wJutakuKanriSettei21__,0);" __wJutakuKanriSettei21Checked8__><span id="iroJKa21val8" __wJutakuKanriSettei21BG8__>管理室1　</span>
		　<input type="radio" name="wJutakuKanriSettei21" value="9" onchange="changecolor('JKa21','9' , 13, __wJutakuKanriSettei21__,0);" __wJutakuKanriSettei21Checked9__><span id="iroJKa21val9" __wJutakuKanriSettei21BG9__>管理室2　</span>
		　<input type="radio" name="wJutakuKanriSettei21" value="10" onchange="changecolor('JKa21','10' , 13, __wJutakuKanriSettei21__,0);" __wJutakuKanriSettei21Checked10__><span id="iroJKa21val10" __wJutakuKanriSettei21BG10__>管理室3　</span>
		　<input type="radio" name="wJutakuKanriSettei21" value="11" onchange="changecolor('JKa21','11' , 13, __wJutakuKanriSettei21__,0);" __wJutakuKanriSettei21Checked11__><span id="iroJKa21val11" __wJutakuKanriSettei21BG11__>管理室4　</span><br>
		　<input type="radio" name="wJutakuKanriSettei21" value="12" onchange="changecolor('JKa21','12' , 13, __wJutakuKanriSettei21__,0);" __wJutakuKanriSettei21Checked12__><span id="iroJKa21val12" __wJutakuKanriSettei21BG12__>マンションコントローラー　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">管理室呼出のボタン名称</td>
	<td>
		　<input type="radio" name="wJutakuKanriSettei22" value="0" onchange="changecolor('JKa22','0' , 2, __wJutakuKanriSettei22__,0);" __wJutakuKanriSettei22Checked0__><span id="iroJKa22val0" __wJutakuKanriSettei22BG0__>システム名称指定　</span>
		　<input type="radio" name="wJutakuKanriSettei22" value="1" onchange="changecolor('JKa22','1' , 2, __wJutakuKanriSettei22__,0);" __wJutakuKanriSettei22Checked1__><span id="iroJKa22val1" __wJutakuKanriSettei22BG1__>相談　</span>
	</td>
</tr>
</table>
<br>
◆　施工設定（防犯）
<table border="1">
<tr>
	<td bgcolor="#ebeeef">親機での設定音・解除音</td>
	<td>
		　<input type="radio" name="wJutakuBouhanSettei1" value="0" onchange="changecolor('JB1','0' , 2, __wJutakuBouhanSettei1__,0);" __wJutakuBouhanSettei1Checked0__><span id="iroJB1val0" __wJutakuBouhanSettei1BG0__>鳴動しない　</span>
		　<input type="radio" name="wJutakuBouhanSettei1" value="1" onchange="changecolor('JB1','1' , 2, __wJutakuBouhanSettei1__,0);" __wJutakuBouhanSettei1Checked1__><span id="iroJB1val1" __wJutakuBouhanSettei1BG1__>鳴動する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">玄関子機での設定音・解除音</td>
	<td>
		　<input type="radio" name="wJutakuBouhanSettei2" value="0" onchange="changecolor('JB2','0' , 2, __wJutakuBouhanSettei2__,0);" __wJutakuBouhanSettei2Checked0__><span id="iroJB2val0" __wJutakuBouhanSettei2BG0__>鳴動しない　</span>
		　<input type="radio" name="wJutakuBouhanSettei2" value="1" onchange="changecolor('JB2','1' , 2, __wJutakuBouhanSettei2__,0);" __wJutakuBouhanSettei2Checked1__><span id="iroJB2val1" __wJutakuBouhanSettei2BG1__>鳴動する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">外部への警報出力遅延時間</td>
	<td>
		　<input type="radio" name="wJutakuBouhanSettei3" value="0" onchange="changecolor('JB3','0' , 5, __wJutakuBouhanSettei3__,0);" __wJutakuBouhanSettei3Checked0__><span id="iroJB3val0" __wJutakuBouhanSettei3BG0__>0秒　</span>
		　<input type="radio" name="wJutakuBouhanSettei3" value="1" onchange="changecolor('JB3','1' , 5, __wJutakuBouhanSettei3__,0);" __wJutakuBouhanSettei3Checked1__><span id="iroJB3val1" __wJutakuBouhanSettei3BG1__>30秒　</span>
		　<input type="radio" name="wJutakuBouhanSettei3" value="2" onchange="changecolor('JB3','2' , 5, __wJutakuBouhanSettei3__,0);" __wJutakuBouhanSettei3Checked2__><span id="iroJB3val2" __wJutakuBouhanSettei3BG2__>60秒　</span>
		　<input type="radio" name="wJutakuBouhanSettei3" value="3" onchange="changecolor('JB3','3' , 5, __wJutakuBouhanSettei3__,0);" __wJutakuBouhanSettei3Checked3__><span id="iroJB3val3" __wJutakuBouhanSettei3BG3__>90秒　</span>
		　<input type="radio" name="wJutakuBouhanSettei3" value="4" onchange="changecolor('JB3','4' , 5, __wJutakuBouhanSettei3__,0);" __wJutakuBouhanSettei3Checked4__><span id="iroJB3val4" __wJutakuBouhanSettei3BG4__>120秒　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">玄関子機への警報出力</td>
	<td>
		　<input type="radio" name="wJutakuBouhanSettei4" value="0" onchange="changecolor('JB4','0' , 2, __wJutakuBouhanSettei4__,0);" __wJutakuBouhanSettei4Checked0__><span id="iroJB4val0" __wJutakuBouhanSettei4BG0__>出力しない　</span>
		　<input type="radio" name="wJutakuBouhanSettei4" value="1" onchange="changecolor('JB4','1' , 2, __wJutakuBouhanSettei4__,0);" __wJutakuBouhanSettei4Checked1__><span id="iroJB4val1" __wJutakuBouhanSettei4BG1__>出力する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">警戒遅延時間の上限</td>
	<td>
		　<input type="radio" name="wJutakuBouhanSettei5" value="0" onchange="changecolor('JB5','0' , 2, __wJutakuBouhanSettei5__,0);" __wJutakuBouhanSettei5Checked0__><span id="iroJB5val0" __wJutakuBouhanSettei5BG0__>300秒　</span>
		　<input type="radio" name="wJutakuBouhanSettei5" value="1" onchange="changecolor('JB5','1' , 2, __wJutakuBouhanSettei5__,0);" __wJutakuBouhanSettei5Checked1__><span id="iroJB5val1" __wJutakuBouhanSettei5BG1__>600秒　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">帰宅通知後の防犯簡易解除</td>
	<td>
		　<input type="radio" name="wJutakuBouhanSettei6" value="0" onchange="changecolor('JB6','0' , 2, __wJutakuBouhanSettei6__,0);" __wJutakuBouhanSettei6Checked0__><span id="iroJB6val0" __wJutakuBouhanSettei6BG0__>使用しない　</span>
		　<input type="radio" name="wJutakuBouhanSettei6" value="1" onchange="changecolor('JB6','1' , 2, __wJutakuBouhanSettei6__,0);" __wJutakuBouhanSettei6Checked1__><span id="iroJB6val1" __wJutakuBouhanSettei6BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">セット錠防犯の遠隔操作</td>
	<td>
		　<input type="radio" name="wJutakuBouhanSettei7" value="0" onchange="changecolor('JB7','0' , 2, __wJutakuBouhanSettei7__,0);" __wJutakuBouhanSettei7Checked0__><span id="iroJB7val0" __wJutakuBouhanSettei7BG0__>使用しない　</span>
		　<input type="radio" name="wJutakuBouhanSettei7" value="1" onchange="changecolor('JB7','1' , 2, __wJutakuBouhanSettei7__,0);" __wJutakuBouhanSettei7Checked1__><span id="iroJB7val1" __wJutakuBouhanSettei7BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">セット錠防犯の室内解除</td>
	<td>
		　<input type="radio" name="wJutakuBouhanSettei8" value="0" onchange="changecolor('JB8','0' , 3, __wJutakuBouhanSettei8__,0);" __wJutakuBouhanSettei8Checked0__><span id="iroJB8val0" __wJutakuBouhanSettei8BG0__>使用しない　</span>
		　<input type="radio" name="wJutakuBouhanSettei8" value="1" onchange="changecolor('JB8','1' , 3, __wJutakuBouhanSettei8__,0);" __wJutakuBouhanSettei8Checked1__><span id="iroJB8val1" __wJutakuBouhanSettei8BG1__>即時解除する　</span>
		　<input type="radio" name="wJutakuBouhanSettei8" value="2" onchange="changecolor('JB8','2' , 3, __wJutakuBouhanSettei8__,0);" __wJutakuBouhanSettei8Checked2__><span id="iroJB8val2" __wJutakuBouhanSettei8BG2__>暗証番号解除する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">セット錠防犯の帰宅予備警報</td>
	<td>
		　<input type="radio" name="wJutakuBouhanSettei9" value="0" onchange="changecolor('JB9','0' , 2, __wJutakuKanriSettei9__,0);" __wJutakuBouhanSettei9Checked0__><span id="iroJB9val0" __wJutakuBouhanSettei9BG0__>使用しない　</span>
		　<input type="radio" name="wJutakuBouhanSettei9" value="1" onchange="changecolor('JB9','1' , 2, __wJutakuBouhanSettei9__,0);" __wJutakuBouhanSettei9Checked1__><span id="iroJB9val1" __wJutakuBouhanSettei9BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">管理用暗証番号</td>
	<td>
		　<input type="radio" name="wJutakuBouhanSettei10" value="0" onchange="changecolor('JB10','0' , 5, __wJutakuBouhanSettei10__,0);" __wJutakuBouhanSettei10Checked0__><span id="iroJB10val0" __wJutakuBouhanSettei10BG0__>使用しない　</span>
		　<input type="radio" name="wJutakuBouhanSettei10" value="1" onchange="changecolor('JB10','1' , 5, __wJutakuBouhanSettei10__,0);" __wJutakuBouhanSettei10Checked1__><span id="iroJB10val1" __wJutakuBouhanSettei10BG1__>5分後使用　</span>
		　<input type="radio" name="wJutakuBouhanSettei10" value="2" onchange="changecolor('JB10','2' , 5, __wJutakuBouhanSettei10__,0);" __wJutakuBouhanSettei10Checked2__><span id="iroJB10val2" __wJutakuBouhanSettei10BG2__>15分後使用　</span>
		　<input type="radio" name="wJutakuBouhanSettei10" value="3" onchange="changecolor('JB10','3' , 5, __wJutakuBouhanSettei10__,0);" __wJutakuBouhanSettei10Checked3__><span id="iroJB10val3" __wJutakuBouhanSettei10BG3__>25分後使用　</span><br>
		　<input type="radio" name="wJutakuBouhanSettei10" value="4" onchange="changecolor('JB10','4' , 5, __wJutakuBouhanSettei10__,0);" __wJutakuBouhanSettei10Checked4__><span id="iroJB10val4" __wJutakuBouhanSettei10BG4__>25分後簡易解除</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">玄関防犯と鍵の連動</td>
	<td>
		　<input type="radio" name="wJutakuBouhanSettei11" value="0" onchange="changecolor('JB11','0' , 2, __wJutakuKanriSettei11__,0);" __wJutakuBouhanSettei11Checked0__><span id="iroJB11val0" __wJutakuBouhanSettei11BG0__>連動させない　</span>
		　<input type="radio" name="wJutakuBouhanSettei11" value="1" onchange="changecolor('JB11','1' , 2, __wJutakuBouhanSettei11__,0);" __wJutakuBouhanSettei11Checked1__><span id="iroJB11val1" __wJutakuBouhanSettei11BG1__>連動させる　</span>
	</td>
</tr>

</table>
<br>
◆　施工設定（玄関子機の設定）
<table border="1">
<tr>
	<td rowspan=2 bgcolor="#ebeeef">　玄関子機の追加　</td>
	<td bgcolor="#ebeeef">　玄関子機の台数　</td>
	<td>
		　<input type="radio" name="wGenkanKokiSettei1" value="0" onchange="changecolor('GK1','0' , 2, __wGenkanKokiSettei1__,0);" __wGenkanKokiSettei1Checked0__><span id="iroGK1val0" __wGenkanKokiSettei1BG0__>一台　</span>
		　<input type="radio" name="wGenkanKokiSettei1" value="1" onchange="changecolor('GK1','1' , 2, __wGenkanKokiSettei1__,0);" __wGenkanKokiSettei1Checked1__><span id="iroGK1val1" __wGenkanKokiSettei1BG1__>二台　</span>
	</td>

	<tr>
	<td bgcolor="#ebeeef">　カメラの配置　</td>
	<td>
		　<input type="radio" name="wGenkanKokiSettei2" value="0" onchange="changecolor('GK1','0' , 2, __wGenkanKokiSettei2__,0);" __wGenkanKokiSettei2Checked0__><span id="iroGK1val0" __wGenkanKokiSettei2BG0__>玄関１　</span>
		　<input type="radio" name="wGenkanKokiSettei2" value="1" onchange="changecolor('GK1','1' , 2, __wGenkanKokiSettei2__,0);" __wGenkanKokiSettei2Checked1__><span id="iroGK1val1" __wGenkanKokiSettei2BG1__>玄関２　</span>
	</td>
</tr> 
<tr>
	<td bgcolor="#ebeeef" colspan="2">　玄関子機ワイド設定　</td>
	<td>
		　<input type="radio" name="wGenkanKokiSettei3" value="0" onchange="changecolor('GK1','0' , 3, __wGenkanKokiSettei3__,0);" __wGenkanKokiSettei3Checked0__><span id="iroGK1val0" __wGenkanKokiSettei3BG0__>ワイド大　</span>
		　<input type="radio" name="wGenkanKokiSettei3" value="1" onchange="changecolor('GK1','1' , 3, __wGenkanKokiSettei3__,0);" __wGenkanKokiSettei3Checked1__><span id="iroGK1val1" __wGenkanKokiSettei3BG1__>ワイド中　</span>
		　<input type="radio" name="wGenkanKokiSettei3" value="2" onchange="changecolor('GK1','2' , 3, __wGenkanKokiSettei3__,0);" __wGenkanKokiSettei3Checked2__><span id="iroGK1val2" __wGenkanKokiSettei3BG2__>ズーム固定　</span>
	</td>
<tr>
<tr>
	<td bgcolor="#ebeeef" colspan="2">　ズーム固定位置設定　</td>
	<td>　玄関子機ワイド設定を「ズーム固定」にした場合のみ設定。</td>
</tr>
</table>

<br>
◆　施工設定（電気錠）
<table border="1">
<tr>
	<td bgcolor="#ebeeef">電気錠の接続</td>
	<td>
		　<input type="radio" name="wDenkijoSettei1" value="0" onchange="changecolor('DJ1',0,4,__wDenkijoSettei1__,0);" __wDenkijoSettei1Checked0__><span id="iroDJ1val0" __wDenkijoSettei1BG0__>接続しない　</span>
		　<input type="radio" name="wDenkijoSettei1" value="1" onchange="changecolor('DJ1',1,4,__wDenkijoSettei1__,0);" __wDenkijoSettei1Checked1__><span id="iroDJ1val1" __wDenkijoSettei1BG1__>玄関1　</span>
		　<input type="radio" name="wDenkijoSettei1" value="2" onchange="changecolor('DJ1',2,4,__wDenkijoSettei1__,0);" __wDenkijoSettei1Checked2__><span id="iroDJ1val2" __wDenkijoSettei1BG2__>玄関2　</span>
		　<input type="radio" name="wDenkijoSettei1" value="3" onchange="changecolor('DJ1',3,4,__wDenkijoSettei1__,0);" __wDenkijoSettei1Checked3__><span id="iroDJ1val3" __wDenkijoSettei1BG3__>玄関1と2　</span>
	</td>
</tr>
</table>
<br>
◆　施工設定（その他）
<table border="1">
<tr>
	<td bgcolor="#ebeeef">補助音響装置での呼出音鳴動</td>
	<td>
		　<input type="radio" name="wSonotaSettei1" value="0" onchange="changecolor('SS1',0,2,__wSonotaSettei1__,0);" __wSonotaSettei1Checked0__><span id="iroSS1val0" __wSonotaSettei1BG0__>鳴動しない　</span>
		　<input type="radio" name="wSonotaSettei1" value="1" onchange="changecolor('SS1',1,2,__wSonotaSettei1__,0);" __wSonotaSettei1Checked1__><span id="iroSS1val1" __wSonotaSettei1BG1__>鳴動する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">録音機能の使用</td>
	<td>
		　<input type="radio" name="wSonotaSettei2" value="0" onchange="changecolor('SS2',0,2,__wSonotaSettei2__,0);" __wSonotaSettei2Checked0__><span id="iroSS2val0" __wSonotaSettei2BG0__>使用しない　</span>
		　<input type="radio" name="wSonotaSettei2" value="1" onchange="changecolor('SS2',1,2,__wSonotaSettei2__,0);" __wSonotaSettei2Checked1__><span id="iroSS2val1" __wSonotaSettei2BG1__>使用する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">放送の音量</td>
	<td>
		　<input type="radio" name="wSonotaSettei3" value="0" onchange="changecolor('SS3',0,5,__wSonotaSettei3__,0);" __wSonotaSettei3Checked0__><span id="iroSS3val0" __wSonotaSettei3BG0__>切　</span>
		　<input type="radio" name="wSonotaSettei3" value="1" onchange="changecolor('SS3',1,5,__wSonotaSettei3__,0);" __wSonotaSettei3Checked1__><span id="iroSS3val1" __wSonotaSettei3BG1__>小　</span>
		　<input type="radio" name="wSonotaSettei3" value="2" onchange="changecolor('SS3',2,5,__wSonotaSettei3__,0);" __wSonotaSettei3Checked2__><span id="iroSS3val2" __wSonotaSettei3BG2__>中　</span>
		　<input type="radio" name="wSonotaSettei3" value="3" onchange="changecolor('SS3',3,5,__wSonotaSettei3__,0);" __wSonotaSettei3Checked3__><span id="iroSS3val3" __wSonotaSettei3BG3__>大　</span>
		　<input type="radio" name="wSonotaSettei3" value="4" onchange="changecolor('SS3',4,5,__wSonotaSettei3__,0);" __wSonotaSettei3Checked4__><span id="iroSS3val4" __wSonotaSettei3BG4__>特大　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">英語切替設定の表示</td>
	<td>
		　<input type="radio" name="wSonotaSettei4" value="0" onchange="changecolor('SS4',0,2,__wSonotaSettei4__,0);" __wSonotaSettei4Checked0__><span id="iroSS4val0" __wSonotaSettei4BG0__>表示しない　</span>
		　<input type="radio" name="wSonotaSettei4" value="1" onchange="changecolor('SS4',1,2,__wSonotaSettei4__,0);" __wSonotaSettei4Checked1__><span id="iroSS4val1" __wSonotaSettei4BG1__>表示する　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">ホーム画面固定の設定</td>
	<td>
		　<input type="radio" name="wSonotaSettei5" value="0" onchange="changecolor('SS5',0,6,__wSonotaSettei5__,0);" __wSonotaSettei5Checked0__><span id="iroSS5val0" __wSonotaSettei5BG0__>ユーザー選択　</span>
		　<input type="radio" name="wSonotaSettei5" value="1" onchange="changecolor('SS5',1,6,__wSonotaSettei5__,0);" __wSonotaSettei5Checked1__><span id="iroSS5val1" __wSonotaSettei5BG1__>通常画面固定　</span>
		　<input type="radio" name="wSonotaSettei5" value="2" onchange="changecolor('SS5',2,6,__wSonotaSettei5__,0);" __wSonotaSettei5Checked2__><span id="iroSS5val2" __wSonotaSettei5BG2__>防犯重視固定　</span>
<br>	　<input type="radio" name="wSonotaSettei5" value="3" onchange="changecolor('SS5',3,6,__wSonotaSettei5__,0);" __wSonotaSettei5Checked3__><span id="iroSS5val3" __wSonotaSettei5BG3__>　シニア1固定　</span>
		　<input type="radio" name="wSonotaSettei5" value="4" onchange="changecolor('SS5',4,6,__wSonotaSettei5__,0);" __wSonotaSettei5Checked4__><span id="iroSS5val4" __wSonotaSettei5BG4__>シニア2固定　</span>
		　<input type="radio" name="wSonotaSettei5" value="5" onchange="changecolor('SS5',5,6,__wSonotaSettei5__,0);" __wSonotaSettei5Checked5__><span id="iroSS5val5" __wSonotaSettei5BG5__>シニア3固定　</span>
	</td>
<tr>
</table>

<br>
<br>
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
<!--<input type="submit" value="登録">-->
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


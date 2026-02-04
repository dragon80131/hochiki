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
<!--<form name="form1" method="POST" action="s_rakutouchkikisetei_confirm.php" >-->
<form name="form1" method="POST" action="./doc/s_kikiseteirakutouch_finish.php?rKey=__rKey__" >
<h6>らくタッチ</h6>

<a href="s_kikisetei.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜戻る</a>
<br>
<br>


◆<a href="s_kikiseteirakutouch_copy.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">過去の案件の機器情報をコピーする</a>




<br>
<br>
◆設定<span style="background-color:#A9F5A9">　　　</span>は初期設定値
<br>
◆設定<span style="background-color:#ffbab3">　　　</span>は変更値
<br><br>



◆　施工設定（集合玄関機設定指示書）
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
<table  border="1">
<!-- ここから -->
<tr>
	<td bgcolor="#ebeeef" rowspan="3">ＳＷ1</td>
	<td bgcolor="#ebeeef" align="center" rowspan="1"> 1~3 </td>
	<td bgcolor="#ebeeef"colspan="2">呼出番号の設定</td>
	<td>&nbsp;</td>
</tr>
<tr>
<td bgcolor="#ebeeef" align="center" rowspan="1">  4  </td>
	<td bgcolor="#ebeeef" colspan="2">キー入力音の設定</td>
	<td colspan="2">
		<input type="radio" name="wShugoSettei1" value="1" onchange="changecolor(1,'1' , 2, __wShugoSettei1__,0);" __wShugoSettei1Checked1__><span id="iro1val1" __wShugoSettei1BG1__> ON : 有 </span>
		<input type="radio" name="wShugoSettei1" value="0" onchange="changecolor(1,'0' , 2, __wShugoSettei1__,0);" __wShugoSettei1Checked0__><span id="iro1val0" __wShugoSettei1BG0__> OFF : 無 </span>
	</td>
</tr>
<tr>
<td bgcolor="#ebeeef" align="center" rowspan="1">  5  </td>
	<td bgcolor="#ebeeef" colspan="2">管理室呼出の設定</td>
	<td colspan="2">
		<input type="radio" name="wShugoSettei2" value="1" onchange="changecolor(2,'1' , 2, __wShugoSettei2__,0);" __wShugoSettei2Checked1__><span id="iro2val1" __wShugoSettei2BG1__> ON : 有 </span>
		<input type="radio" name="wShugoSettei2" value="0" onchange="changecolor(2,'0' , 2, __wShugoSettei2__,0);" __wShugoSettei2Checked0__><span id="iro2val0" __wShugoSettei2BG0__> OFF : 無 </span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="4">ＳＷ2</td>
	<td bgcolor="#ebeeef" align="center" rowspan="1">  2  </td>
	<td bgcolor="#ebeeef"colspan="2">電気錠プリトーンの設定</td>
	<td colspan= "2">
		<input type="radio" name="wShugoSettei3" value="1" onchange="changecolor(3,'1' , 2, __wShugoSettei3__,0);" __wShugoSettei3Checked1__><span id="iro3val1" __wShugoSettei3BG1__> ON : 有 </span>
		<input type="radio" name="wShugoSettei3" value="0" onchange="changecolor(3,'0' , 2, __wShugoSettei3__,0);" __wShugoSettei3Checked0__><span id="iro3val0" __wShugoSettei3BG0__> OFF : 無 </span>
	</td>
</tr>
<tr>
<td bgcolor="#ebeeef" align="center" rowspan="1">  4  </td>
	<td bgcolor="#ebeeef" colspan="2">宅配集荷プリトーン設定</td>
	<td colspan="2">
		<input type="radio" name="wShugoSettei4" value="1" onchange="changecolor(4,'1' , 2, __wShugoSettei4__,0);" __wShugoSettei4Checked1__><span id="iro4val1" __wShugoSettei4BG1__> ON : 有 </span>
		<input type="radio" name="wShugoSettei4" value="0" onchange="changecolor(4,'0' , 2, __wShugoSettei4__,0);" __wShugoSettei4Checked0__><span id="iro4val0" __wShugoSettei4BG0__> OFF : 無 </span>
	</td>
</tr>
<tr>
<td bgcolor="#ebeeef" align="center" rowspan="1">  5  </td>
	<td bgcolor="#ebeeef" colspan="2">宅配集荷表示の設定</td>
	<td colspan="2">
		<input type="radio" name="wShugoSettei5" value="1" onchange="changecolor(5,'1' , 2, __wShugoSettei5__,0);" __wShugoSettei5Checked1__><span id="iro5val1" __wShugoSettei5BG1__> ON : 有 </span>
		<input type="radio" name="wShugoSettei5" value="0" onchange="changecolor(5,'0' , 2, __wShugoSettei5__,0);" __wShugoSettei5Checked0__><span id="iro5val0" __wShugoSettei5BG0__> OFF : 無 </span>
	</td>
</tr>
<tr>
<td bgcolor="#ebeeef" align="center" rowspan="1">  6  </td>
	<td bgcolor="#ebeeef" colspan="2">プリトーン音量の設定</td>
	<td colspan="2">
		<input type="radio" name="wShugoSettei6" value="1" onchange="changecolor(6,'1' , 2, __wShugoSettei6__,0);" __wShugoSettei6Checked1__><span id="iro6val1" __wShugoSettei6BG1__> ON : 大 </span>
		<input type="radio" name="wShugoSettei6" value="0" onchange="changecolor(6,'0' , 2, __wShugoSettei6__,0);" __wShugoSettei6Checked0__><span id="iro6val0" __wShugoSettei6BG0__> OFF : 標準</span>
	</td>
</tr>
</table>

<br>
◆　施工設定（撮像位置の設定）
<table  border="1">
<tr>
	<td bgcolor="#ebeeef" align="left" >カメラ撮像位置の設定　　　　　　</td>
	<td> 現地で要確認　　　　　　</td>
	<!-- <td colspan="2"> 現地で要確認    </td> -->
</tr>
</table>

<br>
◆メニュー画面からの設定（住宅情報盤設定指示書）
<table border="1">
<tr>
	<td bgcolor="#ebeeef">日時の設定</td>
	<td>　必ず設定</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">防犯の解除操作（暗唱番号）の設定</td>
	<td>　<input type="radio" name="wJutakuMenuSettei1" value="1" id="JM1val1" onchange="changecolor('JM1','1',2,__wJutakuMenuSettei1__,0);" __wJutakuMenuSettei1Checked1__><span id="iroJM1val1" __wJutakuMenuSettei1BG1__>使用する　</span>
		　<input type="radio" name="wJutakuMenuSettei1" value="0" id="JM1val0" onchange="changecolor('JM1','0',2,__wJutakuMenuSettei1__,0);" __wJutakuMenuSettei1Checked0__><span id="iroJM1val0" __wJutakuMenuSettei1BG0__>使用しない　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">警戒遅延時間の設定</td>
	<td>　<input type="radio" name="wJutakuMenuSettei2" value="0" id="JM2val0" onchange="changecolor('JM2',0,7,__wJutakuMenuSettei2__,0);" __wJutakuMenuSettei2Checked0__><span id="iroJM2val0" __wJutakuMenuSettei2BG0__>0秒　</span>
		　<input type="radio" name="wJutakuMenuSettei2" value="1" id="JM2val1" onchange="changecolor('JM2',1,7,__wJutakuMenuSettei2__,0);" __wJutakuMenuSettei2Checked1__><span id="iroJM2val1" __wJutakuMenuSettei2BG1__>30秒　</span>
		　<input type="radio" name="wJutakuMenuSettei2" value="2" id="JM2val2" onchange="changecolor('JM2',2,7,__wJutakuMenuSettei2__,0);" __wJutakuMenuSettei2Checked2__><span id="iroJM2val2" __wJutakuMenuSettei2BG2__>60秒　</span>
		　<input type="radio" name="wJutakuMenuSettei2" value="3" id="JM2val3" onchange="changecolor('JM2',3,7,__wJutakuMenuSettei2__,0);" __wJutakuMenuSettei2Checked3__><span id="iroJM2val3" __wJutakuMenuSettei2BG3__>90秒　</span><br>
		　<input type="radio" name="wJutakuMenuSettei2" value="4" id="JM2val4" onchange="changecolor('JM2',4,7,__wJutakuMenuSettei2__,0);" __wJutakuMenuSettei2Checked4__><span id="iroJM2val4" __wJutakuMenuSettei2BG4__>2分　</span>
		　<input type="radio" name="wJutakuMenuSettei2" value="5" id="JM2val5" onchange="changecolor('JM2',5,7,__wJutakuMenuSettei2__,0);" __wJutakuMenuSettei2Checked5__><span id="iroJM2val5" __wJutakuMenuSettei2BG5__>5分　　 </span>
		　<input type="radio" name="wJutakuMenuSettei2" value="6" id="JM2val6" onchange="changecolor('JM2',6,7,__wJutakuMenuSettei2__,0);" __wJutakuMenuSettei2Checked6__><span id="iroJM2val6" __wJutakuMenuSettei2BG6__>10分　</span>
		　※変更方法を居住者様へ説明する。
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">発報遅延時間の設定</td>
	<td>　<input type="radio" name="wJutakuMenuSettei3" value="0" id="JM3val0" onchange="changecolor('JM3',0,7,__wJutakuMenuSettei3__,0);" __wJutakuMenuSettei3Checked0__><span id="iroJM3val0" __wJutakuMenuSettei3BG0__>0秒　</span>
		　<input type="radio" name="wJutakuMenuSettei3" value="1" id="JM3val1" onchange="changecolor('JM3',1,7,__wJutakuMenuSettei3__,0);" __wJutakuMenuSettei3Checked1__><span id="iroJM3val1" __wJutakuMenuSettei3BG1__>30秒　</span>
		　<input type="radio" name="wJutakuMenuSettei3" value="2" id="JM3val2" onchange="changecolor('JM3',2,7,__wJutakuMenuSettei3__,0);" __wJutakuMenuSettei3Checked2__><span id="iroJM3val2" __wJutakuMenuSettei3BG2__>60秒　</span>
		　<input type="radio" name="wJutakuMenuSettei3" value="3" id="JM3val3" onchange="changecolor('JM3',3,7,__wJutakuMenuSettei3__,0);" __wJutakuMenuSettei3Checked3__><span id="iroJM3val3" __wJutakuMenuSettei3BG3__>90秒　</span><br>
		　<input type="radio" name="wJutakuMenuSettei3" value="4" id="JM3val4" onchange="changecolor('JM3',4,7,__wJutakuMenuSettei3__,0);" __wJutakuMenuSettei3Checked4__><span id="iroJM3val4" __wJutakuMenuSettei3BG4__>2分　　</span>
		  <input type="radio" name="wJutakuMenuSettei3" value="5" id="JM3val5" onchange="changecolor('JM3',5,7,__wJutakuMenuSettei3__,0);" __wJutakuMenuSettei3Checked5__><span id="iroJM3val5" __wJutakuMenuSettei3BG5__>5分　　　</span>
		  <input type="radio" name="wJutakuMenuSettei3" value="6" id="JM3val6" onchange="changecolor('JM3',6,7,__wJutakuMenuSettei3__,0);" __wJutakuMenuSettei3Checked6__><span id="iroJM3val6" __wJutakuMenuSettei3BG6__>10分　</span>
		　※変更方法を居住者様へ説明する。
	</td>
</tr>
</table>
<br>

◆カメラ付玄関子機の映像設定（VJ-KDP使用時）
<table border="1">
<tr>
	<td bgcolor="#ebeeef">撮像位置のプリセット</td>
	<td>現場で要確認 ※初期設定：「ワイド小」</td>
	
</tr>
<tr>
	<td bgcolor="#ebeeef">夜間照明LEDの自動点灯の設定</td>
	<td>
		　<input type="radio" name="wJutakuMenuSettei4" value="0" onchange="changecolor('JM4',0,2,__wJutakuMenuSettei4__,0);" __wJutakuMenuSettei4Checked0__><span id="iroJM4val0" __wJutakuMenuSettei4BG0__>自動点灯しない　</span>
		　<input type="radio" name="wJutakuMenuSettei4" value="1" onchange="changecolor('JM4',1,2,__wJutakuMenuSettei4__,0);" __wJutakuMenuSettei4Checked1__><span id="iroJM4val1" __wJutakuMenuSettei4BG1__>自動点灯する　</span>
	</td>
</tr>
</table>
<br>
◆施工設定（玄関子機「VH-DEP」「VH-KDEP-N」使用時）
<table border="1">
<tr>
	<td bgcolor="#ebeeef">映像住戸アダプター</td>
	<td>
		　<input type="radio" name="wJutakuKanriSettei1" value="0" onchange="changecolor('JKa1',0,2,__wJutakuKanriSettei1__,0);" __wJutakuKanriSettei1Checked0__><span id="iroJKa1val0" __wJutakuKanriSettei1BG0__>別設置　</span>
		　<input type="radio" name="wJutakuKanriSettei1" value="1" onchange="changecolor('JKa1',1,2,__wJutakuKanriSettei1__,0);" __wJutakuKanriSettei1Checked1__><span id="iroJKa1val1" __wJutakuKanriSettei1BG1__>内蔵　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">　「内蔵」を選択した場合の玄関子機のカメラ</td>
	<td>
		　<input type="radio" name="wJutakuKanriSettei2" value="1" onchange="changecolor('JKa2',1,2,__wJutakuKanriSettei2__,0);" __wJutakuKanriSettei2Checked1__><span id="iroJKa2val1" __wJutakuKanriSettei2BG1__>なし　</span>
		　<input type="radio" name="wJutakuKanriSettei2" value="0" onchange="changecolor('JKa2',0,2,__wJutakuKanriSettei2__,0);" __wJutakuKanriSettei2Checked0__><span id="iroJKa2val0" __wJutakuKanriSettei2BG0__>あり　</span>
	</td>
</tr>
</table>
<br>
◆　施工設定（管理室）
<table border="1">
<tr>
	<td bgcolor="#ebeeef">管理室親機名称</td>
	<td>
	<table>
		__JutakuKanriSettei3Loop__
		__TRStart1__<td>　<input type="radio" name="wJutakuKanriSettei3" value="__wJutakuKanriSettei3CD__" onchange="changecolor('JKa3','__wJutakuKanriSettei3CD__' , 13, __wJutakuKanriSettei3__,0)" __wJutakuKanriSettei3Checked__><span id="iroJKa3val__wJutakuKanriSettei3CD__" __wJutakuKanriSettei3BG__>__KANRISITUJYUTAKUNAME__　</span></td>__TREnd1__
		__JutakuKanriSettei3Loop__
	</table>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">管理室呼出ボタン表示</td>
	<td>　<input type="radio" name="wJutakuKanriSettei4" value="0" id="JKa4val0" onchange="changecolor('JKa4',0,4,__wJutakuKanriSettei4__,0);" __wJutakuKanriSettei4Checked0__><span id="iroJKa4val0" __wJutakuKanriSettei4BG0__>表示しない　</span>
		　<input type="radio" name="wJutakuKanriSettei4" value="1" id="JKa4val1" onchange="changecolor('JKa4',1,4,__wJutakuKanriSettei4__,0);" __wJutakuKanriSettei4Checked1__><span id="iroJKa4val1" __wJutakuKanriSettei4BG1__>お知らせのみ表示　</span>
		　<input type="radio" name="wJutakuKanriSettei4" value="2" id="JKa4val2" onchange="changecolor('JKa4',2,4,__wJutakuKanriSettei4__,0);" __wJutakuKanriSettei4Checked2__><span id="iroJKa4val2" __wJutakuKanriSettei4BG2__>常時表示　</span><br>
		　<input type="radio" name="wJutakuKanriSettei4" value="3" id="JKa4val3" onchange="changecolor('JKa4',3,4,__wJutakuKanriSettei4__,0);" __wJutakuKanriSettei4Checked3__><span id="iroJKa4val3" __wJutakuKanriSettei4BG3__>安否確認　</span>
	</td>
</tr>
</table>
<br>
◆　施工設定（サービス）
<table border="1">
<tr>
	<td bgcolor="#ebeeef"　colspan="3">サービス選択　              </td>
	<!-- <td> &nbsp; </td> -->
	<td>
		<table>
		__ServiceLoop__
			__TRStart3__<td>　<input type="checkbox" name="wJutakuServiceSettei1[]" value="__wJutakuServiceSetteiCD__" id="JS1val__wJutakuServiceSetteiCD__" onchange="changeBGcolor('JS1',__wJutakuServiceSetteiCD__,8,0,'JS1val__wJutakuServiceSetteiCD__')&resetcheck('JS1',__wJutakuServiceSetteiCD__,8,'JS1val__wJutakuServiceSetteiCD__');" __wJutakuServiceSettei1Checked__><span id="iroJS1val__wJutakuServiceSetteiCD__" __wJutakuServiceSettei1BG__ >__SERVICEJYUTAKUNAME__　 </span> </td> __TREnd3__
		__ServiceLoop__
	</tr>
		</table>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" rowspan="20">詳細設定</td>	
</tr>
	<td bgcolor="#ebeeef" colspan="2">●「トイレ」「バス」「部屋」「コール」を選択した場合</td>
<tr>
	<td bgcolor="#ebeeef" >外部への警報移報遅延</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei5" value="0" id="JS5val0" onchange="changecolor('JS5',0,2,__wJutakuServiceSettei5__,0);" __wJutakuServiceSettei5Checked0__><span id="iroJS5val0" __wJutakuServiceSettei5BG0__>0秒</span>
		　<input type="radio" name="wJutakuServiceSettei5" value="1" id="JS5val1" onchange="changecolor('JS5',1,2,__wJutakuServiceSettei5__,0);" __wJutakuServiceSettei5Checked1__><span id="iroJS5val1" __wJutakuServiceSettei5BG1__>30秒</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" >検出条件</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei6" value="0" id="JS6val0" onchange="changecolor('JS6',0,2,__wJutakuServiceSettei6__,0);" __wJutakuServiceSettei6Checked0__><span id="iroJS6val0" __wJutakuServiceSettei6BG0__>メーク</span>
		　<input type="radio" name="wJutakuServiceSettei6" value="1" id="JS6val1" onchange="changecolor('JS6',1,2,__wJutakuServiceSettei6__,0);" __wJutakuServiceSettei6Checked1__><span id="iroJS6val1" __wJutakuServiceSettei6BG1__>ブレーク</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" >押しボタン方式</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei7" value="0" id="JS7val0" onchange="changecolor('JS7',0,2,__wJutakuServiceSettei7__,0);" __wJutakuServiceSettei7Checked0__><span id="iroJS7val0" __wJutakuServiceSettei7BG0__>ノンロック</span>
		　<input type="radio" name="wJutakuServiceSettei7" value="1" id="JS7val1" onchange="changecolor('JS7',1,2,__wJutakuServiceSettei7__,0);" __wJutakuServiceSettei7Checked1__><span id="iroJS7val1" __wJutakuServiceSettei7BG1__>ロック</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" >玄関子機への警報出力</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei8" value="1" id="JS8val1" onchange="changecolor('JS8',1,2,__wJutakuServiceSettei8__,0);" __wJutakuServiceSettei8Checked1__><span id="iroJS8val1" __wJutakuServiceSettei8BG1__>なし</span>
		　<input type="radio" name="wJutakuServiceSettei8" value="0" id="JS8val0" onchange="changecolor('JS8',0,2,__wJutakuServiceSettei8__,0);" __wJutakuServiceSettei8Checked0__><span id="iroJS8val0" __wJutakuServiceSettei8BG0__>あり</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" >玄関子機への警報出力遅延</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei9" value="0" id="JS9val0" onchange="changecolor('JS9',0,2,__wJutakuServiceSettei9__,0);" __wJutakuServiceSettei9Checked0__><span id="iroJS9val0" __wJutakuServiceSettei9BG0__>0秒</span>
		　<input type="radio" name="wJutakuServiceSettei9" value="1" id="JS9val1" onchange="changecolor('JS9',1,2,__wJutakuServiceSettei9__,0);" __wJutakuServiceSettei9Checked1__><span id="iroJS9val1" __wJutakuServiceSettei9BG1__>30秒</span>
	</td>
</tr>
</tr>
	<td bgcolor="#ebeeef" colspan="2">●「防犯」を選択した場合</td>
<tr>
<tr>
	<td bgcolor="#ebeeef" >親機での設定音・解除音</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei10" value="0" id="JS10val0" onchange="changecolor('JS10',0,2,__wJutakuServiceSettei10__,0);" __wJutakuServiceSettei10Checked0__><span id="iroJS10val0" __wJutakuServiceSettei10BG0__>あり</span>
		　<input type="radio" name="wJutakuServiceSettei10" value="1" id="JS10val1" onchange="changecolor('JS10',1,2,__wJutakuServiceSettei10__,0);" __wJutakuServiceSettei10Checked1__><span id="iroJS10val1" __wJutakuServiceSettei10BG1__>なし</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" >玄関子機での設定音</td>
	<td>
		　<input type="radio" name="wJutakuServiceSettei11" value="0" id="JS11val0" onchange="changecolor('JS11',0,2,__wJutakuServiceSettei11__,0);" __wJutakuServiceSettei11Checked0__><span id="iroJS11val0" __wJutakuServiceSettei11BG0__>あり</span>
		　<input type="radio" name="wJutakuServiceSettei11" value="1" id="JS11val1" onchange="changecolor('JS11',1,2,__wJutakuServiceSettei11__,0);" __wJutakuServiceSettei11Checked1__><span id="iroJS11val1" __wJutakuServiceSettei11BG1__>なし</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" >外部への警報移報遅延</td>
	<td>　<input type="radio" name="wJutakuServiceSettei12" value="0" id="JS12val0" onchange="changecolor('JS12',0,5,__wJutakuServiceSettei12__,0);" __wJutakuServiceSettei12Checked0__><span id="iroJS12val0" __wJutakuServiceSettei12BG0__>0秒　</span>
		　<input type="radio" name="wJutakuServiceSettei12" value="1" id="JS12val1" onchange="changecolor('JS12',1,5,__wJutakuServiceSettei12__,0);" __wJutakuServiceSettei12Checked1__><span id="iroJS12val1" __wJutakuServiceSettei12BG1__>30秒　</span>
		　<input type="radio" name="wJutakuServiceSettei12" value="2" id="JS12val2" onchange="changecolor('JS12',2,5,__wJutakuServiceSettei12__,0);" __wJutakuServiceSettei12Checked2__><span id="iroJS12val2" __wJutakuServiceSettei12BG2__>60秒　</span><br>
		　<input type="radio" name="wJutakuServiceSettei12" value="3" id="JS12val3" onchange="changecolor('JS12',3,5,__wJutakuServiceSettei12__,0);" __wJutakuServiceSettei12Checked3__><span id="iroJS12val3" __wJutakuServiceSettei12BG3__>90秒　</span>
		　<input type="radio" name="wJutakuServiceSettei12" value="4" id="JS12val4" onchange="changecolor('JS12',4,5,__wJutakuServiceSettei12__,0);" __wJutakuServiceSettei12Checked4__><span id="iroJS12val4" __wJutakuServiceSettei12BG4__>120秒　　 </span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" >玄関子機への警報出力遅延</td>
	<td>　<input type="radio" name="wJutakuServiceSettei13" value="0" id="JS13val0" onchange="changecolor('JS13',0,3,__wJutakuServiceSettei13__,0);" __wJutakuServiceSettei13Checked0__><span id="iroJS13val0" __wJutakuServiceSettei13BG0__>0秒　</span>
		　<input type="radio" name="wJutakuServiceSettei13" value="1" id="JS13val1" onchange="changecolor('JS13',1,3,__wJutakuServiceSettei13__,0);" __wJutakuServiceSettei13Checked1__><span id="iroJS13val1" __wJutakuServiceSettei13BG1__>30秒　</span>
		　<input type="radio" name="wJutakuServiceSettei13" value="2" id="JS13val2" onchange="changecolor('JS13',2,3,__wJutakuServiceSettei13__,0);" __wJutakuServiceSettei13Checked2__><span id="iroJS13val2" __wJutakuServiceSettei13BG2__>60秒　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef" >管理用暗証番号の使用有無</td>
	<td>　<input type="radio" name="wJutakuServiceSettei14" value="0" id="JS14val0" onchange="changecolor('JS14',0,4,__wJutakuServiceSettei14__,0);" __wJutakuServiceSettei14Checked0__><span id="iroJS14val0" __wJutakuServiceSettei14BG0__>使用しない</span>
		　<input type="radio" name="wJutakuServiceSettei14" value="1" id="JS14val1" onchange="changecolor('JS14',1,4,__wJutakuServiceSettei14__,0);" __wJutakuServiceSettei14Checked1__><span id="iroJS14val1" __wJutakuServiceSettei14BG1__>25分後使用</span>
		　<input type="radio" name="wJutakuServiceSettei14" value="2" id="JS14val2" onchange="changecolor('JS14',2,4,__wJutakuServiceSettei14__,0);" __wJutakuServiceSettei14Checked2__><span id="iroJS14val2" __wJutakuServiceSettei14BG2__>15分後使用</span><br>
		　<input type="radio" name="wJutakuServiceSettei14" value="3" id="JS14val3" onchange="changecolor('JS14',3,4,__wJutakuServiceSettei14__,0);" __wJutakuServiceSettei14Checked3__><span id="iroJS14val3" __wJutakuServiceSettei14BG3__>5分後使用</span>
	</td>
</tr>
</table>

<br>
<br>
◆　施工設定（その他）
<table border="1">
<tr>
	<td bgcolor="#ebeeef">補助音響装置の鳴動設定</td>
	<td>
		　<input type="radio" name="wSonotaSettei1" value="0" onchange="changecolor('SS1',0,2,__wSonotaSettei1__,0);" __wSonotaSettei1Checked0__><span id="iroSS1val0" __wSonotaSettei1BG0__>警報音のみ</span>
		　<input type="radio" name="wSonotaSettei1" value="1" onchange="changecolor('SS1',1,2,__wSonotaSettei1__,0);" __wSonotaSettei1Checked1__><span id="iroSS1val1" __wSonotaSettei1BG1__>警報音＋呼出音　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">代表移報設定</td>
	<td>
		　<input type="radio" name="wSonotaSettei2" value="0" onchange="changecolor('SS2',0,2,__wSonotaSettei2__,0);" __wSonotaSettei2Checked0__><span id="iroSS2val0" __wSonotaSettei2BG0__>警報のみ　</span>
		　<input type="radio" name="wSonotaSettei2" value="1" onchange="changecolor('SS2',1,2,__wSonotaSettei2__,0);" __wSonotaSettei2Checked1__><span id="iroSS2val1" __wSonotaSettei2BG1__>警報＋呼出　</span>
	</td>
</tr>
<tr>
	<td bgcolor="#ebeeef">緊急放送の音量</td>
	<td>
		　<input type="radio" name="wSonotaSettei3" value="0" onchange="changecolor('SS3',0,2,__wSonotaSettei3__,0);" __wSonotaSettei3Checked0__><span id="iroSS3val0" __wSonotaSettei3BG0__>大　　　　</span>
		　<input type="radio" name="wSonotaSettei3" value="1" onchange="changecolor('SS3',1,2,__wSonotaSettei3__,0);" __wSonotaSettei3Checked1__><span id="iroSS3val1" __wSonotaSettei3BG1__>特大　</span>
	</td>
</tr>
</table>

<!--ここまで-->
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


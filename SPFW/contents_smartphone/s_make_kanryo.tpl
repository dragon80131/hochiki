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

<link href="../css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" >
<script type="text/javascript" src="../js/tools_ajax.js"></script>
<script type="text/javascript" src="../js/ConnectedSelect.js"></script>
<script src="../js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="../js/jquery.ui.core.js" type="text/javascript"></script>
<script src="../js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="../js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>

<script type="text/javascript">
$(function() {
	$("#wHoliday1").datepicker({
		numberOfMonths: 1,
		minDate: new Date(__DateStart__),
		maxDate: new Date(__DateEnd__)
	});
	$("#wHoliday2").datepicker({
		numberOfMonths: 1,
		minDate: new Date(__DateStart__),
		maxDate: new Date(__DateEnd__)
	});
	$("#wHoliday3").datepicker({
		numberOfMonths: 1,
		minDate: new Date(__DateStart__),
		maxDate: new Date(__DateEnd__)
	});
	$("#wHoliday4").datepicker({
		numberOfMonths: 1,
		minDate: new Date(__DateStart__),
		maxDate: new Date(__DateEnd__)
	});
});
</script>

<script >
function checkWakuSelect(){
	var frm = document.mainform;
	var obj = document.getElementById("wakupattern");
	var index = obj.selectedIndex;

	p2 = document.getElementById("p2");
	if( index < 4 && index !==0){
		p2.style.visibility ="hidden";
	}else{
		p2.style.visibility ="visible";
	}
  
	if(frm.wFirstDateFeature.value==0){
		if(document.getElementById("wakupattern").value > 2){
			document.getElementById("OverError").innerHTML = "※最大工事枠数の合計を__MinWakuSum__以下に設定してください。<br> 　ただし、休工日・初日考慮選択時は少し多めに設定してください。";
		}else{
			document.getElementById("OverError").innerHTML = "※最大工事枠数の合計を__MinWakuSum2__以下に設定してください。<br> 　ただし、休工日・初日考慮選択時は少し多めに設定してください。";
		}
	}else{
		if(document.getElementById("wakupattern").value > 2){
			document.getElementById("OverError").innerHTML = "※最大工事枠数の合計を__MinWakuSum3__以下に設定してください。<br> 　ただし、休工日・初日考慮選択時は少し多めに設定してください。";
		}else{
			document.getElementById("OverError").innerHTML = "※最大工事枠数の合計を__MinWakuSum3__以下に設定してください。<br> 　ただし、休工日・初日考慮選択時は少し多めに設定してください。";
		}
	}

}
function checkmove(page){
	var frm = document.mainform;
	var error_flg = "";
	//班数の入力チェック
	if(frm.wHansu.value == ""){
		document.getElementById("HansuError").innerHTML = "<br>※班数を選択してください";
		error_flg = "1";
	}else{
		document.getElementById("HansuError").innerHTML = "";
	}
	
	//
	if(frm.wWakuPattern.value == ""){
		document.getElementById("WakuPatternError").innerHTML = "<br>※工事枠パターンを選択してください";
		error_flg = "1";
	}else{
		document.getElementById("WakuPatternError").innerHTML = "";
	}

	if(frm.wWakuPattern.value <= 2){

		if(frm.wWakuAM.value == "" || frm.wWakuPM1.value == ""){
			document.getElementById("WakuAMPMError").innerHTML = "※最大工事枠数が入力されていません";
			error_flg = "1";
		}else if(frm.wWakuAM.value < 1 || frm.wWakuPM1.value < 1 ){
			document.getElementById("WakuAMPMError").innerHTML = "※入力内容が正しくありません";
			error_flg = "1";
		}else{
			document.getElementById("WakuAMPMError").innerHTML = "";
		}
	}else if(frm.wWakuPattern.value <= 5){

		if(frm.wWakuAM.value == "" || frm.wWakuPM1.value == "" || frm.wWakuPM2.value == ""){
			document.getElementById("WakuAMPMError").innerHTML = "※最大工事枠数が入力されていません";
			error_flg = "1";
		}else if(frm.wWakuAM.value < 1 || frm.wWakuPM1.value < 1 || frm.wWakuPM2.value < 1){
			document.getElementById("WakuAMPMError").innerHTML = "※入力内容が正しくありません";
			error_flg = "1";
		}else{
			document.getElementById("WakuAMPMError").innerHTML = "";
		}
	}

	if(frm.wKojijun.value == ""){
		document.getElementById("KojijunError").innerHTML = "※工事順を選択してください";
		error_flg = "1";
	}else{
		document.getElementById("KojijunError").innerHTML = "";
	}
	var a = frm.wWakuAM.value;
	var b = frm.wWakuPM1.value;
	var c = frm.wWakuPM2.value;
	if(a !== ""|| b !== ""){

		if(frm.wWakuPattern.value <= 2){
			if(frm.wFirstDateFeature.value==0){
				var d = __MinWakuSum2__;
				var e = __MaxWakuSum2__;
				var f = parseInt(a)+parseInt(b);
			}else{
				var d = __MinWakuSum3__;
				var e = __MaxWakuSum2__;
				var f = parseInt(a)+parseInt(b);
			}
		}else{//3枠
			if(frm.wFirstDateFeature.value==0){
				var d = __MinWakuSum__;
				var e = __MaxWakuSum__;
				var f = parseInt(a)+parseInt(b)+parseInt(c);
			}else{
				var d = __MinWakuSum3__;
				var e = __MaxWakuSum__;
				var f = parseInt(a)+parseInt(b)+parseInt(c);
			}
		}

		if(f > d ){//入力した工事枠　が　MinWakuSumより小さくなければならない　
			error_flg = "1";
			document.getElementById("OverError").style.color = "red";
		}else{
			document.getElementById("OverError").style.color = "";
		}
	}

	if(error_flg == ""){
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
<a href="../s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜戻る</a>
</div>


<div class="top-menu left-yose">
<h5>__wBukkenName__</h5>
<h6>詳細工程表</h6>

__IfKoji__

<form action="s_make_matrix.php" method="POST">

<input type="hidden" name="editBukkenCD" value="__editBukkenCD__" >
<input type="hidden" name="rKey" value="__rKey__" >


__IfNew__
<font color="red" >部屋構成が未作成です。</font><br>
__IfNew__

__IfError__
<br><br>
<font color="red" >ログインしたユーザの所属以外の物件を登録・編集することはできません。所属を確認ください。</font><br>
__IfError__

__IfOK__
<br><br>
<font color="red" >部屋構成を登録完了しました。</font><br>
__IfOK__


■部屋構成作成<br>
以下の項目を入力し、「部屋構成の確認」ボタンをクリックしてください。<br>
多棟物件は、未対応です。

<table border=1 >
<tr><td bgcolor="#CCFF99" >総戸数と階高</td>
	<td>総戸数：__wKosu__ 戸　階高：__wKaidaka__ 階</td></tr>
<tr><td bgcolor="#CCFF99" >１フロア最大いくつ部屋がありますか？</td>
	<td><input type="number" name="wYoko" value="__wYoko__" >戸</td></tr>
<!--<tr><td bgcolor="#CCFF99" >部屋番 ○○４を除外しますか？204など</td>
	<td><input type=checkbox name="Except4" value=1 >除外する</td></tr>
<tr><td bgcolor="#CCFF99" >部屋番 ○○９を除外しますか？209など</td>
	<td><input type=checkbox name="Except9" value=1 >除外する</td></tr>-->
</table>

<br>
__HiddenValues__
<input type="submit" value="部屋構成の確認" class="btn btn-primary">
</form>

<br>
<hr>

■登録済み部屋構成 
<!--<input type=button value="工事完了表（エントランスに貼る部屋表）" onclick="location.href='./s_make_kanryodoc.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__'">-->

<!--
	__ColsLoop__
		<td >__Pic__ 
		</td>
	__ColsLoop__ 
-->
<table border=1 >
	__RowsLoop__
	<tr>__ColsBlock__</tr>
	__RowsLoop__
</table>

<br>
<hr>

__IfRoomOK__
■詳細工程表作成<br>
以下の条件をセットして［詳細工程表作成］ボタンをクリックしてください。<br>
<form action="s_make_schedule.php" method="POST" name="mainform" >
<table border=1 >
<tr><td bgcolor="#CCFF99" >工事は何班？</td>
	<td>
		<select name="wHansu" id="hansu">
		<option value="" >-</option>
		<option value="1" __HansuSelect1__>1班</option>
		<option value="2" __HansuSelect2__>2班</option>
		<option value="3" __HansuSelect3__>3班</option>
		<option value="4" __HansuSelect4__>4班</option>
		</select>
		<font color="red" ><span id="HansuError"></span></font>
	</td></tr>
<tr id="blockName">
	<td bgcolor="#CCFF99"  >工事枠パターン</td>
	<td nowrap class="common-list-value-left">
		<select name="wWakuPattern" onChange="checkWakuSelect()" id="wakupattern">
		<option value="" >-</option>
__WakuPatternLoop__
		<option value="__WakuPattern__" __SelectedWakuPattern__  onchange="WakuSettei(__WakuPattern__);">__WakuPatternName__</option>
__WakuPatternLoop__
		</select>
		<font color="red" ><span id="WakuPatternError"></span></font>
	</td></tr>


<tr><td bgcolor="#CCFF99" >最大工事枠数</td>
	<td>
<table>
	<tr>
		<td>
			AM<input type="number" name="wWakuAM"   value="__wWakuAM__"  style="width:50px;" min="1" id="wakuam">
		</td>
		<td>
			PM1<input type="number" name="wWakuPM1"  value="__wWakuPM1__"  style="width:50px;" min="1" id="wakupm1">
		</td>
		<td>
			<span id="p2" __p2style__>PM2<input type="number" name="wWakuPM2" value="__wWakuPM2__" style="width:50px;" min="1" id="wakupm2"></span>
		</td>
	</tr>
</table>
		<span id="OverError">__OverErrorStrings__</span>
		<font color="red" ><span id="WakuAMPMError"></span></font>
</tr>
<!--
<tr><td bgcolor="#CCFF99" >実際、工事は1戸あたり何分？</td>
	<td>
		<select name="wMinuteTime" >
		<option value="" >-</option>
		<option value="30" __HansuSelect30__>30分</option>
		<option value="60" __HansuSelect60__>60分</option>
		<option value="90" __HansuSelect90__>90分</option>
		</select>
	</td></tr>

-->

<tr><td bgcolor="#CCFF99" >専有部期間</td>
	<td>__SenyuStartDate__ ～ __SenyuEndDate__
	</td></tr>
<tr><td bgcolor="#CCFF99" >休工日</td>
	<td>
		<input type="text" name="wHoliday1" id="wHoliday1"  value="__wHoliday1__"  ><br>
		<input type="text" name="wHoliday2" id="wHoliday2"  value="__wHoliday2__"  ><br>
		<input type="text" name="wHoliday3" id="wHoliday3"  value="__wHoliday3__"  ><br>
		<input type="text" name="wHoliday4" id="wHoliday4"  value="__wHoliday4__"  ><br>
	</td></tr>
<tr><td bgcolor="#CCFF99" >専有部工事の初日考慮</td>
	<td>
		<select name="wFirstDateFeature" ><!--ConstTimeは表記上　MinuteUnitは30分でいく。--> 
		<option value="0" >-</option>
		<option value="1" __FirstDateFeature1__>初日午前NG</option>
		<option value="2" __FirstDateFeature2__>初日１５:００以降OK</option>
		</select>
	</td></tr>
<tr><td bgcolor="#CCFF99" >工事順<br>(詳細工程表の並び順</td>
	<td>

	<table>
	<tr><td align=right ><input type="radio" name="wKojijun" value="1" __KojijunChecked1__ ></td><td><img src="../images/kojijun1.png" width="50">下から横へ</td></tr>
	<tr><td align=right ><input type="radio" name="wKojijun" value="2" __KojijunChecked2__ ></td><td><img src="../images/kojijun2.png" width="50">上から横へ</td></tr>
	<tr><td align=right ><input type="radio" name="wKojijun" value="3" __KojijunChecked3__ ></td><td><img src="../images/kojijun3.png" width="50">下から縦へ(2列ずつ）</td></tr>
	<tr><td align=right ><input type="radio" name="wKojijun" value="4" __KojijunChecked4__ ></td><td><img src="../images/kojijun3.png" width="50">下から縦へ(3列ずつ）</td></tr>
	<tr><td>　</td>
		<td colspan=3 >
		<br>
		<img src="../images/kojijun5.png" width="50"><br>
		建物が分離しているなど特殊なケースの場合は、詳細工程表（Excel）出力後、<br>
		編集するもしくは、予約受付センターにご相談ください。　
		</td></tr>
	</table>
		<font color="red" ><span id="KojijunError"></span></font>

	</td></tr>
</table>
<input type="hidden" name="wColsBlock" value="__wColsBlock__" >
<input type="hidden" name="RowsLoop" value="__RowsLoop__" >
<!--<input type="hidden" name="Except4" value=1 >
<input type="hidden" name="Except9" value=1 >-->
<input type="hidden" name="rKey" value="__rKey__">
<br>
<input type="button"  class="btn btn-primary" onclick="javascript:checkmove('s_make_kotei_confirm.php?editBukkenCD=__editBukkenCD__' )"value="詳細工程表作成">

　
__IfNespe__<hr><ネスぺ社員のみ表示><br>
<a href="#" onclick="javascript:move('s_make_yotei_EXCEL.php?editBukkenCD=__editBukkenCD__' )">予定案内</a>
__IfNespe__
__IfKoji__
__IfNotKoji__
	
<form action=# method="POST" name="mainform">
<br>	工事情報登録がまだです。工事情報登録をお願いします。
	


<br>
<br>

<input type="button" value="工事情報登録へ" onclick="javascript:move('../s_koji.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__' )"  class="btn btn-primary"><br>
<br>

</form>


__IfNotKoji__


</form>
__IfRoomOK__

<br>

</div><!--content-all-->

<hr>

__SFooter__
__SCopyright__

</body>
</html>

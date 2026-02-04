<html>
<head>
<title>営業活動支援システム</title>
<LINK REL="stylesheet" TYPE="text/css" HREF="../css/a7.css">
<link rel="stylesheet" href="../css/print.css" type="text/css" media="print" />

<script type="text/javascript" src="../tools.js"></script>
<link href="../css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="../js/jquery.ui.core.js" type="text/javascript"></script>



<script type="text/javascript" src="../js/tools_ajax.js"></script>
<script type="text/javascript" src="../js/ConnectedSelect.js"></script>
<script src="../js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="../js/jquery.ui.core.js" type="text/javascript"></script>
<script src="../js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="../js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>


<script type="text/javascript">
$(function() {
	$("#wHoliday1").datepicker({});
	$("#wHoliday2").datepicker({});
	$("#wHoliday3").datepicker({});
	$("#wHoliday4").datepicker({});
});
</script>

</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">



<hr size="__HRSize__" color="__HRColor__">
<center>《詳細工程情報セット》</center>
<hr size="__HRSize__" color="__HRColor__">


__IfNew__
<font color="red" >部屋構成が未作成です。<br></font>
__IfNew__

__IfError__
<br><br>
<font color="red" >ログインしたユーザの所属以外の物件を登録・編集することはできません。所属を確認ください。<br></font>
__IfError__

__IfOK__
<br><br>
<font color="red" >部屋構成を登録完了しました。<br></font>
__IfOK__


部屋構成を作成しますので、以下の項目を入力し、「部屋構成の確認」ボタンをクリックしてください。<br>
多棟物件は、未対応です。
<form action="s_make_matrix.php" method="POST" name="mainform" >
<table border=1 >
<tr><td bgcolor="palegreen" >総戸数と階高</td>
<td>総戸数：__wKosu__ 戸　階高：__wKaidaka__ 階</td></tr>
<tr><td bgcolor="palegreen" >１フロア最大いくつ部屋がありますか？</td>
<td><input type="text" name="wYoko" value="__wYoko__" >戸</td></tr>

<tr><td bgcolor="palegreen" >部屋番 ○○４を除外しますか？204など</td>
<td><input type=checkbox name="Except4" value=1 >除外する</td></tr>
<tr><td bgcolor="palegreen" >部屋番 ○○９を除外しますか？209など</td>
<td><input type=checkbox name="Except9" value=1 >除外する</td></tr>



</table>
<br>
__HiddenValues__
<input type=submit value="部屋構成の確認">
</form>
<br>

登録済み部屋構成 
<input type=button value="工事完了表（エントランスに貼る部屋表）" onclick="location.href='./s_make_kanryodoc.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__'">

<br>__wBukkenName__ 
<!--
	__ColsLoop__
		<td >__Pic__ 
		</td>
	__ColsLoop__ 
-->
<table border=1 >

	__RowsLoop__
		<tr>
__ColsBlock__	</tr>
	__RowsLoop__

</table>


__IfRoomOK__
次に詳細工程表を作成します。以下の条件をセットして［詳細工程表作成］ボタンをクリックします。<br><br>

専有部期間　__SenyuStartDate__ ～ __SenyuEndDate__<br> 


<form action="s_make_schedule.php" method="POST" name="mainform" >
<table border=1 >
<tr><td bgcolor="palegreen" >工事は何班？</td><td>
	<select name="wHansu" >
				<option value="" >-</option>
	<option value="1" __HansuSelect1__>1班</option>
	<option value="2" __HansuSelect2__>2班</option>
	<option value="3" __HansuSelect3__>3班</option>
	<option value="4" __HansuSelect4__>4班</option>
	</select>

</td></tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">工事枠パターン</td>
		<td nowrap class="common-list-value-left">
			<select  name="wWakuPattern" >
			<option value="" >-</option>
__WakuPatternLoop__
			<option value="__WakuPattern__" __SelectedWakuPattern__  >__WakuPatternName__</option>
__WakuPatternLoop__

			</select>
		</td>
	</tr>	<tr id="blockName">



</td></tr>
<tr><td bgcolor="palegreen" >工事は1戸あたり何分？</td><td>

			<select  name="wMinuteTime" ><!--ConstTimeは表記上　MinuteUnitは30分でいく。--> 
			<option value="" >-</option>
			<option value="1" __HansuSelect1__>30分</option>
			<option value="2" __HansuSelect2__>60分</option>
			<option value="3" __HansuSelect3__>90分</option>

			</select>
</td></tr>

<tr><td bgcolor="palegreen" >休工日</td><td>
<input type="text" name="wHoliday1" id="wHoliday1"  value="__wHoliday1__" ><br>
<input type="text" name="wHoliday2" id="wHoliday2"  value="__wHoliday2__" ><br>
<input type="text" name="wHoliday3" id="wHoliday3"  value="__wHoliday3__" ><br>
<input type="text" name="wHoliday4" id="wHoliday4"  value="__wHoliday4__" ><br>



</td></tr>
<tr><td bgcolor="palegreen" >専有部工事の初日考慮</td><td>
			<select  name="wFirstDateFeature" ><!--ConstTimeは表記上　MinuteUnitは30分でいく。--> 
			<option value="" >-</option>
			<option value="1" __FirstDateFeature1__>初日全時間帯１枠少なめ</option>
			<option value="2" __FirstDateFeature2__>初日午後OK</option>
			<option value="3" __FirstDateFeature3__>初日１５:００以降OK</option>

			</select>
</td></tr>



</table>
<table  >
<tr><td rowspan=2 bgcolor="palegreen" >工事順(詳細工程表の並び順）</td>
<td align=right ><input type="radio" name="KojiJun" value="1" ></td><td><img src="../images/kojijun1.png"><br>下から横へ</td>
<td align=right ><input type="radio" name="KojiJun" value="2" ></td><td><img src="../images/kojijun2.png"><br>上から横へ</td></tr>
<tr><td align=right ><input type="radio" name="KojiJun" value="3" ></td><td><img src="../images/kojijun3.png"><br>下から縦へ</td>
<td align=right ><input type="radio" name="KojiJun" value="4" ></td><td><img src="../images/kojijun4.png"><br>上から縦へ</td></tr>
<tr><td>　</td><td colspan=4 >
<img src="../images/kojijun5.png"><br>
建物が分離しているなど特殊なケースの場合は、詳細工程表（Excel）出力後、<br>
編集するもしくは、予約受付センターにご相談ください。　
</td></tr>

</table>


<br>
__HiddenValues__
<input type=submit value="詳細工程表作成">
</form>
<br>

__IfRoomOK__



<hr size="__HRSize__" color="__HRColor__">


<a href="s_menu.php__QUERY__&editBukkenCD=__editBukkenCD__ ">メニュー</a>
<br><br>
<a href="s_search.php__QUERY__">トップ</a>
<br><br>
<a href="logout.php__QUERY__">ログアウト</a>
<hr size="__HRSize__" color="__HRColor__">

__SFooter__

__SCopyright__
<br>
</body>

</html>

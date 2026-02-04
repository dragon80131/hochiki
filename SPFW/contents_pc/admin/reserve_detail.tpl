<html>
<head>
<title>__MansionName__</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>

__IfAjax__<!--<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>-->
__IfAjax__

<SCRIPT language=JavaScript>
<!-- Hide script from old browser
// プログラム移動
function goPage(pgAct) {
	document.mainform.action = pgAct;
	document.mainform.submit(true)
}

function goPageWithValue(pgAct, val) {
	document.mainform.vDate.value = val;
	document.mainform.action = pgAct;
	document.mainform.submit(true)
}

function goPageWithMonth(pgAct, val) {
	document.mainform.vThisMonth.value = val;
	document.mainform.action = pgAct;
	document.mainform.submit(true)
}

function moveWithReserveInfo(pgAct, hour, minute, stylistcd) {
	document.mainform.work.value = 2;
	document.mainform.vHour.value = hour;
	document.mainform.vMinute.value = minute;
	document.mainform.vStylistCD.value = stylistcd;
	document.mainform.action = pgAct;
	document.mainform.submit(true)
}

function showReserveInfo(mode) {
	var vShow = document.mainform.vShow.value;
	var tmpHtml = '';

	if ((mode == 0 && (vShow == '' || vShow == 1)) || mode == 1) {
		document.mainform.vShow.value = 1;
		toggle('ReserveCalendar', 'show');
		toggle('ReserveList', 'none');

		tmpHtml = '<a href="javascript:void(0);" onclick="javascript:showReserveInfo(2);">&gt; リスト表示</a>';
__IfShowDefault1__		tmpHtml += '　　　<a href="javascript:void(0);" onclick="javascript:moveWithWork(\'reserve_detail.php\', 5);">&gt; タイムテーブル表示をデフォルトの表示モードにする</a>'
__IfShowDefault1__		SwitchReserveInfo.innerHTML = tmpHtml;
	}
	else {
		document.mainform.vShow.value = 2;
		toggle('ReserveCalendar', 'none');
		toggle('ReserveList', 'show');
		tmpHtml = '<a href="javascript:void(0);" onclick="javascript:showReserveInfo(1);">&gt; タイムテーブル表示</a>';
__IfShowDefault2__		tmpHtml += '　　　<a href="javascript:void(0);" onclick="javascript:moveWithWork(\'reserve_detail.php\', 5);">&gt; リスト表示をデフォルトの表示モードにする</a>'
__IfShowDefault2__		SwitchReserveInfo.innerHTML = tmpHtml;
	}
}
// end hiding -->
</SCRIPT>

<script type="text/javascript" src="js/prototype.js"></script>
<script type="text/javascript" src="js/userdata.js"></script>
<script type="text/javascript" src="js/userdata2.js"></script>

<script>
var termColor="#ffffff"
var termBackColor="#999999"
</script>
<style type="text/css">
.explain{position:absolute; width=150;
background-color:#ffebcb;
font-size:8pt;color:#000000;
font-weight:normal;
padding:4;
border-left:1 solid #555555;
border-top:1 solid #555555;
border-right:1 solid #555555;
border-bottom:1 solid #555555;
visibility:"hidden";}
.term{color:#000000; cursor:hand; font-weight:700;}
</style>

</head>

<body>
<div class="content">


__IfDemo__<table class="common-list">

</table>
<br>
__IfDemo__

__IfDefaultSet__<table class="common-list">
	<tr>
		<td nowrap class="common-list-value">デフォルトの表示設定を変更致しました。</td>
	</tr>
</table>
<br>
__IfDefaultSet__



		<a href="https://www2.489501.jp/portal/site.php">ポータルサイト</a>&nbsp
		<a href="index2.php">システム設定</a>&nbsp
		<a href="reserve_detail2.php">決定案内用予約表示</a>&nbsp
		<a href="report_form.php">報告書作成</a>&nbsp
		<a href="../login_form.php">作業用工程表</a>&nbsp
		<a href="../list/c.php">未返事住戸リスト</a>&nbsp
		<a href="https://app.inshare.jp/d3/dnet/xmail.cgi?page=maillist&fid=2&select=1">専用Inshare</a>&nbsp
		<a href="logout.php">ログアウト</a>
	

<form method="POST" action="reserve_detail.php" name="mainform">
<input type="hidden" name="work" >
<table border="0" width="100%">
<tr>
	<td nowrap class="small-common-list-value-left" colspan="2">
		<a href="#" onclick="javascript:goPageWithMonth('reserve_detail.php', '__PreviousWork__');">&lt;&lt;__Previous__</a>　　　　
		<a href="#" onclick="javascript:goPageWithMonth('reserve_detail.php', '');">今月</a>　　　　
		<a href="#" onclick="javascript:goPageWithMonth('reserve_detail.php', '__NextWork__');">__Next__&gt;&gt;</a>　　　　　　
		<font color="__TodayColor__">■</font>…選択中の日　
		<font color="__ReserveColor__">■</font>…予約ありの日　
		<font color="__HolidayColor__">■</font>…休業日　
	</td>
</tr>
<tr>
<td valign="top">
	<table class="small-common-list">
		<tr>
			<td nowrap class="small-common-list-value" colspan="7">
				__ThisMonth__
			</td>
		</tr>
		<tr>
			<td nowrap class="small-common-list-title" width="5%">
				日
			</td>
			<td nowrap class="small-common-list-title" width="5%">
				月
			</td>
			<td nowrap class="small-common-list-title" width="5%">
				火
			</td>
			<td nowrap class="small-common-list-title" width="5%">
				水
			</td>
			<td nowrap class="small-common-list-title" width="5%">
				木
			</td>
			<td nowrap class="small-common-list-title" width="5%">
				金
			</td>
			<td nowrap class="small-common-list-title" width="5%">
				土
			</td>
		</tr>
	__WeekLoop__
		<tr>
	__WeekdayLoop__		<td nowrap class="small-common-list-value" style="background-color: __DayColor__;">__IfOK__<a href="#" onclick="javascript:mainform.vDate.value='__FullDate__';move('reserve_detail.php');">__IfOK____ThisDay____IfOK__</a>__IfOK__<br />
			</td>
	__WeekdayLoop____WeekdayBlock__	</tr>
	__WeekLoop__
	</table>
</td>
<td valign="top">
	<table class="small-common-list">
		<tr>
			<td nowrap class="small-common-list-value" colspan="7">
				__NextMonth__
			</td>
		</tr>
		<tr>
			<td nowrap class="small-common-list-title" width="5%">
				日
			</td>
			<td nowrap class="small-common-list-title" width="5%">
				月
			</td>
			<td nowrap class="small-common-list-title" width="5%">
				火
			</td>
			<td nowrap class="small-common-list-title" width="5%">
				水
			</td>
			<td nowrap class="small-common-list-title" width="5%">
				木
			</td>
			<td nowrap class="small-common-list-title" width="5%">
				金
			</td>
			<td nowrap class="small-common-list-title" width="5%">
				土
			</td>
		</tr>
	__Week2Loop__
		<tr>
	__Weekday2Loop__		<td nowrap class="small-common-list-value" style="background-color: __DayColor2__;">__IfOK2__<a href="#" onclick="javascript:mainform.vDate.value='__FullDate2__';move('reserve_detail.php');">__IfOK2____ThisDay2____IfOK2__</a>__IfOK2__<br />
			</td>
	__Weekday2Loop____WeekdayBlock2__	</tr>
	__Week2Loop__
	</table>
</td>
</tr>
</table>

<table class="common-list" width="700">
	<tr><td  nowrap class="common-list-value-left" >依頼会社様：__Company__ &nbsp 枠構成：__WakuRange__ &nbsp;  工事体制：__Hansu__班 &nbsp 工事所要時間:__ConstTime__分 &nbsp 開錠方法：__Kaijyo__ &nbsp 
                支払方法：__Shiharai__ &nbsp </td></tr>
__IfNoTato__	<tr><td nowrap class="common-list-value-left">決定案内配布日：__TKeteiDate__ &nbsp;共用部期間：__KyoyuStartDate__?__KyoyuEndDate__　&nbsp;専有部期間：__ReserveTo2__?__ReserveFrom2__</td></tr>
	<tr><td  nowrap class="common-list-value-left">注意事項:<font color="#ff8080">__Notes__</font></td></tr>
__IfNoTato__

__IfTato__
	__TatoLoop__
		<tr>
		   <td nowrap class="common-list-value-left">棟名（棟記号）:__ToName__  決定案内配布日：__TKeteiDate__ &nbsp;共用部期間：__KyoyoStartDate__?__KyoyoEndDate__　&nbsp;専有部期間：__TReserveTo2__?__TReserveFrom2__</td>
		</tr>
	__TatoLoop__
	<tr><td  nowrap class="common-list-value-left">注意事項:<font color="#ff8080">__Notes__</font></td></tr>
__IfTato__


</table>




__IfNew__
<table class="common-list" width="700">
	<tr>


		<td nowrap class="common-list-value-left" colspan="40">
		部屋番号コード 
		<input type="text" name="wUserCD" value="__wUserCD__" size="10" __IME_OFF__ onchange="javascript:userdataDisp();">




<!--		__Designation__
		<select name="wStylistCD">
__DesignateStylistLoop__		<option value="__DesignateStylistCD__"__DesignateStylistSelected__>__DesignateStylistName__
__DesignateStylistLoop__		</select>
-->
		時刻
		<select name="wHour">
__HoursLoop__		<option value="__HoursValue__"__HoursSelected__>__HoursValue__
__HoursLoop__		</select>時
		<select name="wMinute">
__MinutesLoop__		<option value="__MinutesValue__"__MinutesSelected__>__MinutesValue__
__MinutesLoop__		</select>分?


<br>

		<div id="UserData" style="background-color: #EEEEEE;"></div>

第2希望工事日:	
		<textarea name="wSecondTimeFrom" cols="10" rows="1" style="width:200px" class="form">__wSecondTimeFrom__</textarea>

&nbsp;変更前工事日時　__TimeFromC__<br>

</td>


<td nowrap class="common-list-value-left" colspan="40">

		工事施工内容　インターホン工事(本工事）は必ずチェック<br>
__MenuLoop__		<input type="checkbox" name="wMenuCD[]" value="__MenuCD__"__MenuChecked__>__MenuName__
__MenuLoop__

<input type="text" name="wR002" size="6" value=__wR002__>本
<br>	備考:
		<textarea name="wMemo" cols="30" rows="1" style="width:200px"  class="form">__wMemo__</textarea><br />

&nbsp;時間指定 時間は半角で入力<input type=text name="wR003" style="width:50px;ime-mode:disabled" class="form" value="__wR003__" >
<select name="wR004" > 

__R004Loop__
		<option value="__R004n__"__R004Selected__>__R004Value__</option>
__R004Loop__		
</select>
(例 9:00)
</td>
</tr>

<tr>
<td  nowrap class="common-list-value-left" colspan="80">

		　　<input type="button" value="部屋番号検索" class="button" onclick="javascript:mainform.target='NEW';moveWithWork('user_search.php', 1);mainform.target='_self';">

	
__IfThisUser__		　　<input type="button" value="お客様情報へ" class="button" onclick="javascript:mainform.target='NEW';moveWithWork('user_detail.php', 4);mainform.target='_self';">

　                          <input type="button" value="通知メールへ        " class="button" onclick="javascript:mainform.target='NEW';move('make_mail.php');mainform.target='_self';">
__IfThisUser__	
		<input type="button" value="新規予約" class="button" onclick="javascript:moveWithWork('reserve_detail.php', 1)">


</td>
</tr>


<tr>
<td  nowrap class="common-list-value-left" colspan="80">

  <h2 class="admin-title">選択日付(__ShowDate__)__ExtraTitle____MansionName__</h2>
  <h2 class="navigation"><a href="#" onclick="javascript:move('reserve_detail.php')">&gt;&gt;&gt; 最新の情報に更新</a></h2>
</td>


</tr>

</table>
__IfNew__

__IfModify__
<table class="common-list" width="700">
	<tr>


		<td nowrap class="common-list-value-left" colspan="40">
		部屋番号コード 
		<input type="text" name="wUserCD" value="__wUserCD__" size="10" __IME_OFF__ onchange="javascript:userdataDisp();">




		__Designation__
		<select name="wStylistCD">
__DesignateStylistLoop__		<option value="__DesignateStylistCD__"__DesignateStylistSelected__>__DesignateStylistName__
__DesignateStylistLoop__		</select>
		時刻
		<select name="wHour">
__HoursLoop__		<option value="__HoursValue__"__HoursSelected__>__HoursValue__
__HoursLoop__		</select>時
		<select name="wMinute">
__MinutesLoop__		<option value="__MinutesValue__"__MinutesSelected__>__MinutesValue__
__MinutesLoop__		</select>分?
<br>
		<div id="UserData" style="background-color: #EEEEEE;"></div>

第2希望日:	
		<textarea name="wSecondTimeFrom" cols="20" rows="1" style="width:200px" class="form">__wSecondTimeFrom__</textarea>


&nbsp;変更前　__TimeFromC__<br>

</td>


<td nowrap class="common-list-value-left" colspan="40">

		工事施工内容　インターホン工事(本工事）は必ずチェック<br>
__MenuLoop__		<input type="checkbox" name="wMenuCD[]" value="__MenuCD__"__MenuChecked__>__MenuName__
__MenuLoop__
<input type="text" name="wR002" size="5" value="__wR002__">本
<br>	備考:
		<textarea name="wMemo" cols="30" rows="1" style="width:200px"  class="form">__wMemo__</textarea><br />

&nbsp;時間指定 時間は半角で入力<input type=text name="wR003" style="width:50px" class="form" value="__wR003__">
<select name="wR004" >


__R004Loop__		<option value="__R004n__" __R004Selected__>__R004Value__
__R004Loop__		
</select>
(例 9:00）
<!--チェック:
		<input type="button" value="第3者チェック" class="button" onclick="javascript:moveWithWork('reserve_detail.php', 6)">
&nbsp;&nbsp;<a href=https://www.489501.jp/portal/admin/ target="_blank">チェックリスト</a>

-->
</td>
</tr>

<tr>
<td  nowrap class="common-list-value-left" colspan="80">

		　　<input type="button" value="部屋番号検索" class="button" onclick="javascript:mainform.target='NEW';moveWithWork('user_search.php', 1);mainform.target='_self';">

		<input type="button" value="__ReservationName__修正" class="button" onclick="javascript:moveWithWork('reserve_detail.php', 1)">

__IfThisUser__		　　<input type="button" value="お客様情報へ" class="button" onclick="javascript:mainform.target='NEW';moveWithWork('user_detail.php', 4);mainform.target='_self';">

　                          <input type="button" value="通知メールへ        " class="button" onclick="javascript:mainform.target='NEW';move('make_mail.php');mainform.target='_self';">
__IfThisUser__	



</td>
</tr>


<tr>
<td  nowrap class="common-list-value-left" colspan="80">

  <h2 class="admin-title">選択日付(__ShowDate__)__ExtraTitle____MansionName__</h2>
  <h2 class="navigation"><a href="#" onclick="javascript:move('reserve_detail.php')">&gt;&gt;&gt; 最新の情報に更新</a></h2>
</td>


</tr>

</table>
__IfModify__




<div id="SwitchReserveInfo"></div>
<table class="common-list" width="700" id="ReserveCalendar">
	<tr>
		<td nowrap class="common-list-title">時刻</td>
__StylistLoop__		<td nowrap class="common-list-title" colspan="__StylistColspan__">__StylistName__</td>
__StylistLoop__	</tr>
__TimesLoop__	<tr>
		<td nowrap class="common-list-title">__Times__</td>
__LinesLoop____IfShow__		<td nowrap class="__Style__" rowspan="__Rowspan__" width="__Width__%">__ReserveStatus__</td>
__IfShow____LinesLoop____LinesBlock__	</tr>
__TimesLoop__	<tr>
	</tr>
</table>


<table class="common-list" width="700" id="ReserveList">
	<tr>
		<td nowrap class="common-list-title">部屋番号CD</td>
		<td nowrap class="common-list-title">部屋番号</td>
		<td nowrap class="common-list-title">日付</td>
		<td nowrap class="common-list-title">予約時間</td>
		<td nowrap class="common-list-title">時間指定</td>
		<td nowrap class="common-list-title">氏名</td>
		<td nowrap class="common-list-title">メニュー</td>
		<td nowrap class="common-list-title">備考</td>
		<td nowrap class="common-list-title">電話番号</td>
		<td nowrap class="common-list-title">詳細/編集</td>
		<td nowrap class="common-list-title">削除</td>
	</tr>

__IfReserveList____ReservationLoop__	<tr class="common-list">
		<td nowrap class="common-list-value">__UserCD__</td>
		<td nowrap class="common-list-value">__ID__</td>
		<td nowrap class="common-list-value">__ReserveDate__</td>
		<td nowrap class="common-list-value">__ListTimeFrom__?__ListTimeTo__</td>
		<td nowrap class="common-list-value">__R003____R004__</td>
		<td nowrap class="common-list-value">__LastName____FirstName__</td>
		<td nowrap class="common-list-value">__ListMenuName__</td>
		<td nowrap class="common-list-value">__Memo__</td>
		<td nowrap class="common-list-value">__TEL__</td>
		<td nowrap class="common-list-value"><a href="#" onclick="javascript:moveWithReservationKeyAlert('reserve_detail.php', __ReservationCD__, 3);">GO!</a></td>
		<td nowrap class="common-list-value"><a href="#" onclick="javascript:moveWithReservationKeyAlert('reserve_detail.php', __ReservationCD__, 4);">GO!</a></td>
	</tr>
__ReservationLoop____IfReserveList____IfNoReserveList__
	<tr>
		<td nowrap class="common-list-value" colspan="12"><font color="red">__ReservationName__データはありません。</font></td>
	</tr>
__IfNoReserveList__</table>

__HiddenValues__


<br>
<br>
</form>
</div>
<script type="text/javascript">
userdataDisp();
showReserveInfo(0);
</script>
</body>
</html>

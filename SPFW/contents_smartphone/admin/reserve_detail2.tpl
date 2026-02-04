<html>
<head>
<title>__ReservationName__管理</title>

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
<h2 class="admin-title">予約管理(__ShowDate__)__ExtraTitle__</h2>
<br />

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

__IfAdminSystem__<h2 class="navigation"><a href="#" onclick="javascript:location.href='client_list.php'">&gt;&gt;&gt; クライアント一覧へ</a></h2>
__IfAdminSystem__<h2 class="navigation"><a href="#" onclick="javascript:location.href='index.php'">&gt;&gt;&gt; トップページ(メニュー)へ</a></h2>
<h2 class="navigation"><a href="#" onclick="javascript:move('reserve_detail.php')">&gt;&gt;&gt; 最新の情報に更新</a></h2>
<br />
		<a href="reserve_detail.php">入力確認画面</a>&nbsp
		<a href="https://www.489501.jp/portal.php">ポータルサイト</a>&nbsp
		<a href="index2.php">システム設定</a>&nbsp
		<a href="logout.php">ログアウト</a>
	

<form method="POST" action="reserve_detail.php" name="mainform">





<div id="SwitchReserveInfo"></div>



<table class="common-list" width="700" id="ReserveList">
	<tr>
		<td nowrap class="common-list-title">部屋番号CD</td>
		<td nowrap class="common-list-title">区分</td>
		<td nowrap class="common-list-title">受付</td>
		<td nowrap class="common-list-title">部屋番号</td>
		<td nowrap class="common-list-title">日付</td>
		<td nowrap class="common-list-title">予約時間</td>
		<td nowrap class="common-list-title">氏名</td>
		<td nowrap class="common-list-title">__Menu__</td>
		<td nowrap class="common-list-title">備考</td>
		<td nowrap class="common-list-title">電話番号</td>
		<td nowrap class="common-list-title">第2希望</td>
		<td nowrap class="common-list-title">未返事</td>
		<td nowrap class="common-list-title">詳細/編集</td>
		<td nowrap class="common-list-title">削除</td>
	</tr>

__IfReserveList____ReservationLoop__	<tr class="common-list">
		<td nowrap class="common-list-value">__UserCD__</td>
		<td nowrap class="common-list-value">__Extra1__</td>
		<td nowrap class="common-list-value">__rUpdated__</td>
		<td nowrap class="common-list-value">__ID__</td>
		<td nowrap class="common-list-value">__ReserveDate__</td>
		<td nowrap class="common-list-value">__ListTimeFrom__〜__ListTimeTo__</td>
		<td nowrap class="common-list-value">__LastName____FirstName__</td>
		<td nowrap class="common-list-value">__ListMenuName2__</td>
		<td nowrap class="common-list-value">__R003____R004____Memo__</td>
		<td nowrap class="common-list-value">__TEL__</td>
		<td nowrap class="common-list-value">__SecondTimeFrom__</td>
		<td nowrap class="common-list-value">__uUpdated__</td>
		<td nowrap class="common-list-value"><a href="#" onclick="javascript:moveWithReservationKeyAlert('reserve_detail.php', __ReservationCD__, 3);">GO!</a></td>
		<td nowrap class="common-list-value"><a href="#" onclick="javascript:moveWithReservationKeyAlert('reserve_detail.php', __ReservationCD__, 4);">GO!</a></td>
	</tr>
__ReservationLoop____IfReserveList____IfNoReserveList__
	<tr>
		<td nowrap class="common-list-value" colspan="12"><font color="red">__ReservationName__データはありません。</font></td>
	</tr>
__IfNoReserveList__</table>

__HiddenValues__
<div class="footer-box">
	__SAdminCopyright__
</div>
</form>
</div>
<script type="text/javascript">
userdataDisp();
showReserveInfo(0);
</script>
</body>
</html>

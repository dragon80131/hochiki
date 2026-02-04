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
<h2 class="admin-title">多棟情報 − 工事基本情報フォーム</h2>
<br />

__IfAdminSystem__<h2 class="navigation"><a href="#" onclick="javascript:move('client_list.php')">&lt;&lt;&lt; クライアントリストへ</a></h2>
__IfAdminSystem__<h2 class="navigation"><a href="#" onclick="javascript:location.href='reserve_set_menu.php'">&lt;&lt;&lt; システム設定メニューへ</a></h2>
<h2 class="navigation"><a href="#" onclick="javascript:location.href='index.php'">&lt;&lt;&lt; トップページ(メニュー)へ</a></h2>
<br />
  <h2 class="navigation"><a href="#" onclick="javascript:location.href='tato.php'">&gt;&gt;&gt; 最新の情報に更新</a>
<br />


<font color=red> __DataAddKekka__ </font>
<br>

棟データ追加
<table class="common-list" width="500">
<form action=tato.php method=POST>

	<tr id="blockName">
		<td nowrap class="common-list-title" width="20">棟記号（例 A , 1- )</td>
		<td nowrap class="common-list-value-left"><input type=text name=wToName value="__wToName__"  __IME_OFF__ class="form"> </td>
	</tr>


	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">共有部工事開始日(例20131013)</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wKyoyoStartDate" value="__wKyoyoStartDate__" size="20" __IME_OFF__ class="form">
		</td>
	</tr>

	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">共有部工事終了日(例20131015)</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wKyoyoEndDate" value="__wKyoyoEndDate__" size="20" __IME_OFF__ class="form">
		</td>
	</tr>


	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">専有部工事開始日(例20131020)</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wReserveTo2" value="__wReserveTo2__" size="20" __IME_OFF__ class="form">
		</td>
	</tr>

	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">専有部工事終了日(例20131030)</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wReserveFrom2" value="__wReserveFrom2__" size="20" __IME_OFF__ class="form">
		</td>
	</tr>

	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">決定案内提供日(例20131030)</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wKeteiDate" value="__wKeteiDate__" size="20" __IME_OFF__ class="form">
		</td>
	</tr>

</table>

__HiddenValues__
<input type=hidden name=work value=1 >			
<input type="submit" value="データ追加" >
__IfEdit__　
<input type="submit" value="データ修正・削除確定" >
　__IfEdit__
</form>　

<br><br>

<table class="common-list" width="700">
	<tr id="blockName">
		<td nowrap class="common-list-title" colspan="2"登録済み棟情報</td>
	</tr>

__ToLoop__
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">棟記号（半角英数字）</td>
		<td nowrap class="common-list-value-left">__ToName__</td>
	</tr>


	<tr id="blockName">
		<td nowrap class="common-list-title" width="200"><font color="yellow">共有部工事期間</font></td>
		<td nowrap class="common-list-value-left">
			__KyoyoStartDate__ 〜 __KyoyoEndDate__
		</td>
	</tr>

	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">専有部工事期間</td>
		<td nowrap class="common-list-value-left">
			__ReserveTo2__ 〜 __ReserveFrom2__
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">決定案内提供日</td>
		<td nowrap class="common-list-value-left">
			__KeteiDate__
		</td>
	</tr>

	<tr id="blockName">
		<td nowrap class="common-list-value-left" colspan=2  >
<table><tr><td>
		<form action=tato.php method=POST >
		__HiddenValues__
		<input type=hidden name=work value="2" >
		<input type=hidden name=editTatoCD value="__TatoCD__">
		<input type="submit" value="修正データ選択" >
		</form>
 </td><td>

		<form action=tato.php method=POST >
		__HiddenValues__
		<input type=hidden name=work value="3" >
		<input type=hidden name=editTatoCD value="__TatoCD__">
		<input type="submit" value="削除データ選択" >
		</form>
</td></tr></table>

		</td>
	</tr>



__ToLoop__

</table>
			




<div class="footer-box">
	__SAdminCopyright__
</div>

</body>
</html>
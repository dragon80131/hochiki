<html>
<head>
<title>通知メール__MansionName__</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>

__IfAjax__<link href="css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="js/jquery.ui.core.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
<script src="js/jquery.numberPicker.js" type="text/javascript"></script>

<script type="text/javascript">
$(function() {
	$("#wBirthday").datepicker({defaultDate: '__DEFAULTDATE__'});
	$("#wJoined").datepicker({});
	$("#wWithdrawn").datepicker({});
__IfDate1__		$("#wQuestion1").datepicker({});
__IfDate1____IfDate2__		$("#wQuestion2").datepicker({});
__IfDate2____IfDate3__		$("#wQuestion3").datepicker({});
__IfDate3____IfDate4__		$("#wQuestion4").datepicker({});
__IfDate4____IfDate5__		$("#wQuestion5").datepicker({});
__IfDate5____IfDate6__		$("#wQuestion6").datepicker({});
__IfDate6____IfDate7__		$("#wQuestion7").datepicker({});
__IfDate7____IfDate8__		$("#wQuestion8").datepicker({});
__IfDate8____IfDate9__		$("#wQuestion9").datepicker({});
__IfDate9____IfDate10__		$("#wQuestion10").datepicker({});
__IfDate10__
	$("#wJoinedHour").numberPicker({startNum: 0, endNum: 23, step: 1});
	$("#wJoinedMinute").numberPicker({startNum: 0, endNum: 59, step: 1});
	$("#wJoinedSecond").numberPicker({startNum: 0, endNum: 59, step: 1});
	$("#wWithdrawnHour").numberPicker({startNum: 0, endNum: 23, step: 1});
	$("#wWithdrawnMinute").numberPicker({startNum: 0, endNum: 59, step: 1});
	$("#wWithdrawnSecond").numberPicker({startNum: 0, endNum: 59, step: 1});
});
</script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__</head>

<body>
<a name="Top">

<div class="content">
<h2 class="admin-title">__MansionName__通知メール作成</h2>
<br />

__IfNormal__<h2 class="navigation"><a href="javascript:history.back();">&lt;&lt;&lt; 部屋番号リストへもどる</a></h2>
<h2 class="navigation"><a href="#" onclick="javascript:window.location.href='user_search.php?rKey=__rKey__'">&lt;&lt;&lt; 検索フォームへもどる</a></h2>
<h2 class="navigation"><a href="index.php">&lt;&lt;&lt; トップメニューへ</a></h2>
__IfNormal__<h2 class="navigation"><a href="#" onclick="javascript:window.close();">&lt;&lt;&lt; 閉じる</a></h2>

<br />

__IfReserveList__


【連絡】__today1__/__today2__　__MansionName__の
__IfChange__日程変更 __IfChange__
__IfListMenuCD__オプション受付__IfListMenuCD__
のご連絡です。 <br><br>

__Company__
<br>

<br>
いつもお世話になります。<br>　 
予約受付センター　木村　です。 <br>

__MansionName__の
__IfChange__日程変更 __IfChange__
__IfListMenuCD__オプション受付__IfListMenuCD__
のご連絡です。 <br><br>

(1件)<br>
__ID__号室<br>
__LastName__ __FirstName__ 様  __Extra1__　__Gender__<br>
__TEL__ <br>
__IfChange__
(変更前）
 __TimeFrom__<br> 

(変更後）
 __TimeFromA__<br>
__IfChange__

__IfNoChange__
 (工事日）__TimeFromA__<br>日程変更なし <br>
__IfNoChange__

__IfListMenuCD__
 オプション __ListMenuCDA__<br> 
__IfListMenuCD__


__Memo__

__IfReserveList__
__IfNoReserveList__ 仮予定のまま　__IfNoReserveList__


<br><br><br>



受付状況は、以下の作業工程表をご活用お願いします。<br>
__URL__<br>よろしくお願いします。<br><br>


<font color="red">受付フェーズ�△里箸�のみ記載する。</font><br>
【アイホン様宛】 <br>
__MansionName__ _専有部工程表__today__ を更新しております。 <br><br>


よろしくお願いします。


<form method="POST" action="user_list.php" name="mainform">

<br>予約センター専用　Inshare　URL<br>
<a href="https://app.inshare.jp/d3/dnet/xmail.cgi?page=maillist&fid=2&select=1">メール作成</a>
<br>



__HiddenValues__
</form>

<div class="footer-box">
	__SAdminCopyright__
</div>

</body>
</html>
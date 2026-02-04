<html>
<head>
<titleリニューアル支援</title>
<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a5.css">
<link rel="stylesheet" href="css/print.css" type="text/css" media="print" />

<script type="text/javascript" src="tools.js"></script>
<link href="css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="js/jquery.ui.core.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
<script src="js/jquery.numberPicker.js" type="text/javascript"></script>
<script type="text/javascript">
$(function() {
	$("#YoteTekyoDate").datepicker({});
	$("#YoteDate").datepicker({});
	$("#YoyakuEnd").datepicker({});
	$("#TekyoDate").datepicker({});
	$("#KeteiDate").datepicker({});
	$("#PhotoTekyoDate").datepicker({});
	$("#IraiDate").datepicker({});
});
</script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
<script type="text/javascript" src="js/ConnectedSelect.js"></script>

</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__


<hr size="__HRSize__" color="__HRColor__">
<center>《予約センター受付依頼》</center>
<hr size="__HRSize__" color="__HRColor__">

<!--
<a href="s_search.php__QUERY__&editBukkenCD=__editBukkenCD__ ">トップ</a><br>

__IfModify__
<a href="s_menu.php__QUERY__&editBukkenCD=__editBukkenCD__ ">メニュー</a>
__IfModify__

__IfError__
<br><br>
<font color="red" >ログインしたユーザの所属以外の物件を登録・編集することはできません。所属を確認ください。<br></font>
__IfError__

__IfErrorBukkenName__
<br><br>
<font color="red" >物件名が入力されていません。<br></font>
__IfErrorBukkenName__
-->

<!--ログインユーザー：__LastName__　物件CD：__editBukkenCD__<br>-->

<br>複写ボタンをクリックしますと依頼内容に反映されます。
<br>依頼内容をすでに入力している場合は、上書きされますのでご注意ください。
<br>最新の１００物件が表示されています。
<form action="s_489.php" method="POST" name="mainform">

<table border=1>

<tr>
<td bgcolor="lightgrey">物件CD</td>
<td bgcolor="lightgrey">物件名</td>
<td bgcolor="lightgrey">管理会社</td>
<td bgcolor="lightgrey">-</td>
</tr>

__listBukkenLoop__
<tr>
<td>__listBukkenCD__</td>
<td>__listBukkenName__</td>
<td>__listKanriGaisya__</td>
<td><input type="radio" name=checkcopy value="__listBukkenCD__"></td>
</tr>
__listBukkenLoop__

</table>
<br>

<input type=hidden name="rKey" value="__rKey__">
<input type=hidden name="editBukkenCD" value="__editBukkenCD__">
<input type=hidden name="wIraiRenkeiStatus" value="__wIraiRenkeiStatus__" >
<input type=hidden name="editIraiRenkeiCD" value="__editIraiRenkeiCD__" > 

<input type=submit value="  複写  " >
</form>

<input type="button" value=" 戻る " onClick="history.back();">


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

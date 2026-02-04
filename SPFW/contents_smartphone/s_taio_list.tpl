<html>
<head>
<title>営業活動支援システム</title>
<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a7.css">


<script type="text/javascript" src="tools.js"></script>

<link href="css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="js/jquery.ui.core.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
<script src="js/jquery.numberPicker.js" type="text/javascript"></script>

<script type="text/javascript">
$(function() {

	$("#wTaioDate").datepicker({});


});
</script>
<script type="text/javascript" src="js/tools_ajax.js"></script>

<script >
function moveWithWorkWithBukkenCD(page, work, key) {
			document.mainform2.editBukkenCD.value = key;
			document.mainform2.work.value = work;
			document.mainform2.action = page;
			document.mainform2.submit(true);
}
</script>

</head>



<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《物件情報/営業活動》</center>
<hr size="__HRSize__" color="__HRColor__">

<a href="s_list.php__QUERY__">>>>物件一覧</a><br>

<form action="s_form.php" name="mainform" method="POST" >
<input type=hidden name="editBukkenCD" value="__editBukkenCD__" >
<input type="hidden" name="rKey" value="__rKey__">
<a href="#" onclick="javascript:moveWithKey('s_form.php', __editBukkenCD__ )">>>>物件詳細情報</a>
</form>

__IfError__
 __ErrorLoop__
<font color="red" >__ErrorString__<br></font>
 __ErrorLoop__
__IfError__



<form action="s_taio_confirm.php" method="POST" name="mainform2">
<input type=hidden name="editBukkenCD" value="__editBukkenCD__" >
<input type=hidden name="editTaioCD" value="" >
<input type=hidden name="work" value="" >
<input type=hidden name="rKey" value="__rKey__">
<input type="button" value="新規折衝登録" class="button" onclick="javascript:moveWithWorkWithBukkenCD('s_taio_form.php',1, __editBukkenCD__)"> <br>

<br>
<table border=1><tr><td>物件名</td><td><b>__wBukkenName__</b></td><td> 担当名</td><td>__wTantoName__ </td></tr></table>
折衝記録一覧<br>
<table border=1>
__TaioListLoop__
<tr><td bgcolor="#e3f0fb">フェーズ</td><td>__Phase__</td><td bgcolor="#e3f0fb">折衝日時</td><td colspan=3 >__TaioDate__</td></tr>
<tr>

<td bgcolor="#e3f0fb">折衝担当</td><td>__TantoName__</td>
<td >
<input type="button" value="修　正" class="button" onclick="javascript:moveWithTaioCD('s_taio_form.php',__TaioCD__)">

</td ><td >
<input type="button" value="削　除" class="button" onclick="javascript:moveWithKeyAndWork5('s_taio_list.php' , __TaioCD__ , 2 , __TaioCD__  )">


</td></tr>


<tr><td bgcolor="#e3f0fb">内容</td><td colspan=3 >__DispTaioNotes__</td></tr>
<tr><td bgcolor="#e3f0fb">作成日時</td><td>__Created__</td><td bgcolor="#e3f0fb">更新日時</td><td>__Updated__</td></tr>
<tr><td bgcolor="#e3f000" colspan=4></td></tr>
__TaioListLoop__
</table>



</form>













<hr size="__HRSize__" color="__HRColor__">
__SCopyright__

<a href="javascript:window.history.back();">前のページに戻る</a>
<br><br>

</body>

</html>

<html>
<head>
<title>物件工程表管理</title>
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">


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





</head>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《折衝記録入力フォーム》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>




__IfError__
 __ErrorLoop__
<font color="red" >__ErrorString__<br></font>
 __ErrorLoop__
__IfError__


<form action="s_taio_confirm.php" method="POST" name="mainform" >

<table border=1>

<tr><td   bgcolor="#e3f0fb" >折衝フェーズ</td>
<td>




<select  name="wPhaseCD"  >
<option  value=0  >--</option>
<option  value=1 __PhaseCDSelected1__ >物件紹介</option>
<option  value=2 __PhaseCDSelected2__ >見積提出</option>
<option  value=3  __PhaseCDSelected3__  >理事長と折衝</option>
<option  value=4  __PhaseCDSelected4__  >理事会</option>
<option  value=5  __PhaseCDSelected5__  >総会</option>
<option  value=6  __PhaseCDSelected6__  >受注（失注/延期）報告</option>
<option  value=7  __PhaseCDSelected7__  >その他</option>
</select>
</form>
<script type="text/javascript">
var pulldownNo=1;
</script>


</td>
</tr>
<tr><td bgcolor="#e3f0fb" >担当者</td><td>
<!--
__TantoLoop__
	<option value="__TantoCD__" __TantoSelected__ >__TantoName__</option>
__TantoLoop__
-->

<select id="SEL1" name="wShozokuCD" >
__ShozokuLoop__
	<option value="__ShozokuCD__"  __ShozokuSelected__ >__ShozokuName__</option>
__ShozokuLoop__

</select>

担当
<select id="SEL2" name="wTantoCD" >
	__ShozokuLoop__
  		<optgroup label="__ShozokuName__">
		<option value="" >--</option>
		 __TantoBlock__
		</optgroup>
	__ShozokuLoop__
</select>




</td><tr>

<tr><td bgcolor="#e3f0fb" >折衝日</td><td><input type=text name="wTaioDate" id="wTaioDate" value="__wTaioDate__" >  </p>
</td></tr>

<tr><td bgcolor="#e3f0fb" >折衝内容</td>
<td>

<textarea cols=80 rows=20 name="wTaioNotes"  >

__wTaioNotes__
</textarea>

</td></tr>





</table>









<br>

<input type=hidden name="rKey" value="__rKey__">
<input type=hidden name="editTaioCD" value="__editTaioCD__">
<input type=hidden name="editBukkenCD" value="__editBukkenCD__">

<input type=submit value = "登　録" >

</form>



<hr size="__HRSize__" color="__HRColor__">
<a href="s_list.php__QUERY__">物件一覧</a><br>
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>


</body>

</html>

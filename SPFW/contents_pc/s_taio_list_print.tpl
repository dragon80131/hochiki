<html>
<head>
<title>折衝記録印刷</title>
<link rel="stylesheet" href="css/print2.css" type="text/css">
<script type="text/javascript" src="tools.js"></script>

<link href="css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="js/jquery.ui.core.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
<script src="js/jquery.numberPicker.js" type="text/javascript"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>


</head>


<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">



<p class="sampleTxt02"><font color=red >__wBukkenName__<br>★★縦向きに印刷し、ヘッダーとフッターはブラウザの印刷機能で、「印刷しない」<br>にチェックお願いします。★★</font><br></p>
<!--<form action="s_taio_confirm.php" method="POST" name="mainform2">
<input type=hidden name="editBukkenCD" value="__editBukkenCD__" >
<input type=hidden name="editTaioCD" value="" >
<input type=hidden name="work" value="" >
<input type=hidden name="rKey" value="__rKey__">
<p class="sampleTxt02"><a href="s_taio_list.php__QUERY__&editBukkenCD=__editBukkenCD__">　＞＞＞折衝記録一覧に戻る</a></p>
</form>-->


__IfNoRecord__
折衝記録は登録されておりません。<br>
__IfNoRecord__

__IfRecord__
<p>__wBukkenName__ 折衝記録一覧</p>
<table class="sampletable" style="font-size : 13px; width:800px">

__TaioListLoop__
<tr><td bgcolor="#e3f0fb" width="100">フェーズ</td><td>__Phase__</td>
	<td bgcolor="#e3f0fb">担当</td><td>__TantoName__</td>
	<td bgcolor="#e3f0fb">折衝日時</td><td>__TaioDate__</td>
	<td bgcolor="#e3f0fb">登録日時</td><td>__Created__</td></tr>
<tr><td bgcolor="#e3f0fb">内容</td><td colspan="7">__DispTaioNotes__</td></tr>
<tr><td bgcolor="#e3f0fb">別資料</td>
	<td colspan="7"><!-- __LinesLoop__ __FileGencho__  __LinesLoop__-->
	__LinesBlock__
    </td></tr>
<tr><td colspan="8"><hr size="__HRSize__" color="__HRColor__"></td></tr>

__IfPageBreak__
<!--ページ区切り--->
</table>

<p class="pagebreak">__wBukkenName__ 折衝記録一覧</p>

<table class="sampletable" style="font-size : 13px; width:800px">
__IfPageBreak__

__TaioListLoop__
</table>

__IfRecord__

</form>




</body>
</html>

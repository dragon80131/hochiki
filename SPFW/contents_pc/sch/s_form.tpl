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
	$("#wBirthday").datepicker({defaultDate: '__DEFAULTDATE__'});
	$("#wJoined").datepicker({});
	$("#wWithdrawn").datepicker({});

	$("#wKyoyoStartDate").datepicker({});
	$("#wKyoyoEndDate").datepicker({});
	$("#wSenyuStartDate").datepicker({});
	$("#wSenyuEndDate").datepicker({});

	$("#wJoinedHour").numberPicker({startNum: 0, endNum: 23, step: 1});
	$("#wJoinedMinute").numberPicker({startNum: 0, endNum: 59, step: 1});
	$("#wJoinedSecond").numberPicker({startNum: 0, endNum: 59, step: 1});
	$("#wWithdrawnHour").numberPicker({startNum: 0, endNum: 23, step: 1});
	$("#wWithdrawnMinute").numberPicker({startNum: 0, endNum: 59, step: 1});
	$("#wWithdrawnSecond").numberPicker({startNum: 0, endNum: 59, step: 1});
});
</script>
<script type="text/javascript" src="js/tools_ajax.js"></script>


</head>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《物件情報入力フォーム》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>



__IfError__
 __ErrorLoop__
<font color="red" >__ErrorString__<br></font>
 __ErrorLoop__
__IfError__

<form action="s_confirm.php" method="POST">
<table border=1>
<tr><td  >物件CD</td><td>__editBukkenCD__</td></tr>
<tr><td  ><b>物件名※必須</b></td><td><input type=text name="wBukkenName" value="__wBukkenName__"></td></tr>
<tr><td  >カラー</td>



<td>
<select name="wBukkenColor" >
__ColorLoop__

<option value="__BukkenColorCD__" color="__ColorValue__"  __ColorChecked__ > __ColorName__ </option>
__ColorLoop__
</select>


</td></tr>
<tr><td  >戸数</td><td><input type=text name="wKosu" value="__wKosu__"></td></tr>
<tr><td  >営業担当CD</td>
<td>

<select name = "tantolist">
<option value="">-</option>
__TantoLoop__
<option value = "__TantoNo__" __TantoSelected__>__TantoName__</option>
__TantoLoop__
</select>

</td></tr>


<tr><td>物件備考</td><td><textarea cols=40 rows=5 name="wBukkenNotes" >__wBukkenNotes__</textarea></td></tr>

<tr><td>共有部開始日</td>
	<td><input type=text name="wKyoyoStartDate"  id="wKyoyoStartDate"  value="__wKyoyoStartDate__"  ><font color="red">※1</font></td></tr>
<tr><td>共有部終了日</td>
	<td><input type=text name="wKyoyoEndDate"  id="wKyoyoEndDate"  value="__wKyoyoEndDate__"  ><font color="red">※1</font></td></tr>
<tr><td>専有部開始日</td>
	<td><input type=text name="wSenyuStartDate"  id="wSenyuStartDate"  value="__wSenyuStartDate__"  ><font color="red">※1</font></td></tr>
<tr><td>専有部終了日</td>
	<td><input type=text name="wSenyuEndDate"  id="wSenyuEndDate"  value="__wSenyuEndDate__"  ><font color="red">※1</font>
</td></tr>


<td>初期設定作業員</td>
	<td>
	__SagyoinListLoop__
	<input type="checkbox" name="wDefaultSagyoinCD[]" value="__SagyoinCD__" __wDefaultSagyoinChecked__>__SagyoinName__
	__SagyoinListLoop__
	</td></tr>
<tr><td>工事番号</td><td><input type=text name="wSetsumei" value="__wSetsumei__"></td></tr>
<tr><td>物件URL</td><td><input type=text name="wURL" value="__wURL__" size=70></td></tr>

<tr><td bgcolor="wheat" >見積NO</td><td><input type=text name="wMitsumoriNo" value="__wMitsumoriNo__"></td></tr>
<tr><td bgcolor="wheat"  >受注額</td><td><input type=text name="wJucyugaku" value="__wJucyugaku__"></td></tr>
<tr><td bgcolor="wheat"  >請求先会社名　名前</td><td><input type=text name="wSeikyuNotes" value="__wSeikyuNotes__"></td></tr>
<tr><td bgcolor="wheat"  >現場担当</td><td><input type=text name="wGenbaTanto" value="__wGenbaTanto__"></td></tr>
<tr><td bgcolor="wheat" >ネスペ</td><td><input type=text name="wNespe" value="__wNespe__"></td></tr>
<tr><td bgcolor="wheat" >パネル手配</td><td><input type=text name="wPanel" value="__wPanel__"></td></tr>
<tr><td bgcolor="wheat" >材料手配</td><td><input type=text name="wZairyo" value="__wZairyo__"></td></tr>
<tr><td bgcolor="wheat" >機器手配</td><td><input type=text name="wKiki" value="__wKiki__"></td></tr>
<tr><td bgcolor="wheat" >機器完成図手配</td><td><input type=text name="wKanseizu" value="__wKanseizu__"></td></tr>
<tr><td bgcolor="wheat" >系統図</td><td><input type=text name="wKeitozu" value="__wKeitozu__"></td></tr>
<tr><td bgcolor="wheat" >入金予定日</td><td><input type=text name="wNyukinDate" value="__wNyukinDate__"></td></tr>


<tr><td  >工事完了チェック</td><td><input type=checkbox name="wComplete" value="1" __CompleteChecked__>工事完了※完了物件一覧に表示されます</td></tr>

<tr><td  >登録状態</td><td>
<input type="radio" name=wRegiStatus value=1 __RegiStatusChecked1__ >本登録
<input type="radio" name=wRegiStatus value=2 __RegiStatusChecked2__ >仮登録
</td></tr>

<tr>

</table>
<font color="red">※1 新規登録以外、開始日・終了日を編集する場合は、物件工程修正画面で日程のチェックを入れないと人工表には反映されません。</font>

<br><br>
<input type=hidden name="editBukkenCD" value="__editBukkenCD__">
<input type=hidden name="rKey" value="__rKey__">
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

<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>リニューアル支援</title>

	<!-- BootstrapのCSS読み込み -->
	<link href="../include/bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<!-- jQuery読み込み -->
	<script src="../include/js/jquery-3.2.1.min.js"></script>

	<!-- BootstrapのJS読み込み -->
	<script src="../include/bootstrap/js/bootstrap.min.js"></script>

	<link rel="stylesheet" type="text/css" href="../css/rnsien.css">
	<script type="text/javascript" src="./tools.js"></script>
</head>
<title>物件工程表管理</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《物件工程修正》</center>
<hr size="__HRSize__" color="__HRColor__">

<a href="s_plan.php__QUERY__">人工表</a><br>
<br>
__IfsagyoinOK__
作業員CD：__editSagyoinCD__
<br>作業員名：__editSagyoinName__
__IfsagyoinOK__

<form action="s_sagyoin.php"  method="POST" >
<input type="hidden" name="rKey" value="__rKey__">
<input type="hidden" name="editSagyoinCD" value="__editSagyoinCD__" >

<select name = "yearlist">
__YearLoop__
<option value = "__year__" __yearselected__>__year__</option>
__YearLoop__
</select>
年

<select name = "monthlist">
__MonthLoop__
<option value = "__month__" __monthselected__>__month__</option>
__MonthLoop__
</select>
月


<input type="submit" value=" 表示 "> ※前ページで表示したい月を先に選んでください。


</form>



<br>
<table class="table table-bordered table-sm"   ><tr bgcolor="lightgrey">
<td colspan= 2 rowspan="3" >物件名</td>


<td colspan=31>__tYear__　年__tMonth__　月</td>
</tr>


<tr>
__DayLoop__
<td bgcolor="__dc__">__dd__</td>
__DayLoop__
</tr>

<tr>
__WeekLoop__
<td bgcolor="__dc__">__dw__</td>
__WeekLoop__
</tr>




<form action="s_plan.php" metho="POST" name="mainform" >
<input type=hidden name="rKey" value="__rKey__" >
<input type=hidden name="work" value="1" >

__BukkenLoop__
<tr>

<td  bgcolor="__wBukkenColor__"><font color="__wBukkenTextColor__">__BukkenCD__</font></td>
<td >__BukkenName__</td>



<td  bgcolor="__Bcolor01__" ><input type="checkbox" name="SS01[]" value="__BukkenCD__01" __SSChecked01__></td>
<td  bgcolor="__Bcolor02__" ><input type="checkbox" name="SS02[]" value="__BukkenCD__02" __SSChecked02__></td>
<td  bgcolor="__Bcolor03__" ><input type="checkbox" name="SS03[]" value="__BukkenCD__03" __SSChecked03__></td>
<td  bgcolor="__Bcolor04__" ><input type="checkbox" name="SS04[]" value="__BukkenCD__04" __SSChecked04__></td>
<td  bgcolor="__Bcolor05__" ><input type="checkbox" name="SS05[]" value="__BukkenCD__05" __SSChecked05__></td>
<td  bgcolor="__Bcolor06__" ><input type="checkbox" name="SS06[]" value="__BukkenCD__06" __SSChecked06__></td>
<td  bgcolor="__Bcolor07__" ><input type="checkbox" name="SS07[]" value="__BukkenCD__07" __SSChecked07__></td>
<td  bgcolor="__Bcolor08__" ><input type="checkbox" name="SS08[]" value="__BukkenCD__08" __SSChecked08__></td>
<td  bgcolor="__Bcolor09__" ><input type="checkbox" name="SS09[]" value="__BukkenCD__09" __SSChecked09__></td>
<td  bgcolor="__Bcolor10__" ><input type="checkbox" name="SS10[]" value="__BukkenCD__10" __SSChecked10__></td>
<td  bgcolor="__Bcolor11__" ><input type="checkbox" name="SS11[]" value="__BukkenCD__11" __SSChecked11__></td>
<td  bgcolor="__Bcolor12__" ><input type="checkbox" name="SS12[]" value="__BukkenCD__12" __SSChecked12__></td>
<td  bgcolor="__Bcolor13__" ><input type="checkbox" name="SS13[]" value="__BukkenCD__13" __SSChecked13__></td>
<td  bgcolor="__Bcolor14__" ><input type="checkbox" name="SS14[]" value="__BukkenCD__14" __SSChecked14__></td>
<td  bgcolor="__Bcolor15__" ><input type="checkbox" name="SS15[]" value="__BukkenCD__15" __SSChecked15__></td>
<td  bgcolor="__Bcolor16__" ><input type="checkbox" name="SS16[]" value="__BukkenCD__16" __SSChecked16__></td>
<td  bgcolor="__Bcolor17__" ><input type="checkbox" name="SS17[]" value="__BukkenCD__17" __SSChecked17__></td>
<td  bgcolor="__Bcolor18__" ><input type="checkbox" name="SS18[]" value="__BukkenCD__18" __SSChecked18__></td>
<td  bgcolor="__Bcolor19__" ><input type="checkbox" name="SS19[]" value="__BukkenCD__19" __SSChecked19__></td>
<td  bgcolor="__Bcolor20__" ><input type="checkbox" name="SS20[]" value="__BukkenCD__20" __SSChecked20__></td>
<td  bgcolor="__Bcolor21__" ><input type="checkbox" name="SS21[]" value="__BukkenCD__21" __SSChecked21__></td>
<td  bgcolor="__Bcolor22__" ><input type="checkbox" name="SS22[]" value="__BukkenCD__22" __SSChecked22__></td>
<td  bgcolor="__Bcolor23__" ><input type="checkbox" name="SS23[]" value="__BukkenCD__23" __SSChecked23__></td>
<td  bgcolor="__Bcolor24__" ><input type="checkbox" name="SS24[]" value="__BukkenCD__24" __SSChecked24__></td>
<td  bgcolor="__Bcolor25__" ><input type="checkbox" name="SS25[]" value="__BukkenCD__25" __SSChecked25__></td>
<td  bgcolor="__Bcolor26__" ><input type="checkbox" name="SS26[]" value="__BukkenCD__26" __SSChecked26__></td>
<td  bgcolor="__Bcolor27__" ><input type="checkbox" name="SS27[]" value="__BukkenCD__27" __SSChecked27__></td>
<td  bgcolor="__Bcolor28__" ><input type="checkbox" name="SS28[]" value="__BukkenCD__28" __SSChecked28__></td>
__Ifday29__	<td  bgcolor="__Bcolor29__" ><input type="checkbox" name="SS29[]" value="__BukkenCD__29" __SSChecked29__></td>
__Ifday29__
__Ifday30__	<td  bgcolor="__Bcolor30__" ><input type="checkbox" name="SS30[]" value="__BukkenCD__30" __SSChecked30__></td>
__Ifday30__
__Ifday31__	<td  bgcolor="__Bcolor31__" ><input type="checkbox" name="SS31[]" value="__BukkenCD__31" __SSChecked31__></td>
__Ifday31__


</tr>

__Ifshowday__
<tr><td colspan=2><input type=submit value= "　更新　" ></td></tr>
<tr bgcolor="lightgrey">
<td colspan= 2 rowspan="2">物件名</td>
__DayLoop__
<td bgcolor="__dc__">__dd__</td>
__DayLoop__
</tr>

<tr bgcolor="lightgrey">
__WeekLoop__
<td bgcolor="__dc__">__dw__</td>
__WeekLoop__
</tr>
__Ifshowday__


__BukkenLoop__



</table>

<input type="hidden" name="editSagyoinCD" value="__editSagyoinCD__">

<input type="hidden" name="editYear" value="__tYear__" >
<input type="hidden" name="editMonth" value="__tMonth__" >


<input type=hidden name="work" value=1 >
<input type=submit value= "　更新　" >


</form>

<hr size="__HRSize__" color="__HRColor__">
<a href="s_plan.php__QUERY__">人工表</a><br>
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</html>

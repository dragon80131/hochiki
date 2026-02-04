<html>
<head>

<LINK REL="stylesheet" TYPE="text/css" HREF="css/a5.css">
<script type="text/javascript" src="tools.js"></script>


</head>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《残工事・現調追加》</center>
<hr size="__HRSize__" color="__HRColor__">




<form action="s_zan.php"  method="POST" >
<input type="hidden" name="rKey" value="__rKey__">


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


<input type="submit" value=" 表示 ">


</form>






<br>
<table class="sampleTable" ><tr bgcolor="lightgrey"  >
<td colspan= 2 rowspan="3" >　</td>


<td colspan=__daycount__>__tYear__　年__tMonth__　月</td>
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
<input type=hidden name="editDateCD" value="" >
<input type=hidden name="editZanSagyoGroupCD" value="" >

<tr>
<td bgcolor="lightgrey"></td>
<td bgcolor="lightgrey"></td>

<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 1 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 2 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 3 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 4 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 5 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 6 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 7 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 8 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 9 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 10 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 11 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 12 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 13 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 14 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 15 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 16 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 17 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 18 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 19 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 20 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 21 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 22 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 23 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 24 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 25 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 26 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 27 );">追加</a></td>
<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 28 );">追加</a></td>
__Ifday29__	<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 29 );">追加</a></td>
__Ifday29__
__Ifday30__	<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 30 );">追加</a></td>
__Ifday30__
__Ifday31__	<td ><a href="#" onclick="javascript:moveWithDateCD('s_zan_form.php', 31 );">追加</a></td>
__Ifday31__

</tr>


__ZanScheduleLoop__
<tr>
<div id="main">
<td bgcolor="lightgrey"></td>
<td bgcolor="lightgrey"></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD01__ );" title="作業内容：__ZanNote01__">__ZanSagyoGroupDisp01__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD02__ );" title="作業内容：__ZanNote02__">__ZanSagyoGroupDisp02__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD03__ );" title="作業内容：__ZanNote03__">__ZanSagyoGroupDisp03__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD04__ );" title="作業内容：__ZanNote04__">__ZanSagyoGroupDisp04__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD05__ );" title="作業内容：__ZanNote05__">__ZanSagyoGroupDisp05__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD06__ );" title="作業内容：__ZanNote06__">__ZanSagyoGroupDisp06__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD07__ );" title="作業内容：__ZanNote07__">__ZanSagyoGroupDisp07__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD08__ );" title="作業内容：__ZanNote08__">__ZanSagyoGroupDisp08__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD09__ );" title="作業内容：__ZanNote09__">__ZanSagyoGroupDisp09__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD10__ );" title="作業内容：__ZanNote10__">__ZanSagyoGroupDisp10__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD11__ );" title="作業内容：__ZanNote11__">__ZanSagyoGroupDisp11__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD12__ );" title="作業内容：__ZanNote12__">__ZanSagyoGroupDisp12__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD13__ );" title="作業内容：__ZanNote13__">__ZanSagyoGroupDisp13__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD14__ );" title="作業内容：__ZanNote14__">__ZanSagyoGroupDisp14__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD15__ );" title="作業内容：__ZanNote15__">__ZanSagyoGroupDisp15__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD16__ );" title="作業内容：__ZanNote16__">__ZanSagyoGroupDisp16__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD17__ );" title="作業内容：__ZanNote17__">__ZanSagyoGroupDisp17__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD18__ );" title="作業内容：__ZanNote18__">__ZanSagyoGroupDisp18__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD19__ );" title="作業内容：__ZanNote19__">__ZanSagyoGroupDisp19__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD20__ );" title="作業内容：__ZanNote20__">__ZanSagyoGroupDisp20__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD21__ );" title="作業内容：__ZanNote21__">__ZanSagyoGroupDisp21__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD22__ );" title="作業内容：__ZanNote22__">__ZanSagyoGroupDisp22__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD23__ );" title="作業内容：__ZanNote23__">__ZanSagyoGroupDisp23__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD24__ );" title="作業内容：__ZanNote24__">__ZanSagyoGroupDisp24__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD25__ );" title="作業内容：__ZanNote25__">__ZanSagyoGroupDisp25__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD26__ );" title="作業内容：__ZanNote26__">__ZanSagyoGroupDisp26__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD27__ );" title="作業内容：__ZanNote27__">__ZanSagyoGroupDisp27__</a></td>
<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD28__ );" title="作業内容：__ZanNote28__">__ZanSagyoGroupDisp28__</a></td>
__Ifday29__	<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD29__ );" title="作業内容：__ZanNote29__">__ZanSagyoGroupDisp29__</a></td>
__Ifday29__
__Ifday30__	<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD30__ );" title="作業内容：__ZanNote30__">__ZanSagyoGroupDisp30__</a></td>
__Ifday30__
__Ifday31__	<td ><a href="#" onclick="javascript:moveWithZanSagyoGroupCD('s_zan_del_form.php', __ZanSagyoGroupCD31__ );" title="作業内容：__ZanNote31__">__ZanSagyoGroupDisp31__</a></td>
__Ifday31__

</div>
</tr>
__ZanScheduleLoop__


<tr bgcolor="lightgrey">
<td colspan= 1 rowspan="3">作業員CD</td>
<td colspan= 1 rowspan="3">作業員名</td>
<td colspan="__daycount__">__tYear__　年__tMonth__　月</td>
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


__SagyoinLoop__
<tr>
<td>__SagyoinCD1__</td>
<td>__SagyoinName1__</td>


<td >__SD01__</td>
<td >__SD02__</td>
<td >__SD03__</td>
<td >__SD04__</td>
<td >__SD05__</td>
<td >__SD06__</td>
<td >__SD07__</td>
<td >__SD08__</td>
<td >__SD09__</td>
<td >__SD10__</td>
<td >__SD11__</td>
<td >__SD12__</td>
<td >__SD13__</td>
<td >__SD14__</td>
<td >__SD15__</td>
<td >__SD16__</td>
<td >__SD17__</td>
<td >__SD18__</td>
<td >__SD19__</td>
<td >__SD20__</td>
<td >__SD21__</td>
<td >__SD22__</td>
<td >__SD23__</td>
<td >__SD24__</td>
<td >__SD25__</td>
<td >__SD26__</td>
<td >__SD27__</td>
<td >__SD28__</td>
__Ifday29__	<td >__SD29__</td>
__Ifday29__
__Ifday30__	<td >__SD30__</td>
__Ifday30__
__Ifday31__	<td >__SD31__</td>
__Ifday31__

</tr>

__SagyoinLoop__




</table>

<input type="hidden" name="tYear" value="__tYear__" >
<input type="hidden" name="tMonth" value="__tMonth__" >


<input type=hidden name="work" value=1 >
<input type=submit value= "　更新　" >


</form>

<hr size="__HRSize__" color="__HRColor__">
<a href="s_plan.php__QUERY__">作業工程表</a><br>
<a href="s_list.php__QUERY__">物件一覧</a><br>



<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</html>

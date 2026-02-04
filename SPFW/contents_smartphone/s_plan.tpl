<html>
<head>

<LINK REL="stylesheet" TYPE="text/css" HREF="css/a5.css">
<script type="text/javascript" src="tools.js"></script>
</head>
<title>物件工程表管理</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">

<hr size="__HRSize__" color="__HRColor__">
<center>《人工表》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>





<table class="sampleTable" > <tr><td>
<a href="s_list.php__QUERY__">＞＞＞物件一覧</a><br>
<a href="#" onclick="javascript:moveWithSagyoinCD('s_sagyoin.php', 0 );">＞＞＞物件工程修正</a> <br>
<a href="#" onclick="javascript:moveWithSagyoinCD('s_zan.php', 999 );">＞＞＞残工事・現調追加</a> 
</td>
<td>
<form action="s_plan.php"  method="POST" >
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
</td>
<td> &nbsp</td>
<td>
物件表示は、物件の専有部工事期間が過ぎていない。<br>
または共有部工事が始まっている必要があります。

</td>



<table class="sampleTable"><tr bgcolor="lightgrey">
<td rowspan="3">No</td>
<td rowspan="3">物件名</td>
<td rowspan="3">戸数</td>
<td rowspan="3">物件備考</td>
<td rowspan="3">営業</td>
<td rowspan="3">監督</td>
<td rowspan="3">説明</td>

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








__BukkenLoop__
<tr>

<td bgcolor="__wBukkenColor__">__BukkenCD__</td>
<td >__BukkenName__</td>
<td >__Kosu__</td>
<td >__BukkenNotes__</td>
<td >__TantoCD__</td>
<td >__GenbaTanto__</td>
<td >__Setsumei__</td>

<td  bgcolor="__Bcolor01__" >__Schk01__</td>
<td  bgcolor="__Bcolor02__" >__Schk02__</td>
<td  bgcolor="__Bcolor03__" >__Schk03__</td>
<td  bgcolor="__Bcolor04__" >__Schk04__</td>
<td  bgcolor="__Bcolor05__" >__Schk05__</td>
<td  bgcolor="__Bcolor06__" >__Schk06__</td>
<td  bgcolor="__Bcolor07__" >__Schk07__</td>
<td  bgcolor="__Bcolor08__" >__Schk08__</td>
<td  bgcolor="__Bcolor09__" >__Schk09__</td>
<td  bgcolor="__Bcolor10__" >__Schk10__</td>
<td  bgcolor="__Bcolor11__" >__Schk11__</td>
<td  bgcolor="__Bcolor12__" >__Schk12__</td>
<td  bgcolor="__Bcolor13__" >__Schk13__</td>
<td  bgcolor="__Bcolor14__" >__Schk14__</td>
<td  bgcolor="__Bcolor15__" >__Schk15__</td>
<td  bgcolor="__Bcolor16__" >__Schk16__</td>
<td  bgcolor="__Bcolor17__" >__Schk17__</td>
<td  bgcolor="__Bcolor18__" >__Schk18__</td>
<td  bgcolor="__Bcolor19__" >__Schk19__</td>
<td  bgcolor="__Bcolor20__" >__Schk20__</td>
<td  bgcolor="__Bcolor21__" >__Schk21__</td>
<td  bgcolor="__Bcolor22__" >__Schk22__</td>
<td  bgcolor="__Bcolor23__" >__Schk23__</td>
<td  bgcolor="__Bcolor24__" >__Schk24__</td>
<td  bgcolor="__Bcolor25__" >__Schk25__</td>
<td  bgcolor="__Bcolor26__" >__Schk26__</td>
<td  bgcolor="__Bcolor27__" >__Schk27__</td>
<td  bgcolor="__Bcolor28__" >__Schk28__</td>
__Ifday29__	<td  bgcolor="__Bcolor29__" >__Schk29__</td>
__Ifday29__
__Ifday30__	<td  bgcolor="__Bcolor30__" >__Schk30__</td>
__Ifday30__
__Ifday31__	<td  bgcolor="__Bcolor31__" >__Schk31__</td>
__Ifday31__

</tr>

__BukkenLoop__

<!--工期外・臨時-->



<tr>
<td  bgcolor="lightgreen" rowspan="2" colspan="7">残工事・現調・臨時など</td>


__DayLoop__
<td bgcolor="__dc__">__dd__</td>
__DayLoop__
</tr>

<tr>
__WeekLoop__
<td bgcolor="__dc__">__dw__</td>
__WeekLoop__
</tr>


__ZanScheduleLoop__
<tr>
<td  bgcolor="lightgreen"  colspan="7"></td>
<td  ><a href="#" title="作業内容：__ZanNote01__">__ZanSagyoGroupDisp01__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote02__">__ZanSagyoGroupDisp02__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote03__">__ZanSagyoGroupDisp03__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote04__">__ZanSagyoGroupDisp04__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote05__">__ZanSagyoGroupDisp05__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote06__">__ZanSagyoGroupDisp06__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote07__">__ZanSagyoGroupDisp07__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote08__">__ZanSagyoGroupDisp08__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote09__">__ZanSagyoGroupDisp09__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote10__">__ZanSagyoGroupDisp10__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote11__">__ZanSagyoGroupDisp11__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote12__">__ZanSagyoGroupDisp12__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote13__">__ZanSagyoGroupDisp13__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote14__">__ZanSagyoGroupDisp14__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote15__">__ZanSagyoGroupDisp15__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote16__">__ZanSagyoGroupDisp16__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote17__">__ZanSagyoGroupDisp17__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote18__">__ZanSagyoGroupDisp18__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote19__">__ZanSagyoGroupDisp19__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote20__">__ZanSagyoGroupDisp20__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote21__">__ZanSagyoGroupDisp21__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote22__">__ZanSagyoGroupDisp22__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote23__">__ZanSagyoGroupDisp23__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote24__">__ZanSagyoGroupDisp24__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote25__">__ZanSagyoGroupDisp25__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote26__">__ZanSagyoGroupDisp26__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote27__">__ZanSagyoGroupDisp27__</a></td>
<td  ><a href="#" title="作業内容：__ZanNote28__">__ZanSagyoGroupDisp28__</a></td>
__Ifday29__	<td  ><a href="#" title="作業内容：__ZanNote29__">__ZanSagyoGroupDisp29__</a></td>
__Ifday29__
__Ifday30__	<td  ><a href="#" title="作業内容：__ZanNote30__">__ZanSagyoGroupDisp30__</a></td>
__Ifday30__
__Ifday31__	<td  ><a href="#" title="作業内容：__ZanNote31__">__ZanSagyoGroupDisp31__</a></td>
__Ifday31__


</tr>

__ZanScheduleLoop__



<!--工期外・臨時-->



<tr bgcolor="lightgrey">
<td rowspan="3"></td>
<td rowspan="3">作業員名</td>
<td rowspan="3" colspan="5">作業員メモ</td>
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





<form action="s_sagyoin.php" name="mainform" method="POST" >
<input type="hidden" name="rKey" value="__rKey__">
<input type="hidden" name="editYear" value="__tYear__">
<input type="hidden" name="editMonth" value="__tMonth__">
<input type="hidden" name="editSagyoinCD" value="" >

__SagyoinLoop__

<tr>
<td>
<a href="#" onclick="javascript:moveWithSagyoinCD('s_sagyoin.php', __SagyoinCD1__ );">修正</a>
</td>

<td>__SagyoinName1__</td>
<td  colspan=5  >-</td>

<td  bgcolor="__Scolor01__" >__SD01__</td>
<td  bgcolor="__Scolor02__" >__SD02__</td>
<td  bgcolor="__Scolor03__" >__SD03__</td>
<td  bgcolor="__Scolor04__" >__SD04__</td>
<td  bgcolor="__Scolor05__" >__SD05__</td>
<td  bgcolor="__Scolor06__" >__SD06__</td>
<td  bgcolor="__Scolor07__" >__SD07__</td>
<td  bgcolor="__Scolor08__" >__SD08__</td>
<td  bgcolor="__Scolor09__" >__SD09__</td>
<td  bgcolor="__Scolor10__" >__SD10__</td>
<td  bgcolor="__Scolor11__" >__SD11__</td>
<td  bgcolor="__Scolor12__" >__SD12__</td>
<td  bgcolor="__Scolor13__" >__SD13__</td>
<td  bgcolor="__Scolor14__" >__SD14__</td>
<td  bgcolor="__Scolor15__" >__SD15__</td>
<td  bgcolor="__Scolor16__" >__SD16__</td>
<td  bgcolor="__Scolor17__" >__SD17__</td>
<td  bgcolor="__Scolor18__" >__SD18__</td>
<td  bgcolor="__Scolor19__" >__SD19__</td>
<td  bgcolor="__Scolor20__" >__SD20__</td>
<td  bgcolor="__Scolor21__" >__SD21__</td>
<td  bgcolor="__Scolor22__" >__SD22__</td>
<td  bgcolor="__Scolor23__" >__SD23__</td>
<td  bgcolor="__Scolor24__" >__SD24__</td>
<td  bgcolor="__Scolor25__" >__SD25__</td>
<td  bgcolor="__Scolor26__" >__SD26__</td>
<td  bgcolor="__Scolor27__" >__SD27__</td>
<td  bgcolor="__Scolor28__" >__SD28__</td>
__Ifday29__	<td  bgcolor="__Scolor29__" >__SD29__</td>
__Ifday29__
__Ifday30__	<td  bgcolor="__Scolor30__" >__SD30__</td>
__Ifday30__
__Ifday31__	<td  bgcolor="__Scolor31__" >__SD31__</td>
__Ifday31__

</tr>

__SagyoinLoop__




__ZanSagyoinLoop__
<tr>
<td></td>
<td>__ZanSagyoinName1__</td>
<td colspan="5">残工事・現場調査など</td>

<td  >__zanSD01__</td>
<td  >__zanSD02__</td>
<td  >__zanSD03__</td>
<td  >__zanSD04__</td>
<td  >__zanSD05__</td>
<td  >__zanSD06__</td>
<td  >__zanSD07__</td>
<td  >__zanSD08__</td>
<td  >__zanSD09__</td>
<td  >__zanSD10__</td>
<td  >__zanSD11__</td>
<td  >__zanSD12__</td>
<td  >__zanSD13__</td>
<td  >__zanSD14__</td>
<td  >__zanSD15__</td>
<td  >__zanSD16__</td>
<td  >__zanSD17__</td>
<td  >__zanSD18__</td>
<td  >__zanSD19__</td>
<td  >__zanSD20__</td>
<td  >__zanSD21__</td>
<td  >__zanSD22__</td>
<td  >__zanSD23__</td>
<td  >__zanSD24__</td>
<td  >__zanSD25__</td>
<td  >__zanSD26__</td>
<td  >__zanSD27__</td>
<td  >__zanSD28__</td>
__Ifday29__	<td  >__zanSD29__</td>
__Ifday29__
__Ifday30__	<td  >__zanSD30__</td>
__Ifday30__
__Ifday31__	<td  >__zanSD31__</td>
__Ifday31__

</tr>

__ZanSagyoinLoop__


</table>



</form>

<hr size="__HRSize__" color="__HRColor__">


<br>
</body>
</html>

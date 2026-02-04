<html>
<head>
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
	
	
<script>
function moveWithSagyoinCD2(page, key) {
	document.mainform2.editSagyoinCD.value = key;
	document.mainform2.action = page;
	document.mainform2.submit(true);
}
</script>

</head>

<style type="text/css">
hr {
page-break-before: always;
}


@media print{
td.nondisp{
	display:none;
	}
body {
zoom: 70%;
}

}
</style>
</head>
<title>物件工程表管理</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">


<center>《人工表・工程管理表》</center>

<br>

<form action="s_plan.php"  method="POST" name="mainform2">
<input type="hidden" name="rKey" value="__rKey__">
<input type="hidden" name="editSagyoinCD"  >

<div class="main">
<table class="left"><!--テーブル①-->
<tr align="left" ><td >
<!--<a href="s_list.php__QUERY__">＞＞＞物件一覧</a><br>-->

<button type="button" class="btn btn-primary" onclick="move('../s_search.php?__rKey__', 0 );" >トップ</button>
<button type="button" class="btn btn-primary" onclick="moveWithSagyoinCD2('s_sagyoin.php', 0 );" >物件工程日修正</button>
<button type="button" class="btn btn-primary" onclick="moveWithSagyoinCD2('s_zan.php', 999 );" >残工事・現調追加</button>
<button type="button" class="btn btn-primary" onclick="move('s_plan_rn.php?__rKey__', 0 );" >アイホン</button>



</td>
</tr><tr>
<td>
・物件基本情報の共用部工事期間（表示k)と専有部工事期間（表示s)が物件ごとに表示されます。<br>
・作業員の表示の制御は、トップページの下部の「ユーザ―管理」から行えます。営業/施工区分を「営業」なら表示されず、「施工」なら表示されます。<br>
・アイホンさまから工事依頼のある物件を取り込むことができます。（物件名、住所、工期のみ）
</td>
</tr><tr>
<td class="nondisp">

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
<td class="nondisp"> &nbsp</td>
<td class="nondisp">
物件の色を印刷するには、ここ（<a href=./images/print.gif>IE</a> <a href=./images/print2.gif>chrome</a>）にチェック
</td></tr>
</table><!--テーブル①-->




<center>




<!--表全体start-->
<table><!--テーブル②-->
<tr><td>

<table class="table table-bordered table-sm" width="100%"><!--テーブル③-->
<tr bgcolor="lightgrey">
<td rowspan="3" width="2%"  >No</td>
<td rowspan="3" width="9%"  >物件名</td>
<td rowspan="3" width="3%"  >戸数</td>
<td rowspan="3" width="8%" class="nondisp">物件備考</td>
<td rowspan="3" width="8%">営業</td>
<!--<td rowspan="3" width="2%">工事番号</td>-->

<td colspan="__daycount__" width="70%">__tYear__　年__tMonth__　月</td>
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

<td bgcolor="__wBukkenColor__">
__Ifhonn__ <font color="__wBukkenTextColor__">__BukkenCD__</font> __Ifhonn__
__Ifkari__ __BukkenCD_kari__ __Ifkari__
</td>
<td >__BukkenName__</td>
<td >__Kosu__</td>
<td class="nondisp">__BukkenNotes__</td>
<td >__TantoName__</td>
<!--<td >__Setsumei__</td>-->

<td bgcolor="__Bcolor01__" ><font color="__BTcolor01__">__Schk01__</font></td>
<td bgcolor="__Bcolor02__" ><font color="__BTcolor02__">__Schk02__</font></td>
<td bgcolor="__Bcolor03__" ><font color="__BTcolor03__">__Schk03__</font></td>
<td bgcolor="__Bcolor04__" ><font color="__BTcolor04__">__Schk04__</font></td>
<td bgcolor="__Bcolor05__" ><font color="__BTcolor05__">__Schk05__</font></td>
<td bgcolor="__Bcolor06__" ><font color="__BTcolor06__">__Schk06__</font></td>
<td bgcolor="__Bcolor07__" ><font color="__BTcolor07__">__Schk07__</font></td>
<td bgcolor="__Bcolor08__" ><font color="__BTcolor08__">__Schk08__</font></td>
<td bgcolor="__Bcolor09__" ><font color="__BTcolor09__">__Schk09__</font></td>
<td bgcolor="__Bcolor10__" ><font color="__BTcolor10__">__Schk10__</font></td>
<td bgcolor="__Bcolor11__" ><font color="__BTcolor11__">__Schk11__</font></td>
<td bgcolor="__Bcolor12__" ><font color="__BTcolor12__">__Schk12__</font></td>
<td bgcolor="__Bcolor13__" ><font color="__BTcolor13__">__Schk13__</font></td>
<td bgcolor="__Bcolor14__" ><font color="__BTcolor14__">__Schk14__</font></td>
<td bgcolor="__Bcolor15__" ><font color="__BTcolor15__">__Schk15__</font></td>
<td bgcolor="__Bcolor16__" ><font color="__BTcolor16__">__Schk16__</font></td>
<td bgcolor="__Bcolor17__" ><font color="__BTcolor17__">__Schk17__</font></td>
<td bgcolor="__Bcolor18__" ><font color="__BTcolor18__">__Schk18__</font></td>
<td bgcolor="__Bcolor19__" ><font color="__BTcolor19__">__Schk19__</font></td>
<td bgcolor="__Bcolor20__" ><font color="__BTcolor20__">__Schk20__</font></td>
<td bgcolor="__Bcolor21__" ><font color="__BTcolor21__">__Schk21__</font></td>
<td bgcolor="__Bcolor22__" ><font color="__BTcolor22__">__Schk22__</font></td>
<td bgcolor="__Bcolor23__" ><font color="__BTcolor23__">__Schk23__</font></td>
<td bgcolor="__Bcolor24__" ><font color="__BTcolor24__">__Schk24__</font></td>
<td bgcolor="__Bcolor25__" ><font color="__BTcolor25__">__Schk25__</font></td>
<td bgcolor="__Bcolor26__" ><font color="__BTcolor26__">__Schk26__</font></td>
<td bgcolor="__Bcolor27__" ><font color="__BTcolor27__">__Schk27__</font></td>
<td bgcolor="__Bcolor28__" ><font color="__BTcolor28__">__Schk28__</font></td>
__Ifday29__	<td bgcolor="__Bcolor29__" ><font color="__BTcolor29__">__Schk29__</font></td>
__Ifday29__
__Ifday30__	<td bgcolor="__Bcolor30__" ><font color="__BTcolor30__">__Schk30__</font></td>
__Ifday30__
__Ifday31__	<td bgcolor="__Bcolor31__" ><font color="__BTcolor31__">__Schk31__</font></td>
__Ifday31__

</tr>

__BukkenLoop__

<!--工期外・臨時-->



<tr>
<td  bgcolor="lightgreen" rowspan="2" colspan="5">残工事・現調・臨時など</td>

 
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
<td  bgcolor="lightgreen"  colspan="5"></td>
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

</table><!--テーブル③-->

<br>

<hr>

</td></tr>
<!--表全体真ん中-->


<tr><td>

<br>

	<table width="100%"><!--テーブル④-->
	<tr><td valign="top" width="20%">
	■凡例：物件Noと物件名


	<table ><!--テーブル⑤-->
	<tr bgcolor="lightgrey">
	<td >No</td><td >物件名</td></tr>
	__BukkenLoop__
	<tr>
	<td bgcolor="__wBukkenColor__">
	__Ifhonn__ <font color="__wBukkenTextColor__">__BukkenCD__</font> __Ifhonn__
	__Ifkari__ __BukkenCD_kari__ __Ifkari__
	</td>
	<td >__BukkenName__</td>
	</tr>
	__BukkenLoop__
	</table><!--テーブル⑤-->





	</td>
	<td width="80%"><!--テーブル④-->

	<table class="table table-bordered table-sm" ><!--テーブル⑥-->

	<tr bgcolor="lightgrey">
	<td rowspan="3" width="5%"></td>
	<td rowspan="3" width="12%">作業員名</td>
	<td colspan="__daycount__" width="83%">__tYear__　年__tMonth__　月</td>
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


	<td bgcolor="__Scolor01__" ><font color="__STcolor01__">__SD01__</font></td>
	<td bgcolor="__Scolor02__" ><font color="__STcolor02__">__SD02__</font></td>
	<td bgcolor="__Scolor03__" ><font color="__STcolor03__">__SD03__</font></td>
	<td bgcolor="__Scolor04__" ><font color="__STcolor04__">__SD04__</font></td>
	<td bgcolor="__Scolor05__" ><font color="__STcolor05__">__SD05__</font></td>
	<td bgcolor="__Scolor06__" ><font color="__STcolor06__">__SD06__</font></td>
	<td bgcolor="__Scolor07__" ><font color="__STcolor07__">__SD07__</font></td>
	<td bgcolor="__Scolor08__" ><font color="__STcolor08__">__SD08__</font></td>
	<td bgcolor="__Scolor09__" ><font color="__STcolor09__">__SD09__</font></td>
	<td bgcolor="__Scolor10__" ><font color="__STcolor10__">__SD10__</font></td>
	<td bgcolor="__Scolor11__" ><font color="__STcolor11__">__SD11__</font></td>
	<td bgcolor="__Scolor12__" ><font color="__STcolor12__">__SD12__</font></td>
	<td bgcolor="__Scolor13__" ><font color="__STcolor13__">__SD13__</font></td>
	<td bgcolor="__Scolor14__" ><font color="__STcolor14__">__SD14__</font></td>
	<td bgcolor="__Scolor15__" ><font color="__STcolor15__">__SD15__</font></td>
	<td bgcolor="__Scolor16__" ><font color="__STcolor16__">__SD16__</font></td>
	<td bgcolor="__Scolor17__" ><font color="__STcolor17__">__SD17__</font></td>
	<td bgcolor="__Scolor18__" ><font color="__STcolor18__">__SD18__</font></td>
	<td bgcolor="__Scolor19__" ><font color="__STcolor19__">__SD19__</font></td>
	<td bgcolor="__Scolor20__" ><font color="__STcolor20__">__SD20__</font></td>
	<td bgcolor="__Scolor21__" ><font color="__STcolor21__">__SD21__</font></td>
	<td bgcolor="__Scolor22__" ><font color="__STcolor22__">__SD22__</font></td>
	<td bgcolor="__Scolor23__" ><font color="__STcolor23__">__SD23__</font></td>
	<td bgcolor="__Scolor24__" ><font color="__STcolor24__">__SD24__</font></td>
	<td bgcolor="__Scolor25__" ><font color="__STcolor25__">__SD25__</font></td>
	<td bgcolor="__Scolor26__" ><font color="__STcolor26__">__SD26__</font></td>
	<td bgcolor="__Scolor27__" ><font color="__STcolor27__">__SD27__</font></td>
	<td bgcolor="__Scolor28__" ><font color="__STcolor28__">__SD28__</font></td>
	__Ifday29__	<td bgcolor="__Scolor29__" ><font color="__STcolor29__">__SD29__</font></td>
	__Ifday29__
	__Ifday30__	<td bgcolor="__Scolor30__" ><font color="__STcolor30__">__SD30__</font></td>
	__Ifday30__
	__Ifday31__	<td bgcolor="__Scolor31__" ><font color="__STcolor31__">__SD31__</font></td>
	__Ifday31__

	</tr>

	__SagyoinLoop__




	__ZanSagyoinLoop__
	<tr>
	<td></td>
	<td>__ZanSagyoinName1__</td>


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


	</table><!--テーブル⑥-->

	</td></tr>
	</table><!--テーブル④-->

	</form>


</td></tr>
</table><!--テーブル②-->
<!--表全体end-->
</div>
</center>


<br>
</body>
</html>

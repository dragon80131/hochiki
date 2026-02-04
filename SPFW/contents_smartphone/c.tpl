<html>
<head>
<title>__ReservationName__管理</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>

__IfAjax__<!--<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>-->
__IfAjax__



<script>
var termColor="#ffffff"
var termBackColor="#999999"
</script>
<style type="text/css">
.explain{position:absolute; width=150;
background-color:#ffebcb;
font-size:8pt;color:#000000;
font-weight:normal;
padding:4;
border-left:1 solid #555555;
border-top:1 solid #555555;
border-right:1 solid #555555;
border-bottom:1 solid #555555;
visibility:"hidden";}
.term{color:#000000; cursor:hand; font-weight:700;}
</style>

</head>

<body>
<div class="content">
<h3 >未返事住戸リスト<br></h3>

__IfDemo__<table class="common-list">

</table>
__IfDemo__

__IfDefaultSet__<table class="common-list">
	<tr>
		<td>デフォルトの表示設定を変更致しました。</td>
	</tr>
</table>
__IfDefaultSet__



	


<div id="SwitchReserveInfo"></div>

計　__i__　戸

<table border=! bordercolor="#123456">
	<tr>

		<td >部屋番号</td>
		<td  >仮予定日　</td>
		<td  >予定時間帯</td>


	</tr>

__IfReserveList____ReservationLoop__	<tr class="common-list">


		<td align="center"  >__ID__</td>
		<td align="center"  >__ReserveDate__</td>
		<td align="center" >__ListTimeFrom__〜__ListTimeTo__</td>

	</tr>
__ReservationLoop____IfReserveList____IfNoReserveList__
	<tr>
		<td colspan="12"><font color="red">__ReservationName__データはありません。</font></td>
	</tr>
__IfNoReserveList__</table>

__HiddenValues__


</div>
<script type="text/javascript">
userdataDisp();
showReserveInfo(0);
</script>
</body>
</html>

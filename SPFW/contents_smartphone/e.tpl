<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
<link rel="stylesheet" href="css/a6.css" type="text/css" />

<!--
.cssTable th,  
.cssTable td {  
    border: 1px solid #CCCCCC;  
    padding: 5px 10px;  
    table-layout: fixed;  
    text-align: left;  
-->

<style>
<!-- 
@media print{ 
    .button{ 
        display: none; 
    } 
} 
--> 
</style>

</style>
</head>
<title>__MansionName__作業工程表</title>
<style type="text/css">
hr {
page-break-before: always;
}
</style>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
<table align="center">
<tr>
<td> 

<table>
<tr>
<table class="sampleTable">
<tr>
<td bgcolor="#dcf8f8">
マンション名
</td>
<td width="300">

__MansionName__
</td>

</tr>
<tr>
<td bgcolor="#dcf8f8">
注意
</td>
<td ><FONT  color="red">
__MansionMemo__ </FONT>
</td>

</tr>
</table>


<br>
<table>
<tr>
<td>
<Div Align='left'>
 AM 9:00-__AendTime__ 
 PM1 13:00-__BendTime__ 
__IfCendTime__  PM2 15:00-__CendTime__ __IfCendTime__
</td>
<td>
<form action= d2.php method=post>
__HiddenValues__
<input type=submit name=submit value='閲覧用にもどる ' class="button" >
</form>

</td>
</tr>
</table>
</Div>
<form method=POST action=f2.php>
<table class="sampleTable">
<tr  align="center" >
<td bgcolor="#eadcf8" width='40'>完了</td>
<td bgcolor="#eadcf8" width='40'>受付</td>
<td bgcolor="#eadcf8" width='40'>変更</td>
<td bgcolor="#eadcf8" width='60'>部屋</td>
<td bgcolor="#eadcf8" width='50'>工事日</td>
<td bgcolor="#eadcf8" width='30'>曜</td>
<td bgcolor="#eadcf8" width='40'>時間</td>
__IfMenu__<td bgcolor="#eadcf8" >__MenuList__ __IfMenu__
<td bgcolor="#eadcf8" width='600' valign='top' >備考</td>

</tr>
__RecoLoop__



__IfNotSameDate__

</table>
<br>
<hr>
<table class="sampleTable" >
<tr  align="center" >
<td bgcolor="#eadcf8" width='40'>完了</td>
<td bgcolor="#eadcf8" width='40'>受付</td>
<td bgcolor="#eadcf8" width='40'>変更</td>
<td bgcolor="#eadcf8" width='60'>部屋</td>
<td bgcolor="#eadcf8" width='50'>工事日</td>
<td bgcolor="#eadcf8" width='30'>曜</td>
<td bgcolor="#eadcf8" width='40'>時間</td>
__IfMenu1__<td bgcolor="#eadcf8" >__MenuList__ __IfMenu1__
<td bgcolor="#eadcf8" width='600' valign='top' >備考</td>

</tr>
__IfNotSameDate__




__IfMikan__

__IfTCheck__
<tr bgcolor="#dce0f5"><td colspan=__colspan__></td></tr>
__IfTCheck__


__IfToday__
<tr align="center" bgcolor=#FFF0F5  height="35">
<td>未</td>
__IfNonMIHENJICOLOR2__<td >__uUpdated__</td>__IfNonMIHENJICOLOR2__
__IfMIHENJICOLOR2__<td ><font color=red>__uUpdated__</font></td>__IfMIHENJICOLOR2__
__IfToday__

__IfNotToday__
<tr align="center" height="35" >
<td>未</td>

__IfNonMIHENJICOLOR__<td >__uUpdated__</td>__IfNonMIHENJICOLOR__
__IfMIHENJICOLOR__<td  bgcolor="#ff9999" ><FONT  color="red">__uUpdated__</FONT></td>__IfMIHENJICOLOR__

__IfNotToday__



__IfMikan__

__Ifkan__
<tr align="center" style=color:#808080 bgcolor=#c0c0c0 height="35">
<td>完</td>


<td >__uUpdated__ </td>


__Ifkan__
<td>__rUpdated__</td>
<td>__ID__</td>
<td>__timeFromDate__</td>
<td>__weekjp__</td>
<td>__timeFromTime__</td>
__IfMenu2__ <td>__menuCD__ __IfMenu2__
<td valign='top'><Div Align="left">
<font size="-1">
__wMemo__
</font>
<font color="blue" size="-1">
__wMemo2__
</font>
</Div>
</td>

</tr>
__RecoLoop__

</table>





<br>
__HiddenValues__
</form>
</td>
</tr>
</table>
<br>
</body>
</head>
</html>

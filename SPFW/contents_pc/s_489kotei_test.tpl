<html>
<head>
<link rel="stylesheet" href="css/a7.css" type="text/css" />
</head>
<title>作業工程表</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">



<table><tr><td>


<table class="sampleTable">
<tr><td bgcolor="#dcf8f8">マンション名</td>
	<td  width="300">__MansionName__</td>
</tr>
<tr><td bgcolor="#dcf8f8">未施工の機器保管場所</td>
	<td>__MansionMemo__ </td>

</tr>
</table>
<br>

<br>


工事写真や複雑な物件を確認する場合は、作業工程表<a href="https://www2.489501.jp/__BukkenCD489__/">こちら</a>をご覧ください。
<table><Div Align='left'>
<tr>
<td>

 AM 9:00-__AendTime__ 
 PM1 13:00-__BendTime__ 
__IfCendTime__  PM2 15:00-__CendTime__ __IfCendTime__
</td>

</tr>
</table>
</Div>

<form action= c.php method=post>
__HiddenValues__
<input type=submit name=submit value='未返事住戸 '>
</form>

<table table-layout: fixed class="sampleTable">
<tr  align="center" ><td colspan=__colspan__ bgcolor="#dcf8f8" >共有部工事日 __KyoyuStartDate__ 〜 __KyoyuEndDate__</td></tr>
<tr  align="center" bgcolor="#dce0f5">
<td>完了</td><td >受付</td>
<td>変更</td><td>部屋</td>


<td>工事日</td><td>曜</td><td>時間</td>
__MenuList__
__IfKey__
__KeyName__
__IfKey__

<td>備考</td>

</tr>


__RecoLoop__

__IfNotSameDate__
  <tr ><td colspan=3></td>
  <td colspan=__colspan2__>
  
  </td></tr>

  <tr  align="center" bgcolor="#dce0f5" >
  <td >完了</td><td>受付</td>
  <td >変更</td><td>部屋</td>



  <td >工事日</td><td >曜</td><td >時間</td>
__MenuList__
__IfKey__
__KeyName__
__IfKey__

<td>備考</td>

</tr>

__IfNotSameDate__


__IfMikan__


     __IfTCheck__
   	<tr bgcolor="#dce0f5"><td colspan=__colspan__></td></tr>
     __IfTCheck__

__IfToday__
   <tr align="center" bgcolor="#FFF0F5" ><td><div id="TodayLink">★</div></td>
     __IfNonMIHENJICOLOR2__<td > __uUpdated__ </td>__IfNonMIHENJICOLOR2__
     __IfMIHENJICOLOR2__<td ><FONT  color="red"> __uUpdated__</FONT></td>__IfMIHENJICOLOR2__

__IfToday__

__IfNotToday__

   <tr align="center" ><td></td>
     __IfNonMIHENJICOLOR__<td > __uUpdated__ </td>__IfNonMIHENJICOLOR__
     __IfMIHENJICOLOR__<td bgcolor="#ff9999" ><FONT  color="red"> __uUpdated__</FONT></td>__IfMIHENJICOLOR__

__IfNotToday__





__IfMikan__

__Ifkan__
<tr align="center" style=color:#808080 bgcolor=#c0c0c0 ><td>完</td>
<td >  __uUpdated__ </td>
__Ifkan__



<td>__rUpdated__</td>
<td>__ID__</td>

<td>__timeFromDate__</td>
<td>__weekjp__</td>
<td>__timeFromTime__</td>
__menuCD__
__IfKey__
__KeyMenuCD__
__IfKey__

<td><Div Align='left'>__wMemo__ <font color="RED">__wR006__</font><font color="blue">__wR001__</font></Div></td>





</tr>

__RecoLoop__

</table>






<hr size="__HRSize__" color="__HRColor__">
<a href="javascript:history.back();" >メニュー</a>
<br><br>
<a href="s_search.php__QUERY__">トップ</a>

<hr size="__HRSize__" color="__HRColor__">

__SFooter__

__SCopyright__

</td></tr></table>


<br>
</body>
</head>
</html>

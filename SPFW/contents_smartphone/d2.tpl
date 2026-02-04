<html>
<head>
<link rel="stylesheet" href="css/a6.css" type="text/css" />
</head>
<title>作業工程表</title>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">



<table ><tr><td>


<table class="sampleTable">
<tr>
<td bgcolor="#dcf8f8">
マンション名
</td>
<td width="300">

__MansionName__
</td>




<td rowspan="2">





<form method=POST action=a3.php>
__HiddenValues__
<input type="submit"  value="注意編集">
</form>


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


<table><Div Align='left'>
<tr>
<td>
<form action= e.php method=post>
__HiddenValues__
<input type=button value='本日ジャンプ' onclick=location.href="#TodayLink" >
</form>

</td>

<td>
<form action= e.php method=post>
__HiddenValues__
<input type=submit name=submit value='日別プリントアウト '>
</form>

</td>
<td>
<form action= c.php method=post>
__HiddenValues__
<input type=submit name=submit value='未返事住戸 '>
</form>

</td>

<td>
<form action= d2keiji.php method=post>
__HiddenValues__
<input type=submit name=submit value='掲示用 '>
</form>

</td>
__IfKowa3__
<td>
<form action= d2kowa.php method=post>
__HiddenValues__
<input type=submit name=submit value='掲示用(幸和様専用） '>
</form>

</td>
__IfKowa3__

__IfSth3__
<td>
<form action= d2sth.php method=post>
__HiddenValues__
<input type=submit name=submit value='対応履歴（STH様専用） '>
</form>

</td>
__IfSth3__


<td>
<form action= d2arpha.php method=post>
__HiddenValues__
<input type=submit name=submit value='横向き '>
</form>

</td>


<td>
<form action= "p2.php" method=post>
__HiddenValues__
<input type=submit name=submit value='現場写真 '>
</form>

</td>


</tr>
</Div></table>





<form method=POST action=a2.php>
__HiddenValues__
<table table-layout: fixed class="sampleTable">
<tr  align="center" ><td colspan=__colspan__ bgcolor="#dcf8f8" >共有部工事日 __KyoyuStartDate__ 〜 __KyoyuEndDate__</td></tr>
<tr  align="center" >
<td bgcolor="#dce0f5">完了</td><td bgcolor="#dce0f5">受付</td>
<td bgcolor="#dce0f5">変更</td><td bgcolor="#dce0f5">部屋</td>


<td bgcolor="#dce0f5">工事日</td><td bgcolor="#dce0f5">曜</td><td bgcolor="#dce0f5">時間</td>
<td bgcolor="#dce0f5">__MenuList__備考</td>
__IfKowa__
<td bgcolor="#dce0f5">名前</td><td bgcolor="#dce0f5">連絡先</td>
__IfKowa__
<td bgcolor="#dce0f5">編集</td>
<td bgcolor="#dce0f5">解除</td></tr>


__RecoLoop__

__IfNotSameDate__
  <tr ><td colspan=3><input type="submit" value="送信" ></td>
  <td colspan=__colspan2__>
  <input type=button value=更新 onclick=location.reload()>
  </td></tr>

  <tr  align="center" >
  <td  bgcolor="#dce0f5">完了</td><td bgcolor="#dce0f5">受付</td>
  <td bgcolor="#dce0f5">変更</td><td bgcolor="#dce0f5">部屋</td>



  <td bgcolor="#dce0f5">工事日</td><td bgcolor="#dce0f5">曜</td><td bgcolor="#dce0f5">時間</td>
  <td bgcolor="#dce0f5">__MenuList__備考</td>
__IfKowa__
<td bgcolor="#dce0f5">名前</td><td bgcolor="#dce0f5">連絡先</td>
__IfKowa__
  <td bgcolor="#dce0f5">編集</td>
  <td bgcolor="#dce0f5">解除</td></tr>

__IfNotSameDate__


__IfMikan__


     __IfTCheck__
   	<tr bgcolor="#dce0f5"><td colspan=__colspan__></td></tr>
     __IfTCheck__

__IfToday__
   <tr align="center" bgcolor="#FFF0F5" ><td><input type=checkbox name=Notes[] value="__uCD__"<div id="TodayLink">★</div></td>
     __IfNonMIHENJICOLOR2__<td > __uUpdated__ </td>__IfNonMIHENJICOLOR2__
     __IfMIHENJICOLOR2__<td ><FONT  color="red"> __uUpdated__</FONT></td>__IfMIHENJICOLOR2__

__IfToday__

__IfNotToday__

   <tr align="center" ><td><input type=checkbox name=Notes[] value="__uCD__"</td>
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
<td>__menuCD__
<Div Align='left'>__wMemo__ <font color="blue">__wMemo2__</font></Div></td>

__IfKowa2__
<td>__Lastname__</td><td>__TEL__</td>
__IfKowa2__


<td>
__IfKANSEI__ - __IfKANSEI__

__IfMIKANSEI__ <input type=radio name="uCD3" value="__uCD__">  __IfMIKANSEI__
</td>

<td>

__IfKANSEI2__  <input type=checkbox name=Notes2[] value="__uCD__"> __IfKANSEI2__

__IfMIKANSEI2__ - __IfMIKANSEI2__

</td>
</tr>

__RecoLoop__

</table>



<input type="submit" value="送信" >

</form>

<br><br>
<a href="logout.php__QUERY__">■ログアウト</a><br>



</td></tr></table>


<br>
</body>
</head>
</html>

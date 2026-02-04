<html>
<head>
<link rel="stylesheet" type="text/css" href="print.css" media="print" />
</head>
<body>
<table>
<tr>
<td>インターホン改修工事　工程表　（__today__時点）</td>
</tr>
<tr><td>
<form action= d2.php method=post>
__HiddenValues__
<input type=submit name=submit value='閲覧用にもどる '>
</form>
</td></tr>
</table>


<table>
<tr>

<td valign="top">
 <table border=1>

    <table border=1>
      <tr bgcolor="#87ceeb"><td align="center" width=40 colspan=2 > __FirsttimeFromDate__</td><td align="center"  width=160>(__Firstweekjp__)</td></tr>
      <tr bgcolor="#40e0d0"><td align="center" >部屋番号</td><td align="center" >時間</td><td>備考</td><tr>

__RecoLoop__


__IfNotSameDate__


    </table>
  </td>
  <td><img src=./images/white.gif width=10></td>
 <td valign="top">
    <table border=1>
      <tr bgcolor="#87ceeb"><td align="center" width=40 colspan=2>__timeFromDate__</td><td align="center" width=160>(__weekjp__)</td></tr>
      <tr bgcolor="#40e0d0"><td align="center" >部屋番号</td><td align="center">時間</td><td>備考</td><tr>
__IfNotSameDate__

       <tr><td align="center" >__ID__</td><td align="center" >__timeFromTime__</td><td>__wMemo2__</td></tr>




__RecoLoop__



    </table>


</td>

</tr>
</table>




</body>
</html>





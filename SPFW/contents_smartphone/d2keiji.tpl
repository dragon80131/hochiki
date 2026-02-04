<html>
<head>
<link rel="stylesheet" href="css/a6.css" type="text/css" />
<style>
<!-- 
@media print{ 
    .button{ 
        display: none; 
    } 
} 
--> 
</style>

</head>
<body>
<table>
<tr>
<td>__MansionName__ インターホン改修工事　工程表　（__today__時点）</td>
</tr>
<tr><td>
<form action= d2.php method=post>
__HiddenValues__
<input type=submit name=submit value='閲覧用にもどる '  class="button" >
</form>
</td></tr>
</table>


<table >
<tr>

<td valign="top">
 <table class="sampleTable">

    <table class="sampleTable">
      <tr bgcolor="#87ceeb"><td align="center" > __FirsttimeFromDate__</td><td align="center">(__Firstweekjp__)</td></tr>
      <tr bgcolor="#40e0d0"><td align="center" >部屋番号</td><td align="center" >時間</td><tr>

__RecoLoop__


__IfNotSameDate__


    </table>
  </td>
  <td><img src=./images/white.gif></td>
 <td valign="top">
    <table class="sampleTable">
      <tr bgcolor="#87ceeb"><td align="center" >__timeFromDate__</td><td align="center">(__weekjp__)</td></tr>
      <tr bgcolor="#40e0d0"><td align="center" >部屋番号</td><td align="center">時間</td><tr>
__IfNotSameDate__

       <tr><td align="center" >__ID__</td><td align="center" >__timeFromTime__</td></tr>




__RecoLoop__



    </table>

<!-- 未返事 -->
<td><img src=./images/white.gif> </td>
<td valign="top">
<table class="sampleTable" >
<tr  bgcolor="#ffff00">	<td>未返事</td></tr>
<tr  bgcolor="#daa520"><td>部屋番号</td><tr>

__MihenjiLoop__
<tr><td align="center" >__MihenjiID__</td><tr>

__MihenjiLoop__

</table>


<!-- 未返事おわり -->
</td>

</tr>
</table>




</body>
</html>





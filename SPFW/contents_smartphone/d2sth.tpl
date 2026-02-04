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

</tr>

</table>


<br>




</Div>


<table><Div Align='left'>
<tr>

<td>
<form action= d2.php method=post>
__HiddenValues__
<input type=submit name=submit value='もどる '>
</form>

</td>



</tr>
</Div></table>





<form method=POST action=a2.php>
__HiddenValues__
<table table-layout: fixed class="sampleTable">
<tr  align="center" >
<td bgcolor="#dce0f5">対応日</td><td bgcolor="#dce0f5">部屋</td><td bgcolor="#dce0f5">名前</td>
<td bgcolor="#dce0f5">問合せ区分</td>
<td bgcolor="#dce0f5">工事日</td><td bgcolor="#dce0f5">曜</td><td bgcolor="#dce0f5">時間</td>
<td bgcolor="#dce0f5">__MenuList__備考</td>
<td bgcolor="#dce0f5">受付区分</td>
</tr>


__RecoLoop__






     __IfTCheck__
   	<tr bgcolor="#dce0f5"><td colspan=__colspan__></td></tr>
     __IfTCheck__




   <tr align="center" >





<td>__uUpdated__</td>
<td>__ID__</td>
<td>__Lastname__</td>
<td><Div Align='left'>__ToiawaseKubun__</Div></td>
<td>__timeFromDate__</td>
<td>__weekjp__</td>
<td>__timeFromTime__</td>
<td>__menuCD__
<Div Align='left'>__wMemo__ <font color="blue">__wMemo2__</font></Div></td>
<td>__UketukeKubun__</td>


</tr>

__RecoLoop__

</table>






<br><br>
<a href="logout.php__QUERY__">■ログアウト</a><br>



</td></tr></table>


<br>
</body>
</head>
</html>

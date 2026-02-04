<html>
<head>
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>
<title>リニューアル支援</title>
</head>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《端末情報登録》</center>
<hr size="__HRSize__" color="__HRColor__">


<form action="s_accessdevice_list.php" method="POST" name="mainform">
<input type="hidden" name="rKey" value="__rKey__">

<a href="#" onclick="javascript:move('s_accessdevice_list.php?rKey=__rKey__')">＜＜＜　登録済端末一覧</a> <br><br>

<table border=1>
<tr><td style="color:#ffffff" bgcolor="#4169E1">CD</td>
	<td>__wAccessDeviceIDCD__　(システムが決定しているので変更できません)</td></tr>
<tr><td style="color:#ffffff" bgcolor="#4169E1"><b>端末ID(monaca DeviceID)</b></td>
	<td><input type=text name="wDeviceID" value="__wDeviceID__" size="50">　<font color="red">※必須</font></td></tr>
<tr><td style="color:#ffffff" bgcolor="#4169E1"><b>氏名・ログインユーザID</b><br>
												<font size="2">※ネスペ社内用の場合は「ネスペ社内用」と記載</font></td>
	<td><input type=text name="wLastName" value="__wLastName__">　<font color="red">※必須</font></td></tr>
<tr><td style="color:#ffffff" bgcolor="#4169E1"><b>ユーザ情報と紐づけ</b><br>
												<font size="2">※複数人で端末利用する場合は選ばなくてよい<br>　備考に詳細を記載すること　例） ○○支店</font></td>
	<td>
		<select name="wUserCD" >
		<option value="0"> - </option>
		__UserListLoop__
		<option value="__tmpUserCD__" __tmpUserSelected__ >__tmpSitenName__　__tmpEigyoshoName__　__tmpLastName__（UserCD:__tmpUserCD__）</option>
		__UserListLoop__
		</select>
</td></tr>
<tr><td style="color:#ffffff" bgcolor="#4169E1"><b>Android or iPad? mini? Air?</b></td>
	<td><input type=text name="wShortName" value="__wShortName__"><font color="red"></font></td></tr>
<tr><td style="color:#ffffff" bgcolor="#4169E1"><b>備考</b><br>
												<font size="2">※ネスペからのレンタル端末・<br>　ネスペ社内用の場合、端末番号記載</font></td>
	<td><input type=text name="wNotes" value="__wNotes__" size="50"><font color="red"></font></td></tr>
<tr><td style="color:#ffffff" bgcolor="#4169E1"><b>アプリバージョン（AppVer）</b></td>
	<td><input type=text name="wAppVer" value="__wAppVer__"><font color="red"></font></td></tr>
</table>


<br>
<input type="hidden" name="work" value="">
<input type="hidden" name="editAccessDeviceIDCD" value="__wAccessDeviceIDCD__">
<input type="button" value="登　録"onclick="javascript:moveWithWork('s_accessdevice_list.php?rKey=__rKey__', 1 )">

</form>
<br>



<hr size="__HRSize__" color="__HRColor__">
<a href="s_search.php__QUERY__">トップ</a><br>
<hr size="__HRSize__" color="__HRColor__">
__SFooter__
__SCopyright__
</body>
</html>

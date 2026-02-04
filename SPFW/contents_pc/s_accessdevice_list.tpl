<html>
<head>

<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a5.css">
<script type="text/javascript" src="tools.js"></script>
<title>リニューアル支援</title>
</head>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《登録済端末一覧》</center>
<hr size="__HRSize__" color="__HRColor__">
<a href="s_search.php__QUERY__">＜＜トップ</a>
　　　<b>ネスペのみ作業可能です。</b>

<br><br>
<a href="s_accessdevice_text.php?rKey=__rKey__" target="_blank">＞＞＞　ログ確認（別タブで開きます）</a><br>
<br>
<a href="#" onclick="javascript:moveWithAccessDeviceIDCD('s_accessdevice_detail.php?rKey=__rKey__', '-1')">＞＞＞　新規登録</a><br>
　　　※アプリ再インストールした場合は、（おそらく）端末IDが変わります。<br>
　　　　一度登録済の端末の場合は、新規登録ではなく編集してください。<br>
　　　　（その際、間違って違う情報に上書きしないでください！他の端末がログインできなくなります！！）<br>
　　　　分からない場合は新規登録し、古い情報の備考に記載してください。（例）6/29 ○○様（No.xx）の端末でアプリ再インストールしたため、この端末IDは現在未使用。等


<form action="s_accessdevice_detail.php" name="mainform" method="POST">
<input type="hidden" name="rKey" value="__rKey__" >
<input type="hidden" name="editAccessDeviceIDCD" value="" >


<table>
<tr style="color:#ffffff" bgcolor="#4169E1">
	<td>CD</td>
	<td>端末ID(monaca)</td>
	<td>LastName</td>
	<td>UserCD</td>
	<td>iPad or Android?</td>
	<td>備考</td>
	<td>Appバージョン</td>
	<td>登録日</td>
	<td>編集</td>
</tr>
__DeviceLoop__
<tr bgcolor="__bc__">
	<td>__AccessDeviceIDCD__</td>
	<td>__DeviceID__</td>
	<td>__LastName__</td>
	<td>__UserCD__ __UserName__</td>
	<td>__ShortName__</td>
	<td>__Notes__</td>
	<td>__AppVer__</td>
	<td>__Created__</td>
	<td><input type="button" value="編集" class="button" onclick="javascript:moveWithAccessDeviceIDCD('s_accessdevice_detail.php?rKey=__rKey__', '__AccessDeviceIDCD__')"></td>
</tr>
__DeviceLoop__
</table>

<br><br>
</form>




<hr size="__HRSize__" color="__HRColor__">
<a href="s_search.php__QUERY__">＜＜トップ</a><br>
<hr size="__HRSize__" color="__HRColor__">
__SFooter__
__SCopyright__
</body>
</html>

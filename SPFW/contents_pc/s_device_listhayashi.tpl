<html>
<head>

	<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a5.css">
	<script type="text/javascript" src="tools.js"></script>
</head>
<title>営業活動支援システム</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
	__SHeader__
	<hr size="__HRSize__" color="__HRColor__">
	<center>《機器・OP名称管理》</center>
	<hr size="__HRSize__" color="__HRColor__">



	__IfErrorDeviceName__
	必須項目を入力してください<br>
	<input type="button" value="もどる" class="button" onclick="javascript:history.back()">
	__IfErrorDeviceName__




	<div>
		<a href="#" onclick="javascript:move('s_device_detailhayashi.php?rKey=__rKey__')">＞＞＞　新規登録</a> <br>
		<a href="#" onclick="javascript:move('s_search.php?rKey=__rKey__')">＞＞＞　トップ</a><br>
		<a href="#" onclick="javascript:move('s_device_listhayashi.php?rKey=__rKey__')">＞＞＞　最新の情報に更新</a><br>
		種類順、型番順にならんでいます。<br>


	</div>


	<form action="s_device_listhayashi.php" name="mainform" method="POST">
		<input type="hidden" name="editDeviceCD" value="">
		<input type=hidden name="rKey" value="__rKey__">
		<input type="hidden" name="work" value="">

		<table class="sampleTable">
			<tr style="color:#ffffff" bgcolor="#4169E1">

				<td>種類</td>
				<td>基本システム</td>
				<td>型番</td>
				<td>機器名</td>
				<td>略称</td>

				<td>仕様書URL</td>
				<td>マニュアルURL</td>
				<td>詳細/編集</td>
				<td>削除</td>
			</tr>

			__DeviceListLoop__
			<tr>

				<td>__Category__</td>
				<td>__BasicSystem__</td>
				<td>__Kataban__</td>
				<td>__DeviceName__</td>
				<td>__ShortName__</td>

				<td>__ShiyoshoURL__</td>
				<td>__ManualURL__</td>
				<td><input type="button" value="編集" class="button" onclick="javascript:moveWithDeviceCD( 's_device_detailhayashi.php?rKey=__rKey__', __DeviceCD__  )"></td>
				<td><input type="button" value="削除" class="button" onclick="javascript:moveWithKeyAndWorkDevice( 's_device_listhayashi.php?rKey=__rKey__', __DeviceCD__ , 2 )"></td>
			</tr>
			__DeviceListLoop__

		</table>
	</form>




	<hr size="__HRSize__" color="__HRColor__">
	<a href="s_search.php__QUERY__">トップ</a><br>
	<hr size="__HRSize__" color="__HRColor__">
	<br>
	__SFooter__
</body>
</html>

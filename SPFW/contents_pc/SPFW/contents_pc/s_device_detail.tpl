<html>
<head>
	<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a5.css">
	<script type="text/javascript" src="tools.js"></script>
	<!-- jQuery読み込み
	<script src="./include/js/jquery-3.2.1.min.js"></script> -->
	<script>
		function SelectCategory() {
			var BasicSystem = document.getElementById("BasicSystem").value; //BasicSystemというIdが付与された要素を取得してBasicSystemに代入
			if (wCategory == "1" || wCategory == "2" || wCategory == "7") {
				//機器種類が居室親機か玄関子機（通話）か増設親機であった場合は基本システムタグを表示
				document.getElementById('BasicSystem').style.display = "";
			} else {
				//そうでない場合は非表示
				document.getElementById('BasicSystem').style.display = "none";
			}
		}
	</script>
</head>




<title>営業活動支援システム</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
	__SHeader__
	<hr size="__HRSize__" color="__HRColor__">
	<center>《機器情報入力フォーム》</center>
	<hr size="__HRSize__" color="__HRColor__">
	<br>


	<a href="#" onclick="javascript:move('s_device_list.php')">＜＜＜　機器一覧</a><br>
	__wTitle__<br>


	<!-- __IfErrorBasicSystem__<font color=red>基本システムを選択してください</font>__IfErrorBasicSystem__ -->
	__IfErrorDeviceName__<font color=red>機器名を入力してください</font>__IfErrorDeviceName__
	__IfErrorShortName__<font color=red>略称を入力してください </font>__IfErrorShortName__
	__IfErrorKataban__<font color=red>型番がダブっています。</font>__IfErrorKataban__

	<form action="s_device_list.php" method="POST" name="mainform">

		<table border=1>
			<tr>
				<td bgcolor="lightpink">機器CD</td>
				<td>__editDeviceCD__ (システムが決定しているので変更できません)</td>
			</tr>
			<tr>
				<td bgcolor="lightpink">機器種類</td>
				<td>
					機器種類のセレクトに追加
					<select name="wCategory" id="wCategory" onchange="SelectCategory()">
						<option value="">-<br>
							__Choice5Loop__
						<option value="__Choice5Value__" __Choice5Selected__>__Choice5Name__<br>
							__Choice5Loop__
					</select>
				</td>
			</tr>
			<tr id="BasicSystem">
				<td bgcolor="lightpink">基本システム</td>
				<td>
					<select name="wRNsystem">
						__RNsystemLoop__
						<option value="__RNSYSTEM_Value__">__RNSYSTEMNAME__</option>
						__RNsystemLoop__
					</select>
				</td>
			</tr>
			<tr>
				<td bgcolor="lightpink">機器名</td>
				<td><input type=text name="wDeviceName" size='35' value="__wDeviceName__">
					<font color="red">※必須</font>
				</td>
			</tr>

			<tr>
				<td bgcolor="lightpink">略称</td>
				<td><input type=text name="wShortName" value="__wShortName__">
					<font color="red">※必須</font>
				</td>
			</tr>
			<tr>
				<td bgcolor="lightpink">型番</td>
				<td><input type=text name="wKataban" value="__wKataban__"></td>
			</tr>
			<tr>
				<td bgcolor="lightpink">仕様書URL</td>
				<td><input type=text size='50' name="wShiyoshoURL" value="__wShiyoshoURL__"></td>
			</tr>
			<tr>
				<td bgcolor="lightpink">マニュアルURL</td>
				<td><input type=text size='50' name="wManualURL" value="__wManualURL__"></td>
			</tr>
		</table>

		<br>
		<input type=hidden name="work" value="">
		<input type=hidden name="editDeviceCD" value="__editDeviceCD__">
		<input type=hidden name="rKey" value="__rKey__">
		<input type="button" value="登　録" onclick="javascript:moveWithWork('s_device_list.php', 1 )">
	</form>


	<hr size="__HRSize__" color="__HRColor__">
	<hr size="__HRSize__" color="__HRColor__">
	<br>
	__SFooter__
</body>
</html>

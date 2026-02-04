<html>
<head>
	<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a5.css">
	<script type="text/javascript" src="tools.js"></script>
	<!-- jQuery読み込み
	<script src="./include/js/jquery-3.2.1.min.js"></script> -->
	<script>
		window.onload = function () {
			SelectCategory();
		}

		function SelectCategory() {
			var wCategory = document.getElementById("wCategory").value; //wCategoryというIdが付与された要素を取得してwCategoryに代入
			if (wCategory === "1" || wCategory === "2" || wCategory === "3" ||
				wCategory === "7") {
				//機器種類が居室親機か玄関子機（通話）か増設親機、もしくはカメラ付き玄関子機であった場合は基本システムタグを表示
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
	<div class="container">
		<div class="container main">
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
				<table border=1 class="main">
					<tr>
						<td bgcolor="lightpink">機器CD</td>
						<td>__editDeviceCD__ (システムが決定しているので変更できません)</td>
					</tr>
					<tr>
						<td bgcolor="lightpink">機器種類</td>
						<td>
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
							<input type="checkbox" name="wRNsystem[]" value="1" __BasicSystem1Checked__>らくタッチPlus　
							<input type="checkbox" name="wRNsystem[]" value="2" __BasicSystem2Checked__>らくタッチ　
							<input type="checkbox" name="wRNsystem[]" value="3" __BasicSystem3Checked__>WISM7α　<br>
							<input type="checkbox" name="wRNsystem[]" value="4" __BasicSystem4Checked__>VIXUS1Pr　
							<input type="checkbox" name="wRNsystem[]" value="5" __BasicSystem5Checked__>PATMO　
							<input type="checkbox" name="wRNsystem[]" value="6" __BasicSystem6Checked__>WISMGP　<br>
							<input type="checkbox" name="wRNsystem[]" value="7" __BasicSystem7Checked__>PATMOα　
							<input type="checkbox" name="wRNsystem[]" value="8" __BasicSystem8Checked__>dearisシリーズ　
							<!-- <select name="wRNsystem[]">
						<option value="__wRNSYSTEM_Value1__">__RNSYSTEMNAME__</option>
					</select>
					<select name="wRNsystem[]">
						<option value="__wRNSYSTEM_Value2__">__RNSYSTEMNAME__</option>
					</select> -->
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
		</div>
</body>
</html>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>リニューアル支援</title>

	<!-- BootstrapのCSS読み込み -->
	<link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<!-- jQuery読み込み -->
	<script src="./include/js/jquery-3.2.1.min.js"></script>

	<!-- BootstrapのJS読み込み -->
	<script src="./include/bootstrap/js/bootstrap.min.js"></script>

	<link rel="stylesheet" type="text/css" href="./css/rnsien.css">
	<script type="text/javascript" src="./tools.js"></script>

	<link href="d2b/css/dropzone.css" type="text/css" rel="stylesheet" />
	<script src="d2b/dropzone.min.js"></script>

	<link href="css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
	<script src="js/jquery-1.4.2.js" type="text/javascript"></script>
	<script src="js/jquery.ui.core.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/tools_ajax.js"></script>
	<script type="text/javascript" src="js/ConnectedSelect.js"></script>

</head>

<body>
	__SHeader__

	<div class="content-all">
		<!--content-all-->

		<div class="left-yose">
			<a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜メニュー</a>
		</div>


		<div class="top-menu left-yose">
			<h5>__wBukkenName__</h5>
			<h6 style="width: 1000px;">現場写真</h6>

			<!--
			<a href="s_pic_select.php__QUERY__&editBukkenCD=__editBukkenCD__ ">＞＞写真一括ダウンロード</a>
			-->

			__IfError__
			__ErrorLoop__
			<font color="red">__ErrorString__<br></font>
			__ErrorLoop__
			__IfError__


			<table class="table table-bordered table-sm" style="width: 1000px;">
				<tr>
					<td bgcolor="green"></td>
				</tr>
				<tr>
					<td><B>◆現調シート</B>　
						<a href=./upfile/imgup.php?SekoStatus=2&Device=11&rKey=__rKey__&BukkenCD=__editBukkenCD__>>>>写真/PDFファイル追加</a>　<b>png,jpg,gif,pdfのみ。</b>
					</td>
				</tr>
				<tr>
					<td>
						<br>
						<table class="table table-borderless" style="width:900px;">
							<tr>__PicGenchoLoop____PicGencho____PicGenchoLoop__</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td bgcolor="green"></td>
				</tr>
				<tr>
					<td bgcolor="#F3FFD8">
						<font size="4"><B>◆現場写真</B></font>　
						<!--		<a href=./upfile/imgup.php?SekoStatus=2&Device=9&rKey=__rKey__&BukkenCD=__editBukkenCD__>>>>写真追加</a><br>-->
						<table class="table table-borderless" style="background-color:#F3FFD8">
							<tr>
								<td style="width:450px;">
									▼ドラッグアンドドロップで写真アップロードが可能です。<br>
									　1度に10枚まで。png,jpg,gifのみ。<br>
									　登録後は画面更新　
									<a href="s_pic.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">画面更新</a><br>
									<table class="table table-borderless" style="width:450px; background-color:#FFFF77">
										<tr>
											<td>
												<form action="d2b/upload_sf_pics.php?SekoStatus=2&Device=9&BukkenCD=__editBukkenCD__" class="dropzone"></form>
											</td>
										</tr>
									</table>
								</td>
								<td>
									<table>
										<tr>__PicKyoyoLoop____PicKyoyo____PicKyoyoLoop__</tr>
									</table>
						</table>
					</td>
				</tr>

				<tr>
					<td bgcolor="green"></td>
				</tr>
				<tr>
					<td bgcolor="#FFDBC9">
						<font size="4"><B>◆図面</B></font>　

						<table class="table table-borderless" style="background-color:#FFDBC9">
							<tr>
								<td style="width:450px;">
									<a href="s_pic.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">画面更新</a><br>
									<table class="table table-borderless" style="width:450px; background-color:#FFAD90">
										<tr>
											<td>
												<form action="d2b/upload_sf_pics.php?SekoStatus=2&Device=8&BukkenCD=__editBukkenCD__" class="dropzone"></form>
											</td>
										</tr>
									</table>
								</td>
								<td>
									<table>
										<tr>__PicZumenLoop____PicZumen____PicZumenLoop__</tr>
									</table>
						</table>
					</td>
				</tr>

				<tr>
					<td bgcolor="green"></td>
				</tr>
				<tr>
					<td bgcolor="#D9E5FF">
						<font size="4"><B>◆部屋番号</B></font>　

						<table class="table table-borderless" style="background-color:#D9E5FF">
							<tr>
								<td style="width:450px;">
									<a href="s_pic.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">画面更新</a><br>
									<table class="table table-borderless" style="width:450px; background-color:#8EB8FF">
										<tr>
											<td>
												<form action="d2b/upload_sf_pics.php?SekoStatus=2&Device=13&BukkenCD=__editBukkenCD__" class="dropzone"></form>
											</td>
										</tr>
									</table>
								</td>
								<td>
									<table>
										<tr>__PicHeyaLoop____PicHeya____PicHeyaLoop__</tr>
									</table>
						</table>
					</td>
				</tr>

			</table>


			<br>
			<a href="s_picdel_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">>>>写真削除はこちらから</a><br>
			<br>

			<a href="s_pic_DL.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">
				写真ダウンロード
			</a><br><br>
		</div>
		<!--content-all-->



		__SFooter__
		__SCopyright__

</body>
</html>

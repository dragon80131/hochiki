<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>リニューアル支援</title>z

	<!-- BootstrapのCSS読み込み -->
	<link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<!-- jQuery読み込み -->
	<script src="./include/js/jquery-3.2.1.min.js"></script>

	<!-- BootstrapのJS読み込み -->
	<script src="./include/bootstrap/js/bootstrap.min.js"></script>

	<link rel="stylesheet" type="text/css" href="./css/rnsien.css">
	<script type="text/javascript" src="tools.js"></script>

	<!--datepicker-->
	<link href="css/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
	<link href="css/jquery-ui-1.12.1/jquery-ui.structure.min.css" rel="stylesheet">
	<link href="css/jquery-ui-1.12.1/jquery-ui.theme.min.css" rel="stylesheet">
	<script src="js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script src="js/jquery-ui/datepicker-ja.js"></script>

	<script type="text/javascript" src="js/tools_ajax.js"></script>
	<script type="text/javascript" src="js/ConnectedSelect.js"></script>
</head>

<body>
	__SHeader__

	<div class="content-all">
		<!--content-all-->

		<div class="top-menu left-yose">
			<h5>__wBukkenName__</h5>
			<h6>予約センター受付依頼　完了</h6>

			<font size="2" color="gray">ログインユーザ：__wLastName__( ID: __ID__ )</font>

			<br><br>

			__IfOK__
			予約センターへ依頼が完了しました。<br>
			__IfMailOK__
			<!--（__wLastName__　様へ確認メールをお送りいたしました。）-->__IfMailOK__<br><br>
			<font color="red">受付準備が整いましたらご連絡いたします。<br></font>
			__IfOK__

			__IfIchijihozonOK__
			一時保存しました。<br>
			予約センターへ依頼はしていません。<br>
			__IfIchijihozonOK__
			<br>

			<form action="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__" method="POST">
				<input type="submit" value="メニュー" class="btn btn-info">
			</form>

		</div>

	</div>
	<!--content-all-->



	__SFooter__
	__SCopyright__

</body>
</html>

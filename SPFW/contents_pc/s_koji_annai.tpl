<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>リニューアル支援</title>
	<!-- BootstrapのCSS読み込み -->
	<link href="../include/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<!-- BootstrapのJS読み込み -->
	<script src="../include/bootstrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="../css/rnsien.css">
	<script type="text/javascript" src="../tools.js"></script>

	</script>

</head>

<body>
	__SHeader__

	<div class="content-all">
		<!--content-all-->
		<div class="left-yose">
			<a href="../s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜戻る</a>
		</div>
		<div class="top-menu left-yose">
			<h5>__wBukkenName__</h5>
			<h6>工事案内作成</h6>
		</div>
		<div>
			<form action="s_koji_annai_Excel.php" method="POST" name="mainform" onSubmit="return checkInput()">
				<input type="hidden" name="rKey" value="__rKey__">
				<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
				<div>
					<div>
						<button type="button" class="btn btn-primary blue" onclick="javascript:move('./s_koji_annai_Excel_v3.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__' )">工事案内</button>
						<button type="button" class="btn btn-primary blue" onclick="javascript:move('./s_koji_annai_Excel_Aihon.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__' )">工事案内(アイホン書式)</button>
						<button type="button" class="btn btn-primary blue" onclick="javascript:move('./s_koji_annai_Excel_v3_en.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__' )">工事案内(英語版)</button>
					</div>
					<br>
					こちらの工事案内を配布資料として利用される場合は、<br>内容をご確認いただき、「作成済み・更新資料」よりアップロードをお願いいたします。<br>
					<!-- <button type="button" class="btn btn-primary" onclick="javascript:move('./s_koji_annai_yotei_kakutei_Excel_v2.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__' )">予定・確定サンプル</button> -->
				</div>
			</form>
			<br>
		</div>
		<hr>
		<input type="button" value="メニューへもどる" onclick="javascript:move('../s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__' )" class="btn btn-info"><br>
		<!-- <br><br><br><br><br><br><br><br><br><br><br>
		**************************************************<br>
		2020/04/21<br>
		以前の1シート仕様の工事案内はこちらから出力可能です。<br>
		<button type="button" class="btn btn-primary" onclick="javascript:move('./s_koji_annai_Excel.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__' )">工事案内出力(1seetVer)</button> -->
		<!--content-all-->
		__SFooter__
		__SCopyright__

</body>
</html>

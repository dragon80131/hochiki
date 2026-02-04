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
	<script type="text/javascript" src="tools.js"></script>

</head>

<body>
	__SHeader__

	<div class="content-all">
		<!--content-all-->

		<hr size="__HRSize__" color="__HRColor__">
		<center>《会社名・パスワード入力フォーム》</center>
		<hr size="__HRSize__" color="__HRColor__">

		<div class="left-yose">
			<a href="#" onclick="javascript:move('s_search.php')">＜＜＜　トップ</a><br>
本機能は、管理者の方のみ利用できます。
__IfKanrishaFlg__
			__wTitle__<br>

			<font color="red">__ErrorLoop____ErrorString__<br />__ErrorLoop__</font>

			<form action="s_gyosya_passwd.php" method="POST" name="mainform">

				<table class="table table-bordered table-sm">
					<tr>
						<td bgcolor="lightgreen">施工業者CD</td>
						<td>__editGyosyaCD__ (システムが決定しているので変更できません)</td>
					</tr>
					<tr>
						<td bgcolor="lightgreen">
							施工業者名
						</td>
						<td>
							<font color="red">※必須</font><br>
							<input type="text" name="wGyosyaName" value="__wGyosyaName__" style="width:300px"><br>
							<font size="2" color="gray">※「株式会社」「有限会社」等を入力してください</font>
						</td>
					</tr>
					<tr>
						<td bgcolor="lightgreen">
							業者名ふりがな
						</td>
						<td>
							<font color="red">※必須</font><br>
							<input type="text" name="wGyosyaNameKana" value="__wGyosyaNameKana__" style="width:300px"><br>
							<font size="2" color="gray">※「かぶしきがいしゃ」「ゆうげんがいしゃ」等は入力しないでください</font>
						</td>
					</tr>
					<tr>
						<td bgcolor="lightgreen">
							電話番号
						</td>
						<td>
							<font color="red">※必須</font><br>
							<input type="text" name="wGyosyaTEL" value="__wGyosyaTEL__" style="width:150px">
						</td>
					</tr>					<tr>
						<td bgcolor="lightgreen">
							会社パスワード
						</td>
						<td>
							<font color="red">※必須 作業工程表、写真アプリ、完成図書アプリのログインパスワードです。</font>
							<br>新しくネスペに依頼いただく物件から変更後のパスワードとなります。ネスペに依頼済みの物件は、変更前のパスワードとなります。
							依頼済みの物件のパスワードを変更したい場合はネスペにご連絡ください。<br>
							<input type="text" name="wGyosyaPasswd" value="__wGyosyaPasswd__" style="width:150px">
						</td>
					</tr>
					<tr>
						<td bgcolor="lightgreen">備考</td>
						<td><input type="text" name="wGyosyaNotes" value="__wGyosyaNotes__" style="width:300px"></td>
					</tr>
				</table>

				<br>
				<input type="hidden" name="work" value="">
				<input type="hidden" name="editGyosyaCD" value="__editGyosyaCD__">
				<input type="hidden" name="rKey" value="__rKey__">
				<input type="button" value="登　録" class="btn btn-primary" onclick="javascript:moveWithWork('s_gyosya_passwd.php', 1 )">
			</form>
__IfKanrishaFlg__
		</div>


		<hr size="__HRSize__" color="__HRColor__">
		<a href="s_search.php?rKey=__rKey__">トップ</a><br>
		<hr size="__HRSize__" color="__HRColor__">
		__SFooter__
		__SCopyright__


	</div>
	<!--content-all-->

</body>
</html>

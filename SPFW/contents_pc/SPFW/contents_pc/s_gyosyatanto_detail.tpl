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
		<center>《ユーザー情報入力フォーム》</center>
		<hr size="__HRSize__" color="__HRColor__">

		<div class="left-yose">
			<a href="#" onclick="javascript:move('s_gyosyatanto_list.php')">＜＜＜　ユーザー一覧</a><br><br>


			<font color="red">__ErrorLoop____ErrorString__<br />__ErrorLoop__</font>

			<form action="s_gyosyatanto_list.php" method="POST" name="mainform">

				<table class="table table-bordered table-sm">
					<tr>
						<td bgcolor="lightcyan">担当CD</td>
						<td>__editGyosyaTantoCD__</td>
					</tr>
					<tr>
						<td bgcolor="lightcyan">担当氏名</td>
						<td>
							<font color="red">※必須</font><br>
							<input type="text" name="wGyosyaTantoName" value="__wGyosyaTantoName__" style="width:200px">
						</td>
					</tr>
					<tr>
						<td bgcolor="lightcyan">担当氏名かな</td>
						<td>
							<font color="red">※必須 ひらがな</font><br>
							<input type="text" name="wGyosyaTantoNameKana" value="__wGyosyaTantoNameKana__" style="width:200px">
						</td>
					</tr>
					<tr>
						<td bgcolor="lightcyan">会社名</td>
						<td>
							<font color="red">※必須</font><br>
							<select name="wGyosyaCD">
								<option value="">-</option>
								__GyosyaLoop__
								<option value="__GyosyaCD__" __GyosyaSelected__>__GyosyaName__ </option>
								__GyosyaLoop__
							</select>
						</td>
					</tr>
					<tr>
						<td bgcolor="lightcyan">支店名・営業所名</td>
						<td><input type="text" name="wSitenEigyoshoName" value="__wSitenEigyoshoName__" style="width:300px"></td>
					</tr>
					<tr>
						<td bgcolor="lightcyan">営業/施工</td>
						<td>
							<label><input type="radio" name="wUserType" value="2" __UserTypeChecked2__>営業</label>　
							<label><input type="radio" name="wUserType" value="3" __UserTypeChecked3__>施工</label>
						</td>
					</tr>
					<tr>
						<td bgcolor="lightcyan">ログインID</td>
						<td>
							<font color="gray" size="2">入力する場合は、ユーザ名を半角英数字6文字以上で入力してください。</font><br>
							<input type="text" name="wID" value="__wID__" style="width:100px" __IME_OFF__>
						</td>
					</tr>
					<tr>
						<td bgcolor="lightcyan">パスワード</td>
						<td>
							<font color="gray" size="2">入力する場合は、パスワードを半角英数字8文字以上で入力してください。</font><br>
							<input type="text" name="wPasswd" value="__wPasswd__" style="width:100px" __IME_OFF__>
						</td>
					</tr>
					<tr>
						<td bgcolor="lightcyan">電話番号</td>
						<td><input type="text" name="wGyosyaTantoTEL" value="__wGyosyaTantoTEL__" style="width:150px"></td>
					</tr>
					<tr>
						<td bgcolor="lightcyan">携帯電話</td>
						<td><input type="text" name="wGyosyaTantoKeitai" value="__wGyosyaTantoKeitai__" style="width:150px"></td>
					</tr>
					<tr>
						<td bgcolor="lightcyan">メールアドレス</td>
						<td>
							<font color="red">※必須</font><br>
							<input type="text" name="wGyosyaTantoMail" value="__wGyosyaTantoMail__" style="width:300px">
						</td>
					</tr>
					<tr>
						<td bgcolor="lightcyan">メールアドレス2</td>
						<td><input type="text" name="wGyosyaTantoMail2" value="__wGyosyaTantoMail2__" style="width:300px"></td>
					</tr>
					<tr>
						<td bgcolor="lightcyan">備考</td>
						<td><input type="text" name="wGyosyaTantoNotes" value="__wGyosyaTantoNotes__" style="width:300px"></td>
					</tr>
				</table>

				<input type="hidden" name="dummy" value="入口">
				<!--20171026 一部文字化け対策で日本語のdummyを送信する-->
				<input type="hidden" name="work" value="">
				<input type="hidden" name="editGyosyaTantoCD" value="__editGyosyaTantoCD__">
				<input type="hidden" name="rKey" value="__rKey__">
				<input type="button" value="登　録" class="btn btn-primary" onclick="javascript:moveWithWork('s_gyosyatanto_list.php', 1 )">
			</form>

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

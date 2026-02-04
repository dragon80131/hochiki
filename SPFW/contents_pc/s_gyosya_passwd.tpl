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
	<hr size="__HRSize__" color="__HRColor__">
	<center>《会社名・パスワード管理》</center>
	<hr size="__HRSize__" color="__HRColor__">

	<div class="left-yose">
		<a href="#" onclick="javascript:move('s_search.php__QUERY__')">＜＜＜　トップ</a><br>
本機能は、管理者の方のみ利用できます。
__IfKanrishaFlg__
<br>ユーザ　１２３４でログインしているパスワードです。<br>
	セキュリティ強化のため初期パスワードの変更お願いします。<br>
	パスワードを変更された場合、変更前の物件のパスワードは変更されていません。<br>
	登録済みの物件の１２３４のパスワードを変更希望の会社様はネスぺにご連絡お願いします。<br>
	スマート工事くんのパスワードとは連動しておりません。<br>
		<form action="s_gyosya_list.php" name="mainform" method="POST">
			<input type="hidden" name="editGyosyaCD" value="">
			<input type="hidden" name="work" value="">
			<input type="hidden" name="rKey" value="__rKey__">

			<table class="table table-bordered table-sm table-striped">
				<tr>
					<th>詳細/編集</th>
					<th>施工業者名</th>
					<th>電話番号</th>
					<th>会社パスワード</th>
					<th>備考</th>
					<!--<th>削除</th>-->
				</tr>
				__GyosyaListLoop__
				<tr>
					<td><input type="button" value="編集" class="btn btn-info btn-sm" onclick="javascript:moveWithGyosyaCD( 's_gyosya_passwd_detail.php?rKey=__rKey__', __GyosyaCD__  )"></td>
					<td>__GyosyaName__</td>
					<td>__GyosyaTEL__</td>
					<td>__GyosyaPasswd__</td>
					<td>__GyosyaNotes__</td>
					<!--<td><input type="button" value="削除" class="btn btn-danger btn-sm" onclick="javascript:moveWithKeyAndWork10( 's_gyosya_list.php?rKey=__rKey__', __GyosyaCD__ , 2 )"></td>-->
				</tr>
				__GyosyaListLoop__

			</table>
		</form>
__IfKanrishaFlg__

	</div>



	<hr size="__HRSize__" color="__HRColor__">
	<a href="s_search.php__QUERY__">トップ</a><br>
	<hr size="__HRSize__" color="__HRColor__">

	__SFooter__
	__SCopyright__
</body>
</html>

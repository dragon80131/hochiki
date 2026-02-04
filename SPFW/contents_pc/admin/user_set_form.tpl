<html>
<head>
<title>会員管理 − 動作設定</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>
__IfAjax__<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__</head>

<body>
<div class="content">
<h2 class="admin-title">会員管理 − 動作設定__ExtraTitle__</h2>
<br />

<form method="POST" action="user_set_end.php" name="mainform">

<h2 class="navigation"><a href="#" onclick="javascript:window.location.href='user_set_menu.php'">&lt;&lt;&lt; システム設定メニューへもどる</a></h2>
<h2 class="navigation"><a href="#" onclick="javascript:window.location.href='user_search.php?rKey=__rKey__'">&lt;&lt;&lt; 検索フォームへもどる</a></h2>
<h2 class="navigation"><a href="index.php">&lt;&lt;&lt; トップメニューへ</a></h2>
<br />

<table class="common-list">
	<tr>
		<td nowrap class="common-list-title" colspan="2">ログイン関連</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">ログイン動作</td>
		<td class="common-list-value-left">
			<input type="checkbox" name="wLoginFlg" value="t" size="50" class="form"__LoginFlgChecked__>IDとパスワードによるログインを可能にする<br />
			※可能にする場合、自動的に基本設問設定のIDとパスワードは設問項目として利用されるようになります。<br />
			<input type="checkbox" name="wEasyLoginFlg" value="t" size="50" class="form"__EasyLoginFlgChecked__>簡単ログインを可能にする<br />
			※簡単ログインは端末識別子によるログインです。IDやパスワード入力の手間が省けます。
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">セッションタイムアウト</td>
		<td class="common-list-value-left">
			<input type="text" name="wTimeout" value="__wTimeout__" size="10" __IME_ON__ class="form">　分<br />
			※ログイン後この時間が経過すると再度ログインする必要があります。無制限な場合には0を入れてください。セキュリティ上、無制限にはされないことをおすすめします。
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">ログインキー変更</td>
		<td class="common-list-value-left">
			<input type="checkbox" name="wKeyChangeFlg" value="t" size="50" class="form"__KeyChangeFlgChecked__>ログインキーを変更する<br />
			※URLに付くログイン状態を保つためのキーが、ログインの度に変更されます。<br />
			※メールマガジン発行などの場合でキーを変更されると困る場合、チェックを外してください。<br />
			※よく分からない場合にはチェックを入れておいたほうがセキュリティ上安全です。
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" colspan="2">登録内容変更関連</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">登録内容変更時認証</td>
		<td class="common-list-value-left">
			<input type="checkbox" name="wChangeSecurityFlg" value="t" size="50" class="form"__ChangeSecurityFlgChecked__>会員登録内容を変更する時はパスワードを求める<br />
			※会員登録内容の変更画面に入る時に、一度パスワードを求めます。変更画面では個人情報を扱いますので、セキュリティを強化できます。<br />
			※可能にする場合、自動的に基本設問設定のパスワードは設問項目として利用されるようになります。
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" colspan="2">登録完了画面内設定</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">リンク先・文言</td>
		<td class="common-list-value-left">
			リンク先　　<input type="text" name="wLinkTo" value="__wLinkTo__" size="50" class="form"><br />
			リンク文言　<input type="text" name="wLinkMessage" value="__wLinkMessage__" size="50" class="form"><br />
			※会員登録画面にあるリンクの飛び先URLとリンク文言を設定できます。外部サイトへ遷移したい場合等に使います。<br />
			※空欄にすると「トップへ」という文言でトップページに遷移します。
		</td>
	</tr>

	<tr id="blockAction">
		<td nowrap class="common-list-value-left" colspan="2">
			<input type="button" value="データ更新" class="button" onclick="javascript:move('user_set_end.php')">　
			<input type="reset" value="フォームを元に戻す" class="button">　
			<input type="button" value="もどる" class="button" onclick="javascript:history.back()">　　　
		</td>
	</tr>
</table>

__HiddenValues__
</form>

<div class="footer-box">
	__SAdminCopyright__
</div>

</body>
</html>
<html>
<head>
<title>設問設定</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>
</head>

<body>

<div class="content">
<h2 class="admin-title">設問設定__ExtraTitle__</h2>
<br />

<form method="POST" action="question_s.php" name="mainform">

__IfFromUser__<h2 class="navigation"><a href="#" onclick="javascript:window.location.href='user_set_menu.php'">&lt;&lt;&lt; システム設定メニューへもどる</a></h2>
<h2 class="navigation"><a href="#" onclick="javascript:window.location.href='user_search.php?rKey=__rKey__'">&lt;&lt;&lt; 検索フォームへもどる</a></h2>
__IfFromUser____IfFromClient__<h2 class="navigation"><a href="#" onclick="javascript:window.location.href='client_list.php'">&lt;&lt;&lt; 顧客リストへもどる</a></h2>
__IfFromClient__<h2 class="navigation"><a href="index.php">&lt;&lt;&lt; トップメニューへ</a></h2>
<br />
<h2 class="navigation"><font color="red">※必須チェックがONの場合、利用チェックもONでないと有効になりません。</font></h2>
<h2 class="navigation"><font color="red">※検索チェックがONの場合、ユーザ検索時に絞り込み条件にすることができます。</font></h2>

<table class="common-list" width="700">
	<tr>
		<td class="common-list-title">項目リスト</td>
		<td class="common-list-value" rowspan="__RowSpan__" valign="middle">
			左から選択して「移動」ボタンを押してください<br />
			<input type="button" value="移動" onclick="javascript:move('user_list_set_form.php');">
		</td>
		<td class="common-list-title">一覧表示項目</td>
	</tr>
__QuestionLoop__	<tr>
		<td class="common-list-value-left"><input type="checkbox" name="wToList[]" value="__QuestionID__">__Question__</td>
		<td class="common-list-value-left">
__IfExists__			<input type="hidden" name="wListQuestionID[__ListNumber__]" value="__ListQuestionID__">
			<input type="checkbox" name="wDelete[__ListNumber__]" value="t"><input type="text" name="wQuestionNameNew[__ListNumber__]" value="__ListQuestionName__" size="30">
__IfExists__		</td>
	</tr>
__QuestionLoop__
	<tr>
		<td class="common-list-title">　</td>
		<td class="common-list-title">
			<input type="button" value="リストタイトル名称変更" onclick="javascript:moveWithWork('user_list_set_form.php', 1);">
			<input type="button" value="削除" onclick="javascript:moveWithWork('user_list_set_form.php', 2);">
		</td>
	</tr>
</table>

__HiddenValues__

<div class="footer-box">
	__SAdminCopyright__
</div>
</form>

</body>
</html>
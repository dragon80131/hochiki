<html>
<head>
<title>設問設定</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>
__IfAjax__<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__</head>

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

<table class="common-list" width="700" id="mainTable">
	<tr>
		<td nowrap class="common-list-title">設問番号</td>
		<td nowrap class="common-list-title">設問用途</td>
		<td nowrap class="common-list-title">項目名称(ユーザ向け)</td>
		<td nowrap class="common-list-title">利用チェック</td>
		<td nowrap class="common-list-title">必須チェック</td>
		<td nowrap class="common-list-title">検索チェック</td>
	</tr>

__QuestionLoop__	<tr class="common-list">
		<td class="common-list-value">__QuestionNo__</td>
		<td class="common-list-value">__Question__</td>
		<td class="common-list-value-left"><input type="text" name="wQuestionName[__QuestionNo__]" value="__QuestionName__" size="30"></td>
		<td nowrap class="common-list-value"><input type="checkbox" name="wUse[__QuestionNo__]" value="t"__UseChecked__></td>
		<td nowrap class="common-list-value"><input type="checkbox" name="wRequired[__QuestionNo__]" value="t"__RequiredChecked__></td>
		<td nowrap class="common-list-value"><input type="checkbox" name="wSearch[__QuestionNo__]" value="t"__SearchChecked__></td>
	</tr>
__QuestionLoop__

	<tr>
		<td nowrap class="common-list-value-left" colspan="6">
			<input type="button" value="設定" onclick="javascript:move('user_property_end.php');">
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
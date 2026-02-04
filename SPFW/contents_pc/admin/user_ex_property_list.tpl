<html>
<head>
<title>設問管理 − 設問リスト</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>
__IfAjax__<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__</head>

<body>

<div class="content">
<h2 class="admin-title">設問管理 − 設問リスト__ExtraTitle__</h2>
<br />

__IfCreate__
<table class="common-list">
	<tr>
		<td nowrap class="common-list-value">
		『__Question__』の新規登録を行いました。<br />
		</td>
	</tr>
</table>
<br />
__IfCreate__

__IfUpdate__
<table class="common-list" width="700">
	<tr>
		<td nowrap class="common-list-value">
		『__Question__』の情報更新を行いました。<br />
		</td>
	</tr>
</table>
<br />
__IfUpdate__

__IfDelete__
<table class="common-list" width="700">
	<tr>
		<td nowrap class="common-list-value">
		『__Question__』の削除を行いました。<br />
		</td>
	</tr>
</table>
<br />
__IfDelete__

<form method="POST" action="user_ex_property_detail.php" name="mainform">

<h2 class="navigation"><a href="#" onclick="javascript:moveWithQuestionKey('user_ex_property_detail.php', -1, __MaxFlg__)">&gt;&gt;&gt; 新しい設問を作成する</a></h2>
<br />
__IfFromUser__<h2 class="navigation"><a href="#" onclick="javascript:window.location.href='user_set_menu.php'">&lt;&lt;&lt; システム設定メニューへもどる</a></h2>
<h2 class="navigation"><a href="#" onclick="javascript:window.location.href='user_search.php?rKey=__rKey__'">&lt;&lt;&lt; 検索フォームへもどる</a></h2>
__IfFromUser____IfFromClient__<h2 class="navigation"><a href="#" onclick="javascript:window.location.href='client_list.php'">&lt;&lt;&lt; 顧客リストへもどる</a></h2>
__IfFromClient__<h2 class="navigation"><a href="index.php">&lt;&lt;&lt; トップメニューへ</a></h2>
<br />
<h2 class="navigation"><font color="red">※設問は最大10問までとなります。</font></h2>
<h2 class="navigation"><font color="red">※削除するとその設問の回答データはなくなりますのでご注意ください。</font></h2>

<table class="common-list" width="700" id="mainTable">
	<tr>
		<td nowrap class="common-list-title">	設問番号</td>
		<td nowrap class="common-list-title">	管理番号</td>
		<td nowrap class="common-list-title">必須</td>
		<td nowrap class="common-list-title">設問</td>
		<td nowrap class="common-list-title">設問タイプ</td>
		<td nowrap class="common-list-title">選択肢</td>
		<td nowrap class="common-list-title">詳細/編集</td>
		<td nowrap class="common-list-title">削除</td>
	</tr>

__IfResults__
__QuestionLoop__	<tr class="common-list">
		<td class="common-list-value">__QuestionNo__</td>
		<td class="common-list-value">__ColumnIndex__</td>
		<td class="common-list-value">__IfRequired__◎__IfRequired__</td>
		<td class="common-list-value-left">__Question__</td>
		<td class="common-list-value">__pTypeOfQuestion__</td>
		<td class="common-list-value-left">__pChoices__</td>
		<td nowrap class="common-list-value"><a href="#" onclick="javascript:moveWithQuestionKey('user_ex_property_detail.php', __QuestionCD__);">GO!</a></td>
		<td nowrap class="common-list-value"><a href="#" onclick="javascript:moveWithQuestionKeyAndWork('user_ex_property_list.php', __QuestionCD__, 2);">GO!</a></td>
	</tr>
__QuestionLoop__
__IfResults__

	__IfNoResults__
	<tr>
		<td nowrap class="common-list-value" colspan="8"><font color="red">現在設問は設定されていません</font></td>
	</tr>
__IfNoResults__
</table>

__HiddenValues__
<div class="footer-box">
	__SAdminCopyright__
</div>
</form>

</body>
</html>
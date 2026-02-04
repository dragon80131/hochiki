<html>
<head>
<title>設問管理 − 設問フォーム</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>
__IfAjax__<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__
<SCRIPT language=JavaScript>
<!-- Hide script from old browser
// 変更警告
function checkType() {
	currentType = __CurrentTypeOfQuestion__;
	newType = document.mainform.wTypeOfQuestion.value;

	setDisplay();

	if (currentType == -1 || currentType == newType || (currentType == 3 || currentType == 4) && (newType == 3 || newType == 4)) {
		return true;
	}
	else {
		alert('設問タイプをこの形式に変更すると、これまでの会員の回答がリセットされます。ご注意ください。');
		return true;
	}
}

// 変更警告
function setDisplay() {
	newType = document.mainform.wTypeOfQuestion.value;

	var ua;
	var show;
	ua = navigator.userAgent.toLowerCase();
	if (ua.indexOf("msie") != -1)
		show = 'block';
	else
		show = 'table-row';

	document.getElementById("Size1").style.display = "none";
	document.getElementById("Size2").style.display = "none";
	document.getElementById("TextFormat").style.display = "none";;
	document.getElementById("Choices").style.display = "none";;

	if (newType == 1) {
		document.getElementById("Size1").style.display = show;
		document.getElementById("Size2").style.display = "none";
		document.getElementById("TextFormat").style.display = show;;
		document.getElementById("Choices").style.display = "none";;
	}
	else if (newType == 2) {
		document.getElementById("Size1").style.display = "none";
		document.getElementById("Size2").style.display = show;
		document.getElementById("TextFormat").style.display = show;;
		document.getElementById("Choices").style.display = "none";;
	}
	else if (newType == 3 || newType == 4 || newType == 5) {
		document.getElementById("Size1").style.display = "none";
		document.getElementById("Size2").style.display = "none";
		document.getElementById("TextFormat").style.display = "none";;
		document.getElementById("Choices").style.display = show;;
	}
}
// end hiding -->
</SCRIPT>

</head>

<body onload="setDisplay()">
<div class="content">
<h2 class="admin-title">設問管理 − 設問フォーム__ExtraTitle__</h2>
<br />

__IfFromUser__<h2 class="navigation"><a href="#" onclick="javascript:window.location.href='user_ex_property_list.php'">&lt;&lt;&lt; 設問リストへもどる</a></h2>
<h2 class="navigation"><a href="#" onclick="javascript:window.location.href='user_set_menu.php'">&lt;&lt;&lt; システム設定メニューへもどる</a></h2>
<h2 class="navigation"><a href="#" onclick="javascript:window.location.href='user_search.php?rKey=__rKey__'">&lt;&lt;&lt; 検索フォームへもどる</a></h2>
__IfFromUser____IfFromClient__<h2 class="navigation"><a href="#" onclick="javascript:window.location.href='client_list.php'">&lt;&lt;&lt; 顧客リストへもどる</a></h2>
__IfFromClient__<h2 class="navigation"><a href="index.php">&lt;&lt;&lt; トップメニューへ</a></h2>
<br />

<form method="POST" action="user_ex_property_list.php" name="mainform">

<table class="common-list" width="700">
	<tr id="">
		<td nowrap class="common-list-title" width="200">設問</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wQuestion" value="__wQuestion__" size="60" __IME_ON__ class="form">
		</td>
	</tr>
	<tr id="">
		<td class="common-list-title" width="200">必須指定</td>
		<td class="common-list-value-left">
			<input type="checkbox" name="wRequired" value="t"__RequiredChecked__>この設問を必須にする
		</td>
	</tr>
	<tr id="TypeOfQuestion">
		<td nowrap class="common-list-title" width="200">設問タイプ</td>
		<td nowrap class="common-list-value-left">
			<select name="wTypeOfQuestion" class="form" onChange="javascript:checkType()">
__TypeOfQuestionLoop__			<option value="__TypeOfQuestionValue__"__TypeOfQuestionSelected__>__TypeOfQuestionName__
__TypeOfQuestionLoop__			</select>
		</td>
	</tr>
	<tr id="Size1">
		<td class="common-list-title" width="200">テキストボックスサイズ</td>
		<td class="common-list-value-left">
			<input type="text" name="wSize" value="__wSize__" size="5" __IME_ON__ class="form">　桁
		</td>
	</tr>
	<tr id="Size2">
		<td class="common-list-title" width="200">テキストエリアサイズ</td>
		<td class="common-list-value-left">
			横　<input type="text" name="wCols" value="__wCols__" size="5" __IME_ON__ class="form">　桁　×　
			縦　<input type="text" name="wRows" value="__wRows__" size="5" __IME_ON__ class="form">　行
		</td>
	</tr>
	<tr id="TextFormat">
		<td class="common-list-title" width="200">入力モード</td>
		<td class="common-list-value-left">
			<select name="wTextFormat" class="form">
__TextFormatLoop__				<option value="__TextFormatValue__"__TextFormatSelected__>__TextFormatName__
__TextFormatLoop__				</select>
		</td>
	</tr>
	<tr id="Choices">
		<td class="common-list-title" width="200">選択肢</td>
		<td class="common-list-value-left">
			<textarea name="wChoices" cols="20" rows="10" class="form">__wChoices__</textarea>
		</td>
	</tr>

	<tr id="">
		<td class="common-list-title" width="200">検索指定</td>
		<td class="common-list-value-left">
			<input type="checkbox" name="wSearch" value="t"__SearchChecked__>この設問で会員検索を行う
		</td>
	</tr>
	<tr id="">
		<td class="common-list-title" width="200">非表示指定</td>
		<td class="common-list-value-left">
			<input type="checkbox" name="wInternalUse" value="t"__InternalUseChecked__>この設問は会員登録画面には出さない(管理用の項目としてのみ利用)
		</td>
	</tr>

	<tr id="blockAction">
		<td nowrap class="common-list-value-left" colspan="2">
			
			<input type="button" value="データ更新" class="button" onclick="javascript:moveWithWork('user_ex_property_list.php', 1)">　
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
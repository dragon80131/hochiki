<html>
<head>
	<title>お知らせ編集</title>
	<link rel="stylesheet" href="css/a8.css" type="text/css" />
	<META http-equiv=Content-Type content="text/html; charset=s-jis">
	<meta http-equiv="Content-Language" content="ja">
	<meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
	<script src="js/jquery-1.4.2.js" type="text/javascript"></script>
	<script src="tools.js" type="text/javascript"></script>
	<script>
		function moveWithNoticeKeyAlert(page, key, work) {
			if (work == 2) {
				if (window.confirm("削除しますか？")) {
					document.mainform.editNoticeCD.value = key;
					document.mainform.work.value = work;
					document.mainform.action = page;
					document.mainform.submit(true);
				}
			}
		}

		function move(page) {
			document.mainform.work.value = "-1";
			document.mainform.action = page;
			document.mainform.submit(true);
		}

	</script>
</head>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">

	<hr size="__HRSize__" color="#6699FF">
	<h3>　SPADEお知らせ内容編集画面</h3>
	<hr size="__HRSize__" color="#6699FF">
	<a href="./s_search.php?rKey=__rKey__">>>一覧</a>
	<br><br><br>

	<form method="POST" action="noticememo.php?rKey=__rKey__" name="mainform" class="noticememo">
		<input type="hidden" name="editNoticeCD" value="">
		<input type="hidden" name="work" value="1">
		<input type="hidden" name="rKey" value="__rKey__">
		内容<br>
		<textarea name="memo" cols="60" rows="8"></textarea><br>
		<input type="submit" name="btn1" value="登録">

		<br><br><br><br>
		<table class="noticeTable" width="60%">
			<tr>
				<td bgcolor="#c5ceff">お知らせ内容</td>
				<td bgcolor="#c5ceff">登録日</td>
				<td bgcolor="#c5ceff"></td>
			</tr>
			__MemoLoop__
			<tr>
				<td width="49%">__Memo__</td>
				<td width="10%">__Updated__</td>
				<td width="1%">
					<input type="button" value="削除" class="button" onclick="javascript:moveWithNoticeKeyAlert('noticememo.php', '__NoticeCD__', '2')">
				</td>
			</tr>
			__MemoLoop__
		</table>
	</form>
</body>
</html>

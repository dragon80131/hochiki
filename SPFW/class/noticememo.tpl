<html>
<head><title>周知メモ</title>

<link rel="stylesheet" href="css/a8.css" type="text/css" />


<META http-equiv=Content-Type content="text/html; charset=s-jis">

<meta http-equiv="Content-Language" content="ja">
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
<script src="js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="tools.js" type="text/javascript"></script>
<script>
function moveWithKeijiKeyAlert(page, key, work) {
	if (work == 2){
		if (window.confirm("削除しますか？")) {
			document.mainform.editSyuchiMemoCD.value = key;
			document.mainform.work.value = work;
			document.mainform.action = page;
			document.mainform.submit(true);
		}
	}
}
function move(page) {
	//document.mainform.editSyuchiMemoCD.value = key;
	document.mainform.work.value = "-1";
	document.mainform.action = page;
	document.mainform.submit(true);
}

</script>
</head>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">

<hr size="__HRSize__" color="#6699FF">
	<h3><font color = "#3366CC">《__Today__の<a href="https://app.inshare.jp/d3/dnet/xmemp.cgi?page=mempindex&bproc=dnet.cgi?"  target="_blank" >周知メモ</a>編集画面》</font></h3>
	<hr size="__HRSize__" color="#6699FF">
<table>
<a href="./site.php">>>物件一覧</a>
	<br><br>




<form method="POST" action="syuchimemo.php" name="mainform">
<input type="hidden" name="editSyuchiMemoCD" value="">
<input type="hidden" name="work" value="1">


<table class="sampleTable">
<tr bgcolor="#e3f0fb">
	</tr>
	<br><tr>

　名前　：<input type="text" name="personal_name"><br>
周知内容：<textarea name="contents" cols="40" rows="8">
</textarea><br>
<input type="submit" name="btn1" value="投稿する">




<br><br><br><br>
<table><h4>↓入力済　　※必要のないものは消してください※</h4>
__MemoLoop__
<tr><th>__Memo__</th>
<td>__SyuchiName__</td><td>
<input type="button" value="削除" class="button" onclick="javascript:moveWithKeijiKeyAlert('syuchimemo.php', '__SyuchiMemoCD__', '2')"></td></tr>
__MemoLoop__
</table>
<!--
<br><br>
<br>
<table><h4>シフト</h4>
__SyukkinLoop__
<tr><th>__Syukkin__</th>
__SyukkinLoop__
</table>

-->


</form>


</body>
</html>



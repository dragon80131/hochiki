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

<link href="./d2b/css/dropzone.css" type="text/css" rel="stylesheet" />
<script src="./d2b/dropzone.min.js"></script>
<script>
// ファイルサイズ取得
function check(no){ 
	var list1 = "", list2 = "", list3 = "", list4 = "", list5 = "";
	var fileList1 = document.getElementById("tempfile1").files;
	var fileList2 = document.getElementById("tempfile2").files;
	var fileList3 = document.getElementById("tempfile3").files;
	var fileList4 = document.getElementById("tempfile4").files;
	var fileList5 = document.getElementById("tempfile5").files;
	var listsize1 = "", listsize2 = "", listsize3 = "", listsize4 = "", listsize5 = "";
	for(var i=0; i<fileList1.length; i++){
		list1 += "[" + fileList1[i].size + " bytes]" + fileList1[i].name + "<br>";
		listsize1 = fileList1[i].size;
	}
	for(var i=0; i<fileList2.length; i++){
		list2 += "[" + fileList2[i].size + " bytes]" + fileList2[i].name + "<br>";
		listsize2 = fileList2[i].size;
	}
	for(var i=0; i<fileList3.length; i++){
		list3 += "[" + fileList3[i].size + " bytes]" + fileList3[i].name + "<br>";
		listsize3 = fileList3[i].size;
	}
	for(var i=0; i<fileList4.length; i++){
		list4 += "[" + fileList4[i].size + " bytes]" + fileList4[i].name + "<br>";
		listsize4 = fileList4[i].size;
	}
	for(var i=0; i<fileList5.length; i++){
		list5 += "[" + fileList5[i].size + " bytes]" + fileList5[i].name + "<br>";
		listsize5 = fileList5[i].size;
	}
	document.getElementById("tempfileresult").innerHTML = list1 +list2 + list3 + list4 + list5;

	var alertFlg = "";
	if (no == 1) {
		if (listsize1 > 10485760) { alertFlg = 1; }
	} else if (no == 2) {
		if (listsize2 > 10485760) { alertFlg = 1; }
	} else if (no == 3) {
		if (listsize3 > 10485760) { alertFlg = 1; }
	} else if (no == 4) {
		if (listsize4 > 10485760) { alertFlg = 1; }
	} else if (no == 5) {
		if (listsize5 > 10485760) { alertFlg = 1; }
	}

	document.getElementById("tempfilesizeresult").style.display="none";
	if (listsize1 > 10485760 || listsize2 > 10485760 || listsize3 > 10485760 || listsize4 > 10485760 || listsize5 > 10485760) {
		document.getElementById("tempfilesizeresult").innerHTML = "ng";
	} else {
		document.getElementById("tempfilesizeresult").innerHTML = "";
	}

	if (alertFlg == 1) {
		alert("サイズが10MB以上のファイルは登録できません。");
		return false;
	}
}

function tempfileclear(oId) {
	var obj = document.getElementById(oId);
	var stO = obj.innerHTML;
	obj.innerHTML = stO;
	check();
}

function moveWithWorkAndFileCD(page, key, work, msg) {
	if (work == 2){
		if (window.confirm(" 削除してもよろしいでしょうか？ ")) {
			document.mainform.editFileCD.value = key;
			document.mainform.work.value = work;
			document.mainform.action = page;
			document.mainform.submit(true);
			document.mainform.work.value = '';
		}
	}
}
</script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
<script type="text/javascript" src="js/ConnectedSelect.js"></script>

</head>

<body>
__SHeader__

<div class="content-all"><!--content-all-->

<div class="left-yose">
<a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜メニュー</a>
</div>

<div class="top-menu left-yose">
<h5>__BukkenName__</h5>
<h6>作成済・更新資料一覧</h6>


<form action="s_made_list.php" method="POST" name="mainform" enctype="multipart/form-data">
<input type="hidden" name="rKey" value="__rKey__" >
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__" >
<input type="hidden" name="editFileCD" value="" >
<input type="hidden" name="work" value="" >

<table class="table table-bordered table-sm">
<tr><th></th><th>ファイル名</th><th>登録日</th><th>登録者</th><th>削除</th></tr>
__FileLoop__
<tr>
	<td style="width:30px">__FileNo__</td>
	<td><a href="./viewfile.php?rKey=__rKey__&BukkenCD=__editBukkenCD__&FileCD=__FileCD__ " >__FileName__ </a></td>
	<td style="width:160px"><font size="2">__Created__</font></td> 
	<td style="width:100px">__LastName__</td>
	<td style="width:60px"><input type="button" value="削除" class="button" onclick="javascript:moveWithWorkAndFileCD('s_made_list.php',  __FileCD__ ,2 )"></td>
</tr>
__FileLoop__
</table>
</form>


<br>
システムで作成後に更新したファイルや、予約センターへ依頼時に必要なファイルなどをアップします。
<table class="table table-bordered table-sm">
<tr><td class="yb">
		※1ファイルのサイズが10M(=10485760 bytes)を超えると登録できません。<br>
		<!--<a href="/kotei/upfile/iraifile_tmp/yoyakuirai_file.pdf" target="_blank">▼資料サイズが大きい場合</a><br>-->
	</td></tr>
<tr><td>
	ファイルアップロード用<br>
	<div style="background-color:aquamarine">
		<form action="./d2b/kojikosinupload.php?BukkenCD=__editBukkenCD__&UserCD=__wUserCD__" class="dropzone"></form>
	</div>

	<br>
	<a href="#" onclick="javascript:move('s_made_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')"> 最新の情報に更新</a><br>
</td></tr>
</table>




<!--<input type="button" value="内容確認" onclick="javascript:FilesSubmit(this.form);" class="btn btn-primary">-->
<br>


</div>



</div><!--content-all-->


__SFooter__
__SCopyright__

</body>
</html>

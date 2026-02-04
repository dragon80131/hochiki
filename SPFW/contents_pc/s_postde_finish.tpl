<html>
<head>
<title>リニューアル支援</title>
<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a5.css">
<!-- BootstrapのCSS読み込み -->
<link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<script type="text/javascript" src="tools.js"></script>
<link href="css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="js/jquery.ui.core.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
<script src="js/jquery.numberPicker.js" type="text/javascript"></script>
<script type="text/javascript">
$(function() {
	$("#YoteTekyoDate").datepicker({});
	$("#YoteDate").datepicker({});
	$("#YoyakuEnd").datepicker({});
	$("#TekyoDate").datepicker({});
	$("#KeteiDate").datepicker({});
	$("#PhotoTekyoDate").datepicker({});
	$("#IraiDate").datepicker({});
});

// 画面読み込み時処理
window.onload = function(){
	var result = "__resultMitumoriNo__";
	if (result == "NG") {
		alert("件名Noの登録が正しくありません。\nメニュー画面の物件情報から「件名No」を、10桁の半角数字で登録してください。");
	}
}

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
</script>

<script type="text/javascript">
 //ファイル名取得のため
function FilesSubmit(Form){


	if (document.mainform.wPSScene1.value=='2' || document.mainform.wPSScene2.value=='2') {
		if (document.mainform.wPhotoPattern.value=='a-1' || document.mainform.wPhotoPattern.value=='a-2') {
			window.alert('専有部の撮影シーンで「施工中」が選択されている為、台帳選択a(4枚)は選択できません');
			return(false);
		}
	}

	if (document.getElementById("tempfilesizeresult").innerHTML == 'ng') {
		window.alert('資料のサイズが大きすぎます。1ファイル10MB以下にしてください。');
		return(false);
	}
	if (document.mainform.wAnswer.value=='') {
		window.alert('専有部工事日時変更受付方法が入力されていません');
		return(false);
	}else{
		var str = "";
		var frm = Form;
		for(i=0;i<frm.length;i++) {
			if(frm.elements[i].type == "file") {
				str = str+frm.elements[i].name+":<->:"+frm.elements[i].value+"%<->%";
			}
		}
		frm.hiddenfilenames.value=str;
		frm.submit();
	}
}


function moveWithWorkAndIraiFileCD(page, key, work, msg) {
	if (work == 2){
		if (window.confirm(" 削除してもよろしいでしょうか？ ")) {
			document.mainform.editIraiFileCD.value = key;
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

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">

　<a href="s_menu2.php__QUERY__&editBukkenCD=__editBukkenCD__">メニューへ</a>

<hr size="__HRSize__" color="__HRColor__">
<center>《ポストでポン》</center>
<hr size="__HRSize__" color="__HRColor__">


<br>

<form action="..//s_doc.php" method="POST" name="mainform" enctype="multipart/form-data">
<input type="hidden" name="wShozokuCD" value="__wShozokuCD__" >
<input type="hidden" name="editIraiFileCD" value="" >
<input type="hidden" name="work" value="" >

物件情報を送信しました。


<input type="hidden" name="rKey" value="__rKey__">
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">


<!-- ファイル名取得のため
<input type="submit" value="  内容確認  " >-->
<input type="hidden" name="hiddenfilenames" value="">
<input type="submit" value="工事資料" >
</form>
<br>

<hr size="__HRSize__" color="__HRColor__">
<a href="../s_menu.php__QUERY__&editBukkenCD=__editBukkenCD__ ">メニュー</a><br><br>
<a href="../s_search.php__QUERY__">トップ</a><br>
<hr size="__HRSize__" color="__HRColor__">
__SFooter__
__SCopyright__
<br>
</body>
</html>

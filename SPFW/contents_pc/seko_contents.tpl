<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>スマート工事くん</title>

<link rel="stylesheet" type="text/css" href="./css/rnsien.css">
<script type="text/javascript" src="tools.js"></script>
__IfAjax__<script type="text/javascript" src="js/jquery-1.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__

<!-- jQuery読み込み -->
<script src="include/js/jquery-3.2.1.min.js"></script>
<script>
$(document).on('change','input[name="upfile"]',function(){

	var idname = $(this).attr("id");
	var idname2 = idname.slice(6);

	var fd = new FormData();
	//if ($("input[name='upfile']").val()!== '') {
	if ($("input[id="+idname+"]").val()!== '') {
		//fd.append( "file", $("input[name='upfile']").prop("files")[0] );
		//fd.append( "file", $("input[id='upfile1']").prop("files")[0] );
		fd.append( "file", $("input[id="+idname+"]").prop("files")[0] );
	}
	//fd.append("dir",$("#upfile1").val());
	fd.append("dir",$("#"+idname).val());
	var postData = {
		type : "POST",
		dataType : "json",
		data : fd,
		processData : false,
		contentType : false
	};


	$.ajax(
		"seko_fileupload_ajax.php?UserCD=__UserCD__&GyosyaCD=0&FileType="+idname2, postData
	).done(function( text ){
		console.log(text);
		if (text.result == "ok") {
			window.location.reload(true);	//ページをリロード
		} else {
			alert("登録に失敗しました");
		}
	})
	.fail(function(jqXHR, statusText, errorThrown){
		console.log(errorThrown);
		alert("通信環境を確認してください "+errorThrown);
	});

});
function moveWithFileCDWithWorkDel(page, key, work) {
	if (work == 2){
		if (window.confirm(" 削除してもよろしいでしょうか？ ")) {
			document.mainform.editSekoFileCD.value = key;
			document.mainform.work.value = work;
			document.mainform.action = page;
			document.mainform.submit(true);
			document.mainform.work.value = '';
		}
	}
}

</script>
<style>
label {
	color: #000000; /* ラベルテキストの色を指定する */
	background-color: #d3d3d3; /* ラベルの背景色を指定する */
	padding: 2px; /* ラベルとテキスト間の余白を指定する */
	border: solid 2px #808080; /* ラベルのボーダーを指定する */
	font-size: 12px;
}
</style>
</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<div align="right">__LastName__</div>

<hr size="__HRSize__" color="__HRColor__">
<center>《工事アイテム》</center>
<hr size="__HRSize__" color="__HRColor__">

<table class="w680"><tr><td><!--table①-->

<div align="left"><a href="s_seko_document.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜＜　戻る</a></div>

<form action="seko_top_menu.php" name="mainform" method="POST" >
<input type="hidden" name="editSekoFileCD" >
<input type="hidden" name="work" >
<input type="hidden" name="rKey" value="__rKey__">


__IfKanrisyaFlg__
<!--<div align="left"><font color="gray" size="2">※アイホン管理者のみ登録可能</font></div>-->
__IfKanrisyaFlg__
__IfNoKanrisyaFlg__
<div align="left">必要なファイルをダウンロードしてください。</div>
__IfNoKanrisyaFlg__
<br>



<h3>掲示物
__IfKanrisyaFlg__
<div align="right" style="float:right">
	<label for="upfiletmp1">ファイルを登録<input type="file" name="upfile" id="upfiletmp1" style="display:none;"></label></div>
<div style="clear:both">
__IfKanrisyaFlg__
</h3>
<table class="tborder max left">
<tr style="color:#ffffff" bgcolor="#4169E1">
	<td>ファイル名</td>
	<td width="150px">日付</td>
	__IfKanrisyaFlg__<td width="50px">削除</td>__IfKanrisyaFlg__
</tr>
__FileLoop1__
<tr __IfColor__ bgcolor="mintcream" __IfColor__>
	<td>__FileLink1__</td>
	<td><font size="2">__Created1__</font></td>
	__IfKanrisyaDelFlg1__<td align="center"><a href="#" onclick="javascript:moveWithFileCDWithWorkDel('seko_contents.php', __SekoFileCD1__, 2)"><input type="button" class="gomi" value="削除"></a></td>__IfKanrisyaDelFlg1__
</tr>
__FileLoop1__
</table>




<br><br>
<h3>労務安全書類
__IfKanrisyaFlg__
<div align="right" style="float:right">
	<label for="upfiletmp2">ファイルを登録<input type="file" name="upfile" id="upfiletmp2" style="display:none;"></label></div>
<div style="clear:both">
__IfKanrisyaFlg__
</h3>
<table class="tborder max left">
<tr style="color:#ffffff" bgcolor="#4169E1">
	<td>ファイル名</td>
	<td width="150px">日付</td>
	__IfKanrisyaFlg__<td width="50px">削除</td>__IfKanrisyaFlg__
</tr>
__FileLoop2__
<tr __IfColor__ bgcolor="mintcream" __IfColor__>
	<td>__FileLink2__</td>
	<td><font size="2">__Created2__</font></td>
	__IfKanrisyaDelFlg2__<td align="center"><a href="#" onclick="javascript:moveWithFileCDWithWorkDel('seko_contents.php', __SekoFileCD2__, 2)"><input type="button" class="gomi" value="削除"></a></td>__IfKanrisyaDelFlg2__
</tr>
__FileLoop2__
</table>




<br><br>
<h3>その他標準
__IfKanrisyaFlg__
<div align="right" style="float:right">
	<label for="upfiletmp3">ファイルを登録<input type="file" name="upfile" id="upfiletmp3" style="display:none;"></label></div>
<div style="clear:both">
__IfKanrisyaFlg__
</h3>
<table class="tborder max left">
<tr style="color:#ffffff" bgcolor="#4169E1">
	<td>ファイル名</td>
	<td width="150px">日付</td>
	__IfKanrisyaFlg__<td width="50px">削除</td>__IfKanrisyaFlg__
</tr>
__FileLoop3__
<tr __IfColor__ bgcolor="mintcream" __IfColor__>
	<td>__FileLink3__</td>
	<td><font size="2">__Created3__</font></td>
	__IfKanrisyaDelFlg3__<td align="center"><a href="#" onclick="javascript:moveWithFileCDWithWorkDel('seko_contents.php', __SekoFileCD3__, 2)"><input type="button" class="gomi" value="削除"></a></td>__IfKanrisyaDelFlg3__
</tr>
__FileLoop3__
</table>




<br><br>
<h3>消耗品購入ラインナップ
__IfKanrisyaFlg__
<div align="right" style="float:right">
	<label for="upfiletmp4">ファイルを登録<input type="file" name="upfile" id="upfiletmp4" style="display:none;"></label></div>
<div style="clear:both">
__IfKanrisyaFlg__
</h3>
<table class="tborder max left">
<tr style="color:#ffffff" bgcolor="#4169E1">
	<td>ファイル名</td>
	<td width="150px">日付</td>
	__IfKanrisyaFlg__<td width="50px">削除</td>__IfKanrisyaFlg__
</tr>
__FileLoop4__
<tr __IfColor__ bgcolor="mintcream" __IfColor__>
	<td>__FileLink4__</td>
	<td><font size="2">__Created4__</font></td>
	__IfKanrisyaDelFlg4__<td align="center"><a href="#" onclick="javascript:moveWithFileCDWithWorkDel('seko_contents.php', __SekoFileCD4__, 2)"><input type="button" class="gomi" value="削除"></a></td>__IfKanrisyaDelFlg4__
</tr>
__FileLoop4__
</table>




</form>

<!--<br><br>
<div style="border:#ff0000 solid 1px;">
--メモ　テンプレートファイル登録例--<br>
・現場調査シート<br>
・工事車両看板<br>
・建設業許可証<br>
・労災保険関係成立票<br>
・工事完了確認書<br>
・不在通知書<br>
</div>-->


</td></tr></table><!--table①-->


<br>
<hr size="__HRSize__" color="__HRColor__">
<a href="seko_top_menu.php?rKey=__rKey__">トップ</a><br>
<hr size="__HRSize__" color="__HRColor__">
__SFooter__
__SCopyright__
</body>
</html>

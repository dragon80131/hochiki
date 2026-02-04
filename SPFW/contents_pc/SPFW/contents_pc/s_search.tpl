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

	<script>
		$(function () {
			$('input:checkbox[name="allchk"]').change(function () {
				var prop = $('#allchk').prop('checked');
				if (prop) {
					$('input:checkbox[name="wSitenCD[]"]').prop('checked', true);
				} else {
					$('input:checkbox[name="wSitenCD[]"]').prop('checked', false);
				}
			});

			$('.search_conditions').keypress(function (e) {
				if (e.which == 13) {
					kensaku('s_search.php?rKey=__rKey__');
					return false;
				}
			});
		});


		// 次ページへ
		function kensaku(val) {
			// hidden追加
			var ele = document.createElement('input');
			ele.setAttribute('type', 'hidden');
			ele.setAttribute('name', 'KensakuDisp');
			ele.setAttribute('value', '1');
			document.mainform.appendChild(ele);

			window.document.mainform.action = val + "#bukkensearch";
			window.document.mainform.target = "_self";
			window.document.mainform.method = "POST";
			window.document.mainform.submit();
		}

		function deleteBukken(page, editBukkenCD) {

			if (window.confirm('物件を削除しますか？')) {
				document.mainform.deleteFlg.value = true;
				document.mainform.editBukkenCD.value = editBukkenCD;
				document.mainform.action = page;

				document.mainform.submit();
			}
		}
	</script>
</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
	__SHeader__

	<div class="content-all">
		<!--content-all-->

		<hr size="__HRSize__" color="__HRColor__">
		<center>《リニューアル支援》</center>
		<hr size="__HRSize__" color="__HRColor__">


		<div class="left-yose">
			<font size="2" color="gray">ログインユーザ：__LastName__</font>
		</div>

		<div class="top-news left-yose">
			<h5>__IfSystemNespeUser__<a href="./noticememo.php?rKey=__rKey__"><b>◆</a>__IfSystemNespeUser__お知らせ</b></h5>
			__NoticeLoop__
			■__wUpdated__
			<div class="top-news-memo">
				__Memo__<br><br>
			</div>
			__NoticeLoop__
		</div>
		<br>
		<hr size="__HRSize__" color="__HRColor__">

		<form action="s_search.php?rKey=__rKey__" name="mainform" method="POST">
			<input type="hidden" name="editBukkenCD" value="">
			<input type="hidden" name="rKey" value="__rKey__">
			<input type="hidden" name="Extra1" value="__Extra1__">
			<input type="hidden" name="deleteFlg" value="">


			<div class="left-yose" id="bukkensearch">
				<h5>◆物件一覧</h5>
				<!--<p><font color="red">※新規物件登録は、検索後可能です。</font></p>-->
				__IfError__<br>
				<font color="red">検索条件を入力してください。</font>__IfError__
				<table class="table table-bordered table-sm">
					<!--
						<tr><th class="bw">__KENMEINO__</th>
						<td colspan="3"><input type="text" name="wKenmeiNo" class="search_conditions" value="__wKenmeiNo__" style="width:150px;"></td></tr>
						-->
					<tr>
						<th class="bw">物件CD</th>
						<td colspan="3"><input type="text" name="wBukkenCD" class="search_conditions" value="__wBukkenCD__" style="width:100px;"></td>
					</tr>
					<tr>
						<th class="bw">物件名</th>
						<td colspan="3"><input type="text" name="wBukkenName" value="__wBukkenName__" class="search_conditions" style="width:400px;"></td>
					</tr>
					<tr>
						<th class="bw">住所</th>
						<td><input type="text" name="wAddress" value="__wAddress__" style="width:200px;"></td>
					</tr>
					<tr>
						<th class="bw">物件メモ</th>
						<td><input type="text" name="wBukkenMemo" value="__wBukkenMemo__" style="width:200px;"></td>
					</tr>
					__IfNespe__
					<tr>
						<th class="bw">会社</th>
						<td><input type="text" name="wCompany" value="__wCompany__" style="width:200px;"></td>
					</tr>
					__IfNespe__
				</table>

				<button type="button" class="btn btn-primary" onclick="kensaku('s_search.php?rKey=__rKey__')">　検索　</button>
				<button type="button" class="btn btn-default" onclick="javascript:move('s_search.php?rKey=__rKey__')">リセット</button>
			</div>

			<hr>

			<a href="s_form_sinki.php?rKey=__rKey__" style="color:red">＞＞＞　新規工事登録はこちら</a><br>

			__IfSearch__
			<!--検索結果表示-->

			<div class="left-yose" style="float:left">
				<h5>◇検索結果</h5>
				__IfNoResults__
				データがありません
				__IfNoResults__
			</div>

			<div align="right" style="font-size:x-large;">
				<!--<a href="s_form_sinki.php?rKey=__rKey__" style="color:red">＞＞＞　新規物件登録はこちら</a><br>-->
			</div>

			<div style="clear:both"></div>

			<table class="table table-bordered table-striped table-sm">
				<tr>
					<th class="bw">物件CD</th>
					<th class="bw">物件名</th>
					<th class="bw">依頼状況</th>
					__IfNespe__
					<th class="bw">会社名</th>
					__IfNespe__

					__IfGyosya__
					<th class="bw">全体工期</th>
					__IfGyosya__
					<th class="bw">物件メモ</th>
					<th class="bw">削除</th>
				</tr>

				__BukkenLoop__
				<tr>
					<td id="__BukkenCD__">__BukkenCD__</td>
					<td><a href="#" onclick="javascript:moveWithKey('s_menu.php?rKey=__rKey__',__BukkenCD__ )">__BukkenName__</a></td>
					<td>__IraiRenkeiStatus__</td>
					<td>__GyosyaDisp__</td>
					<td>__BukkenMemo__</td>
					<td><a href="#" onclick="javascript:deleteBukken('s_search.php?rKey=__rKey__',__BukkenCD__)">__IfNotAlreadyCooperate__ 削除 __IfNotAlreadyCooperate__</a></td>
				</tr>
				__BukkenLoop__
			</table>

			<div class="left-yose" style="float:left">
				__IfToTop__<a href="#" onClick="javascript:changePage('s_search.php?rKey=__rKey__&KensakuDisp=1', 1, __AllPages__)">__IfToTop__&lt;&lt;__IfToTop__</a>__IfToTop__　
				__IfToPre__<a href="#" onClick="javascript:changePage('s_search.php?rKey=__rKey__&KensakuDisp=1', __PreviousPage__, __AllPages__)">__IfToPre__&lt;__IfToPre__</a>__IfToPre__　
				<input type="text" name="myPage" value="__myPage__" size="4" __IME_OFF__ class="form">/__AllPages__
				<input type="button" value="ページジャンプ" class="button" onclick="javascript:changePage('s_search.php?rKey=__rKey__&KensakuDisp=1', '0', __AllPages__)">　
				__IfToNext__<a href="#" onClick="javascript:changePage('s_search.php?rKey=__rKey__&KensakuDisp=1', __NextPage__, __AllPages__)">__IfToNext__&gt;__IfToNext__</a>__IfToNext__　
				__IfToLast__<a href="#" onClick="javascript:changePage('s_search.php?rKey=__rKey__&KensakuDisp=1', __LastPage__, __AllPages__)">__IfToLast__&gt;&gt;__IfToLast__</a>__IfToLast__
			</div>

			<div align="right">
				<!--<button type="button" class="btn btn-success" onclick="javascript:move('s_search_kekkaCSV.php?rKey=__rKey__')">検索結果をDownload</button>-->
			</div>

			__IfNespe__
			<div align="right">
				<button type="button" class="btn btn-success" onclick="javascript:move('./sch/s_plan.php?rKey=__rKey__')">人工表</button>

			</div>
			__IfNespe__


			<div style="clear:both"></div>

			__IfSearch__
			<!--検索結果表示-->

		</form>


		<hr size="__HRSize__" color="__HRColor__">


		<div class="top-menu left-yose">
			<h5>◆マスターメンテナンス</h5>
			<table class="table table-borderless table-sm table-kintou">
				__IfNespe__
				<tr>
					<td>・<a href="s_system_list.php?rKey=__rKey__">システム名称管理（ネスペのみ）</a></td>
				</tr>
				<tr>
					<td>・<a href="s_device_list.php?rKey=__rKey__">機器・オプション管理（ネスペのみ）</a></td>
				</tr>
				<tr>
					<td>・<a href="s_gyosya_list.php?rKey=__rKey__">業者管理（ネスペのみ）</a></td>
				</tr>
				__IfNespe__
				<tr>
					<td>・<a href="s_gyosyatanto_list.php?rKey=__rKey__">ユーザー管理</a></td>
				</tr>
				<!--20210210未使用<tr><td>・<a href="s_tanto_list.php?rKey=__rKey__">ユーザー管理</a></td></tr>-->
			</table>
		</div>

		<br>

		<!--
		__IfSystemUser__
		<div class="top-menu left-yose">
			<h5>◆管理者用</h5>
			<table class="table table-borderless table-sm table-kintou">
			<tr><td>・<a href="s_matome.php?rKey=__rKey__">利用状況（管理者のみ）</a></td>
				<td>・<a href="s_matome_irai.php?rKey=__rKey__">依頼状況（管理者のみ）</a></td></tr>
			<tr><td>・<a href="s_motouke.php?rKey=__rKey__" >元請け物件（管理者のみ）</a></td></tr>
			__IfSystemNespeUser__
			<tr><td>・<a href="s_accessdevice_list.php?rKey=__rKey__">アプリ端末登録（ネスペのみ）</a></td></tr>
		-->
		<!--<tr><td>・<a href="test001.html?rKey=__rKey__" target="_blank">☆map30k　１（ネスペのみ）</a></td>
			<td>・<a href="test002.html?rKey=__rKey__" target="_blank">☆map30k　２（ネスペのみ）</a></td></tr>-->
		<!--これはいらない-->
		<!--
			__IfSystemNespeUser__
			</table>
		</div>
		__IfSystemUser__
		-->

		<hr size="__HRSize__" color="__HRColor__">
		<a href="logout.php__QUERY__">ログアウト</a><br>
		<hr size="__HRSize__" color="__HRColor__">
		__SFooter__
		__SCopyright__


	</div>
	<!--content-all-->

</body>
</html>

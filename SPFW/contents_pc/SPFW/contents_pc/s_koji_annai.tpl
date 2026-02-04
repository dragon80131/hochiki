<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>リニューアル支援</title>

	<!-- BootstrapのCSS読み込み -->
	<link href="../include/bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<!-- jQuery読み込み -->
	<script src="../include/js/jquery-3.2.1.min.js"></script>

	<!-- BootstrapのJS読み込み -->
	<script src="../include/bootstrap/js/bootstrap.min.js"></script>

	<link rel="stylesheet" type="text/css" href="../css/rnsien.css">
	<script type="text/javascript" src="../tools.js"></script>


	<script src="../js/jquery-1.4.2.js" type="text/javascript"></script>
	<script src="../js/jquery.ui.core.js" type="text/javascript"></script>
	<script src="../js/jquery.ui.datepicker.js" type="text/javascript"></script>
	<script src="../js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
	<script src="../js/jquery.numberPicker.js" type="text/javascript"></script>


	<script type="text/javascript">
		function initOnload() {
			changeDispCompanyBottom();
			changeDispCompanyBottom3();
			changeShiteiVest();
		}
		$(function () {
			$(".datepicker").datepicker({
				numberOfMonths: 2
			});
		});

		// 「工事に関するお問い合わせ先」変更時
		function changeDispCompanyBottom() {
			if (document.getElementsByName("DispCompanyBottomChk1")[0].checked) {
				document.getElementById("DispCompanyBottom1Font").style.color = "black";
				document.getElementsByName("DispCompanyBottom1FLG")[0].disabled = false;
			} else {
				document.getElementById("DispCompanyBottom1Font").style.color = "lightgray";
				document.getElementsByName("DispCompanyBottom1FLG")[0].disabled = true;
			}
			if (document.getElementsByName("DispCompanyBottomChk2")[0].checked) {
				document.getElementById("DispCompanyBottom2Font").style.color = "black";
				document.getElementsByName("DispCompanyBottom2FLG")[0].disabled = false;
			} else {
				document.getElementById("DispCompanyBottom2Font").style.color = "lightgray";
				document.getElementsByName("DispCompanyBottom2FLG")[0].disabled = true;
			}
			if (document.getElementsByName("DispCompanyBottomChk3")[0].checked) {
				document.getElementById("DispCompanyBottom3Font").style.color = "black";
				document.getElementsByName("DispCompanyBottom3FLG")[0].disabled = false;
				document.getElementsByName("DispCompanyBottom3")[0].disabled = false;
				document.getElementsByName("DispCompanyBottomTEL3")[0].disabled = false;
				changeDispCompanyBottom3();
			} else {
				document.getElementById("DispCompanyBottom3Font").style.color = "lightgray";
				document.getElementsByName("DispCompanyBottom3FLG")[0].disabled = true;
				document.getElementsByName("DispCompanyBottom3")[0].disabled = true;
				document.getElementsByName("DispCompanyBottomTEL3")[0].disabled = true;
			}
		}
		// 「工事に関するお問い合わせ先」[その他][する]変更時
		function changeDispCompanyBottom3() {
			if (document.getElementsByName("DispCompanyBottom3FLG")[0].checked) {
				document.getElementsByName("DispCompanyBottomTEL3")[0].disabled = false;
			} else {
				document.getElementsByName("DispCompanyBottomTEL3")[0].disabled = true;
			}
		}
		// 施工主体指定の着用着
		function changeShiteiVest() {
			if (document.getElementsByName("wShiteiVest")[0].selectedIndex == 8) {
				document.getElementsByName("ShiteiVestOther")[0].disabled = false;
				document.getElementsByName("ShiteiVestOther")[0].value = "__ShiteiVestOther__";
				document.getElementById("ShiteiVestOtherFont").style.color = "black";
			} else {
				document.getElementsByName("ShiteiVestOther")[0].disabled = true;
				document.getElementsByName("ShiteiVestOther")[0].value = "";
				document.getElementById("ShiteiVestOtherFont").style.color = "lightgray";
			}
		}
		//submit前の入力チェック
		function checkInput() {

			var flg = true;

			//工事に関するお問い合わせ先
			/*	if(document.getElementsByName("DispCompanyBottomChk1")[0].checked
					|| document.getElementsByName("DispCompanyBottomChk2")[0].checked
						|| document.getElementsByName("DispCompanyBottomChk3")[0].checked){
					flg = true;
				}else{
					alert("工事に関するお問い合わせ先を一つ以上選択してください");
					return false;
				}
			*/

			//問合せ先　その他を表示するにチェックがある場合
			if (document.getElementsByName("DispCompanyBottomChk3")[0].checked) {
				alertStr = "工事に関するお問い合わせ先\nその他:";
				//会社名に入力があるかチェック
				if (document.getElementsByName("DispCompanyBottom3")[0].value == "") {
					alertStr += " <会社名> ";
					flg = false;
				}

				//電話番号がONなら　入力があるかチェック
				if (document.getElementsByName("DispCompanyBottom3FLG")[0].checked) {
					if (document.getElementsByName("DispCompanyBottomTEL3")[0].value == "") {
						alertStr += " <電話番号> ";
						flg = false;
					}
				}
				if (flg == false) {
					alert(alertStr + " を入力して下さい");
					return false;
				}
			}

			if (document.getElementsByName("wSagyoin[]")[0].checked ||
				document.getElementsByName("wSagyoin[]")[1].checked) {
				flg = true;
			} else {
				alert("作業員の着用するものは一つ以上を選択してください");
				return false;
			}

			return true;
		}
	</script>
	<style>
		p.list {
			margin-bottom: 5px;
		}
	</style>
</head>

<body onload="initOnload();">
	__SHeader__

	<div class="content-all">
		<!--content-all-->

		<div class="left-yose">
			<a href="../s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜戻る</a>
		</div>


		<div class="top-menu left-yose">
			<h5>__wBukkenName__</h5>
			<h6>工事案内作成</h6>
		</div>


		<!-- <input type="hidden" name="rKey" value="__rKey__">
				<input type="hidden" name="editBukkenCD" value="__editBukkenCD__"> -->

		<!-- ◆工事案内資料 記載情報（工事基本情報の補足）
				<table class="table table-bordered table-sm">
					<tr>
						<th class="yb">資料右上に記載する会社</th>
						<td>
							<font size="2" color="gray">※資料には上から順に出力されます</font>
							<p class="list">①　<input type="text" name="DispCompanyTop1" value="__DispCompanyTop1__" style="width:350px;"></p>
							<p class="list">②　<input type="text" name="DispCompanyTop2" value="__DispCompanyTop2__" style="width:350px;"></p>
							<p class="list">③　<input type="text" name="DispCompanyTop3" value="__DispCompanyTop3__" style="width:350px;"></p>
						</td>
					</tr>

					<tr>
						<th class="yb">全体工期</th>
						<td>
							<input type="text" name="ZentaiStartDate" value="__ZentaiStartDate__" style="width:100px;" class="datepicker"></p>
							<input type="text" name="ZentaiEndDate" value="__ZentaiEndDate__" style="width:100px;" class="datepicker"></p>
						</td>
					</tr> -->


		<!-- <tr>
						<th class="yb">資料下部<br>工事に関するお問い合わせ先に<br>記載する会社</th>
						<td>
							<p class="list"><input type="checkbox" name="DispCompanyBottomChk1" value="1" __DispCompanyChecked1__ onclick="changeDispCompanyBottom();">管理会社</p>
							<p class="list">
								<span id="DispCompanyBottom1Font" style="color:lightgray;">
									（　電話番号を記載　
									<input type="radio" name="DispCompanyBottom1FLG" value="1" __DispCompanyBottom1FLGchecked1__>する　
									<input type="radio" name="DispCompanyBottom1FLG" value="0" __DispCompanyBottom1FLGchecked0__>しない　
									）
								</span>
							</p>
							<p class="list"><input type="checkbox" name="DispCompanyBottomChk2" value="1" __DispCompanyChecked2__ onclick="changeDispCompanyBottom();">__GyosyaName__</p>
							<p class="list">
								<span id="DispCompanyBottom2Font" style="color:lightgray;">
									（　電話番号を記載　
									<input type="radio" name="DispCompanyBottom2FLG" value="1" __DispCompanyBottom2FLGchecked1__>する　
									<input type="radio" name="DispCompanyBottom2FLG" value="0" __DispCompanyBottom2FLGchecked0__>しない　
									）
								</span>
							</p>
							<p class="list"><input type="checkbox" name="DispCompanyBottomChk3" value="1" __DispCompanyChecked3__ onclick="changeDispCompanyBottom();">その他
								<input type="text" name="DispCompanyBottom3" value="__DispCompanyBottom3__" style="width:400px">
							</p>
							<p class="list">
								<span id="DispCompanyBottom3Font" style="color:lightgray;">
									（　電話番号を記載　
									<input type="radio" name="DispCompanyBottom3FLG" value="1" __DispCompanyBottom3FLGchecked1__ onChange="changeDispCompanyBottom3();">する
									TEL:<input type="text" name="DispCompanyBottomTEL3" value="__DispCompanyBottomTEL3__" style="width:150px">　
									<input type="radio" name="DispCompanyBottom3FLG" value="0" __DispCompanyBottom3FLGchecked0__ onChange="changeDispCompanyBottom3();">しない　
									）
								</span>
							</p>
						</td>
					</tr> -->
		<!-- <tr>
					<th class="yb">作業員着用</th>
					<td>
						<font size="2" color="gray">※着用するものを選択してください</font><br>
						<input type="checkbox" name="wSagyoin[]" value="ベスト" __wSagyoinChecked1__>ベスト　
						<input type="checkbox" name="wSagyoin[]" value="腕章" __wSagyoinChecked2__>腕章　　
					</td>
				</tr <tr>
				<th class="yb">施工主体指定の着用着</th>
				<td>
					<font size="2" color="gray">※選択した会社の画像が表示されます。</font><br>
					<select name="wShiteiVest" onChange="changeShiteiVest();">
						__ShiteiVestLoop__<option value="__ShiteiVestCD__" __SelectedShiteiVest__>__ShiteiVestName__</option>__ShiteiVestLoop__
					</select>
					<br>
					<span id="ShiteiVestOtherFont" style="color:lightgray;">
						<font size="2">※その他の場合は組織名を入力</font><br>
						<input type="text" name="ShiteiVestOther" value="__ShiteiVestOther__" style="width:250px;">
					</span>
				</td>
				</tr>
				<table> -->
		<!--20200417
作成する案内状の種類を選択してください。<br>
<input type="radio" name="wAnnaijyoType" value="0" checked>通常版
<input type="radio" name="wAnnaijyoType" value="1" >簡易版
<br><br>-->

		<!--<input type="submit" value="登録＆ファイル出力" class="btn btn-primary"　>-->
		<div>
			<form action="s_koji_annai_Excel.php" method="POST" name="mainform" onSubmit="return checkInput()">
				<input type="hidden" name="rKey" value="__rKey__">
				<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
				<br>
				<div>
					<div>
						<button type="button" class="btn btn-primary" onclick="javascript:move('./s_koji_annai_Excel_v3.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__' )">工事案内</button>
						<button type="button" class="btn btn-primary" onclick="javascript:move('./s_koji_annai_Excel_v3_en.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__' )">工事案内(英語版)</button>
					</div>
					<br>
					<button type="button" class="btn btn-primary" onclick="javascript:move('./s_koji_annai_yotei_kakutei_Excel_v2.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__' )">予定・確定サンプル</button>
				</div>
			</form>
			<br>
		</div>
		<hr>
		<input type="button" value="メニューへもどる" onclick="javascript:move('../s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__' )" class="btn btn-info"><br>
		<!-- <br><br><br><br><br><br><br><br><br><br><br>
		**************************************************<br>
		2020/04/21<br>
		以前の1シート仕様の工事案内はこちらから出力可能です。<br>
		<button type="button" class="btn btn-primary" onclick="javascript:move('./s_koji_annai_Excel.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__' )">工事案内出力(1seetVer)</button> -->
		<!--content-all-->
		__SFooter__
		__SCopyright__

</body>
</html>

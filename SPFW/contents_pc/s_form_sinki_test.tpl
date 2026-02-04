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
	<script type="text/javascript" src="js/ConnectedSelect.js"></script>

	<!--datepicker-->
	<link href="css/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
	<link href="css/jquery-ui-1.12.1/jquery-ui.structure.min.css" rel="stylesheet">
	<link href="css/jquery-ui-1.12.1/jquery-ui.theme.min.css" rel="stylesheet">
	<script src="js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script src="js/jquery-ui/datepicker-ja.js"></script>

	<!--select2-->
	<link href="css/select2.css" type="text/css" rel="stylesheet">
	<script src="js/select2.min.js" type="text/javascript"></script>

	<script type="text/javascript">
		$(function () {
			$("#wChosaDate").datepicker({});
		});
	</script>
	<style>
		/* 点滅 */
		.blinking {
			-webkit-animation: blink 1.5s ease-in-out infinite alternate;
			-moz-animation: blink 1.5s ease-in-out infinite alternate;
			animation: blink 1.5s ease-in-out infinite alternate;
		}

		@-webkit-keyframes blink {
			0% {
				opacity: 0;
			}

			100% {
				opacity: 1;
			}
		}

		@-moz-keyframes blink {
			0% {
				opacity: 0;
			}

			100% {
				opacity: 1;
			}
		}

		@keyframes blink {
			0% {
				opacity: 0;
			}

			100% {
				opacity: 1;
			}
		}
	</style>
	<!--s_kihon_formから移植-->
	<!--datepicker-->
	<link href="css/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
	<link href="css/jquery-ui-1.12.1/jquery-ui.structure.min.css" rel="stylesheet">
	<link href="css/jquery-ui-1.12.1/jquery-ui.theme.min.css" rel="stylesheet">
	<script src="js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script src="js/jquery-ui/datepicker-ja.js"></script>

	<script>
		$(function () {
			$(".datepicker").datepicker({
				numberOfMonths: 2
			});

			$(".wHoliday").datepicker({
				numberOfMonths: 1,
				minDate: new Date($('input:text[name="wSenyuStartDate"]').val()),
				maxDate: new Date($('input:text[name="wSenyuEndDate"]').val())
			});

			var dates = jQuery(".senyubucls, .wHoliday").datepicker({
				numberOfMonths: 1,
				onSelect: function (selectedDate) {
					var option = this.id == 'senyubustart' ? 'minDate' : 'maxDate',
						instance = $(this).data('datepicker'),
						date = $.datepicker.parseDate(
							instance.settings.dateFormat ||
							$.datepicker._defaults.dateFormat,
							selectedDate, instance.settings);
					dates.not(this).datepicker('option', option, date);
				}
			});
		});

		function initOnload() {
			changeShoboTokurei();
			changeKirikaeHeiko();
			setOption();
			changeShiharaiConveni();
			changeRNNyukan();
			changeAnsho();
		}
		// 「消防特例」[220号共同住宅用]選択時に「火災警報試験」を有効にする
		function changeShoboTokurei() {
			if (document.getElementsByName("wShoboTokurei")[3].checked) {
				$(".wShoboSikenhoho").removeAttr("disabled");
				$("#wShoboTokurei220Font").css("color", "black");
				$("#wShoboTokurei220FontTH").css("color", "black");
			} else {
				$(".wShoboSikenhoho").attr("disabled", "disabled");
				$("#wShoboTokurei220Font").css("color", "lightgray");
				$("#wShoboTokurei220FontTH").css("color", "lightgray");
			}
		}
		// 「切替方法」[並行稼働]選択時
		function changeKirikaeHeiko() {
			if (document.getElementsByName("wKirikaehoho")[1].checked) {
				//ラジオボタンを有効化
				document.getElementsByName("wKirikaeHeikoEizoriyo")[0].disabled = false;
				document.getElementsByName("wKirikaeHeikoEizoriyo")[1].disabled = false;
				document.getElementById("KirikaeHeikoEizoriyoFont").style.color = "black";
			} else {
				document.getElementsByName("wKirikaeHeikoEizoriyo")[0].disabled = true;
				document.getElementsByName("wKirikaeHeikoEizoriyo")[1].disabled = true;
				document.getElementById("KirikaeHeikoEizoriyoFont").style.color = "lightgray";
			}
		}
		// オプション申し込み有無(これは読み込み時のみ使う)
		function setOption() {
			var max_no = document.getElementById('maxno_op_nondisp').innerHTML;
			if (max_no > 0) { // オプションがあれば
				document.getElementById("Shiharai_id").style.color = "black"; // オプション支払い方法
				document.getElementById("OPUketukei_id").style.color = "black"; // オプション受付方法
				for (let i = 0; i < document.getElementsByName("wShiharai[]").length; i++) { // オプション支払い方法
					document.getElementsByName("wShiharai[]")[i].disabled = false;
				}
				for (let i = 0; i < document.getElementsByName("wOPUketuke").length; i++) { // オプション受付方法
					document.getElementsByName("wOPUketuke")[i].disabled = false;
				}

			} else {
				document.getElementById("Shiharai_id").style.color = "lightgray";
				document.getElementById("OPUketukei_id").style.color = "lightgray";
				for (let i = 0; i < document.getElementsByName("wShiharai[]").length; i++) { // オプション支払い方法
					document.getElementsByName("wShiharai[]")[i].disabled = true;
				}
				for (let i = 0; i < document.getElementsByName("wOPUketuke").length; i++) { // オプション受付方法
					document.getElementsByName("wOPUketuke")[i].disabled = true;
				}
			}
		}
		// 「支払い方法」[コンビニ]選択時
		function changeShiharaiConveni() {
			if (document.getElementsByName("wShiharai[]")[3].checked) {
				//ラジオボタンを有効化
				document.getElementsByName("wShiharaiConveni")[0].disabled = false;
				document.getElementsByName("wShiharaiConveni")[1].disabled = false;
				document.getElementById("wShiharaiConveniFont").style.color = "black";
			} else {
				document.getElementsByName("wShiharaiConveni")[0].disabled = true;
				document.getElementsByName("wShiharaiConveni")[1].disabled = true;
				document.getElementById("wShiharaiConveniFont").style.color = "lightgray";
			}
		}
		// RN後の入館方法 (ノンタッチタグ)
		function changeRNNyukan() {
			if (document.getElementsByName('wRNNyukan[]')[2].checked) {
				document.getElementById("TagSuu_id").style.color = '#000000';
				document.getElementsByName('wTagSuu')[0].disabled = false;
				document.getElementsByName('wOwnerTagSuu')[0].disabled = false;
			} else {
				document.getElementById("TagSuu_id").style.color = '#CCCCCC';
				document.getElementsByName('wTagSuu')[0].disabled = true;
				document.getElementsByName('wOwnerTagSuu')[0].disabled = true;
			}
		}
		// 「工事期間中の暗証番号」選択時
		function changeAnsho() {
			if (document.mainform.wAnshoNo[1].checked) {
				document.getElementById("AnshoNoKojichu_id").style.color = '#000000';
				document.getElementsByName('wAnshoNoKojichu')[0].disabled = false;
			} else {
				document.getElementById("AnshoNoKojichu_id").style.color = '#CCCCCC';
				document.getElementsByName('wAnshoNoKojichu')[0].disabled = true;
			}
		}

		//<!-- 休工日 +1行追加ボタン処理 -->
		$(function () {
			var i = "__maxNo__"; //idカウント用。編集の場合は初期値がloopの数だけある。
			$(document).on("click", "[id='addrow_free']", function (e) {
				var table = document.getElementById('kyukobi_table');
				// 行を行末に追加
				var row = table.insertRow(-1);
				// セルの挿入
				var cell1 = row.insertCell(-1);
				var cell2 = row.insertCell(-1);

				cell1.innerHTML = "<input type='button' value='削除' class='btn btn-info btn-xs' style='margin:5px 0px' onclick='removeList(this)'>";
				cell2.innerHTML = "<input type='text' name='wHoliday[]' value='' class='wHoliday' style='width:120px' >";
				cell2.innerHTML += "　<input type='text' name='wHoliday[]' value='' class='wHoliday' style='width:120px' >";
				cell2.innerHTML += "　<input type='text' name='wHoliday[]' value='' class='wHoliday' style='width:120px' >";

				//商品用の1行追加するボタンをクリックした時
				i = parseInt(i) + 3; //通るたび(ボタン押す度)に+3する→idに名前つけるときに使う

				$(".wHoliday").datepicker({
					numberOfMonths: 1,
					minDate: new Date($('input:text[name="wSenyuStartDate"]').val()),
					maxDate: new Date($('input:text[name="wSenyuEndDate"]').val())
				});
			});
		});

		function removeList(obj) { //行を削除
			// 削除ボタンを押下された行を取得
			var tr = obj.parentNode.parentNode;
			// trのインデックスを取得して行を削除する
			tr.parentNode.deleteRow(tr.sectionRowIndex);
		}


		$(function () {
			// オプション カテゴリ選んだ場合の動作
			// 参考 https://qiita.com/bass-inu/items/8526cf677599c7d9bbb0
			var $children = $('.children'); //子要素を変数に入れます。
			var original = $children.html(); //オリジナルをとっておく

			original = original.replace("selected", " ");
			$('.parent').change(function () {
				var id = $(this).attr('id'); // 親のid
				var val1 = $(this).val(); // 親のvalue

				var tmp = id.substr(11); // 番号のみ取得
				var $kosel = $('#children_sel_' + tmp + ''); // 子のセレクタ
				var $kotext = $('#children_text_' + tmp + ''); // 自由入力のテキストボックス
				var $kospan = $('#children_span_' + tmp + ''); // 子(セレクトボックス)を囲んだspan(自由入力で消す用)

				//削除された要素をもとに戻すため.html(original)を入れておく
				//$children.html(original).find('option').each(function() {
				$kosel.html(original).find('option').each(function () {
					var val2 = $(this).data('val'); //data-valの値を取得

					//valueと異なるdata-valを持つ要素を削除
					if (val1 != val2) {
						$(this).not(':first-child').remove();
					}
				});

				if ($(this).val() == "100") { //自由入力を選択したらテキストボックス表示
					$kotext.css('display', 'block');
					$kospan.css('display', 'none');
				} else { //自由入力じゃない　テキストボックス消す
					$kotext.css('display', 'none');
					$kotext.val("");
					$kospan.css('display', 'block');
				}
				if ($(this).val() == "0") { // 親が未選択なら、子を全表示
					//$children.html(original);
					$kosel.html(original);
				}
			});

			// オプション 1行追加
			$(document).on("click", "[id='addrow_op']", function (e) {
				//Noの最大値を取得
				var max_no = document.getElementById('maxno_op_nondisp').innerHTML;
				//次のNoを生成
				var next_no = parseInt(max_no) + 1;

				if (next_no > 10) {
					alert("これ以上追加できません。");
					return false;
				}

				//tbodyの最初の子供（ダミー行）をコピー
				$("#tbodyID_op > tbody > tr:first").clone(true).appendTo(
					$("#tbodyID_op > tbody")
				);
				//追加した行（最終行）を活性化させる。
				$('#tbodyID_op > tbody > tr:last').css('display', '');
				//追加した行の各テキストボックスのname属性を変更する。
				$('#tbodyID_op > tbody > tr:last > td > input').each(function () {
					var base_name = $(this).attr('name');
					$(this).attr('name', base_name + "_" + next_no);

					var base_id = $(this).attr('id');
					$(this).attr('id', base_id + "_" + next_no);
				});
				$('#tbodyID_op > tbody > tr:last > td > select').each(function () {
					var base_name = $(this).attr('name');
					$(this).attr('name', base_name + "_" + next_no);

					var base_id = $(this).attr('id');
					$(this).attr('id', base_id + "_" + next_no);
				});
				$('#tbodyID_op > tbody > tr:last > td > span').each(function () {
					var base_id = $(this).attr('id');
					$(this).attr('id', base_id + "_" + next_no);
				});
				$('#tbodyID_op > tbody > tr:last > td > span > select').each(function () {
					var base_name = $(this).attr('name');
					$(this).attr('name', base_name + "_" + next_no);

					var base_id = $(this).attr('id');
					$(this).attr('id', base_id + "_" + next_no);
				});

				// 行追加用
				document.getElementById('maxno_op_nondisp').innerHTML = next_no;


				// オプション支払い方法・受付方法
				/*/document.getElementById("Shiharai_id").style.color = "black"; // オプション支払い方法
				document.getElementById("OPUketukei_id").style.color = "black"; // オプション受付方法
				for (let i = 0; i < document.getElementsByName("wShiharai[]").length; i++){ // オプション支払い方法
					document.getElementsByName("wShiharai[]")[i].disabled = false;
				}
				for (let i = 0; i < document.getElementsByName("wOPUketuke").length; i++){ // オプション受付方法
					document.getElementsByName("wOPUketuke")[i].disabled = false;
				}
				*/
			});
		});
	</script>

	<script>
		$(function () {

			//セレクトボックスが切り替わったら発動
			$('input[name = "wKojiShozokuName"]').change(function () {

				//選択したvalue値を変数に格納
				var q = $(this).val();

				$.ajax({
					url: './s_form_sinki_api.php',
					type: 'POST',
					dataType: 'text',
					data: {
						'gyosyacd': q
					}

				}).done(function (data) {
					/* 通信成功時 */
					//gyosyacdに紐づいた会社名を取ってきて貼り付ける。
					$('input[name = "q"]').val(data);
				}).fail(function (data) {
					/* 通信失敗時 */
				});

			});

		});

		//型番自由入力
		$(function () {

			$('input[name = "ziyu"]').change(function () {
				var a = $(this).prop('checked');
				if (a == true) {
					$("#wOyaKataban2").show();
					$("#wOyaKataban").hide();
					$("#wKokiKataban2").show();
					$("#wKokiKataban").hide();
					$("#ziyuflg").val("1");
				} else {
					$("#wOyaKataban2").hide();
					$("#wOyaKataban").show();
					$("#wKokiKataban2").hide();
					$("#wKokiKataban").show();
					$("#ziyuflg").val("");
				}

			});
		});

	</script>

	<style>
		/* 20190709 add */
		p.mintitle {
			font-size: 1.5em;
			position: relative;
			padding: 0.25em 0;
		}

		p.mintitle:after {
			content: "";
			display: block;
			height: 4px;
			background: -webkit-linear-gradient(to right, rgb(230, 90, 90), transparent);
			background: linear-gradient(to right, rgb(230, 90, 90), transparent);
		}

		p.list {
			margin-bottom: 5px;
		}
	</style>

</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__" 　onload="initOnload();">


	<div class="content-all">
		<!--content-all-->

		<div class="top-menu left-yose">
			<h6>新規工事登録</h6>

			<form action="s_finish_sinki_test.php" method="POST" name="mainform">

				<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
				<input type="hidden" name="rKey" value="__rKey__">
				<input type="hidden" name="work" value="1">

				<font color="red">※は入力必要項目です。</font><br>
				<span id="ErrorString" style="color:red"></span>
				__IfError__
				__ErrorLoop__
				<font color="red">__ErrorStrings__<br></font>
				__ErrorLoop__
				__IfError__
				<br>

				<p class="mintitle">工事基本情報</p>
				<!--◆資料に記載する情報-->
				<font size="2" color="gray">　
					<table class="table table-bordered table-sm" style="width:800px">
						<colgroup>
							<col style='width:200px;'>
							<col style=''>
						</colgroup>

						<tr>
							<th class="yb">工事番号</th>
							<td><input type="text" name="wKenmeiNo" value="__wKenmeiNo__"></td>
						</tr>

						<tr>
							<th class="yb">物件名<font color="red">　※</font>
							</th>
							<td><input type="text" name="wBukkenName" value="__wBukkenName__" style="width:400px;" placeholder="例：○○マンション"></td>
						</tr>

						<tr>
							<th class="yb">物件名(ふりがな)</th>
							<td><input type="text" name="wBukkenName_Hurigana" value="__wBukkenName_Hurigana__" style="width:400px;"></td>
						</tr>

						<tr>
							<th class="yb">住所<font color="red">　※</font>
							</th>
							<td><input type="text" name="wAddress" id="Addressid" value="__wAddress__" style="width:400px;"></td>
						</tr>
						<tr>
							<th class="yb">住戸数<font color="red">　※</font>
							</th>
							<td><input type="number" name="wKosu" value="__wKosu__" style="width:80px; ime-mode:disabled;"> 戸</td>
						</tr>
						<tr>
							<th class="yb">階高<font color="red">　※</font>
							</th>
							<td><input type="number" name="wKaidaka" value="__wKaidaka__" style="width:80px; ime-mode:disabled;"> 階</td>
						</tr>
						<tr>
							<th class="yb">会社名<font color="red">　※</font>
							</th>
							<td><input type="search" name="wKojiShozokuName" value="__GyosyaName__" style="width:400px;" autocomplete="on" list="keywords">
								<datalist id="keywords">
									__GyosyaLoop__
									<option value="__GyosyaName2__">__GyosyaCD2__</option>
									__GyosyaLoop__
								</datalist>
								<input type="hidden" name="q" value="__GyosyaCD__">
							</td>
						</tr>
						<tr>
							<th class="yb">会社TEL<font color="red">　※</font>
							</th>
							<td><input type="text" name="wKojiShozokuTEL" value="" style="width:150px;"></td>
						</tr>
						<tr>
							<th class="yb">担当名<font color="red">　※</font>
							</th>
							<td><input type="text" name="wKojiTantoName" value="__LastName__" style="width:300px;"></td>
						</tr>
						<tr>
							<th class="yb">管理会社名</th>
							<td><input type="text" name="wKanriGaisya" value="__wKanriGaisya__" style="width:300px;"></td>
						</tr>
						<tr>
							<th class="yb">オーナー名(賃貸物件の場合)</th>
							<td><input type="text" name="wOwner_name" value="__wOwner_name__" style="width:300px;"></td>
						</tr>
						<tr>
							<th class="yb">物件メモ</th>
							<td><textarea name="wBukkenMemo" rows="3" cols="50">__wBukkenMemo__</textarea></td>
						</tr>

					</table>

					◆工期
					<table class="table table-bordered table-sm" style="width:800px">
						<colgroup>
							<col style='width:200px;'>
							<col style=''>
						</colgroup>
						<tr>
							<th class="yb">共用部</th>
							<td>
								<input type="text" name="wKyoyoStartDate" value="__wKyoyoStartDate__" class="datepicker" style="width:120px;">～
								<input type="text" name="wKyoyoEndDate" value="__wKyoyoEndDate__" class="datepicker" style="width:120px;">
							</td>
						</tr>
						<tr>
							<th class="yb">専有部<font color="red">　※</font>
							</th>
							<td>
								<input type="text" name="wSenyuStartDate" id="senyubustart" value="__wSenyuStartDate__" class="datepicker senyubucls" style="width:120px;">～
								<input type="text" name="wSenyuEndDate" value="__wSenyuEndDate__" class="datepicker senyubucls" style="width:120px;">
							</td>
						</tr>
						<tr>
							<th class="yb">予備日<br>
							※希望があった際は確認なしで変更を承ります。</th>
							<td>
								<input type="text" name="wYobiStartDate" value="__wYobiStartDate__" class="datepicker" style="width:120px">～
								<input type="text" name="wYobiEndDate" value="__wYobiEndDate__" class="datepicker" style="width:120px">
							</td>
						</tr>
						<tr>
							<th class="yb">休工日</th>
							<td>
								<table class="table table-bordered table-sm" id="kyukobi_table">
									<tr>
										<td><input type="button" value="＋1行追加" style="background-color:transparent;" id="addrow_free"></td>
										<td>
											<input type="text" name="wHoliday[]" value="__wHoliday1__" class="wHoliday" style="width:120px">
											　<input type="text" name="wHoliday[]" value="__wHoliday2__" class="wHoliday" style="width:120px">
											　<input type="text" name="wHoliday[]" value="__wHoliday3__" class="wHoliday" style="width:120px">
											__KyukoTable__

								</table>
							</td>
						</tr>
						<tr>
							<th class="yb">工事案内配布日</th>
							<td>
								<input type="text" name="wAnnaiDate" value="__wAnnaiDate__" class="datepicker" style="width:120px">
							</td>
						</tr>
						<tr>
							<th class="yb">受付締切日</th>
							<td>
								<input type="text" name="wReceptionDate" value="__wReceptionDate__" class="datepicker" style="width:120px">
							</td>
						</tr>
					</table>

					◆型番情報
					<label><input type="checkbox" name="ziyu" id="" value="1" class="ziyu">自由入力</label>
					<table class="table table-bordered table-sm" id="" style="width: 800px">
						<colgroup>
							<col style='width:200px;'>
							<col style=''>
						</colgroup>
						<tr>
							<th class="yb">基本システム</th>
							<td><select name="wRNsystem" id="BasicSystems">
									__RNsystemLoop__
									<option value="__RNSYSTEM_Value__" __RNsystemSelected__>__RNSYSTEMNAME__</option>
									__RNsystemLoop__
								</select>
							</td>
						</tr>
						<tr id="wOyaKataban">
							<th class="yb">室内親機品番</th>
							<td>
								<select name="wOyaKataban" id="OyaKataban">
									<option value="">-</option>
									__OyaKatabanLoop__
									<option value="__OyaDeviceKataban__" __OyaDeviceKatabanSelected__>__OyaDeviceKataban__　__OyaDeviceName__</option>
									__OyaKatabanLoop__
								</select>
							</td>
						</tr>

						<tr id="wKokiKataban">
							<th class="yb">玄関子機品番</th>
							<td>
								<select name="wKokiKataban" id="KokiKataban">
									<option value="">-</option>
									__KokiKatabanLoop__
									<option value="__KokiDeviceKataban__" __KokiDeviceKatabanSelected__>__KokiDeviceKataban__　__KokiDeviceName__</option>
									__KokiKatabanLoop__
								</select>
							</td>
						</tr>

						<tr id="wOyaKataban2" style="display:none">
							<th class="yb">室内親機品番(自由入力)</th>
							<td><input type="text" name="wOyaKataban2"></td>
						</tr>

						<tr id="wKokiKataban2" style="display:none">
							<th class="yb">玄関子機品番(自由入力)</th>
							<td><input type="text" name="wKokiKataban2"></td>
						</tr>

						<input type="hidden" id="ziyuflg" name="ziyuflg" value="">

					</table>

					◆オプション工事　
					<p id="maxno_op_nondisp" style="display:none">__maxno_op_nondisp__</p>
					<!--行追加用-->
					<input type="button" value="＋1行追加" style="background-color:transparent;" id="addrow_op">　
					<font size="2" color="gray">※最大10個登録可能</font>
					<table class="table table-bordered table-sm" id="tbodyID_op" style="width: 800px">
						<colgroup>
							<col style='width:100px;'>
							<col style='width:500px;'>
							<col style='width:150px;'>
						</colgroup>
						<!--↓コピー用 非表示 最初の子要素をｺﾋﾟｰ↓-->
						<tr style="display:none">
							<td><button type="button" onClick="removeList(this);" class="btn btn-info btn-xs" style="margin:5px 0px">削除</button></td>
							<td>
								<select name="wCategoryCD" class="parent" id="parent_sel" style="width:180px">
									__CategoryLoop__<option value="__CategoryCD__">__CategoryName__</option>__CategoryLoop__
								</select>
								<span id="children_span">
									<select name="wOPDeviceCD" class="children" id="children_sel" style="width:400px">
										<option value="">-</option>
										__OPDeviceLoop__<option value="__OPDeviceCD__" data-val="__OPCategoryCD__">__OPCategoryName__ - __OPKataban__ - __OPDeviceName__ </option>__OPDeviceLoop__
									</select>
								</span>
								<input type="text" name="wOPZiyuu" value="__wOPZiyuu__" id="children_text" style="width:400px; display:none" placeholder="オプションを入力してください">
							</td>
							<td>￥<input type="numer" name="wOPPrice" style="width:80px; ime-mode:disabled;"></td>
						</tr>
						<!--↑コピー用 非表示 最初の子要素をｺﾋﾟｰ↑-->
						<tr>
							<th class="yb">削除</th>
							<th class="yb">名称</th>
							<th class="yb">価格（税込）</th>
						</tr>
						__OPLoop__ __table_op__ __OPLoop__
					</table>

					<br>

					<input type="button" value="登録する" onClick="required_check(2)" class="btn btn-primary blue">
					<input type="hidden" name="touroku_btn" id="touroku_btn" value="">
			</form>

			<br>
			<a href="s_search.php__QUERY__" class="btn btn-info" style="margin-top:20px;">トップへ</a>
		</div>
	</div>
	<!--content-all-->

	__SFooter__
	__SCopyright__

	<script>
		// 必須項目チェック（1:一時保存 2:登録）
		function required_check(val) {

			var html = [];


			//if (!chk_input("wKenmeiNo")) html.push("管理Noが入力されていません。");
			if (!chk_input("wBukkenName")) html.push("物件名が入力されていません。");

			if (html.length > 0) {
				document.getElementById('ErrorString').innerHTML = "";
				for (let i = 0; i < html.length; i++) {
					document.getElementById('ErrorString').innerHTML += html[i] + "<br>";
				}
				window.scrollTo(0, 0);
			} else {
				// エラーなし かつ 一時保存ボタン
				if (html.length == 0 && val == 1) {
					//document.mainform.action = "s_kihon_finish.php";
					document.mainform.action = "s_finish_sinki.php";
					document.mainform.submit(true);

				} else if (val == 2) { // 登録ボタン
					form_check(val);
				}
			}
		}

		// 登録時必須項目チェック（2:登録）
		function form_check(val) {

			var html = [];
			/*
			if (!chk_input("wTantoCD")) html.push("担当者が選択されていません。");
			if (!chk_input("wKojiName")) html.push("工事名称が入力されていません。");
			*/
			if (!chk_input("wKosu")) html.push("住戸数が入力されていません。");
			if (!chk_input("wKaidaka")) html.push("階高が入力されていません。");
			/*
			if (!chk_checked("wSekoShutaiOP")) html.push("施工主体が選択されていません。");
			if (!chk_checked("wKanrisitu")) html.push("管理室親機が選択されていません。");
			if (!chk_checked("wAutoLock")) html.push("集合玄関機が選択されていません。");
			if (!chk_checked("wShoboTokurei")) html.push("消防特例が選択されていません。");
			if (document.getElementsByName("wShoboTokurei")[3].checked) { // 消防特例:[220号共同住宅用]を選択時のみ
				if (!chk_checked("wShoboSikenhoho")) html.push("火災警報試験が選択されていません。");
			}
			if (!chk_checked("wJikaho")) html.push("自火報連動が選択されていません。");
			if (!chk_checked("wKasaiHeya")) html.push("火災抵抗器交換部屋立入りが選択されていません。");
			*/

			if (!chk_input("wKojiShozokuName")) html.push("会社名が入力されていません。");
			if (!chk_input("wKojiShozokuTEL")) html.push("会社TELが入力されていません。");

			if (!chk_input("wKojiTantoName")) html.push("担当名が入力されていません。");

			//if (!chk_input("wZentaiStartDate") || !chk_input("wZentaiEndDate")) html.push("全体工期が入力されていません。");

			if (!chk_input("wSenyuStartDate") || !chk_input("wSenyuEndDate")) html.push("専有部工期が入力されていません。");
			/*
			if (!chk_input("wConstTime")) html.push("案内資料上の作業時間が入力されていません。");
			/*
			if (!chk_checked("wKirikaehoho")) html.push("切替方法が選択されていません。");
			if (!chk_checked("wKansenKoji")) html.push("幹線ルートが選択されていません。");
			if (!chk_checked("wGasKoji")) html.push("ガス漏れ警報器連動が選択されていません。");
			if (!chk_checked("wBohanKoji")) html.push("防犯センサー連動が選択されていません。");
			if (!chk_checked("wRosuiKoji")) html.push("漏水センサー連動が選択されていません。");
			if (!chk_checked("wTakuhai")) html.push("宅配連動が選択されていません。");
			*/
			/*
			if (!chk_checked("wCurrentNyukan[]")) {
				if (!chk_input("wCurrentNyukanSonota")) html.push("施工前の入館方法が選択・入力されていません。");
			}
			if (!chk_checked("wRNNyukan[]")) {
				if (!chk_input("wRNNyukanSonota")) html.push("施工後の入館方法が選択・入力されていません。");
			}
			*/
			/*
			if (!chk_input("wAnswer")) html.push("専有部工事日時変更受付方法が選択されていません。");
			if (!chk_checked("wWEBRecept")) html.push("WEB受付が選択されていません。");
			*/
			if (html.length > 0) {
				document.getElementById('ErrorString').innerHTML = "";
				for (let i = 0; i < html.length; i++) {
					document.getElementById('ErrorString').innerHTML += html[i] + "<br>";
				}
				window.scrollTo(0, 0);
			} else {
				document.getElementById("touroku_btn").value = 1; // hiddenに値設定

				//document.mainform.action = "s_kihon_finish.php";
				document.mainform.action = "s_finish_sinki.php";
				document.mainform.submit(true);
			}
		}
		// 必須項目の入力確認(text,selectbox)
		function chk_input(name) {
			if (document.getElementsByName(name)[0].value != "") {
				return true;
			}
			return false;
		}

		// 必須項目のチェック項目の入力確認(radio,checkbox)
		function chk_checked(name) {
			for (let i = 0; i < document.getElementsByName(name).length; i++) {
				if (document.getElementsByName(name)[i].checked) {
					return true;
				}
			}
			return false;
		}
	</script>




	</div>
	<!--content-all-->

</body>

</html>
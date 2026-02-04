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
			var i = "__maxNo__";//idカウント用。編集の場合は初期値がloopの数だけある。
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
				i = parseInt(i) + 3;//通るたび(ボタン押す度)に+3する→idに名前つけるときに使う

				$(".wHoliday").datepicker({
					numberOfMonths: 1,
					minDate: new Date($('input:text[name="wSenyuStartDate"]').val()),
					maxDate: new Date($('input:text[name="wSenyuEndDate"]').val())
				});
			});
		});
		function removeList(obj) {//行を削除
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

				if ($(this).val() == "100") {//自由入力を選択したらテキストボックス表示
					$kotext.css('display', 'block');
					$kospan.css('display', 'none');
				} else {//自由入力じゃない　テキストボックス消す
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
					/* https://kinocolog.com/ajax/test.html というURL指定も可 */
					type: 'POST',
					dataType: 'text',
					data: { 'gyosyacd': q }

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
	<script>/*

__SHeader__

<div class="content-all"><!--content-all-->

<hr size="__HRSize__" color="__HRColor__">
<center>《物件新規登録》</center>
<hr size="__HRSize__" color="__HRColor__">

<div class="left-yose">
<a href="s_search.php?rKey=__rKey__">＜＜トップ</a>
</div>


物件の新規登録を行います。<br>
下記以外の情報は、登録ボタン押下後に登録可能となります。<br><br>


__IfError__
<div class="left-yose" style="border:1px solid #F00;padding:10px;border-radius:10px;background-color:yellow">
<font color="red"><b>
<span class="blinking">※入力エラー<br></span>
__IfError__ __ErrorMessage__ __IfError__
</b></font>
</div>
__IfError__



<form action="s_finish_sinki.php?rKey=__rKey__" method="POST" name="mainform">

<div class="left-yose">
<font color="red">※は必須項目です。</font>
<table class="table table-bordered table-sm">
<!--
<tr><th>所属<font color="red">※</font></th>
<td><select name="ins_ShozokuCD">
__SitenListLoop__
<option value=__SitenCD__ __SitenSelected__ >__SitenName__ </option>
__SitenListLoop__
</select>
</td></tr>
-->
<tr><th>管理No<font color="red">※</font></th>
<td><!--<font color="gray" size="2">10桁の半角数字で入力してください。</font><br>-->
<input type="text" name="ins_MitumoriNo" value="__ins_MitumoriNo__" size="10"></td></tr>
<tr><th>物件名<font color="red">※</font></th>
<td><input type="text" name="ins_BukkenName" value="__ins_BukkenName__" size="40"></td></tr>
<tr><th>住所<font color="red">※</font></th>
<td><input type="text" name="ins_Address" value="__ins_Address__" size="60"></td></tr>
<tr><th>分譲/賃貸<font color="red">※</font></th>
<td><input type="radio" name="ins_Bunjyo" value="1" __BunjyoChecked1__ >分譲
<input type="radio" name="ins_Bunjyo" value="2" __BunjyoChecked2__ >賃貸</td></tr>
<tr><th>総戸数<font color="red">※</font></th>
<td><input type="number" name="ins_Kosu" value="__ins_Kosu__" size="4">戸</td></tr>
</table>
</div>


<div class="left-yose">
<font color="red">
※環境依存文字は文字化けするため使用しないでください。<br>
物件名に以下の文字が含まれる場合は自動変換されます。<br>
【Ⅰ～Ⅹのローマ数字・①?⑩の丸数字】⇒【I?Xのアルファベット・(1)～(10)の括弧数字】<br>
</font>
</div>

<input type="hidden" name="work" value="1" >
<input type="hidden" name="rKey" value="__rKey__">
<input type="submit" value = "  登　録  " class="btn btn-primary">
</form>



<hr size="__HRSize__" color="__HRColor__">
<a href="s_search.php__QUERY__">トップ</a>
<hr size="__HRSize__" color="__HRColor__">
__SFooter__
__SCopyright__

*/</script>
	<!--全体のコメントアウト消すときにこのscriptタグと対応しているscriptタグ両方とも消す-->


	<!--s_kihon_formから移植-->
	<div class="content-all">
		<!--content-all-->

		<div class="left-yose">
			<!--<a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜メニュー</a>-->
		</div>


		<div class="top-menu left-yose">
			<!--<h5>__wBukkenName__</h5>-->
			<h6>新規工事登録</h6>

			<!--物件CD：__editBukkenCD__　物件名：__wBukkenName__<br>-->
			<!--所属：__SitenName__<br>-->

			<!--<form action="s_kihon_finish.php" method="POST" name="mainform">-->
			<form action="s_finish_sinki.php" method="POST" name="mainform">

				<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
				<input type="hidden" name="rKey" value="__rKey__">
				<input type="hidden" name="work" value="1">

				<font color="red">※は入力必要項目です。</font><br>
				<span id="ErrorString" style="color:red"></span>
				__IfError____ErrorLoop__
				<font color="red">__ErrorStrings__<br></font>
				__ErrorLoop____IfError__
				<br>
				<!--
	<p class="mintitle">１．物件基本情報</p>
	<table class="table table-bordered table-sm" style="width:800px">
	<colgroup>
		<col style='width:200px;'>
		<col style=''>
	</colgroup>
	<tr><th>件名No<font color="red">　※</font></th>
		<td><input type="text" name="wKenmeiNo" value="__KenmeiNo__" style="width:120px;"></td></tr>
	<tr><th>物件担当者<font color="red">　※</font></th>
		<td><select name="wTantoCD" style="width:120px;">
			<option value="">-</option>
			__TantoLoop__ <option value="__TantoCD__" __TantoCDSelected__ >__TantoName__ </option> __TantoLoop__
			</select>
		</td></tr>
	<tr><th>物件名<font color="red">　※</font></th>
		<td><input type="text" name="wBukkenName" value="__wBukkenName__" style="width:400px;" placeholder="例：○○マンション">
			<br><font size="2" color="gray">※物件名に工事名称が入っている場合は工事名称を削除してください。<br>
											例：○○マンションインターホン更新工事 → ○○マンション<br>
											　：○○マンション<s>インターホン更新工事</s></font>
		</td></tr>
	<tr><th>工事名称<font color="red">　※</font></th>
		<td><input type="text" name="wKojiName" value="__wKojiName__" style="width:400px;" placeholder="例：インターホン更新工事">
			<br><font size="2" color="gray">※工事名称は各工事案内に反映されます</font>
	</td></tr>
	<tr><th>住戸数<font color="red">　※</font></th>
		<td><input type="text" name="wKosu" value="__wKosu__" style="width:80px; ime-mode:disabled;"> 戸</td></tr>
	<tr><th>階高<font color="red">　※</font></th>
		<td><input type="text" name="wKaidaka" value="__wKaidaka__" style="width:80px; ime-mode:disabled;"> 階</td></tr>
	<tr><th>管理会社</th>
		<td>管理会社名：<input type="text" name="wKanriGaisya" value="__wKanriGaisya__" style="width:300px;"><br>
			TEL：<input type="text" name="wKanriGaisyaTEL" value="__wKanriGaisyaTEL__" style="width:150px;">　
			担当者名：<input type="text" name="wKanriGaisyaTanto" value="__wKanriGaisyaTanto__" style="width:150px;"><br>
			<font size="2" color="gray">※間違いの無いよう正式名称を記載してください</font>
		</td></tr>
	<tr><th>施工主体<font color="red">　※</font></th>
		<td><input type="radio" name="wSekoShutaiOP" value="0" __SekoShutaiChecked0__ >元請け（アイホン）　
			<input type="radio" name="wSekoShutaiOP" value="1" __SekoShutaiChecked1__ >下請け（管理会社）　
		</td></tr>
	-->
				<!--20190709不要
	<tr><th>住所</th>
		<td><input type="text" name="wAddress" id="Addressid" value="__wAddress__" style="width:400px;"></td></tr>
	<tr><th>棟数</th>
		<td><input type="text" name="wTosu"  value="__wTosu__" size="4" > 棟</td></tr>
	<tr><th>分譲/賃貸</th>
		<td><input type="radio" name="wBunjyo" value="1" __BunjyoChecked1__ >分譲　
			<input type="radio" name="wBunjyo" value="2" __BunjyoChecked2__ >賃貸
		</td></tr>
	<tr><th>竣工(年月）</th>
		<td><input type="text" name="wShunko" size="8" value="__wShunko__"></td></tr>
	<tr><th>受注金額</th><td>__wJyucyuKingaku__</td></tr>
	<tr><th>集合玄関機品番</th>
		<td><input type="text" name="wShuGenKataban" value="__wShuGenKataban__"></td></tr>
	-->

				<!--
	<tr><th>基本システム</th>
		<td><select name="wRNsystem" >
			__RNsystemLoop__ <option value="__RNSYSTEM_Value__" __RNsystemSelected__ >__RNSYSTEMNAME__</option> __RNsystemLoop__
			</select>
		</td></tr>
	<tr><th>室内親機品番</th>
		<td><input type="text" name="wOyaKataban" value="__wOyaKataban__" style="width:150px;"></td></tr>
	<tr><th>玄関子機品番</th>
		<td><input type="text" name="wKokiKataban" value="__wKokiKataban__" style="width:150px;"></td></tr>
	<tr><th>管理室親機有無<font color="red">　※</font></th>
		<td><input type="radio" name="wKanrisitu" value="1" __KanrisituChecked1__>有　
			<input type="radio" name="wKanrisitu" value="0" __KanrisituChecked0__>無　
		</td></tr>
	<tr><th>集合玄関機有無<font color="red">　※</font></th>
		<td><input type="radio" name="wAutoLock" value="1" __AutoLockChecked1__ >有　
			<input type="radio" name="wAutoLock" value="2" __AutoLockChecked2__ >無
		</td></tr>
	<tr><th>消防特例<font color="red">　※</font></th>
		<td><input type="radio" name="wShoboTokurei" value="0" __ShoboTokureiChecked0__ onchange="changeShoboTokurei();">特例なし　
			<input type="radio" name="wShoboTokurei" value="1" __ShoboTokureiChecked1__ onchange="changeShoboTokurei();">170号　
			<input type="radio" name="wShoboTokurei" value="2" __ShoboTokureiChecked2__ onchange="changeShoboTokurei();">220号住戸用　
			<input type="radio" name="wShoboTokurei" value="3" __ShoboTokureiChecked3__ onchange="changeShoboTokurei();">220号共同住宅用　
		</td></tr>
	<tr><th><span id="wShoboTokurei220FontTH" style="color:lightgray;">火災警報試験<font color="red">　※</font></span></th>
		<td><span id="wShoboTokurei220Font" style="color:lightgray;">
				<font size="2" color="gray">※消防特例:[220号共同住宅用]を選択時、選択可</font><br>
				<input type="radio" name="wShoboSikenhoho" class="wShoboSikenhoho" value="0" __ShoboSikenhohoChecked0__>着工前後　
				<input type="radio" name="wShoboSikenhoho" class="wShoboSikenhoho" value="1" __ShoboSikenhohoChecked1__>終了後　
				<input type="radio" name="wShoboSikenhoho" class="wShoboSikenhoho" value="2" __ShoboSikenhohoChecked2__>都度
			</span>
		</td></tr>
	<tr><th>自火報連動<font color="red">　※</font></th>
		<td><input type="radio" name="wJikaho" value="0" __JikahoChecked0__ >なし　
			<input type="radio" name="wJikaho" value="1" __JikahoChecked1__ >有り（専有部感知器 既設流用）
			<input type="radio" name="wJikaho" value="2" __JikahoChecked2__ >有り（専有部感知器 交換）
		</td></tr>
	<tr><th>火災抵抗器交換部屋立入り<font color="red">　※</font></th>
		<td><input type="radio" name="wKasaiHeya" value="0" __KasaiHeyaChecked0__ >なし　
			<input type="radio" name="wKasaiHeya" value="1" __KasaiHeyaChecked1__ >有り
		</td></tr>
	</table>
	-->



				<!--<br><br><br>-->
				<p class="mintitle">工事基本情報</p>
				<!--◆資料に記載する情報-->
				<font size="2" color="gray">　
					<!--※初回登録時、ログインユーザー情報が表示されます。必要に応じて変更してください。<br>物件CDは自動で決定されます。管理Noはそちらで物件を管理する際にになります。</font>-->
					<table class="table table-bordered table-sm" style="width:800px">
						<colgroup>
							<col style='width:200px;'>
							<col style=''>
						</colgroup>

						<tr>
							<th class="yb">工事番号</th>
							<td><input type="text" name="wKenmeiNo" value="__wKenmeiNo__"></td>
						</tr>

						__Ifnespe__
						<!--<tr><th class="yb">会社名<font color="red">　※</font></th>

			<td><select name="gyosya">
				__GyosyaLoop__
					<option value="__GyosyaCD2__">__GyosyaName2__</option>
				__GyosyaLoop__
			</select></td></tr>
		-->
						__Ifnespe__
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
							<td><input type="text" name="wKosu" value="__wKosu__" style="width:80px; ime-mode:disabled;"> 戸</td>
						</tr>
						<tr>
							<th class="yb">階高<font color="red">　※</font>
							</th>
							<td><input type="text" name="wKaidaka" value="__wKaidaka__" style="width:80px; ime-mode:disabled;"> 階</td>
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
					</table>
					<!--
	◆施工業者情報<font size="2">　※リストにない場合はマスタ登録から追加してください。</font>
	<table class="table table-bordered table-sm" style="width:800px">
	<colgroup>
		<col style='width:200px;'>
		<col style=''>
	</colgroup>
	<tr><th class="yb">施工業者担当者１</th>
		<td><select name="wGyosyaTantoCD1">
			<option value="0">-</option>
			__GyosyaTantoLoop__ <option value="__GyosyaTantoCD__" __GyosyaTantoCD1Selected__>__GyosyaName__ __GyosyaTantoName__</option> __GyosyaTantoLoop__
			</select>
		</td></tr>
	<tr><th class="yb">施工業者担当者２</th>
		<td><select name="wGyosyaTantoCD2">
			<option value="0">-</option>
			__GyosyaTantoLoop__ <option value="__GyosyaTantoCD__" __GyosyaTantoCD2Selected__ > __GyosyaName__ __GyosyaTantoName__</option> __GyosyaTantoLoop__
			</select>
		</td></tr>
	</table>
	-->
					◆工期
					<table class="table table-bordered table-sm" style="width:800px">
						<colgroup>
							<col style='width:200px;'>
							<col style=''>
						</colgroup>
						<!--
	<tr><th class="yb">全体工期<font color="red">　※</font></th>
		<td>
			<input type="text" name="wZentaiStartDate" value="__wZentaiStartDate__" class="datepicker" style="width:120px;">～
			<input type="text" name="wZentaiEndDate" value="__wZentaiEndDate__" class="datepicker" style="width:120px;">
		</td></tr>
	-->
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
							<th class="yb">予備日</th>
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
						<!--
	<tr><th class="yb">案内資料上の作業時間<font color="red">　※</font></th>
		<td><input type="text" name="wConstTime" value="__wConstTime__" style="width:50px; ime-mode:disabled;">分</td></tr>
	-->
						<!--
	<tr><th class="yb">切替方法<font color="red">　※</font></th>
		<td><input type="radio" name="wKirikaehoho" value="0" __KirikaehohoChecked0__ onClick="changeKirikaeHeiko();">停止　
			<input type="radio" name="wKirikaehoho" value="1" __KirikaehohoChecked1__ onClick="changeKirikaeHeiko();">並行稼働
				<span id="KirikaeHeikoEizoriyoFont" style="color:lightgray;">
					（ 既設映像幹線流用：<input type="radio" name="wKirikaeHeikoEizoriyo" value="1" __KirikaeHeikoEizoriyoChecked1__ >する　
					<input type="radio" name="wKirikaeHeikoEizoriyo" value="0" __KirikaeHeikoEizoriyoChecked0__ >しない ）
				</span>
		</td></tr>
	<tr><th class="yb">幹線ルート<font color="red">　※</font></th>
		<td><input type="radio" name="wKansenKoji" value="0" __KansenKojiChecked0__ >パイプシャフト渡り　
			<input type="radio" name="wKansenKoji" value="1" __KansenKojiChecked1__ >玄関子機渡り　
			<input type="radio" name="wKansenKoji" value="2" __KansenKojiChecked2__ >部屋渡り　
			<input type="radio" name="wKansenKoji" value="3" __KansenKojiChecked3__ >1:1
		</td></tr>
	<tr><th class="yb">ガス漏れ警報器連動<font color="red">　※</font></th>
		<td><input type="radio" name="wGasKoji" value="0" __GasKojiChecked0__ >なし　
			<input type="radio" name="wGasKoji" value="1" __GasKojiChecked1__ >有り（既設流用）
			<input type="radio" name="wGasKoji" value="2" __GasKojiChecked2__ >有り（交換）
		</td></tr>
	<tr><th class="yb">防犯センサー連動<font color="red">　※</font></th>
		<td><input type="radio" name="wBohanKoji" value="0" __BohanKojiChecked0__ >なし　
			<input type="radio" name="wBohanKoji" value="1" __BohanKojiChecked1__ >１階住戸のみ　
			<input type="radio" name="wBohanKoji" value="3" __BohanKojiChecked3__> 設置住戸のみ-->
						<!--未着手-->
						<!--
			<input type="radio" name="wBohanKoji" value="2" __BohanKojiChecked2__ >全住戸
		</td></tr>
	<tr><th class="yb">漏水センサー連動<font color="red">　※</font></th>
		<td><input type="radio" name="wRosuiKoji" value="0" __RosuiKojiChecked0__ >なし　
			<input type="radio" name="wRosuiKoji" value="1" __RosuiKojiChecked1__ >有り（既設流用）
			<input type="radio" name="wRosuiKoji" value="2" __RosuiKojiChecked2__ >有り（交換）
		</td></tr>
	<tr><th class="yb">宅配連動<font color="red">　※</font></th>
		<td><input type="radio" name="wTakuhai" value="0" __TakuhaiChecked0__ >なし　
			<input type="radio" name="wTakuhai" value="1" __TakuhaiChecked1__ >有り
		</td></tr>
		-->
					</table>
					◆型番情報
					<input type="checkbox" name="ziyu" id="" value="1" class="ziyu">自由入力
					<table class="table table-bordered table-sm" id="" style="width: 800px">
						<colgroup>
							<col style='width:200px;'>
							<col style=''>
						</colgroup>
						<tr>
							<th class="yb">基本システム</th>
							<td><select name="wRNsystem">
									__RNsystemLoop__ <option value="__RNSYSTEM_Value__" __RNsystemSelected__>__RNSYSTEMNAME__</option> __RNsystemLoop__
								</select>
							</td>
						</tr>
						<tr id="wOyaKataban">
							<th class="yb">室内親機品番</th>
							<td>

								<select name="wOyaKataban">
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


								<select name="wKokiKataban">
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
							<td>￥<input type="text" name="wOPPrice" style="width:80px; ime-mode:disabled;"></td>
						</tr>
						<!--↑コピー用 非表示 最初の子要素をｺﾋﾟｰ↑-->
						<tr>
							<th class="yb">削除</th>
							<th class="yb">名称</th>
							<th class="yb">価格（税込）</th>
						</tr>
						__OPLoop__ __table_op__ __OPLoop__
					</table>

					<table class="table table-bordered table-sm" style="width:800px">
						<colgroup>
							<col style='width:200px;'>
							<col style=''>
						</colgroup>

						<style>
							<!--
							<tr><th class="yb">オプション支払い方法</th><td colspan="2"><font size="2"color="gray">※オプション登録時、選択可</font><br><span id="Shiharai_id"style="color:lightgray;"><input type="checkbox"name="wShiharai[]"value="現金"__ShiharaiSelected1__>現金 <br><input type="checkbox"name="wShiharai[]"value="振込"__ShiharaiSelected2__>振込 <br><input type="checkbox"name="wShiharai[]"value="NP"__ShiharaiSelected3__>コンビニ・郵便局・銀行(NP後払い） <br> <input type="checkbox"name="wShiharai[]"value="コンビニ"__ShiharaiSelected4__ onchange="changeShiharaiConveni();">コンビニ払込票支払 <span id="wShiharaiConveniFont"style="color:lightgray;"> (<input type="radio"name="wShiharaiConveni"value="0"__ShiharaiConveni0__ >通常 <input type="radio"name="wShiharaiConveni"value="1"__ShiharaiConveni1__ >上限54000円税込 ） </span> </span> </td></tr> <tr><th class="yb">オプション受付方法</th> <td colspan="2"> <font size="2"color="gray">※オプション登録時、選択可</font><br> <span id="OPUketukei_id"style="color:lightgray;"> <input type="radio"name="wOPUketuke"value="0"__OPUketukeChecked0__ >日程受付と同じフリーダイヤル <br> <input type="radio"name="wOPUketuke"value="2"__OPUketukeChecked2__ >フリーダイヤルとWEB受付 < !--未着手
							-->
						<br>
						<input type="radio" name="wOPUketuke" value="1" __OPUketukeChecked1__>アンケート　
						</span>
						</td>
						</tr>
					</table>
					--></style>
					<!--◆その他-->
					<table class="table table-bordered table-sm" style="width:800px">
						<colgroup>
							<col style='width:200px;'>
							<col style=''>
						</colgroup>
						<!--
	<tr><th class="yb">施工前の入館方法<font color="red">　※</font></th>
		<td><input type="checkbox" name="wCurrentNyukan[]" value="0" __CurrentNyukanChecked0__ >鍵　
			<input type="checkbox" name="wCurrentNyukan[]" value="1" __CurrentNyukanChecked1__ >暗証番号　
			<input type="checkbox" name="wCurrentNyukan[]" value="2" __CurrentNyukanChecked2__ >ノンタッチタグ<br>
			施工前その他方法:<input type="text" name="wCurrentNyukanSonota" value="__wCurrentNyukanSonota__" style="width:250px;">
		</td>
	</tr>
	<tr><th class="yb">施工後の入館方法<font color="red">　※</font></th>
		<td><input type="checkbox" name="wRNNyukan[]" value="0" __RNNyukanChecked0__ >鍵　
			<input type="checkbox" name="wRNNyukan[]" value="1" __RNNyukanChecked1__ >暗証番号　
			<input type="checkbox" name="wRNNyukan[]" value="2" __RNNyukanChecked2__ onchange="changeRNNyukan();">ノンタッチタグ<br>
			施工後その他方法:<input type="text" name="wRNNyukanSonota" value="__wRNNyukanSonota__" style="width:250px;">
		</td>
	</tr>
	<tr><th class="yb">タグ本数</th>
		<td>
			<font size="2" color="gray">※施工後の入館方法:[ノンタッチタグ]を選択時、選択可</font><br>
			<p id="TagSuu_id" style="margin-bottom:0px">
				標準<input type="text" name="wTagSuu" value="__wTagSuu__" style="width:50px; ime-mode:disabled;">本渡し　
				外部オーナー<input type="text" name="wOwnerTagSuu" value="__wOwnerTagSuu__" style="width:50px; ime-mode:disabled;">本渡し
			</p>
		</td></tr>
	<tr><th class="yb">切替タイミング</th>
		<td>
			<input type="radio" name="wTagKirikae" value="1" __TagKirikaeChecked1__ __noTagSetDisabled__>着工日　
			<input type="radio" name="wTagKirikae" value="2" __TagKirikaeChecked2__ __noTagSetDisabled__>工事終了日
		</td>
	</tr>
	<tr><th class="yb">工事期間中の暗証番号</th>
		<td><input type="radio" name="wAnshoNo" value="1" onchange="changeAnsho();" __AnshoNoChecked1__ >無　
			<input type="radio" name="wAnshoNo" value="0" onchange="changeAnsho();" __AnshoNoChecked0__ >有　<br>

			<p id="AnshoNoKojichu_id" style="margin-left:30px; margin-bottom:0px">
				住民様ご案内暗証番号:　呼出ボタン＋（数字4桁）
				<input type="text" maxlength="4" name="wAnshoNoKojichu" value="__wAnshoNoKojichu__" style="width:80px; ime-mode:disabled;"><br>
				<font size="2" color="gray">※4桁で記入してください。記入例）2019
			</p>
		</td>
	</tr>
	-->
						<!--
	<tr><th class="yb">専有部工事<br>日時変更受付方法<font color="red">　※</font></th>
		<td>
			<select name="wAnswer" style="width:300px;">
				<option value="">-</option>
				<option value="A.日時変更住戸のみ返答" __Answer1Selected__>A.日時変更住戸のみ返答</option>
				<option value="B.全住戸返答" __Answer2Selected__>B.全住戸返答</option>
				<option value="C.全住戸返答+確定時未返事シート" __Answer3Selected__>C.全住戸返答+確定時未返事シート</option>
			</select>
		</td>
	</tr>
	<tr><th class="yb">WEB受付<font color="red">　※</font></th>
		<td>
			<input type="radio" name="wWEBRecept" value="1" __WEBReceptChecked1__ >有　
			<input type="radio" name="wWEBRecept" value="0" __WEBReceptChecked0__ >無　
		</td>
	</tr>
	-->
					</table>



					<br>

					<!--20190719 一旦コメントアウト
	<table class="table table-bordered table-sm">
	<colgroup>
		<col style='width:200px;'>
		<col style=''>
	</colgroup>
	<tr><th class="yb">★★★親機パネル</th>
		<td><input type="radio" name="wOyakiPanel" value="1" __OyakiPanelChecked1__>なし　
			<input type="radio" name="wOyakiPanel" value="0" __OyakiPanelChecked0__>有り　
		</td></tr>
	<tr><th class="yb">★★★子機パネル</th>
		<td><input type="radio" name="wKokiPanel" value="1" __KokiPanelChecked1__>なし　
			<input type="radio" name="wKokiPanel" value="0" __KokiPanelChecked0__>有り　
		</td></tr>
	</table>-->


					<!--<input type="button" value="一時保存" onClick="required_check(1)" class="btn btn-warning">-->　　
					<input type="button" value="登録する" onClick="required_check(2)" class="btn btn-primary">
					<input type="hidden" name="touroku_btn" id="touroku_btn" value="">
					<!--登録ボタン押下後、エラーなしの場合に設定する-->
			</form>

			<br>
		</div>
	</div>
	<!--content-all-->

	<hr size="__HRSize__" color="__HRColor__">
	<a href="s_search.php__QUERY__">トップ</a>
	<hr size="__HRSize__" color="__HRColor__">
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

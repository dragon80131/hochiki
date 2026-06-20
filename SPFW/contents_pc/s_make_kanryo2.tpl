<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>__TITLENAME__</title>

	<!-- BootstrapのCSS読み込み -->
	<link href="../include/bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<!-- jQuery読み込み -->
	<script src="../include/js/jquery-3.2.1.min.js"></script>

	<!-- BootstrapのJS読み込み -->
	<script src="../include/bootstrap/js/bootstrap.min.js"></script>

	<link rel="stylesheet" type="text/css" href="../css/rnsien.css">
	<script type="text/javascript" src="../tools.js"></script>

	<link href="../css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="../js/tools_ajax.js"></script>
	<script type="text/javascript" src="../js/ConnectedSelect.js"></script>
	<script src="../js/jquery-3.3.1.min.js" type="text/javascript"></script>
	<script src="../js/jquery.ui.core.js" type="text/javascript"></script>
	<script type="text/javascript" src="tools.js"></script>

	<!--datepicker-->
	<link href="../css/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet" />
	<link href="../css/jquery-ui-1.12.1/jquery-ui.structure.min.css" rel="stylesheet" />
	<link href="../css/jquery-ui-1.12.1/jquery-ui.theme.min.css" rel="stylesheet" />
	<script src="../js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script src="../js/jquery-ui/datepicker-ja.js"></script>


	<script type="text/javascript">
		var wakupatterns = JSON.parse('__wakupattern_json__');
		var Kaidaka = '__wKaidaka__';

		$(function () {
			var wakupattern = JSON.parse('__wakupattern_json__');

			$(".datepicker").datepicker({
				numberOfMonths: 2,
			});

			$(".wHoliday").datepicker({
				numberOfMonths: 1,
				minDate: new Date(__DateStart__),
				maxDate: new Date(__DateEnd__),
				showButtonPanel: true,
				closeText: '閉じる',
				currentText: '今日',
				beforeShow: function(input, inst) {
					setTimeout(function() {
						var buttonPane = $(inst.dpDiv).find(".ui-datepicker-buttonpane");
						var btn = $('<button>', {
						text: '削除',
						class: 'ui-datepicker-clear ui-state-default ui-priority-primary ui-corner-all',
						click: function() {
							$.datepicker._clearDate(input);
						}
						});
						// Avoid duplicate Clear button
						if (buttonPane.find('.ui-datepicker-clear').length === 0) {
							btn.appendTo(buttonPane);
						}
					}, 1);
				},
				onSelect: function (selectedDate) {
					if(selectedDate == "")
						return false;
					let currentObj = $(this);
					let currentName = $(this).attr("name");
					let otherDate = null;

					$("[name='"+currentName+"']").not(this).each(function () {
						otherDate = $(this).val();
						if (selectedDate === otherDate) {
							alert("すでに選択された日付が存在します。別の日付を選択してください。");
							currentObj.val("");
							return false;
						}
					});
				}			
			});

			$(".wReserveDay").datepicker({
				numberOfMonths: 1,
				showButtonPanel: true,
				closeText: '閉じる',
				currentText: '今日',
				beforeShowDay: function(date) {
					let start = new Date(__DateStart__);
					let end = new Date(__DateEnd__);
					start.setHours(0, 0, 0, 0);
					end.setHours(0, 0, 0, 0);

					if (date >= start && date <= end) {
						return [false, "", "利用不可"];
					}
					return [true, ""];
				},
				beforeShow: function(input, inst) {
					setTimeout(function() {
						var buttonPane = $(inst.dpDiv).find(".ui-datepicker-buttonpane");
						var btn = $('<button>', {
						text: '削除',
						class: 'ui-datepicker-clear ui-state-default ui-priority-primary ui-corner-all',
						click: function() {
							$.datepicker._clearDate(input);
						}
						});
						// Avoid duplicate Clear button
						if (buttonPane.find('.ui-datepicker-clear').length === 0) {
							btn.appendTo(buttonPane);
						}
					}, 1);
				},
				onSelect: function (selectedDate) {
					if(selectedDate == "")
						return false;
					let currentObj = $(this);
					let currentName = $(this).attr("name");
					let otherDate = null;

					$("[name='"+currentName+"']").not(this).each(function () {
						otherDate = $(this).val();
						if (selectedDate === otherDate) {
							alert("すでに選択された日付が存在します。別の日付を選択してください。");
							currentObj.val("");
							return false;
						}
					});
				}			
			});	

			$(".wFloorDay").datepicker({
				numberOfMonths: 1,
				minDate: new Date(__DateStart__),
				maxDate: new Date(__DateEnd__),
				showButtonPanel: true,
				closeText: '閉じる',
				currentText: '今日',
				beforeShowDay: function(date) {
					let objHolidays = document.getElementsByName('wHoliday[]');
					for (let objHoliday of objHolidays) {
						let holiday = new Date(objHoliday.value);
						if (date.toDateString() === holiday.toDateString()) {
							return [false, "", "利用不可"];
						}
					}					
					return [true, ""];
				},
				beforeShow: function(input, inst) {
					setTimeout(function() {
						var buttonPane = $(inst.dpDiv).find(".ui-datepicker-buttonpane");
						var btn = $('<button>', {
						text: '削除',
						class: 'ui-datepicker-clear ui-state-default ui-priority-primary ui-corner-all',
						click: function() {
							$.datepicker._clearDate(input);
						}
						});
						// Avoid duplicate Clear button
						if (buttonPane.find('.ui-datepicker-clear').length === 0) {
							btn.appendTo(buttonPane);
						}
					}, 1);
				}
			});

			var dates = jQuery(".senyubucls, .wHoliday").datepicker({
				numberOfMonths: 1,
				onSelect: function (selectedDate) {
					var option = this.id == "SenyuStartDate" ? "minDate" : "maxDate",
						instance = $(this).data("datepicker"),
						date = $.datepicker.parseDate(
							instance.settings.dateFormat ||
							$.datepicker._defaults.dateFormat,
							selectedDate,
							instance.settings
						);
					dates.not(this).datepicker("option", option, date);
				},
			});


			$(document).on('change', '.wakupattern', function () {
				$(".MaxWakuForm").remove();
				var waku_ampm_cnt = wakupattern[$(this).val()]['AMPM'].length;
				var waku_form_html = "";
				for (var i = 0; i < waku_ampm_cnt; i++) {
					waku_form_html += '<td class="MaxWakuForm">';
					waku_form_html += wakupattern[$(this).val()]['AMPM'][i];
					waku_form_html += '<input type="number" name="wWaku';
					waku_form_html += wakupattern[$(this).val()]['AMPM'][i];
					waku_form_html += '" value="" style="width:50px;" class="wakuampm" min="1"></td>';
				}
				$(".maxWakuFormTr").append(waku_form_html);
			});

			refreshArrangeType();
		});

		function refreshArrangeType(){
			let wArrangeTypeValue = $('input[name="wArrangeType"]:checked').val();
			if(wArrangeTypeValue == '1'){
				$("#tableMakeKanryo").attr('class', 'arrangeTypeTable1');
			}else{
				$("#tableMakeKanryo").attr('class', 'arrangeTypeTable0');
			}
		}

		//<!-- 休工日 +1行追加ボタン処理 -->
		$(function () {
			var i = "1"; //idカウント用。編集の場合は初期値がloopの数だけある。
			$(document).on("click", "[id='addrow_free']", function (e) {
				var table = document.getElementById("kyukobi_table");
				// 行を行末に追加
				var row = table.insertRow(-1);
				// セルの挿入
				var cell1 = row.insertCell(-1);
				var cell2 = row.insertCell(-1);

				cell1.innerHTML =
					"<a href='javascript:void(0)' class='remove-btn' onclick='removeList(this)'><img src='../images/icon_delete.png'></a>";
				cell2.innerHTML =
					"<input type='text' name='wHoliday[]' value='' class='wHoliday' style='width:120px' autocomplete='off'>";
				cell2.innerHTML +=
					"　<input type='text' name='wHoliday[]' value='' class='wHoliday' style='width:120px' autocomplete='off'>";
				cell2.innerHTML +=
					"　<input type='text' name='wHoliday[]' value='' class='wHoliday' style='width:120px' autocomplete='off'>";

				//商品用の1行追加するボタンをクリックした時
				i = parseInt(i) + 3; //通るたび(ボタン押す度)に+3する→idに名前つけるときに使う

				$(".wHoliday").datepicker({
					numberOfMonths: 1,
					minDate: new Date(__DateStart__),
					maxDate: new Date(__DateEnd__),
					showButtonPanel: true,
					closeText: '閉じる',
					currentText: '今日',
					beforeShow: function(input, inst) {
						setTimeout(function() {
							var buttonPane = $(inst.dpDiv).find(".ui-datepicker-buttonpane");
							var btn = $('<button>', {
							text: '削除',
							class: 'ui-datepicker-clear ui-state-default ui-priority-primary ui-corner-all',
							click: function() {
								$.datepicker._clearDate(input);
							}
							});
							// Avoid duplicate Clear button
							if (buttonPane.find('.ui-datepicker-clear').length === 0) {
								btn.appendTo(buttonPane);
							}
						}, 1);
					},
					onSelect: function (selectedDate) {
						if(selectedDate == "")
							return false;
						let currentObj = $(this);
						let currentName = $(this).attr("name");
						let otherDate = null;

						$("[name='"+currentName+"']").not(this).each(function () {
							otherDate = $(this).val();
							if (selectedDate === otherDate) {
								alert("すでに選択された日付が存在します。別の日付を選択してください。");
								currentObj.val("");
								return false;
					}
				});
					}			
				});

			});

			$(document).on("click", "[id='addrow_reserveday']", function (e) {
				var table = document.getElementById("reserveday_table");
				// 行を行末に追加f
				var row = table.insertRow(-1);
				// セルの挿入
				var cell1 = row.insertCell(-1);
				var cell2 = row.insertCell(-1);

				cell1.innerHTML =
					"<a href='javascript:void(0)' class='remove-btn' onclick='removeList(this)'><img src='../images/icon_delete.png'></a>";
				cell2.innerHTML =
					"<input type='text' name='wReserveDay[]' value='' class='wReserveDay' style='width:120px' autocomplete='off'>";
				cell2.innerHTML +=
					"　<input type='text' name='wReserveDay[]' value='' class='wReserveDay' style='width:120px' autocomplete='off'>";
				cell2.innerHTML +=
					"　<input type='text' name='wReserveDay[]' value='' class='wReserveDay' style='width:120px' autocomplete='off'>";

				$(".wReserveDay").datepicker({
					numberOfMonths: 1,
					showButtonPanel: true,
					closeText: '閉じる',
					currentText: '今日',
					beforeShowDay: function(date) {
						let start = new Date(__DateStart__);
						let end = new Date(__DateEnd__);
						start.setHours(0, 0, 0, 0);
						end.setHours(0, 0, 0, 0);

						if (date >= start && date <= end) {
							return [false, "", "利用不可"];
						}
						return [true, ""];
					},
					beforeShow: function(input, inst) {
						setTimeout(function() {
							var buttonPane = $(inst.dpDiv).find(".ui-datepicker-buttonpane");
							var btn = $('<button>', {
							text: '削除',
							class: 'ui-datepicker-clear ui-state-default ui-priority-primary ui-corner-all',
							click: function() {
								$.datepicker._clearDate(input);
							}
							});
							// Avoid duplicate Clear button
							if (buttonPane.find('.ui-datepicker-clear').length === 0) {
								btn.appendTo(buttonPane);
							}
						}, 1);
					},
					onSelect: function (selectedDate) {
						if(selectedDate == "")
							return false;
						let currentObj = $(this);
						let currentName = $(this).attr("name");
						let otherDate = null;

						$("[name='"+currentName+"']").not(this).each(function () {
							otherDate = $(this).val();
							if (selectedDate === otherDate) {
								alert("すでに選択された日付が存在します。別の日付を選択してください。");
								currentObj.val("");
								return false;
							}
						});
					}			
				});	

			});			
		});

		function removeList(obj) {
			if(confirm("削除しますか？")){
				//行を削除
				// 削除ボタンを押下された行を取得
				var tr = obj.parentNode.parentNode;
				// trのインデックスを取得して行を削除する
				tr.parentNode.deleteRow(tr.sectionRowIndex);
			}
		}			

		function check_form1(){
			let wYokoVal = document.getElementById("wYoko").value;
			if(wYokoVal == ""){
				$("#wYokoError").html("※1フロアの最大部屋数を入力してください。");
				return false;
			}else{
				$("#wYokoError").html("");
			}

			return true;
		}	

	</script>


	<script>
		function modorumove(val) {
			document.mainform.action = val;
			document.mainform.submit(true);
		}

		function ExcelImport(val) {
			document.mainform2.action = val;
			document.mainform2.submit(true);
		}

		function sakuzyo(work, val) {

			// 「OK」時の処理開始 ＋ 確認ダイアログの表示
			if (window.confirm('すでに作業日程が登録されております。住人様が登録された情報も削除されます。日程データを削除してもよろしいですか？')) {
				document.mainform3.work.value = work;
				document.mainform3.action = val;
				document.mainform3.submit(true);
				document.mainform3.work.value = '';
			}
			// 「キャンセル」時の処理開始
			else {
				window.alert('キャンセルされました'); // 警告ダイアログを表示
			}

		}
	</script>

	<script>
		// function checkWakuSelect() {
		// 	var frm = document.mainform;
		// 	var obj = document.getElementById("wakupattern");
		// 	var index = obj.selectedIndex;
		// 	console.log(index);
		// 	var Waku2 = new Array(1, 2, 3, 15);
		// 	var Waku3 = new Array(4, 5, 6, 10, 11, 12, 13, 14, 17, 23);
		// 	var Waku4 = new Array(7, 21);
		// 	var Waku5 = new Array(8, 16);
		// 	var Waku7 = new Array(9, "");


		// 	a1 = document.getElementById("asuu");
		// 	a2 = document.getElementById("a2");
		// 	a3 = document.getElementById("a3");
		// 	p1 = document.getElementById("psuu");
		// 	p2 = document.getElementById("p2");
		// 	p3 = document.getElementById("p3");
		// 	p4 = document.getElementById("p4");

		// 	$("#wakupm1").attr('name', 'wWakuPM1');
		// 	$("#wakupm1").val('__wWakuPM1__');
		// 	a1.style.visibility = "visible";
		// 	a2.style.display = "block";
		// 	a3.style.display = "block";
		// 	p1.style.visibility = "visible";
		// 	p2.style.display = "block";
		// 	p3.style.display = "block";
		// 	p4.style.display = "block";

		// 	if ($.inArray(index, Waku2) != -1) {
		// 		$("#wakupm1").attr('name', 'wWakuPM');
		// 		$("#wakupm1").val('__wWakuPM__');
		// 		a1.style.visibility = "hidden";
		// 		a2.style.display = "none";
		// 		a3.style.display = "none";
		// 		p1.style.visibility = "hidden";
		// 		p2.style.display = "none";
		// 		p3.style.display = "none";
		// 		p4.style.display = "none";
		// 	}

		// 	if ($.inArray(index, Waku3) != -1) {
		// 		a1.style.visibility = "hidden";
		// 		a2.style.display = "none";
		// 		a3.style.display = "none";
		// 		p3.style.display = "none";
		// 		p4.style.display = "none";
		// 	}

		// 	if ($.inArray(index, Waku4) != -1) {
		// 		a3.style.display = "none";
		// 		p3.style.display = "none";
		// 		p4.style.display = "none";
		// 	}

		// 	if ($.inArray(index, Waku5) != -1) {
		// 		a3.style.display = "none";
		// 		p4.style.display = "none";
		// 	}

		// 	if ('__DateSum__' > 1) {
		// 		if (document.getElementById("wakupattern").value > 2) {
		// 			document.getElementById("OverError").innerHTML = "※最大工事枠数の合計を__MinWakuSum__以下に設定してください。<br> 　ただし、休工日・初日考慮選択時は少し多めに設定してください。";
		// 		} else {
		// 			document.getElementById("OverError").innerHTML = "※最大工事枠数の合計を__MinWakuSum2__以下に設定してください。<br> 　ただし、休工日・初日考慮選択時は少し多めに設定してください。";
		// 		}
		// 	} else {
		// 		if (document.getElementById("wakupattern").value > 2) {
		// 			document.getElementById("OverError").innerHTML = "※最大工事枠数の合計を__MinWakuSum3__以下に設定してください。<br> 　ただし、休工日・初日考慮選択時は少し多めに設定してください。";
		// 		} else {
		// 			document.getElementById("OverError").innerHTML = "※最大工事枠数の合計を__MinWakuSum3__以下に設定してください。<br> 　ただし、休工日・初日考慮選択時は少し多めに設定してください。";
		// 		}
		// 	}

		// }
		function checkmove(page) {
			var frm = document.mainform;

			var ErrorString = '';

			//班数の入力チェック
			if ($("#hansu").val() == "") {
				if(ErrorString != '')
					ErrorString += '<br>';
				ErrorString += "班数を選択してください。";
			}

			let wArrangeTypeValue = $('input[name="wArrangeType"]:checked').val();
			if(wArrangeTypeValue == '1'){
				if ($(".wakupattern").val() == "") {
					if(ErrorString != '')
						ErrorString += '<br>';
					ErrorString += "時間枠パターンを選択してください。";
				}

				let wakuErrorString = '';
				$(".wakuampm").each(function () {
					if ($(this).val() == "") {
						wakuErrorString = "最大枠数が入力されていません。";
					} else if ($(this).val() < 1) {
						wakuErrorString = "入力内容が正しくありません。";
					}
				});

				if(wakuErrorString != ''){
					if(ErrorString != '')
						ErrorString += '<br>';
					ErrorString += wakuErrorString;
				}

				Kaidaka = parseInt(Kaidaka);
				if(isNaN(Kaidaka))
					Kaidaka = 0;

				let jsonFloorPlanInfo = [];
				let SenyuStartDate = new Date('__SenyuStartDate__');
				let SenyuEndDate = new Date('__SenyuEndDate__');
				let current = new Date(SenyuStartDate);
				let AMPMValue = [];
				let wMaxWaku = [];
				let wWakuPatternVal = document.getElementById("wWakuPattern").value;
				if(wakupatterns && wakupatterns[wWakuPatternVal] && wakupatterns[wWakuPatternVal]['AMPM']){
					for(let i=0; i<wakupatterns[wWakuPatternVal]['AMPM'].length; i++){
						AMPMValue[wakupatterns[wWakuPatternVal]['AMPM'][i]] = 0;
						wMaxWaku[wakupatterns[wWakuPatternVal]['AMPM'][i]] = 0;
						if ($('[name="wWaku'+wakupatterns[wWakuPatternVal]['AMPM'][i]+'"]').length > 0){
							wMaxWaku[wakupatterns[wWakuPatternVal]['AMPM'][i]] = parseInt($('[name="wWaku'+wakupatterns[wWakuPatternVal]['AMPM'][i]+'"]').val());
							if(isNaN(wMaxWaku[wakupatterns[wWakuPatternVal]['AMPM'][i]]))
								wMaxWaku[wakupatterns[wWakuPatternVal]['AMPM'][i]] = 0;
						}
					}
				}

				while (current <= SenyuEndDate) {
					jsonFloorPlanInfo[current.toISOString().split('T')[0]] = {...AMPMValue};
					current.setDate(current.getDate() + 1);
				}

				for(let floor = Kaidaka; floor >= 1; floor --){
					let aFloorDay = "";
					if ($('[name="wFloorDay_'+floor+'"]').length > 0)
						aFloorDay = $('[name="wFloorDay_'+floor+'"]').val();

					let aFloorWaku = "";
					if ($('[name="wFloorWaku_'+floor+'"]').length > 0)
						aFloorWaku = $('[name="wFloorWaku_'+floor+'"]').val();

					let aFloorCols = 0;
					if ($('[name="wFloorCols_'+floor+'"]').length > 0){
						aFloorCols = $('[name="wFloorCols_'+floor+'"]').val();
						aFloorCols = parseInt(aFloorCols);
						if(isNaN(aFloorCols))
							aFloorCols = 0;
					}
					if(aFloorDay != "" && aFloorWaku != "" && aFloorCols > 0){
						if (jsonFloorPlanInfo?.[aFloorDay]?.[aFloorWaku] !== undefined) {
							jsonFloorPlanInfo[aFloorDay][aFloorWaku] += aFloorCols;
						}
					}
				}

				for (let kFloorDay in jsonFloorPlanInfo) {
					for (let kFloorWaku in jsonFloorPlanInfo[kFloorDay]) {
						if(wMaxWaku?.[kFloorWaku] !== undefined && wMaxWaku[kFloorWaku] > 0 && jsonFloorPlanInfo[kFloorDay][kFloorWaku] > 0){
							if(jsonFloorPlanInfo[kFloorDay][kFloorWaku] > wMaxWaku[kFloorWaku]){
								if(ErrorString != '')
									ErrorString += '<br>';
								ErrorString += kFloorDay+"の"+kFloorWaku+"に選択された部屋数が、最大枠数を超えています。";

							}
						}
					}
				}

			}else{
				if ($(".wakupattern").val() == "") {
					if(ErrorString != '')
						ErrorString += '<br>';
					ErrorString += "工事枠パターンを選択してください。";
				}

				let wakuErrorString = '';
				$(".wakuampm").each(function () {
					if ($(this).val() == "") {
						wakuErrorString = "最大工事枠数が入力されていません。";
					} else if ($(this).val() < 1) {
						wakuErrorString = "入力内容が正しくありません。";
					}
				});

				if(wakuErrorString != ''){
					if(ErrorString != '')
						ErrorString += '<br>';
					ErrorString += wakuErrorString;
				}


				if ($("input[name='wKojijun']:checked").val() == "" || $("input[name='wKojijun']:checked").val() == undefined) {
					if(ErrorString != '')
						ErrorString += '<br>';
					ErrorString += "工事順を選択してください。";
				}
			}

			if (ErrorString == "") {
				$("#ErrorString").html("");
				document.mainform.action = page;
				document.mainform.submit(true);
			}

			$("#ErrorString").html(ErrorString);
		}

		function notifyWorkPattern(){
			let wWakuPatternVal = document.getElementById("wWakuPattern").value;
			if(wWakuPatternVal == '3' || wWakuPatternVal == '4' || wWakuPatternVal == '5'){
				document.getElementById("wFirstDateFeature2").disabled = false;
			}else{
				document.getElementById("wFirstDateFeature2").disabled = true;
			}
/*			
			let optionsWaku ='<option value=""></option>';
			if(wakupatterns && wakupatterns[wWakuPatternVal] && wakupatterns[wWakuPatternVal]['AMPM']){
				for(let i=0; i<wakupatterns[wWakuPatternVal]['AMPM'].length; i++){
					optionsWaku += '<option value="'+wakupatterns[wWakuPatternVal]['AMPM'][i]+'">'+wakupatterns[wWakuPatternVal]['AMPM'][i]+'</option>';
				}
				$(".sFloorWakuSelect").html(optionsWaku);
			}
*/			
		}

		document.addEventListener("DOMContentLoaded", function() {
			notifyWorkPattern();
		});
	</script>

	<style>
		.remove-btn{
			display: block;
			margin-left: auto;
			width: fit-content;
			margin-right: 0;
			margin-top: 3px;
		}
		.HasError{
			color:red;
		}
		.arrangeTypeTable1 .arrangeTypeElement1{
			display:block;
		}
		.arrangeTypeTable1 .arrangeTypeElement0{
			display:none;
		}
		.arrangeTypeTable1 .arrangeTypeRow1{
			display:table-row;
		}
		.arrangeTypeTable1 .arrangeTypeRow0{
			display:none;
		}

		.arrangeTypeTable0 .arrangeTypeElement1{
			display:none;
		}
		.arrangeTypeTable0 .arrangeTypeElement0{
			display:block;
		}
		.arrangeTypeTable0 .arrangeTypeRow1{
			display:none;
		}
		.arrangeTypeTable0 .arrangeTypeRow0{
			display:table-row;
		}

		.FloorTable{
			width:100%;
			border:1;
			border-collapse: collapse;
		}
		.FloorTable th{
			font-weight:normal;
			background-color:#f4cccc;
			text-align:center;
		}
		.FloorTable th,
		.FloorTable td{
			border: 1px solid #333;
			border-collapse: collapse;
		}

		.btn-group label.btn:has(input:checked) {
			color: #fff;
			background-color: #db7561;
			border-color: #dd7e6b;
		}		
	</style>
</head>

<body>
	__SHeaderKanri__

	<div class="content-all2">
		<!--content-all-->
<br>
		<div class="left-yose d-flex align-items-end flex-wrap">
			<a href="../s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__"class="btn btn-primary mr-1 mb-1">メニュー</a>
			&nbsp;
			<ul class="nav nav-tabs mb-1 building_nav">
				__IfBuildingExist__
				<li class="nav-item">
					<a class="nav-link __mainNaviClass__" href="s_make_kanryo2.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">__wBuildingName__</a>
				</li>
				__IfBuildingExist__
				__BuildingLoop__
				<li class="nav-item">
					<a class="nav-link __naviClass__" href="s_make_kanryo2.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__BuildingCD__">__BuildingName__</a>
				</li>
				__BuildingLoop__
			</ul>		
		</div>


		<div class="top-menu left-yose">
			<h5>__wBukkenName__</h5>
			<h6>詳細工程表 作成ツール</h6>

			__IfKoji__

			<form action="s_make_matrix.php" method="POST">

				<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
				<input type="hidden" name="editBuildingCD" value="__editBuildingCD__">
				<input type="hidden" name="rKey" value="__rKey__">


				__IfNew__
				<font color="red">部屋構成が未作成です。</font><br>
				__IfNew__

				__IfError__
				<br>
				<font color="red">ログインしたユーザの組織以外の物件を登録・編集することはできません。組織を確認ください。</font><br>
				__IfError__

				__IfOK__
				<br>
				<font color="red">部屋構成を登録完了しました。</font><br>
				__IfOK__


				■部屋構成作成<br>
				以下の項目を入力し、「部屋構成の確認」ボタンをクリックしてください。<br>
				特殊な時間枠(15:30開始など）については本ツール未対応です。
				<div class="HasError" id="wYokoError"></div>
				<table border=1>
					<tr>
						<td bgcolor="#f4cccc">総戸数と階高</td>
						<td>総戸数：__wKosu__ 戸　階高：__wKaidaka__ 階</td>
					</tr>
					<tr>
						<td bgcolor="#f4cccc">１フロア最大いくつ部屋がありますか？</td>
						<td>
						<input type="number" name="wYoko" id="wYoko" value="__wYoko__" min="1" required>戸
						</td>
					</tr>
					<!--<tr><td bgcolor="#f4cccc" >部屋番 ○○４を除外しますか？204など</td>
					<td><input type=checkbox name="Except4" value=1 >除外する</td></tr>
				<tr><td bgcolor="#f4cccc" >部屋番 ○○９を除外しますか？209など</td>
					<td><input type=checkbox name="Except9" value=1 >除外する</td></tr>-->
				</table>

				<br>
				__HiddenValues__
				<input type="submit" value="部屋構成の確認" class="btn btn-primary blue" onclick="return check_form1();">
			</form>

			<br>

			__IfRoomOK2__
			■登録済み部屋構成
			<!--<input type=button value="工事完了表（エントランスに貼る部屋表）" onclick="location.href='./s_make_kanryodoc.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__'">-->

				
			<table border=1>
				__RowsLoop__
				<tr>__ColsBlock__</tr>
				__RowsLoop__
			</table>
			__IfRoomOK2__

			__IfRoomOK__
			<br>

			<h6>詳細工程表 簡単作成ツール</h6>
			以下の条件をセットして［工程表案表示］ボタンをクリックしてください。<br>
			<div id="ErrorString" style="color: red"></div>

			<form action="s_make_schedule.php" method="POST" name="mainform">
			<div class="btn-group">
			<label class="btn btn-primary">
				<input type="radio" name="wArrangeType" value="1" id="wArrangeType1" __ArrangeTypeChecked1__ onchange="refreshArrangeType()"> 点検
			</label>
			<label class="btn btn-primary">
				<input type="radio" name="wArrangeType" value="0" id="wArrangeType2" __ArrangeTypeChecked0__ onchange="refreshArrangeType()"> 工事
			</label>
			</div>
				<table border=1 id="tableMakeKanryo" class="__clsTableMakeKanryo__">
					<tr>
						<td bgcolor="#f4cccc">工事は何班？<font color="red">※</font></td>
						<td>
							<select name="wHansu" id="hansu">
								<option value="">-</option>
								<option value="1" __HansuSelect1__>1班</option>
								<option value="2" __HansuSelect2__>2班</option>
								<option value="3" __HansuSelect3__>3班</option>
								<option value="4" __HansuSelect4__>4班</option>
							</select>
							<font color="red"><span id="HansuError"></span></font>
						</td>
					</tr>
					<tr id="blockName">
						<td bgcolor="#f4cccc">
							<div class="arrangeTypeElement0">
								工事枠パターン<font color="red">※</font>
							</div>
							<div class="arrangeTypeElement1">
								時間枠パターン<font color="red">※</font>
							</div>
						</td>
						<td nowrap class="common-list-value-left">
							<select name="wWakuPattern" id="wWakuPattern" class="wakupattern" onchange="notifyWorkPattern();">
								<option value="">-</option>
								__WakuPatternLoop__
								<option value="__WakuPattern__" __SelectedWakuPattern__>__WakuPatternName__</option>
								__WakuPatternLoop__
							</select>
							<font color="red"><span id="WakuPatternError"></span></font>
						</td>
					</tr>
					<tr>
						<td bgcolor="#f4cccc" id="lblMaxWaku">
							<div class="arrangeTypeElement0">
								最大工事枠数<font color="red">※</font>
							</div>
							<div class="arrangeTypeElement1">
								最大工事枠数<font color="red">※</font>
							</div>
						</td>
						<td><br>
							<table>
								<tr class="maxWakuFormTr">
									__MaxWakuForm__
								</tr>
							</table>
							<span id="OverError">__OverErrorStrings__</span><br>　数字はあくまで目安になります。
							<font color="red"><span id="WakuAMPMError"></span></font><br><br>
						</td>
					</tr>
					<tr>
						<td bgcolor="#f4cccc">
							<div class="arrangeTypeElement0">
								枠越え
							</div>
							<div class="arrangeTypeElement1">
								 枠越え
							</div>

						</td>
						<td>
							<input type="number" name="wFrameOverflow" value="__wFrameOverflow__">
						</td>
					</tr>		
					<!--
						<tr><td bgcolor="#f4cccc" >実際、工事は1戸あたり何分？</td>
							<td>
								<select name="wMinuteTime" >
								<option value="" >-</option>
								<option value="30" __HansuSelect30__>30分</option>
								<option value="60" __HansuSelect60__>60分</option>
								<option value="90" __HansuSelect90__>90分</option>
								</select>
						</td></tr>
						-->

					<tr>
						<td bgcolor="#f4cccc">専有部期間</td>
						<td>__SenyuStartDate__ ～ __SenyuEndDate__
						</td>
					</tr>
					<tr>
						<td bgcolor="#f4cccc">受付締切日</td>
						<td>
							
							__YoyakuEndDate__
						</td>
					</tr>		
					<tr>
						<td bgcolor="#f4cccc">休工日</td>
						<td>
							<table class="table table-bordered table-sm" id="kyukobi_table">
								<tr>
									<td>
										<input type="button" value="＋1行追加" style="background-color: transparent"
											id="addrow_free" />
									</td>
									<td>
										<input type="text" name="wHoliday[]" value="__wHoliday1__" class="wHoliday"
											style="width: 120px" autocomplete="off">
										　<input type="text" name="wHoliday[]" value="__wHoliday2__" class="wHoliday"
											style="width: 120px" autocomplete="off">
										　<input type="text" name="wHoliday[]" value="__wHoliday3__" class="wHoliday"
											style="width: 120px" autocomplete="off">
									</td>
								</tr>
								__KyukoTable__
							</table>
						</td>
					</tr>
					<tr class="arrangeTypeRow0">
						<td bgcolor="#f4cccc">予備日</td>
						<td>
							<table class="table table-bordered table-sm" id="reserveday_table">
								<tr>
									<td>
										<input type="button" value="＋1行追加" style="background-color: transparent"
											id="addrow_reserveday" />
									</td>
									<td>
										<input type="text" name="wReserveDay[]" value="__wReserveDay1__" class="wReserveDay"
											style="width: 120px" autocomplete="off">
										　<input type="text" name="wReserveDay[]" value="__wReserveDay2__" class="wReserveDay"
											style="width: 120px" autocomplete="off">
										　<input type="text" name="wReserveDay[]" value="__wReserveDay3__" class="wReserveDay"
											style="width: 120px" autocomplete="off">
									</td>
								</tr>
								__ReserveDayTable__
							</table>
						</td>
					</tr>
					<tr class="arrangeTypeRow0">
						<td bgcolor="#f4cccc">専有部工事の初日考慮</td>
						<td>
							<select name="wFirstDateFeature">
								<!--ConstTimeは表記上　MinuteUnitは30分でいく。-->
								<option value="0">-</option>
								<option value="1" __FirstDateFeature1__>初日午前NG</option>
								<option id="wFirstDateFeature2" value="2" __FirstDateFeature2__>初日１５:００以降OK</option>
							</select>
						</td>
					</tr>
					<tr class="arrangeTypeRow0">
						<td bgcolor="#f4cccc">工事順<br>(詳細工程表の並び順)<font color="red">※</font></td>
						<td>

							<table>
								<tr>
									<td><label><input type="radio" name="wKojijun" value="1" __KojijunChecked1__><img
												src="../images/kojijun1.png" width="50">下から横へ</label></td>
									<td><label><input type="radio" name="wKojijun" value="5" __KojijunChecked5__><img
												src="../images/kojijun4.png" width="50">上から縦へ(2列ずつ）</label></td>
								</tr>
								<tr>
									<td><label><input type="radio" name="wKojijun" value="2" __KojijunChecked2__><img
												src="../images/kojijun2.png" width="50">上から横へ(昇順)</label></td>
									<td><label><input type="radio" name="wKojijun" value="6" __KojijunChecked6__><img
												src="../images/kojijun4.png" width="50">上から縦へ(3列ずつ）</label></td>
								</tr>
								<tr>
									<td><label><input type="radio" name="wKojijun" value="7" __KojijunChecked7__><img
												src="../images/kojijun2.png" width="50">上から横へ(降順)</label></td>
									<td><label><input type="radio" name="wKojijun" value="3" __KojijunChecked3__><img
												src="../images/kojijun3.png" width="50">下から縦へ(2列ずつ）</label></td>
								</tr>
								<tr>
									<td><label><input type="radio" name="wKojijun" value="4" __KojijunChecked4__><img
												src="../images/kojijun3.png" width="50">下から縦へ(3列ずつ）</label></td>
								</tr>
							</table>
							<font color="red"><span id="KojijunError"></span></font>

						</td>
					</tr>
					<tr class="arrangeTypeRow1">
						<td colspan="2">
							<table class="FloorTable">
								<tr>
									<th width="20%">階高</th>
									<th width="40%">日付</th>
									<th width="20%">時間枠</th>
									<th width="20%">部屋数</th>
								</tr>
								__FloorTableInfo__
							</table>
						</td>
					</tr>
				</table>
				<input type="hidden" name="wColsBlock" value="__wColsBlock__">
				<input type="hidden" name="RowsLoop" value="__RowsLoop__">
				<!--<input type="hidden" name="Except4" value=1 >
				<input type="hidden" name="Except9" value=1 >-->
				<input type="hidden" name="rKey" value="__rKey__">
				<br>
				<input type="button" class="btn btn-primary blue"
					onclick="javascript:checkmove('s_make_kotei_confirm2.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__' )"
					value="工程表案表示">
				<br>

			</form>
				__IfKoji__

				__IfNotKoji__
				<form action=# method="POST" name="mainform">
					<br> 工事情報登録がまだです。工事情報登録をお願いします。
					<br>
					<br>

					<input type="button" value="工事情報登録へ"
						onclick="javascript:move('../s_koji.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__&editBuildingCD=__editBuildingCD__' )"
						class="btn btn-success"><br>
					<br>

				</form>
				__IfNotKoji__<br>


			__IfRoomOK__

<!--
			上記の工程表作成ができない場合は、Excelファイルで日程情報の登録を行います。<br>
			部屋番号が数字でない場合や多棟物件が対象です。入居者様のWEB受付に利用されます。<br>

			<form action=# method="POST" name="mainform2">
				<input type="button" value="Excel日程データ読み込み"
					onclick="ExcelImport('d_csvUpload.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__&editBuildingCD=__editBuildingCD__');"
					class="btn btn-primary blue"><br>
			</form>
-->
<br>




			__IfKojiDateOK__
			登録済みの日程データ
			<br>

			<table border="1">
				<tr bgcolor="lightgray">
					<th></th>
					<th>部屋番号</th>
					<th>パスワード</th>
					<th>日程</th>
					<th>__ReserveTableTimeTitle__</th>
				</tr>
				__CellLoop__

				<tr>
					<th bgcolor="lightgray">__No__</th>
					<td>__RoomID__
					</td>
					<td>__Passwd__
					</td>
					<td>
						__wTimeFromDate__
					</td>
					<td align="center">
						__wTimeFromTime__
					</td>
				</tr>
				__CellLoop__
			</table>

			<br>
			<form action=# method="POST" name="mainform3">
				<input type="hidden" name="work">
			</form>
			<input type="button" value="日程データ削除"
				onclick="sakuzyo('2','s_make_kanryo2.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__');"
				class="btn btn-primary blue">
			<br>
			__IfKojiDateOK__
			<br>

			<hr>


			<!-- <input type="button" value="メニューへもどる" onclick="javascript:move('../s_menu.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__&editBuildingCD=__editBuildingCD__' )"  class="btn btn-info"><br> -->

		</div>
		<!--content-all-->

		__SFooter__
		__SCopyright__

</body>

</html>
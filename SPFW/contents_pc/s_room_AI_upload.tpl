<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>リニューアル支援</title>

	<!-- BootstrapのCSS読み込み -->
	<link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="/kotei/css/kotei.css" rel="stylesheet">
	<link href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous" rel="stylesheet">

	<!-- jQuery読み込み -->
	<script src="./include/js/jquery-3.2.1.min.js"></script>

	<!-- BootstrapのJS読み込み -->
	<script src="./include/bootstrap/js/bootstrap.min.js"></script>

	<link rel="stylesheet" type="text/css" href="./css/rnsien.css">
	<script type="text/javascript" src="./tools.js"></script>

	<link href="d2b/css/dropzone.css" type="text/css" rel="stylesheet" />
	<script src="d2b/dropzone.min.js"></script>

	<script src="js/jquery-ui-1.12.1.custom/external/jquery/jquery.js" type="text/javascript"></script>
	<script src="js/jquery-ui-1.12.1.custom/jquery-ui.min.js" type="text/javascript"></script>
	<link href="js/jquery-ui-1.12.1.custom/jquery-ui.theme.min.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="js/tools_ajax.js"></script>
	<script type="text/javascript" src="js/ConnectedSelect.js"></script>
	<script src="./js/contextmenu/jquery.contextMenu.min.js"></script>
	<link href="js/contextmenu/jquery.contextMenu.min.css" rel="stylesheet" type="text/css" />

	<style>
		.wrap {
			display: flex;
			flex-wrap: wrap;
		}

		.box {
			margin: 0 2px;
		}

		.txt {
			display: block;
			width: 100px;
			padding: 0.5em;
			border: 1px solid #999;
			box-sizing: border-box;
			background: #f2f2f2;
			margin: 0.5em 0;
		}

		body,
		html {
			margin: 0px;
			padding: 0px;
			font-family: 'Titillium Web', sans-serif;
			font-size: 23px;
			/* line-height: 42px; */
			font-weight: 100;
			text-align: center;
			height: 100%;
		}

		.drophover {
			background-color: #fffacd;
		}


		.heyatr {
			border: 2px black solid;
			height: 55px;

		}

		.heyatd {
			border: 1px black solid;
		}

		.trash {
			background-color: #ff7f50;
			background-image: url(./images/trash.png);
			background-size: 40px;
			background-repeat: no-repeat;
			background-position: center;
			border: none;
			border-radius: 6px;
			padding: 12px 40px;
			margin: 10px;
			display: inline-block;
			height: 50px;
			width: 100px;
		}

		.trashhover {
			animation: big 0.1s;
			animation-fill-mode: forwards;

		}


		#trashzone {
			position: fixed;
			/* 要素の位置を固定する */
			bottom: 0;
			/* 基準の位置を画面の一番下に指定する */
			width: 100%;
			background-color: rgba(0, 0, 0, 0.5);
			margin-right: calc(50% - 50vw);
			margin-left: calc(50% - 50vw);
		}

		@keyframes big {
			0% {
				transform: scale(1);
			}

			100% {
				transform: scale(1.1);
			}
		}

		@keyframes small {
			100% {
				transform: scale(0.9);
			}
		}

		/**
 * コンテキストメニュー
 */
		.context-menu-icon.context-menu-icon--fa {
			font-family: inherit;
			font-weight: 180;
			line-height: inherit;
		}

		.context-menu-list {
			font-size: 14px;
		}

		.control-label+.label {
			margin-top: 8px;
			padding-top: 5px;
		}
	</style>
	<script>
		$(function () {
			$('.heyatr').sortable({
				placeholder: "ui-state-highlight",
				cursor: "move",
				items: "td:not(.plus)"
			});

			$('.heyatr').droppable({
				hoverClass: "drophover",
				tolerance: "pointer",
				drop: function (e, ui) {
					// ドロップ先に追加
					heya_tr_no = $(this).children().attr("id").replace("plus", "");
					var classname = ui.draggable.attr("class");
					if (classname.indexOf('ui-state-highlight' + heya_tr_no) == -1) {
						$('.list' + heya_tr_no).append("<td class='ui-state-highlight" + heya_tr_no + " heyatd'>" + ui.draggable.html() + "</td>");
						console.log($('.list' + heya_tr_no).children().length);
						val = [];
						$('.list' + heya_tr_no).children().each(function (index, element) {
							if (index > 0) {
								console.log(index + ':' + $(element).children());
								txt = $(element).children();
								console.log(txt.val());
								val.push(txt.val());
							}
						});
						val.sort();
						$('.list' + heya_tr_no).children().each(function (index, element) {
							if (index > 0) {
								txt = $(element).children();
								txt.val(val[index - 1]);
								txt.attr('value', val[index - 1]);
							}
						});

						// 元を削除
						ui.draggable.remove();
					}
				}
			});
			$(document).on("change", ".txt", function () {
				var val = $(this).val();
				console.log(val);
				$(this).attr('value', val);
			});
			$('.trash').droppable({
				hoverClass: "trashhover",
				tolerance: "pointer",
				drop: function (e, ui) {
					ui.draggable.remove();
				}
			});
			var number = '__trCnt__';
			$(document).on("click", ".plus", function () {
				var idname = $(this).attr('id');
				// console.log($(this).parent());
				no = idname.replace("plus", "");
				addobject = "<td class='ui-state-highlight" + no + " heyatd'>";
				addobject += "<input type='text' class='txt' name='room[]' value=''>";
				addobject += "</td>";
				$(this).parent().append(addobject);

			});
			var context = $.contextMenu({
				selector: ".heyatd"
				, autoHide: true
				, trigger: 'right'
				, animation: {
					duration: 500
					, show: 'fadeIn'
					, hide: 'fadeOut'
				}
				, callback: function (key, options) {
					if (key == "deletetd") {
						options.$trigger.remove();
					} else if (key == "addup" || key == "adddown") {
						number++;
						addtr = "<tr  class='list" + number + " heyatr'>";
						addtr += "<td class='plus' id='plus" + number + "'>";
						addtr += "<input type='button' value='+'>";
						addtr += "</td>";
						addtr += "<tr>";
						if (key == "addup") {
							options.$trigger.parent().before(addtr);
						}
						if (key == "adddown") {
							options.$trigger.parent().after(addtr);
						}

					} else if (key == "deleterow") {

						options.$trigger.parent().remove();

					}
					$('.heyatr').sortable({
						placeholder: "ui-state-highlight",
						cursor: "move",
						items: "td:not(.plus)"
					});

					$('.heyatr').droppable({
						hoverClass: "drophover",
						tolerance: "pointer",
						drop: function (e, ui) {
							// ドロップ先に追加
							heya_tr_no = $(this).children().attr("id").replace("plus", "");
							var classname = ui.draggable.attr("class");
							if (classname.indexOf('ui-state-highlight' + heya_tr_no) == -1) {
								$('.list' + heya_tr_no).append("<td class='ui-state-highlight" + heya_tr_no + " heyatd'>" + ui.draggable.html() + "</td>");
								console.log($('.list' + heya_tr_no).children().length);
								val = [];
								$('.list' + heya_tr_no).children().each(function (index, element) {
									if (index > 0) {
										console.log(index + ':' + $(element).children());
										txt = $(element).children();
										console.log(txt.val());
										val.push(txt.val());
									}
								});
								val.sort();
								$('.list' + heya_tr_no).children().each(function (index, element) {
									if (index > 0) {
										txt = $(element).children();
										txt.val(val[index - 1]);
										txt.attr('value', val[index - 1]);
									}
								});

								// 元を削除
								ui.draggable.remove();
							}
						}
					});
				}
				, items: {
					"deletetd": { name: "部屋番号削除", icon: "fa-angle-right" },
					"sep1": "---------",
					"addup": { name: "上に行追加", icon: "fa-angle-right" },
					"adddown": { name: "下に行追加", icon: "fa-angle-right" },
					"deleterow": { name: "選択行を削除", icon: "fa-angle-right" },
					"sep2": "---------",
					"quit": {
						name: "閉じる", icon: function () {
							return "context-menu-icon context-menu-icon-quit";
						}
					}
				}
			});
			var context = $.contextMenu({
				selector: ".heyatr"
				, autoHide: true
				, trigger: 'right'
				, animation: {
					duration: 500
					, show: 'fadeIn'
					, hide: 'fadeOut'
				}
				, callback: function (key, options) {
					if (key == "addup" || key == "adddown") {
						number++;
						addtr = "<tr  class='list" + number + " heyatr'>";
						addtr += "<td class='plus' id='plus" + number + "'>";
						addtr += "<input type='button' value='+'>";
						addtr += "</td>";
						addtr += "<tr>";
						if (key == "addup") {
							options.$trigger.before(addtr);
						}
						if (key == "adddown") {
							options.$trigger.after(addtr);
						}

					} else if (key == "deleterow") {
						options.$trigger.remove();

					}
					$('.heyatr').sortable({
						placeholder: "ui-state-highlight",
						cursor: "move",
						items: "td:not(.plus)"
					});

					$('.heyatr').droppable({
						hoverClass: "drophover",
						tolerance: "pointer",
						drop: function (e, ui) {
							// ドロップ先に追加
							heya_tr_no = $(this).children().attr("id").replace("plus", "");
							var classname = ui.draggable.attr("class");
							if (classname.indexOf('ui-state-highlight' + heya_tr_no) == -1) {
								$('.list' + heya_tr_no).append("<td class='ui-state-highlight" + heya_tr_no + " heyatd'>" + ui.draggable.html() + "</td>");
								console.log($('.list' + heya_tr_no).children().length);
								val = [];
								$('.list' + heya_tr_no).children().each(function (index, element) {
									if (index > 0) {
										console.log(index + ':' + $(element).children());
										txt = $(element).children();
										console.log(txt.val());
										val.push(txt.val());
									}
								});
								val.sort();
								$('.list' + heya_tr_no).children().each(function (index, element) {
									if (index > 0) {
										txt = $(element).children();
										txt.val(val[index - 1]);
										txt.attr('value', val[index - 1]);
									}
								});

								// 元を削除
								ui.draggable.remove();
							}
						}
					});
				}
				, items: {
					"addup": { name: "上に行追加", icon: "fa-angle-right" },
					"adddown": { name: "下に行追加", icon: "fa-angle-right" },
					"deleterow": { name: "選択行を削除", icon: "fa-angle-right" },
					"sep1": "---------",
					"quit": {
						name: "閉じる", icon: function () {
							return "context-menu-icon context-menu-icon-quit";
						}
					}
				}
			});


		});
	</script>
</head>
<div id='trashzone'>
	<span class="trash ui-widget-header">
	</span>
</div>
<body>
	__SHeader__

	<div class="left-yose">
		<a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜メニュー</a>
	</div>

	<div class="container">
		<!--container-->

		<div class="top-menu left-yose">
			<h5>__wBukkenName__</h5>
			<h6 style="width: 1000px;">部屋番号抽出</h6>


			<!--<a href="s_pic_select.php__QUERY__&editBukkenCD=__editBukkenCD__ ">＞＞写真一括ダウンロード</a>-->

			<font size="4"><B>◆部屋番号</B></font><br>
			<!-- <a href="s_pic.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">画面更新</a><br> -->

			<form method="post" action="s_room_AI_finish.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__" enctype="multipart/form-data">
				__result_table__
				<input type="button" onclick="submit();" value="送信" />
			</form>
			<br>
			<br>
			<br>
			<br>
			<br>
		</div>
		<!--container-->

		<hr>

		__SFooter__
		__SCopyright__

</body>
</html>

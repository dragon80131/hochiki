<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>__TITLENAME__</title>

	<!-- BootstrapのCSS読み込み -->
	<link href="../include/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../css/rnsien.css">
	<link href="../css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />

	<script src="../include/js/jquery-3.2.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>	
	<!-- BootstrapのJS読み込み -->
	<script src="../include/bootstrap/js/bootstrap.min.js"></script>

	<script type="text/javascript" src="../js/tools_ajax.js"></script>
	<script type="text/javascript" src="../js/ConnectedSelect.js"></script>
	<script src="../js/jquery.ui.core.js" type="text/javascript"></script>
	<script src="../js/jquery.ui.datepicker.js" type="text/javascript"></script>
	<script src="../js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
	<script type="text/javascript" src="../tools.js"></script>
	<style>
		.modal-header{
			border-bottom:none;
		}
		.modal-footer{
			border-top:none;
		}
		.modal-body{
			font-size:13px;
			padding:5px 1rem;
		}
		#confirmBtn{
			width:70px;
		}
		.modal-backdrop.show{
			display:none;
			opacity:0;
		}
		.modal-open .modal-backdrop.show{
			display:block;
			opacity:.5;
		}
		@media (min-width: 576px) {
			.modal-dialog {
				max-width: 330px;
			}
		}

	</style>
	<script>
	document.addEventListener('DOMContentLoaded', function () {
        document.cookie = "downloadComplete=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    });
	var bMakeClicked = false;
		$(document).ready(function () {
			$('#alert').on('hidden.bs.modal', function (e) {
				document.location.href = '../s_menu.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__';
			});
		});

		var ReservationCount = parseInt('__ReservationCount__');
		if(isNaN(ReservationCount))
			ReservationCount = 0;

		function modorumove(val) {
			document.form1.action = val;
			document.form1.submit(true);
		}

		function go_confirm(url){
			if(!bMakeClicked){
				if(confirm("新しく作成された作業日程は反映されません。問題ございませんか？")){
					document.location.href = url;
				}
			}else{
				document.location.href = url;
			}
		}

		function makeKanryo(page){
			if(ReservationCount > 0){
				if(!confirm("すでに作業日程が作成されています。住人様が登録した情報等も削除されますが問題ございませんでしょうか。")){
					$('#alert').modal({
						backdrop: false,
						keyboard: false
					});
					return false;
				}
			}
			bMakeClicked = true;
			document.mainform.action = page;
			document.mainform.submit(true);

			const timer = setInterval(function() {
				if (document.cookie.includes('downloadComplete=1')) {
					clearInterval(timer);
					setTimeout(function () {
						document.location.href = '../s_menu.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__';
					}, 200);
				}
			}, 500);

		}

	</script>

</head>

<body>
	__SHeaderKanri__

	<div class="content-all">
		<!--content-all-->
		<form action="s_make_kanryo.php" method="POST" name="form1">
			<!--form1-->
			__HiddenValues__
			<input type="hidden" name="backw" value="1">
			<input type="hidden" name="rKey" value="__rKey__">
			<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
			<input type="hidden" name="editBuildingCD" value="__editBuildingCD__">

			<div class="left-yose d-flex align-items-end">
				<a href="javascript:void(0)" onclick="go_confirm('./s_make_kanryo2.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__')" class="btn btn-primary mb-1 mr-1">もどる</a>
				__IfBuildingExist__
				&nbsp;
				<div class="cur_building_dis mb-1 building_nav">__wBuildingName__</div>
				__IfBuildingExist__
			</div>
		</form>
		<!--/form1-->
		<div class="top-menu left-yose">

			<h5>__BukkenName__</h5>
			<h6>専有部日程案内</h6>


			■詳細工程表を作成します。
			<br>以下の日程計画でよろしければ、[工程表 作成]ボタンをクリックしてください。<br>変更するには、[工程表案 修正]ボタンをクリックしてください。<br>

			<form action="#" method="POST" name="mainform">
	<!--			
				<table border=1>
					<tr>
						<td bgcolor="#CCFF99">工事は何班？</td>
						<td>
							__wHansu__班
						</td>
					</tr>
					<tr id="blockName">
						<td bgcolor="#CCFF99">工事枠パターン</td>
						<td nowrap class="common-list-value-left">
							__wWakuPatternName__
						</td>
					</tr>
					<tr>
						<td bgcolor="#CCFF99">最大工事枠数</td>
						<td>
							__wWakuAMPM__
						</td>
					</tr>

					<tr>
						<td bgcolor="#CCFF99">専有部期間</td>
						<td>__SenyuStartDate__ ～ __SenyuEndDate__
						</td>
					</tr>
					<tr>
						<td bgcolor="#CCFF99">休工日</td>
						<td>
							__wHoliday1__　
							__wHoliday2__　
							__wHoliday3__　
							__wHoliday4__
						</td>
					</tr>
					<tr>
						<td bgcolor="#CCFF99">専有部工事の初日考慮</td>
						<td>
							__FirstDateFeatureDisp__
						</td>
					</tr>
					<tr>
						<td bgcolor="#CCFF99">工事順<br>(詳細工程表の並び順</td>
						<td>

							<table>
								<tr>
									<td align=right><img src="__KojijunImg__" width="50">__KojijunDisp__
									</td>
								</tr>
							</table>

						</td>
					</tr>
				</table>
			-->

				<br>__IfError__<font color=red>※すべての部屋を組み込むことができませんでした。編集ボタンより調整してください。</font>__IfError__<br>
				__Koteihyou__

				<br>
				<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
				<input type="hidden" name="editBuildingCD" value="__editBuildingCD__">
				<input type="hidden" name="rKey" value="__rKey__">

				<input type="hidden" name="wHansu" value="__wHansu__">
				<input type="hidden" name="wWakuPattern" value="__wWakuPattern__">
				<input type="hidden" name="wFrameOverflow" value="__wFrameOverflow__">

				<input type="hidden" name="wWakuAM" value="__wWakuAM__">
				<input type="hidden" name="wWakuAM2" value="__wWakuAM2__">
				<input type="hidden" name="wWakuAM3" value="__wWakuAM3__">
				<input type="hidden" name="wWakuPM" value="__wWakuPM__">
				<input type="hidden" name="wWakuPM1" value="__wWakuPM1__">
				<input type="hidden" name="wWakuPM2" value="__wWakuPM2__">
				__wHolidayHTML__
				__wReserveDayHTML__
				<input type="hidden" name="wFirstDateFeature" value="__wFirstDateFeature__">
				<input type="hidden" name="wKojijun" value="__wKojijun__">

				<input type="hidden" name="wWakuAMcol" value="__wWakuAMcol__">
				<input type="hidden" name="wWakuAM2col" value="__wWakuAM2col__">
				<input type="hidden" name="wWakuAM3col" value="__wWakuAM3col__">
				<input type="hidden" name="wWakuPM1col" value="__wWakuPM1col__">
				<input type="hidden" name="wWakuPM2col" value="__wWakuPM2col__">
				<input type="hidden" name="wKoteihyouEX" value="__wKoteihyouEX__">
				<input type="hidden" name="wHansuEX" value="__wHansuEX__">
				<input type="hidden" name="RowsLoop" value="__RowsLoop__">
				<input type="hidden" name="wHoliday1" value="__wHoliday1__">
				<input type="hidden" name="wShukujitucolor" value="__wShukujitucolor__">
				<input type="hidden" name="wKyukobi" value="__wKyukobi__">
				<input type="hidden" name="wKaiRoom3" value="__wKaiRoom3__">
				<input type="hidden" name="wMaxWakuSu" value="__MaxWakuSu__">
				<input type="hidden" name="rowCountforDay" value="__rowCountforDay__">
				<input type="hidden" name="wArrangeType" value="__wArrangeType__">
				<input type="hidden" name="FloorReserveInfo" value="__FloorReserveInfo__">
				<table>
					<tr>
						<td style="width:200px">
							<input type="button" class="btn btn-success" onclick="makeKanryo('s_make_kotei_EXCEL.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__' )" value="工程表 作成" __SakuseiDisabled__>
						</td>
						<td>
							<input type="submit" class="btn btn-success" onclick="javascript:move('s_make_kanryo_hensyu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__' )" value="工程表案 修正">
						</td>
						<!--<td>
			<input type="submit" class="btn btn-primary" onclick="javascript:move('s_make_kotei_finish.php?editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__' )" value="登録（準備中）">
		</td>-->
					</tr>
					<tr>
						<td colspan="3">
							<br><br>
							<font color="red" size="4"><b>
									<br>
									本機能で作成された作業日程は、システムに登録され、入居者様向けWEB予約受付の日程として利用されます。<br>
								</b></font>
							<!-- 詳細工程表作成後に、部屋移動をする場合はこちらから修正し、ダウンロードしてください↓<br>
							<button type="button" onclick="javascript:move('s_make_kojidate.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__' )" class="btn btn-Success">部屋ごとの修正</button> -->
						</td>
					</tr>
				</table>


			</form>
			<!--mainform-->
			<br>
			　
			__IfNespe__
			<hr>
			<ネスぺ社員のみ表示><br>
				<input type="submit" onclick="javascript:move('s_make_yotei_EXCEL.php?editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__' )" value="予定案内">
				__IfNespe__


				<hr>
			<div class="d-flex flex-row align-items-center">
				<input type="button" value="作業日程登録" onclick="go_confirm('./s_make_kanryo2.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__')" class="btn btn-brown"><br>
				<input type="button" value="メニューに戻る" onclick="go_confirm('../s_menu.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__' )" class="btn btn-info ml-2"><br>
			</div>

		</div>
		<div class="modal fade" id="alert" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="modalTitle">通知</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					
					<div class="modal-body">
						キャンセルされました。
					</div>
					
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" id="confirmBtn" data-dismiss="modal">OK</button>
					</div>
				
				</div>
			</div>
		</div>
		<div class="modal-backdrop fade show"></div>
		<!--content-all-->



		__SFooter__
		__SCopyright__

</body>
</html>

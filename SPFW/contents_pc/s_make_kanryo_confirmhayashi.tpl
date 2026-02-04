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

	<link href="../css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../js/tools_ajax.js"></script>
	<script type="text/javascript" src="../js/ConnectedSelect.js"></script>
	<script src="../js/jquery-1.4.2.js" type="text/javascript"></script>
	<script src="../js/jquery.ui.core.js" type="text/javascript"></script>
	<script src="../js/jquery.ui.datepicker.js" type="text/javascript"></script>
	<script src="../js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>

	<script type="text/javascript">

	</script>

	<script>
		function modorumove(val) {
			document.form1.action = val;
			document.form1.submit(true);
		}
	</script>

</head>

<body>
	__SHeader__

	<div class="content-all">
		<!--content-all-->
		<form action="s_make_kanryohayashi.php" method="POST" name="form1">
			<!--form1-->
			__HiddenValues__
			<input type="hidden" name="backw" value="1">
			<input type="hidden" name="rKey" value="__rKey__">
			<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
			<div class="left-yose">
				<a href="#" onclick="modorumove('s_make_kanryohayashi.php');">＜戻る</a>
			</div>
		</form>
		<!--/form1-->
		<div class="top-menu left-yose">

			<h5>__BukkenName__</h5>
			<h6>専有部日程案内</h6>


			■詳細工程表を作成します。
			<br>以下の条件でよろしければ［詳細工程表作成］ボタンをクリックしてください。<br>変更するには、［編集］ボタンをクリックしてください。<br>

			<form action="s_make_schedule.php" method="POST" name="mainform">
				<!--mainform-->
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
					<!--
<tr><td bgcolor="#CCFF99" >実際、工事は1戸あたり何分？</td>
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
				<br>
				■詳細工程表（イメージ）<br>
				<br>__IfError__<font color=red>※すべての部屋を組み込むことができませんでした。編集ボタンより調整してください。</font>__IfError__ 下記 __m__戸分<br>
				__Koteihyou__

				<br>
				<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
				<input type="hidden" name="rKey" value="__rKey__">
				<input type="hidden" name="wWakuPattern" value="__wWakuPattern__">
				<input type="hidden" name="wWakuAM" value="__wWakuAM__">
				<input type="hidden" name="wWakuAM2" value="__wWakuAM2__">
				<input type="hidden" name="wWakuAM3" value="__wWakuAM3__">
				<input type="hidden" name="wWakuAM4" value="__wWakuAM4__">

				<input type="hidden" name="wWakuPM1" value="__wWakuPM1__">
				<input type="hidden" name="wWakuPM2" value="__wWakuPM2__">
				<input type="hidden" name="wWakuPM3" value="__wWakuPM3__">
				<input type="hidden" name="wWakuPM4" value="__wWakuPM4__">
				<input type="hidden" name="wWakuPM5" value="__wWakuPM5__">
				<input type="hidden" name="wWakuPM6" value="__wWakuPM6__">
				<input type="hidden" name="wWakuAMcol" value="__wWakuAMcol__">
				<input type="hidden" name="wWakuAM2col" value="__wWakuAM2col__">
				<input type="hidden" name="wWakuAM3col" value="__wWakuAM3col__">
				<input type="hidden" name="wWakuAM4col" value="__wWakuAM4col__">
				<input type="hidden" name="wWakuPM1col" value="__wWakuPM1col__">
				<input type="hidden" name="wWakuPM2col" value="__wWakuPM2col__">
				<input type="hidden" name="wWakuPM3col" value="__wWakuPM3col__">
				<input type="hidden" name="wWakuPM4col" value="__wWakuPM4col__">
				<input type="hidden" name="wWakuPM5col" value="__wWakuPM5col__">
				<input type="hidden" name="wWakuPM6col" value="__wWakuPM6col__">
				<input type="hidden" name="wKoteihyouEX" value="__wKoteihyouEX__">
				<input type="hidden" name="wHansu" value="__wHansu__">
				<input type="hidden" name="RowsLoop" value="__RowsLoop__">
				<input type="hidden" name="wHoliday1" value="__wHoliday1__">
				<input type="hidden" name="wShukujitucolor" value="__wShukujitucolor__">
				<input type="hidden" name="wKyukobi" value="__wKyukobi__">
				<input type="hidden" name="wKaiRoom3" value="__wKaiRoom3__">
				<input type="hidden" name="wMaxWakuSu" value="__MaxWakuSu__">
				<table>
					<tr>
						<td style="width:200px">
							<input type="submit" class="btn btn-primary" onclick="javascript:move('s_make_kotei_EXCELhayashi.php?editBukkenCD=__editBukkenCD__' )" value="詳細工程表作成" __SakuseiDisabled__>
						</td>
						<td>
							<input type="submit" class="btn btn-primary" onclick="javascript:move('s_make_kanryo_hensyuhayashi.php?editBukkenCD=__editBukkenCD__' )" value="編集">
						</td>
						<!--<td>
			<input type="submit" class="btn btn-primary" onclick="javascript:move('s_make_kotei_finish.php?editBukkenCD=__editBukkenCD__' )" value="登録（準備中）">
		</td>-->
					</tr>
					<tr>
						<td colspan="3">
							<br><br>
							<font color="red" size="4"><b>
									※お願い※<br>
									作成済のエクセルの詳細工程表で、部屋の移動を行わないでください。<br>
								</b></font>
							詳細工程表作成後に、部屋移動をする場合はこちらから修正し、ダウンロードしてください↓<br>
							<button type="button" onclick="javascript:move('s_make_kojidate.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__' )" class="btn btn-Success">部屋ごとの修正</button>
						</td>
					</tr>
				</table>


			</form>
			<!--mainform-->
			<br>
			　
			__IfNespe__
			<hr>
			<ネスぺ社員のみ表示><br>
				<input type="submit" onclick="javascript:move('s_make_yotei_EXCEL.php?editBukkenCD=__editBukkenCD__' )" value="予定案内">
				__IfNespe__


				<hr>
				<input type="button" value="メニューへもどる" onclick="javascript:move('../s_menu.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__' )" class="btn btn-info"><br>

		</div>
		<!--content-all-->



		__SFooter__
		__SCopyright__

</body>
</html>

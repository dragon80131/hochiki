<html>

<head>
	<title>月別スケジュール</title>
	<!-- BootstrapのCSS読み込み -->
    <link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

	<link rel="stylesheet" type="text/css" href="./css/rnsien.css" />
	<script type="text/javascript" src="tools.js"></script>
	<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1" />


	<style type="text/css">
		/* 表示文字の装飾 */
		div.info {
			color: #555;
			display: inline-block;
			/* インライン要素化 */
			border-bottom: dashed 1px #555;
			/* 下線を引く */
		}

		/* ツールチップ部分を隠す */
		div.info span {
			display: none;
		}

		/* マウスオーバー */
		div.info:hover {
			position: relative;
			color: #333;
		}

		/* マウスオーバー時にツールチップを表示 */
		div.info:hover span {
			display: block;
			/* ボックス要素にする */
			position: absolute;
			/* relativeからの絶対位置 */
			top: -5px;
			left: 100px;
			font-size: 90%;
			color: #fff;
			background-color: #51A2C1;
			width: 205px;
			padding: 5px;
			border-radius: 3px;
			z-index: 100;
		}

		/* フキダシ部分を作成 */
		div.info span:before {
			content: '';
			display: block;
			position: absolute;
			/* relativeからの絶対位置 */
			height: 0;
			width: 0;
			top: 5px;
			left: -10px;
			border: 10px transparent solid;
			border-right-width: 0;
			border-left-color: #51A2C1;
			transform: rotate(180deg);
			/* 傾きをつける */
			-webkit-transform: rotate(180deg);
			-o-transform: rotate(180deg);
			z-index: 100;
		}

		.sampleTable2 table {
			border-collapse: collapse;
			border-spacing: 0;
		}

		.sampleTable2 tr,
		td {
			border-collapse: collapse;
			border-spacing: 0;
			margin: 0;
			padding: 0;
		}

		.sampleTable2 thead {
			position: -webkit-sticky;
			position: sticky;
			top: 0;
			z-index: 1;
		}

		.sampleTable2 thead:before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			border-left: 1px solid #AAAAAA;
			border-bottom: 1px solid #AAAAAA;
		}

		.submited {
			background-color: darkgray;
		}
	</style>


</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
	__SHeader__
	<div class="content-all">


	__IfMenu__
	<a href="ns_genbalist.php__QUERY__">■現場一覧</a>　
	<a href="ns_schedule_list.php?rKey=__rKey__">■スケジュール修正</a><br>

	__IfMenu__
	<br>
	<a href="s_search.php?rKey=__rKey__" class="btn btn-info">トップ</a><br>
	<p class="mintitle">月間スケジュール</p>
	<!--<table>
		<tr valign="top">
			<td>-->

				<form action="ns_monthly.php" method="POST">
					<input type="hidden" name="rKey" value="__rKey__">

					<select name="yearlist">
						__YearLoop__<option value="__year__" __yearselected__>__year__</option>__YearLoop__
					</select>年

					<select name="monthlist">
						__MonthLoop__<option value="__month__" __monthselected__>__month__</option>__MonthLoop__
					</select>月

					<input type="submit" value=" 表示 ">
				</form>

			<!--</td>
			 <td>
				　★・・・要相談
				　×・・・休日　
				　MAPのみ：<font color="orange">■</font>オレンジ
				　設定のみ：<font color="limegreen">■</font>黄緑
				　MAP+設定：<font color="blue">■</font>青
				　設定+ポート収容表：<font color="yellow">■</font>黄色
			</td> 
		</tr>
	</table>-->


	<div class="sampleTable2">
		<table class="table table-bordered table-sm" width="100%" style="font-size:90%">
			<thead>
				<tr bgcolor="lightgrey">
					<!-- <td class="sample" rowspan="3">CD</td> -->
					<td class="sample" rowspan="3">現場名</td>
					<td class="sample" rowspan="3">協力会社</td>
					<td class="sample sp-d-none" rowspan="3">担当者</td>
					<td class="sample sp-d-none" rowspan="3">担当者2</td>

					<td class="sample" colspan="__daycount__" width="70%">__tYear__　年__tMonth__　月</td>
				</tr>

				<tr>__DayLoop__<td class="sample" bgcolor="__dc__" style="padding:0px; text-align:center;">__dd__</td>__DayLoop__</tr>
				<tr>__WeekLoop__<td class="sample" bgcolor="__dc__" style="padding:0px; text-align:center;">__dw__</td>__WeekLoop__</tr>

			</thead>
			__BukkenLoops__
			<tr>
				<!-- <td>__BukkenCD__</td> -->
				<td class="__Progress__">
					<div class="info">
						<a href="s_date2.php?rKey=__rKey__&editBukkenCD=__BukkenCD__" class="__Progress__">__BukkenName__</a>
						<span>戸数：__Kosu__<br>__StatusName__<br>機器：__KikiTenkenMonth__月　総合:__SougouTenkenMonth__月
						</span>
					</div>
				</td>
				<td>__GyosyaName__</td>
				<td class="sp-d-none">__TantoName1__</td>
				<td class="sp-d-none">__TantoName2__</td>

				<td bgcolor="__Bcolor01__"></td>
				<td bgcolor="__Bcolor02__"></td>
				<td bgcolor="__Bcolor03__"></td>
				<td bgcolor="__Bcolor04__"></td>
				<td bgcolor="__Bcolor05__"></td>
				<td bgcolor="__Bcolor06__"></td>
				<td bgcolor="__Bcolor07__"></td>
				<td bgcolor="__Bcolor08__"></td>
				<td bgcolor="__Bcolor09__"></td>
				<td bgcolor="__Bcolor10__"></td>
				<td bgcolor="__Bcolor11__"></td>
				<td bgcolor="__Bcolor12__"></td>
				<td bgcolor="__Bcolor13__"></td>
				<td bgcolor="__Bcolor14__"></td>
				<td bgcolor="__Bcolor15__"></td>
				<td bgcolor="__Bcolor16__"></td>
				<td bgcolor="__Bcolor17__"></td>
				<td bgcolor="__Bcolor18__"></td>
				<td bgcolor="__Bcolor19__"></td>
				<td bgcolor="__Bcolor20__"></td>
				<td bgcolor="__Bcolor21__"></td>
				<td bgcolor="__Bcolor22__"></td>
				<td bgcolor="__Bcolor23__"></td>
				<td bgcolor="__Bcolor24__"></td>
				<td bgcolor="__Bcolor25__"></td>
				<td bgcolor="__Bcolor26__"></td>
				<td bgcolor="__Bcolor27__"></td>
				<td bgcolor="__Bcolor28__"></td>
				__Ifday29__ <td bgcolor="__Bcolor29__"></td>__Ifday29__
				__Ifday30__ <td bgcolor="__Bcolor30__"></td>__Ifday30__
				__Ifday31__ <td bgcolor="__Bcolor31__"></td>__Ifday31__

			</tr>
			__BukkenLoops__

		</table>
	</div>
	</div>

	__SFooter__

</body>

</html>
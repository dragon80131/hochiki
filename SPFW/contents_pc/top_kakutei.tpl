<html>

<head>
	<script type="text/javascript" src="../../html5jp/graph/circle.js"></script>
	<script type="text/javascript" src="../../tools.js"></script>
	<script type="text/javascript">
		window.onload = function () {
			var cg = new html5jp.graph.circle("sample");
			if (!cg) {
				return;
			}
			var items = [
				["日程変更", __CountTa__],
				["日時確認", __CountTb__],
				["オプション", __CountTc__],
				["工事に関する", __CountTd__],
				["機器に関する", __CountTe__],
				["不在表", __CountTf__],
				["その他", __CountTg__]
			];
			cg.draw(items);

			var cg2 = new html5jp.graph.circle("sample2");
			if (!cg2) {
				return;
			}
			var items2 = [
				["完了", __Donichi__],
				["未完了", __DonichiIgai__]
			];
			cg2.draw(items2);

		};
	</script>


	<link rel="stylesheet" type="text/css" href="css/common.css">
	<link rel="stylesheet" type="text/css" href="css/style_okyakusama.css">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<!--最小限のビューポート設定-->
	<meta name="viewport" content="width=device-width">

	<title>予約システムTOP</title>
</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
	<table>

		<div id="navi">
			<a class="bar-migi" href="logout.php__QUERY__">ログアウト</a>
		</div>

		__SHeader__

		<h1>__MansionName__ &nbsp;__wBuildingName__</h1>
		<h2 class="tophaba">__wwID__号室様</h2>

		<H2>
			__IfWebBiko____WebBiko____IfWebBiko__
		</H2>

		<!--▽利用マニュアルボタン-->
		<!-- <div class="manu">
			<a href="https://www2.489501.jp/index4.html" target="_blank"><img src="./images/manu.png"></a>
		</div> -->
		<!--▽利用マニュアルボタン-->


		<!--▽仮日程ありの場合の表示-->

		__IfReservation__
		__IfConfirm__
		<h3>現在の予約</h3>
		<p class="menu">
			__DispReservationDate__(__w1__)&nbsp;__Reservationtime__<br>
		</p>
		__IfConfirm__
		__IfDecline__
		<h3>現在の予約</h3>
		<p class="menu">
			辞退
		</p>
		__IfDecline__
		__IfReservation__





		__IfNew__

		<!--▽OPあるときのボタン-->


		__IfDAYOPEN__
		<p>
			<font color=red>オプションの受付は終了しました。<br>
				現在、日程予約のみ受け付けております。</font>
		</p>
		<div class="menu-btn-noop">
			__IfReservation__
			<a href="reserve_list.php__QUERY__&wLang=__wLang__&btnflg=1"><img src="./images/nittei1.png" class="nittei-btn-dai"></a>
			__IfReservation__
			__IfNoReservation__
			<a href="reserve_form_kakutei.php__QUERY__&wLang=__wLang__&btnflg=1"><img src="./images/nittei1.png" class="nittei-btn-dai"></a>
			__IfNoReservation__
		</div>
		__IfDAYOPEN__


		__IfOPEN__
		
		<div class="menu-btn-noop">
			__IfReservation__
				__IfConfirm__
				<a href="reserve_list.php__QUERY__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__&wLang=__wLang__&btnflg=1" class="schedule-btn">
					日程変更
				</a>
				__IfConfirm__
				__IfNotConfirm__
				<a href="reserve_list.php__QUERY__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__&wLang=__wLang__&btnflg=1" class="schedule-btn">
					日程予約
				</a>
				__IfNotConfirm__
				__IfDecline__
				<a href="reserve_form_kakutei.php__QUERY__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__&wLang=__wLang__&btnflg=1" class="schedule-btn">
					日程再予約
				</a>
				__IfDecline__
			__IfReservation__
			__IfNoReservation__
			<a href="reserve_form_kakutei.php__QUERY__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__&wLang=__wLang__&btnflg=1" class="schedule-btn">
				日程予約
			</a>
			__IfNoReservation__
		</div>
		__IfOPEN__


		__IfDAYCLOSE__
		__IfReservation__
		<p>
			<font color=red>日程予約受付は終了しました。<br>
				現在、オプション予約のみ受け付けております。</font>
		</p>
		<div class="menu-btn-noop">
			<a href="reserve_formOP.php__QUERY__&wLang=__wLang__&btnflg=2"><img src="./images/op1.png" class="nittei-btn-dai"></a>
		</div>
		__IfReservation__
		__IfNoReservation__
		<p>
			<font color=red>日程予約受付は終了しました。<br>
				現在、オプション予約のみ受け付けておりますが、日程登録をされていない方はWEBからのお申込みができません。資料記載のフリーダイヤルにて受け付けております。</font>
		</p>
		__IfNoReservation__
		__IfDAYCLOSE__


		__IfCLOSE__
		<p>
			<font color=red>インターネットからの受付は終了しました。<br>
				ご相談は、資料記載のフリーダイヤルにて受け付けております。</font>
		</p>
		__IfCLOSE__



		<!--▽工事資料、取付機器、FAQボタン-->
		<div class="sonotamenu-btn">
			__IfkikiShow__
			<a href="device.php__QUERY__"><img src="./images/kiki.png" class="kiki-btn"></a>
			__IfkikiShow__
			__IfAppUsage__
			<a href="faq.php__QUERY__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__"><img src="./images/faq.png" class="faq-btn"></a>
			__IfAppUsage__
		</div>
		<!--△△工事資料、取付機器、FAQボタン---->

		<p>
			__IfJoho__<a href="form.php__QUERY__&CustomerEdit=1&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__">
				<font size=4>※お客様情報を修正される場合はこちら</font>
			</a>__IfJoho__
		</p>



		<!--▽工事期間-->
		__IfNotPostponed__
		<h3>作業期間</h3>
		<p>
			__IfKyoyuStartDate__
			共用部期間：__DispKyoyuStartDate__(__w2__)～
			__DispKyoyuEndDate__(__w3__)<br>
			<!--共用部なければ非表示-->
			__IfKyoyuStartDate__

			専有部期間：__DispSenyuStartDate__(__w4__)～
			__DispSenyuEndDate__(__w5__)<br>

			予約受付期限：__DispYoyakuEndDate__
		</p>
		<p class="menu-saigo">※休工日については配布資料をご確認ください。</p>
		__IfNotPostponed__
		<!--△工事期間-->





		__IfKakuopen__
		<!--▽完了書 サンプルマンション2参考　受領済になれば表示-->
		<h3>工事完了確認書</h3>

		<div class="kanryou">
			__Kakunin__
		</div>

		<!--△完了書 サンプルマンション2参考-->

		<div class="out">
			<a href="logout.php?rKey=__rKey__" class="tophe-btn">ログアウト</a>
		</div>

		<!--▲完了書、工事写真ダウンロード-->
		__IfKakuopen__


		__SFooter__

		__SCopyright__



	</table>
</body>

</html>
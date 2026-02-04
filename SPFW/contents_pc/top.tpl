<html>

<head>
	<script type="text/javascript" src="../../html5jp/graph/circle.js"></script>
	<script type="text/javascript" src="../../tools.js"></script>
	<script type="text/javascript">
		window.onload = function() {
			var disp = "__disp__";
			if (disp == 1) {
				document.getElementById("disp").style.display = "none";
			}

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





		function Questionnaire(url, compflg) {
			if (compflg == 3) { //専有部最終日から2週間経過
				alert("回答締切日が過ぎているため回答できません。");
				return false;
			} else if (compflg == 2) { //工事の翌日
				alert("工事完了後にご回答ください");
				return false;
			} else { //回答可能
				window.document.mainform.action = "https://app4.489501.jp/aas_arp/" + url;
				window.document.mainform.target = "_blank";
				window.document.mainform.method = "POST";
				window.document.mainform.submit();
			}
		}
	</script>


	<link rel="stylesheet" type="text/css" href="css/common.css">
	<link rel="stylesheet" type="text/css" href="css/style_okyakusama.css">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<!--最小限のビューポート設定-->
	<meta name="viewport" content="width=device-width">

	<title>ReservatinSystem</title>
</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
	<table>

		<div id="navi">
			<a class="bar-migi" href="logout.php__QUERY__">__logout1__
				<!--ログアウト-->
			</a>
		</div>

		__SHeader__

		<h1>__MansionName__</h1>
		<h2 class="tophaba">__top1__ &nbsp <!--号室 -->__wwID__ </h2>

		<!--<H2>
			<font color="red">新型コロナウイルスにより、工事が緊急に中止、延期する可能性があります。<br>その場合は、工事が予定どおり行えませんのでご了承お願いします。</font>
		</H2>-->

		__IfWebBiko__
		<H2>
			__WebBiko__
		</H2>
		__IfWebBiko__

		<!--▽利用マニュアルボタン
		<div class="manu">
			<a href="https://www2.489501.jp/index4.html" target="_blank"><img src="./images/__btn_manual__"></a>
		</div>
		▽利用マニュアルボタン-->

		<!--▽仮日程ありの場合の表示-->

		__IfReservation__
		<h3>__top4__<!--ご予約内容--></h3>
		<p class="menu">
			__IfNotPostponed__
			__IfTimeFrom__
			__IfBeforeKetteiTeikyo__ __reserve_list2__<!--仮日程日-->：__IfBeforeKetteiTeikyo__
			__IfAfterKetteiTeikyo__　__reserve_list5__<!--工事日-->：__IfAfterKetteiTeikyo__

			__DispReservationDate__
			(__w1__) __Reservationtime__<br>
			__IfTimeFrom__

			__IfBeforeKetteiTeikyo__（ __top13__<!--調整が済みましたら決定通知を配布致しますので、書面にてご確認お願い致します。--> ）<br>__IfBeforeKetteiTeikyo__
			__IfNotPostponed__

		</p>
		__IfReservation__
		__WebDisp__


		__IfPostponed__
		<p style="color:red;">
			現在、インターネットでの日程受付を行っておりません。
		</p>
		__IfPostponed__


		__IfNew__

		<!--▽OPあるときのボタン-->
		__IfDAYOPOPEN__
		あああ
		<div class="menu-btn-noop">
			__IfNoReservation__
			<a href="reserve_form.php__QUERY__&wLang=__wLang__&btnflg=1"><img src="./images/__nittei1_png__" class="nittei-btn-dai"></a>

			__IfNoReservation__
		</div>
		<div class="menu-btn">
			__IfReservation__
			<a href="reserve_list.php__QUERY__&wLang=__wLang__&btnflg=1"><img src="./images/__nittei_png__" class="nittei-btn"></a>
			<a href="reserve_formOP.php__QUERY__&wLang=__wLang__&btnflg=2"><img src="./images/__op_png__" class="op-btn"></a>
			__IfReservation__
		</div>
		__IfDAYOPOPEN__


		__IfDAYOPCLOSE__
		<p>
			<font color=red> __top6__ <!--インターネットからの受付は終了しました。--><br>
				__top7__ <!--ご相談は、資料記載のフリーダイヤルにて受け付けております。--></font>
		</p>
		__IfDAYOPCLOSE__


		__IfDAYOPEN__

		__IfOpAri__
		<p>
			<font color=red id="disp">__top14__ <!--オプションの受付は終了しました。<br>
				現在、日程予約のみ受け付けております。--></font>
		</p>
		__IfOpAri__
		<div class="menu-btn-noop">
			__IfReservation__
			__IfTimeFrom__
			<a href="reserve_list.php__QUERY__&wLang=__wLang__&btnflg=1"><img src="./images/__nittei1_png__" class="nittei-btn-dai"></a>
			__IfTimeFrom__
			__IfKaraOpSenkou__
			<a href="reserve_form.php__QUERY__&wLang=__wLang__&btnflg=1"><img src="./images/__nittei1_png__" class="nittei-btn-dai"></a>
			__IfKaraOpSenkou__			
			__IfReservation__
			
			__IfNoReservation__
			<a href="reserve_form.php__QUERY__&wLang=__wLang__&btnflg=1"><img src="./images/__nittei1_png__" class="nittei-btn-dai"></a>
			__IfNoReservation__
		</div>
		__IfDAYOPEN__


		__IfDAYOPENnoop__
		<div class="menu-btn-noop">
			__IfReservation__
			<a href="reserve_list.php__QUERY__&wLang=__wLang__&btnflg=1"><img src="./images/__nittei1_png__" class="nittei-btn-dai"></a>
			__IfReservation__
			__IfNoReservation__
			<a href="reserve_form.php__QUERY__&wLang=__wLang__&btnflg=1"><img src="./images/__nittei1_png__" class="nittei-btn-dai"></a>
			__IfNoReservation__
		</div>
		__IfDAYOPENnoop__


		__IfDAYCLOSE__
		__IfReservation__
		<p>
			<font color=red>__top15__ <!--日程予約受付は終了しました。<br>
				現在、オプション予約のみ受け付けております。--></font>
		</p>
		<div class="menu-btn-noop">
			<a href="reserve_formOP.php__QUERY__&wLang=__wLang__&btnflg=2"><img src="./images/__op1_png__" class="nittei-btn-dai"></a>
		</div>
		__IfReservation__
		__IfNoReservation__
		<p>
			<font color=red>__top16__ <!--日程予約受付は終了しました。<br>
				現在、オプション予約のみ受け付けておりますが、日程登録をされていない方はWEBからのお申込みができません。資料記載のフリーダイヤルにて受け付けております。--></font>
		</p>
		__IfNoReservation__
		__IfDAYCLOSE__


		__IfDAYCLOSEnoop__
		<p>
			<font color=red>__top17__ <!--インターネットからの受付は終了しました。<br>
				ご相談は、資料記載のフリーダイヤルにて受け付けております。--></font>
		</p>
		__IfDAYCLOSEnoop__


		__IfQuest__
		<form action="#" name="mainform" method="POST">
			<div class="menu-btn"><!--工事後アンケート-->
				__IfReservation__
				<a href="#" onclick="javascript:Questionnaire('an_form.php?rKey=__rKey__&editClientCD=__MyClientCD__&an=1','__CompKojiDisp__')"><img src="./images/questionnaire.png" class="nittei-btn-dai"></a>
				__IfReservation__
			</div>
		</form>
		__IfQuest__


		__IfKojiMovieShow__
		<div class="sonotamenu-btn2">
			<a href="kojimovie.php__QUERY__"><img src="./images/__kojimovie_png__" class="movie-btn"></a>
		</div>
		__IfKojiMovieShow__

		<!--▽工事資料、取付機器、FAQボタン-->
		<div class="sonotamenu-btn">
			<a href="koji.php__QUERY__"><img src="./images/__siryou_png__" class="siryou-btn"></a>
			__IfkikiShow__
			<a href="device.php__QUERY__"><img src="./images/__kiki_png__" class="kiki-btn"></a>
			__IfkikiShow__
			__IfAppUsage__
			<a href="faq.php__QUERY__"><img src="./images/__faq_png__" class="faq-btn"></a>
			__IfAppUsage__
		</div>
		<!--△工事資料、取付機器、FAQボタン---->

		<p>
			__IfOPOPEN2__
			__IfJoho__<a href="form.php__QUERY__&CustomerEdit=1">
				<font size=4>__top8__ <!--※お客様情報を修正される場合はこちら--></font>
			</a>__IfJoho__
			__IfOPOPEN2__
		</p>



		__IfPicopen__
		<!--▼完了書、施工写真ダウンロード-->
		<p>__top18__ <!--工事の施工写真、工事完了確認書をご確認いただけます。各項目の画像をクリックすると拡大されます。--></p>

		<!--▽施工写真 サンプルマンション2参考　施工写真投入されれば表示-->
		<h3>__top20__ <!--施工写真--></h3>
		<p class="kiki">__top21__ <!--■室内親機--></p>

		<div class="sekowaku">

			<div class="mae_waku">
				<div class="mae_midasi">__top23__
					<!--施工前-->
				</div>
				<div class="photo_waku">
					<div class="mae1">__IfMae1____Oyamae1____IfMae1__</div>
					<div class="mae2">__IfMae2____Oyamae2____IfMae2__</div>
				</div>
			</div>

			__IfChu__
			<div class="naka_waku">
				<div class="naka_midasi">__top24__
					<!--施工中-->
				</div>
				<div class="photo_waku">
					<div class="naka1">__IfChu1____Oyachu1____IfChu1__</div>
					<div class="naka2">__IfChu2____Oyachu2____IfChu2__</div>
				</div>
			</div>
			__IfChu__

			<div class="ato_waku">
				<div class="ato_midasi">__top25__
					<!--施工後-->
				</div>
				<div class="photo_waku">
					<div class="ato1">__IfGo1____Oyago1____IfGo1__</div>
					<div class="ato2">__IfGo2____Oyago2____IfGo2__</div>
				</div>
			</div>

		</div>
		<div class="kaizyo"></div>

		<p class="kiki">__top22__ <!--■玄関子機--></p>
		<div class="sekowaku">

			<div class="mae_waku">
				<div class="mae_midasi">__top23__
					<!--施工前-->
				</div>
				<div class="photo_waku">
					<div class="mae1">__IfMae3____Komae1____IfMae3__</div>
					<div class="mae2">__IfMae4____Komae2____IfMae4__</div>
				</div>
			</div>

			__IfChu__
			<div class="naka_waku">
				<div class="naka_midasi">__top24__
					<!--施工中-->
				</div>
				<div class="photo_waku">
					<div class="naka1">__IfChu3____Kochu1____IfChu3__</div>
					<div class="naka2">__IfChu4____Kochu2____IfChu4__</div>
				</div>
			</div>

			__IfChu__
			<div class="ato_waku">
				<div class="ato_midasi">__top25__
					<!--施工後-->
				</div>
				<div class="photo_waku">
					<div class="ato1">__IfGo3____Kogo1____IfGo3__</div>
					<div class="ato2">__IfGo4____Kogo2____IfGo4__</div>
				</div>
			</div>

			<div class="kaizyo"></div>

		</div>
		<!--△施工写真 サンプルマンション2参考-->
		__IfPicopen__


		__IfKakuopen__
		<!--▽完了書 サンプルマンション2参考　受領済になれば表示-->
		<h3>__top26__ <!--工事完了確認書--></h3>

		<div class="kanryou">
			__Kakunin__
		</div>

		<!--△完了書 サンプルマンション2参考-->

		<div class="out">
			<a href="logout.php?rKey=__rKey__" class="tophe-btn">__logout1__
				<!--ログアウト-->
			</a>
		</div>

		<!--▲完了書、工事写真ダウンロード-->
		__IfKakuopen__


		__SFooter__

		__SCopyright__



	</table>
</body>

</html>
<html>

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width">
	<!-- jQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

	<!-- BootStrap -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="css/common.css">
	<link rel="stylesheet" type="text/css" href="css/style_seko.css">

	<title>作業工程表</title>

	<script>
		function moveWithUserCD(page, uCD) {
			document.mainform.uCD3.value = uCD;
			document.mainform.action = page;
			document.mainform.submit(true);
		}


		$(function () {
			__AddressPackLoop__
			$('#a__AddressNo__').on('click', function () {
				var text = $(this).text();

				if (!text.includes('不在')) {
					if (!confirm('緊急連絡先から削除します。よろしいですか？')) {
						return false;
					} else {
						//ajaxでDB書込み
						let bAddressNoFalse = document.getElementById('b__AddressNo__').value;

						var aaa = {};
						aaa['cAddressNoFalse'] = bAddressNoFalse;
						$.ajax({
							type: 'POST',
							url: '../../sk/d2b/upload_andSF.php',
							dataType: "json",
							data: aaa
						}).done(function (response) {
							if (response == "1") {
								//alert("緊急連絡先から削除。");
							}
						}).fail(function (xhr) {
							alert("エラー１");
						}).always(function (xhr, msg) {
							location.reload();
						});

						$(this).text('__SekoLastName__(不在)');
					}

				} else {
					if (!confirm('緊急連絡先に戻します。よろしいですか？')) {
						return false;
					} else {
						//ajaxでDB書込み
						let bAddressNoTrue = document.getElementById('b__AddressNo__').value;
						var aaa = {};
						aaa['cAddressNoTrue'] = bAddressNoTrue;
						$.ajax({
							type: 'POST',
							url: '../../sk/d2b/upload_andSF.php',
							dataType: "json",
							data: aaa
						}).done(function (response) {
							if (response == "1") {
								//alert("緊急連絡先にもどる。");
							}
						}).fail(function (xhr) {
							alert("エラー２");
						}).always(function (xhr, msg) {
							location.reload();
						});

						$(this).text('__SekoLastName__');
					}

				}
			});

			__AddressPackLoop__
		});

		$(function () {
			$('#ReserveUpdatedHistoryButton').on('click', function () {
				var text = $(this).text();
				if (text == '受付締切後の日程調整履歴を表示') {
					$(this).text('受付締切後の日程調整履歴を非表示');
				} else {
					$(this).text('受付締切後の日程調整履歴を表示');
				}
				$('#ReserveUpdatedHistory').toggle();
			});
		});

		$(function () {
			$('.RirekiModalTrigger').on('click', function () {
				let HiddenUserCD = $(this).next('.HiddenUserCD').val();

				if (HiddenUserCD) {
					//選択した部屋の対応履歴を取得
					$.ajax({
						type: 'POST',
						url: './get_rireki.php',
						data: {
							'rKey': '__rKey__',
							'UserCD': HiddenUserCD,
							'ClientCD': '__TargetClientCD__',
						},
						dataType: 'json',
					}).done(function (data) {
						if (data == null) {
							alert('対応履歴がありません。');
							return false;
						} else {
							$('#RirekiModalBody').empty();
							$('#RirekiModalBody').html(data);
							$('#RirekiUserCD').text(HiddenUserCD);
						}
					}).fail(function (XMLHttpRequest, textStatus, error) {
						alert('エラーが発生しました。データを更新できませんでした。');//通信失敗
					});
				}
			});

		});

	</script>

	<style>
		.ReservationInfo {
			padding: 2px 0;
		}

		.OddNumber {
			background-color: #f3f3f3;
		}

		.NoReservation {
			margin: 20px;
		}

		.NoReservation span {
			color: red;
			font-weight: bold;
		}

		.NoReservation table {
			border-collapse: collapse;
		}

		.NoReservation td {
			border: 1px solid #b1b1b1;
			padding: 3px;
		}

		.tdheader {
			background-color: #D1E8FF;
		}

		.fs-12 {
			font-size: 1.2em;
		}
	</style>

</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
	<table>
		<tr>
			<td>

				<h1>__MansionName__</h1>
				<h2>作業用工程表</h2>

				<table class="kanribasyo" style="width:500px">
					<tr bgcolor="#EFF7FF">
						<td colspan="3">■工事メモ<br>
							<font color="red" size="2">※自由にご利用ください。</font>
						</td>
					</tr>
					<tr>
						<td>
							<font color="blue" size="4">&ensp;__MansionMemo__&ensp;</font>
						</td>
						<td width="80px">
							__HokanImage__
						</td>
						<td width="80px">
							<form method="POST" action="a3.php" class="formyohaku">
								__HiddenValues__
								<input type="submit" value="メモ編集" class="hensyuu-btn">
							</form>
						</td>
					</tr>
				</table>
				<!--hensyuuボタン-->


				<!--▼ボタン大枠-->
				<div id="hyou-btn-waku" class="nopuri">
					<!--▽本日ジャンプボタン-->
					<form action="e.php" method="post">
						__HiddenValues__
						<input type="button" value='本日ジャンプ' onclick=location.href="#TodayLink" class="hyou-btn">
					</form>

					<!--▽トップボタン-->
					<form action="top_seko.php" method="post">
						__HiddenValues__
						<input type="submit" name="submit" value='トップ' class="hyou-btn">
					</form>

					<!--▽日別プリントアウトボタン-->
					<form action="e.php" method="post">
						__HiddenValues__
						<input type="submit" name="submit" value='日別プリントアウト' class="hyou-btn">
					</form>

					<!--▽未返事住戸ボタン-->
					<form action="c.php" method="post">
						__HiddenValues__
						<input type="submit" name="submit" value='未返事住戸' class="hyou-btn">
					</form>

					<!--▽未返事住戸（空日程）ボタン 20200610add-->
					<form action="c.php?type=kara" method="post">
						__HiddenValues__
						<input type="submit" name="submit" value='未返事住戸(空日程)' class="hyou-btn">
					</form>

					<!--▽掲示用ボタン-->
					<form action="d2keiji.php" method="post">
						__HiddenValues__
						<input type="submit" name="submit" value='掲示用' class="hyou-btn">
					</form>

					<!--▽部屋順表示(テラ様専用）ボタン-->
					__IfTerra__
					<form action="d2_terra.php" method="post">
						__HiddenValues__
						<input type="submit" name="submit" value='部屋順表示' class="hyou-btn">
					</form>
					__IfTerra__

					<!--▽部屋順表示トボタン-->
					<form action="d2_terra.php?rKey=__rKey__" method="post">
						__HiddenValues__
						<input type="submit" name="submit" value='部屋順表示' class="hyou-btn">
					</form>

					<!--▽対応履歴）ボタン-->
					<form action="d2_taioall.php" method="post">
						__HiddenValues__
						<input type="submit" name="submit" value='対応履歴一覧' class="hyou-btn">
					</form>

					<!--▽相談中ボタン-->
					<form action="soudan_list.php" method="post">
						__HiddenValues__
						<input type="submit" name="submit" value='相談中一覧' class="hyou-btn">
					</form>

					__tatoLoop__
					<form style="display: inline" action="d2_tato.php" method="POST">
						<input type="hidden" name="TatouserCD" value=__TatouserCD__>
						<input type="hidden" name="UserCD" value=__UserCD__>
						<input type="hidden" name="rKey" value=__rKey__>

						<input type="submit" value=__TatouserCD__工程表 class="hyou-btn">
					</form>
					__tatoLoop__

					<!-- 多棟有の場合、リンク作成 -->
					<div class="nopuri">
						__AddLoop__
						<form style="display: inline" action="d2_tato.php?rKey=__rKey__&c=__TargetClientCD__&TatouserCD=__ToName__" method="POST">
							<input type="hidden" name="TatouserCD" value=__TatoCD__>
							<!-- <input type="hidden" name="TatouserCD" value=__TatouserCD__> -->
							<input type="hidden" name="UserCD" value=__UserCD__>
							<input type="hidden" name="rKey" value=__rKey__>
							<!-- <input type="submit" value="__TatouserCD__工程表" class="hyou-btn"> -->
							<input type="submit" value="__ToName__棟工程表" class="hyou-btn">
						</form>
						__AddLoop__

					</div>

				</div>
				<!--▲ボタン大枠-->


				<p class="kikan">
					__IfnoTato__
					__IfKyoyuStartDate__
					■共用部：__KyoyuStartDateNen__年__KyoyuStartDateGetu__月__KyoyuStartDateHi__日(__KyoyuStartDateWeek2__)～
					__KyoyuEndDateNen__年__KyoyuEndDateGetu__月__KyoyuEndDateHi__日(__KyoyuEndDateWeek2__)<br>
					__IfKyoyuStartDate__
					■専有部：__SenyuStartDateNen__年__SenyuStartDateGetu__月__SenyuStartDateHi__日(__ReserveToWeek2__)～
					__SenyuEndDateNen__年__SenyuEndDateGetu__月__SenyuEndDateHi__日(__ReserveFromWeek2__)
					__IfnoTato__<br>
					■住戸数：__Juuko__戸&nbsp;<br>
					■全戸回答の有無：__wAnswer__<br>
					■工事枠：__WakuDisp2__<br>
					__IfQuestionFlg__
					■アンケート実施物件<br>
					__IfQuestionFlg__
				</p>

				<p class="kikan">
					●緊急連絡順（左から）：__AddressPackLoop__
					<button id="a__AddressNo__" class="btn btn-sm btn-secondary">__SekoLastName__</button>
					<input type="hidden" id="b__AddressNo__" value="b__AddressNo__">
					__AddressPackLoop__
				</p>


				<!--▽オプション設定あり-->
				__IfOption__
				<br>
				<p class="kouteijikan">●OP申し込み個数</p>
				<table class="kanribasyo">
					<tr bgcolor="#EFF7FF">
						__TopOptionLoop__
						<td align="center" bgcolor="#d1e8ff" width="90">__TopOptionName__</td>
						__TopOptionLoop__
					</tr>
					<tr>
						__TopOptionLoop__
						<td align="center">__TopOptionCnt__</td>
						__TopOptionLoop__
					</tr>
				</table>
				<br>
				__IfOption__
				<!--△オプション設定あり-->


				<!-- Modalトリガー -->
				<div style="margin-left:20px;">
					__IfZan__
					<button class="hyou-btn" data-bs-toggle="modal" data-bs-target="#ZanKojiModal">残工事住戸(__ZanKojiSuu__戸)</button>
					__IfZan__
					<button class="hyou-btn" data-bs-toggle="modal" data-bs-target="#ReservationInfoModal">部屋数を表示</button>
					<button class="hyou-btn" data-bs-toggle="modal" data-bs-target="#NoResponseModal">未返事住戸</button>
					<button class="hyou-btn" data-bs-toggle="modal" data-bs-target="#NoResponseKaraModal">未返事住戸（空日程）</button>
					__IfUpdatedToday__
					<button class="hyou-btn" data-bs-toggle="modal" data-bs-target="#ReserveUpdatedHistoryModal">受付締切後の日程調整履歴 [本日更新：__UpdatedTodayCnt__件]</button>
					__IfUpdatedToday__

				</div>

				<br><br>

				<!-- ▽ 残工事Modal -->
				<div class="modal fade" id="ZanKojiModal" tabindex="-1" aria-labelledby="ZanKojiModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-xl modal-dialog-scrollable">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="ZanKojiModalLabel">残工事住戸(日程順で表示)</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<div class="fs-12" style="overflow-wrap: break-word;word-wrap: break-word;">
									__ZanKojiList__
								</div>
							</div>
							<div class="modal-footer border-0">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
							</div>
						</div>
					</div>
				</div>
				<!-- △ 残工事Modal -->


				<!-- ▽ 部屋数を表示Modal -->
				<div class="modal fade" id="ReservationInfoModal" tabindex="-1" aria-labelledby="ReservationInfoModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-xl modal-dialog-scrollable">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="ReservationInfoModalLabel">部屋数を表示</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body fs-12">
								__ReservationInfo__
							</div>
							<div class="modal-footer border-0">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
							</div>
						</div>
					</div>
				</div>
				<!-- △ 部屋数を表示Modal -->


				<!-- ▽ 未返事住戸Modal -->
				<div class="modal fade" id="NoResponseModal" tabindex="-1" aria-labelledby="NoResponseModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-scrollable">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="NoResponseModalLabel">未返事住戸 __MihenjiNum__戸</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<table class="table fs-12">
									<thead>
										<tr>
											<th>部屋番号</th>
											<th>仮予定日</th>
											<th>予定時間帯</th>
										</tr>
									</thead>
									<tbody>
										__MihenjiHeyaLoop__
										<tr>
											<td>__MihenjiHeya__</td>
											<td>__MihenjiDate__</td>
											<td>__MihenjiTime__</td>
										</tr>
										__MihenjiHeyaLoop__
									</tbody>
								</table>
							</div>
							<div class="modal-footer border-0">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
							</div>
						</div>
					</div>
				</div>
				<!-- △ 未返事住戸Modal -->


				<!-- ▽ 未返事住戸（空日程）Modal -->
				<div class="modal fade" id="NoResponseKaraModal" tabindex="-1" aria-labelledby="NoResponseKaraModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-xl modal-dialog-scrollable">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="NoResponseKaraModalLabel">未返事住戸（空日程）__MihenjiKaraCnt__戸</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<div class="fs-12" style="overflow-wrap: break-word;word-wrap: break-word;">
									__MihenjiKaraRooms__
								</div>
							</div>
							<div class="modal-footer border-0">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
							</div>
						</div>
					</div>
				</div>
				<!-- △ 未返事住戸（空日程）Modal -->

				<!-- ▽ 受付締切後の日程調整履歴を表示 Modal -->
				<div class="modal fade" id="ReserveUpdatedHistoryModal" tabindex="-1" aria-labelledby="ReserveUpdatedHistoryModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-scrollable">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="ReserveUpdatedHistoryModalLabel">受付締切後の日程調整履歴</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<table class="table table-striped">
									<thead>
										<tr style="background:#d1e8ff;">
											<td>部屋</td>
											<td>更新日時</td>
											<td>変更前</td>
											<td>変更後</td>
										</tr>
									</thead>
									<tbody>
										__HistoryLoop__
										<tr>
											<td>__ReserveUpdatedUser__</td>
											<td>__HistoryCreated__</td>
											<td>__HistoryLastTimeFrom__</td>
											<td>__HistoryTimeFrom__</td>
										</tr>
										__HistoryLoop__
									</tbody>
								</table>
							</div>
							<div class="modal-footer border-0">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
							</div>
						</div>
					</div>
				</div>
				<!-- △ 受付締切後の日程調整履歴を表示 Modal -->


				<!--<p class="kouteijikan">
					受付状況の説明：<br>
					<span style="background-color:#ffc0cb">赤：未連絡</span><br>
					<span style="background-color:#ffffbf">黄色：変更なし（webから日程変更なし　もしくはwebからﾕｰｻﾞｰ登録のみで未連絡）</span><br>
					<span style="background-color:#ccffcc">緑：変更あり</span>
				</p>-->

				__IfNoResrvationDisp__
				<div class="NoReservation">
					<span>お客様情報のみ登録、日程未確定</span><br>
					<table>
						<tr>
							<td nowrap class="tdheader" style="width:100px;">部屋番号</td>
							<td nowrap class="tdheader" style="width:150px;">氏名</td>
							<td nowrap class="tdheader" style="width:120px;">電話番号</td>
						</tr>
						__UserList__
					</table>
				</div>
				__IfNoResrvationDisp__

				<br>

				<form method="POST" action="a2.php" name="mainform">
					<input type="hidden" name="editUserCD">
					<input type="hidden" name="uCD3">
					__HiddenValues__



					<table class="koutei">
						<!--項目一番上-->
						<tr bgcolor="#d1e8ff" font-size="0.9em">
							<td>完了</td>
							<td>部屋</td>
							<td>工事日時</td>
							<td>受付状況</td>

							<!--▽オプション-->
							__MenuList__
							__IfKey__
							__KeyName__
							__IfKey__
							<!--△オプション-->

							<td>備考</td>
							<!-- <td>工事完了日</td> -->

							<!--▽名前、連絡先-->
							__IfNameTelDisp__
							<td>名前</td>
							<td>連絡先</td>
							__IfNameTelDisp__
							<!--△名前、連絡先-->

							<td>編集</td>
							<td>対応履歴</td>
						</tr>


						__RecoLoop__

						__IfNotSameDate__
						<!--▽送信ボタン-->
						<tr>
							<td colspan="__colspan__">
								<input type="submit" value="完了/解除" class="hyou-btn">
								<input type="button" value="画面更新" onclick=location.reload() class="koushin-btn">
							</td>
						</tr>
						<!--△送信ボタン-->

						<!--項目二番目以降-->
						<tr bgcolor="#d1e8ff" font-size="0.9em">
							<td>完了</td>
							<td>部屋</td>
							<td>工事日時</td>
							<td>受付状況</td>

							<!--▽オプション-->
							__MenuList__
							__IfKey__
							__KeyName__
							__IfKey__
							<!--△オプション-->

							<td>備考</td>
							<!-- <td>工事完了日</td> -->

							<!--▽名前、連絡先-->
							__IfNameTelDisp__
							<td>名前</td>
							<td>連絡先</td>
							__IfNameTelDisp__
							<!--△名前、連絡先-->

							<td>編集</td>
							<td>対応履歴</td>
						</tr>
						__IfNotSameDate__

						__Ifkan__
						<tr align="center" style="color:#808080" bgcolor="#c0c0c0">
							__Ifkan__

							__IfMikan__
							__IfNotToday__
						<tr>__IfNotToday__
							__IfToday__
						<tr bgcolor="#FFF0F5">__IfToday__
							__IfMikan__

							<td>
								__IfMIKANSEI2__
								<input type="checkbox" name=Notes[] value="__uCD__">
								<!--完了チェック完了後非表示-->
								__IfMIKANSEI2__
								__IfKANSEI2__
								解<input type="checkbox" name=Notes2[] value="__uCD__">
								<!--解除チェック完了後表示-->
								__IfKANSEI2__
							</td>

							<td>__IfToday2__<div id="TodayLink">★</div>__IfToday2__<span class="futoji">__ID__</span></td>
							<!--部屋番号-->
							<td>__IfKyoyu____timeFromDate__(__weekjp__)<span class="kaigyou">__timeFromTime__</span>__IfKyoyu__
								__IfKyoyuBefore__ __timeFromTime__ __IfKyoyuBefore__</td>							<!--工事日時-->

							__uUpdated__

							<!--▽オプション-->
							__menuCD__
							__IfKey__
							__KeyMenuCD__
							__IfKey__
							<!--△オプション-->

							<td>
								<!--備考欄-->
								<div align='left'>
									__Jikan__ __Shitei__ __wMemo__ __HearingMemo__
									<font color="red">
										__IfOpApplied__
										__OPShiharai__
										__IfOpApplied__
									</font>
									<!--<font color="red">__wR006__</font>-->
									<!--<font color="blue">__wMemo2__</font>-->
									<font color="blue">__rSekoMemo__</font>
									<!--現場で入力した備考-->
								</div>
							</td>
							<!-- <td>
								__Kakuninsyo_date__
							</td> -->

							<!--▽名前、連絡先-->
							__IfNameTelDisp2__
							<td>__LastName__</td>
							<td>__TEL__</td>
							__IfNameTelDisp2__
							<!--△名前、連絡先-->

							<td>
								<!--編集-->
								<input type="button" value="編集" onclick="javascript:moveWithUserCD('a2.php?rKey=__rKey__','__uCD__')" class="hyou-btn">
							</td>
							<td>
								<!--対応履歴-->
								<input type="button" value="履歴" onclick="location.href='./d2_taio.php?rKey=__rKey__&uCD=__uCD__'" class="hyou-btn">
							</td>

							<!--▽工事おわってないとき-->
							__IfMikan__

							__IfTCheck__
						<tr style="height:15px;" bgcolor="#d1e8ff">
							<td colspan=__colspan__></td>
						</tr>
						__IfTCheck__

						__IfMikan__
						<!--△工事おわってないとき-->

		</tr>

		__RecoLoop__
		<tr>
			<td colspan="__colspan__">
				<input type="submit" value="完了/解除" class="hyou-btn">
				<input type="button" value="画面更新" onclick=location.reload() class="koushin-btn">
			</td>
		</tr>
	</table>

	<div class="sekotophe" style="margin-top:5px">
		__SFooter__
	</div>
	</form>

	__If489TelNumberDisp__
	工事予約受付センター：__TelNumber__
	__If489TelNumberDisp__

	<!-- Separate Popper and Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>

</body>

</html>
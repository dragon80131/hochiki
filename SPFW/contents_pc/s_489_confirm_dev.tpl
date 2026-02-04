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

	<!--datepicker-->
	<link href="css/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
	<link href="css/jquery-ui-1.12.1/jquery-ui.structure.min.css" rel="stylesheet">
	<link href="css/jquery-ui-1.12.1/jquery-ui.theme.min.css" rel="stylesheet">
	<script src="js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script src="js/jquery-ui/datepicker-ja.js"></script>

	<script type="text/javascript" src="js/tools_ajax.js"></script>
	<script type="text/javascript" src="js/ConnectedSelect.js"></script>
</head>

<body>
	__SHeader__

	<div class="content-all">
		<!--content-all-->
		<div class="top-menu left-yose">
			<h5>__wBukkenName__</h5>
			<h6>予約センター受付依頼　確認画面</h6>

			<form action="s_489_finish.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__" method="POST">
				<font color="red" size="4"><b>入力内容をご確認ください。問題なければ［送信］ボタンをクリックしてください。</b></font>
				<br><br>

				1．受付依頼ステータス
				<table class="table table-bordered table-sm">
					<colgroup>
						<col style='width:200px;'>
						<col style=''>
					</colgroup>
					<tr>
						<th>物件CD</th>
						<td>__editBukkenCD__</td>
					</tr>
					<tr>
						<th>物件名</th>
						<td>__wBukkenName__</td>
					</tr>
					<tr>
						<th>受付依頼ステータス</th>
						<td>__wwIraiRenkeiStatus__</td>
					</tr>
					<tr>
						<th>依頼受付日</th>
						<td>__wIraiDate__</td>
					</tr>
				</table>

				2．物件基本情報
				<table class="table table-bordered table-sm">
					<colgroup>
						<col style='width:200px;'>
						<col style=''>
					</colgroup>
					<tr>
						<th>住所</th>
						<td>__wAddress__</td>
					</tr>
					<tr>
						<th>総戸数</th>
						<td>__wKosu__</td>
					</tr>
					<tr>
						<th>管理会社名</th>
						<td>__wKanriGaisya__</td>
					</tr>
				</table>

				基本料金に含まれているもの
				<table class="table table-bordered table-sm">
					<colgroup>
						<col style='width:100px;'>
						<col style='width:200px;'>
						<col style=''>
					</colgroup>
					<tr>
						<th colspan="2">専有部工事日時変更受付方法</th>
						<td>__wAnswer__</td>
					</tr>
					<tr>
						<th colspan="2">予約センター作成資料</th>
						<td>__wAnswer489__</td>
					</tr>
					<tr>
						<th colspan="2">WEB受付</th>
						<td>__wwWEBRecept__</td>
					</tr>
					<tr>
						<th rowspan="2">利用様式</th>
						<th>予定案内</th>
						<td>__wYoshikiYotei__</td>
					</tr>
					<tr>
						<th>確定案内</th>
						<td>__wYoshikiKakutei__</td>
					</tr>
					<tr>
						<th class="yb" colspan="2">予定・決定案内　記載社名</th>
						<td>__wYoteiKetteiCompanyName1__<br>
							__wYoteiKetteiCompanyName2__<br>
							__wYoteiKetteiCompanyName3__
						</td>
					</tr>
				</table>

				オプション (基本料金に含まれていないメニュー）
				<table class="table table-bordered table-sm">
					<colgroup>
						<col style='width:150px;'>
						<col style='width:150px;'>
						<col style=''>
					</colgroup>
					<tr>
						<th class="yb" rowspan="3">電子看板アプリ</th>
						<th class="yb">利用有無</th>
						<td>__wwPicStatus__</td>
					</tr>
					<th class="yb">電子看板アプリ用<br>工事名</th>
					<td>__wPicAppKojiName__</td>
					</tr>
					<th class="yb">電子看板アプリ用<br>施工会社名</th>
					<td>__wPicAppSekoName__</td>
					</tr>
					<tr>
						<th class="yb" rowspan="3">ポスティング</th>
						<th class="yb">利用有無</th>
						<td>__wwPostingFlg__</td>
						__IfPosting__
					<tr>
						<th class="yb">投函場所</th>
						<td>__wwPostingBasho__</td>
					</tr>
					__IfEntrancePIN__
					<tr>
						<th class="yb">集合玄関の暗証番号</th>
						<td>__wwEntrancePIN__</td>
					</tr>
					__IfEntrancePIN__
					__IfPosting__
					</tr>
				</table>

				3．日程
				<table class="table table-bordered table-sm">
					<colgroup>
						<col style='width:200px;'>
						<col style='width:200px;'>
						<col style='width:400px;'>
					</colgroup>
					<tr>
						<th class="yb">予定案内提供日</th>
						<td>__wYoteTekyoDate__</td>
						<td class="yb">
							<font color="dimgray">ネスペ⇒担当者に資料を提供する日</font>
						</td>
					</tr>
					<tr>
						<th class="yb">工事説明資料配布日</th>
						<td>__wYoteDate__</td>
						<td class="yb">
							<font color="dimgray">予約システム受付サービス開始日</font>
						</td>
					</tr>
					<tr>
						<th class="yb">変更受付締切日</th>
						<td>__wYoyakuEnd__</td>
						<td class="yb">
							<font color="dimgray">受付を一旦終了し、工事日時調整開始</font>
						</td>
					</tr>
					<tr>
						<th class="yb">専有部決定案内提供日</th>
						<td>__wTekyoDate__</td>
						<td class="yb">
							<font color="dimgray">ネスペ⇒担当者に資料を提供する日(AM中)</font>
						</td>
					</tr>
					<tr>
						<th class="yb">専有部決定案内配布日</th>
						<td>__wKeteiDate__</td>
						<td class="yb">
							<font color="dimgray">以後の専有部工事日時変更は、メールにて報告</font>
						</td>
					</tr>
					<tr>
						<th class="yb">オプション締切日</th>
						<td>__wOpEndDate__</td>
						<td class="yb">
							<font size="2" color="red">オプションの締切が日程の締切と異なる場合変更します</font>
						</td>
					</tr>
				</table>

				4．工事施工会社情報
				<table class="table table-bordered table-sm">
					<colgroup>
						<col style='width:200px;'>
						<col style=''>
					</colgroup>
					<tr>
						<th class="yb" colspan="2">担当者情報</th>
					</tr>
					<tr>
						<th class="yb">担当者１</th>
						<td>__TantoName1__
						</td>
					</tr>
					<tr>
						<th class="yb">担当者２</th>
						<td>__TantoName2__
						</td>
					</tr>
					<tr>
						<th class="yb">担当者３</th>
						<td>__TantoName3__
						</td>
					</tr>
					<tr>
						<th class="yb">担当者４</th>
						<td>__TantoName4__
						</td>
					</tr>
					<tr>
						<th class="yb">担当者メモ</th>
						<td>__wTantoMemo__
						</td>
					</tr>

					<tr>
						<th class="yb" colspan="2">施工業者情報</th>
					</tr>
					<tr>
						<th class="yb">施工業者担当者１</th>
						<td>
							__GyosyaTantoName1__
						</td>
					</tr>
					<tr>
						<th class="yb">施工業者担当者２</th>
						<td>
							__GyosyaTantoName2__
						</td>
					</tr>
					<tr>
						<th class="yb">施工業者担当者３</th>
						<td>
							__GyosyaTantoName3__
						</td>
					</tr>
					<tr>
						<th class="yb">施工業者担当者４</th>
						<td>
							__GyosyaTantoName4__
						</td>
					</tr>
					<tr>
						<th class="yb">施工業者担当者５</th>
						<td>
							__GyosyaTantoName5__
						</td>
					</tr>
					<tr>
						<th class="yb">施工業者担当者メモ</th>
						<td>
							__wGyosyaTantoMemo__
						</td>
					</tr>

				</table>

				5．工事可能戸数
				<table class="table table-bordered table-sm">
					<colgroup>
						<col style='width:150px;'>
						<col style='width:150px;'>
						<col style=''>
					</colgroup>
					<tr>
						<th rowspan="3">作業時間帯区分</th>
						<th>午前</th>
						<td>__wTimeAStart__～__wTimeAEnd__ 　__wTimeASu__戸</td>
					</tr>
					<tr>
						<th>午後１</th>
						<td>__wTimeBStart__～__wTimeBEnd__ 　__wTimeBSu__戸</td>
					</tr>
					<tr>
						<th>午後２</th>
						<td>__wTimeCStart__～__wTimeCEnd__ 　__wTimeCSu__戸</td>
					</tr>

					<tr>
						<th>工事枠パターン</th>
						<th></th>
						<td>__WakuPattern_disp__</td>
					</tr>

					<tr>
						<th>最大可能枠数</th>
						<th>(例:4-3-3)</th>
						<td>__wMaxWakuSu__ </td>
					</tr>
					<input type="hidden" name="wMaxWakuSu" value=__wMaxWakuSu__>
				</table>


				6．施工方法情報
				<table class="table table-bordered table-sm">
					<colgroup>
						<col style='width:200px;'>
						<col style=''>
					</colgroup>
					<tr>
						<th>居室親機型番</th>
						<td>__OyaKataban__ __OyaDeviceName__</td>
					</tr>
					<tr>
						<th>玄関子機型番</th>
						<td>__KokiKataban__ __KokiDeviceName__</td>
					</tr>
					<tr>
						<th>1日の基本班体制数</th>
						<td>__wHansu__ 班</td>
					</tr>
					<input type="hidden" name="wHansu" value=__wHansu__>

					<tr>
						<th>1戸あたりの標準作業時間</th>
						<td>__wConstTime__ 分</td>
					</tr>
					<tr>
						<th>工事期間中の集玄開錠方法</th>
						<td>__wwKaijyo__</td>
					</tr>

				</table>

				7．写真撮影方法
				<table class="table table-bordered table-sm">
					<colgroup>
						<col style='width:200px;'>
						<col style='width:200px;'>
						<col style='width:400px;'>
					</colgroup>
					<tr>
						<th class="yb">写真台帳提供日</th>
						<td>__wPhotoTekyoDate__</td>
						<td class="yb">
							<font color="gray">写真台帳の提供日(以降の写真はアイホンメンテ)</font>
						</td>
					</tr>

					<tr>
						<th class="yb">台帳選択</th>
						<td>__wPhotoPattern__</td>
						<td class="yb" colspan="2">
							<font color="gray">写真台帳【選択】シートより確認</font>
						</td>
					</tr>

					<tr>
						<th class="yb">専有部</th>
						<th class="yb">機器名　</th>
						<th class="yb">撮影シーン</th>
					</tr>
					<tr>
						<th class="yb">項目1</th>
						<td>__wwPhotoSenyu1__</td>
						<td>__wwPSScene1__</td>
					</tr>
					<tr>
						<th class="yb">項目2</th>
						<td>__wwPhotoSenyu2__</td>
						<td>__wwPSScene2__</td>
					</tr>
					<tr>
						<th class="yb">項目3</th>
						<td>__wwPhotoSenyu3__</td>
						<td>__wwPSScene3__</td>
					</tr>
					<tr>
						<th class="yb">項目4</th>
						<td>__wwPhotoSenyu4__</td>
						<td>__wwPSScene4__</td>
					</tr>
					<tr>
						<th class="yb">項目5</th>
						<td>__wPhotoSenyu5__</td>
						<td>__wwPSScene5__</td>
					</tr>
					<tr>
						<th class="yb">項目6</th>
						<td>__wPhotoSenyu6__</td>
						<td>__wwPSScene6__</td>
					</tr>
					<tr>
						<th class="yb">共用部</th>
						<th class="yb">機器名</th>
						<th class="yb">撮影シーン</th>
					</tr>
					<tr>
						<th class="yb">項目1</th>
						<td>__wwPhotoKyoyo1__</td>
						<td>__wwPKScene1__</td>
					</tr>
					<tr>
						<th class="yb">項目2</th>
						<td>__wwPhotoKyoyo2__</td>
						<td>__wwPKScene2__</td>
					</tr>
					<tr>
						<th class="yb">項目3</th>
						<td>__wwPhotoKyoyo3__</td>
						<td>__wwPKScene3__</td>
					</tr>
					<tr>
						<th class="yb">項目4</th>
						<td>__wwPhotoKyoyo4__</td>
						<td>__wwPKScene4__</td>
					</tr>
					<tr>
						<th class="yb">項目5</th>
						<td>__wPhotoKyoyo5__</td>
						<td>__wwPKScene5__</td>
					</tr>
					<tr>
						<th class="yb">項目6</th>
						<td>__wPhotoKyoyo6__</td>
						<td>__wwPKScene6__</td>
					</tr>
					<tr>
						<th class="yb">項目7</th>
						<td>__wPhotoKyoyo7__</td>
						<td>__wwPKScene7__</td>
					</tr>
					<tr>
						<th class="yb">項目8</th>
						<td>__wPhotoKyoyo8__</td>
						<td>__wwPKScene8__</td>
					</tr>
					<tr>
						<th class="yb">項目9</th>
						<td>__wPhotoKyoyo9__</td>
						<td>__wwPKScene9__</td>
					</tr>
				</table>

				<table class="table table-bordered table-sm">
					<tr>
						<th class="yb">備考</th>
					</tr>
					<tr>
						<td>__wwNotes__</td>
					</tr>
				</table>

				<table class="table table-bordered table-sm">
					<tr>
						<th class="yb">人工表</th>
					</tr>
					<tr>
						<td>__wwIR01__</td>
					</tr>
				</table>

				8.関連資料（住人様ご案内資料、仮日程表など）
				<table class="table table-bordered table-sm">
					<tr>
						<th class="yb">登録済み資料</th>
					</tr>
					<tr>
						<td>
							<table class="table table-bordered table-sm">
								__FileLoop__
								<tr>
									<td style="width:30px">__FileNo__</td>
									<td>__File__</td>
									<td style="width:160px">
										<font size="2">__Created__</font>
									</td>
								</tr>
								__FileLoop__
							</table>
						</td>
					</tr>
				</table>

				<br>
				<font size=5 color="navy"><b>※依頼内容に間違いがないかご確認お願いします。</b></font><br>
				__CautionMessage__<br>

				__HiddenValues__
				<input type="submit" value=" 予約センターへ依頼送信 " class="btn btn-primary blue">
			</form>


			<br><br>


			<form action="s_489_dev.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__" method="POST">
				__HiddenValues__
				<input type="hidden" name="Modota" value="1">
				<input type="submit" value=" 戻る " class="btn btn-info">
			</form>

		</div>

	</div>
	<!--content-all-->



	__SFooter__
	__SCopyright__

</body>

</html>
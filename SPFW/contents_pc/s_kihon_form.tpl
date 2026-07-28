<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>__TITLENAME__</title>
	<!-- BootstrapのCSS読み込み -->
	<link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

	<!-- jQuery読み込み -->
	<script src="./include/js/jquery-3.2.1.min.js"></script>

	<!-- BootstrapのJS読み込み -->
	<script src="./include/bootstrap/js/bootstrap.min.js"></script>

	<link rel="stylesheet" type="text/css" href="./css/rnsien.css" />
	<script type="text/javascript" src="./tools.js"></script>

	<!--datepicker-->
	<link href="css/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet" />
	<link href="css/jquery-ui-1.12.1/jquery-ui.structure.min.css" rel="stylesheet" />
	<link href="css/jquery-ui-1.12.1/jquery-ui.theme.min.css" rel="stylesheet" />
	<script src="js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script src="js/jquery-ui/datepicker-ja.js"></script>


	<style>
		.kobetsu {
			margin-left: 5px;
			margin-bottom: 5px;
			display: block;
		}
		.remove-btn{
			display: block;
			margin-left: auto;
			width: fit-content;
			margin-right: 0;
			margin-top: 3px;
		}
		/* 休工日: default行と追加行を同じボックスモデルで揃える */
		table[id^="kyukobi_table"] {
			table-layout: fixed;
			width: 100%;
		}
		table[id^="kyukobi_table"] col.kyukobi-action-col {
			width: 90px;
		}
		table[id^="kyukobi_table"] td:first-child {
			width: 90px;
			vertical-align: middle;
		}
		table[id^="kyukobi_table"] td:first-child .kyukobi-action {
			display: inline-flex;
			align-items: center;
			min-width: 86px;
		}
		table[id^="kyukobi_table"] td:nth-child(2) {
			vertical-align: middle;
		}
		.holiday-field-group {
			display: inline-flex;
			align-items: center;
			gap: 4px;
			vertical-align: middle;
			margin-right: 8px;
			box-sizing: border-box;
		}
		.holiday-field-group input[type="text"] {
			width: 120px;
			margin: 0;
			vertical-align: middle;
		}
		.holiday-field-group .holiday-period {
			width: 70px;
			margin: 0 !important;
			vertical-align: middle;
		}
		.holiday-field-group img.ui-datepicker-trigger {
			margin: 0;
			vertical-align: middle;
		}
		.remove-building-btn{
			margin-top: 3px;
			margin-left: 5px;
		}
		.building_boundary{
			background: #5c6369;
		    color: #fff;
		}
		.mintitle{
			display:flex;
			align-items:center;
			background-color:#404040;
			color:#fff;
			margin-bottom:10px;
			padding:6px 10px;
			font-size:18px;
		}
		.mintitle .red{
			display:block;
			font-size:14px;
			margin-left:30px;
			color:red;
		}
		.HasError{
			color:red;
		}
	</style>

<script>
	$(function() {
		$(".yoyaku-end-datepicker").datepicker({
			numberOfMonths: 2,   // 2カ月表示
			showButtonPanel: true
		});
	});
</script>
</head>

<body>
  <div class="container">
      <div class="row">
          <div class="col-12 text-center">
              __SHeaderKanri2__
          </div>
      </div>
  </div>
  <div class="container">
      <div class="row mt-3">
          <div class="col-12">
		__IfRegist__
			<a href="javascript:void(0)" onclick="go_top('s_search.php__QUERY__&m=__m__');" class="btn btn-info">トップへ</a>
		__IfRegist__
	__IfSinki__
			<a href="javascript:void(0)" onclick="go_top('s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&m=__m__');" class="btn btn-info mb-1">メニュー</a>
	__IfSinki__
	__IfDeveloper__
		__IfRegist__
			<a href="s_kihon_copy.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&m=__m__" class="btn btn-blue mb-1">過去物件情報からコピー</a>
		__IfRegist__
	__IfDeveloper__
	__IfSinki__
			__IfWorker__
			__IfShowSchedule__
			<a href="sh_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&m=__m__" class="btn btn-blue mb-1">日程変更（TEL受付）</a>
			__IfShowSchedule__			
			<a href="sh_henko_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&m=__m__" class="btn btn-blue mb-1">作業工程表</a>
			<a href="s_kanryo.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&m=__m__" class="btn btn-dark-blue mb-1">完了報告</a>
			__IfWorker__
	__IfSinki__
          </div>
      </div>
  </div>
  <div class="container">
      <div class="row mt-3">
          <div class="col-12">
				__IfDeveloper__
				<form action="s_kihon_finish.php" method="POST" name="mainform" id="mainform" autocomplete="off">

					<input type="hidden" name="editBukkenCD" value="__editBukkenCD__" />
					<input type="hidden" name="rKey" value="__rKey__" />
					<input type="hidden" name="work" value="1" />

					<span id="ErrorString" style="color: red"></span>
					__IfError____ErrorLoop__
					<font color="red">__ErrorStrings__<br /></font>
					__ErrorLoop____IfError__


					<div class="mintitle">
						物件基本情報
						<span class="red">※は入力必要項目です。</span>
					</div>
					<!--◆資料に記載する情報-->
					<table class="table table-bordered table-sm row-table">
						<colgroup>
							<col style="width: 280px" />
							<col style="" />
						</colgroup>

						<tr>
							<th class="yb" width="28%">物件名<font color="red">※</font>
							</th>
							<td width="72%">
								<input class="full-width" type="text" name="wBukkenName" id="wBukkenName" value="__wBukkenName__" placeholder="例：○○マンション" v-model="bukkenname">
								<br />
								<font color="gray" size="2" v-if="existmansionname == true">※マンション名をご記入ください。</font>
							</td>
						</tr>

						<tr>
							<th class="yb">物件名ふりがな
							</th>
							<td>
								<input class="full-width" type="text" name="wBukkenNameKana" id="wBukkenNameKana" value="__wBukkenNameKana__">
							</td>
						</tr>

						<!--<tr>
							<th class="yb">多棟有無</th>
							<td>

								<input type="radio" name="TatoFlg" id="" value="0" class="mr-1" "__TatoFlg0__">無
								<input type="radio" name="TatoFlg" id="" value="1" class="mr-1" "__TatoFlg1__">有

							</td>
						</tr>-->
						<tr>

							<th class="yb">作業名称（例:火報点検）<font color="red">※</font>
							</th>
							<td>
								<input class="full-width" type="text" name="wSagyoName" id="wSagyoName" value="__wSagyoName__" v-model="">
							</td>
						</tr>
						<tr>

							<th class="yb">住所<font color="red">※</font>
							</th>
							<td>
								<input class="full-width" type="text" name="wAddress" id="wAddress" value="__wAddress__" v-model="address">
							</td>
						</tr>
						<tr>
							<th class="yb">担当者</th>
							<td>
								<span>1.</span>
								<select name="TantoCD1" id="TantoCD1" v-model="TantoCD1Selected">
									<option value="">-</option>
									__TantoLoop__
									<option value="__TantoCD__" __TantoCD1Selected__ >__TantoName__</option>
									__TantoLoop__
								</select>
								<span>2.</span>
								<select name="TantoCD2" id="TantoCD2" v-model="TantoCD2Selected">
									<option value="">-</option>
									__TantoLoop__
									<option value="__TantoCD__" __TantoCD2Selected__ >__TantoName__</option>
									__TantoLoop__
								</select>
								<span>3.</span>
								<select name="TantoCD3" id="TantoCD3" v-model="TantoCD3Selected">
									<option value="">-</option>
									__TantoLoop__
									<option value="__TantoCD__" __TantoCD3Selected__ >__TantoName__</option>
									__TantoLoop__
								</select><br>
								<!--<span>4.</span>
								<select name="TantoCD4" id="TantoCD4" v-model="TantoCD4Selected">
									<option value="">-</option>
									__TantoLoop__
									<option value="__TantoCD__" __TantoCD4Selected__ >__TantoName__</option>
									__TantoLoop__
								</select>
								<span>5.</span>
								<select name="TantoCD5" id="TantoCD5" v-model="TantoCD5Selected">
									<option value="">-</option>
									__TantoLoop__
									<option value="__TantoCD__" __TantoCD5Selected__ >__TantoName__</option>
									__TantoLoop__
								</select>-->
							</td>
						</tr>

						<tr>
							<th class="yb">協力会社</th>
							<td>
								<select name="GyosyaCompany" id="GyosyaCompany" v-model="GyosyaCompany">
									__SekoCompanyLoop__
									<option value="__GyosyaCD__" __GyosyaSelected__ >__GyosyaName__</option>
									__SekoCompanyLoop__
								</select>
							</td>
						</tr>
						<tr>
							<th class="yb">管理会社</th>
							<td>
								<select name="KanriCompany" id="KanriCompany" v-model="KanriCompany">
									<option value="">-</option>
									__KanriCompanyLoop__
									<option value="__KanriCompanyCD__" __KanriCompanySelected__ >__KanriCompanyName__</option>
									__KanriCompanyLoop__
								</select>
							</td>
						</tr>
						<tr>
							<th class="yb">担当支店・支社<font color="red">※</font></th>
							<td>
								<select name="BrancheCompany" id="BrancheCompany" v-model="BrancheCompany">
									<option value="">-</option>
									__BrancheCompanyLoop__
									<option value="__BrancheCD__" __BrancheSelected__ >__BrancheName__</option>
									__BrancheCompanyLoop__
								</select>
							</td>
						</tr>

						<tr>
							<th class="yb">作業期間(全体)<font color="red">※</font>
								<span style="display: block; color: red">*棟毎の専有部の入力がない場合、作業期間(全体)が適用されます。</span>
							</th>
							<td>
								<input type="text" name="SenyuStartDate" id="SenyuStartDate"  class="datepicker" value="__SenyuStartDate__">～
								<input type="text" name="SenyuEndDate" id="SenyuEndDate"  class="datepicker" value="__SenyuEndDate__">

							</td>
						</tr>
						<tr>
							<th class="yb">共用部
							</th>
							<td>
								<input type="text" name="KyoyobuStartDate" id="KyoyobuStartDate"  class="datepicker" value="__KyoyobuStartDate__">～
								<input type="text" name="KyoyobuEndDate" id="KyoyobuEndDate"  class="datepicker" value="__KyoyobuEndDate__">
							</td>
						</tr>
						<tr>
							<th class="yb">
								<span>作業時間<font color="red">※</font></span>
								<span style="display: block; color: blue">配布資料に自動反映されます。</span>
							</th>
							<td>
								<input type="number" name="MinuteTime" id="MinuteTime" value="__MinuteTime__" style="width: 80px;">分
							</td>
						</tr>
						<tr>
							<th class="yb">
								<span>作業指示</span>
								<span style="display: block; color: red">*管理人常駐日や工事可能な曜日などの記載にお使い下さい。</span>
							</th>
							<td>
								<textarea name="wBukkenMemo" class="full-width">__wBukkenMemo__</textarea>
							</td>
						</tr>
						<tr>
							<th class="yb">
								<span>備考欄</span>
								<!-- <span style="display: block; color: red">*物件TOPに表示されます</span> -->
							</th>
							<td>
								<textarea name="Biko" class="full-width">__Biko__</textarea>
							</td>
						</tr>
						<tr>

							<th class="yb">総住戸数・棟数</th>
							<td>
								総住戸数:<span id="house_count"></span> &nbsp;&nbsp;&nbsp; 棟数:<span id="building_count"></span>
							</td>
						</tr>
					</table>
					<a href="javascript:void(0)" class="btn btn-primary blue mb-2" onclick="addBuilding();">棟追加</a>
					<table class="table table-bordered table-sm row-table">
						<colgroup>
							<col style="width: 280px" />
							<col style="" />
						</colgroup>

						<tr id="main_building_label">
							<td colspan="2" class="building_boundary">棟1</td>
						</tr>
						<tr>
							<th class="yb" width="28%">棟名称
							</th>
							<td width="72%">
								<input type="text" name="wBuilding" id="wBuilding" value="__wBuilding__" style="width:80px" onchange="refeshMainBuildingLabel();">
							</td>
						</tr>
						<tr>
							<th class="yb">住戸数<font color="red">※</font>
							</th>
							<td>
								住戸数(半角数字)
								<input type="number" name="wKosu" id="wKosu" class="dwelling" value="__wKosu__" style="width: 80px; ime-mode: disabled" v-model="Kosu" onchange="calcDwellingCount();">
								戸&nbsp;階高
								<input type="number" name="wKaidaka" id="wKaidaka" value="__wKaidaka__" style="width: 80px; ime-mode: disabled" v-model="Kosu">
								階
							</td>
						</tr>
						<tr>
							<th class="yb">専有部</th>
							<td>
								<input type="text" name="SenyuStartDate1" id="SenyuStartDate1"  class="datepicker senyubucls" value="__SenyuStartDate1__">～
								<input type="text" name="SenyuEndDate1" id="SenyuEndDate1"  class="datepicker senyubucls" value="__SenyuEndDate1__">
							</td>
						</tr>
						<tr>
							<th class="yb"><span>受付締切日<font color="red">※</font></span></th>
							<td>
								<input type="text" name="YoyakuEndDate" id="YoyakuEndDate" value="__YoyakuEndDate__" class="datepicker yoyaku-end-datepicker">
							</td>
						</tr>
						<tr>
							<th class="yb">休工日</th>
							<td>
								<table class="table table-bordered table-sm" id="kyukobi_table">
									<colgroup>
										<col class="kyukobi-action-col">
										<col>
									</colgroup>
									<tr>
										<td><span class="kyukobi-action"><input type="button" value="＋1行追加" style="background-color: transparent" onclick="addHoliday('kyukobi_table', 'wHoliday[]', '');" /></span></td>
										<td>
											<span class="holiday-field-group"><input type="text" name="wHoliday[]" value="__wHoliday1__" class="wHoliday" style="width:120px" autocomplete="off"><select name="wHolidayPeriod[]" class="holiday-period"><option value="ALL" __wHolidayPeriodSelectedALL1__>全日</option><option value="AM" __wHolidayPeriodSelectedAM1__>午前</option><option value="PM" __wHolidayPeriodSelectedPM1__>午後</option></select></span><span class="holiday-field-group"><input type="text" name="wHoliday[]" value="__wHoliday2__" class="wHoliday" style="width:120px" autocomplete="off"><select name="wHolidayPeriod[]" class="holiday-period"><option value="ALL" __wHolidayPeriodSelectedALL2__>全日</option><option value="AM" __wHolidayPeriodSelectedAM2__>午前</option><option value="PM" __wHolidayPeriodSelectedPM2__>午後</option></select></span><span class="holiday-field-group"><input type="text" name="wHoliday[]" value="__wHoliday3__" class="wHoliday" style="width:120px" autocomplete="off"><select name="wHolidayPeriod[]" class="holiday-period"><option value="ALL" __wHolidayPeriodSelectedALL3__>全日</option><option value="AM" __wHolidayPeriodSelectedAM3__>午前</option><option value="PM" __wHolidayPeriodSelectedPM3__>午後</option></select></span>
										</td>
									</tr>
									__KyukoTable__
								</table>
							</td>
						</tr>
						<tr>
							<th class="yb">予備日</th>
							<td>
								<table class="table table-bordered table-sm" id="reserveday_table">
									<tr>
										<td>
											<input type="button" value="＋1行追加" style="background-color: transparent"
												onclick="addReserveDay('reserveday_table', 'wReserveDay[]', '');" />
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
					</table>

					__IfUpdate__
						__BuildingLoop__
					<table class="table table-bordered table-sm row-table">
						<colgroup>
							<col style="width: 280px" />
							<col style="" />
						</colgroup>
						<tbody>
							<tr>
								<td colspan="2" class="building_boundary">棟__BuildingNo__
								<a href="javascript:void(0)" class="remove-building-btn" onclick="removeBuilding(this)"><img src="images/icon_delete.png"></a>
								</td>
							</tr>
							<tr>
								<th class="yb" width="28%">棟名称
								</th>
								<td width="72%">
									<input type="text" name="editBuildingName__BuildingCD__" class="subBuildingName" value="__BuildingName__" style="width:80px">
								</td>
							</tr>
							<tr>
								<th class="yb">住戸数<font color="red">※</font>
								</th>
								<td>
									住戸数(半角数字)
									<input type="number" name="editKosu__BuildingCD__" class="dwelling" value="__Kosu__" style="width: 80px; ime-mode: disabled" v-model="Kosu" onchange="calcDwellingCount();">
									戸&nbsp;階高
									<input type="number" name="editKaidaka__BuildingCD__" class="buildingKaidaka" value="__Kaidaka__" style="width: 80px; ime-mode: disabled" v-model="Kosu">
									階
								</td>
							</tr>
							<tr>
								<th class="yb">専有部</th>
								<td>
									<input type="text" name="editSenyuStartDate__BuildingCD__"  class="datepicker senyubucls" value="__BuildingSenyuStartDate__" id="SenyuStartDate__BuildingNo__">～
									<input type="text" name="editSenyuEndDate__BuildingCD__"  class="datepicker senyubucls" value="__BuildingSenyuEndDate__" id="SenyuEndDate__BuildingNo__">
								</td>
							</tr>
							<tr>
								<th class="yb"><span>受付締切日<font color="red">※</font></span></th>
								<td>
									<input type="text" name="editYoyakuEndDate__BuildingCD__" id="YoyakuEndDate__BuildingNo__" value="__BuildingYoyakuEndDate__" class="datepicker yoyaku-end-datepicker building-yoyaku-end">
								</td>
							</tr>
							<tr>
								<th class="yb">休工日</th>
								<td>
									<table class="table table-bordered table-sm" id="kyukobi_table__BuildingCD__">
										<colgroup>
											<col class="kyukobi-action-col">
											<col>
										</colgroup>
										<tr>
											<td><span class="kyukobi-action"><input type="button" value="＋1行追加" style="background-color: transparent" onclick="addHoliday('kyukobi_table__BuildingCD__', 'editHoliday__BuildingCD__[]', '__BuildingNo__');" /></span></td>
											<td>
												<span class="holiday-field-group"><input type="text" name="editHoliday__BuildingCD__[]" value="__BuildingHoliday1__" class="wHoliday__BuildingNo__" style="width:120px" autocomplete="off">__BuildingHolidayPeriodSelect1__</span><span class="holiday-field-group"><input type="text" name="editHoliday__BuildingCD__[]" value="__BuildingHoliday2__" class="wHoliday__BuildingNo__" style="width:120px" autocomplete="off">__BuildingHolidayPeriodSelect2__</span><span class="holiday-field-group"><input type="text" name="editHoliday__BuildingCD__[]" value="__BuildingHoliday3__" class="wHoliday__BuildingNo__" style="width:120px" autocomplete="off">__BuildingHolidayPeriodSelect3__</span>
											</td>
										</tr>
										__BuildingKyukoTable__
									</table>
								</td>
							</tr>
							<tr>
								<th class="yb">予備日</th>
								<td>
									<table class="table table-bordered table-sm" id="reserveday_table__BuildingCD__">
										<tr>
											<td>
												<input type="button" value="＋1行追加" style="background-color: transparent"
													onclick="addReserveDay('reserveday_table__BuildingCD__', 'editReserveDay__BuildingCD__[]', '__BuildingNo__');" />
											</td>
											<td>
												<input type="text" name="editReserveDay__BuildingCD__[]" value="__BuildingReserveDay1__" class="wReserveDay__BuildingNo__"
													style="width: 120px" autocomplete="off">
												　<input type="text" name="editReserveDay__BuildingCD__[]" value="__BuildingReserveDay2__" class="wReserveDay__BuildingNo__"
													style="width: 120px" autocomplete="off">
												　<input type="text" name="editReserveDay__BuildingCD__[]" value="__BuildingReserveDay3__" class="wReserveDay__BuildingNo__"
													style="width: 120px" autocomplete="off">
											</td>
										</tr>
										__BuildingReserveDayTable__
									</table>
								</td>
							</tr>

						</tbody>	
					</table>
						__BuildingLoop__
					__IfUpdate__

					__IfRegist__
						__BuildingLoop__
					<table class="table table-bordered table-sm row-table">
						<colgroup>
							<col style="width: 280px" />
							<col style="" />
						</colgroup>
						<tbody>
							<tr>
								<td colspan="2" class="building_boundary">棟__BuildingNo__
								<a href="javascript:void(0)" class="remove-building-btn" onclick="removeBuilding(this)"><img src="images/icon_delete.png"></a>
								</td>
							</tr>
							<tr>
								<th class="yb" width="28%">棟名称
								</th>
								<td width="72%">
									<input type="text" name="newBuilding[]" class="subBuildingName" value="__BuildingName__" style="width:80px">
								</td>
							</tr>
							<tr>
								<th class="yb">住戸数<font color="red">※</font>
								</th>
								<td>
									住戸数(半角数字)
									<input type="number" name="newKosu[]" class="dwelling" value="__Kosu__" style="width: 80px; ime-mode: disabled" v-model="Kosu" onchange="calcDwellingCount();">
									戸&nbsp;階高
									<input type="number" name="newKaidaka[]" class="buildingKaidaka" value="__Kaidaka__" style="width: 80px; ime-mode: disabled" v-model="Kosu">
									階
								</td>
							</tr>
							<tr>
								<th class="yb">専有部</th>
								<td>
									<input type="text" name="newSenyuStartDate[]"  class="datepicker senyubucls" value="__BuildingSenyuStartDate__" id="SenyuStartDate__BuildingNo__">～
									<input type="text" name="newSenyuEndDate[]"  class="datepicker senyubucls" value="__BuildingSenyuEndDate__" id="SenyuEndDate__BuildingNo__">
								</td>
							</tr>
							<tr>
								<th class="yb"><span>受付締切日<font color="red">※</font></span></th>
								<td>
									<input type="text" name="newYoyakuEndDate[]" id="YoyakuEndDate__BuildingNo__" value="__BuildingYoyakuEndDate__" class="datepicker yoyaku-end-datepicker building-yoyaku-end">
								</td>
							</tr>
							<tr>
								<th class="yb">休工日</th>
								<td>
									<table class="table table-bordered table-sm" id="kyukobi_table_new__BuildingNo__">
										<colgroup>
											<col class="kyukobi-action-col">
											<col>
										</colgroup>
										<tr>
											<td><span class="kyukobi-action"><input type="button" value="＋1行追加" style="background-color: transparent" onclick="addHoliday('kyukobi_table_new__BuildingNo__', 'newHoliday__BuildingNo__[]', '__BuildingNo__');" /></span></td>
											<td>
												<span class="holiday-field-group"><input type="text" name="newHoliday__BuildingNo__[]" value="__BuildingHoliday1__" class="wHoliday__BuildingNo__" style="width:120px" autocomplete="off"><select name="newHolidayPeriod__BuildingNo__[]" class="holiday-period"><option value="ALL" selected>全日</option><option value="AM">午前</option><option value="PM">午後</option></select></span><span class="holiday-field-group"><input type="text" name="newHoliday__BuildingNo__[]" value="__BuildingHoliday2__" class="wHoliday__BuildingNo__" style="width:120px" autocomplete="off"><select name="newHolidayPeriod__BuildingNo__[]" class="holiday-period"><option value="ALL" selected>全日</option><option value="AM">午前</option><option value="PM">午後</option></select></span><span class="holiday-field-group"><input type="text" name="newHoliday__BuildingNo__[]" value="__BuildingHoliday3__" class="wHoliday__BuildingNo__" style="width:120px" autocomplete="off"><select name="newHolidayPeriod__BuildingNo__[]" class="holiday-period"><option value="ALL" selected>全日</option><option value="AM">午前</option><option value="PM">午後</option></select></span>
											</td>
										</tr>
										__BuildingKyukoTable__
									</table>
								</td>
							</tr>
							<tr>
								<th class="yb">予備日</th>
								<td>
									<table class="table table-bordered table-sm" id="reserveday_table_new__BuildingNo__">
										<tr>
											<td>
												<input type="button" value="＋1行追加" style="background-color: transparent"
													onclick="addReserveDay('reserveday_table_new__BuildingNo__', 'newReserveDay__BuildingNo__[]', '__BuildingNo__');" />
											</td>
											<td>
												<input type="text" name="newReserveDay__BuildingNo__[]" value="__BuildingReserveDay1__" class="wReserveDay__BuildingNo__"
													style="width: 120px" autocomplete="off">
												　<input type="text" name="newReserveDay__BuildingNo__[]" value="__BuildingReserveDay2__" class="wReserveDay__BuildingNo__"
													style="width: 120px" autocomplete="off">
												　<input type="text" name="newReserveDay__BuildingNo__[]" value="__BuildingReserveDay3__" class="wReserveDay__BuildingNo__"
													style="width: 120px" autocomplete="off">
											</td>
										</tr>
										__BuildingReserveDayTable__
									</table>
								</td>
							</tr>

						</tbody>	
					</table>
					<input type="hidden" name="newBuilingNo[]" value="__BuildingNo__">
						__BuildingLoop__

					__IfRegist__

					<div class="mt-3" id="charge_row">
						<input type="hidden" name="work" value="1" >
						<input type="submit" value="登録する" class="btn btn-primary blue" onclick="return check_form();"/>
					
					</div>


				</form>
				__IfDeveloper__

				__IfWorker__
					<div class="mintitle">
						物件基本情報
					</div>
					<table class="table table-bordered table-sm row-table">
						<tr>
							<th width="28%" class="yb">物件名
							</th>
							<td width="72%">
								__wBukkenName__
							</td>
						</tr>
						<tr>

							<th class="yb">作業名称
							</th>
							<td>
								__wSagyoName__
							</td>
						</tr>
						<tr>

							<th class="yb">住所
							</th>
							<td>
								__wAddress__
							</td>
						</tr>
						<tr>
							<th class="yb">担当者</th>
							<td>
							__TantoCD1Name__　__TantoCD2Name__　__TantoCD3Name__
							</td>
						</tr>
						<tr>
							<th class="yb">協力会社</th>
							<td>__GyosyaCompanySelName__</td>
						</tr>
						<tr>
							<th class="yb">管理会社</th>
							<td>__KanriCompanySelName__</td>
						</tr>
						<tr>
							<th class="yb">担当支店・支社</th>
							<td>__BrancheCompanySelName__</td>
						</tr>
						<tr>
							<th class="yb">作業期間(全体)
								<span style="display: block; color: red">*棟毎の専有部の入力がない場合、作業期間(全体)が適用されます。</span>
							</th>
							<td>
								__SenyuStartDate__ ～ __SenyuEndDate__
							</td>
						</tr>
						<tr>
							<th class="yb">共用部
							</th>
							<td>
								__KyoyobuStartDate__ ～ __KyoyobuEndDate__
							</td>
						</tr>
						<tr>
							<th class="yb">
								<span>作業時間</span>
								<span style="display: block; color: blue">配布資料に自動反映されます。</span>
							</th>
							<td>
								__MinuteTime__分
							</td>
						</tr>
						<tr>
							<th class="yb">
								<span>作業指示</span>
								<span style="display: block; color: red">*管理人常駐日や工事可能な曜日などの記載にお使い下さい。</span>
							</th>
							<td>
								__wBukkenMemo__
							</td>
						</tr>
						<tr>
							<th class="yb">
								<span>備考欄</span>
							</th>
							<td>
								__Biko__
							</td>
						</tr>


						<tr>
							<th class="yb">総住戸数・棟数
							</th>
							<td>
								総住戸数:__TotalKosuCount__ &nbsp;&nbsp;&nbsp; 棟数:__TotalBuildingCount__
							</td>
						</tr>
					</table>

					<table class="table table-bordered table-sm row-table">
						<colgroup>
							<col style="width: 280px" />
							<col style="" />
						</colgroup>
						<tr id="main_building_label">
							<td colspan="2" class="building_boundary">棟1</td>
						</tr>
						<tr>
							<th class="yb" width="28%">棟名称
							</th>
							<td width="72%">
								__wBuilding__
							</td>
						</tr>
						<tr>
							<th class="yb">住戸数
							</th>
							<td>
								住戸数(半角数字):__wKosu__
								戸&nbsp;階高:__wKaidaka__
								階
							</td>
						</tr>
						<tr>
							<th class="yb">専有部</th>
							<td>
								__wkSenyuDate1__
							</td>
						</tr>
						<tr>
							<th class="yb">受付締切日</th>
							<td>
								__YoyakuEndDate__
							</td>
						</tr>
						<tr>
							<th class="yb">休工日</th>
							<td>
								__wkHoliday1__

							</td>
						</tr>
						<tr>
							<th class="yb">予備日</th>
							<td>
								__wkReserveDay1__

							</td>
						</tr>
					</table>

					__BuildingLoop__
					<table class="table table-bordered table-sm row-table">
						<colgroup>
							<col style="width: 280px" />
							<col style="" />
						</colgroup>
						<tbody>
							<tr>
								<td colspan="2" class="building_boundary">棟__BuildingNo__
								</td>
							</tr>
							<tr>
								<th class="yb" width="28%">棟名称
								</th>
								<td width="72%">
									__BuildingName__
								</td>
							</tr>
							<tr>
								<th class="yb">住戸数
								</th>
								<td>
									住戸数(半角数字):__Kosu__
									戸&nbsp;階高:__Kaidaka__
									階
								</td>
							</tr>
							<tr>
								<th class="yb">専有部</th>
								<td>
									__BuildingSenyuDate__
								</td>
							</tr>
							<tr>
								<th class="yb">受付締切日</th>
								<td>
									__BuildingYoyakuEndDate__
								</td>
							</tr>
							<tr>
								<th class="yb">休工日</th>
								<td>
									__BuildingHolidayStr__
								</td>
							</tr>
							<tr>
								<th class="yb">予備日</th>
								<td>
									__BuildingReserveDayStr__
								</td>
							</tr>
						</tbody>	
					</table>
					__BuildingLoop__

				__IfWorker__

          </div>
      </div>
  </div>
  <div class="container">
      <div class="row">
          <div class="col-12">
			__SFooter__ __SCopyright__
          </div>
      </div>
  </div>
	<!-- <script>
		// Vueの記述 headの中だと動かない
		var app = new Vue({

			el: '#app',
			data: {
				bukkenname: '__wBukkenName__',
				bukkenname_hurigana: '__wBukkenName_Hurigana__',
				existmansionname: false,
				Kosu: '__wKosu__',
				address: '__wAddress__',
				TatoFlg: '__TatoFlg__',
				TantoCD1Selected: '__wTantoCD1__',
				TantoCD2Selected: '__wTantoCD2__',
				TantoCD3Selected: '__wTantoCD3__',
				TantoCD4Selected: '__wTantoCD4__',
				TantoCD5Selected: '__wTantoCD5__',
				GyosyaCompany: '__wGyosyaCD__',
				KanriCompany: '__wKanriCompanyCD__',
				KikiTenkenMonth: '__KikiTenkenMonth__',
				SougouTenkenMonth: '__SougouTenkenMonth__',
				KikiTenkenKikan: '__KikiTenkenKikan__',
				SougouTenkenKikan: '__SougouTenkenKikan__',

				SougouAMKojiTime: '__SougouAMKojiTime__',
				SougouPMKojiTime: '__SougouPMKojiTime__',
				KikiAMKojiTime: '__KikiAMKojiTime__',
				KikiPMKojiTime: '__KikiPMKojiTime__',

				ExistTenkenKikanSelected: '__ExistTenkenKikan__',
				ExistTenkenKikan_SougouSelected: '__ExistTenkenKikan_Sougou__',

				// 防災情報
				BousaiFlg: '__BousaiFlg__',
				BousaiTenkenMonth: '__BousaiTenkenMonth__',
				BousaiKojiTime: '__BousaiKojiTime__',
				GyosyaBousaiCD: '__GyosyaBousaiCD__',
				KanriCompanyBousaiCD: '__KanriCompanyBousaiCD__',
				WorkPlace: '__WorkPlace__',

				Same_kiki_sougou_flg: '__Same_kiki_sougou_flg__',
			},
			methods: {

				onSubmit: function(e) {

					if (!this.bukkenname) {
						alert("物件名が未入力です");
						this.existmansionname = true;
						e.preventDefault();
					}

					if (!this.address) {
						alert("住所が未入力です");
						e.preventDefault();
					}

				},
			}

		})
	</script> -->

	<script>
	const form = document.getElementById('mainform');
	if(form){
		const inputs = form.querySelectorAll('input[type="text"], select, textarea');
		var initialValues = {};

		inputs.forEach(el => {
			initialValues[el.name] = el.value;
		});
	}

	function deepEqual(a, b) {
		if (a === b) return true;

		if (typeof a !== "object" || typeof b !== "object" || a === null || b === null) {
			return false;
		}

		const keysA = Object.keys(a);
		const keysB = Object.keys(b);

		if (keysA.length !== keysB.length) return false;

		for (let key of keysA) {
			if (!keysB.includes(key)) return false;
			if (!deepEqual(a[key], b[key])) return false;
		}

		return true;
	}

	function go_top(url){
		if(form){
			var curInputs = form.querySelectorAll('input[type="text"], select, textarea');
			var curValues = {};
			curInputs.forEach(el => {
				curValues[el.name] = el.value;
			});
			if(deepEqual(initialValues, curValues)){
				window.location.href = url;
			}else{
				if(confirm("入力中のデータが削除されますがよろしいですか？"))
					window.location.href = url;
			}
		}else{
			window.location.href = url;
		}
	}

	var LastBuildingNo = parseInt('__LastBuildingNo__');
	if(isNaN(LastBuildingNo))
		LastBuildingNo = 1;

	function applyDatepicker(){
		let gSenyuStartDate = $("#SenyuStartDate").val();
		let gSenyuEndDate = $("#SenyuEndDate").val();

		let SenyuStartDate = $("#SenyuStartDate1").val();
		if(SenyuStartDate == "")
			SenyuStartDate = gSenyuStartDate;
		else if(SenyuStartDate < gSenyuStartDate)
			SenyuStartDate = gSenyuStartDate;

		let SenyuEndDate = $("#SenyuEndDate1").val();
		if(SenyuEndDate == "")
			SenyuEndDate = gSenyuEndDate;
		else if(SenyuEndDate > gSenyuEndDate)
			SenyuEndDate = gSenyuEndDate;

		$('#SenyuStartDate1').datepicker({
			numberOfMonths: 2,
			minDate: new Date($("#SenyuStartDate").val()),
			maxDate: new Date(SenyuEndDate),
			showButtonPanel: true
		});

		$('#SenyuEndDate1').datepicker({
			numberOfMonths: 2,
			minDate: new Date(SenyuStartDate),
			maxDate: new Date($("#SenyuEndDate").val()),
			showButtonPanel: true
		});
		
		$('#SenyuStartDate1').on('change', function() {
			$("#SenyuEndDate1").datepicker('option', {
				minDate: new Date($("#SenyuStartDate1").val()),
			});
			updateDatepicker();
		});	

		$('#SenyuEndDate1').on('change', function() {
			$("#SenyuStartDate1").datepicker('option', {
				maxDate: new Date($("#SenyuEndDate1").val()),
			});
			updateDatepicker();
		});				

		$(".wHoliday").datepicker({
			numberOfMonths: 1,
			minDate: new Date(SenyuStartDate),
			maxDate: new Date(SenyuEndDate),
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
				let start = new Date(SenyuStartDate);
				let end = new Date(SenyuEndDate);
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

		for(let aBuildingNo = 2; aBuildingNo <= LastBuildingNo; aBuildingNo++){
			if ($('#SenyuStartDate'+aBuildingNo).length) {
				let SenyuStartDate = $("#SenyuStartDate"+aBuildingNo).val();
				if(SenyuStartDate == "")
					SenyuStartDate = gSenyuStartDate;
				else if(SenyuStartDate < gSenyuStartDate)
					SenyuStartDate = gSenyuStartDate;

				let SenyuEndDate = $("#SenyuEndDate"+aBuildingNo).val();
				if(SenyuEndDate == "")
					SenyuEndDate = gSenyuEndDate;
				else if(SenyuEndDate > gSenyuEndDate)
					SenyuEndDate = gSenyuEndDate;

				$('#SenyuStartDate'+aBuildingNo).datepicker({
					numberOfMonths: 2,
					minDate: new Date($('#SenyuStartDate').val()),
					maxDate: new Date(SenyuEndDate),
					showButtonPanel: true
				});

				$('#SenyuEndDate'+aBuildingNo).datepicker({
					numberOfMonths: 2,
					minDate: new Date(SenyuStartDate),
					maxDate: new Date($("#SenyuEndDate").val()),
					showButtonPanel: true
				});

				$("#SenyuStartDate"+aBuildingNo).on('change', function() {
					$("#SenyuEndDate"+aBuildingNo).datepicker('option', {
						minDate: new Date($("#SenyuStartDate"+aBuildingNo).val()),
					});
					updateDatepicker();
				});	

				$("#SenyuEndDate"+aBuildingNo).on('change', function() {
					$("#SenyuStartDate"+aBuildingNo).datepicker('option', {
						maxDate: new Date($("#SenyuEndDate"+aBuildingNo).val()),
					});
					updateDatepicker();
				});	

				$(".wHoliday"+aBuildingNo).datepicker({
					numberOfMonths: 1,
					minDate: new Date(SenyuStartDate),
					maxDate: new Date(SenyuEndDate),
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

				$(".wReserveDay"+aBuildingNo).datepicker({
					numberOfMonths: 1,
					showButtonPanel: true,
					closeText: '閉じる',
					currentText: '今日',
					beforeShowDay: function(date) {
						let start = new Date(SenyuStartDate);
						let end = new Date(SenyuEndDate);
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

			}

		}

	}

	function updateDatepicker(){
		let gSenyuStartDate = $("#SenyuStartDate").val();
		let gSenyuEndDate = $("#SenyuEndDate").val();

		let SenyuStartDate = $("#SenyuStartDate1").val();
		if(SenyuStartDate == "")
			SenyuStartDate = gSenyuStartDate;
		else if(SenyuStartDate < gSenyuStartDate)
			SenyuStartDate = gSenyuStartDate;

		let SenyuEndDate = $("#SenyuEndDate1").val();
		if(SenyuEndDate == "")
			SenyuEndDate = gSenyuEndDate;
		else if(SenyuEndDate > gSenyuEndDate)
			SenyuEndDate = gSenyuEndDate;

		$("#SenyuStartDate1").datepicker('option', {
			minDate: new Date($("#SenyuStartDate").val()),
			maxDate: new Date(SenyuEndDate),
		});
		$("#SenyuEndDate1").datepicker('option', {
			minDate: new Date(SenyuStartDate),
			maxDate: new Date($("#SenyuEndDate").val()),
		});

		$('#SenyuStartDate1').on('change', function() {
			$("#SenyuEndDate1").datepicker('option', {
				minDate: new Date($("#SenyuStartDate1").val()),
			});
			updateDatepicker();
		});	

		$('#SenyuEndDate1').on('change', function() {
			$("#SenyuStartDate1").datepicker('option', {
				maxDate: new Date($("#SenyuEndDate1").val()),
			});
			updateDatepicker();
		});	

		$(".wHoliday").datepicker('option', {
			minDate: new Date(SenyuStartDate),
			maxDate: new Date(SenyuEndDate),
		});

		$(".wReserveDay").datepicker('option', {
			beforeShowDay: function(date) {
				let start = new Date(SenyuStartDate);
				let end = new Date(SenyuEndDate);
				start.setHours(0, 0, 0, 0);
				end.setHours(0, 0, 0, 0);

				if (date >= start && date <= end) {
					return [false, "", "利用不可"];
				}
				return [true, ""];
			},
		});	

		for(let aBuildingNo = 2; aBuildingNo <= LastBuildingNo; aBuildingNo++){
			if ($('#SenyuStartDate'+aBuildingNo).length) {
				let SenyuStartDate = $("#SenyuStartDate"+aBuildingNo).val();
				if(SenyuStartDate == "")
					SenyuStartDate = gSenyuStartDate;
				else if(SenyuStartDate < gSenyuStartDate)
					SenyuStartDate = gSenyuStartDate;

				let SenyuEndDate = $("#SenyuEndDate"+aBuildingNo).val();
				if(SenyuEndDate == "")
					SenyuEndDate = gSenyuEndDate;
				else if(SenyuEndDate > gSenyuEndDate)
					SenyuEndDate = gSenyuEndDate;

				$("#SenyuStartDate"+aBuildingNo).datepicker('option', {
					minDate: new Date($('#SenyuStartDate').val()),
					maxDate: new Date(SenyuEndDate),
				});
				$("#SenyuEndDate"+aBuildingNo).datepicker('option', {
					minDate: new Date(SenyuStartDate),
					maxDate: new Date($("#SenyuEndDate").val()),
				});

				$('#SenyuStartDate'+aBuildingNo).on('change', function() {
					$("#SenyuEndDate"+aBuildingNo).datepicker('option', {
						minDate: new Date($("#SenyuStartDate"+aBuildingNo).val()),
					});
					updateDatepicker();
				});	

				$('#SenyuEndDate'+aBuildingNo).on('change', function() {
					$("#SenyuStartDate"+aBuildingNo).datepicker('option', {
						maxDate: new Date($("#SenyuEndDate"+aBuildingNo).val()),
					});
					updateDatepicker();
				});	

				$(".wHoliday"+aBuildingNo).datepicker('option', {
					minDate: new Date(SenyuStartDate),
					maxDate: new Date(SenyuEndDate),
				});

				$(".wReserveDay"+aBuildingNo).datepicker('option', {
					beforeShowDay: function(date) {
						let start = new Date(SenyuStartDate);
						let end = new Date(SenyuEndDate);
						start.setHours(0, 0, 0, 0);
						end.setHours(0, 0, 0, 0);

						if (date >= start && date <= end) {
							return [false, "", "利用不可"];
						}
						return [true, ""];
					},
				});	

			}

		}

	}	
	
	$(function () {
		jQuery("#SenyuStartDate").datepicker({
			maxDate: new Date($("#SenyuEndDate").val()),
			numberOfMonths: 2,
			showButtonPanel: true
		});

		jQuery("#SenyuEndDate").datepicker({
			minDate: new Date($("#SenyuStartDate").val()),
			numberOfMonths: 2,
			showButtonPanel: true
		});

		jQuery("#KyoyobuStartDate").datepicker({
			maxDate: new Date($("#KyoyobuEndDate").val()),
			numberOfMonths: 2,
			showButtonPanel: true
		});

		jQuery("#KyoyobuEndDate").datepicker({
			minDate: new Date($("#KyoyobuStartDate").val()),
			numberOfMonths: 2,
			showButtonPanel: true
		});
/*
		jQuery(".senyubucls").datepicker({
			numberOfMonths: 2,
			minDate: new Date($("#SenyuStartDate").val()),
			maxDate: new Date($("#SenyuEndDate").val()),
			showButtonPanel: true
		});
*/		
		refeshMainBuildingLabel();
		calcDwellingCount();

		$('#SenyuStartDate').on('change', function() {
			$("#SenyuEndDate").datepicker('option', {
				minDate: new Date($("#SenyuStartDate").val()),
			});

			updateDatepicker();
		});	

		$('#SenyuEndDate').on('change', function() {
			$("#SenyuStartDate").datepicker('option', {
				maxDate: new Date($("#SenyuEndDate").val()),
			});

			updateDatepicker();
		});	

		$('#KyoyobuStartDate').on('change', function() {
			$("#KyoyobuEndDate").datepicker('option', {
				minDate: new Date($("#KyoyobuStartDate").val()),
			});
		});	

		$('#KyoyobuEndDate').on('change', function() {
			$("#KyoyobuStartDate").datepicker('option', {
				maxDate: new Date($("#KyoyobuEndDate").val()),
			});
		});	


		
		$('.senyubucls').on('change', function() {
			updateDatepicker();
		});	

		applyDatepicker();
	});


	function holidayPeriodSelectHtmlJs(name){
		return "<select name='"+name+"' class='holiday-period'><option value='ALL' selected>全日</option><option value='AM'>午前</option><option value='PM'>午後</option></select>";
	}

	function holidayFieldGroupHtml(objName, aBuildingNo, periodName){
		return "<span class='holiday-field-group'><input type='text' name='"+objName+"' value='' class='wHoliday"+aBuildingNo+"' style='width:120px' autocomplete='off'>"+holidayPeriodSelectHtmlJs(periodName)+"</span>";
	}

	function addHoliday(tableId, objName, aBuildingNo){
		var table = document.getElementById(tableId);
		var row = table.insertRow(-1);
		var cell1 = row.insertCell(-1);
		var cell2 = row.insertCell(-1);
		var periodName = objName.replace('Holiday', 'HolidayPeriod');

		cell1.innerHTML = "<span class='kyukobi-action'><a href='javascript:void(0)' class='remove-btn' onclick='removeList(this)'><img src='images/icon_delete.png'></a></span>";
		cell2.innerHTML = holidayFieldGroupHtml(objName, aBuildingNo, periodName)
			+ holidayFieldGroupHtml(objName, aBuildingNo, periodName)
			+ holidayFieldGroupHtml(objName, aBuildingNo, periodName);

		applyDatepicker();
	}

	function addReserveDay(tableId, objName, aBuildingNo){
		var table = document.getElementById(tableId);
		// 行を行末に追加
		var row = table.insertRow(-1);
		// セルの挿入
		var cell1 = row.insertCell(-1);
		var cell2 = row.insertCell(-1);

		cell1.innerHTML =
			"<a href='javascript:void(0)' class='remove-btn' onclick='removeList(this)'><img src='images/icon_delete.png'></a>";
		cell2.innerHTML =
			"<input type='text' name='"+objName+"' value='' class='wReserveDay"+aBuildingNo+"' style='width:120px' autocomplete='off'>";
		cell2.innerHTML +=
			"　<input type='text' name='"+objName+"' value='' class='wReserveDay"+aBuildingNo+"' style='width:120px' autocomplete='off'>";
		cell2.innerHTML +=
			"　<input type='text' name='"+objName+"' value='' class='wReserveDay"+aBuildingNo+"' style='width:120px' autocomplete='off'>";

		applyDatepicker();
	}	

	function removeList(obj) {
		if(confirm("削除しますか？")){
			//行を削除
			// 削除ボタンを押下された行を取得
			var tr = obj.parentNode.parentNode;
			// trのインデックスを取得して行を削除する
			tr.parentNode.deleteRow(tr.sectionRowIndex);
		}
	}		

	function removeBuilding(obj) {
		if(confirm("削除しますか？")){
			//行を削除
			// 削除ボタンを押下された行を取得
			var table = obj.parentNode.parentNode.parentNode.parentNode;
			if(table){
				table.remove();
			}
			calcDwellingCount();
		}
	}		

	function addBuilding(){
		LastBuildingNo ++;
		var sHTML = '<table class="table table-bordered table-sm row-table">\
						<colgroup>\
							<col style="width: 280px" />\
							<col style="" />\
						</colgroup>\
						<tbody>\
							<tr>\
								<td colspan="2" class="building_boundary">棟'+LastBuildingNo+'\
								<a href="javascript:void(0)" class="remove-building-btn" onclick="removeBuilding(this)"><img src="images/icon_delete.png"></a>\
								</td>\
							</tr>\
							<tr>\
								<th class="yb" width="28%">棟名称\
								<td width="72%">\
									<input type="text" name="newBuilding[]"  class="subBuildingName" value="" style="width:80px">\
								</td>\
							</tr>\
							<tr>\
								<th class="yb">住戸数<font color="red">※</font></th>\
								<td>\
									住戸数(半角数字)\
									<input type="number" class="dwelling" name="newKosu[]" value="" style="width: 80px; ime-mode: disabled" onchange="calcDwellingCount();">\
									戸&nbsp;階高\
									<input type="number" name="newKaidaka[]"  class="buildingKaidaka" value="" style="width: 80px; ime-mode: disabled">\
									階\
								</td>\
							</tr>\
							<tr>\
								<th class="yb">専有部</th>\
								<td>\
									<input type="text" name="newSenyuStartDate[]"  class="datepicker senyubucls" value="" id="SenyuStartDate'+LastBuildingNo+'">～\
									<input type="text" name="newSenyuEndDate[]"  class="datepicker senyubucls" value="" id="SenyuEndDate'+LastBuildingNo+'">\
								</td>\
							</tr>\
							<tr>\
								<th class="yb"><span>受付締切日<font color="red">※</font></span></th>\
								<td>\
									<input type="text" name="newYoyakuEndDate[]" id="YoyakuEndDate'+LastBuildingNo+'" value="" class="datepicker yoyaku-end-datepicker building-yoyaku-end">\
								</td>\
							</tr>\
							<tr>\
								<th class="yb">休工日</th>\
								<td>\
									<table class="table table-bordered table-sm" id="kyukobi_table_new_'+LastBuildingNo+'">\
										<colgroup><col class="kyukobi-action-col"><col></colgroup>\
										<tr>\
											<td><span class="kyukobi-action"><input type="button" value="＋1行追加" style="background-color: transparent" onclick="addHoliday(\''+'kyukobi_table_new_'+LastBuildingNo+'\', \'newHoliday'+LastBuildingNo+'[]\', \''+LastBuildingNo+'\');" /></span></td>\
											<td>\
												<span class="holiday-field-group"><input type="text" name="newHoliday'+LastBuildingNo+'[]" value="" class="wHoliday'+LastBuildingNo+'" style="width:120px" autocomplete="off"><select name="newHolidayPeriod'+LastBuildingNo+'[]" class="holiday-period"><option value="ALL" selected>全日</option><option value="AM">午前</option><option value="PM">午後</option></select></span><span class="holiday-field-group"><input type="text" name="newHoliday'+LastBuildingNo+'[]" value="" class="wHoliday'+LastBuildingNo+'" style="width:120px" autocomplete="off"><select name="newHolidayPeriod'+LastBuildingNo+'[]" class="holiday-period"><option value="ALL" selected>全日</option><option value="AM">午前</option><option value="PM">午後</option></select></span><span class="holiday-field-group"><input type="text" name="newHoliday'+LastBuildingNo+'[]" value="" class="wHoliday'+LastBuildingNo+'" style="width:120px" autocomplete="off"><select name="newHolidayPeriod'+LastBuildingNo+'[]" class="holiday-period"><option value="ALL" selected>全日</option><option value="AM">午前</option><option value="PM">午後</option></select></span>\
											</td>\
										</tr>\
									</table>\
								</td>\
							</tr>\
							<tr>\
								<th class="yb">予備日</th>\
								<td>\
									<table class="table table-bordered table-sm" id="reserveday_table_new_'+LastBuildingNo+'">\
										<tr>\
											<td>\
												<input type="button" value="＋1行追加" style="background-color: transparent" onclick="addReserveDay(\''+'reserveday_table_new_'+LastBuildingNo+'\', \'newReserveDay'+LastBuildingNo+'[]\', \''+LastBuildingNo+'\');" />\
											</td>\
											<td>\
												<input type="text" name="newReserveDay'+LastBuildingNo+'[]" value="" class="wReserveDay'+LastBuildingNo+'"\
													style="width: 120px" autocomplete="off">\
												　<input type="text" name="newReserveDay'+LastBuildingNo+'[]" value="" class="wReserveDay'+LastBuildingNo+'"\
													style="width: 120px" autocomplete="off">\
												　<input type="text" name="newReserveDay'+LastBuildingNo+'[]" value="" class="wReserveDay'+LastBuildingNo+'"\
													style="width: 120px" autocomplete="off">\
											</td>\
										</tr>\
									</table>\
								</td>\
							</tr>\
						</tbody>\
					</table>\
					<input type="hidden" name="newBuilingNo[]" value="'+LastBuildingNo+'">';
		$('#charge_row').before(sHTML);
/*
		$(".senyubucls").datepicker({
			numberOfMonths: 2,
			minDate: new Date($('input:text[id="SenyuStartDate"]').val()),
			maxDate: new Date($('input:text[id="SenyuEndDate"]').val()),
			showButtonPanel: true
		});
*/		
		applyDatepicker();
		$(".yoyaku-end-datepicker").datepicker({
			numberOfMonths: 2,
			showButtonPanel: true
		});
		calcDwellingCount();
	}
	
	function calcDwellingCount(){
		let total_dwelling = 0;
		let count_dwelling = 0;
		$('.dwelling').each(function() {
			let dwelling = parseInt($(this).val());
			if(isNaN(dwelling))
				dwelling = 0;
			total_dwelling += dwelling;
			count_dwelling ++;
		});
		$("#house_count").html(total_dwelling);
		$("#building_count").html(count_dwelling);
	}

	function check_form(){
/*		
		var isSubBuildingBlank = false;
		var blankObj = null;
		$(".subBuildingName").each(function () {
			if (!isSubBuildingBlank && $(this).val() == '') {
				isSubBuildingBlank = true;
				blankObj = $(this);
			}
		});

		if(isSubBuildingBlank){
			alert("追加する棟の名称を入力してください。");
			blankObj.focus();
			return false;
		}
*/
		var ErrorString = '';
		// 物件名
		let wBukkenNameVal = document.getElementById("wBukkenName").value;
		if(wBukkenNameVal.trim() == ""){
			if(ErrorString != '')
				ErrorString += '<br>';
			ErrorString += "物件名は必須項目です。";
		}

		// 作業名称
		let wSagyoNameVal = document.getElementById("wSagyoName").value;
		if(wSagyoNameVal.trim() == ""){
			if(ErrorString != '')
				ErrorString += '<br>';
			ErrorString += "作業名称は必須項目です。";
		}

		// 住所
		let wAddressVal = document.getElementById("wAddress").value;
		if(wAddressVal.trim() == ""){
			if(ErrorString != '')
				ErrorString += '<br>';
			ErrorString += "住所は必須項目です。";
		}

		// 担当支店・支社
		let BrancheCompanyVal = document.getElementById("BrancheCompany").value;
		if(BrancheCompanyVal == ""){
			if(ErrorString != '')
				ErrorString += '<br>';
			ErrorString += "担当支店・支社は必須項目です。";
		}

		// 作業期間
		let SenyuStartDateVal = document.getElementById("SenyuStartDate").value;
		let SenyuEndDateVal = document.getElementById("SenyuEndDate").value;
		if(SenyuStartDateVal == "" || SenyuEndDateVal == ""){
			if(ErrorString != '')
				ErrorString += '<br>';
			ErrorString += "作業期間は必須項目です。";
		}

		// 受付締切日（棟ごと）
		let YoyakuEndDateVal = document.getElementById("YoyakuEndDate").value;
		if(YoyakuEndDateVal == ""){
			if(ErrorString != '')
				ErrorString += '<br>';
			ErrorString += "受付締切日は必須項目です。";
		}
		$(".building-yoyaku-end").each(function() {
			if($(this).val() == ""){
				if(ErrorString.indexOf("受付締切日は必須項目です。") < 0){
					if(ErrorString != '')
						ErrorString += '<br>';
					ErrorString += "受付締切日は必須項目です。";
				}
			}
		});

		// 作業時間
		let MinuteTimeVal = document.getElementById("MinuteTime").value;
		if(MinuteTimeVal.trim() == ""){
			if(ErrorString != '')
				ErrorString += '<br>';
			ErrorString += "作業時間は必須項目です。";
		}

		// 住戸数 住戸数
		let wKosuVal = document.getElementById("wKosu").value;
		let wKaidakaVal = document.getElementById("wKaidaka").value;
		if(wKosuVal == "" || wKaidakaVal == ""){
			if(ErrorString != '')
				ErrorString += '<br>';
			ErrorString += "住戸数は必須項目です。";
		}

		let error_flg = 0;
		$("input[name='newKosu[]']").map(function() {
			if($(this).val() == "" || $(this).parent().find(".buildingKaidaka").val() == ""){
				error_flg = "1";
			}
		})
		if(error_flg){
			if(ErrorString != '')
				ErrorString += '<br>';
			ErrorString += "多棟の住戸数は必須項目です。";
		}


		if(ErrorString != ''){
			$("#ErrorString").html(ErrorString);
			window.scrollTo({
				top: 0,
				behavior: 'smooth'
			});
			return false;
		}
		$("#ErrorString").html("");
		return true;
	}

	function refeshMainBuildingLabel(){
/*		
		if($("#wBuilding").val() != ''){
			$("#main_building_label").show();
		}
		else{
			let count_buildingKaidaka = 0;
			$('.buildingKaidaka').each(function() {
				count_buildingKaidaka ++;
			});
			if(count_buildingKaidaka > 0)
				$("#main_building_label").show();
			else
				$("#main_building_label").hide();
		}
*/
	}
	$('#mainform').on('keydown', 'input', function (event) {
		if (event.key === 'Enter') {
			event.preventDefault();
			return false;
		}
	});
	</script>
</body>

</html>
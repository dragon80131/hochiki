<html>
<head>
<title>部屋番号管理 − 工事基本情報フォーム</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>
__IfAjax__<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__</head>

<body>
<div class="content">
<h2 class="admin-title">顧客管理 − 工事基本情報フォーム</h2>
<br />

__IfAdminSystem__<h2 class="navigation"><a href="#" onclick="javascript:move('client_list.php')">&lt;&lt;&lt; クライアントリストへ</a></h2>
__IfAdminSystem__<h2 class="navigation"><a href="#" onclick="javascript:location.href='reserve_set_menu.php'">&lt;&lt;&lt; システム設定メニューへ</a></h2>
<h2 class="navigation"><a href="#" onclick="javascript:location.href='index.php'">&lt;&lt;&lt; トップページ(メニュー)へ</a></h2>
<br />




<form method="POST" action="client_list.php" name="mainform">

<table class="common-list" width="700">
	<tr id="blockName">
		<td nowrap class="common-list-title" colspan="2">基本設定情報</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">設定コード</td>
		<td nowrap class="common-list-value-left">
			__wSettingCD__　(システムが決定しているので変更できません)
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">時間単位</td>
		<td nowrap class="common-list-value-left">
			<select name="wMinuteUnit">
__MinuteUnitLoop__			<option value="__MinuteUnitValue__"__MinuteUnitSelected__>__MinuteUnitValue__
__MinuteUnitLoop__		</select>分
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" width="200">予約時間(固定)</td>
		<td class="common-list-value-left">
			<select name="wReserveTime">
__ReserveTimeLoop__			<option value="__ReserveTimeValue__"__ReserveTimeSelected__>__ReserveTimeValue__
__ReserveTimeLoop__		</select>分
		</td>
	</tr>

	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">マンション名</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wMansionName" value="__wMansionName__" size="40"  class="form">
  ★多棟あり/なし



__TatoLoop__
    	        <input type="checkbox" name="wTatoFlg[__Index__]" value="__TatoFlg__" __TatoChecked__ >__TatoFlg__
__TatoLoop__

<a href=./tato.php >棟ごとの日程入力</a>
		</td>
	</tr>

	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">依頼会社</td>
		<td nowrap class="common-list-value-left">
__CompanyLoop__
		<input type="radio" name="wCompany" value="__Company__"__CompanyChecked__>__Company__
__CompanyLoop__

	</td>
	</tr>

	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">支店名</td>
		<td nowrap class="common-list-value-left">

			<input type="text" name="wSiten" value="__wSiten__" size="20"  class="form">


	</td>
	</tr>


	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">注意事項</td>
		<td nowrap class="common-list-value-left">
			<textarea name="wNotes" value="__wNotes__" cols="60" rows="5" __IME_ON__ class="form">__wNotes__</textarea>
		</td>
	</tr>

	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">何班体制</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wHansu" value="__wHansu__" size="10"  class="form">班
		</td>
	</tr>

	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">工事所要時間</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wConstTime" value="__wConstTime__" size="10"  class="form">分
		</td>
	</tr>


	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">開錠方法</td>
		<td nowrap class="common-list-value-left">
__KaijyoLoop__
		<input type="checkbox" name="wKaijyo[__Index__]" value="__Kaijyo__"__KaijyoChecked__>__Kaijyo__
__KaijyoLoop__
	</td>
	</tr>

	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">オプション支払方法</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wShiharai" value="__wShiharai__" size="40"  class="form">
		</td>
	</tr>


	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">AM,PM,1PM2の枠構成（例：７５５）</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wWakuRange" value="__wWakuRange__" size="12" __IME_OFF__ class="form">
		</td>
	</tr>

	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">1日の最大の枠数（ダミー込）</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wWaku" value="__wWaku__" size="4" __IME_OFF__ class="form">
		</td>
	</tr>




	<tr id="blockName">
		<td nowrap class="common-list-title" width="200"><font color="yellow">共有部工事開始日(例20131013)</font></td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wKyoyuStartDate" value="__wKyoyuStartDate__" size="20" __IME_OFF__ class="form">
		</td>
	</tr>

	<tr id="blockName">
		<td nowrap class="common-list-title" width="200"><font color="yellow">共有部工事終了日(例20131015)</font></td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wKyoyuEndDate" value="__wKyoyuEndDate__" size="20" __IME_OFF__ class="form">
		</td>
	</tr>


	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">専有部工事開始日(例20131020)</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wReserveTo2" value="__wReserveTo2__" size="20" __IME_OFF__ class="form">
		</td>
	</tr>

	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">専有部工事終了日(例20131030)</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wReserveFrom2" value="__wReserveFrom2__" size="20" __IME_OFF__ class="form">
		</td>
	</tr>


	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">決定案内提供日(例20131015)</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wKeteiDate" value="__wKeteiDate__" size="20" __IME_OFF__ class="form">
		</td>
	</tr>



	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">午前枠工事終了時刻(例12:00)</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wAendTime" value="__wAendTime__" size="7" __IME_OFF__ class="form">
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">午後１枠終了時刻(例15:00)</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wBendTime" value="__wBendTime__" size="7" __IME_OFF__ class="form">
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">午後２枠終了時刻(例17:30)</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wCendTime" value="__wCendTime__" size="7" __IME_OFF__ class="form">
		</td>
	</tr>

	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">工事終了日まで今日から何日<br>ある？おおめでOK</td>
		<td nowrap class="common-list-value-left">
		__wReserveFromOK__	<input type="text" name="wReserveFrom" value="__wReserveFrom__" size="10" __IME_OFF__ class="form">
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">工事期間開始日の何日前に締切？<br>（例２日なら2）</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wReserveTo" value="__wReserveTo__" size="10" __IME_OFF__ class="form">　　
		
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">取消受け付け終了(N日前まで)</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wCancelTo" value="__wCancelTo__" size="10" __IME_OFF__ class="form">
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">予約確認メール送付</td>
		<td nowrap class="common-list-value-left">
			予約日の　<input type="text" name="wConfirmTiming" value="__wConfirmTiming__" size="10" __IME_OFF__ class="form">　日前
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">工事実施時間</td>
		<td nowrap class="common-list-value-left">
__WeekdayBlock__
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">昼休み</td>
		<td nowrap class="common-list-value-left">
__LunchTimeBlock__
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">夕方休み（基本なし）</td>
		<td nowrap class="common-list-value-left">
__EveningTimeBlock__
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">休日設定</td>
		<td nowrap class="common-list-value-left">
__WeekdayLoop__			<input type="checkbox" name="wHoliday[__Index__]" value="t"__HolidayChecked__>__WeekdayName__&nbsp;&nbsp;
__WeekdayLoop__		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">FREE枠利用</td>
		<td nowrap class="common-list-value-left">
			<input type="checkbox" name="wFreeFlg" value="t"__FreeFlgChecked__>FREE枠を利用する
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">メニュー制限</td>
		<td nowrap class="common-list-value-left">
			<input type="checkbox" name="wMenuByStaffFlg" value="t"__MenuByStaffFlgChecked__>スタッフ毎に設定したメニューを有効にする<br />
			※これを有効化すると、各スタッフは設定されたメニューしか予約を受けられなくなります。
		</td>
	</tr>

	<tr id="blockName">
		<td nowrap class="common-list-title" colspan="2">用語表記</td>
	</tr>
__WordLoop__	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">__WordName__(__DefaultWord__)</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wWord[]" value="__wWord__" size="50" __IME_ON__ class="form">
		</td>
	</tr>
__WordLoop__

	<tr id="blockName">
		<td nowrap class="common-list-title" colspan="2">その他</td>
	</tr>

	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">初回登録者</td>
		<td nowrap class="common-list-value-left">
			__Creator__
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">初回登録日時</td>
		<td nowrap class="common-list-value-left">
			__Created__
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">最終更新者</td>
		<td nowrap class="common-list-value-left">
			__Updater__
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="200">最終更新日時</td>
		<td nowrap class="common-list-value-left">
			__Updated__
		</td>
	</tr>
	<tr id="blockAction">
		<td nowrap class="common-list-value-left" colspan="2">
			
			<input type="button" value="データ更新" class="button" onclick="javascript:moveWithWork('client_setting_end.php', 1)">　
			<input type="reset" value="フォームを元に戻す" class="button">　
			<input type="button" value="もどる" class="button" onclick="javascript:history.back()">
			
		</td>
	</tr>
</table>

__HiddenValues__
</form>

<div class="footer-box">
	__SAdminCopyright__
</div>

</body>
</html>
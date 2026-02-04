<html>
<head>
<title>部屋番号管理 − 詳細情報フォーム</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>

__IfAjax__<link href="css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="js/jquery.ui.core.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
<script src="js/jquery.numberPicker.js" type="text/javascript"></script>

<script type="text/javascript">
$(function() {
	$("#wBirthday").datepicker({defaultDate: '__DEFAULTDATE__'});
	$("#wJoined").datepicker({});
	$("#wWithdrawn").datepicker({});
__IfDate1__		$("#wQuestion1").datepicker({});
__IfDate1____IfDate2__		$("#wQuestion2").datepicker({});
__IfDate2____IfDate3__		$("#wQuestion3").datepicker({});
__IfDate3____IfDate4__		$("#wQuestion4").datepicker({});
__IfDate4____IfDate5__		$("#wQuestion5").datepicker({});
__IfDate5____IfDate6__		$("#wQuestion6").datepicker({});
__IfDate6____IfDate7__		$("#wQuestion7").datepicker({});
__IfDate7____IfDate8__		$("#wQuestion8").datepicker({});
__IfDate8____IfDate9__		$("#wQuestion9").datepicker({});
__IfDate9____IfDate10__		$("#wQuestion10").datepicker({});
__IfDate10__
	$("#wJoinedHour").numberPicker({startNum: 0, endNum: 23, step: 1});
	$("#wJoinedMinute").numberPicker({startNum: 0, endNum: 59, step: 1});
	$("#wJoinedSecond").numberPicker({startNum: 0, endNum: 59, step: 1});
	$("#wWithdrawnHour").numberPicker({startNum: 0, endNum: 23, step: 1});
	$("#wWithdrawnMinute").numberPicker({startNum: 0, endNum: 59, step: 1});
	$("#wWithdrawnSecond").numberPicker({startNum: 0, endNum: 59, step: 1});
});
</script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__</head>

<body>
<a name="Top">

<div class="content">
<h2 class="admin-title">部屋番号管理 − 詳細情報フォーム__ExtraTitle__</h2>
<br>


<h2 class="navigation"><a href="#" onclick="javascript:window.close();">&lt;&lt;&lt; 閉じる</a></h2>


<form method="POST" action="user_list.php" name="mainform">

<table class="common-list" width="700">
	<tr id="blockName">
		<td nowrap class="common-list-title" colspan="4"> __MansionName__ </td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="100">部屋番号コード</td>
		<td nowrap class="common-list-value-left"  >
			__wUserCD__　
		</td>


		<td nowrap class="common-list-title" width="100">−</td>
		<td nowrap class="common-list-value-left"  >
			__Choice8Value__　
		</td>

	</tr>-->

__IfUID__<!--	<tr id="blockName">
		<td nowrap class="common-list-title" width="100">端末識別子</td>
		<td nowrap class="common-list-value-left">
			__UID__
		</td>
	</tr>-->
__IfUID__<!--	<tr id="blockName">
		<td nowrap class="common-list-title" width="100">登録用URL</td>
		<td nowrap class="common-list-value-left">
			__MAIN_URL__form.php?rKey=__RegistKey__
		</td>
	</tr>
	<tr id="blockName">
		<td nowrap class="common-list-title" width="100">トップページURL</td>
		<td nowrap class="common-list-value-left">
			__MAIN_URL__top.php?rKey=__RegistKey__
		</td>
	</tr>-->
__IfPoint__	<tr id="blockName">
		<td nowrap class="common-list-title" width="100">ポイント</td>
		<td nowrap class="common-list-value-left">
			現在　<b>__wPoints__</b>　ポイント<br />
			区分　<select name="wClassification">
__ClassificationLoop__			<option value="__ClassificationValue__">__Classification__
__ClassificationLoop__		</select>　で　
			<input type="text" size="10" name="wPoints" value="">　ポイント加算する<br />
			メモ：<input type="text" size="60" name="wPointNotes" value="">
		</td>
	</tr>
__IfPoint__

__IfTato__
__IfAddress__	<tr id="blockName">
		<td nowrap class="common-list-title" width="100">専有部日程</td>
		<td nowrap class="common-list-value-left">
__IfZipCode__			__ZipCodeName__：
			<input type="text" name="wZipCode" value="__wZipCode__" size="10" maxlength="8" __IME_OFF__ class="form"><br />__IfZipCode__
__IfPrefecture__			__PrefectureName__：
			<select name="wPrefecture">
__PrefectureLoop__			<option value="__PrefectureValue__"__PrefectureSelected__>__Prefecture____PrefectureLoop__
			</select><br />__IfPrefecture__
__IfAddress1__			__Address1Name__：
			<input type="text" name="wAddress1" value="__wAddress1__" size="15" __IME_ON__ class="form"><br />__IfAddress1__
__IfAddress2__			__Address2Name__：
			<input type="text" name="wAddress2" value="__wAddress2__" size="30" __IME_ON__ class="form"><br />__IfAddress2__
__IfAddress3__			__Address3Name__：
			<input type="text" name="wAddress3" value="__wAddress3__" size="30" __IME_ON__ class="form"><br />__IfAddress3__
			<!--住所カナ：
			<input type="text" name="wAddressKana" value="__wAddressKana__" size="50" __IME_ON__ class="form">-->
		</td>
	</tr>__IfAddress__
__IfTato__

__IfID__	<tr id="blockName">
		<td nowrap class="common-list-title" width="100">__IDName__</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wID" value="__wID__" size="15" __IME_OFF__ class="form">
		</td>
	__IfID__
__IfPasswd__	
		<td nowrap class="common-list-title" width="100" >__PasswdName__</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wPasswd" value="__wPasswd__" size="15" __IME_OFF__ class="form">
		</td>
	</tr>__IfPasswd__
__IfName__	<tr id="blockName">
__IfLastName__		<td nowrap class="common-list-title" width="100">__LastNameName__</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wLastName" value="__wLastName__" size="15" __IME_ON__ class="form">
		</td>
	__IfLastName__
__IfFirstName__		<td nowrap class="common-list-title" width="100">__FirstNameName__</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wFirstName" value="__wFirstName__" size="15" __IME_ON__ class="form">
		</td>
	</tr>__IfFirstName__


__IfLastNameKana__	<tr id="blockName">
		<td nowrap class="common-list-title" width="100">__LastNameKanaName__</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wLastNameKana" value="__wLastNameKana__" size="30" __IME_ON__ class="form">
		</td>
	</tr>__IfLastNameKana__
__IfFirstNameKana__	<tr id="blockName">
		<td nowrap class="common-list-title" width="100">__FirstNameKanaName__</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wFirstNameKana" value="__wFirstNameKana__" size="30" __IME_ON__ class="form">
		</td>
	</tr>__IfFirstNameKana__
__IfGender__	<tr id="blockMukouFlg">
		<td nowrap class="common-list-title" width="100">__GenderName__</td>
		<td nowrap class="common-list-value-left">
__GenderLoop__
			<input type="radio" name="wGender" value="__wGenderValue__" __GenderChecked__>__Gender__　
__GenderLoop__
		</td>
	__IfGender__


__IfQuestion1__				
					<td class="common-list-title" id="red">所有/賃貸</td>
					<td class="common-list-value-left">
__IfRadio1____Choice1Loop__					<input type="radio" name="wQuestion1" value="__Choice1Value__"__Choice1Checked__>__Choice1Name__
__Choice1Loop____IfRadio1__
__IfCheckAll1__					<!--<input type="checkbox" name="wCheckAll1" value="t"__CheckAll1Checked__>全選択<BR>-->__IfCheckAll1__
__IfCheckbox1____Choice1Loop__					<input type="checkbox" name="wQuestion1[]" value="__Choice1Value__"__Choice1Checked__>__Choice1Name__<BR>
__Choice1Loop____IfCheckbox1__
__IfSelect1__					<select name="wQuestion1">
__Choice1Loop__					<option value="__Choice1Value__"__Choice1Selected__>__Choice1Name__<BR>
__Choice1Loop__					</select><BR>__IfSelect1__
__IfText1__					<input type="text" size="60" name="wQuestion1" value="__wQuestion1__"><BR>
__IfText1__
__IfTextarea1__					<textarea cos="50" rows="3" name="wQuestion1">__wQuestion1__</textarea><BR>
__IfTextarea1__
__IfDate1____IfAjax__			<input type="text" name="wQuestion1" id="wQuestion1" value="__wQuestion1__" size="14" maxlength="1" __IME_OFF__ class="form">
__IfAjax____IfNoAjax__			<input type="text" name="wQuestion1Year" value="__wQuestion1Year__" size="6" maxlength="4" __IME_OFF__ class="form"> 年 
			<input type="text" name="wQuestion1Month" value="__wQuestion1Month__" size="6" maxlength="2" __IME_OFF__ class="form"> 月 
			<input type="text" name="wQuestion1Day" value="__wQuestion1Day__" size="6" maxlength="2" __IME_OFF__ class="form"> 日
__IfNoAjax____IfDate1__
__IfQuestionEmpty1__					<FONT COLOR="RED">この項目は必須です。</FONT><BR>__IfQuestionEmpty1__
__IfQuestionError1__					<FONT COLOR="RED">この項目の入力が正しくないようです。</FONT><BR>__IfQuestionError1__
					</td>
				</tr>__IfQuestion1__





__IfTEL__	<tr id="blockName">
		<td nowrap class="common-list-title" width="100">__TELName__</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wTEL" value="__wTEL__" size="20" maxlength="11" __IME_OFF__ class="form">※必須
		</td>
	__IfTEL__



		<td nowrap class="common-list-title" width="100">メールアドレス</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wEMail" value="__wEMail__" size="30" __IME_OFF__ class="form">
			<font class="notice"></font>
		</td>
	</tr>
__IfBirthday__	<tr id="blockName">
		<td nowrap class="common-list-title" width="100">__BirthdayName__</td>
		<td nowrap class="common-list-value-left">
__IfAjax__			<input type="text" name="wBirthday" id="wBirthday" value="__wBirthday__" size="14" maxlength="10" __IME_OFF__ class="form">
__IfAjax____IfNoAjax__			<input type="text" name="wBirthdayYear" value="__wBirthdayYear__" size="6" maxlength="4" __IME_OFF__ class="form"> 年 
			<input type="text" name="wBirthdayMonth" value="__wBirthdayMonth__" size="6" maxlength="2" __IME_OFF__ class="form"> 月 
			<input type="text" name="wBirthdayDay" value="__wBirthdayDay__" size="6" maxlength="2" __IME_OFF__ class="form"> 日
__IfNoAjax__		</td>
	</tr>__IfBirthday__





__IfQuestion2__				<tr>
					<td class="common-list-title" id="red">受付<br>（1回目）</td>
					<td class="common-list-value-left">



__IfText2__


					<input type="text" size="60" name="wQuestion2" value="__wQuestion2__"><BR>
__IfText2__





					</td>
				__IfQuestion2__






__IfQuestion3__			
					<td class="common-list-title" id="red">内容（1回目）<br>機器や工事に関する質問、要望やアドバイス、苦情などを入力</td>
					<td class="common-list-value-left">
__IfRadio3____Choice3Loop__					<input type="radio" name="wQuestion3" value="__Choice3Value__"__Choice3Checked__>__Choice3Name__<BR>
__Choice3Loop____IfRadio3__
__IfCheckAll3__					<!--<input type="checkbox" name="wCheckAll3" value="t"__CheckAll3Checked__>全選択<BR>-->__IfCheckAll3__
__IfCheckbox3____Choice3Loop__					<input type="checkbox" name="wQuestion3[]" value="__Choice3Value__"__Choice3Checked__>__Choice3Name__<BR>
__Choice3Loop____IfCheckbox3__
__IfSelect3__					<select name="wQuestion3">
__Choice3Loop__					<option value="__Choice3Value__"__Choice3Selected__>__Choice3Name__<BR>
__Choice3Loop__					</select><BR>__IfSelect3__
__IfText3__					<input type="text" size="60" name="wQuestion3" value="__wQuestion3__"><BR>
__IfText3__
__IfTextarea3__					<textarea cos="60" rows="7" name="wQuestion3">__wQuestion3__</textarea><BR>
__IfTextarea3__
__IfDate3____IfAjax__			<input type="text" name="wQuestion3" id="wQuestion3" value="__wQuestion3__" size="14" maxlength="3" __IME_OFF__ class="form">
__IfAjax____IfNoAjax__			<input type="text" name="wQuestion3Year" value="__wQuestion3Year__" size="6" maxlength="4" __IME_OFF__ class="form"> 年 
			<input type="text" name="wQuestion3Month" value="__wQuestion3Month__" size="6" maxlength="2" __IME_OFF__ class="form"> 月 
			<input type="text" name="wQuestion3Day" value="__wQuestion3Day__" size="6" maxlength="2" __IME_OFF__ class="form"> 日
__IfNoAjax____IfDate3__
__IfQuestionEmpty3__					<FONT COLOR="RED">この項目は必須です。</FONT><BR>__IfQuestionEmpty3__
__IfQuestionError3__					<FONT COLOR="RED">この項目の入力が正しくないようです。</FONT><BR>__IfQuestionError3__
					</td>
				</tr>__IfQuestion3__



__IfQuestion4__				<tr>
					<td class="common-list-title" id="red">受付<br>（2回目）</td>
					<td class="common-list-value-left">
__IfRadio4____Choice4Loop__					<input type="radio" name="wQuestion4" value="__Choice4Value__"__Choice4Checked__>__Choice4Name__<BR>
__Choice4Loop____IfRadio4__
__IfCheckAll4__					<!--<input type="checkbox" name="wCheckAll4" value="t"__CheckAll4Checked__>全選択<BR>-->__IfCheckAll4__
__IfCheckbox4____Choice4Loop__					<input type="checkbox" name="wQuestion4[]" value="__Choice4Value__"__Choice4Checked__>__Choice4Name__<BR>
__Choice4Loop____IfCheckbox4__
__IfSelect4__					<select name="wQuestion4">
__Choice4Loop__					<option value="__Choice4Value__"__Choice4Selected__>__Choice4Name__<BR>
__Choice4Loop__					</select><BR>__IfSelect4__
__IfText4__					<input type="text" size="60" name="wQuestion4" value="__wQuestion4__"><BR>
__IfText4__
__IfTextarea4__					<textarea cos="50" rows="3" name="wQuestion4">__wQuestion4__</textarea><BR>
__IfTextarea4__
__IfDate4____IfAjax__			<input type="text" name="wQuestion4" id="wQuestion4" value="__wQuestion4__" size="14" maxlength="4" __IME_OFF__ class="form">
__IfAjax____IfNoAjax__			<input type="text" name="wQuestion4Year" value="__wQuestion4Year__" size="6" maxlength="4" __IME_OFF__ class="form"> 年 
			<input type="text" name="wQuestion4Month" value="__wQuestion4Month__" size="6" maxlength="2" __IME_OFF__ class="form"> 月 
			<input type="text" name="wQuestion4Day" value="__wQuestion4Day__" size="6" maxlength="2" __IME_OFF__ class="form"> 日
__IfNoAjax____IfDate4__
__IfQuestionEmpty4__					<FONT COLOR="RED">この項目は必須です。</FONT><BR>__IfQuestionEmpty4__
__IfQuestionError4__					<FONT COLOR="RED">この項目の入力が正しくないようです。</FONT><BR>__IfQuestionError4__
					</td>
				__IfQuestion4__

__IfQuestion5__				
					<td class="common-list-title" id="red">内容<br>（2回目）</td>
					<td class="common-list-value-left">
__IfRadio5____Choice5Loop__					<input type="radio" name="wQuestion5" value="__Choice5Value__"__Choice5Checked__>__Choice5Name__<BR>
__Choice5Loop____IfRadio5__
__IfCheckAll5__					<!--<input type="checkbox" name="wCheckAll5" value="t"__CheckAll5Checked__>全選択<BR>-->__IfCheckAll5__
__IfCheckbox5____Choice5Loop__					<input type="checkbox" name="wQuestion5[]" value="__Choice5Value__"__Choice5Checked__>__Choice5Name__<BR>
__Choice5Loop____IfCheckbox5__
__IfSelect5__					<select name="wQuestion5">
__Choice5Loop__					<option value="__Choice5Value__"__Choice5Selected__>__Choice5Name__<BR>
__Choice5Loop__					</select><BR>__IfSelect5__
__IfText5__					<input type="text" size="60" name="wQuestion5" value="__wQuestion5__"><BR>
__IfText5__
__IfTextarea5__					<textarea cos="60" rows="7" name="wQuestion5">__wQuestion5__</textarea><BR>
__IfTextarea5__
__IfDate5____IfAjax__			<input type="text" name="wQuestion5" id="wQuestion5" value="__wQuestion5__" size="14" maxlength="5" __IME_OFF__ class="form">
__IfAjax____IfNoAjax__			<input type="text" name="wQuestion5Year" value="__wQuestion5Year__" size="6" maxlength="4" __IME_OFF__ class="form"> 年 
			<input type="text" name="wQuestion5Month" value="__wQuestion5Month__" size="6" maxlength="2" __IME_OFF__ class="form"> 月 
			<input type="text" name="wQuestion5Day" value="__wQuestion5Day__" size="6" maxlength="2" __IME_OFF__ class="form"> 日
__IfNoAjax____IfDate5__
__IfQuestionEmpty5__					<FONT COLOR="RED">この項目は必須です。</FONT><BR>__IfQuestionEmpty5__
__IfQuestionError5__					<FONT COLOR="RED">この項目の入力が正しくないようです。</FONT><BR>__IfQuestionError5__
					</td>
				</tr>__IfQuestion5__

__IfQuestion6__	


			<tr>
					<td class="common-list-title" id="red">受付日（1回目）</td>
					<td class="common-list-value-left">
				
				




__IfDate6__


  __IfAjax__
			<input type="text" name="wQuestion6" id="wQuestion6" value="__wQuestion6__" size="14" maxlength="6" __IME_OFF__ class="form">
  __IfAjax__



__IfDate6__

	</td>
				
__IfQuestion6__







__IfQuestion7__				
					<td class="common-list-title" id="red">受付日（2回目）</td>
					<td class="common-list-value-left">
__IfRadio7____Choice7Loop__					<input type="radio" name="wQuestion7" value="__Choice7Value__"__Choice7Checked__>__Choice7Name__<BR>
__Choice7Loop____IfRadio7__
__IfCheckAll7__					<!--<input type="checkbox" name="wCheckAll7" value="t"__CheckAll7Checked__>全選択<BR>-->__IfCheckAll7__
__IfCheckbox7____Choice7Loop__					<input type="checkbox" name="wQuestion7[]" value="__Choice7Value__"__Choice7Checked__>__Choice7Name__<BR>
__Choice7Loop____IfCheckbox7__
__IfSelect7__					<select name="wQuestion7">
__Choice7Loop__					<option value="__Choice7Value__"__Choice7Selected__>__Choice7Name__<BR>
__Choice7Loop__					</select><BR>__IfSelect7__
__IfText7__					<input type="text" size="60" name="wQuestion7" value="__wQuestion7__"><BR>
__IfText7__
__IfTextarea7__					<textarea cos="50" rows="3" name="wQuestion7">__wQuestion7__</textarea><BR>
__IfTextarea7__
__IfDate7____IfAjax__			<input type="text" name="wQuestion7" id="wQuestion7" value="__wQuestion7__" size="14" maxlength="7" __IME_OFF__ class="form">
__IfAjax____IfNoAjax__			<input type="text" name="wQuestion7Year" value="__wQuestion7Year__" size="6" maxlength="4" __IME_OFF__ class="form"> 年 
			<input type="text" name="wQuestion7Month" value="__wQuestion7Month__" size="6" maxlength="2" __IME_OFF__ class="form"> 月 
			<input type="text" name="wQuestion7Day" value="__wQuestion7Day__" size="6" maxlength="2" __IME_OFF__ class="form"> 日
__IfNoAjax____IfDate7__
__IfQuestionEmpty7__					<FONT COLOR="RED">この項目は必須です。</FONT><BR>__IfQuestionEmpty7__
__IfQuestionError7__					<FONT COLOR="RED">この項目の入力が正しくないようです。</FONT><BR>__IfQuestionError7__
					</td>
				</tr>__IfQuestion7__

__IfQuestion8__				<tr>
					<td class="common-list-title" id="red">設問8</td>
					<td class="common-list-value-left">__Question8__<br>
__IfRadio8____Choice8Loop__					<input type="radio" name="wQuestion8" value="__Choice8Value__"__Choice8Checked__>__Choice8Name__<BR>
__Choice8Loop____IfRadio8__
__IfCheckAll8__					<!--<input type="checkbox" name="wCheckAll8" value="t"__CheckAll8Checked__>全選択<BR>-->__IfCheckAll8__
__IfCheckbox8____Choice8Loop__					<input type="checkbox" name="wQuestion8[]" value="__Choice8Value__"__Choice8Checked__>__Choice8Name__<BR>
__Choice8Loop____IfCheckbox8__
__IfSelect8__					<select name="wQuestion8">
__Choice8Loop__					<option value="__Choice8Value__"__Choice8Selected__>__Choice8Name__<BR>
__Choice8Loop__					</select><BR>__IfSelect8__
__IfText8__					<input type="text" size="60" name="wQuestion8" value="__wQuestion8__"><BR>
__IfText8__
__IfTextarea8__					<textarea cos="50" rows="3" name="wQuestion8">__wQuestion8__</textarea><BR>
__IfTextarea8__
__IfDate8____IfAjax__			<input type="text" name="wQuestion8" id="wQuestion8" value="__wQuestion8__" size="14" maxlength="8" __IME_OFF__ class="form">
__IfAjax____IfNoAjax__			<input type="text" name="wQuestion8Year" value="__wQuestion8Year__" size="6" maxlength="4" __IME_OFF__ class="form"> 年 
			<input type="text" name="wQuestion8Month" value="__wQuestion8Month__" size="6" maxlength="2" __IME_OFF__ class="form"> 月 
			<input type="text" name="wQuestion8Day" value="__wQuestion8Day__" size="6" maxlength="2" __IME_OFF__ class="form"> 日
__IfNoAjax____IfDate8__
__IfQuestionEmpty8__					<FONT COLOR="RED">この項目は必須です。</FONT><BR>__IfQuestionEmpty8__
__IfQuestionError8__					<FONT COLOR="RED">この項目の入力が正しくないようです。</FONT><BR>__IfQuestionError8__
					</td>
				</tr>__IfQuestion8__

__IfQuestion9__				<tr>
					<td class="common-list-title" id="red">設問9</td>
					<td class="common-list-value-left">__Question9__<br>
__IfRadio9____Choice9Loop__					<input type="radio" name="wQuestion9" value="__Choice9Value__"__Choice9Checked__>__Choice9Name__<BR>
__Choice9Loop____IfRadio9__
__IfCheckAll9__					<!--<input type="checkbox" name="wCheckAll9" value="t"__CheckAll9Checked__>全選択<BR>-->__IfCheckAll9__
__IfCheckbox9____Choice9Loop__					<input type="checkbox" name="wQuestion9[]" value="__Choice9Value__"__Choice9Checked__>__Choice9Name__<BR>
__Choice9Loop____IfCheckbox9__
__IfSelect9__					<select name="wQuestion9">
__Choice9Loop__					<option value="__Choice9Value__"__Choice9Selected__>__Choice9Name__<BR>
__Choice9Loop__					</select><BR>__IfSelect9__
__IfText9__					<input type="text" size="60" name="wQuestion9" value="__wQuestion9__"><BR>
__IfText9__
__IfTextarea9__					<textarea cos="50" rows="3" name="wQuestion9">__wQuestion9__</textarea><BR>
__IfTextarea9__
__IfDate9____IfAjax__			<input type="text" name="wQuestion9" id="wQuestion9" value="__wQuestion9__" size="14" maxlength="9" __IME_OFF__ class="form">
__IfAjax____IfNoAjax__			<input type="text" name="wQuestion9Year" value="__wQuestion9Year__" size="6" maxlength="4" __IME_OFF__ class="form"> 年 
			<input type="text" name="wQuestion9Month" value="__wQuestion9Month__" size="6" maxlength="2" __IME_OFF__ class="form"> 月 
			<input type="text" name="wQuestion9Day" value="__wQuestion9Day__" size="6" maxlength="2" __IME_OFF__ class="form"> 日
__IfNoAjax____IfDate9__
__IfQuestionEmpty9__					<FONT COLOR="RED">この項目は必須です。</FONT><BR>__IfQuestionEmpty9__
__IfQuestionError9__					<FONT COLOR="RED">この項目の入力が正しくないようです。</FONT><BR>__IfQuestionError9__
					</td>
				</tr>__IfQuestion9__

__IfQuestion10__				<tr>
					<td class="common-list-title" id="red">設問10</td>
					<td class="common-list-value-left">__Question10__<br>
__IfRadio10____Choice10Loop__					<input type="radio" name="wQuestion10" value="__Choice10Value__"__Choice10Checked__>__Choice10Name__<BR>
__Choice10Loop____IfRadio10__
__IfCheckAll10__					<!--<input type="checkbox" name="wCheckAll10" value="t"__CheckAll10Checked__>全選択<BR>-->__IfCheckAll10__
__IfCheckbox10____Choice10Loop__					<input type="checkbox" name="wQuestion10[]" value="__Choice10Value__"__Choice10Checked__>__Choice10Name__<BR>
__Choice10Loop____IfCheckbox10__
__IfSelect10__					<select name="wQuestion10">
__Choice10Loop__					<option value="__Choice10Value__"__Choice10Selected__>__Choice10Name__<BR>
__Choice10Loop__					</select><BR>__IfSelect10__
__IfText10__					<input type="text" size="60" name="wQuestion10" value="__wQuestion10__"><BR>
__IfText10__
__IfTextarea10__					<textarea cos="50" rows="3" name="wQuestion10">__wQuestion10__</textarea><BR>
__IfTextarea10__
__IfDate10____IfAjax__			<input type="text" name="wQuestion10" id="wQuestion10" value="__wQuestion10__" size="14" maxlength="10" __IME_OFF__ class="form">
__IfAjax____IfNoAjax__			<input type="text" name="wQuestion10Year" value="__wQuestion10Year__" size="6" maxlength="4" __IME_OFF__ class="form"> 年 
			<input type="text" name="wQuestion10Month" value="__wQuestion10Month__" size="6" maxlength="2" __IME_OFF__ class="form"> 月 
			<input type="text" name="wQuestion10Day" value="__wQuestion10Day__" size="6" maxlength="2" __IME_OFF__ class="form"> 日
__IfNoAjax____IfDate10__
__IfQuestionEmpty10__					<FONT COLOR="RED">この項目は必須です。</FONT><BR>__IfQuestionEmpty10__
__IfQuestionError10__					<FONT COLOR="RED">この項目の入力が正しくないようです。</FONT><BR>__IfQuestionError10__
					</td>
				</tr>__IfQuestion10__

<!--	<tr id="blockName">
		<td nowrap class="common-list-title" width="100">ポイント</td>
		<td nowrap class="common-list-value-left">
			<input type="text" name="wPoints" value="__wPoints__" size="10" __IME_OFF__ class="form">ポイント
		</td>
	</tr>
	<tr id="blockMukouFlg">
		<td nowrap class="common-list-title" width="100">メルマガ購読</td>
		<td nowrap class="common-list-value-left">
__MailMagaFlgLoop__
			<input type="radio" name="wMailMagaFlg" value="__wMailMagaFlgValue__" __MailMagaFlgChecked__>__MailMagaFlg__　
__MailMagaFlgLoop__
		</td>
	</tr>-->

	<tr id="blockAction">
		<td nowrap class="common-list-value-left" colspan="2">
			
			<input type="button" value="データ更新" class="button" onclick="javascript:moveWithWork('user_list.php', 1)">　
			<input type="reset" value="フォームを元に戻す" class="button">　
			<input type="button" value="もどる" class="button" onclick="javascript:history.back()">
			
		</td>
	</tr>
</table>


__IfReserveSystem__
<a name="ReserveList">
<a href="javascript:void(0);" onclick="javascript:toggle('Reserve', 0);">__ReservationName__履歴表示ON/OFF</a>　　　
<a href="#Top">画面上部へ</a><br />
__IfDemo__<font color="red"><b>こちらはオンライン予約システムをご導入の場合に表示される部分です。</b></font>
__IfDemo__<table class="common-list" width="700" id="Reserve">
	<tr>
		<td nowrap class="common-list-title">__ReservationName__コード</td>
		<td nowrap class="common-list-title">日付</td>
		<td nowrap class="common-list-title">__ReservationName__時間</td>
		<td nowrap class="common-list-title">担当__Staff__</td>
		<td nowrap class="common-list-title">__Designation__</td>
		<td nowrap class="common-list-title">__Menu__</td>
		<td nowrap class="common-list-title">詳細/編集</td>
		<td nowrap class="common-list-title">削除</td>
	</tr>

__IfReserveList____ReservationLoop__	<tr class="common-list">
		<td nowrap class="common-list-value">__ReservationCD__</td>
		<td nowrap class="common-list-value">__ReserveDate__</td>
		<td nowrap class="common-list-value">__TimeFrom__〜__TimeTo__</td>
		<td nowrap class="common-list-value">__StylistName__</td>
		<td nowrap class="common-list-value">__FreeFlg__</td>
		<td nowrap class="common-list-value">__MenuName__</td>
		<td nowrap class="common-list-value"><a href="#" onclick="javascript:moveWithURLKey('url_detail.php', __URLCD__);">GO!</a></td>
		<td nowrap class="common-list-value"><a href="#" onclick="javascript:moveWithURLKeyAlert('url_list.php', __URLCD__, 2);">GO!</a></td>
	</tr>
__ReservationLoop____IfReserveList____IfNoReserveList__
	<tr>
		<td nowrap class="common-list-value" colspan="12"><font color="red">当部屋番号の__ReservationName__履歴はありません。</font></td>
	</tr>
__IfNoReserveList__</table>
__IfReserveSystem__


__IfCoupon__
<a name="CouponLogList">
<a href="javascript:void(0);" onclick="javascript:toggle('CouponLog', 0);">クーポン利用履歴表示ON/OFF</a>　　　
<a href="#Top">画面上部へ</a><br />
__IfDemo__<font color="red"><b>こちらはオンラインクーポンシステムをご導入の場合に表示される部分です。</b></font>
__IfDemo__<table class="common-list" width="700" id="CouponLog">
	<tr>
		<td nowrap class="common-list-title">履歴コード</td>
		<td nowrap class="common-list-title">利用クーポン</td>
		<td nowrap class="common-list-title">利用日時</td>
		<td nowrap class="common-list-title">キャリア</td>
	</tr>

__IfCouponLogList____CouponLogLoop__	<tr class="common-list">
		<td nowrap class="common-list-value">__LogCD__</td>
		<td nowrap class="common-list-value">__CouponTitle__</td>
		<td nowrap class="common-list-value">__Used__</td>
		<td nowrap class="common-list-value">__Carrier__</td>
	</tr>
__CouponLogLoop____IfCouponLogList____IfNoCouponLogList__
	<tr>
		<td nowrap class="common-list-value" colspan="12"><font color="red">当部屋番号のクーポン利用履歴はありません。</font></td>
	</tr>
__IfNoCouponLogList__</table>
__IfCoupon__

__HiddenValues__
</form>

<div class="footer-box">
	__SAdminCopyright__
</div>

</body>
</html>
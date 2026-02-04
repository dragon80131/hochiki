<html>
<head>
<title>連絡先登録</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《連絡先登録》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>
<table align="center"><tr><td align="left">

__IfReservation__

お客様の工事予定は以下の日程となっております。<br><br>
__ReservationLoop__<b><H3>__ReservationDate__</H3></b>
<br>
__ReservationLoop__

__IfReservation__
<br>お客様の連絡先情報を登録お願いします。<br>





<hr size="__HRSize__" color="__HRColor__">

<table align="center"><tr><td align="left">

__IfError__<font color="RED">__ErrorMessage__</font><br>__IfError__

<form method="POST" action="confirm.php">
__IfID__■__IDName__:__wID____IfIDRequired____IfIDRequired__<br>
<input type="hidden" name="wID" value="__wID__" istyle="3"><br>
__IfIDEmpty__<font color="RED">__IDName__の入力は必須です。</font><br>__IfIDEmpty__
__IfIDError__<font color="RED">__IDName__の入力は半角英数字2桁〜12桁でおねがいします。</font><br>__IfIDError__
__IfIDUsed__<font color="RED">ID__IDName__は、既に他の方が登録しています。</font><br>__IfIDUsed__
__IfID__

__IfPasswd____IfPasswdRequired____IfPasswdRequired__
<input type="hidden" name="wPasswd" value="__wPasswd__" istyle="3">
__IfPasswdEmpty__<font color="RED">__PasswdName__の入力は必須です。</font>__IfPasswdEmpty__
__IfPasswdError__<font color="RED">__PasswdName__の入力は半角英数字4桁〜12桁でおねがいします。</font><br>__IfPasswdError__

__IfPasswd__



__IfLastName__■__LastNameName__:__IfLastNameRequired____IfLastNameRequired__<font color="RED">（必須）</font><br>
<input type="text" name="wLastName" value="__wLastName__" istyle="1"><br>
__IfLastNameEmpty__<font color="RED">__LastNameName__の入力は必須です。</font><br>__IfLastNameEmpty__
__IfLastNameError__<font color="RED">__LastNameName__は全角8文字以内で入力してください。</font><br>__IfLastNameError__
__IfLastName__

__IfFirstName__■__FirstNameName__:__IfFirstNameRequired____IfFirstNameRequired__<br>
<input type="text" name="wFirstName" value="__wFirstName__" istyle="1"><br>
__IfFirstNameEmpty__<font color="RED">__FirstNameName__の入力は必須です。</font><br>__IfFirstNameEmpty__
__IfFirstNameError__<font color="RED">__FirstNameName__は全角8文字以内で入力してください。</font><br>__IfFirstNameError__
<br>
__IfFirstName__

__IfLastNameKana__■__LastNameKanaName__:__IfLastNameKanaRequired__◎__IfLastNameKanaRequired__<br>
<input type="text" name="wLastNameKana" value="__wLastNameKana__" istyle="1"><br>
__IfLastNameKanaEmpty__<font color="RED">__LastNameKanaName__の入力は必須です。</font><br>__IfLastNameKanaEmpty__
__IfLastNameKanaError__<font color="RED">__LastNameKanaName__は全角10文字以内で入力してください。</font><br>__IfLastNameKanaError__
__IfLastNameKana__

__IfFirstNameKana__■__FirstNameKanaName__:__IfFirstNameKanaRequired__◎__IfFirstNameKanaRequired__<br>
<input type="text" name="wFirstNameKana" value="__wFirstNameKana__" istyle="1"><br>
__IfFirstNameKanaEmpty__<font color="RED">__FirstNameKanaName__の入力は必須です。</font><br>__IfFirstNameKanaEmpty__
__IfFirstNameKanaError__<font color="RED">__FirstNameKanaName__は全角10文字以内で入力してください。</font><br>__IfFirstNameKanaError__
<br>
__IfFirstNameKana__

__IfBirthday__■__BirthdayName__:__IfBirthdayRequired__◎__IfBirthdayRequired__<br>
<input type="text" name="wBirthday" value="__wBirthday__" size="8" maxlength="8" istyle="4"><br>
<font color="GRAY">※西暦で入力して下さい。（例:19720720）</font><br>
__IfBirthdayEmpty__<font color="RED">__BirthdayName__の入力は必須です。</font><br>__IfBirthdayEmpty__
__IfBirthdayError__<font color="RED">__BirthdayName__の入力が正しくないようです。</font><br>__IfBirthdayError__
<br>
__IfBirthday__

__IfGender__■__GenderName__:__IfGenderRequired__◎__IfGenderRequired__<br>
__GenderLoop__<input type="radio" name="wGender" value="__GenderValue__"__GenderChecked__>__Gender____GenderLoop__<br>
__IfGenderEmpty__<font color="RED">__GenderName__の入力は必須です。</font><br>__IfGenderEmpty__
<br>
__IfGender__

__IfZipCode__■__ZipCodeName__:__IfZipCodeRequired__◎__IfZipCodeRequired__<br>
<input type="text" name="wZipCode" value="__wZipCode__" size="7" maxlength="7" istyle="4"><br>
__IfZipCodeEmpty__<font color="RED">__ZipCodeName__の入力は必須です。</font><br>__IfZipCodeEmpty__
__IfZipCodeError__<font color="RED">__ZipCodeName__の入力が正しくないようです。</font><br>__IfZipCodeError__
<font color="GRAY">※ハイフン不要</font><br>
<br>
__IfZipCode__

__IfPrefecture__■__PrefectureName__:__IfPrefectureRequired__◎__IfPrefectureRequired__<br>
<select name="wPrefecture">
__PrefectureLoop__<option value="__PrefectureValue__"__PrefectureSelected__>__Prefecture____PrefectureLoop__
</select><br>
<br>
__IfPrefecture__

__IfAddress1__■__Address1Name__:__IfAddress1Required__◎__IfAddress1Required__<br>
<input type="text" name="wAddress1" value="__wAddress1__" istyle="1"><br>
__IfAddress1Empty__<font color="RED">__Address1Name__の入力は必須です。</font><br>__IfAddress1Empty__
__IfAddress1__

__IfAddress2__■__Address2Name__:__IfAddress2Required__◎__IfAddress2Required__<br>
<input type="text" name="wAddress2" value="__wAddress2__" istyle="1"><br>
__IfAddress2Empty__<font color="RED">__Address2Name__の入力は必須です。</font><br>__IfAddress2Empty__
__IfAddress2__


__IfTEL__■連絡先電話番号(ハイフンなし):__IfTELRequired____IfTELRequired__<font color="RED">（必須）</font><br>
<input type="text" name="wTEL" value="__wTEL__" istyle="4" style="ime-mode:disabled;"><br>
__IfTELEmpty__<font color="RED">__TELName__の入力は必須です。</font><br>__IfTELEmpty__
__IfTELError__<font color="RED">__TELName__の入力がおかしいようです。</font><br>__IfTELError__
<br>
__IfTEL__

<!--
__IfAddress3__■__Address3Name__:__IfAddress3Required____IfAddress3Required__<br>
<input type="hidden" name="wAddress3" value="__wAddress3__" istyle="1"><br>
__IfAddress3Empty__<font color="RED">__Address3Name__の入力は必須です。</font><br>__IfAddress3Empty__
<br>
__IfAddress3__
-->
<input type="hidden" name="wAddress3" value="__wAddress3__" istyle="1"><br>



__IfQuestion1__■__Question1__:__IfQuestion1Required__◎__IfQuestion1Required__<font color="RED">（必須）</font><br>
__IfRadio1____Choice1Loop__<input type="radio" name="wExtra1" value="__Choice1Value__"__Choice1Checked__>__Choice1Name__<br>
__Choice1Loop____IfRadio1__
__IfCheckAll1__<input type="checkbox" name="wCheckAll1" value="t"__CheckAll1Checked__>全選択<br>__IfCheckAll1__
__IfCheckbox1____Choice1Loop__<input type="checkbox" name="wExtra1[]" value="__Choice1Value__"__Choice1Checked__>__Choice1Name__<br>
__Choice1Loop____IfCheckbox1__
__IfSelect1__<select name="wExtra1">
__Choice1Loop__<option value="__Choice1Value__"__Choice1Selected__>__Choice1Name__<br>
__Choice1Loop__</select><br>__IfSelect1__
__IfText1__<input type="text" name="wExtra1" size="__Size1__" value="__wExtra1__"__TextMode1__><br>
__IfText1__
__IfTextarea1__<textarea name="wExtra1" cols="__Cols1__" rows="__Rows1__"__TextMode1__>__wExtra1__</textarea><br>
__IfTextarea1__
__IfDate1__<input type="text" name="wExtra1" size="__Size1__" value="__wExtra1__" maxlength="8"__TextMode1__><br>
※西暦で入力して下さい。（例:19720720）<br>
__IfDate1__
__IfQuestionEmpty1__<font color="RED">この項目は必須です。</font><br>__IfQuestionEmpty1__
__IfQuestionError1__<font color="RED">この項目の入力が正しくないようです。</font><br>__IfQuestionError1__
__IfQuestionWrong1__<font color="RED">利用できない文字が含まれています。</font><br>__IfQuestionWrong1__
<br>__IfQuestion1__

__IfQuestion2__■__Question2__:__IfQuestion2Required__◎__IfQuestion2Required__<br>
__IfRadio2____Choice2Loop__<input type="radio" name="wExtra2" value="__Choice2Value__"__Choice2Checked__>__Choice2Name__<br>
__Choice2Loop____IfRadio2__
__IfCheckAll2__<input type="checkbox" name="wCheckAll2" value="t"__CheckAll2Checked__>全選択<br>__IfCheckAll2__
__IfCheckbox2____Choice2Loop__<input type="checkbox" name="wExtra2[]" value="__Choice2Value__"__Choice2Checked__>__Choice2Name__<br>
__Choice2Loop____IfCheckbox2__
__IfSelect2__<select name="wExtra2">
__Choice2Loop__<option value="__Choice2Value__"__Choice2Selected__>__Choice2Name__<br>
__Choice2Loop__</select><br>__IfSelect2__
__IfText2__<input type="text" name="wExtra2" size="__Size2__" value="__wExtra2__"__TextMode2__><br>
__IfText2__
__IfTextarea2__<textarea name="wExtra2" cols="__Cols2__" rows="__Rows2__"__TextMode2__>__wExtra2__</textarea><br>
__IfTextarea2__
__IfDate2__<input type="text" name="wExtra2" size="__Size2__" value="__wExtra2__" maxlength="8"__TextMode2__><br>
※西暦で入力して下さい。（例:19720720）<br>
__IfDate2__
__IfQuestionEmpty2__<font color="RED">この項目は必須です。</font><br>__IfQuestionEmpty2__
__IfQuestionError2__<font color="RED">この項目の入力が正しくないようです。</font><br>__IfQuestionError2__
__IfQuestionWrong2__<font color="RED">利用できない文字が含まれています。</font><br>__IfQuestionWrong2__
<br>__IfQuestion2__

__IfQuestion3__■__Question3__:__IfQuestion3Required__◎__IfQuestion3Required__<br>
__IfRadio3____Choice3Loop__<input type="radio" name="wExtra3" value="__Choice3Value__"__Choice3Checked__>__Choice3Name__<br>
__Choice3Loop____IfRadio3__
__IfCheckAll3__<input type="checkbox" name="wCheckAll3" value="t"__CheckAll3Checked__>全選択<br>__IfCheckAll3__
__IfCheckbox3____Choice3Loop__<input type="checkbox" name="wExtra3[]" value="__Choice3Value__"__Choice3Checked__>__Choice3Name__<br>
__Choice3Loop____IfCheckbox3__
__IfSelect3__<select name="wExtra3">
__Choice3Loop__<option value="__Choice3Value__"__Choice3Selected__>__Choice3Name__<br>
__Choice3Loop__</select><br>__IfSelect3__
__IfText3__<input type="text" name="wExtra3" size="__Size3__" value="__wExtra3__"__TextMode3__><br>
__IfText3__
__IfTextarea3__<textarea name="wExtra3" cols="__Cols3__" rows="__Rows3__"__TextMode3__>__wExtra3__</textarea><br>
__IfTextarea3__
__IfDate3__<input type="text" name="wExtra3" size="__Size3__" value="__wExtra3__" maxlength="8"__TextMode3__><br>
※西暦で入力して下さい。（例:19720720）<br>
__IfDate3__
__IfQuestionEmpty3__<font color="RED">この項目は必須です。</font><br>__IfQuestionEmpty3__
__IfQuestionError3__<font color="RED">この項目の入力が正しくないようです。</font><br>__IfQuestionError3__
__IfQuestionWrong3__<font color="RED">利用できない文字が含まれています。</font><br>__IfQuestionWrong3__
<br>__IfQuestion3__

__IfQuestion4__■__Question4__:__IfQuestion4Required__◎__IfQuestion4Required__<br>
__IfRadio4____Choice4Loop__<input type="radio" name="wExtra4" value="__Choice4Value__"__Choice4Checked__>__Choice4Name__<br>
__Choice4Loop____IfRadio4__
__IfCheckAll4__<input type="checkbox" name="wCheckAll4" value="t"__CheckAll4Checked__>全選択<br>__IfCheckAll4__
__IfCheckbox4____Choice4Loop__<input type="checkbox" name="wExtra4[]" value="__Choice4Value__"__Choice4Checked__>__Choice4Name__<br>
__Choice4Loop____IfCheckbox4__
__IfSelect4__<select name="wExtra4">
__Choice4Loop__<option value="__Choice4Value__"__Choice4Selected__>__Choice4Name__<br>
__Choice4Loop__</select><br>__IfSelect4__
__IfText4__<input type="text" name="wExtra4" size="__Size4__" value="__wExtra4__"__TextMode4__><br>
__IfText4__
__IfTextarea4__<textarea name="wExtra4" cols="__Cols4__" rows="__Rows4__"__TextMode4__>__wExtra4__</textarea><br>
__IfTextarea4__
__IfDate4__<input type="text" name="wExtra4" size="__Size4__" value="__wExtra4__" maxlength="8"__TextMode4__><br>
※西暦で入力して下さい。（例:19720720）<br>
__IfDate4__
__IfQuestionEmpty4__<font color="RED">この項目は必須です。</font><br>__IfQuestionEmpty4__
__IfQuestionError4__<font color="RED">この項目の入力が正しくないようです。</font><br>__IfQuestionError4__
__IfQuestionWrong4__<font color="RED">利用できない文字が含まれています。</font><br>__IfQuestionWrong4__
<br>__IfQuestion4__

__IfQuestion5__■__Question5__:__IfQuestion5Required__◎__IfQuestion5Required__<br>
__IfRadio5____Choice5Loop__<input type="radio" name="wExtra5" value="__Choice5Value__"__Choice5Checked__>__Choice5Name__<br>
__Choice5Loop____IfRadio5__
__IfCheckAll5__<input type="checkbox" name="wCheckAll5" value="t"__CheckAll5Checked__>全選択<br>__IfCheckAll5__
__IfCheckbox5____Choice5Loop__<input type="checkbox" name="wExtra5[]" value="__Choice5Value__"__Choice5Checked__>__Choice5Name__<br>
__Choice5Loop____IfCheckbox5__
__IfSelect5__<select name="wExtra5">
__Choice5Loop__<option value="__Choice5Value__"__Choice5Selected__>__Choice5Name__<br>
__Choice5Loop__</select><br>__IfSelect5__
__IfText5__<input type="text" name="wExtra5" size="__Size5__" value="__wExtra5__"__TextMode5__><br>
__IfText5__
__IfTextarea5__<textarea name="wExtra5" cols="__Cols5__" rows="__Rows5__"__TextMode5__>__wExtra5__</textarea><br>
__IfTextarea5__
__IfDate5__<input type="text" name="wExtra5" size="__Size5__" value="__wExtra5__" maxlength="8"__TextMode5__><br>
※西暦で入力して下さい。（例:19720720）<br>
__IfDate5__
__IfQuestionEmpty5__<font color="RED">この項目は必須です。</font><br>__IfQuestionEmpty5__
__IfQuestionError5__<font color="RED">この項目の入力が正しくないようです。</font><br>__IfQuestionError5__
__IfQuestionWrong5__<font color="RED">利用できない文字が含まれています。</font><br>__IfQuestionWrong5__
<br>__IfQuestion5__

__IfQuestion6__■__Question6__:__IfQuestion6Required__◎__IfQuestion6Required__<br>
__IfRadio6____Choice6Loop__<input type="radio" name="wExtra6" value="__Choice6Value__"__Choice6Checked__>__Choice6Name__<br>
__Choice6Loop____IfRadio6__
__IfCheckAll6__<input type="checkbox" name="wCheckAll6" value="t"__CheckAll6Checked__>全選択<br>__IfCheckAll6__
__IfCheckbox6____Choice6Loop__<input type="checkbox" name="wExtra6[]" value="__Choice6Value__"__Choice6Checked__>__Choice6Name__<br>
__Choice6Loop____IfCheckbox6__
__IfSelect6__<select name="wExtra6">
__Choice6Loop__<option value="__Choice6Value__"__Choice6Selected__>__Choice6Name__<br>
__Choice6Loop__</select><br>__IfSelect6__
__IfText6__<input type="text" name="wExtra6" size="__Size6__" value="__wExtra6__"__TextMode6__><br>
__IfText6__
__IfTextarea6__<textarea name="wExtra6" cols="__Cols6__" rows="__Rows6__"__TextMode6__>__wExtra6__</textarea><br>
__IfTextarea6__
__IfDate6__<input type="text" name="wExtra6" size="__Size6__" value="__wExtra6__" maxlength="8"__TextMode6__><br>
※西暦で入力して下さい。（例:19720720）<br>
__IfDate6__
__IfQuestionEmpty6__<font color="RED">この項目は必須です。</font><br>__IfQuestionEmpty6__
__IfQuestionError6__<font color="RED">この項目の入力が正しくないようです。</font><br>__IfQuestionError6__
__IfQuestionWrong6__<font color="RED">利用できない文字が含まれています。</font><br>__IfQuestionWrong6__
<br>__IfQuestion6__

__IfQuestion7__■__Question7__:__IfQuestion7Required__◎__IfQuestion7Required__<br>
__IfRadio7____Choice7Loop__<input type="radio" name="wExtra7" value="__Choice7Value__"__Choice7Checked__>__Choice7Name__<br>
__Choice7Loop____IfRadio7__
__IfCheckAll7__<input type="checkbox" name="wCheckAll7" value="t"__CheckAll7Checked__>全選択<br>__IfCheckAll7__
__IfCheckbox7____Choice7Loop__<input type="checkbox" name="wExtra7[]" value="__Choice7Value__"__Choice7Checked__>__Choice7Name__<br>
__Choice7Loop____IfCheckbox7__
__IfSelect7__<select name="wExtra7">
__Choice7Loop__<option value="__Choice7Value__"__Choice7Selected__>__Choice7Name__<br>
__Choice7Loop__</select><br>__IfSelect7__
__IfText7__<input type="text" name="wExtra7" size="__Size7__" value="__wExtra7__"__TextMode7__><br>
__IfText7__
__IfTextarea7__<textarea name="wExtra7" cols="__Cols7__" rows="__Rows7__"__TextMode7__>__wExtra7__</textarea><br>
__IfTextarea7__
__IfDate7__<input type="text" name="wExtra7" size="__Size7__" value="__wExtra7__" maxlength="8"__TextMode7__><br>
※西暦で入力して下さい。（例:19720720）<br>
__IfDate7__
__IfQuestionEmpty7__<font color="RED">この項目は必須です。</font><br>__IfQuestionEmpty7__
__IfQuestionError7__<font color="RED">この項目の入力が正しくないようです。</font><br>__IfQuestionError7__
__IfQuestionWrong7__<font color="RED">利用できない文字が含まれています。</font><br>__IfQuestionWrong7__
<br>__IfQuestion7__

__IfQuestion8__■__Question8__:__IfQuestion8Required__◎__IfQuestion8Required__<br>
__IfRadio8____Choice8Loop__<input type="radio" name="wExtra8" value="__Choice8Value__"__Choice8Checked__>__Choice8Name__<br>
__Choice8Loop____IfRadio8__
__IfCheckAll8__<input type="checkbox" name="wCheckAll8" value="t"__CheckAll8Checked__>全選択<br>__IfCheckAll8__
__IfCheckbox8____Choice8Loop__<input type="checkbox" name="wExtra8[]" value="__Choice8Value__"__Choice8Checked__>__Choice8Name__<br>
__Choice8Loop____IfCheckbox8__
__IfSelect8__<select name="wExtra8">
__Choice8Loop__<option value="__Choice8Value__"__Choice8Selected__>__Choice8Name__<br>
__Choice8Loop__</select><br>__IfSelect8__
__IfText8__<input type="text" name="wExtra8" size="__Size8__" value="__wExtra8__"__TextMode8__><br>
__IfText8__
__IfTextarea8__<textarea name="wExtra8" cols="__Cols8__" rows="__Rows8__"__TextMode8__>__wExtra8__</textarea><br>
__IfTextarea8__
__IfDate8__<input type="text" name="wExtra8" size="__Size8__" value="__wExtra8__" maxlength="8"__TextMode8__><br>
※西暦で入力して下さい。（例:19720720）<br>
__IfDate8__
__IfQuestionEmpty8__<font color="RED">この項目は必須です。</font><br>__IfQuestionEmpty8__
__IfQuestionError8__<font color="RED">この項目の入力が正しくないようです。</font><br>__IfQuestionError8__
__IfQuestionWrong8__<font color="RED">利用できない文字が含まれています。</font><br>__IfQuestionWrong8__
<br>__IfQuestion8__

__IfQuestion9__■__Question9__:__IfQuestion9Required__◎__IfQuestion9Required__<br>
__IfRadio9____Choice9Loop__<input type="radio" name="wExtra9" value="__Choice9Value__"__Choice9Checked__>__Choice9Name__<br>
__Choice9Loop____IfRadio9__
__IfCheckAll9__<input type="checkbox" name="wCheckAll9" value="t"__CheckAll9Checked__>全選択<br>__IfCheckAll9__
__IfCheckbox9____Choice9Loop__<input type="checkbox" name="wExtra9[]" value="__Choice9Value__"__Choice9Checked__>__Choice9Name__<br>
__Choice9Loop____IfCheckbox9__
__IfSelect9__<select name="wExtra9">
__Choice9Loop__<option value="__Choice9Value__"__Choice9Selected__>__Choice9Name__<br>
__Choice9Loop__</select><br>__IfSelect9__
__IfText9__<input type="text" name="wExtra9" size="__Size9__" value="__wExtra9__"__TextMode9__><br>
__IfText9__
__IfTextarea9__<textarea name="wExtra9" cols="__Cols9__" rows="__Rows9__"__TextMode9__>__wExtra9__</textarea><br>
__IfTextarea9__
__IfDate9__<input type="text" name="wExtra9" size="__Size9__" value="__wExtra9__" maxlength="8"__TextMode9__><br>
※西暦で入力して下さい。（例:19720720）<br>
__IfDate9__
__IfQuestionEmpty9__<font color="RED">この項目は必須です。</font><br>__IfQuestionEmpty9__
__IfQuestionError9__<font color="RED">この項目の入力が正しくないようです。</font><br>__IfQuestionError9__
__IfQuestionWrong9__<font color="RED">利用できない文字が含まれています。</font><br>__IfQuestionWrong9__
<br>__IfQuestion9__


__IfEMail__■__EMailName__:__IfEMailRequired____IfEMailRequired__<br>
※登録時、工事前日にメールが届きます。<br>
<input type="text" name="wEMail" value="__wEMail__" istyle="3" style="ime-mode:disabled;"><br>
__IfEMailEmpty__<font color="RED">__EMailName__の入力は必須です。</font><br>__IfEMailEmpty__
__IfEMailError__<font color="RED">__EMailName__の入力形式がおかしいようです。</font><br>__IfEMailError__
__IfEMailUsed__<font color="RED">ご希望の__EMailName__は既に他の方に利用されています。</font><br>__IfEMailUsed__
<br>
__IfEMail__

__IfQuestion10__■__Question10__:__IfQuestion10Required__◎__IfQuestion10Required__<br>
__IfRadio10____Choice10Loop__<input type="radio" name="wExtra10" value="__Choice10Value__"__Choice10Checked__>__Choice10Name__<br>
__Choice10Loop____IfRadio10__
__IfCheckAll10__<input type="checkbox" name="wCheckAll10" value="t"__CheckAll10Checked__>全選択<br>__IfCheckAll10__
__IfCheckbox10____Choice10Loop__<input type="checkbox" name="wExtra10[]" value="__Choice10Value__"__Choice10Checked__>__Choice10Name__<br>
__Choice10Loop____IfCheckbox10__
__IfSelect10__<select name="wExtra10">
__Choice10Loop__<option value="__Choice10Value__"__Choice10Selected__>__Choice10Name__<br>
__Choice10Loop__</select><br>__IfSelect10__
__IfText10__<input type="text" name="wExtra10" size="__Size10__" value="__wExtra10__"__TextMode10__><br>
__IfText10__
__IfTextarea10__<textarea name="wExtra10" cols="__Cols10__" rows="__Rows10__"__TextMode10__>__wExtra10__</textarea><br>
__IfTextarea10__
__IfDate10__<input type="text" name="wExtra10" size="__Size10__" value="__wExtra10__" maxlength="8"__TextMode10__><br>
※西暦で入力して下さい。（例:19720720）<br>
__IfDate10__
__IfQuestionEmpty10__<font color="RED">この項目は必須です。</font><br>__IfQuestionEmpty10__
__IfQuestionError10__<font color="RED">この項目の入力が正しくないようです。</font><br>__IfQuestionError10__
__IfQuestionWrong10__<font color="RED">利用できない文字が含まれています。</font><br>__IfQuestionWrong10__
<br>__IfQuestion10__

<br>
__HiddenValues__
__COMMON_POST_QUERY__
<center><input type="submit" value="次へ"></center>
</form>
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>

</td></tr></table>
</td></tr></table>

</body>
</head>
</html>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/common.css">
	<link rel="stylesheet" type="text/css" href="css/style_okyakusama.css">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<!--最小限のビューポート設定-->
	<meta name="viewport" content="width=device-width">
	<meta name="format-detection" content="telephone=no">

	<title>ReservatinSystem</title>
</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
		<div id="navi">
			<!--<a class="gengo" href="#.php">Englishi</a>-->
			<a class="bar" href="top.php__QUERY__">__finish3__
				<!--予約TOP-->
			</a>
			<a class="bar-migi" href="logout.php__QUERY__">__logout1__
				<!--ログアウト-->
			</a>
		</div>

		__SHeader__

		<h1>__MansionName__ __wBuildingName__</h1>
		<h2>__top1__ __wID__</h2>


		<h3>__confirm9__<!--登録内容のご確認--></h3>


		__IfNoop__
		<!--▽STEP-日程予約の場合の表示-->
		<div id="step4">
			<img src="./images/__steppng__" alt="step">
		</div>
		<!--△STEP-日程予約の場合の表示-->
		__IfNoop__
		<p>
		__confirm10__
		</p>
		<form method="POST" action="finish.php__QUERY__&wLang=__wLang__">
		<table class="formwaku">
				<input type="hidden" name="btnflg" value="__btnflg__" istyle="3">
				<input type="hidden" name="work" value="1">
				<tr>
					<th scope="row">__confirm1__
						<!--氏名-->
					</th>
					<td>__IfLastName____wLastName____IfNotLastName__未記入__IfNotLastName____IfLastName__</td>
				</tr>

				<tr>
					<th scope="row">__confirm2__
						<!--電話番号-->
					</th>
					<td>__IfTEL____wTEL____IfNotTEL__未記入__IfNotTEL____IfTEL__</td>
				</tr>

				__IfHearing__
				<tr>
					<th scope="row" rowspan="__HearingCount__">__form22__<font color="red"> *</font>
					</th>
				</tr>
				__HearingNameLoop__
				__IfSelectHearing__
				__HearingDisp__
				__IfSelectHearing__
				__HearingNameLoop__
				__IfHearing__
				<tr>
					<th scope="row">__form13__
						<!--メールアドレス-->
					</th>
					<td>__wEMail__</td>
				</tr>
				<tr>
					<th scope="row">変更後パスワード
						<!--メールアドレス-->
					</th>
					<td>__wPasswd__</td>
				</tr>
				<tr>
					<!--					<th scope="row">__form23__備考・特記事項</th>
					<td>__wFreeMemo__</td>
				</tr>
				<tr>-->
					<!--個人情報できてない-->
					<th scope="row">__form15__
						<!--個人情報の取り扱いについて-->
					</th>
					<td>__Kojin__</td>
				</tr>
		</table>


		<!--<form method="POST" action="finish.php">-->
		<!--__IfID__■__IDName__:<br>
__wID____IfNotID__未記入__IfNotID__<br>__IfID__

<br>
__IfLastName__■お名前:<br>
__wLastName____IfNotLastName__未記入__IfNotLastName__　__IfLastName__

__IfFirstName__
__wFirstName____IfNotFirstName__未記入__IfNotFirstName__<br><br>__IfFirstName__

__IfLastNameKana__■__LastNameKanaName__:<br>
__wLastNameKana____IfNotLastNameKana____IfNotLastNameKana__<br>__IfLastNameKana__

__IfFirstNameKana__■__FirstNameKanaName__:<br>
__wFirstNameKana____IfNotFirstNameKana__未記入__IfNotFirstNameKana__<br><br>__IfFirstNameKana__

__IfBirthday__■__BirthdayName__:<br>
__mBirthday____IfNotBirthday__未記入__IfNotBirthday__<br>
<br>__IfBirthday__
<br><br>
■__GenderName__:<br>
__mGender__<br>
<br>__IfGender__

__IfZipCode__■__ZipCodeName__:<br>
__wZipCode____IfNotZipCode__未記入__IfNotZipCode__<br>
<br>__IfZipCode__

__IfPrefecture__■__PrefectureName__:<br>
__mPrefecture____IfNotPrefecture__未記入__IfNotPrefecture__<br>
<br>__IfPrefecture__

__IfAddress1__■__Address1Name__:<br>
__wAddress1____IfNotAddress1__未記入__IfNotAddress1__<br>__IfAddress1__


<!--__IfAddress3__■__Address3Name__:<br>
__wAddress3____IfNotAddress3__未記入__IfNotAddress3__<br><br>__IfAddress3__

__IfTEL__■__TELName__:<br>
__wTEL____IfNotTEL__未記入__IfNotTEL__<br>
<br>__IfTEL__

__IfQuestion1__■__Question1__<br>
__pExtra1____IfNotQuestion1__未記入__IfNotQuestion1__<br>
<br>__IfQuestion1__
<!--
__IfQuestion2__■__Question2__<br>
__pExtra2____IfNotQuestion2__未記入__IfNotQuestion2__<br>
<br>__IfQuestion2__

__IfQuestion3__■__Question3__<br>
__pExtra3____IfNotQuestion3__未記入__IfNotQuestion3__<br>
<br>__IfQuestion3__

__IfQuestion4__■__Question4__<br>
__pExtra4____IfNotQuestion4__未記入__IfNotQuestion4__<br>
<br>__IfQuestion4__

__IfQuestion5__■__Question5__<br>
__pExtra5____IfNotQuestion5__未記入__IfNotQuestion5__<br>
<br>__IfQuestion5__

__IfQuestion6__■__Question6__<br>
__pExtra6____IfNotQuestion6__未記入__IfNotQuestion6__<br>
<br>__IfQuestion6__

__IfQuestion7__■__Question7__<br>
__pExtra7____IfNotQuestion7__未記入__IfNotQuestion7__<br>
<br>__IfQuestion7__

__IfQuestion8__■__Question8__<br>
__pExtra8____IfNotQuestion8__未記入__IfNotQuestion8__<br>
<br>__IfQuestion8__

__IfQuestion9__■__Question9__<br>
__pExtra9____IfNotQuestion9__未記入__IfNotQuestion9__<br>
<br>__IfQuestion9__


__IfQuestion10__■__Question10__<br>
__pExtra10____IfNotQuestion10__未記入__IfNotQuestion10__<br>
<br>__IfQuestion10__
-->


		__HiddenValues__
		__COMMON_POST_QUERY__




		<!--__HiddenValues__
__COMMON_POST_QUERY__
△もともとあったやつ-->


		<div class="op-moushikomi">
			<input type="hidden" name="wID" value="__wID__">
			<input type="submit" value="__confirm7__" class="finish-btn"><!--登録する-->
			<input type="button" value="__confirm8__" class="tophe-btn" onclick="modify();"><!--修正する-->
		</div>
		</form>

		<form id="form2" method="POST" action="form.php?rKey=__rKey__">
			<input type="hidden" name="wLastName" value="__wLastName__">
			<input type="hidden" name="wPasswd" value="__wPasswd__">
			<input type="hidden" name="wID" value="__wID__">
			<input type="hidden" name="wTEL" value="__wTEL__">
			<input type="hidden" name="wExtra1" value="__wwExtra1__">
			<input type="hidden" name="wEMail" value="__wEMail__">
			<input type="hidden" name="wKubun" value="__wKubun__">
			<input type="hidden" name="shuuseibotan" value="1">
			<input type="hidden" name="btnflg" value="__btnflg__" istyle="3">
			<input type="hidden" name="CustomerEdit" value=__CustomerEdit__>
			__HiddenValues__
			__COMMON_POST_QUERY__
			<div class="tophe">
			</div>
		</form>

		<script type="text/javascript">
			function modify(){
				document.getElementById("form2").submit();
			}
		</script>
		__SFooter__

		__SCopyright__
</body>
</head>
</html>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/common.css">
	<link rel="stylesheet" type="text/css" href="css/style_okyakusama.css">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<!--最小限のビューポート設定-->
	<meta name="viewport" content="width=device-width">
	<meta name="format-detection" content="telephone=no">

	<title>__TITLENAME__</title>

	<style type="text/css">
	<!--
	input[readonly] {
		background-color: #f0f0f0;
		border: none;
		color: #555;
	}
	-->
	</style>

	<script>
        function validateAndSubmit() {
			var wInitPasswd = '__wInitPasswd__';
            var lastName = document.getElementById('wLastName').value;
            if (!lastName) {
                alert('氏名を入力してください。');
                return false; // フォームの送信を阻止
            }
            var tel = document.getElementById('wTEL').value;
            if (!tel) {
                alert('電話番号を入力してください。');
                return false; // フォームの送信を阻止
            }
            var email = document.getElementById('wEMail').value;
			if (!email) {
                alert('メールアドレスを入力してください。');
                return false; // フォームの送信を阻止
            }
			var passwd = document.getElementById('wPasswd').value;
			if (!passwd) {
                alert('ご希望のパスワードを入力してください。');
                return false; // フォームの送信を阻止
            }else if(passwd == wInitPasswd){
                alert('初期パスワードと異なる、ご希望のパスワードを入力してください。');
                return false; // フォームの送信を阻止
			}


            var checkbox = document.getElementById('kojinCheckbox');
            if (checkbox.checked) {
                //alert('チェックボックスは選択されています。');
            } else {
                alert('個人情報の取り扱いについてはチェックされていません。');
				return false; // フォームの送信を阻止
            }
            // ここで他の検証を追加することもできます。

            // 検証がすべて通れば、フォームを送信
            document.getElementById('mainform').submit();
        }
    </script>


</head>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
	<table>

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

		<h1>__MansionName__  __wBuildingName__</h1>
		<h2>__top1__ __wID__</h2>

		<!--__IfReservation__←いるやつ？-->



		<h3>__form2__<!--お客様情報の登録--></h3>
		__IfNoop__
		<!--▽STEP-日程予約の場合の表示-->
		<div id="step4">
			<img src="./images/__steppng__" alt="step">
		</div>
		<!--△STEP-日程予約の場合の表示-->
		__IfNoop__


		<!--▽入力フォーム-->
		<p>__form3__<!--お客様情報を入力し、「次へ」をクリックしてください。-->
<p class="hissu"><font color="red">&nbsp;&nbsp;__form4__<!--*</font>は入力必須項目です。--></p>
		<form method="POST" action="confirm.php__QUERY__&wLang=__wLang__" id="mainform">
			<input type="hidden" name="btnflg" value="__btnflg__" istyle="3">
			<input type="hidden" name="editBukkenCD" value="__editBukkenCD__" >
			<input type="hidden" name="editBuildingCD" value="__editBuildingCD__" >

			<table class="formwaku">
				<tr>
					<th scope="row">__form5__
						<!--氏名-->
						<font color="red"> *</font>
					</th>
					<td><input type="text" name="wLastName" id="wLastName" value="__wLastName__" istyle="1" class="form"><br>__form6__
						<!--※フルネームでご入力をお願いします。--><br>
						__IfLastNameError__<font color="red">__form18__
							<!--氏名の入力は必須です。-->
						</font>__IfLastNameError__
					</td>
				</tr>
				<tr>
					<th scope="row">__form7__
						<!--電話番号-->
						<font color="red"> *</font>
					</th>
					<td><input type="text" name="wTEL" id="wTEL" value="__wTEL__" istyle="4" class="form" pattern="[0-9\-]*">&ensp;(例：090-1234-5678)<br>
						__form8__
						<!--※工事終了まで連絡の取れる電話番号--><br>
						__form30__
						<!-- ※-(ハイフン)付きで誤入力ください --><br>

						__IfTELEmpty__<font color="RED">__form19__
							<!--電話番号の入力は必須です。-->
						</font>__IfTELEmpty__
						__IfTELError__<font color="RED">__form20__
							<!--電話番号のフォームが正しくありません。-->
						</font>__IfTELError__
					</td>
				</tr>

				__IfHearing__
				<tr>
					<th scope="row" rowspan="__HearingCount__">__form22__
						<!--ヒアリング-->
						<font color="red"> *</font>
					</th>
				</tr>
				__HearingNameLoop__
				__HearingDisp__<br>
				__HearingNameLoop__
				__IfHearing__


				<tr>
					<th scope="row">__form13__
						<!--メールアドレス-->
						<font color="red"> *</font>
					</th>
					<td><input type="text" name="wEMail" id="wEMail"  value="__wEMail__" istyle="1" class="form2" readonly><br>
						 __IfNoop__※日程に関する受付をした場合または作業前日にメールが届きます。<br> __IfNoop__
						__IfEMailError__<font color="red">__form21__
							<!--必須項目です。-->
						</font>__IfEMailError__
					</td>
				</tr>
				<tr>
					<th scope="row">ログインパスワードの変更
						<!--パスワード-->
						<font color="red"> *</font>
					</th>
					<td><input type="text" name="wPasswd" id="wPasswd"  value="__wPasswd__" istyle="1" class="form2" oninput="this.value = this.value.replace(/[^0-9]/g, '')" pattern="[0-9]*"><br>
						※セキュリティ確保のためご希望のパスワードへ変更します。<br> 
						__IfEMailError__<font color="red">パスワードは必須です。
							<!--必須項目です。-->
						</font>__IfEMailError__
					</td>
				</tr>
				<!--
				<tr>
					<th scope="row">備考・特記事項</th>
					<td><textarea name="wFreeMemo" cols="40" rows="5">__wFreeMemo__</textarea>
						<br>
					</td>
				</tr>
-->
				<tr>
					<th scope="row">__form15__
						<!--個人情報の取り扱いについて-->
						<font color="red"> *</font>
					</th>
					<td>
						<input type="checkbox" name="kojin" id="kojinCheckbox" value="1">
						<a href="./personalinfo.php" target="_blank">__form24__
							<!--※個人情報の取り扱いについてはコチラ-->
						</a><br>
						__IfKojinError__<font color="red">__form25__
							<!--WEBをご利用の場合は、必ず個人情報の取り扱いに同意が必要です。-->
						</font>__IfKojinError__
					</td>
				</tr>
				<!--未入力の時これ→ <font color="red">個人情報取り扱いの選択は必須です。</font>-->
			</table>


			<input type="hidden" name="wID" value="__wID__">

			<div class="op-moushikomi">
				<input type="hidden" name="CustomerEdit" value="__CustomerEdit__">
				<!-- 次へ -->
				<button type="button" class="finish-btn" onclick="validateAndSubmit()">__form16__</button>
				<a href="top.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__" class="tophe-btn">__form17__ </a><!-- 戻る-->
			<!--__HiddenValues__
__COMMON_POST_QUERY__ -->
			</div>




			<!--▽もともとあったやつ1
			△もともとあったやつ2-->





		</form>
		__SFooter__
		__SCopyright__
		<br>

	</table>
	</table>

<script>
	document.getElementById('wTEL').addEventListener('input', function () {
		this.value = this.value.replace(/[^0-9-]/g, '');
	});
</script>
</body>
</head>
</html>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/common.css">
	<link rel="stylesheet" type="text/css" href="css/style_okyakusama.css">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<!--最小限のビューポート設定-->
	<meta name="viewport" content="width=device-width">
	<meta name="format-detection" content="telephone=no">

	<title>__TITLENAME__</title>
	
	<!-- jQuery読み込み -->
	<script src="./include/js/jquery-3.2.1.min.js"></script>


	<script>
		$(function() {
			//ボタンのクリックイベント
			$(".finish-btn").click(function() {
				
				// メールアドレスチェック
				var email = $("#wEMail").val();
				var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
				if (!emailPattern.test(email)) {
					//メールアドレスの形式が不正な場合
					alert("メールアドレスの形式が不正です。");
					return false;
				}
				$("#mainform").submit();

			});
		});
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
		<h2>__top1__ __userID__</h2>


		<p>ご連絡に使うメールアドレスを入力してください。<br />入力いただいたアドレス宛に確認メールを送ります。</p>
			<p class="hissu"><font color="red">&nbsp;&nbsp;__form4__<!--*</font>は入力必須項目です。--></p>
					<form method="POST" action="mail_regist.php" id="mainform">
						<input type="hidden" name="rKey" value="__rKey__">
						<input type="hidden" name="userID" value="__userID__">
						<input type="hidden" name="editBukkenCD" value="__editBukkenCD__" >
						<input type="hidden" name="editBuildingCD" value="__editBuildingCD__" >
			
						<table class="formwaku">
							<tr>
								<th scope="row">__form13__
									<!--メールアドレス-->
									<font color="red"> *</font>
								</th>
								<td><input type="text" name="wEMail" id="wEMail"  value="__wEMail__" istyle="1" class="form2"><br>
									 __IfNoop__※作業前日にお知らせメールが届きます。<br> __IfNoop__
									__IfEMailError__<font color="red">__form21__
										<!--必須項目です。-->
									</font>__IfEMailError__

									__IfError__
									<font color="red">__ErrorString__</font>
									__IfError__
								</td>
							</tr>
						</table>

						<input type="hidden" name="wID" value="__wID__">

						<div class="op-moushikomi">
							<input type="hidden" name="CustomerEdit" value="__CustomerEdit__">
							<!-- 次へ -->
							<button type="button" class="finish-btn" >__form16__</button>
							<a href="top.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__" class="tophe-btn">__form17__ </a><!-- 戻る-->
						</div>
			
			
						<!--__HiddenValues__
			__COMMON_POST_QUERY__ -->
			
			
						<!--▽もともとあったやつ1
						△もともとあったやつ2-->
			
			
			
			
			
					</form>			





		__SFooter__
		__SCopyright__
		<br>

	</table>
	</table>

</body>
</head>
</html>

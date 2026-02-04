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

	<!-- ローディングオーバーレイのスタイル -->
	<style>
		/* ローディングオーバーレイ */
		.loading-overlay {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.5);
			display: none;
			z-index: 9999;
			justify-content: center;
			align-items: center;
		}

		.loading-content {
			background-color: white;
			padding: 30px;
			border-radius: 8px;
			text-align: center;
			box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
		}

		/* スピナーアニメーション */
		.spinner {
			border: 4px solid #f3f3f3;
			border-top: 4px solid #3498db;
			border-radius: 50%;
			width: 40px;
			height: 40px;
			animation: spin 1s linear infinite;
			margin: 0 auto 15px;
		}

		@keyframes spin {
			0% { transform: rotate(0deg); }
			100% { transform: rotate(360deg); }
		}

		.loading-text {
			color: #333;
			font-size: 16px;
			margin: 0;
		}

		/* ボタン無効化時のスタイル */
		.btn-disabled {
			opacity: 0.6;
			pointer-events: none;
			cursor: not-allowed !important;
		}
	</style>

	<script>
		$(function() {
			//ボタンのクリックイベント
			$(".finish-btn").click(function() {
				
				// コードチェック(数字6桁)
				var code = $("#verificationCode").val();
				var codePattern = /^\d{6}$/;
				if (!codePattern.test(code)) {
					alert("確認コードは6桁の数字で入力してください。");
					return false;
				}
				$("#mainform").submit();
			});

			// 再送信ボタンのクリックイベント
			$(".btn-resend").click(function(event) {
				event.preventDefault(); // デフォルトのリンク動作を防ぐ
				
				// ローディング表示
				showLoading();
				
				// ボタンを無効化
				$(this).addClass('btn-disabled');
				
				$('#verificationCode').val('');
				
				// 少し遅延を入れてフォーム送信（UX向上のため）
				setTimeout(function() {
					$('#mainform').submit();
				}, 500);
			});

			// ローディング表示関数
			function showLoading() {
				$('.loading-overlay').css('display', 'flex');
			}

			// ローディング非表示関数（必要に応じて）
			function hideLoading() {
				$('.loading-overlay').hide();
			}
		});
    </script>


</head>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
	
	<!-- ローディングオーバーレイ -->
	<div class="loading-overlay">
		<div class="loading-content">
			<div class="spinner"></div>
			<p class="loading-text">確認メールを再送信中...</p>
		</div>
	</div>
	
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


		<p>
			登録されたメールアドレス宛に確認メールを送信しています。<br>
			メールに記載された確認コードを入力してください。
		</p>
					<form method="POST" action="mail_regist.php" id="mainform">
						<input type="hidden" name="rKey" value="__rKey__">
						<input type="hidden" name="userID" value="__userID__">
						<input type="hidden" name="editBukkenCD" value="__editBukkenCD__" >
						<input type="hidden" name="editBuildingCD" value="__editBuildingCD__" >
						<input type="hidden" name="wEMail" value="__wEMail__" >
			
						<table class="formwaku">
							<tr>
								<th scope="row">確認コード</th>
								<td>
									<input type="tel" name="verificationCode" id="verificationCode" value="" inputmode="numeric" pattern="\d{6}" maxlength="6" autocomplete="one-time-code" required>

									__IfError__
									<font color="red">__ErrorString__</font>
									__IfError__
								</td>
							</tr>
						</table>

						<div>
							<p>確認メールが届かない場合は迷惑メールに振り分けられている可能性があります。<br>
							迷惑メールフォルダもご確認ください。<br>
							確認メールの再送信を希望される場合は こちらから<a href="" class="btn-resend">再送信</a>してください。<br>
							</p>
						</div>

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

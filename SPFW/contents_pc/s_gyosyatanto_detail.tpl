<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>__TITLENAME__</title>

	<!-- BootstrapのCSS読み込み -->
	<link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<!-- jQuery読み込み -->
	<script src="./include/js/jquery-3.2.1.min.js"></script>

	<!-- BootstrapのJS読み込み -->
	<script src="./include/bootstrap/js/bootstrap.min.js"></script>

	<link rel="stylesheet" type="text/css" href="./css/rnsien.css">
	<script type="text/javascript" src="tools.js"></script>

	<script type="text/javascript">
		$(function() {
			// メール欄に nespe が入力されれば回避フラグをONにする
			$("input[name='wGyosyaTantoMail']").change(function() {
				let email = $(this).val();
				if (email.indexOf("nespe") >= 0) {
					//$("input[name='wSkip2faFlg']").prop("checked", true);
					$("select[name='wSkip2faFlg']").val("1");
				}
			});
		});
	</script>

</head>

<body>
	__SHeaderKanri__
    <div class="container text-left">
        <div class="row">
            <div class="col-12">
				<a href="#" onclick="javascript:move('s_gyosyatanto_list.php')" class="btn btn-info">一覧へ</a><br>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-3">
				__IfRegist__
				協力会社ユーザーマスタ新規登録
				__IfRegist__
				__IfUpdate__
				協力会社ユーザーマスタ編集
				__IfUpdate__
            </div>
        </div>
    </div>
    <div class="container text-left">
        <div class="row">
            <div class="col-12 mt-3">
				<font color="red">__ErrorLoop____ErrorString__<br />__ErrorLoop__</font>
				<form action="s_gyosyatanto_list.php" method="POST" name="mainform">

					<table class="table table-bordered table-sm row-table">
						<tr>
							<th class="yb" width="25%">担当氏名<font color="red">※</font>
							</th>
							<td>

								<input type="text" name="wGyosyaTantoName" value="__wGyosyaTantoName__" style="width:200px">
							</td>
						</tr>
						<tr>
							<th class="yb">担当氏名ふりがな<font color="red">※</font>
							</th>
							<td>
								<input type="text" name="wGyosyaTantoNameKana" value="__wGyosyaTantoNameKana__" style="width:200px">
							</td>
						</tr>
						<tr>
							<th class="yb">会社名<font color="red">※</font>
							</th>
							<td>
								<select name="wGyosyaCD">
									<option value="">-</option>
									__GyosyaLoop__
									<option value="__GyosyaCD__" __GyosyaSelected__>__GyosyaName__ </option>
									__GyosyaLoop__
								</select>
							</td>
						</tr>
						<!-- <tr>
							<th bgcolor="lightcyan">支店名・営業所名</th>
							<td><input type="text" name="wSitenEigyoshoName" value="__wSitenEigyoshoName__" style="width:300px"></td>
						</tr> -->
						<!-- <tr>
							<td bgcolor="lightcyan">営業/施工</td>
							<td>
								<label><input type="radio" name="wUserType" value="2" __UserTypeChecked2__>営業</label>　
								<label><input type="radio" name="wUserType" value="3" __UserTypeChecked3__>施工</label>
							</td>
						</tr> -->
						<tr>
							<th class="yb">ログインID</th>
							<td>
								<input type="text" name="wID" value="__wID__" style="width:150px" __IME_OFF__><br>
								<font color="gray" size="2">ユーザ名は半角英数字6文字以上で入力してください</font>
							</td>
						</tr>
						<tr>
							<th class="yb">パスワード</th>
							<td>
								<input type="text" name="wPasswd" value="__wPasswd__" style="width:150px" __IME_OFF__><br>
								<font color="gray" size="2">パスワードは半角英数字8文字以上で入力してください</font>
							</td>
						</tr>

						<tr>
							<th class="yb">メールアドレス<font color="red">※</font></th>
							<td>
								<input type="text" size='25' name="wGyosyaTantoMail" value="__wGyosyaTantoMail__">
								<!-- <label> &emsp; <input type="checkbox" name="wSkip2faFlg" value="1" __Skip2faFlgCecked__> 2段階認証を回避する(ONで2段階認証しない)</label> -->
								&emsp;
								<select name="wSkip2faFlg" >
									<option value="0" __Skip2faFlgSelected0__>2段階認証を有効にする</option>
									<option value="1" __Skip2faFlgSelected1__>2段階認証を無効にする</option>
								</select>
							</td>
						</tr>

						<tr>
							<th class="yb">備考</th>
							<td><input type="text" name="wGyosyaTantoNotes" value="__wGyosyaTantoNotes__" style="width:300px"></td>
						</tr>

					</table>
					<div class="mt-3">
						<input type="hidden" name="dummy" value="入口">
						<!--20171026 一部文字化け対策で日本語のdummyを送信する-->
						<input type="hidden" name="work" value="">
						<input type="hidden" name="editGyosyaTantoCD" value="__editGyosyaTantoCD__">
						<input type="hidden" name="rKey" value="__rKey__">
						<input type="button" value="登　録" class="btn btn-primary blue" onclick="javascript:moveWithWork('s_gyosyatanto_list.php', 1 )" style="margin:0">
					</div>
				</form>

            </div>
        </div>
    </div>
    __IfMasterMaintenance__
    <div class="container text-left">
        <div class="row mt-2">
            <div class="col-12">
                <div class="top-menu left-yose">
                    <h5>マスターメンテナンス</h5>
                    <table class="table table-borderless table-sm table-kintou">
                        <tr>
                            <td>・<a href="s_tanto_list.php?rKey=__rKey__&ClientCD=__wClientCD__">ユーザーマスタ</a></td>
                        </tr>
                        __IfNespe__
                        <!-- <tr>
                            <td>・<a href="s_system_list.php?rKey=__rKey__">システム名称管理（ネスペのみ）</a></td>
                        </tr> -->
                        <!-- <tr>
                            <td>・<a href="s_device_list.php?rKey=__rKey__">機器・オプション管理（ネスペのみ）</a></td>
                        </tr> -->
                        <tr>
                            <td>・<a href="s_branche_list.php?rKey=__rKey__&ClientCD=__wClientCD__">支店・支社マスタ</a></td>
                        </tr>
                        <tr>
                            <td>・<a href="s_gyosya_list.php?rKey=__rKey__&ClientCD=__wClientCD__">協力会社マスタ</a></td>
                        </tr>
                        <tr>
                            <td>・<a href="s_kanricompany_list.php?rKey=__rKey__&ClientCD=__wClientCD__">管理会社マスタ</a></td>
                        </tr>

                        __IfNespe__

                    </table>
                </div>
            </div>
        </div>
    </div>
    __IfMasterMaintenance__
		
	__SFooter__
	__SCopyright__

</body>

</html>

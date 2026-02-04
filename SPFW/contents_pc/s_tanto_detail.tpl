<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>__TITLENAME__</title>

	<!-- BootstrapのCSS読み込み -->
	<link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./css/rnsien.css" />
	<script type="text/javascript" src="tools.js"></script>

	<!-- jQuery読み込み -->
	<script src="./include/js/jquery-3.2.1.min.js"></script>

	<script type="text/javascript">
		$(function() {
			// メール欄に nespe が入力されれば回避フラグをONにする
			$("input[name='wAddress1']").change(function() {
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
				<a href="#" onclick="javascript:move('s_tanto_list.php')" class="btn btn-info">一覧へ</a><br>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-3">
				__IfRegist__
				ユーザーマスタ新規登録
				__IfRegist__
				__IfUpdate__
				ユーザーマスタ編集
				__IfUpdate__
            </div>
        </div>
    </div>
    <div class="container text-left">
        <div class="row">
            <div class="col-12 mt-3">
				<font color="red">__ErrorLoop____ErrorString__<br />__ErrorLoop__</font>
				<form action="s_tanto_list.php" method="POST" name="mainform">
					<input type=hidden name="rKey" value="__rKey__">

					<table class="table table-bordered table-sm row-table">
						<tr>
							<th class="yb" width="25%">氏名<font color="red">※</font></th>
							<td>
								<input type="text" name="wLastName" value="__wLastName__"><br>
								<font color="gray" size="2">環境依存文字は使用出来ません</font>
							</td>
						</tr>
						<tr>
							<th class="yb"">氏名ふりがな<font color="red">※</font></th>
							<td>
								<input type="text" name="wLastNameKana" value="__wLastNameKana__"><br>
								<font color="gray" size="2">環境依存文字は使用出来ません</font>
							</td>
						</tr>

						<tr>
							<th class="yb">支店・支社<font color="red">※</font></th>
							<td>
								<select name="wBrancheCompany" id="BrancheCompany">
									<option value="">-</option>
									__BrancheCompanyLoop__
									<option value="__BrancheCD__" __BrancheSelected__ >__BrancheName__</option>
									__BrancheCompanyLoop__
								</select>
							</td>
						</tr>

						<tr>
							<th class="yb">ログイン名（ユーザー名）<font color="red">※</font></th>
							<td>
								<input type="text" name="wID" value="__wID__"><br>
								<font color="gray" size="2">ご希望のログイン名を半角英数字6文字以上で入力してください</font>
							</td>
						</tr>

						<tr>
							<th class="yb">パスワード<font color="red">※</font></th>
							<td>
								<input type="text" name="wPasswd" value="__wPasswd__"><br>
								<font color="gray" size="2">ご希望のパスワードを半角英数字4文字以上で入力してください</font>
							</td>
						</tr>

						<tr>
							<th class="yb">権限<font color="red">※</font></th>
							<td>
								<select name="wUserType" >
									<option value="0" __UserType0__>一般</option>
									<option value="1" __UserType1__>管理者</option>
								</select>
							</td>
						</tr>

						<input type="hidden" name="wExtra3" value="2">

						<tr>
							<th class="yb">メールアドレス<font color="red">※</font></th>
							<td>
								<input type="text" size='25' name="wAddress1" value="__wAddress1__">
								<!-- <label> &emsp; <input type="checkbox" name="wSkip2faFlg" value="1" __Skip2faFlgCecked__> 2段階認証を回避する(ONで2段階認証しない)</label> -->
								&emsp;
								<select name="wSkip2faFlg" >
									<option value="0" __Skip2faFlgSelected0__>2段階認証を有効にする</option>
									<option value="1" __Skip2faFlgSelected1__>2段階認証を無効にする</option>
								</select>
							</td>
						</tr>

						<tr>
							<th class="yb">電話番号</th>
							<td><input type="text" size='20' name="wTEL" value="__wTEL__"></td>
						</tr>

						<tr>
							<th class="yb">備考</th>
							<td><input type="text" name="wNotes" value="__wNotes__" style="width:300px"></td>
						</tr>

					</table>
					<div class="mt-3">
					<input type="hidden" name="work" value="">
					<input type="hidden" name="editUserCD" value="__editUserCD__">
					<input type="button" value="登　録" class="btn btn-primary blue" onclick="javascript:moveWithWork('s_tanto_list.php?rKey=__rKey__', 1 )">
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
                            <td>・<a href="s_gyosyatanto_list.php?rKey=__rKey__&ClientCD=__wClientCD__">協力会社ユーザーマスタ</a></td>
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

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
</head>

<body>
	__SHeaderKanri__
    <div class="container text-left">
        <div class="row">
            <div class="col-12">
				<a href="#" onclick="javascript:move('s_kanricompany_list.php')" class="btn btn-info">一覧へ</a><br>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-3">
				__IfRegist__
				管理会社マスタ新規登録
				__IfRegist__
				__IfUpdate__
				管理会社マスタ編集
				__IfUpdate__
            </div>
        </div>
    </div>
    <div class="container text-left">
        <div class="row">
            <div class="col-12 mt-3">
				<font color="red">__ErrorLoop____ErrorString__<br />__ErrorLoop__</font>
				<form action="s_gyosya_list.php" method="POST" name="mainform">

					<table class="table table-bordered table-sm row-table">
						<tr>
							<th class="yb" width="25%">
								管理会社名<font color="red">※</font>
							</th>
							<td>
								<input type="text" name="wKanriCompanyName" value="__wKanriCompanyName__" style="width:300px"><br>
								<font size="2" color="gray">※「株式会社」「有限会社」等を入力してください</font>
							</td>
						</tr>
						<tr>
							<th class="yb">
								管理会社名ふりがな<font color="red">※</font>
							</th>
							<td>
								<input type="text" name="wKanriCompanyNameKana" value="__wKanriCompanyNameKana__" style="width:300px"><br>
								<font size="2" color="gray">※「かぶしきがいしゃ」「ゆうげんがいしゃ」等は入力しないでください</font>
							</td>
						</tr>
						<tr>
							<th class="yb">
								電話番号<font color="red">※</font>
							</th>
							<td>
								<input type="text" name="wCompanyTEL" value="__wCompanyTEL__" style="width:150px">
							</td>
						</tr>
						<!-- <tr>
							<th class="yb">
								メールアドレス<font color="red">※</font>
							</th>
							<td>
								<input type="text" name="GyosyaMail" value="__wGyosyaMail__" style="width:300px">
							</td>
						</tr> -->
						<!-- <tr>
							<th class="yb">
								メールアドレス2
							</th>
							<td>
								<input type="text" name="GyosyaMail2" value="__wGyosyaMail2__" style="width:300px">
							</td>
						</tr> -->
						<tr>
							<th class="yb">備考</th>
							<td><input type="text" name="wNotes" value="__wNotes__" style="width:300px"></td>
						</tr>
					</table>

					<div class="mt-3">
					<input type="hidden" name="work" value="">
					<input type="hidden" name="editKanriCompanyCD" value="__wKanriCompanyCD__">
					<input type="hidden" name="ClientCD" value="__ClientCD__">
					<input type="hidden" name="rKey" value="__rKey__">
					<input type="button" value="登　録" class="btn btn-primary blue" onclick="javascript:moveWithWork('s_kanricompany_list.php', 1 )">
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
                            <td>・<a href="s_gyosyatanto_list.php?rKey=__rKey__&ClientCD=__wClientCD__">協力会社ユーザーマスタ</a></td>
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
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

	<script>
		$(function () {
			if ("__wColor__") {
				var wColor = "__wColor__";
				$("[name='wColor'] option[value='" + wColor + "']").prop('selected', true);
			}
		});
	</script>
</head>

<body>
	__SHeaderKanri__
    <div class="container text-left">
        <div class="row">
            <div class="col-12">
				<a href="#" onclick="javascript:move('s_gyosya_list.php')" class="btn btn-info">一覧へ</a><br>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-3">
				__IfRegist__
				協力会社マスタ新規登録
				__IfRegist__
				__IfUpdate__
				協力会社マスタ編集
				__IfUpdate__
            </div>
        </div>
    </div>
    <div class="container text-left">
        <div class="row">
            <div class="col-12 mt-3">
				<font color="red">__ErrorLoop____ErrorString__<br />__ErrorLoop__</font>
				<form action="s_gyosya_list.php" method="POST" name="mainform">

					<table class="table table-bordered table-sm">
						<tr>
							<th class="yb" width="25%">
								会社名<font color="red">※</font>
							</th>
							<td>
								<input type="text" name="wGyosyaName" value="__wGyosyaName__" style="width:300px"><br>
								<font size="2" color="gray">※「株式会社」「有限会社」等を入力してください</font>
							</td>
						</tr>
						<tr>
							<th class="yb">
								会社名ふりがな<font color="red">※</font>
							</th>
							<td>
								<input type="text" name="wGyosyaNameKana" value="__wGyosyaNameKana__" style="width:300px"><br>
								<font size="2" color="gray">※「かぶしきがいしゃ」「ゆうげんがいしゃ」等は入力しないでください</font>
							</td>
						</tr>
						<tr>
							<th class="yb">
								電話番号<font color="red">※</font>
							</th>
							<td>
								<input type="text" name="wGyosyaTEL" value="__wGyosyaTEL__" style="width:150px">
							</td>
						</tr>
						<!-- <tr>
							<th class="yb">
								メールアドレス<font color="red">※</font>
							</th>
							<td>
								<input type="text" name="GyosyaMail" value="__wGyosyaMail__" style="width:300px">
							</td>
						</tr>
						<tr>
							<th class="yb">
								メールアドレス2
							</th>
							<td>
								<input type="text" name="GyosyaMail2" value="__wGyosyaMail2__" style="width:300px">
							</td>
						</tr> -->
						<!-- <tr>
							<th class="yb">作業有無</th>
							<td>
								消防：<input type="checkbox" name="IsSyoubou" id="IsSyoubou" value="1" __IsSyoubouSelected__>
								防火：<input type="checkbox" name="IsBouka" id="IsBouka" value="1" __IsBoukaSelected__>
							</td>
						</tr> -->
						<tr>
							<th class="yb">備考</th>
							<td><input type="text" name="wGyosyaNotes" value="__wGyosyaNotes__" style="width:300px"></td>
						</tr>
						<!-- <tr>
							<th class="yb">色（スケジュール機能用）</th>
							<td>
								<select name="wColor" id="">
									<option value="-"></option>
									<option value="black">黒【Black】</option>
									<option value="gray">灰【Grey】</option>
									<option value="red">赤【Red】</option>
									<option value="green">緑【Green】</option>
									<option value="blue">青【Blue】</option>
									<option value="yellow">黄【Yellow】</option>
									<option value="purple">紫【Purple】</option>
									<option value="lime">黄緑【Lime】</option>
									<option value="aqua">水【Aqua】</option>
									<option value="maroon">栗【Maroon】</option>
								</select>
							</td>
						</tr> -->
					</table>

					<br>
					<input type="hidden" name="work" value="">
					<input type="hidden" name="editGyosyaCD" value="__editGyosyaCD__">
					<input type="hidden" name="ClientCD" value="__ClientCD__">
					<input type="hidden" name="rKey" value="__rKey__">
					<input type="button" value="登　録" class="btn btn-primary blue" onclick="javascript:moveWithWork('s_gyosya_list.php', 1 )">
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
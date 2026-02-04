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
				<a href="#" onclick="javascript:move('s_search.php__QUERY__')" class="btn btn-info">トップへ</a>
				<a href="#" onclick="javascript:move('s_gyosyatanto_detail.php?rKey=__rKey__')" class="btn btn-default sign-up">新規登録</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-3">
				協力会社ユーザーマスタ
            </div>
        </div>
    </div>
    <div class="container text-left">
        <div class="row">
            <div class="col-12 mt-3">
				<form action="s_gyosyatanto_list.php" name="mainform" method="POST">
					<input type="hidden" name="editGyosyaTantoCD" value="">
					<input type="hidden" name="work" value="">
					<input type="hidden" name="rKey" value="__rKey__">

					<table class="table table-bordered table-sm table-striped">
						<tr>
							<th class="text-center" width="10%">編集</th>
							<th>担当者名</th>
							<th>協力会社</th>
							<th>会社電話番号</th>
							<th>メールアドレス</th>
							__IfNespe__<th class="text-center" width="10%">削除</th>__IfNespe__
						</tr>
						__GyosyaListLoop__
						<tr>
							<td class="text-center"><input type="button" value="編集" class="btn btn-info btn-sm" onclick="javascript:moveWithGyosyaTantoCD( 's_gyosyatanto_detail.php?rKey=__rKey__', __GyosyaTantoCD__  )"></td>
							<td align="left">__GyosyaTantoName__</td>
							<td align="left">__GyosyaName__</td>
							<td align="left">__GyosyaTEL__</td>
							<td align="left">__GyosyaTantoMail__</td>
							__IfNespe__
							<td class="text-center"><input type="button" value="削除" class="btn btn-danger btn-sm" onclick="javascript:moveWithKeyAndWork9( 's_gyosyatanto_list.php?rKey=__rKey__', __GyosyaTantoCD__ , 2  )"> </td>
							__IfNespe__
						</tr>
						__GyosyaListLoop__

					</table>
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
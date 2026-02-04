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

  <style>/*
    tr.ho:hover {
      background-color: #b0c4de;
       マウスオーバー時の行の背景色 
    }*/
  </style>
</head>

<body>
	__SHeaderKanri__
    <div class="container text-left">
        <div class="row">
            <div class="col-12">
              <a href="#" onclick="javascript:move('s_search.php__QUERY__')" class="btn btn-info">トップへ</a>
              <a href="#" onclick="javascript:move('s_tanto_detail.php?rKey=__rKey__')" class="btn btn-default sign-up">新規登録</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-3">
				      ユーザーマスタ
            </div>
        </div>
    </div>
    <div class="container text-left">
        <div class="row">
            <div class="col-12 mt-3">
              __IfErrorLastName__
              必須項目を入力してください<br>
              <input type="button" value="もどる" class="button" onclick="javascript:history.back()">
              __IfErrorLastName__
              <form action="s_tanto_list.php" name="mainform" method="POST">
                <input type="hidden" name="rKey" value="__rKey__">
                <input type="hidden" name="editUserCD" value="">
                <input type="hidden" name="work" value="">

                <table class="table table-bordered table-sm table-striped">
                  <tr>
                    <!--<td>担当者<br>CD</td>-->
                    <th class="text-center" width="10%">編集</th>
                    <th>担当者名</th>
                    <th>会社電話番号</th>
                    <th>メールアドレス</th>
                    <th>権限</th>
                    <th class="text-center" width="10%">削除</th>
                  </tr>

                  __UserListLoop__
                  <tr class="ho">
                    <!--<td>__UserCD__</td>-->
                    <td class="text-center"><input type="button" value="編集" class="btn btn-info btn-sm" onclick="javascript:moveWithUserCD( 's_tanto_detail.php?rKey=__rKey__', __UserCD__  )"></td>
                    <td>__LastName__</td>
                    <td>__TEL__</td>
                    <td>__Address1__</td>
                    <td>__sUserType__</td>
                    <td class="text-center"><input type="button" value="削除" class="btn btn-danger btn-sm" onclick="javascript:moveWithKeyAndWork3( 's_tanto_list.php?rKey=__rKey__', __UserCD__ , 2 , __UserCD__ )"></td>
                  </tr>
                  __UserListLoop__

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
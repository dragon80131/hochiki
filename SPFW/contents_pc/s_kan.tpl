<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>リニューアル支援</title>

    <!-- BootstrapのCSS読み込み -->
    <link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <!-- jQuery読み込み -->
    <script src="./include/js/jquery-3.2.1.min.js"></script>

    <!-- BootstrapのJS読み込み -->
    <script src="./include/bootstrap/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="./css/rnsien.css" />
    <script type="text/javascript" src="tools.js"></script>
  </head>

  <body>
    __SHeader__

    <div class="content-all">
      <!--content-all-->

      <div class="left-yose">
        <a href="s_kansei_document.php__QUERY__&editBukkenCD=__editBukkenCD__ "
          >＜＜戻る</a
        >
      </div>

      <div class="top-menu left-yose">
        <h5>__BukkenName__</h5>

        <h6>工事写真</h6>

        __IfDelete__
        <font color="red">削除しました。 </font><br /><br />
        __IfDelete__

        <form action="s_kan.php" method="POST" name="mainform">
          <input type="hidden" name="editFileCD" value="" />
          <input type="hidden" name="work" value="" />

          予約センターから登録されたファイル
          <table class="table table-bordered table-sm">
            <tr>
              <th>ファイル名</th>
              <th style="width: 200px">登録日</th>
              __IfKanrisya__
              <th style="width: 100px">削除</th>
              __IfKanrisya__
            </tr>
            __IfNoFile__
            <tr>
              <td colspan="3">工事台帳写真はありません。</td>
            </tr>
            __IfNoFile__ __FileLoop__
            <tr>
              <td>__FileKan__</td>
              <td>__Created__</td>
              __IfKanrisya__
              <td>
                <input
                  type="button"
                  value="削除"
                  class="button"
                  onclick="javascript:moveWithKeyAndWork11( 's_kan.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__', __FileCD__ , 2  )"
                />
              </td>
              __IfKanrisya__
            </tr>
            __FileLoop__
          </table>
        </form>

        <!--
スマート工事くんから登録されたファイル
<table class="table table-bordered table-sm">
<tr><th>ファイル名</th>
	<th style="width:200px">登録日</th>
</tr>
__IfNokFile__
<tr><td colspan="3">スマート工事くんから登録された工事台帳写真はありません。 </td></tr>
__IfNokFile__
__kFileLoop__
	<tr>
		<td>__kFileKan__</td>
		<td>__kCreated__</td>
	</tr>
__kFileLoop__
</table>
-->
      </div>
    </div>
    <!--content-all-->

    __SFooter__ __SCopyright__
  </body>
</html>

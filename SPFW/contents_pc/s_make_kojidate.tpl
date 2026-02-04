<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>リニューアル支援</title>

    <!-- BootstrapのCSS読み込み -->
    <link href="../include/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <!-- jQuery読み込み -->
    <script src="../include/js/jquery-3.2.1.min.js"></script>

    <!-- BootstrapのJS読み込み -->
    <script src="../include/bootstrap/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="../css/rnsien.css" />
    <script type="text/javascript" src="../tools.js"></script>
  </head>

  <body>
    __SHeader__

    <div class="content-all">
      <!--content-all-->

      <div class="left-yose">
        <a href="s_make_kanryo.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__"
          >＜戻る</a
        >
      </div>

      <div class="top-menu left-yose">
        <h5>__BukkenName__ __wBuildingName__</h5>
        <h6>仮日程表 部屋一覧</h6>

        以下の仮日程が登録されています。<br />
        修正する必要がある場合は、以下の[修正]ボタンから修正してください。<br />
        枠超えの制御はしていません。修正は慎重にお願いします。<br /><br />

        <form
          action="./s_make_kotei_EXCEL_kari.php?rKey=__rKey__"
          method="POST"
        >
          <input type="hidden" name="editBukkenCD" value="__editBukkenCD__" />
          <input type="hidden" name="editBuildingCD" value="__editBuildingCD__" />
          <input
            type="submit"
            value="登録済のデータで詳細工程表をダウンロードする"
            class="btn btn-primary blue"
          />
        </form>

        <br />
        <form action="#" method="POST" name="mainform">
          <table
            class="table table-bordered table-sm"
            style="font-size: 12px; width: 400px"
          >
            <tr bgcolor="lightgray">
              <th colspan="5">登録済データ</th>
            </tr>
            <tr bgcolor="lightgray">
              <th>No</th>
              <th>部屋番号</th>
              <th>日程</th>
              <th>開始時間</th>
              <th>修正</th>
            </tr>
            __KojiDateLoop__
            <tr>
              <th bgcolor="lightgray">__No__</th>
              <td>__UserCD__</td>
              <td>__TimeFromDate__</td>
              <td>__TimeFromTime__</td>
              <td>
                <button
                  type="button"
                  onclick="javascript:move('s_make_kojidate_confirm.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__&KojiDateCD=__KojiDateCD__')"
                >
                  修正
                </button>
              </td>
            </tr>
            __KojiDateLoop__
          </table>
        </form>
      </div>
    </div>
    <!--content-all-->
    __SFooter__ __SCopyright__
  </body>
</html>

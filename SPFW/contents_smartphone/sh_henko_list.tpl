<!DOCTYPE html>
<html lang="ja">

<head>


  <title>__TITLENAME__</title>
  <link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <script src="./include/js/jquery-3.2.1.min.js"></script>
  <script src="./include/bootstrap/js/bootstrap.min.js"></script>



  <style>
    @media (max-width: 768px) {
      .mintitle {
        font-size: 1.2em;
      }
      .table td, .table th {
        font-size: 0.9em;
        padding: 8px;
      }
      .btn {
        width: 100%;
        margin-bottom: 10px;
      }
    }
  </style>

  <script>
    $(function () {
      $(".TaioLog").click(function () {
        var currentcontent = $(this).closest("td").find(".TaioLogs").val();
        var currentcd = $(this).closest("tr").find("[name='FormCD']").val();
        $.ajax({
          type: "POST",
          url: "RegistorTaioLog.php",
          data: { "currentcd": currentcd, "TaioLog": currentcontent },
        }).done(function (data, textStatus, jqXHR) {
          alert("対応ログを更新しました。")
        }).fail(function (jqXHR, textStatus, errorThrown) {
          alert("対応ログを更新出来ませんでした。");
        });
      });
    });
  </script>
</head>

<body>
  __SHeader__

  <div class="content-all">
    <br>
    <div class="left-yose mb-3">
      <a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__" class="btn btn-info btn-block">メニュー</a>
    </div>

    <div class="table-responsive">
      <form action="s_kihon_finish.php" method="POST" name="mainform" class="form-horizontal">
        <input type="hidden" name="editBukkenCD" value="__editBukkenCD__" />
        <input type="hidden" name="rKey" value="__rKey__" />
        <input type="hidden" name="work" value="1" />

        <p class="mintitle">
        <table class="table toilist2">
          <tr>
            <th>部屋番号
              <a href="./sh_henko_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&OrderBy=ID">▼</a>
              <a href="./sh_henko_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&OrderBy=ID_Desc">▲</a>
            </th>
            <th colspan="2">日程
              <a href="./sh_henko_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&OrderBy=TimeFrom">▼</a>
              <a href="./sh_henko_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&OrderBy=TimeFrom_Desc">▲</a>
            </th>

            <th>メモ</th>
            <th>受付有無</th>
            <th>受付担当</th>
          </tr>
          __ResidentsFormLoop__
          <tr>
            <input type="hidden" value="__FormCD__" name="FormCD">
            <td>__ID__</td>
            <td>__TimeFromDate__</td>
            <td>__TimeFromTime__</td>

            <td>__Memo__</td>
            <td>__DispReply__</td>
            <td>__DispUpdater__</td>
          </tr>
          __ResidentsFormLoop__
        </table>
      </form>
    </div>
  </div>
  __SFooter__ __SCopyright__
</body>

</html>

<!DOCTYPE html>
<html lang="ja">

<head>
  <title>__TITLENAME__</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<!--最小限のビューポート設定-->
	<meta name="viewport" content="width=device-width">
	<meta name="format-detection" content="telephone=no">

  
  <!-- BootstrapのCSS読み込み -->
  <link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

  <!-- jQuery読み込み -->
  <script src="./include/js/jquery-3.2.1.min.js"></script>

  <!-- BootstrapのJS読み込み -->
  <script src="./include/bootstrap/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="./css/rnsien.css">


  <script src="./tools.js"></script>

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


</head>

<body>


  <div class="content-all">
    <br>
    <div class="left-yose mb-3">
      <a href="s_d2_search.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__" class="btn btn-success">メニュー</a>
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
            <!-- <th>名前</th>
            <th>連絡先</th> -->
            <th>メモ</th>
            <th>完了書</th>
            <!-- <th>受付有無</th>
            <th>受付担当</th> -->
          </tr>
          __ResidentsFormLoop__
          <tr __KanryosyoColor__ >
            <input type="hidden" value="__FormCD__" name="FormCD">
            <td>__ID__</td>
            <td>__TimeFromDate__</td>
            <td>__TimeFromTime__</td>
            <!-- <td>__LastName__</td>
            <td>__TEL__</td> -->
            <td>__Memo__</td>
            <td align="center">


              __Kanryosyo__

            </td>
            <!-- <td>__DispReply__</td>
            <td>__DispUpdater__</td> -->
          </tr>
          __ResidentsFormLoop__
        </table>
      </form>
    </div>
  </div>
  __SFooter__ __SCopyright__
</body>

</html>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>__TITLENAME__</title>
  <link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="./css/rnsien.css" />
  <script src="./include/js/jquery-3.2.1.min.js"></script>
  <script src="./include/bootstrap/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="./css/scroll-hint.css">
  <script src="./include/js/scroll-hint.min.js"></script>
  <style>
    @media (max-width: 768px) {
      .table td, .table th {
        font-size: 0.9em;
        padding: 8px;
        white-space: nowrap;
      }
      .scroll-hint-icon{
        top:50vh;
      }
    }
    .signed{
      background-color:#cfe2f3;
    }
    .declined{
      background-color:#c1c3c5;
    }
    table th{
      text-align:center;
    }
    #srch_date{
      width: 70px;
      margin-bottom: 0;
      padding: .2rem .5rem;
    }
    .mintitle{
      margin:5px 0;
      font-size:14px;
    }
    .search_part{
      display:flex;
      flex-direction:row;
      flex-wrap:wrap;
      align-items:center;
      margin-left:10px;
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
  <div class="container">
      <div class="row">
          <div class="col-12 text-center">
              __SHeaderKanri2__
          </div>
      </div>
  </div>
  <div class="container">
      <form action="sh_henko_list.php" method="post">
      <input type="hidden" name="editBukkenCD" value="__editBukkenCD__" />
      <input type="hidden" name="editBuildingCD" value="__editBuildingCD__" />
      <input type="hidden" name="rKey" value="__rKey__" />

      <div class="row mt-3">
          <div class="col-12">
            <div class="left-yose d-flex align-items-center flex-wrap">
              <a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&m=__m__" class="btn btn-info mb-1 mr-1">メニュー</a>
              __IfWorker__
              <a href="s_kihon_form.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&m=__m__" class="btn btn-dark-blue mb-1 mr-1">物件基本情報</a>
              __IfShowSchedule__
              <a href="sh_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&m=__m__&m=__m__" class="btn btn-blue mb-1 mb-1 mr-1">日程変更（TEL受付）</a>
              __IfShowSchedule__			
              <a href="s_kanryo.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&m=__m__" class="btn btn-dark-blue mb-1 mr-1">完了報告</a>
              __IfWorker__
            </div>
            __IfBuildingExist__
            <div class="left-yose d-flex align-items-center flex-wrap">
              <ul class="nav nav-tabs mb-1 building_nav">
                <li class="nav-item">
                  <a class="nav-link __mainNaviClass__" href="sh_henko_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">__wBuildingName__</a>
                </li>
                __BuildingLoop__
                <li class="nav-item">
                  <a class="nav-link __naviClass__" href="sh_henko_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__BuildingCD__">__BuildingName__</a>
                </li>
                __BuildingLoop__
              </ul>		
              <a href="s_henko_download.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__" class="btn btn-brown mb-1 ml-1">ダウンロード</a>
              <div class="search_part">
                階数:<select name="wKaidaka" class="mr-3">
                <option value=""></option>
                __KaidakaLoop__
                  <option value="__aKaidaka__" __aKaidakaSelected__>__aKaidaka__</option>
                __KaidakaLoop__
                </select>
                日程:
                <input type="text" id="srch_date" name="srchDate" value="__srchDate__" class="form-control ml-1">
                <button type="submit" class="btn btn-dark-blue mb-1 ml-1">絞り込み</button>
              </div>
            </div>
            __IfBuildingExist__
            __IfBuildingNotExist__
            <div class="left-yose d-flex align-items-center flex-wrap">
              <a href="s_henko_download.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__" class="btn btn-brown mb-1">ダウンロード</a>
              <div class="search_part">
                階数:<select name="wKaidaka" class="mr-3">
                <option value=""></option>
                __KaidakaLoop__
                  <option value="__aKaidaka__" __aKaidakaSelected__>__aKaidaka__</option>
                __KaidakaLoop__
                </select>
                日程:
                <input type="text" id="srch_date" name="srchDate" value="__srchDate__" class="form-control ml-1">
                <button type="submit" class="btn btn-dark-blue mb-1 ml-1">絞り込み</button>
              </div>  
            </div>
            __IfBuildingNotExist__

          </div>
      </div>
      </form>
  </div>
  <div class="container">
      <div class="row">
          <div class="col-12">
            <div class="js-scrollable">
              <form action="s_kihon_finish.php" method="POST" name="mainform" class="form-horizontal">
                <input type="hidden" name="editBukkenCD" value="__editBukkenCD__" />
                <input type="hidden" name="editBuildingCD" value="__editBuildingCD__" />
                <input type="hidden" name="rKey" value="__rKey__" />
                <input type="hidden" name="work" value="1" />

                <div class="mintitle">
                __sAMPMInfo__
                </div>
                <table class="table toilist2">
                  <tr>
                    <th width="9%" nowrap>部屋番号
                      <a href="./sh_henko_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__&OrderBy=ID">▼</a>
                      <a href="./sh_henko_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__&OrderBy=ID_Desc">▲</a>
                    </th>
                    <th width="9%" nowrap>日程
                      <a href="./sh_henko_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__&OrderBy=TimeFrom">▼</a>
                      <a href="./sh_henko_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__&OrderBy=TimeFrom_Desc">▲</a>
                    </th>
                    <th width="8%">時間帯</th>
                    <th width="8%">時間指定</th>
                    __IfShowHeader1__
                    <th width="12%">名前</th>
                    <th width="15%">連絡先</th>
                    __IfShowHeader1__
                    <th>メモ</th>
                    <th width="8%">受付有無</th>
                    __IfShowHeader2__
                    <th width="8%">受付担当</th>
                    <th width="10%">ステータス</th>
                    <th width="14%">最終更新日時</th>
                    __IfShowHeader2__
                  </tr>
                  __ResidentsFormLoop__
                  <tr class="__RowClass__">
                    <input type="hidden" value="__FormCD__" name="FormCD">
                    <td>__ID__</td>
                    <td>__TimeFromDate__</td>
                    <td>__TimeFromTime__</td>
                    <td>__TimeExact__ __TimeMeaning__</td>
                    __IfShowColumn1__
                    <td>__LastName__</td>
                    <td>__TEL__</td>
                    __IfShowColumn1__
                    <td>__Memo__</td>
                    <td>__DispReply__</td>
                    __IfShowColumn2__
                    <td>__DispUpdater__</td>
                    <td align="center">__Status__</td>
                    <td>__Updated__</td>
                    __IfShowColumn2__
                  </tr>
                  __ResidentsFormLoop__
                </table>
              </form>
            </div>

          </div>
      </div>
  </div>
  <div class="container">
      <div class="row">
          <div class="col-12">
            __SFooter__ __SCopyright__
          </div>
      </div>
  </div>
</body>
<script>
  new ScrollHint('.js-scrollable');
</script>
</html>

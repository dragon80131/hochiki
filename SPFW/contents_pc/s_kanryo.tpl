<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="format-detection" content="telephone=no">
  <title>__TITLENAME__</title>
	<link rel="stylesheet" type="text/css" href="css/common.css">
	<link rel="stylesheet" type="text/css" href="css/style_okyakusama.css">
	<!--最小限のビューポート設定-->
  <!-- BootstrapのCSS読み込み -->
  <link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="/kotei/css/kotei.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="./css/rnsien.css" />
  <link href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous" rel="stylesheet" />
  <!-- jQuery読み込み -->
  <script src="./include/js/jquery-3.2.1.min.js"></script>
  <script src="./tools.js"></script>
  <style>
    .tb_pc_show{
      display:table-cell;
    }
    .tb_pc_hide{
      display:none;
    }
    @media (max-width: 768px) {
      .tb_pc_show{
        display:none;
      }
      .tb_pc_hide{
        display:table-cell;
      }
    }

    @media (max-width: 768px) {
      .table td, .table th {
        font-size: 0.9em;
        padding: 8px;
      }
      .id_col{
        text-align:center;
      }

    }

    .main_content{
      width:fit-content;
      margin-left:auto;
      margin-right:auto;
    }    

    #loading-mask {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.4);
      z-index: 9999;
      display: none; /* Hidden by default */
    }

    .spinner {
      width: 50px;
      height: 50px;
      border: 6px solid #fff;
      border-top-color: transparent;
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

  </style>
<!-- 
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
  </style> -->


</head>

<body>
  <div class="container">
      <div class="row">
          <div class="col-12 text-center">
              __SHeaderKanri2__
          </div>
      </div>
  </div>
  <div class="main_content">
    <div class="container-fluid">
      <form action="s_kanryo.php" method="post">
        <input type="hidden" name="editBukkenCD" value="__editBukkenCD__" />
        <input type="hidden" name="editBuildingCD" value="__editBuildingCD__" />
        <input type="hidden" name="rKey" value="__rKey__" />

        <div class="row mt-3">
            <div class="col-12">
              <div class="d-flex align-items-center flex-wrap">
                <a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&m=__m__" class="btn btn-info mb-1 mr-1">メニュー</a>
                __IfWorker__
                <a href="s_kihon_form.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&m=__m__" class="btn btn-dark-blue mb-1 mr-1">物件基本情報</a>
                __IfShowSchedule__
                <a href="sh_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&m=__m__" class="btn btn-blue mb-1 mr-1">日程変更（TEL受付）</a>
                __IfShowSchedule__			
                <a href="sh_henko_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&m=__m__" class="btn btn-blue mb-1 mr-1">作業工程表</a>
                __IfWorker__
                __IfBuildingNotExist__
                <a href="s_kanryo_download.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__" class="btn btn-brown mb-1">ダウンロード</a>
                &nbsp;
                階数:<select name="wKaidaka">
                <option value=""></option>
                __KaidakaLoop__
                  <option value="__aKaidaka__" __aKaidakaSelected__>__aKaidaka__</option>
                __KaidakaLoop__
                </select>
                <button type="submit" class="btn btn-dark-blue mb-1 ml-1">絞り込み</button>
                __IfBuildingNotExist__
              </div>
              __IfBuildingExist__
              <div class="d-flex align-items-center flex-wrap">
                <ul class="nav nav-tabs building_nav mb-1">
                  <li class="nav-item">
                    <a class="nav-link __mainNaviClass__" href="s_kanryo.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">__wBuildingName__</a>
                  </li>
                  __BuildingLoop__
                  <li class="nav-item">
                    <a class="nav-link __naviClass__" href="s_kanryo.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__BuildingCD__">__BuildingName__</a>
                  </li>
                  __BuildingLoop__
                </ul>		
                <a href="s_kanryo_download.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__" class="btn btn-brown mb-1 ml-1">ダウンロード</a>
                &nbsp;
                階数:<select name="wKaidaka">
                <option value=""></option>
                __KaidakaLoop__
                  <option value="__aKaidaka__" __aKaidakaSelected__>__aKaidaka__</option>
                __KaidakaLoop__
                </select>
                <button type="submit" class="btn btn-dark-blue mb-1 ml-1">絞り込み</button>
              </div>
              __IfBuildingExist__

            </div>
        </div>
      </form>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
              <div class="table-responsive">
                <form action="s_kihon_finish.php" method="POST" name="mainform" class="form-horizontal">
                  <input type="hidden" name="editBukkenCD" value="__editBukkenCD__" />
                  <input type="hidden" name="editBuildingCD" value="__editBuildingCD__" />
                  <input type="hidden" name="rKey" value="__rKey__" />
                  <input type="hidden" name="work" value="1" />

                  <p class=""></p>
                  <table border="1">
                    <tr>
                      <th width="20%" class="text-center text-nowrap tb_pc_show">部屋番号
                        <a href="./s_kanryo.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__&OrderBy=ID">▼</a>
                        <a href="./s_kanryo.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__&OrderBy=ID_Desc">▲</a>
                      </th>
                      <th width="10%" class="text-center">避難</th>
                      <th width="30%" class="text-center">完了書受領日時</th>
                      <th width="20%" class="text-center text-nowrap tb_pc_hide">部屋番号
                        <a href="./s_kanryo.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__&OrderBy=ID">▼</a>
                        <a href="./s_kanryo.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__&OrderBy=ID_Desc">▲</a>
                      </th>
                      <!-- <th>名前</th>
                      <th>連絡先</th> -->
                      <th width="30%" class="text-center text-nowrap">完了書</th>
                      <!-- <th>受付有無</th>
                      <th>受付担当</th> -->
                      <th width="10%" class="text-center">削除</th>
                    </tr>
                    __ResidentsFormLoop__
                    <tr __KanryosyoColor__ >
                      <input type="hidden" value="__FormCD__" name="FormCD">
                      <td class="id_col tb_pc_show">__ID__</td>
                      <td class="id_col" align="center"><input type="checkbox" class="form-input" __RefugeFlgChecked__ onchange="notifyRefugeFlg(this, '__ID__');"></td>
                      <td class="text-center">__Created__</td>
                      <td class="id_col tb_pc_hide">__ID__</td>
                      <td align="center">


                        __Kanryosyo__

                      </td>
                      <!-- <td>__DispReply__</td>
                      <td>__DispUpdater__</td> -->
                      <td align="center">
                      __KanryosyoDel__
                      </td>
                    </tr>
                    __ResidentsFormLoop__
                  </table>
                </form>
              </div>

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
  <div id="loading-mask">
    <div class="spinner"></div>
  </div>  
</body>
<script>
  function del_sign(page){
    if (window.confirm("削除しますか？")) {
      document.location.href = page;
    }
  }

  function notifyRefugeFlg(obj, UserID){
    let wRefugeFlg = 0;
    if(obj.checked)
      wRefugeFlg = 1;

    $("#loading-mask").show();
    $.ajax({
      type: "POST",
      url: "change_refuge_flg.php",
      data: { 'editBukkenCD': '__editBukkenCD__', 'editBuildingCD': '__editBuildingCD__', 'rKey': '__rKey__', 'UserID': UserID, 'RefugeFlg': wRefugeFlg },
      success: function (data) {
        $("#loading-mask").fadeOut();
      },
      complete: function () {
        $("#loading-mask").fadeOut();
      }
    });
  }
</script>
</html>

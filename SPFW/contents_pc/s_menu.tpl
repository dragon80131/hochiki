<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>__TITLENAME__</title>

  <!-- BootstrapのCSS読み込み -->
  <link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://app5.489501.jp/kotei/css/kotei.css" rel="stylesheet" />
  <link href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous" rel="stylesheet" />

  <!-- jQuery読み込み -->
  <script src="./include/js/jquery-3.2.1.min.js"></script>
  <!-- BootstrapのJS読み込み -->
  <script src="./include/bootstrap/js/bootstrap.min.js"></script>

  <link rel="stylesheet" type="text/css" href="./css/rnsien.css" />
  <link rel="stylesheet" type="text/css" href="./css/kotei.css" />
  <script type="text/javascript" src="tools.js"></script>
  <!--datepicker-->
  <link href="css/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet" />
  <link href="css/jquery-ui-1.12.1/jquery-ui.structure.min.css" rel="stylesheet" />
  <link href="css/jquery-ui-1.12.1/jquery-ui.theme.min.css" rel="stylesheet" />
  <script src="js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
  <script src="js/jquery-ui/datepicker-ja.js"></script>

  <script src="./js/vue.js"></script>


  <style>
    .ui-dialog-buttonset {
      width: 100%;
      display: flex;
      display: -webkit-flex;
      text-align: center;
      justify-content: space-around;
      -webkit-justify-content: space-around;
    }

    /* .app{
      margin-right:auto; 
      margin-left:auto;
    } */

    .main_content{
      width:fit-content;
      margin-left:auto;
      margin-right:auto;
    }
    ul.menu_list > li{
      position:relative;
    }
    ul.menu_list > li.notcompleted::after{
      content: '';
      width: 14px;
      height: 14px;
      border-radius: 10px;
      background-color: #fb1a1a;
      position: absolute;
      right: -5px;
      top: -5px;
      z-index: 1;
      border: solid 1px #fff;
    }
  </style>
  <script type="text/javascript">
    $(function() {
      $(document).on("click", "#IraiButton", function() {
        $("#div3").dialog("open");

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
    <div class="main_content">
      <br>
      <div class="container-fluid">
          <div class="row">
              <div class="col-12">
                  <a href="s_search.php__QUERY__&m=__m__" class="btn btn-info">トップへ</a>
                  <!-- 物件CD：__editBukkenCD__　　<br /><br /> -->
              </div>
          </div>
          <div class="row mt-3">
          __IfDeveloper__
              <div class="col-12 col-lg-10">
          __IfDeveloper__

        __IfWorker__
              <div class="col-12 col-lg-12">
        __IfWorker__
        
                <table class="table table-bordered table-sm" border="1">
                  <tr>
                    <th style="width: 140px; font-weight: normal; background-color: #c7c7c7">物件名</th>
                    <td>__BukkenName__</td>
                  </tr>
                  <tr>
                    <th style="font-weight: normal; background-color: #c7c7c7">総住戸数・棟数</th>
                    <td>総住戸数:__TotalKosu__ &nbsp;&nbsp;&nbsp; 棟数:__CountKosu__</td>
                  </tr>
                  <tr>
                    <th style="font-weight: normal; background-color: #c7c7c7">作業名称</th>
                    <td>__SagyoName__</td>
                  </tr>
                  <!-- <tr>
                    <th style="font-weight: normal; background-color: #c7c7c7">進捗</th>
                    <td>__StatusInfo__</td>
                  </tr> -->

                  <tr>
                    <th style="font-weight: normal; background-color: #c7c7c7">住所</th>
                    <td>__Address__</td>
                  </tr>

                  <tr>
                    <th style="font-weight: normal; background-color: #c7c7c7">依頼会社</th>
                    <td>__ClientName__</td>
                  </tr>

                  <tr>
                    <th style="font-weight: normal; background-color: #c7c7c7">担当者</th>
                    <td>__TantoCD1Name__　__TantoCD2Name__　__TantoCD3Name__　__TantoCD4Name__　__TantoCD5Name__</td>
                  </tr>

                  <tr>
                    <th style="font-weight: normal; background-color: #c7c7c7">備考欄</th>
                    <td style=" white-space: pre-wrap;">__Biko__</td>
                  </tr>
                </table>

              </div>
          </div>
      </div>
    <form action="#" method="POST" name="mainform">
      <input type="hidden" id="editBukkenCD" name="editBukkenCD" value="__editBukkenCD__" />
      <input type="hidden" name="rKey" value="__rKey__" />
      __IfDeveloper__
      <div class="container-fluid">
          <div class="row">
              <div class="col-12">
                <div class="content-left" id="manager_menulist">
                  <div class="menu_box">
                    <div class="menu_midashi">
                      <!--<span class="icon2"></span>-->1.作業準備
                    </div>

                    <ul class="menu_list bukken_info">
                      <li class="green">
                        <a href="#" class="__check_bukken__" onclick="javascript:move('s_kihon_form.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">物件基本情報</a>
                      </li>
                    </ul>



                    <ul class="menu_list IsBouka" >
                      <li class="menu_single __IfTempSave__ notcompleted __IfTempSave__ ">
                        <a href="#" onclick="javascript:move('./doc/s_make_kanryo2.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">作業日程登録</a>
                      </li>
                    </ul>

                          </div>
                  <div class="menu_box">
                    <div class="menu_midashi">
                      2.入居者様対応
                    </div>
                    <ul class="menu_list regist_format">
                      <li class="green">
                        <a href="#" class="__check_bukken__" onclick="javascript:move('s_format.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&menu=1')">案内資料生成</a>
                      </li>
                    </ul>
                  <!--
                    <ul class="menu_list">
                      <li class="green">
                        <a href="#" onclick="javascript:move('s_upload_file.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">関連資料登録</a>
                      </li>
                    </ul>
                  -->

                  <ul class="menu_list manage_resident_require">
                    <li class="menu_single">
                      <a href="#" onclick="javascript:move('sh_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&menu=1')">日程変更（TEL受付）</a>
                    </li>
                  </ul>


                  </div>


          <!--
                  <div class="menu_box">
                    <div class="menu_midashi">
                      2.作業準備
                    </div>



                    <ul class="menu_list IsBouka" >
                      <li class="menu_single">
                        <a href="#" onclick="javascript:move('./s_date_bousai.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">作業日程登録(特別班)</a>
                      </li>
                    </ul>


                    <ul class="menu_list download_document">
                      <li class="green">
                        <a href="#" onclick="javascript:move('s_haihu_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">配布資料DL</a>
                      </li>
                    </ul>


                  </div>
          -->
                  <div class="menu_box">
                    <div class="menu_midashi">
                      <!--<span class="icon3"></span>-->3.状況確認
                    </div>




                    <!-- <ul class="menu_list manage_resident_require">
                      <li class="green">
                        <a href="#" onclick="javascript:move('s_Taio_List.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">問合せ状況</a>
                      </li>
                    </ul> -->

                    <ul class="menu_list">
                      <li class="green">
                        <a href="#" onclick="javascript:move('sh_henko_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">作業工程表</a>
                      </li>
                    </ul>

                    <ul class="menu_list">
                      <li class="menu_single">
                        <!-- <a href="#" onclick="javascript:move('s_upload_file_for_report.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">完了報告</a> -->
                        <a href="#" onclick="javascript:move('s_kanryo.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">完了報告</a>
                      </li>
                    </ul>
                  
                  </div>
                </div>              
              
              </div>
          </div>
      </div>
      __IfDeveloper__
      __IfWorker__
      <div class="container-fluid">
          <div class="row">
              <div class="col-12">
                <div class="menu_box">
                  <ul class="menu_list bukken_info">
                    <li class="menu_single">
                      <a href="#" onclick="javascript:move('s_kihon_form.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&m=__m__')">物件基本情報</a>
                    </li>
                  </ul>
                </div>

                <div class="menu_box">
                __IfShowSchedule__
                  <ul class="menu_list manage_resident_require">
                    <li class="green">
                      <a href="#" onclick="javascript:move('sh_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&m=__m__')">日程変更（TEL受付）</a>
                    </li>
                  </ul>
                __IfShowSchedule__
                  <ul class="menu_list">
                    <li class="green">
                      <a href="#" onclick="javascript:move('sh_henko_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&m=__m__')">作業工程表</a>
                    </li>
                  </ul>
                </div>

                <div class="menu_box">
                  <ul class="menu_list">
                    <li class="menu_single">
                      <a href="#" onclick="javascript:move('s_kanryo.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&m=__m__')">完了報告</a>
                    </li>
                  </ul>
                </div>
              </div>
          </div>
      </div>
      __IfWorker__
    </form>
    
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
              <div id="div3" style="display: none">
                <!-- <p>メッセージ</p> -->
              </div>
              __SFooter__ __SCopyright__
            </div>
        </div>
    </div>
</body>

</html>
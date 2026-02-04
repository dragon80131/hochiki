<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>__TITLENAME__</title>

  <!-- BootstrapのCSS読み込み -->
  <link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

  <!-- jQuery読み込み -->
  <script src="./include/js/jquery-3.2.1.min.js"></script>

  <!-- BootstrapのJS読み込み -->
  <script src="./include/bootstrap/js/bootstrap.min.js"></script>

  <link rel="stylesheet" type="text/css" href="./css/rnsien.css" />
  <script type="text/javascript" src="./tools.js"></script>

  <link href="d2b/css/dropzone.css" type="text/css" rel="stylesheet" />
  <script src="d2b/dropzone.min.js"></script>

  <!--datepicker-->
  <link href="css/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet" />
  <link href="css/jquery-ui-1.12.1/jquery-ui.structure.min.css" rel="stylesheet" />
  <link href="css/jquery-ui-1.12.1/jquery-ui.theme.min.css" rel="stylesheet" />
  <script src="js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
  <script src="js/jquery-ui/datepicker-ja.js"></script>

  <!-- vue -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/vue"></script> -->
  <script src="./js/vue.js"></script>


  <script>
    $(function () {
      $("#myDropzone").dropzone({ url: 'url/post' });
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

  <div id="app">

    <div class="content-all">
      <br />
      <div class="left-yose d-flex align-items-end flex-wrap">
        <a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__" class="btn btn-info mr-1 mb-1">メニュー</a>
        &nbsp;
        <ul class="nav nav-tabs building_nav mb-1">
          __IfBuildingExist__
          <li class="nav-item">
            <a class="nav-link __mainNaviClass__" href="s_format.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">__wBuildingName__</a>
          </li>
          __IfBuildingExist__
          __BuildingLoop__
          <li class="nav-item">
            <a class="nav-link __naviClass__" href="s_format.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__BuildingCD__">__BuildingName__</a>
          </li>
          __BuildingLoop__
        </ul>		
      </div>

      <div class="left-yose">
<br>

        <p>入居者様向けの作業日程の配布資料を登録します。<br>
        </p>

        <div v-if="Same_kiki_sougou_flg != 1">
          <form action="s_kihon_finish.php" method="POST" name="mainform">
            <input type="hidden" name="editBukkenCD" value="__editBukkenCD__" />
            <input type="hidden" name="editBuildingCD" value="__editBuildingCD__" />
            <input type="hidden" name="rKey" value="__rKey__" />
            <input type="hidden" name="work" value="1" />
        
            <p>１．入居者様向けの案内資料を自動生成します。<br></p>

            
            <input type="button" value="案内資料ダウンロード" onClick="return required_check(2,3)" class="btn btn-primary blue" />
            <input type="hidden" name="touroku_btn" id="touroku_btn" value="" />
          </form> 
          <br>

資料掲載情報<br>
QRコード:<img src="__QRCD__" height="100px"><br>
パソコン用URL：__URL__<br>
物件管理番号：__editBukkenCD__<br>
初期パスワード：__wPasswd__<br>

__Koteihyou__<br>

          <br />
          <p>２．上記の資料を修正した場合、<br>
          以下の黄色部分に、ファイルをドラッグ&ドロップし保存することが可能です。<br>
                      </p>
          <table class="table table-borderless" style="background-color: #f3ffd8">
            <tr>
              <td style="width: 450px">
                登録後は画面更新→<a href="s_format.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__">画面更新</a><br />
                <table class="table table-borderless" style="width: 450px; background-color: #ffff77">
                  <tr>
                    <td>
                      <form action="d2b/upload_sf_pics.php?ClientCD=__ClientCD__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__&TenkenKind=1&rKey=__rKey__" class="dropzone"></form>
                    </td>
                  </tr>
                </table>
              </td>
              <td>
                <table class="table table-borderless" style="background-color: #cef4c9">
                  <tr>
                    <td valign="middle">
                      <table>
                      __UploadFileLoop__
                      <tr><td>
                      __Created__&nbsp;&nbsp;__LastName__<br>
                      <a href="__FilePath__">
                      __FileName__</a>
                      </td><td>
                        <!-- 例として静的にファイル名を記述 -->
                        <button  class="btn btn-primary blue"onclick="deleteFile('__FileName__','__UploadFileID__')">削除</button>
                      </td></td></tr>
                      __UploadFileLoop__
                      </table>

                    </td>

                  </tr>
                </table>
              </td>
            </tr>
          </table>
         </div>


      </div>


    </div>

  </div>
  <!--content-all-->

  __SFooter__ __SCopyright__

  <script>
    // 必須項目チェック（1:一時保存 2:登録）
    function required_check(val, kind) {//2,1
      var html = [];

      if (html.length > 0) {
        document.getElementById("ErrorString").innerHTML = "";
        for (let i = 0; i < html.length; i++) {
          document.getElementById("ErrorString").innerHTML += html[i] + "<br>";
        }
        window.scrollTo(0, 0);
      } else {
        // エラーなし かつ 一時保存ボタン
        if (html.length == 0 && val == 1) {
          document.mainform.action = "s_kihon_finish.php";
          document.mainform.submit(true);
        } else if (val == 2) {
          // 登録ボタン
          form_check(val, kind);
        }
      }
    }

    // 登録時必須項目チェック（2:登録）
    function form_check(val, kind) {//2,1
      var html = [];

      if (html.length > 0) {
        document.getElementById("ErrorString").innerHTML = "";
        for (let i = 0; i < html.length; i++) {
          document.getElementById("ErrorString").innerHTML += html[i] + "<br>";
        }
        window.scrollTo(0, 0);
      } else {

        if (kind == 1) {
          //機器の時
          document.getElementById("touroku_btn").value = 1;
          document.mainform.action = "reserve_detail_yotei_EXCEL.php?ClientCD=__ClientCD__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__&TenkenKind=1";
          document.mainform.submit(true);
        } 
        if (kind == 3) {
          //雑排の時
          document.getElementById("touroku_btn").value = 1;
          document.mainform.action = "reserve_detail_yotei_EXCEL.php?ClientCD=__ClientCD__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__&TenkenKind=3";
          document.mainform.submit(true);
        } 
      }
    }

    function deleteFile(fileName,uploadFileID) {
        if (confirm('本当に削除しますか？ ' + fileName )) {
            fetch('delete_file_fromid.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                //body: 'fileName=' + encodeURIComponent(fileName)
                // fileNameとeditBukkenCDの両方をURLエンコードしてbodyに追加
                body: 'UploadFileID=' + encodeURIComponent(uploadFileID)


            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload(); // ページをリロードして更新
            })
            .catch(error => alert('Error:' + error));
        }
    }




  </script>
</body>

</html>
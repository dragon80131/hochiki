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

  </script>
  <style>
    p.list {
      margin-bottom: 5px;
    }

    .round_btn {
      display: inline-block;
      position: relative;
      width: 30px;
      height: 30px;
      border: 2px solid #333;
      /* 枠の調整 */
      border-radius: 50%;
      /* 丸みの度合い */
      background: #fff;
      /* ボタンの背景色 */
    }

    .round_btn::before,
    .round_btn::after {
      content: "";
      position: absolute;
      top: 50%;
      left: 50%;
      width: 3px;
      /* 棒の幅（太さ） */
      height: 22px;
      /* 棒の高さ */
      background: #333;
      /* バツ印の色 */
    }

    .round_btn::before {
      transform: translate(-50%, -50%) rotate(45deg);
    }

    .round_btn::after {
      transform: translate(-50%, -50%) rotate(-45deg);
    }
  </style>
</head>

<body>
  __SHeader__

  <div id="app">

    <div class="content-all">
      <!--content-all-->
      <br>
      <div class="left-yose">
        <a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__" class="btn btn-info">メニュー</a>
      </div>

      <div class="left-yose">
        <p class="mintitle">関連資料登録</p>

        <div>
          <!--物件CD：__editBukkenCD__　-->物件名：__wBukkenName__
        </div><br>
        <span>お知らせ資料、点検報告書以外の関連資料を登録してください。<br><br>
          以下の黄色部分に、関連資料ファイルをドラッグ&ドロップし登録。<br>
          登録後、「画面更新」を選択すると右部分に登録したファイル名が表示されます。<br>
          ファイルを削除する場合は、ファイル名左側の×印を選択してください。</span>

        <table class="table table-borderless" style="background-color:#F3FFD8">
          <tr>
            <td style="width:450px;">
              登録後は画面更新
              <a href="s_upload_file.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">画面更新</a><br>
              <table class="table table-borderless" style="width:450px; background-color:#FFFF77">
                <tr>
                  <td>
                    <form action="d2b/upload_Bukken_File.php?ClientCD=__ClientCD__&editBukkenCD=__editBukkenCD__&FileKind=1" class="dropzone"></form>
                  </td>
                </tr>
              </table>
            </td>
            <td>
              <table>

                <tr v-for="a in array">

                  <td valign="middle">
                    <span class="round_btn" :data-filecd="a.FileCD" @click="onSubmit(a.FileCD)"></span>
                  </td>

                  <td>
                    <a :href="'./kojifile/' + a.ServerFileName" :download="a.RealFileName">{{ a.RealFileName }}</a>
                  </td>

                </tr>


              </table>
            </td>
          </tr>
        </table>

      </div>
    </div>

  </div>
  <!--content-all-->

  __SFooter__ __SCopyright__

  <script>
    // 必須項目チェック（1:一時保存 2:登録）
    function required_check(val, kind) {
      var html = [];

      if (html.length > 0) {
        document.getElementById("ErrorString").innerHTML = "";
        for (let i = 0; i < html.length; i++) {
          document.getElementById("ErrorString").innerHTML +=
            html[i] + "<br>";
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
    function form_check(val, kind) {
      var html = [];

      if (html.length > 0) {
        document.getElementById("ErrorString").innerHTML = "";
        for (let i = 0; i < html.length; i++) {
          document.getElementById("ErrorString").innerHTML +=
            html[i] + "<br>";
        }
        window.scrollTo(0, 0);
      } else {
        if (kind == 1) {//機器の時
          document.getElementById("touroku_btn").value = 1;
          document.mainform.action = "s_format_Excel.php?ClientCD=__ClientCD__&editBukkenCD=__editBukkenCD__&TenkenKind=1";
          document.mainform.submit(true);
        } else if (kind == 2) {//総合の時
          document.getElementById("touroku_btn2").value = 1;
          document.mainform2.action = "s_format_Excel.php?ClientCD=__ClientCD__&editBukkenCD=__editBukkenCD__&TenkenKind=2";
          document.mainform2.submit(true);
        }
      }
    }

    // 必須項目の入力確認(text,selectbox)
    function chk_input(name) {
      if (document.getElementsByName(name)[0].value != "") {
        return true;
      }
      return false;
    }

    // 必須項目のチェック項目の入力確認(radio,checkbox)
    function chk_checked(name) {
      for (let i = 0; i < document.getElementsByName(name).length; i++) {
        if (document.getElementsByName(name)[i].checked) {
          return true;
        }
      }
      return false;
    }



    // Vueの記述 headの中だと動かない
    var app = new Vue({

      el: '#app',
      data: {
        array: __ArrayInfo__
      },
      methods: {

        onSubmit: function (FileCD) {

          var IsDelete = window.confirm('ファイルを削除しますか');
          if (IsDelete) {

            var TargetFileCD = FileCD;

            $.ajax({
              type: "POST",
              url: "s_delete_file_API.php",
              data: { 'TargetFileCD': TargetFileCD },
              success: function (data) {
                alert("ファイル削除完了しました。");
                window.location.reload();
              },
              complete: function () {
              }
            });

          } else { }
        }

      }

    })
  </script>
</body>

</html>
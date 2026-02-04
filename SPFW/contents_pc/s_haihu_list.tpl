<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>物件点検管理</title>
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
  <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  <script src="js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
  <script src="js/jquery-ui/datepicker-ja.js"></script>

  <!-- vue -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/vue"></script> -->
  <script src="./js/vue.js"></script>


  <style>
    p.list {
      margin-bottom: 5px;
    }

    .NextTenkenKind {
      display: none;
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
  <script>

    $(function () {

      $.datepicker.setDefaults({
        dateFormat: 'yy年mm月dd日',
        showButtonPanel: true,
        dayNames: ['日', '月', '火', '水', '木', '金', '土']
      });

      $(".datepicker").datepicker({
        numberOfMonths: 2
      });
    });

    $(document).on('click', '.js-addrow', function () {
      $("#ForCopyData tr").clone().appendTo('.table2');
    });

    $(document).on('click', '.js-deleterow', function () {
      var tablelength = $('.table2 tr').length;
      console.log(tablelength);
      if (tablelength > 2) {
        $(this).closest("tr").remove();
      } else {
        alert("0行には出来ません。");
      }
    });

  </script>
</head>

<body>
  __SHeader__
  <div id=app>

    <div class="content-all">
      <!--content-all-->
      <div class="left-yose">
        <a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜メニュー</a>
      </div>

      <div class="top-menu left-yose">
        物件CD：__editBukkenCD__　物件名：__wBukkenName__<br />

        <form action="s_date_finish.php" method="POST" name="mainform">
          <input type="hidden" name="editBukkenCD" value="__editBukkenCD__" />
          <input type="hidden" name="rKey" value="__rKey__" />
          <input type="hidden" name="work" value="1" />

          <p class="mintitle">配布資料ダウンロード</p>

          <table class="table table-bordered table-sm" style="width: 800px">

            <tr>
              <td>作業月　__KikiTenkenMonth__月</td>
              <td>
                <a class="__NextTenkenKind__" href="#" onclick="javascript:move('s_haifu_Excel.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&TenkenKind=1')">配布資料DL</a>
              </td>
            </tr>

            <!-- <tr>
              <td>総合点検　__SougouTenkenMonth__月</td>
              <td>
                <a class="__NextTenkenKind2__" href="#" onclick="javascript:move('s_haifu_Excel.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&TenkenKind=2')">配布資料DL</a>
              </td>
            </tr> -->

            <tr v-if="BousaiFlg == 1">
              <td>防火点検　__BousaiTenkenMonth__月</td>
              <td>
                <a href="#" onclick="javascript:move('s_haifu_bousai_Excel.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">配布資料DL</a>
              </td>
            </tr>

          </table>

          <div>入居者様のWEB日程受付用のQRコードです。独自資料をご使用の際はこちらのQRコードを保存して資料に貼り付けてご使用下さい。</div>
          <img src="__dataUri__">

          <input type="hidden" name="touroku_btn" id="touroku_btn" value="" />
        </form>
      </div>


      <div style="text-align:left">お知らせ資料をこちらに登録し、保管してください。</div>
      <div style="text-align:left">以下の黄色部分に、関連資料ファイルをドラッグ＆ドロップし登録</div>
      <div style="text-align:left">登録後、「画面更新」を選択すると右部分に登録したファイル名が表示されます。</div>
      <div style="text-align:left">ファイルを削除する場合は、ファイル名左側に×印選択してください。</div>

      <table class="table table-borderless" style="background-color:#F3FFD8">
        <tr>
          <td style="width:450px;">
            登録後は画面更新
            <a href="s_haihu_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">画面更新</a><br>
            <table class="table table-borderless" style="width:450px; background-color:#FFFF77">
              <tr>
                <td>
                  <form action="d2b/upload_Bukken_File.php?ClientCD=__ClientCD__&editBukkenCD=__editBukkenCD__&FileKind=3" class="dropzone"></form>
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
                  <a :href="'./kojifile/' + a.ServerFileName" target="_blank">{{ a.RealFileName }}</a>
                </td>

              </tr>

            </table>

          </td>
        </tr>
      </table>


    </div>

  </div>
  <!--content-all-->

  __SFooter__ __SCopyright__

  <script>
    // 必須項目チェック（1:一時保存 2:登録）
    function required_check(val) {
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
          form_check(val);
        }
      }
    }

    // 登録時必須項目チェック（2:登録）
    function form_check(val) {
      var html = [];

      if (html.length > 0) {
        document.getElementById("ErrorString").innerHTML = "";
        for (let i = 0; i < html.length; i++) {
          document.getElementById("ErrorString").innerHTML +=
            html[i] + "<br>";
        }
        window.scrollTo(0, 0);
      } else {
        alert("作業日程を登録しました。戻って、配布資料をダウンロードしてください");
        document.getElementById("touroku_btn").value = 1; // hiddenに値設定
        document.mainform.action = "s_date.php?work=1&ClientCD=__ClientCD__&editBukkenCD=__editBukkenCD__";
        document.mainform.submit(true);
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
        array: __ArrayInfo__,
        BousaiFlg: __BousaiFlg__,
        $BousaiTenkenMonth: __BousaiTenkenMonth__,
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
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>__TITLENAME__</title>

  <!-- BootstrapのCSS読み込み -->
  <link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="/kotei/css/kotei.css" rel="stylesheet" />
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

  <style>
    .ui-dialog-buttonset {
      width: 100%;
      display: flex;
      display: -webkit-flex;
      text-align: center;
      justify-content: space-around;
      -webkit-justify-content: space-around;
    }
  </style>
  <script type="text/javascript">
    $(function () {
      $("#div3").dialog({
        autoOpen: false, //呼ばれるまで非表示
        modal: true, //モーダル表示
        title: "依頼する点検の種類を選択してください。",
        width: 400, //ダイアログの横幅(px)
        height: 150, //ダイアログの縦幅(px)
        buttons: {
          //ボタン
          機器点検: function () {
            var BukkenCD = $("#editBukkenCD").val();
            $.ajax({
              type: "POST",
              url: "IraiToSekoCompany.php?TenkenKind=1",
              data: { editBukkenCD: BukkenCD },
            })
              .done(function (data, textStatus, jqXHR) {
                alert("施工会社に機器点検日程登録依頼を致しました。");
              })
              .fail(function (jqXHR, textStatus, errorThrown) {
                alert("メールが送信出来ませんでした。");
              });
            $(this).dialog("close");
          },
          総合点検: function () {
            var BukkenCD = $("#editBukkenCD").val();
            $.ajax({
              type: "POST",
              url: "IraiToSekoCompany.php?TenkenKind=2",
              data: { editBukkenCD: BukkenCD },
            })
              .done(function (data, textStatus, jqXHR) {
                alert("施工会社に総合点検日程登録依頼を致しました。");
              })
              .fail(function (jqXHR, textStatus, errorThrown) {
                alert("メールが送信出来ませんでした。");
              });
            $(this).dialog("close");
          },
        },
      });

      $(document).on("click", "#IraiButton", function () {
        $("#div3").dialog("open");

        // if (!confirm('施工会社に日程登録依頼をしますか。')) {
        //   return false;
        // } else {

        //   var BukkenCD = $("#editBukkenCD").val();
        //   $.ajax({
        //     type: "POST",
        //     url: "IraiToSekoCompany.php",
        //     data: { "editBukkenCD": BukkenCD },
        //   }).done(function (data, textStatus, jqXHR) {
        //     // TargetRemove.closest('div').remove();
        //     alert("施工会社に日程登録依頼を致しました。")
        //   }).fail(function (jqXHR, textStatus, errorThrown) {
        //     alert("メールが送信出来ませんでした。");
        //   });
        // }
      });

      //協力業者の場合の制御
      var UserKbn = "__UserKbn__";
      if (UserKbn) {
        if (UserKbn == "3") {
          //協力業者の場合
          $(".bukken_info").hide();
          $(".regist_format").hide();
          // $(".download_document").hide();
          $(".manage_resident_require").hide();
        }
      }
    });
  </script>
</head>

<body>
  __SHeader__
  <br />
  <div class="content-all">
    <div class="left-yose">
      <a href="s_search.php__QUERY__" class="btn btn-info">トップへ</a>
      <!-- 物件CD：__editBukkenCD__　　<br /><br /> -->
    </div>

    <div class="mt-5">
      <div class="left-yose">
        <table class="table table-bordered table-sm" style="width: 500px" border="1">
          <tr>
            <th style="width: 100px; font-weight: normal; background-color: #c7c7c7">物件名</th>
            <td style="width: 300px">__BukkenName__</td>
          </tr>
          <tr>
            <th style="font-weight: normal; background-color: #c7c7c7">住戸数</th>
            <td>__Kosu__</td>
          </tr>
          <tr>
            <th style="font-weight: normal; background-color: #c7c7c7">進捗</th>
            <td>__StatusInfo__</td>
          </tr>

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
            <td>__TantoCD1Name__　__TantoCD2Name__</td>
          </tr>
        </table>
      </div>
    </div>

    <form action="#" method="POST" name="mainform">
      <input type="hidden" id="editBukkenCD" name="editBukkenCD" value="__editBukkenCD__" />
      <input type="hidden" name="rKey" value="__rKey__" />

      <div class="content-left">
        <div class="menu_box">
          <div class="menu_midashi">
            <!--<span class="icon2"></span>-->1.点検準備
          </div>

          <ul class="menu_list bukken_info">
            <li class="green">
              <a href="#" class="__check_bukken__" onclick="javascript:move('s_kihon_form.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">物件基本情報</a>
            </li>
          </ul>

          <ul class="menu_list regist_format">
            <li class="green">
              <a href="#" class="__check_bukken__" onclick="javascript:move('s_format.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">資料フォーマット登録</a>
            </li>
          </ul>

          <!-- <ul class="menu_list">
          <li class="menu_single">
            <a href="#" id="IraiButton">日程登録依頼</a>
          </li>
        </ul> -->

          <ul class="menu_list">
            <li class="green">
              <a href="#" onclick="javascript:move('s_upload_file.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">関連資料登録</a>
            </li>
          </ul>
        </div>

        <div class="menu_box">
          <div class="menu_midashi">
            <!--<span class="icon4"></span>-->2.作業準備
          </div>

          <ul class="menu_list">
            <li class="menu_single">
              <a href="#" onclick="javascript:move('./s_date.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">作業日程登録</a>
            </li>
          </ul>

          <ul class="menu_list download_document">
            <li class="green">
              <!-- <a href="#" onclick="javascript:move('s_haifu_Excel.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">配布資料DL</a> -->
              <a href="#" onclick="javascript:move('s_haihu_list.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">配布資料DL</a>
            </li>
          </ul>
        </div>

        <div class="menu_box">
          <div class="menu_midashi">
            <!--<span class="icon3"></span>-->3.お問い合わせ～完了
          </div>

          <ul class="menu_list manage_resident_require">
            <li class="green">
              <a href="#" onclick="javascript:move('s_Taio_Form.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">お問い合わせ登録</a>
            </li>
          </ul>

          <ul class="menu_list">
            <li class="menu_single">
              <a href="#" onclick="javascript:move('s_Taio_List.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">お問い合わせ一覧</a>
            </li>
          </ul>

          <ul class="menu_list">
            <li class="menu_single">
              <a href="#" onclick="javascript:move('s_upload_file_for_report.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">点検完了報告</a>
            </li>
          </ul>
        </div>
      </div>
    </form>

    <div id="div3" style="display: none">
      <!-- <p>メッセージ</p> -->
    </div>
    __SFooter__ __SCopyright__
  </div>
</body>

</html>
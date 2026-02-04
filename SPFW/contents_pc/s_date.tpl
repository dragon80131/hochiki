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
  <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  <script src="js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
  <script src="js/jquery-ui/datepicker-ja.js"></script>

 <!-- <style>
    p.mintitle {
      font-size: 1.5em;
      position: relative;
      padding: 0.25em 0;
    }

    p.mintitle:after {
      content: "";
      display: block;
      height: 4px;
      background: -webkit-linear-gradient(to right,
          rgb(230, 90, 90),
          transparent);
      background: linear-gradient(to right, rgb(230, 90, 90), transparent);
    }

    p.list {
      margin-bottom: 5px;
    }
  </style>-->
  <script>

    $(function () {

      $.datepicker.setDefaults({
        // dateFormat: 'yy年mm月dd日(DD)',
        // dateFormat: 'yy年mm月dd日',
        showButtonPanel: true,
        dayNames: ['日', '月', '火', '水', '木', '金', '土']
      });

      $(".datepicker").datepicker({
        numberOfMonths: 2,
        showOn: "focus"
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
  <br>
  <div class="content-all">
    <!--content-all-->
    <div class="left-yose">
      <a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__" class="btn btn-info">メニュー</a>
    </div>

    <p class="mintitle">実施日程登録</p>

    <div class="left-yose">
      <!-- <div>物件CD：__editBukkenCD__</div> -->
      <!--
      <div>物件名：__wBukkenName__</div>
      <div>住戸数:__Kosu__戸</div>

      __IfNextKiki__
      <div>機器点検:__KikiTenkenMonth__月　AM:__KikiAMKojiTime__ PM:__KikiPMKojiTime__ 期間:__KikiTenkenKikan__日間</div>
      __IfNextKiki__

      __IfNextSougou__
      <div>総合点検:__SougouTenkenMonth__月　AM:__SougouAMKojiTime__ PM:__SougouPMKojiTime__ 期間:__SougouTenkenKikan__日間</div>
      __IfNextSougou__


      __IfExistDate__
      <div>個別入室住居</div>
      __KojiNitteiLoop__
      <div>
        <span>__Nittei__</span>
        <span>__KobetsuTime__</span>
        <span>__RoomNumber__</span>
      </div>
      __KojiNitteiLoop__

      __IfExistDate__
      <div style="white-space: pre-wrap; margin-top:48px;">__wBukkenMemo__</div>
    -->

      <table class="table table-bordered table-sm row-table">
        <tr>
          <th class="yb">物件名</th>
          <td>__wBukkenName__</td>
        </tr>
        <tr>
          <th class="yb">住戸数</th>
          <td>__Kosu__戸</td>
        </tr>
        __IfNextKiki__
        <tr>
          <th class="yb">機器点検</th>
          <td>__KikiTenkenMonth__月　AM:__KikiAMKojiTime__ PM:__KikiPMKojiTime__ 期間:__KikiTenkenKikan__日間</td>
        </tr>
        __IfNextKiki__
        __IfNextSougou__
        <tr>
          <th class="yb">総合点検</th>
          <td>__SougouTenkenMonth__月　AM:__SougouAMKojiTime__ PM:__SougouPMKojiTime__ 期間:__SougouTenkenKikan__日間</td>
        </tr>
        __IfNextSougou__
        __IfExistDate__
        <tr>
          <th class="yb">個別入室住居</th>
          <td>
            __KojiNitteiLoop__
            <div>
              <span>__Nittei__</span>
              <span>__KobetsuTime__</span>
              <span>__RoomNumber__</span>
            </div>
            __KojiNitteiLoop__
          </td>
        </tr>
        __IfExistDate__
        <tr>
          <th class="yb">作業指示</th>
          <td class="pre-wrap">__wBukkenMemo__</td>
        </tr>
      </table>



    <form action="s_date_finish.php" method="POST" name="mainform">
      <input type="hidden" name="editBukkenCD" value="__editBukkenCD__" />
      <input type="hidden" name="rKey" value="__rKey__" />
      <input type="hidden" name="work" value="1" />

      <table class="table table-bordered table-sm row-table">
        <tr>
          <th class="yb">実施日程</th>
          <td>
            <input type="text" name="wSagyoDate" value="" size=15 placeholder="" class="datepicker" autocomplete="off">
            ～
            <input type="text" name="wSagyoDate2" value="" size=15 placeholder="" class="datepicker" autocomplete="off">
          </td>
        </tr>

        <tr>
          <th class="yb">前回実施日程</th>
          <td>
            <span>__FirstKojiDate__</span>～<span>__LastKojiDate__</span>
          </td>
        </tr>
      </table>
    </div>
      <!-- __IfKobetsu__
        <p class="mintitle">作業日程登録（部屋表記あり）</p>
        <table class="table2 table-bordered table-sm" style="width: 800px">
          <tr>
            <td></td>
            <td></td>
            <td>作業日</td>
            <td>作業時間</td>
            <td>部屋番号</td>
          </tr>

          __KojiNitteiLoop__

          <tr>
            <td>
              <button type="button" class="js-addrow">
                <div class="">+</div>
              </button>
            </td>
            <td>
              <button type="button" class="js-deleterow">
                <div class="">×</div>
              </button>
            </td>
            <td>
              <input type="text" name="wSagyoDateKobetsu[]" class="datepicker" value="__KobetsuDate__">
            </td>
            <td>
              <input type="text" name="wSagyoTimeKobetsu[]" value="__KobetsuTime__">
            </td>
            <td>
              <input type="text" name="wRoomNo[]" value="__RoomNumber__">
            </td>
          </tr>

          __KojiNitteiLoop__

          <tr>
            <td>
              <button type="button" class="js-addrow">
                <div class="">+</div>
              </button>
            </td>
            <td>
              <button type="button" class="js-deleterow">
                <div class="">×</div>
              </button>
            </td>
            <td>
              <input type="text" name="wSagyoDateKobetsu[]" class="datepicker" value="">
            </td>
            <td>
              <input type="text" name="wSagyoTimeKobetsu[]" value="">
            </td>
            <td>
              <input type="text" name="wRoomNo[]" value="">
            </td>
          </tr>
        </table>
        __IfKobetsu__ -->



      <!--<input type=" button" value="一時保存" onClick="required_check(1)" class="btn btn-warning">-->
      <input type="submit" value="実施日程登録" class="btn btn-primary blue" style="margin-top: 10px;">
      <input type="hidden" name="touroku_btn" id="touroku_btn" value="" />
    </form>

    <table style="display:none" id="ForCopyData">
      <tr>
        <td>
          <button type="button" class="js-addrow">
            <div class="">+</div>
          </button>
        </td>
        <td>
          <button type="button" class="js-deleterow">
            <div class="">×</div>
          </button>
        </td>
        <td>
          <input type="text" name="wSagyoDateKobetsu[]" value="" placeholder="" class="datepicker" >
        </td>
        <td>
          <input type="text" name="wSagyoTimeKobetsu[]">
        </td>

        <td>
          <input type="text" name="wRoomNo[]">
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
      /*
      if (!chk_input("wKenmeiNo")) html.push("件名Noが入力されていません。");
      if (!chk_input("wBukkenName")) html.push("物件名が入力されていません。");
      */
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
      /*
      if (!chk_input("wTantoCD")) html.push("担当者が選択されていません。");
      if (!chk_input("wKojiName")) html.push("工事名称が入力されていません。");
      */

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
  </script>
</body>

</html>
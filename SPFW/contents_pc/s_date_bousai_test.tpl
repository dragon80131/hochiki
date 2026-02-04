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

  <style>
    p.list {
      margin-bottom: 5px;
    }
  </style>
  <script>

    $(function () {

      $.datepicker.setDefaults({
        // dateFormat: 'yy年mm月dd日(DD)',
        // dateFormat: 'yy年mm月dd日',
        // showButtonPanel: true,
        dayNames: ['日', '月', '火', '水', '木', '金', '土']
      });

      $(".datepicker").datepicker({
        numberOfMonths: 2,
        // showOn: "focus"
      });

    });

  </script>
</head>

<body>
  __SHeader__

  <div class="content-all">
    <!--content-all-->
    <div class="left-yose">
      <a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__" class="btn btn-info">メニュー</a>
    </div>

    <p class="mintitle">防火実施日程登録</p>
    <form action="s_date_bousai_finish2_test.php" method="POST" name="mainform">

      <div class="left-yose">
        <!-- <div>物件CD：__editBukkenCD__</div> 
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
      <div style="white-space: pre-wrap; margin-top:48px;">__wBukkenMemo__</div>-->

        <table class="table table-bordered table-sm row-table">
          <tr>
            <th class="yb width120">物件名</th>
            <td>__wBukkenName__</td>
          </tr>
          <tr>
            <th class="yb">住戸数</th>
            <td>__Kosu__戸</td>
          </tr>
          <tr>
            <th class="yb">点検月</th>
            <td>__BousaiTenkenMonth__月</td>
          </tr>

          <tr>
            <th class="yb">作業場所</th>
            <td>__WorkPlace__</td>
          </tr>

          <tr>
            <th class="yb">備考</th>
            <td style="white-space: pre-wrap;">__BoukaBikou__</td>
          </tr>
        </table>
      </div>

      <input type="hidden" name="editBukkenCD" value="__editBukkenCD__" />
      <input type="hidden" name="rKey" value="__rKey__" />
      <input type="hidden" name="work" value="1" />
      <div class="left-yose">
        <table class="table table-bordered table-sm row-table">

          <tr>
            <th class="yb">
              <span>作業時間(防火)</span>
            </th>
            <td>
              <div class="br-sp"><span></span>
                <input type="text" name="BousaiKojiTime" id="BousaiKojiTime" value="__BousaiKojiTime__" autocomplete="off">
              </div>
            </td>
          </tr>

          <tr>
            <td class="yb width120">作業日程</td>
            <td id="SagyouNittei">

              <div class="InputRows mb-2">
                <input type="text" name="BousaiStartDate" value="" size=20 class="datepicker" autocomplete="off">~
                <input type="text" name="BousaiEndDate" value="" size=20 class="datepicker" autocomplete="off">
              </div>

            </td>
          </tr>

          <tr>
						<td class="yb">最終作業登録者</td>
						<td>
							<div>__BousaiLastUpdated__　__name__</div>
						</td>
					</tr>

          <tr>
            <td class="yb">前回作業日程</td>
            <td>
              <span>__BousaiStartDate__</span>～<span>__BousaiEndDate__</span>
            </td>
          </tr>

        </table>
      </div>
      <input type="submit" value="作業情報登録" class="btn btn-primary blue" style="margin-top: 10px;">
      <input type="hidden" name="touroku_btn" id="touroku_btn" value="" />
    </form>

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
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title></title>

  <!-- BootstrapのCSS読み込み -->
  <link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

  <!-- jQuery読み込み -->
  <script src="./include/js/jquery-3.2.1.min.js"></script>

  <!-- BootstrapのJS読み込み -->
  <script src="./include/bootstrap/js/bootstrap.min.js"></script>

  <link rel="stylesheet" type="text/css" href="./css/rnsien.css" />
  <script type="text/javascript" src="./tools.js"></script>

  <!--datepicker-->
  <link href="css/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet" />
  <link href="css/jquery-ui-1.12.1/jquery-ui.structure.min.css" rel="stylesheet" />
  <link href="css/jquery-ui-1.12.1/jquery-ui.theme.min.css" rel="stylesheet" />
  <script src="js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
  <script src="js/jquery-ui/datepicker-ja.js"></script>

  <script>

  </script>
  <style>
    /* 20190709 add 
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
    */
    p.list {
      margin-bottom: 5px;
    }
  </style>
</head>

<body>
  __SHeader__

  <div class="content-all">
    <div class="left-yose">
      <a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__" class="btn btn-info">メニュー</a>
    </div>
    <br>
    <div class="left-yose">

      <form action="s_kihon_finish.php" method="POST" name="mainform">
        <input type="hidden" name="editBukkenCD" value="__editBukkenCD__" />
        <input type="hidden" name="rKey" value="__rKey__" />
        <input type="hidden" name="work" value="1" />

        <!-- <font color="red">※は入力必要項目です。</font><br /> -->
        <!-- <span id="ErrorString" style="color: red"></span> -->
        <!-- __IfError____ErrorLoop__ -->
        <!-- <font color="red">__ErrorStrings__<br /></font> -->
        <!-- __ErrorLoop____IfError__ -->

        <!-- <p class="mintitle">お問い合わせありがとうございます。</p> -->
        <p>日程設定を変更致しました。</p>



      </form>

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
        document.getElementById("touroku_btn").value = 1; // hiddenに値設定

        document.mainform.action = "s_kihon_finish.php";
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
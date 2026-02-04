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
  </style>
</head>

<body>
  __SHeader__

  <div class="content-all">
    <div class="left-yose">
      <a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜メニュー</a>
    </div>

    <div class="top-menu left-yose">

      <form action="s_kihon_finish.php" method="POST" name="mainform">
        <input type="hidden" name="editBukkenCD" value="__editBukkenCD__" />
        <input type="hidden" name="rKey" value="__rKey__" />
        <input type="hidden" name="work" value="1" />

        __IfError____ErrorLoop__
        <font color="red">__ErrorStrings__<br /></font>
        __ErrorLoop____IfError__

        <p class="mintitle" style="font-size:32px;">作業終了報告が完了しました。</p>

        <!-- <div class="form-group">
          <label for="" style="font-size:24px;">部屋番号</label>
          <input type="text" name="RoomNo" class="form-control" placeholder="例：201">
        </div>

        <div class="form-group">
          <label for="" style="font-size:24px;">名前</label>
          <input type="text" name="Name" class="form-control" placeholder="例：山田太郎" />
        </div>

        <div class="form-group">
          <label for="" style="font-size:24px;">連絡先</label>
          <input type="text" name="TEL" class="form-control" placeholder="例：080-9999-9999">
        </div>

        <div class="form-group">
          <label for="" style="font-size:24px;">日程のご要望</label>
          <textarea name="Contents" rows="3" cols="50" class="form-control" placeholder="例：工事日を10月1日に変更してもらいたいです。"></textarea>
        </div>

        <div class="mt-3">
          <div>物件名：__wBukkenName__</div>
        </div> -->

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


  </script>
</body>

</html>
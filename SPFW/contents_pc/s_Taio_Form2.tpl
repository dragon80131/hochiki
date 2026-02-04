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

    .NextTenkenKind {
      display: none;
    }
  </style>
  <script>
    $(function () {
      $(document).on('change', 'input[name="IsInRoom"]', function () {

        var IsInRoom = $('input[name = "IsInRoom"]:checked').val();
        if (IsInRoom == "1") {
          $("#RequestForDate").show();
        } else if (IsInRoom == "2") {
          $("#RequestForDate").hide();
        } else { }
      });

      $('form').submit(function () {

        var selectedval = $("[name=DemandDate] option:selected ");
        var ampm = selectedval.data('ampm');
        var dates = selectedval.data('dates');

        $("<input>", {
          type: 'hidden',
          name: 'ampm',
          value: ampm
        }).appendTo('form');

        $("<input>", {
          type: 'hidden',
          name: 'dates',
          value: dates
        }).appendTo('form');


      });

      $(document).on('change', '[name=DemandDate]', function (e) {

        var currentval = $(this).val();

        if (currentval == "不在") {

          $("#IsNeeded").hide();

        } else {

          $("#IsNeeded").show();

        }
      });



    });


  </script>
</head>

<body>
  __SHeader__

  <div class="content-all">
    <br>
    __IfrKey__
    <div class="left-yose">
      <a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__" class="btn btn-info">メニュー</a>
    </div>
    __IfrKey__


    <div class="left-yose">

      <form action="s_Taio_Finish.php" method="POST" name="mainform">
        <input type="hidden" name="editBukkenCD" value="__editBukkenCD__" />
        <input type="hidden" name="rKey" value="__rKey__" />
        <input type="hidden" name="work" value="1" />

        <!-- <font color="red">※は入力必要項目です。</font><br />
        <span id="ErrorString" style="color: red"></span>
        __IfError____ErrorLoop__
        <font color="red">__ErrorStrings__<br /></font>
        __ErrorLoop____IfError__ -->

        <p class="mintitle">点検日程お問い合わせフォーム</p>
        <div style="color: red">
          <div>点検期間内の日程のご要望をご記入下さい。</div>
          <div>日程以外のご要望等はお電話にて承っております。</div>
          <div>作業期間外の日程変更はお受けしておりません。</div>
          <div>次回の点検作業日にご協力お願いします。</div>
        </div>
        <div>
        </div>
        <div class="form-group">
          <label for="" class="midashi">部屋番号</label>
          <input type="text" name="RoomNo" class="form-control" placeholder="例：201">
        </div>

        <div class="form-group">
          <label for="" class="midashi">名前</label>
          <input type="text" name="Name" class="form-control" placeholder="例：山田太郎" />
        </div>

        <div class="form-group">
          <label for="" class="midashi">連絡先</label>
          <input type="text" name="TEL" class="form-control" placeholder="例：080-9999-9999">
        </div>

        <div class="form-group">
        <label for="" class="midashi">日程について</label>
          <select class="form-control" name="DemandDate" id="DemandDate">

            <option value="">-</option>
            __DatesLoop__
            <option data-ampm="__AMPM__" data-dates="__DatesData__">__Dates__</option>
            __DatesLoop__

            <option value="不在">不在</option>

          </select>

        </div>

        __IfrKey__
        <div class="form-group mt-2" id="RequestForDate">
          <!-- <label for="" style="font-size:24px;">日程のご要望</label> -->
          <label for="" class="midashi">対応ログ</label>
          <textarea name="Contents" rows="3" cols="50" class="form-control" placeholder="例：工事日を10月1日に変更してもらいたいです。"></textarea>
        </div>
        __IfrKey__

        <div>
          <div>
            <div id="IsNeeded">選択頂きました、日程・時間内にお伺いいたします。</div>
            <div>その他お問い合わせにつきましてはお電話にてご連絡下さい。</div>
            <br>
            <div>RKI設備保全㈱</div>
            <div>9時30分～17時(土、日、祝日を除く)</div>
            <div>TEL：03-6809-4100</div>
          </div>
        </div>

        <!--<input type="button" value="一時保存" onClick="required_check(1)" class="btn btn-warning">-->
        <div class="mt-3">
          <input type="submit" value="登録する" class="btn btn-primary blue">
        </div>
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
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
  <script>
    $(function () {


      $('.needornot').change(function () {
        var id = $(this).attr('id');
        var val = $(this).prop("checked");
        var TargetKoumoku = JudgeKoumoku(id);
        ToggleContents(val, TargetKoumoku);
      })

      // $('.needornot').each(function () {
      //   var id = $(this).attr('id');
      //   var val = $(this).prop("checked");
      //   var TargetKoumoku = JudgeKoumoku(id);
      //   ToggleContents(val, TargetKoumoku);
      // })

    });

    function JudgeKoumoku(id) {

      if (id == "check1") {
        var TargetKoumoku = "Zikahou";
      } else if (id == "check1b") {
        var TargetKoumoku = "SPSetsubi";
      } else if (id == "check1c") {
        var TargetKoumoku = "Idoushiki";
      } else if (id == "check1d") {
        var TargetKoumoku = "KasaiTuhou";
      } else if (id == "check1e") {
        var TargetKoumoku = "GasuSetsubi";
      }
      return TargetKoumoku;
    }

    function ToggleContents(val, TargetKoumoku) {
      if (val) {
        $('.' + TargetKoumoku).show();
      } else {
        $('.' + TargetKoumoku).hide();
      }
    }


  </script>
</head>

<body>
  __SHeader__

  <div class="content-all">
    __IfrKey__
    <div class="left-yose">
      <a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜メニュー</a>
    </div>
    __IfrKey__


    <div class="top-menu left-yose">

      <form action="s_Result_Report_Finish.php" method="POST" name="mainform">
        <input type="hidden" name="editBukkenCD" value="__editBukkenCD__" />
        <input type="hidden" name="rKey" value="__rKey__" />
        <input type="hidden" name="work" value="1" />

        <!-- <font color="red">※は入力必要項目です。</font><br />
        <span id="ErrorString" style="color: red"></span>
        __IfError____ErrorLoop__
        <font color="red">__ErrorStrings__<br /></font>
        __ErrorLoop____IfError__ -->

        <p class="mintitle" style="font-size:32px;">作業終了報告</p>

        <div>

          <div class="mb-1">
            <div>作業が終了しましたので、結果を報告致します。</div>

            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="ResultReport" id="" value="1" __ResultReportSelected1__>
              <label class="form-check-label" for="">異常なし</label>
            </div>

            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="ResultReport" id="" value="2" __ResultReportSelected2__>
              <label class="form-check-label" for="">不良箇所あり</label>
            </div>

          </div>

          <div class="form-group mt-2">
            <label for="textarea1">備考</label>
            <textarea id="textarea1" class="form-control" rows="5" name="Biko">__Biko__</textarea>
          </div>


          <div class="form-check">
            <input class="form-check-input needornot" type="checkbox" id="check1" __ZikahouChecked__>
            <label class="form-check-label " for="check1a">自動火災報知器・非常警報・防排煙関係</label>
          </div>

          <div class="Zikahou mt-2 mb-2">

            <div class="ml-4">
              <div class="form-check">
                <input class="form-check-input " type="checkbox" id="check1a" name="ZyushinDengen" __ZyushinDengenChecked__>
                <label class="form-check-label " for="check1a">受信機等の電源（蓄電池含）投入</label>
              </div>
            </div>

            <div class="ml-4">
              <div class="form-check">
                <input class="form-check-input " type="checkbox" id="check1b" name="RendoubanConfirm" __RendoubanConfirmChecked__>
                <label class="form-check-label " for="check1b">連動盤の各スイッチ復旧確認</label>
              </div>
            </div>

            <div class="ml-4">
              <div class="form-check">
                <input class="form-check-input " type="checkbox" id="check1c" name="ZyushinConfirm" __ZyushinConfirmChecked__>
                <label class="form-check-label " for="check1c">受信機の各スイッチ復旧確認</label>
              </div>
            </div>

            <div class="ml-4">
              <div class="form-check">
                <input class="form-check-input " type="checkbox" id="check1d" name="HukuZyushinConfirm" __HukuZyushinConfirmChecked__>
                <label class="form-check-label " for="check1d">副受信機の各スイッチ復旧確認</label>
              </div>
            </div>

            <div class="ml-4">
              <div class="form-check">
                <input class="form-check-input " type="checkbox" id="check1e" name="KeibiCompany" __KeibiCompanyChecked__>
                <label class="form-check-label " for="check1e">警備会社等への連絡</label>
              </div>
            </div>

            <div class="ml-4">
              <div class="form-check">
                <input class="form-check-input " type="checkbox" id="check1f" name="SenyouKairoConfirm" __SenyouKairoConfirmChecked__>
                <label class="form-check-label " for="check1f">専用回路（電源）の確認</label>
              </div>
            </div>

          </div>


          <div class="form-check">
            <input class="form-check-input needornot" type="checkbox" id="check1b" __SPSetsubiChecked__>
            <label class="form-check-label" for="check1b">SP設備、泡消火設備、消火栓関係</label>
          </div>

          <div class="SPSetsubi mt-2 mb-2">

            <div class="ml-4">
              <div class="form-check">
                <input class="form-check-input " type="checkbox" id="check1a" name="PumpConfirm" __PumpConfirmChecked__>
                <label class="form-check-label " for="check1a">ポンプ廻り弁の定位置確認</label>
              </div>
            </div>

            <div class="ml-4">
              <div class="form-check">
                <input class="form-check-input " type="checkbox" id="check1b" name="AirPositionConfirm" __AirPositionConfirmChecked__>
                <label class="form-check-label " for="check1b">圧力空気槽廻り弁の定位置確認</label>
              </div>
            </div>

            <div class="ml-4">
              <div class="form-check">
                <input class="form-check-input " type="checkbox" id="check1c" name="WaterKentiConfirm" __WaterKentiConfirmChecked__>
                <label class="form-check-label " for="check1c">流水検知1次側仕切弁全開確認</label>
              </div>
            </div>

            <div class="ml-4">
              <div class="form-check">
                <input class="form-check-input " type="checkbox" id="check1d" name="WaterShingouConfirm" __WaterShingouConfirmChecked__>
                <label class="form-check-label " for="check1d">流水検知の信号ラインの弁全開確認</label>
              </div>
            </div>

            <div class="ml-4">
              <div class="form-check">
                <input class="form-check-input " type="checkbox" id="check1e" name="AirAtsuryokuConfirm" __AirAtsuryokuConfirmChecked__>
                <label class="form-check-label " for="check1e">圧力空気槽の圧力確認</label>
              </div>
            </div>

            <div class="ml-4">
              <div class="form-check">
                <input class="form-check-input " type="checkbox" id="check1f" name="HaisuiConfirm" __HaisuiConfirmChecked__>
                <label class="form-check-label " for="check1f">排水弁の全閉確認</label>
              </div>
            </div>

            <div class="ml-4">
              <div class="form-check">
                <input class="form-check-input " type="checkbox" id="check1f" name="SeigyoDengenConfirm" __SeigyoDengenConfirm__>
                <label class="form-check-label " for="check1f">制御盤の電源確認</label>
              </div>
            </div>

            <div class="ml-4">
              <div class="form-check">
                <input class="form-check-input " type="checkbox" id="check1f" name="SenyouKairoConfirmForSPSetsubi" __SenyouKairoConfirmForSPSetsubiChecked__>
                <label class="form-check-label " for="check1f">専用回路（電源）の確認</label>
              </div>
            </div>

          </div>


          <div class="form-check">
            <input class="form-check-input needornot" type="checkbox" id="check1c" __IdoushikiChecked__>
            <label class="form-check-label" for="check1c">移動式粉末消火設備</label>
          </div>

          <div class="Idoushiki mt-2 mb-2">

            <div class="ml-4">
              <div class="form-check">
                <input class="form-check-input " type="checkbox" id="check1a" name="NozuruConfirm" __NozuruConfirmChecked__>
                <label class="form-check-label " for="check1a">ノズル開閉弁全閉確認</label>
              </div>
            </div>

            <div class="ml-4">
              <div class="form-check">
                <input class="form-check-input " type="checkbox" id="check1b" name="HousyutsuConfirm" __HousyutsuConfirmChecked__>
                <label class="form-check-label " for="check1b">放出弁全閉確認</label>
              </div>
            </div>

            <div class="ml-4">
              <div class="form-check">
                <input class="form-check-input " type="checkbox" id="check1c" name="CleaningConfirm" __CleaningConfirmChecked__>
                <label class="form-check-label " for="check1c">クリーニング回路全閉確認</label>
              </div>
            </div>

          </div>

          <div class="form-check">
            <input class="form-check-input needornot" type="checkbox" id="check1d" __KasaiTuhouChecked__>
            <label class="form-check-label" for="check1d">火災通報装置</label>
          </div>

          <div class="KasaiTuhou mt-2 mb-2">

            <div class="ml-4">
              <div class="form-check">
                <input class="form-check-input " type="checkbox" id="check1a" name="HontaiDengenConfirm" __HontaiDengenConfirmChecked__>
                <label class="form-check-label " for="check1a">本体の電源確認</label>
              </div>
            </div>

            <div class="ml-4">
              <div class="form-check">
                <input class="form-check-input " type="checkbox" id="check1b" name="HizyouDengenForKasai" __HizyouDengenForKasaiChecked__>
                <label class="form-check-label " for="check1b">非常電源の接続</label>
              </div>
            </div>

            <div class="ml-4">
              <div class="form-check">
                <input class="form-check-input " type="checkbox" id="check1c" name="TellKaisenConfirm" __TellKaisenConfirmChecked__>
                <label class="form-check-label " for="check1c">電話回線の確認</label>
              </div>
            </div>

          </div>



          <div class="form-check">
            <input class="form-check-input needornot" type="checkbox" id="check1e" __GasuSetsubiChecked__>
            <label class="form-check-label" for="check1e">ガス設備</label>
          </div>

          <div class="GasuSetsubi mt-2 mb-2">

            <div class="ml-4">
              <div class="form-check">
                <input class="form-check-input " type="checkbox" id="check1a" name="DoukanSetsuzoku" __DoukanSetsuzokuChecked__>
                <label class="form-check-label " for="check1a">導管接続</label>
              </div>
            </div>

            <div class="ml-4">
              <div class="form-check">
                <input class="form-check-input " type="checkbox" id="check1b" name="HizyouDengenForGasuSetsubi" __HizyouDengenForGasuSetsubiChecked__>
                <label class="form-check-label " for="check1b">非常電源接続</label>
              </div>
            </div>

            <div class="ml-4">
              <div class="form-check">
                <input class="form-check-input " type="checkbox" id="check1c" name="KidouSetsuzoku" __KidouSetsuzokuChecked__>
                <label class="form-check-label " for="check1c">起動容器の接続</label>
              </div>
            </div>

          </div>




        </div>


    </div>



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


        <div for="" style="font-size:24px;">日程について</div>
        <div class="form-check form-check-inline mt-2">
          <input class="form-check-input" type="radio" name="IsInRoom" id="exampleRadios1" value="1">
          <label class="form-check-label" for="IsInRoom">日程変更</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="IsInRoom" id="exampleRadios2" value="2">
          <label class="form-check-label" for="IsInRoom">在宅不可</label>
        </div>

        <div class="form-group mt-2" id="RequestForDate">
          <label for="" style="font-size:24px;">日程のご要望</label>
          <textarea name="Contents" rows="3" cols="50" class="form-control" placeholder="例：工事日を10月1日に変更してもらいたいです。"></textarea>
        </div>

        <div>
          <div>
            <div>ご要望によっては担当者よりご連絡する場合がございます。</div>
          </div>
        </div> -->

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
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
      $(document).on('click', '.round_btn', function (e) {
        var IsDelete = window.confirm('ファイルを削除しますか');
        if (IsDelete) {
          var TargetFileCD = $(this).data('filecd');

          $.ajax({
            type: "POST",
            url: "s_delete_file_API.php",
            data: { 'TargetFileCD': TargetFileCD },
            // dataType: 'json',
            success: function (data) {
              alert("ファイル削除完了しました。");
              window.location.reload();
            },
            complete: function () {
              // modal_loading.hide();
            }
          });
        } else { }
      });

      $('.needornot').change(function () {
        var id = $(this).attr('id');
        var val = $(this).prop("checked");
        var TargetKoumoku = JudgeKoumoku(id);
        ToggleContents(val, TargetKoumoku);
      })

    })

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

    .hiddencontents {
      display: none;
    }
  </style>
</head>

<body>
  __SHeader__

  <div class="content-all">
    <!--content-all-->
    <br>
    <div class="left-yose">
      <a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__" class="btn btn-info">メニュー</a>
      <div class="pt-3">
        物件名：__wBukkenName__
      </div>
    </div>

    <div class="left-yose">

      <form action="s_Result_Report_Finish.php" method="POST" name="mainform">
        <input type="hidden" name="editBukkenCD" value="__editBukkenCD__" />
        <input type="hidden" name="rKey" value="__rKey__" />
        <input type="hidden" name="work" value="1" />

        <p class="mintitle">点検完了報告</p>

        <div>

          <div class="mb-1">

            <div class="form-group">
              <label for="" class="midashi">作業責任者</label>
              <input type="text" class="form-control" name="Sagyousya" id="Sagyousya" value="__Sagyousya__">
            </div>

            <div class="form-group">
              <label for="" class="midashi">作業が終了しましたので、結果を報告致します。</label><br>


              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="ResultReport" id="" value="1" __ResultReportSelected1__>
                <label class="form-check-label" for="">異常なし</label>
              </div>

              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="ResultReport" id="" value="2" __ResultReportSelected2__>
                <label class="form-check-label" for="">不良箇所あり</label>
              </div>
            </div>
          </div>




          <div class="form-check">
            <input class="form-check-input needornot" type="checkbox" id="check1" __ZikahouChecked__>
            <label class="form-check-label " for="check1">自動火災報知器・非常警報・防排煙関係</label>
          </div>

          <div class="Zikahou mt-2 mb-2 __JudgehiddenZikahou__">

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

          <div class="SPSetsubi mt-2 mb-2 __JudgehiddenSPSetsubi__">

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

          <div class="Idoushiki mt-2 mb-2 __JudgehiddenIdoushiki__">

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

          <div class="KasaiTuhou mt-2 mb-2 __JudgehiddenKasaiTuhou__">

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

          <div class="GasuSetsubi mt-2 mb-2 __JudgehiddenGasuSetsubi__">

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

          <div class="form-group ">
            <label for="textarea1" class="midashi mt-2">備考 (例:鍵の借用について)</label>
            <!-- <textarea id="textarea1" class="form-control" rows="5" name="Biko">__Biko__</textarea> -->
            <textarea id="textarea1" class="form-control" rows="5" name="Biko">__Biko_forReport__</textarea>
          </div>
        </div>

    </div>

    <div class="">
      <input type="submit" value="登録する" class="btn btn-primary blue">
    </div>

    <input type="hidden" name="touroku_btn" id="touroku_btn" value="" />

    </form>



    <div class="left-yose">
      <p class="mintitle">点検完了報告（ファイルアップロード）</p>
      <table class="table table-borderless" style="background-color:#F3FFD8">
        <tr>
          <td style="width:450px;">
            登録後は画面更新
            <a href="s_upload_file_for_report.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">画面更新</a><br>
            <table class="table table-borderless" style="width:450px; background-color:#FFFF77">
              <tr>
                <td>
                  <form action="d2b/upload_Bukken_File.php?ClientCD=__ClientCD__&editBukkenCD=__editBukkenCD__&FileKind=2" class="dropzone"></form>
                </td>
              </tr>
            </table>
          </td>
          <td>
            <table>
              __ReportFileLoop__
              <tr>
                __IfNotSeko__
                <td valign="middle">
                  <span class="round_btn" data-filecd="__Report_FileCD__"></span>
                </td>
                __IfNotSeko__
                <td>
                  <a href="./kojifile/__Report_ServerFileName__" download="__Report_RealFileName__">
                    __Report_RealFileName__
                  </a>
                  <br>__Report_Updated__
                </td>
              </tr>
              __ReportFileLoop__
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
  </script>
</body>

</html>
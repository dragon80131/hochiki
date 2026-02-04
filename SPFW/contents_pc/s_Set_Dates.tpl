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

  <!-- vue -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/vue"></script> -->
  <script src="./js/vue.js"></script>

  <script>

  </script>
  <style>
    p.list {
      margin-bottom: 5px;
    }

    .NextTenkenKind {
      display: none;
    }

    .font-12px {
      font-size: 12px;
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




        const IsSetVal = $("#DemandDate").val();
        if (IsSetVal == '') {
          alert("日程を選択して下さい。");
          return false;
        }

        //一部住戸入室の時にチェックが入ってるかチェック
        const IsItibuUmu = $("#itibu_zyuko_umu").val();
        if (IsItibuUmu == "1") {
          if ($("#itibu_zyuko_umu").prop("checked")) {

          } else {
            alert('チェックボックスにチェックを入れてください');
            return false;
          }
        }

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

  <div id="app">

    <div class="content-all">

      <div class="left-yose" v-if="ExistrKey == true">
        <a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__" class="btn btn-info">メニュー</a>
      </div>

      <div class="left-yose">

        <form action="s_Set_Dates_Finish.php" method="POST" name="mainform">
          <input type="hidden" name="editBukkenCD" value="__editBukkenCD__" />
          <input type="hidden" name="rKey" value="__rKey__" />
          <input type="hidden" name="work" value="1" />


          <p class="mintitle">点検非表示設定</p>

          <div class="bukken-name">__BukkenName__</div>
          <!-- <div>非表示にしたい日程にチェックを入れて下さい。</div> -->

          <div class="form-group">
            <label for="" class="midashi">非表示にしたい日程にチェックを入れて下さい。</label>
            <!-- <select class="form-control" name="DemandDate" id="DemandDate"> -->

            __DatesLoop__
            <!-- <option data-ampm="__AMPM__" data-dates="__DatesData__" v-if="DatesLoop > 1">__Dates__</option> -->
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="DatesData[]" id="" value="__Dates__" data-ampm="__AMPM__" __DatesChecked__>
              <label class="form-check-label" for="">__Dates__</label>
            </div>
            __DatesLoop__

          </div>

          <div class="mt-3">
            <input type="submit" value="登録する" class="btn btn-primary blue">
          </div>
          <input type="hidden" name="touroku_btn" id="touroku_btn" value="" />
        </form>

      </div>
    </div>
    <!--content-all-->

  </div>

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


    // Vueの記述 headの中だと動かない
    var app = new Vue({

      el: '#app',
      data: {
        ExistrKey: '__ExistrKey__',
        DatesLoop: __DatesLoop__
      }

    })



  </script>
</body>

</html>
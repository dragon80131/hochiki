<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title></title>

  <!-- BootstrapのCSS読み込み -->
  <link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <!-- jQuery読み込み -->
  <script src="./include/js/jquery-3.2.1.min.js"></script>
  <script type="text/javascript" src="./js/jquery.textarea-auto-height.js"></script>

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
    $(function () {

      $(".TaioLog").click(function () {

        var currentcontent = $(this).closest("td").find(".TaioLogs").val();
        var currentcd = $(this).closest("tr").find("[name='FormCD']").val();
        var BukkenCD = "__editBukkenCD__";

        $.ajax({

          type: "POST",
          url: "RegistorTaioLog.php",
          data: { "currentcd": currentcd, "TaioLog": currentcontent },

        }).done(function (data, textStatus, jqXHR) {

          alert("対応ログを更新しました。")

        }).fail(function (jqXHR, textStatus, errorThrown) {

          alert("対応ログを更新出来ませんでした。");

        });

      });


      $('textarea').textareaAutoHeight();

    });

  </script>
  <style>
    p.list {
      margin-bottom: 5px;
    }
  </style>
</head>

<body>
  __SHeader__

  <div id="app">

    <div class="content-all">
      <br />
      <div class="left-yose">
        <a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__" class="btn btn-info">メニュー</a>
        <!-- <a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__" class="btn btn-info">前回までの問い合わせ</a> -->
      </div>

      <div class="left-yose mt-2">

        <form action="s_Taio_List.php" method="POST" name="mainform">

          <input type="hidden" name="editBukkenCD" value="__editBukkenCD__" />
          <input type="hidden" name="rKey" value="__rKey__" />
          <input type="hidden" name="work" value="1" />

          <input type="text" name="SearchRoomNo" class="mt-2" value="__SearchRoomNo__" placeholder="部屋番号検索 例:101">
          <input type="submit" value="検索">

          <div class="mt-2 mb-2">

            <div>__BukkenName__</div>

            <span v-if="isExistLastDate">
              __Formated_FirstDate__ ~ __Formated_LastDate__
            </span>

            <span v-if="isnotExistLastDate">
              __Formated_FirstDate__
            </span>

          </div>

          <!-- <button type="button" id="editdisplay" @click="active">スマホ用表示</button> -->

          <p class="mintitle">お問い合わせ一覧</p>

          <table class="table toilist">
            <tr>
              <th style="text-align:center;">部屋番号</td>
              <th>時間要望</th>
              <th>対応ログ</th>
              <th v-if="isActive">名前</th>
              <th v-if="isActive">連絡先</th>
              <th v-if="isActive">投稿者</th>
              <th v-if="isActive">投稿日</th>
              <!-- <th v-if="isActive">不要</th> -->
            </tr>

            __ResidentsFormLoop__


            <tr>

              <input type="hidden" value="__FormCD__" name="FormCD">

              <td style="font-size:14px;">__RoomNo__</td>

              <td>__DemandDate__</td>

              <td>
                <textarea class="TaioLogs" name="" id="">__TaioLog__</textarea>
                <!-- <button type="button" class="TaioLog btn btn-primary blue">登録</button> -->
              </td>

              <td v-if="isActive">__Name__</td>

              <td v-if="isActive">__TEL__</td>

              <td v-if="isActive">__CreatorName__</td>

              <td v-if="isActive">__Created__</td>

              <!-- <td v-if="isActive">
                <button type="button" class="Delete btn btn-danger btn-sm" id="__FormCD__" v-if="isClientCD" @click="onSubmit(__FormCD__)">不要</button>
              </td> -->

            </tr>

            __ResidentsFormLoop__

          </table>

          <hr>
          <p class="mintitle">不要お問い合わせ一覧</p>

          <table class="table toilist">

            <tr>
              <th>部屋番号</td>
              <th v-if="isActive">時間要望</th>
              <th>対応ログ</th>
              <th v-if="isActive">名前</th>
              <th v-if="isActive">連絡先</th>
              <th v-if="isActive">投稿者</th>
              <th v-if="isActive">投稿日</th>
              <th v-if="isActive">不要</th>
            </tr>

            __ResidentsFormLoop2__
            <tr>

              <input type="hidden" value="__FormCD2__" name="FormCD">
              <td>__RoomNo2__</td>
              <td v-if="isActive">__DemandDate2__</td>
              <td>
                <textarea class="TaioLogs" name="" id="">__TaioLog2__</textarea>
                <!-- <button type="button" class="TaioLog btn btn-primary blue">登録</button> -->
              </td>
              <td v-if="isActive">__Name2__</td>
              <td v-if="isActive">__TEL2__</td>

              <td v-if="isActive">__CreatorName2__</td>
              <td v-if="isActive">__Created2__</td>

              <td v-if="isActive">
              </td>

            </tr>
            __ResidentsFormLoop2__

          </table>

        </form>

      </div>
    </div>
    <!--content-all-->

  </div>

  __SFooter__ __SCopyright__

  <script>



    // Vueの記述 headの中だと動かない
    var app = new Vue({

      el: '#app',
      data: {
        isActive: true,
        isnotExistLastDate: "__isnotExistLastDate__",
        isExistLastDate: "__isExistLastDate__",
        isClientCD: "__isClientCD__",
      },
      methods: {

        onSubmit: function (formcd) {

          if (confirm("削除しますか?")) {

            $.ajax({
              type: "POST",
              url: "DeleteTaioLog.php",
              data: { "currentcd": formcd },
            }).done(function (data, textStatus, jqXHR) {
              window.location.reload();
            }).fail(function (jqXHR, textStatus, errorThrown) {

            });
          }

        },

        active: function () {
          this.isActive = !this.isActive;
        }

      }

    })


  </script>
</body>

</html>
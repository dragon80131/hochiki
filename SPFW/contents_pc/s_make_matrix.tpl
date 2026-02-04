<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>リニューアル支援</title>

    <!-- BootstrapのCSS読み込み -->
    <link href="../include/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <!-- jQuery読み込み -->
    <script src="../include/js/jquery-3.4.1.min.js"></script>

    <!-- BootstrapのJS読み込み -->
    <script src="../include/bootstrap/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="../css/rnsien.css?20190514" />
    <script type="text/javascript" src="../tools.js"></script>

    <link
      href="../css/jquery-ui-1.8.2.custom.css"
      rel="stylesheet"
      type="text/css"
    />
    <script type="text/javascript" src="../js/tools_ajax.js"></script>
    <script type="text/javascript" src="../js/ConnectedSelect.js"></script>
    <!--<script src="../js/jquery-1.4.2.js" type="text/javascript"></script>-->
    <script src="../js/jquery.ui.core.js" type="text/javascript"></script>
    <script type="text/javascript">
      jQuery(function ($) {
        var checked_last = null;
        jQuery(".check-range").on("click", function (event) {
          if (event.shiftKey && checked_last) {
            //Shiftを押してクリックした場所は終点として処理をする
            var $targets = $(".check-range");
            var p1 = $targets.index(checked_last);
            var p2 = $targets.index(this);
            for (var i = Math.min(p1, p2); i <= Math.max(p1, p2); ++i) {
              $targets.get(i).checked = checked_last.checked;
            }
          } else {
            //Shiftを押さずにクリックした場所は始点として覚えておく
            checked_last = this;
          }
        });
      });

      function RoomCheck() {
        var RoomSuu = 0;
        let checkboxes = document.querySelectorAll('input[name="KaiRoom[]"]');
        checkboxes.forEach(function(checkbox) {
          if (checkbox.checked) {
            RoomSuu += 1;
          }
        });

        document.getElementById("RoomSuu").innerHTML = RoomSuu;
      }
      function moveandCheck() {
        if(document.getElementById("wKosu").value == "" || document.getElementById("wKosu").value == "0"){
          document.getElementById("ErrorString").innerHTML = "戸数は必須項目です。";
          return false;
        }
        var RoomSuu = 0;
        let checkboxes = document.querySelectorAll('input[name="KaiRoom[]"]');
        checkboxes.forEach(function(checkbox) {
          if (checkbox.checked) {
            RoomSuu += 1;
          }
        });

        let wKosuVal = document.getElementById("wKosu").value;
        wKosuVal = parseInt(wKosuVal);
        if(isNaN(wKosuVal))
          wKosuVal = 0;
        if (RoomSuu == wKosuVal) {
          return true;
        } else {
          document.getElementById("ErrorString").innerHTML =
            "部屋数が違います。";
        }
        return false;
      }
    </script>
  </head>

  <body
    bgcolor="__SBackground__"
    text="__STextColor__"
    link="__SLinkColor__"
    alink="__SALinkColor__"
    vlink="__SVLinkColor__"
  >
  __SHeaderKanri__
    <div class="content-all pt-3" style="text-align:left">
      <!--content-all-->
      __IfBuildingExist__
      <div class="cur_building_dis mb-3">__wBuildingName__</div>
      __IfBuildingExist__

      <h5>詳細工程表セット</h5>

      __IfError__
      <br /><br />
      <font color="red"
        >ログインしたユーザの所属以外の物件を登録・編集することはできません。所属を確認ください。<br
      /></font>
      __IfError__ __IfOK__
      <br /><br />
      <font color="red"
        >予約センターへ依頼が完了しました。受付準備が整いましたらご連絡いたします。<br
      /></font>
      __IfOK__
      部屋構成を作成しますので、以下の項目を入力し、「確定」ボタンをクリックしてください。<br />
      <form action="s_make_kanryo2.php" method="POST" name="mainform" onsubmit="return moveandCheck();">
        <font color="red"><span id="ErrorString"></span></font><br />
        <table border="1">
          <tr>
            <th width="200" style="text-align: center" bgcolor="#f4cccc">
              戸数
            </th>
            <th width="200" style="text-align: center" bgcolor="#f4cccc">
              チェックしている部屋数
            </th>
          </tr>
          <tr>
            <td width="200" style="text-align: center">
            <input type="number" id="wKosu" name="wKosu" value="__wKosu__" style="width: 80px; ime-mode: disabled" required="">
            </td>
            <td width="200" style="text-align: center">
              <span id="RoomSuu">__wRoomSuu__</span>
            </td>
          </tr>
        </table>
        <!--
	__ColsLoop__
		<td >__Pic__ 
		</td>
	__ColsLoop__ 
--><br />
        <table border="1" class="room-composition">
          __RowsLoop__
          <tr>
            __ColsBlock__
          </tr>
          __RowsLoop__
        </table>

        <br />
        <!--
<table>
<tr><td rowspan=3 bgcolor="palegreen" >工事順(詳細工程表の並び順）</td>
	<td align=right ><input type="radio" name="KojiJun" value="1" ></td><td><img src="../images/kojijun1.png" width="50"><br>下から横へ</td>
	<td align=right ><input type="radio" name="KojiJun" value="2" ></td><td><img src="../images/kojijun2.png" width="50"><br>上から横へ</td></tr>
<tr><td align=right ><input type="radio" name="KojiJun" value="3" ></td><td><img src="../images/kojijun3.png" width="50"><br>下から縦へ</td>
	<td align=right ><input type="radio" name="KojiJun" value="4" ></td><td><img src="../images/kojijun4.png" width="50"><br>上から縦へ</td></tr>
<tr><td>　</td>
	<td colspan=4 >
		<br>
		<img src="../images/kojijun5.png" width="50"><br>
		建物が分離しているなど特殊なケースの場合は、詳細工程表（Excel）出力後、<br>
		編集するもしくは、予約受付センターにご相談ください。　
	</td></tr>
</table>-->

        <input type="hidden" name="rKey" value="__rKey__" />
        <input type="hidden" name="editBukkenCD" value="__editBukkenCD__" />
        <input type="hidden" name="editBuildingCD" value="__editBuildingCD__" />
        <input type="hidden" name="work" value="1" /><br />
        <input
          type="submit"
          value="確定"
          class="btn btn-primary blue"
        />
        <br /><br />
        <input
          type="button"
          value="もどる"
          class="btn btn-primary"
          onclick="javascript:history.back();"
        />
      </form>
      <br />
    </div>
    <!--content-all-->

    __SFooter__ __SCopyright__
  </body>
</html>

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
    <style>
		.HasError{
			color:red;
      text-align:left;
		}
	</style>
    <script type="text/javascript" src="../js/tools_ajax.js"></script>
    <script type="text/javascript" src="../js/ConnectedSelect.js"></script>
    <!--<script src="../js/jquery-1.4.2.js" type="text/javascript"></script>-->
    <script src="../js/jquery.ui.core.js" type="text/javascript"></script>
    <script type="text/javascript">
      jQuery(function ($) {
        var checked_last = null;
        var dragStartCheckbox = null;
        var dragEndCheckbox = null;
        var dragStartCell = null;
        var dragEndCell = null;
        var dragWithCtrl = false;
        var dragHoverCell = null;
        var pendingRange = [];
        var pendingInitialized = false;
        var suppressNextClick = false;
        var isDragging = false;

        function setUserSelectDisabled(disabled) {
          if (disabled) {
            $("body").addClass("no-user-select");
          } else {
            $("body").removeClass("no-user-select");
          }
        }

        function getCheckboxRange(startCheckbox, endCheckbox) {
          var $targets = $(".room-composition .check-range");
          var p1 = $targets.index(startCheckbox);
          var p2 = $targets.index(endCheckbox);

          if (p1 < 0 || p2 < 0) {
            return [];
          }

          var range = [];
            for (var i = Math.min(p1, p2); i <= Math.max(p1, p2); ++i) {
            range.push($targets.get(i));
          }
          return range;
        }

        function getCellPosition(cell) {
          if (!cell) {
            return null;
          }
          var $cell = $(cell);
          var $row = $cell.closest("tr");
          var $table = $cell.closest("table.room-composition");
          if ($row.length === 0 || $table.length === 0) {
            return null;
          }

          var rowIndex = $table.find("tr").index($row.get(0));
          var colIndex = cell.cellIndex;
          if (rowIndex < 0 || colIndex == null || colIndex < 0) {
            return null;
          }
          return { row: rowIndex, col: colIndex, table: $table };
        }

        function getCheckboxRectangle(startCell, endCell) {
          var pos1 = getCellPosition(startCell);
          var pos2 = getCellPosition(endCell);
          if (!pos1 || !pos2 || pos1.table.get(0) !== pos2.table.get(0)) {
            return [];
          }

          var $table = pos1.table;
          var r1 = Math.min(pos1.row, pos2.row);
          var r2 = Math.max(pos1.row, pos2.row);
          var c1 = Math.min(pos1.col, pos2.col);
          var c2 = Math.max(pos1.col, pos2.col);

          var range = [];
          for (var r = r1; r <= r2; ++r) {
            var $row = $table.find("tr").eq(r);
            if ($row.length === 0) continue;
            for (var c = c1; c <= c2; ++c) {
              var $cell = $row.find("td").eq(c);
              if ($cell.length === 0) continue;
              var checkbox = $cell.find(".check-range").get(0) || null;
              if (checkbox) {
                range.push(checkbox);
              }
            }
          }
          return range;
        }

        function clearDragHighlights() {
          $(".room-composition td.drag-highlight").removeClass("drag-highlight");
        }

        function getCellRectangle(startCell, endCell) {
          var pos1 = getCellPosition(startCell);
          var pos2 = getCellPosition(endCell);
          if (!pos1 || !pos2 || pos1.table.get(0) !== pos2.table.get(0)) {
            return [];
          }

          var $table = pos1.table;
          var r1 = Math.min(pos1.row, pos2.row);
          var r2 = Math.max(pos1.row, pos2.row);
          var c1 = Math.min(pos1.col, pos2.col);
          var c2 = Math.max(pos1.col, pos2.col);

          var cells = [];
          for (var r = r1; r <= r2; ++r) {
            var $row = $table.find("tr").eq(r);
            if ($row.length === 0) continue;
            for (var c = c1; c <= c2; ++c) {
              var $cell = $row.find("td").eq(c);
              if ($cell.length === 0) continue;
              cells.push($cell.get(0));
            }
          }
          return cells;
        }

        function getCellsFromCheckboxRange(startCheckbox, endCheckbox) {
          var checkboxes = getCheckboxRange(startCheckbox, endCheckbox);
          var cells = [];
          for (var i = 0; i < checkboxes.length; ++i) {
            var $cell = $(checkboxes[i]).closest("td");
            if ($cell.length > 0) {
              cells.push($cell.get(0));
            }
          }
          return cells;
        }

        function clearPendingRange() {
          pendingRange = [];
          pendingInitialized = false;
        }

        function getPendingRoomNumbers() {
          var roomNumbers = [];
          for (var i = 0; i < pendingRange.length; ++i) {
            roomNumbers.push(pendingRange[i].value);
          }
          return roomNumbers;
        }

        $(document).on("mousedown", ".room-composition td", function (event) {
          dragStartCell = this;
          dragStartCheckbox = $(this).find(".check-range").get(0) || null;
          dragWithCtrl = !!(event && (event.ctrlKey || event.metaKey));
          isDragging = false;
          dragHoverCell = this;
          clearDragHighlights();
          // Always suppress native text selection while dragging on table cells.
          if (event && typeof event.preventDefault === "function") {
            event.preventDefault();
          }
          setUserSelectDisabled(true);
          if (dragStartCell) {
            $(dragStartCell).addClass("drag-highlight");
          }
        });

        $(document).on("mousemove", function () {
          if (dragStartCheckbox) {
            isDragging = true;
          }
        });

        $(document).on("mouseenter mousemove", ".room-composition td", function () {
          if (!dragStartCell || !dragStartCheckbox) {
            return;
          }
          dragHoverCell = this;
          var cells = dragWithCtrl
            ? getCellRectangle(dragStartCell, dragHoverCell)
            : getCellsFromCheckboxRange(dragStartCheckbox, $(this).find(".check-range").get(0) || null);
          clearDragHighlights();
          for (var i = 0; i < cells.length; ++i) {
            $(cells[i]).addClass("drag-highlight");
          }
        });

        $(document).on("mouseup", ".room-composition td", function () {
          if (!dragStartCheckbox) {
            return;
          }
          dragEndCell = this;
          dragEndCheckbox = $(this).find(".check-range").get(0) || null;
          if (isDragging && dragStartCheckbox !== dragEndCheckbox) {
            pendingRange = dragWithCtrl
              ? getCheckboxRectangle(dragStartCell, dragEndCell)
              : getCheckboxRange(dragStartCheckbox, dragEndCheckbox);
            if (pendingRange.length > 0) {
              pendingInitialized = true;
              suppressNextClick = true;
              $("#multiSelectModal").modal("show");
            }
          }

          dragStartCheckbox = null;
          dragEndCheckbox = null;
          dragStartCell = null;
          dragEndCell = null;
          dragWithCtrl = false;
          dragHoverCell = null;
          isDragging = false;
          setUserSelectDisabled(false);
          clearDragHighlights();
        });

        $(document).on("mouseup", function () {
          // If mouseup happens outside the table, still allow drag selection
          if (
            dragStartCheckbox &&
            dragHoverCell &&
            dragStartCell !== dragHoverCell &&
            isDragging
          ) {
            var dragHoverCheckbox = $(dragHoverCell).find(".check-range").get(0) || null;
            pendingRange = dragWithCtrl
              ? getCheckboxRectangle(dragStartCell, dragHoverCell)
              : getCheckboxRange(dragStartCheckbox, dragHoverCheckbox);
            if (pendingRange.length > 0) {
              pendingInitialized = true;
              suppressNextClick = true;
              $("#multiSelectModal").modal("show");
            }
          }
          dragStartCheckbox = null;
          dragStartCell = null;
          dragEndCell = null;
          dragWithCtrl = false;
          dragHoverCell = null;
          isDragging = false;
          setUserSelectDisabled(false);
          clearDragHighlights();
        });

        $(".room-composition").on("click", ".check-range", function (event) {
          if (suppressNextClick) {
            event.preventDefault();
            event.stopImmediatePropagation();
            suppressNextClick = false;
            return false;
          }

          if (event.shiftKey && checked_last) {
            // Shiftを押してクリックした場所は終点として処理する
            var range = getCheckboxRange(checked_last, this);
            for (var i = 0; i < range.length; ++i) {
              range[i].checked = checked_last.checked;
            }
          } else {
            // Shiftを押さずにクリックした場所は始点として覚えておく
            checked_last = this;
          }

          RoomCheck();
        });

        function applyPendingRange(shouldCheck) {
          if (!pendingInitialized || pendingRange.length === 0) {
            $("#multiSelectModal").modal("hide");
            return false;
          }

          for (var i = 0; i < pendingRange.length; ++i) {
            pendingRange[i].checked = shouldCheck;
          }

          RoomCheck();
          clearPendingRange();
          $("#multiSelectModal").modal("hide");
          return true;
        }

        $("#multiSelectApply").on("click", function () {
          applyPendingRange(true);
        });

        $("#multiSelectClear").on("click", function () {
          applyPendingRange(false);
        });

        $("#multiSelectModal").on("show.bs.modal", function () {
          var roomNumbers = getPendingRoomNumbers();
          if (roomNumbers.length > 0) {
            $("#selectedRooms").text(roomNumbers.join(", "));
          } else {
            $("#selectedRooms").text("なし");
          }
        });

        $("#multiSelectModal").on("hidden.bs.modal", function () {
          $("#selectedRooms").text("");
          clearPendingRange();
          suppressNextClick = false;
          clearDragHighlights();
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
      function check_form(){
        let wKosuVal = document.getElementById("wKosu").value;
        if(wKosuVal == ""){
          $("#ErrorString").html("※戸数を入力してください。");
          window.scrollTo({
            top: 0,
            behavior: 'smooth'
          });
          return false;
        }else{
          $("#ErrorString").html("");
        }

        return true;
      }	
    </script>
    <style>
    .setting_input_panel{
      position: sticky;
      left: 0;
      top: 0;
      background: #fff;
      z-index: 10;
      -webkit-box-shadow: 1px 2px 13px 7px rgba(79, 66, 66, 0.32);
      box-shadow: 1px 2px 13px 7px rgba(79, 66, 66, 0.32);
    }

    /* Prevent native text selection while Ctrl/Command-dragging */
    body.no-user-select, body.no-user-select *{
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }

    .room-composition td.drag-highlight{
      background: rgba(0, 123, 255, 0.2);
      outline: 2px solid rgba(0, 123, 255, 0.35);
      outline-offset: -2px;
    }
    </style>
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
        <table border="1" class="setting_input_panel">
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

        <div class="modal fade" id="multiSelectModal" tabindex="-1" role="dialog" aria-labelledby="multiSelectModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="multiSelectModalLabel">複数部屋を設定</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="mt-2">
                  <div>以下の部屋が設定されます。</div>
                  <p id="selectedRooms"></p>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="multiSelectClear">選択を解除する</button>
                <button type="button" class="btn btn-primary blue" id="multiSelectApply">選択する</button>
              </div>
            </div>
          </div>
        </div>

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
          onclick="return check_form();"
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

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
    /* 20190709 add */
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
      $(document).on('change', 'input[name="IsInRoom"]', function () {

        var IsInRoom = $('input[name = "IsInRoom"]:checked').val();
        // var IsInRoom = $(this).val();
        if (IsInRoom == "1") {
          $("#RequestForDate").show();
        } else if (IsInRoom == "2") {
          $("#RequestForDate").hide();
        } else { }
      });

    });
  </script>
</head>

<body>
  __SHeader__

  <div class="content-all">

    <div class="top-menu left-yose">
      <p class="mintitle" style="font-size:32px;">点検日程お問い合わせフォーム</p>
      <div style="color: red">
        <div style="font-size:24px;">点検日程の受付締切日を越えています。</div>
      </div>

    </div>
  </div>
  <!--content-all-->

  __SFooter__ __SCopyright__

  <script>
  </script>
</body>

</html>
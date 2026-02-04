<html>

<head>
  <title>消防設備点検予約システム-ログイン</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<!--最小限のビューポート設定-->
	<meta name="viewport" content="width=device-width">
	<meta name="format-detection" content="telephone=no">

  
  <!-- BootstrapのCSS読み込み -->
  <link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

  <!-- jQuery読み込み -->
  <script src="./include/js/jquery-3.2.1.min.js"></script>

  <!-- BootstrapのJS読み込み -->
  <script src="./include/bootstrap/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="./css/rnsien.css">

  <script type="text/javascript">



  </script>
</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">



<img src="./images/489kanri.png" alt="ダイヤ for メンテナンス" class="header-img" width="600" height="73">
<div class="content-all">
  <div style="text-align: center">
    <br />
    <br />
    ユーザー名とパスワードを入力し、<br>ログインしてください。<br />
    <br />
    
    __IfError__<font color="red">__ErrorMessage__</font><br />__IfError__

    <form method="POST" action="login_finish2.php">
      ユーザー名:(半角入力)<br />
      <input type="text" name="wID" value="__wID__" istyle="3" style="ime-mode: disabled" /><br />
      パスワード:(半角入力)<br />
      <input type="password" name="wPasswd" value="__wPasswd__" istyle="3" style="ime-mode: disabled" /><br />
      <br />

      __HiddenValues__

      __COMMON_POST_QUERY__
      <input type="submit" value="ログイン" class="btn btn-primary blue" />
    </form>
</div>
    __SCopyright__
  </div>
</body>

</html>
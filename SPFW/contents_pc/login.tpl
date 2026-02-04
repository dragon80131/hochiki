<html>

<head>
	<link rel="stylesheet" type="text/css" href="css/common.css">
	<link rel="stylesheet" type="text/css" href="css/style_okyakusama.css">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<!--最小限のビューポート設定-->
	<meta name="viewport" content="width=device-width">
	<meta name="format-detection" content="telephone=no">
  <title>消防設備点検予約システム-ログイン</title>

  </script>
  <!-- <style>
  @media (max-width: 768px) {
    .content-all {
      padding: 10px;
    }
  
    /* 追加のスタイル */
  }
</style> -->
</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">

  <div class="container">
    __SHeaderWithoutLogout__

    <div class="row">
      <div class="col-12">
        <div style="text-align: center">
          <br />
          <br />
          ログインしてください。<br />
          <br />
  
          __IfError__<font color="red">__ErrorMessage__</font><br />__IfError__
  
          <form method="POST" action="login_finish.php">
            <div class="form-group">
              <label for="bukkenCD">物件管理番号:(半角入力)</label>
              <input type="text" id="bukkenCD" name="editBukkenCD" value="__editBukkenCD__" class="form-control" style="ime-mode: disabled"  />
            </div>
            <div class="form-group">
              <label for="userID">部屋番号:(半角入力)</label>
              <input type="text" id="userID" name="wID" value="__wID__" class="form-control" style="ime-mode: disabled" autocomplete="username" />
          </div>
          <div class="form-group">
              <label for="password">パスワード:(半角入力)</label>
              <input type="password" id="password" name="wPasswd" value="__wPasswd__" class="form-control" style="ime-mode: disabled" autocomplete="current-password" />
          </div>
            <br />
           <input type="hidden" name="editBuildingCD" value="__editBuildingCD__">
            __COMMON_POST_QUERY__
            <input type="submit" value="ログイン" class="finish-btn" style="margin:0 auto"/>
          </form>
          <br />

        </div>
      </div>
    </div>
  </div>
  
    __SFooter__ __SCopyright__
  </div>
</body>

</html>
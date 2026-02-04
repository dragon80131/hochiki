<html>
<head>
<link href="./d2b/css/dropzone.css" type="text/css" rel="stylesheet" />
<script src="./d2b/dropzone.min.js"></script>
<title>部屋番号管理 − 工事基本情報フォーム</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>
__IfAjax__<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__
</head>

<body>
<div class="content">
<h2 class="admin-title">工事関連資料アップロード</h2>
<br />
<br><br>
ファイルアップロード用<br>
<form action="../d2b/kojiupload.php" class="dropzone"></form>


<!--
__IfAdminSystem__<h2 class="navigation"><a href="#" onclick="javascript:move('client_list.php')">&lt;&lt;&lt; クライアントリストへ</a></h2>
__IfAdminSystem__<h2 class="navigation"><a href="#" onclick="javascript:location.href='reserve_set_menu.php'">&lt;&lt;&lt; システム設定メニューへ</a></h2>
<h2 class="navigation"><a href="#" onclick="javascript:location.href='index.php'">&lt;&lt;&lt; トップページ(メニュー)へ</a></h2>
<br />

__IfErrNoUP__ <font color="#ff0000">ユーザファイルをアップロードできません。</font><br>   __IfErrNoUP__
__IfErrNoFile__   <font color="#ff0000">ユーザファイルが選択されていません。</font><br> __IfErrNoFile__


__IfUp__
<form action="kojiUpload.php" method="post" enctype="multipart/form-data">
  工事内容ファイル(工事のお知らせ、工程表、日程表、オプション申込など)：<br />
  <input type="file" name="upfile" size="30" /><br />
  <br />


  <input type="submit" value="アップロード" />
</form>
__IfUp__


__IfDb__
<br>
ファイルをアップロードしました。<br><br>


__IfDb__
-->
<br><br>
<a href=./reserve_detail.php?__rKey__>もどる</a>
<br><br>


<b>現在のアップロードされているファイル一覧</b><br>
__fileLoop__
__fileList__
__fileLoop__

<div class="footer-box">

</div>

</body>
</html>
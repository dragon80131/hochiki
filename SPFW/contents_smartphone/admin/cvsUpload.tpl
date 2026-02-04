<html>
<head>
<title>部屋番号管理 − 工事基本情報フォーム</title>

<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>
__IfAjax__<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__</head>

<body>
<div class="content">
<h2 class="admin-title">ユーザデータと仮日程データのインポート</h2>
<br />

__IfAdminSystem__<h2 class="navigation"><a href="#" onclick="javascript:move('client_list.php')">&lt;&lt;&lt; クライアントリストへ</a></h2>
__IfAdminSystem__<h2 class="navigation"><a href="#" onclick="javascript:location.href='reserve_set_menu.php'">&lt;&lt;&lt; システム設定メニューへ</a></h2>
<h2 class="navigation"><a href="#" onclick="javascript:location.href='index.php'">&lt;&lt;&lt; トップページ(メニュー)へ</a></h2>
<br />
__IfErrmisFile__   <font color="#ff0000">ユーザファイルがuser.csvではありません。</font><br> __IfErrmisFile__
__IfErrNoUP__ <font color="#ff0000">ユーザファイルをアップロードできません。</font><br>   __IfErrNoUP__
__IfErrNoFile__   <font color="#ff0000">ユーザファイルが選択されていません。</font><br> __IfErrNoFile__

__IfErrmisFile2__   <font color="#ff0000">仮日程ファイルがreserve.csvではありません。</font><br> __IfErrmisFile2__
__IfErrNoUP2__ <font color="#ff0000">仮日程ファイルをアップロードできません。</font><br>   __IfErrNoUP2__
__IfErrNoFile2__   <font color="#ff0000">仮日程ファイルが選択されていません。</font><br> __IfErrNoFile2__


__IfUp__
<form action="cvsUpload.php" method="post" enctype="multipart/form-data">
  ユーザファイル(user.csv)：<br />
  <input type="file" name="upfile" size="30" /><br />
  <br />
  仮日程ファイル(reserve.csv)：<br />
  <input type="file" name="upfile2" size="30" /><br />
  <br />
  <input type="submit" value="アップロード" />
</form>
__IfUp__


__IfDb__
<br>
ファイルをアップロードしました。


<form action="cvsImport.php" method="post" >

  <br />
  <input type="submit" value="次へ" />
</form>
__IfDb__


<div class="footer-box">

</div>

</body>
</html>
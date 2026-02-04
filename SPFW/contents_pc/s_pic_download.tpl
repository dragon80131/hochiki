<html>
<head>
<title>営業活動支援システム</title>
<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a7.css">

<script type="text/javascript" src="tools.js"></script>
<link href="css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="js/jquery.ui.core.js" type="text/javascript"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>

<script type="text/javascript" src="js/ConnectedSelect.js"></script>

</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__


<hr size="__HRSize__" color="__HRColor__">
<center>《現場写真》</center>
<hr size="__HRSize__" color="__HRColor__">

<br>


__IfErr1__
ファイル書き込みに失敗しました<br>
__IfErr1__

__IfErr2__
ファイルロックに失敗しました<br>
__IfErr2__

__IfErr3__
ファイル変換に失敗しました<br>
JPGファイルのみ変換可能です<br>
__IfErr3__

__IfNoErr__
<br>ダウンロードの準備ができました。ダウンロードをクリックしてください。
<br><br><a href=./upfile/dl/image.zip>ダウンロード</a>

__IfNoErr__


<hr size="__HRSize__" color="__HRColor__">


<a href="s_menu.php__QUERY__&editBukkenCD=__editBukkenCD__ ">メニュー</a>
<br><br>
<a href="s_search.php__QUERY__">トップ</a>
<br><br>
<a href="logout.php__QUERY__">ログアウト</a>
<hr size="__HRSize__" color="__HRColor__">

__SFooter__

__SCopyright__
<br>
</body>

</html>

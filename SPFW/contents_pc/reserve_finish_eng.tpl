<html>
<head>
<link rel="stylesheet" type="text/css" href="css/common.css">
<link rel="stylesheet" type="text/css" href="css/style_okyakusama.css">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
<!--最小限のビューポート設定-->
<meta name="viewport" content="width=device-width">

<title>ご予約の確定</title>
</head>
<body>
<table>

<div id="navi">
<!--<a class="gengo" href="#.php">Englishi</a>-->
<a class="bar" href="top.php__QUERY__&wLang=1">予約TOP</a>
<a class="bar-migi" href="logout.php__QUERY__&wLang=1">ログアウト</a>
</div>

__SHeader__

<h1>__MansionName__ __wBuildingName__</h1>
<h2>__wID__号室様</h2>


<h3>日程予約の完了</h3>


<div id="step">
<img src="./images/step3.png" alt="step">
</div>


<p>日程のご予約を受け付けました。</p>
<p>
日程調整後、<font color="red">__KetteiHaifuDate__頃</font>に決定通知を配布致しますので、<br>改めてご確認をお願いします。
</p>
__IfOPOPEN__
__IfOp__
<p><!--OPあるときのみ表示-->
※オプションを申込み希望のお客様は<br>「予約システムTOP」→「オプション申込み」へお進みください。
</p>
__IfOp__
__IfOPOPEN__

<div class="yoyakutop">
    <a href="top.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__" class="tophe-btn">予約システムTOPへ</a>

</div>
<div class="out"> 
<a href="logout.php?rKey=__rKey__&wLang=1" class="tophe-btn">ログアウト</a>
</div>

__SFooter__

__SCopyright__
</table>
</body>
</html>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>リニューアル支援</title>

<!-- BootstrapのCSS読み込み -->
<link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- jQuery読み込み -->
<script src="./include/js/jquery-3.2.1.min.js"></script>

<!-- BootstrapのJS読み込み -->
<script src="./include/bootstrap/js/bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="./css/rnsien.css">
<script type="text/javascript" src="tools.js"></script>


<body>
__SHeader__

<div class="content-all"><!--content-all-->

<div class="left-yose">
<a href="./s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜メニュー</a>
</div>


<div class="top-menu left-yose">
<h5>__wBukkenName__</h5>
<h6>作業指示書</h6>




<a href="#" onclick="javascript:move('./doc/s_sagyoshiji_Excel.php?editBukkenCD=__editBukkenCD__')" class="square_btn">　作業指示書</a>
　<br><br>
<h6>機器設定指示書</h6>

<form action="s_form.php" method="POST" name="mainform" >
<input type="hidden" name="rKey" value="__rKey__" >


<a href="#" onclick="javascript:move('s_kikiseteirakutouchplus.php?editBukkenCD=__editBukkenCD__')" class="square_btn">　らくタッチPlus</a>

<br>
<br>

<a href="#" onclick="javascript:move('s_kikiseteiPATMO_GBM-2M_K.php?editBukkenCD=__editBukkenCD__')" class="square_btn">　PATMO・GBM-2M（K）</a>

<br>
<br>

<a href="#" onclick="javascript:move('s_kikiseteiVIXUS1Pr.php?editBukkenCD=__editBukkenCD__')" class="square_btn">　VIXUS1Pr</a>

<br>
<br>

<a href="#" onclick="javascript:move('s_kikiseteiWISM7α.php?editBukkenCD=__editBukkenCD__')" class="square_btn">　WISM7α</a>

<br>
<br>

<a href="#" onclick="javascript:move('s_kikiseteirakutouch.php?editBukkenCD=__editBukkenCD__')" class="square_btn">　らくタッチ</a>

<!--　
<a href="#" onclick="javascript:move('s_rakutouchkikisetei.php?editBukkenCD=__editBukkenCD__')" class="square_btn">　2</a>
　
<a href="#" onclick="javascript:move('#')" class="square_btn">　3</a>
-->

</div>

</div><!--content-all-->

<hr>

__SFooter__
__SCopyright__

</body>
</html>

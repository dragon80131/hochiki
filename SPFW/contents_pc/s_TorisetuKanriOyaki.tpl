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

</head>

<body>
__SHeader__

<div class="content-all"><!--content-all-->

<div class="left-yose">
<a href="./s_kansei_document.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜戻る</a>
</div>


<div class="top-menu left-yose">

<form action=# method="POST" name="mainform" >
<input type="hidden" name="editBukkenCD" >

<h6>管理親機一覧</h6>

<table class="table">
<tr><td>らくタッチPlus対応機器<br>
		VIXUS1Pr対応機器</td>
	<td>
		<a href="./doc/kiki_manual/VJX-MKCRA_etc_OP.pdf" target="_blank" >VJX-MKCRA_etc_OP</a>
	</td>
</tr>
<tr><td>らくタッチ対応機器<br>
		DASHWISM7α対応機器<br>
		DASHWISM GP対応機器</td>
	<td>
		<a href="./doc/kiki_manual/VHX-M_A_OP.pdf" target="_blank" >VHX-M_A_OP</a><br>
		<a href="./doc/kiki_manual/VHX-M「」_A_DASHWISM_OP.pdf" target="_blank" >VHX-M「」_A_DASHWISM_OP</a><br>
		<a href="./doc/kiki_manual/VHX-MRM_A_OP.pdf" target="_blank" >VHX-MRM_A_OP</a>
	</td>
</tr>
<tr><td>PATMO対応機器</td>
	<td>
		<a href="./doc/kiki_manual/GBX-MK_OP.pdf" target="_blank" >GBX-MK</a>
	</td>
</tr>
</table>



<!--
<br><br><br><br><br><br><br><br><br><br><br><br><br>

<h6>らくタッチPlus対応機器</h6>
<a href="./doc/kiki_manual/VJX-MKCRA_etc_OP.pdf" target="_blank" class="square_btn">VJX-MKCRA</a>　
<a href="./doc/kiki_manual/VJX-MKCRA_etc_OP.pdf" target="_blank" class="square_btn">VJX-MKRA</a>　
<br><br>

<h6>らくタッチ対応機器</h6>
<a href="./doc/kiki_manual/VHX-M_A_OP.pdf" target="_blank" class="square_btn">VHX-MKR/A</a>　
<a href="./doc/kiki_manual/VHX-M「」_A_DASHWISM_OP.pdf" target="_blank" class="square_btn">VHX-MKR/A(DASHWISM)</a>　
<a href="./doc/kiki_manual/VHX-M_A_OP.pdf" target="_blank" class="square_btn">VHX-MKR/A-J</a>　
<a href="./doc/kiki_manual/VHX-M_A_OP.pdf" target="_blank" class="square_btn">VHX-MKR/A-J-COR</a>　
<br><br>
<a href="./doc/kiki_manual/VHX-MRM_A_OP.pdf" target="_blank" class="square_btn">VHX-MKRM/A-J</a>　
<a href="./doc/kiki_manual/VHX-MRM_A_OP.pdf" target="_blank" class="square_btn">VHX-MKRM/A-J-COR</a>　
<br><br>

<h6>DASHWISM7α対応機器</h6>
<a href="./doc/kiki_manual/VHX-M_A_OP.pdf" target="_blank" class="square_btn">VHX-MKR/A</a>　
<a href="./doc/kiki_manual/VHX-M「」_A_DASHWISM_OP.pdf" target="_blank" class="square_btn">VHX-MKR/A(DASHWISM)</a>　
<a href="./doc/kiki_manual/VHX-M_A_OP.pdf" target="_blank" class="square_btn">VHX-MKR/A-J</a>　
<a href="./doc/kiki_manual/VHX-M_A_OP.pdf" target="_blank" class="square_btn">VHX-MKR/A-J-COR</a>　
<br><br>
<a href="./doc/kiki_manual/VHX-MRM_A_OP.pdf" target="_blank" class="square_btn">VHX-MKRM/A-J</a>　
<a href="./doc/kiki_manual/VHX-MRM_A_OP.pdf" target="_blank" class="square_btn">VHX-MKRM/A-J-COR</a>　
<br><br>

<h6>VIXUS1Pr対応機器</h6>
<a href="./doc/kiki_manual/VJX-MKCRA_etc_OP.pdf" target="_blank" class="square_btn">VJX-MKCRA</a>　
<a href="./doc/kiki_manual/VJX-MKCRA_etc_OP.pdf" target="_blank" class="square_btn">VJX-MKRA</a>　
<br><br>

<h6>DASHWISM GP対応機器</h6>
<a href="./doc/kiki_manual/VHX-M_A_OP.pdf" target="_blank" class="square_btn">VHX-MKR/A</a>　
<a href="./doc/kiki_manual/VHX-M「」_A_DASHWISM_OP.pdf" target="_blank" class="square_btn">VHX-MKR/A(DASHWISM)</a>　
<a href="./doc/kiki_manual/VHX-M_A_OP.pdf" target="_blank" class="square_btn">VHX-MKR/A-J</a>　
<a href="./doc/kiki_manual/VHX-M_A_OP.pdf" target="_blank" class="square_btn">VHX-MKR/A-J-COR</a>　
<br><br>

<h6>PATMO対応機器</h6>
<a href="./doc/kiki_manual/GBX-MK_OP.pdf" target="_blank" class="square_btn">GBX-MK</a>　
<br><br>-->

<!--
<h6>その他</h6>
<a href="./doc/kiki_manual/QH-3KAT_OP.pdf" target="_blank" class="square_btn">QH-3KAT</a>　
<a href="./doc/kiki_manual/QHK-RMC-R_etc_OP.pdf" target="_blank" class="square_btn">QHK-RMC-Rシリーズ</a>　
<a href="./doc/kiki_manual/VH-3KAT_OP.pdf" target="_blank" class="square_btn">VH-3KAT</a>　
<a href="./doc/kiki_manual/VH-3KAU_OP.pdf" target="_blank" class="square_btn">VH-3KAU</a>　
<br><br>
<a href="./doc/kiki_manual/VH-3KVT_OP.pdf" target="_blank" class="square_btn">VH-3KVT</a>　
<a href="./doc/kiki_manual/VH-3KVU_OP.pdf" target="_blank" class="square_btn">VH-3KVU</a>　
<br><br>
-->

</form>


</div>






</div><!--content-all-->

<hr>

__SFooter__
__SCopyright__

</body>
</html>

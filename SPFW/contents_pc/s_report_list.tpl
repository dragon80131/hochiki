<html>
<head>

<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a5.css">
<script type="text/javascript" src="tools.js"></script>
__IfAjax__<script type="text/javascript" src="js/jquery-1.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__</head>


</head>
<title>リニューアル支援</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《管理レポート一覧》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>
<form action="s_form.php" method="POST" name="mainform" >
<input type=hidden name="rKey" value="__rKey__" >
<input type=hidden name="angle" value="" >

●決算月の2カ月前および3カ月前となっている物件リスト<br>（受注、失注、保留中は除く）
<a href="#" onclick="javascript:moveWithAngle('s_report.php?rKey=__rKey__',2 )">表示</a> <br>

<br>
●今月の現地調査実施物件
<a href="#" onclick="javascript:moveWithAngle('s_report.php?rKey=__rKey__',3 )">表示</a> <br>

<br>
●保留中物件
<a href="#" onclick="javascript:moveWithAngle('s_report.php?rKey=__rKey__',5 )">表示</a> <br>






</form>



<hr size="__HRSize__" color="__HRColor__">
<a href="s_search.php__QUERY__">トップ</a><br>
<hr size="__HRSize__" color="__HRColor__">
__SFooter__
__SCopyright__
</body>
</html>

<html>
<head>
<titleリニューアル支援</title>
<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a5.css">
<link rel="stylesheet" href="css/print.css" type="text/css" media="print" />

<script type="text/javascript" src="tools.js"></script>


<!-- BootstrapのCSS読み込み -->
<link href="../include/bootstrap/css/bootstrap.min.css" rel="stylesheet">


<link href="../css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery-1.4.2.js" type="text/javascript"></script>


</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__


<hr size="__HRSize__" color="__HRColor__">
<center>《アイホンさまからの依頼済み物件一覧》</center>
<hr size="__HRSize__" color="__HRColor__">

<!--　
<a href="s_search.php__QUERY__&editBukkenCD=__editBukkenCD__ ">トップ</a><br>

__IfModify__
<a href="s_menu.php__QUERY__&editBukkenCD=__editBukkenCD__ ">メニュー</a>
__IfModify__

__IfError__
<br><br>
<font color="red" >ログインしたユーザの所属以外の物件を登録・編集することはできません。所属を確認ください。<br></font>
__IfError__

__IfErrorBukkenName__
<br><br>
<font color="red" >物件名が入力されていません。<br></font>
__IfErrorBukkenName__
-->

<!--ログインユーザー：__LastName__　物件CD：__editBukkenCD__<br>-->

<br>アイホンさまから __GyosyaName__ さまに、工事依頼された物件が下記に表示されております。<br>
取り込みボタンをクリックしますと案件情報が取り込まれ人工表に反映されます。工事工程等にご利用ください。
<br>表示の案件は、すでにアイホン様からネスぺに日程調整依頼を承っておりますので、ネスぺに日程調整依頼は不要です。
<br>すでに登録されている物件の場合は、案件がダブりますので取込不要です。
<br>最新の50物件が表示されています。
<form action="s_plan.php" method="POST" name="mainform">

<table border=1>

<tr>
<td bgcolor="lightgrey">物件CD</td>
<td bgcolor="lightgrey">物件名</td>
<td bgcolor="lightgrey">登録日</td>
<td bgcolor="lightgrey">取り込み</td>
</tr>

__listBukkenLoop__
<tr>
<td>__listBukkenCD__</td>
<td>__listBukkenName__</td>
<td>__listUpdated__</td>
<td align="center">
__IfTorikomizumi__
取込済み
__IfTorikomizumi__

__IfTorikomimada__
<input type="hidden" name=listBukkenName[] value="__listBukkenName__">
<input type="checkbox" name=checkcopy[] value="__listBukkenCD__">
<input type="hidden" name=KyoyoStartDate[] value="__KyoyoStartDate__">
<input type="hidden" name=KyoyoEndDate[] value="__KyoyoEndDate__">
<input type="hidden" name=SenyuStartDate[] value="__SenyuStartDate__">
<input type="hidden" name=KSenyuEndDate[] value="__SenyuEndDate__">
__IfTorikomimada__

</td>
</tr>
__listBukkenLoop__

</table>
<br>

<input type=hidden name="rKey" value="__rKey__">
<input type=hidden name="work_rn" value="1">
<input type=hidden name="editBukkenCD" value="__editBukkenCD__">
<input type=submit value="  取り込み  "  class="btn btn-primary"  >
</form>
<input type="button" value=" 戻る " onClick="history.back();"  class="btn btn-primary" >





__SFooter__

__SCopyright__
<br>
</body>

</html>

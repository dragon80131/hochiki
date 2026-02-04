<html>
<head>

<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a5.css">
<script type="text/javascript" src="tools.js"></script>
<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
</head>
<title>営業活動支援システム</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《支店別　月ごとの利用状況》</center>
<hr size="__HRSize__" color="__HRColor__">
<a href="#" onClick="history.back();">＜＜前のページにもどる</a>

<br><br>

◆支店別　月ごとの利用状況　（システム利用開始日は2016年1月12日）<br>
__MonthLoop__

<table>
<!--<tr><td colspan=4 bgcolor="lightgrey"><b>2016/1/12　〜　__Today__　までの受付実績/システム利用実績</b></td></tr>-->
<tr><td colspan=4 bgcolor="sandybrown"><b>__DisplayMonth__</b></td></tr>

<tr bgcolor="lightgrey"><td>支店　　</td><td>登録・更新された物件数</td><td>物件新規登録</td><td>折衝記録登録数</td></tr>
<tr><td>札幌　　</td><td>__sitenUpBukkenCnt1__</td><td>__sitenNewBukkenCnt1__</td><td>__sitenTaioCnt1__</td></tr>
<tr><td>東北　　</td><td>__sitenUpBukkenCnt2__</td><td>__sitenNewBukkenCnt2__</td><td>__sitenTaioCnt2__</td></tr>
<tr><td>北関東　</td><td>__sitenUpBukkenCnt3__</td><td>__sitenNewBukkenCnt3__</td><td>__sitenTaioCnt3__</td></tr>
<tr><td>東京　　</td><td>__sitenUpBukkenCnt4__</td><td>__sitenNewBukkenCnt4__</td><td>__sitenTaioCnt4__</td></tr>
<tr><td>横浜　　</td><td>__sitenUpBukkenCnt5__</td><td>__sitenNewBukkenCnt5__</td><td>__sitenTaioCnt5__</td></tr>
<tr><td>名古屋　</td><td>__sitenUpBukkenCnt6__</td><td>__sitenNewBukkenCnt6__</td><td>__sitenTaioCnt6__</td></tr>
<tr><td>大阪　　</td><td>__sitenUpBukkenCnt7__</td><td>__sitenNewBukkenCnt7__</td><td>__sitenTaioCnt7__</td></tr>
<tr><td>中・四国</td><td>__sitenUpBukkenCnt8__</td><td>__sitenNewBukkenCnt8__</td><td>__sitenTaioCnt8__</td></tr>
<tr><td>九州　　</td><td>__sitenUpBukkenCnt9__</td><td>__sitenNewBukkenCnt9__</td><td>__sitenTaioCnt9__</td></tr>
<tr><td colspan=4 bgcolor="red"></td></tr>
<tr bgcolor="lightgoldenrodyellow"><td>支店合計</td><td>__UpCnt__</td><td>__NewCnt__</td><td>__TaioCnt__</td></tr>
</table>
<br>

__MonthLoop__


</form>

<hr size="__HRSize__" color="__HRColor__">
<a href="s_search.php__QUERY__">＜＜トップ</a><br>
<br>
<!--<a href="logout.php__QUERY__">ログアウト</a>-->

__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</html>

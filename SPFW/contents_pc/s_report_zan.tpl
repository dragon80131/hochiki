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
<center>《残工事のある物件一覧》</center>
<hr size="__HRSize__" color="__HRColor__">



物件名検索<br>
<form action="s_report_zan_search.php" method="POST" >
<input type="text" name="wBukkenName" value="__wBukkenName__" size="40">

<input type="hidden" name="rKey" value="__rKey__">
<input type="hidden" name="KensakuDisp" value="1">

<input type="submit" value=" 検 索 " >
</form>
<br>



残工事部屋のある物件一覧<br>
作業工程表と連動し情報が更新されます。また、この画面の「残修正」から完了状態に変更すると下記の一覧に表示されなくなります。<br>
残工事が６部屋以上の場合、省略して表示されます。すべての部屋を確認するには物件のリンクから「リニューアル残工事情報」を参照ください。<br>
<font color="red"><b>※不明・・・作業工程表に完了報告がないため取得できず</b></font><br>

<form action="s_report_zan.php?rKey=__rKey__" name="mainform" method="POST" >
<input type="hidden" name="editBukkenCD" value="">

__IfToTop__<a href="#" onClick="javascript:changePage('s_report_zan.php?rKey=__rKey__', 1, __AllPages__)">__IfToTop__&lt;&lt;__IfToTop__</a>__IfToTop__　
__IfToPre__<a href="#" onClick="javascript:changePage('s_report_zan.php?rKey=__rKey__', __PreviousPage__, __AllPages__)">__IfToPre__&lt;__IfToPre__</a>__IfToPre__　
<input type="text" name="myPage" value="__myPage__" size="4" __IME_OFF__ class="form">/__AllPages__
<input type="button" value="ページジャンプ" class="button" onclick="javascript:changePage('s_report_zan.php?rKey=__rKey__', '0', __AllPages__)">　
__IfToNext__<a href="#" onClick="javascript:changePage('s_report_zan.php?rKey=__rKey__', __NextPage__, __AllPages__)">__IfToNext__&gt;__IfToNext__</a>__IfToNext__　
__IfToLast__<a href="#" onClick="javascript:changePage('s_report_zan.php?rKey=__rKey__', __LastPage__, __AllPages__)">__IfToLast__&gt;&gt;__IfToLast__</a>__IfToLast__

　　[1ページあたり__cRowsPerPage__データ]


<table border=1>
<tr style="color:#ffffff" bgcolor="#4169E1">
<td>物件名</td>
<td>残部屋情報</td>
<td>施工業者</td>
<td>管理会社</td>
<td>未施工機器の保管場所</td>
</tr>

__BukkenLoop__
<tr>
<td><a href="./s_menu.php?rKey=__rKey__&editBukkenCD=__BukkenCD__">__BukkenName__</a></td>
<td>__ZanHeyaDisp__ </td>
<td>__GyosyaName1__ __GyosyaTantoName1__ __GyosyaName2__ __GyosyaTantoName2__ </td>
<td>__KanriGaisya__</td>
<td>__MansionMemo__</td>
</tr>
__BukkenLoop__

</table>

</form>


<br>

<hr size="__HRSize__" color="__HRColor__">
<!--<a href="#" onClick="history.back(); return false;">前に戻る</a><br>-->
<a href="s_search.php__QUERY__">トップ</a><br>
<hr size="__HRSize__" color="__HRColor__">

__SFooter__
__SCopyright__
</body>
</html>

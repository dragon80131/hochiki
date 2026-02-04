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
<center>《残工事のある物件》</center>
<hr size="__HRSize__" color="__HRColor__">



<b>検索結果</b>
<br><br>

__IfSearchOK__
工事が完了し予約センターにて未工事部屋の日程調整を行う都度、情報は更新されます。

<table border=1>
<tr style="color:#ffffff" bgcolor="#4169E1">
<td>物件名</td>
<td>残部屋情報</td>
<td>施工業者</td>
<td>管理会社</td>
<td>残部屋情報更新日</td>
</tr>

__BukkenLoop__
<tr>
<td><a href="./s_menu.php?rKey=__rKey__&editBukkenCD=__BukkenCD__">__BukkenName__</a></td>
<td>__ZanHeyaDisp__</td>
<td>__GyosyaName1__ __GyosyaTantoName1__ __GyosyaName2__ __GyosyaTantoName2__ </td>
<td>__KanriGaisya__</td>
<td>__ZanDate__</td>
</tr>
__BukkenLoop__

</table>
__IfSearchOK__

__IfSerachNG__
物件名が存在しません。<br>
__IfSerachNG__

<br>

<hr size="__HRSize__" color="__HRColor__">
<a href="#" onClick="history.back(); return false;">前に戻る</a><br>
<a href="s_search.php__QUERY__">トップ</a><br>
<hr size="__HRSize__" color="__HRColor__">

__SFooter__
__SCopyright__
</body>
</html>

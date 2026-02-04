<html>
<head>

<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>
</head>
<title>物件工程表管理</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《物件一覧》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>
<br>

<form action="s_form.php" metho="POST" >
<input type=hidden name="rKey" value="__rKey__" >

<input type=submit value=" 新規登録 " >
</form>

<br>
<table border=1><tr style="color:#ffffff" bgcolor="#4169E1" >
<td>物件CD</td>
<td>−</td>
<td>物件名</td>
<td>カラー</td>
<td>戸数</td>
<td>営業担当CD</td>
<td>物件備考</td>
<td>無効FLG</td>
<td>説明員数</td>
<td>共有部開始日</td>
<td>共有部終了日</td>
<td>専有部開始日</td>
<td>専有部終了日</td>
<td>確定フラグ</td>
<td>初期設定作業員</td>
<td>見積NO</td>
<td>受注額</td>
<td>請求先会社名　名前</td>
<td>現場担当</td>
<td>ネスペ</td>
<td>パネル手配</td>
<td>材料手配</td>
<td>機器手配</td>
<td>機器完成図手配</td>
<td>系統図</td>
<td>完工</td>
<td>入金予定日</td>
<td>削除</td>


</tr>
<form action="s_form.php" name="mainform" method="POST" >
<input type="hidden" name="editBukkenCD" value="">
<input type="hidden" name="work" value="">
<input type="hidden" name="rKey" value="__rKey__">
__BukkenLoop__
<tr>
<td>__BukkenCD__</td>
<td><input type="button" value="編集" class="button" onclick="javascript:moveWithKey('s_form.php', __BukkenCD__ )"></td>
<td>__BukkenName__</td>
<td>__BukkenColor__</td>
<td>__Kosu__</td>
<td>__TantoCD__</td>
<td>__BukkenNotes__</td>
<td>__MukouFlg__</td>
<td>__Setsumei__</td>
<td>__KyoyoStartDate__</td>
<td>__KyoyoEndDate__</td>
<td>__SenyuStartDate__</td>
<td>__SenyuEndDate__</td>
<td>__ConfirmFlg__</td>
<td>__DefaultSagyoin__</td>
<td>__MitsumoriNo__</td>
<td>__Jucyugaku__</td>
<td>__SeikyuNotes__</td>
<td>__GenbaTanto__</td>
<td>__Nespe__</td>
<td>__Panel__</td>
<td>__Zairyo__</td>
<td>__Kiki__</td>
<td>__Kanseizu__</td>
<td>__Keitozu__</td>
<td>__Complete__</td>
<td>__NyukinDate__</td>
<td><input type="button" value="削除" class="button" onclick="javascript:moveWithKeyAndWork( 's_finish.php', __BukkenCD__ , 2 ,__BukkenCD__ )"></td>

</tr>
__BukkenLoop__
</tr></table>

</form>

<hr size="__HRSize__" color="__HRColor__">
<a href="top.php__QUERY__">トップ</a><br>
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</html>

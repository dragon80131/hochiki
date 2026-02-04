<html>
<head>
<title>物件工程表管理</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《物件情報更新》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>
<br>



__IfError__
物件情報が更新できませんでした。
__IfError__

__IfOK__
__wBukkenName__　物件情報を更新しました。



<br>
<!--
<table border=1>
<tr><td nowrap class="common-list-value-left"    >物件CD</td><td>__editBukkenCD__</td></tr>
<tr><td nowrap class="common-list-value-left"    >物件名</td><td>__wBukkenName__</td></tr>
<tr><td nowrap class="common-list-value-left"    >カラー</td><td>__wBukkenColor__</td></tr>
<tr><td nowrap class="common-list-value-left"    >戸数</td><td>__wKosu__</td></tr>
<tr><td nowrap class="common-list-value-left"    >営業担当CD</td><td>__TantoName__</td></tr>
<tr><td nowrap class="common-list-value-left"    >物件備考</td><td>__wBukkenNotes__</td></tr>
<tr><td nowrap class="common-list-value-left"    >無効FLG</td><td>__wMukouFlg__</td></tr>
<tr><td nowrap class="common-list-value-left"    >説明員数</td><td>__wSetsumei__</td></tr>
<tr><td nowrap class="common-list-value-left"    >共有部開始日</td><td>__wKyoyoStartDate__</td></tr>
<tr><td nowrap class="common-list-value-left"    >共有部終了日</td><td>__wKyoyoEndDate__</td></tr>
<tr><td nowrap class="common-list-value-left"    >専有部開始日</td><td>__wSenyuStartDate__</td></tr>
<tr><td nowrap class="common-list-value-left"    >専有部終了日</td><td>__wSenyuEndDate__</td></tr>

<tr>
<td nowrap class="common-list-value-left">初期設定作業員</td>
<td>

__DefaultSagyoinLoop__
<input type="checkbox" name="wDefaultSagyoin[]" value="__wDefaultSagyoin__" "__wDefaultSagyoinChecked__">__wDefaultSagyoin__
__DefaultSagyoinLoop__

__SagyoinCheckLoop__
__SagyoinCheckName__
__SagyoinCheckLoop__

</td></tr>

<tr><td nowrap class="common-list-value-left">見積NO</td><td>__wMitsumoriNo__</td></tr>
<tr><td nowrap class="common-list-value-left">受注額</td><td>__wJucyugaku__</td></tr>
<tr><td nowrap class="common-list-value-left">請求先会社名　名前</td><td>__wSeikyuNotes__</td></tr>
<tr><td nowrap class="common-list-value-left">現場担当</td><td>__wGenbaTanto__</td></tr>
<tr><td nowrap class="common-list-value-left">ネスペ</td><td>__wNespe__</td></tr>
<tr><td nowrap class="common-list-value-left">パネル手配</td><td>__wPanel__</td></tr>
<tr><td nowrap class="common-list-value-left">材料手配</td><td>__wZairyo__</td></tr>
<tr><td nowrap class="common-list-value-left">機器手配</td><td>__wKiki__</td></tr>
<tr><td nowrap class="common-list-value-left">機器完成図手配</td><td>__wKanseizu__</td></tr>
<tr><td nowrap class="common-list-value-left">系統図</td><td>__wKeitozu__</td></tr>
<tr><td nowrap class="common-list-value-left">完工</td><td>__wComplete__</td></tr>
<tr><td nowrap class="common-list-value-left">入金予定日</td><td>__wNyukinDate__</td></tr>

</table>
-->

__IfOK__

__IfDeleteOK__

物件データを削除しました。<br>
__IfDeleteOK__

<br>





<hr size="__HRSize__" color="__HRColor__">
<a href="s_list.php__QUERY__">物件一覧</a><br>
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</head>
</html>

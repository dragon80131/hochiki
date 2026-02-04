<html>
<head>
<title>工程管理システム</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《物件登録・更新》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>
下記でよければ、確定ボタンをクリックしてください。<br>修正する場合は、戻るボタンをクリックしてください。<br>
<br>
<form action="s_finish.php" metho="POST">
<table border=1><tr>
<tr><td>物件CD</td><td>__editBukkenCD__</td></tr>
<tr><tr><td>物件名</td><td>__wBukkenName__</td></tr>
<tr><tr><td>戸数</td><td>__wKosu__</td></tr>
<tr><tr><td>営業担当CD</td><td>__TantoName__</td></tr>
<tr><tr><td>物件備考</td><td>__wBukkenNotes__</td></tr>

<tr><td>共有部開始日</td><td>__wKyoyoStartDate__</td></tr>
<tr><td>共有部終了日</td><td>__wKyoyoEndDate__</td></tr>
<tr><td>専有部開始日</td><td>__wSenyuStartDate__</td></tr>
<tr><td>専有部終了日</td><td>__wSenyuEndDate__</td></tr>
<tr><td>確定フラグ</td><td>__wConfirmFlg__</td></tr>
<tr>
<td>初期設定作業員</td><td>
__DefaultSagyoinLoop__
<input type="checkbox" name="wDefaultSagyoin" value="__wDefaultSagyoin__" "__wDefaultSagyoinChecked__">__wDefaultSagyoin__
__DefaultSagyoinLoop__
</td></tr>
<tr><td>工事番号</td><td>__wSetsumei__</td></tr>
<tr><td>物件URL</td><td>__wURL__</td></tr>

<tr><td bgcolor="wheat" >見積NO</td><td>__wMitsumoriNo__</td></tr>
<tr><td bgcolor="wheat" >受注額</td><td>__wJucyugaku__</td></tr>
<tr><td bgcolor="wheat" >請求先会社名　名前</td><td>__wSeikyuNotes__</td></tr>
<tr><td bgcolor="wheat" >現場担当</td><td>__wGenbaTanto__</td></tr>
<tr><td bgcolor="wheat" >ネスペ</td><td>__wNespe__</td></tr>
<tr><td bgcolor="wheat" >パネル手配</td><td>__wPanel__</td></tr>
<tr><td bgcolor="wheat" >材料手配</td><td>__wZairyo__</td></tr>
<tr><td bgcolor="wheat" >機器手配</td><td>__wKiki__</td></tr>
<tr><td bgcolor="wheat" >機器完成図手配</td><td>__wKanseizu__</td></tr>
<tr><td bgcolor="wheat" >系統図</td><td>__wKeitozu__</td></tr>

<tr><td bgcolor="wheat" >入金予定日</td><td>__wNyukinDate__</td></tr>
<tr><td>完工</td><td>__wwComplete__</td></tr>
<tr><td>登録状況</td><td>__wwRegiStatus__</td></tr>

</tr>




</tr>
</tr></table>
<br>
<input type=hidden name="rKey" value="__rKey__" >
<input type=hidden name="editBukkenCD" value="__editBukkenCD__" >

<!--ここに変数の値がhiddenでわたされる。-->
__HiddenValues__


<input type=submit value = "確　定" >
<br><br>
<input type=button onClick="history.back()" value = "戻　る" >


</form>




<hr size="__HRSize__" color="__HRColor__">
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</head>
</html>

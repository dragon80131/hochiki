<html>
<head>
<link rel="stylesheet" href="css/a6.css" type="text/css" /> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=sjis" />
<meta id="viewport" name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<title>専有部一覧</title>
<!--<link rel="stylesheet" href="stylesheets/iphone.css" />-->
<link rel="apple-touch-icon" href="images/apple-touch-icon.png" />
<script type="text/javascript">
function clickclear(thisfield, defaulttext) {
if (thisfield.value == defaulttext) {
thisfield.value = "";
}
}
function clickrecall(thisfield, defaulttext) {
if (thisfield.value == "") {
thisfield.value = defaulttext;
}
}
</script>
</head>


<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">

<hr size="__HRSize__" color="__HRColor__">
<center>《写真台帳作成サービス》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>

<table align=center><tr><td>
__MansionName__
<table border=1>
<tr><td rowspan=2>
<H3><B>写真台帳作成サービス利用方法</B></H3>
・「写真１」「写真２」をクリックし、写真をアップできます。iPhone、iPad、アンドロイド端末では、「ファイル選択」ボタンでカメラが起動します。<br>
・○は写真撮影済みを意味します。
・「Pic」リンクで部屋ごとの写真の確認および削除ができます。<br>
・「DL」リンクは、部屋ごとの写真をダウンロードできます。
以下の機能は、パソコンにて行います。<br>
・「専有部全写真表示」は、専有部の全写真が一覧表示されます。<br>
・「オプション項目追加」で写真の必要な項目を追加できます。<br>
・「全写真ダウンロード」は、共用部と専有部の写真をダウンロードすることができます。<br>
・ダウンロードしたファイルを解凍し、「自動作成Excelファイル」を利用しますと完成図書(工事写真部分)を瞬時に作成することができます。

</td><td>


<form method="post" action="./p2_list.php">
__HiddenValues__
<input type="submit"         value="オプション項目追加" >
</form>

</td>

<td>
<form method="post" action="./p2kyoyo.php">
__HiddenValues__
<input type="submit"         value="共用部の写真へ" >
</form>

<form method="post" action="./d2.php?rKey=__rKey__">
__HiddenValues__
<input type="submit" value="作業工程表へ戻る">
</form>

</td>

</tr>

<tr><td >

<form method="post" action="./p2pc.php">
__HiddenValues__
<input type="submit"         value="専有部全写真表示" >
</form>

<form method="post" action="./upfile/pic_file_select.php">
<input type=hidden name=MansionName value="__MansionName__">
__HiddenValues__
<input type="submit" value="全写真ダウンロード">
</form>
</td><td>
<INPUT type="button" name="botan3" value="自動作成Excelファイル" language="javascript" onclick="self.location.href='./AutoMake.xlsm'">
 </td><tr>


<tr><td colspan=3 align="center" >





専有部
<table class="sampleTable" >
<tr bgcolor="#dce0f5">
<td rowspan=2 valign="center">
<form method="post" action="p2.php"> 
<input type="hidden" name="OrderN" value="3,1" > 
<input type="hidden" name="rKey" value="__rKey__"> 
<input type="submit" value="▲" style="WIDTH: 40px; HEIGHT: 17px"> 
</form>
<form method="post" action="p2.php"> 
<input type="hidden" name="OrderN" value="1" > 
<input type="hidden" name="rKey" value="__rKey__"> 
<input type="submit" value="▼" style="WIDTH: 40px; HEIGHT: 17px"> 
</form>
</td>
<td colspan=4 align=center >室内親機</td>
<td colspan=4 align=center >玄関子機</td>
__OpLoop__
<td colspan=2 align=center >__OpName__</td>
__OpLoop__

    <td rowspan=2 >確認</td>
    <td rowspan=2 >DL</td>
</tr>
<tr bgcolor="#dce0f5">

　　<td colspan=2 align=center >施工前</td><td colspan=2 align=center >施工後</td>
　　<td colspan=2 align=center >施工前</td><td colspan=2 align=center >施工後</td>
__OpLoop__
<td colspan=2 align=center >__OpStatus__</td>
__OpLoop__
</tr>

__jLoop__
<tr __IfComp__ bgcolor="#c0c0c0" __IfComp__>
  <td><center><A name="__ID__">__ID__</A></center></td>
  <td>__MaeOya__</td><td>__MaeOya2__</td><td>__GoOya__</td><td>__GoOya2__</td>
  <td>__MaeKo__</td><td>__MaeKo2__</td><td>__GoKo__</td><td>__GoKo2__</td>
  __IfOp3__<td>__Op3__</td><td>__Op32__</td>__IfOp3__ 
  __IfOp4__<td>__Op4__</td><td>__Op42__</td>__IfOp4__
  __IfOp5__<td>__Op5__</td><td>__Op52__</td>__IfOp5__
  __IfOp6__<td>__Op6__</td><td>__Op62__</td>__IfOp6__
  __IfOp7__<td>__Op7__</td><td>__Op72__</td>__IfOp7__
  __IfOp8__<td>__Op8__</td><td>__Op82__</td>__IfOp8__
  <td><a href="./p2H.php?ID=__ID__&rKey=__rKey__">Pic</a></td>
  <td><a href="./upfile/pic_fileH_select.php?ID=__ID__&rKey=__rKey__">DL</a></td>

</tr>
__If3__
<tr bgcolor="#dce0f5">
<td rowspan=2 valign="center">部屋</td>
<td colspan=4 align=center >室内親機</td>
<td colspan=4 align=center >玄関子機</td>
__OpLoop__
<td colspan=2 align=center >__OpName__</td>
__OpLoop__

    <td rowspan=2 >確認</td>
    <td rowspan=2 >DL</td>
</tr>
<tr bgcolor="#dce0f5">

　　<td colspan=2 align=center >施工前</td><td colspan=2 align=center >施工後</td>
　　<td colspan=2 align=center >施工前</td><td colspan=2 align=center >施工後</td>
__OpLoop__
<td colspan=2 align=center >__OpStatus__</td>
__OpLoop__
</tr>
__If3__



__jLoop__


</table>
</td></tr></table>


</td></tr></table>

<hr size="__HRSize__" color="__HRColor__">
NetServicePowerhouse 
<br>
</body>

</html>

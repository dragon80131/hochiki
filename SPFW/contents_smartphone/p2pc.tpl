<html>
<head>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=sjis" />
<meta id="viewport" name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<title>専有部一覧</title>
<!--<link rel="stylesheet" href="stylesheets/iphone.css" /> -->
<link rel="stylesheet" href="css/a6.css" type="text/css" /> 
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

<script type="text/javascript" charset="utf-8">
window.onload = function() {
setTimeout(function(){window.scrollTo(0, 1);}, 100);
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
<table border=1
<tr><td rowspan=2>

・写真の右のチェックボックスにチェックをいれ、削除ボタンで削除できます。<br>
<br>


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

<form method="post" action="./p2.php">
__HiddenValues__
<input type="submit"         value="専有部写真UP" >
</form>

<form method="post" action="./upfile/pic_file_select.php">
<input type=hidden name=MansionName value="__MansionName__">
__HiddenValues__
<input type="submit" value="全写真ダウンロード">
</form>
</td><td>
<INPUT type="button" name="botan3" value="自動作成Excelファイル" language="javascript" onclick="self.location.href='./AutoMake.xls'">
 </td><tr>


<tr><td colspan=3 align="center" >



<ul class="data">
専有部
<table class="sampleTable" >
<tr bgcolor="#dce0f5">
<td rowspan=2 >部屋</td>
<td colspan=4 align=center >室内親機</td>
<td colspan=4 align=center >玄関子機</td>
__OpLoop__
<td align=center  colspan=2 >__OpName__</td>
__OpLoop__
    <td rowspan=2 >DL</td>
</tr>
<tr bgcolor="#dce0f5">
　　<td colspan=2 align=center >施工前</td><td colspan=2 align=center >施工後</td>
　　<td colspan=2 align=center >施工前</td><td colspan=2 align=center >施工後</td>
__OpLoop__
<td align=center colspan=2 >__OpStatus__</td>
__OpLoop__
</tr>

<form action="p2dpc.php" method="POST">

__jLoop__
<tr  >
  <td><center>__ID__</center></td>
  <td>__MaeOya__</td><td>__MaeOya2__</td><td>__GoOya__</td><td>__GoOya2__</td>
  <td>__MaeKo__</td><td>__MaeKo2__</td><td>__GoKo__</td><td>__GoKo2__</td>


  __IfOp3__<td>__Op3__</td><td>__Op32__</td>__IfOp3__ 
  __IfOp4__<td>__Op4__</td><td>__Op42__</td>__IfOp4__
  __IfOp5__<td>__Op5__</td><td>__Op52__</td>__IfOp5__
  __IfOp6__<td>__Op6__</td><td>__Op62__</td>__IfOp6__
  __IfOp7__<td>__Op7__</td><td>__Op72__</td>__IfOp7__
  __IfOp8__<td>__Op8__</td><td>__Op82__</td>__IfOp8__

  <td><a href="./upfile/pic_fileH_select.php?ID=__ID__&rKey=__rKey__">DL</a></td>

__If3__
<tr  bgcolor="#dce0f5">
<td rowspan=2 valign="center"  bgcolor="#c0c0c0" >部屋</td>
<td colspan=4 align=center >室内親機</td>
<td colspan=4 align=center >玄関子機</td>
__OpLoop__
<td align=center colspan=2 >__OpName__</td>
__OpLoop__


    <td rowspan=2 >DL</td>
</tr>
<tr bgcolor="#dce0f5">

　　<td colspan=2 align=center >施工前</td><td colspan=2 align=center >施工後</td>
　　<td colspan=2 align=center >施工前</td><td colspan=2 align=center >施工後</td>
__OpLoop__
<td align=center  colspan=2 >__OpStatus__</td>
__OpLoop__
</tr>
__If3__

</tr>
__jLoop__




</table>
</td></tr></table>
</ul>

__HiddenValues__

<input type=submit value=" 削 除 ">
</form>
<br><br>
<INPUT type="button" value=" もどる "
onclick="history.back()">


</td></tr></table>

<hr size="__HRSize__" color="__HRColor__">
NetServicePowerhouse 
<br>
</body>

</html>

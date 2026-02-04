<html>
<head>
<link rel="stylesheet" href="../css/a6.css" type="text/css" /> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=sjis" />
<meta id="viewport" name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<title>ダウンロード</title>
<!--<link rel="stylesheet" href="../stylesheets/iphone.css" />-->
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




<ul>
<table><tr><td>


採用する写真を選択し、ページ下の「採用写真確定」ボタンをクリックしてください。<br><br>
<INPUT type="button" value=" もどる "
onclick="history.back()">
<br>

__IfErr1__
ファイル書き込みに失敗しました<br>
__IfErr1__

__IfErr2__
ファイルロックに失敗しました<br>
__IfErr2__

__IfNoErr__
<br>ダウンロードの準備ができました。ダウンロードをクリックしてください。
<br><br><a href=./dl/image.zip>ダウンロード</a>

__IfNoErr__

<form action=pic_file_OK.php method=POST>
<table class="sampleTable" >
<tr  bgcolor="#dce0f5">
<td rowspan=2 >部屋</td>
<td colspan=4 align=center >室内親機</td>
<td colspan=4 align=center >玄関子機</td>
__OpLoop__
<td  colspan=2 align=center >__OpName__</td>
__OpLoop__

</tr>
<tr  bgcolor="#dce0f5">
　　<td  colspan=2 align=center >施工前</td><td  colspan=2 align=center>施工後</td>
　　<td  colspan=2 align=center >施工前</td><td  colspan=2 align=center>施工後</td>
__OpLoop__
<td  colspan=2 align=center >__OpStatus__</td>
__OpLoop__
</tr>

__jLoop__
<tr>
  <td><center>__ID__</center></td>
  <td>__ISDE111__</td><td>__ISDE112__</td><td>__ISDE211__</td><td>__ISDE212__</td>
  <td>__ISDE121__</td><td>__ISDE122__</td><td>__ISDE221__</td><td>__ISDE222__</td>

__IfOp13__
  <td>__ISDE131__</td><td>__ISDE132__</td>
__IfOp13__
__IfOp23__
  <td>__ISDE231__</td><td>__ISDE232__</td>
__IfOp23__

__IfOp14__
  <td>__ISDE141__</td><td>__ISDE142__</td>
__IfOp14__
__IfOp24__
  <td>__ISDE241__</td><td>__ISDE242__</td>
__IfOp24__


__IfOp15__
  <td>__ISDE151__</td><td>__ISDE152__</td>
__IfOp15__
__IfOp25__
  <td>__ISDE251__</td><td>__ISDE252__</td>
__IfOp25__

__IfOp16__
  <td>__ISDE161__</td><td>__ISDE162__</td>
__IfOp16__
__IfOp26__
  <td>__ISDE261__</td><td>__ISDE262__</td>
__IfOp26__

__IfOp17__
  <td>__ISDE171__</td><td>__ISDE172__</td>
__IfOp17__
__IfOp27__
  <td>__ISDE271__</td><td>__ISDE272__</td>
__IfOp27__


__IfOp18__
  <td>__ISDE181__</td><td>__ISDE182__</td>
__IfOp18__
__IfOp28__
  <td>__ISDE281__</td><td>__ISDE282__</td>
__IfOp28__

__If3__
<tr bgcolor="#dce0f5">
<td rowspan=2 valign="center">部屋</td>
<td colspan=4 align=center >室内親機</td>
<td colspan=4 align=center >玄関子機</td>
__OpLoop__
<td colspan=2 align=center >__OpName__</td>
__OpLoop__


</tr>
<tr bgcolor="#dce0f5">

　　<td colspan=2 align=center >施工前</td><td colspan=2 align=center >施工後</td>
　　<td colspan=2 align=center >施工前</td><td colspan=2 align=center >施工後</td>
__OpLoop__
<td colspan=2 align=center >__OpStatus__</td>
__OpLoop__
</tr>
__If3__


</tr>
__jLoop__



</table>

</td></tr><tr><td>
共用部
<table class=sampleTable>
<tr  bgcolor="#dce0f5" ><td>装置名</td><td colspan=2 >施工前</td><td colspan=2 >施工後</td></tr>

<tr><td>__KDeviceName1__</td><td>__SDE1101__</td><td>__SDE1102__</td><td>__SDE2101__</td><td>__SDE2102__</td></tr>

<tr><td>__KDeviceName2__</td><td>__SDE1111__</td><td>__SDE1112__</td><td>__ISDE2111__</td><td>__SDE2112__</td></tr>

<tr><td>__KDeviceName3__</td><td>__SDE1121__</td><td>__SDE1122__</td><td>__ISDE2121__</td><td>__SDE2122__</td></tr>

<tr><td>__KDeviceName4__</td><td>__SDE1131__</td><td>__SDE1132__</td><td>__SDE2131__</td><td>__SDE2132__</td></tr>

<tr><td>__KDeviceName5__</td><td>__SDE1141__</td><td>__SDE1142__</td><td>__SDE2141__</td><td>__SDE2142__</td></tr>

<tr><td>__KDeviceName6__</td><td>__SDE1151__</td><td>__SDE1152__</td><td>__SDE2151__</td><td>__SDE2152__</td></tr>

<tr><td>__KDeviceName7__</td><td>__SDE1161__</td><td>__SDE1162__</td><td>__SDE2161__</td><td>__SDE2162__</td></tr>

<tr><td>__KDeviceName8__</td><td>__SDE1171__</td><td>__SDE1172__</td><td>__SDE2171__</td><td>__SDE2172__</td></tr>

</table>
<br>
<br>
採用する写真を選択し、下の採用写真確定ボタンをクリックしてください。<br>
<input type=hidden name=jLoop value=__jLoop__>

__HiddenValues__
<input type=submit value=" 採用写真確定 " >
<br><br>
<INPUT type="button" value=" もどる "
onclick="history.back()">
<br>
</td></tr></table>
</ul>

</td></tr></table>

<hr size="__HRSize__" color="__HRColor__">
NetServicePowerhouse 


</body>
</html>

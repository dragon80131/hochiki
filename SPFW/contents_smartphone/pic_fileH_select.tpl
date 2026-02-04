<html>
<head>
<!--<link rel="stylesheet" href="css/a5.css" type="text/css" /> -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=sjis" />
<meta id="viewport" name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<title>ダウンロード</title>
<link rel="stylesheet" href="../stylesheets/iphone.css" />
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

<form action=pic_fileH_OK.php method=POST>
<table class="sampleTable" >
<tr>
<td rowspan=2 >部屋</td>
<td colspan=4 align=center >室内親機</td>
<td colspan=4 align=center >玄関子機</td>
__OpLoop__
<td  colspan=2 align=center >__OpName__</td>
__OpLoop__

</tr>
<tr>
　　<td  colspan=2 align=center >施工前</td><td  colspan=2 align=center>施工後</td>
　　<td  colspan=2 align=center >施工前</td><td  colspan=2 align=center>施工後</td>
__OpLoop__
<td  colspan=2 align=center >__OpStatus__</td>
__OpLoop__
</tr>


<tr>
  <td><center>__ID__</center></td>
  <td>__SDE111__</td><td>__SDE112__</td><td>__SDE211__</td><td>__SDE212__</td>
  <td>__SDE121__</td><td>__SDE122__</td><td>__SDE221__</td><td>__SDE222__</td>

__IfOp13__
  <td>__SDE131__</td><td>__SDE132__</td>
__IfOp13__
__IfOp23__
  <td>__SDE231__</td><td>__SDE232__</td>
__IfOp23__

__IfOp14__
  <td>__SDE141__</td><td>__SDE142__</td>
__IfOp14__
__IfOp24__
  <td>__SDE241__</td><td>__SDE242__</td>
__IfOp24__


__IfOp15__
  <td>__SDE151__</td><td>__SDE152__</td>
__IfOp15__
__IfOp25__
  <td>__SDE251__</td><td>__SDE252__</td>
__IfOp25__

__IfOp16__
  <td>__SDE161__</td><td>__SDE162__</td>
__IfOp16__
__IfOp26__
  <td>__SDE261__</td><td>__SDE262__</td>
__IfOp26__

__IfOp17__
  <td>__SDE171__</td><td>__SDE172__</td>
__IfOp17__
__IfOp27__
  <td>__SDE271__</td><td>__SDE272__</td>
__IfOp27__


__IfOp18__
  <td>__SDE181__</td><td>__SDE182__</td>
__IfOp18__
__IfOp28__
  <td>__SDE281__</td><td>__SDE282__</td>
__IfOp28__



</tr>



</table>

<br>
<br>
採用する写真を選択し、下の採用写真確定ボタンをクリック<br>
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

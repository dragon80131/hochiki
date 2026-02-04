<html>
<head>
<title>営業活動支援システム</title>
<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a7.css">
<script type="text/javascript" src="tools.js"></script>

</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__

<hr size="__HRSize__" color="__HRColor__">
<center>《現場写真　メモ》</center>
<hr size="__HRSize__" color="__HRColor__">
<br>

<font color="red"><b>
※「㈱、㈲、Ⅰ、Ⅱ、Ⅲ、①、②、㍉、℡」のような環境依存文字は文字化けしますので使用しないでください。※
</b></font>
<br>

写真に対するメモを記載します。<br>

<form action="s_pic.php?editBukkenCD=__editBukkenCD__" method="POST" name="mainform">
<input type=hidden name=editBukkenCD value=__editBukkenCD__ >
<input type=hidden name=editPictureCD value=__editPictureCD__ >
<input type=hidden name=work value=3 >
<input type=hidden name=rKey value="__rKey__" >

<table class="sampleTable">
<tr>
<td bgcolor="#98FB98">メモ</td>
<td><input type=text name=editMemo value="__editMemo__" size=40></td>
</tr>
</table>

<br>
<input type=submit value=更新 >
</form>

<hr size="__HRSize__" color="__HRColor__">
<br>

</body>
</html>

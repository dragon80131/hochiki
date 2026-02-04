<html>
<head>

<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a5.css">
<script type="text/javascript" src="tools.js"></script>
</head>
<title>Êª·ï¹©ÄøÉ½´ÉÍý</title>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>¡Ôºî¶È°÷ÅÐÏ¿´ÉÍý¡Õ</center>
<hr size="__HRSize__" color="__HRColor__">
<br>

<form action="s_sagyoin_detail.php" method="POST" >
<input type=hidden name="rKey" value="__rKey__" >

<a href="#" onclick="javascript:move('s_plan.php?rKey=__rKey__')">¡ä¡ä¡ä¡¡ºî¶È¹©ÄøÉ½</a> <br>
<a href="#" onclick="javascript:move('s_sagyoin_detail.php?rKey=__rKey__')">¡ä¡ä¡ä¡¡¿·µ¬ÅÐÏ¿</a> <br>

</form>


<form action="s_sagyoin_list.php" name="mainform" method="POST">
<input type="hidden" name="editSagyoinCD" value="" >
<input type="hidden" name="work" value="1" >
<table class="sampleTable"><tr style="color:#ffffff" bgcolor="#4169E1" >
<td>ºî¶È°÷CD</td>
<td>ºî¶È°÷Ì¾</td>
<td>Í½È÷</td>
<td>¾ÜºÙ/ÊÔ½¸</td>
<td>ºï½ü</td>

</tr>

__SagyoinListLoop__
<tr>
<td>__SagyoinCD__</td>
<td>__SagyoinName__</td>
<td>__Sa001__</td>
<td><input type="button" value="ÊÔ½¸" class="button" onclick="javascript:moveWithSagyoinCD( 's_sagyoin_detail.php?rKey=__rKey__', __SagyoinCD__  )"></td>
<td><input type="button" value="ºï½ü" class="button" onclick="javascript:moveWithKeyAndWork3( 's_sagyoin_list.php?rKey=__rKey__', __SagyoinCD__ , 2 , __SagyoinCD__ )"></td>
</tr>
__SagyoinListLoop__

</tr></table>

</form>

<hr size="__HRSize__" color="__HRColor__">
<a href="s_list.php__QUERY__">Êª·ï°ìÍ÷</a><br>
__SFooter__
<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>
</body>
</html>

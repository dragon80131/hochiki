<html>
<head>
<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<title>Êó¹ð½ñºîÀ®</title>

<!--[if IE]><script type="text/javascript" src="./html5jp/excanvas/excanvas.js"></script><![endif]-->
<script type="text/javascript" src="./html5jp/graph/circle.js"></script>
<script type="text/javascript">
window.onload = function() {
  var cg = new html5jp.graph.circle("sample");
  if( ! cg ) { return; }
  var items = [
    ["ÆüÄøÊÑ¹¹", __CountTa__ ],
    ["Æü»þ³ÎÇ§", __CountTb__ ],
    ["¥ª¥×¥·¥ç¥ó", __CountTc__ ],
    ["¹©»ö¤Ë´Ø¤¹¤ë", __CountTd__ ],
    ["µ¡´ï¤Ë´Ø¤¹¤ë", __CountTe__ ],
    ["ÉÔºßÉ½", __CountTf__ ],
    ["¤½¤ÎÂ¾", __CountTg__ ]
  ];
  cg.draw(items);

  var cg2 = new html5jp.graph.circle("sample2");
  if( ! cg2 ) { return; }
  var items2 = [
    ["ÅÚÆü", __Donichi__ ],
    ["Ê¿Æü", __DonichiIgai__ ]
  ];
  cg2.draw(items2);

};
</script>

</head>

<body>

<form  method="POST" action="report_confirm.php">

<table border=0>
<tr ><td><img src=./images/space.gif width="22" height="30"></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td><img src=./images/space.gif width="22" height="30"></td></tr>
<tr><td></td><td colspan=9></td><td></td></tr>
<tr><td></td><td colspan=9><font size="4">__Company__</font></td><td></td></tr>
<tr><td></td><td colspan=9><img src=./images/space.gif width="18" height="350"></td><td></td></tr>
<tr><td></td><td align="center" colspan=9><H2>__Subject__</H2></td><td></td></tr>
<tr><td></td>
  <td align="center" colspan=9><p><font size="+1"><img src=./images/space.gif width="18" height="75">¡Ê¡¡__MansionName__¡¡¡Ë</font></p>
    </td><td></td></tr>
<tr><td></td><td align="center" colspan=9><img src=./images/space.gif width="18" height="330"></td><td></td></tr>
<tr><td></td><td align="center" colspan=9><font size="+1">__Today__</font></td><td></td></tr>
<tr><td></td><td align="center" colspan=9></td><td></td></tr>
<tr><td></td><td align="center" colspan=9><font size="+1">³ô¼°²ñ¼Ò¥Í¥¹¥Ú</font></td><td></td></tr>
<tr><td></td><td align="center" colspan=9><img src=./images/space.gif width="18" height="70"></td><td></td></tr>
<tr><td></td><td align="center" colspan=9></td><td></td></tr>
<tr><td></td><td colspan=9>__Explain__ </td><td></td></tr>
<tr><td><img src=./images/space.gif></td>
<td>£±¡¥</td>
<td colspan="8">¹©»ö´ðËÜ¾ðÊó</td><td></td></tr>
<tr><td></td><td></td>
  <td colspan="2">¥Þ¥ó¥·¥ç¥ó</td>
  <td colspan="6">__MansionName__</td><td></td></tr>
<tr><td></td><td></td>
  <td colspan="2">½»½ê</td>
  <td colspan="6">__BukenAddress__ </td><td></td></tr>
<tr><td></td><td></td>
  <td colspan="2">Áí¸Í¿ô</td><td>__CountID__¸Í</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td>
  <td colspan="2">¹©»öÆâÍÆ</td><td colspan="6">__KojiContents__</td><td></td></tr>
<tr><td></td><td></td>
<td colspan="2">¹©»ö´ü´Ö</td>
<td colspan="6">__ReserveStart__ ¡Á __ReserveEnd__</td><td></td></tr>
<tr><td></td><td></td>
<td colspan="2">Í½ÄêÉ½ÇÛÉÛÆü</td>
<td colspan="6">__YoteiDate__</td><td></td></tr>
<tr><td></td><td></td>
<td colspan="2">·èÄêÉ½ÇÛÉÛÆü</td>
<td colspan="6">__CloseDate__</td><td></td></tr>
<tr><td><img src=./images/space.gif></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td>
<td>£²¡¥</td>
<td colspan="8">¼ïÎàÊÌ¼õÉÕ¾õ¶·</td>
<td></td></tr>
<tr>
  <td>&nbsp;</td>
  <td></td>
  <td colspan="8" bordercolor="#999999">
  <table border="1" bordercolor="#999999">
 
 <tr>
  <td  align=center width="60" bordercolor="#999999">ÆüÄøÊÑ¹¹</td>
  <td  align=center width="60" bordercolor="#999999">¹©»öÆü»þ<br>
    ³ÎÇ§</td>
  <td  align=center width="60" bordercolor="#999999">¥ª¥×¥·¥ç¥ó<br>
    ¿½¹þ¤ß</td>
  <td  align=center width="60" bordercolor="#999999">¹©»ö¤Ë´Ø¤¹¤ë<br>
    Ìä¹ç¤»</td>
  <td  align=center width="60" bordercolor="#999999">µ¡´ï¤Ë´Ø¤¹¤ë<br>
    Ìä¹ç¤»</td>
  <td  align=center width="60" bordercolor="#999999">ÉÔºßÉ½¤Î<br>
    Ï¢Íí</td>
  <td  align=center width="60" bordercolor="#999999">¤½¤ÎÂ¾</td>
  <td  align=center width="60" bordercolor="#999999">¹ç·×</td>
  <td></td></tr>
<tr>
<td  align=center bordercolor="#999999">__CountTa__ </td>
<td  align=center bordercolor="#999999">__CountTb__</td>
<td  align=center bordercolor="#999999">__CountTc__</td>
<td  align=center bordercolor="#999999">__CountTd__</td>
<td  align=center bordercolor="#999999">__CountTe__</td>
<td  align=center bordercolor="#999999">__CountTf__</td>
<td  align=center bordercolor="#999999">__CountTg__</td>
  <td  align=center bordercolor="#999999">__CountTsum__</td>
  <td></td>
</tr>
<tr>
<td  align=center bordercolor="#999999">__CountIa__</td>
<td  align=center bordercolor="#999999">__CountIb__</td>
<td  align=center bordercolor="#999999">__CountIc__</td>
<td  align=center bordercolor="#999999">__CountId__</td>
<td  align=center bordercolor="#999999">__CountIe__</td>
<td  align=center bordercolor="#999999">__CountTf__</td>
<td  align=center bordercolor="#999999">__CountIg__</td>
  <td  align=center bordercolor="#999999">__CountIsum__</td>
  <td></td>
</tr>
<tr>
<td  align=center bordercolor="#999999">__CountKa__</td>
<td  align=center bordercolor="#999999">__CountKb__</td>
<td  align=center bordercolor="#999999">__CountKc__</td>
<td  align=center bordercolor="#999999">__CountKd__</td>
<td  align=center bordercolor="#999999">__CountKe__</td>
<td  align=center bordercolor="#999999">__CountKf__</td>
<td  align=center bordercolor="#999999">__CountKg__</td>
  <td  align=center bordercolor="#999999">__CountKsum__</td>
  <td></td>
</tr>
  </table></td>
  <td></td>
</tr>
<tr><td></td><td></td>
<tr><td></td><td></td>
  <td colspan="8">¢¨ ¾åÃÊ¡§¤¹¤Ù¤Æ¤ÎÌä¹ç¤»·ï¿ô¡ÊWEB¤ÏÆüÄøÊÑ¹¹¡¢¹©»öÆü»þ³ÎÇ§¡¢¥ª¥×¥·¥ç¥ó¿½¹þ¤ß¤òÈ¿±Ç¡Ë</td><td></td></tr>
<tr><td></td><td></td>
  <td colspan="8">¡¡ ÃæÃÊ¡§¥¢¥¤¥Û¥óÍÍ¡¦»Ü¹©²ñ¼ÒÍÍ¤è¤êÏ¢Íí¤Î¤¢¤Ã¤¿·ï¿ô</td><td></td></tr>
<tr><td></td><td></td>
  <td colspan="8">¡¡ ²¼ÃÊ¡§´ÉÍý²ñ¼ÒÍÍ¡¢´ÉÍý°÷ÍÍ¤è¤êÏ¢Íí¤Î¤¢¤Ã¤¿·ï¿ô</td><td></td></tr>
<tr>
  <td>&nbsp;</td>
  <td></td>
  <td colspan="7"></td>
  <td colspan="2"></td>
</tr>
<tr><td></td><td></td>
  <td colspan="7">¼ïÎà¤´¤È¤ÎÌä¹ç¤»·ï¿ô±ß¥°¥é¥Õ<br><div><canvas width="480" height="360" id="sample"></canvas></div>
</td>
  <td colspan="2"></td>
</tr>
<tr><td></td><td></td><td><img src=./images/space.gif  width="18" height="85"></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td>
  <td>£³¡¥</td>
  <td colspan="8">Ê¿Æü¡¦ÅÚÆü½ËÆüÊÌ¼õÉÕ¾õ¶·</td><td></td></tr>

<tr>
  <td>&nbsp;</td>
  <td></td>
  <td></td>
  <td colspan="4" bordercolor="#999999">
  
  <table border=1 bordercolor="#999999">
  
  <tr><td></td>
  <td width="60" align=center  bordercolor="#999999">Ê¿Æü</td>
  <td width="60"  align=center bordercolor="#999999">ÅÚÆü½Ë</td>
  <td width="60" align=center  bordercolor="#999999">¹ç·×</td>
  <td></td><td></td><td></td><td></td></tr>
<tr>
  <td bordercolor="#999999"><p>Ìä¹ç¤»</p>
    </td>
  <td  align=center bordercolor="#999999">__DonichiIgai__</td>
  <td  align=center bordercolor="#999999">__Donichi__</td>
  <td  align=center bordercolor="#999999">__DonichiSum__</td>
  <td></td><td></td><td></td><td></td></tr>
  
  </table>
  
  </td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
</tr>

<tr><td></td><td></td><td colspan="8"></td><td></td></tr>
<tr><img src=./images/space.gif><td></td><td></td>
  <td colspan="7">Ê¿Æü¡¦ÅÚÆü½ËÆüÊÌ·ï¿ô±ß¥°¥é¥Õ<br><div><canvas width="320" height="240" id="sample2"></canvas></div>
</td>
  <td colspan="2"></td>
</tr>
<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td><img src=./images/space.gif></td>
  <td>£´¡¥</td>
  <td colspan="8">ÊÑ¹¹³ä¹çÅù</td><td></td></tr>
<tr><td></td><td></td>
  <td colspan="8">­¡Í½Ìó¥»¥ó¥¿¡¼¤ËÏ¢Íí¤Î¤¢¤Ã¤¿¸Í¿ô</td><td></td></tr>
<tr><td></td><td></td><td></td><td></td>
<td colspan="2">__CountName__ ¡¿__CountID__¡¡¡ÊÁí¸Í¿ô¡Ë¡¡</td>
<td><p>__CountNameRate__¡ó</p>
  </td>
<td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td><img src=./images/space.gif></td><td></td>
<td colspan="8">­¢¹©»öÆüÊÑ¹¹³ä¹ç</td>
<td></td></tr>
<tr><td><img src=./images/space.gif></td><td></td><td></td>
  <td colspan="7">¹©»öÆü¤ÎÊÑ¹¹¤Î¿½¤·¹þ¤ß¤¬¤¢¤Ã¤¿¸Í¿ô</td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td colspan="2">__CountID1Loop__ ¡¿ __CountID__ ¡ÊÁí¸Í¿ô¡Ë¡¡</td>
  <td>__CountID1Rate__¡ó</td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td>
  <td colspan="8">­£ÅÅÏÃ¡¿WEB¼õÉÕ³ä¹ç</td><td></td></tr>
<tr><td></td><td></td><td></td>
  <td colspan="7">¾ò·ï¤òÆ±¤¸¤Ë¤¹¤ë¤¿¤á¤Ë¡¢·èÄê°ÆÆâÁ°¤Î¸Í¿ô¤òÈæ³Ó¤·¤Æ¤¤¤Þ¤¹¡£</td><td></td></tr>
<tr><td></td><td></td><td></td><td></td>
  <td colspan="6">WEB¼õÉÕ¸Í¿ô¡¡¡¿¡¡·èÄê°ÆÆâÇÛÉÛÁ°¤ËÏ¢Íí¤Î¤¢¤Ã¤¿¸Í¿ô</td><td></td></tr>
<tr><td></td><td></td><td></td><td></td>
  <td colspan="2">__CountWeb__  ¡¿  __CountIDAC__ ¡¡   </td><td><p>__CountWebRate__¡ó</p>
    </td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>

<tr><td><img src=./images/space.gif></td><td></td>
  <td colspan="8">­¤·èÄê°ÆÆâÄÌÃÎ°Ê¹ß¤Î¹©»öÆüÊÑ¹¹³ä¹ç</td><td></td></tr>

<tr><td></td><td></td><td></td>
  <td></td>
  <td colspan="2">__CountAC__ ¡¿  __CountID__ ¡ÊÁí¸Í¿ô¡Ë</td><td><p>__CountACRate__¡ó</p>
<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>

<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td><img src=./images/space.gif></td><td></td><td colspan="8">


__IfOP__

<table border=1 bordercolor="#999999">
¥ª¥×¥·¥ç¥ó¤Ë¤Ä¤¤¤Æ
<tr>
  <td>¥ª¥×¥·¥ç¥óÆâÌõ</td>
__IfMenu1__  <td width="60" align=center bordercolor="#999999" >__MenuName1__</td>
__IfMenu1__
__IfMenu2__  <td width="60" align=center bordercolor="#999999" >__MenuName2__</td>
__IfMenu2__
__IfMenu3__  <td width="60" align=center bordercolor="#999999" >__MenuName3__</td>
__IfMenu3__
__IfMenu4__  <td width="60" align=center bordercolor="#999999" >__MenuName4__</td>
__IfMenu4__
__IfMenu5__  <td width="60" align=center bordercolor="#999999" >__MenuName5__</td>
__IfMenu5__
__IfMenu6__  <td width="60" align=center bordercolor="#999999" >__MenuName6__</td>
__IfMenu6__
__IfMenu7__  <td width="60" align=center bordercolor="#999999" >__MenuName7__</td>
__IfMenu7__
 <td></td></tr>

<tr>
  <td>&nbsp;</td>
__IfMenu1__  <td align=center bordercolor="#999999" >__CountMenu1__</td>
__IfMenu1__
__IfMenu2__  <td align=center bordercolor="#999999" >__CountMenu2__</td>
__IfMenu2__
__IfMenu3__  <td align=center bordercolor="#999999" >__CountMenu3__</td>
__IfMenu3__
__IfMenu4__  <td align=center bordercolor="#999999" >__CountMenu4__</td>
__IfMenu4__
__IfMenu5__  <td align=center bordercolor="#999999" >__CountMenu5__</td>
__IfMenu5__
__IfMenu6__  <td align=center bordercolor="#999999" >__CountMenu6__</td>
__IfMenu6__
__IfMenu7__  <td align=center bordercolor="#999999" >__CountMenu7__</td>
__IfMenu7__
  <td></td></tr>
</table>

 __IfOP__



</td><td></td></tr>
<tr><td><img src=./images/space.gif></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr>
  <td>&nbsp;</td>
  <td>£µ¡¥</td>
  <td>¤ªµÒÍÍ¤ÎÀ¼</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td></td>
  <td colspan="8">__Voice__</td>
  <td></td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td></td>
  <td colspan="8"></td>
  <td></td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td></td>
  <td colspan="8"> </td>
  <td></td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td></td>
  <td colspan="8"></td>
  <td></td>
</tr>



</table>

<br><br>

</form>

</body>
</html>

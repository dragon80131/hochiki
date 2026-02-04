<html>
<head>
<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<title>報告書作成</title>

<!--[if IE]><script type="text/javascript" src="./html5jp/excanvas/excanvas.js"></script><![endif]-->
<script type="text/javascript" src="./html5jp/graph/circle.js"></script>
<script type="text/javascript">
window.onload = function() {
  var cg = new html5jp.graph.circle("sample");
  if( ! cg ) { return; }
  var items = [
    ["日程変更", __CountTa__ ],
    ["日時確認", __CountTb__ ],
    ["オプション", __CountTc__ ],
    ["工事に関する", __CountTd__ ],
    ["機器に関する", __CountTe__ ],
    ["不在表", __CountTf__ ],
    ["その他", __CountTg__ ]
  ];
  cg.draw(items);

  var cg2 = new html5jp.graph.circle("sample2");
  if( ! cg2 ) { return; }
  var items2 = [
    ["土日", __Donichi__ ],
    ["平日", __DonichiIgai__ ]
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
  <td align="center" colspan=9><p><font size="+1"><img src=./images/space.gif width="18" height="75">（　__MansionName__　）</font></p>
    </td><td></td></tr>
<tr><td></td><td align="center" colspan=9><img src=./images/space.gif width="18" height="330"></td><td></td></tr>
<tr><td></td><td align="center" colspan=9><font size="+1">__Today__</font></td><td></td></tr>
<tr><td></td><td align="center" colspan=9></td><td></td></tr>
<tr><td></td><td align="center" colspan=9><font size="+1">株式会社ネスペ</font></td><td></td></tr>
<tr><td></td><td align="center" colspan=9><img src=./images/space.gif width="18" height="70"></td><td></td></tr>
<tr><td></td><td align="center" colspan=9></td><td></td></tr>
<tr><td></td><td colspan=9>__Explain__ </td><td></td></tr>
<tr><td><img src=./images/space.gif></td>
<td>１．</td>
<td colspan="8">工事基本情報</td><td></td></tr>
<tr><td></td><td></td>
  <td colspan="2">マンション</td>
  <td colspan="6">__MansionName__</td><td></td></tr>
<tr><td></td><td></td>
  <td colspan="2">住所</td>
  <td colspan="6">__BukenAddress__ </td><td></td></tr>
<tr><td></td><td></td>
  <td colspan="2">総戸数</td><td>__CountID__戸</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td>
  <td colspan="2">工事内容</td><td colspan="6">__KojiContents__</td><td></td></tr>
<tr><td></td><td></td>
<td colspan="2">工事期間</td>
<td colspan="6">__ReserveStart__ 〜 __ReserveEnd__</td><td></td></tr>
<tr><td></td><td></td>
<td colspan="2">予定表配布日</td>
<td colspan="6">__YoteiDate__</td><td></td></tr>
<tr><td></td><td></td>
<td colspan="2">決定表配布日</td>
<td colspan="6">__CloseDate__</td><td></td></tr>
<tr><td><img src=./images/space.gif></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td>
<td>２．</td>
<td colspan="8">種類別受付状況</td>
<td></td></tr>
<tr>
  <td>&nbsp;</td>
  <td></td>
  <td colspan="8" bordercolor="#999999">
  <table border="1" bordercolor="#999999">
 
 <tr>
  <td  align=center width="60" bordercolor="#999999">日程変更</td>
  <td  align=center width="60" bordercolor="#999999">工事日時<br>
    確認</td>
  <td  align=center width="60" bordercolor="#999999">オプション<br>
    申込み</td>
  <td  align=center width="60" bordercolor="#999999">工事に関する<br>
    問合せ</td>
  <td  align=center width="60" bordercolor="#999999">機器に関する<br>
    問合せ</td>
  <td  align=center width="60" bordercolor="#999999">不在表の<br>
    連絡</td>
  <td  align=center width="60" bordercolor="#999999">その他</td>
  <td  align=center width="60" bordercolor="#999999">合計</td>
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
  <td colspan="8">※ 上段：すべての問合せ件数（WEBは日程変更、工事日時確認、オプション申込みを反映）</td><td></td></tr>
<tr><td></td><td></td>
  <td colspan="8">　 中段：アイホン様・施工会社様より連絡のあった件数</td><td></td></tr>
<tr><td></td><td></td>
  <td colspan="8">　 下段：管理会社様、管理員様より連絡のあった件数</td><td></td></tr>
<tr>
  <td>&nbsp;</td>
  <td></td>
  <td colspan="7"></td>
  <td colspan="2"></td>
</tr>
<tr><td></td><td></td>
  <td colspan="7">種類ごとの問合せ件数円グラフ<br><div><canvas width="480" height="360" id="sample"></canvas></div>
</td>
  <td colspan="2"></td>
</tr>
<tr><td></td><td></td><td><img src=./images/space.gif  width="18" height="85"></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td>
  <td>３．</td>
  <td colspan="8">平日・土日祝日別受付状況</td><td></td></tr>

<tr>
  <td>&nbsp;</td>
  <td></td>
  <td></td>
  <td colspan="4" bordercolor="#999999">
  
  <table border=1 bordercolor="#999999">
  
  <tr><td></td>
  <td width="60" align=center  bordercolor="#999999">平日</td>
  <td width="60"  align=center bordercolor="#999999">土日祝</td>
  <td width="60" align=center  bordercolor="#999999">合計</td>
  <td></td><td></td><td></td><td></td></tr>
<tr>
  <td bordercolor="#999999"><p>問合せ</p>
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
  <td colspan="7">平日・土日祝日別件数円グラフ<br><div><canvas width="320" height="240" id="sample2"></canvas></div>
</td>
  <td colspan="2"></td>
</tr>
<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td><img src=./images/space.gif></td>
  <td>４．</td>
  <td colspan="8">変更割合等</td><td></td></tr>
<tr><td></td><td></td>
  <td colspan="8">①予約センターに連絡のあった戸数</td><td></td></tr>
<tr><td></td><td></td><td></td><td></td>
<td colspan="2">__CountName__ ／__CountID__　（総戸数）　</td>
<td><p>__CountNameRate__％</p>
  </td>
<td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td><img src=./images/space.gif></td><td></td>
<td colspan="8">②工事日変更割合</td>
<td></td></tr>
<tr><td><img src=./images/space.gif></td><td></td><td></td>
  <td colspan="7">工事日の変更の申し込みがあった戸数</td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td colspan="2">__CountID1Loop__ ／ __CountID__ （総戸数）　</td>
  <td>__CountID1Rate__％</td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td>
  <td colspan="8">③電話／WEB受付割合</td><td></td></tr>
<tr><td></td><td></td><td></td>
  <td colspan="7">条件を同じにするために、決定案内前の戸数を比較しています。</td><td></td></tr>
<tr><td></td><td></td><td></td><td></td>
  <td colspan="6">WEB受付戸数　／　決定案内配布前に連絡のあった戸数</td><td></td></tr>
<tr><td></td><td></td><td></td><td></td>
  <td colspan="2">__CountWeb__  ／  __CountIDAC__ 　   </td><td><p>__CountWebRate__％</p>
    </td><td></td><td></td><td></td><td></td></tr>
<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>

<tr><td><img src=./images/space.gif></td><td></td>
  <td colspan="8">④決定案内通知以降の工事日変更割合</td><td></td></tr>

<tr><td></td><td></td><td></td>
  <td></td>
  <td colspan="2">__CountAC__ ／  __CountID__ （総戸数）</td><td><p>__CountACRate__％</p>
<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>

<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td><img src=./images/space.gif></td><td></td><td colspan="8">


__IfOP__

<table border=1 bordercolor="#999999">
オプションについて
<tr>
  <td>オプション内訳</td>
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
  <td>５．</td>
  <td>お客様の声</td>
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

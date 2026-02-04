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
<tr ><td><font color="#FFFFFF">1234567</font></td>
  <td><font color="#FFFFFF">1234567</font></td>
  <td><font color="#FFFFFF">1234567</font></td>
  <td><font color="#FFFFFF">1234567</font></td>
  <td><font color="#FFFFFF">1234567</font></td>
  <td><font color="#FFFFFF">1234567</font></td>
  <td><font color="#FFFFFF">1234567</font></td>
  <td><font color="#FFFFFF">1234567</font></td>
  <td><font color="#FFFFFF">1234567</font></td>
  <td><font color="#FFFFFF">1234567</font></td>
  <td><font color="#FFFFFF">1234567</font></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td colspan=9><font size="4">以下の各項目を確認し、追記及び修正し「報告書作成ボタン」をクリックしてください。</font></td><td><font color="#FFFFFF">1234567</font></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td colspan=9><font size="4"><input type=text name=Company value="アイホン株式会社　様" width="100"></font></td><td><font color="#FFFFFF">1234567</font></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td colspan=9></td><td><font color="#FFFFFF">1234567</font></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td align="center" colspan=9><font size="6"><input type=text name=Subject value="インターホン工事変更受付報告書" width="200" ></font></td><td><font color="#FFFFFF">1234567</font></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td>
  <td align="center" colspan=9><p><font size="+1">（　__MansionName__　）</font></p>
    </td><td><font color="#FFFFFF">1234567</font></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td align="center" colspan=9></td><td><font color="#FFFFFF">1234567</font></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td align="center" colspan=9><font size="+1">__Today__</font></td><td><font color="#FFFFFF">1234567</font></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td align="center" colspan=9></td><td><font color="#FFFFFF">1234567</font></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td align="center" colspan=9><font size="+1">株式会社ネスペ</font></td><td><font color="#FFFFFF">1234567</font></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td align="center" colspan=9></td><td><font color="#FFFFFF">1234567</font></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td align="center" colspan=9></td><td><font color="#FFFFFF">1234567</font></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td colspan=9><textarea name=Explain rows=2 cols=100 >「__MansionName__」のインターホン工事変更受付について、以下の通りご報告いたします。</textarea></td><td><font color="#FFFFFF">1234567</font></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td>
<td>１．</td>
<td colspan="8">工事基本情報</td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td>
  <td colspan="2">マンション</td>
  <td colspan="6">__MansionName__</td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td>
  <td colspan="2">住所</td>
  <td colspan="6"><input type=text name="BukenAddress" value="" style="width:300px;" ></td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td>
  <td colspan="2">総戸数</td><td>__CountID__</td><td>戸</td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td>
  <td colspan="2">工事内容</td><td colspan="6"><input type=text name="KojiContents" value="インターホン交換工事" width="300"></td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td>
<td colspan="2">工事期間</td>
<td colspan="6">__ReserveStart__ 〜 __ReserveEnd__</td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td>
<td colspan="2">予定表配布日</td>
<td colspan="6"><input type=text name="YoteiDate" value="__YoteiDateDisp__" style="width:180px;" ></td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td>
<td colspan="2">決定表配布日</td>
<td colspan="6"><input type=text name="CloseDate" value="__CloseDateDisp__" style="width:180px;" ></td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td>
<td>２．</td>
<td colspan="8">種類別受付状況</td>
<td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td>
  <td>日程変更</td>
  <td>工事日時<br>
    確認</td>
  <td>オプション<br>
    申込み</td>
  <td>工事に関する<br>
    問合せ</td>
  <td>機器に関する<br>
    問合せ</td>
  <td>不在表の<br>
    連絡</td>
  <td>その他</td>
  <td>合計</td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td>
<td>__CountTa__ </td>
<td>__CountTb__</td>
<td>__CountTc__</td>
<td>__CountTd__</td>
<td>__CountTe__</td>
<td>__CountTf__</td>
<td>__CountTg__</td>
  <td>__CountTsum__</td>
  <td></td>
</tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td>
<td>__CountIa__</td>
<td>__CountIb__</td>
<td>__CountIc__</td>
<td>__CountId__</td>
<td>__CountIe__</td>
<td>__CountTf__</td>
<td>__CountIg__</td>
  <td>__CountIsum__</td>
  <td></td>
</tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td>
<td>__CountKa__</td>
<td>__CountKb__</td>
<td>__CountKc__</td>
<td>__CountKd__</td>
<td>__CountKe__</td>
<td>__CountKf__</td>
<td>__CountKg__</td>
  <td>__CountKsum__</td>
  <td></td>
</tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td>
  <td colspan="8">※1アイホン様に確認した件数</td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td>
  <td colspan="8">※2管理会社様、管理員様に確認した件数</td><td></td></tr>
<tr>
  <td>&nbsp;</td>
  <td></td>
  <td colspan="7"></td>
  <td colspan="2"></td>
</tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td>
  <td colspan="7">種類ごとの問合せ件数円グラフ<br><div><canvas width="400" height="300" id="sample"></canvas></div>
</td>
  <td colspan="2"></td>
</tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td>
  <td>３．</td>
  <td colspan="8">平日・土日受付状況</td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td><td></td><td></td>
  <td>平日</td>
  <td>土日祝</td>
  <td>合計</td><td></td><td></td><td></td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td><td></td>
  <td><p>問合せ</p>
    </td>
  <td>__DonichiIgai__</td>
  <td>__Donichi__</td>
  <td>__DonichiSum__</td><td></td><td></td><td></td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td><td colspan="8"></td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td>
  <td colspan="7">平日・土日件数円グラフ<br><div><canvas width="400" height="300" id="sample2"></canvas></div>
</td>
  <td colspan="2"></td>
</tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td>
  <td>４．</td>
  <td colspan="8">変更割合等</td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td>
  <td colspan="8">①予約センターに連絡のあった戸数</td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td><td></td><td></td>
<td colspan="2">__CountName__ ／__CountID__　（総戸数）　</td>
<td><p>__CountNameRate__％</p>
  </td>
<td></td><td></td><td></td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td>
<td colspan="8">②工事日変更割合</td>
<td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td><td></td>
  <td colspan="7">工事日の変更の申し込みがあった戸数</td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td><td></td><td></td><td colspan="2">__CountID1Loop__ ／ __CountID__ （総戸数）　</td>
  <td>__CountID1Rate__％</td><td></td><td></td><td></td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td>
  <td colspan="8">③電話／WEB受付割合</td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td><td></td>
  <td colspan="7">条件を同じにするために、決定案内前の戸数を比較しています。</td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td><td></td><td></td>
  <td colspan="6">WEB受付戸数　／　決定案内配布前に連絡のあった戸数</td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td><td></td><td></td>
  <td colspan="2">__CountWeb__  ／  __CountIDAC__ 　</td>
  <td><p>__CountWebRate__％</p>
    </td><td></td><td></td><td></td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td>
  <td colspan="8">④決定案内通知以降の工事日変更割合</td><td></td></tr>

<tr><td></td><td></td><td></td>
  <td></td>
  <td>__CountAC__ ／  __CountID__ （総戸数）</td><td><p>__CountACRate__％</p></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td>
  <td>オプション内訳</td>
__IfMenu1__  <td align=center >__MenuName1__</td>__IfMenu1__
__IfMenu2__  <td align=center >__MenuName2__</td>__IfMenu2__
__IfMenu3__  <td align=center >__MenuName3__</td>__IfMenu3__
__IfMenu4__  <td align=center >__MenuName4__</td>__IfMenu4__
__IfMenu5__  <td align=center >__MenuName5__</td>__IfMenu5__
__IfMenu6__  <td align=center >__MenuName6__</td>__IfMenu6__
__IfMenu7__  <td align=center >__MenuName7__</td>__IfMenu7__
 <td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td>
  <td></td>
__IfMenu1__  <td align=center >__CountMenu1__</td>__IfMenu1__
__IfMenu2__  <td align=center >__CountMenu2__</td>__IfMenu2__
__IfMenu3__  <td align=center >__CountMenu3__</td>__IfMenu3__
__IfMenu4__  <td align=center >__CountMenu4__</td>__IfMenu4__
__IfMenu5__  <td align=center >__CountMenu5__</td>__IfMenu5__
__IfMenu6__  <td align=center >__CountMenu6__</td>__IfMenu6__
__IfMenu7__  <td align=center >__CountMenu7__</td>__IfMenu7__
  <td></td></tr>
<tr><td><font color="#FFFFFF">1234567</font></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
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
  <td colspan="8"><textarea cols=100 rows=7 name=Voice>
__CountContentsLoop__
__CountContents__ , 
__CountContentsLoop__
</textarea> </td>
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
  <td colspan="8"> <input type="submit" value="　報告書作成　"></td>
  <td></td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td></td>
  <td colspan="8">修正する場合は、ブラウザの「戻る」を使ってください。</td>
  <td></td>
</tr>



</table>

__HiddenValues__

<br><br>

</form>

</body>
</html>

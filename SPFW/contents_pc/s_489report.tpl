<html>
<head>
<title>報告書作成</title>

<!--[if IE]><script type="text/javascript" src="./html5jp/excanvas/excanvas.js"></script><![endif]-->
<script type="text/javascript" src="./html5jp/graph/circle.js"></script>
<script type="text/javascript" src="tools.js"></script>
<script type="text/javascript">
window.onload = function() {
  var cg = new html5jp.graph.circle("sample");
  if( ! cg ) { return; }
  var items = [
    ["日程変更", __CountTa__ ],
    ["日時確認", __CountTb__ ],
    ["オプション", __CountTc__ ],
    ["機器に関する", __CountTd__ ],
    ["工事に関する", __CountTe__ ],
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
<style>
.sakusei {
	width:200px;
	text-align: center;
	border: 1px solid #15aeec;
	background-color: #49c0f0;
	background-image: -webkit-linear-gradient(top, #49c0f0, #2cafe3);
	background-image: linear-gradient(to bottom, #49c0f0, #2cafe3);
	border-radius: 4px;
	color: #fff;
	line-height: 50px;
	-webkit-transition: none;
	transition: none;
	text-shadow: 0 1px 1px rgba(0, 0, 0, .3);
}
.sakusei:hover {
	border:1px solid #1090c3;
	background-color: #1ab0ec;
	background-image: -webkit-linear-gradient(top, #1ab0ec, #1a92c2);
	background-image: linear-gradient(to bottom, #1ab0ec, #1a92c2);
}
.sakusei:active {
	background: #1a92c2;
	box-shadow: inset 0 3px 5px rgba(0, 0, 0, .2);
	color: #1679a1;
	text-shadow: 0 1px 1px rgba(255, 255, 255, .5);
}
</style>

</head>

<body>

<h2 style="border-bottom: 2px dotted #89c3eb;border-left: 10px solid #89c3eb;padding: 7px;">報告書作成・ダウンロード</h2>


<form action="s_489report_create.php" method="POST" name="mainform" >
<input type=hidden name="rKey" value="__rKey__" >

以下の内容で報告書を作成します。エクセルの報告書がダウンロードされます。<br>
※物件によっては、予約センターの登録状況により、データの誤差や、報告書がうまく表示されない場合もあります。<br>
<div class="sakusei" onclick="javascript:move('s_489report_create.php?editBukkenCD=__editBukkenCD__&BukkenCD489=__BukkenCD489__')">
報告書を作成する</div>

</form>


<a href="javascript:history.back()">＜＜　前のページに戻る</a>
<br><br>




<table width="800"><tr><td>
<hr size="__HRSize__" color="gray">
アイホン株式会社　御中<br>
<center>

<H2>インターホン工事変更受付報告書</H2><br>
（　__MansionName__　）<br><br>

<font size="+1">__dispHoukokusyoDay__</font><br><br>
<font size="+1">株式会社ネスペ</font><br><br><br>

</center>

</td></tr>
<tr><td>
<hr size="__HRSize__" color="gray">

「__MansionName__」のインターホン工事変更受付について、<br>以下の通りご報告いたします。<br><br>
</td></tr>
<tr><td>

	<h3>１．工事基本情報</h3>
	<table border=1 cellspacing="0" bordercolor="#505050" width="700">
	<tr><td>マンション</td>
		<td>　__MansionName__</td></tr>
	<tr><td>住所</td>
		<td>　__Address__ </td></tr>
	<tr><td>総戸数</td>
		<td>　__CountID__　戸</td></tr>
	<tr><td>工事内容</td>
		<td>　インターホン交換工事</td></tr>
	<tr><td>工事期間</td>
		<td>　__KojiStart__ 〜 __KojiEnd__</td></tr>
	<tr><td>予定表配布日</td>
		<td>　__dispYoyakuStart__</td></tr>
	<tr><td>決定表配布日</td>
		<td>　__dispKeteiDate__</td></tr>
	</table>
	<br>

</td></tr>
<tr><td>

	<h3>２．種類別電話受付状況</h3>
	<table border="1" cellspacing="0"  bordercolor="#505050" width="700">
	<tr bgcolor="#dcdcdc">
		<td align="center" width="90" bordercolor="#999999">日程変更</td>
		<td align="center" width="90" bordercolor="#999999">工事日時<br>確認</td>
		<td align="center" width="90" bordercolor="#999999">オプション<br>申し込み</td>
		<td align="center" width="90" bordercolor="#999999">工事に関する<br>問合せ</td>
		<td align="center" width="90" bordercolor="#999999">機器に関する<br>問合せ</td>
	<!--<td align="center" width="90" bordercolor="#999999">不在表の<br>連絡</td>
		<td align="center" width="90" bordercolor="#999999">その他</td>-->
		<td align="center" width="90" bordercolor="#999999">合計</td>
	</tr> 
	<tr height="35">
		<td align="center" bordercolor="#999999">__CountTa__ 件 </td>
		<td align="center" bordercolor="#999999">__CountTb__ 件</td>
		<td align="center" bordercolor="#999999">__CountTc__ 件</td>
		<td align="center" bordercolor="#999999">__CountTd__ 件</td>
		<td align="center" bordercolor="#999999">__CountTe__ 件</td>
	<!--<td align="center" bordercolor="#999999">__CountTf__ 件</td>
		<td align="center" bordercolor="#999999">__CountTg__ 件</td>-->
		<td align="center" bordercolor="#999999">__CountTsum__ 件</td>
	</tr>
	<!--
	<tr height="35">
		<td align="center" bordercolor="#999999">(__CountIa__ 件)</td>
		<td align="center" bordercolor="#999999">(__CountIb__ 件)</td>
		<td align="center" bordercolor="#999999">(__CountIc__ 件)</td>
		<td align="center" bordercolor="#999999">(__CountId__ 件)</td>
		<td align="center" bordercolor="#999999">(__CountIe__ 件)</td>
		<td align="center" bordercolor="#999999">(__CountTf__ 件)</td>
		<td align="center" bordercolor="#999999">(__CountIg__ 件)</td>
		<td align="center" bordercolor="#999999">(__CountIsum__ 件)</td>
	</tr>-->
	</table>

	<br>
<!--	種類ごとの問合せ件数円グラフ<br>
	※エクセルDLにて表示<br>
	<div><canvas width="480" height="360" id="sample"></canvas></div>-->


</td></tr>
<tr><td>

	<h3>３．曜日別受付状況</h3>
	<table border=1 cellspacing="0" bordercolor="#505050" width="350">
	<tr bgcolor="#dcdcdc">
		<td width="90"></td>
		<td width="90" align="center" bordercolor="#999999">平日</td>
		<td width="90" align="center" bordercolor="#999999">土日祝</td>
		<td width="90" align="center" bordercolor="#999999">合計</td>
	</tr>
	<tr height="35">
		<td bordercolor="#999999"><p>問合せ</p></td>
		<td align="center" bordercolor="#999999">__DonichiIgai__ 件</td>
		<td align="center" bordercolor="#999999">__Donichi__ 件</td>
		<td align="center" bordercolor="#999999">__DonichiSum__ 件</td>
	</tr>
	</table>

	<br>
<!--	平日・土日祝日別件数円グラフ<br>
	※エクセルDLにて表示<br>
	<div><canvas width="320" height="240" id="sample2"></canvas></div>-->

</td></tr>
<tr><td>
<hr size="__HRSize__" color="gray">

	<h3>４．変更割合等</h3>
	<table width="700">
	<tr><td colspan="3">①予約センター、WEB受付で受付けた戸数</td></tr>
	<tr style="padding-bottom: 50px"><td>　</td>
		<td>　__CountName__ ／__CountID__　（総戸数）　</td>
		<td><p>__CountNameRate__％</p></td></tr>
	<tr><td colspan="3">　</td></tr>

	<tr><td colspan="3">②工事日変更割合</td></tr>
	<tr><td>　</td>
		<td colspan="2"><font size="2">工事日の変更の申し込みがあった戸数</font></td></tr>
	<tr><td>　</td>
		<td>　__CountID1Loop__ ／ __CountID__ （総戸数）　</td>
		<td>__CountID1Rate__％</td></tr>
	<tr><td colspan="3">　</td></tr>

	<tr><td colspan="3">③電話／WEB受付割合<font size="2">（WEB受付がない場合も本項目は表示されます）</font></td></tr>
	<tr><td>　</td>
		<td colspan="2"><font size="2">条件を同じにするために、決定案内前の戸数を比較しています。</font></td></tr>
	<tr><td>　</td>
		<td colspan="2">WEB受付戸数　／　決定案内配布前に連絡のあった戸数</td></tr>
	<tr><td>　</td>
		<td>　__CountWeb__  ／  __CountIDAC__</td>
		<td><p>__CountWebRate__％</p></td></tr>
	<tr><td colspan="3">　</td></tr>

	<tr><td colspan="3">④決定案内通知以降の工事日変更割合</td></tr>
	<tr><td>　</td>
		<td>　__CountAC__ ／  __CountID__ （総戸数）</td>
		<td><p>__CountACRate__％</p></td><tr>
	<tr><td colspan="3">　</td></tr>

<!--
__IfOP__
	<tr><td colspan=3>⑤オプションについて</td></tr>
	<tr><td>　</td>
		<td>オプション内訳</td>
		<td>
			<table border=1 cellspacing="0" bordercolor="#505050">
			__IfMenu1__  <td width="60" bgcolor="#dcdcdc" align=center bordercolor="#999999" >__MenuName1__</td>__IfMenu1__
			__IfMenu2__  <td width="60" bgcolor="#dcdcdc" align=center bordercolor="#999999" >__MenuName2__</td>__IfMenu2__
			__IfMenu3__  <td width="60" bgcolor="#dcdcdc" align=center bordercolor="#999999" >__MenuName3__</td>__IfMenu3__
			__IfMenu4__  <td width="60" bgcolor="#dcdcdc" align=center bordercolor="#999999" >__MenuName4__</td>__IfMenu4__
			__IfMenu5__  <td width="60" bgcolor="#dcdcdc" align=center bordercolor="#999999" >__MenuName5__</td>__IfMenu5__
			__IfMenu6__  <td width="60" bgcolor="#dcdcdc" align=center bordercolor="#999999" >__MenuName6__</td>__IfMenu6__
			__IfMenu7__  <td width="60" bgcolor="#dcdcdc" align=center bordercolor="#999999" >__MenuName7__</td>__IfMenu7__
			</tr><tr>
			__IfMenu1__  <td align=center bordercolor="#999999" >__CountMenu1__</td>__IfMenu1__
			__IfMenu2__  <td align=center bordercolor="#999999" >__CountMenu2__</td>__IfMenu2__
			__IfMenu3__  <td align=center bordercolor="#999999" >__CountMenu3__</td>__IfMenu3__
			__IfMenu4__  <td align=center bordercolor="#999999" >__CountMenu4__</td>__IfMenu4__
			__IfMenu5__  <td align=center bordercolor="#999999" >__CountMenu5__</td>__IfMenu5__
			__IfMenu6__  <td align=center bordercolor="#999999" >__CountMenu6__</td>__IfMenu6__
			__IfMenu7__  <td align=center bordercolor="#999999" >__CountMenu7__</td>__IfMenu7__
			</tr>
			</table>
		</td></tr>
__IfOP__
-->

__IfOption__
	<tr><td colspan="3">⑤オプションについて</td></tr>
	<tr><td>　</td>
		<td colspan="2">申込住戸数　__dispMenutCountJuuko__</td>
	</tr>
	<tr>
		<td>　</td>
		<td colspan="2">
			オプション内訳
			<table border=1 cellspacing="0" bordercolor="#505050">
				<tr>
<!--				<tr><td rowspan="2" bordercolor="#505050" align="center">オプション<br>内訳</td>-->
				__disp1MenuLoop__
					<td align="center" bgcolor="#FFDDFF" width="90">__disp1MenuName__</td>
				__disp1MenuLoop__
				</tr>
				<tr>
				__disp1MenuLoopsuu__
					<td align="center">__disp1MenutCount__</td>
				__disp1MenuLoopsuu__
				</tr>


				<tr>
				__disp2MenuLoop__
					<td align="center" bgcolor="#FFDDFF" width="90">__disp2MenuName__</td>
				__disp2MenuLoop__
				</tr>
				<tr>
				__disp2MenuLoopsuu__
					<td align="center">__disp2MenutCount__</td>
				__disp2MenuLoopsuu__
				</tr>
			</table>

__IfOption__

	</table>
	<br>

</td></tr>
<tr><td>

	<h3>５．お客様の声</h3>
	<table>
	<tr><td>__Voice__</td></tr>
	</table>

</td></tr></table>
<br><br>
以上

</body>
</html>

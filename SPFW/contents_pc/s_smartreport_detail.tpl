<html>
<head>
<link rel="stylesheet" href="css/a7.css" type="text/css" />
<script type="text/javascript" src="tools.js"></script>
<title>	リニューアル支援</title>
__SHeader__

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">

<hr size="__HRSize__" color="__HRColor__">
<center>《スマレポ　詳細画面》</center>
<hr size="__HRSize__" color="__HRColor__">

<br>

<form action=gen_edit.php method=POST  name="mainform">
<input type=hidden name=editGenbaCD value="">
<input type=hidden name=work value="">
__HiddenValues__

<table><!--②-->
<tr><td align="center">


<table border=1 class="sampleTable" width="700"><!--①-->
<tr bgcolor="#99ccff"><td colspan="4">◆詳細情報</td></tr>
<tr><td bgcolor="#CCE5FF" width="100">受付番号</td>
	<td bgcolor="#F7EDEA" width="300">__UketukeNo__</td>
	<td bgcolor="#CCE5FF" width="100">郵便番号</td>
	<td width="100">__Yubin__</td></tr>
<tr><td bgcolor="#CCE5FF">現場名</td>
	<td bgcolor="__bcG__">__GenbaName__</td>
	<td bgcolor="#CCE5FF">修理依頼日</td>
	<td>__Created__</td></tr>
<tr><td bgcolor="#CCE5FF">マンション名</td>
	<td bgcolor="__bcM__">__MansionName__</td>
	<td bgcolor="#CCE5FF">完了</td>
	<td>__CompState__</td></tr>

<tr><td colspan="4"></td></tr>
<tr><td bgcolor="#CCE5FF" width="100">故障状態</td>
	<td bgcolor="#F7EDEA" colspan="3">__KoshoState__</td></tr>
<tr><td bgcolor="#CCE5FF">故障内容</td>
	<td bgcolor="#F7EDEA" colspan="3">__KoshoDetail__</td></tr>
<tr><td bgcolor="#CCE5FF">作業内容</td>
	<td bgcolor="#F7EDEA" colspan="3">__WorkDetail__</td></tr>
<!--<tr><td bgcolor="#CCE5FF">ステータス</td>
	<td colspan="3">__TaioStatus__</td></tr>-->

<tr><td colspan="4"></td></tr>
<tr><td bgcolor="#CCE5FF">備考欄</td>
	<td colspan="3">__Note__</td></tr>
</table><!--①-->

<font color="red">※背景に色のついている箇所が報告書に反映されます。</font><br>

</form>

<br>

<table border=1 cellspacing="5" frame="void" style="border-spacing : 10px 0px;"><!--③-->
<tr bgcolor="#99ccff"><td colspan="6">◆写真一覧</td></tr>
<tr bgcolor="#99ccff" align="center">
	<td>項目No</td>
	<td>項目1</td>
	<td>項目2</td>
	<td>項目3</td>
	<td>項目4</td>
	<td>項目5</td>
</tr>
<tr>
	<td bgcolor="#CCE5FF">修理前</td>
	<td>__MAE1Loop__ __MAE1__ __MAE1Loop__</td>
	<td>__MAE2Loop__ __MAE2__ __MAE2Loop__</td>
	<td>__MAE3Loop__ __MAE3__ __MAE3Loop__</td>
	<td>__MAE4Loop__ __MAE4__ __MAE4Loop__</td>
	<td>__MAE5Loop__ __MAE5__ __MAE5Loop__</td>
</tr>
<tr>
	<td bgcolor="#CCE5FF">修理中</td>
	<td>__CHU1Loop__ __CHU1__ __CHU1Loop__</td>
	<td>__CHU2Loop__ __CHU2__ __CHU2Loop__</td>
	<td>__CHU3Loop__ __CHU3__ __CHU3Loop__</td>
	<td>__CHU4Loop__ __CHU4__ __CHU4Loop__</td>
	<td>__CHU5Loop__ __CHU5__ __CHU5Loop__</td>
</tr>
<tr>
	<td bgcolor="#CCE5FF">修理後</td>
	<td>__GO1Loop__ __GO1__ __GO1Loop__</td>
	<td>__GO2Loop__ __GO2__ __GO2Loop__</td>
	<td>__GO3Loop__ __GO3__ __GO3Loop__</td>
	<td>__GO4Loop__ __GO4__ __GO4Loop__</td>
	<td>__GO5Loop__ __GO5__ __GO5Loop__</td>
</tr>
<tr>
	<td bgcolor="#CCE5FF">その他</td>
	<td colspan=5>__MAE6Loop__ __MAE6__ __MAE6Loop__</td>
</tr>
</table><!--③-->


<br><br>

<table border="1" class="sampleTable"><!--④-->
<tr bgcolor="#99ccff"><td colspan="2">◆メニュー</td></tr>
<tr><td>
		<form action="./upfile/s_smartreport_pic_OK.php?rKey=__rKey__" method="post">
		<input type=hidden name=editGenbaCD value=__editGenbaCD__>
		<input type=submit value="全写真ダウンロード">
		</form>
	</td>
	<td>
		画像ファイルのダウンロードができます。<br>
	</td>
</tr>
<tr><td>
		<form action="./upfile/s_smartreport_ex_OK.php" method="post">
		<input type=hidden name=editGenbaCD value=__editGenbaCD__>
		<input type=hidden name=UketukeNo value=__UketukeNo__>
		<input type=submit value="報告書Excelダウンロード">
		</form>
	</td>
	<td>
		報告書Excelのダウンロードができます。
	</td>
</tr>
</table><!--④-->

<br>

</td></tr>
</table><!--②-->


<br>
<hr size="__HRSize__" color="__HRColor__">
<a href="#" onClick="history.back(); return false;">前にもどる</a><br><br>
<a href="s_search.php?rKey=__rKey__">トップ</a><br>
<hr size="__HRSize__" color="__HRColor__">
__SFooter__
__SCopyright__
</body>
</html>

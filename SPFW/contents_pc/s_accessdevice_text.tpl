<html>
<head>

<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a5.css">
<script type="text/javascript" src="tools.js"></script>
<title>リニューアル支援</title>

<style type='text/css'>
<!--
table.a tr:hover {
  background-color: #FFCC99;    /* マウスオーバー時の行の背景色 */
}
-->
</style>

</head>
<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《アプリログインログ》</center>
<hr size="__HRSize__" color="__HRColor__">
<!--<a href="javascript:history.back();">＜＜戻る</a>-->


<table>
<tr><td colspan="3" bgcolor="lightgray">表の見方</td></tr>
<tr><td style='color:#ffffff' bgcolor='#4169E1'>時間</td>
	<td>ログインした時間</td><td></td></tr>
<tr><td style='color:#ffffff' bgcolor="green">DeviceID</td>
	<td>端末ID</td><td></td></tr>
<tr><td style='color:#ffffff' bgcolor="green">端末CD</td>
	<td>DBに登録された管理CD　連番（あれば登録済）</td><td></td></tr>
<tr><td style='color:#ffffff' bgcolor="green">端末結果</td>
	<td>端末登録状況</td>
	<td>ok:登録済　ng:未登録</td></tr>
<tr><td style='color:#ffffff' bgcolor="darkorange">ユーザ結果</td>
	<td>ユーザ登録状況</td>
	<td>ok:登録済　ng:未登録</td></tr>
<tr><td style='color:#ffffff' bgcolor="crimson">IP結果</td>
	<td>IP結果（社内かどうか）</td>
	<td>ok:社内　ng:社外</td></tr>
<tr><td style='color:#ffffff' bgcolor='#4169E1'>AppVer</td>
	<td>アプリバージョン</td><td></td></tr>
</table>
※空白で項目を分けて表を表示しています。データ内に空白がある場合は表のレイアウトがくずれます。<br>



<br><br>
__nowdate__　時点のdeviceid.txtを表示<br>

<table class="a" width=100%>
<tr style='color:#ffffff'>
	<td bgcolor='#4169E1'>時間</td>
	<td bgcolor="green">DeviceID</td>
	<td bgcolor="green">端末CD</td>
	<td bgcolor="green">端末結果</td>
	<td bgcolor="darkorange">ID</td>
	<td bgcolor="darkorange">パスワード</td>
	<td bgcolor="darkorange">ユーザ結果</td>
	<td bgcolor="crimson">IP</td>
	<td bgcolor="crimson">IP結果</td>
	<td bgcolor='#4169E1'>ログイン結果</td>
	<td bgcolor='#4169E1'>アプリver</td>
</tr>

__dataLoop__
<tr bgcolor="__bc__">__line__</tr>
__dataLoop__

</table>


<hr size="__HRSize__" color="__HRColor__">
<hr size="__HRSize__" color="__HRColor__">
__SFooter__
__SCopyright__
</body>
</html>

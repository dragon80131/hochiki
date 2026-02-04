<html>
<head>
<title>ポスティング管理 - 詳細情報フォーム</title>

<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>
__IfAjax__<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__</head>

<body>

<div class="content">
__SAdminHeader__
<h2 class="admin-title">ポスティング管理 - 詳細情報フォーム</h2>
<br />


<table class="common-list">
	<tr>
		<td nowrap class="common-list-title-left" style="border:0px;priority">
			__MansionName__<br />
		</td>
	</tr>
</table>

<h2 class="navigation"><a href="#" onclick="javascript:move('d_koji.php')">&gt;&gt;&gt; 工事登録メニューへもどる</a></h2>
<br />

<form method="POST" action="d_posting_list.php" name="mainform">

<table class="common-list" width="700">

	<tr>
		<td nowrap class="common-list-title">ポスティング有無</td>
		<td nowrap class="common-list-value-left">
		__DispPostingFlg__</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" rowspan="2" >投函場所</td>
		<td nowrap class="common-list-value-left">__DispPostingBasho__</td>
	</tr>
	<tr>
		<td nowrap class="common-list-value-left">__DispPostType__</td>
	</tr>

	<tr>
		<td nowrap class="common-list-title">腕章</td>
		<td nowrap class="common-list-value-left">__DispWanshoFlg__</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title">その他依頼事項</td>
		<td nowrap class="common-list-value-left">__DispPostingBiko__</td>
	</tr>

	<tr>
		<td nowrap class="common-list-title" colspan="2">以下ドア前ポスト/ドア貼り付けの場合のみ記載します。
		</td>
	</tr>

	<tr>
		<td nowrap class="common-list-title">エントランス開錠方法</td>
		<td nowrap class="common-list-value-left"> __DispKaijyo__ __DispKaijyoAnshoNo__ <br>
		管理員様TEL __DispKanriinTEL__
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title">ドアにポストは？</td>
		<td nowrap class="common-list-value-left">__DispDoorPostFlg__</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title">掲示資料 貼付場所</td>
		<td nowrap class="common-list-value-left">__DispKeijiBasho__


		</td>
	</tr>


	<tr>
		<td>
__HiddenValues__			
			<input type="button" value="データ更新" class="button" onclick="javascript:moveWithWork('d_posting_list.php', 1)">　

			<input type="button" value="もどる" class="button" onclick="javascript:history.back()">
			
		</td>
	</tr>
</table>


</form>

<div class="footer-box">
	__SAdminCopyright__
</div>

</body>
</html>
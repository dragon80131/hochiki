<html>
<head>
<title>ポスティング管理  ポスティング検索結果リスト</title>

<LINK REL="stylesheet" TYPE="text/css" HREF="style.css">
<script type="text/javascript" src="tools.js"></script>
__IfAjax__<script type="text/javascript" src="js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="js/tools_ajax.js"></script>
__IfAjax__</head>

<body>

<div class="content">
__SAdminHeader__
<h2 class="admin-title">ポスティング管理  ポスティングリスト__ExtraTitle__</h2>
<br />


<table class="common-list">
	<tr>
		<td nowrap class="common-list-title-left" style="border:0px;priority">
			__MansionName__<br />
		</td>
	</tr>
</table>

__IfCreate__
<table class="common-list">
	<tr>
		<td nowrap class="common-list-value">
		『__wStylistName__』の新規登録を行いました。<br />
		</td>
	</tr>
</table>
__IfCreate__

__IfUpdate__
<table class="common-list" width="400">
	<tr>
		<td nowrap class="common-list-value">
		『__wStylistName__』の情報更新を行いました。<br />
		</td>
	</tr>
</table>
__IfUpdate__

__IfDelete__
<table class="common-list" width="400">
	<tr>
		<td nowrap class="common-list-value">
		『__wStylistName__』の削除を行いました。<br />
		</td>
	</tr>
</table>
__IfDelete__


<h2 class="navigation"><a href="#" onclick="javascript:move('d_koji.php')">&gt;&gt;&gt; 工事登録メニューへもどる</a></h2>

<br />
<!-- <h2 class="navigation"><a href="#" onclick="javascript:moveWithStylistKey('d_stylist_detail.php', -1)">&gt;&gt;&gt; 新しいポスティングを作成する</a></h2> -->
<h2 class="navigation"><a href="#" onclick="javascript:move('d_posting_list.php')">&gt;&gt;&gt; 最新の情報に更新</a></h2>
<br />

<form method="POST" action="d_posting_detail.php" name="mainform">


<table class="common-list" >
	<tr>
		<td nowrap class="common-list-title">ポスティング有無</td>
		<td nowrap class="common-list-value-left">
		<input type="radio" name="wPostingFlg" value="1"  __PostingChecked1__ >依頼する 
		<input type="radio" name="wPostingFlg" value="2"  __PostingChecked2__ >しない</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title" rowspan="2" >投函場所</td>
		<td nowrap class="common-list-value-left">
		<input type="radio" name="wPostingBasho" value="1"  __PostingBashoChecked1__ >集合ポスト
		<input type="radio" name="wPostingBasho" value="2"  __PostingBashoChecked2__ >ドア前ポスト/ドア貼り付け</td>
	</tr>
	<tr>

		<td nowrap class="common-list-value-left">

		<!--<input type="checkbox" name="wYoteiPost">おまかせ東京パック（ヒアリング付き現調案内、予定・確定・催促）<br>-->
		<input type="checkbox" name="wPostType[]" value="1" __PostTypeChecked1__  >予定案内<br>
		<input type="checkbox" name="wPostType[]" value="2" __PostTypeChecked2__  >確定案内・未連絡住戸用催促案内
		</td>

	</tr>

	<tr>
		<td nowrap class="common-list-title">腕章</td>
		<td nowrap class="common-list-value-left">
		<input type="checkbox" name="wWanshoFlg" value="1" __WanshoFlgChecked1__ >腕章をして投函<br>
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title">その他依頼事項</td>
		<td nowrap class="common-list-value-left">
		専用封筒に入れ投函などご要望がありましたら記載お願いします。<br>

		<input type="text" name="wPostingBiko" value="__wPostingBiko__" style="width:300px;">


		</td>
	</tr>

	<tr>
		<td nowrap class="common-list-title" colspan="2">以下ドア前ポスト/ドア貼り付けの場合のみ記載します。
		</td>
	</tr>

	<tr>
		<td nowrap class="common-list-title">エントランス開錠方法</td>
		<td nowrap class="common-list-value-left">

		<input type="radio" name="wKaijyo" value="1" __KaijyoChecked1__ >オートロックなし
		<input type="radio" name="wKaijyo" value="2" __KaijyoChecked2__ >暗証番号  
		<input type="radio" name="wKaijyo" value="3" __KaijyoChecked3__ >キーボックス内の鍵で開錠   
		<input type="radio" name="wKaijyo" value="4" __KaijyoChecked4__ >管理員に依頼 　<font color="red">(必須）</font> <br>
		上記選択の暗証番号、キーボックスの設置場所と開錠方法を入力ください。 <br>
		<input type="text" name="wKaijyoAnshoNo" value="__wKaijyoAnshoNo__" style="width:300px;"> <br>
		管理員様TEL<input type="text" name="wKanriinTEL" value="__wKanriinTEL__" style="width:100px;"> 
		鍵借用の場合、訪問前に管理員様へ連絡します。
		</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title">ドア前のポスト状況</td>
		<td nowrap class="common-list-value-left">
		<input type="radio" name="wDoorPostFlg" value="1"  __DoorPostChecked1__ >ドアにポストあり
		<input type="radio" name="wDoorPostFlg" value="2"  __DoorPostChecked2__ >なし</td>
	</tr>
	<tr>
		<td nowrap class="common-list-title">掲示資料 貼付場所</td>
		<td nowrap class="common-list-value-left">
		掲示板・エレベータ内は、標準で貼付します。<br>
		それ以外の貼る場所があれば記載してください。<br>

		<input type="text" name="wKeijiBasho" value="__wKeijiBasho__" style="width:300px;">


		</td>
	</tr>


</table><br> 
<input type="submit" value=" 確認 " >

__HiddenValues__
<div class="footer-box">
	__SAdminCopyright__
</div>
</form>

</body>
</html>
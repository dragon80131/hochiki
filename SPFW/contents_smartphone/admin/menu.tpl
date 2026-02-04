<ul id="dd">
	<li>
		<a href="user_list.php" class="menu" id="mmenu1" 
			onmouseover="mopen(1);"
			onmouseout="mclosetime();">部屋番号管理</a>
		<div class="submenu" id="menu1"
			onmouseover="mcancelclosetime()"
			onmouseout="mclosetime();">
				<a href="user_search.php">部屋番号検索</a>
				<a href="user_list.php">部屋番号一覧</a>
				<a href="user_detail.php?editUserCD=-1">部屋番号登録</a>
				<a href="user_set_menu.php">システム設定</a>
		</div>
	</li>
	<li>
		<a href="reserve_detail.php" class="menu" id="mmenu12" 
			onmouseover="mopen(12);"
			onmouseout="mclosetime();">工事予約管理システム</a>
		<div class="submenu" id="menu12"
			onmouseover="mcancelclosetime()"
			onmouseout="mclosetime();">
				<a href="reserve_detail.php">工事予約受付状況</a>
				<a href="reserve_set_menu.php">システム設定</a>
				<a href="cvsUpload.php">データインポート</a>
				<a href="stylist_list.php">施工会社管理</a>
				<a href="menu_list.php">メニュー管理</a>
				<a href="calendar_list.php">休日管理</a>

		</div>
	</li>
	<li>
		<a href="#" class="menu" id="mmenu99" 
			onmouseover="mopen(99);"
			onmouseout="mclosetime();">　</a>
	</li>
	<li>
		<a href="#" class="menu" id="mmenu99" 
			onmouseover="mopen(99);"
			onmouseout="mclosetime();">　</a>
	</li>
	<li>
		<a href="administrator_list" class="menu" id="mmenu6" 
			onmouseover="mopen(6);"
			onmouseout="mclosetime();">管理者設定</a>
		<div class="submenu" id="menu6"
			onmouseover="mcancelclosetime()"
			onmouseout="mclosetime();">
				<a href="administrator_list.php">管理者一覧</a>
				<a href="administrator_detail.php?editAdminCD=-1">新規管理者登録</a>
		</div>
	</li>
	<li>
		<a href="system_set_form.php" class="menu" id="mmenu7" 
			onmouseover="mopen(7);"
			onmouseout="mclosetime();">共通設定</a>
	</li>
	<li>
		<a href="logout.php" class="menu" id="mmenu17" 
			onmouseover="mopen(17);"
			onmouseout="mclosetime();">ログアウト</a>
	</li>
</ul> 

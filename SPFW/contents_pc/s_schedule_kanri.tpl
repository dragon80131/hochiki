<html>
<head>
	<title>エスコスケジュール一覧</title>
	<LINK REL="stylesheet" TYPE="text/css" HREF="css/schedule_style.css">
	<script type="text/javascript" src="js/jquery-1.2.js"></script>
	<script type="text/javascript" src="tools.js"></script>

	<!--font icon-->

	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
</head>

<h2>エスコスケジュール一覧</h2>
<br>
<br>
<body>
	<table width="100%">
		<tr>
			<form action="s_schedule_kanri.php?rKey=__rKey__" method="POST">
				<td><select name="yearlist">
						__yearLoop__<option value="__year__" __yearselected__>__year__</option>__yearLoop__
					</select>年
					<select name="monthlist">
						__monthLoop__<option value="__month__" __monthselected__>__month__</option>__monthLoop__
					</select>月
					<input type="submit" value=" 表示 ">
				</td>
				<td>
					<div style="text-align:center">
						<span class="kojibox">　　　</span>…専有部　
						<span class="kojigaibox">　外　</span>…期間外作業　　
					</div>
				</td>
				<!--
				<td>
					<div style="text-align:right">
						<select name="searchSiten">
							<option value="">-</option>
							<option value="東京支店" __searchSitenSelected1__>東京支店</option>
							<option value="大阪支店" __searchSitenSelected2__>大阪支店</option>
							<option value="名古屋支店" __searchSitenSelected3__>名古屋支店</option>
							<option value="札幌支店" __searchSitenSelected4__>札幌支店</option>
							<option value="福岡支店" __searchSitenSelected5__>福岡支店</option>
						</select>
						<input type="submit" value=" 検索 ">
					</div>
				</td>-->
			</form>
		</tr>
	</table>
	<br>
	<input type="button" value="戻る" class="btn btn-primary" onClick="location.href='s_search.php?rKey=__rKey__'"><br>
	<table border="1" class="common-list-kanri" id="mainTable">
		<thead>
			<tr class="list-box">
				<th rowspan="3" nowrap class="common-list-title2">物件名</th>
				<!--<th rowspan="3" nowrap class="common-list-title2">営業所</th>-->
				<th rowspan="3" nowrap class="common-list-title2">工事店</th>
				<th colspan="__daycount__" nowrap class="common-list-title2">　__tyear__ 年　__tmonth__ 月</td>
			</tr>
			<tr>
				__dayLoop__<th nowrap class="common-list-value2" bgcolor="__dc__" style="text-align:center;">__dd__</th>__dayLoop__
			</tr>
			<tr class="weekLoop">
				__weekLoop__<th nowrap class="common-list-value2" bgcolor="__dc__" style="text-align:center;">__dw__</th>__weekLoop__
			</tr>
		</thead>

		<tbody>
			__listLoop__
			<tr class="zandate-box">
				<td nowrap class="common-list-value2 mansionname"><a href="__bukkenurl__" target="_blank"> __mansionname__</a></td>
				<!--<td nowrap class="common-list-value2 eigyoname">__eigyoname__</td>-->
				<td nowrap class="common-list-value2 sekosyozoku">__sekosyozoku__ </td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor01__">
					<center>__zandate01__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor02__">
					<center>__zandate02__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor03__">
					<center>__zandate03__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor04__">
					<center>__zandate04__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor05__">
					<center>__zandate05__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor06__">
					<center>__zandate06__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor07__">
					<center>__zandate07__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor08__">
					<center>__zandate08__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor09__">
					<center>__zandate09__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor10__">
					<center>__zandate10__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor11__">
					<center>__zandate11__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor12__">
					<center>__zandate12__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor13__">
					<center>__zandate13__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor14__">
					<center>__zandate14__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor15__">
					<center>__zandate15__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor16__">
					<center>__zandate16__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor17__">
					<center>__zandate17__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor18__">
					<center>__zandate18__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor19__">
					<center>__zandate19__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor20__">
					<center>__zandate20__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor21__">
					<center>__zandate21__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor22__">
					<center>__zandate22__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor23__">
					<center>__zandate23__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor24__">
					<center>__zandate24__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor25__">
					<center>__zandate25__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor26__">
					<center>__zandate26__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor27__">
					<center>__zandate27__</center>
				</td>
				<td nowrap class="common-list-value2" bgcolor="__Bcolor28__">
					<center>__zandate28__</center>
				</td>
				__Ifday29__ <td nowrap class="common-list-value2" bgcolor="__Bcolor29__">
					<center>__zandate29__</center>
				</td>__Ifday29__
				__Ifday30__ <td nowrap class="common-list-value2" bgcolor="__Bcolor30__">
					<center>__zandate30__</center>
				</td>__Ifday30__
				__Ifday31__ <td nowrap class="common-list-value2" bgcolor="__Bcolor31__">
					<center>__zandate31__</center>
				</td>__Ifday31__
			</tr>
			__listLoop__

		</tbody>
	</table>
</body>
</html>

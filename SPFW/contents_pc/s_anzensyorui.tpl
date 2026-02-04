<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>リニューアル支援</title>

<!-- BootstrapのCSS読み込み -->
<link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- jQuery読み込み -->
<script src="./include/js/jquery-3.2.1.min.js"></script>

<!--datepicker-->
<link href="css/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet">
<link href="css/jquery-ui-1.12.1/jquery-ui.structure.min.css" rel="stylesheet">
<link href="css/jquery-ui-1.12.1/jquery-ui.theme.min.css" rel="stylesheet">
<script src="js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
<script src="js/jquery-ui/datepicker-ja.js"></script>

<!-- BootstrapのJS読み込み -->
<script src="./include/bootstrap/js/bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="./css/rnsien.css">
<script type="text/javascript" src="tools.js"></script>
<script type="text/javascript">
function Hyouji(val){
	if(val==1){
		document.getElementById("ATE").style.display = "block";
		document.getElementById("AIPHONE").style.display = "none";
	}else{
		document.getElementById("AIPHONE").style.display = "block";
		document.getElementById("ATE").style.display = "none";
	}
	document.getElementById("Seisei").disabled = false;
}
</script>
<script>






$(function() {
	$(".datepicker").datepicker({});
});

</script>

<script type="text/javascript">
function FormatHyouji(val){
	if(val==1){
		document.getElementById("HASEKO").style.display = "none";
		document.getElementById("AIPHONE2").style.display = "block";
	}else if(val==2){
		document.getElementById("AIPHONE2").style.display = "none";
		document.getElementById("HASEKO").style.display = "block";
	}
	//document.getElementById("Seisei").disabled = false;
}
</script>


</head>

<body>
__SHeader__

<div class="content-all"><!--content-all-->

<div class="left-yose">
<a href="./s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜メニュー</a>
</div>


<div class="top-menu left-yose">
<h5>__wBukkenName__</h5>
<h6>安全書類</h6>



<form action="./doc/s_anzensyoruiEXCEL.php?rKey=__rKey__" method="POST" name="mainform" >
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">


書式選択<br>
		<input type="radio" name="wFormat" value="1" onchange="FormatHyouji(1);">アイホン元請けの場合　　　
		<input type="radio" name="wFormat" value="2" onchange="FormatHyouji(2);">下請けの場合

<span id="AIPHONE2" style="display: none;" >


		◆<a href="s_anzensyorui_copy.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">過去の案件の安全書類をコピーする</a>
		<br><br>

		◆物件情報記入欄

		<table border="1" style="width:800px;">

		<tr><td bgcolor="#ebeeef" colspan="3" align=center>元請記入欄</td></tr>
		<tr><td>書類作成日</td>
			<td>
				<input type="text" name="wSyoruiSakuseiDate" value="__wSyoruiSakuseiDate__" class="datepicker" style="width:100px;background-color:#FFFFCC">
			</td>
			<td>
				※契約後、着工前1週間前がベスト
			</td>
		</tr>
		<tr><td>工事名称</td>
			<td>
				<input type="text" name="wAnzenKojiName" value="__wAnzenKojiName__" style="width:280px;background-color:#FFFFCC">
			</td>
			<td>
				※契約書に合わせる
			</td>
		</tr>
		<tr><td>工事内容</td>
			<td>
				<input type="text" name="wAnzenKojiNaiyo" value="__wAnzenKojiNaiyo__" style="width:280px;background-color:#FFFFCC">
			</td>
			<td>
				※工事概要を簡潔に<br>
				　例<br>
				　・インターホン改修
			</td>
		</tr>
		<tr><td>建物名称</td>
			<td>
				<input type="text" name="BukkenName" value="__BukkenName__" style="width:280px;background-color:#FFFFCC">
			</td>
			<td>
				※マンション名、または病院名を記入<br>
				(基本的には国や地方自治体などの注文者や元請企業が<br>決めた建物の名前を記入します)
			</td>
		</tr>
		<tr><td>発注者名</td>
			<td colspan="2">
				<input type="text" name="wAnzenHattyusya" value="__wAnzenHattyusya__" style="width:350px;background-color:#FFFFCC">
			</td>
		</tr>
		<tr><td>発注者住所</td>
			<td colspan="2">
				<input type="text" name="wAnzenHattyusyaAddress" value="__wAnzenHattyusyaAddress__" style="width:700px;background-color:#FFFFCC">
			</td>
		</tr>
		<tr><td>発注者電話番号</td>
			<td>
				<input type="text" name="wAnzenHattyusyaTEL" value="__wAnzenHattyusyaTEL__" style="background-color:#FFFFCC">
			</td>
			<td>
				緊急連絡先体制に反映します
			</td>
		</tr>
		<tr><td>契約日</td>
			<td>
				<input type="text" name="wContract" value="__wContract__" class="datepicker" style="width:100px;background-color:#FFFFCC">
			</td>
			<td>
				※契約書記載
			</td>
		</tr>
		<tr><td>工事開始日</td>
			<td colspan="2">
				<input type="text" name="ZentaiStartDate" value="__ZentaiStartDate__" style="width:100px;background-color:#FFFFCC">
			</td>
		</tr>
		<tr><td>工事終了日</td>
			<td colspan="2">
				<input type="text" name="ZentaiEndDate" value="__ZentaiEndDate__" style="width:100px;background-color:#FFFFCC">
			</td>
		</tr>
		<tr><td>支店長</td>
			<td colspan="2">
				<input type="text" name="wSitentyo" value="__wSitentyo__" style="background-color:#FFFFCC">
			</td>
		</tr>
		<tr><td>営業所長</td>
			<td colspan="2">
				<input type="text" name="wEigyoSyotyo" value="__wEigyoSyotyo__" style="background-color:#FFFFCC">
			</td>
		</tr>
		<tr><td>主任技術者</td>
			<td>
				<input type="text" name="wSyuninGijutusya" value="__wSyuninGijutusya__" style="background-color:#FFFFCC">
			</td>
			<td>
				※他社仕入稟議に記入した方を記入
			</td>
		</tr>
		<tr><td>物件担当者</td>
			<td>
				<input type="text" name="wBukkenTanto" value="__wBukkenTanto__" style="background-color:#FFFFCC">
			</td>
			<td>
				※現場代理人になります
				(資格は不要です)
			</td>
		</tr>
		<tr><td>緊急連絡先</td>
			<td>
				<input type="text" name="wEmergencyTEL" value="__wEmergencyTEL__" style="background-color:#FFFFCC">
			</td>
			<td>
				※できれば携帯が良いですが営業所番号でも可
			</td>
		</tr>
		</table>
		<br>

		<table border="1" style="width:700px;">
		<tr>
			<tr><td bgcolor="#ebeeef" colspan="3" align="center">1次下請記入欄</td></tr>
		</tr>
		<tr>
			<td colspan="3" align="center">
				<input type="radio" name="Sitauke" value="1" onchange="Hyouji(1);">ATEの場合　　　
				<input type="radio" name="Sitauke" value="2" onchange="Hyouji(2);">アイホンの場合
			</td>
		</tr>
		</table>
		<span id="ATE" style="display: none;" >

		<br>
		■二次下請け会社　情報<br>
			<table border="1" style="width:700px;">


				<tr><td colspan=3>施工業者情報の登録は、下記のスマート工事から行ってください。
				<a href="https://sf2.489501.jp/seko/seko_company_all.php?rKey=__rKey__&editGyosyaCD=__GyosyaCD__" target="_blank" >スマート工事</a><br>
		登録された場合は、右の更新をクリックしてください。<a href="s_anzensyorui.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&cat=anzen">更新</a>
					</td>
				</tr>
				<tr><td>下請契約日</td>
					<td>
						<input type="text" name="wATEContract" value="__wATEContract__"  class="datepicker"  style="width:100px;background-color:#FFFFCC"; >
					</td>
					<td>
						※他社仕入稟議による注文書発行日
					</td>
				</tr>
				<tr><td>ATE社名</td>
					<td colspan="2">
						__ATESyamei__
					</td>
				</tr>
				<tr><td>ATE住所</td>
					<td colspan="2">
						__ATEAddress__
					</td>
				</tr>
				<tr><td>ATE代表者名</td>
					<td colspan="2">
						__ATEDaihyou__
					</td>
				</tr>
				<tr><td>ATE担当者名</td>
					<td colspan="2">
						__ATETanto__
					</td>
				</tr>
				<tr><td>緊急連絡番号</td>
					<td colspan="2">
						__ATEEmergencyTEL__
					</td>
				</tr>
				<tr  ><td rowspan=2>建設業許可番号</td>
					<td colspan="2">
						__LicenseNumber__
					</td>
				</tr>		<tr>
					<td colspan="2">
						__Syurui__
					</td>
				</tr>
				<tr><td>建設業許可年月日</td>
					<td colspan="2">
						__LicenseDate__
					</td>
				</tr>
				<tr><td>健康保険番号</td>
					<td colspan="2">
						<input type="text" name="wHealthNumber" value="__wHealthNumber__" style="background-color:#FFFFCC";>
					</td>
				</tr>
				<tr><td>年金番号</td>
					<td colspan="2">
						<input type="text" name="wNenkinNumber" value="__wNenkinNumber__" style="background-color:#FFFFCC";>
					</td>
				</tr>
				<tr><td>年金保険の種類</td>
					<td colspan="2">
						<input type="text" name="wHNSyurui" value="__wHNSyurui__" style="background-color:#FFFFCC";>
					</td>
				</tr>
				<tr><td>雇用保険番号</td>
					<td colspan="2">
						<input type="text" name="wEmployNumber" value="__wEmployNumber__" style="background-color:#FFFFCC";>
					</td>
				</tr>
			</table>


		</span>

		<span id="AIPHONE" style="display: none;">

		<br>
		■アイホン（一次下請け）情報 <br>
			<table border="1" style="width:700px;">
				<tr><td>契約日</td>
					<td>
						<input type="text" name="wAIPHONEContract" value="__wAIPHONEContract__"  class="datepicker"   style="width:100px;background-color:#FFFFCC";>
					</td>
					<td>
						※他社仕入稟議による注文書発行日
					</td>
				</tr>
				<tr><td>社名</td>
					<td colspan="2">
						__AIPHONESyamei__
					</td>
				</tr>
				<tr><td>住所</td>
					<td colspan="2">
						__AIPHONEAddress__
					</td>
				</tr>
				<tr><td>代表者名</td>
					<td colspan="2">
						__AIPHONEDaihyou__
					</td>
				</tr>
				<tr><td>担当者名</td>
					<td colspan="2">
						<input type="text" name="wAIPHONETanto" value="__wAIPHONETanto__" style="background-color:#FFFFCC";>
					</td>
				</tr>
				<tr><td>緊急連絡番号</td>
					<td colspan="2">
						<input type="text" name="wAIPHONEEmergencyTEL" value="__wAIPHONEEmergencyTEL__" style="background-color:#FFFFCC";>
					</td>
				</tr>
				<tr><td>建設業許可番号</td>
					<td colspan="2">
						__AIPHONELicenseNumber__
					</td>
				</tr>
				<tr><td>建設業許可年月日</td>
					<td colspan="2">
						__AIPHONELicenseDate__
					</td>
				</tr>
				<tr><td>健康保険番号</td>
					<td colspan="2">
						__AIPHONEHealthNumber__
					</td>
				</tr>
				<tr><td>年金番号</td>
					<td colspan="2">
						__AIPHONENenkinNumber__
					</td>
				</tr>
				<tr><td>年金保険の種類</td>
					<td colspan="2">
						__AIPHONEHNSyurui__
					</td>
				</tr>
				<tr><td>雇用保険番号</td>
					<td colspan="2">
						__AIPHONEEmployNumber__
					</td>
				</tr>
			</table>
		</span>


	
		<br><br>
		<!--<input type="submit" id="Seisei" value="資料生成" disabled>-->

		<input type="submit" value="資料生成" class="btn btn-primary" >


		<br>
		<a href="./doc/template/安全書類作成ツール.zip">安全書類作成ツール</a><br>

</span><!--アイホンフォーマットEnd-->


<span id="HASEKO" style="display: none;" >

		◆<a href="s_anzensyorui_copy.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">過去の案件の安全書類をコピーする</a>
		<br><br>

		◆物件情報記入欄

		<table border="1" style="width:800px;">

		<tr><td bgcolor="#ebeeef" colspan="3" align=center>元請記入欄</td></tr>
		<tr><td>物件名</td>
			<td>
				<input type="text" name="BukkenName" value="__BukkenName__" style="width:280px;background-color:#CCFFFF">
			</td>
			<td>
			</td>
		</tr>
		<tr><td>工事内容</td>
			<td>
				<input type="text" name="wAnzenKojiNaiyo" value="__wAnzenKojiNaiyo__" style="width:280px;background-color:#CCFFFF">
			</td>
			<td>
				　例　インターホン改修
			</td>
		</tr>
		<tr><td>工事名称</td>
			<td>
				<input type="text" name="wAnzenKojiName" value="__wAnzenKojiName__" style="width:280px;background-color:#CCFFFF">
			</td>
			<td>
				※契約書に合わせる
			</td>
		</tr>
		<tr><td>発注者</td>
			<td>
				<input type="text" name="wAnzenHattyusya" value="__wAnzenHattyusya__" style="width:280px;background-color:#CCFFFF">
			</td>
			<td>
			
			</td>
		</tr>

		<tr><td>発注者住所</td>
			<td colspan="2">
				<input type="text" name="wAnzenHattyusyaAddress" value="__wAnzenHattyusyaAddress__" style="width:700px;background-color:#CCFFFF">
			</td>
		</tr>


		<tr><td>階数・戸数</td>
			<td>
				__Kaidaka__ __Kosu__ 
			</td>
			<td>
			
			</td>
		</tr>




		<tr><td>長谷工支店名</td>
			<td colspan="2">
				<input type="text" name="wHasekoSiten" value="__wHasekoSiten__" style="width:350px;background-color:#CCFFFF">
			</td>
		</tr>
		<tr><td>支店住所</td>
			<td colspan="2">
				<input type="text" name="wHasekoAddress" value="__wHasekoAddress__" style="width:550px;background-color:#CCFFFF">
			</td>
		</tr>
		<tr><td>電話</td>
			<td>
				<input type="text" name="wHasekoTEL" value="__wHasekoTEL__" style="background-color:#CCFFFF">
			</td>
			<td>

			</td>
		</tr>
		<tr><td>長谷工支店担当者（氏名）</td>
			<td>
				<input type="text" name="wHasekoTanto" value="__wHasekoTanto__" style="width:350px;background-color:#CCFFFF">
			</td>
			<td>

			</td>
		</tr>
		<tr><td>工期　自</td>
			<td colspan="2">
				<input type="text" name="ZentaiStartDate" value="__ZentaiStartDate__" style="width:100px;background-color:#CCFFFF">
			</td>
		</tr>
		<tr><td>工事　至</td>
			<td colspan="2">
				<input type="text" name="ZentaiEndDate" value="__ZentaiEndDate__" style="width:100px;background-color:#CCFFFF">
			</td>
		</tr>

		<tr><td colspan="3">アイホン（一次下請け）</td></tr>

		<tr><td>アイホン支店・営業所</td>
			<td colspan="2">
				__wEigyoshoName__
			</td>
		</tr>
		<tr><td>住所</td>
			<td colspan="2">
				__wEigyoshoAddress__
			</td>
		</tr>
		<tr><td>電話</td>
			<td>
				__wEigyoshoTEL__
			</td>
			<td>

			</td>
		</tr>
		<tr><td>担当者（氏名）</td>
			<td>
				<input type="text" name="wAIPHONETanto" value="__wAIPHONETanto__" style="background-color:#CCFFFF">
			</td>
			<td>
			</td>
		</tr>
		<tr><td>担当者　携帯電話</td>
			<td>
				<input type="text" name="wAIPHONEEmergencyTEL" value="__wAIPHONEEmergencyTEL__" style="background-color:#CCFFFF">
			</td>
			<td>

			</td>
		</tr>


		<tr><td>安全衛生担当役員（氏名・役職）</td>
			<td>
				<input type="text" name="wHasekoAnzenYakuin" value="__wHasekoAnzenYakuin__" style="background-color:#CCFFFF">
			</td>
			<td>

			</td>
		</tr>


		<tr><td>安全衛生担当責任者（氏名・役職）</td>
			<td>
				<input type="text" name="wHasekoAnzenSekininsya" value="__wHasekoAnzenSekininsya__" style="background-color:#CCFFFF">
			</td>
			<td>

			</td>
		</tr>


		<tr><td>安全衛生担当者（氏名・役職）</td>
			<td>
				<input type="text" name="wHasekoAnzenTanto" value="__wHasekoAnzenTanto__" style="background-color:#CCFFFF">
			</td>
			<td>

			</td>
		</tr>


		<tr><td>工事担当役員（氏名・役職）</td>
			<td>
				<input type="text" name="wHasekoKojiYakuin" value="__wHasekoKojiYakuin__" style="background-color:#CCFFFF">
			</td>
			<td>

			</td>
		</tr>


		<tr><td>工事担当責任者（氏名・役職）</td>
			<td>
				<input type="text" name="wHasekoKojiSekininsya" value="__wHasekoKojiSekininsya__" style="background-color:#CCFFFF">
			</td>
			<td>

			</td>
		</tr>

		<tr><td>工事担当者（氏名・役職）</td>
			<td>
				<input type="text" name="wHasekoKojiTanto" value="__wHasekoKojiTanto__" style="background-color:#CCFFFF">
			</td>
			<td>

			</td>
		</tr>


		<tr><td>現場代理人（氏名）</td>
			<td>
				<input type="text" name="wHasekoGenbaDairinin" value="__wHasekoGenbaDairinin__" style="background-color:#CCFFFF">
			</td>
			<td>

			</td>
		</tr>


		<tr><td>職長（氏名）</td>
			<td>
				<input type="text" name="wHasekoShokucho" value="__wHasekoShokucho__" style="background-color:#CCFFFF">
			</td>
			<td>

			</td>
		</tr>

		<tr><td>主任技術者</td>
			<td>
				<input type="text" name="wHasekoSyuninGijyutusya" value="__wHasekoSyuninGijyutusya__" style="background-color:#CCFFFF">
			</td>
			<td>

			</td>
		</tr>


		<tr><td>専門技術者</td>
			<td>
				<input type="text" name="wHasekoSenmonGijyutusya" value="__wHasekoSenmonGijyutusya__" style="background-color:#CCFFFF">
			</td>
			<td>

			</td>
		</tr>


		<tr><td>担当工事内容</td>
			<td>
				<input type="text" name="wHasekoTantoKojiNaiyo" value="__wHasekoTantoKojiNaiyo__" style="background-color:#CCFFFF">
			</td>
			<td>

			</td>
		</tr>






		</table>
		<br>



		<br>
		■二次下請け会社　情報<br>
			<table border="1" style="width:700px;">


				<tr><td colspan=3>施工業者情報の登録は、下記のスマート工事から行ってください。
				<a href="https://sf2.489501.jp/seko/seko_company_all.php?rKey=__rKey__&editGyosyaCD=__GyosyaCD__" target="_blank" >スマート工事</a><br>
		登録された場合は、右の更新をクリックしてください。<a href="s_anzensyorui.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&cat=anzen">更新</a>
					</td>
				</tr>

				<tr><td>二次下請け会社</td>
					<td colspan="2">
						__ATESyamei__
					</td>
				</tr>
				<tr><td>住所</td>
					<td colspan="2">
						__ATEAddress__
					</td>
				</tr>
				<tr><td>担当者名</td>
					<td colspan="2">
						__ATETanto__
					</td>
				</tr>
				<tr><td>担当者　携帯電話</td>
					<td colspan="2">
						__ATEEmergencyTEL__
					</td>
				</tr>
				<tr  ><td rowspan=2>建設業許可番号</td>
					<td colspan="2">
						__LicenseNumber__
					</td>
				</tr>		<tr>
					<td colspan="2">
						__Syurui__
					</td>
				</tr>
				<tr><td>建設業許可年月日</td>
					<td colspan="2">
						__LicenseDate__
					</td>
				</tr>



				<tr><td>安全衛生責任者（氏名）</td>
					<td colspan="2">
						<input type="text" name="wHaseko2jiAnzenSekininsya" value="__wHaseko2jiAnzenSekininsya__" style="background-color:#CCFFFF";>
					</td>
				</tr>
				<tr><td>主任技術者（氏名）</td>
					<td colspan="2">
						<input type="text" name="wHaseko2jiSyuninGijyutusya" value="__wHaseko2jiSyuninGijyutusya__" style="background-color:#CCFFFF";>
					</td>
				</tr>
				<tr><td>専門技術者</td>
					<td colspan="2">
						<input type="text" name="wHaseko2jiSenmonGijyutusya" value="__wHaseko2jiSenmonGijyutusya__" style="background-color:#CCFFFF";>
					</td>
				</tr>
				<tr><td>担当工事内容</td>
					<td colspan="2">
						<input type="text" name="wHaseko2jiTantoKojiNaiyo" value="__wHaseko2jiTantoKojiNaiyo__" style="background-color:#CCFFFF";>
					</td>
				</tr>
			</table>




	
		<br><br>

		<input type="submit" value="資料生成" class="btn btn-primary" >


		<br>
		<a href="./doc/template/安全書類長谷工.xlsx">安全書類作成ツール</a><br>

</span><!--長谷工フォーマットEnd-->
<br>
		</form>
<form action="./s_menu.php?rKey=__rKey__" method="POST"  >
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">

<input type="submit" value="戻る" class="btn btn-primary" >
</form>

__SFooter__
__SCopyright__

</body>
</html>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SPADE</title>

<!-- BootstrapのCSS読み込み -->
<link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<script src="./include/js/jquery-3.2.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="./css/rnsien.css">
<script type="text/javascript" src="tools.js"></script>
<link href="css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="js/jquery.ui.core.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
<script src="js/jquery.numberPicker.js" type="text/javascript"></script>
<script type="text/javascript">

function rendou(){
	if('__YoteiFlg__'=='true'){
		//alert("__YoteiFlg__");
		var YoteiPage = document.getElementById('yoteipage').value;
		var KeijiBusu = document.getElementById('KeijiSuuid').value;
	}
	var SubTotal = __SubTotaltpl__;
	var Tax = __TAXtpl__;
	var Total = __Totaltpl__;
	if(YoteiPage==null){

	}else{
		var YoteiPageTanka = YoteiPage * 20;
		var YoteiKin = YoteiPageTanka * __wKosu__;
		document.getElementById('YoteiTanka').innerHTML = "\\" + Number(YoteiPageTanka).toLocaleString();
		document.getElementById('YoteiKin').innerHTML = "\\" + Number(YoteiKin).toLocaleString();
		document.getElementById('YobiPage').innerHTML = YoteiPage;
		document.getElementById('YobiTanka').innerHTML = "\\" + Number(YoteiPageTanka).toLocaleString();
		SubTotal = SubTotal + YoteiKin;

		var YobiBusu = document.getElementById('YobiSuuid').value;
		if(YobiBusu==null){
			
				
		}else{
			var YobiKin = YobiBusu * YoteiPageTanka;
			document.getElementById('YobiKin').innerHTML = "\\" + Number(YobiKin).toLocaleString();
			SubTotal = SubTotal + YobiKin;
			
		}
	}
	if(KeijiBusu==null){
		
	}else{
		var KeijiKin = KeijiBusu * 20;
		document.getElementById('KeijiKin').innerHTML = "\\" + Number(KeijiKin).toLocaleString();
		SubTotal = SubTotal + KeijiKin;
	}
	// if('__wSmartFlg__'==1){
	// 	var batteryflg = document.getElementById('batteryid').checked;
	// 	if(batteryflg){
		
	// 		document.getElementById('batterycol').style.color='black';
	// 		document.getElementById('battery').innerHTML = "\\" + Number(3000).toLocaleString();
	// 		SubTotal = SubTotal + 3000 ;

	// 	}else{

	// 		document.getElementById('batterycol').style.color='grey';
	// 		document.getElementById('battery').innerHTML = "\\" + Number(0).toLocaleString();

	// 		SubTotal = SubTotal;
	// 	}

	// }

	// var today = new Date();

	// if(today.getFullYear() >= 2019 ){
	// 	if(today.getMonth()+1 >= 10){
	// 		Tax = Math.floor(SubTotal * 0.1);
	// 	}else{
	// 		Tax = Math.floor(SubTotal * 0.08);
	// 	}
	// }else{
	// 	Tax = SubTotal * 0.08;
	// }

	Tax = Math.floor(SubTotal * 0.1);
	Total = SubTotal + Tax;

	document.getElementById('subtotalid').innerHTML = "\\" + Number(SubTotal).toLocaleString();
	document.getElementById('taxid').innerHTML = "\\" + Number(Tax).toLocaleString();
	document.getElementById('totalid').innerHTML = "\\" + Number(Total).toLocaleString();
	document.getElementById('mitsumorikin').innerHTML = "\\" + Number(Total).toLocaleString();
}


function moveandcheck(){
	var error_flg = false;
	if('__YoteiFlg__' == 'true'){
		var YoteiPage = document.getElementById('yoteipage').value;
		var YobiBusu = document.getElementById('YobiSuuid').value;
		var KeijiBusu = document.getElementById('KeijiSuuid').value;
		document.getElementById('Yoteiid').innerHTML = "";
		if(YoteiPage==""){
			error_flg = true;
			document.getElementById('Yoteiid').innerHTML = "<br>※工事案内資料のページ数を記入してください。";
		}
		if(YobiBusu==""){
			error_flg = true;
			document.getElementById('Yoteiid').innerHTML += "<br>※予備の部数を記入してください。";
		}
		if(KeijiBusu==""){
			error_flg = true;
			document.getElementById('Yoteiid').innerHTML += "<br>※掲示用の部数を記入してください。";
		}
	}

	if(error_flg==false){
		document.mainform.action = './doc/s_489_mitsumori_Excel.php';
		document.mainform.submit(true);
	}else{
		window.scrollTo(0,50);
	}

}


</script>


<script type="text/javascript" src="js/tools_ajax.js"></script>
<script type="text/javascript" src="js/ConnectedSelect.js"></script>

</head>

<body>
__SHeader__

<div class="content-all"><!--content-all-->

<div class="left-yose">

<h6>御見積書</h6>


<form action="./doc/s_489_mitsumori_Excel.php" method="POST" name="mainform" enctype="multipart/form-data">
<input type="hidden" name="editFileCD" value="" >
<input type="hidden" name="work" value="" >

<br>
予定案内ポスティングサービスをご利用の場合は、工事案内のページ枚数と、<br>
予備、掲示用の部数を入力してください。
<font color="red"><span id=Yoteiid ></span></font>
<br>
<br>

<div style='text-align:center;'>
	<table border="1" width="600" style='margin-left:auto;margin-right:auto;'>
	<tr><td width="250"><b><font size="4">件　　名</font></b></td><td><b><font size="4">工事予約受付センター受付費</b></font></td></tr>
	</table><br>
	<table border="1" width="600" style='margin-left:auto;margin-right:auto;'>
	<tr><td width="250"><b><font size="4">見積金額</font></b></td><td><b><font size="4"><span id="mitsumorikin">__wTotal__</span></font></b></td></tr>
	</table>
<br>
</div>
<table border="1" width="800" style='margin-left:auto;margin-right:auto;'>
<tr>
	<th rowspan="2">項目</th>	<!--①-->
	<th rowspan="2">サービス名称</th>	<!--②-->
	<th rowspan="2" width="3" >数量</th>	<!--③-->
	<th rowspan="2">単位</th>	<!--④-->
	<th colspan="2">ご提供価格</th>	<!--⑤-->
</tr>
<tr>
	<th >単価（円）</th>
	<th>金額（円）</th>
</tr>
<tr>
	<td colspan="2">受付業務費</td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr>
	<td ></td>
	<td rowspan="2">__wBukkenName__<br>受付業務基本料金</td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
</tr>
<tr>
	<td></td>
	<td style="text-align:right">1</td>
	<td>物件</td>
	<td style="text-align:right">\12,000</td>
	<td style="text-align:right">\12,000</td>
</tr>
<tr>
	<td></td>
	<td>受付業務費</td>
	<td style="text-align:right">__wKosu__</td>
	<td>戸</td>
	<td style="text-align:right">\__UketukeTanka__</td>
	<td style="text-align:right">\__UketukeKingaku__</td>
</tr>
__YoteiTable__
__KetteiTable__
__KojiPicTable__
__KakuninTable__
<!-- __SmartTable__ -->
<tr><td colspan='4' rowspan='3'></td><td>小計</td><td style="text-align:right"><span id='subtotalid'>__wSubTotal__</span></td>
</tr>
<tr><td>
		消費税等
	</td>
	<td style="text-align:right">
		<span id="taxid">__wTAX__</span>
	</td>
</tr>
<tr><td>
		合計
	</td>
	<td style="text-align:right">
		<span id="totalid">__wTotal__</span>
	</td>
	</tr>
	<tr></tr>
	<tr><td>備考</td><td colspan="5"></td>
</table>

<br><br>
<input type="hidden" name="rKey" value="__rKey__">
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
<input type="hidden" name="wIraiRenkeiStatus" value="__wIraiRenkeiStatus__" >
<input type="hidden" name="editIraiRenkeiCD" value="__editIraiRenkeiCD__" > 
__HiddenValues__

<!-- ファイル名取得のため-->
<input type="button" value="見積書ダウンロード" onclick="moveandcheck()"  class="btn btn-primary blue" >
<!--<input type="hidden" name="hiddenfilenames" value="">
<input type="button" value="内容確認" onclick="javascript:FilesSubmit(this.form);" class="btn btn-primary">-->
</form>
<br>

<a href="#" onClick="window.close(); return false;" class="btn btn-info">閉じる</a></p>

</div>



</div><!--content-all-->

__SFooter__
__SCopyright__

</body>
</html>

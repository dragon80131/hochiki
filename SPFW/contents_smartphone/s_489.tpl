<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>リニューアル支援</title>

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
$(function() {
	$("#YoteTekyoDate").datepicker({});
	$("#YoteDate").datepicker({});
	$("#YoyakuEnd").datepicker({});
	$("#TekyoDate").datepicker({});
	$("#KeteiDate").datepicker({});
	$("#PhotoTekyoDate").datepicker({});
	$("#IraiDate").datepicker({});
	$("#OpEndDate").datepicker({});
});



// ファイルサイズ取得
function check(no){ 
	var list1 = "", list2 = "", list3 = "", list4 = "", list5 = "";
	var fileList1 = document.getElementById("tempfile1").files;
	var fileList2 = document.getElementById("tempfile2").files;
	var fileList3 = document.getElementById("tempfile3").files;
	var fileList4 = document.getElementById("tempfile4").files;
	var fileList5 = document.getElementById("tempfile5").files;
	var listsize1 = "", listsize2 = "", listsize3 = "", listsize4 = "", listsize5 = "";
	for(var i=0; i<fileList1.length; i++){
		list1 += "[" + fileList1[i].size + " bytes]" + fileList1[i].name + "<br>";
		listsize1 = fileList1[i].size;
	}
	for(var i=0; i<fileList2.length; i++){
		list2 += "[" + fileList2[i].size + " bytes]" + fileList2[i].name + "<br>";
		listsize2 = fileList2[i].size;
	}
	for(var i=0; i<fileList3.length; i++){
		list3 += "[" + fileList3[i].size + " bytes]" + fileList3[i].name + "<br>";
		listsize3 = fileList3[i].size;
	}
	for(var i=0; i<fileList4.length; i++){
		list4 += "[" + fileList4[i].size + " bytes]" + fileList4[i].name + "<br>";
		listsize4 = fileList4[i].size;
	}
	for(var i=0; i<fileList5.length; i++){
		list5 += "[" + fileList5[i].size + " bytes]" + fileList5[i].name + "<br>";
		listsize5 = fileList5[i].size;
	}
	document.getElementById("tempfileresult").innerHTML = list1 +list2 + list3 + list4 + list5;

	var alertFlg = "";
	if (no == 1) {
		if (listsize1 > 10485760) { alertFlg = 1; }
	} else if (no == 2) {
		if (listsize2 > 10485760) { alertFlg = 1; }
	} else if (no == 3) {
		if (listsize3 > 10485760) { alertFlg = 1; }
	} else if (no == 4) {
		if (listsize4 > 10485760) { alertFlg = 1; }
	} else if (no == 5) {
		if (listsize5 > 10485760) { alertFlg = 1; }
	}

	document.getElementById("tempfilesizeresult").style.display="none";
	if (listsize1 > 10485760 || listsize2 > 10485760 || listsize3 > 10485760 || listsize4 > 10485760 || listsize5 > 10485760) {
		document.getElementById("tempfilesizeresult").innerHTML = "ng";
	} else {
		document.getElementById("tempfilesizeresult").innerHTML = "";
	}

	if (alertFlg == 1) {
		alert("サイズが10MB以上のファイルは登録できません。");
		return false;
	}
}

function tempfileclear(oId) {
	var obj = document.getElementById(oId);
	var stO = obj.innerHTML;
	obj.innerHTML = stO;
//	check();
}
</script>

<script>
function moveWithWorkAndFileCD(page, key, work, msg) {
	if (work == 2){
		if (window.confirm(" 削除してもよろしいでしょうか？ ")) {
			document.mainform.editFileCD.value = key;
			document.mainform.work.value = work;
			document.mainform.action = page;
			document.mainform.submit(true);
			document.mainform.work.value = '';
		}
	}
}
 </script>

<script>
function CheckAndEstimate(){
	var error_flg="";
	//1行目のチェック
//var Answer = document.getElementByID("wAnswer");
	var wAnswer = document.getElementById("wAnswer").value;
	if(wAnswer == 'C.全住戸返答+確定時未返事シート' ){
		var wAnswerTanka = 600;
	}else if(wAnswer == 'B.全住戸返答' ){
		var wAnswerTanka = 500;
	}else if(wAnswer == 'A.日時変更住戸のみ返答' ){
		var wAnswerTanka = 400;
	}

	var wPicStatus =  document.getElementById("wPicStatus").value;
	if( wPicStatus == 1){
	 	 wPicCost =  parseInt( 5000 ) +  ( parseInt(100) * parseInt( __wKosu__) );
	}

	 wAnswerCost = parseInt( wPicCost ) + parseInt( wAnswerTanka) * parseInt( __wKosu__);
//	alert( wAnswerCost );

	var wSmartFlg = document.getElementById("wSmartFlg").value;
  
   alert('スマートボードあり');
	if( wSmartFlg == 1){
	 	 wSmartCost =  6000 ;
	}
 
    var wPostType1Check = document.mainform.wPostType1.checked;
    var wPostType2Check = document.mainform.wPostType2.checked;
    var wPostType3Check = document.mainform.wPostType3.checked;
    if( wPostType1Check == true ){
     	PostingCost = parseInt(10000 ) + (parseInt(100)  * ( parseInt( __wKosu__) + parseInt( 3 ) ));
	}
    if( wPostType2Check == true ){
     	PostingCost = parseInt( PostingCost ) + parseInt( 10000 ) +  ( parseInt(5) * ( parseInt( __wKosu__) + parseInt( 3 )) );
	}
    if( wPostType3Check == true ){
     	PostingCost = 50000 ;
	}
//工事写真
     var TotalCost = parseInt( wSmartCost ) +  parseInt( PostingCost ) + parseInt( wAnswerCost ); 

      alert( '見積額:' + TotalCost +'円');
//      alert(  TotalCost );

//    if (wPostType1Check == true) {
//      alert( "予定案内ポスティング");
//    }


//	if(document.getElementsByName("wAnswer").value!="") {	//機器名称がある

	
}
</script>


<script type="text/javascript" src="js/tools_ajax.js"></script>
<script type="text/javascript" src="js/ConnectedSelect.js"></script>

</head>

<body>
__SHeader__

<div class="content-all"><!--content-all-->

<div class="left-yose">
<a href="s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜メニュー</a>

<h6>予約センター受付依頼</h6>


<!--
<a href="s_489_copy.php__QUERY__&editBukkenCD=__editBukkenCD__">＞＞ 過去の物件</a>
★マークの項目は、過去物件の依頼データを反映することが可能です。<br>
この機能を使う場合は、入力前に設定してください。（先に入力すると、入力内容がクリアされます。）<br>
-->

<form action="s_489_confirm.php" method="POST" name="mainform" enctype="multipart/form-data">
<input type="hidden" name="editFileCD" value="" >
<input type="hidden" name="work" value="" >


<p align="right"><font color="blue">参考フォーマット：</font>
<a href="/kotei/upfile/iraifile_tmp/Format_ver1.xls">▼予定案内・確定案内フォーマットver1.xls</a>
<a href="/kotei/upfile/iraifile_tmp/iraitejun.pdf">依頼手順書</a></p>

1．受付依頼ステータス
<table class="table table-bordered table-sm">
<tr><td class="yb">件名No</td><td>__KenmeiNo__</td></tr>
<tr><td class="yb">物件CD</td><td>__editBukkenCD__</td></tr>
<tr><td class="yb">物件名</td><td>__wBukkenName__</td></tr>
<tr><td class="yb">所属支店</td><td>__wSitenName__</td></tr>
<tr><td class="yb">受付依頼ステータス</td><td>__wwIraiRenkeiStatus__</td></tr>
<tr><td class="yb">受付依頼日</td>
	<td><input type="text" name="wIraiDate" id="IraiDate" value="__wIraiDate__"></td></tr>
</table>

2．物件基本情報
<table class="table table-bordered table-sm">
<tr><td class="yb" colspan="2">住所</td><td>__wAddress__</td></tr>
<tr><td class="yb" colspan="2">★施工主体</td>
	<td>
		__IfMotoUke__元請け__IfMotoUke__<br>
		<input type="text" name="wSekoShutai" value="__wSekoShutai__" style="width:400px;">
		<br><font size="2">記入例）元請けではない場合、依頼元(管理会社名など）を記載します。</font>
	</td></tr>
<tr><td class="yb" colspan="2">マンション販売形態</td><td>__wwBunjyo__</td></tr>
<tr><td class="yb" colspan="2">総戸数</td><td>__wKosu__</td></tr>




 </table>
基本料金に含まれているもの
<table class="table table-bordered table-sm">

<tr><td class="yb" colspan="2">★専有部工事日時変更受付方法</td>
	<td>
		<select name="wAnswer"  style="width:300px;background-color:#FFF0F5;" id="wAnswer"  >
		<option value="">-</option>
		<option value="A.日時変更住戸のみ返答" __Answer1Selected__>A.日時変更住戸のみ返答</option>
		<option value="B.全住戸返答" __Answer2Selected__>B.全住戸返答</option>
		<option value="C.全住戸返答+確定時未返事シート" __Answer3Selected__>C.全住戸返答+確定時未返事シート</option>
		</select>
	</td></tr>
<tr><td class="yb" colspan="2">WEB受付</td>
	<td bgcolor="#FFF0F5">
		<input type="radio" name="wWEBRecept" value="0" __WEBRecept0Checked__  >無　
		<input type="radio" name="wWEBRecept" value="1" __WEBRecept1Checked__ >有　
	</td>
</tr>
<tr><td class="yb" rowspan="3" >利用様式</td>
	<td class="yb">予定案内</td>
	<td>
全国統一フォーマットとなります。<br>
<!--<select name="wYoshikiYotei">
		<option value="利用しない" __YoshikiYotei0Selected__>利用しない</option>
		<option value="予定１-A" __YoshikiYotei1Selected__>予定１-A</option>
		<option value="予定１-B" __YoshikiYotei2Selected__>予定１-B</option>
		<option value="予定２-A" __YoshikiYotei3Selected__>予定２-A</option>
		<option value="予定２-B" __YoshikiYotei4Selected__>予定２-B</option>
		<option value="予定３-A" __YoshikiYotei5Selected__>予定３-A</option>
		<option value="予定３-B" __YoshikiYotei6Selected__>予定３-B</option>
		<option value="独自書式" __YoshikiYotei7Selected__>独自書式</option>
		</select>-->
		<input type="hidden"  name="wYoshikiYotei" value="統一書式" >
		<font color="red" size="2">※独自書式の場合はテンプレート添付必須</font>
	</td></tr>
<tr><td class="yb">確定案内</td>
	<td>
全国統一フォーマットとなります。<br>
<!--
<select name="wYoshikiKakutei">
		<option value="利用しない" __YoshikiKakutei0Selected__>利用しない</option>
		<option value="確定１" __YoshikiKakutei1Selected__>確定１</option>
		<option value="確定２" __YoshikiKakutei2Selected__>確定２</option>
		<option value="独自書式" __YoshikiKakutei3Selected__>独自書式</option>
		</select>
-->
		<input type="hidden"  name="wYoshikiKakutei" value="統一書式" >
		<font color="red" size="2">※独自書式の場合はテンプレート添付必須</font>
	</td></tr>
 <tr><td class="yb">★オプション案内</td>
	<td><select name="wOPUketuke">
		<option value="">-</option>
		<option value="1" __OPUketuke1Selected__>必要</option>
		<option value="2" __OPUketuke2Selected__>不要</option>
		</select>
	</td></tr>



<tr><td class="yb" colspan="2">★確定案内 記載社名</td>
	<td><select name="wKakuteiCompanyName">
		<option value="アイホン株式会社" __KakuteiCompany1Selected__>アイホン株式会社</option>
		<option value="アイホン株式会社+管理会社" __KakuteiCompany2Selected__>アイホン株式会社+管理会社</option>
		<option value="資料記載に合わせる" __KakuteiCompany3Selected__>資料記載に合わせる</option>
		<option value="その他" __KakuteiCompany4Selected__>その他</option><br>
		</select>
		<br>
		<font size="2" color="gray">※その他の場合は「★8．その他（注意事項等）」に入力して下さい</font><br>
		<font color="red" size="2">※ポスティングありの場合は記入必須</font>
	</td></tr>

  



<tr><td class="yb" colspan="2">タブレット確認書アプリ利用</td>
	<td><select name="wKakuninFlg">

			<option value="1" __KakuninFlg1Selected__>利用する</option>
		</select>　<font size="2" color="gray">※費用はかかりません。</font>
	</td></tr>
<tr><td class="yb" rowspan="2">確認書アプリ</td>
	<td class="yb">タイトル</td>
	<td><input type="text" name="wKakuninTitle" value="__wKakuninTitle__" size="30"><br>
		<font size="2" color="dimgray">※「インターホン工事完了確認書」以外の場合修正</font>
	</td></tr>
<tr><td class="yb">フッター</td>
	<td><input type="text" name="wKakuninFooter" value="__wKakuninFooter__" size="30"><br>
		<font size="2" color="dimgray">※「アイホン株式会社」以外の場合修正</font>
	</td></tr>


</tr></table>
オプション (基本料金に含まれていないメニュー）
 <table class="table table-bordered table-sm">

<tr><td class="yb" rowspan=2 >工事写真管理アプリ</td><td class="yb" >利用有無</td>
	<td nowrap class="common-list-value-left" bgcolor="#FFF0F5">
		<select name="wPicStatus" id="wPicStatus" >
			<option value="0" __PicStatus0Selected__>利用しない</option>
			<option value="1" __PicStatus1Selected__>利用する</option>
		</select>
	</td></tr>
<tr><td class="yb" >施工会社名</td>
	<td><input type="text" name="wPicAppSekoName" value="__wPicAppSekoName__" style="width:300px;">
<br>電子看板の施工会社名（15文字以内）</td></tr>


	<tr><td class="yb" rowspan="6" >ポスティング業務依頼</td>
		<td  class="yb">利用有無</td>
		<td nowrap class="common-list-value-left" bgcolor="#FFF0F5">
       	
		<select name="wPostingFlg" id="wPostingFlg" >
		<option value="2" __PostingSelected2__>利用しない</option>
		<option value="1" __PostingSelected1__>利用する</option>
		</select>

</td>
	</tr>
	<tr>
		<td  class="yb" rowspan="1" >投函場所</td>
		<td nowrap class="common-list-value-left">
		<input type="radio" name="wPostingBasho" value="1"  __PostingBashoChecked1__ >集合ポスト
		<input type="radio" name="wPostingBasho" value="2"  __PostingBashoChecked2__ >ドア前ポスト/ドア貼り付け</td>
	</tr>
	<tr>

		<td  class="yb" rowspan="1" >投函する案内</td>
		<td nowrap class="common-list-value-left">

		<!--<input type="checkbox" name="wYoteiPost">おまかせ東京パック（ヒアリング付き現調案内、予定・確定・催促）<br>-->
		<input type="checkbox" name="wPostType[]" id="wPostType1" value="1" __PostTypeChecked1__  >予定案内<br>
		<input type="checkbox" name="wPostType[]" id="wPostType2"  value="2" __PostTypeChecked2__  >確定案内・未連絡住戸用催促案内<br>
		<input type="checkbox" name="wPostType[]" id="wPostType3"  value="3" __PostTypeChecked3__  >東京おまかせパック（現調・予定・確定）<br>
		掲示場所（エントランス、エレベータ、非常口）<input type="text" name="wKeijiBasho" value="__wKeijiBasho__"  >
		</td>

	</tr>

	<tr>
		<td  class="yb">腕章</td>
		<td nowrap class="common-list-value-left">
		<input type="radio" name="wWanshoFlg" value="1" __WanshoFlgChecked1__ >腕章をして投函<br>
		<input type="radio" name="wWanshoFlg" value="2" __WanshoFlgChecked2__ >腕章しない<br>
		</td>
	</tr>
 	<tr><td class="yb" >管理員向け封書宛名</td>
	<td><select name="wPostingName">
			<option value="アイホン株式会社" __PostingName1Selected__>アイホン株式会社</option>
			<option value="アイホン株式会社+管理会社" __PostingName2Selected__>アイホン株式会社+管理会社</option>
			<option value="資料記載に合わせる" __PostingName3Selected__>資料記載に合わせる</option>
			<option value="その他" __PostingName4Selected__>その他</option><br>
		</select>
		<br>管理員様封書の差出名
	</td></tr>
	<tr>
		<td  class="yb">その他依頼事項</td>
		<td nowrap class="common-list-value-left">
		専用封筒に入れ投函などご要望がありましたら記載お願いします。<br>

		<input type="text" name="wPostingBiko" value="__wPostingBiko__" style="width:300px;">


		</td>
	</tr>


<tr><td class="yb" >スマートボード利用</td><td class="yb" >利用有無</td>
	<td nowrap class="common-list-value-left" bgcolor="#FFF0F5">

		<select name="wSmartFlg" id="wSmartFlg" >
			<option value="0" __SmartFlg0Selected__>利用しない</option>
			<option value="1" __SmartFlg1Selected__>利用する</option>
		</select>
	</td></tr>


</table>

 <input type="button" value="見積書ダウンロード" onclick="CheckAndEstimate();" class="btn btn-primary">
 <!--



</table>

    -->





 <br>


3．日程
<table class="table table-bordered table-sm">
<tr><td class="yb">予定案内提供日</td>
	<td><input type="text" name="wYoteTekyoDate" id="YoteTekyoDate" value="__wYoteTekyoDate__"  size="10"></td>
	<td class="yb"><font size="2" color="dimgray">ネスペ⇒担当者に資料を提供する日</font></td></tr>

<tr><td class="yb">工事説明資料配布日</td>
	<td><input type="hidden" name="wYoteDate" id="YoteDate" value="__wYoteDate__">__wYoteDate__</td>
	<td class="yb"><font size="2" color="dimgray">予約システム受付サービス開始日</font></td></tr>

<tr><td class="yb">変更受付締切日</td>
	<td><input type="hidden" name="wYoyakuEnd" id="YoyakuEnd" value="__wYoyakuEnd__" >__wYoyakuEnd__</td>
	<td class="yb"><font size="2" color="dimgray">受付を一旦終了し、工事日時調整開始</font></td></tr>

<tr><td class="yb">専有部決定案内提供日</td>
	<td><input type="text" name="wTekyoDate" id="TekyoDate" value="__wTekyoDate__" size="10"></td>
	<td class="yb"><font size="2" color="dimgray">ネスペ⇒担当者に資料を提供する日(AM中)</font></td></tr>

<tr><td class="yb">専有部決定案内配布日</td>
	<td><input type="hidden" name="wKeteiDate" id="KeteiDate" value="__wKeteiDate__" >__wKeteiDate__</td>
	<td class="yb"><font size="2" color="dimgray">以後の専有部工事日時変更は、メールにて報告</font></td></tr>


<tr><td class="yb">オプション締切日</td>
	<td><input type="text" name="wOpEndDate" id="OpEndDate" value="__wOpEndDate__" size="10" ></td>
	<td class="yb"><font size="2" color=red> オプションの締切が日程の締切と異なる場合変更します</font></td></tr>
</table>

4．工事施工会社情報
<table class="table table-bordered table-sm">
<tr><td class="yb" colspan="2">アイホン担当者情報　<a href=./s_tanto_detail.php?rKey=__rKey__>リストにない場合の登録はこちらから＞＞</a>
	</td></tr>
<tr><td class="yb">アイホン担当者１</td>
	<td>
		<select name="wTantoCD1">
		<option value="0">-</option>
		__TantoLoop__
		<option value="__TantoCD__" __TantoCD1Selected__ >__EigyoshoName__　__TantoName__</option>
		__TantoLoop__
		</select>
	</td></tr>
<tr><td class="yb">アイホン担当者２</td>
	<td>
		<select name="wTantoCD2">
		<option value="0">-</option>
		__TantoLoop__
		<option value="__TantoCD__" __TantoCD2Selected__ >__EigyoshoName__　__TantoName__</option>
		__TantoLoop__
		</select>
	</td></tr>
<tr><td class="yb" colspan="2">施工業者情報
	<br><font size="2" color="gray">※リストにない場合は「8．その他（注意事項等）」に会社名,担当者名,連絡先,メールアドレスをご記入お願いします。</font></td></tr>
<tr><td class="yb">施工業者担当者１</td>
	<td>
		<select name="wGyosyaTantoCD1">
		<option value="0">-</option>
		__GyosyaTantoLoop__
		<option value="__GyosyaTantoCD__" __GyosyaTantoCD1Selected__>__GyosyaName__ __GyosyaTantoName__</option>
		__GyosyaTantoLoop__
		</select>
	</td></tr>
<tr><td class="yb">施工業者担当者２</td>
	<td>
		<select name="wGyosyaTantoCD2">
		<option value="0">-</option>
		__GyosyaTantoLoop__
		<option value="__GyosyaTantoCD__" __GyosyaTantoCD2Selected__ > __GyosyaName__ __GyosyaTantoName__</option>
		__GyosyaTantoLoop__
		</select>
	</td></tr>
</table>

5．工事可能戸数
<table class="table table-bordered table-sm">
<tr><td class="yb" rowspan="3">作業時間帯区分</td>
	<td class="yb">午前</td>
	<td>__wTimeAStart__～__wTimeAEnd__ 　__wTimeASu__戸	</td></tr>
<tr><td class="yb">午後１</td>
	<td>__wTimeBStart__～__wTimeBEnd__ 　__wTimeBSu__戸	</td></tr>
<tr><td class="yb">午後２</td>
	<td>__wTimeCStart__～__wTimeCEnd__ 　__wTimeCSu__戸	</td></tr>
</table>
<input type="hidden" name="wTimeAStart" value="__wTimeAStart__" >
<input type="hidden" name="wTimeAEnd" value="__wTimeAEnd__" >
<input type="hidden" name="wTimeASu" value="__wTimeASu__" >
<input type="hidden" name="wTimeBStart" value="__wTimeBStart__" >
<input type="hidden" name="wTimeBEnd" value="__wTimeBEnd__" >
<input type="hidden" name="wTimeBSu" value="__wTimeBSu__" >
<input type="hidden" name="wTimeCStart" value="__wTimeCStart__" >
<input type="hidden" name="wTimeCEnd" value="__wTimeCEnd__" >
<input type="hidden" name="wTimeCSu" value="__wTimeCSu__" >




6．施工方法情報
<table class="table table-bordered table-sm">
<tr><td class="yb" colspan="2"><font size="2" color="gray">※リストにない場合は「★8．その他（注意事項等）」に機器名、型番、価格をご記入お願いします。</font></td></tr>

<tr><td class="yb">居室親機型番</td>
	<td>__OyaKataban__ (__OyaDeviceName__)
	</td></tr>
<tr><td class="yb">玄関子機型番</td>
	<td>
		__KokiKataban__ (__KokiDeviceName__)
	</td></tr>
<!--<tr><td class="yb">★玄関子機パネル型番</td>
	<td><input type="hidden" name="wKokiPanelKataban" value="__wKokiPanelKataban__" >__wKokiPanelKataban__</td></tr>-->
<tr><td class="yb" rowspan="10">オプション機器<br>販売価格</td>
	<td>１．

		__OP1Name__<input type="hidden" name="OP1Name" value="__OP1Name__" >
		　__wwOPPrice1__<input type="hidden" name="wOPPrice1" value="__wOPPrice1__" >
	</td></tr>
<tr><td>２．__OP2Name__<input type="hidden" name="OP2Name" value="__OP2Name__" >
		　__wwOPPrice2__<input type="hidden" name="wOPPrice2" value="__wOPPrice2__" >
	</td></tr>
<tr><td>３．__OP3Name__<input type="hidden" name="OP3Name" value="__OP3Name__" >
		　__wwOPPrice3__<input type="hidden" name="wOPPrice3" value="__wOPPrice3__" >
	</td></tr>
<tr><td>４．__OP4Name__<input type="hidden" name="OP4Name" value="__OP4Name__" >
		　__wwOPPrice4__<input type="hidden" name="wOPPrice4" value="__wOPPrice4__" >
	</td></tr>
<tr><td>５．__OP5Name__<input type="hidden" name="OP5Name" value="__OP5Name__" >
		　__wwOPPrice5__<input type="hidden" name="wOPPrice5" value="__wOPPrice5__" >
	</td></tr>
<tr><td>６．__OP6Name__<input type="hidden" name="OP6Name" value="__OP6Name__" >
		　__wwOPPrice6__<input type="hidden" name="wOPPrice6" value="__wOPPrice6__" >
	</td></tr>
<tr><td>７．__OP7Name__<input type="hidden" name="OP7Name" value="__OP7Name__" >
		　__wwOPPrice7__<input type="hidden" name="wOPPrice7" value="__wOPPrice7__" >
	</td></tr>
<tr><td>８．__OP8Name__<input type="hidden" name="OP8Name" value="__OP8Name__" >
		　__wwOPPrice8__<input type="hidden" name="wOPPrice8" value="__wOPPrice8__" >
	</td></tr>
<tr><td>９．__OP9Name__<input type="hidden" name="OP9Name" value="__OP9Name__" >
		　__wwOPPrice9__<input type="hidden" name="OP9Name" value="__OP9Name__" >
	</td></tr>
<tr><td>１０．__OP10Name__<input type="hidden" name="OP10Name" value="__OP10Name__" >
		　__wwOPPrice10__<input type="hidden" name="OP10Name" value="__OP10Name__" >
<input type="hidden" name="wOPCategory1" value="__wOPCategory1__" >
<input type="hidden" name="wOPCategory2" value="__wOPCategory2__" >
<input type="hidden" name="wOPCategory3" value="__wOPCategory3__" >
<input type="hidden" name="wOPCategory4" value="__wOPCategory4__" >
<input type="hidden" name="wOPCategory5" value="__wOPCategory5__" >
<input type="hidden" name="wOPCategory6" value="__wOPCategory6__" >
<input type="hidden" name="wOPCategory7" value="__wOPCategory7__" >
<input type="hidden" name="wOPCategory8" value="__wOPCategory8__" >
<input type="hidden" name="wOPCategory9" value="__wOPCategory9__" >
<input type="hidden" name="wOPCategory10" value="__wOPCategory10__" >
	</td></tr>
<tr><td class="yb">★オプション支払方法</td>
	<td>
		__wShiharai0__ __wShiharai1__
		__wShiharai2__ __wShiharai4__ 
		<input type="hidden" name="wShiharai" value="__wShiharai__">
	</td></tr>
<tr><td class="yb">1日の基本班体制数</td>
	<td><input type="hidden" name="wHansu" value="__wHansu__" >__wHansu__ 班</td></tr>
<tr><td class="yb">1戸あたりの標準作業時間</td>
	<td><input type="hidden" name="wConstTime" value="__wConstTime__" >__wConstTime__ 分</td></tr>
<tr><td class="yb">住戸工事期間中の休工日</td>
	<td><input type="hidden" name="wKyukoDate" value="__wKyukoDate__" >__wKyukoDate__</td></tr>
<tr><td class="yb">工事期間中の集玄開錠方法</td>
	<td>
		<input type="checkbox" name="wKaijyo[]" value="鍵" __Kaijyo1Checked__>鍵
		<input type="checkbox" name="wKaijyo[]" value="仮暗証番号" __Kaijyo2Checked__>仮暗証番号
		<input type="checkbox" name="wKaijyo[]" value="暗証番号" __Kaijyo3Checked__>暗証番号
		<input type="checkbox" name="wKaijyo[]" value="工事期間中は終日開放" __Kaijyo4Checked__>工事期間中は終日開放
	</td></tr>
<!--<tr><td class="yb">★居室端末の移動対応</td>
	<td>
		<select name="wIsetu">
		<option value="">-</option>
		<option value="しない" __Isetu1Selected__>しない</option>
		<option value="有料にて対応" __Isetu2Selected__>有料にて対応</option>
		<option value="無料対応" __Isetu3Selected__>無料対応</option>
		<option value="アイホン担当者に確認" __Isetu4Selected__>アイホン担当者に確認</option>
		</select></td></tr>-->
</table>

7．写真撮影方法  工事写真管理アプリ利用時のみご記入ください。
<table class="table table-bordered table-sm">
<tr><td class="yb">写真台帳提供日</td>
	<td><input type="text" name="wPhotoTekyoDate" id="PhotoTekyoDate" value="__wPhotoTekyoDate__" size="10"></td>
	<td class="yb">写真台帳の提供日(以降の写真はアイホンメンテ)</td></tr>

<tr><td class="yb">★台帳選択</td>
	<td><select name="wPhotoPattern">
		<option value="">-</option>
		<option value="a-1" __PhotoPattern1Selected__>a-1　施工中なし　</option>
		<!--<option value="a-2" __PhotoPattern2Selected__>a-2</option>-->
		<option value="b-1" __PhotoPattern3Selected__>b-1　施工中あり　</option>
		<!--<option value="b-2" __PhotoPattern4Selected__>b-2</option>-->
		</select>
		
	</td>
	<td class="yb">写真台帳【選択】シートより確認<br>※残工事部屋がある場合その部屋の写真スペースはあけておきます。</td></tr>

<tr><td class="yb">専有部</td>
	<td class="yb">機器名　</td>
	<td class="yb">撮影シーン</td></tr>
<tr><td class="yb">★項目1</td>
	<td><select name="wPhotoSenyu1">
		<option value="1" __PhotoSenyu11Selected__>居室親機</option>
		<option value="2" __PhotoSenyu12Selected__>住宅情報盤</option>
		<option value="3" __PhotoSenyu13Selected__>玄関子機</option>
		<option value="0">-</option>
		</select>
	</td>
	<td>
		<select name="wPSScene1">
		<option value="1" __PSScene11Selected__>施工前/施工後</option>
		<option value="2" __PSScene12Selected__>施工前/施工中/施工後</option>
		<option value="3" __PSScene13Selected__>施工後のみ</option>
		<option value="0">-</option>
		</select>
	</td></tr>
<tr><td class="yb">★項目2</td>
	<td><select name="wPhotoSenyu2">
		<option value="1" __PhotoSenyu21Selected__>居室親機</option>
		<option value="2" __PhotoSenyu22Selected__>住宅情報盤</option>
		<option value="3" __PhotoSenyu23Selected__>玄関子機</option>
		<option value="0">-</option>
		</select>
	</td>
	<td><select name="wPSScene2">
		<option value="1" __PSScene21Selected__>施工前/施工後</option>
		<option value="2" __PSScene22Selected__>施工前/施工中/施工後</option>
		<option value="3" __PSScene23Selected__>施工後のみ</option>
		<option value="0">-</option>
		</select>
	</td></tr>
<tr><td class="yb">★項目3</td>
	<td><select name="wPhotoSenyu3">
		<option value="0">-</option>
		<option value="1" __PhotoSenyu31Selected__>居室親機</option>
		<option value="2" __PhotoSenyu32Selected__>住宅情報盤</option>
		<option value="3" __PhotoSenyu33Selected__>玄関子機</option>
		</select>
	</td>
	<td><select name="wPSScene3">
		<option value="0">-</option>
		<option value="1" __PSScene31Selected__>施工前/施工後</option>
		<option value="2" __PSScene32Selected__>施工前/施工中/施工後</option>
		<option value="3" __PSScene33Selected__>施工後のみ</option>
		</select>
	</td></tr>
<tr><td class="yb">★項目4</td>
	<td><select name="wPhotoSenyu4">
		<option value="0">-</option>
		<option value="1" __PhotoSenyu41Selected__>居室親機</option>
		<option value="2" __PhotoSenyu42Selected__>住宅情報盤</option>
		<option value="3" __PhotoSenyu43Selected__>玄関子機</option>
		</select>
	</td>
	<td><select name="wPSScene4">
		<option value="0">-</option>
		<option value="1" __PSScene41Selected__>施工前/施工後</option>
		<option value="2" __PSScene42Selected__>施工前/施工中/施工後</option>
		<option value="3" __PSScene43Selected__>施工後のみ</option>
		</select>
	</td></tr>

<tr><td class="yb">共用部</td>
	<td class="yb">機器名</td>
	<td class="yb">撮影シーン</td></tr>
<tr><td class="yb">★項目1</td>
	<td><select name="wPhotoKyoyo1">
		<option value="1" __PhotoKyoyo11Selected__>制御装置</option>
		<option value="2" __PhotoKyoyo12Selected__>管理室親機</option>
		<option value="3" __PhotoKyoyo13Selected__>集合玄関機</option>
		<option value="4" __PhotoKyoyo14Selected__>映像増幅器</option>
		<option value="0">-</option>
		</select>
	</td>
	<td><select name="wPKScene1">
		<option value="1" __PKScene11Selected__>施工前/施工後</option>
		<option value="2" __PKScene12Selected__>施工前/施工中/施工後</option>
		<option value="3" __PKScene13Selected__>施工後のみ</option>
		<option value="0">-</option>
		</select>
	</td></tr>
<tr><td class="yb">★項目2</td>
	<td><select name="wPhotoKyoyo2">
		<option value="1" __PhotoKyoyo21Selected__>制御装置</option>
		<option value="2" __PhotoKyoyo22Selected__>管理室親機</option>
		<option value="3" __PhotoKyoyo23Selected__>集合玄関機</option>
		<option value="4" __PhotoKyoyo24Selected__>映像増幅器</option>
		<option value="0">-</option>
		</select>
	</td>
	<td><select name="wPKScene2">
		<option value="1" __PKScene21Selected__>施工前/施工後</option>
		<option value="2" __PKScene22Selected__>施工前/施工中/施工後</option>
		<option value="3" __PKScene23Selected__>施工後のみ</option>
		<option value="0">-</option>
		</select>
	</td></tr>
<tr><td class="yb">★項目3</td>
	<td><select name="wPhotoKyoyo3">
		<option value="1" __PhotoKyoyo31Selected__>制御装置</option>
		<option value="2" __PhotoKyoyo32Selected__>管理室親機</option>
		<option value="3" __PhotoKyoyo33Selected__>集合玄関機</option>
		<option value="4" __PhotoKyoyo34Selected__>映像増幅器</option>
		<option value="0">-</option>
		</select>
	</td>
	<td><select name="wPKScene3">
		<option value="1" __PKScene31Selected__>施工前/施工後</option>
		<option value="2" __PKScene32Selected__>施工前/施工中/施工後</option>
		<option value="3" __PKScene33Selected__>施工後のみ</option>
		<option value="0">-</option>
		</select>
	</td></tr>
<tr><td class="yb">★項目4</td>
	<td><select name="wPhotoKyoyo4">
		<option value="0">-</option>
		<option value="1" __PhotoKyoyo41Selected__>制御装置</option>
		<option value="2" __PhotoKyoyo42Selected__>管理室親機</option>
		<option value="3" __PhotoKyoyo43Selected__>集合玄関機</option>
		<option value="4" __PhotoKyoyo44Selected__>映像増幅器</option>
		</select>
	</td>
	<td><select name="wPKScene4">
		<option value="0">-</option>
		<option value="1" __PKScene41Selected__>施工前/施工後</option>
		<option value="2" __PKScene42Selected__>施工前/施工中/施工後</option>
		<option value="3" __PKScene43Selected__>施工後のみ</option>
		</select>
	</td></tr>
<tr><td class="yb">★項目5</td>
	<td><input type="text" name="wPhotoKyoyo5" value="__wPhotoKyoyo5__"></td>
	<td><select name="wPKScene5">
		<option value="0">-</option>
		<option value="1" __PKScene51Selected__>施工前/施工後</option>
		<option value="2" __PKScene52Selected__>施工前/施工中/施工後</option>
		<option value="3" __PKScene53Selected__>施工後のみ</option>
		</select>
	</td></tr>
<tr><td class="yb">★項目6</td>
	<td><input type="text" name="wPhotoKyoyo6" value="__wPhotoKyoyo6__"></td>
	<td><select name="wPKScene6">
		<option value="0">-</option>
		<option value="1" __PKScene61Selected__>施工前/施工後</option>
		<option value="2" __PKScene62Selected__>施工前/施工中/施工後</option>
		<option value="3" __PKScene63Selected__>施工後のみ</option>
		</select>
	</td></tr>
<tr><td class="yb">★項目7</td>
	<td><input type="text" name="wPhotoKyoyo7" value="__wPhotoKyoyo7__"></td>
	<td><select name="wPKScene7">
		<option value="0">-</option>
		<option value="1" __PKScene71Selected__>施工前/施工後</option>
		<option value="2" __PKScene72Selected__>施工前/施工中/施工後</option>
		<option value="3" __PKScene73Selected__>施工後のみ</option>
		</select>
	</td></tr>
<tr><td class="yb">★項目8</td>
	<td><input type="text" name="wPhotoKyoyo8" value="__wPhotoKyoyo8__"></td>
	<td><select name="wPKScene8">
		<option value="0">-</option>
		<option value="1" __PKScene81Selected__>施工前/施工後</option>
		<option value="2" __PKScene82Selected__>施工前/施工中/施工後</option>
		<option value="3" __PKScene83Selected__>施工後のみ</option>
		</select>
	</td></tr>
<tr><td class="yb">★項目9</td>
	<td><input type="text" name="wPhotoKyoyo9" value="__wPhotoKyoyo9__"></td>
	<td><select name="wPKScene9">
		<option value="0">-</option>
		<option value="1" __PKScene91Selected__>施工前/施工後</option>
		<option value="2" __PKScene92Selected__>施工前/施工中/施工後</option>
		<option value="3" __PKScene93Selected__>施工後のみ</option>
		</select>
	</td></tr>
</table>

★8．その他（注意事項等）
<table class="table table-bordered table-sm">
<tr><td class="yb">備考</td></tr>
<tr><td><textarea name="wNotes" cols="100" rows="7">__wNotes__</textarea></td></tr>
</table>

9.関連資料（住人様ご案内資料、仮日程表など）
<table class="table table-bordered table-sm">

<tr><td>
<!--		<table class="table table-borderless">
		<tr><td>
				<input type="hidden" name="MAX_FILE_SIZE" value="10485760" />

				<span id="f1"><input type="file" name="tempfile[]" size="30" id="tempfile1" onchange="check(1)" /></span>
				<input type="button" value="クリア" onclick="tempfileclear('f1')"><br />

				<span id="f2"><input type="file" name="tempfile[]" size="30" id="tempfile2" onchange="check(2)" /></span>
				<input type="button" value="クリア" onclick="tempfileclear('f2')"><br />

				<span id="f3"><input type="file" name="tempfile[]" size="30" id="tempfile3" onchange="check(3)" /></span>
				<input type="button" value="クリア" onclick="tempfileclear('f3')"><br />

				<span id="f4"><input type="file" name="tempfile[]" size="30" id="tempfile4" onchange="check(4)" /></span>
				<input type="button" value="クリア" onclick="tempfileclear('f4')"><br />

				<span id="f5"><input type="file" name="tempfile[]" size="30" id="tempfile5" onchange="check(5)" /></span>
				<input type="button" value="クリア" onclick="tempfileclear('f5')"><br />
			</td>
			<td>
				<div id="tempfileresult"></div>
			</td></tr>
		</table>
		<div id="tempfilesizeresult" style="display: none;"></div>

		<br>
-->
		<table class="table table-bordered table-sm">
			<tr><td class="yb" colspan="4">登録済ファイル</td></tr>
				__FileLoop__
				<tr>
					<td style="width:30px">__FileNo__</td>
					<td>・__File__</td>
					<td style="width:160px"><font size="2">__Created__</font></td> 
					<td style="width:60px"><input type="button" value="削除" class="button" onclick="javascript:moveWithWorkAndFileCD('s_489.php',  __FileCD__ ,2 )"></td>
				</tr>
				__FileLoop__
		</table>
	</td>
</tr>
</table>

<!--施工会社様向け依頼事項Start-->
<!--
10.施工業者指示内容
<table class="table table-bordered table-sm">
<tr><td bgcolor="palegreen" colspan="2">施工業者様へ下記内容を予約センターから通知いたします。</td></tr>

<tr><td bgcolor="palegreen">リニューアル方式</td>
	<td>
		<input type="radio" name="wRenewalType" __RenewalTypeChecked1__ value="1">既設システム停止でのRN<br>
		<input type="radio" name="wRenewalType" __RenewalTypeChecked2__ value="2">新旧並行稼働でのRN
	</td></tr>
<tr><td bgcolor="palegreen">配線</td>
	<td>
		<input type="radio" name="wHaisenType" __HaisenTypeChecked1__ value="1">既設配線流用<br>
		<input type="radio" name="wHaisenType" __HaisenTypeChecked2__ value="2">既設配線抜き替え（大幅）<br>
		<input type="radio" name="wHaisenType" __HaisenTypeChecked3__ value="3">既設配線抜き替え（一部）<br>
		<input type="radio" name="wHaisenType" __HaisenTypeChecked4__ value="4">新規露出配線
	</td></tr>
<tr><td bgcolor="palegreen">管理室呼び出し</td>
	<td>
		<input type="radio" name="wKanrishituCall" __KanrishituCallChecked1__ value="1">表示なし<br>
		<input type="radio" name="wKanrishituCall" __KanrishituCallChecked2__ value="2">常時表示<br>
		<input type="radio" name="wKanrishituCall" __KanrishituCallChecked3__ value="3">メモリ時のみ
	</td></tr>
<tr><td bgcolor="palegreen">写真撮影件数（共用部）</td>
	<td>
		<input type="radio" name="wPicKSu" __PicKSuChecked1__ value="1">全箇所<br>
		<input type="radio" name="wPicKSu" __PicKSuChecked2__ value="2">主要機器のみ<br>
		<input type="radio" name="wPicKSu" __PicKSuChecked3__ value="3">不要
	</td></tr>
<tr><td bgcolor="palegreen">写真撮影枚数（共用部）</td>
	<td>
		<input type="radio" name="wPicKType" __PicKTypeChecked1__ value="1">作業前・中・後<br>
		<input type="radio" name="wPicKType" __PicKTypeChecked2__ value="2">作業前・後<br>
		<input type="radio" name="wPicKType" __PicKTypeChecked3__ value="3">不要
	</td></tr>
<tr><td bgcolor="palegreen">写真撮影件数（専有部）</td>
	<td>
		<input type="radio" name="wPicSu" __PicSuChecked1__ value="1">全戸<br>
		<input type="radio" name="wPicSu" __PicSuChecked2__ value="2">抜粋<br>
		<input type="radio" name="wPicSu" __PicSuChecked3__ value="3">不要
	</td></tr>
<tr><td bgcolor="palegreen">写真撮影枚数（専有部）</td>
	<td>
		<input type="radio" name="wPicType" __PicTypeChecked1__ value="1">作業前・中・後<br>
		<input type="radio" name="wPicType" __PicTypeChecked2__ value="2">作業前・後<br>
		<input type="radio" name="wPicType" __PicTypeChecked3__ value="3">不要
	</td></tr>
<tr><td bgcolor="palegreen">消防申請</td>
	<td>
		<input type="radio" name="wShobo" __ShoboChecked1__ value="1">必要<br>
		<input type="radio" name="wShobo" __ShoboChecked2__ value="2">不要
	</td></tr>
<tr><td bgcolor="palegreen">工事完了後の提出書類</td>
	<td>
		<input type="checkbox" name="wTeishutuDoc[]" __TeishutuDocChecked1__ value="1">完了確認書<br>
		<input type="checkbox" name="wTeishutuDoc[]" __TeishutuDocChecked2__ value="2">工事写真台帳<br>
		<input type="checkbox" name="wTeishutuDoc[]" __TeishutuDocChecked3__ value="3">系統図
	</td></tr>
<tr><td bgcolor="palegreen">その他指示事項</td>
	<td><textarea name="wSonotaShiji" cols="80" rows="7">__wSonotaShiji__</textarea></td></tr>
</table>

-->
<!--施工会社様向け依頼事項End-->




<br><br>
<input type="hidden" name="rKey" value="__rKey__">
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
<input type="hidden" name="wIraiRenkeiStatus" value="__wIraiRenkeiStatus__" >
<input type="hidden" name="editIraiRenkeiCD" value="__editIraiRenkeiCD__" > 


<!-- ファイル名取得のため-->
<input type="submit" value="  内容確認  "  class="btn btn-primary" >
<!--<input type="hidden" name="hiddenfilenames" value="">
<input type="button" value="内容確認" onclick="javascript:FilesSubmit(this.form);" class="btn btn-primary">-->
</form>
<br>


</div>



</div><!--content-all-->

<hr>

__SFooter__
__SCopyright__

</body>
</html>

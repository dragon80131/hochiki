<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>__TITLENAME__</title>

<!-- BootstrapのCSS読み込み -->
<link href="../include/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../css/rnsien.css">
<link href="../css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />

<script src="../include/js/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>	
<!-- BootstrapのJS読み込み -->
<script src="../include/bootstrap/js/bootstrap.min.js"></script>

<script type="text/javascript" src="../js/tools_ajax.js"></script>
<script type="text/javascript" src="../js/ConnectedSelect.js"></script>
<script src="../js/jquery.ui.core.js" type="text/javascript"></script>
<script src="../js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="../js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
<script type="text/javascript" src="../tools.js"></script>
<style>
	.modal-header{
		border-bottom:none;
	}
	.modal-footer{
		border-top:none;
	}
	.modal-body{
		font-size:13px;
		padding:5px 1rem;
	}
	#confirmBtn{
		width:70px;
	}
	.modal-backdrop.show{
		display:none;
		opacity:0;
	}
	.modal-open .modal-backdrop.show{
		display:block;
		opacity:.5;
	}
	table.ex_table td.link_cell{
		font-size:20px;
		font-weight:bold;
	}
	@media (min-width: 576px) {
		.modal-dialog {
			max-width: 330px;
		}
	}

	.missing_cell_list{
		display:flex;
		flex-direction:row;
		flex-wrap:wrap;
		font-size:20px;
		font-weight:bold;
		color:red;
	}
	.missing_cell{
		display:block;
	}
	.missing_cell::after{
		content:'、';
	}
	.missing_cell:last-child::after{
		display:none;
	}
	.missing_cell a{
		color:red;
	}
	.missing_cell.active a{
		color:#007bff;
	}
	.missing_cell.active::after{
		color:#007bff;
	}
	.btn_wrap{
		display:flex;
		flex-direction:row;
		align-items:center;
		flex-wrap:wrap;
	}

</style>

<script >
document.addEventListener('DOMContentLoaded', function () {
	document.cookie = "downloadComplete=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
});

$(document).ready(function () {
	$('#alert').on('hidden.bs.modal', function (e) {
		document.location.href = '../s_menu.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__';
	});
});

var ReservationCount = parseInt('__ReservationCount__');
var tempSavedReservationCnt = parseInt('__tempSavedReservationCnt__');
if(isNaN(ReservationCount))
	ReservationCount = 0;

function modorumove(val){
	document.form1.action = val;
	document.form1.submit(true);
}

function go_confirm(url){
	if(tempSavedReservationCnt > 0){
		document.location.href = url;
	}else{
		if(confirm("修正した内容は反映されませんが、問題ございませんか？")){
			document.location.href = url;
		}
	}
}
function submit_confirm(page){
	if(tempSavedReservationCnt > 0){
		disableKoteihyouEXForPost();
		document.mainform.action = page;
		document.mainform.submit(true);
	}else{
		if(confirm("修正した内容は反映されませんが、問題ございませんか？")){
			disableKoteihyouEXForPost();
			document.mainform.action = page;
			document.mainform.submit(true);
		}
	}
}

function appendKoteihyouEXChunks() {
	$('form[name="mainform"] input[name^="wKoteihyouEX_"]').filter(function () {
		return this.name !== 'wKoteihyouEX_count';
	}).remove();
	$('form[name="mainform"] input[name="wKoteihyouEX_count"]').remove();

	let KoteihyouEXChunk = '|';
	let wKoteihyouEX_count = 0;
	let wKoteihyouEX_no = 0;
	$('input[name="wwKoteihyouEX[]"]').each(function() {
		KoteihyouEXChunk += $(this).val() + '|';
		wKoteihyouEX_no ++;
		if(wKoteihyouEX_no > 299){
			$('<input>').attr({
				type: 'hidden',
				name: 'wKoteihyouEX_'+wKoteihyouEX_count,
				value: KoteihyouEXChunk
			}).appendTo('form[name="mainform"]');
			wKoteihyouEX_count ++;
			wKoteihyouEX_no = 0;
			KoteihyouEXChunk = '|';
		}
	});
	if(wKoteihyouEX_no > 0){
		$('<input>').attr({
			type: 'hidden',
			name: 'wKoteihyouEX_'+wKoteihyouEX_count,
			value: KoteihyouEXChunk
		}).appendTo('form[name="mainform"]');
		wKoteihyouEX_count ++;
	}
	$('<input>').attr({
		type: 'hidden',
		name: 'wKoteihyouEX_count',
		value: wKoteihyouEX_count
	}).appendTo('form[name="mainform"]');

	$('input[name="wwKoteihyouEX[]"]').prop('disabled', true);

	return wKoteihyouEX_count;
}

function disableKoteihyouEXForPost() {
	$('input[name="wwKoteihyouEX[]"]').prop('disabled', true);
}

function makeKanryo(page){
	if(ReservationCount > 0){
		if(!confirm("すでに作業日程が作成されています。住人様が登録した情報等も削除されますが問題ございませんでしょうか。")){
			$('#alert').modal({
				backdrop: false,
				keyboard: false
			});
			return false;
		}
	}

	appendKoteihyouEXChunks();

	document.mainform.action = page;
	document.mainform.submit(true);
	const timer = setInterval(function() {
		if (document.cookie.includes('downloadComplete=1')) {
			clearInterval(timer);
			setTimeout(function () {
				document.location.href = '../s_menu.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__';
			}, 200);
		}
	}, 500);
}

function temporarilySave(){
	appendKoteihyouEXChunks();

	$('#act').val("temp_save");
	document.mainform.action = '';
	document.mainform.submit(true);
}

//submit前の入力チェック
function checkInput(){

	var error_flg = false;
	/*
	var answer = document.getElementById("wAnswer").value;
	var picappseko = document.getElementById("wPicAppSeko").value;
	var picappkoji = document.getElementById("wPicAppKoji").value;
	var postingflg = document.getElementById("wPostingFlg").value;
	var smartflg = document.getElementById("wSmartFlg").value;
	var Basho = "";

	if(answer==""){
		error_flg = true;
		document.getElementById("AnswerError").innerHTML = "※受付方法を選択してください。";
		Basho = 500;
	}else{
		document.getElementById("AnswerError").innerHTML = "";
	}
	if (picappseko.length > 15) {
		error_flg = true;
		document.getElementById("PicAppSekoError").innerHTML = "15文字以内で設定してください。";
		if(Basho == "") Basho = 900;
	} else {
		document.getElementById("PicAppSekoError").innerHTML = "";
	}
	if (picappkoji.length > 15) {
		error_flg = true;
		document.getElementById("PicAppKojiError").innerHTML = "15文字以内で設定してください。";
		if(Basho == "") Basho = 900;
	} else {
		document.getElementById("PicAppKojiError").innerHTML = "";
	}
	if(postingflg==""){
		error_flg = true;
		document.getElementById("PostingError").innerHTML = "※利用の有無を選択してください。";
		if(Basho == "") Basho = 1100;
	}else{
		document.getElementById("PostingError").innerHTML = "";
	}
	if(smartflg==""){
		error_flg = true;
		document.getElementById("SmartError").innerHTML = "※利用の有無を選択してください。";
		if(Basho == "") Basho = 1500;
	}else{
		document.getElementById("SmartError").innerHTML = "";
	}
	*/
	if(error_flg){
		window.scrollTo(0,Basho);
		return false;
	}else{
		document.mainform.method = "POST";
		document.mainform.target = "_self";
		document.mainform.action = "s_make_kotei_EXCEL.php?hensyu=1";
		document.mainform.submit();
	}

alert('ここになにかしかける');
}

window.onload = function() {
    location.hash = "#m" + __m__;
};

$(document).on('click', 'a[href="#"]', function (e) {
	e.preventDefault();
});
$(document).on('click', '.link_cell', function () {
	$(".link_cell").removeClass("active");
	$(this).addClass("active");
});
$(document).on('click', '.missing_cell', function () {
	$(".missing_cell").removeClass("active");
	$(this).addClass("active");
});

$(document).on('click', '.link_cell_blank', function () {
	let room_obj = $(".link_cell.active");
	if(room_obj.length){
		let blank_room = $(this).find("a").length>0?$(this).find("a").html():'';
		let blank_room_val = $(this).find('input[type="hidden"]').length>0?$(this).find('input[type="hidden"]').val():'';

		let valid_room = room_obj.find("a").length>0?room_obj.find("a").html():'';
		let valid_room_val = room_obj.find('input[type="hidden"]').length>0?room_obj.find('input[type="hidden"]').val():'';

		if(blank_room_val && valid_room_val){
			room_obj.find("a").html(blank_room);
			room_obj.find('input[type="hidden"]').val(blank_room_val);

			$(this).find("a").html(valid_room);
			$(this).find('input[type="hidden"]').val(valid_room_val);

			room_obj.removeClass('link_cell').removeClass('active').addClass('link_cell_blank');
			$(this).removeClass('link_cell_blank').removeClass('active').addClass('link_cell');
		}
	}else{
		room_obj = $(".missing_cell.active");
		if(room_obj.length){
			let blank_room = $(this).find("a").length>0?$(this).find("a").html():'';
			let blank_room_val = $(this).find('input[type="hidden"]').length>0?$(this).find('input[type="hidden"]').val():'';

			let valid_room = room_obj.find("a").length>0?room_obj.find("a").html():'';
			let valid_room_val = room_obj.find('input[type="hidden"]').length>0?room_obj.find('input[type="hidden"]').val():'';

			if(blank_room_val && valid_room_val){
				$(this).find("a").html(valid_room);
				$(this).find('input[type="hidden"]').val(valid_room_val);

				room_obj.remove();
				$(this).removeClass('link_cell_blank').removeClass('active').addClass('link_cell');
			}

			if ($('.missing_cell_list .missing_cell').length > 0) {
				$('#make_kanryo_btn').prop('disabled', true);
			} else {
				$('#make_kanryo_btn').prop('disabled', false);
			}			
		}
	}
});


</script>


</head>

<body>
__SHeaderKanri__

<div class="content-all"><!--content-all-->
<form action="s_make_kanryo.php" method="POST" name="form1"><!--form1-->
__HiddenValues__
<input type="hidden" name="backw" value="1">
<input type="hidden" name="rKey" value="__rKey__">
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
<input type="hidden" name="editBuildingCD" value="__editBuildingCD__">
<div class="left-yose">
<!--<a href="#" onclick="modorumove('s_make_kanryo.php');">＜戻る</a>-->
</div>
</form><!--/form1-->

<div class="top-menu left-yose">
__IfBuildingExist__
<div class="cur_building_dis building_nav">__wBuildingName__</div>
__IfBuildingExist__

<h6>詳細工程表編集</h6>

<br>
■不足している部屋<br>
__IfNotShortage__
	不足している部屋はありません。<br>
__IfNotShortage__
__IfShortage__
	<div class="missing_cell_list">　__ShortageRoom__</div>
__IfShortage__
<br>
<br>



■詳細工程表<br>
部屋番号をクリック、次に、「空き」または「枠越」をクリックしてください。

<br>__IfError__<font color=red >※すべての部屋を組み込むことができませんでした。</font>__IfError__<br>
__Koteihyou__
<form action="s_make_matrix.php" method="POST" name="mainform"><!--mainform-->
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__" >
<input type="hidden" name="editBuildingCD" value="__editBuildingCD__" >
<input type="hidden" name="rKey" value="__rKey__">

<input type="hidden" name="wHansu" value="__wHansu__">
<input type="hidden" name="wWakuPattern" value="__wWakuPattern__">
<input type="hidden" name="wFrameOverflow" value="__wFrameOverflow__">

<input type="hidden" name="wWakuAM" value="__wWakuAM__">
<input type="hidden" name="wWakuAM2" value="__wWakuAM2__">
<input type="hidden" name="wWakuAM3" value="__wWakuAM3__">
<input type="hidden" name="wWakuPM" value="__wWakuPM__">
<input type="hidden" name="wWakuPM1" value="__wWakuPM1__">
<input type="hidden" name="wWakuPM2" value="__wWakuPM2__">
__wHolidayHTML__
__wReserveDayHTML__
<input type="hidden" name="wFirstDateFeature" value="__wFirstDateFeature__">
<input type="hidden" name="wKojijun" value="__wKojijun__">

<input type="hidden" name="wWakuAMcol" value="__wWakuAMcol__">
<input type="hidden" name="wWakuAM2col" value="__wWakuAM2col__">
<input type="hidden" name="wWakuAM3col" value="__wWakuAM3col__">
<input type="hidden" name="wWakuPMcol" value="__wWakuPMcol__">
<input type="hidden" name="wWakuPM1col" value="__wWakuPM1col__">
<input type="hidden" name="wWakuPM2col" value="__wWakuPM2col__">
__wHansuEXHTML__
<input type="hidden" name="RowsLoop" value="__RowsLoop__">
<input type="hidden" name="wHoliday1" value="__wHoliday1__">
<input type="hidden" name="wShukujitucolor" value="__wShukujitucolor__">
<input type="hidden" name="wKyukobi" value="__wKyukobi__">
<input type="hidden" name="wKaiRoom3" value="__wKaiRoom3__">
<input type="hidden" name="wMaxWakuSu" value="__MaxWakuSu__">
<input type="hidden" name="rowCountforDay" value="__rowCountforDay__">
<input type="hidden" name="wArrangeType" value="__wArrangeType__">
<input type="hidden" name="FloorReserveInfo" value="__FloorReserveInfo__">
<input type="hidden" id="act" name="act" value="">

<div class="btn_wrap">
	<input type="button" id="make_kanryo_btn" onclick="makeKanryo('s_make_kotei_EXCEL.php' )" class="btn btn-success" value="工程表 作成" __SakuseiDisabled__>
	<input type="button" class="btn btn-primary" value="一時保存" style="margin-right:20px; margin-left:5px;" onclick="temporarilySave()">

	<input type="button" class="btn btn-success" onclick="submit_confirm('s_make_kotei_confirm2.php' )" value="もとの工程表案に戻る">
</div>

	<!-- <br><br>
	<font color="red" size="4"><b>
	※お願い※<br>
	作成済のエクセルの詳細工程表で、部屋の移動を行わないでください。<br>
	文字サイズの変更や、印刷範囲の修正は可能です。<br>
	</b></font>
	
	<button type="button" onclick="javascript:move('s_make_kojidate.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__' )" class="btn btn-success">部屋ごとの修正</button>
<br> -->
<br>


</form><!--mainform-->

　
__IfNespe__<hr><ネスぺ社員のみ表示><br>
<input type="submit" onclick="javascript:move('s_make_yotei_EXCEL.php?editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__' )" value="予定案内">
__IfNespe__




<hr>
<input type="button" value="メニューに戻る" onclick="go_confirm('../s_menu.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__' )"  class="btn btn-info"><br>

</div><!--content-all-->

<div class="modal fade" id="alert" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalTitle">通知</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">
				キャンセルされました。
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="confirmBtn" data-dismiss="modal">OK</button>
			</div>
		
		</div>
	</div>
</div>
<div class="modal-backdrop fade show"></div>

__SFooter__
__SCopyright__
	<script>
	__IfShowConfirmTempShow__
	if(confirm("一時保存された工程表案があります。\r\n保存済みの工程表案を表示しますか？"))
		location.href="s_make_kanryo_hensyu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&editBuildingCD=__editBuildingCD__&temp_show=1";
	__IfShowConfirmTempShow__
	</script>

</body>
</html>

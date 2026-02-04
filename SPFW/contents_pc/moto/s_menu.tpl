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
<!-- BootstrapのJS読み込み -->
<script src="./include/bootstrap/js/bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="./css/rnsien.css">
<!--<link rel="stylesheet" type="text/css" href="./css/tab.css">-->
<script type="text/javascript" src="tools.js"></script>

<!--
<link href="css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="js/jquery.ui.core.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
<script src="js/jquery.numberPicker.js" type="text/javascript"></script>-->

<style>
div.menu_box {
    margin: 0 10px 0 10px;
    text-align: left;
    display: inline-block;
    vertical-align: top;
    width: 250px;
}
div.menu_midashi {
    padding-left: 10px;
    text-align: left;
    margin-top: 18px;
    margin-bottom: 8px;
}
.menu_midashi {
    position: relative;
    background-color: #4db1df;
    color: #fff;
    overflow: hidden;
    padding: .3em;
    width: 250px;
}

ul.menu_list, ol {
  padding: 0;
  position: relative;
}

ul.menu_list li, ol li {
  color: #2d8fdd;
  border-left: solid 6px #2d8fdd;/*左側の線*/
  background: #f1f8ff;/*背景色*/
  margin-bottom: 3px;/*下のバーとの余白*/
  line-height: 1.5;
  padding: 0.5em;
  list-style-type: none!important;/*ポチ消す*/
}

ul.menu_list li.orange, ol li.orange {
  border-left: solid 6px #dd7b2d;/*左側の線*/
  background: #fff3f1;/*背景色*/
}
</style>

</head>

<body>
__SHeader__
<br>

<div class="left-yose" style="padding-left:20px">
	<a href="s_search.php__QUERY__" class="btn btn-info">トップへ</a>　物件CD：__editBukkenCD__　　<br><br>

	<table class="table-bordered">
		<tr><th>件名No</th><td>__BKN_NO__</td>
			<th>物件名称</th><td>__BKN_NM_ALL__</td></tr>
		<tr><th>主管担当部署</th><td>__BSYO_MEI__</td>
			<th>主管担当者</th><td>__SYIN_KNJ__</td></tr>
		<tr><th>住所</th><td>__JYUSYO_JKY__</td>
			<th>住戸数</th><td>__KO_SU__</td></tr>
		<tr><th>管理会社</th><td>__KanriGaisya__</td>
			<th>内定日</th><td>__NAITEI_YMD_D__</td>
			</tr>
	</table>
</div>

<!--
<div class="tab-content">
  <input type="radio" id="tab1" name="tab" checked >
  <label for="tab1">メニュー</label>
  <input type="radio" id="tab2" name="tab" >
  <label for="tab2">【現調】物件情報</label>
  <input type="radio" id="tabA" name="tab">
  <label for="tabA">★工事情報登録</label>
  <input type="radio" id="tab3" name="tab">
  <label for="tab3">①工事案内資料</label>
  <input type="radio" id="tab4" name="tab">
  <label for="tab4">機器設定指示書</label>
  <input type="radio" id="tab5" name="tab">
  <label for="tab5">②ネスぺ依頼</label>
  <input type="radio" id="tab6" name="tab">
  <label for="tab6">③作業工程表</label>
  <input type="radio" id="tab7" name="tab">
  <label for="tab7">④予定・決定案内</label>
  <input type="radio" id="tab8" name="tab">
  <label for="tab8">⑤残工事情報</label>
  <input type="radio" id="tab9" name="tab">
  <label for="tab9">⑥完成図書</label>
  <div class="tab-box">
    <div id="tabView1">
		<iframe src="s_menu2.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__ " name="nespe" width="100%" height="400" scrolling="auto" id="iframetab1">
			この部分はインラインフレームを使用しています。
		</iframe>
	</div>
    <div id="tabView2">
		<iframe src="s_form.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__ " name="nespe" width="100%" scrolling="auto" id="iframetab2">
			この部分はインラインフレームを使用しています。
		</iframe>
	</div>
    <div id="tabViewA">
		<iframe src="s_koji.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__" name="nespe" width="100%" scrolling="auto" id="iframetabA">
			この部分はインラインフレームを使用しています。
		</iframe>
	</div>
    <div id="tabView3">
		<iframe src="s_doc.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__" name="nespe" width="100%" scrolling="auto" id="iframetab3">
			この部分はインラインフレームを使用しています。
		</iframe>
	</div>

    <div id="tabView4">
		<iframe src="s_kikisetei.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__" name="nespe" width="100%" scrolling="auto" id="iframetab4">
			この部分はインラインフレームを使用しています。
		</iframe>
	</div>
    <div id="tabView5">
		<iframe src="s_489.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__" name="nespe" width="100%" scrolling="auto" id="iframetab5">
			この部分はインラインフレームを使用しています。
		</iframe>
	</div>
    <div id="tabView6">
		<iframe src="s_489kotei.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__" name="nespe" width="100%" scrolling="auto" id="iframetab6">
			この部分はインラインフレームを使用しています。
		</iframe>
	</div>
    <div id="tabView7">
		<iframe src="s_489download.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__" name="nespe" width="100%" height="400" scrolling="auto" id="iframetab7">
			この部分はインラインフレームを使用しています。
		</iframe>
	</div>
    <div id="tabView8">
		<iframe src="s_zan.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__" name="nespe" width="100%" height="400" scrolling="auto" id="iframetab8">
			この部分はインラインフレームを使用しています。
		</iframe>
	</div>
    <div id="tabView9">
		<iframe src="s_kan.php?editBukkenCD=__editBukkenCD__&rKey=__rKey__" name="nespe" width="100%" height="400" scrolling="auto" id="iframetab9">
			この部分はインラインフレームを使用しています。
		</iframe>
	</div>

  </div>
</div>
-->


<form action="#" method="POST" name="mainform">
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
<input type="hidden" name="rKey" value="__rKey__">

※2018/12/16 ☆は開発中
<div>

	<div class="menu_box">
		<div class="menu_midashi">①物件情報</div>
		<ul class="menu_list">
			<li class="orange"><a href="#" onclick="javascript:move('s_form.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">・物件基本情報</a></li>
			<li><a href="#" onclick="javascript:move('s_form_gencho.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">・現場調査</a></li>
			<li><a href="#" onclick="javascript:move('s_pic.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">・現場写真</a></li>

			<li><a href="#">☆・契約書作成</a></li>
			<li><a href="#">☆・簡易取説</a></li>
			<li><a href="#">☆・提案書</a></li>
		</ul>
	</div>

	<div class="menu_box">
		<div class="menu_midashi">②工事情報（案内関係）</div>
		<ul class="menu_list">
			<li class="orange"><a href="#" onclick="javascript:move('s_koji.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">・工事情報登録</a></li>
			<li><a href="#" onclick="javascript:move('s_doc.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">・工程表作成</a></li>
			<li><a href="#" onclick="javascript:move('./doc/s_annai.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">・工事案内作成</a></li>
			<li><a href="#" onclick="javascript:move('s_koji_annai.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">・各工事案内</a></li>
			<li><a href="#" onclick="javascript:move('s_koji_document.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&cat=annai_genba')">・現場掲示物</a></li>

		</ul>
	</div>

	<div class="menu_box">
		<div class="menu_midashi">②工事情報（施工関係）</div>
		<ul class="menu_list">
			<li><a href="#" >☆・作業指示</a></li>
			<li><a href="#" onclick="javascript:move('s_kikisetei.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">　L・作業指示書、機器設定表</a></li>
			<li><a href="#" >☆・工事完了確認書</a></li>
<!--			<li><a href="#" onclick="javascript:move('s_koji_document.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&cat=anzen')">・安全書類作成</a></li>-->
			<li><a href="#" onclick="javascript:move('s_anzensyorui.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&cat=anzen')">・安全書類作成</a></li>
			<li><a href="#" onclick="javascript:move('s_koji_document.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&cat=sagyonippo')">・作業日報、新規入場</a></li>
			<li><a href="#" >☆・施工計画書</a></li>
			<li><a href="#" onclick="javascript:move('s_koji_document.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&cat=syoubou')">・消防申請書作成</a></li>
			<li><a href="#" onclick="javascript:move('s_koji_document.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&cat=sanpai')">・産廃書類</a></li>
			<li><a href="#" onclick="javascript:move('s_koji_document.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&cat=genba')">・現場掲示物作成</a></li>
			<li><a href="#" onclick="javascript:move('s_koji_document.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__&cat=sonota')">・その他現場資料作成</a></li>
		</ul>
	</div>

	<div class="menu_box">
		<div class="menu_midashi">②工事情報（ネスペ依頼）</div>
		<ul class="menu_list">
			<li class="orange"><a href="#" onclick="javascript:move('s_489.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">・ネスペ依頼</a></li>
			<li><a href="#" onclick="javascript:move('s_489kotei.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">・作業工程表</a></li>
			<li><a href="#" onclick="javascript:move('s_489download.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">・予定・決定案内</a></li>
			<li><a href="#" onclick="javascript:move('./doc/s_hensin_Excel.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">・返信督促案内</a></li>
		</ul>
	</div>


	<div class="menu_box">
		<div class="menu_midashi">③現場納入</div>
		<ul class="menu_list">
			<li><a href="#" onclick="javascript:move('s_zan.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">・残工事情報</a></li>

			<li><a href="#" onclick="javascript:move('s_kansei_document.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')">・完成図書</a></li>
			<li><a href="#" >☆・オプション集計</a></li>
			<li><a href="#" >☆・残工事覚書</a></li>
			<li><a href="__filepath2__" >・工事完了報告書A</a></li>
			__IfNotSufficient__
			<li>・工事完了報告書B</li>
			__IfNotSufficient__
			__IfSufficient__
			<li><a href="#" onclick="javascript:move('./doc/s_make_kojikanryouB.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__')" >・工事完了報告書B</a></li>
			__IfSufficient__
			<li><a href="#" >☆・業務課へ提出すべき書類のチェックリスト</a></li>

		</ul>
	</div>

</div>

</form>



__SFooter__
__SCopyright__

<script>
$(document).ready(function () {
  hsize = $(window).height();
  hsize = hsize - 250;
  $("#tabView2 iframe").css("height", hsize + "px");
  $("#tabView3 iframe").css("height", hsize + "px");
  $("#tabViewA iframe").css("height", hsize + "px");
  $("#tabView4 iframe").css("height", hsize + "px");
  $("#tabView5 iframe").css("height", hsize + "px");
  $("#tabView6 iframe").css("height", hsize + "px");
});
$(window).resize(function () {
  hsize = $(window).height();
  hsize = hsize - 250;
  $("#tabView2 iframe").css("height", hsize + "px");
  $("#tabView3 iframe").css("height", hsize + "px");
  $("#tabViewA iframe").css("height", hsize + "px");
  $("#tabView4 iframe").css("height", hsize + "px");
  $("#tabView5 iframe").css("height", hsize + "px");
  $("#tabView6 iframe").css("height", hsize + "px");
});
</script>

</body>
</html>

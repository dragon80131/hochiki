<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>リニューアル支援</title>

<!-- BootstrapのCSS読み込み -->
<link href="../include/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- jQuery読み込み -->
<script src="../include/js/jquery-3.2.1.min.js"></script>

<!-- BootstrapのJS読み込み -->
<script src="../include/bootstrap/js/bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="../css/rnsien.css">
<script type="text/javascript" src="../tools.js"></script>

</head>

<body>
__SHeader__

<div class="content-all"><!--content-all-->

<div class="left-yose">
<a href="../s_menu.php?rKey=__rKey__&editBukkenCD=__editBukkenCD__">＜戻る</a>
</div>


<div class="top-menu left-yose">

<h6>工事案内資料</h6>


<form action="s_annai_Excel.php" method="POST" name="mainform" >
<input type="hidden" name="rKey" value="__rKey__" >
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__" >

資料の記載事項（工事基本情報の補足）


<!--
オートロック	あり	なし		
切替方法	停止	並行稼動		
火災抵抗器交換部屋立入り	なし	キッチン	全居室	
幹線構築方法	既設流用	新規幹線構築	部屋内渡り	
ガス漏れ検知器交換	あり	なし		など
-->
<table  border=1> 



<tr><td bgcolor="lemonchiffon" >物件名（物件基本より）</td><td bgcolor="#EEEEEE" >
__BukkenName__
</td></tr>
<tr><td bgcolor="lemonchiffon" >オートロック（現調より）</td><td bgcolor="#EEEEEE" >
__DispAutoLock__
</td></tr>
<tr><td bgcolor="lemonchiffon" >特例</td><td bgcolor="#EEEEEE" >
__ShoboTokureiDisp__
</td></tr>
<tr><td bgcolor="lemonchiffon" >オプション</td><td bgcolor="#EEEEEE" >
__OPDisp__
</td></tr>
<tr><td bgcolor="lemonchiffon" >署名</td><td bgcolor="#EEEEEE" >
__KojiShozokuName__<br>
担当：__KojiTantoName__<br>
電話：__TantoTEL__<br>
協力業者:__GyosyaName__<br>
</td></tr>
<tr><td bgcolor="lemonchiffon" >案内資料上の作業時間</td><td>
<input type="text" name="wConstTime"  style="width:50px" value="__wConstTime__" >分
</td></tr>
<tr><td bgcolor="lemonchiffon" >切替方法</td><td>
<input type="radio" name="wKirikaehoho" value="0"  __KirikaehohoChecked0__ >停止
<input type="radio" name="wKirikaehoho" value="1"  __KirikaehohoChecked1__ >並行稼働
</td></tr>
<tr><td bgcolor="lemonchiffon" >自火報交換</td><td>
<input type="radio" name="wJikaho" value="0"  __JikahoChecked0__ >なし
<input type="radio" name="wJikaho" value="1"  __JikahoChecked1__ >有り
</td></tr>


<tr><td bgcolor="lemonchiffon" >火災抵抗器交換部屋立入り</td><td>
<input type="radio" name="wKasaiHeya" value="0"  __KasaiHeyaChecked0__  >なし
<input type="radio" name="wKasaiHeya" value="1"  __KasaiHeyaChecked1__  >キッチン
<input type="radio" name="wKasaiHeya" value="2"  __KasaiHeyaChecked2__  >全居室
</td></tr>


<tr><td bgcolor="lemonchiffon" >幹線構築方法</td><td>
<input type="radio" name="wKansenKoji" value="0"  __KansenKojiChecked0__   >既設流用
<input type="radio" name="wKansenKoji" value="1"  __KansenKojiChecked1__   >新規幹線工事
<input type="radio" name="wKansenKoji" value="2"  __KansenKojiChecked2__   >部屋渡り
</td></tr>


<tr><td bgcolor="lemonchiffon" >ガス漏れ警報器交換</td><td>
<input type="radio" name="wGasKoji" value="0"  __GasKojiChecked0__   >なし
<input type="radio" name="wGasKoji" value="1"  __GasKojiChecked1__   >有り
</td></tr>



<tr><td bgcolor="lemonchiffon" >防犯センサー交換</td><td>
<input type="radio" name="wBohanKoji" value="0"  __BohanKojiChecked0__   >なし
<input type="radio" name="wBohanKoji" value="1"  __BohanKojiChecked1__   >１階住戸のみ
<input type="radio" name="wBohanKoji" value="2"  __BohanKojiChecked2__   >全住戸
</td></tr>





<tr><td bgcolor="lemonchiffon" >漏水センサー交換</td><td>
<input type="radio" name="wRosuiKoji" value="0"  __RosuiKojiChecked0__  >なし
<input type="radio" name="wRosuiKoji" value="1"  __RosuiKojiChecked1__   >有り
</td></tr>

<tr><td bgcolor="lemonchiffon" >ノンタッチタグ</td><td>
<input type="radio" name="wTagKoji" value="0"  __TagKojiChecked0__ >なし
<input type="radio" name="wTagKoji" value="1"  __TagKojiChecked1__ >有り
</td></tr>
<table>

<br><br>


<hr>

作成する案内状の種類を選択してください。
<br>
<input type="radio" name="wAnnaijyoType" value="0"  checked>通常版
<input type="radio" name="wAnnaijyoType" value="1"  >簡易版
<br><br>

<input type="submit" value=" 案内作成 " >

</form>

</div>






</div><!--content-all-->

<hr>

__SFooter__
__SCopyright__

</body>
</html>

<html>
<head>
<title>営業活動支援システム</title>


<link rel="stylesheet" href="css/pdefault.css" type="text/css" />
<link rel="stylesheet" href="css/print.css" type="text/css" media="print" />


</head>



<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">




<p class="sampleTxt02"><font color=red >★★縦向きに印刷してください。★★</font><br>

<a href="s_form.php__QUERY__&editBukkenCD=__editBukkenCD__">>>>物件詳細に戻る</a>


</p>

<p class="sampleTxt01">
__wBukkenName__ 物件情報 1
<!--ｈｔｍｌはりつけ-->



<!--ｈｔｍｌはりつけ-->
<table border=1>

<!--1行目-->
<tr>
<td colspan=2 bgcolor=lightgoldenrodyellow>物件名</td>
<td colspan=6 >
 <input type="text" name="wBukkenName" value="__wBukkenName__" size=40>
</td>
<td colspan=2 bgcolor=lightgoldenrodyellow>見積No</td>
<td colspan=2 >
 <input type="text" name="wMitumoriNo" value="__wMitumoriNo__" size=10>
</td>
<td colspan=2 bgcolor=lightgoldenrodyellow>訪問日</td>
<td colspan=2 >
 <input type="text" name="wChosaDate" id="wChosaDate" value="__wChosaDate__" size=10 >
</td>
<td colspan=2 bgcolor=lightgoldenrodyellow>現調者</td>
<td colspan=2 >
 <input type="text" name="wResearcher"  value="__wResearcher__" size=12 >
</td>
</tr>

<!--2行目-->
<tr>
<td colspan=2 bgcolor=lightgoldenrodyellow>住所</td>
<td colspan=10 ><input type="text" name="wAddress" value="__wAddress__" size=60></td>
<td colspan=4 >
 <input type="radio" name=wBunjyo value=1 __BunjyoChecked1__ >分譲
 <input type="radio" name=wBunjyo value=2 __BunjyoChecked2__ >賃貸
</td>
<td  colspan=4 >竣工(例:200103)
 <input type="text" name="wShunko" value="__wShunko__" size=10>
</td>
</tr>

<tr><td colspan=20 bgcolor="green"></td></tr>



<tr>
<td colspan=4 bgcolor=lightgoldenrodyellow><B>◆部屋構成</B></td>
<td colspan=16>
　棟数（<input type="text" name="wTosu" value="__wTosu__" size=4>)棟
　総戸数（<input type="text" name="wKosu" value="__wKosu__" size=4>)戸
　階高（<input type="text" name="wKaidaka" value="__wKaidaka__" size=4>)階
</td>
</tr>
<tr><td colspan=20 >部屋構成その他<br>
<textarea name="wHeyaBiko" cols=100 rows=5>__wHeyaBiko__</textarea>
</td>
</tr>

<tr><td colspan=20 bgcolor="green"></td></tr>



<tr>
<td colspan=12 rowspan=3 >
【管理会社情報・管理組合情報】<br>
管理会社名：<input type="text" name="wKanriGaisya" value="__wKanriGaisya__">
決算：


<select name="wSokai" >
<option value="" >--</option>
<option value="1" __SokaiSelected1__ >1月</option>
<option value="2" __SokaiSelected2__ >2月</option>
<option value="3" __SokaiSelected3__ >3月</option>
<option value="4" __SokaiSelected4__ >4月</option>
<option value="5" __SokaiSelected5__ >5月</option>
<option value="6" __SokaiSelected6__ >6月</option>
<option value="7" __SokaiSelected7__ >7月</option>
<option value="8" __SokaiSelected8__ >8月</option>
<option value="9" __SokaiSelected9__ >9月</option>
<option value="10" __SokaiSelected10__ >10月</option>
<option value="11" __SokaiSelected11__ >11月</option>
<option value="12" __SokaiSelected12__ >12月</option>
</select><br>

担当者名  ：<input type="text" name="wKanriGaisyaTanto" value="__wKanriGaisyaTanto__" size=6>
担当TEL   ：<input type="text" name="wKanriGaisyaTEL" value="__wKanriGaisyaTEL__" size=10><br>
管理員勤務：<input type="text" name="wKanriKinmu" value="__wKanriKinmu__">
管理員TEL ：<input type="text" name="wKanriTEL" value="__wKanriTEL__" size=10><br>

</td>

<td colspan=2 rowspan=2 bgcolor=lightgoldenrodyellow>要望</td>
<td colspan=6 rowspan=2 >
 <input type="checkbox" name=wYobo[] value=1 __YoboChecked1__ >現状同等
 <input type="checkbox" name=wYobo[] value=2 __YoboChecked2__ >カラーモニター<br>
 <input type="checkbox" name=wYobo[] value=3 __YoboChecked3__ >その他
</td>
</tr>

<!--4行目-->
<tr>
</tr>

<!--5行目-->
<tr>
<td colspan=4 bgcolor=lightgoldenrodyellow>提案予定システム</td>
<td colspan=4 >
 <input type="text" name="wNextSystem" value="__wNextSystem__">
</td>
</tr>

<tr><td colspan=20 bgcolor="green"></td></tr>



<!--6行目-->
<tr>
<td colspan=20 bgcolor=lightgoldenrodyellow><B>◆調査情報</B></td>
</tr>

<!--7行目-->
<tr>
<td colspan=8>【オートロック】　
 <input type="radio" name="wAutoLock" value=1 __AutoLockChecked1__ >有
 <input type="radio" name="wAutoLock" value=2 __AutoLockChecked2__ >無
</td>

<td colspan=4>
 <input type="checkbox" name="wDoorType[]" value=1 __DoorTypeChecked1__ >電気錠
 <input type="checkbox" name="wDoorType[]" value=2 __DoorTypeChecked2__ >オートドア
</td>
<td colspan=8 >【既設メーカー】
 <input type="radio" name="wMaker" value=1 __MakerChecked1__ >アイホン
 <input type="radio" name="wMaker" value=2 __MakerChecked2__ >松下電工<br>
 <input type="radio" name="wMaker" value=3 __MakerChecked3__ >その他
<input type="text" name="wMakerSonota" value="__wMakerSonota__">
</td>
</tr>

<!--8行目-->
<tr>
<td colspan=8 >【リニューアル状況】　
 <input type="radio" name=wRNstate value=1 __RNstateChecked1__ >済
 <input type="radio" name=wRNstate value=2 __RNstateChecked2__ >未
 <input type="radio" name=wRNstate value=0 __RNstateChecked0__ >不明

</td>
<td colspan=4 >RN実施
 <input type="text" name="wRNYear" value="__wRNYear__" size=7> 年
</td>
<td colspan=8 >【既設システム】
<select name="wMakerSystem">
	<option value="" >--</option>
__SystemLoop__
	<option value="__SystemCD__" __SystemSelected__>__SystemName__</option>
__SystemLoop__
</select>
</td>
</tr>

<!--9行目-->
<tr><td colspan=20 >物件情報メモ<br>
<textarea name="wBukkenMemo" cols=140 rows=4>__wBukkenMemo__</textarea>
</td></tr>



<tr><td colspan=20 bgcolor="green"></td></tr>

<td colspan=20 bgcolor=lightgoldenrodyellow><B>◆共用部</B></td>

<tr>
<td colspan=4 rowspan=5 bgcolor=lightgoldenrodyellow >【制御装置】</td>
<td colspan=16 >品番：<input type="text" name="wSeigyoKataban" value="__wSeigyoKataban__" ></td>
</td></tr>
<tr>
<td colspan=6 bgcolor=lightgoldenrodyellow>サイズ（縦×横）</td>
<td colspan=10 >
 <input type="text" name="wSeigyoSize[]" value="__wSeigyoSizeHeight__" size=8> ×
 <input type="text" name="wSeigyoSize[]" value="__wSeigyoSizeWidth__" size=8></td>
</tr>

<tr>
<td colspan=2 bgcolor=lightgoldenrodyellow>警備会社</td>
<td colspan=14 >
 <input type="radio" name="wSeigyoKeibi" value=1 __SeigyoKeibiChecked1__>有
 <input type="radio" name="wSeigyoKeibi" value=2 __SeigyoKeibiChecked2__>無
 会社名：
<select name="wSeigyoKeibiGaisya">
	<option value="" >--</option>
__KeibiLoop__
	<option value="__KeibiCD__" __KeibiSelected__>__KeibiName__</option>
__KeibiLoop__

</select>


 移報出力：
<select name="wSeigyoKeibiIho">
	<option value="" >--</option>
	<option value="1" __SeigyoKeibiIhoSelected1__>無</option>
	<option value="2" __SeigyoKeibiIhoSelected2__>RS-232C</option>
	<option value="3" __SeigyoKeibiIhoSelected3__>RS-422</option>
	<option value="4" __SeigyoKeibiIhoSelected4__>メーク出力</option>
	<option value="5" __SeigyoKeibiIhoSelected5__>その他</option>
</select>
</td>
</tr>

<tr>
<td colspan=2 bgcolor=lightgoldenrodyellow>宅配連動</td>
<td colspan=14 >
 <input type="radio" name="wSeigyoTakuhai" value=1 __SeigyoTakuhaiChecked1__>有
 <input type="radio" name="wSeigyoTakuhai" value=2 __SeigyoTakuhaiChecked2__>無
 会社名：
<select name="wSeigyoTakuhaiGaisya">
	<option value="" >--</option>
__TakuhaiLoop__
	<option value="__TakuhaiCD__" __TakuhaiSelected__>__TakuhaiName__</option>
__TakuhaiLoop__

</select>


 接続方式：
<select name="wSeigyoTakuhaiSetuzoku">
	<option value="" >--</option>
	<option value="1" __SeigyoTakuhaiSetuzokuSelected1__>2線式</option>
	<option value="2" __SeigyoTakuhaiSetuzokuSelected2__>4線式</option>
</select>
</td>
</tr>

<tr>
<td colspan=2 bgcolor=lightgoldenrodyellow>ELV連動</td>
<td colspan=14 >
 <input type="radio" name="wSeigyoEV" value=1 __SeigyoEVChecked1__>有
 <input type="radio" name="wSeigyoEV" value=2 __SeigyoEVChecked2__>無
 会社名：
 <input type="text" name="wSeigyoEVGaisya" value="__wSeigyoEVGaisya__">
</td>
</tr>

<tr></tr>
<tr></tr>



<tr><td colspan=20 bgcolor="grey"></td></tr>




<tr>
<td rowspan=6 colspan=4 bgcolor=lightgoldenrodyellow >【集合玄関機】<br>
 <input type="radio" name=wShuGenExist value=1 __ShuGenExistChecked1__ >有
 <input type="radio" name=wShuGenExist value=2 __ShuGenExistChecked2__ >無
</td>
<td colspan=6>品番：<input type="text" name="wShuGenKataban" value="__wShuGenKataban__" ></td>
<td colspan=10>台数：<input type="text" name="wShuGenDaisu" value="__wShuGenDaisu__" >台</td>
</td>
</tr>

<tr>
<td colspan=6 bgcolor=lightgoldenrodyellow>サイズ（縦×横 地上からの高さ）</td>
<td colspan=10 >
 <input type="text" name="wShuGenSize[]" value="__wShuGenSizeHeight__" size=8> ×
 <input type="text" name="wShuGenSize[]" value="__wShuGenSizeWidth__" size=8> ×
 <input type="text" name="wShuGenSize[]" value="__wShuGenSizeTall__" size=8></td> 
</tr>

<tr>
<td colspan=2 bgcolor=lightgoldenrodyellow>カメラ</td>
<td colspan=4 >
 <input type="radio" name=wShuGenCamera value=1 __ShuGenCameraChecked1__ >有
 <input type="radio" name=wShuGenCamera value=2 __ShuGenCameraChecked2__ >無
</td>

<td colspan=2 bgcolor=lightgoldenrodyellow>取付部分</td>
<td colspan=10 >
 <input type=checkbox name="wShuGenToritukeBubun[]" value=1 __ShuGenToritukeBubunChecked1__ >壁面躯体
 <input type=checkbox name="wShuGenToritukeBubun[]" value=2 __ShuGenToritukeBubunChecked2__ >取付台<br>
 <input type=checkbox name="wShuGenToritukeBubun[]" value=3 __ShuGenToritukeBubunChecked3__ >大理石
 <input type=checkbox name="wShuGenToritukeBubun[]" value=4 __ShuGenToritukeBubunChecked4__ >ボード
</td>
</tr>

<tr>
<td colspan=2 bgcolor=lightgoldenrodyellow>逆マスター</td>
<td colspan=4 >
 <input type="radio" name=wShuGenGyakuMaster value=1 __ShuGenGyakuMasterChecked1__ >有
 <input type="radio" name=wShuGenGyakuMaster value=2 __ShuGenGyakuMasterChecked2__ >無
</td>

<td colspan=2 bgcolor=lightgoldenrodyellow>取付方法</td>
<td colspan=10 >
 <input type="radio" name="wShuGenToritukeHoho" value=1 __ShuGenToritukeHohoChecked1__ >壁面水平
 <input type="radio" name="wShuGenToritukeHoho" value=2 __ShuGenToritukeHohoChecked2__ >傾斜有
</td>
</tr>

<tr>
<td colspan=2 bgcolor=lightgoldenrodyellow>色調</td>
<td colspan=14 >
 <input type="text" name="wShuGenColor" value="__wShuGenColor__" size=12>
</td>
</tr>

<tr>
<td colspan=2 bgcolor=lightgoldenrodyellow>材質</td>
<td colspan=14>
 <input type="text" name="wShuGenMaterial" value="__wShuGenMaterial__" size=12>
</td>


<tr><td colspan=20 bgcolor="grey"></td></tr>




<tr>
<td colspan=4 rowspan=3  bgcolor=lightgoldenrodyellow >【管理室親機】<br>
 <input type="radio" name=wKanOyaExist value=1 __KanOyaExistChecked1__ >有
 <input type="radio" name=wKanOyaExist value=2 __KanOyaExistChecked2__ >無
</td>
<td colspan=6 >品番：<input type="text" name="wKanOyaKataban" value="__wKanOyaKataban__" >
<td colspan=10 >台数：<input type="text" name="wKanOyaDaisu" value="__wKanOyaDaisu__" >台</td>
</td>
</tr>

<tr>
<td colspan=6 bgcolor=lightgoldenrodyellow>サイズ（縦×横）</td>
<td colspan=10 >
 <input type="text" name="wKanOyaSize[]" value="__wKanOyaSizeHeight__" size=8> ×
 <input type="text" name="wKanOyaSize[]" value="__wKanOyaSizeWidth__" size=8></td>
</tr>

<tr>
<td colspan=2 bgcolor=lightgoldenrodyellow>取付方法</td>
<td colspan=14 >
 <input type="radio" name="wKanOyaTorituke" value=1 __KanOyaToritukeChecked1__ >壁掛
 <input type="radio" name="wKanOyaTorituke" value=2 __KanOyaToritukeChecked2__ >卓上
</td>
</tr>




<tr><td colspan=20 bgcolor="grey"></td></tr>



<tr>
<td colspan=4  bgcolor=lightgoldenrodyellow>【住戸アダプタ】</td>

<td colspan=16 >
 <input type="radio" name="wJyukoAdapter" value=1 __JyukoAdapterChecked1__ >露出
 <input type="radio" name="wJyukoAdapter" value=2 __JyukoAdapterChecked2__ >カセット
 <input type="radio" name="wJyukoAdapter" value=3 __JyukoAdapterChecked3__ >サイコロ型
 <input type="radio" name="wJyukoAdapter" value=4 __JyukoAdapterChecked4__ >無し
</td>
</tr>




<!--ここまではOK-->


<tr><td colspan=20 bgcolor="grey"></td></tr>

<tr>
<td colspan=20>共用部その他<br>
<textarea name="wKyoyoSonota" cols=140 rows=5>__wKyoyoSonota__</textarea>
</td>
</tr>

<tr><td colspan=20 bgcolor="green"></td></tr>



<tr>
<td colspan=20 bgcolor=lightgoldenrodyellow><B>◆専有部</B></td>


<tr>
<td colspan=4 rowspan=9 bgcolor=lightgoldenrodyellow >【居室親機】</td>
<td colspan=2 bgcolor=lightgoldenrodyellow>品番</td>
<td colspan=14><input type="text" name="wOyaKataban" value="__wOyaKataban__">
</td>
</tr>

<tr>
<td colspan=6 bgcolor=lightgoldenrodyellow>サイズ（縦×横×深さ）</td>
<td colspan=10 >
 <input type="text" name="wOyaSize[]" value="__wOyaSizeHeight__" size=8> ×
 <input type="text" name="wOyaSize[]" value="__wOyaSizeWidth__" size=8> ×
 <input type="text" name="wOyaSize[]" value="__wOyaSizeDepth__" size=8></td>
</td></tr>

<tr>
<td colspan=2 bgcolor=lightgoldenrodyellow>調査居室</td>
<td colspan=4><input type="text" name="wOyaID" value="__wOyaID__" size=3 >号室
</td>

<td colspan=3 bgcolor=lightgoldenrodyellow>取付タイプ</td>
<td colspan=7 >
 <input type="radio" name="wOyaTorituke" value=1 __OyaToritukeChecked1__ >露出
 <input type="radio" name="wOyaTorituke" value=2 __OyaToritukeChecked2__ >埋込
</td>
</tr>

<tr>
<td colspan=6>
<select name="wOyaSW">
	<option value="" >--</option>
	<option value="1" __OyaSWSelected1__>1</option>
	<option value="2" __OyaSWSelected2__>2</option>
	<option value="3" __OyaSWSelected3__>3</option>
	<option value="4" __OyaSWSelected4__>4</option>
	<option value="5" __OyaSWSelected5__>5</option>
</select>
ヶ用スイッチボックス
</td>
<td colspan=10>
<select name="wOyaUnit">
	<option value="" >--</option>
	<option value="1" __OyaUnitSelected1__>1</option>
	<option value="2" __OyaUnitSelected2__>2</option>
	<option value="3" __OyaUnitSelected3__>3</option>
	<option value="4" __OyaUnitSelected4__>4</option>
	<option value="5" __OyaUnitSelected5__>5</option>
	<option value="6" __OyaUnitSelected6__>6</option>
	<option value="7" __OyaUnitSelected7__>7</option>
	<option value="8" __OyaUnitSelected8__>8</option>
</select>
ユニット
</td>
</tr>



<tr>
<td colspan=2 bgcolor=lightgoldenrodyellow>付属機器</td>
<td colspan=14 >
 <input type="checkbox" name="wOyaFuzoku[]" value=1 __OyaFuzokuChecked1__ >セキュリティユニット
 <input type="checkbox" name="wOyaFuzoku[]" value=2 __OyaFuzokuChecked2__ >モニター 
 <input type="checkbox" name="wOyaFuzoku[]" value=3 __OyaFuzokuChecked3__ >テレコン
 <input type="checkbox" name="wOyaFuzoku[]" value=4 __OyaFuzokuChecked4__ >有線
</td>
</tr>

<tr>
<td colspan=2 bgcolor=lightgoldenrodyellow>管理室呼出</td>
<td colspan=4>
 <input type="radio" name=wOyaKanri value=1 __OyaKanriChecked1__ >有
 <input type="radio" name=wOyaKanri value=2 __OyaKanriChecked2__ >無
</td>

<td colspan=3 bgcolor=lightgoldenrodyellow>増設親機</td>
<td colspan=7>
 <input type="radio" name=wOyaZosetu value=1 __OyaZosetuChecked1__ >有
 <input type="radio" name=wOyaZosetu value=2 __OyaZosetuChecked2__ >無
</td>
</tr>

<tr>
<td colspan=2 bgcolor=lightgoldenrodyellow>化粧パネル</td>
<td colspan=14>
 <input type="radio" name=wOyaPanel value=1 __OyaPanelChecked1__ >要
 <input type="radio" name=wOyaPanel value=2 __OyaPanelChecked2__ >不要
</td>
</tr>

<tr>
<td colspan=2 bgcolor=lightgoldenrodyellow>警報表示</td>
<td colspan=14 >
 <input type="checkbox" name="wOyaKeiho[]" value=1 __OyaKeihoChecked1__ >非常
 <input type="checkbox" name="wOyaKeiho[]" value=2 __OyaKeihoChecked2__  >ガス
 <input type="checkbox" name="wOyaKeiho[]" value=3 __OyaKeihoChecked3__  >火災
 <input type="checkbox" name="wOyaKeiho[]" value=4 __OyaKeihoChecked4__  >障害 
 <input type="checkbox" name="wOyaKeiho[]" value=5 __OyaKeihoChecked5__  >防犯（窓/玄関）
 <input type="checkbox" name="wOyaKeiho[]" value=6 __OyaKeihoChecked6__  >水漏れ
 <input type="checkbox" name="wOyaKeiho[]" value=7 __OyaKeihoChecked7__  >コール<br>
 <input type="checkbox" name="wOyaKeiho[]" value=8 __OyaKeihoChecked8__ >その他
 <input type="text" name="wOyaKeihoSonota" value="__wOyaKeihoSonota__">
</td>
</tr>

<tr>
<td colspan=2 bgcolor=lightgoldenrodyellow>火報メーカー</td>
<td colspan=14 >
 <input type="radio" name="wOyaKahoMaker" value=1 __OyaKahoMakerChecked1__ >能美
 <input type="radio" name="wOyaKahoMaker" value=2 __OyaKahoMakerChecked2__ >ホーチキ
 <input type="radio" name="wOyaKahoMaker" value=3 __OyaKahoMakerChecked3__ >ニッタン
 <input type="radio" name="wOyaKahoMaker" value=4 __OyaKahoMakerChecked4__ >松下<br>
 <input type="radio" name="wOyaKahoMaker" value=5 __OyaKahoMakerChecked5__ >その他
 <input type="text" name="wOyaKahoMakerSonota" value="__wOyaKahoMakerSonota__">
</td>
</tr>





<tr><td colspan=20 bgcolor="grey"></td></tr>



<tr>
<td colspan=4 rowspan=4  bgcolor=lightgoldenrodyellow  >【玄関子機】</td>
<td colspan=2 bgcolor=lightgoldenrodyellow>品番</td>
<td colspan=14><input type="text" name="wKokiKataban" value="__wKokiKataban__" ></td>
</td>
</tr>

<tr>
<td colspan=6 bgcolor=lightgoldenrodyellow>サイズ（縦×横×地上からの高さ×ボックスの深さ）</td>
<td colspan=10 >
 <input type="text" name="wKokiSize[]" value="__wKokiSizeHeight__" size=8> ×
 <input type="text" name="wKokiSize[]" value="__wKokiSizeWidth__" size=8> ×
 <input type="text" name="wKokiSize[]" value="__wKokiSizeTall__" size=8> ×
 <input type="text" name="wKokiSize[]" value="__wKokiSizeDepth__" size=8></td>
</tr>

<tr>
<td colspan=2 bgcolor=lightgoldenrodyellow>取付タイプ</td>
<td colspan=14 >
 <input type="radio" name="wKokiType" value=1 __KokiTypeChecked1__>露出
 <input type="radio" name="wKokiType" value=2 __KokiTypeChecked2__>埋込<br>
<select name="wKokiSW">
	<option value="" >--</option>
	<option value="1" __KokiSWSelected1__>1</option>
	<option value="2" __KokiSWSelected2__>2</option>
	<option value="3" __KokiSWSelected3__>3</option>
</select>
ヶ用スイッチボックス

</td>
</tr>

<tr>
<td colspan=2 bgcolor=lightgoldenrodyellow>化粧パネル</td>
<td colspan=4 >
 <input type="radio" name="wKokiPanel"  value=1 __KokiPanelChecked1__ >要
 <input type="radio" name="wKokiPanel"  value=2 __KokiPanelChecked2__ >不要
<td colspan=3 bgcolor=lightgoldenrodyellow>色調</td>
<td colspan=7 ><input type="text" name="wKokiColor" value="__wKokiColor__" size=10 ></td>
</tr>

<tr><td colspan=20 bgcolor="grey"></td></tr>

<tr>
<td colspan=20>専有部その他<br>
<textarea name="wSenyuSonota" cols=140 rows=5>__wSenyuSonota__</textarea>
</td>
</tr>

<tr><td colspan=20 bgcolor="green"></td></tr>

<!--ページ区切り--->
</table>
<p class="pagebreak">__wBukkenName__ 物件情報 2</p>
<table border=1>

<!--29行目-->
<tr>
<td colspan=4 bgcolor=lightgoldenrodyellow >◆系統</td>
<td colspan=6>
 <input type="checkbox" name=wKeito[] value=1 __KeitoChecked1__ >PS渡り
 <input type="checkbox" name=wKeito[] value=2 __KeitoChecked2__ >玄関子機渡り
 <input type="checkbox" name=wKeito[] value=3 __KeitoChecked3__ >部屋内渡り
</td>

<td colspan=10>
<select name="wKeitoSu">
	<option value="" >--</option>
	<option value="1" __KeitoSuSelected1__ >1</option>
	<option value="2" __KeitoSuSelected2__ >2</option>
	<option value="3" __KeitoSuSelected3__ >3</option>
	<option value="4" __KeitoSuSelected4__ >4</option>
	<option value="5" __KeitoSuSelected5__ >5</option>
	<option value="6" __KeitoSuSelected6__ >6</option>
</select>
系統
</td>
</tr>

<tr>
<td colspan=20 >
--系統写真--
<a href=./upfile/imgup.php?SekoStatus=2&Device=6&Eda=__KeitoNo__&rKey=__rKey__&BukkenCD=__editBukkenCD__>>>>写真追加</a><br>
<table><tr>__PicKeito1__ __PicKeito2__ __PicKeito3__ __PicKeito4__</tr></table>
</td>
</tr>

<!--30行目-->


<tr><td colspan=20 bgcolor="grey"></td></tr>

<td colspan=4 bgcolor=lightgoldenrodyellow><B>◆ミルキー項目</B></td>
<td colspan=16>ミルキー更新日：<input type="text" name="wMilkyDate" value="__wMilkyDate__" size=8></td>
<tr>
<td colspan=20 valign="top">ミルキー項目<br>
<textarea name="wMilkyBiko" cols=140 rows=5>__wMilkyBiko__</textarea>
</td>
</tr>

<tr><td colspan=20 bgcolor="grey"></td></tr>

<tr>
<td colspan=20 valign="top">備考<br>
<textarea name="wBikoYobo" cols=140 rows=5>__wBikoYobo__</textarea>
</td>
</tr>



</table>
<!--ｈｔｍｌコピーおわり-->






</p><!--A4印刷　End-->

<br>










</body>

</html>

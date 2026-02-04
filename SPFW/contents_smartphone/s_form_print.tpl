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
__wBukkenName__ 現場調査シート1
<table border=1>

<tr>
<td colspan=2 >マンション名※必須</td><td colspan=14 >__wBukkenName__</td>
<td colspan=2 >訪問日</td>
<td  colspan=3 >__wChosaDate__</td>
<td colspan=2 >現場確認者</td>
<td  colspan=3 >
__wB004__<!--現場確認者-->
</td>

</tr>


<!--2行目-->
<tr>
<td colspan=2>住所</td><td colspan=14 >__wAddress__ </td>
<td colspan=5 >
<input type="radio" name=wBunjyo value=1 __BunjyoChecked1__ >分譲
<input type="radio" name=wBunjyo value=2 __BunjyoChecked2__ >賃貸  </td>
<td  colspan=5 >
竣工:__wShunko__</td>
</tr>


<!--3行目-->
<tr>
<td colspan=16 rowspan=3 >


管理情報・理事会情報<br>
管理会社名：__wKanriGaisya__
　担当名：__wKanriGaisyaTanto__
　担当TEL：__wKanriGaisyaTEL__

管理員勤務：__wKanriKeitai__
　管理員TEL：__wKanriTEL__
　総会：__wSokai__月<br>
__wKanriSonota__


</td>
<td colspan=2 rowspan=2 >
要望</td>
<td  colspan=8 rowspan=2 >
<input type="checkbox" name=wYobo[] value=1 __YoboChecked1__ >現状同等
<input type="checkbox" name=wYobo[] value=2 __YoboChecked2__ >カラーモニター<br>
<input type="checkbox" name=wYobo[] value=3 __YoboChecked3__ >その他
__wYoboSonota__
</td>


</tr>


<!--4行目-->
<tr>
</tr>

<!--5行目-->
<tr>
<td colspan=4>提案予定システム</td>
<td  colspan=6 >
__wNextSystem__</td>
</tr>


<!--6行目-->
<tr>
<td colspan=26><B>◆調査情報</B></td>
</tr>


<!--7行目-->
<tr>
<td colspan=7>【オートロック】　
<input type="radio" name=wAutoLock value=1 __AutoLockChecked1__ >有
<input type="radio" name=wAutoLock value=2 __AutoLockChecked2__ >無

</td>


<td colspan=6>
 <input type="radio" name="wDoorType" value=1 __DoorTypeChecked1__ >電気錠
 <input type="radio" name="wDoorType" value=2 __DoorTypeChecked2__ >オートドア
</td>
<td  colspan=13 >【既設メーカー】
 <input type="radio" name="wMaker" value=1 __MakerChecked1__ >アイホン
 <input type="radio" name="wMaker" value=2 __MakerChecked2__ >松下電工
 <input type="radio" name="wMaker" value=3 __MakerChecked3__ >その他
__wMakerSonota__
</td>
</tr>




<!--追加行目-->
<tr>
<td colspan=7>【リニューアル状況】　
<input type="radio" name=wB001 value=1 __B001Checked1__ >有
<input type="radio" name=wB001 value=2 __B001Checked2__ >無

</td>


<td colspan=5>
リニューアル実施：__wB003__　年
</td><td colspan=5>
見積No:__wB005__
</td>
<td  colspan=9 >【竣工時メーカー】
 <input type="radio" name="wB002" value=1 __B002Checked1__ >アイホン
 <input type="radio" name="wB002" value=2 __B002Checked2__ >松下電工
 <input type="radio" name="wB002" value=3 __B002Checked3__ >その他
</td>
</tr>





<tr><td colspan=26 bgcolor="green"></td></tr>



<td colspan=26><B>◆共用部</B></td>

<tr>
<td colspan=4 rowspan=2>【制御装置】</td>
<td colspan=12 >通話品番:__wSeigyoKatabanTuwa__</td>
<td colspan=10 rowspan=8 bgcolor=lightgoldenrodyellow>
<table><tr>__PicSeigyo1__ __PicSeigyo2__</tr></table>

</td>
</tr>

<tr>
<td colspan=12 >映像品番：__wSeigyoKatabanEizo__</td>
</tr>

<tr>
<td colspan=4 rowspan=6 ><!--ドローエリア1-->__SumPic1__</td>
<td colspan=2 rowspan=6 >移報出力</td>
<td colspan=3 rowspan=6 >
 <input type="radio" name="wIhoIF" value=1 __IhoIFChecked1__ >無<br>
 <input type="radio" name="wIhoIF" value=2 __IhoIFChecked2__ >RS-232C<br>
 <input type="radio" name="wIhoIF" value=3 __IhoIFChecked3__ >RS-422<br>
 <input type="radio" name="wIhoIF" value=4 __IhoIFChecked4__ >メーク出力<br>
 <input type="radio" name="wIhoIF" value=5 __IhoIFChecked5__ >
 __wIhoSonota__
</td>
<td colspan=2 >警備会社</td>
<td colspan=5 >__wIhoshuturyokusaki__</td>
</tr>

<tr>
<td colspan=2 >宅配連動</td>
<td colspan=5 >__wSeigyoTakuhai__</td>
</tr>

<tr>
<td colspan=2 >ELV連動</td>
<td colspan=5 >__wSeigyoEV__</td>
</tr>

<tr>
<td colspan=7 rowspan=3 width=45 >その他<br>
__wSeigyoSonota__ </td>
</tr>

<tr></tr>
<tr></tr>


<tr><td colspan=26 bgcolor="grey"></td></tr>



<tr>
<td colspan=4 bgcolor="__shuexistcolor__">【集合玄関機】
<input type="radio" name=wShugenExist value=1 __ShugenExistChecked1__ >有
<input type="radio" name=wShugenExist value=2 __ShugenExistChecked2__ >無
</td>
<td colspan=12 bgcolor="__shuexistcolor__">
 品番：__wShuGenKataban__</td>
<td colspan=10 rowspan=7 bgcolor=lightgoldenrodyellow>
<table><tr>__PicShuGen1__ __PicShuGen2__</tr></table>
</td>
</tr>

<tr>
<td colspan=4 rowspan=6 bgcolor="__shuexistcolor__"><!--ドローエリア2-->__SumPic2__</td>
<td colspan=2 bgcolor="__shuexistcolor__">カメラ</td>
<td colspan=3 bgcolor="__shuexistcolor__">
<input type="radio" name=wShuGenCamera value=1 __ShuGenCameraChecked1__ >有
<input type="radio" name=wShuGenCamera value=2 __ShuGenCameraChecked2__ >無
</td>
<td colspan=2 rowspan=2 bgcolor="__shuexistcolor__">取付部分</td>
<td colspan=5 rowspan=2 bgcolor="__shuexistcolor__">
 <input type=checkbox name="wShuGenToritukeBubun[]" value=1 __ShuGenToritukeBubunChecked1__  >壁面躯体
 <input type=checkbox name="wShuGenToritukeBubun[]" value=2 __ShuGenToritukeBubunChecked2__  >取付台<br>
 <input type=checkbox name="wShuGenToritukeBubun[]" value=3 __ShuGenToritukeBubunChecked3__  >大理石
 <input type=checkbox name="wShuGenToritukeBubun[]" value=4 __ShuGenToritukeBubunChecked4__  >ボード
</td>
</tr>

<tr>
<td colspan=2 rowspan=3 bgcolor="__shuexistcolor__">逆マスター</td>
<td colspan=3 rowspan=3 bgcolor="__shuexistcolor__">
 <input type="radio" name="wGyakuMaster" value=1 __GyakuMasterChecked1__ >有
 <input type="radio" name="wGyakuMaster" value=2 __GyakuMasterChecked2__  >無<br>
 <input type="radio" name="wGyakuMaster" value=3 __GyakuMasterChecked3__ >その他<br>
__wGyakuMasterSonota__
</td>
</tr>

<tr>
<td colspan=2 bgcolor="__shuexistcolor__">取付方法</td>
<td colspan=5 bgcolor="__shuexistcolor__">
<input type="radio" name="wShuGenToritukeHoho" value=1 __ShuGenToritukeHohoChecked1__ >壁面水平
<input type="radio" name="wShuGenToritukeHoho" value=2 __ShuGenToritukeHohoChecked2__ >傾斜有
</td>
</tr>

<tr>
<td colspan=2 bgcolor="__shuexistcolor__">化粧パネル</td>
<td colspan=5 bgcolor="__shuexistcolor__">
 <input type="radio" name="wShuGenPanel" value=1 __ShuGenPanelChecked1__ >要
 <input type="radio" name="wShuGenPanel" value=2 __ShuGenPanelChecked2__ >不要
</td>
</tr>

<tr>
<td colspan=2 bgcolor="__shuexistcolor__">色調</td>
<td colspan=3 bgcolor="__shuexistcolor__">
 <input type="text" name="wShuGenColor" value="__wShuGenColor__" size=12 style="background-color:__shuexistcolor__"></td>
<td colspan=7 rowspan=2 bgcolor="__shuexistcolor__">
__wShuGenSonota__</td>
</tr>

<tr>
<td colspan=2 bgcolor="__shuexistcolor__">材質</td>
<td colspan=3 bgcolor="__shuexistcolor__">
 <input type="text" name="wShuGenMaterial" value="__wShuGenMaterial__" size=12 style="background-color:__shuexistcolor__">__wShugenMaterial__</td>
</tr>


<tr><td colspan=26 bgcolor="grey"></td></tr>





<tr>
<td colspan=4 bgcolor="__kanoyaexistcolor__">【管理室親機】
<input type="radio" name=wKanOyaExist value=1 __KanOyaExistChecked1__ >有
<input type="radio" name=wKanOyaExist value=2 __KanOyaExistChecked2__ >無
</td>
<td colspan=6 bgcolor="__kanoyaexistcolor__">品番：__wKanOyaKataban__</td>
<td colspan=16 rowspan=7 bgcolor=lightgoldenrodyellow>

<table><tr>__PicKanOya1__ __PicKanOya2__</tr></table>

</td>
</tr>

<tr>
<td colspan=4 rowspan=6 bgcolor="__kanoyaexistcolor__"><!--ドローエリア3-->__SumPic3__</td>
<td colspan=2 bgcolor="__kanoyaexistcolor__">警報表示</td>
<td colspan=4 bgcolor="__kanoyaexistcolor__">
 <input type="radio" name=wKanOyaDisp value=1 __KanOyaDispChecked1__ >有
 <input type="radio" name=wKanOyaDisp value=2 __KanOyaDispChecked2__ >無
</td>
</tr>

<tr>
<td colspan=2 bgcolor="__kanoyaexistcolor__">通話機能</td>
<td colspan=4 bgcolor="__kanoyaexistcolor__">
 <input type="radio" name=wKanOyaTuwa value=1 __KanOyaTuwaChecked1__ >有
 <input type="radio" name=wKanOyaTuwa value=2 __KanOyaTuwaChecked2__ >無
</td>
</tr>

<tr>
<td colspan=2 rowspan=2 bgcolor="__kanoyaexistcolor__">取付方法</td>
<td colspan=4 rowspan=2 bgcolor="__kanoyaexistcolor__">
 <input type="radio" name="wKanOyaTorituke" value=1 __KanOyaToritukeChecked1__ >壁掛
 <input type="radio" name="wKanOyaTorituke" value=2 __KanOyaToritukeChecked2__ >卓上<br>
 <input type="radio" name="wKanOyaTorituke" value=3 __KanOyaToritukeChecked3__ >その他
__wKanOyaToritukeSonota__
</td>
</tr>

<tr></tr>

<tr>
<td colspan=6 rowspan=2 bgcolor="__kanoyaexistcolor__">
__wKanOyaSonota__
</td>
</tr>

<tr></tr>



<tr><td colspan=26 bgcolor="green"></td></tr>
<td colspan=26><B>◆専有部</B></td>


<tr>
<td colspan=4 >【居室親機】</td>
<td colspan=22>
 調査居室：__wOyaID__ 号室　　
 品番：__wOyaKataban__
</td>
</tr>

<tr>
<td colspan=4 rowspan=6 ><!--ドローエリア4-->__SumPic4__</td>
<td colspan=2 rowspan=3 >取付タイプ</td>
<td colspan=2 rowspan=3 >
 <input type="radio" name="wOyaTorituke" value=1 __OyaToritukeChecked1__ >露出<br>
 <input type="radio" name="wOyaTorituke" value=2 __OyaToritukeChecked2__ >埋込<br>
 <input type="radio" name="wOyaTorituke" value=3 __OyaToritukeChecked3__ >その他
</td>
<td colspan=4 rowspan=3 >
 <input type="text" name="wOyaSW" value="__wOyaSW__" size=1>ヶ用スイッチボックス<br>
 <input type="text" name="wOyaUnit" value="__wOyaUnit__"  size=1>ユニット<br>
__wOyaToritukeSonota__
</td>

<td colspan=2 rowspan=3 >付属機器</td>
<td colspan=6 rowspan=3 >
 <input type="checkbox" name="wOyaFuzoku[]" value=1 __OyaFuzokuChecked1__ >セキュリティユニット<br>
 <input type="checkbox" name="wOyaFuzoku[]" value=2 __OyaFuzokuChecked2__  >モニター
 <input type="checkbox" name="wOyaFuzoku[]" value=3 __OyaFuzokuChecked3__  >テレコン<br>
 <input type="checkbox" name="wOyaFuzoku[]" value=4 __OyaFuzokuChecked4__  >有線<br>
 <input type="checkbox" name="wOyaFuzoku[]" value=5 __OyaFuzokuChecked5__  >その他
__wOyaFuzokuSonota__
</td>

<td colspan=2 >管理室呼出</td>
<td colspan=4>
<input type="radio" name="wOyaKanri" value=1 __OyaKanriChecked1__ >有
<input type="radio" name="wOyaKanri" value=2 __OyaKanriChecked2__ >無
</tr>

<tr>
<td colspan=2 >増設親機</td>
<td colspan=4>
<input type="radio" name="wOyaZosetu" value=1 __OyaZosetuChecked1__ >有
<input type="radio" name="wOyaZosetu" value=2 __OyaZosetuChecked2__ >無
</tr>

<tr>
<td colspan=2 rowspan=3>火報メーカー</td>
<td colspan=4 rowspan=3>
 <input type="radio" name="wOyaKahoMaker" value=1 __OyaKahoMakerChecked1__ >能美
 <input type="radio" name="wOyaKahoMaker" value=2 __OyaKahoMakerChecked2__  >ホーチキ<br>
 <input type="radio" name="wOyaKahoMaker" value=3 __OyaKahoMakerChecked3__  >ニッタン
 <input type="radio" name="wOyaKahoMaker" value=4 __OyaKahoMakerChecked4__  >松下<br>
 <input type="radio" name="wOyaKahoMaker" value=5 __OyaKahoMakerChecked5__  >その他
__wOyaKahoMakerSonota__
</td>
</tr>

<tr>
<td colspan=2 rowspan=2>電源</td>
<td colspan=6 rowspan=2 >
 <input type="radio" name="wOyaDengen" value=1 __OyaDengenChecked1__ >AC100V<br>
 <input type="radio" name="wOyaDengen" value=2 __OyaDengenChecked2__  >その他
__wOyaDengenSonota__
 </td>

<td colspan=2 rowspan=3 >警報表示</td>
<td colspan=6 rowspan=3 >
 <input type="checkbox" name="wOyaKeiho[]" value=1 __OyaKeihoChecked1__ >非常
 <input type="checkbox" name="wOyaKeiho[]" value=2 __OyaKeihoChecked2__  >ガス
 <input type="checkbox" name="wOyaKeiho[]" value=3 __OyaKeihoChecked3__  >火災<br>
 <input type="checkbox" name="wOyaKeiho[]" value=4 __OyaKeihoChecked4__  >障害
 <input type="checkbox" name="wOyaKeiho[]" value=5 __OyaKeihoChecked5__  >防犯（窓/玄関）<br>
 <input type="checkbox" name="wOyaKeiho[]" value=6 __OyaKeihoChecked6__  >水漏れ
 <input type="checkbox" name="wOyaKeiho[]" value=7 __OyaKeihoChecked7__  >コール<br>
 <input type="checkbox" name="wOyaKeiho[]" value=8 __OyaKeihoChecked8__  >その他
__wOyaKeihoSonota__
</td>
</tr>

<tr>
</tr>

<tr>
<td colspan=2>化粧パネル</td>
<td colspan=6>
<input type="radio" name=wOyaPanel value=1 __OyaPanelChecked1__ >要
<input type="radio" name=wOyaPanel value=2 __OyaPanelChecked2__ >不要
</td>

<td colspan=6 > 
その他
__wOyaSonota__"
</td>
</tr>

<tr>
<td colspan=26 bgcolor=lightgoldenrodyellow>
<table><tr>__PicOya1__ __PicOya2__ __PicOya3__ __PicOya4__</tr></table>
<br><br><br></td>
</tr>

<tr><td colspan=26 bgcolor="grey"></td></tr>

<!--ページ区切り--->
</table>
<p class="pagebreak">__wBukkenName__ 現場調査シート２</p>
<table border=1>

<tr>
<td colspan=4 >【玄関子機】</td>
<td colspan=6 >品番：__wKokiKataban__</td>
<td colspan=16 rowspan=7 bgcolor=lightgoldenrodyellow>
<table><tr>__PicKoki1__ __PicKoki2__</tr></table>
</td>
</tr>

<tr>
<td colspan=4 rowspan=6 ><!--ドローエリア5-->__SumPic5__</td>
<td colspan=2 rowspan=2 >取付タイプ</td>
<td colspan=4 rowspan=2 >
 <input type="radio" name="wKokiType" value=1 __KokiTypeChecked1__>露出
 <input type="radio" name="wKokiType" value=2 __KokiTypeChecked2__>埋込<br>
 __wKokiSW__ヶ用スイッチボックス
</td>
</tr>

<tr></tr>

<tr>
<td colspan=2 >化粧パネル</td>
<td colspan=4 >
 <input type="radio" name="wKokiPanel"  value=1 __KokiPanelChecked1__ >要
 <input type="radio" name="wKokiPanel"  value=2 __KokiPanelChecked2__ >不要
</td>
</tr>

<tr>
<td colspan=2 >色調</td>
<td colspan=4 >__wKokiColor__</td>
</tr>

<tr>
<td colspan=2 rowspan=2 >使用予定<br>映像アダプタ</td>

<td colspan=4 rowspan=2>
 <input type="radio" name="wKokiAdapter" value=1 __KokiAdapterChecked1__ >露出
 <input type="radio" name="wKokiAdapter" value=2 __KokiAdapterChecked2__ >カセット<br>
 <input type="radio" name="wKokiAdapter" value=3 __KokiAdapterChecked3__ >サイコロ型
</td>
</tr>

<tr></tr>




<!--★-->




<!--29行目-->
<tr>
<td colspan=10>◆系統　　　　
 <input type="radio" name="wKeito" value=1 __KeitoChecked1__ >PS渡り
 <input type="radio" name="wKeito" value=2 __KeitoChecked2__  >玄関子機渡り　
   __wKeitoSonota__
</td>
<td colspan=16>
    幹線:__wKansen__
     __wKeitoSu__ 系統

</td>
</tr>

<!--30行目-->
<tr>
<td colspan=10 >
<!--ドローエリア6-->__SumPic6Loop__ __SumPic6__ __SumPic6Loop__
</td><td colspan=16 >
__PicKeito1__ __PicKeito2__
</td>
</tr>


<tr><td colspan=26 bgcolor="green"></td></tr>







<!--31行目-->
<tr>
<td colspan=20 >
◆部屋番号　　　総戸数:__wKosu__ 戸
</td>
<td colspan=10 rowspan=2 valign="top">備考<br>
__wBikoYobo__
</td>
</tr>


<!--32行目-->

<tr><td colspan=12><!--ドローエリア7-->__SumPic7__</td>
<td colspan=8 >

__PicHeya1__ __PicHeya2__

</td>

</tr>

<tr><td colspan=26 bgcolor="green"></td></tr>




</table>



</p><!--A4印刷　End-->

<br>






<hr size="__HRSize__" color="__HRColor__">



</body>

</html>

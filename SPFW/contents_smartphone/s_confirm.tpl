<html>
<head>
<title>営業活動支援システム</title>
<LINK REL="stylesheet" TYPE="text/css" HREF="./css/a7.css">

<script type="text/javascript" src="tools.js"></script>
<link href="css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="js/jquery.ui.core.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
<script src="js/jquery.numberPicker.js" type="text/javascript"></script>

<script type="text/javascript">
$(function() {

	$("#wChosaDate").datepicker({});

});
</script>
<script type="text/javascript" src="js/tools_ajax.js"></script>

</head>



<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">
__SHeader__
<hr size="__HRSize__" color="__HRColor__">
<center>《物件情報/営業活動》</center>
<hr size="__HRSize__" color="__HRColor__">

>>><a href="s_list.php__QUERY__">物件一覧にもどる</a>
__IfError__
 __ErrorLoop__
<font color="red" >__ErrorString__<br></font>
 __ErrorLoop__
__IfError__





<form action="s_finish.php" method="POST" name="mainform">
<table border=1 >
<tr><td  >物件CD</td><td>__editBukkenCD__</td><td  ><b>物件名※必須</b></td><td>__wBukkenName__</td></tr>


<tr><td  >営業所</td><td>__wShozokuName__</td>

<td  >営業担当</td><td>__wTantoName__</td></tr>
</table>

<!--ｈｔｍｌはりつけ-->
<table border=1>
<!--1行目-->
<tr>
<td colspan=4>マンション名</td><td colspan=12 >__wBukkenName__</td>
<td colspan=3 >訪問日</td><td  colspan=7 >__wChosaDate__</td>
</tr>


<!--2行目-->
<tr>
<td colspan=2>住所</td><td colspan=14 >__wAddress__</td>
<td colspan=5 >__wBunjyo1__</td>
<td  colspan=5 >竣工 __wShunko__ </td>
</tr>


<!--3行目-->
<tr>
<td colspan=16 rowspan=3 >


管理情報・理事会情報<br>
管理会社名：__wKanriGaisya__  担当名：__wKanriGaisyaTanto__ __wKanriGaisyaTEL__
<br>
管理員勤務：__wKanriKeitai__ __wKanriTEL__" 総会：__wSokai__ 月<br>
その他（理事会等）<br>
__wKanriSonota__


</td>
<td colspan=2 rowspan=2 >要望</td>
<td  colspan=8 rowspan=2 >
__wYobo1__ __wYobo2__ __wYobo3__ 

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
__wNextSystem__
</td>
</tr>


<!--6行目-->
<tr>
<td colspan=26><B>調査情報</B></td>
</tr>


<!--7行目-->
<tr>
<td colspan=5>【オートロック】　__wAutoLock1__
</td>


<td colspan=6>
 __wDoorType1__
</td>
<td  colspan=15 >【既設メーカー】
 __wMaker1__ __wMaker2__ __wMaker3__ __wMakerSonota__
 </td>
</tr>




<!--追加行目-->
<tr>
<td colspan=7>【リニューアル状況】　
__wB0011__
</td>


<td colspan=5>
リニューアル実施  __wB003__ 年
</td><td colspan=5>
見積No<input type="text" name="wB005" value="__wB005__">
</td>
<td  colspan=9 >【竣工時メーカー】
 __wB0021__ __wB0022__ __wB0023__ 
</td>
</tr>

<tr><td colspan=26 bgcolor="green"></td></tr>

<!--8行目-->
<tr>
<td colspan=3>【集合玄関機】</td><td colspan=13 >
品番：__wShuGenKataban__</td>
<td colspan=3>【管理室親機】</td>
<td colspan=7 >
品番：__wKanOyaKataban__</td>

</tr>


<!--9行目-->
<tr>
<td colspan=4 rowspan=6 >ドローエリア</td>
<td colspan=2 >カメラ</td>
<td colspan=3>
__wShuGenCamera1__
</td>

<td colspan=2 rowspan=2 >取付部分</td>
<td colspan=5 rowspan=2 >
__wShuGenToritukeBubun1__ 
__wShuGenToritukeBubun2__ 
__wShuGenToritukeBubun3__ 
__wShuGenToritukeBubun4__ 

</td>

<td colspan=4 rowspan=6 >ドローエリア</td>
<td colspan=2 >警報表示</td>
<td colspan=4>
__wKanOyaDisp1__
</td>
</tr>

<!--10行目-->
<tr>

<td colspan=2 rowspan=3 >逆マスタ</td>
<td colspan=3 rowspan=3 >
 __wGyakuMaster1__  __wGyakuMaster2__  __wGyakuMaster3__
__wGyakuMasterSonota__
</td>


<td colspan=2 >通話機能</td>
<td colspan=4 >
__wKanOyaTuwa1__
</td>
</tr>

<!--11行目-->
<tr>

<td colspan=2 >取付方法</td>
<td colspan=5>
__wShuGenToritukeHoho1__

</td>

<td colspan=2 rowspan=2 >取付方法</td>
<td colspan=5 rowspan=2 >
 __wKanOyaTorituke1__
 __wKanOyaTorituke2__
 __wKanOyaTorituke3__
__wKanOyaToritukeSonota__
</td>

</tr>

<!--12行目-->
<tr>

<td colspan=2 >化粧パネル</td>
<td colspan=5>
 __wShuGenPanel1__
</td>

</tr>

<!--13行目-->
<tr>

<td colspan=2 >色調</td>
<td colspan=3>__wShuGenColor__
<td colspan=7 rowspan=2  >その他<br>
 __wShuGenSonota__
</td>

<td colspan=7 rowspan=2 >その他<br>
 __wKanOyaSonota__
</td>

</tr>


<!--14行目-->
<tr>

<td colspan=2 >材質</td>
<td colspan=3>__wShuGenMaterial__</td>

</tr>

<tr><td colspan=26 bgcolor="green"></td></tr>

<!--15行目-->
<tr>
<td colspan=3 rowspan=2>【制御装置】</td>
<td colspan=13>（通話品番：__wSeigyoKatabanTuwa__)</td>
<td colspan=3 >玄関子機</td>
<td colspan=7 >（品番：__wKokiKataban__）</td>

</tr>

<!--16行目-->
<tr>
<td colspan=13 >（映像品番：__wSeigyoKatabanEizo__ )
</td>
<td colspan=4 rowspan=6 >ドローエリア</td>
<td colspan=2 rowspan=2 >取付方法</td>
<td colspan=4 rowspan=2 >
 __wKokiType1__

<br>
 __wKokiSW__ ヶ用スイッチBOX
</td>

<!--17行目-->
<tr>
<td colspan=4 rowspan=5 >ドローエリア</td>
<td colspan=2 rowspan=5 >移報出力</td>
<td colspan=3 rowspan=5 >
 __wIhoIF1__ __wIhoIF2__ __wIhoIF3__ __wIhoIF4__ __wIhoIF5__
 __wIhoSonota__
</td>
<td colspan=2 >警備会社</td>
<td colspan=5 >__wIhoshuturyokusaki__</td>
</tr>


<!--18行目-->
<tr>
<td colspan=2 >宅配連動</td>
<td colspan=5 >__wSeigyoTakuhai__</td>

<td colspan=2 >化粧パネル</td>
<td colspan=5 >
 __wKokiPanel1__
</td>
</tr>

<!--19行目-->
<tr>
<td colspan=2 >EV連動</td>
<td colspan=5 >__wSeigyoEV__</td>

<td colspan=2 >色調</td>
<td colspan=5 >__wKokiColor__</td>

</tr>


<!--20行目-->
<tr>
<td colspan=7 rowspan=2 >__wSeigyoSonota__</td>

<td colspan=3 rowspan=2 >使用予定映像<br>アダプタ</td>

<td colspan=4 rowspan=2>
 __wKokiAdapter1__  __wKokiAdapter2__  __wKokiAdapter3__ 

</td>

</tr>

<!--21行目-->
<tr></tr>

<tr><td colspan=26 bgcolor="green"></td></tr>

<!--22行目-->
<tr>
<td colspan=3>【居室親機】</td>
<td colspan=23>
調査居室 __wOyaID__ 号室
（品番：__wOyaKataban__　）
</td>

</tr>



<!--23行目-->
<tr>
<td colspan=4 rowspan=6 >ドローエリア</td>
<td colspan=2 rowspan=3 >取付タイプ</td>
<td colspan=2 rowspan=3 >
 __wOyaTorituke1__ __wOyaTorituke2__ __wOyaTorituke3__

</td>
<td colspan=4 rowspan=3 >
 __wOyaSW__ヶ用スイッチBOX<br>
 __wOyaUnit__ユニット<br>
 __wOyaToritukeSonota__
</td>


<td colspan=2 rowspan=3 >付属機器</td>
<td colspan=6 rowspan=3 >
 __wOyaFuzoku1__ __wOyaFuzoku2__ __wOyaFuzoku3__ __wOyaFuzoku4__ __wOyaFuzoku5__

__wOyaFuzokuSonota__

</td>

<td colspan=2 >管理室呼出</td>
<td colspan=4>
__wOyaKanri1__
</tr>



<!--24行目-->
<tr>

<td colspan=2 >増設親機</td>
<td colspan=4>
__wOyaZosetu1__
</tr>



<!--25行目-->
<tr>

<td colspan=2 rowspan=3>火報メーカ</td>
<td colspan=4 rowspan=3>

 __wOyaKahoMaker1__ __wOyaKahoMaker2__ __wOyaKahoMaker3__ __wOyaKahoMaker4__ __wOyaKahoMaker5__
 __wOyaKahoMakerSonota__
</td>

</tr>


<!--26行目-->
<tr>

<td colspan=2 rowspan=2>電源</td>
<td colspan=6 rowspan=2 >
 __wOyaDengen1__
 __wOyaDengenSonota__
 </td>

<td colspan=2 rowspan=3 >警報表示</td>
<td colspan=6 rowspan=3 >
 __wOyaKeiho1__ __wOyaKeiho2__ __wOyaKeiho3__ __wOyaKeiho4__ __wOyaKeiho5__
 __wOyaKeiho6__ __wOyaKeiho7__ __wOyaKeiho8__
 __wOyaKeihoSonota__
</td>
</tr>



<!--27行目-->
<tr>

</tr>


<!--28行目-->
<tr>
<td colspan=2>化粧パネル</td>
<td colspan=6>

__wOyaPanel1__

</td>

<td colspan=6 > 
その他
__wOyaSonota__
</td>
</tr>

<tr><td colspan=26 bgcolor="green"></td></tr>

<!--29行目-->
<tr>
<td colspan=11>◆系統　  __wKeito1__     その他 __wKeitoSonota__
</td>
<td colspan=15>
    幹線   __wKansen__     ( __wKeitoSu__ )系統

</td>
</tr>

<!--30行目-->
<tr>
<td colspan=26 >
ドローエリア（系統図）
</td>
</tr>


<tr><td colspan=26 bgcolor="green"></td></tr>

<!--31行目-->
<tr>
<td colspan=20 >
◆部屋番号　　　総戸数 __wKosu__ 戸
</td>
<td colspan=10 rowspan=16>備考<br>
__wYoboSonota__
</td>
</tr>


<!--32行目-->
<tr>
<td>1501</td>	<td>1502</td>	<td>1503</td>	<td>1504</td>	<td>1505</td>	<td>1506</td>	<td>1507</td>	<td>1508</td>	<td>1509</td>	<td>1510</td>	<td>1511</td>	<td>1512</td>	<td>1513</td>	<td>1514</td>	<td>1515</td>	<td>1516</td>	<td>1517</td>	<td>1518</td>	<td>1519</td>	<td>1520</td>
</tr>
<tr>
<td>1401</td>	<td>1402</td>	<td>1403</td>	<td>1404</td>	<td>1405</td>	<td>1406</td>	<td>1407</td>	<td>1408</td>	<td>1409</td>	<td>1410</td>	<td>1411</td>	<td>1412</td>	<td>1413</td>	<td>1414</td>	<td>1415</td>	<td>1416</td>	<td>1417</td>	<td>1418</td>	<td>1419</td>	<td>1420</td>
</tr>
<tr>
<td>1301</td>	<td>1302</td>	<td>1303</td>	<td>1304</td>	<td>1305</td>	<td>1306</td>	<td>1307</td>	<td>1308</td>	<td>1309</td>	<td>1310</td>	<td>1311</td>	<td>1312</td>	<td>1313</td>	<td>1314</td>	<td>1315</td>	<td>1316</td>	<td>1317</td>	<td>1318</td>	<td>1319</td>	<td>1320</td>
</tr>
<tr><td>1201</td>	<td>1202</td>	<td>1203</td>	<td>1204</td>	<td>1205</td>	<td>1206</td>	<td>1207</td>	<td>1208</td>	<td>1209</td>	<td>1210</td>	<td>1211</td>	<td>1212</td>	<td>1213</td>	<td>1214</td>	<td>1215</td>	<td>1216</td>	<td>1217</td>	<td>1218</td>	<td>1219</td>	<td>1220</td>
</tr>
<tr><td>1101</td><td>1102</td>	<td>1103</td>	<td>1104</td>	<td>1105</td>	<td>1106</td>	<td>1107</td>	<td>1108</td>	<td>1109</td>	<td>1110</td>	<td>1111</td>	<td>1112</td>	<td>1113</td>	<td>1114</td>	<td>1115</td>	<td>1116</td>	<td>1117</td>	<td>1118</td>	<td>1119</td>	<td>1120</td>
</tr>
<tr><td>1001</td><td>1002</td>	<td>1003</td>	<td>1004</td>	<td>1005</td>	<td>1006</td>	<td>1007</td>	<td>1008</td>	<td>1009</td>	<td>1010</td>	<td>1011</td>	<td>1012</td>	<td>1013</td>	<td>1014</td>	<td>1015</td>	<td>1016</td>	<td>1017</td>	<td>1018</td>	<td>1019</td>	<td>1020</td>
</tr>
<tr><td>901</td><td>902</td>	<td>903</td>	<td>904</td>	<td>905</td>	<td>906</td>	<td>907</td>	<td>908</td>	<td>909</td>	<td>910</td>	<td>911</td>	<td>912</td>	<td>913</td>	<td>914</td>	<td>915</td>	<td>916</td>	<td>917</td>	<td>918</td>	<td>919</td>	<td>920</td>
</tr>
<tr><td>801</td><td>802</td>	<td>803</td>	<td>804</td>	<td>805</td>	<td>806</td>	<td>807</td>	<td>808</td>	<td>809</td>	<td>810</td>	<td>811</td>	<td>812</td>	<td>813</td>	<td>814</td>	<td>815</td>	<td>816</td>	<td>817</td>	<td>818</td>	<td>819</td>	<td>820</td>
</tr>
<tr><td>701</td><td>702</td>	<td>703</td>	<td>704</td>	<td>705</td>	<td>706</td>	<td>707</td>	<td>708</td>	<td>709</td>	<td>710</td>	<td>711</td>	<td>712</td>	<td>713</td>	<td>714</td>	<td>715</td>	<td>716</td>	<td>717</td>	<td>718</td>	<td>719</td>	<td>720</td>
</tr>
<tr><td>601</td><td>602</td>	<td>603</td>	<td>604</td>	<td>605</td>	<td>606</td>	<td>607</td>	<td>608</td>	<td>609</td>	<td>610</td>	<td>611</td>	<td>612</td>	<td>613</td>	<td>614</td>	<td>615</td>	<td>616</td>	<td>617</td>	<td>618</td>	<td>619</td>	<td>620</td>
</tr>
<tr><td>501</td><td>502</td>	<td>503</td>	<td>504</td>	<td>505</td>	<td>506</td>	<td>507</td>	<td>508</td>	<td>509</td>	<td>510</td>	<td>511</td>	<td>512</td>	<td>513</td>	<td>514</td>	<td>515</td>	<td>516</td>	<td>517</td>	<td>518</td>	<td>519</td>	<td>520</td>
</tr>
<tr><td>401</td><td>402</td>	<td>403</td>	<td>404</td>	<td>405</td>	<td>406</td>	<td>407</td>	<td>408</td>	<td>409</td>	<td>410</td>	<td>411</td>	<td>412</td>	<td>413</td>	<td>414</td>	<td>415</td>	<td>416</td>	<td>417</td>	<td>418</td>	<td>419</td>	<td>420</td>
</tr>
<tr><td>301</td><td>302</td>	<td>303</td>	<td>304</td>	<td>305</td>	<td>306</td>	<td>307</td>	<td>308</td>	<td>309</td>	<td>310</td>	<td>311</td>	<td>312</td>	<td>313</td>	<td>314</td>	<td>315</td>	<td>316</td>	<td>317</td>	<td>318</td>	<td>319</td>	<td>320</td>
</tr>
<tr><td>201</td><td>202</td>	<td>203</td>	<td>204</td>	<td>205</td>	<td>206</td>	<td>207</td>	<td>208</td>	<td>209</td>	<td>210</td>	<td>211</td>	<td>212</td>	<td>213</td>	<td>214</td>	<td>215</td>	<td>216</td>	<td>217</td>	<td>218</td>	<td>219</td>	<td>220</td>
</tr>
<tr><td>101</td><td>102</td>	<td>103</td>	<td>104</td>	<td>105</td>	<td>106</td>	<td>107</td>	<td>108</td>	<td>109</td>	<td>110</td>	<td>111</td>	<td>112</td>	<td>113</td>	<td>114</td>	<td>115</td>	<td>116</td>	<td>117</td>	<td>118</td>	<td>119</td>	<td>120</td>

</td>
</tr>

<tr><td colspan=26 bgcolor="green"></td></tr>


<!--34行目-->
<tr>
<td colspan=26>◆現場写真　　　　
</td>
</tr>

<!--35行目-->
<tr>
<td colspan=26 >
共有部
</td>
</tr>
<!--36行目-->
<tr>
<td colspan=26 >
専有部
</td>
</tr>
</table>
<!--ｈｔｍｌコピーおわり-->

<br>
<input type=hidden name="editBukkenCD" value="__editBukkenCD__" >
<input type=hidden name="rKey" value="__rKey__">
<input type=hidden name="work" value="1">

<input type="hidden" name="wBukkenName" value="__wBukkenName__" >
<input type="hidden" name="wTantoCD" value="__wTantoCD__" >
<input type="hidden" name="wShozokuCD" value="__wShozokuCD__" >
<input type="hidden" name="wChosaDate" value="__wChosaDate__" >
<input type="hidden" name="wShunko" value="__wShunko__" >
<input type="hidden" name="wKosu" value="__wKosu__" >
<input type="hidden" name="wAddress" value="__wAddress__" >
<input type="hidden" name="wNextSystem" value="__wNextSystem__" >
<input type="hidden" name="wKanriGaisya" value="__wKanriGaisya__" >
<input type="hidden" name="wKanriGaisyaTanto" value="__wKanriGaisyaTanto__" >
<input type="hidden" name="wKanriGaisyaTEL" value="__wKanriGaisyaTEL__" >
<input type="hidden" name="wKanriSonota" value="__wKanriSonota__" >
<input type="hidden" name="wKanriKeitai" value="__wKanriKeitai__" >
<input type="hidden" name="wKanriTEL" value="__wKanriTEL__" >
<input type="hidden" name="wSokai" value="__wSokai__" >
<input type="hidden" name="wYobo" value="__wYobo__" >
<input type="hidden" name="wYoboSonota" value="__wYoboSonota__" >
<input type="hidden" name="wElevator" value="__wElevator__" >
<input type="hidden" name="wSeigyoTakuhai" value="__wSeigyoTakuhai__" >
<input type="hidden" name="wSeigyoEV" value="__wSeigyoEV__" >
<input type="hidden" name="wAutoLock" value="__wAutoLock__" >
<input type="hidden" name="wDoorType" value="__wDoorType__" >
<input type="hidden" name="wMaker" value="__wMaker__" >
<input type="hidden" name="wMakerSonota" value="__wMakerSonota__" >
<input type="hidden" name="wShuGenKataban" value="__wShuGenKataban__" >
<input type="hidden" name="wShuGenDaisu" value="__wShuGenDaisu__" >
<input type="hidden" name="wShuGenCamera" value="__wShuGenCamera__" >
<input type="hidden" name="wGyakuMaster" value="__wGyakuMaster__" >
<input type="hidden" name="wGyakuMasterSonota" value="__wGyakuMasterSonota__" >
<input type="hidden" name="wShuGenColor" value="__wShuGenColor__" >
<input type="hidden" name="wShuGenMaterial" value="__wShuGenMaterial__" >
<input type="hidden" name="wShuGenPanel" value="__wShuGenPanel__" >
<input type="hidden" name="wShuGenToritukeBubun" value="__wShuGenToritukeBubun__" >
<input type="hidden" name="wShuGenToritukeHoho" value="__wShuGenToritukeHoho__" >
<input type="hidden" name="wShuGenSonota" value="__wShuGenSonota__" >
<input type="hidden" name="wSeigyoKatabanTuwa" value="__wSeigyoKatabanTuwa__" >
<input type="hidden" name="wSeigyoKatabanEizo" value="__wSeigyoKatabanEizo__" >
<input type="hidden" name="wSeigyoSonota" value="__wSeigyoSonota__" >
<input type="hidden" name="wIhoshuturyokusaki" value="__wIhoshuturyokusaki__" >
<input type="hidden" name="wIhoIF" value="__wIhoIF__" >
<input type="hidden" name="wIhoSonota" value="__wIhoSonota__" >
<input type="hidden" name="wKanOyaKataban" value="__wKanOyaKataban__" >
<input type="hidden" name="wKanOyaDisp" value="__wKanOyaDisp__" >
<input type="hidden" name="wKanOyaTuwa" value="__wKanOyaTuwa__" >
<input type="hidden" name="wKanOyaTorituke" value="__wKanOyaTorituke__" >
<input type="hidden" name="wKanOyaToritukeSonota" value="__wKanOyaToritukeSonota__" >
<input type="hidden" name="wKanOyaSonota" value="__wKanOyaSonota__" >
<input type="hidden" name="wOyaKataban" value="__wOyaKataban__" >
<input type="hidden" name="wOyaTorituke" value="__wOyaTorituke__" >
<input type="hidden" name="wOyaDengen" value="__wOyaDengen__" >
<input type="hidden" name="wOyaFuzoku" value="__wOyaFuzoku__" >
<input type="hidden" name="wOyaKeiho" value="__wOyaKeiho__" >
<input type="hidden" name="wOyaKanri" value="__wOyaKanri__" >
<input type="hidden" name="wOyaZosetu" value="__wOyaZosetu__" >
<input type="hidden" name="wOyaID" value="__wOyaID__" >
<input type="hidden" name="wOyaSW" value="__wOyaSW__" >
<input type="hidden" name="wOyaUnit" value="__wOyaUnit__" >
<input type="hidden" name="wOyaToritukeSonota" value="__wOyaToritukeSonota__" >
<input type="hidden" name="wOyaDengenSonota" value="__wOyaDengenSonota__" >
<input type="hidden" name="wOyaFuzokuSonota" value="__wOyaFuzokuSonota__" >
<input type="hidden" name="wOyaKeihoSonota" value="__wOyaKeihoSonota__" >
<input type="hidden" name="wOyaKahoMaker" value="__wOyaKahoMaker__" >
<input type="hidden" name="wOyaKahoMakerSonota" value="__wOyaKahoMakerSonota__" >
<input type="hidden" name="wOyaPanel" value="__wOyaPanel__" >
<input type="hidden" name="wOyaSonota" value="__wOyaSonota__" >
<input type="hidden" name="wKokiKataban" value="__wKokiKataban__" >
<input type="hidden" name="wKokiSW" value="__wKokiSW__" >
<input type="hidden" name="wKokiPanel" value="__wKokiPanel__" >
<input type="hidden" name="wKokiColor" value="__wKokiColor__" >
<input type="hidden" name="wKokiAdapter" value="__wKokiAdapter__" >
<input type="hidden" name="wKeito" value="__wKeito__" >
<input type="hidden" name="wKeitoSonota" value="__wKeitoSonota__" >
<input type="hidden" name="wKansen" value="__wKansen__" >
<input type="hidden" name="wKeitoSu" value="__wKeitoSu__" >
<input type="hidden" name="wBunjyo" value="__wBunjyo__" >
<input type="hidden" name="wBikoYobo" value="__wBikoYobo__" >
<input type="hidden" name="wB001" value="__wB001__" >
<input type="hidden" name="wB002" value="__wB002__" >
<input type="hidden" name="wB003" value="__wB003__" >
<input type="hidden" name="wB004" value="__wB004__" >
<input type="hidden" name="wB005" value="__wB005__" >
<input type="hidden" name="wB006" value="__wB006__" >
<input type="hidden" name="wB007" value="__wB007__" >
<input type="hidden" name="wB008" value="__wB008__" >
<input type="hidden" name="wB009" value="__wB009__" >
<input type="hidden" name="wB010" value="__wB010__" >
<input type="hidden" name="wMukouFlg" value="__wMukouFlg__" >
<input type="hidden" name="wCreated" value="__wCreated__" >
<input type="hidden" name="wCreator" value="__wCreator__" >
<input type="hidden" name="wUpdated" value="__wUpdated__" >
<input type="hidden" name="wUpdater" value="__wUpdater__" >


<input type=submit value = "確　定" >
<input type="button" value=" 戻る " onclick="history.back()">

</form>



<hr size="__HRSize__" color="__HRColor__">
__SCopyright__
<br>




</body>

</html>

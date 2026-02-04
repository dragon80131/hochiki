<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>リニューアル支援</title>

<!-- BootstrapのCSS読み込み -->
<link href="./include/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="./css/rnsien.css">

<script type="text/javascript" src="tools.js"></script>
<link href="css/jquery-ui-1.8.2.custom.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="js/jquery.ui.core.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker.js" type="text/javascript"></script>
<script src="js/jquery.ui.datepicker-ja.js" type="text/javascript"></script>
<script src="js/jquery.numberPicker.js" type="text/javascript"></script>
<script type="text/javascript">
</script>


<script type="text/javascript" src="js/tools_ajax.js"></script>
<script type="text/javascript" src="js/ConnectedSelect.js"></script>

</head>

<body bgcolor="__SBackground__" text="__STextColor__" link="__SLinkColor__" alink="__SALinkColor__" vlink="__SVLinkColor__">

<h6>らくタッチPlus</h6>
<br>
◆　基本情報
<table  border="1">

<tr>
	<td bgcolor="lemonchiffon" colspan="2">マンション名</td>
	<td colspan="4" bgcolor="lightyellow">__wBukkenName__</td></tr>
	<td bgcolor="lemonchiffon" colspan="2">工事名称</td>
	<td colspan="4">__KojiName__</td></tr>
<tr><td bgcolor="lemonchiffon" colspan="2" >住所</td>
	<td colspan="2"><input type="text" name="__wAddress__"style="width:400px"></td>
	<td bgcolor="lemonchiffon">戸数</td>
	<td ><input type="text" name="Kosuu" value="__wKosuu__"></td></tr>
<tr><td bgcolor="lemonchiffon" colspan="2">管理会社</td>
	<td colspan="2">
		<input type="text" name="wKanriGaisya" value="__wKanriGaisya__"></td>
	<td bgcolor="lemonchiffon">担当者</td>
	<td ><input type="text" name="wTanto" id="IraiDate" value="__wTanto__">様</td></tr>
<tr><td bgcolor="lemonchiffon" colspan="2">消防特例</td>
	<td colspan="4">　<input type="radio" name="wIraiDate" id="IraiDate" value="0">特例なし
					　<input type="radio" name="wIraiDate" id="IraiDate" value="1">170号
					　<input type="radio" name="wIraiDate" id="IraiDate" value="2">220号住戸用
					　<input type="radio" name="wIraiDate" id="IraiDate" value="3">220号共住用</td></tr>
<tr><td bgcolor="lemonchiffon" colspan="2">管理員</td>
	<td colspan="1"><input type="text" name="wIraiDate" id="IraiDate" value="__wIraiDate__">様</td>
	<td bgcolor="lemonchiffon" colspan="2">管理室TEL</td>
	<td ><input type="text" name="wIraiDate" id="IraiDate" value="__wIraiDate__"></td></tr>
<tr><td bgcolor="lemonchiffon" colspan="2">勤務状況</td>
	<td colspan="4">　<input type="checkbox" name="wIraiDate" id="IraiDate" value="0">月
					　<input type="checkbox" name="wIraiDate" id="IraiDate" value="1">火
					　<input type="checkbox" name="wIraiDate" id="IraiDate" value="2">水
					　<input type="checkbox" name="wIraiDate" id="IraiDate" value="3">木
					　<input type="checkbox" name="wIraiDate" id="IraiDate" value="4">金
					　<input type="checkbox" name="wIraiDate" id="IraiDate" value="5">土
					　<input type="checkbox" name="wIraiDate" id="IraiDate" value="6">日
	</td></tr>
<tr><td bgcolor="lemonchiffon" colspan="2">勤務状況備考</td>
	<td colspan="4"><input type="text" name="wIraiDate" id="IraiDate" value="" style="width:400px"></td></tr>
</table>

<br>
◆　工事種別・工事概要
<table  border="1">
<tr><td bgcolor="lemonchiffon" colspan="2">インターホン設備更新</td>
	<td>　<input type="radio" name="wInterhonKoshin" value="0">有
		　<input type="radio" name="wInterhonKoshin" value="1">無　
	</td>
	<td bgcolor="lemonchiffon" colspan="2">基本システム</td>
	<td>らくタッチPlus　</td>
	<td bgcolor="lemonchiffon" colspan="2">カメラ</td>
	<td><input type="text" name="wCamera" value="__wCamera__">
</tr>
<tr><td bgcolor="lemonchiffon" colspan="2">ガス漏れ警報器更新</td>
	<td>　<input type="radio" name="GasKoshin" value="0">有
		　<input type="radio" name="GasKoshin" value="1">無　
	</td>
	<td bgcolor="lemonchiffon" colspan="2">ベース更新</td>
	<td>　<input type="radio" name="BaseKoshin" value="0">有
		　<input type="radio" name="BaseKoshin" value="1">無　
	</td>
</tr>
<tr><td bgcolor="lemonchiffon" colspan="2">専有部火災感知器更新</td>
	<td>　<input type="radio" name="SenyuKasaiKoushin" value="0">有
		　<input type="radio" name="SenyuKasaiKoushin" value="1">無　
	</td>
	<td bgcolor="lemonchiffon" colspan="2">中継器更新</td>
	<td>　<input type="radio" name="TyueikiKoshin" value="0">有
		　<input type="radio" name="TyueikiKoshin" value="1">無　
	</td>
</tr>
<tr><td bgcolor="lemonchiffon" colspan="2">その他工事</td>
	<td colspan="7">　<input type="radio" name="GasKoshin" value="0">有
		　<input type="radio" name="GasKoshin" value="1">無　
		　工事内容：
		　<input type="text" name="KojiNaiyo" value="__KojiNaiyo__">
	</td>
</tr>
<tr><td bgcolor="lemonchiffon" colspan="2">全体工期</td>
	<td colspan="7">　<input type="number" name="wZentaiYearStart" value="__wZentaiYearStart__" min="2018" max="2200" style="width:80px">年
		<input type="number" name="wBasicInfo" value="__wBasicInfo__" min="1" max="12" style="width:40px">月
		<input type="number" name="wBasicInfo" value="__wBasicInfo__" min="1" max="31" style="width:40px">日（
		<input type="text" name="wZentaiYobiStart" value="__wZentaiYobiStart__" style="width:20px">）～
			<input type="number" name="wBasicInfo" value="__wBasicInfo__" min="2018" max="2200" style="width:80px">年
		<input type="number" name="wBasicInfo" value="__wBasicInfo__" min="1" max="12" style="width:40px">月
		<input type="number" name="wBasicInfo" value="__wBasicInfo__" min="1" max="31" style="width:40px">日（
		<input type="text" name="wZentaiYobiStart" value="__wZentaiYobiStart__" style="width:20px">）
	</td>
</tr>
<tr><td bgcolor="lemonchiffon" colspan="2">共用部</td>
	<td colspan="7">　<input type="number" name="wBasicInfo" value="__wBasicInfo__" min="2018" max="2200" style="width:80px">年
		<input type="number" name="wBasicInfo" value="__wBasicInfo__" min="1" max="12" style="width:40px">月
		<input type="number" name="wBasicInfo" value="__wBasicInfo__" min="1" max="31" style="width:40px">日（
		<input type="text" name="wZentaiYobiStart" value="__wZentaiYobiStart__" style="width:20px">）～
			<input type="number" name="wBasicInfo" value="__wBasicInfo__" min="2018" max="2200" style="width:80px">年
		<input type="number" name="wBasicInfo" value="__wBasicInfo__" min="1" max="12" style="width:40px">月
		<input type="number" name="wBasicInfo" value="__wBasicInfo__" min="1" max="31" style="width:40px">日（
		<input type="text" name="wZentaiYobiStart" value="__wZentaiYobiStart__" style="width:20px">）
	</td>
</tr>
<tr><td bgcolor="lemonchiffon" colspan="2">専有部</td>
	<td colspan="7">　<input type="number" name="wBasicInfo" value="__wBasicInfo__" min="2018" max="2200" style="width:80px">年
		<input type="number" name="wBasicInfo" value="__wBasicInfo__" min="1" max="12" style="width:40px">月
		<input type="number" name="wBasicInfo" value="__wBasicInfo__" min="1" max="31" style="width:40px">日（
		<input type="text" name="wZentaiYobiStart" value="__wZentaiYobiStart__" style="width:20px">）～
			<input type="number" name="wBasicInfo" value="__wBasicInfo__" min="2018" max="2200" style="width:80px">年
		<input type="number" name="wBasicInfo" value="__wBasicInfo__" min="1" max="12" style="width:40px">月
		<input type="number" name="wBasicInfo" value="__wBasicInfo__" min="1" max="31" style="width:40px">日（
		<input type="text" name="wZentaiYobiStart" value="__wZentaiYobiStart__" style="width:20px">）
	</td>
</tr>
<tr><td bgcolor="lemonchiffon" colspan="2">予備日</td>
	<td colspan="7">　<input type="number" name="wBasicInfo" value="__wBasicInfo__" min="2018" max="2200" style="width:80px">年
		<input type="number" name="wBasicInfo" value="__wBasicInfo__" min="1" max="12" style="width:40px">月
		<input type="number" name="wBasicInfo" value="__wBasicInfo__" min="1" max="31" style="width:40px">日（
		<input type="text" name="wZentaiYobiStart" value="__wZentaiYobiStart__" style="width:20px">）～
			<input type="number" name="wBasicInfo" value="__wBasicInfo__" min="2018" max="2200" style="width:80px">年
		<input type="number" name="wBasicInfo" value="__wBasicInfo__" min="1" max="12" style="width:40px">月
		<input type="number" name="wBasicInfo" value="__wBasicInfo__" min="1" max="31" style="width:40px">日（
		<input type="text" name="wZentaiYobiStart" value="__wZentaiYobiStart__" style="width:20px">）
	</td>
</tr>
<tr><td bgcolor="lemonchiffon" colspan="2">産廃回収日</td>
	<td colspan="7">　<input type="number" name="wBasicInfo" value="__wBasicInfo__" min="2018" max="2200" style="width:80px">年
		<input type="number" name="wBasicInfo" value="__wBasicInfo__" min="1" max="12" style="width:40px">月
		<input type="number" name="wBasicInfo" value="__wBasicInfo__" min="1" max="31" style="width:40px">日（
		<input type="text" name="wZentaiYobiStart" value="__wZentaiYobiStart__" style="width:20px">）
	</td>
</tr>
</table><br>
◆　設備
<table border="1">
<tr><td bgcolor="lemonchiffon" colspan="2">資材置場</td>
	<td>　<input type="radio" name="wSetubi1" value="0">有
		　<input type="radio" name="wSetubi1" value="1">無　
	</td>
</tr>
<tr><td bgcolor="lemonchiffon" colspan="2">道具置場</td>
	<td>　<input type="radio" name="wSetubi2" value="0">有
		　<input type="radio" name="wSetubi2" value="1">無　
	</td>
</tr>
<tr><td bgcolor="lemonchiffon" colspan="2">産廃置場</td>
	<td>　<input type="radio" name="wSetubi3" value="0">有
		　<input type="radio" name="wSetubi3" value="1">無　
	</td>
</tr>
<tr><td bgcolor="lemonchiffon" colspan="2">休憩所</td>
	<td>　<input type="radio" name="wSetubi3" value="0">有
		　<input type="radio" name="wSetubi3" value="1">無　
	</td>
</tr>
<tr><td bgcolor="lemonchiffon" colspan="2">トイレ</td>
	<td>　<input type="radio" name="wSetubi4" value="0">有
		　<input type="radio" name="wSetubi4" value="1">無　
	</td>
</tr>
<tr><td bgcolor="lemonchiffon" colspan="2">喫煙所</td>
	<td>　<input type="radio" name="wSetubi5" value="0">有
		　<input type="radio" name="wSetubi5" value="1">無　
	</td>
</tr>
<tr><td bgcolor="lemonchiffon" colspan="2">鍵の借用</td>
	<td>　<input type="radio" name="wSetubi6" value="0">有
		　<input type="radio" name="wSetubi6" value="1">無　
	</td>
</tr>
<tr><td bgcolor="lemonchiffon" colspan="2">駐車場</td>
	<td>　<input type="radio" name="wSetubi7" value="0">有
		　<input type="radio" name="wSetubi7" value="1">無　
	</td>
</tr>
</table>
<br>
◆　警報関係
<tr><td bgcolor="lemonchiffon" colspan="2">警備会社</td>
	<td><input type="text" name="Keiho1" value="__Keiho1__"></td>
	<td bgcolor="lemonchiffon" colspan="2">警備会社TEL</td>
	<td><input type="text" name="Keiho2" value="__Keiho2__"></td>
</tr>
<tr><td bgcolor="lemonchiffon" colspan="2">非常</td>
	<td>　<input type="radio" name="Keiho3" value="0">有
		　<input type="radio" name="Keiho3" value="1">無　</td>
	<td bgcolor="lemonchiffon" colspan="2">移報</td>
	<td>　<input type="radio" name="Keiho4" value="0">有
		　<input type="radio" name="Keiho4" value="1">無　
</tr>
<tr><td bgcolor="lemonchiffon" colspan="2">ガス漏れ</td>


			<!--20180518 確認書アプリは「利用する」しか選べないように変更
			<option value="0" __KakuninFlg0Selected__>利用しない</option>
			<option value="1" __KakuninFlg1Selected__>利用する</option>
		</select>　<font size="2" color="gray">※費用はかかりません。</font>
		</td></tr>
<tr><td colspan="1" rowspan="2" bgcolor="lemonchiffon">確認書アプリ</td>
	<td bgcolor="lemonchiffon">タイトル</td>
	<td><input type="text" name="wKakuninTitle" value="__wKakuninTitle__" size="30"><br><font size="2" color="dimgray">※「インターホン工事完了確認書」以外の場合修正</font></td></tr>
<tr><td bgcolor="lemonchiffon">フッター</td>
	<td><input type="text" name="wKakuninFooter" value="__wKakuninFooter__" size="30"><br><font size="2" color="dimgray">※「アイホン株式会社」以外の場合修正</font></td></tr>

<tr><td colspan="2" bgcolor="lemonchiffon">スマートボード利用</td>
	<td><select name="wSmartFlg">
		<option value="0" __SmartFlg0Selected__>利用しない</option>
		<option value="1" __SmartFlg1Selected__>利用する</option>
		</select></td></tr>




<tr><td bgcolor="lightgrey" colspan="5">3．日程</td></tr>

<tr><td bgcolor="lightgrey" rowspan="5" ></td><td bgcolor="lemonchiffon" >予定案内提供日</td>
	<td><input type="text" name="wYoteTekyoDate" id="YoteTekyoDate" value="__wYoteTekyoDate__" size="10"></td>
	<td colspan="2" bgcolor="lightyellow"><font size="2" color="dimgray">ネスペ⇒担当者に資料を提供する日</font></td></tr>

<tr><td bgcolor="lemonchiffon">工事説明資料配布日</td>
	<td><input type="text" name="wYoteDate" id="YoteDate" value="__wYoteDate__" size="10"></td>
	<td bgcolor="lightyellow"><font size="2" color="dimgray">予約システム受付サービス開始日</font></td></tr>

<tr><td bgcolor="lemonchiffon">変更受付締切日</td>
	<td><input type="text" name="wYoyakuEnd" id="YoyakuEnd" value="__wYoyakuEnd__" size="10"></td>
	<td bgcolor="lightyellow"><font size="2" color="dimgray">受付を一旦終了し、工事日時調整開始</font></td></tr>

<tr><td bgcolor="lemonchiffon">専有部決定案内提供日</td>
	<td><input type="text" name="wTekyoDate" id="TekyoDate" value="__wTekyoDate__" size="10"></td>
	<td bgcolor="lightyellow"><font size="2" color="dimgray">ネスペ⇒担当者に資料を提供する日(AM中)</font></td></tr>

<tr><td bgcolor="lemonchiffon">専有部決定案内配布日</td>
	<td><input type="text" name="wKeteiDate" id="KeteiDate" value="__wKeteiDate__" size="10"></td>
	<td bgcolor="lightyellow"><font size="2" color="dimgray">以後の専有部工事日時変更は、メールにて報告</font></td></tr>



<!--1行目
<tr><td bgcolor="lightgrey" colspan="5">4．工事施工会社情報</td></tr>

<tr><td bgcolor="lightgrey" rowspan="6"></td>
	<td bgcolor="gainsboro" colspan="4">アイホン担当者情報　<a href=./s_tanto_detail.php?rKey=__rKey__>登録はこちら＞＞</a>
	</td></tr>
<tr><td bgcolor="lemonchiffon">★アイホン担当者１</td>
	<td colspan="3">
		<select name="wTantoCD1">
		<option value="0">-</option>
		__TantoLoop__
		<option value="__TantoCD__" __TantoCD1Selected__ >__EigyoshoName__　__TantoName__</option>
		__TantoLoop__
		</select></td></tr>
<tr><td bgcolor="lemonchiffon">★アイホン担当者２</td>
	<td colspan="3">
		<select name="wTantoCD2">
		<option value="0">-</option>
		__TantoLoop__
		<option value="__TantoCD__" __TantoCD2Selected__ >__EigyoshoName__　__TantoName__</option>
		__TantoLoop__
		</select></td></tr>

<tr><td bgcolor="gainsboro" colspan="4">施工業者情報
	<br><font size="2">　リストにない場合は「★8．その他（注意事項等）」に会社名,担当者名,連絡先,メールアドレスをご記入お願いします。</font></td></tr>

<tr><td bgcolor="lemonchiffon">施工業者担当者１</td>
	<td colspan="3">
		<select name="wGyosyaTantoCD1">
		<option value="0">-</option>
		__GyosyaTantoLoop__
		<option value="__GyosyaTantoCD__" __GyosyaTantoCD1Selected__>__GyosyaName__ __GyosyaTantoName__</option>
		__GyosyaTantoLoop__
		</select></td></tr>

<tr><td bgcolor="lemonchiffon">施工業者担当者２</td>
	<td colspan="3">
		<select name=wGyosyaTantoCD2 >
		<option value="0">-</option>
		__GyosyaTantoLoop__
		<option value="__GyosyaTantoCD__" __GyosyaTantoCD2Selected__ > __GyosyaName__ __GyosyaTantoName__</option>
		__GyosyaTantoLoop__
		</select></td></tr>




<tr><td bgcolor="lightgrey" colspan="5">5．工事可能戸数</td></tr>

<tr><td bgcolor="lightgrey" rowspan="3"></td><td rowspan=3 bgcolor="lemonchiffon">作業時間帯区分</td>
	<td bgcolor="lemonchiffon">★午前</td>
	<td colspan="2"><input type="text" name="wTimeAStart" value="__wTimeAStart__" size="10">～
		<input type="text" name="wTimeAEnd" value="__wTimeAEnd__" size="10">
		<input type="text" name="wTimeASu" value="__wTimeASu__" size="2">戸</td></tr>
<tr><td bgcolor="lemonchiffon">★午後１</td>
	<td colspan="2"><input type="text" name="wTimeBStart" value="__wTimeBStart__" size="10">～
		<input type="text" name="wTimeBEnd" value="__wTimeBEnd__" size="10">
		<input type="text" name="wTimeBSu" value="__wTimeBSu__" size="2">戸</td></tr>
<tr><td bgcolor="lemonchiffon">★午後２</td>
	<td colspan="2"><input type="text" name="wTimeCStart" value="__wTimeCStart__" size="10">～
		<input type="text" name="wTimeCEnd" value="__wTimeCEnd__" size="10">
		<input type="text" name="wTimeCSu" value="__wTimeCSu__" size="2">戸</td></tr>




<tr><td bgcolor="lightgrey" colspan="5">6．施工方法情報
	<br><font size="2">　リストにない場合は「★8．その他（注意事項等）」に機器名、型番、価格をご記入お願いします。</font></td></tr>

<tr><td bgcolor="lightgrey" rowspan="13"></td><td bgcolor="lemonchiffon">★居室親機型番</td>
	<td colspan="3">
		<select name="wOyaDeviceCD">
		<option value="0">-</option>
		__OyaDeviceLoop__
		<option value="__OyaDeviceCD__" __OyaDeviceSelected__ >__OyaKataban__ (__OyaDeviceName__)</option>
		__OyaDeviceLoop__
		</select></td></tr>

<tr><td bgcolor="lemonchiffon">★玄関子機型番</td>
	<td colspan="3">
		<select name="wKokiDeviceCD">
		<option value="0">-</option>
		__KokiDeviceLoop__
		<option value="__KokiDeviceCD__" __KokiDeviceSelected__ >__KokiKataban__ (__KokiDeviceName__)</option>
		__KokiDeviceLoop__
		</select></td></tr>

<tr><td bgcolor="lemonchiffon">★玄関子機パネル型番</td>
	<td colspan="3"><input type="text" name="wKokiPanelKataban" value="__wKokiPanelKataban__" ></td></tr>


<tr><td rowspan="4" bgcolor="lemonchiffon">★オプション機器<br>★販売価格</td>
	<td colspan="3">
		<select name="wOP1DeviceCD">
		<option value="0">-</option>
		__OPDeviceLoop__
		<option value="__OPDeviceCD__" __OP1DeviceSelected__ >__OPKataban__ (__OPDeviceName__)</option>
		__OPDeviceLoop__
		</select>
		\<input type="text" name="wOPPrice1" value="__wOPPrice1__" size=6></td></tr>

<tr><td colspan="3">
		<select name="wOP2DeviceCD">
		<option value="0">-</option>
		__OPDeviceLoop__
		<option value="__OPDeviceCD__" __OP2DeviceSelected__ >__OPKataban__ (__OPDeviceName__)</option>
		__OPDeviceLoop__
		</select>
		\<input type="text" name="wOPPrice2" value="__wOPPrice2__" size="6"></td></tr>

<tr><td colspan="3">
		<select name="wOP3DeviceCD">
		<option value="0">-</option>
		__OPDeviceLoop__
		<option value="__OPDeviceCD__" __OP3DeviceSelected__ >__OPKataban__ (__OPDeviceName__)</option>
		__OPDeviceLoop__
		</select>
		\<input type="text" name="wOPPrice3" value="__wOPPrice3__" size="6"></td></tr>

<tr><td colspan="3">
		<select name="wOP4DeviceCD">
		<option value="0">-</option>
		__OPDeviceLoop__
		<option value="__OPDeviceCD__" __OP4DeviceSelected__ >__OPKataban__ (__OPDeviceName__)</option>
		__OPDeviceLoop__
		</select>
		\<input type="text" name="wOPPrice4" value="__wOPPrice4__" size="6"></td></tr>

<tr><td bgcolor="lemonchiffon">★オプション支払方法</td>
	<td colspan="3">
		<select name=wShiharai>
		<option value="">-</option>
		<option value="現金" __Shiharai1Selected__>現金</option>
		<option value="振込" __Shiharai2Selected__>振込</option>
		<option value="現金 or 振込選択(ヒアリング依頼)" __Shiharai3Selected__>現金 or 振込選択(ヒアリング依頼)</option>
		</select></td></tr>

<tr><td bgcolor="lemonchiffon">★1日の基本班体制数</td>
	<td colspan="3"><input type="text" name="wHansu" value="__wHansu__"> 班</td></tr>

<tr><td bgcolor="lemonchiffon">★1戸あたりの標準作業時間</td>
	<td colspan="3"><input type="text" name="wConstTime" value="__wConstTime__" > 分</td></tr>

<tr><td bgcolor="lemonchiffon">★住戸工事期間中の休工日</td>
	<td colspan="3"><input type="text" name="wKyukoDate" value="__wKyukoDate__"></td></tr>

<tr><td bgcolor="lemonchiffon">★工事期間中の集玄開錠方法</td>
	<td colspan="3">
		<input type="checkbox" name="wKaijyo[]" value="鍵" __Kaijyo1Checked__>鍵
		<input type="checkbox" name="wKaijyo[]" value="仮暗証番号" __Kaijyo2Checked__>仮暗証番号
		<input type="checkbox" name="wKaijyo[]" value="暗証番号" __Kaijyo3Checked__>暗証番号
		<input type="checkbox" name="wKaijyo[]" value="工事期間中は終日開放" __Kaijyo4Checked__>工事期間中は終日開放
	</td></tr>

<tr><td bgcolor="lemonchiffon">★居室端末の移動対応</td>
	<td colspan="3">
		<select name="wIsetu">
		<option value="">-</option>
		<option value="しない" __Isetu1Selected__>しない</option>
		<option value="有料にて対応" __Isetu2Selected__>有料にて対応</option>
		<option value="無料対応" __Isetu3Selected__>無料対応</option>
		<option value="アイホン担当者に確認" __Isetu4Selected__>アイホン担当者に確認</option>
		</select></td></tr>

-->

<!--1行目
<tr><td bgcolor="lightgrey" colspan="5">7．写真撮影方法  工事写真管理アプリ利用時のみご記入ください。</td></tr>

<tr><td bgcolor="lightgrey" rowspan="17"></td><td bgcolor="lemonchiffon">写真台帳提供日</td>
	<td><input type="text" name="wPhotoTekyoDate" id="PhotoTekyoDate" value="__wPhotoTekyoDate__" size="10"></td>
	<td colspan="2" bgcolor="lightyellow">写真台帳の提供日(以降の写真はアイホンメンテ)</td></tr>

<tr><td bgcolor="lemonchiffon">★台帳選択</td>
	<td><select name="wPhotoPattern">
		<option value="">-</option>
		<option value="a-1" __PhotoPattern1Selected__>a-1</option>
		<option value="a-2" __PhotoPattern2Selected__>a-2</option>
		<option value="b-1" __PhotoPattern3Selected__>b-1</option>
		<option value="b-2" __PhotoPattern4Selected__>b-2</option>
		</select></td>
	<td colspan="2" bgcolor="lightyellow">写真台帳【選択】シートより確認</td></tr>

<tr><td bgcolor="gainsboro">専有部</td>
	<td bgcolor="gainsboro">機器名　</td>
	<td colspan="2" bgcolor="gainsboro">撮影シーン</td></tr>
<tr><td bgcolor="lemonchiffon">★項目1</td>
	<td><select name="wPhotoSenyu1">
		<option value="1" __PhotoSenyu11Selected__>居室親機</option>
		<option value="2" __PhotoSenyu12Selected__>住宅情報盤</option>
		<option value="3" __PhotoSenyu13Selected__>玄関子機</option>
		<option value="0">-</option>
		</select></td>
	<td colspan="2">
		<select name="wPSScene1">
		<option value="1" __PSScene11Selected__>施工前/施工後</option>
		<option value="2" __PSScene12Selected__>施工前/施工中/施工後</option>
		<option value="3" __PSScene13Selected__>施工後のみ</option>
		<option value="0">-</option>
		</select></td></tr>

<tr><td bgcolor="lemonchiffon">★項目2</td>
	<td><select name="wPhotoSenyu2">
		<option value="1" __PhotoSenyu21Selected__>居室親機</option>
		<option value="2" __PhotoSenyu22Selected__>住宅情報盤</option>
		<option value="3" __PhotoSenyu23Selected__>玄関子機</option>
		<option value="0">-</option>
		</select></td>
	<td colspan="2"><select name="wPSScene2">
		<option value="1" __PSScene21Selected__>施工前/施工後</option>
		<option value="2" __PSScene22Selected__>施工前/施工中/施工後</option>
		<option value="3" __PSScene23Selected__>施工後のみ</option>
		<option value="0">-</option>
		</select></td></tr>

<tr><td bgcolor="lemonchiffon">★項目3</td>
	<td><select name="wPhotoSenyu3">
		<option value="0">-</option>
		<option value="1" __PhotoSenyu31Selected__>居室親機</option>
		<option value="2" __PhotoSenyu32Selected__>住宅情報盤</option>
		<option value="3" __PhotoSenyu33Selected__>玄関子機</option>
		</select></td>
	<td colspan="2"><select name="wPSScene3">
		<option value="0">-</option>
		<option value="1" __PSScene31Selected__>施工前/施工後</option>
		<option value="2" __PSScene32Selected__>施工前/施工中/施工後</option>
		<option value="3" __PSScene33Selected__>施工後のみ</option>
		</select></td></tr>

<tr><td bgcolor="lemonchiffon">★項目4</td>
	<td><select name="wPhotoSenyu4">
		<option value="0">-</option>
		<option value="1" __PhotoSenyu41Selected__>居室親機</option>
		<option value="2" __PhotoSenyu42Selected__>住宅情報盤</option>
		<option value="3" __PhotoSenyu43Selected__>玄関子機</option>
		</select></td>
	<td colspan="2"><select name="wPSScene4">
		<option value="0">-</option>
		<option value="1" __PSScene41Selected__>施工前/施工後</option>
		<option value="2" __PSScene42Selected__>施工前/施工中/施工後</option>
		<option value="3" __PSScene43Selected__>施工後のみ</option>
		</select></td></tr>

<tr><td bgcolor="gainsboro">共用部</td>
	<td bgcolor="gainsboro">機器名</td>
	<td colspan="2" bgcolor="gainsboro">撮影シーン</td></tr>
<tr><td bgcolor="lemonchiffon">★項目1</td>
	<td><select name="wPhotoKyoyo1">
		<option value="1" __PhotoKyoyo11Selected__>制御装置</option>
		<option value="2" __PhotoKyoyo12Selected__>管理室親機</option>
		<option value="3" __PhotoKyoyo13Selected__>集合玄関機</option>
		<option value="4" __PhotoKyoyo14Selected__>映像増幅器</option>
		<option value="0">-</option>
		</select></td>
	<td colspan="2"><select name="wPKScene1">
		<option value="1" __PKScene11Selected__>施工前/施工後</option>
		<option value="2" __PKScene12Selected__>施工前/施工中/施工後</option>
		<option value="3" __PKScene13Selected__>施工後のみ</option>
		<option value="0">-</option>
		</select></td></tr>

<tr><td bgcolor="lemonchiffon">★項目2</td>
	<td><select name="wPhotoKyoyo2">
		<option value="1" __PhotoKyoyo21Selected__>制御装置</option>
		<option value="2" __PhotoKyoyo22Selected__>管理室親機</option>
		<option value="3" __PhotoKyoyo23Selected__>集合玄関機</option>
		<option value="4" __PhotoKyoyo24Selected__>映像増幅器</option>
		<option value="0">-</option>
		</select></td>
	<td colspan="2"><select name="wPKScene2">
		<option value="1" __PKScene21Selected__>施工前/施工後</option>
		<option value="2" __PKScene22Selected__>施工前/施工中/施工後</option>
		<option value="3" __PKScene23Selected__>施工後のみ</option>
		<option value="0">-</option>
		</select></td></tr>

<tr><td bgcolor="lemonchiffon">★項目3</td>
	<td><select name="wPhotoKyoyo3">
		<option value="1" __PhotoKyoyo31Selected__>制御装置</option>
		<option value="2" __PhotoKyoyo32Selected__>管理室親機</option>
		<option value="3" __PhotoKyoyo33Selected__>集合玄関機</option>
		<option value="4" __PhotoKyoyo34Selected__>映像増幅器</option>
		<option value="0">-</option>
		</select></td>
	<td colspan="2"><select name="wPKScene3">
		<option value="1" __PKScene31Selected__>施工前/施工後</option>
		<option value="2" __PKScene32Selected__>施工前/施工中/施工後</option>
		<option value="3" __PKScene33Selected__>施工後のみ</option>
		<option value="0">-</option>
		</select></td></tr>

<tr><td bgcolor="lemonchiffon">★項目4</td>
	<td><select name="wPhotoKyoyo4">
		<option value="0">-</option>
		<option value="1" __PhotoKyoyo41Selected__>制御装置</option>
		<option value="2" __PhotoKyoyo42Selected__>管理室親機</option>
		<option value="3" __PhotoKyoyo43Selected__>集合玄関機</option>
		<option value="4" __PhotoKyoyo44Selected__>映像増幅器</option>
	</select></td>
	<td colspan="2"><select name="wPKScene4">
		<option value="0">-</option>
		<option value="1" __PKScene41Selected__>施工前/施工後</option>
		<option value="2" __PKScene42Selected__>施工前/施工中/施工後</option>
		<option value="3" __PKScene43Selected__>施工後のみ</option>
		</select></td></tr>

<tr><td bgcolor="lemonchiffon">★項目5</td>
	<td><input type="text" name="wPhotoKyoyo5" value="__wPhotoKyoyo5__"></td>
	<td colspan="2"><select name="wPKScene5">
		<option value="0">-</option>
		<option value="1" __PKScene51Selected__>施工前/施工後</option>
		<option value="2" __PKScene52Selected__>施工前/施工中/施工後</option>
		<option value="3" __PKScene53Selected__>施工後のみ</option>
		</select></td></tr>

<tr><td bgcolor="lemonchiffon">★項目6</td>
	<td><input type="text" name="wPhotoKyoyo6" value="__wPhotoKyoyo6__"></td>
	<td colspan="2"><select name="wPKScene6">
		<option value="0">-</option>
		<option value="1" __PKScene61Selected__>施工前/施工後</option>
		<option value="2" __PKScene62Selected__>施工前/施工中/施工後</option>
		<option value="3" __PKScene63Selected__>施工後のみ</option>
		</select></td></tr>

<tr><td bgcolor="lemonchiffon">★項目7</td>
	<td><input type="text" name="wPhotoKyoyo7" value="__wPhotoKyoyo7__"></td>
	<td colspan="2"><select name="wPKScene7">
		<option value="0">-</option>
		<option value="1" __PKScene71Selected__>施工前/施工後</option>
		<option value="2" __PKScene72Selected__>施工前/施工中/施工後</option>
		<option value="3" __PKScene73Selected__>施工後のみ</option>
		</select></td></tr>

<tr><td bgcolor="lemonchiffon">★項目8</td>
	<td><input type="text" name="wPhotoKyoyo8" value="__wPhotoKyoyo8__"></td>
	<td colspan="2"><select name="wPKScene8">
		<option value="0">-</option>
		<option value="1" __PKScene81Selected__>施工前/施工後</option>
		<option value="2" __PKScene82Selected__>施工前/施工中/施工後</option>
		<option value="3" __PKScene83Selected__>施工後のみ</option>
		</select></td></tr>

<tr><td bgcolor="lemonchiffon">★項目9</td>
	<td><input type="text" name="wPhotoKyoyo9" value="__wPhotoKyoyo9__"></td>
	<td colspan="2"><select name="wPKScene9">
		<option value="0">-</option>
		<option value="1" __PKScene91Selected__>施工前/施工後</option>
		<option value="2" __PKScene92Selected__>施工前/施工中/施工後</option>
		<option value="3" __PKScene93Selected__>施工後のみ</option>
		</select></td></tr>


<tr><td bgcolor="lightgrey" colspan="5">★8．その他（注意事項等）</td></tr>
<tr><td bgcolor="lightgrey"></td><td colspan="4"><textarea name="wNotes" cols="100" rows="7">__wNotes__</textarea></td></tr>

<tr><td bgcolor="lightgrey" colspan="5"></td></tr>


<tr><td colspan="2" bgcolor="lightgrey">関連資料<br>（住人様ご案内資料、仮日程表など）</td>
	<td colspan="3">
		※5つ以上ファイルがある場合は圧縮してzip形式にしていただき、登録してください。<br>
		<span style="background-color:yellow" >
		<font color="red"><b>※1ファイルのサイズが10M(=10485760 bytes)を超えると登録できません。</b></font></span><br>
		<a href="/kotei/upfile/iraifile_tmp/yoyakuirai_file.pdf" target="_blank">▼資料サイズが大きい場合</a><br>

		<input type="hidden" name="MAX_FILE_SIZE" value="10485760" />

		<table>⑩-->
<!--		<tr><td>
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
		</td><td>
			<div id="tempfileresult"></div>
		</table>
		<div id="tempfilesizeresult" style="display: none;"></div>資料サイズ結果-->

<!--		<br>
		<table border=1 style="width:100%">
			<tr><td colspan=4 bgcolor="gainsboro">登録済ファイル</td></tr>
			__IraiFileLoop__
				<tr bgcolor="lightyellow">
					<td style="width:30px">__FileNo__</td>
					<td>・__IraiFile__</td>
					<td style="width:160px"><font size="2">__Created__</font></td> 
					<td style="width:60px"><input type="button" value="削除" class="button" onclick="javascript:moveWithWorkAndIraiFileCD('s_489.php',  __IraiFileCD__ ,2 )"></td>
				</tr>
			__IraiFileLoop__
		</table>
	</td>
</tr>
-->

<!--施工会社様向け依頼事項Start

<tr><td colspan="5">
	<br><b>施工業者指示内容</b><br>
	施工業者様へ下記内容を予約センターから通知いたします。
	</td></tr>
-->
<!--1行目-->
<!--
<tr><td bgcolor="palegreen" colspan="5">施工業者指示内容</td></tr>

<tr><td rowspan="10"  bgcolor="palegreen"></td>
	<td bgcolor="palegreen" colspan="1">リニューアル方式</td>
	<td colspan="3">
		<input type="radio" name="wRenewalType" __RenewalTypeChecked1__ value="1">既設システム停止でのRN<br>
		<input type="radio" name="wRenewalType" __RenewalTypeChecked2__ value="2">新旧並行稼働でのRN
	</td></tr>

<tr><td bgcolor="palegreen" colspan="1">配線</td>
	<td colspan="3" >
		<input type="radio" name="wHaisenType" __HaisenTypeChecked1__ value="1">既設配線流用<br>
		<input type="radio" name="wHaisenType" __HaisenTypeChecked2__ value="2">既設配線抜き替え（大幅）<br>
		<input type="radio" name="wHaisenType" __HaisenTypeChecked3__ value="3">既設配線抜き替え（一部）<br>
		<input type="radio" name="wHaisenType" __HaisenTypeChecked4__ value="4">新規露出配線
	</td></tr>


<tr><td bgcolor="palegreen" colspan="1">管理室呼び出し</td>
	<td colspan="3">
		<input type="radio" name="wKanrishituCall" __KanrishituCallChecked1__ value="1">表示なし<br>
		<input type="radio" name="wKanrishituCall" __KanrishituCallChecked2__ value="2">常時表示<br>
		<input type="radio" name="wKanrishituCall" __KanrishituCallChecked3__ value="3">メモリ時のみ
	</td></tr>



<tr><td bgcolor="palegreen" colspan="1">写真撮影件数（共用部）</td>
	<td colspan="3">
		<input type="radio" name="wPicKSu" __PicKSuChecked1__ value="1">全箇所<br>
		<input type="radio" name="wPicKSu" __PicKSuChecked2__ value="2">主要機器のみ<br>
		<input type="radio" name="wPicKSu" __PicKSuChecked3__ value="3">不要
	</td></tr>

<tr><td bgcolor="palegreen" colspan="1">写真撮影枚数（共用部）</td>
	<td colspan="3">
		<input type="radio" name="wPicKType" __PicKTypeChecked1__ value="1">作業前・中・後<br>
		<input type="radio" name="wPicKType" __PicKTypeChecked2__ value="2">作業前・後<br>
		<input type="radio" name="wPicKType" __PicKTypeChecked3__ value="3">不要
	</td></tr>

<tr><td bgcolor="palegreen" colspan="1">写真撮影件数（専有部）</td>
	<td colspan="3">
		<input type="radio" name="wPicSu" __PicSuChecked1__ value="1">全戸<br>
		<input type="radio" name="wPicSu" __PicSuChecked2__ value="2">抜粋<br>
		<input type="radio" name="wPicSu" __PicSuChecked3__ value="3">不要
	</td></tr>

<tr><td bgcolor="palegreen" colspan="1">写真撮影枚数（専有部）</td>
	<td colspan="3">
		<input type="radio" name="wPicType" __PicTypeChecked1__ value="1">作業前・中・後<br>
		<input type="radio" name="wPicType" __PicTypeChecked2__ value="2">作業前・後<br>
		<input type="radio" name="wPicType" __PicTypeChecked3__ value="3">不要
	</td></tr>

<tr><td bgcolor="palegreen" colspan="1">消防申請</td>
	<td colspan="3">
		<input type="radio" name="wShobo" __ShoboChecked1__ value="1">必要<br>
		<input type="radio" name="wShobo" __ShoboChecked2__ value="2">不要
	</td></tr>

<tr><td bgcolor="palegreen" colspan="1">工事完了後の提出書類</td>
	<td colspan="3">
		<input type="checkbox" name="wTeishutuDoc[]" __TeishutuDocChecked1__ value="1">完了確認書<br>
		<input type="checkbox" name="wTeishutuDoc[]" __TeishutuDocChecked2__ value="2">工事写真台帳<br>
		<input type="checkbox" name="wTeishutuDoc[]" __TeishutuDocChecked3__ value="3">系統図
	</td></tr>


<tr><td bgcolor="palegreen" colspan="1">その他指示事項</td>
	<td colspan="3"><textarea name="wSonotaShiji" cols="80" rows="7">__wSonotaShiji__</textarea></td></tr>-->
</table>



<!--施工会社様向け依頼事項End-->
<!--
<font color="red" size=4><b>※「㈱、Ⅰ、Ⅱ、①、②」等の環境依存文字は文字化けします。<br>
							　「（株）、I、II、(1)、(2)」に変更お願いします。</b></font>

<br><br>
<input type="hidden" name="rKey" value="__rKey__">
<input type="hidden" name="editBukkenCD" value="__editBukkenCD__">
<input type="hidden" name="wIraiRenkeiStatus" value="__wIraiRenkeiStatus__" >
<input type="hidden" name="editIraiRenkeiCD" value="__editIraiRenkeiCD__" > 
<input type="hidden" name="wBunjyo" value="__wBunjyo__" > 
<input type="hidden" name="wKosu" value="__wKosu__" > 
<input type="hidden" name="wKanriKinmu" value="__wKanriKinmu__" > 
->

<!-- ファイル名取得のため
<input type="submit" value="  内容確認  " >-->
<!--
<input type="hidden" name="hiddenfilenames" value="">
<input type="button" value="内容確認" onclick="javascript:FilesSubmit(this.form);">
</form>
<br>
-->
<!--
<hr size="__HRSize__" color="__HRColor__">
<a href="s_menu.php__QUERY__&editBukkenCD=__editBukkenCD__ ">メニュー</a><br><br>
<a href="s_search.php__QUERY__">トップ</a><br>
<hr size="__HRSize__" color="__HRColor__">
__SFooter__
__SCop-->

</body>
</html>


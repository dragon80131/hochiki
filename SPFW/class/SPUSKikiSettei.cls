<?php
/**
 * Kojiクラス
 *
 * メニューマスタを扱うクラスです。
 *
 * @package 
 * @access  public
 * @author  MasahitoSAMEKAWA <masahito@souppot.jp>
 * @create  2003/01/31
 * @version 0.1
 **/

// 依存
include_once _CLS_DIR . "SPFWLog.cls";

class KikiSettei {
	/**
	 * データベースクラスのインスタンス
	 */
	var $myDB;
	/**
	 * レコードカウント
	 */
	var $RecCnt;
	/**
	 * クエリ実行結果セット
	 */
	var $tmprtn;
	/**
	 * 対象テーブル名称
	 */
	var $TableName;
	/**
	 * メニューコード
	 * 
	 * 連番
	 */
	var $KikiSetteiCD;
	var $KikiCD;
	 var $ClientCD;
	 var $BukkenCD;
	 var $ShugoSettei1; 	#受話音量
	 var $ShugoSettei2; 	#画面の明るさ
	 var $ShugoSettei3; 	#エントランスカメラの接続
	 var $ShugoSettei4; 	#英語切替機能
	 var $ShugoSettei5; 	#サービスメニュー
	 var $ShugoSettei6; 	#管理室呼出機能
	 var $ShugoSettei7; 	#自画像カメラ映像の表示
	 var $ShugoSettei8; 	#ズーム位置プリセット　左右
	 var $ShugoSettei9; 	#ズーム位置プリセット　値
	 var $ShugoSettei10; 	#ズーム位置プリセット　上下
	 var $ShugoSettei11;	#ズーム位置プリセット　値
	 var $ShugoSettei12;	#お知らせの表示
	 var $ShugoSettei13;	#お知らせの表示通知音鳴動回数
	 var $ShugoSettei14;	#お知らせの表示時間
	 var $ShugoSettei15;	#電気錠の接続
	 var $ShugoSettei16;	#電気錠の解錠通知音鳴動回数
	 var $ShugoSettei17;	#通知音の音量
	 var $ShugoSettei18;	#キー入力音の鳴動
	 var $ShugoSettei19;	#システム日時変更機能
	 var $ShugoSettei20;	#カメラ起動時の逆光補正
	 var $ShugoSettei21;	#居室の自己診断

							#呼出機能
	 var $OyakiSettei1;			#管理室親機呼出
	 var $OyakiSettei2;			#集合玄関機呼出
	 var $OyakiSettei3;			#着信履歴の表示
							#放送機能
	 var $OyakiSettei4;			#個別放送
	 var $OyakiSettei5;			#グループ放送
	 var $OyakiSettei6;			#フロア放送
	 var $OyakiSettei7;			#一斉放送
							#音声メッセージ機能
	 var $OyakiSettei8;			#個別配信
	 var $OyakiSettei9;			#グループ配信
	 var $OyakiSettei10;		#フロア配信
	 var $OyakiSettei11;		#一斉配信
							#画像メッセージ機能
	 var $OyakiSettei12;		#個別配信
	 var $OyakiSettei13;		#グループ配信
	 var $OyakiSettei14;		#フロア配信
	 var $OyakiSettei15;		#一斉配信
							#居室お知らせ機能
	 var $OyakiSettei16;		#メモリーメッセージ
	 var $OyakiSettei17;		#預かり物情報
	 var $OyakiSettei18;		#管理室立ち寄り情報
	 var $OyakiSettei19;		#居室への通知停止時間帯の変更
							#休止モード
	 var $OyakiSettei20;		#休止モード機能
	 var $OyakiSettei21;		#休止モード解除の操作回数
							#警報音機能
	 var $OyakiSettei22;		#玄関子機での警報鳴動
	 var $OyakiSettei23;		#警報音ダンダントーン
	 var $OyakiSettei24;		#警報音声の種類
	 var $OyakiSettei25;		#警報音抑止時間帯・音量の変更

							#警報時操作機能
	 var $OyakiSettei26;		#警報音消音機能
	 var $OyakiSettei27;		#居室の警報音停止機能
	 var $OyakiSettei28;		#緊急対応機能
	 var $OyakiSettei29;		#生活異変警報の居室復旧
							#警報音停止機能
	 var $OyakiSettei30;		#警報音自動停止タイマー
	 var $OyakiSettei31;		#警報音自動停止タイマー　秒数
	 var $OyakiSettei32;		#外部警報音停止スイッチ操作可能タイマー
	 var $OyakiSettei33;		#外部警報音停止スイッチ操作可能タイマー　秒数
							#解錠用暗証番号機能
	 var $OyakiSettei34;		#共通暗証番号の変更
	 var $OyakiSettei35;		#個別暗証番号の変更
	 var $OyakiSettei36;		#暗証番号有効時間帯の変更
							#連続・待受中解錠機能
	 var $OyakiSettei37;		#連続解錠時間帯の変更
	 var $OyakiSettei38;		#待受中解錠機能
							#居室制御機能
	 var $OyakiSettei39;		#居室のグループ編成
	 var $OyakiSettei40;		#居室の登録・解除
	 var $OyakiSettei41;		#居室の録画録音消去
	 var $OyakiSettei42;	#管理室親機名称
	 var $OyakiSettei43;	#特殊機能#居室の自己診断

							#移報端子割付
	 var $OyakiSettei44;		#T1
	 var $OyakiSettei45;		#T2
	 var $OyakiSettei46;		#T3
	 var $OyakiSettei47;		#T4
	 var $OyakiSettei48;		#T5
	 var $OyakiSettei49;		#T6
	 var $OyakiSettei50;		#T7
	 var $OyakiSettei51;		#T8


	 var $JutakuMenuSettei1;
	 var $JutakuMenuSettei2;
	 var $JutakuMenuSettei3;
	 var $JutakuMenuSettei4;
	 var $JutakuMenuSettei5;
	 var $JutakuMenuSettei6;
	 var $JutakuMenuSettei7;
	 var $JutakuMenuSettei8;
	 var $JutakuMenuSettei9;
	 var $JutakuMenuSettei10;
	 var $JutakuMenuSettei11;
	 var $JutakuMenuSettei12;

	 var $JutakuServiceTSettei1;
	 var $JutakuServiceTSettei2;
	 var $JutakuServiceTSettei3;
	 var $JutakuServiceTSettei4;
	 var $JutakuServiceTSettei5;
	 var $JutakuServiceTSettei6;
	 var $JutakuServiceTSettei7;
	 var $JutakuServiceTSettei8;


	 var $JutakuServiceSettei1;
	 var $JutakuServiceSettei2;
	 var $JutakuServiceSettei3;
	 var $JutakuServiceSettei4;
	 var $JutakuServiceSettei5;
	 var $JutakuServiceSettei6;
	 var $JutakuServiceSettei7;
	 var $JutakuServiceSettei8;
	 var $JutakuServiceSettei9;
	 var $JutakuServiceSettei10;
	 var $JutakuServiceSettei11;
	 var $JutakuServiceSettei12;
	 var $JutakuServiceSettei13;
	 var $JutakuServiceSettei14;
	 var $JutakuServiceSettei15;
	 var $JutakuServiceSettei16;
	 var $JutakuServiceSettei17;
	 var $JutakuServiceSettei18;
	 var $JutakuServiceSettei19;
	 var $JutakuServiceSettei20;
	 var $JutakuServiceSettei21;
	 var $JutakuServiceSettei22;
	 var $JutakuServiceSettei23;
	 var $JutakuServiceSettei24;
	 var $JutakuServiceSettei25;
	 var $JutakuServiceSettei26;
	 var $JutakuServiceSettei27;
	 var $JutakuServiceSettei28;
	 var $JutakuServiceSettei29;
	 var $JutakuServiceSettei30;
	 var $JutakuServiceSettei31;
	 var $JutakuServiceSettei32;
	 var $JutakuServiceSettei33;
	 var $JutakuServiceSettei34;
	 var $JutakuServiceSettei35;
	 var $JutakuServiceSettei36;
	 var $JutakuServiceSettei37;
	 var $JutakuServiceSettei38;
	 var $JutakuServiceSettei39;
	 var $JutakuServiceSettei40;
	 var $JutakuServiceSettei41;
	 var $JutakuServiceSettei42;
	 var $JutakuServiceSettei43;
	 var $JutakuServiceSettei44;
	 var $JutakuServiceSettei45;
	 var $JutakuServiceSettei46;
	 var $JutakuServiceSettei47;
	 var $JutakuServiceSettei48;
	 var $JutakuServiceSettei49;
	 var $JutakuServiceSettei50;
	 var $JutakuServiceSettei51;
	 var $JutakuServiceSettei52;
	 var $JutakuServiceSettei53;
	 var $JutakuServiceSettei54;
	 var $JutakuServiceSettei55;

	 var $JutakuSistemSettei1;
	 var $JutakuSistemSettei2;
	 var $JutakuSistemSettei3;
	 var $JutakuSistemSettei4;
	 var $JutakuSistemSettei5;
	 var $JutakuSistemSettei6;
	 var $JutakuSistemSettei7;
	 var $JutakuSistemSettei8;
	 var $JutakuSistemSettei9;
	 var $JutakuSistemSettei10;

	 var $JutakuKeihouSettei1;
	 var $JutakuKeihouSettei2;
	 var $JutakuKeihouSettei3;
	 var $JutakuKeihouSettei4;
	 var $JutakuKeihouSettei5;

	 var $JutakuKanriSettei1;
	 var $JutakuKanriSettei2;
	 var $JutakuKanriSettei3;
	 var $JutakuKanriSettei4;
	 var $JutakuKanriSettei5;
	 var $JutakuKanriSettei6;
	 var $JutakuKanriSettei7;
	 var $JutakuKanriSettei8;
	 var $JutakuKanriSettei9;
	 var $JutakuKanriSettei10;
	 var $JutakuKanriSettei11;
	 var $JutakuKanriSettei12;
	 var $JutakuKanriSettei13;
	 var $JutakuKanriSettei14;
	 var $JutakuKanriSettei15;
	 var $JutakuKanriSettei16;
	 var $JutakuKanriSettei17;
	 var $JutakuKanriSettei18;
	 var $JutakuKanriSettei19;
	 var $JutakuKanriSettei20;
	 var $JutakuKanriSettei21;
	 var $JutakuKanriSettei22;

	 var $JutakuBouhanSettei1;
	 var $JutakuBouhanSettei2;
	 var $JutakuBouhanSettei3;
	 var $JutakuBouhanSettei4;
	 var $JutakuBouhanSettei5;
	 var $JutakuBouhanSettei6;
	 var $JutakuBouhanSettei7;
	 var $JutakuBouhanSettei8;
	 var $JutakuBouhanSettei9;
	 var $JutakuBouhanSettei10;
	 var $JutakuBouhanSettei11;

	 var $GenkanKokiSettei1;
	 var $GenkanKokiSettei2;
	 var $GenkanKokiSettei3;

 	 var $DenkijoSettei1;


	 var $SonotaSettei1;
	 var $SonotaSettei2;
	 var $SonotaSettei3;
	 var $SonotaSettei4;
	 var $SonotaSettei5;


	/**
	 * 無効フラグ
	 */
	var $MukouFlg;
	/**
	 * 作成日時
	 */
	var $Created;
	/**
	 * 作成者
	 */
	var $Creator;
	/**
	 * 更新日時
	 */
	var $Updated;
	/**
	 * 更新者
	 */
	var $Updater;

	function __construct($myDB = "",$KikiSetteiCD = -1) {
		// ログ準備
		$myLog = new SPFWLog();

		$this->Err = "";
		
		if ($myDB == "") {
			$this->Err = "データベースへのコネクションがありません";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		// 整数変換
		settype($KikiSetteiCD,"integer");
		// 整数チェック
		if (!is_long($KikiSetteiCD) or $KikiSetteiCD == 0) {
			$this->Err = "ユーザーコード が不正です";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}

		$this->myDB = $myDB;
		$this->TableName = "tKikiSetteiF";
		$this->KikiSetteiCD = $KikiSetteiCD;

		return TRUE;
	}

	function initialize(){
		$this->Err = "";
		
		$this->KikiSetteiCD = "";

	$this->KikiCD = "";
	  $this->ClientCD = "";
	  $this->BukkenCD = "";
	for($i=1 ;$i<22 ;$i++){
		$this->{"ShugoSettei".$i} = "";
	}
	for($i=1 ; $i < 52 ; $i++){
		$this->{"OyakiSettei".$i} = "";
	}
	for($i=1 ; $i < 13 ; $i++){
		$this->{"JutakuMenuSettei".$i} = "";
	}
	for($i=1 ; $i < 9 ; $i++){
		$this->{"JutakuServiceTSettei".$i} = "";
	}

	for($i=1 ; $i < 56 ; $i++){
		$this->{"JutakuServiceSettei".$i} = "";
	}
	for($i=1 ; $i < 11 ; $i++){
		$this->{"JutakuSistemSettei".$i} = "";
	}
	for($i=1 ; $i < 6 ; $i++){
		$this->{"JutakuKeihouSettei".$i} = "";
	}
	for($i=1 ; $i < 23 ; $i++){
		$this->{"JutakuKanriSettei".$i} = "";
	}
	for($i=1 ; $i < 12 ; $i++){
		$this->{"JutakuBouhanSettei".$i} = "";
	}
	
	for($i=1 ; $i < 4 ; $i++){
		$this->{"GenkanKokiSettei".$i} = "";
	}
	# $this->GenkanKokiSettei1 = "";
	
	$this->DenkijoSettei1 = "";

	for($i=0 ; $i < 6 ; $i++){
		$this->{"SonotaSettei".$i} = "";
	}
		$this->MukouFlg = "";
		$this->Created = "";
		$this->Creator = "";
		$this->Updated = "";
		$this->Updater = "";
	}

	//抽出
	function executeSelect($WhereStr = "", $OrderByStr = ""){
		// ログ準備
		$myLog = new SPFWLog();

		$this->Err = "";

		//設定
		if ($this->myDB == "") {
			$this->Err = "データベースへのコネクションがありません";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}
		
		// ページプロパティ取得
		$sqlMoji = "select ";
		$sqlMoji .= "KikiSetteiCD";

		$sqlMoji .= ", KikiCD ";
		$sqlMoji .= ", ClientCD ";
		$sqlMoji .= ", BukkenCD ";

	for($i=1 ; $i<22 ; $i++){
		$sqlMoji .= ", ShugoSettei".$i;
	}
	for($i=1 ; $i<52 ; $i++){
		$sqlMoji .= ", OyakiSettei".$i;
	}
	for($i=1 ; $i<13 ;$i++){
		$sqlMoji .= ", JutakuMenuSettei".$i;
	}
	for($i=1 ; $i<9 ;$i++){
		$sqlMoji .= ", JutakuServiceTSettei".$i;
	}
	for($i=1 ; $i<56 ;$i++){
		$sqlMoji .= ", JutakuServiceSettei".$i;
	}
	for($i=1 ; $i<11 ; $i++){
		$sqlMoji .= ", JutakuSistemSettei".$i;
	}
	for($i=1 ; $i<6 ; $i++){
		$sqlMoji .= ", JutakuKeihouSettei".$i;
	}
	for($i=1 ; $i<23 ; $i++){
		$sqlMoji .= ", JutakuKanriSettei".$i;
	}
	for($i=1 ; $i<12 ; $i++){
		$sqlMoji .= ", JutakuBouhanSettei".$i;
	}
	for($i=1 ; $i<4 ; $i++){
		$sqlMoji .= ", GenkanKokiSettei".$i;
	}
	#$sqlMoji .= ", GenkanKokiSettei1";
	
	$sqlMoji .= ", DenkijoSettei1";

	for($i=1 ; $i<6 ; $i++){
		$sqlMoji .= ", SonotaSettei".$i;
	}

		$sqlMoji .= ", MukouFlg";
		$sqlMoji .= ", Created";
		$sqlMoji .= ", Creator";
		$sqlMoji .= ", Updated";
		$sqlMoji .= ", Updater";
		$sqlMoji .= " from $this->TableName";

		if($WhereStr != "")
			$sqlMoji = $sqlMoji . " where " . $WhereStr;

		if($OrderByStr != "")
			$sqlMoji = $sqlMoji . " order by " . $OrderByStr;

		$rtn = $this->myDB->executeQuery($sqlMoji);
		
		if (!$rtn){
			$this->Err = "レコード取得に失敗しました";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}
		else{
			$this->RecCnt = $this->myDB->getNumberOfRows($rtn);
			if ($this->RecCnt == 1){
				$this->tmprtn = $rtn;
				$this->getDataSet(0);
			}
			else if($this->RecCnt > 1){
				$this->tmprtn = $rtn;
			}
		}

		return TRUE;
	}

	//タプル取得
	function getDataSet($RowNo){
		if($RowNo >= $this->RecCnt){
			$this->Err = "行番号が不正です";
			return FALSE;
		}
		else{
			list(
				$this->KikiSetteiCD,

			    $this->KikiCD,
			    $this->ClientCD,
			    $this->BukkenCD,
			
			    $this->ShugoSettei1,
			    $this->ShugoSettei2,
			    $this->ShugoSettei3,
			    $this->ShugoSettei4,
			    $this->ShugoSettei5,
			    $this->ShugoSettei6,
			    $this->ShugoSettei7,
			    $this->ShugoSettei8,
			    $this->ShugoSettei9,
			    $this->ShugoSettei10,
			    $this->ShugoSettei11,
			    $this->ShugoSettei12,
			    $this->ShugoSettei13,
			    $this->ShugoSettei14,
			    $this->ShugoSettei15,
			    $this->ShugoSettei16,
			    $this->ShugoSettei17,
			    $this->ShugoSettei18,
			    $this->ShugoSettei19,
			    $this->ShugoSettei20,
			    $this->ShugoSettei21,
			    $this->OyakiSettei1,
			    $this->OyakiSettei2,
			    $this->OyakiSettei3,
			    $this->OyakiSettei4,
			    $this->OyakiSettei5,
			    $this->OyakiSettei6,
			    $this->OyakiSettei7,
			    $this->OyakiSettei8,
			    $this->OyakiSettei9,
			    $this->OyakiSettei10,
			    $this->OyakiSettei11,
			    $this->OyakiSettei12,
			    $this->OyakiSettei13,
			    $this->OyakiSettei14,
			    $this->OyakiSettei15,
			    $this->OyakiSettei16,
			    $this->OyakiSettei17,
			    $this->OyakiSettei18,
			    $this->OyakiSettei19,
			    $this->OyakiSettei20,
			    $this->OyakiSettei21,
			    $this->OyakiSettei22,
			    $this->OyakiSettei23,
			    $this->OyakiSettei24,
			    $this->OyakiSettei25,
			    $this->OyakiSettei26,
			    $this->OyakiSettei27,
			    $this->OyakiSettei28,
			    $this->OyakiSettei29,
			    $this->OyakiSettei30,
			    $this->OyakiSettei31,
			    $this->OyakiSettei32,
			    $this->OyakiSettei33,
			    $this->OyakiSettei34,
			    $this->OyakiSettei35,
			    $this->OyakiSettei36,
			    $this->OyakiSettei37,
			    $this->OyakiSettei38,
			    $this->OyakiSettei39,
			    $this->OyakiSettei40,
			    $this->OyakiSettei41,
			    $this->OyakiSettei42,
			    $this->OyakiSettei43,
			    $this->OyakiSettei44,
			    $this->OyakiSettei45,
			    $this->OyakiSettei46,
			    $this->OyakiSettei47,
			    $this->OyakiSettei48,
			    $this->OyakiSettei49,
			    $this->OyakiSettei50,
			    $this->OyakiSettei51,
			    $this->JutakuMenuSettei1,
			    $this->JutakuMenuSettei2,
			    $this->JutakuMenuSettei3,
			    $this->JutakuMenuSettei4,
			    $this->JutakuMenuSettei5,
			    $this->JutakuMenuSettei6,
			    $this->JutakuMenuSettei7,
			    $this->JutakuMenuSettei8,
			    $this->JutakuMenuSettei9,
			    $this->JutakuMenuSettei10,
			    $this->JutakuMenuSettei11,
			    $this->JutakuMenuSettei12,
			    $this->JutakuServiceTSettei1,
			    $this->JutakuServiceTSettei2,
			    $this->JutakuServiceTSettei3,
			    $this->JutakuServiceTSettei4,
			    $this->JutakuServiceTSettei5,
			    $this->JutakuServiceTSettei6,
			    $this->JutakuServiceTSettei7,
			    $this->JutakuServiceTSettei8,
			    $this->JutakuServiceSettei1,
			    $this->JutakuServiceSettei2,
			    $this->JutakuServiceSettei3,
			    $this->JutakuServiceSettei4,
			    $this->JutakuServiceSettei5,
			    $this->JutakuServiceSettei6,
			    $this->JutakuServiceSettei7,
			    $this->JutakuServiceSettei8,
			    $this->JutakuServiceSettei9,
			    $this->JutakuServiceSettei10,
			    $this->JutakuServiceSettei11,
			    $this->JutakuServiceSettei12,
			    $this->JutakuServiceSettei13,
			    $this->JutakuServiceSettei14,
			    $this->JutakuServiceSettei15,
			    $this->JutakuServiceSettei16,
			    $this->JutakuServiceSettei17,
			    $this->JutakuServiceSettei18,
			    $this->JutakuServiceSettei19,
			    $this->JutakuServiceSettei20,
			    $this->JutakuServiceSettei21,
			    $this->JutakuServiceSettei22,
			    $this->JutakuServiceSettei23,
			    $this->JutakuServiceSettei24,
			    $this->JutakuServiceSettei25,
			    $this->JutakuServiceSettei26,
			    $this->JutakuServiceSettei27,
			    $this->JutakuServiceSettei28,
			    $this->JutakuServiceSettei29,
			    $this->JutakuServiceSettei30,
			    $this->JutakuServiceSettei31,
			    $this->JutakuServiceSettei32,
			    $this->JutakuServiceSettei33,
			    $this->JutakuServiceSettei34,
			    $this->JutakuServiceSettei35,
			    $this->JutakuServiceSettei36,
			    $this->JutakuServiceSettei37,
			    $this->JutakuServiceSettei38,
			    $this->JutakuServiceSettei39,
			    $this->JutakuServiceSettei40,
			    $this->JutakuServiceSettei41,
			    $this->JutakuServiceSettei42,
			    $this->JutakuServiceSettei43,
			    $this->JutakuServiceSettei44,
			    $this->JutakuServiceSettei45,
			    $this->JutakuServiceSettei46,
			    $this->JutakuServiceSettei47,
			    $this->JutakuServiceSettei48,
			    $this->JutakuServiceSettei49,
			    $this->JutakuServiceSettei50,
			    $this->JutakuServiceSettei51,
			    $this->JutakuServiceSettei52,
			    $this->JutakuServiceSettei53,
			    $this->JutakuServiceSettei54,
			    $this->JutakuServiceSettei55,

			    $this->JutakuSistemSettei1,
			    $this->JutakuSistemSettei2,
			    $this->JutakuSistemSettei3,
			    $this->JutakuSistemSettei4,
			    $this->JutakuSistemSettei5,
			    $this->JutakuSistemSettei6,
			    $this->JutakuSistemSettei7,
			    $this->JutakuSistemSettei8,
			    $this->JutakuSistemSettei9,
			    $this->JutakuSistemSettei10,
			    $this->JutakuKeihouSettei1,
			    $this->JutakuKeihouSettei2,
			    $this->JutakuKeihouSettei3,
			    $this->JutakuKeihouSettei4,
			    $this->JutakuKeihouSettei5,
			    $this->JutakuKanriSettei1,
			    $this->JutakuKanriSettei2,
			    $this->JutakuKanriSettei3,
			    $this->JutakuKanriSettei4,
			    $this->JutakuKanriSettei5,
			    $this->JutakuKanriSettei6,
			    $this->JutakuKanriSettei7,
			    $this->JutakuKanriSettei8,
			    $this->JutakuKanriSettei9,
			    $this->JutakuKanriSettei10,
			    $this->JutakuKanriSettei11,
			    $this->JutakuKanriSettei12,
			    $this->JutakuKanriSettei13,
			    $this->JutakuKanriSettei14,
			    $this->JutakuKanriSettei15,
			    $this->JutakuKanriSettei16,
			    $this->JutakuKanriSettei17,
			    $this->JutakuKanriSettei18,
			    $this->JutakuKanriSettei19,
			    $this->JutakuKanriSettei20,
			    $this->JutakuKanriSettei21,
			    $this->JutakuKanriSettei22,

			    $this->JutakuBouhanSettei1,
			    $this->JutakuBouhanSettei2,
			    $this->JutakuBouhanSettei3,
			    $this->JutakuBouhanSettei4,
			    $this->JutakuBouhanSettei5,
			    $this->JutakuBouhanSettei6,
			    $this->JutakuBouhanSettei7,
			    $this->JutakuBouhanSettei8,
			    $this->JutakuBouhanSettei9,
			    $this->JutakuBouhanSettei10,
			    $this->JutakuBouhanSettei11,
			    
   			    $this->GenkanKokiSettei1,
   			    $this->GenkanKokiSettei2,
   			    $this->GenkanKokiSettei3,
   			    
   			    $this->DenkijoSettei1,

   			    $this->SonotaSettei1,
   			    $this->SonotaSettei2,
   			    $this->SonotaSettei3,
   			    $this->SonotaSettei4,
   			    $this->SonotaSettei5,


				$this->MukouFlg,
				$this->Created,
				$this->Creator,
				$this->Updated,
				$this->Updater
			) = $this->myDB->fetchRow($this->tmprtn,$RowNo);


		   $this->KikiCD = stripslashes($this->KikiCD);
		   $this->ClientCD = stripslashes($this->ClientCD);
		   $this->BukkenCD = stripslashes($this->BukkenCD);

		for($i=1 ;$i<22 ;$i++){
			$this->{"ShugoSettei".$i} = stripslashes($this->{"ShugoSettei".$i});
		}
		for($i=1 ;$i<52 ;$i++){
		   $this->{"OyakiSettei".$i} = stripslashes($this->{"OyakiSettei".$i});
		}
		for($i=1 ;$i<13 ;$i++){		
			$this->{"JutakuMenuSettei".$i} = stripslashes($this->{"JutakuMenuSettei".$i});
		}
		for($i=1 ;$i<9 ;$i++){
			$this->{"JutakuServiceTSettei".$i} = stripslashes($this->{"JutakuServiceTSettei".$i});
		}
		for($i=1 ;$i<56 ;$i++){
			$this->{"JutakuServiceSettei".$i} = stripslashes($this->{"JutakuServiceSettei".$i});
		}
		for($i=1 ;$i < 11 ;$i++ ){
			$this->{"JutakuSistemSettei".$i} = stripslashes($this->{"JutakuSistemSettei".$i});
		}
		for($i=1 ;$i < 6 ;$i++ ){
			$this->{"JutakuKeihouSettei".$i} = stripslashes($this->{"JutakuKeihouSettei".$i});
		}
		for($i=1 ;$i<23 ;$i++ ){
			$this->{"JutakuKanriSettei".$i} = stripslashes($this->{"JutakuKanriSettei".$i});
		}
		for($i=1 ;$i<12 ;$i++ ){
			$this->{"JutakuBouhanSettei".$i} = stripslashes($this->{"JutakuBouhanSettei".$i});
		}
		for($i=1 ;$i<4 ;$i++ ){
			$this->{"GenkanKokiSettei".$i} = stripslashes($this->{"GenkanKokiSettei".$i});
		}
		#$this->GenkanKokiSettei1 = stripslashes($this->GenkanKokiSettei1);
		
		$this->DenkijoSettei1 = stripslashes($this->DenkijoSettei1);

		for($i=1 ;$i<6 ;$i++){
			$this->{"SonotaSettei".$i} = stripslashes($this->{"SonotaSettei".$i});
		}

			$this->MukouFlg = $this->convertFlgToBoolean($this->MukouFlg);
		}
	}

	/* アップデート */
	function executeUpdate() {
		// ログ準備
		$myLog = new SPFWLog();
		

		// エラークリア
		$this->Err = "";

		//設定
		if ($this->myDB == "") {
			$this->Err = "データベースへのコネクションがありません";
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}
		
		if ($this->KikiSetteiCD < 0) {
			// 新規
			// step 1. 新規番号取得のためトランザクション開始
			if (!($this->myDB->executeQuery("BEGIN"))) {
				$this->Err = "追加処理に失敗しました(BEGIN)";
				$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
				unset($myLog);
				return FALSE;
			} else {
				// step 2. ロック
				if (!($this->myDB->lockTable($this->TableName))) {
					$this->Err = "追加処理に失敗しました(LOCK)";
					$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
					$this->myDB->executeQuery("ROLLBACK");
					unset($myLog);
					return FALSE;
				} else {
					// step 3. 取得
					$sqlMoji = "select KikiSetteiCD from $this->TableName order by KikiSetteiCD desc limit 1";
					$maxCD = $this->myDB->getOneValue($sqlMoji);
					$maxCD = ($maxCD == -1) ? 0 : $maxCD;
					$maxCD++;

					// step 4. インサート
					$sqlMoji = "Insert into $this->TableName (";
					$sqlMoji .= "KikiSetteiCD";
	
				     if ($this->KikiCD != "") $sqlMoji .= ", KikiCD";
				     if ($this->ClientCD != "") $sqlMoji .= ", ClientCD";
				     if ($this->BukkenCD != "") $sqlMoji .= ", BukkenCD";
					
					for($i=1 ;$i<22 ;$i++){
						if($this->{"ShugoSettei".$i} != "") $sqlMoji .= ", ShugoSettei".$i;
					}
//						if($this->ShugoSettei1 != "") $sqlMoji .= ", ShugoSettei1";

					for($i=1 ;$i<52 ;$i++){
						if($this->{"OyakiSettei".$i} != "") $sqlMoji .= ", OyakiSettei".$i;
					}
					for($i=1 ;$i<13 ;$i++){
						if($this->{"JutakuMenuSettei".$i} != "") $sqlMoji .= ", JutakuMenuSettei".$i;
					}
					for($i=1 ;$i<9 ;$i++){
						if($this->{"JutakuServiceTSettei".$i} != "") $sqlMoji .= ", JutakuServiceTSettei".$i;
					}
					for($i=1 ;$i<56 ;$i++){
						if($this->{"JutakuServiceSettei".$i} != "") $sqlMoji .= ", JutakuServiceSettei".$i;
					}
					for($i=1 ;$i<11 ;$i++){
						if($this->{"JutakuSistemSettei".$i} != "") $sqlMoji .= ", JutakuSistemSettei".$i;
					}
					for($i=1 ;$i<6 ;$i++){
						if($this->{"JutakuKeihouSettei".$i} != "") $sqlMoji .= ", JutakuKeihouSettei".$i;
					}
					for($i=1 ;$i<23 ;$i++){
						if($this->{"JutakuKanriSettei".$i} != "") $sqlMoji .= ", JutakuKanriSettei".$i;
					}
					for($i=1 ;$i<12 ;$i++){
						if($this->{"JutakuBouhanSettei".$i} != "") $sqlMoji .= ", JutakuBouhanSettei".$i;
					}
					for($i=1 ;$i<4 ;$i++){
						if($this->{"GenkanKokiSettei".$i} != "") $sqlMoji .= ", GenkanKokiSettei".$i;
					}
					#if($this->GenkanKokiSettei1 != "") $sqlMoji .= ", GenkanKokiSettei1";
					if($this->DenkijoSettei1 != "") $sqlMoji .= ", DenkijoSettei1";
					
					for($i=1 ;$i<6 ;$i++){
						if($this->{"SonotaSettei".$i} != "") $sqlMoji .= ", SonotaSettei".$i;
					}




					if ($this->MukouFlg != "") $sqlMoji .= ", MukouFlg";

					$sqlMoji .= ", Created";
					if ($this->Creator != "") $sqlMoji .= ", Creator";

					$sqlMoji .= ", Updated";
					if ($this->Updater != "") $sqlMoji .= ", Updater";

					$sqlMoji .= ") values (";
					$sqlMoji .= "$maxCD";


				     if ($this->KikiCD != "") $sqlMoji .= ", '" . addslashes($this->KikiCD) . "'";
				     if ($this->ClientCD != "") $sqlMoji .= ", '" . addslashes($this->ClientCD) . "'";
				     if ($this->BukkenCD != "") $sqlMoji .= ", '" . addslashes($this->BukkenCD) . "'";
					for($i=1 ;$i<22 ;$i++){
						if ($this->{"ShugoSettei".$i} != "") $sqlMoji .= ", '" . addslashes($this->{"ShugoSettei".$i}) . "'";
					}
//				     if ($this->ShugoSettei1 != "") $sqlMoji .= ", '" . addslashes($this->ShugoSettei1) . "'";
					for($i=1 ;$i<52 ;$i++){
						if ($this->{"OyakiSettei".$i} != "") $sqlMoji .= ", '" . addslashes($this->{"OyakiSettei".$i}) . "'";
					}
					for($i=1 ;$i<13 ;$i++){
						if ($this->{"JutakuMenuSettei".$i} != "") $sqlMoji .= ", '" .addslashes($this->{"JutakuMenuSettei".$i}) . "'";
					}
					for($i=1 ;$i<9 ;$i++){
						if ($this->{"JutakuServiceTSettei".$i} != "") $sqlMoji .= ", '" .addslashes($this->{"JutakuServiceTSettei".$i}) . "'";
					}
					for($i=1 ;$i<56 ;$i++){
						if ($this->{"JutakuServiceSettei".$i} != "") $sqlMoji .= ", '" .addslashes($this->{"JutakuServiceSettei".$i}) . "'";
					}
					for($i=1 ;$i<11 ;$i++){
						if ($this->{"JutakuSistemSettei".$i} != "") $sqlMoji .= ", '" .addslashes($this->{"JutakuSistemSettei".$i}) . "'";
					}
					for($i=1 ;$i<6 ;$i++){
						if ($this->{"JutakuKeihouSettei".$i} != "") $sqlMoji .= ", '" .addslashes($this->{"JutakuKeihouSettei".$i}) . "'";
					}
					for($i=1 ;$i<23 ;$i++){
						if ($this->{"JutakuKanriSettei".$i} != "") $sqlMoji .= ", '" .addslashes($this->{"JutakuKanriSettei".$i}) . "'";
					}
					for($i=1 ;$i<12 ;$i++){
						if ($this->{"JutakuBouhanSettei".$i} != "") $sqlMoji .= ", '" .addslashes($this->{"JutakuBouhanSettei".$i}) . "'";
					}
					for($i=1 ;$i<4 ;$i++){
						if ($this->{"GenkanKokiSettei".$i} != "") $sqlMoji .= ", '" .addslashes($this->{"GenkanKokiSettei".$i}) . "'";
					}
					#if ($this->GenkanKokiSettei1 != "") $sqlMoji .= ", '" .addslashes($this->GenkanKokiSettei1) . "'";
					if ($this->DenkijoSettei1 != "") $sqlMoji .= ", '" .addslashes($this->DenkijoSettei1) . "'";

					for($i=1 ;$i<6 ;$i++){
						if ($this->{"SonotaSettei".$i} != "") $sqlMoji .= ", '" .addslashes($this->{"SonotaSettei".$i}) . "'";
					}
					


					if ($this->MukouFlg != "") $sqlMoji .= ", " . $this->convertFlgToString($this->MukouFlg);
					$sqlMoji .= ", NOW()";
					if ($this->Creator != "") $sqlMoji .= ", $this->Creator";
					$sqlMoji .= ", NOW()";
					if ($this->Updater != "") $sqlMoji .= ", $this->Updater";
					$sqlMoji .= ")";

					$rtn = $this->myDB->executeQuery($sqlMoji);
					if (!$rtn){
						$this->Err = "追加処理に失敗しました";
						$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
						unset($myLog);
						return FALSE;
					}
					else{
						// step 5. コミット
						if (!($this->myDB->executeQuery("COMMIT"))) {
							$this->Err = "追加処理に失敗しました(COMMIT)";
							$this->myDB->executeQuery("ROLLBACK");
							return FALSE;
						}
						else {
							$this->KikiSetteiCD = $maxCD;
						}
					}
				}
			}
		} else {
			// アップデート
			$sqlMoji = "Update $this->TableName set ";
			$sqlMoji .= "KikiSetteiCD = $this->KikiSetteiCD";

		   $sqlMoji .= ", KikiCD = " . $this->convertNullStringValue($this->KikiCD);
		   $sqlMoji .= ", ClientCD = " . $this->convertNullStringValue($this->ClientCD);
		   $sqlMoji .= ", BukkenCD = " . $this->convertNullStringValue($this->BukkenCD);
			
			for($i=1 ;$i<22 ;$i++){
				$sqlMoji .= ", ShugoSettei".$i." = " . $this->convertNullStringValue($this->{"ShugoSettei".$i});
			}
			for($i=1 ;$i<52 ;$i++){
				$sqlMoji .= ", OyakiSettei".$i." = " . $this->convertNullStringValue($this->{"OyakiSettei".$i});
			}
			for($i=1 ;$i<13 ; $i++){
				$sqlMoji .= ", JutakuMenuSettei".$i." = " . $this->convertNullStringValue($this->{"JutakuMenuSettei".$i});
			}
			for($i=1 ;$i<9 ; $i++){
				$sqlMoji .= ", JutakuServiceTSettei".$i." = " . $this->convertNullStringValue($this->{"JutakuServiceTSettei".$i});
			}
			for($i=1 ;$i<56 ; $i++){
				$sqlMoji .= ", JutakuServiceSettei".$i." = " . $this->convertNullStringValue($this->{"JutakuServiceSettei".$i});
			}
			for($i=1 ;$i<11 ; $i++){
				$sqlMoji .= ", JutakuSistemSettei".$i." = " . $this->convertNullStringValue($this->{"JutakuSistemSettei".$i});
			}
			for($i=1 ;$i<6 ; $i++){
				$sqlMoji .= ", JutakuKeihouSettei".$i." = " . $this->convertNullStringValue($this->{"JutakuKeihouSettei".$i});
			}
			for($i=1 ;$i<23 ; $i++){
				$sqlMoji .= ", JutakuKanriSettei".$i." = " . $this->convertNullStringValue($this->{"JutakuKanriSettei".$i});
			}
			for($i=1 ;$i<12 ; $i++){
				$sqlMoji .= ", JutakuBouhanSettei".$i." = " . $this->convertNullStringValue($this->{"JutakuBouhanSettei".$i});
			}
			for($i=1 ;$i<4 ; $i++){
				$sqlMoji .= ", GenkanKokiSettei".$i." = " . $this->convertNullStringValue($this->{"GenkanKokiSettei".$i});
			}
			#$sqlMoji .= ", GenkanKokiSettei1 = " . $this->convertNullStringValue($this->GenkanKokiSettei1);
			
			$sqlMoji .= ", DenkijoSettei1 = " . $this->convertNullStringValue($this->DenkijoSettei1);
			
		
			for($i=1 ;$i<6 ; $i++){
				$sqlMoji .= ", SonotaSettei".$i." = " . $this->convertNullStringValue($this->{"SonotaSettei".$i});
			}

			$sqlMoji .= ", MukouFlg = " . $this->convertFlgToString($this->MukouFlg);
			$sqlMoji .= ", Created = " . $this->convertNullStringValue($this->Created);
			$sqlMoji .= ", Creator = " . $this->convertNullIntValue($this->Creator);
			$sqlMoji .= ", Updated = NOW()";
			$sqlMoji .= ", Updater = " . $this->convertNullIntValue($this->Updater);
			$sqlMoji .= " where KikiSetteiCD = $this->KikiSetteiCD";

			$rtn = $this->myDB->executeQuery($sqlMoji);
			if (!$rtn){
				$this->Err = "更新処理に失敗しました";
				$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
				unset($myLog);
				return FALSE;
			}
		}

		return TRUE;
	}

	function convertFlgToString($flg){
		if ($flg == "true" || $flg == "t")
			return "true";
		else if ($flg == "false" || $flg == "f")
			return "false";

		if ($flg == true || $flg == 1)
			return "true";
		else if ($flg == false || $flg == 0)
			return "false";

		return "false";
	}

	function convertFlgToBoolean($flg){
		if ($flg == "t")
			return TRUE;
		else if ($flg == "f")
			return FALSE;
	}

	function convertNullIntValue($value){
		if ($value == "")
			return "NULL";
		else
			return $value;
	}

	function convertNullStringValue($value){
		if ($value == "")
			return "NULL";
		else
			return "'" . addslashes($value) . "'";
	}

	function setTransaction($lockLevel = "EXCLUSIVE"){
		// ログ準備
		$myLog = new SPFWLog();

		$lockString = "LOCK TABLE " . $this->TableName . " IN SHARE ROW " . $lockLevel . " MODE";
		$rtn = $this->myDB->setTransaction($lockString);
		if (!$rtn){
			$this->Err = $this->myDB->Err;
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}
		return TRUE;
	}

	function rollbackTransaction(){
		// ログ準備
		$myLog = new SPFWLog();

		$rtn = $this->myDB->rollbackTransaction();
		if (!$rtn){
			$this->Err = $this->myDB->Err;
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}
		return TRUE;
	}

	function commitTransaction(){
		// ログ準備
		$myLog = new SPFWLog();

		$rtn = $this->myDB->commitTransaction();
		if (!$rtn){
			$this->Err = $this->myDB->Err;
			$myLog->error("ClassName : " . get_class($this) . " : " . $this->Err);
			unset($myLog);
			return FALSE;
		}
		return TRUE;
	}
}
?>

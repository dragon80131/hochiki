<?php
	include_once "setting.properties";
	include_once _INC_DIR . "carrier.inc";
	include_once _INC_DIR . "global.inc";

	include_once _CLS_DIR . "SPFWDatabase.cls";
	include_once _CLS_DIR . "SPFWLog.cls";
	include_once _CLS_DIR . "SPFWTemplate.cls";
	include_once _CLS_DIR . "SPFWListObject.cls";
	include_once _CLS_DIR . "SPFWDate.cls";
	include_once _CLS_DIR . "SPFWInputCheck.cls";
	include_once _CLS_DIR . "SPFWParameter.cls";
	include_once _CLS_DIR . "SPUSUser.cls";

	include_once _CLS_DIR . "SPUSBukken.cls";
	include_once _CLS_DIR . "SPUSSiten.cls";
	include_once _CLS_DIR . "SPUSDevice.cls";
	include_once _CLS_DIR . "SPUSFile.cls";

	// データベースコネクト
	$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
	if (!$myDB->Connection)
		trigger_error("SPFWDatabase Failed.", E_USER_ERROR);


	########################################################
	# 認証動作
	########################################################
	$rKey = SPFWParameter::getValues("rKey");

	$myUser = new User($myDB);

	if (!$myUser->doAuthenticationByRegistKey($rKey))
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	if ($myUser->UserCD == -1) {
		$URL = _MAIN_URL . 'login_form.php';
		header('Location: ' . $URL);
		exit;
	}

	$wUserCD = $myUser->UserCD;
	$MyShozokuCD = $myUser->Extra1 ;	#所属支店CD
	$MyZokusei = $myUser->Extra3 ;		#管理ユーザ２一般ユーザ１
	$MyEigyoshoCD = $myUser->Extra4 ;	#営業所CD nespeユーザはNULLになってる
	unset($myUser);


	########################################################
	# パラメーター取得
	########################################################

	$editBukkenCD = SPFWParameter::getValues("editBukkenCD");
#	$image_name = SPFWParameter::getValues("image_name");
#	$image_name_kakuchoshi = SPFWParameter::getValues("image_name_kakuchoshi");


	########################################################
	# 添付ファイル処理
	########################################################
####仮ディレクトリにいれておく。

	#添付ファイルがあるかどうか調べる
	$FileLoop = count($_FILES['tempfile']['name']);


	for ($i=0; $i<$FileLoop; $i++) {#ファイル数分繰り返す
		$image_name[$i] = $_FILES['tempfile']['name'][$i]; //ファイル名取得
		if ($image_name[$i]) {
			// 添付ファイルあり
			$TempAri = true;
		}
	}


	if ($TempAri) {

	####20160823追加
	####添付ファイル名：2バイト目が5C等になると、文字が消えてしまう対策
	####参考URL:http://id-of-radiance.info/index/solo/id/6237/PHP_SJIS/
		// パラメータチェック→処理対象データ有り
		if(isset($_POST['hiddenfilenames']) && $_POST['hiddenfilenames'] != "") {

			// パラメータ分解 その１・ファイルタイプ別分離
			//$tmp = explode("%<->%",$_POST['hiddenfilenames'],"EUC-JP","SJIS"));
			$tmp = explode("%<->%",$_POST['hiddenfilenames']);

			// その２・ファイル内情報分離
			$chgfiles = array();
			for($i=0;$i<count($tmp);$i++) {
				// ファイル内情報分離
				$tmp2 = array();
				$tmp2 = explode(":<->:",$tmp[$i]);

				// ２項目目＝ファイル名有り→ファイル名分離・登録
				$tmpkey = str_replace("[","",$tmp2[0]);
				$tmpkey = str_replace("]","",$tmpkey);
				if($tmp2[1] != "") {
					$tmp3 = explode("\\",$tmp2[1]);
					//$chgfiles[$tmpkey][] = $tmp3[count($tmp3)-1],"SJIS","EUC-JP");
					$chgfiles[$tmpkey][] = $tmp3[count($tmp3)-1];
				} else {
					// ファイル名無し→無いなりに空値登録
					$chgfiles[$tmpkey][] = "";
				}
			}

			// ファイル名取得
			foreach($chgfiles as $key=>$val) {
				for($i=0;$i<count($val);$i++) {
					if(isset($_FILES[$key][name][$i])) {
						//$_FILES[$key][name][$i] = $val[$i];
						$FileName[$i] = $val[$i]; #表示用
						$image_name[$i] = $FileName[$i];		#finishに渡すほう

						#拡張子を取得
						$path_parts = pathinfo($image_name[$i]);
						$image_name_kakuchoshi[$i] = $path_parts['extension']; #finishに渡すほう

					}
				}
			}

		}
	####20160823追加 End

		// ファイル保存場所
		$dir = "./upfile/kosinfile_tmp/".$editBukkenCD."/";
		if (file_exists($dir)) {
			#フォルダが存在する場合は中身をいったんけす。
			$Command = "rm -f " . $dir."/*";
			shell_exec($Command);
		} else {
			// kosinfile_tmp/フォルダに物件番号のフォルダを作成する
			mkdir("./upfile/kosinfile_tmp/".$editBukkenCD, 0777);
			chmod("./upfile/kosinfile_tmp/".$editBukkenCD, 0777); // chmodで明示的に変更
		}

		$FileErrorMessage = "";
		
		#添付ファイル
		$FileLoop = count($_FILES['tempfile']['name']);
		for ($i=0; $i<$FileLoop; $i++) {#ファイル数分繰り返す


		    //アップロードファイル確認
		    #0 UPLOAD_ERR_OK
			#1 UPLOAD_ERR_INI_SIZE アップロードされたファイルは、php.iniのupload_max_filesizeディレクティブの値を超えています
			#2 UPLOAD_ERR_FORM_SIZE HTML フォームで指定されたMAX_FILE_SIZEを超えています
			#3 UPLOAD_ERR_PARTIAL 一部のみしかアップロードされていません
			#4 UPLOAD_ERR_NO_FILE ファイル未選択
			if ($_FILES['tempfile']['error'][$i] == 0) { // 1ファイル10M（10485760）以下のみ可能！

				$image_path[$i] = $dir.$image_name[$i]; //ファイルの保存場所

				//ファイルアップロードとエラーチェック アップロードする必要ある？
				if( move_uploaded_file( $_FILES['tempfile']['tmp_name'][$i], $image_path[$i] ) === TRUE){
					$IfOKUP = TRUE;

					if ($i == 0) $FileName1 = $FileName[$i];
					if ($i == 1) $FileName2 = $FileName[$i];
					if ($i == 2) $FileName3 = $FileName[$i];
					if ($i == 3) $FileName4 = $FileName[$i];
					if ($i == 4) $FileName5 = $FileName[$i];

				}else{
					#フォルダに格納できなかったときの表示
					$FileErrorMessage .= $image_name[$i]."<br>　登録資料の登録に失敗しました。予約センターまでご連絡お願いします。<br>";
				}

			} else if ( ($_FILES['tempfile']['error'][$i] == 1) or ($_FILES['tempfile']['error'][$i] == 2) ) {
				$FileErrorMessage .= $image_name[$i]."<br>　ファイルサイズが大きすぎます。ファイルサイズを小さくしてください。<br>";

			}else{ 
				#ファイル名なし：ファイル選択なし
			}

		}#ファイル数のForのEnd

		// エラー文言（tpl用）
		if ($FileErrorMessage) 
			$IfFileErrorMessage = TRUE;

	}
#######仮ディレクトリにいれるEnd

	########################################################
	# ファイルのアップロード  
	########################################################
	#以下のファイルのアップロードは、s_489_confirm.phpで行う。
	#以下では、アップロードされているファイルをDBに登録する

	// ファイル保存場所
	$dir = "./upfile/kosinfile_tmp/".$editBukkenCD."/";

	$OKFileCnt = 0;//登録したファイル数

	for ($i = 0; $i < count($image_name) ; $i++){
		//echo "<br>image_name[$i]:".$image_name[$i];#s_489_confirmから渡ってきた
		if ($image_name[$i]) { // ファイル名があれば。なければファイルなし

			// 画像の取得
			$image_path[$i] = $dir.$image_name[$i];
			$img_file[$i] = file_get_contents( $image_path[$i] );

			//バイナリーファイル取得が成功した場合
			if($img_file[$i]){	
				//画像を保存するSQL文の実行
				$myFile = new File($myDB);

				$myFile->FileCD = -1;
				$myFile->BukkenCD = $editBukkenCD;
				$myFile->SekoStatus = 5;                     #5作成ファイル
				$myFile->P001 = $image_name[$i];             #ファイル名
				$myFile->P002 = $image_name_kakuchoshi[$i];  #拡張子

				$myFile->File = $img_file[$i];
				$myFile->Memo = $Memo;
				$myFile->Creator = $wUserCD;
				$myFile->Updater = $wUserCD;

				if (!$myFile->executeUpdate()){
					$ErrorString = array();
					$ErrorString[] = "メニューマスタ情報の更新に失敗しました。";
					showAdminSorryPage($ErrorString);
				}

				$IfiraifileOK = true;
				$OKFileCnt = $OKFileCnt + 1;

			}else{
				$IfNG = TRUE; #echo("画像ファイルを選択してください。");
			}
		}
	}#添付ファイルのFor　End
	###ファイルのアップロードＥｎｄ

	########################################################
	# コンテンツ表示
	########################################################

	$CNT_FILE = "s_made_confirm.tpl";

	$myTemplate = new SPFWTemplate($CNT_FILE, $MyCarrier);
	$HiddenValues = $myTemplate->getValuesToPass();

	$myTemplate->convertTags();
	$myTemplate->outputTemplate();

	unset($myTemplate);
	unset($myLog);
?>

<?php
	########################################################
	# ���¥ӥå�(�����ԥӥå� ���饤����ȥӥå� �ν��1�ʤ����Ѳ�ǽ)
	########################################################

	if ($IfASP) {
		$PageAuthority['client_detail.php'] = "10";
		$PageAuthority['client_list.php'] = "10";
		$PageAuthority['delivermail_set_end.php'] = "01";
		$PageAuthority['delivermail_set_form.php'] = "01";
		$PageAuthority['delivermail_subscribe_end.php'] = "01";
		$PageAuthority['delivermail_subscribe_form.php'] = "01";
		$PageAuthority['emptymail_set_end.php'] = "01";
		$PageAuthority['emptymail_set_form.php'] = "01";
		$PageAuthority['stepmail_set_end.php'] = "01";
		$PageAuthority['stepmail_set_form.php'] = "01";
		$PageAuthority['user_csv_end.php'] = "01";
		$PageAuthority['user_csv_form.php'] = "01";
		$PageAuthority['user_ex_property_detail.php'] = "11";
		$PageAuthority['user_ex_property_list.php'] = "11";
		$PageAuthority['user_mail_end.php'] = "01";
		$PageAuthority['user_mail_form.php'] = "01";
		$PageAuthority['user_property_end.php'] = "11";
		$PageAuthority['user_property_form.php'] = "11";
		$PageAuthority['user_regist_end.php'] = "01";
		$PageAuthority['user_regist_form.php'] = "01";
		$PageAuthority['user_set_end.php'] = "01";
		$PageAuthority['user_set_form.php'] = "01";
		$PageAuthority['user_set_menu.php'] = "01";

		$MyFilename = basename($_SERVER["SCRIPT_NAME"]);
	}

	########################################################
	# ������ǧ��
	########################################################
	include_once _CLS_DIR . "SPUSAdministrator.cls";
	include_once _INC_DIR . "carrier.inc";

	$myAdministrator = new Administrator($myDB);
	if (!$IfMobileAdmin || $MyCarrier == 1) {
		if (!$myAdministrator->doAuthentication()){
			$URL = _MAIN_URL . 'admin/login.php';
			header ('Location: ' . $URL);
			exit;
		}
	}
	else {
		if ($rKey == NULL) {
			$ErrorString = array();
			$ErrorString[] = "�����������˸��꤬����褦�Ǥ���";
			$ErrorLoop = count($ErrorString);
			include_once 'login_form.php';
			exit;
		}

		if (!$myAdministrator->doAuthenticationByRegistKey($rKey)){
			$ErrorString = array();
			$ErrorString[] = "�����������˸��꤬����褦�Ǥ���";
			$ErrorLoop = count($ErrorString);
			$myTemplate = new SPFWTemplate(_ADMIN_ERROR_TPL, $MyCarrier, TRUE);
			unset($myTemplate);
			exit;
		}

		if ($myAdministrator->AdminCD == -1) {
			$ErrorString = array();
			$ErrorString[] = "�����������˸��꤬����褦�Ǥ���";
			$ErrorLoop = count($ErrorString);
			include_once 'login_form.php';
			exit;
		}
	}

	$AdminCD = $myAdministrator->AdminCD;
	$AdminName = $myAdministrator->LastName . $myAdministrator->FirstName;

	if ($IfASP) {
		include_once _CLS_DIR . "SPUSClient.cls";
		include_once _CLS_DIR . "SPFWError.cls";

		$MyClientCD = $myAdministrator->ClientCD;

		if ($MyClientCD == 0) {
			$IfAdminSystem = TRUE;
			$AdminName = "�����ƥ������";
			$MyAuthority = "10";

			define("_ROWS_PER_PAGE_DEFAULT_INDEX", $SRowsPerPage);
		}
		else {
			$IfAdminClient = TRUE;
			$myClient = new Client($myDB);
			if (!$myClient->executeSelect("ClientCD = " . $MyClientCD . " AND MukouFlg = FALSE", NULL) || $myClient->RecCnt != 1) {
				$ErrorString = array();
				$ErrorString[] = "�����Ծ������Ф˼��Ԥ��ޤ�����";
				$ErrorLoop = count($ErrorString);
				$myTemplate = new SPFWTemplate(_ADMIN_ERROR_TPL, $MyCarrier, TRUE);
				unset($myTemplate);
				exit;
			}
			$MyClientID = $myClient->ID;
			$AdminName = $myClient->ClientName;
			$AdminEMail = $myClient->EMail;
			$ExtraTitle = "��[" . $AdminName . " ��]";
			$MyAuthority = "01";

			$Filename = ($IfASP) ? _OBJECT_DIR . 'system_set_' . sprintf('%03d', $MyClientCD) . '.obj' : _OBJECT_DIR . 'system_set.obj';

			if (file_exists($Filename)) {
				$ObjText = SPFWTools::getFile($Filename);
				$Obj = unserialize($ObjText);
				unset ($ObjText);
			}

			$SAjax = $Obj['Ajax'];
			$SRowsPerPage = $Obj['RowsPerPage'];
			$SAdminTitle = $Obj['AdminTitle'];
			$SAdminCopyright = $Obj['AdminCopyright'];
			$SBackground = $Obj['Background'];
			$STextColor = $Obj['TextColor'];
			$SLinkColor = $Obj['LinkColor'];
			$SVLinkColor = $Obj['VLinkColor'];
			$SALinkColor = $Obj['ALinkColor'];
			$HRColor = $Obj['HRColor'];
			$HRSize = $Obj['HRSize'];
			$SHeader = $Obj['Header'];
			$SFooter = $Obj['Footer'];
			$SCopyright = $Obj['Copyright'];

			if ($SAjax == 't')
				$IfAjax = TRUE;
			else
				$IfAjax = FALSE;
			$IfNoAjax = !$IfAjax;

			define("_ROWS_PER_PAGE_DEFAULT_INDEX", $SRowsPerPage);
		}

		if ($PageAuthority[$MyFilename] == NULL)
			$PageAuthority[$MyFilename] = "11";

		if (bindec($PageAuthority[$MyFilename] & $MyAuthority) != bindec($MyAuthority))
			showAdminSorryPage('�����ͤδ������¤ǤϤ��Υڡ����˥��������Ǥ��ޤ���');
	}

	unset($myAdministrator);

	// ����Ƚ��
	if (date('H') >= 6 && date('H') <= 9)
		$IfMorning = TRUE;
	else if (date('H') >= 10 && date('H') <= 17)
		$IfNoon = TRUE;
	else
		$IfNight = TRUE;
?>

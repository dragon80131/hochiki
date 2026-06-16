<?php
/**
 * マンション単位 WEB予約・更新通知
 */

function ensureBukkenAlertTables($myDB) {
	static $checked = false;
	if ($checked) {
		return;
	}
	$checked = true;

	$sql = "CREATE TABLE IF NOT EXISTS tBukkenWebActivityF (
		BukkenCD int NOT NULL,
		LastWebActivityAt datetime NOT NULL,
		PRIMARY KEY (BukkenCD)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='WEB予約・更新通知用'";
	$myDB->executeQuery($sql);

	$sql = "CREATE TABLE IF NOT EXISTS tBukkenAlertReadF (
		UserCD int NOT NULL,
		BukkenCD int NOT NULL,
		LastReadAt datetime NOT NULL,
		PRIMARY KEY (UserCD, BukkenCD)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='物件更新通知既読'";
	$myDB->executeQuery($sql);
}

function recordBukkenWebActivity($myDB, $BukkenCD) {
	$BukkenCD = intval($BukkenCD);
	if ($BukkenCD <= 0) {
		return;
	}
	ensureBukkenAlertTables($myDB);
	$sql = "INSERT INTO tBukkenWebActivityF (BukkenCD, LastWebActivityAt) VALUES (" . $BukkenCD . ", NOW())";
	$sql .= " ON DUPLICATE KEY UPDATE LastWebActivityAt = NOW()";
	$myDB->executeQuery($sql);
}

function markBukkenAlertRead($myDB, $UserCD, $BukkenCD) {
	$UserCD = intval($UserCD);
	$BukkenCD = intval($BukkenCD);
	if ($UserCD <= 0 || $BukkenCD <= 0) {
		return;
	}
	ensureBukkenAlertTables($myDB);
	$sql = "INSERT INTO tBukkenAlertReadF (UserCD, BukkenCD, LastReadAt) VALUES (" . $UserCD . ", " . $BukkenCD . ", NOW())";
	$sql .= " ON DUPLICATE KEY UPDATE LastReadAt = NOW()";
	$myDB->executeQuery($sql);
}

function markAllBukkenAlertsRead($myDB, $UserCD, $UserKbn, $ClientCD, $UserType, $BrancheCD, $GyosyaCD) {
	ensureBukkenAlertTables($myDB);
	$UserCD = intval($UserCD);
	if ($UserCD <= 0 || $UserKbn == 4) {
		return;
	}

	$sql = "INSERT INTO tBukkenAlertReadF (UserCD, BukkenCD, LastReadAt)";
	$sql .= " SELECT " . $UserCD . ", a.BukkenCD, NOW()";
	$sql .= " FROM tBukkenWebActivityF a";
	$sql .= " INNER JOIN tBukkenM b ON b.BukkenCD = a.BukkenCD AND b.MukouFlg = FALSE";
	$sql .= " LEFT JOIN tBukkenAlertReadF r ON r.UserCD = " . $UserCD . " AND r.BukkenCD = a.BukkenCD";
	$sql .= " WHERE a.LastWebActivityAt > COALESCE(r.LastReadAt, '1970-01-01 00:00:00')";
	$sql .= getBukkenAlertScopeSql($myDB, $UserKbn, $ClientCD, $UserType, $BrancheCD, $GyosyaCD);
	$sql .= " ON DUPLICATE KEY UPDATE LastReadAt = NOW()";
	$myDB->executeQuery($sql);
}

function getBukkenAlertScopeSql($myDB, $UserKbn, $ClientCD, $UserType, $BrancheCD, $GyosyaCD) {
	$sql = "";
	if ($UserKbn == 1 || $UserKbn == 2) {
		$sql .= " AND b.ClientCD = " . intval($ClientCD);
		if ($UserKbn == 1 && $UserType != "1" && $BrancheCD !== "" && $BrancheCD !== null) {
			$sql .= " AND b.BrancheCD = '" . $myDB->escapeString($BrancheCD) . "'";
		}
	} else if ($UserKbn == 3) {
		$GyosyaCD = intval($GyosyaCD);
		$sql .= " AND (b.GyosyaCD = " . $GyosyaCD . " OR b.GyosyaBousaiCD = " . $GyosyaCD . ")";
	}
	return $sql;
}

function getUnreadBukkenAlerts($myDB, $UserCD, $UserKbn, $ClientCD, $UserType, $BrancheCD, $GyosyaCD) {
	ensureBukkenAlertTables($myDB);
	$UserCD = intval($UserCD);
	if ($UserCD <= 0 || $UserKbn == 4) {
		return array();
	}

	$sql = "SELECT b.BukkenCD, b.BukkenName, a.LastWebActivityAt";
	$sql .= " FROM tBukkenWebActivityF a";
	$sql .= " INNER JOIN tBukkenM b ON b.BukkenCD = a.BukkenCD AND b.MukouFlg = FALSE";
	$sql .= " LEFT JOIN tBukkenAlertReadF r ON r.UserCD = " . $UserCD . " AND r.BukkenCD = a.BukkenCD";
	$sql .= " WHERE a.LastWebActivityAt > COALESCE(r.LastReadAt, '1970-01-01 00:00:00')";
	$sql .= getBukkenAlertScopeSql($myDB, $UserKbn, $ClientCD, $UserType, $BrancheCD, $GyosyaCD);
	$sql .= " ORDER BY a.LastWebActivityAt DESC";

	$rtn = $myDB->executeQuery($sql);
	if (!$rtn) {
		return array();
	}

	$items = array();
	$rows = $myDB->getNumberOfRows($rtn);
	for ($i = 0; $i < $rows; $i++) {
		$row = $myDB->fetchRow($rtn, $i);
		$bukkenName = $row[1];
		if ($bukkenName === false || $bukkenName === null || $bukkenName === '') {
			$bukkenName = '物件CD:' . $row[0];
		}
		$items[] = array(
			'BukkenCD' => $row[0],
			'BukkenName' => $bukkenName,
			'LastWebActivityAt' => $row[2],
		);
	}
	$myDB->freeResult($rtn);
	return $items;
}

function authenticateKanriUserForAlert($myDB, $rKey) {
	if ($rKey == NULL) {
		return null;
	}
	$myUser = new User($myDB);
	if (!$myUser->doAuthenticationByRegistKey($rKey)) {
		return null;
	}
	if ($myUser->UserCD == -1) {
		return null;
	}
	return $myUser;
}

<?php
/**
 * マンション単位 WEB予約・更新通知
 */

# 未活動を表すダミー日時（LEFT JOIN の NULL 埋めにも使用）
define('_BUKKEN_ALERT_EPOCH', '1970-01-01 00:00:00');

function ensureBukkenAlertTables($myDB) {
	static $checked = false;
	if ($checked) {
		return;
	}
	$checked = true;

	$sql = "CREATE TABLE IF NOT EXISTS tBukkenWebActivityF (
		BukkenCD int NOT NULL,
		LastWebActivityAt datetime NOT NULL,
		LastActivityType tinyint NOT NULL DEFAULT 1 COMMENT '1:予約 2:更新',
		PRIMARY KEY (BukkenCD)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='WEB予約・更新通知用'";
	$myDB->executeQuery($sql);

	$rtn = $myDB->executeQuery("SHOW COLUMNS FROM tBukkenWebActivityF LIKE 'LastActivityType'");
	if ($rtn && $myDB->getNumberOfRows($rtn) == 0) {
		$myDB->executeQuery("ALTER TABLE tBukkenWebActivityF ADD COLUMN LastActivityType tinyint NOT NULL DEFAULT 1 COMMENT '1:予約 2:更新' AFTER LastWebActivityAt");
	}
	if ($rtn) {
		$myDB->freeResult($rtn);
	}

	$rtn = $myDB->executeQuery("SHOW COLUMNS FROM tBukkenWebActivityF LIKE 'LastTelActivityAt'");
	if ($rtn && $myDB->getNumberOfRows($rtn) == 0) {
		$myDB->executeQuery("ALTER TABLE tBukkenWebActivityF ADD COLUMN LastTelActivityAt datetime NULL COMMENT 'TEL受付更新日時（協力会社向け）' AFTER LastActivityType");
	}
	if ($rtn) {
		$myDB->freeResult($rtn);
	}

	$sql = "CREATE TABLE IF NOT EXISTS tBukkenAlertReadF (
		UserCD int NOT NULL,
		BukkenCD int NOT NULL,
		LastReadAt datetime NOT NULL,
		PRIMARY KEY (UserCD, BukkenCD)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COMMENT='物件更新通知既読'";
	$myDB->executeQuery($sql);
}

function recordBukkenWebActivity($myDB, $BukkenCD, $activityType = 1) {
	$BukkenCD = intval($BukkenCD);
	$activityType = ($activityType == 2) ? 2 : 1;
	if ($BukkenCD <= 0) {
		return;
	}
	ensureBukkenAlertTables($myDB);
	$sql = "INSERT INTO tBukkenWebActivityF (BukkenCD, LastWebActivityAt, LastActivityType) VALUES (" . $BukkenCD . ", NOW(), " . $activityType . ")";
	$sql .= " ON DUPLICATE KEY UPDATE LastWebActivityAt = NOW(), LastActivityType = " . $activityType;
	$myDB->executeQuery($sql);
}

/**
 * TEL受付での更新を記録する。
 * 協力会社(UserKbn=3)のみが通知対象。幹事企業側は自分の操作なので通知しない。
 */
function recordBukkenTelActivity($myDB, $BukkenCD) {
	$BukkenCD = intval($BukkenCD);
	if ($BukkenCD <= 0) {
		return;
	}
	ensureBukkenAlertTables($myDB);
	$sql = "INSERT INTO tBukkenWebActivityF (BukkenCD, LastWebActivityAt, LastActivityType, LastTelActivityAt)";
	$sql .= " VALUES (" . $BukkenCD . ", '" . _BUKKEN_ALERT_EPOCH . "', 2, NOW())";
	$sql .= " ON DUPLICATE KEY UPDATE LastTelActivityAt = NOW()";
	$myDB->executeQuery($sql);
}

function getBukkenActivityLabel($activityType) {
	return ($activityType == 2) ? '更新' : '予約';
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

	$activityAt = getBukkenActivityAtSql($UserKbn);

	$sql = "INSERT INTO tBukkenAlertReadF (UserCD, BukkenCD, LastReadAt)";
	$sql .= " SELECT " . $UserCD . ", a.BukkenCD, NOW()";
	$sql .= " FROM tBukkenWebActivityF a";
	$sql .= " INNER JOIN tBukkenM b ON b.BukkenCD = a.BukkenCD AND b.MukouFlg = FALSE";
	$sql .= " LEFT JOIN tBukkenAlertReadF r ON r.UserCD = " . $UserCD . " AND r.BukkenCD = a.BukkenCD";
	$sql .= " WHERE " . $activityAt . " > COALESCE(r.LastReadAt, '" . _BUKKEN_ALERT_EPOCH . "')";
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

/**
 * 通知判定に使う日時の式を返す。
 * 協力会社(UserKbn=3)のみ TEL受付の更新日時も対象に含める。
 */
function getBukkenActivityAtSql($UserKbn) {
	if ($UserKbn == 3) {
		# CAST は GREATEST が付与する小数秒を落とすため（JS の Date 解釈対策）
		return "CAST(GREATEST(a.LastWebActivityAt, COALESCE(a.LastTelActivityAt, '" . _BUKKEN_ALERT_EPOCH . "')) AS DATETIME)";
	}
	return "a.LastWebActivityAt";
}

/**
 * 表示ラベル用の種別の式を返す。
 * 協力会社で TEL受付の方が新しい場合は「更新」扱いにする。
 */
function getBukkenActivityTypeSql($UserKbn) {
	if ($UserKbn == 3) {
		return "CASE WHEN COALESCE(a.LastTelActivityAt, '" . _BUKKEN_ALERT_EPOCH . "') > a.LastWebActivityAt THEN 2 ELSE a.LastActivityType END";
	}
	return "a.LastActivityType";
}

function getUnreadBukkenAlertCount($myDB, $UserCD, $UserKbn, $ClientCD, $UserType, $BrancheCD, $GyosyaCD) {
	return count(getUnreadBukkenAlerts($myDB, $UserCD, $UserKbn, $ClientCD, $UserType, $BrancheCD, $GyosyaCD));
}

function getRecentBukkenAlerts($myDB, $UserCD, $UserKbn, $ClientCD, $UserType, $BrancheCD, $GyosyaCD, $limit = 5) {
	ensureBukkenAlertTables($myDB);
	$UserCD = intval($UserCD);
	$limit = intval($limit);
	if ($limit <= 0) {
		$limit = 5;
	}
	if ($UserCD <= 0 || $UserKbn == 4) {
		return array();
	}

	$activityAt = getBukkenActivityAtSql($UserKbn);
	$activityType = getBukkenActivityTypeSql($UserKbn);

	$sql = "SELECT b.BukkenCD, b.BukkenName, " . $activityAt . " AS LastActivityAt, " . $activityType . " AS ActivityType,";
	$sql .= " CASE WHEN " . $activityAt . " > COALESCE(r.LastReadAt, '" . _BUKKEN_ALERT_EPOCH . "') THEN 1 ELSE 0 END AS IsUnread";
	$sql .= " FROM tBukkenWebActivityF a";
	$sql .= " INNER JOIN tBukkenM b ON b.BukkenCD = a.BukkenCD AND b.MukouFlg = FALSE";
	$sql .= " LEFT JOIN tBukkenAlertReadF r ON r.UserCD = " . $UserCD . " AND r.BukkenCD = a.BukkenCD";
	$sql .= " WHERE " . $activityAt . " > '" . _BUKKEN_ALERT_EPOCH . "'";
	$sql .= getBukkenAlertScopeSql($myDB, $UserKbn, $ClientCD, $UserType, $BrancheCD, $GyosyaCD);
	$sql .= " ORDER BY " . $activityAt . " DESC";
	$sql .= " LIMIT " . $limit;

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
			'LastActivityAt' => $row[2],
			'ActivityType' => intval($row[3]),
			'ActivityLabel' => getBukkenActivityLabel($row[3]),
			'IsUnread' => ($row[4] == 1),
		);
	}
	$myDB->freeResult($rtn);
	return $items;
}

function getUnreadBukkenAlerts($myDB, $UserCD, $UserKbn, $ClientCD, $UserType, $BrancheCD, $GyosyaCD) {
	ensureBukkenAlertTables($myDB);
	$UserCD = intval($UserCD);
	if ($UserCD <= 0 || $UserKbn == 4) {
		return array();
	}

	$activityAt = getBukkenActivityAtSql($UserKbn);

	$sql = "SELECT b.BukkenCD, b.BukkenName, " . $activityAt . " AS LastActivityAt";
	$sql .= " FROM tBukkenWebActivityF a";
	$sql .= " INNER JOIN tBukkenM b ON b.BukkenCD = a.BukkenCD AND b.MukouFlg = FALSE";
	$sql .= " LEFT JOIN tBukkenAlertReadF r ON r.UserCD = " . $UserCD . " AND r.BukkenCD = a.BukkenCD";
	$sql .= " WHERE " . $activityAt . " > COALESCE(r.LastReadAt, '" . _BUKKEN_ALERT_EPOCH . "')";
	$sql .= getBukkenAlertScopeSql($myDB, $UserKbn, $ClientCD, $UserType, $BrancheCD, $GyosyaCD);
	$sql .= " ORDER BY " . $activityAt . " DESC";

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
			'LastActivityAt' => $row[2],
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

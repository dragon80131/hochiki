<?php
$isAdminMode = TRUE;
// ini_set('display_errors', "On");

include_once "D:/xampp/htdocs/hochiki/SPFW/inc/setting.properties";
include_once _INC_DIR . "carrier.inc";
include_once _INC_DIR . "global.inc";
include_once _CLS_DIR . "SPFWDatabase.cls";
include_once _CLS_DIR . "SPFWLog.cls";
include_once _CLS_DIR . "SPFWTemplate.cls";
include_once _CLS_DIR . "SPFWParameter.cls";
include_once _CLS_DIR . "SPFWListObject.cls";
include_once _CLS_DIR . "SPFWDate.cls";
include_once _CLS_DIR . "SPFWInputCheck.cls";
include_once _CLS_DIR . "SPUSUser.cls";
include_once _CLS_DIR . "SPUSBukken.cls";
// include_once _CLS_DIR . "SPUSKoji.cls";
include_once _CLS_DIR . "SPFWTools.cls";
include_once _CLS_DIR . "SPUSBuilding.cls";

$myDB = new SPFWDatabase(_MAIN_DB, _HOST_NAME, _USER_NAME, _PASSWD, FALSE);
if (!$myDB->Connection)
	trigger_error("SPFWDatabase Failed.", E_USER_ERROR);

########################################################
# 値取得
########################################################
$rKey 			= SPFWParameter::getValues("rKey");
$editBukkenCD 	= SPFWParameter::getValues('editBukkenCD');
$editBuildingCD = SPFWParameter::getValues('editBuildingCD');
$m 				= SPFWParameter::getValues('m');

########################################################
# 認証動作
########################################################
if ($rKey) {
	$myUser = new User($myDB);
	if ($rKey == NULL)
		showSorryPage(_ILLEGAL_ACCESS2);

	if (!$myUser->doAuthenticationByRegistKey($rKey))
		trigger_error("doAuthentication Failed.", E_USER_ERROR);

	if ($myUser->UserCD == -1)
		showSorryPage(_ILLEGAL_ACCESS2);

	$UserCD 		= $myUser->UserCD;
	$ClientCD 		= $myUser->ClientCD; #幹事企業CD
	$UserKbn 		= $myUser->UserKbn;
	unset($myUser);
}


########################################################
# 
########################################################
$myBukken = new Bukken($myDB);
if (!$myBukken->executeSelect(" BukkenCD = $editBukkenCD AND MukouFlg = FALSE", "")) {
	$ErrorString = array();
	$ErrorString[] = "設定ファイル情報の抽出に失敗しました。";
	$ErrorLoop = count($ErrorString);
	$myTemplate = new SPFWTemplate(_ADMIN_ERROR_TPL, $MyCarrier, TRUE);
	unset($myTemplate);
	exit;
}

$myBuilding = new Building($myDB);
if($editBuildingCD){
	if (!$myBuilding->executeSelect("BukkenCD = " . $editBukkenCD . " AND BuildingCD = ".$editBuildingCD." AND MukouFlg = FALSE", "") || $myBuilding->RecCnt != 1) {
		$ErrorString = array();
		$ErrorString[] = "設定ファイル情報の抽出に失敗しました。";
		$ErrorLoop = count($ErrorString);
		$myTemplate = new SPFWTemplate(_ADMIN_ERROR_TPL, $MyCarrier, TRUE);
		unset($myTemplate);
		exit;
	}
}


$MansionName 		= str_replace("'", "\'", $myBukken->BukkenName);
$wSagyoName 		= $myBukken->SagyoName;
$TargetClientCD = $myBukken->ClientCD; #111
$wBuildingName = $myBukken->BuildingName;


$WakuPattern = $myBukken->WakuPattern;
$Kaidaka = intval($myBukken->Kaidaka);
$Kosu = intval($myBukken->Kosu);

if($editBuildingCD){
	$WakuPattern = $myBuilding->WakuPattern;
	$Kaidaka = intval($myBuilding->Kaidaka);
	$Kosu = intval($myBuilding->Kosu);
	$wBuildingName = $myBuilding->BuildingName;
}

// 棟一覧
$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "u.BuildingCD, ";
$sql .= "u.BuildingName ";

$myListObject->SelectSQL = $sql;

$sql = " FROM tBuildingM u ";
$sql .= " WHERE u.MukouFlg = FALSE AND BukkenCD='".$editBukkenCD."'";

$myListObject->Condition = $sql;
$myListObject->Order = "u.BuildingCD ASC";
$myListObject->Limit = "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting User List Failed.", E_USER_ERROR);

$BuildingCD = [];
$BuildingName = [];
$BuildingLoop = $myListObject->Rows;

for ($i = 0; $i < $BuildingLoop; $i++) {
	$BuildingCD[$i] = $myListObject->GetValue($i, 0);
	$BuildingName[$i] = $myListObject->GetValue($i, 1);
}
unset($myListObject);

function numberToCircled($number) {
    $map = [
        1 => '①', 2 => '②', 3 => '③', 4 => '④', 5 => '⑤',
        6 => '⑥', 7 => '⑦', 8 => '⑧', 9 => '⑨', 10 => '⑩',
        11 => '⑪', 12 => '⑫', 13 => '⑬', 14 => '⑭', 15 => '⑮',
        16 => '⑯', 17 => '⑰', 18 => '⑱', 19 => '⑲', 20 => '⑳'
    ];

    return $map[$number] ?? $number;
}

// 棟名称が空の場合、例外処理
if(!$wBuildingName){
	if($editBuildingCD){
		$wBuildingName = '棟'.numberToCircled(2);
		for ($i = 0; $i < $BuildingLoop; $i++) {
			if($BuildingCD[$i] == $editBuildingCD){
				$wBuildingName = '棟'. numberToCircled($i+2);
			}
		}

	}else{
		if($BuildingLoop > 0){
			$wBuildingName = '棟'.numberToCircled(1);
		}
	}
}
if($BuildingLoop < 1){
	$wBuildingName = '';
}

unset($myBukken);

if (is_array($WAKUPATTERN[$WakuPattern]['AMPM'])) {
	$WakuKazu = count($WAKUPATTERN[$WakuPattern]['AMPM']);
}
// $WakuKazu = count($WAKUPATTERN[$WakuPattern]['AMPM']);
$sSTime = $WAKUPATTERN[$WakuPattern]['StartTime'][0];
$sETime = $WAKUPATTERN[$WakuPattern]['EndTime'][$WakuKazu - 1];

$TimeLoop  = $WakuKazu;
for ($i = 0; $i < $TimeLoop; $i++) { #時間選択し
	$sSTime = $WAKUPATTERN[$WakuPattern]['StartTime'][$i];
	$sETime = $WAKUPATTERN[$WakuPattern]['EndTime'][$i];
	$wTime[$i] = $sSTime . "～" . $sETime;
}


$myListObject = new SPFWListObject($myDB);

$sql = "SELECT ";
$sql .= "u.ID, ";
$sql .= "r.TimeFrom, ";
$sql .= "r.KanryoFlg,";
$sql .= "s.FilePath,";
$sql .= "s.Created,";
$sql .= "u.RefugeFlg, ";
$sql .= "DATE_FORMAT(r.TimeFrom, '%Y-%m-%d') AS DateFrom, ";
$sql .= "DATE_FORMAT(r.TimeFrom, '%p') AS AMPM ";
$myListObject->SelectSQL = $sql;

$sql = " FROM tUserM u left outer join tReservationF r on r.UserCD = u.UserCD ";
$sql .= "  left outer join tSignF s on s.UserCD = u.UserCD and s.MukouFlg = FALSE";

$sql .= " WHERE u.MukouFlg = FALSE AND u.BukkenCD = " . $editBukkenCD;
if($editBuildingCD){
	$sql .= " AND u.BuildingCD = " . $editBuildingCD;
}else{
	$sql .= " AND u.BuildingCD IS NULL ";
}

$sql .= " AND (r.Created  > CURDATE() - INTERVAL 4 MONTH OR r.Created IS NULL)";
$sql .= " AND (r.Status = 1 OR r.Status IS NULL)";
$myListObject->Condition	= $sql;

$OrderBy = "DATE_FORMAT(r.TimeFrom, '%Y-%m-%d'), DATE_FORMAT(r.TimeFrom, '%p'), CAST(u.ID AS UNSIGNED)";
$myListObject->Order 		= $OrderBy;

$myListObject->Limit 		= "allpage";

if (!($myListObject->GetList(1)))
	trigger_error("Getting Menu List Failed.", E_USER_ERROR);

$ResidentsFormLoop = $myListObject->Rows;
for ($i = 0; $i < $ResidentsFormLoop; $i++) {
	$ID[$i] 	= $myListObject->GetValue($i, 0);
	$TimeFromDate[$i]	= str_replace("-","/",substr($myListObject->GetValue($i, 1),5,5));
	$KanryoFlg[$i] = $myListObject->GetValue($i,2);
	$FilePath[$i] = $myListObject->GetValue($i,3);
	$Created[$i] = str_replace("-","/",substr($myListObject->GetValue($i, 4),0,16));
	$RefugeFlg[$i] = $myListObject->GetValue($i,5);
	$DateFrom[$i] = $myListObject->GetValue($i,6);
	$AMPM[$i] = $myListObject->GetValue($i,7);
}

unset($myListObject);

########################################################
# Excelファイル生成
########################################################
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as XlsxWriter;

$reader = new XlsxReader();

// $FileFormat = './template/complete_report_vertical.xlsx';
$FileFormat = './template/complete_report.xlsx';
$spreadsheet = $reader->load($FileFormat); //template.xlsx 読込
$sheet = $spreadsheet->getActiveSheet();
$sheet->getDefaultRowDimension()->setRowHeight(12.95);

$page_rows = 52;
$last_row_content = 0;
$content_row_start = 0;

function drawHeader($sheet, $startRow, $wSagyoName, $wBukkenName, $schedule_date){
	global $last_row_content;
	$styleArray1 = [
		'font' => [
			'name' => 'メイリオ',
			'size' => 18,
			'bold' => true,
		],
		'alignment' => [
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
		]
	];

	$styleArray2 = [
		'font' => [
			'name' => 'メイリオ',
			'size' => 12,
		],
		'borders' => [
			'bottom' => [
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // 太線
				'color' => ['argb' => '00000000'], // 黒色
			],
		],
		'alignment' => [
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
		]
	];

	$styleArray3 = [
		'font' => [
			'name' => 'メイリオ',
			'size' => 18,
			'bold' => false,
		],
		'borders' => [
			'bottom' => [
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // 太線
				'color' => ['argb' => '00000000'], // 黒色
			],
		],
		'alignment' => [
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
			'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
		]
	];

	$styleArray4 = [
		'font' => [
			'name' => 'メイリオ',
			'size' => 8,
		],
		'borders' => [
			'allBorders' => [
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // 太線
				'color' => ['argb' => '00000000'], // 黒色
			],
		],
		'alignment' => [
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
		]
	];

	// 1行
	$sheet->getRowDimension($startRow)->setRowHeight(28.5);
	$sheet->mergeCells("A".$startRow . ":" . "AP".$startRow);
	$sheet->getStyle("A".$startRow)->applyFromArray($styleArray1);
	$sheet->setCellValue("A".$startRow, $wSagyoName." 訪問表");

	// 2行
	$sheet->getRowDimension($startRow+1)->setRowHeight(28.5);

	// 3行
	$sheet->getRowDimension($startRow+2)->setRowHeight(21);
	$sheet->mergeCells("B".($startRow+2) . ":" . "E".($startRow+2));
	$sheet->getStyle("B".($startRow+2) . ":" . "H".($startRow+2))->applyFromArray($styleArray2);
	$sheet->setCellValue("B".($startRow+2), "名称：");

	$sheet->getStyle("J".($startRow+2) . ":" . "AC".($startRow+2))->applyFromArray($styleArray3);
	$sheet->setCellValue("J".($startRow+2), $wBukkenName);

	// 4行
	$sheet->getRowDimension($startRow+3)->setRowHeight(21);
	$sheet->mergeCells("B".($startRow+3) . ":" . "E".($startRow+3));
	$sheet->mergeCells("F".($startRow+3) . ":" . "AC".($startRow+3));
	$sheet->setCellValue("B".($startRow+3), "予定：");
	$sheet->setCellValue("F".($startRow+3), $schedule_date);
	$sheet->getStyle("B".($startRow+3) . ":" . "AC".($startRow+3))->applyFromArray($styleArray2);


	// 5行
	$sheet->getRowDimension($startRow+4)->setRowHeight(21);
	$sheet->mergeCells("B".($startRow+4) . ":" . "E".($startRow+4));
	$sheet->mergeCells("F".($startRow+4) . ":" . "AC".($startRow+4));
	$sheet->setCellValue("B".($startRow+4), "実地：");
	$sheet->setCellValue("F".($startRow+4), "年　　月　　日 AM:PM　～　AM:PM");
	$sheet->getStyle("B".($startRow+4) . ":" . "AC".($startRow+4))->applyFromArray($styleArray2);

	// 6行
	$sheet->getRowDimension($startRow+5)->setRowHeight(28.5);
	// 7行
	$sheet->getRowDimension($startRow+6)->setRowHeight(12);


	// Sign Cell
	$sheet->mergeCells("AH".($startRow+2) . ":" . "AK".($startRow+2));
	$sheet->setCellValue("AH".($startRow+2), "管理員");
	$sheet->getStyle("AH".($startRow+2) . ":" . "AK".($startRow+2))->applyFromArray($styleArray4);

	$sheet->mergeCells("AL".($startRow+2) . ":" . "AO".($startRow+2));
	$sheet->setCellValue("AL".($startRow+2), "作業責任者");
	$sheet->getStyle("AL".($startRow+2) . ":" . "AO".($startRow+2))->applyFromArray($styleArray4);

	$sheet->mergeCells("AH".($startRow+3) . ":" . "AK".($startRow+4));
	$sheet->getStyle("AH".($startRow+3) . ":" . "AK".($startRow+4))->applyFromArray($styleArray4);
	$sheet->mergeCells("AL".($startRow+3) . ":" . "AO".($startRow+4));
	$sheet->getStyle("AL".($startRow+3) . ":" . "AO".($startRow+4))->applyFromArray($styleArray4);
	$last_row_content = $startRow+4;
	return $sheet;
}

function drawTicket($sheet, $RoomColRange, $content_row_start, $curRowIndex, $curColIndex, $room, $refuge, $sign_file){
	global $last_row_content;
	$styleArray1 = [
		'font' => [
			'name' => 'HG丸ｺﾞｼｯｸM-PRO',
			'size' => 11,
		],
		'borders' => [
			'allBorders' => [
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // 太線
				'color' => ['argb' => '00000000'], // 黒色
			],
		],
		'alignment' => [
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
		]
	];

	$styleArray2 = [
		'font' => [
			'name' => 'HG丸ｺﾞｼｯｸM-PRO',
			'size' => 7,
		],
		'borders' => [
			'allBorders' => [
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // 太線
				'color' => ['argb' => '00000000'], // 黒色
			],
			// 'inside' => [
			//     'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, // 内側は細線
			//     'color' => ['argb' => '00000000'], // 黒色
			// ],
		],
		'alignment' => [
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
		]
	];

	// for header of room
	$col_header1 = $content_row_start + 1 * $curRowIndex + ($curRowIndex - 1) * 4;
	$col_header2 = $col_header1;
	$sheet->mergeCells($RoomColRange[$curColIndex - 1][0] . $col_header1 . ":" . $RoomColRange[$curColIndex - 1][1] . $col_header2);
	$sheet->setCellValue($RoomColRange[$curColIndex - 1][0] . $col_header1, $room);
	$sheet->getStyle($RoomColRange[$curColIndex - 1][0] . $col_header1 . ":" . $RoomColRange[$curColIndex - 1][1] . $col_header2)->applyFromArray($styleArray1);
	$sheet->getRowDimension($col_header1)->setRowHeight(14);


	
	$col_content1 = $col_header2 + 1;
	$col_content2 = $col_content1;
	$sheet->mergeCells($RoomColRange[$curColIndex - 1][0] . $col_content1 . ":" . $RoomColRange[$curColIndex - 1][1] . $col_content2);
	$sheet->setCellValue($RoomColRange[$curColIndex - 1][0] . $col_content1, $refuge);
	$sheet->getStyle($RoomColRange[$curColIndex - 1][0] . $col_content1 . ":" . $RoomColRange[$curColIndex - 1][1] . $col_content2)->applyFromArray($styleArray1);
	$sheet->getRowDimension($col_content1)->setRowHeight(14);

	// for content of room
	$col_content1 = $col_header2 + 2;
	$col_content2 = $col_content1 + 1;
	$sheet->mergeCells($RoomColRange[$curColIndex - 1][0] . $col_content1 . ":" . $RoomColRange[$curColIndex - 1][1] . $col_content2);
	// $sheet->setCellValue($RoomColRange[$curColIndex - 1][0] . $col_content1, $created_date);
	$sheet->getStyle($RoomColRange[$curColIndex - 1][0] . $col_content1 . ":" . $RoomColRange[$curColIndex - 1][1] . $col_content2)->applyFromArray($styleArray2);
	$sheet->getRowDimension($col_content1)->setRowHeight(17);
	$sheet->getRowDimension($col_content2)->setRowHeight(17);

	if($sign_file != ''){
		$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
		$drawing->setPath($sign_file);
		$drawing->setWidth(73);
		$drawing->setOffsetX(1);
		$drawing->setOffsetY(5);
		// $drawing->setHeight(50);
		// $drawing->setOffsetY(5);
		$drawing->setCoordinates($RoomColRange[$curColIndex - 1][0] . $col_content1);
		$drawing->setWorksheet($sheet);
	}
	$last_row_content = $col_content2;
	return $sheet;
}


if($wBuildingName != ""){
	$MansionName = $MansionName."(".$wBuildingName.")";
}

$RoomColRange = [
	['B', 'F'],		// 1 clumn
	['G', 'K'],	// 2 clumn
	['L', 'P'],	// 3 clumn
	['Q', 'U'],	// 4 clumn
	['V', 'Z'],	// 5 clumn
	['AA', 'AE'],	// 6 clumn
	['AF', 'AJ'],	// 7 clumn
	['AK', 'AO']	// 8 clumn
];

$maxRowCountPerPage = 8;
$maxColCountPerPage = 8;

$curRowIndex = 1;
$curColIndex = 1;
$old_DateFrom = "";
$curFloor = "";

for($i=0; $i < count($ID); $i++){
	if($old_DateFrom != $DateFrom[$i]." ".$AMPM[$i]){
		if($i != 0){
			// draw blank cell
			while($curColIndex <= $maxColCountPerPage){
				$sheet = drawTicket($sheet, $RoomColRange, $content_row_start+7, $curRowIndex, $curColIndex, "", "", "");
				$curColIndex ++;
			}

			$curRowIndex ++;
			if($curRowIndex <= $maxRowCountPerPage){
				for($blank_i=$curRowIndex; $blank_i<=$maxRowCountPerPage; $blank_i++){
					for($blank_j=1; $blank_j<=$maxColCountPerPage; $blank_j++){
						$sheet = drawTicket($sheet, $RoomColRange, $content_row_start+7, $blank_i, $blank_j, "", "", "");
					}
				}
			}
			$styleArray5 = [
				'font' => [
					'name' => 'HG丸ｺﾞｼｯｸM-PRO',
					'size' => 11,
				],
				'alignment' => [
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
					'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				]
			];

			$last_row_content += 3;
			$sheet->setCellValue("B".$last_row_content, "記事");
			$sheet->getStyle("B".$last_row_content)->applyFromArray($styleArray5);
			$sheet->setCellValue("F".$last_row_content, "１＿避難器具のある居室は「避難」に○印を入れてください");
			$sheet->getStyle("F".$last_row_content)->applyFromArray($styleArray5);

			$last_row_content ++;	
			$sheet->setCellValue("F".$last_row_content, "２＿居住者不在の場合はその訪問した時間を記入してください");
			$sheet->getStyle("F".$last_row_content)->applyFromArray($styleArray5);

			$curFloor = "";
			$curRowIndex = 1;
			$curColIndex = 1;
			$content_row_start += $page_rows;

		}

		$sheet = drawHeader($sheet, $content_row_start+1, $wSagyoName, $MansionName, date("Y年　n月　j日", strtotime($DateFrom[$i])). " ".$AMPM[$i]."　～　".$AMPM[$i]);
	}
	$old_DateFrom = $DateFrom[$i]." ".$AMPM[$i];

	$room = $ID[$i];
	// $created_date = $Created[$i]?$Created[$i]:'';
	$sign_file = $FilePath[$i]?$FilePath[$i]:'';

	$is_next_floor = false;
	$KaiRoomLen = strlen($room);
	#Room[$x]…02,03,12  Kai[$x]…1,2,11 など
	$Floor = substr($room, 0, ($KaiRoomLen - 2));
	if($curFloor != "" && $curFloor != $Floor)
		$is_next_floor = true;
	$curFloor = $Floor;

	if($is_next_floor){
		// draw blank cell
		while($curColIndex <= $maxColCountPerPage){
			$sheet = drawTicket($sheet, $RoomColRange, $content_row_start+7, $curRowIndex, $curColIndex, "", "", "");
			$curColIndex ++;
		}

		$curRowIndex ++;
		$curColIndex = 1;
	}else if($curColIndex > $maxColCountPerPage){
		$curRowIndex ++;
		$curColIndex = 1;
	}

	if($curRowIndex > $maxRowCountPerPage){
		$styleArray5 = [
			'font' => [
				'name' => 'HG丸ｺﾞｼｯｸM-PRO',
				'size' => 11,
			],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
				'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
			]
		];

		$last_row_content += 3;
		$sheet->setCellValue("B".$last_row_content, "記事");
		$sheet->getStyle("B".$last_row_content)->applyFromArray($styleArray5);
		$sheet->setCellValue("F".$last_row_content, "１＿避難器具のある居室は「避難」に○印を入れてください");
		$sheet->getStyle("F".$last_row_content)->applyFromArray($styleArray5);

		$last_row_content ++;
		$sheet->setCellValue("F".$last_row_content, "２＿居住者不在の場合はその訪問した時間を記入してください");
		$sheet->getStyle("F".$last_row_content)->applyFromArray($styleArray5);

		$curRowIndex = 1;
		$content_row_start += $page_rows;
		$sheet = drawHeader($sheet, $content_row_start+1, $wSagyoName, $MansionName, date("Y年　n月　j日", strtotime($DateFrom[$i])). " ".$AMPM[$i]."　～　".$AMPM[$i]);

	}

	// for refuge of room
	$refuge = '';
	if($RefugeFlg[$i] == '1')
		$refuge = '避難';

	$sheet = drawTicket($sheet, $RoomColRange, $content_row_start+7, $curRowIndex, $curColIndex, $room, $refuge, $sign_file);
	$curColIndex ++;
}
// draw blank cell
while($curColIndex <= $maxColCountPerPage){
	$sheet = drawTicket($sheet, $RoomColRange, $content_row_start+7, $curRowIndex, $curColIndex, "", "", "");
	$curColIndex ++;
}

$curRowIndex ++;
if($curRowIndex <= $maxRowCountPerPage){
	for($i=$curRowIndex; $i<=$maxRowCountPerPage; $i++){
		for($j=1; $j<=$maxColCountPerPage; $j++){
			$sheet = drawTicket($sheet, $RoomColRange, $content_row_start+7, $i, $j, "", "", "");
		}
	}

	$styleArray5 = [
		'font' => [
			'name' => 'HG丸ｺﾞｼｯｸM-PRO',
			'size' => 11,
		],
		'alignment' => [
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
			'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
		]
	];

	$last_row_content += 3;
	$sheet->setCellValue("B".$last_row_content, "記事");
	$sheet->getStyle("B".$last_row_content)->applyFromArray($styleArray5);
	$sheet->setCellValue("F".$last_row_content, "１＿避難器具のある居室は「避難」に○印を入れてください");
	$sheet->getStyle("F".$last_row_content)->applyFromArray($styleArray5);

	$last_row_content ++;
	$sheet->setCellValue("F".$last_row_content, "２＿居住者不在の場合はその訪問した時間を記入してください");
	$sheet->getStyle("F".$last_row_content)->applyFromArray($styleArray5);

}

$last_row_content = ceil($last_row_content / $page_rows) * $page_rows;

$sheet->getPageSetup()->setPrintArea('A1:AP'.$last_row_content);

if($wBuildingName != ""){
	$sheet->setCellValue("B1", $MansionName."(".$wBuildingName.")　印取表");
}else{
	$sheet->setCellValue("B1", $MansionName."　印取表");
}

header("Content-Description: File Transfer");

if($wBuildingName != ""){
	$wFileName = str_replace(",", "", $MansionName) . '_' . str_replace(",", "", $wBuildingName) . '_完了報告_' . date("Ymdhi") . '.xlsx';
}else{
	$wFileName = str_replace(",", "", $MansionName) . '完了報告_' . date("Ymdhi") . '.xlsx';
}

$ieFileName = mb_convert_encoding($wFileName, 'SJIS-win', 'UTF-8');
$userAgent = $_SERVER['HTTP_USER_AGENT'];

if (preg_match('/MSIE|Trident/', $userAgent)) {
    // IE の場合
    header('Content-Disposition: attachment; filename="' . $ieFileName . '"');
} else {
    // その他のモダンブラウザ（UTF-8 + RFC5987対応）
    $encodedFilename = rawurlencode($wFileName);
    header("Content-Disposition: attachment; filename*=UTF-8''" . $encodedFilename);
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Expires: 0');
ob_end_clean(); //バッファ消去

$writer = new XlsxWriter($spreadsheet);
$writer->save('php://output');
exit;
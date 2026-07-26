<?php
/**
 * Resolve per-building 専有部 period and 受付締切日.
 *
 * Building 1 uses tBukkenM (SenyuStartDate1 / YoyakuEndDate).
 * Building 2+ uses tBuildingM (SenyuStartDate / SenyuEndDate / YoyakuEndDate),
 * falling back to bukken values when empty.
 *
 * @param object      $myBukken
 * @param object|null $myBuilding  Loaded Building row when editBuildingCD is set
 * @param mixed       $editBuildingCD
 * @return array{SenyuStartDate:string,SenyuEndDate:string,YoyakuEndDate:string}
 */
function resolveBuildingSenyuAndYoyaku($myBukken, $myBuilding = null, $editBuildingCD = null)
{
	$SenyuStartDateGeneral = isset($myBukken->SenyuStartDate) ? $myBukken->SenyuStartDate : '';
	$SenyuEndDateGeneral = isset($myBukken->SenyuEndDate) ? $myBukken->SenyuEndDate : '';

	$SenyuStartDate = isset($myBukken->SenyuStartDate1) ? $myBukken->SenyuStartDate1 : '';
	$SenyuEndDate = isset($myBukken->SenyuEndDate1) ? $myBukken->SenyuEndDate1 : '';
	$YoyakuEndDate = isset($myBukken->YoyakuEndDate) ? $myBukken->YoyakuEndDate : '';

	if ($editBuildingCD && $myBuilding) {
		if (!empty($myBuilding->SenyuStartDate)) {
			$SenyuStartDate = $myBuilding->SenyuStartDate;
		}
		if (!empty($myBuilding->SenyuEndDate)) {
			$SenyuEndDate = $myBuilding->SenyuEndDate;
		}
		if (!empty($myBuilding->YoyakuEndDate)) {
			$YoyakuEndDate = $myBuilding->YoyakuEndDate;
		}
	}

	if (!$SenyuStartDate || !$SenyuEndDate) {
		$SenyuStartDate = $SenyuStartDateGeneral;
		$SenyuEndDate = $SenyuEndDateGeneral;
	}

	return array(
		'SenyuStartDate' => $SenyuStartDate,
		'SenyuEndDate' => $SenyuEndDate,
		'YoyakuEndDate' => $YoyakuEndDate,
	);
}

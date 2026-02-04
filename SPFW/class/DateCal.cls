<?php

define('TERM_UNIT_YEAR', 'year');
define('TERM_UNIT_MONTH', 'month');
define('TERM_UNIT_WEEK', 'week');
define('TERM_UNIT_DAY', 'day');
define('TIMESTAMP_UNIT_DAY', 24 * 60 * 60);

/**
 * Calculation date distance between two date.
 * 
 * @param string $dateStart  Date of start point.
 * @param string $dateEnd  Date of end point.
 * @param string $termUnit  Unit string of terms (ex. day, month...)
 * @return integer  Distance between twon date depend on assigned term unit.
 */
    function _calcDateDistance($dateStart, $dateEnd, $termUnit = null)
    {
    // Check args
        if (empty($dateStart) || empty($dateEnd)) {
            return false;
        }
    // Change to timestamp
        $timestampStart = strtotime($dateStart);
        $timestampEnd = strtotime($dateEnd);
    // Check & Compare dates
        if (empty($timestampStart) || empty($timestampEnd)) {
                return false;
        } else {
                if ($timestampStart > $timestampEnd) {
                        $tmpTimestamp = $timestampStart;
                        $timestampStart = $timestampEnd;
                        $timestampEnd = $tmpTimestamp;
                }
        }
    // Set vars    
        $timestampDistance = $timestampEnd - $timestampStart;
        $datesStart = getdate($timestampStart);
        $datesEnd = getdate($timestampEnd);
    // Calc distance
        $distance = 0;
        switch ($termUnit) {
        case TERM_UNIT_YEAR:
        case TERM_UNIT_MONTH:
        // Set vars
            $lessThanTerm = false;
            $distanceYear = $datesEnd['year'] - $datesStart['year'];
            $distanceMonth = $datesEnd['mon'] - $datesStart['mon'];
            $distanceDay = $datesEnd['mday'] - $datesStart['mday'];
        // Check day & moving down
            if ($distanceDay < 0) {
                if ($distanceMonth > 0) {
                    $distanceMonth = $distanceMonth - 1;
                } else {
                        if ($distanceYear > 0) {
                                $distanceYear = $distanceYear - 1;
                                $distanceMonth = $distanceMonth + 12 - 1;
                        } else {
                                $lessThanTerm = true;
                        }
                }
            }
        // Check month & moving down
            if ($distanceMonth < 0) {
                if ($distanceYear > 0) {
                    $distanceYear = $distanceYear - 1;
                    $distanceMonth = $distanceMonth + 12;
                } else {
                        $lessThanTerm = true;
                }
            }
        // Check year
            //if ($distanceYear < 0) {}
        // Check calc result & sum result.
            switch ($termUnit) {
                case TERM_UNIT_YEAR:
                        if ($lessThanTerm) {
                                $distance = 0;
                        } else {
                                $distance = abs($distanceYear);
                        }
                    break;
                case TERM_UNIT_MONTH:
                        if ($lessThanTerm) {
                                $distance += abs($distanceMonth);
                        } else {
                        // Sum year & month
                            if (!empty($distanceYear)) {
                                $distance += abs($distanceYear) * 12;
                            }
                            if (!empty($distanceMonth)) {
                                $distance += abs($distanceMonth);
                            }
                        }
                break;
            }
            break;
        case TERM_UNIT_WEEK:
            $distance = $timestampDistance / (TIMESTAMP_UNIT_DAY * 7);
            $distance = abs(floor($distance));
            break;
        case TERM_UNIT_DAY:
        default:
                $distance = $timestampDistance / TIMESTAMP_UNIT_DAY;
                $distance = abs(floor($distance));
            break;
        }
        return $distance;
    }
?>

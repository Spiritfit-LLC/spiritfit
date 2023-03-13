<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?php
if (!empty($arResult["TIMETABLE"])){
    $timetable=[];
    foreach($arResult["TIMETABLE"] as $TIME){
        if ($TIME<="12:00"){
            $key="0MORNING";
        }
        elseif ($TIME<="18:00"){
            $key="1DAYTIME";
        }
        else{
            $key="2EVENING";
        }
        $timetable[$key][]=$TIME;
    }
    ksort($timetable);
    $arResult["TIMETABLE"]=$timetable;
}

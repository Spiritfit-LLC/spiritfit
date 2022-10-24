<?php

$formFields=[];
$checkboxes=[];
foreach ($arResult["FORM_FIELDS"]["FIELDS"] as $FIELD){
    if ($FIELD["TYPE"]!="checkbox" && $FIELD["TYPE"]!="textarea"){
        $formFields[]=$FIELD;
    }
    elseif($FIELD["TYPE"]=="textarea"){
        $arResult["TEXTAREA"]=$FIELD;
    }
    else{
        $checkboxes[]=$FIELD;
    }
}
list($arResult["LEFT_FIELDS"], $arResult["RIGHT_FIELDS"]) = array_chunk($formFields, ceil(count($formFields) / 2));
$arResult["CHECKBOXES"]=$checkboxes;

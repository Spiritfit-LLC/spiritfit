<?php
$rsForm = CForm::GetList($by='s_sort', $order='asc', array("SITE" => $_REQUEST["site"]), $v3);
while ($arForm = $rsForm->Fetch())
{
    $arrForms[$arForm["ID"]] = "[".$arForm["ID"]."] ".$arForm["NAME"];
}



$arComponentParameters = array(
    "GROUPS" => array(),
    "PARAMETERS" => array(
        "WEB_FORM_ID" => array(
            "NAME" => "ID WEB-формы",
            "TYPE" => "LIST",
            "VALUES" => $arrForms,
            "ADDITIONAL_VALUES"	=> "Y",
            "PARENT" => "DATA_SOURCE",
            "REFRESH"=>"Y"
        ),
        "WEB_FORM_FIELDS"=>array(),
        "FORM_TYPE" => Array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => "Тип события формы в 1С",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "",
        ),
        "CLUB_ID" => Array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => "ID клуба",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "",
        ),
        "TEXT_FORM"=>array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => "Заголовок формы",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "",
        ),
        "EXTERNAL_CLUB_ID"=>array(
            "PARENT"=>"DATA_SOURCE",
            "NAME"=>"СТАТИЧНЫЙ КЛУБ ID",
            "TYPE"=>"INTEGER",
            "MULTIPLE" => "N",
            "DEFAULT" => 0,
        )
    ),
);

if (0 < intval($arCurrentValues['WEB_FORM_ID']))
{
    $arParams=[];
    $arPropList = array();
    $rsForm = CForm::GetDataByID($arCurrentValues["WEB_FORM_ID"], $arParams["arForm"], $arParams["arQuestions"], $arParams["arAnswers"], $arParams["arDropDown"], $arParams["arMultiSelect"]);
    foreach($arParams["arQuestions"] as $key=>$value){
        $arFields[$key]="[".$key."] ".$value[$key]["TITLE"];
    }
    $arComponentParameters['PARAMETERS']['WEB_FORM_FIELDS'] = array(
        "PARENT" => "DATA_SOURCE",
        "NAME" => "Поля веб формы",
        "TYPE" => "LIST",
        "MULTIPLE" => "Y",
        "DEFAULT" => "",
        "VALUES"=>$arFields
    );
}
?>
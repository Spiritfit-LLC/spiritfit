<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php

$arResult["CLUBS"]=[];
foreach ($arResult["ITEMS"] as $arItem){
    if (!empty($arItem["PROPERTIES"]["TEAM"]["VALUE"])){
        $arClub=[
            "ID"=>$arItem["ID"],
            "NAME"=>$arItem["~NAME"]
        ];
        $order=array('SORT' => 'ASC', 'NAME' => 'ASC');
        $filter=array("IBLOCK_ID" => $arItem["PROPERTIES"]["TEAM"]["LINK_IBLOCK_ID"], "ID" => $arItem["PROPERTIES"]["TEAM"]["VALUE"], "ACTIVE" => "Y");
        $select=array("ID", "NAME", "PREVIEW_PICTURE");
        $itemRes=CIBlockElement::GetList($order, $filter, false, false);

        $trainer=[];
        while($item=$itemRes->GetNextElement()){
            $arFields=$item->GetFields();
            $arFields["PROPERTIES"]=$item->GetProperties();
            $trainer["PICTURE"] = CFile::ResizeImageGet($arFields["PREVIEW_PICTURE"], array("width" => "379", "height" => "580", BX_RESIZE_IMAGE_PROPORTIONAL))["src"];
            $trainer["VIDEO"] = CFile::GetPath($arFields['PROPERTIES']["VIDEO"]["VALUE"]);
            $trainer["NAME"] = $arFields["NAME"];
            if ($arFields['PROPERTIES']['BACK_TEXT']['VALUE']) {
                $trainer['TEXT'] = $arFields['PROPERTIES']['BACK_TEXT']['VALUE']['TEXT'];
            }
            $arClub["TEAM"][] = $trainer;
        }
        $arResult["CLUBS"][]=$arClub;
    }
}

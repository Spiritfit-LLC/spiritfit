<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php
// получаем разделы
$dbResSect = CIBlockSection::GetList(
    Array("SORT"=>"ASC"),
    Array("IBLOCK_ID"=>$arParams['IBLOCK_ID'])
);
//Получаем разделы и собираем в массив
while($sectRes = $dbResSect->GetNext())
{
    $arSections[] = $sectRes;
}

$items=[];

//Собираем  массив из Разделов и элементов
foreach ($arResult["ITEMS"] as $key=>&$arItem){
    $f=false;
    foreach($arSections as $arSection){
        if($arItem['IBLOCK_SECTION_ID'] == $arSection['ID']){
            $f=true;
            break;
        }
    }
    if (!$f){
        unset($arResult["ITEMS"][$key]);
        continue;
    }

    $arItem["MIN_PRICE"] = min(array_column($arItem["PROPERTIES"]["PRICE"]["VALUE"], "PRICE"));
    $minPrice=(int)array_column($arItem["PROPERTIES"]["PRICE"]["VALUE"], "PRICE")[0];
    $minPriceClub=array_column($arItem["PROPERTIES"]["PRICE"]["VALUE"], "LIST")[0];
    foreach($arItem["PROPERTIES"]["PRICE"]["VALUE"] as $arPrice){
        if (empty($arPrice["PRICE"]) || empty($arPrice["LIST"])){
            continue;
        }

        if (((int)$arPrice["PRICE"]<=$minPrice) || (empty($minPriceClub) || empty($minPrice))){
            $minPrice=(int)$arPrice["PRICE"];
            $minPriceClub=$arPrice["LIST"];
        }
    }

    $index=array_search($minPriceClub, array_column($arItem["PROPERTIES"]["BASE_PRICE"]["VALUE"], "LIST"));
    $monthCount=$arItem["PROPERTIES"]["BASE_PRICE"]["VALUE"][$index]["NUMBER"];
    $arItem["MIN_RPICE_PER_MONTH"]=false;
    if ($monthCount>1){
        $arItem["MIN_PRICE"]=ceil($arItem["MIN_PRICE"]/$monthCount);
        $arItem["MIN_PRICE_PER_MONTH"]=true;
    }

    $db_groups = CIBlockElement::GetElementGroups($arItem["ID"], false);
    while($ar_group = $db_groups->Fetch()) {
        $arItem["SECTIONS"][]=$ar_group["ID"];
    }
}

?>
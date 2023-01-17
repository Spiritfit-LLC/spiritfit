<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php
// получаем разделы
$dbResSect = CIBlockSection::GetList(
    Array("SORT"=>"DESC"),
    Array("IBLOCK_ID"=>$arParams['IBLOCK_ID'])
);
//Получаем разделы и собираем в массив
while($sectRes = $dbResSect->GetNext())
{
    $arSections[] = $sectRes;
}

$items=[];

$SECTIONS=[];

//$arResult["PRESENT_HEIGHT"]=0;
//Собираем  массив из Разделов и элементов
foreach ($arResult["ITEMS"] as $key=>&$arItem){
    $f=false;
    foreach($arSections as $arSection){
        if($arItem['IBLOCK_SECTION_ID'] == $arSection['ID']){
            $f=true;
            if (!key_exists($arSection['ID'], $SECTIONS)){
                $SECTIONS[$arSection['ID']]=[
                    "ID"=>$arSection["ID"],
                    "NAME"=>$arSection["NAME"],
                    "SORT"=>$arSection["SORT"]
                ];
            }
            break;
        }
    }
    if (!$f){
        unset($arResult["ITEMS"][$key]);
        continue;
    }



    while (true){
        $minPrice=min(array_column($arItem["PROPERTIES"]["PRICE"]["VALUE"], "PRICE"));
        $minPriceKey=array_search($minPrice, array_column($arItem["PROPERTIES"]["PRICE"]["VALUE"], "PRICE"));
        $minPriceNumber=$arItem["PROPERTIES"]["PRICE"]["VALUE"][$minPriceKey]["NUMBER"];
        $minPriceClub=$arItem["PROPERTIES"]["PRICE"]["VALUE"][$minPriceKey]["LIST"];

        
        $clubFilter=["PROPERTY_HIDE_CLUB"=>false, "PROPERTY_HIDE_LINK"=>false, "PROPERTY_HIDE_ABONEMENT"=>false];
        $club=Utils::getClub($minPriceClub, false, $clubFilter);
        if (empty($club)){
            unset($arItem["PROPERTIES"]["PRICE"]["VALUE"][$minPriceKey]);
            if (count($arItem["PROPERTIES"]["PRICE"]["VALUE"])==0){
                break;
            }
        }
        else{
            break;
        }
    }


    foreach ($arItem["PROPERTIES"]["PRICE"]["VALUE"] as $key2=>$price){
        if ($price["NUMBER"]==$minPriceNumber){
            unset($arItem["PROPERTIES"]["PRICE"]["VALUE"][$key2]);
        }
    }

    $minPrice2=min(array_column($arItem["PROPERTIES"]["PRICE"]["VALUE"], "PRICE"));
    $arItem["MIN_PRICE2"]=$minPrice2;

    $arItem["MIN_PRICE"]=$minPrice;

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


    if (!empty($arItem["PROPERTIES"]["CARD_BASE_PRICE"]["VALUE"])){
        $arItem["BASE_PRICE"]=$arItem["PROPERTIES"]["CARD_BASE_PRICE"]["VALUE"];
    }

}
usort($SECTIONS, function ($item1, $item2) {
    return $item1['SORT'] <=> $item2['SORT'];
});
$arResult["SECTIONS"]=$SECTIONS;

?>

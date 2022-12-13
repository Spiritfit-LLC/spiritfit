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


    $price_sign_keys = array_keys(array_intersect(array_column($arItem["PROPERTIES"]["PRICE_SIGN_DETAIL"]["VALUE"], "LIST"), [$arParams["CLUB_ID"]]));

    $price_keys = array_keys(array_intersect(array_column($arItem["PROPERTIES"]["PRICE"]["VALUE"], "LIST"), [$arParams["CLUB_ID"]]));

    $base_price_keys = array_keys(array_intersect(array_column($arItem["PROPERTIES"]["BASE_PRICE"]["VALUE"], "LIST"), [$arParams["CLUB_ID"]]));

    $PRICES=[];
    foreach ($price_keys as $price_key){
        $PRICE_ITEM=[
            "PRICE"=>$arItem["PROPERTIES"]["PRICE"]["VALUE"][$price_key]["PRICE"],
            "NUMBER"=>$arItem["PROPERTIES"]["PRICE"]["VALUE"][$price_key]["NUMBER"]
        ];

        $flag=false;
        foreach ($price_sign_keys as $price_sign_key){
            if ($arItem["PROPERTIES"]["PRICE_SIGN_DETAIL"]["VALUE"][$price_sign_key]["NUMBER"]==$arItem["PROPERTIES"]["PRICE"]["VALUE"][$price_key]["NUMBER"] && !empty($arItem["PROPERTIES"]["PRICE_SIGN_DETAIL"]["VALUE"][$price_sign_key]["PRICE"])){
                $PRICE_ITEM["SIGN"]=$arItem["PROPERTIES"]["PRICE_SIGN_DETAIL"]["VALUE"][$price_sign_key]["PRICE"];
                $flag=true;
                break;
            }
        }

        if (!$flag){
            $props = [];
            $arFilter = ['IBLOCK_CODE' => 'price_sign', 'NAME' => $arItem["PROPERTIES"]["PRICE"]["VALUE"][$price_key]["NUMBER"]];
            $dbPrice = CIBlockElement::GetList([], $arFilter, false, false, ['NAME', 'PROPERTY_MONTH']);
            if ($res=$dbPrice->Fetch()){
                $PRICE_ITEM["SIGN"]=$res["PROPERTY_MONTH_VALUE"];
            }
        }

        foreach ($base_price_keys as $base_price_key){
            if ($arItem["PROPERTIES"]["BASE_PRICE"]["VALUE"][$price_sign_key]["NUMBER"]==$arItem["PROPERTIES"]["PRICE"]["VALUE"][$price_key]["NUMBER"]){
                $PRICE_ITEM["BASE_PRICE"]=$arItem["PROPERTIES"]["BASE_PRICE"]["VALUE"][$price_sign_key]["PRICE"];
            }
        }
        $PRICES[]=$PRICE_ITEM;
    }

    usort($PRICES, function ($item1, $item2) {
        return $item2['NUMBER'] > $item1['NUMBER'];
    });

    $arItem["PRICES"]=$PRICES;
//    echo "<pre>";
//    print_r($PRICES);
//    echo "</pre>";

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
usort($SECTIONS, function ($item1, $item2) {
    return $item1['SORT'] <=> $item2['SORT'];
});
$arResult["SECTIONS"]=$SECTIONS;

?>
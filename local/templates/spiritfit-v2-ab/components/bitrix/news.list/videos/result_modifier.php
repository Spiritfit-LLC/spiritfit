<?
$arrayGetList['order'] = ["SORT"=>"ASC"];
$arrayGetList['filter'] = ["IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y"]; 
$arrayGetList['select'] = ["IBLOCK_ID", "ID", "NAME", "DESCRIPTION", "DEPTH_LEVEL", "UF_DIRECTION_NAME"]; 
$sections = CIBlockSection::GetList($arrayGetList['order'], $arrayGetList['filter'], true, $arrayGetList['select'], false);
while ($section = $sections->fetch()) 
{
    if ($section["DEPTH_LEVEL"] == 2 && $section["ELEMENT_CNT"] != 0) {
        $arResult["SECTIONS"][$section["ID"]] = $section;
    } elseif ($section["DEPTH_LEVEL"] == 1) {
        $arResult["MAIN_SECTION"] = $section;
    }
}

foreach ($arResult["ITEMS"] as $key=>$item) {
    $properties = CIBlockElement::GetProperty($arParams["IBLOCK_ID"], $item["ID"], [], []);
    while ($property = $properties->GetNext()) {
        $arResult["ITEMS"][$key][$property["CODE"]] = $property;
        if ($property["CODE"] == "PREVIEW_AVAILABLE_VIDEO") {
            $arResult["ITEMS"][$key]["PREVIEW_AVAILABLE_VIDEO_SRC"] = CFile::GetPath($property['VALUE']);
        }
        if ($property["CODE"] == "PREVIEW_CLOSED_VIDEO") {
            $arResult["ITEMS"][$key]["PREVIEW_CLOSED_VIDEO_SRC"] = CFile::GetPath($property['VALUE']);
        }
    }
}

$theme = $_REQUEST['theme'];
if ($theme && $theme != "all") {
    $getListArray['filter'] = ["IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "ID" => $theme]; 
    $getListArray['select'] = ["IBLOCK_ID", "ID", "NAME", "DESCRIPTION"];  
    $section = \Bitrix\Iblock\SectionTable::getList($getListArray);

    while ($sectionFields = $section->fetch()) 
    {
        $arResult["CHOOSEN_SECTION"][$sectionFields["ID"]] = $sectionFields;
    }

    $videosFromSection = [];
    $getListArrayElements['order'] = ["SORT"=>"ASC"];
    $getListArrayElements['filter'] = ["IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "SECTION_ID" => $theme];
    $getListArrayElements['select'] = ["IBLOCK_ID", "ID", "SECTION_ID"];  
    $elements = CIBlockElement::GetList($getListArrayElements['order'], $getListArrayElements['filter'], false, false, $getListArrayElements['select']);
    while ($element = $elements->fetch()) 
    {
        foreach ($arResult["ITEMS"] as $key=>$item) 
        {
            if ($element["ID"] == $item["ID"]) 
            {
                $videosFromSection[] = $item;
            }
        }
    }
    $arResult["CHOOSEN_SECTION"][$theme]["ITEMS"] = $videosFromSection;

    $ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arParams["IBLOCK_ID"], $theme);
    $seoValues = $ipropValues->getValues();
    if ($seoValues['SECTION_META_TITLE']) {
        $arResult['SEO']['SECTION_META_TITLE'] = $seoValues['SECTION_META_TITLE'];
    }
    if ($seoValues['SECTION_META_DESCRIPTION']) {
         $arResult['SEO']['SECTION_META_DESCRIPTION'] = $seoValues['SECTION_META_DESCRIPTION'];
    }
    if ($seoValues['SECTION_META_KEYWORDS']) {
         $arResult['SEO']['SECTION_META_KEYWORDS'] = $seoValues['SECTION_META_KEYWORDS'];
    }
} else {
    $ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arParams["IBLOCK_ID"], $arResult["MAIN_SECTION"]["ID"]);
    $seoValues = $ipropValues->getValues();
    if ($seoValues['SECTION_META_TITLE']) {
        $arResult['SEO']['SECTION_META_TITLE'] = $seoValues['SECTION_META_TITLE'];
    }
    if ($seoValues['SECTION_META_DESCRIPTION']) {
         $arResult['SEO']['SECTION_META_DESCRIPTION'] = $seoValues['SECTION_META_DESCRIPTION'];
    }
    if ($seoValues['SECTION_META_KEYWORDS']) {
         $arResult['SEO']['SECTION_META_KEYWORDS'] = $seoValues['SECTION_META_KEYWORDS'];
    }
}

$siteProperties = Utils::getInfo();
$arResult['BUTTON_TEXT_LEFT'] = $siteProperties['PROPERTIES']['BUTTON_TEXT_LEFT']['~VALUE'];
$arResult['BUTTON_TEXT_RIGHT'] = $siteProperties['PROPERTIES']['BUTTON_TEXT_RIGHT']['~VALUE'];
?>
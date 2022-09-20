<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!empty($arResult["ELEMENT"]["PROPERTIES"]["PHOTO_GALLERY"]["VALUE"])) {
    foreach ($arResult["ELEMENT"]["PROPERTIES"]["PHOTO_GALLERY"]["VALUE"] as $key => $value) {
        $arResult["ELEMENT"]["PROPERTIES"]["PHOTO_GALLERY"]["VALUE"][$key] = CFile::GetPath($value);
    }
}
if (!empty($arResult["ELEMENT"]["PROPERTIES"]["PREVIEW_VIDEO"]["VALUE"])) {
    $arResult["ELEMENT"]["PROPERTIES"]["PREVIEW_VIDEO"]["VALUE"] = CFile::GetPath($arResult["ELEMENT"]["PROPERTIES"]["PREVIEW_VIDEO"]["VALUE"]);
} else {
    preg_match("/(watch\?v=(.*))|((?!.*\/).*?$)/", $arResult["ELEMENT"]["PROPERTIES"]["LINK_VIDEO"]["VALUE"], $match);
    $match = $match[2] ? $match[2] : $match[3];

    if ($match) {
        $arResult["ELEMENT"]["PROPERTIES"]["PREVIEW_VIDEO"]["VALUE"] = "http://i3.ytimg.com/vi/" . $match . "/maxresdefault.jpg";
    }
}

if (!empty($arResult["ELEMENT"]["PROPERTIES"]["LINK_VIDEO"]["VALUE"])) {
    preg_match("/(watch\?v=(.*))|((?!.*\/).*?$)/", $arResult["ELEMENT"]["PROPERTIES"]["LINK_VIDEO"]["VALUE"], $match);
    $match = $match[2] ? $match[2] : $match[3];

    if ($match) {
        $arResult["ELEMENT"]["PROPERTIES"]["LINK_VIDEO"]["VALUE"] = "https://www.youtube.com/embed/" . $match;
    }
}

if (!empty($arResult["SEO"]["ELEMENT_META_TITLE"])) {
    $APPLICATION->SetPageProperty('title', $arResult["SEO"]["ELEMENT_META_TITLE"]);
}


if (!empty($arResult["SEO"]["ELEMENT_META_DESCRIPTION"])) {
    $APPLICATION->SetPageProperty('description',$arResult["SEO"]["ELEMENT_META_DESCRIPTION"]);
}

$APPLICATION->AddChainItem(strip_tags($arResult["ELEMENT"]["~NAME"]));
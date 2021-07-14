<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!empty($arResult["ELEMENT"]["PROPERTIES"]["PHOTO_GALLERY"]["VALUE"]))
{
    foreach ($arResult["ELEMENT"]["PROPERTIES"]["PHOTO_GALLERY"]["VALUE"] as $key => $value) {
        $arResult["ELEMENT"]["PROPERTIES"]["PHOTO_GALLERY"]["VALUE"][$key] = CFile::GetPath($value);
    }
}
if (!empty($arResult["ELEMENT"]["PROPERTIES"]["PREVIEW_VIDEO"]["VALUE"]))
{
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

$clubsProps = Utils::getClubsCoordiantes();
// Убираем клубы без координат на карте
foreach ($clubsProps as $key => $club) {
    if (empty($club["PROPERTY_CORD_YANDEX_VALUE"])) {
        unset($clubsProps[$key]);
        continue;
    }
    if (!empty($club["PROPERTY_PHONE_VALUE"]))
        $clubsProps[$key]["PROPERTY_PHONE_LINK"] = preg_replace('![^0-9]+!', '', $club["PROPERTY_PHONE_VALUE"]);
}
$clubsProps = array_values($clubsProps);
if (!empty(current($clubsProps)))
    $arResult["CLUB_PROPS"] = $clubsProps;
<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$page_element=$APPLICATION->GetCurPage();

$url_parts = explode("/", $page_element);
$last = count($url_parts)-1;
if ($url_parts[$last]=="") $last--;
$code = $url_parts[$last];
$code = preg_replace('/(.*).(php|html|htm)/', '$1', $code);

$id = CIBlockFindTools::GetElementID(false, $code, false, false, array());

if (empty($id)){
   CHTTP::SetStatus("404 Not Found");
    @define("ERROR_404","Y");
    require(\Bitrix\Main\Application::getDocumentRoot()."/404.php");
   die();
}

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

if (!empty($arResult["SEO"]["ELEMENT_META_TITLE"])) {
    $APPLICATION->SetPageProperty('title', $arResult["SEO"]["ELEMENT_META_TITLE"]);
}
if (!empty($arResult["SEO"]["ELEMENT_META_DESCRIPTION"])) {
    $APPLICATION->SetPageProperty('description',$arResult["SEO"]["ELEMENT_META_DESCRIPTION"]);
}

if ((empty($_POST) || !empty($_POST['_pjax'])) && empty($_POST['club'])) {
    foreach ($arResult["arAnswers"]["club"][0]['ITEMS'] as &$club) {
        $club['SELECTED']= '';

    }
}




$APPLICATION->AddChainItem(strip_tags($arResult["ELEMENT"]["~NAME"]));
<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$settings = Utils::getInfo();
if( !empty($arParams["BLOCK_TITLE"]) ) {
    $arResult['TITLE'] = $arParams["BLOCK_TITLE"];
} else {
    $arResult['TITLE'] = (!empty($settings['PROPERTIES']['ABONEMENTS_SLIDER_TITLE']['VALUE'])) ? $settings['PROPERTIES']['ABONEMENTS_SLIDER_TITLE']['VALUE'] : "";
}

$arResult['BROWSER'] = getBrowserInformation();
$arResult["SLIDER_PHOTOS"] = [];

$arResult['IS_SAFARI_BROWSER'] = false;
if( (!empty($arResult['BROWSER']['NAME']) && $arResult['BROWSER']['NAME'] === "Safari") ) {
    $arResult['IS_SAFARI_BROWSER'] = true;
}

$videoPreview = (!empty($arResult['PROPERTIES']['BLOCK_PREVIEW']['VALUE'][$k])) ? $arResult['PROPERTIES']['BLOCK_PREVIEW']['VALUE'][$k] : '';
if( !empty($arResult['PROPERTIES']['BLOCK_PHOTO']['VALUE']) ) {
    foreach( $arResult['PROPERTIES']['BLOCK_TEXT']['~VALUE'] as $k => $value ) {

        $photo = (!empty($arResult['PROPERTIES']['BLOCK_PHOTO']['VALUE'][$k])) ? $arResult['PROPERTIES']['BLOCK_PHOTO']['VALUE'][$k] : '';
        $mobilePhoto = (!empty($arResult['PROPERTIES']['BLOCK_PHOTO_MOBILE']['VALUE'][$k])) ? $arResult['PROPERTIES']['BLOCK_PHOTO_MOBILE']['VALUE'][$k] : '';

        if( empty($photo) && !empty($videoPreview) ) {
            $photo = $videoPreview;
        }

        $text = $value['TEXT'];
        $title = (!empty($arResult['PROPERTIES']['BLOCK_TEXT']['DESCRIPTION'][$k])) ? $arResult['PROPERTIES']['BLOCK_TEXT']['DESCRIPTION'][$k] : $arResult['NAME'];

        $link = (!empty($arResult['PROPERTIES']['BLOCK_LINKS']['VALUE'][$k])) ? trim($arResult['PROPERTIES']['BLOCK_LINKS']['VALUE'][$k]) : '';
        $linkTitle = (!empty($arResult['PROPERTIES']['BLOCK_LINKS']['DESCRIPTION'][$k])) ? $arResult['PROPERTIES']['BLOCK_LINKS']['DESCRIPTION'][$k] : 'Подробнее';

        $isBlank = false;
        if( !empty($link) && ( strpos($link, 'http:') !== false || strpos($link, 'https:') !== false ) && strpos($link, $_SERVER['HTTP_HOST']) === false ) {
            $isBlank = true;
        }
        preg_match('/^.+\..+\//xi', $link, $matches);
        if( !empty($matches) ) {
            $isBlank = true;
        }

        $arBlock = ['TEXT' => $text, 'TITLE' => $title, 'LINK' => ['URL' => $link, 'TITLE' => $linkTitle, 'IS_BLANK' => $isBlank], 'PHOTO' => []];

        $itemPhotoData = CFile::GetFileArray($photo);

        if( $arResult['IS_SAFARI_BROWSER'] && !empty($photo) ) {
            if( empty($mobilePhoto) ) $mobilePhoto = $photo;
            $arBlock['PHOTO']['1280'] = CFile::ResizeImageGet($photo, array('width' => 1280, 'height' => 800), BX_RESIZE_IMAGE_PROPORTIONAL)["src"];
            $arBlock['PHOTO']['800'] = CFile::ResizeImageGet($mobilePhoto, array('width' => 800, 'height' => 500), BX_RESIZE_IMAGE_PROPORTIONAL)["src"];
            $arBlock['PHOTO']['450'] = CFile::ResizeImageGet($mobilePhoto, array('width' => 450, 'height' => 281), BX_RESIZE_IMAGE_PROPORTIONAL)["src"];
            $arBlock['PHOTO_DESCRIPTION'] = !empty($itemPhotoData['DESCRIPTION']) ? $itemPhotoData['DESCRIPTION'] : '';
        } else if( !empty($photo) ) {
            $itemPhotoMobileData = (!empty($mobilePhoto)) ? CFile::GetFileArray($mobilePhoto) : $itemPhotoData;
            $arBlock['PHOTO']['1280'] = \ImageConverter\Picture::getResizeWebp($itemPhotoData, 1280, 800, true)['WEBP_SRC'];
            $arBlock['PHOTO']['800'] = \ImageConverter\Picture::getResizeWebp($itemPhotoMobileData, 800, 500, true)['WEBP_SRC'];
            $arBlock['PHOTO']['450'] = \ImageConverter\Picture::getResizeWebp($itemPhotoMobileData, 450, 281, true)['WEBP_SRC'];
        }

        $arResult["SLIDER_PHOTOS"][] = $arBlock;
    }
}
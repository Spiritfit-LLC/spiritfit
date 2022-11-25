<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Outcode\Quiz;
use Bitrix\Main\Loader;

global $USER;

$arResult['DETAIL_PICTURE_SRC'] = '';
if( !empty($arResult['DETAIL_PICTURE']) ) {
    $arResult['DETAIL_PICTURE_SRC'] = CFile::ResizeImageGet($arResult['DETAIL_PICTURE'], array('width' => 290, 'height' => 345), BX_RESIZE_IMAGE_PROPORTIONAL)['src'];
}
/* Получаем значения SEO */
$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues(Utils::GetIBlockIDBySID("quiz"), $arResult["ID"]);
$seoValues = $ipropValues->getValues();


if (empty($seoValues['ELEMENT_META_TITLE'])){
    $APPLICATION->SetPageProperty('title', $arResult["NAME"]);
}
else{
    $APPLICATION->SetPageProperty('title', $seoValues['ELEMENT_META_TITLE']);
}
if (!empty($seoValues['ELEMENT_META_DESCRIPTION'])){
    $APPLICATION->SetPageProperty('description', $seoValues['ELEMENT_META_DESCRIPTION']);

}
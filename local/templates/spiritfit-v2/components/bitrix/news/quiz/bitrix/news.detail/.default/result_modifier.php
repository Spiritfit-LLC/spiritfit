<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

global $USER;

$arResult['DETAIL_PICTURE_SRC'] = '';
if( !empty($arResult['DETAIL_PICTURE']) ) {
    $arResult['DETAIL_PICTURE_SRC'] = CFile::ResizeImageGet($arResult['DETAIL_PICTURE'], array('width' => 290, 'height' => 345), BX_RESIZE_IMAGE_PROPORTIONAL)['src'];
}

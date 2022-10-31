<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if( !empty($arResult['QUESTION']['PROPERTIES']['ANSWERS_IMAGE']['VALUE']) ) {
    if( is_array(arResult['QUESTION']['PROPERTIES']['ANSWERS_IMAGE']['VALUE']) ) {
        foreach( $arResult['QUESTION']['PROPERTIES']['ANSWERS_IMAGE']['VALUE'] as &$value ) {
            $value = [
                'ID' => $value,
                'SRC' => CFile::GetPath($value),
                'SRC_RESIZED' => CFile::ResizeImageGet($value, array("width" => 320, "height" => 320), BX_RESIZE_IMAGE_PROPORTIONAL, false)['src']
            ];
        }
        unset($value);
    } else {
        $arResult['QUESTION']['PROPERTIES']['ANSWERS_IMAGE']['VALUE'] = CFile::ResizeImageGet($arResult['QUESTION']['PROPERTIES']['ANSWERS_IMAGE']['VALUE'], array("width" => 320, "height" => 320), BX_RESIZE_IMAGE_PROPORTIONAL, false)['src'];
    }
}
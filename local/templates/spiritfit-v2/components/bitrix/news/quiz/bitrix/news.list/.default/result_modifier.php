<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$arResult['PRIZES'] = [];
if(!empty($arParams['IBLOCK_PRIZE_ID'])) {
    $res = CIBlockElement::GetList(['SORT' => 'ASC'], ['IBLOCK_ID' => $arParams['IBLOCK_PRIZE_ID'], 'ACTIVE' => 'Y'], false, false, ['ID', 'NAME', 'PREVIEW_PICTURE', 'SORT']);
    while($elArr = $res->GetNext()) {
        $arResult['PRIZES'][] = [
            'NAME' => $elArr['NAME'],
            'PICTURE' => CFile::ResizeImageGet($elArr['PREVIEW_PICTURE'], array('width' => 282, 'height' => 252), BX_RESIZE_IMAGE_EXACT)['src']
        ];
    }
}
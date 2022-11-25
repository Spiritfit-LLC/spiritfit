<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$arResult['PRIZES'] = [];
if(!empty($arParams['IBLOCK_PRIZE_ID'])) {
    $res = CIBlockElement::GetList(['SORT' => 'ASC'], ['IBLOCK_ID' => $arParams['IBLOCK_PRIZE_ID'], 'ACTIVE' => 'Y'], false, false, ['ID', 'NAME', 'PREVIEW_PICTURE', 'SORT', 'PROPERTY_LINK']);
    while($elArr = $res->GetNext()) {
        $arResult['PRIZES'][] = [
            'NAME' => $elArr['NAME'],
            'PICTURE' => CFile::ResizeImageGet($elArr['PREVIEW_PICTURE'], array('width' => 282, 'height' => 252), BX_RESIZE_IMAGE_EXACT)['src'],
            'LINK'=>$elArr["PROPERTY_LINK_VALUE"]
        ];
    }
}


$APPLICATION->SetPageProperty('title',"День рождения Spirit.Fitness – Онлайн игра Spirit.Квиз");
$APPLICATION->SetPageProperty('description',"Участвуйте в онлайн игре Spirit.Квиз, выигрывайте по-настоящему ценные призы и просто веселитесь вместе с нами. С днем рождения, сеть фитнес-клубов Spirit.Fitness!");
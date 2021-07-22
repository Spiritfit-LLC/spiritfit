<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arResult["ITEMS_TWO"] = array_chunk($arResult["ITEMS"], ceil(count($arResult["ITEMS"]) / 2));
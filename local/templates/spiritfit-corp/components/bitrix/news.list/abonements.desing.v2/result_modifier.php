<?php
	if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
	
	\Bitrix\Main\Loader::includeModule('iblock');
	
	$cardsArr = [];
	$ids = [];
	foreach( $arResult["ITEMS"] as $item ) {
		$ids[] = $item["ID"];
	}
	if( !empty($ids) ) {
		$sortArr = [];
		if( !empty($arParams["SORT_BY1"]) && !empty($arParams["SORT_ORDER1"]) ) {
			$sortArr[$arParams["SORT_BY1"]] = $arParams["SORT_ORDER1"];
		}
		if( !empty($arParams["SORT_BY2"]) && !empty($arParams["SORT_ORDER2"]) ) {
			$sortArr[$arParams["SORT_BY2"]] = $arParams["SORT_ORDER2"];
		}
		$res = CIBlockElement::GetList($sortArr, ["IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y"], false);
		while( $ob = $res->GetNextElement() ) {
			$arFields = $ob->GetFields();
			$arFields["PROPERTIES"] = $ob->GetProperties();
			
			$itemArr = [
				"ID" => $arFields["ID"],
				"TITLE_FRONT" => $arFields["NAME"],
				"TEXT1_FRONT" => $arFields["PROPERTIES"]["TEXT_1"]["VALUE"],
				"TEXT2_FRONT" => $arFields["PROPERTIES"]["TEXT_2"]["VALUE"],
				"TEXT3_FRONT" => $arFields["PROPERTIES"]["TEXT_3"]["VALUE"],
				"BUTTON_1_TEXT" => $arFields["PROPERTIES"]["BUTTON_TEXT_1"]["VALUE"],
				"BUTTON_2_TEXT" => $arFields["PROPERTIES"]["BUTTON_TEXT_2"]["VALUE"],
				"BUTTON_3_TEXT" => $arFields["PROPERTIES"]["BACK_BUTTON_TEXT"]["VALUE"],
				"BUTTON_1_URL" => $arFields["PROPERTIES"]["BUTTON_URL_1"]["VALUE"],
				"BUTTON_3_URL" => $arFields["PROPERTIES"]["BACK_BUTTON_LINK"]["VALUE"],
				"TITLE_BACK" => $arFields["PROPERTIES"]["BACK_TITLE"]["VALUE"],
				"TEXT1_BACK" => $arFields["PROPERTIES"]["BACK_TEXT"]["VALUE"]
			];
			if( !empty($arFields["PREVIEW_PICTURE"]) ) {
				$itemArr["PICTURE"] = CFile::ResizeImageGet($arFields["PREVIEW_PICTURE"], ["width" => 380, "height" => 380], BX_RESIZE_IMAGE_PROPORTIONAL)["src"]; 
			} else {
				$itemArr["PICTURE"] = "";
			}
			if( !empty($arFields["DETAIL_PICTURE"]) ) {
				$itemArr["PICTURE_BACKGROUND"] = CFile::ResizeImageGet($arFields["DETAIL_PICTURE"], ["width" => 380, "height" => 380], BX_RESIZE_IMAGE_PROPORTIONAL)["src"]; 
			} else {
				$itemArr["PICTURE_BACKGROUND"] = "";
			}
			$cardsArr[] = $itemArr;
			unset($itemArr);
		}
	}
	unset($ids);
	
	$arResult["ITEMS"] = $cardsArr;
	
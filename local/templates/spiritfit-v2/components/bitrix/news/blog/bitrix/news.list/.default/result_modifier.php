<?
    if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
	
	use \ImageConverter\Picture;
	
	if( !function_exists("getAdditionaBlogItem") ) {
		function getAdditionaBlogItem( $arItem, $isSafari ) {
			$item = ["ID" => $arItem["ID"], "NAME" => !empty($arItem["PROPERTIES"]["TITLE"]["~VALUE"]) ? $arItem["PROPERTIES"]["TITLE"]["~VALUE"] : $arItem["NAME"], "LINK" => $arItem["DETAIL_PAGE_URL"]];
    		
			if( !empty($arItem["PREVIEW_PICTURE"]) && !is_array($arItem["PREVIEW_PICTURE"]) ) {
				$arItem["PREVIEW_PICTURE"] = ["ID" => $arItem["PREVIEW_PICTURE"]];
			}
			
			if( !empty($arItem["PREVIEW_PICTURE"]) && $isSafari ) {
				$item["PICTURE"] = [
    				"SMALL" => !empty($arItem["PROPERTIES"]["PICT_SECOND"]["VALUE"]) ? CFile::ResizeImageGet($arItem["PROPERTIES"]["PICT_SECOND"]["VALUE"], ["width" => 130, "height" => 130], BX_RESIZE_IMAGE_EXACT)["src"] : CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], ["width" => 130, "height" => 130], BX_RESIZE_IMAGE_EXACT)["src"],
    				"MEDIUM" => CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], ["width" => 573, "height" => 311], BX_RESIZE_IMAGE_EXACT)["src"],
    				"BIG" => CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], ["width" => 1180, "height" => 640], BX_RESIZE_IMAGE_EXACT)["src"]
    			];
    		} else if( !empty($arItem["PREVIEW_PICTURE"]) ) {
				$item["PICTURE"] = [
    				"SMALL" => !empty($arItem["PROPERTIES"]["PICT_SECOND"]["VALUE"]) ? Picture::getResizeWebpFileId($arItem["PROPERTIES"]["PICT_SECOND"]["VALUE"], 130, 130, false)["WEBP_SRC"] : Picture::getResizeWebpFileId($arItem["PREVIEW_PICTURE"]["ID"], 130, 130, false)["WEBP_SRC"],
    				"MEDIUM" => Picture::getResizeWebpFileId($arItem["PREVIEW_PICTURE"]["ID"], 573, 311, false)["WEBP_SRC"],
    				"BIG" =>  Picture::getResizeWebpFileId($arItem["PREVIEW_PICTURE"]["ID"], 1180, 640, false)["WEBP_SRC"]
    			];
			}
    		$item["PREVIEW_TEXT"] = $arItem["~PREVIEW_TEXT"];
    		$item["DATE"] = !empty($arItem["ACTIVE_FROM"]) ? explode(" ", $arItem["ACTIVE_FROM"])[0] : explode(" ", $arItem["TIMESTAMP_X"])[0];
    		$item["SECTION"] = [];
			
    		if( !empty($arItem["IBLOCK_SECTION_ID"]) ) {
    			$dbGroups = CIBlockElement::GetElementGroups($arItem["ID"], true, ["ID", "NAME"]);
    			while( $arGroup = $dbGroups->Fetch() ) {
    				$item["SECTION"][] = $arGroup["NAME"];
    			}
    		}
			
			return $item;
		}
	}
	
	$arResult['BROWSER'] = getBrowserInformation();
	$isSafari = true;
	if( (!empty($arResult['BROWSER']['NAME']) && $arResult['BROWSER']['NAME'] !== "Safari") || empty($arResult['BROWSER']['NAME']) ) {
		$isSafari = false;
	}
	if( empty($arParams["CACHE_TYPE"]) || $arParams["CACHE_TYPE"] !== "N" ) {
		$isSafari = true;
	}

    $itemsArr = [];
    foreach( $arResult["ITEMS"] as $arItem ) {
    	$itemsArr[] = getAdditionaBlogItem($arItem, $isSafari);
    }
    unset($arResult["ITEMS"]);

    $arResult["ITEMS"] = $itemsArr;
	
	$sortArr = [];
	if( !empty($arParams["SORT_BY1"]) && !empty($arParams["SORT_ORDER1"]) ) {
		$sortArr[$arParams["SORT_BY1"]] = $arParams["SORT_ORDER1"];
	}
	if( !empty($arParams["SORT_BY2"]) && !empty($arParams["SORT_ORDER2"]) ) {
		$sortArr[$arParams["SORT_BY2"]] = $arParams["SORT_ORDER2"];
	}
	if( empty($sortArr) ) $sortArr["ID"] = "ASC";
	
	$arResult["LEFT_ITEMS"] = [];
	$res = CIBlockElement::GetList( $sortArr, ["IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "PROPERTY_IN_LEFT_VALUE" => "Да"], false );
	while( $obItem = $res->GetNextElement() ) {
		$arItem = $obItem->GetFields();
		$arItem["PROPERTIES"] = $obItem->GetProperties();
		$arResult["LEFT_ITEMS"][] = getAdditionaBlogItem($arItem, $isSafari);
	}
<?
	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
	
	use \ImageConverter\Picture;
	
	$arResult['BROWSER'] = getBrowserInformation();
	$isSafari = true;
	if( (!empty($arResult['BROWSER']['NAME']) && $arResult['BROWSER']['NAME'] !== "Safari") || empty($arResult['BROWSER']['NAME']) ) {
		$isSafari = false;
	}
	
	$arResult["SECTIONS"] = [];
    $dbList = CIBlockSection::GetList([], ["IBLOCK_ID" => $arParams["IBLOCK_ID"], "DEPTH_LEVEL" => 1], false);
    while( $arRes = $dbList->GetNext() ) {
    	$arResult["SECTIONS"][$arRes["ID"]] = $arRes["NAME"];
    }
	
	$arResult["SECTION_NAME"] = !empty($arResult["SECTIONS"][$arResult["IBLOCK_SECTION_ID"]]) ? $arResult["SECTIONS"][$arResult["IBLOCK_SECTION_ID"]] : "";
	
	$arResult["DATE"] = !empty($arResult["ACTIVE_FROM"]) ? explode(" ", $arResult["ACTIVE_FROM"])[0] : explode(" ", $arResult["TIMESTAMP_X"])[0];
	$arResult["PICTURE_SRC"] = "";
	
	$pictureId = !empty($arResult["DETAIL_PICTURE"]["ID"]) ? $arResult["DETAIL_PICTURE"]["ID"] : false;
	if( empty($pictureId) && !empty($arResult["FIELDS"]["PREVIEW_PICTURE"]["ID"]) ) $pictureId = $arResult["FIELDS"]["PREVIEW_PICTURE"]["ID"];
	
	if( !empty($pictureId) && $isSafari ) {
		$arResult["PICTURE_SRC"] = CFile::ResizeImageGet($pictureId, ["width" => 1180, "height" => 640], BX_RESIZE_IMAGE_PROPORTIONAL)["src"];
	} else if( !empty($pictureId) ) {
		$arResult["PICTURE_SRC"] = Picture::getResizeWebpFileId($pictureId, 1180, 640, false)["WEBP_SRC"];
	}
	
	$arResult["ADDITIONAL_ITEMS"] = [];
	
	$sortArr = [];
	if( !empty($arParams["SORT_BY1"]) && !empty($arParams["SORT_ORDER1"]) ) {
		$sortArr[$arParams["SORT_BY1"]] = $arParams["SORT_ORDER1"];
	}
	if( !empty($arParams["SORT_BY2"]) && !empty($arParams["SORT_ORDER2"]) ) {
		$sortArr[$arParams["SORT_BY2"]] = $arParams["SORT_ORDER2"];
	}
	if( empty($sortArr) ) $sortArr["ID"] = "ASC";
	
	$additionalIds = [];
	if( !empty($arResult["PROPERTIES"]["ADDITIONAL"]["VALUE"]) ) {
		$additionalIds = $arResult["PROPERTIES"]["ADDITIONAL"]["VALUE"];
	} else {
		$res = CIBlockElement::GetList( $sortArr, ["IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y"], false, ["nPageSize" => 4, 'nElementID' => $arResult["ID"]], ["ID"] );
		while( $arItem = $res->GetNext() ) {
			if( $arItem["ID"] === $arResult["ID"] ) continue;
			$additionalIds[] = $arItem["ID"];
		}
	}
	
	if( !empty($additionalIds) ) {
		$res = CIBlockElement::GetList( $sortArr, ["IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "ID" => $additionalIds], false );
		while( $obItem = $res->GetNextElement() ) {
			
			$arItem = $obItem->GetFields();
			$arItem["PROPERTIES"] = $obItem->GetProperties();
			
			if( $arItem["ID"] === $arResult["ID"] ) continue;
			
			$item = ["ID" => $arItem["ID"], "NAME" => !empty($arItem["PROPERTIES"]["TITLE"]["~VALUE"]) ? $arItem["PROPERTIES"]["TITLE"]["~VALUE"] : $arItem["NAME"], "LINK" => $arItem["DETAIL_PAGE_URL"]];
			
			if( !empty($arItem["PREVIEW_PICTURE"]) && $isSafari ) {
				$item["PICTURE"] = [
    				"SMALL" => !empty($arItem["PROPERTIES"]["PICT_SECOND"]["VALUE"]) ? CFile::ResizeImageGet($arItem["PROPERTIES"]["PICT_SECOND"]["VALUE"], ["width" => 130, "height" => 130], BX_RESIZE_IMAGE_EXACT)["src"] : CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], ["width" => 130, "height" => 130], BX_RESIZE_IMAGE_EXACT)["src"],
    				"MEDIUM" => CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], ["width" => 573, "height" => 311], BX_RESIZE_IMAGE_EXACT)["src"],
    				"BIG" => CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], ["width" => 1180, "height" => 640], BX_RESIZE_IMAGE_EXACT)["src"]
    			];
    		} else if( !empty($arItem["PREVIEW_PICTURE"]) ) {
				$item["PICTURE"] = [
    				"SMALL" => !empty($arItem["PROPERTIES"]["PICT_SECOND"]["VALUE"]) ? Picture::getResizeWebpFileId($arItem["PROPERTIES"]["PICT_SECOND"]["VALUE"], 130, 130, false)["WEBP_SRC"] : Picture::getResizeWebpFileId($arItem["PREVIEW_PICTURE"], 130, 130, false)["WEBP_SRC"],
    				"MEDIUM" => Picture::getResizeWebpFileId($arItem["PREVIEW_PICTURE"], 573, 311, false)["WEBP_SRC"],
    				"BIG" =>  Picture::getResizeWebpFileId($arItem["PREVIEW_PICTURE"], 1180, 640, false)["WEBP_SRC"]
    			];
			}
			
    		$item["PREVIEW_TEXT"] = $arItem["~PREVIEW_TEXT"];
    		$item["DATE"] = !empty($arItem["ACTIVE_FROM"]) ? explode(" ", $arItem["ACTIVE_FROM"])[0] : explode(" ", $arItem["TIMESTAMP_X"])[0];
    		$item["SECTION"] = [];

    		if( !empty($arItem["IBLOCK_SECTION_ID"]) && isset($arResult["SECTIONS"][$arItem["IBLOCK_SECTION_ID"]]) ) {
    			$item["SECTION"] = ["NAME" => $arResult["SECTIONS"][$arItem["IBLOCK_SECTION_ID"]], "ID" => $arItem["IBLOCK_SECTION_ID"]];
    		}
			
    		$arResult["ADDITIONAL_ITEMS"][] = $item;
		}
	}
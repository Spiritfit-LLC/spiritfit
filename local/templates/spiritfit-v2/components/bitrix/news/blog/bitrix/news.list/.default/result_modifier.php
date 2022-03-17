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

    $itemsArr = [];
    foreach( $arResult["ITEMS"] as $arItem ) {
    	$item = ["ID" => $arItem["ID"], "NAME" => $arItem["~NAME"], "LINK" => $arItem["DETAIL_PAGE_URL"]];
    	if( !empty($arItem["PREVIEW_PICTURE"]) && $isSafari ) {
			$item["PICTURE"] = [
    			"SMALL" => !empty($arItem["PROPERTIES"]["PICT_SECOND"]["VALUE"]) ? CFile::ResizeImageGet($arItem["PROPERTIES"]["PICT_SECOND"]["VALUE"], ["width" => 130, "height" => 130], BX_RESIZE_IMAGE_EXACT)["src"] : CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], ["width" => 130, "height" => 130], BX_RESIZE_IMAGE_EXACT)["src"],
    			"MEDIUM" => CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], ["width" => 573, "height" => 311], BX_RESIZE_IMAGE_EXACT)["src"],
    			"BIG" => CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], ["width" => 1180, "height" => 640], BX_RESIZE_IMAGE_EXACT)["src"]
    		];
    	} else if( !empty($arItem["PREVIEW_PICTURE"]) ) {
			$item["PICTURE"] = [
    			"SMALL" => !empty($arItem["PROPERTIES"]["PICT_SECOND"]["VALUE"]) ? Picture::getResizeWebpFileId($arItem["PROPERTIES"]["PICT_SECOND"]["VALUE"], 130, 130, false)["WEBP_SRC"] : Picture::getResizeWebp($arItem["PREVIEW_PICTURE"], 130, 130, false)["WEBP_SRC"],
    			"MEDIUM" => Picture::getResizeWebp($arItem["PREVIEW_PICTURE"], 573, 311, false)["WEBP_SRC"],
    			"BIG" =>  Picture::getResizeWebp($arItem["PREVIEW_PICTURE"], 1180, 640, false)["WEBP_SRC"]
    		];
		}
    	$item["PREVIEW_TEXT"] = $arItem["~PREVIEW_TEXT"];
    	$item["DATE"] = !empty($arItem["ACTIVE_FROM"]) ? explode(" ", $arItem["ACTIVE_FROM"])[0] : explode(" ", $arItem["TIMESTAMP_X"])[0];
    	$item["SECTION"] = [];

    	if( !empty($arItem["IBLOCK_SECTION_ID"]) && isset($arResult["SECTIONS"][$arItem["IBLOCK_SECTION_ID"]]) ) {
    		$item["SECTION"] = ["NAME" => $arResult["SECTIONS"][$arItem["IBLOCK_SECTION_ID"]], "ID" => $arItem["IBLOCK_SECTION_ID"]];
    	}

    	$itemsArr[] = $item;
    }
    unset($arResult["ITEMS"]);

    $arResult["ITEMS"] = $itemsArr;
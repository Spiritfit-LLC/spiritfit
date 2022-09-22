<?
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
	
	$abonementsIBlockId = Utils::GetIBlockIDBySID('subscription');
	$pageBlocks = [
		"main" => [
			"HEADER_IMAGE",
			"HEADER_TITLE1",
			"HEADER_TITLE2",
			"HEADER_DESCRIPTION",
			"HEADER_SORT",
		],
		"block2" => [
			"BLOCK2_TITLE",
			"BLOCK2_DESCRIPTION",
			"BLOCK2_LIST",
			"BLOCK2_SORT",
		],
		"block3" => [
			"BLOCK3_TITLE",
			"BLOCK3_DESCRIPTION",
			"BLOCK3_LIST",
			"BLOCK3_LOGOS",
			"BLOCK3_SORT",
		],
		"block4" => [
			"BLOCK4_FORM_ID",
		],
	];
	
	$arResult['BLOCKS'] = [];
	
	foreach( $pageBlocks as $blockName => $items ) {
		reset($items);
		$lastKey = key($items);
		
		$resArr = [
			"FILE_NAME" => $blockName.".php",
			"SORT" => !empty($arResult["PROPERTIES"][$items[$lastKey]]["VALUE"]) ? intval($arResult["PROPERTIES"][$items[$lastKey]]["VALUE"]) : 0,
			"PROPERTIES" => []
		];
		
		foreach( $items as $k => $propName ) {
			if( $k == $lastKey ) continue;
			if( !empty($arResult["PROPERTIES"][$items[$propName]]["VALUE"]) ) {
				if( empty($arResult["PROPERTIES"][$items[$propName]]["LINK_IBLOCK_ID"]) && $arResult["PROPERTIES"][$items[$propName]]["LINK_IBLOCK_ID"] != $abonementsIBlockId ) {
					$values = [];
					if( !is_array($arResult["PROPERTIES"][$items[$propName]]["VALUE"]) ) {
						$values[] = $arResult["PROPERTIES"][$items[$propName]]["VALUE"];
					} else {
						$values = $arResult["PROPERTIES"][$items[$propName]]["VALUE"];;
					}
					foreach( $values as $id ) {
						$res = CIBlockElement::GetByID($id);
						if($resObj = $res->GetNextElement()) {
							$arFields = $resObj->GetFields();
							$arFields["PROPERTIES"] = $resObj->GetProperties();
							
							$resArr["PROPERTIES"][$propName][] = $arFields;
						}
					}
				} else {
					$resArr["PROPERTIES"][$propName] = $arResult["PROPERTIES"][$items[$propName]]["VALUE"];
				}
			}
		}
		
		$arResult['BLOCKS'][] = $resArr;
	}
	
	usort($arResult['BLOCKS'], function($item1, $item2) { return $item1['SORT'] <=> $item2['SORT']; });

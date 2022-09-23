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
			"BLOCK4_FORM_TYPE",
			"BLOCK4_CLIENT_TYPE",
			"BLOCK4_SORT",
		],
	];
	$imageSizes = [
	];
	
	$arResult['BLOCKS'] = [];
	foreach( $pageBlocks as $blockName => $items ) {
		end($items);
		$lastKey = key($items);
		
		$resArr = [
			"FILE_NAME" => $blockName.".php",
			"SORT" => !empty($arResult["PROPERTIES"][$items[$lastKey]]["VALUE"]) ? intval($arResult["PROPERTIES"][$items[$lastKey]]["VALUE"]) : 0,
			"PROPERTIES" => []
		];
		foreach( $items as $k => $propName ) {
			if( $k == $lastKey ) continue;
			if( !empty($arResult["PROPERTIES"][$propName]["VALUE"]) ) {
				if( !empty($arResult["PROPERTIES"][$propName]["LINK_IBLOCK_ID"]) && $arResult["PROPERTIES"][$propName]["LINK_IBLOCK_ID"] != $abonementsIBlockId ) {
					$values = [];
					if( !is_array($arResult["PROPERTIES"][$propName]["VALUE"]) ) {
						$values[] = $arResult["PROPERTIES"][$propName]["VALUE"];
					} else {
						$values = $arResult["PROPERTIES"][$propName]["VALUE"];;
					}
					foreach( $values as $id ) {
						$res = CIBlockElement::GetByID($id);
						if($resObj = $res->GetNextElement()) {
							$arFields = $resObj->GetFields();
							$arFields["PROPERTIES"] = $resObj->GetProperties();
							
							foreach( $arFields["PROPERTIES"] as &$property ) {
								if( $property["PROPERTY_TYPE"] !== "F" || empty($property["VALUE"]) ) continue;
								if( !is_array($property["VALUE"]) ) {
									$path = CFile::GetPath($property["VALUE"]);
									$property["VALUE"] =
									[
										"SRC" => $path,
										"DESCRIPTION" => !empty($property["~DESCRIPTION"]) ? $property["~DESCRIPTION"] : "",
										"TYPE" => pathinfo($path, PATHINFO_EXTENSION)
									];
								} else {
									$resPropsArr = [];
									foreach($property["VALUE"] as $num => $imageId) {
										$path = CFile::GetPath($imageId);
										$resPropsArr[] =
										[
											"SRC" => $path,
											"DESCRIPTION" => !empty($property["~DESCRIPTION"][$num]) ? $property["~DESCRIPTION"][$num] : "",
											"TYPE" => pathinfo($path, PATHINFO_EXTENSION)
										];
									}
									$property["VALUE"] = $resPropsArr;
									unset($resPropsArr);
								}
							}
							unset($property);
							
							if( !empty($arFields["PREVIEW_PICTURE"]) ) $arFields["PREVIEW_PICTURE"] = CFile::ResizeImageGet($arFields["PREVIEW_PICTURE"], array("width" => 280, "height" => 280), BX_RESIZE_IMAGE_PROPORTIONAL, false)["src"];
							if( !empty($arFields["DETAIL_PICTURE"]) ) $arFields["DETAIL_PICTURE"] = CFile::ResizeImageGet($arFields["DETAIL_PICTURE"], array("width" => 650, "height" => 650), BX_RESIZE_IMAGE_PROPORTIONAL, false)["src"];
							
							$resArr["PROPERTIES"][$propName][] = $arFields;
						}
					}
				} else if( !empty($arResult["PROPERTIES"][$propName]["~VALUE"]["TEXT"]) ) {
					$resArr["PROPERTIES"][$propName] = $arResult["PROPERTIES"][$propName]["~VALUE"]["TEXT"];
				} else if($arResult["PROPERTIES"][$propName]["PROPERTY_TYPE"] == "F") {
					
					$hasSizes = isset($imageSizes[$propName]);
					
					if( !is_array($arResult["PROPERTIES"][$propName]["VALUE"]) ) {
						$resArr["PROPERTIES"][$propName] =
						[
							"SRC" => $hasSizes ? CFile::ResizeImageGet($arResult["PROPERTIES"][$propName]["VALUE"], array("width" => $imageSizes[$propName][0], "height" => $imageSizes[$propName][1]), BX_RESIZE_IMAGE_PROPORTIONAL, false)["src"] : CFile::GetPath($arResult["PROPERTIES"][$propName]["VALUE"]),
							"DESCRIPTION" => !empty($arResult["PROPERTIES"][$propName]["~DESCRIPTION"]) ? $arResult["PROPERTIES"][$propName]["~DESCRIPTION"] : ""
						];
					} else {
						$resArr["PROPERTIES"][$propName] = [];
						foreach($arResult["PROPERTIES"][$propName]["VALUE"] as $num => $imageId) {
							$resArr["PROPERTIES"][$propName][] =
							[
								"SRC" => $hasSizes ? CFile::ResizeImageGet($imageId, array("width" => $imageSizes[$propName][0], "height" => $imageSizes[$propName][1]), BX_RESIZE_IMAGE_PROPORTIONAL, false)["src"] : CFile::GetPath($imageId),
								"DESCRIPTION" => !empty($arResult["PROPERTIES"][$propName]["~DESCRIPTION"][$num]) ? $arResult["PROPERTIES"][$propName]["~DESCRIPTION"][$num] : ""
							];
						}
					}
				} else {
					$resArr["PROPERTIES"][$propName] = $arResult["PROPERTIES"][$propName]["~VALUE"];
				}
			}
		}
		
		$arResult['BLOCKS'][] = $resArr;
	}
	
	usort($arResult['BLOCKS'], function($item1, $item2) { return $item1['SORT'] <=> $item2['SORT']; });
	
	$this->__component->SetResultCacheKeys(["BLOCKS"]);

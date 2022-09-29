<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$abonementsIBlockId = Utils::GetIBlockIDBySID('subscription');
$pageBlocks = [
    "main" => [
        "HEADER_IMAGE",
        "HEADER_DESCRIPTION",
        "HEADER_BUTTON",
        "HEADER_BUTTON_LINK",
        "HEADER_SORT",
    ],
    "block2" => [
        "BLOCK2_IMAGE",
        "BLOCK2_TITLE1",
        "BLOCK2_TITLE2",
        "BLOCK2_LIST1",
        "BLOCK2_LIST2",
        "BLOCK2_ACTIVE",
		"BLOCK2_SORT"
    ],
    "block3" => [
        "BLOCK3_TITLE",
        "BLOCK3_LIST",
        "BLOCK3_ACTIVE",
		"BLOCK3_SORT"
    ],
    "block4" => [
        "BLOCK4_TITLE",
        "BLOCK4_LIST",
        "BLOCK4_BUTTON",
        "BLOCK4_BUTTON_LINK",
        "BLOCK4_ACTIVE",
		"BLOCK4_SORT"
    ],
    "block5" => [
        "BLOCK5_TITLE",
        "BLOCK5_LIST",
        "BLOCK5_BUTTON",
        "BLOCK5_BUTTON_LINK",
        "BLOCK5_VIDEO",
        "BLOCK5_ACTIVE",
		"BLOCK5_SORT"
    ],
    "block6" => [
        "BLOCK6_TITLE",
        "BLOCK6_DESCRIPTION",
        "BLOCK6_LIST",
        "BLOCK6_ACTIVE",
        "BLOCK6_BUTTON_TITLE",
		"BLOCK6_SORT"
    ],
    "block7" => [
        "BLOCK7_TITLE",
        "BLOCK7_DESCRIPTION",
        "BLOCK7_LIST",
        "BLOCK7_GIFT_LIST",
        "BLOCK7_ACTIVE",
		"BLOCK7_SORT"
    ],
    "block8" => [
        "BLOCK8_TITLE",
        "BLOCK8_LIST",
        "BLOCK8_ACTIVE",
		"BLOCK8_SORT"
    ],
    "block9" => [
        "BLOCK9_TITLE",
        "BLOCK9_FORM_TYPE",
        "BLOCK9_CLIENT_TYPE",
		"BLOCK9_ACTIVE",
        "BLOCK9_SORT"
    ],
];
$imageSizes = [
    "HEADER_IMAGE" => [573, 902],
    "BLOCK2_IMAGE" => [639, 762],
    "BLOCK3_LIST" => [44, 55],
    "BLOCK7_LIST" => [83, 72],
    "BLOCK7_GIFT_LIST" => [42, 42],
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
                    $path = $hasSizes ? CFile::ResizeImageGet($arResult["PROPERTIES"][$propName]["VALUE"], array("width" => $imageSizes[$propName][0], "height" => $imageSizes[$propName][1]), BX_RESIZE_IMAGE_PROPORTIONAL, false)["src"] : CFile::GetPath($arResult["PROPERTIES"][$propName]["VALUE"]);
                    $resArr["PROPERTIES"][$propName] =
                        [
                            "SRC" => $path,
                            "DESCRIPTION" => !empty($arResult["PROPERTIES"][$propName]["~DESCRIPTION"]) ? $arResult["PROPERTIES"][$propName]["~DESCRIPTION"] : "",
                            "TYPE" => pathinfo($path, PATHINFO_EXTENSION)
                        ];
                } else {
                    $resArr["PROPERTIES"][$propName] = [];
                    foreach($arResult["PROPERTIES"][$propName]["VALUE"] as $num => $imageId) {
                        $path = $hasSizes ? CFile::ResizeImageGet($imageId, array("width" => $imageSizes[$propName][0], "height" => $imageSizes[$propName][1]), BX_RESIZE_IMAGE_PROPORTIONAL, false)["src"] : CFile::GetPath($imageId);
                        $resArr["PROPERTIES"][$propName][] =
                            [
                                "SRC" => $path,
                                "DESCRIPTION" => !empty($arResult["PROPERTIES"][$propName]["~DESCRIPTION"][$num]) ? $arResult["PROPERTIES"][$propName]["~DESCRIPTION"][$num] : "",
                                "TYPE" => pathinfo($path, PATHINFO_EXTENSION)
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

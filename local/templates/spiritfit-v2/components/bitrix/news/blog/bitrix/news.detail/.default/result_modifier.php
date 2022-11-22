<?
	if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
	
	use \ImageConverter\Picture;
	
	if( !function_exists("getAdditionaBlogItem") ) {
		function getAdditionaBlogItem( $arItem, $isSafari ) {
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
	
	$arResult["SECTION_NAMES"] = [];
	if( !empty($arResult["IBLOCK_SECTION_ID"]) ) {
		$dbGroups = CIBlockElement::GetElementGroups($arResult["ID"], true, ["ID", "NAME"]);
    	while( $arGroup = $dbGroups->Fetch() ) {
    		$arResult["SECTION_NAMES"][] = $arGroup["NAME"];
    	}
	}
	
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
		$res = CIBlockElement::GetList( $sortArr, ["IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y"], false, ["nPageSize" => 1, 'nElementID' => $arResult["ID"]], ["ID"] );
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
			
    		$arResult["ADDITIONAL_ITEMS"][] = getAdditionaBlogItem($arItem, $isSafari);
		}
	}
	
	$arResult["LEFT_ITEMS"] = [];
	$res = CIBlockElement::GetList( $sortArr, ["IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "PROPERTY_IN_LEFT_VALUE" => "Да"], false );
	while( $obItem = $res->GetNextElement() ) {
		$arItem = $obItem->GetFields();
		$arItem["PROPERTIES"] = $obItem->GetProperties();
		$arResult["LEFT_ITEMS"][] = getAdditionaBlogItem($arItem, $isSafari);
	}

    function remove_empty_html_tags($html)
    {
        return preg_replace("/\xEF\xBB\xBF/", "", $html);
    }

    for ($i=0; $i<count($arResult['PROPERTIES']['BLOG_TEXT']['VALUE']); $i++){
        $arResult['TXT'][]=[
            'TEXT'=>remove_empty_html_tags(htmlspecialchars_decode($arResult['PROPERTIES']['BLOG_TEXT']['VALUE'][$i]['TEXT'])),
            'TITLE'=>$arResult['PROPERTIES']['BLOG_TEXT']['DESCRIPTION'][$i],
            'ID'=>'section_'.$i,
        ];
    }

/* Получаем значения SEO */
$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues(Utils::GetIBlockIDBySID('blog'), $arResult["ID"]);
$seoValues = $ipropValues->getValues();

if (empty($seoValues['ELEMENT_META_TITLE'])){
    $APPLICATION->SetPageProperty('title',$arResult["NAME"].' - Блог фитнес-клуба Spirit Fitness');
}
if (empty($seoValues['ELEMENT_META_DESCRIPTION'])){
    $APPLICATION->SetPageProperty('description',$arResult["NAME"].'. В разделе «Блог» вы можете узнать много полезной информации о тренировках. Блог фитнес-клуба Spirit Fitness.');
}

CIBlockElement::SetPropertyValues($arResult['ID'], $arResult['IBLOCK_ID'], (int)$arResult['PROPERTIES']['SHOWING_COUNT']['VALUE']+1, "SHOWING_COUNT");

if(empty($arResult['PROPERTIES']['RATING']['VALUE'])){
    $arResult['PROPERTIES']['RATING']['VALUE']=0;
}
$arResult['PROPERTIES']['RATING_PROCENT']=$arResult['PROPERTIES']['RATING']['VALUE']/5*100;

$date = str_replace('.', '-', $arResult["DATE"]);
$arResult["datePublished"]=date('Y-m-d', strtotime($date));

$arFilter = array(
    "IBLOCK_ID" => Utils::GetIBlockIDBySID('clubs'),
    "PROPERTY_SOON" => false,
    "ACTIVE" => "Y",
    "PROPERTY_HIDE_LINK_VALUE"=>false,
    "!PROPERTY_NUMBER"=>"00"
);
$dbElements = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilter, false, false, array("ID", "NAME", "PROPERTY_NUMBER"));

while ($res = $dbElements->fetch()) {
    $arResult["CLUBS"][$res["PROPERTY_NUMBER_VALUE"]] = $res["NAME"];
}
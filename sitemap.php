<?
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	
	\Bitrix\Main\Loader::includeModule("iblock");
	
	$sitemapArr = [];
	$dirsArr = [
		"/trenirovki/",
		"/trenirovki/atmosfera/",
		"/trenirovki/gruppovye-trenirovki/",
		"/trenirovki/onlayn-trenirovki/",
		"/trenirovki/trenazhernyy-zal/",
		"/kachestvo-obsluzhivaniya/",
		"/mobilnoe-prilozheniya/",
		"/mobilnoe-prilozheniya/specproekty/"
	];
	
	/*Клубы*/
	$clubsArr = [];
	$res = CIBlockElement::GetList([], ["IBLOCK_ID" => 6, "ACTIVE" => "Y", "PROPERTY_HIDE_LINK" => false], false, false);
	while($ob = $res->GetNextElement()) {
		$arFields = $ob->GetFields();
		$arFields["PROPERTIES"] = $ob->GetProperties();
		
		if( !empty($arFields["PROPERTIES"]["ABONEMENTS"]["VALUE"]) && !empty($arFields["PROPERTIES"]["NUMBER"]["VALUE"]) ) {
			$clubsArr[$arFields["PROPERTIES"]["NUMBER"]["VALUE"]] = $arFields["PROPERTIES"]["ABONEMENTS"]["VALUE"];
		}
		
		$lastmod = strtotime($arFields['TIMESTAMP_X']);
		$sitemapArr[] = [ 'loc' => $arFields["DETAIL_PAGE_URL"], 'lastmod' => date('Y-m-d', $lastmod).'T'.date('H:i:sP', $lastmod)];
	}
	
	/*Абонементы*/
	$res = CIBlockElement::GetList([], ["IBLOCK_ID" => 9, "ACTIVE" => "Y", "PROPERTY_HIDDEN" => false], false, false);
	while($arFields = $res->GetNext()) {
		$lastmod = strtotime($arFields['TIMESTAMP_X']);
		$sitemapArr[] = [ 'loc' => $arFields["DETAIL_PAGE_URL"], 'lastmod' => date('Y-m-d', $lastmod).'T'.date('H:i:sP', $lastmod)];
		
		foreach( $clubsArr as $clubId => $abinementsId ) {
			if( in_array($arFields["ID"], $abinementsId) ) {
				$sitemapArr[] = [ 'loc' => $arFields["DETAIL_PAGE_URL"] . $clubId . '/', 'lastmod' => date('Y-m-d', $lastmod).'T'.date('H:i:sP', $lastmod)];
			}
		}
	}
	
	/*Файлы*/
	foreach( $dirsArr as $dir ) {
		$fullDir = $_SERVER["DOCUMENT_ROOT"] . $dir . "index.php";
		if( file_exists($fullDir) ) {
			$lastmod = filemtime($fullDir);
			$sitemapArr[] = [ 'loc' => $dir, 'lastmod' => date('Y-m-d', $lastmod).'T'.date('H:i:sP', $lastmod)];
		}
	}
	
	$serverBase = '://' . $_SERVER["HTTP_HOST"];
	if( !empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on" ) {
		$serverBase = 'https' . $serverBase;
	} else {
		$serverBase = 'http' . $serverBase;
	}
	
	header('Content-Type: application/xml');
	
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
	foreach( $sitemapArr as $item ) {
		echo '<url><loc>'.$serverBase.$item["loc"].'</loc><lastmod>'.$item["lastmod"].'</lastmod></url>';
	}
	echo '</urlset>';
	
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
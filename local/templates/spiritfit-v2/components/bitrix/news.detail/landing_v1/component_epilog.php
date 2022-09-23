<?
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
	
	foreach( $arResult['BLOCKS'] as $block ) {
		$filePath = $templateFolder . "/blocks/" . $block["FILE_NAME"];
		$APPLICATION->IncludeFile( $filePath, ["BLOCKS" => $block["PROPERTIES"]], 
			[
				"MODE" => "php",
				"NAME" => "Редактирование включаемой области раздела",
				"TEMPLATE"  => ""
			]
		);
	}
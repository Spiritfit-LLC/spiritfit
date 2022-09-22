<?
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
	
	/** @var array $arParams */
	/** @var array $arResult */
	/** @global CMain $APPLICATION */
	/** @global CUser $USER */
	/** @global CDatabase $DB */
	/** @var CBitrixComponentTemplate $this */
	/** @var string $templateName */
	/** @var string $templateFile */
	/** @var string $templateFolder */
	/** @var string $componentPath */
	/** @var CBitrixComponent $component */
	
	$this->setFrameMode(true);
	
	foreach( $arResult['BLOCKS'] as $block ) {
		$filePath = $templateFolder . "/blocks/" . $block["FILE_NAME"];
		$APPLICATION->IncludeFile( $filePath, $block["PROPERTIES"], 
			[
				"MODE" => "php",
				"NAME" => "Редактирование включаемой области раздела",
				"TEMPLATE"  => ""
			]
		);
	}

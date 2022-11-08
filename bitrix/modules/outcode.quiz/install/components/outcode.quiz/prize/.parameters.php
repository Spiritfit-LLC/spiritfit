<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;
use Bitrix\Iblock;

if ( !Loader::includeModule('iblock') ) return;

$arIBlockType = CIBlockParameters::GetIBlockTypes();
$arIBlock = [];

$arIBlock = array();
$iblockFilter = (
	!empty($arCurrentValues['IBLOCK_TYPE'])
	? array('TYPE' => $arCurrentValues['IBLOCK_TYPE'], 'ACTIVE' => 'Y')
	: array('ACTIVE' => 'Y')
);
$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $iblockFilter);
while ($arr = $rsIBlock->Fetch()) {
	$id = (int)$arr['ID'];
	if (isset($offersIblock[$id]))
		continue;
	$arIBlock[$id] = '['.$id.'] '.$arr['NAME'];
}

$propertyNum = [];
$propertyMsg = [];

$iblockExists = (!empty($arCurrentValues['IBLOCK_ID']) && (int)$arCurrentValues['IBLOCK_ID'] > 0);
if ($iblockExists) {
	$propertyIterator = Iblock\PropertyTable::getList(array(
		'select' => array('ID', 'IBLOCK_ID', 'NAME', 'CODE', 'PROPERTY_TYPE', 'MULTIPLE', 'LINK_IBLOCK_ID', 'USER_TYPE', 'SORT'),
		'filter' => array('=IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'], '=ACTIVE' => 'Y' , '=PROPERTY_TYPE' => [\Bitrix\Iblock\PropertyTable::TYPE_NUMBER, \Bitrix\Iblock\PropertyTable::TYPE_STRING]),
		'order' => array('SORT' => 'ASC', 'NAME' => 'ASC')
	));
	while ($property = $propertyIterator->fetch()) {

		$propertyCode = (string)$property['CODE'];
		if ($propertyCode == '')
			$propertyCode = $property['ID'];
		$propertyName = '['.$propertyCode.'] '.$property['NAME'];

		if( $property['PROPERTY_TYPE'] == 'N' ) {
			$propertyNum[$propertyCode] = $propertyName;
		}
		if( $property['PROPERTY_TYPE'] == 'S' ) {
			$propertyMsg[$propertyCode] = $propertyName;
		}
	}
}

$allowedDays = [
	'Monday' => 'Monday',
	'Tuesday' => 'Tuesday',
	'Wednesday' => 'Wednesday',
	'Thursday' => 'Thursday',
	'Friday' => 'Friday',
	'Saturday' => 'Saturday',
	'Sunday' => 'Sunday'
];

$allowedFirstDayHoursAfter = [];
$objDateTime = new DateTime('01.01.2022 00:00:00');
for( $i = 0; $i < 24; $i++ ) {
	$timeString = $objDateTime->format('H:i:s');
    $allowedFirstDayHoursAfter[$timeString] = $timeString;
    $objDateTime->add('1 hour');
}


$arComponentParameters = [
	'GROUPS' => [],
	'PARAMETERS' => [
		"IBLOCK_TYPE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("QUIZ_IBLOCK_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlockType,
			"REFRESH" => "Y",
		),
		"IBLOCK_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("QUIZ_IBLOCK_IBLOCK"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arIBlock,
			"REFRESH" => "Y",
		),
		"PROPERTY_NUM" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("QUIZ_IBLOCK_PROPERTY_NUM"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			'REFRESH' => 'Y',
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $propertyNum,
		),
        "PROPERTY_MINVALUE" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("QUIZ_IBLOCK_PROPERTY_MINVALUE"),
            "TYPE" => "LIST",
            "MULTIPLE" => "N",
            'REFRESH' => 'Y',
            "ADDITIONAL_VALUES" => "Y",
            "VALUES" => $propertyNum,
        ),
		"PROPERTY_MSG" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("QUIZ_IBLOCK_PROPERTY_MSG"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			'REFRESH' => 'Y',
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $propertyMsg,
		),
		"SHOW_RESULT_ON_DAYS" => array(
            'PARENT' => "BASE",
            'NAME' => GetMessage("QUIZ_SHOW_RESULT_ON_DAYS"),
            'TYPE' => "LIST",
            'ADDITIONAL_VALUES' => 'N',
            "MULTIPLE" => "Y",
            'VALUES' => $allowedDays,
            'REFRESH' => 'N',
        ),
        "SHOW_RESULT_ON_FIRST" => array(
            'PARENT' => "BASE",
            'NAME' => GetMessage("QUIZ_SHOW_RESULT_ON_FIRST"),
            'TYPE' => "LIST",
            'ADDITIONAL_VALUES' => 'N',
            'VALUES' => $allowedFirstDayHoursAfter,
            'REFRESH' => 'N',
        ),
        'CALCULATE_FULL_RESULT' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage("QUIZ_CALCULATE_FULL_RESULT"),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
			'REFRESH' => 'Y',
		),
		"ELEMENT_ID" => array(
            'PARENT' => 'BASE',
			'NAME' => GetMessage('QUIZ_ELEMENT_ID'),
			'TYPE' => 'STRING',
			'DEFAULT' => '',
        ),
        "LIMIT" => array(
            'PARENT' => 'BASE',
            'NAME' => GetMessage('QUIZ_LIMIT'),
            'TYPE' => 'STRING',
            'DEFAULT' => '10',
        ),
		'CACHE_TIME' => [
			'DEFAULT' => 36000
		],
	],
];
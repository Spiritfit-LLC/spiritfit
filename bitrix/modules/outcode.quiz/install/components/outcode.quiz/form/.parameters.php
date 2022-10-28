<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use \Bitrix\Main\Type\DateTime;

$standartTimes = [];
$objDateTime = new DateTime('01.01.2022 00:00:00');
for( $i=0; $i<24; $i++ ) {
    $timeString = $objDateTime->format('H:i:s');
    $standartTimes[$timeString] = $timeString;
    $objDateTime->add('1 hour');
}

$arComponentParameters = [
	'GROUPS' => [],
	'PARAMETERS' => [
		'API_PATH' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('API_PATH'),
			'TYPE' => 'STRING',
			'DEFAULT' => '',
		),
        'PERSONAL_PATH' => array(
            'PARENT' => 'BASE',
            'NAME' => GetMessage('PERSONAL_PATH'),
            'TYPE' => 'STRING',
            'DEFAULT' => '',
        ),
		"SHOW_RESULT_ON_TIME" => array(
            'PARENT' => "BASE",
            'NAME' => GetMessage("SHOW_RESULT_ON_TIME"),
            'TYPE' => "LIST",
            'ADDITIONAL_VALUES' => 'N',
            'VALUES' => $standartTimes,
            'REFRESH' => 'Y',
        ),
		'SHOW_RESULTS_ON_LAST_ALWAYS' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage("SHOW_RESULTS_ON_LAST_ALWAYS"),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
			'REFRESH' => 'Y',
		),
		'CACHE_TIME' => [
			'DEFAULT' => 36000
		],
	],
];
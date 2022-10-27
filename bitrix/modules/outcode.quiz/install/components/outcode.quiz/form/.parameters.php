<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;

$daysOfWeek = [
	'Monday' => GetMessage('QUIZ_DAY_1'),
	'Tuesday' => GetMessage('QUIZ_DAY_2'),
	'Wednesday' => GetMessage('QUIZ_DAY_3'),
	'Thursday' => GetMessage('QUIZ_DAY_4'),
	'Friday' => GetMessage('QUIZ_DAY_5'),
	'Saturday' => GetMessage('QUIZ_DAY_6'),
	'Sunday' => GetMessage('QUIZ_DAY_7')
];

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
		"SHOW_QUESTION_ON_TIME" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SHOW_QUESTION_ON_TIME"),
			"TYPE" => "STRING",
			'MULTIPLE' => 'Y',
			'HIDDEN' => 'N',
			'DEFAULT' => '',
		),
		'SHOW_QUESTION_INTERVAL' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('SHOW_QUESTION_INTERVAL'),
			'TYPE' => 'STRING',
			'DEFAULT' => '3600',
		),
		'SHOW_RESULTS_DAY' => array(
			'PARENT' => "BASE",
			'NAME' => GetMessage("SHOW_RESULTS_DAY"),
			'TYPE' => "LIST",
			'ADDITIONAL_VALUES' => 'Y',
			'VALUES' => $daysOfWeek,
			'REFRESH' => 'Y',
		),
		'SHOW_RESULTS_ON_LAST' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('SHOW_RESULTS_ON_LAST'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y',
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
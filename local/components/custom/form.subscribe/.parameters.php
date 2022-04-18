<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!CModule::IncludeModule("form")) return;

$arComponentParameters = array(
	"GROUPS" => array(),
	"PARAMETERS" => array(
		"FORM_TYPE" => Array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => "Тип события формы в 1С",
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "",
		),
		"ACTION_TYPE" => Array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => "Тип события отправки в 1С",
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "",
		)
	),
);
?>
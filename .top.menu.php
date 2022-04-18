<?
$aMenuLinks = Array(
	Array(
		"Клубы", 
		"/clubs/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Тренировки", 
		"/trenirovki/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Приложение", 
		"/mobilnoe-prilozheniya/", 
		Array(), 
		Array(), 
		"" 
	),
    Array(
        "Абонементы",
        "/abonement/",
        Array(),
        Array(),
        ""
    ),
);

CModule::IncludeModule("iblock");
$siteProperties = Utils::getInfo();

global $USER;

if( !empty($siteProperties["PROPERTIES"]["VIDEO_TRANSLATION_SHOW"]["VALUE"])) {
	$aMenuLinks[] = Array(
		"SPIRIT.TV", 
		"/spirittv/", 
		Array(), 
		Array(), 
		""
	);
}
?>
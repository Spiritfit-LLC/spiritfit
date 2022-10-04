<?

CModule::IncludeModule("iblock");

$dbElements = CIBlockElement::GetList(array("PROPERTY_NUMBER" => "ASC"), array("IBLOCK_CODE" => "clubs", "PROPERTY_SOON" => false), false, false, array("ID", "NAME", "PROPERTY_NUMBER_VALUE"));

while ($res = $dbElements->fetch()) {
    $arResult["QUESTIONS"]["club"]['ITEMS'][] = array(
        "ID" => $res["ID"],
        "MESSAGE" => $res["NAME"],
        "NUMBER" => $res["PROPERTY_NUMBER_VALUE"]
    );
}
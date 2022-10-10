<?
CModule::IncludeModule('iblock');
$arFilter = array('IBLOCK_ID'=>6,'ACTIVE'=>'Y', '!ID'=>265, '!PROPERTY_SOON_VALUE'=>'Y', '!PROPERTY_NOT_OPEN_YET_VALUE'=>'Да', '!PROPERTY_HIDE_LINK_VALUE'=>'Да');
$arSelect = array('NAME');
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

while ($arClub = $res->GetNext()) {
	$arResult['CLUBS'][] = strip_tags($arClub['~NAME']);	
}
?>

<?

class ElementUpdate
{
    // создаем обработчик события "OnBeforeIBlockElementUpdate"
    function OnAfterIBlockElementUpdateHandler(&$arFields)
    {
        if ($arFields['IBLOCK_ID'] == ABONEMENTS_IBLOCK_ID) {
            $showPage = false;
            foreach ($arFields['PROPERTY_VALUES'] as $key => $arProps) {
                if ($key == ABONEMENTS_BASE_PRICE_ID) {
                    foreach ($arProps as $arBasePrice) {
                        if ($arBasePrice['VALUE']['PRICE']) {
                            $showPage = true;
                        }
                    }                
                }
                if ($key == ABONEMENTS_PRICE_SIGN_ID) {
                    foreach ($arProps as $arBasePrice) {
                        if ($arBasePrice['VALUE']['PRICE'] == 'Бесплатно') {
                            $showPage = true;
                        }
                    }                
                }
            }
            if (!$showPage) {
                CIBlockElement::SetPropertyValuesEx($arFields['ID'], false, array('SOON' => 11));
            }else{
                CIBlockElement::SetPropertyValuesEx($arFields['ID'], false, array('SOON' => ''));
            }
        }
    }
}
?>
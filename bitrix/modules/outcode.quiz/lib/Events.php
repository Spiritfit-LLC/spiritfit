<?php
    namespace Outcode;
    
    use \Bitrix\Main;
    use \Bitrix\Main\Localization\Loc;
    use \Bitrix\Main\Loader;
    use \Bitrix\Highloadblock as HL;
    
    class Events {
        public static function checkUserNickName(&$arFields) {

            return $arFields;

            /*if( !Loader::includeModule('outcode.quiz') ) return $arFields;

            if( empty($arFields['PERSONAL_PROFESSION']) && !empty($arFields['EMAIL']) ) {
                $arFields['PERSONAL_PROFESSION'] = \Outcode\Tools::getLoginFromEmail($arFields['EMAIL']);
            } else if( !empty($arFields['PERSONAL_PROFESSION']) && !empty($arFields['ID']) && \Bitrix\Main\UserTable::getCount(['PERSONAL_PROFESSION' => $arFields['PERSONAL_PROFESSION'], '!ID' => $arFields['ID']]) != 0 ) {
            	$arFields['PERSONAL_PROFESSION'] = \Outcode\Tools::getLoginFromEmail($arFields['EMAIL']);
            }
            
            return $arFields;*/
        }
    }
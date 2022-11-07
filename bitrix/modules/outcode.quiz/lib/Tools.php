<?php
	namespace Outcode;
	
	use \Bitrix\Main;
	use \Bitrix\Main\Localization\Loc;
    use \Bitrix\Main\Loader;
    use \Bitrix\Highloadblock as HL;
	
	class Tools {
        public static function getModuleId() {
            return "outcode.quiz";
        }
	    public static function getDefaultSettings() : array {
            return [
                'I_BLOCK_CODE' => 'outcode_quiz',
                'HL_BLOCK_NAME' => 'OutcodeQuizResult',
                'HL_BLOCK_PRIZE_NAME' => 'OutcodeQuizPrize',
                'I_BLOCK_ID' => self::iBlockGetIdByCode('outcode_quiz'),
                'HL_BLOCK_ID' => self::hlBlockGetIdByName('OutcodeQuizResult'),
                'HL_BLOCK_PRIZE_ID' => self::hlBlockGetIdByName('OutcodeQuizPrize')
            ];
        }
	    public static function iBlockGetIdByCode(string $code) : int {
            if(!Loader::includeModule('iblock')) return 0;

            $res = \CIBlock::GetList([], [ 'CODE' => $code ]);
            if($arRes = $res->Fetch()) {
                return $arRes['ID'];
            }

            return 0;
        }
        public static function hlBlockGetIdByName(string $name) : int {
            if(!Loader::includeModule('highloadblock')) return 0;

            $arFilter = ['select' => ['ID'], 'filter' => ['=NAME' => $name]];
            $hlBlock = HL\HighloadBlockTable::getList($arFilter)->fetch();
            if(is_array($hlBlock) && !empty($hlBlock))  {
                return $hlBlock['ID'];
            }

            return 0;
        }
        public static function getKey() : string {
            $key =  \Bitrix\Main\Config\Option::get(self::getModuleId(), "CIPHER_KEY");
            return !empty($key) ? $key : self::getModuleId();
        }
        public static function getLoginFromEmail(string $email) : string {
            $exArr = explode('@', $email);
            return !empty($exArr[0]) ? $exArr[0] : $email;
        }
	}	
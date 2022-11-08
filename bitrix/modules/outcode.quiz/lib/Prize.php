<?php
namespace Outcode;

use \Bitrix\Main;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Type\DateTime;

class Prize {

    private $settings;
    private $userId;
    private $userEmail;
    private $hlEntityDataClass;

    function __construct() {
        global $USER;

        $this->settings = Tools::getDefaultSettings();
        $this->userId = $USER->IsAuthorized() ? $USER->GetID() : 0;
        if( !empty($this->userId) ) {
            $this->userEmail = $USER->GetEmail();
        }

        if( !empty($this->settings['HL_BLOCK_PRIZE_ID']) ) {
            $hlBlock = \Bitrix\Highloadblock\HighloadBlockTable::getById($this->settings['HL_BLOCK_PRIZE_ID'])->fetch();
            $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlBlock);
            $this->hlEntityDataClass = $entity->getDataClass();
        }
    }

    public function isSelected(int $timeStart, int $timeEnd) : bool {
        if( empty($this->userId) || !isset($this->hlEntityDataClass) ) return false;

        $timeStartSystem = \Bitrix\Main\Type\DateTime::createFromTimestamp($timeStart);
        $timeEndSystem = \Bitrix\Main\Type\DateTime::createFromTimestamp($timeEnd);

        $currentUserQuestionsCount = $this->hlEntityDataClass::getCount(['UF_USER_ID' => $this->userId, '>UF_RESULT_DATE' => $timeStartSystem->toString(), '<UF_RESULT_DATE' => $timeEndSystem->toString()]);
        return $currentUserQuestionsCount > 0;
    }

    public function isSelectedCount(int $timeStart, int $timeEnd) : int {
        if( empty($this->userId) || !isset($this->hlEntityDataClass) ) return 0;

        $timeStartSystem = \Bitrix\Main\Type\DateTime::createFromTimestamp($timeStart);
        $timeEndSystem = \Bitrix\Main\Type\DateTime::createFromTimestamp($timeEnd);

        $currentUserQuestionsCount = $this->hlEntityDataClass::getCount(['UF_USER_ID' => $this->userId, '>UF_RESULT_DATE' => $timeStartSystem->toString(), '<UF_RESULT_DATE' => $timeEndSystem->toString()]);
        return $currentUserQuestionsCount;
    }

    public function selectPrize( int $elementId, string $numPropCode, string $mailPropCode ) : bool {
        if( empty($this->userId) || !isset($this->hlEntityDataClass) ) return false;
        
        $available = 0;
        $iblockId = 0;
        $mailMsg = '';
        $res = \CIBlockElement::GetByID($elementId);
        if($arRes = $res->GetNext()) {
            $iblockId = $arRes['IBLOCK_ID'];
            $propRes = \CIBlockElement::GetProperty($arRes['IBLOCK_ID'], $arRes['ID'], [], ['CODE' => $numPropCode]);
            if( $arProp = $propRes->GetNext() ) {
                $available = intval($arProp['VALUE']);
            }
            $propRes = \CIBlockElement::GetProperty($arRes['IBLOCK_ID'], $arRes['ID'], [], ['CODE' => $mailPropCode]);
            if( $arProp = $propRes->GetNext() ) {
                if( !empty($arProp['~VALUE']['TEXT']) ) $mailMsg = intval($arProp['~VALUE']['TEXT']);
            }
        }

        if( $available > 0 && $iblockId > 0 ) {
            $available -= 1;

            $currentDate = new DateTime();
            $dataArr = [
                'UF_USER_ID' => $this->userId,
                'UF_ELEMENT_ID' => $elementId,
                'UF_RESULT_DATE' => $currentDate->toString()
            ];

            $result = $this->hlEntityDataClass::add($dataArr);
            if( $result->isSuccess() ) {
                \CIBlockElement::SetPropertyValuesEx($elementId, $iblockId, [$numPropCode => $available]);
                $emailFields = [
                    'EMAIL_TO' => $this->userEmail,
                    'EMAIL_BODY' => $mailMsg
                ];
                \CEvent::SendImmediate('QUIZ_PRIZE_SELECT', SITE_ID, $emailFields, 'N');

                return true;
            }
        }

        return false;
    }

    public function isButtonEnabled(int $timeStart, int $timeEnd, int $limit = 50, int $minValue = 0, bool $isTotal = false) : bool {
        if( empty($this->userId) || ($isTotal && $this->isSelectedCount($timeStart, $timeEnd) > 2) || (!$isTotal && $this->isSelected($timeStart, $timeEnd)) ) return false;

        $quiz = new Quiz();
        $quizResult = $quiz->isUserInTop($timeStart, $timeEnd, $this->userId, $limit);

        if( !$quizResult['IN_TOP'] && $quizResult['TOTAL_VALUE'] > $minValue ) {
            return true;
        }

        return false;
    }
}
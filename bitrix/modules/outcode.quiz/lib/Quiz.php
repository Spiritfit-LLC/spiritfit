<?php
namespace Outcode;

use \Bitrix\Main;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Type\DateTime;

class Quiz {

    private $settings;
    private $userId;
    private $hlEntityDataClass;
    private $pushPath;

    function __construct(string $pushPath = '') {
        global $USER;

        $this->settings = Tools::getDefaultSettings();
        $this->userId = $USER->IsAuthorized() ? $USER->GetID() : 0;

        if( !empty($this->settings['HL_BLOCK_ID']) ) {
            $hlBlock = \Bitrix\Highloadblock\HighloadBlockTable::getById($this->settings['HL_BLOCK_ID'])->fetch();
            $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlBlock);
            $this->hlEntityDataClass = $entity->getDataClass();
        }

        $this->pushPath = $pushPath;
    }

    public function getQuestion(int $id) : array {
        if( empty($this->userId) || empty($this->settings['I_BLOCK_ID']) ) return [];

        $res = \CIBlockElement::GetList([], ['ID' => $id], false);
        if($resArr = $res->GetNextElement()) {
            $arFields = $resArr->GetFields();
            $arFields['PROPERTIES'] = $resArr->GetProperties();

            return $arFields;
        }

        return [];
    }

    public function getQuestionByTime(int $time) : array {
        if( empty($this->settings['I_BLOCK_ID']) ) return [];

        $res = \CIBlockElement::GetList([],
            [
                'IBLOCK_ID' => $this->settings['I_BLOCK_ID'],
                'ACTIVE' => 'Y',
                '<=PROPERTY_DATE_START' => date('Y-m-d H:i:s', $time),
                '>=PROPERTY_DATE_END' => date('Y-m-d H:i:s', $time)
            ],
            false
        );

        if($resArr = $res->GetNextElement()) {
            $arFields = $resArr->GetFields();
            $arFields['PROPERTIES'] = $resArr->GetProperties();

            return $arFields;
        }

        return [];
    }

    public function isQuestionAnswered(int $questionId) : bool {
        if( empty($this->userId) || !isset($this->hlEntityDataClass) ) return false;
        $currentUserQuestionsCount = $this->hlEntityDataClass::getCount(['UF_USER_ID' => $this->userId, 'UF_QUESTION_ID' => $questionId]);
        return $currentUserQuestionsCount > 0;
    }

    public function getQuestionScore(int $questionId) : int {
        if( empty($this->userId) || !isset($this->hlEntityDataClass) ) return 0;

        $rsData = $this->hlEntityDataClass::getList([
            'select' => ['UF_USER_ID', 'UF_RESULT'],
            "filter" => ['UF_USER_ID' => $this->userId, 'UF_QUESTION_ID' => $questionId]
        ])->Fetch();

        return !empty($rsData['UF_RESULT']) ? $rsData['UF_RESULT'] : 0;
    }

    public function addResult(int $questionId, string $value) : array {
        if( empty($this->userId) || !isset($this->hlEntityDataClass) ) return [];

        $currentDate = new DateTime();
        $currentQuestion = $this->getQuestion($questionId);
        $userInfo = \Bitrix\Main\UserTable::getList([
            'select' => ['*'],
            'filter' => ['ID' => $this->userId]
        ])->fetch();

        if( empty($currentQuestion) ) return [];

        /* Проверяем не отвечал ли пользователь на этот вопрос */
        $currentQuestionsAnswered = $this->isQuestionAnswered($questionId);
        if( $currentQuestionsAnswered ) {
            return ['ID' => 0, 'RESULT' => $this->getQuestionScore($questionId)];
        }

        /* Проверяем правильность ответа на вопрос */
        $isCorrectByUser = false;
        $outStingResult = '';
        switch($currentQuestion['PROPERTIES']['TYPE']['VALUE']) {
            case 'Text':
                foreach( $currentQuestion['PROPERTIES']['ANSWERS_STRING']['VALUE'] as $string) {
                    if( mb_strripos($value, $string) !== false ) {
                        $isCorrectByUser = true;
                        $outStingResult = $value;
                        break;
                    }
                }
                break;
            case 'Strings':
                foreach( $currentQuestion['PROPERTIES']['ANSWERS_STRING']['VALUE'] as $string) {
                    $isCorrectAnswer = ( preg_match("/#/", $string) != 0 );
                    $string = str_replace('#', '', $string);
                    if( $isCorrectAnswer && $string == $value ) {
                        $isCorrectByUser = true;
                        $outStingResult = $string;
                        break;
                    }
                }
                break;
            case 'Images':
                $fileId = intval($value);
                foreach( $currentQuestion['PROPERTIES']['ANSWERS_IMAGE']['VALUE'] as $key => $imageId) {
                    $string = !empty($currentQuestion['PROPERTIES']['ANSWERS_IMAGE']['DESCRIPTION'][$key]) ? trim($currentQuestion['PROPERTIES']['ANSWERS_IMAGE']['DESCRIPTION'][$key]) : '';
                    $isCorrectAnswer = ( preg_match("/#/", $string) != 0 );
                    $string = str_replace('#', '', $string);
                    if( $isCorrectAnswer && $imageId == $fileId ) {
                        $isCorrectByUser = true;
                        $outStingResult = !empty($string) ? $string : $fileId;
                        break;
                    }
                }
                break;
        }

        /* Формируем результат */
        $outResult = 0;
        if( $isCorrectByUser ) {
            $answersCount = $this->hlEntityDataClass::getCount(['UF_QUESTION_ID' => $questionId, '>UF_RESULT' => 0]);
            $outResult = 100 - (10 * $answersCount);
            if( $outResult < 10 ) $outResult = 10;
        }

        $dataArr = [
            'UF_QUESTION_ID' => $currentQuestion['ID'],
            'UF_QUESTION_VALUE' => $currentQuestion['NAME'],
            'UF_USER_ID' => $this->userId,
            'UF_USER_EMAIL' => $userInfo['EMAIL'],
            'UF_ANSWER' => $outStingResult,
            'UF_RESULT' => $outResult,
            'UF_RESULT_DATE' => $currentDate->toString()
        ];

        $result = $this->hlEntityDataClass::add($dataArr);
        if( $result->isSuccess() ) {
            /* Отправка в 1C*/
            $userIsPushed = $this->hlEntityDataClass::getCount(['UF_USER_ID' => $this->userId, 'UF_PUSHED' => 1]);
            if( empty($userIsPushed) && !empty($this->pushPath) ) {
                $resultPush = new ResultPush($this->pushPath . "startplay");
                $cRes = $resultPush->send(['email' => $userInfo['EMAIL']]);
                if( !$cRes['error'] ) {
                    $this->hlEntityDataClass::update($result->getId(), ['UF_PUSHED' => 1]);
                }
            }

            return ['ID' => $result->getId(), 'RESULT' => $outResult];
        }

        return [];
    }

    public function getAllResults(int $timeStart, int $timeEnd, int $limit = 0) : array {
        $arResult = ['TOTAL_RESULT' => [], 'BY_QUESTIONS' => []];
        $filterArr = [];

        $timeStartSystem = \Bitrix\Main\Type\DateTime::createFromTimestamp($timeStart);
        $timeEndSystem = \Bitrix\Main\Type\DateTime::createFromTimestamp($timeEnd);

        $requestArr = [
            "select" => ["*"],
            "order" => ['UF_RESULT_DATE' => 'ASC'],
            "filter" => ['>UF_RESULT_DATE' => $timeStartSystem->toString(), '<UF_RESULT_DATE' => $timeEndSystem->toString(), '>UF_RESULT' => 0]
        ];

        if( !empty($limit) ) $requestArr['limit'] = $limit;

        $rsObj = $this->hlEntityDataClass::getList($requestArr);
        while( $rsData = $rsObj->Fetch() ) {
            if(!in_array($rsData['UF_USER_ID'], $filterArr)) $filterArr[] = $rsData['UF_USER_ID'];

            if( !isset($arResult['TOTAL_RESULT'][$rsData['UF_USER_ID']]) ) $arResult['TOTAL_RESULT'][$rsData['UF_USER_ID']] = 0;
            $arResult['TOTAL_RESULT'][$rsData['UF_USER_ID']] += intval($rsData['UF_RESULT']);

            if( !isset($arResult['BY_QUESTIONS'][$rsData['UF_QUESTION_VALUE']][$rsData['UF_USER_ID']]) ) $arResult['BY_QUESTIONS'][$rsData['UF_QUESTION_VALUE']][$rsData['UF_USER_ID']] = 0;
            $arResult['BY_QUESTIONS'][$rsData['UF_QUESTION_VALUE']][$rsData['UF_USER_ID']] += intval($rsData['UF_RESULT']);
        }

        /* Добавляем пользователей к результатам */
        $usersObj = \Bitrix\Main\UserTable::getList([
            'select' => ['ID', 'LOGIN', 'EMAIL', 'PERSONAL_PROFESSION'],
            'filter' => ['ID' => $filterArr]
        ]);
        while( $userArr = $usersObj->Fetch() ) {
            if( isset($arResult['TOTAL_RESULT'][$userArr['ID']]) ) {
                $arResult['TOTAL_RESULT'][$userArr['ID']] = [
                    'VALUE' => $arResult['TOTAL_RESULT'][$userArr['ID']],
                    'EMAIL' => $userArr['EMAIL'],
                    'LOGIN' => empty($userArr['PERSONAL_PROFESSION']) ? Tools::getLoginFromEmail($userArr['EMAIL']) : $userArr['PERSONAL_PROFESSION'],
                ];
            }

            foreach( $arResult['BY_QUESTIONS'] as &$item) {
                if( isset($item[$userArr['ID']]) ) {
                    $item[$userArr['ID']] = [
                        'VALUE' => $item[$userArr['ID']],
                        'EMAIL' => $userArr['EMAIL'],
                        'LOGIN' => empty($userArr['PERSONAL_PROFESSION']) ? Tools::getLoginFromEmail($userArr['EMAIL']) : $userArr['PERSONAL_PROFESSION'],
                    ];
                } else {
                    $item[$userArr['ID']] = [
                        'VALUE' => $item[$userArr['ID']],
                        'EMAIL' => '',
                        'LOGIN' => '',
                    ];
                }
            }
            unset($item);
        }

        /* Сортировка */
        foreach( $arResult['BY_QUESTIONS'] as &$items ) {
            usort($items, function ($item1, $item2) {
                return $item2['VALUE'] <=> $item1['VALUE'];
            });
        }
        unset($item);

        usort($arResult['TOTAL_RESULT'], function ($item1, $item2) {
            return $item2['VALUE'] <=> $item1['VALUE'];
        });

        return $arResult;
    }

    public function getUserResults(int $timeStart, int $timeEnd) : array {
        if( empty($this->userId) ) return [];

        $timeStartSystem = \Bitrix\Main\Type\DateTime::createFromTimestamp($timeStart);
        $timeEndSystem = \Bitrix\Main\Type\DateTime::createFromTimestamp($timeEnd);

        $arResult = ['QUESTIONS' => [], 'TOTAL_RESULT' => 0];
        $rsObj = $this->hlEntityDataClass::getList([
            "select" => ["*"],
            "order" => ['UF_RESULT_DATE' => 'DESC'],
            "filter" => ['UF_USER_ID' => $this->userId, '>UF_RESULT_DATE' => $timeStartSystem->toString(), '<UF_RESULT_DATE' => $timeEndSystem->toString()]
        ]);
        while( $rsData = $rsObj->Fetch() ) {
            $arResult['TOTAL_RESULT'] += intval($rsData['UF_RESULT']);
            $arResult['QUESTIONS'][$rsData['UF_QUESTION_VALUE']] = [
                'ANSWER' => $rsData['UF_ANSWER'],
                'RESULT' => $rsData['UF_RESULT']
            ];
        }

        ksort($arResult['QUESTIONS']);
        return $arResult;
    }

    public function getUserUid() : string {
        if( empty($this->userId) ) return "";

        $cipher = new \Bitrix\Main\Security\Cipher();
        $encoded = $cipher->encrypt($this->userId, Tools::getKey());

        return urlencode( base64_encode($encoded) );
    }

    public static function addBonus(string $uid, int $bonusVal) : array {
        $cipher = new \Bitrix\Main\Security\Cipher();

        $decoded = base64_decode( urldecode($uid) );
        $userId = $cipher->decrypt($decoded, Tools::getKey());

        $userInfo = \Bitrix\Main\UserTable::getList([
            'select' => ['*'],
            'filter' => ['ID' => intval($userId)]
        ])->fetch();

        if( !empty($userInfo['ID']) ) {
            $currentDate = new DateTime();
            $dataArr = [
                'UF_QUESTION_ID' => 0,
                'UF_QUESTION_VALUE' => Loc::getMessage('QUIZ_BONUS'),
                'UF_USER_ID' => $userInfo['ID'],
                'UF_USER_EMAIL' => $userInfo['EMAIL'],
                'UF_ANSWER' => 'QUIZ_BONUS',
                'UF_RESULT' => $bonusVal,
                'UF_RESULT_DATE' => $currentDate->ToString()
            ];

            $settings = Tools::getDefaultSettings();

            if( !empty($settings['HL_BLOCK_ID']) ) {
                $hlBlock = \Bitrix\Highloadblock\HighloadBlockTable::getById($settings['HL_BLOCK_ID'])->fetch();
                $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlBlock);
                $hlEntityDataClass = $entity->getDataClass();

                $result = $hlEntityDataClass::add($dataArr);
                if( $result->isSuccess() ) {
                    return ['ID' => $result->getId(), 'RESULT' => $bonusVal];
                }
            }

            return [];

        } else {
            return [];
        }
    }
}
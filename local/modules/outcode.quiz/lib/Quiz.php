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

            if(!empty($arFields['PROPERTIES']['QUESTION_STRING']['VALUE'])) $arFields['NAME'] = $arFields['PROPERTIES']['QUESTION_STRING']['VALUE'];

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

            if(!empty($arFields['PROPERTIES']['QUESTION_STRING']['VALUE'])) $arFields['NAME'] = $arFields['PROPERTIES']['QUESTION_STRING']['VALUE'];

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

    public function getQuestionPosition(int $timeStart, int $timeEnd, int $questionId = 0) : array
    {
        $resultArr = ['TOTAL' => 0, 'CURRENT' => 0];
        $res = \CIBlockElement::GetList([],
            [
                'IBLOCK_ID' => $this->settings['I_BLOCK_ID'],
                'ACTIVE' => 'Y',
                '>=PROPERTY_DATE_START' => date('Y-m-d H:i:s', $timeStart),
                '<=PROPERTY_DATE_START' => date('Y-m-d H:i:s', $timeEnd)
            ],
            false,
            false,
            ['ID']
        );

        $counter = 0;
        while($resArr = $res->GetNext()) {
            $counter += 1;
            if( $questionId == $resArr['ID'] ) {
                $resultArr['CURRENT'] = $counter;
            }
        }
        $resultArr['TOTAL'] = $counter;

        return $resultArr;
    }

    public function addResult(int $questionId, string $value, $isGetQuestionDate = false) : array {
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
        $outStingResult = strip_tags($value);
        switch($currentQuestion['PROPERTIES']['TYPE']['VALUE']) {
            case 'Images':
            case 'Text':
                foreach( $currentQuestion['PROPERTIES']['ANSWERS_STRING']['VALUE'] as $string) {
                    $string = str_replace('#', '', $string);
//                    file_put_contents(__DIR__.'/debug.txt', print_r($string, true)."\n", FILE_APPEND);
//                    file_put_contents(__DIR__.'/debug.txt', print_r($value, true)."\n", FILE_APPEND);
//                    file_put_contents(__DIR__.'/debug.txt', print_r(mb_strripos($value, $string), true)."\n", FILE_APPEND);

                    if( mb_strripos($value, $string) !== false ) {
                        $isCorrectByUser = true;
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
            'UF_RESULT_DATE' => $isGetQuestionDate ? $currentQuestion['PROPERTIES']['DATE_END']['VALUE'] : $currentDate->toString()
        ];

        $result = $this->hlEntityDataClass::add($dataArr);
        if( $result->isSuccess() ) {
            /* Отправка в 1C*/
            /*$userIsPushed = $this->hlEntityDataClass::getCount(['UF_USER_ID' => $this->userId, 'UF_PUSHED' => 1]);
            if( empty($userIsPushed) && !empty($this->pushPath) ) {
                $resultPush = new ResultPush($this->pushPath . "startplay");
                $cRes = $resultPush->send(['email' => $userInfo['EMAIL']]);
                if( !$cRes['error'] ) {
                    $this->hlEntityDataClass::update($result->getId(), ['UF_PUSHED' => 1]);
                }
            }*/

            return ['ID' => $result->getId(), 'RESULT' => $outResult];
        }

        return [];
    }

    public function isUserInTop( int $timeStart, int $timeEnd, int $userId, $limit = 50 ) : array {
        $arRes = ['IN_TOP' => false, 'TOTAL_VALUE' => 0, 'USER' => [], 'IN_QUIZ' => false];
        $arUserTable = [];

        if( !$this->isUserInQuiz($userId) || !isset($this->hlEntityDataClass) ) return $arRes;

        $userInfo = \Bitrix\Main\UserTable::getList([
            'select' => ['ID', 'EMAIL'],
            'filter' => ['ID' => $userId]
        ])->fetch();
        if( !empty($userInfo['ID']) ) {
            $arRes['USER'] = $userInfo;

            $timeStartSystem = \Bitrix\Main\Type\DateTime::createFromTimestamp($timeStart);
            $timeEndSystem = \Bitrix\Main\Type\DateTime::createFromTimestamp($timeEnd);

            $rsObj = $this->hlEntityDataClass::getList([
                "select" => ["*"],
                "order" => ['UF_RESULT_DATE' => 'ASC'],
                "filter" => ['>UF_RESULT_DATE' => $timeStartSystem->toString(), '<UF_RESULT_DATE' => $timeEndSystem->toString(), '>UF_RESULT' => 0]
            ]);
            while( $rsData = $rsObj->Fetch() ) {
                if( !isset($arUserTable[$rsData['UF_USER_ID']]) )  $arUserTable[$rsData['UF_USER_ID']] = ['VALUE' => 0, 'ID' => $rsData['UF_USER_ID']];
                $arUserTable[$rsData['UF_USER_ID']]['VALUE'] += intval($rsData['UF_RESULT']);
            }

            $arRes['IN_QUIZ'] = isset($arUserTable[$userId]);

            usort($arUserTable, function ($item1, $item2) {
                return $item2['VALUE'] <=> $item1['VALUE'];
            });

            if( $limit == 0 ) {
                foreach( $arUserTable as $item ) {
                    if( $userId == $item['ID'] ) {
                        $arRes['IN_TOP'] = false;
                        $arRes['TOTAL_VALUE'] = $item['VALUE'];
                        break;
                    }
                }
                return $arRes;
            }

            $counter = 0;
            $inTop = true;
            foreach( $arUserTable as $item ) {
                if( $userId == $item['ID'] ) {
                    $arRes['IN_TOP'] = $inTop;
                    $arRes['TOTAL_VALUE'] = $item['VALUE'];
                    break;
                }

                $counter += 1;
                if( $counter >= $limit && $inTop ) $inTop = false;
            }
            unset($counter);
            unset($arUserTable);
        }

        return $arRes;
    }

    public function getAllResults(int $timeStart, int $timeEnd) : array {
        $arResult = ['TOTAL_RESULT' => [], 'BY_QUESTIONS' => []];

        if( !isset($this->hlEntityDataClass) ) return $arResult;

        $filterArr = [];

        $timeStartSystem = \Bitrix\Main\Type\DateTime::createFromTimestamp($timeStart);
        $timeEndSystem = \Bitrix\Main\Type\DateTime::createFromTimestamp($timeEnd);

        $rsObj = $this->hlEntityDataClass::getList([
            "select" => ["*"],
            "order" => ['UF_RESULT_DATE' => 'ASC'],
            "filter" => ['>UF_RESULT_DATE' => $timeStartSystem->toString(), '<UF_RESULT_DATE' => $timeEndSystem->toString(), '>UF_RESULT' => 0]
        ]);
        while( $rsData = $rsObj->Fetch() ) {
            if(!in_array($rsData['UF_USER_ID'], $filterArr)) $filterArr[] = $rsData['UF_USER_ID'];

            if( !isset($arResult['TOTAL_RESULT'][$rsData['UF_USER_ID']]) ) $arResult['TOTAL_RESULT'][$rsData['UF_USER_ID']] = 0;
            $arResult['TOTAL_RESULT'][$rsData['UF_USER_ID']] += intval($rsData['UF_RESULT']);

            if( !isset($arResult['BY_QUESTIONS'][$rsData['UF_USER_ID']]) ) $arResult['BY_QUESTIONS'][$rsData['UF_USER_ID']] = [];
            if( !in_array($rsData['UF_QUESTION_VALUE'], $arResult['BY_QUESTIONS'][$rsData['UF_USER_ID']]) ) {
                $arResult['BY_QUESTIONS'][$rsData['UF_USER_ID']][] = $rsData['UF_QUESTION_VALUE'];
            }
        }

        /* Добавляем пользователей к результатам */
        $usersObj = \Bitrix\Main\UserTable::getList([
            'select' => ['ID', 'LOGIN', 'EMAIL', 'PERSONAL_PROFESSION', 'UF_QUIZ_WINNER'],
            'filter' => ['ID' => $filterArr]
        ]);
        while( $userArr = $usersObj->Fetch() ) {
            if( isset($arResult['TOTAL_RESULT'][$userArr['ID']]) ) {
                $arResult['TOTAL_RESULT'][$userArr['ID']] = [
                    'VALUE' => $arResult['TOTAL_RESULT'][$userArr['ID']],
                    'EMAIL' => $userArr['EMAIL'],
                    'LOGIN' => !empty($userArr['EMAIL']) ? Tools::getLoginFromEmail($userArr['EMAIL']) : $userArr['ID'],
                    'USER_ID' => $userArr['ID'],
                    'WINNER' => $userArr['UF_QUIZ_WINNER']
                ];
            }
        }

        usort($arResult['TOTAL_RESULT'], function ($item1, $item2) {
            return $item2['VALUE'] <=> $item1['VALUE'];
        });

        return $arResult;
    }

    public function getLimitedResults(int $timeStart, int $timeEnd, int $limit) : array {
        if( empty($this->userId) || !isset($this->hlEntityDataClass) ) return [];
        if( !$this->isUserInQuiz($this->userId) ) return [];

        $timeStartSystem = \Bitrix\Main\Type\DateTime::createFromTimestamp($timeStart);
        $timeEndSystem = \Bitrix\Main\Type\DateTime::createFromTimestamp($timeEnd);

        $resultArr = [];
        $usersCount = 0;

        $rsObj = $this->hlEntityDataClass::getList([
            "select" => ["*"],
            "order" => ['UF_RESULT_DATE' => 'ASC'],
            "filter" => ['>UF_RESULT_DATE' => $timeStartSystem->toString(), '<UF_RESULT_DATE' => $timeEndSystem->toString(), '>UF_RESULT' => 0]
        ]);
        while( $rsData = $rsObj->Fetch() ) {

            if( !isset($resultArr[$rsData['UF_USER_ID']]) ) {
                $resultArr[$rsData['UF_USER_ID']] = 0;
                $usersCount += 1;
            }

            $resultArr[$rsData['UF_USER_ID']] += intval($rsData['UF_RESULT']);

            if( $usersCount >= $limit ) break;
        }

        return $resultArr;
    }

    public function getUserResults(int $timeStart=0, int $timeEnd=0, $limit = 0) : array {
        if( empty($this->userId) ) return [];

        $filter=['UF_USER_ID' => $this->userId];

        if ($timeStart!=0){
            $timeStartSystem = \Bitrix\Main\Type\DateTime::createFromTimestamp($timeStart);
            $filter['>UF_RESULT_DATE']=$timeStartSystem->toString();
        }
        if ($timeEnd!=0){
            $timeEndSystem = \Bitrix\Main\Type\DateTime::createFromTimestamp($timeEnd);
            $filter['<UF_RESULT_DATE']=$timeEndSystem->toString();
        }

        $requestArr = [
            "select" => ["*"],
            "order" => ['UF_RESULT_DATE' => 'ASC'],
            "filter" => $filter
        ];


        if( !empty($limit) ) $requestArr['limit'] = $limit;

        $arResult = ['QUESTIONS' => [], 'TOTAL_RESULT' => 0];
        $rsObj = $this->hlEntityDataClass::getList($requestArr);

        while( $rsData = $rsObj->Fetch() ) {
            $answerString = '';
            $arQuestion = $this->getQuestion($rsData['UF_QUESTION_ID']);
            if( !empty($arQuestion) ) {
                if( $arQuestion['PROPERTIES']['TYPE']['VALUE'] == 'Text' || $arQuestion['PROPERTIES']['TYPE']['VALUE'] == 'Images') {
                    $answerString = !empty($arQuestion['PROPERTIES']['ANSWERS_STRING']['VALUE'][0]) ? $arQuestion['PROPERTIES']['ANSWERS_STRING']['VALUE'][0] : '';
                } else {
                    foreach( $arQuestion['PROPERTIES']['ANSWERS_STRING']['VALUE'] as $string ) {
                        if( preg_match("/#/", $string) != 0 ) {
                            $answerString = str_replace('#', '', $string);
                            break;
                        }
                    }
                }
            }
            $arResult['TOTAL_RESULT'] += intval($rsData['UF_RESULT']);
            $arResult['QUESTIONS'][] = [
                'QUESTION' => $rsData['UF_QUESTION_VALUE'],
                'ANSWER' => $rsData['UF_ANSWER'],
                'RESULT' => $rsData['UF_RESULT'],
                'CORRECT_ANSWER' => $answerString
            ];
        }

        sort($arResult["QUESTIONS"], SORT_DESC);
//        ksort($arResult['QUESTIONS'], 'ASC');
        return $arResult;
    }

    public function getUserUid() : string {
        if( empty($this->userId) ) return "";

        $cipher = new \Bitrix\Main\Security\Cipher();
        $encoded = $cipher->encrypt($this->userId, Tools::getKey());

        return urlencode( base64_encode($encoded) );
    }

    public function isUserInQuiz( int $userId = 0 ) : bool {
        if( !isset($this->hlEntityDataClass) ) return false;
        if( empty($userId) && empty($this->userId) ) return false;

        if( empty($userId) ) $userId = $this->userId;
        return $this->hlEntityDataClass::getCount(['UF_USER_ID' => $userId]) > 0 ? true : false;
    }

    public static function addBonus(string $uid, int $bonusVal) : array {
        $cipher = new \Bitrix\Main\Security\Cipher();

        $decoded = base64_decode($uid);
        $userId = $cipher->decrypt($decoded, Tools::getKey());

        global $USER;
        if (intval($userId)==$USER->GetID()){
            return [];
        }

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
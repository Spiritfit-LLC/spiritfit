<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class PersonalCoachTrainings extends CBitrixComponent implements Controllerable{
    public function ConfigureActions(){
        return [
            'getSlots'=>[
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ]
        ];
    }
    public function listKeysSignedParameters(){
        return [
            "USER_LOGIN",
            "USER_1CID"
        ];
    }

    function onPrepareComponentParams($arParams){
        global $USER;
        $rsUser = CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();

        $arParams["USER_LOGIN"]=$arUser["LOGIN"];
        $arParams["USER_1CID"]=$arUser["UF_1CID"];

        return $arParams;
    }

    private $monthArr = [
                'Январь',
                'Февраль',
                'Март',
                'Апрель',
                'Май',
                'Июнь',
                'Июль',
                'Август',
                'Сентябрь',
                'Октябрь',
                'Ноябрь',
                'Декабрь'
            ];
    private $weekArr=[
            "ВС",
            "ПН",
            "ВТ",
            "СР",
            "ЧТ",
            "ПТ",
            "СБ"
        ];

    function executeComponent()
    {
        $date = date('Y-m-d');
        for($i=0; $i<32; $i++){
            list($day, $week, $month)=explode(';',date('d;w;n', strtotime($date)));

            $this->arResult["DAYS"][]=
                [
                    "DATE"=>date('Y.m.d', strtotime($date)),
                    "DAY"=>$day,
                    "MONTH"=>$this->monthArr[$month-1],
                    "WEEK"=>$this->weekArr[$week],
                    "WEEKEND"=>$week==0 || $week==6?true:false,
                ];
            $date = date('Y-m-d', strtotime($date . "+1 days"));
        }

        $this->IncludeComponentTemplate();
    }

    private function prepareTimeTable($timetable, $date){
        ksort($timetable);

        $TIMETABLE=[];
        $CURR_TIME="10:00";
        while ($CURR_TIME<="21:00"){
            if ($date==date("Y.m.d") && date("H:i")>$CURR_TIME){
                $CURR_TIME=date('H:i',  strtotime("+1 hours", strtotime($CURR_TIME)));
                continue;
            }
//            if ($CURR_TIME<"12:00"){
//                $key="0MORNING";
//            }
//            elseif ($CURR_TIME<"18:00"){
//                $key="1DAYTIME";
//            }
//            else{
//                $key="2EVENING";
//            }

            if (key_exists($CURR_TIME, $timetable)){
                $TIMETABLE[$CURR_TIME]=[
                    "TYPE"=>$timetable[$CURR_TIME]["type"],
                ];
                if ($timetable[$CURR_TIME]["type"]=="busy"){
                    $TIMETABLE[$CURR_TIME]["WORKOUT"]=$timetable[$CURR_TIME]["workout"];
                    if (empty($TIMETABLE[$CURR_TIME]["WORKOUT"]["id"])){
                        $TIMETABLE[$CURR_TIME]["WORKOUT"]["id"]="duty_".$CURR_TIME;
                    }
                }
            }
            else{
                $TIMETABLE[$CURR_TIME]=["TYPE"=>"EMPTY"];
            }
            $CURR_TIME=date('H:i',  strtotime("+1 hours", strtotime($CURR_TIME)));
        }
        $this->arResult["TIMETABLE"]=$TIMETABLE;
        $this->arResult["DATE"]=FormatDate("l, f d", MakeTimeStamp($date, "YYYY.MM.DD"));
        ob_start();
        $this->IncludeComponentTemplate('timetable');
        $result=ob_get_clean();

        return $result;
    }

    //AJAX
    public function getSlotsAction($date=null){
        if (empty($date)){
            $date=date('Y.m.d');
        }
        else{
            $dt = DateTime::createFromFormat("Y.m.d", $date);
            if ($dt===false){
                throw new Exception("Неверный формат даты. Дата не соответствует формату YYYY.MM.DD");
            }
            $errors=$dt::getLastErrors();
            if (($errors===false || $errors["error_count"]>0)){
                throw new Exception("Неверный формат даты. Дата не соответствует формату YYYY.MM.DD");
            }
        }
//        //тут надо по апи принять расписание тренера конкретного
        $arParams=[
            "login"=>$this->arParams["USER_LOGIN"],
            "id1c"=>$this->arParams["USER_1CID"],
            "date"=>$date
        ];

        $api=new Api([
            "action"=>"getSlots",
            "params"=>$arParams
        ]);
        $response=$api->result();
        if (!$response["success"]){
            if (!empty($response['data']['result']['userMessage'])){
               throw new Exception($response['data']['result']['userMessage'], 1);
            }
            else{
                throw new Exception("Непредвиденная ошибка", 100);
            }
        }

        $timetable=$response["data"]["result"]["result"];

        //ИМИТАЦИЯ ДАННЫХ
//        sleep(1);
//        $timetable=[
//            "15:00"=>[
//                "type"=>"busy",
//                "workout"=>[
//                    "id"=>"0125",
//                    "type"=>"Персональная тренировка",
//                    "client"=>"Павел Воля",
//                    "changeble"=>true,
//                ]
//            ],
//            "16:00"=>[
//                "type"=>"busy",
//                "workout"=>[
//                    "id"=>"0125",
//                    "type"=>"Персональная тренировка",
//                    "client"=>"Павел Воля",
//                    "changeble"=>true,
//                ]
//            ],
//            "17:00"=>[
//                "type"=>"busy",
//                "workout"=>[
//                    "id"=>"0123",
//                    "type"=>"Персональная тренировка",
//                    "client"=>"Гарик Харламов",
//                    "changeble"=>true,
//                ]
//            ],
//            "18:00"=>[
//                "type"=>"busy",
//                "workout"=>[
//                    "id"=>"0123",
//                    "type"=>"Персональная тренировка",
//                    "client"=>"Гарик Харламов",
//                    "changeble"=>true,
//                ]
//            ],
//            "19:00"=>[
//                "type"=>"busy",
//                "workout"=>[
//                    "id"=>"0123",
//                    "type"=>"Персональная тренировка",
//                    "client"=>"Гарик Харламов",
//                    "changeble"=>true,
//                ]
//            ],
//            "20:00"=>[
//                "type"=>"busy",
//                "workout"=>[
//                    "id"=>"0124",
//                    "type"=>"Пробная тренировка",
//                    "client"=>"Тимур Батруха",
//                    "changeble"=>false,
//                ]
//            ],
//            "21:00"=>[
//                "type"=>"free"
//            ],
//            "22:00"=>[
//                "type"=>"free"
//            ],
//        ];

        return $this->prepareTimeTable($timetable, $date);
    }

    public function setSlotsAction($type, $start, $finish, $date=null){
        if (empty($date)){
            $date=date('Y.m.d');
        }
        else{
            $dt = DateTime::createFromFormat("Y.m.d", $date);
            if ($dt===false){
                throw new Exception("Неверный формат даты. Дата не соответствует формату YYYY.MM.DD");
            }
            $errors=$dt::getLastErrors();
            if (($errors===false || $errors["error_count"]>0)){
                throw new Exception("Неверный формат даты. Дата не соответствует формату YYYY.MM.DD");
            }
        }

        if ($type!=="free" && $type!=="busy" && $type!=="cancel" && $type!=="delete"){
            throw new Exception("Неизвестный тип запроса");
        }
        $finish=date('H:i',  strtotime("+1 hours", strtotime($finish)));
        $arParams=[
            "login"=>$this->arParams["USER_LOGIN"],
            "id1c"=>$this->arParams["USER_1CID"],
            "date"=>$date,
            "type"=>$type,
            "start"=>$start,
            "finish"=>$finish
        ];

        if ($type==="busy"){
            $arParams["clientName"]=Context::getCurrent()->getRequest()->getPost("clientName");
        }

//        return $arParams;

        $api=new Api([
            "action"=>"setSlots",
            "params"=>$arParams
        ]);
        $response=$api->result();
        if (!$response["success"]){
            if (!empty($response['data']['result']['userMessage'])){
               throw new Exception($response['data']['result']['userMessage'], 1);
            }
            else{
                throw new Exception("Непредвиденная ошибка", 100);
            }
        }

        $timetable=$response["data"]["result"]["result"];
        return $this->prepareTimeTable($timetable, $date);
    }

    public function cancelSlotAction($workout_id, $date){
        $arParams=[
            "login"=>$this->arParams["USER_LOGIN"],
            "id1c"=>$this->arParams["USER_1CID"],
            "type"=>"cancel",
            "workoutId"=>$workout_id
        ];

//        return $arParams;

        //
        $api=new Api([
            "action"=>"setSlots",
            "params"=>$arParams
        ]);
        $response=$api->result();
        if (!$response["success"]){
            if (!empty($response['data']['result']['userMessage'])){
               throw new Exception($response['data']['result']['userMessage'], 1);
            }
            else{
                throw new Exception("Непредвиденная ошибка", 100);
            }
        }

        $timetable=$response["data"]["result"]["result"];
        return $this->prepareTimeTable($timetable, $date);
    }
}

//if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
//
//use \Bitrix\Main\Loader;
//use \Bitrix\Main\Context;
//use Bitrix\Main\Engine\Contract\Controllerable;
//use Bitrix\Main\Engine\ActionFilter;
//
//class PersonalCoachTrainings extends CBitrixComponent implements Controllerable{
//    public function ConfigureActions(){
//        return [
//            'setSlots'=>[
//                'prefilters' => [
//                    new ActionFilter\Authentication,
//                    new ActionFilter\HttpMethod(
//                        array(ActionFilter\HttpMethod::METHOD_POST)
//                    ),
//                    new ActionFilter\Csrf(),
//                ],
//                'postfilters' => []
//            ],
//            'getTimetable'=>[
//                'prefilters' => [
//                    new ActionFilter\Authentication,
//                    new ActionFilter\HttpMethod(
//                        array(ActionFilter\HttpMethod::METHOD_POST)
//                    ),
//                    new ActionFilter\Csrf(),
//                ],
//                'postfilters' => []
//            ],
//        ];
//    }
//
//    function onPrepareComponentParams($arParams){
//
//    }
//
//    function executeComponent(){
//        $monthArr = [
//            'Январь',
//            'Февраль',
//            'Март',
//            'Апрель',
//            'Май',
//            'Июнь',
//            'Июль',
//            'Август',
//            'Сентябрь',
//            'Октябрь',
//            'Ноябрь',
//            'Декабрь'
//        ];
//        $weekArr=[
//            "ВС",
//            "ПН",
//            "ВТ",
//            "СР",
//            "ЧТ",
//            "ПТ",
//            "СБ"
//        ];
//        $date = date('m/d/Y');
//        for($i=0; $i<32; $i++){
//            list($day, $week, $month)=explode(';',date('d;w;n', strtotime($date)));
//
//            $this->arResult["DAYS"][]=
//                [
//                    "DATE"=>$date,
//                    "DAY"=>$day,
//                    "MONTH"=>$monthArr[$month-1],
//                    "WEEK"=>$weekArr[$week],
//                    "WEEKEND"=>$week==0 || $week==6?true:false,
//                ];
//            $date = date('m/d/Y', strtotime($date . "+1 days"));
//        }
//
//        $this->arResult['COMPONENT_NAME']=$this->GetName();
//
//        $this->IncludeComponentTemplate();
//    }
//
//    public function getDayTimetable($date=false){
//        if (empty($date)){
//            $date=date('d.m.Y');
//        }
//
//        $CURR_TIME="10:00";
//
//        //тут надо по апи принять расписание тренера конкретного
////        global $USER;
////        $rsUser = CUser::GetByID($USER->GetID());
////        $arUser = $rsUser->Fetch();
////
////        $api=new Api([
////            "action"=>"coachworkouts",
////            "params"=>[
////                "id1c"=>$arUser['UF_1CID'],
////                "login"=>$arUser["LOGIN"],
////                "date"=>$date
////            ]
////        ]);
////        $response=$api->result();
////        if (!$response["success"]){
////            if (!empty($response['data']['result']['userMessage'])){
////               throw new Exception($response['data']['result']['userMessage'], 1);
////            }
////            else{
////                throw new Exception("Непредвиденная ошибка", 100);
////            }
////        }
//
////        $timetable=$response['data']['result']["result"];
//
////      ИМИТАЦИЯ ДАННЫХ
//        $timetable=[
//            "15:00"=>[
//                "type"=>"busy",
//                "workout"=>[
//                    "id"=>"0125",
//                    "type"=>"Персональная тренировка",
//                    "client"=>"Павел Воля",
//                    "changeble"=>true,
//                ]
//            ],
//            "15:30"=>[
//                "type"=>"busy",
//                "workout"=>[
//                    "id"=>"0125",
//                    "type"=>"Персональная тренировка",
//                    "client"=>"Павел Воля",
//                    "changeble"=>true,
//                ]
//            ],
//            "17:30"=>[
//                "type"=>"busy",
//                "workout"=>[
//                    "id"=>"0123",
//                    "type"=>"Персональная тренировка",
//                    "client"=>"Гарик Харламов",
//                    "changeble"=>true,
//                ]
//            ],
//            "18:00"=>[
//                "type"=>"busy",
//                "workout"=>[
//                    "id"=>"0123",
//                    "type"=>"Персональная тренировка",
//                    "client"=>"Гарик Харламов",
//                    "changeble"=>true,
//                ]
//            ],
//            "18:30"=>[
//                "type"=>"busy",
//                "workout"=>[
//                    "id"=>"0123",
//                    "type"=>"Персональная тренировка",
//                    "client"=>"Гарик Харламов",
//                    "changeble"=>true,
//                ]
//            ],
//            "19:00"=>[
//                "type"=>"busy",
//                "workout"=>[
//                    "id"=>"0123",
//                    "type"=>"Персональная тренировка",
//                    "client"=>"Гарик Харламов",
//                    "changeble"=>true,
//                ]
//            ],
//            "20:00"=>[
//                "type"=>"busy",
//                "workout"=>[
//                    "id"=>"0124",
//                    "type"=>"Пробная тренировка",
//                    "client"=>"Тимур Батруха",
//                    "changeble"=>false,
//                ]
//            ],
//            "21:00"=>[
//                "type"=>"free"
//            ],
//            "20:30"=>[
//                "type"=>"free"
//            ],
//        ];
//
//        $result=[];
//        while ($CURR_TIME<="21:00"){
//            if ($date==date("d.m.Y") && date("H:i")>$CURR_TIME){
//                $CURR_TIME=date('H:i',  strtotime("+30 minutes", strtotime($CURR_TIME)));
//                continue;
//            }
//            if ($CURR_TIME<"12:00"){
//                $key="0MORNING";
//            }
//            elseif ($CURR_TIME<"18:00"){
//                $key="1DAYTIME";
//            }
//            else{
//                $key="2EVENING";
//            }
//
//            if (key_exists($CURR_TIME, $timetable)){
//                $result[$key][$CURR_TIME]=[
//                    "TYPE"=>$timetable[$CURR_TIME]["type"],
//                ];
//                if ($timetable[$CURR_TIME]["type"]=="busy"){
//                    $result[$key][$CURR_TIME]["WORKOUT"]=$timetable[$CURR_TIME]["workout"];
//                }
//            }
//            else{
//                $result[$key][$CURR_TIME]=["TYPE"=>"EMPTY"];
//            }
//            $CURR_TIME=date('H:i',  strtotime("+30 minutes", strtotime($CURR_TIME)));
//        }
//
//        ksort($result);
////        $_SESSION["TW_TIMETABLE"]=$result;
//        return $result;
//    }
//
//
//    //AJAX
//    public function getTimetableAction(){
//        $date=Context::getCurrent()->getRequest()->getPost('date');
//        if (empty($date)){
//            $date=date('d.m.Y');
//        }
//
//        $this->arResult["TIMETABLE"]=$this->getDayTimetable($date);
//        ob_start();
//        $this->IncludeComponentTemplate('timetable');
//        $result=ob_get_clean();
//
//        return $result;
//    }
//
//    //Добавляем свободный слот в расписание
//    public function setSlotsAction(){
//        $date=date('d.m.Y', strtotime(Context::getCurrent()->getRequest()->getPost('date')));
//        $slots=Context::getCurrent()->getRequest()->getPost('slots');
//        $action=Context::getCurrent()->getRequest()->getPost("action");
//        if ($action=="busy"){
//            $client=Context::getCurrent()->getRequest()->getPost("client");
//            if (empty($client)){
//                throw new Exception("Введите имя и фамилию клиента", 1);
//            }
//        }
//
//        //Отправляем инфу в апи
//        global $USER;
//        $rsUser = CUser::GetByID($USER->GetID());
//        $arUser = $rsUser->Fetch();
//
//        $arParams=[
//            "id1c"=>$arUser['UF_1CID'],
//            "login"=>$arUser["LOGIN"],
//            "date"=>$date,
//            "action"=>$action,
//            "slots"=>$slots
//        ];
//        if ($action=="busy"){
//            $arParams["client"]=$client;
//        }
////        $api=new Api([
////            "action"=>"coachsetslots",
////            "params"=>$arParams
////        ]);
////        $response=$api->result();
////        if (!$response["success"]){
////            if (!empty($response['data']['result']['userMessage'])){
////               throw new Exception($response['data']['result']['userMessage'], 1);
////            }
////            else{
////                throw new Exception("Непредвиденная ошибка", 100);
////            }
////        }
//
//        return $arParams;
//    }
//}
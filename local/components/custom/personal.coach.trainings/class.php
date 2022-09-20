<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class PersonalCoachTrainings extends CBitrixComponent implements Controllerable{
    public function ConfigureActions(){
        return [
            'setSlots'=>[
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'getTimetable'=>[
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
        ];
    }

    function onPrepareComponentParams($arParams){

    }

    function executeComponent(){
        $monthArr = [
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
        $weekArr=[
            "ВС",
            "ПН",
            "ВТ",
            "СР",
            "ЧТ",
            "ПТ",
            "СБ"
        ];
        $date = date('m/d/Y');
        for($i=0; $i<32; $i++){
            list($day, $week, $month)=explode(';',date('d;w;n', strtotime($date)));

            $this->arResult["DAYS"][]=
                [
                    "DATE"=>$date,
                    "DAY"=>$day,
                    "MONTH"=>$monthArr[$month-1],
                    "WEEK"=>$weekArr[$week],
                    "WEEKEND"=>$week==0 || $week==6?true:false,
                ];
            $date = date('m/d/Y', strtotime($date . "+1 days"));
        }

        $this->arResult['COMPONENT_NAME']=$this->GetName();

        $this->IncludeComponentTemplate();
    }

    public function getDayTimetable($date=false){
        if (empty($date)){
            $date=date('d.m.Y');
        }

        $CURR_TIME="10:00";

        //тут надо по апи принять расписание тренера конкретного
//        global $USER;
//        $rsUser = CUser::GetByID($USER->GetID());
//        $arUser = $rsUser->Fetch();
//
//        $api=new Api([
//            "action"=>"coachworkouts",
//            "params"=>[
//                "id1c"=>$arUser['UF_1CID'],
//                "login"=>$arUser["LOGIN"],
//                "date"=>$date
//            ]
//        ]);
//        $response=$api->result();
//        if (!$response["success"]){
//            if (!empty($response['data']['result']['userMessage'])){
//               throw new Exception($response['data']['result']['userMessage'], 1);
//            }
//            else{
//                throw new Exception("Непредвиденная ошибка", 100);
//            }
//        }

//        $timetable=$response['data']['result']["result"];

//      ИМИТАЦИЯ ДАННЫХ
        $timetable=[
            "15:00"=>[
                "type"=>"busy",
                "workout"=>[
                    "id"=>"0125",
                    "type"=>"Персональная тренировка",
                    "client"=>"Павел Воля",
                    "changeble"=>true,
                ]
            ],
            "15:15"=>[
                "type"=>"busy",
                "workout"=>[
                    "id"=>"0125",
                    "type"=>"Персональная тренировка",
                    "client"=>"Павел Воля",
                    "changeble"=>true,
                ]
            ],
            "15:30"=>[
                "type"=>"busy",
                "workout"=>[
                    "id"=>"0125",
                    "type"=>"Персональная тренировка",
                    "client"=>"Павел Воля",
                    "changeble"=>true,
                ]
            ],
            "15:45"=>[
                "type"=>"busy",
                "workout"=>[
                    "id"=>"0125",
                    "type"=>"Персональная тренировка",
                    "client"=>"Павел Воля",
                    "changeble"=>true,
                ]
            ],
            "17:45"=>[
                "type"=>"busy",
                "workout"=>[
                    "id"=>"0123",
                    "type"=>"Персональная тренировка",
                    "client"=>"Гарик Харламов",
                    "changeble"=>true,
                ]
            ],
            "18:00"=>[
                "type"=>"busy",
                "workout"=>[
                    "id"=>"0123",
                    "type"=>"Персональная тренировка",
                    "client"=>"Гарик Харламов",
                    "changeble"=>true,
                ]
            ],
            "18:15"=>[
                "type"=>"busy",
                "workout"=>[
                    "id"=>"0123",
                    "type"=>"Персональная тренировка",
                    "client"=>"Гарик Харламов",
                    "changeble"=>true,
                ]
            ],
            "18:30"=>[
                "type"=>"busy",
                "workout"=>[
                    "id"=>"0123",
                    "type"=>"Персональная тренировка",
                    "client"=>"Гарик Харламов",
                    "changeble"=>true,
                ]
            ],
            "18:45"=>[
                "type"=>"busy",
                "workout"=>[
                    "id"=>"0123",
                    "type"=>"Персональная тренировка",
                    "client"=>"Гарик Харламов",
                    "changeble"=>true,
                ]
            ],
            "19:00"=>[
                "type"=>"busy",
                "workout"=>[
                    "id"=>"0123",
                    "type"=>"Персональная тренировка",
                    "client"=>"Гарик Харламов",
                    "changeble"=>true,
                ]
            ],
            "19:15"=>[
                "type"=>"busy",
                "workout"=>[
                    "id"=>"0123",
                    "type"=>"Персональная тренировка",
                    "client"=>"Гарик Харламов",
                    "changeble"=>true,
                ]
            ],
            "19:30"=>[
                "type"=>"busy",
                "workout"=>[
                    "id"=>"0124",
                    "type"=>"Пробная тренировка",
                    "client"=>"Тимур Батруха",
                    "changeble"=>false,
                ]
            ],

            "20:00"=>[
                "type"=>"busy",
                "workout"=>[
                    "id"=>"0124",
                    "type"=>"Пробная тренировка",
                    "client"=>"Тимур Батруха",
                    "changeble"=>false,
                ]
            ],
            "21:00"=>[
                "type"=>"free"
            ],
            "20:30"=>[
                "type"=>"free"
            ],
            "20:45"=>[
                "type"=>"free"
            ],
            "19:45"=>[
                "type"=>"busy",
                "workout"=>[
                    "id"=>"0124",
                    "type"=>"Пробная тренировка",
                    "client"=>"Тимур Батруха",
                    "changeble"=>false,
                ]
            ],
        ];

        $result=[];
        while ($CURR_TIME<="21:00"){
            if ($date==date("d.m.Y") && date("H:i")>$CURR_TIME){
                $CURR_TIME=date('H:i',  strtotime("+15 minutes", strtotime($CURR_TIME)));
                continue;
            }
            if ($CURR_TIME<"12:00"){
                $key="0MORNING";
            }
            elseif ($CURR_TIME<"18:00"){
                $key="1DAYTIME";
            }
            else{
                $key="2EVENING";
            }

            if (key_exists($CURR_TIME, $timetable)){
                if (!is_array($result[$key][$CURR_TIME])){
                    $result[$key][$CURR_TIME]=[
                        "TYPE"=>$timetable[$CURR_TIME]["type"],
                    ];
                    if ($timetable[$CURR_TIME]["type"]=="busy"){
                        $result[$key][$CURR_TIME]["WORKOUT"]=$timetable[$CURR_TIME]["workout"];
                    }
                }
            }
            else{
                $result[$key][$CURR_TIME]=["TYPE"=>"EMPTY"];
            }
            $CURR_TIME=date('H:i',  strtotime("+15 minutes", strtotime($CURR_TIME)));
        }

        ksort($result);
        $_SESSION["TW_TIMETABLE"]=$result;
        return $result;
    }


    //    AJAX
    public function getTimetableAction(){
        $date=Context::getCurrent()->getRequest()->getPost('date');
        if (empty($date)){
            $date=date('d.m.Y');
        }

        $this->arResult["TIMETABLE"]=$this->getDayTimetable($date);
        ob_start();
        $this->IncludeComponentTemplate('timetable');
        $result=ob_get_clean();

        return $result;
    }

    //Добавляем свободный слот в расписание
    public function setSlotsAction(){
        $date=date('d.m.Y', strtotime(Context::getCurrent()->getRequest()->getPost('date')));
        $slots=Context::getCurrent()->getRequest()->getPost('slots');
        $action=Context::getCurrent()->getRequest()->getPost("action");
        if ($action=="busy"){
            $client=Context::getCurrent()->getRequest()->getPost("client");
            if (empty($client)){
                throw new Exception("Введите имя и фамилию клиента", 1);
            }
        }

        //Отправляем инфу в апи
        global $USER;
        $rsUser = CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();

        $arParams=[
            "id1c"=>$arUser['UF_1CID'],
            "login"=>$arUser["LOGIN"],
            "date"=>$date,
            "action"=>$action,
            "slots"=>$slots
        ];
        if ($action=="busy"){
            $arParams["client"]=$client;
        }
//        $api=new Api([
//            "action"=>"coachsetslots",
//            "params"=>$arParams
//        ]);
//        $response=$api->result();
//        if (!$response["success"]){
//            if (!empty($response['data']['result']['userMessage'])){
//               throw new Exception($response['data']['result']['userMessage'], 1);
//            }
//            else{
//                throw new Exception("Непредвиденная ошибка", 100);
//            }
//        }

        return $arParams;
    }
}
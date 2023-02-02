<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class PersonalTrialWorkout extends CBitrixComponent implements Controllerable{
    public function ConfigureActions(){
        return [
            'setSlot'=>[
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
            'getTrainers'=>[
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

    function onPrepareComponentParams($arParams){

    }

    private function GetClubArr(){
        $arFilter = array(
            "IBLOCK_CODE" => 'clubs',
            "PROPERTY_SOON" => false,
            "ACTIVE" => "Y",
            "PROPERTY_HIDE_LINK_VALUE"=>false
        );

        $dbElements = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilter, false, false, array("ID", "CODE", "NAME", "PROPERTY_NUMBER"));
        while ($res = $dbElements->fetch()) {
            $CLUBS[]=array(
                'VALUE'=>$res["PROPERTY_NUMBER_VALUE"],
                'STRING'=>$res["NAME"]
            );
        }
        return $CLUBS;
    }

    function executeComponent()
    {
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
        for($i=0; $i<7; $i++){
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

        $this->arResult["CLUBS_ARR"]=$this->GetClubArr();
        $this->arResult['COMPONENT_NAME']=$this->GetName();


        global $USER;
        $user=CUser::GetByID($USER->GetID())->Fetch();

        $TW=unserialize($user["UF_TRIALWORKOUT"]);
        $this->arResult["NO_COACH"]=$TW["nocoach"];
//        if ($TW["nocoach"]){
//            $result='<option value="free">Без тренера</option>';
//        }

        $this->IncludeComponentTemplate();
    }


    public function getDayTimetable($date, $club_num, $action="new", $tw_type="free"){
        $date=date('d.m.Y', strtotime($date));

        if ($tw_type=="coach"){
            global $USER;
            $rsUser = CUser::GetByID($USER->GetID());
            $arUser = $rsUser->Fetch();
            $id1c=$arUser['UF_1CID'];

            $api=new Api([
                "action"=>"trialworkout",
                "params"=>[
                    "id1c"=>$id1c,
                    "login"=>$USER->GetLogin(),
                    "action"=>$action,
                    "date"=>$date,
                    "clubid"=>$club_num
                ]
            ]);

            $response=$api->result();

//            return [$response, [
//                "id1c"=>$id1c,
//                "login"=>$USER->GetLogin(),
//                "action"=>$action,
//                "date"=>$date,
//                "clubid"=>$club_num
//            ]];

            if (empty($response["data"]["result"]["result"]) && count($response["data"]["result"]["result"])==0){
                return false;
            }

            $timetable=$response["data"]["result"]["result"];
            sort($timetable);

            foreach ($timetable as $time){
                if ($time<"12:00"){
                    $key="0MORNING";
                }
                elseif ($time<"18:00"){
                    $key="1DAYTIME";
                }
                else{
                    $key="2EVENING";
                }

                $result[$key][]=$time;
            }
        }
        else{
            $result=[];
            $CURR_TIME="10:00";
            while ($CURR_TIME<="21:00"){
                if ($date==date("d.m.Y") && date("H:i")>$CURR_TIME){
                    $CURR_TIME=date('H:i',  strtotime("+1 hours", strtotime($CURR_TIME)));
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

                $result[$key][]=$CURR_TIME;
                $CURR_TIME=date('H:i',  strtotime("+1 hours", strtotime($CURR_TIME)));
            }
        }
        ksort($result);
        $_SESSION["TW_TIMETABLE"]=$result;
        return $result;
    }



//    AJAX
    public function getTimetableAction(){
        $date=Context::getCurrent()->getRequest()->getPost('date');
        $tw_type=Context::getCurrent()->getRequest()->getPost('tw_type');
        if (empty($date)){
            $date=date('d.m.Y');
        }
        $club_num=Context::getCurrent()->getRequest()->getPost('club');
        $action=Context::getCurrent()->getRequest()->getPost('action');
        if (empty($action)){
            $action="new";
        }

        $this->arResult["TIMETABLE"]=$this->getDayTimetable($date, $club_num, $action, $tw_type);

//        return $this->arResult["TIMETABLE"];

        ob_start();
        $this->IncludeComponentTemplate('timetable');
        $result=ob_get_clean();

        return $result;
    }

    public function getTrainersAction(){
        $time=trim(Context::getCurrent()->getRequest()->getPost('time'));



        foreach ($_SESSION["TW_TIMETABLE"] as $key=>$value){
            if (key_exists($time,$value)){
                if ($value[$time]["TYPE"]=="FREE"){
                    $type="FREE";
                    foreach($value[$time]["ITEMS"] as $coach){
                        if ($coach["NAME"]=="Пустой контрагент"){
                            $coach["NAME"]="Дежурный тренер";
                        }
                        $result.='<option value="'.$coach["ID"].'">'.$coach["NAME"].'</option>';
                    }
                    break;
                }
                else{
                    $type="NOTFREE";
                    $result.='<option value="">Поиск тренера</option>';
                }
            }
        }
        return ['type'=>$type, "result"=>$result];
    }



    public function setSlotAction()
    {
        global $USER;

        $id = Context::getCurrent()->getRequest()->getPost("tw_type");
        $time = trim(Context::getCurrent()->getRequest()->getPost("time"));
        $clubid = Context::getCurrent()->getRequest()->getPost("club");
        $date=date('d.m.Y', strtotime(Context::getCurrent()->getRequest()->getPost("date")));

        $login = $USER->GetLogin();

        $rsUser = CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();
        $id1c = $arUser['UF_1CID'];


        $arParams = [
            "date" => $date,
            "clubid" => $clubid,
            "time" => $time,
            "login" => $login,
            "id1c" => $id1c,
        ];


        if (!empty(Context::getCurrent()->getRequest()->getPost("action"))) {
            $action = Context::getCurrent()->getRequest()->getPost("action");
        } else {
            $action = "new";
        }
        $arParams["action"] = $action;

        if (empty($id)) {
            $arParams["id"] = "";
        } else {
            $arParams["id"] = $id;
        }

        $api = new Api([
            "action" => "trialworkoutsignup",
            "params" => $arParams,
        ]);

        $response = $api->result();
        if (!$response["success"]) {
            if (!empty($response["data"]["result"]["userMessage"])) {
                throw new Exception($response["data"]["result"]["userMessage"], 1);
            } else {
                throw new Exception("Непредвиденная ошибка", 100);
            }
        }

        PersonalUtils::UpdatePersonalInfoFrom1C($USER->GetID());

        $club=Utils::getClub($clubid, null);
        $clubName=str_replace('<br>', ' ', $club['NAME']);
        return ['reload' => true, "section" => Utils::GetIBlockSectionIDBySID("trialworkout"), 'dataLayer'=>['eLabel'=>$clubName, 'eAction'=>'SendFormTrialWorkout-PersonalAccount', 'eCategory'=>'UX'],];
    }
}


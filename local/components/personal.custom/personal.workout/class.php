<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class PersonalWorkout extends CBitrixComponent implements Controllerable
{
    public function ConfigureActions()
    {
        return [
            'setSlot' => [
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'getTimetable' => [
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

    private function GetClubArr(){
        $arFilter = array(
            "IBLOCK_CODE" => 'clubs',
            "PROPERTY_SOON" => false,
            "ACTIVE" => "Y",
            "PROPERTY_HIDE_LINK_VALUE"=>false
        );

        $dbElements = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilter, false, false, array("ID", "CODE", "NAME", "PROPERTY_NUMBER"));

        if (!empty($_REQUEST["club"])){
            $club_num=$_REQUEST["club"];
        }
        else{
            $club_num="01";
        }

        while ($res = $dbElements->fetch()) {
            $CLUBS[]=array(
                'VALUE'=>$res["PROPERTY_NUMBER_VALUE"],
                'STRING'=>$res["NAME"],
                'SELECTED'=>$club_num==$res["PROPERTY_NUMBER_VALUE"]?true:false
            );
        }
        return $CLUBS;
    }


    function executeComponent()
    {
        global $USER;
        $fields=PersonalUtils::get_personal_fields($USER->GetID(), array(
            "lk-workout-workout",
            "lk-workout-status"
        ));

        $workout=$fields["lk-workout-workout"];

        if ($workout["VALUE"]){
            $this->arResult["WORKOUT"]=$workout;
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
            for($i=0; $i<14; $i++){
                list($day, $week, $month)=explode(';',date('d;w;n', strtotime($date)));

                $this->arResult["DAYS"][]=
                    [
                        "DATE"=>date('d.m.Y', strtotime($date)),
                        "DAY"=>$day,
                        "MONTH"=>$monthArr[$month-1],
                        "WEEK"=>$weekArr[$week],
                        "WEEKEND"=>$week==0 || $week==6?true:false,
                    ];
                $date = date('m/d/Y', strtotime($date . "+1 days"));
            }

            $this->arResult["CLUBS_ARR"]=$this->GetClubArr();
            $this->arResult["NO_COACH"]=$workout["nocoach"];

            if ($this->arResult["WORKOUT"]["CLUE"]){
                $this->arResult["WORKOUT"]["CLUE_VALUE"]=htmlspecialcharsback($this->arResult["WORKOUT"]["CLUE_VALUE"]);
                if (mb_strlen($this->arResult["WORKOUT"]["CLUE_VALUE"])>200){
                    $this->arResult["CLUE_PREVIEW"]=mb_strimwidth($this->arResult["WORKOUT"]["CLUE_VALUE"], 0, 200, "...");
                    $this->arResult["CLUE_DETAIL"]=$this->arResult["WORKOUT"]["CLUE_VALUE"];
                }
                else{
                    $this->arResult["CLUE_PREVIEW"]=$this->arResult["WORKOUT"]["CLUE_VALUE"];
                    $this->arResult["CLUE_DETAIL"]=false;
                }
            }

            $this->IncludeComponentTemplate();
        }
    }

    private function getDayTimetable($club, $date, $action="new", $pt_type="free"){
        if ($pt_type=="coach"){
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
                    "clubid"=>$club
                ]
            ]);

            $response=$api->result();

            if (empty($response["data"]["result"]["result"]) && count($response["data"]["result"]["result"])==0){
                return false;
            }

            $timetable=$response["data"]["result"]["result"];
            sort($timetable);
        }
        else{
            $timetable=[];
            $CURR_TIME="10:00";
            while ($CURR_TIME<="21:00"){
                if ($date==date("d.m.Y") && date("H:i")>$CURR_TIME){
                    $CURR_TIME=date('H:i',  strtotime("+1 hours", strtotime($CURR_TIME)));
                    continue;
                }

                $timetable[]=$CURR_TIME;
                $CURR_TIME=date('H:i',  strtotime("+1 hours", strtotime($CURR_TIME)));
            }
        }

        return $timetable;
    }

    //ajax
    public function getTimetableAction($club, $date, $action="new", $pt_type="free", $template=null){
        $this->arResult["TIMETABLE"]=$this->getDayTimetable($club, $date, $action, $pt_type);
        $this->arResult["ACTION"]=$action;

        ob_start();

        if (!empty($template)){
            $this->setTemplateName($template);
            $this->IncludeComponentTemplate('timetable');
        }
        else{
            $this->IncludeComponentTemplate('timetable');
        }

        $result=ob_get_clean();
        return $result;
    }

    public function setSlotAction($club, $date, $time, $action="new", $pt_type="free", $template=null){

        global $USER;
        $rsUser = CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();

        $arParams = [
            "date" => $date,
            "clubid" => $club,
            "time" => $time,
            "login" => $arUser["LOGIN"],
            "id1c" => $arUser["UF_1CID"],
            "action" => $action,
            "id" => $pt_type
        ];

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

        PersonalUtils::get_lk_info(false, $arUser);
        $CLUB=Utils::getClub($club, null);
        $clubName=str_replace('<br>', ' ', $CLUB['NAME']);

        if ($action=="new"){
            $message="Вы успешно записались на тренировку!";
        }
        else{
            $message="Тренировка успешно изменена!";
        }
        return ['reload' => true, 'dataLayer'=>['eLabel'=>$clubName, 'eAction'=>'SendFormTrialWorkout-PersonalAccount', 'eCategory'=>'UX'], "message"=>$message];
    }
}

//TODO не забудь сделать отмену и подтверждение
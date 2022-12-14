<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class ScheduleClubomponent extends CBitrixComponent implements Controllerable{
    
    function onPrepareComponentParams($arParams){
        if (!$arParams["IBLOCK_CODE"]) {
            $arParams["ERROR"] = "Не выбран инфоблок";
        }

        return $arParams;
    }

    public function ConfigureActions(){
        return [
            'getSchedule' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
        ];
    }

    private function getClub(){
        $arFilter = array(
            "IBLOCK_CODE" => $this->arParams["IBLOCK_CODE"],
            "ACTIVE"=>'Y',
            array(
                "LOGIC" => "AND",
                array("!PROPERTY_SCHEDULE_JSON_VALUE_VALUE"=>"false"),
                array("!PROPERTY_SCHEDULE_JSON_VALUE"=>false),
            ),
            "!PROPERTY_NUMBER"=>false,
            "PROPERTY_HIDE_LINK_VALUE"=>false
            );
        $dbElements = CIBlockElement::GetList(array(), $arFilter, false, false, array("ID", "NAME", "PROPERTY_NUMBER", "PROPERTY_SCHEDULE_JSON"));
        $result = array();
        while ($res = $dbElements->fetch()) {
            $result[$res['PROPERTY_NUMBER_VALUE']] = $res;
            if ($res['PROPERTY_NUMBER_VALUE']==$this->arResult["CLUB_ACTIVE"]){
                $result[$res['PROPERTY_NUMBER_VALUE']]["SELECTED"]=true;
            }
            else{
                $result[$res['PROPERTY_NUMBER_VALUE']]["SELECTED"]=false;
            }
        }
        return $result;
    }



    private function prepareShedule($schedule) {
        $result = array();
        $arSchedule = json_decode($schedule, true);
        function cmp($a, $b) {
            return strcmp($a["beginDate"], $b["beginDate"]);
        }
        usort($arSchedule, "cmp");

        $this->arResult["COACHES"]=[];

        $CHOOSE_TRAINING=false;

        $LESSONS_ID=[];
        foreach ($arSchedule as $key => $item){
            if (!in_array($item["lessonType"]["id"], $LESSONS_ID)){
                $LESSONS_ID[]=$item["lessonType"]["id"];
            }
        }

        $LESSONS=[];
        $filter = ['ACTIVE'=>'Y', 'IBLOCK_ID'=>Utils::GetIBlockIDBySID("club-schedule"), "PROPERTY_ID1C"=>$LESSONS_ID];
        CIBlockElement::GetPropertyValuesArray($LESSONS, $filter['IBLOCK_ID'], $filter);
        foreach ($LESSONS as $key=>$LESSON){
            $LESSONS[$LESSON["ID1C"]["VALUE"]]=$LESSON;
            unset($LESSONS[$key]);
        }

        $this->arResult["FILTERS"]=[];
        foreach ($arSchedule as $key => $item) {
            $date = FormatDate("D",  strtotime("- 3 hours", $item["beginDate"]));

            $now = new DateTime();
            if ($now->format("Y.m.d H:i")>FormatDate("Y.m.d H:i", strtotime("- 3 hours", $item["beginDate"])) &&
                $now->format("Y.m.d H:i")<FormatDate("Y.m.d H:i", strtotime("- 3 hours", $item["endDate"])) &&
                !$CHOOSE_TRAINING){
                $ACTIVE=true;
                $CHOOSE_TRAINING=true;
            }
            else{
                $ACTIVE=false;
            }

            //Группа мышц
            $MUSCULE_GROUPS=[];
            if (!is_array($LESSONS[$item["lessonType"]["id"]]["MUSCULE_GROUPS"]["VALUE"])){
                $LESSONS[$item["lessonType"]["id"]]["MUSCULE_GROUPS"]["VALUE"]=[$LESSONS[$item["lessonType"]["id"]]["MUSCULE_GROUPS"]["VALUE"]];
            }
            foreach ($LESSONS[$item["lessonType"]["id"]]["MUSCULE_GROUPS"]["VALUE"] as $MUSCULE_GROUP){
                if (!key_exists(base64_encode($MUSCULE_GROUP), $this->arResult["FILTERS"]["LOAD_LEVEL"])  && !empty($MUSCULE_GROUP)){
                    $this->arResult["FILTERS"]["MUSCULE_GROUPS"][base64_encode($MUSCULE_GROUP)]=$MUSCULE_GROUP;
                }
                $MUSCULE_GROUPS[]=[
                    "ID"=>base64_encode($MUSCULE_GROUP)
                ];
            }

            //Что я хочу
            $I_WANTS=[];
            if (!is_array($LESSONS[$item["lessonType"]["id"]]["I_WANT"]["VALUE"])){
                $LESSONS[$item["lessonType"]["id"]]["I_WANT"]["VALUE"]=[$LESSONS[$item["lessonType"]["id"]]["I_WANT"]["VALUE"]];
            }
            foreach ($LESSONS[$item["lessonType"]["id"]]["I_WANT"]["VALUE"] as $I_WANT){
                if (!key_exists(base64_encode($I_WANT), $this->arResult["FILTERS"]["LOAD_LEVEL"]) && !empty($I_WANT)){
                    $this->arResult["FILTERS"]["I_WANT"][base64_encode($I_WANT)]=$I_WANT;
                }
                $I_WANTS[]=[
                    "ID"=>base64_encode($I_WANT)
                ];
            }

            //Уровень нагрузки
            $LOAD_LEVELS=[];
            if (!is_array($LESSONS[$item["lessonType"]["id"]]["LOAD_LEVEL"]["VALUE"])){
                $LESSONS[$item["lessonType"]["id"]]["LOAD_LEVEL"]["VALUE"]=[$LESSONS[$item["lessonType"]["id"]]["LOAD_LEVEL"]["VALUE"]];
            }
            foreach ($LESSONS[$item["lessonType"]["id"]]["LOAD_LEVEL"]["VALUE"] as $LOAD_LEVEL){
                if (!key_exists(base64_encode($LOAD_LEVEL), $this->arResult["FILTERS"]["LOAD_LEVEL"])  && !empty($LOAD_LEVEL)){
                    $this->arResult["FILTERS"]["LOAD_LEVEL"][base64_encode($LOAD_LEVEL)]=$LOAD_LEVEL;
                }
                $LOAD_LEVELS[]=[
                    "ID"=>base64_encode($LOAD_LEVEL)
                ];
            }

            $FILTER=[
                "MUSCULE_GROUP"=>$MUSCULE_GROUPS,
                "I_WANT"=>$I_WANTS,
                "LOAD_LEVEL"=>$LOAD_LEVELS
            ];

            if (FormatDate("H:i", strtotime("- 3 hours", $item["beginDate"]))<"12:00"){
                $FILTER["TIME"]="MORNING";
            }
            elseif (FormatDate("H:i", strtotime("- 3 hours", $item["beginDate"]))<"18:00"){
                $FILTER["TIME"]="DAY";
            }
            else{
                $FILTER["TIME"]="EVENING";
            }

            $MEDIA=null;
            if (!empty($LESSONS[$item["lessonType"]["id"]]["VIDEO"]["VALUE"])){
                $type="VIDEO";
                $MEDIA=CFile::GetPath($LESSONS[$item["lessonType"]["id"]]["VIDEO"]["VALUE"]);
            }
            elseif (!empty($LESSONS[$item["lessonType"]["id"]]["IMAGE"]["VALUE"])){
                $type="IMG";
                $image=CFile::ResizeImageGet($LESSONS[$item["lessonType"]["id"]]["IMAGE"]["VALUE"], array('width' => 450, 'height' => 600), BX_RESIZE_IMAGE_PROPORTIONAL);
                $MEDIA=$image["src"];

            }

            $result[$date]["TRAININGS"][] = array(
                "NAME" => $item["lessonType"]["name"],
                "TIME" => FormatDate("H:i", strtotime("- 3 hours", $item["beginDate"])),
                "COACH" => $item["coach"]["firstname"],
                "DESCRIPTION" => nl2br($item["description"]),
                "UID"=>uniqid(),
                "ACTIVE"=>$ACTIVE,
                "COACH_ID"=>$item["coach"]["id"],
                "MEDIA"=>$MEDIA,
                "MEDIA_TYPE"=>$type,
                "FILTER"=>$FILTER,
                "VIRTUAL"=>$item["lessonType"]["online"]
            );
            $result[$date]["DATE"]=FormatDate("d.m", strtotime("- 3 hours", $item["beginDate"]));
            if (date("d.m")==$result[$date]["DATE"]){
                $result[$date]["ACTIVE"]=true;
            }
            else{
                $result[$date]["ACTIVE"]=false;
            }
            $result[$date]["UID"]=uniqid();

//            if (!key_exists($item["coach"]["id"], $this->arResult["COACHES"]) && !empty($item["coach"]["firstname"])){
//                $this->arResult["COACHES"][$item["coach"]["id"]]=$item["coach"];
//            }
//            if (!key_exists($item["direction"]["id"], $this->arResult["DIRECTIONS"]) && !empty($item["direction"]["id"])){
//                $this->arResult["DIRECTIONS"][$item["direction"]["id"]]=$item["direction"];
//            }
//            if (!key_exists($item["lessonType"]["name"], $this->arResult["EXERCISES"]) && !empty($item["lessonType"]["name"])){
//                $this->arResult["EXERCISES"][$item["lessonType"]["name"]]=$item["lessonType"];
//            }
            $this->arResult["TIME_FILTER"]=[
                [
                    "VALUE"=>"MORNING",
                    "NAME"=>"Утро"
                ],
                [
                    "VALUE"=>"DAY",
                    "NAME"=>"День"
                ],
                [
                    "VALUE"=>"EVENING",
                    "NAME"=>"Вечер"
                ],
            ];

        }
        return $result;
    }

    function executeComponent(){
        global $APPLICATION;
        Loader::includeModule('iblock');

        if ($this->arParams["ERROR"]) {
            echo $this->arParams["ERROR"];
            return;
        }

        $clubNumber = $this->request["club"] ? $this->request["club"] : "01";
        $this->arResult["CLUB_ACTIVE"] = $clubNumber;

        if($this->arParams['CLUB_NUMBER']){
            $this->arResult["CLUB_ACTIVE"] = $this->arParams['CLUB_NUMBER'];
        }

        $club = $this->getClub();
        foreach($club as $key => $value){
            $this->arResult["CLUBS"][$key] = $value;
            if ($value["PROPERTY_NUMBER_VALUE"]==$this->arResult["CLUB_ACTIVE"]){
                $this->arResult["SCHEDULE"]=$this->prepareShedule($value["PROPERTY_SCHEDULE_JSON_VALUE"]);
            }
        }

        $this->arResult['COMPONENT_NAME']=$this->GetName();

        global $USER;
        if ($USER->IsAuthorized()){
            $rsUser = CUser::GetByID($USER->GetID());
            if ($arUser2 = $rsUser->Fetch()){
                if (boolval($arUser2["UF_USAGETW"])){
                    $this->arResult["SHOW_TRIALWORKOUT_BTN"]=true;
                    $this->arResult["LK_TRIALWORKOUT"]=true;
                    $this->arResult["LK_TRIALWORKOUT_SECTION"]=Utils::GetIBlockSectionIDBySID('trialworkout_zapis');
                }
                else{
                    $this->arResult["SHOW_TRIALWORKOUT_BTN"]=false;
                }
            }
            else{
                $this->arResult["LK_TRIALWORKOUT"]=false;
                $this->arResult["SHOW_TRIALWORKOUT_BTN"]=true;
            }
        }
        else{
            $this->arResult["LK_TRIALWORKOUT"]=false;
            $this->arResult["SHOW_TRIALWORKOUT_BTN"]=true;
        }
        $this->arResult["AJAX_WEB_FORM_ID"]=Utils::GetFormIDBySID("TRIAL_TRAINING_NEW");

        $this->includeComponentTemplate();
    }


    public function getScheduleAction(){
        global $APPLICATION;
        Loader::includeModule('iblock');

        $club=Context::getCurrent()->getRequest()->getPost('club');
        if (empty($club)){
            throw new Exception('Клуб не выбран');
        }
        $this->arResult["CLUB_ACTIVE"] = $club;
        $club = $this->getClub();
        foreach($club as $key => $value){
            $this->arResult["CLUBS"][$key] = $value;
            if ($value["PROPERTY_NUMBER_VALUE"]==$this->arResult["CLUB_ACTIVE"]){
                $this->arResult["SCHEDULE"]=$this->prepareShedule($value["PROPERTY_SCHEDULE_JSON_VALUE"]);
            }
        }

        if (Context::getCurrent()->getRequest()->getPost('page')=='reception'){
            $template_dir=str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__.'/templates/reception');
        }
        else{
            $template_dir=str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__.'/templates/profitator.style');
        }

        ob_start();
        $this->includeComponentTemplate('',
            $template_dir
        );
        $result=ob_get_clean();

        return ['schedule'=>$result, 'coaches'=>$this->arResult["COACHES"], 'directions'=>$this->arResult["DIRECTIONS"]];
    }
}
?>
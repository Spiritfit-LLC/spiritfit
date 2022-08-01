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

        $this->arResult["COACHES"]=[];

        $CHOOSE_TRAINING=false;
        foreach ($arSchedule as $key => $item) {
            $date = FormatDate("D",  strtotime("- 3 hours", $item["beginDate"]));

            $now = new DateTime();
            if ($now->format("d.m.Y H:i")>FormatDate("d.m.Y H:i", strtotime("- 3 hours", $item["beginDate"])) &&
                $now->format("d.m.Y H:i")<FormatDate("d.m.Y H:i", strtotime("- 3 hours", $item["endDate"])) &&
                !$CHOOSE_TRAINING){
                $ACTIVE=true;
                $CHOOSE_TRAINING=true;
            }
            else{
                $ACTIVE=false;
            }
            $result[$date]["TRAININGS"][] = array(
                "NAME" => $item["lessonType"],
                "TIME" => FormatDate("H:i", strtotime("- 3 hours", $item["beginDate"])),
                "COACH" => $item["coach"]["firstname"],
                "DESCRIPTION" => nl2br($item["description"]),
                "UID"=>uniqid(),
                "ACTIVE"=>$ACTIVE,
                "COACH_ID"=>$item["coach"]["id"]
            );
            $result[$date]["DATE"]=FormatDate("d.m", strtotime("- 3 hours", $item["beginDate"]));
            if (date("d.m")==$result[$date]["DATE"]){
                $result[$date]["ACTIVE"]=true;
            }
            else{
                $result[$date]["ACTIVE"]=false;
            }
            $result[$date]["UID"]=uniqid();

            if (!key_exists($item["coach"]["id"], $this->arResult["COACHES"])){
                $this->arResult["COACHES"][$item["coach"]["id"]]=$item["coach"];
            }
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

        ob_start();
        $this->includeComponentTemplate('',
            str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__.'/templates/profitator.style')
        );
        $result=ob_get_clean();

        return $result;
    }
}
?>
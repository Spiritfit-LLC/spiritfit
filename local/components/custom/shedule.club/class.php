<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;

class ScheduleClubomponent extends CBitrixComponent{
    
    function onPrepareComponentParams($arParams){
        if (!$arParams["IBLOCK_CODE"]) {
            $arParams["ERROR"] = "Не выбран инфоблок";
        }

        return $arParams;
    }

    private function getClub() {
        $arFilter = array("IBLOCK_CODE" => $this->arParams["IBLOCK_CODE"], "ACTIVE"=>'Y');
        $dbElements = CIBlockElement::GetList(array(), $arFilter, false, false, array("ID", "NAME", "PROPERTY_SCHEDULE_JSON", "PROPERTY_NUMBER"));
        $result = array();
        while ($res = $dbElements->fetch()) {
            $result[$res['PROPERTY_NUMBER_VALUE']] = $res;
        }
        return $result;
    }

    private function prepareShedule($schedule) {
        $result = array();
        $arSchedule = json_decode($schedule, true);

        foreach ($arSchedule as $key => $item) {
            $date = FormatDate("D",  strtotime("- 3 hours", $item["beginDate"]));

            $result[$date][] = array(
                "NAME" => $item["lessonType"],
                "TIME" => FormatDate("H:i", strtotime("- 3 hours", $item["beginDate"])),
                "COACH" => $item["coach"]["firstname"],
                "DESCRIPTION" => $item["description"],
            );
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
            $shedule = $this->prepareShedule($value["PROPERTY_SCHEDULE_JSON_VALUE"]);
            if($shedule){
                $this->arResult["CLUB"][$key] = $value['NAME'];
                $this->arResult["SCHEDULE"][$key] = $shedule;
            }
        }
        
        $this->arResult["SETTINGS"] = Utils::getInfo();
        $this->includeComponentTemplate();
        
    }
}
?>
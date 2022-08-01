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
            ]
        ];
    }

    function onPrepareComponentParams($arParams){
        $this->arResult["STATUS"]=1;
        if (!empty($arParams["ADDITIONAL_TW_TABLE"])){
            $this->arResult["ADDITIONAL_TIMETABLE"]=$arParams["ADDITIONAL_TW_TABLE"];
            $this->arResult["STATUS"]=2;
        }
        if((empty($arParams['TW_TABLE']) || count($arParams['TW_TABLE'])==0) && empty($arParams["ADDITIONAL_TW_TABLE"])){
            $this->arResult["STATUS"]=2;
        }
        elseif (empty($arParams["DATE"])){
            $this->arResult["ERROR"]="День не выбран";
        }
        elseif (empty($arParams["CLUB_ID"])){
            $this->arResult["ERROR"]="Клуб не выбран";
        }
        else{
            $this->arResult["ERROR"]=false;
        }

        $this->arResult["TW_ACTION"]=$arParams["TW_ACTION"];
        return $arParams;
    }

    private function parseTimeTable(){
        $result=[];
        foreach($this->arParams['TW_TABLE'] as $TIME){
            if (!is_array($result[mb_substr($TIME["time"], 0, 2)])){
                $result[mb_substr($TIME["time"], 0, 2)]=[];
            }

            if (!is_array($result[mb_substr($TIME["time"], 0, 2)][mb_substr($TIME["time"], 3, 2)])){
                $result[mb_substr($TIME["time"], 0, 2)][mb_substr($TIME["time"], 3, 2)]=[];
            }
            $TIME["name"]=str_replace("Пустой контрагент", "Дежурный тренер", $TIME["name"]);
            array_push($result[mb_substr($TIME["time"], 0, 2)][mb_substr($TIME["time"], 3, 2)], $TIME);

//            $TIME["name"]="TEST";
//            array_push($result[mb_substr($TIME["time"], 0, 2)][mb_substr($TIME["time"], 3, 2)], $TIME);
        }
        if (count($result)>0){
            return $result;
        }
        else{
            return false;
        }
    }

    function executeComponent()
    {
        $this->arResult['COMPONENT_NAME']=$this->GetName();
        $this->arResult["CLUB_ID"]=$this->arParams["CLUB_ID"];
        $this->arResult["DATE"]=$this->arParams["DATE"];

        if ($this->arResult["STATUS"]==1){
            $this->arResult["TIMETABLE"]=$this->parseTimeTable();
            $this->arResult["PAGE_TYPE"]="DEFAULT";

            $this->arResult["ADDITIONAL_TIMETABLE"]=\Bitrix\Main\Component\ParameterSigner::signParameters(
                $this->getName() . "_timetable",
                $this->arParams["TW_TABLE"]
            );

            $this->IncludeComponentTemplate();
        }
        else{
            $this->arResult["PAGE_TYPE"]="CHOOSETIME";
            $this->IncludeComponentTemplate('choosetime');

        }

        $template = & $this->GetTemplate();
        $template->addExternalJs(SITE_TEMPLATE_PATH . '/libs/timepicker-ui/timepicker-ui.umd.js');
        $template->addExternalCss('https://fonts.googleapis.com/icon?family=Material+Icons');
    }


//    AJAX
    public function setSlotAction(){
        global $USER;

        $id=Context::getCurrent()->getRequest()->getPost("tw_coach");
        $time=Context::getCurrent()->getRequest()->getPost("tw_time");
        $clubid=Context::getCurrent()->getRequest()->getPost("clubid");
        $date=Context::getCurrent()->getRequest()->getPost("date");

        $login=$USER->GetLogin();

        $rsUser = CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();
        $id1c=$arUser['UF_1CID'];


        $arParams=[
            "date"=>$date,
            "clubid"=>$clubid,
            "time"=>$time,
            "login"=>$login,
            "id1c"=>$id1c,
        ];


        if(!empty(Context::getCurrent()->getRequest()->getPost("tw_action"))){
            $action=Context::getCurrent()->getRequest()->getPost("tw_action");
        }
        else{
            $action="new";
        }
        $arParams["action"]=$action;

        if (empty($id) || $id=="none"){
            if ($id=="none"){
                $id="";
            }
            elseif (!empty(Context::getCurrent()->getRequest()->getPost("timetable"))){
                $additional_timetable=\Bitrix\Main\Component\ParameterSigner::unsignParameters(
                    $this->getName() . "_timetable",
                    Context::getCurrent()->getRequest()->getPost("timetable")
                );
                $id="";
                foreach ($additional_timetable as $time_item){
                    if ($time_item["time"]==$time){
                        $id=$time_item["id"];
                        break;
                    }
                }
            }
            else{
                $id="";
            }
        }
        $arParams["id"]=$id;


        $api=new Api([
            "action"=>"trialworkoutsignup",
            "params"=>$arParams,
        ]);

        $response=$api->result();
        if (!$response["success"]){
            if (!empty($response["data"]["result"]["userMessage"])){
                throw new Exception($response["data"]["result"]["userMessage"], 1);
            }
            else{
                throw new Exception("Непредвиденная ошибка", 100);
            }
        }

        PersonalUtils::UpdatePersonalInfoFrom1C($USER->GetID());
        return ['reload'=>true, "section"=>Utils::GetIBlockSectionIDBySID("trialworkout")];
    }
}


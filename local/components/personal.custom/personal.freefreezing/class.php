<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class PersonalFreeFreezing extends CBitrixComponent implements Controllerable{
    public function ConfigureActions()
    {
        return [

        ];
    }

    function executeComponent()
    {
        global $USER;
        $fields=PersonalUtils::get_personal_fields($USER->GetID(), array(
            "lk-services-freefreezing",
        ));

        $free_freezing=$fields["lk-services-freefreezing"];
        $this->arResult["TITLE"]=$free_freezing["NAME"];
        if ($free_freezing["VALUE"]["status"]!="block_off" && $free_freezing["VALUE"]["status"]!="block_on"){
            $this->arResult["FORM"]=true;
            $this->arResult["INFO"]=$free_freezing["CLUE_VALUE"];
            foreach ($free_freezing["VALUE"]["count"] as $days){
                $mod=$days%1;
                if ($mod == 1) {
                    $str = " день";
                }
                elseif ($mod>1 && $mod<5){
                    $str = " дня";
                }
                else{
                    $str = " дней";
                }
                $this->arResult["COUNTS"][$days]=$days.$str;
            }

            ksort($this->arResult["COUNTS"]);
        }
        else if ($free_freezing["VALUE"]["status"]=="block_on"){
            $this->arResult["FORM"]=false;
            $this->arResult["INFO"]="Заморозка активирована";
        }
        else if ($free_freezing["VALUE"]["status"]=="block_off"){
            $this->arResult["FORM"]=false;
            $this->arResult["INFO"]="Бесплатная заморозка недоступна";
        }


        $this->IncludeComponentTemplate();
    }

    function doFreeFreezingAction($count){
        global $USER;

        $rsUser = CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();
        $arParams=[
            'id1c'=>$arUser['UF_1CID'],
            'login'=>$arUser['LOGIN'],
            'value'=>(int)$count
        ];

        $api=new Api([
            'action'=>'lkfreefreezingpost',
            'params'=> $arParams
        ]);

        $result=$api->result();
        if (!$result['success']){
            throw new Exception($result['data']['result']['userMessage'], 100);
        }
        PersonalUtils::get_lk_info(false, $arUser);
        return true;
    }
}
<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;


class PersonalPartnerPromocode extends CBitrixComponent implements Controllerable {
    public function ConfigureActions(){
        return [

        ];
    }

    protected function listKeysSignedParameters()
    {
        return [

        ];
    }

    private function preparePromocodes(){
        if (empty($this->arResult["PROMOCODE_LIST"]) || count($this->arResult["PROMOCODE_LIST"])==0){
            $this->arResult["ERROR"]="Партнерские промокоды отсутствуют";
            return;
        }
        foreach ($this->arResult["PROMOCODE_LIST"] as &$PROMOCODE_ITEM){
            if ($PROMOCODE_ITEM["type"]=="FRIEND") continue;
            if (empty($PROMOCODE_ITEM["code"])) continue;

            if (empty($PROMOCODE_ITEM["description"])){
                $PROMOCODE_ITEM["description"]="";
            }

            $PROMOCODE_ITEM["link"]=!empty($PROMOCODE_ITEM["url"]);
//            if (empty($PROMOCODE_ITEM["qr"])){
//                if (!$PROMOCODE_ITEM["link"]){
//                    $qr_data=$PROMOCODE_ITEM["code"];
//                }
//                else{
//                    $qr_data=$PROMOCODE_ITEM["url"];
//                }
//                if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/upload/user_promocode/')) {
//                    mkdir($_SERVER['DOCUMENT_ROOT'] . '/upload/user_promocode/', 0774, true);
//                }
//                global $USER;
//                $fileName=$PROMOCODE_ITEM["code"]."_".$USER->GetID().".png";
//                if (!file_exists($_SERVER["DOCUMENT_ROOT"].'/upload/user_promocode/'.$fileName)){
//                    $arParams=[
//                        'data'=>$qr_data,
//                        'logo'=>false,
//                        'background'=>'white',
//                        "box_size"=>20,
//                        "size"=>1
//                    ];
//
//                    $api=new Api([
//                        'action'=>'getqrcode',
//                        'params'=>$arParams,
//                    ]);
//
//                    $response=$api->result();
//                    if (!$response['success']){
//
//                    }
//                    else{
//                        $qr_source=$response['data']['result']['result']['code_src'];
//                        file_put_contents($_SERVER["DOCUMENT_ROOT"].'/upload/user_promocode/'.$fileName, file_get_contents($qr_source));
//                    }
//                }
//                $PROMOCODE_ITEM["qr"]='/upload/user_promocode/'.$fileName;
//            }
        }
    }

    function executeComponent()
    {
        if (!Loader::includeModule('iblock')) {
            $this->arResult["ERROR"]="Не удалось загрузить модуль iblock";
        }

        global $USER;
        if (!$USER->IsAuthorized()){
            $this->arResult["ERROR"]="Авторизуйтесь для просмотра партнерских промкодов";
        }
        $rsUser = CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();
        $this->arResult["PROMOCODE_LIST"] = unserialize($arUser["UF_PARTNERS_PROMOCODE"]);

        $this->preparePromocodes();

        if (!empty($this->arResult["ERROR"])){
            echo $this->arResult["ERROR"];
            return;
        }

        $this->IncludeComponentTemplate();

    }

}
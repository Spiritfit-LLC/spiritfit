<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class Banner extends CBitrixComponent implements Controllerable
{
    public function ConfigureActions(){
        return [
            'request' => [
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

    function onPrepareComponentParams($arParams){
        if( empty($arParams["URL"]) ){
            $this->arResult["URL"] = ["ALL"];
        }
        else{
            if (!is_array($this->arResult["URL"])){
                $this->arResult["URL"] = [$arParams["URL"]];
            }
            else{
                $this->arResult["URL"] = $arParams["URL"];
            }
        }


        if (!empty($arParams["BACKGROUND"])){
            $this->arResult["BACKGROUND"]="url('".$arParams["BACKGROUND"]."')";
        }
        return $arParams;
    }

    function executeComponent() {
        global $APPLICATION;
        if (!empty($this->arResult["ERROR"])){
            echo $this->arResult["ERROR"];
            return;
        }

        $this->arResult['BANNER_ID']=uniqid();
        $this->arResult["FORM_TYPE"]=$this->arParams["FORM_TYPE"];
        $this->arResult['COMPONENT_NAME']=$this->GetName();

        foreach($this->arResult["URL"] as $URL){
            if ($URL=="ALL"){
                $this->IncludeComponentTemplate();
                break;
            }
            else{
                $PAGE=$APPLICATION->GetCurPage();
                if ($PAGE==$URL){
                    $this->IncludeComponentTemplate();
                    break;
                }
            }
        }
    }

    //AJAX
    public function requestAction(){
        $DATA=Context::getCurrent()->getRequest()->toArray();
        $phone=substr(preg_replace('![^0-9]+!', '', $DATA["phone"]), 1);
        if ($phone[0]!='9' || strlen($phone)!=10){
            throw new Exception("Неверный формат номера", 10);
        }

        if ($DATA["privacy"][0]=="1"){
            $api=new Api([
                "action"=>"phone",
                "params"=>[
                    "type"=>$DATA["type"],
                    "phone"=>$phone
                ]
            ]);
        }
        else{
            throw new Exception("Подтвердите согласие на обработку персональных данных", 10);
        }
        return true;
    }
}
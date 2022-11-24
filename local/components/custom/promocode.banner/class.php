<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;

class PromocodeBanner extends CBitrixComponent{
    function onPrepareComponentParams($arParams){
        if(empty($arParams['PROMOCODE']) && $arParams["PROMOCODE"]!="0"){
            $this->arResult["ERROR"]="Отсутствует промокод";
        }
        elseif (empty($arParams["BANNER_TIME"])){
            $this->arResult["ERROR"]="Не задано время до показа";
        }
        else{
            $this->arResult["ERROR"]=false;
        }
        return $arParams;
    }

    function executeComponent()
    {
        if (empty($this->arResult["ERROR"])){
            $this->arResult["PROMOCODE"]=$this->arParams["PROMOCODE"];
            $this->arResult["BANNER_DISCOUNT"]=$this->arParams["BANNER_DISCOUNT"];
            $this->arResult["BANNER_TIME"]=$this->arParams["BANNER_TIME"];
            $this->arResult["PAGE"]=$this->arParams["PAGE"];
            $this->arResult["CLUB"]=$this->arParams["CLUB"];

            $this->IncludeComponentTemplate();
        }
        else{
            echo $this->arResult["ERROR"];
        }
    }
}
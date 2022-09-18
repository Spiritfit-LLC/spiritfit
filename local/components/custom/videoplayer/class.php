<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class CustomVideoPlayer extends CBitrixComponent implements Controllerable{
    public function ConfigureActions(){
        return [

        ];
    }

    function onPrepareComponentParams($arParams){
        if( empty($arParams['VIDEOFILE']) ){
            $this->arResult["ERROR"] = "Не выбран видефайл";
        }
        return $arParams;
    }

    function executeComponent()
    {
        if (!empty($this->arParams["ERROR"])) {
            \Bitrix\Iblock\Component\Tools::process404(
                '',
                true,
                true,
                true,
                false
            );
        }

        if (empty($this->arParams["ERROR"])){
            $this->arResult['COMPONENT_ID']=uniqid();
            $this->arResult['VIDEOFILE']=$this->arParams['VIDEOFILE'];
            $this->arResult['POSTER']=$this->arParams['POSTER'];
            $this->IncludeComponentTemplate();
        }
        else{
            echo $this->arResult["ERROR"];
        }
    }
}

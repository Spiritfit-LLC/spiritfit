<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class PersonalMainComponent extends CBitrixComponent{

    function onPrepareComponentParams($arParams)
    {
        global $APPLICATION;
        if (empty($arParams["USER_ID"])){
            $this->arResult["ERROR"]="Пользователь не определен";
        }
        else{
            $dbUser=CUser::GetByID($arParams["USER_ID"]);
            if ($arUser=$dbUser->Fetch()){
                $arParams["USER_LOGIN"]=$arUser["LOGIN"];
                $arParams["USER_ID1C"]=$arUser["UF_1CID"];

                $this->arResult["USER_NAME"]=$arUser["NAME"];
                $this->arResult["USER_SURNAME"]=$arUser["LAST_NAME"];

                $this->arResult["USER_PHOTO"]=$arUser["PERSONAL_PHOTO"];
            }
            else{
                $this->arResult["ERROR"]="Пользователь не найден";
            }
        }

        foreach ($arParams["MENU"] as $MENU){
            $MENU["LINK"]=$APPLICATION->GetCurPage().$MENU["LINK"];
        }
        return $arParams;
    }

    function executeComponent()
    {
        $FIELDS=PersonalUtils::get_personal_fields($this->arParams["USER_ID"], array(
            "lk-tarif",
            "lk-loyalty",
            "lk-visits"
        ));

        //TODO:ПРОГРАММА ЛОЯЛЬНОСТИ - НОВАЯ МОДЕЛЬ
        $this->arResult["USER_PL"]=$FIELDS["lk-loyalty"];
        if ($this->arResult["USER_PL"]["VALUE"]["isreg"]){
            $this->arResult["USER_PL_LEVEL"]=$FIELDS["lk-loyalty"]["VALUE"]["level"];
        }

        //TODO:АБОНЕМЕНТ - НОВАЯ МОДЕЛЬ
        $this->arResult["USER_TARIF"]=$FIELDS["lk-tarif"];

        if (!empty($this->arResult["USER_TARIF"]["VALUE"])){
            if ($this->arResult["USER_TARIF"]["VALUE"]["status"]["id"]==4 || $this->arResult["USER_TARIF"]["VALUE"]["status"]["id"]==3){
                $this->arResult["USER_ABONEMENT_NAME"]=$this->arResult["USER_TARIF"]["VALUE"]["name"];
                switch($FIELDS["lk-tarif"]["VALUE"]["type"]){
                    case "d":
                        $period="день";
                        $payment=(int)$FIELDS["lk-tarif"]["VALUE"]["cost"]/$FIELDS["lk-tarif"]["VALUE"]["count"];
                        break;
                    case "m":
                        $period="мес";
                        $payment=(int)$FIELDS["lk-tarif"]["VALUE"]["cost"]/$FIELDS["lk-tarif"]["VALUE"]["count"];
                        break;
                    case "y":
                        $period="мес";
                        $payment=(int)$FIELDS["lk-tarif"]["VALUE"]["cost"]/($FIELDS["lk-tarif"]["VALUE"]["count"] * 12);
                        break;
                }
                $this->arResult["USER_TARIF_PRICE"]=$payment . " руб/" . $period;
            }
            else{
                $this->arResult["USER_ABONEMENT_NAME"]=$this->arResult["USER_TARIF"]["VALUE"]["status"]["name"];
            }

            switch ($this->arResult["USER_TARIF"]["VALUE"]["status"]["id"]){
                case -1:
                case 0:
                    $this->arResult["USER_ABONEMENT_BTN"]=[
                        "TEXT"=>"КУПИТЬ",
                        "LINK"=>"/abonement/"
                    ];
                    break;
                case 4:
                    $this->arResult["USER_ABONEMENT_BTN"]=[
                        "TEXT"=>"СМЕНИТЬ",
                        "LINK"=>"/abonement/"
                    ];
                    break;
                default:
                    $this->arResult["USER_ABONEMENT_BTN"]=null;
                    break;
            }
        }
        else{
            $this->arResult["USER_ABONEMENT_NAME"]="Не куплен";
            $this->arResult["USER_ABONEMENT_BTN"]=null;
        }


        //TODO:ПОСЕЩЕНИЯ - НОВАЯ МОДЕЛЬ
        $this->arResult["USER_VISITS"]=$FIELDS["lk-visits"];



        $this->IncludeComponentTemplate();
    }
}
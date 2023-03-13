<?php

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class PersonalServicesComponent extends CBitrixComponent implements Controllerable{

    public function ConfigureActions()
    {
        return [
            'getDetail' => [
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
        ];
    }

    function executeComponent()
    {
        global $USER;
        if ($USER->IsAuthorized()){
            $dbPromocode=CIBlockElement::GetList(array('SORT'=>"ASC"), array("ACTIVE"=>"Y", "IBLOCK_ID"=>Utils::GetIBlockIDBySID("lk-promocode")));
            if ($el = $dbPromocode->GetNextElement()){
                $arPromocode = $el->GetFields();
                $arPromocode["PROPERTIES"] = $el->GetProperties();
                $promocodes[]=$arPromocode;
            }

            if ($promocodes){
                foreach ($promocodes as $promocode){
                    $user_groups = $USER->GetUserGroupArray();
                    if (count(array_intersect($user_groups, $promocode["PROPERTIES"]["USER_GROUP"]["VALUE"]))>0){
                        $this->arResult["PROMOCODES"][]=$promocode;
                    }
                }
            }



            $fields=PersonalUtils::get_personal_fields($USER->GetID(), array(
                "lk-loyalty-partners",
            ));
            $partners=$fields["lk-loyalty-partners"]["VALUE"]["Spiritbox"];

            $partners_id_arr=array_column($partners, "id");
            if (count($partners_id_arr)>0){
                $dbRes=CIBlockElement::GetList(array("SORT"=>"ASC"), array("IBLOCK_ID"=>Utils::GetIBlockIDBySID("lk-partners"), "ACTIVE"=>"Y", "PROPERTY_ID1C"=>$partners_id_arr));
                while($arRes=$dbRes->Fetch()){
                    $this->arResult["SPIRITBOX"][] = $arRes;
                }
            }

            $dbRes=CIBlockElement::GetList(array("SORT"=>"ASC"), array("IBLOCK_ID"=>Utils::GetIBlockIDBySID("personal-pfd"), "ACTIVE"=>"Y"));
            while ($el = $dbRes->GetNextElement()){
                $arRes=$el->GetFields();
                $arRes["PROPERTIES"]=$el->GetProperties();
                if ($arRes["PROPERTIES"]["TYPE"]["VALUE_XML_ID"]=="1c"){
                    foreach ($fields["lk-loyalty-partners"]["VALUE"] as $key=>$value){
                        if ($key!="Spiritbox" && in_array($arRes["PROPERTIES"]["ID1C"]["VALUE"], array_column($value, "id"))){
                            $arRes["1C_VALUE"]=$value;
                            $pfd[]=$arRes;
                            break;
                        }
                    }
                }
                else{
                    $pfd[]=$arRes;
                }
            }

            $this->arResult["PFD"]=$pfd;

        }
        else{
            $this->arResult["ERROR"]="Пользователь не авторизован";
        }

        $this->IncludeComponentTemplate();
    }

    //AJAX
    public function getDetailAction($id, $template_folder=""){
        global $USER;

        $dbRes=CIBlockElement::GetByID($id);
        if (!$rs=$dbRes->GetNextElement()){
            throw new Exception("Партнер не найден или не доступен", 1);
        }

        $arProps = $rs->GetProperties();
        $arRes = $rs->GetFields();

        if ($arRes["IBLOCK_ID"]==Utils::GetIBlockIDBySID("lk-partners")){
            $fields=PersonalUtils::get_personal_fields($USER->GetID(), array(
                "lk-loyalty-partners",
            ));
            $partners=$fields["lk-loyalty-partners"]["VALUE"]["Spiritbox"];
            $partners_id_arr=array_column($partners, "id");
            if (!in_array($arProps["ID1C"]["VALUE"], $partners_id_arr)){
                throw new Exception("Партнер не найден или не доступен", 1);
            }

            $index=array_search($arProps["ID1C"]["VALUE"], $partners_id_arr);
            $this->arResult["TYPE"] = $partners[$index]["type"];

            if ($this->arResult["TYPE"]=="code"){
                $this->arResult["PROMOCODE"] = $partners[$index]["value"];
            }
            else if ($this->arResult["TYPE"]=="qr"){
                $this->arResult["QR_LINK"]=$partners[$index]["value"];
            }
            $this->arResult["CALL2ACTION"] = "С персональной скидкой выгодней!";

        }
        elseif ($arRes["IBLOCK_ID"]==Utils::GetIBlockIDBySID("lk-promocode")){
            $user_groups = $USER->GetUserGroupArray();
            if (count(array_intersect($user_groups, $arProps["USER_GROUP"]["VALUE"]))==0){
                throw new Exception("Партнер не найден или не доступен", 1);
            }
            $this->arResult["CALL2ACTION"] = "С персональной скидкой выгодней!";
            $this->arResult["TYPE"]="code";
            $this->arResult["PROMOCODE"] = $arProps["PROMOCODE"]["VALUE"];
        }
        elseif ($arRes["IBLOCK_ID"]==Utils::GetIBlockIDBySID("personal-pfd")){
            $this->arResult["CALL2ACTION"] = htmlspecialcharsback($arRes["PREVIEW_TEXT"]);

            if ($arProps["TYPE"]["VALUE_XML_ID"]=="pt_trigger"){
                $this->arResult["TYPE"]="trigger";
                $this->arResult["BTN"]=["value"=>$arProps["VALUE"]["VALUE"], "trigger"=>"workout"];
            }
            elseif ($arProps["TYPE"]["VALUE_XML_ID"]=="link"){
                $this->arResult["TYPE"]="link";
                $this->arResult["BTN"]=[
                    "href"=>$arProps["VALUE"]["DESCRIPTION"],
                    "value"=>$arProps["VALUE"]["VALUE"]
                ];
            }
            elseif ($arProps["TYPE"]["VALUE_XML_ID"]=="promocode"){
                $this->arResult["TYPE"]="code";
                $this->arResult["PROMOCODE"]=$arProps["VALUE"]["VALUE"];
            }
            elseif ($arProps["TYPE"]["VALUE_XML_ID"]=="1c"){
                $fields=PersonalUtils::get_personal_fields($USER->GetID(), array(
                    "lk-loyalty-partners",
                ));
                foreach ($fields["lk-loyalty-partners"]["VALUE"] as $key=>$value){
                    if ($key=="Spiritbox")
                        continue;
                    foreach ($value as $partner){
                        $this->arResult["TYPE"]=$partner["type"];
                        if ($this->arResult["TYPE"]=="code"){
                            $this->arResult["PROMOCODE"]=$partner["value"];
                        }
                        elseif ($this->arResult["TYPE"]=="qr"){
                            $this->arResult["QR_LINK"]=$partner["value"];
                        }
                        break;
                    }
                }
            }
        }




        $this->arResult["DETAIL_PICTURE"] = $arRes["DETAIL_PICTURE"];
        $this->arResult["TITLE"] = $arRes["NAME"];
        $this->arResult["ACTIVE_TO"] = $arRes["ACTIVE_TO"];
        $this->arResult["DETAIL_TEXT"] = $arRes["DETAIL_TEXT"];



        if ($template_folder!==""){
            $template_folder = \Bitrix\Main\Component\ParameterSigner::unsignParameters($this->getName(), $template_folder);
        }

        ob_start();
        $this->IncludeComponentTemplate("detail", $template_folder);
        return ob_get_clean();

    }
}
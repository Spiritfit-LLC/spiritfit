<?php
\Bitrix\Main\Loader::includeModule('rest');

use \Bitrix\Main\Type\DateTime;
use Bitrix\Main\Loader;

class ClubApi extends \IRestService{
    private const SCOPE='club';

    public static function OnRestServiceBuildDescription(){
        return [
            'custom.'.static::SCOPE => [
                'club.info' => [
                    'callback' => [__CLASS__, "getInfo"],
                    'options' => [],
                ],
            ],
        ];
    }

    private static function checkQuery($query, $params){
        if($query['error'])
        {
            throw new \Bitrix\Rest\RestException(
                '',
                'WRONG_REQUEST',
                \CRestServer::STATUS_WRONG_REQUEST
            );
        }

        foreach ($params as $param){
            if (!isset($query[$param]))
            {
                throw new \Bitrix\Rest\RestException(
                    "Отсутствует обязательный параметр $param",
                    'WRONG_REQUEST',
                    \CRestServer::STATUS_WRONG_REQUEST
                );
            }
        }
    }

    public static function getInfo($query, $n, \CRestServer $server){
        Loader::includeModule('iblock');
        $clubNumber=$query["clubid"];
        $arFilter = array("IBLOCK_CODE" => "clubs", "ACTIVE" => "Y");
        if (!empty($clubNumber)){
            $arFilter["PROPERTY_NUMBER"] = $clubNumber;
            $dbElements = CIBlockElement::GetList(array("PROPERTY_NUMBER" => "ASC"), $arFilter, false, array(), array());
            if ($res = $dbElements->GetNextElement()) {
                $props = $res->GetProperties();
                for ($i=0; $i<count($props["PHOTO_GALLERY"]["VALUE"]); $i++){
                    $PHOTOGALLERY[]=[
                        "TITLE"=>$props["PHOTO_DESC"]["DESCRIPTION"][$i],
                        "IMAGE"=>MAIN_SITE_URL . CFile::GetPath($props["PHOTO_GALLERY"]["VALUE"][$i]),
                        "DESCRIPTION"=>htmlspecialchars_decode($props["PHOTO_DESC"]["VALUE"][$i]["TEXT"])
                    ];
                }
                for ($i=0; $i<count($props["UTP"]["VALUE"]); $i++){
                    $UTP[]=[
                        "ICON"=>MAIN_SITE_URL . CFile::GetPath($props["UTP"]["VALUE"][$i]),
                        "TITLE"=>$props["UTP"]["DESCRIPTION"][$i],
                        "DESCRIPTION"=>!empty($props["UTP_DESC"]["VALUE"][$i]["TEXT"])?htmlspecialchars_decode($props["UTP_DESC"]["VALUE"][$i]["TEXT"]):null
                    ];
                }
                return [
                    "PHOTOGALLERY"=>$PHOTOGALLERY,
                    "WORK"=>$props["WORK"]["VALUE"],
                    "EMAIL"=>$props["EMAIL"]["VALUE"],
                    "ADDRESS"=>htmlspecialchars_decode($props["ADRESS"]["VALUE"]["TEXT"]),
                    "CORD"=>$props["CORD_YANDEX"]["VALUE"],
                    "PHONE"=>$props["PHONE"]["VALUE"],
                    "UTP"=>$UTP
                ];
            }
            else{
                throw new \Bitrix\Rest\RestException(
                    'Клуб не найден',
                    'WRONG_REQUEST',
                    \CRestServer::STATUS_WRONG_REQUEST
                );
            }
        }
        $dbElements = CIBlockElement::GetList(array("PROPERTY_NUMBER" => "ASC"), $arFilter, false, array(), array());
        while ($res = $dbElements->GetNextElement()) {
            $props = $res->GetProperties();
            for ($i=0; $i<count($props["PHOTO_GALLERY"]["VALUE"]); $i++) {
                $PHOTOGALLERY[]=[
                    "TITLE"=>$props["PHOTO_DESC"]["DESCRIPTION"][$i],
                    "IMAGE"=>MAIN_SITE_URL . CFile::GetPath($props["PHOTO_GALLERY"]["VALUE"][$i]),
                    "DESCRIPTION"=>htmlspecialchars_decode($props["PHOTO_DESC"]["VALUE"][$i]["TEXT"])
                ];

            }
            for ($i=0; $i<count($props["UTP"]["VALUE"]); $i++){
                $UTP[]=[
                    "ICON"=>MAIN_SITE_URL . CFile::GetPath($props["UTP"]["VALUE"][$i]),
                    "TITLE"=>$props["UTP"]["DESCRIPTION"][$i],
                    "DESCRIPTION"=>!empty($props["UTP_DESC"]["VALUE"][$i]["TEXT"])?htmlspecialchars_decode($props["UTP_DESC"]["VALUE"][$i]["TEXT"]):null
                ];
            }
            $CLUBS[$props["NUMBER"]["VALUE"]] = [
                "PHOTOGALLERY"=>$PHOTOGALLERY,
                "WORK"=>$props["WORK"]["VALUE"],
                "EMAIL"=>$props["EMAIL"]["VALUE"],
                "ADDRESS"=>htmlspecialchars_decode($props["ADRESS"]["VALUE"]["TEXT"]),
                "CORD"=>$props["CORD_YANDEX"]["VALUE"],
                "PHONE"=>$props["PHONE"]["VALUE"],
                "UTP"=>$UTP
            ];
        }

        return $CLUBS;
    }


}

AddEventHandler('rest', 'OnRestServiceBuildDescription', Array("ClubApi", "OnRestServiceBuildDescription"));

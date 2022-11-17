<?php
\Bitrix\Main\Loader::includeModule('rest');

use \Bitrix\Main\Type\DateTime;
use Bitrix\Main\Loader;

class AbonementApi extends \IRestService{
    private const SCOPE='abonement';

    public static function OnRestServiceBuildDescription(){
        return [
            'custom.'.static::SCOPE => [
                'abonement.info' => [
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
        $abonement_id=$query["abonement"];

        $filter=["ACTIVE"=>"Y", "IBLOCK_ID"=>Utils::GetIBlockIDBySID("subscription")];
        if (!empty($abonement_id)){
            $filter["PROPERTY_CODE_ABONEMENT"]=$abonement_id;
            $dbElements = CIBlockElement::GetList(array("SORT" => "ASC"), $filter, false, array(), array());
            if ($abonement=$dbElements->GetNextElement()){
                $fields = $abonement->GetFields();
                $props = $abonement->GetProperties();
                $ABONEMENT=[
                    "TITLE"=>HTMLToTxt(html_entity_decode($fields['NAME'], ENT_NOQUOTES, 'UTF-8'), "", [], false),
                    "BACKGROUND"=>MAIN_SITE_URL.CFile::GetPath($fields["PREVIEW_PICTURE"]),
                    "DESCRIPTION"=>HTMLToTxt(html_entity_decode($fields["PREVIEW_TEXT"], ENT_NOQUOTES, 'UTF-8'), "", [], false),
                    "PAYMENT_LIST"=>$props["INCLUDE"]["VALUE"],
                ];

                $FEATURES=[];
                foreach ($props["FOR_PRESENT"]["VALUE"] as $PRESENT){
                    if (!in_array($PRESENT["LIST"], $FEATURES)){
                        $FEATURES[$PRESENT["LIST"]][]=$PRESENT["PRICE"];
                    }
                }

                foreach ($FEATURES as $CLUBID=>$FEATURE){
                    $dbClub=CIBlockElement::GetProperty(Utils::GetIBlockIDBySID("clubs"),$CLUBID,Array(),Array("CODE"=>"NUMBER"));
                    $club_number=$dbClub->Fetch();

                    $FEATURES[$club_number["VALUE"]]=$FEATURE;
                    unset($FEATURES[$CLUBID]);
                }

                $ABONEMENT["FEATURES"]=$FEATURES;

                return $ABONEMENT;
            }
            else{
                throw new \Bitrix\Rest\RestException(
                    'Абонемент не найден',
                    'WRONG_REQUEST',
                    \CRestServer::STATUS_WRONG_REQUEST
                );
            }
        }
        else{
            $dbElements = CIBlockElement::GetList(array("SORT" => "ASC"), $filter, false, array(), array());
            while ($abonement=$dbElements->GetNextElement()){
                $fields = $abonement->GetFields();
                $props = $abonement->GetProperties();
                $ABONEMENT=[
                    "TITLE"=>HTMLToTxt(html_entity_decode($fields['NAME'], ENT_NOQUOTES, 'UTF-8'), "", [], false),
                    "BACKGROUND"=>MAIN_SITE_URL.CFile::GetPath($fields["PREVIEW_PICTURE"]),
                    "DESCRIPTION"=>HTMLToTxt(html_entity_decode($fields["PREVIEW_TEXT"], ENT_NOQUOTES, 'UTF-8'), "", [], false),
                    "PAYMENT_LIST"=>$props["INCLUDE"]["VALUE"],
                ];

                $FEATURES=[];
                foreach ($props["FOR_PRESENT"]["VALUE"] as $PRESENT){
                    if (!in_array($PRESENT["LIST"], $FEATURES)){
                        $FEATURES[$PRESENT["LIST"]][]=$PRESENT["PRICE"];
                    }
                }

                foreach ($FEATURES as $CLUBID=>$FEATURE){
                    $dbClub=CIBlockElement::GetProperty(Utils::GetIBlockIDBySID("clubs"),$CLUBID,Array(),Array("CODE"=>"NUMBER"));
                    $club_number=$dbClub->Fetch();

                    $FEATURES[$club_number["VALUE"]]=$FEATURE;
                    unset($FEATURES[$CLUBID]);
                }

                $ABONEMENT["FEATURES"]=$FEATURES;
                $RESULT[]=$ABONEMENT;
            }

            return $RESULT;
        }

    }


}

AddEventHandler('rest', 'OnRestServiceBuildDescription', Array("AbonementApi", "OnRestServiceBuildDescription"));

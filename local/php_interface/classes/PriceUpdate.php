<?php

use \Bitrix\Main\Loader;

class PriceUpdate {

    public static $apiUrl = "https://app.spiritfit.ru/Fitness/hs/website/0/pricenew";
 
    public static $logFile = "/logs/update-price.txt";

    public static function init() {
        if (!Loader::includeModule('iblock')) {
            return false;
        }

        file_put_contents($_SERVER["DOCUMENT_ROOT"] . self::$logFile, "Начало обновления цен " . date('d.m.Y H:i:s')."\n", FILE_APPEND);
        
        $clubs = self::getClubs();
        $abonements = self::getAbonements();

        self::updateElement($clubs, $abonements);
        
        return "PriceUpdate::init();";
    }

    private static function updateElement($clubs, $abonements) {
        
        $arResultElements = self::send();

        /* Временно отключаем обноление цен у абонементов с кодами 1С */
        if( isset($arResultElements["reload"]) ) {
            unset($arResultElements["reload"]);
        }
        if( isset($arResultElements["reload1"]) ) {
            unset($arResultElements["reload1"]);
        }
        if( isset($arResultElements["reload2"]) ) {
            unset($arResultElements["reload2"]);
        }
		
//		if( isset($arResultElements["presalesmonth2"]) ) {
//            unset($arResultElements["presalesmonth2"]);
//        }
//		if( isset($arResultElements["presalesmonth"]) ) {
//            unset($arResultElements["presalesmonth"]);
//        }
//		if( isset($arResultElements["presalesyear"]) ) {
//            unset($arResultElements["presalesyear"]);
//        }
//		if( isset($arResultElements["presalesyear2"]) ) {
//            unset($arResultElements["presalesyear2"]);
//        }
        /* Временно отключаем обноление цен у абонементов с кодами 1С */

        foreach ($arResultElements as $key => $arTypeAbonement) {
            $props = array();
            if ($abonements[$key]) {
                //костыль от 16.04
                if($key == "on_demand") {
                    $arTypeAbonement[0]["month"] = 1;
                }
                
                $showPage = false;
                foreach ($arTypeAbonement as $result) {
                    foreach ($clubs as $id => $club) { 
                        if ($result['clubid'] == $club) {
                            
                            if ($result['baseprice']){
                                $showPage = true;
                            }
                           
                            $props["BASE_PRICE"][] = serialize(
                                array(
                                    "NUMBER" => $result["month"],
                                    "PRICE" => $result["baseprice"],
                                    "LIST" => $id
                                )
                            );
                            $props["PRICE"][] = serialize(
                                array(
                                    "NUMBER" => $result["month"],
                                    "PRICE" => $result["price"],
                                    "LIST" => $id
                                )
                            );
                            if ($result['prices']) {
                                $props["PRICE"][] = serialize(
                                    array(
                                        "NUMBER" => $result['prices']["month"],
                                        "PRICE" => $result['prices']["price"],
                                        "LIST" => $id
                                    )
                                );
                            }
							if ($result['prices1']) {
                                $props["PRICE"][] = serialize(
                                    array(
                                        "NUMBER" => $result['prices1']["month"],
                                        "PRICE" => $result['prices1']["price"],
                                        "LIST" => $id
                                    )
                                );
                            }
                        }
                    }
                }
                
                CIBlockElement::SetPropertyValuesEx($abonements[$key], false, $props);
                if (!$showPage){
                    $propBtn = array('SOON' => 11);
                }else{
                    $propBtn = array('SOON' => '');
                }
                CIBlockElement::SetPropertyValuesEx($abonements[$key], false, $propBtn);
            }
        }
		
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . self::$logFile, "Элемент: " . $id."\n", FILE_APPEND);
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . self::$logFile, "Цены: " . json_encode($props)."\n", FILE_APPEND);
    }

    private static function getClubs() {
        $arResult = array();
        $arFilter = array("IBLOCK_CODE" => "clubs", "ACTIVE"=>'Y');
        $dbElements = CIBlockElement::GetList(array(), $arFilter, false, false, array("ID", "PROPERTY_NUMBER"));

        while ($res = $dbElements->fetch()) {
            $arResult[$res["ID"]] = $res["PROPERTY_NUMBER_VALUE"];
        }

        return $arResult;
    }

    private static function getAbonements() {
        $arResult = array();
        $arFilter = array("IBLOCK_CODE" => "subscription", "ACTIVE"=>'Y');
        $dbElements = CIBlockElement::GetList(array(), $arFilter, false, false, array("ID", "PROPERTY_CODE_ABONEMENT"));

        while ($res = $dbElements->fetch()) {
            if ($res["PROPERTY_CODE_ABONEMENT_VALUE"]) {
                $arResult[$res["PROPERTY_CODE_ABONEMENT_VALUE"]] = $res["ID"];
            }
        }

        return $arResult;
    }

    private static function send() {
        $url = self::$apiUrl;
        $options = array( 
            CURLOPT_HEADER => 0, 
            CURLOPT_URL => $url, 
            CURLOPT_PORT => 443,
            CURLOPT_RETURNTRANSFER => 1, 
            CURLOPT_TIMEOUT => 10, 
        ); 
        
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($result, true);
        if ($result["errorCode"] === 0) {
            return $result["result"];
        } else {
            return false;
        }
    }
}
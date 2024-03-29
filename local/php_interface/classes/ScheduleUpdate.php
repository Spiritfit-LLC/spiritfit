<?php

use \Bitrix\Main\Loader;

class ScheduleUpdate {

    public static $apiUrl = "https://app.spiritfit.ru/Fitness/hs/website/%s/schedule";

    public static function init() {
        if (!Loader::includeModule('iblock')) {
            return false;
        }

        $clubs = self::getClubs();

        self::updateElement($clubs);

        return "ScheduleUpdate::init();";
    }

    private static function updateElement($clubs) {
        $props = array();

        foreach ($clubs as $id => $club) {
            $arResult = self::send($club);
            // $props["SCHEDULE_JSON"] = json_encode($arResult); old variant

            /*
            //Смотрим полученные данные
            if( $id === 224 ) {
                $max = 0;
                foreach( $arResult as $r ) {
                    if( $max < $r["beginDate"] ) {
                        $max = $r["beginDate"];
                    }
                }
                echo $max;
                echo '<pre>'; print_r($arResult); echo '</pre>'; exit;
            }*/

//            $arResult = preg_replace_callback('/\\\\u([a-f0-9]{4})/i', create_function('$m', 'return chr(hexdec($m[1])-1072+224);'), json_encode($arResult));
//            $props["SCHEDULE_JSON"] = iconv('cp1251', 'utf-8', $arResult);
            foreach ($arResult as $training){
                $filter = ['ACTIVE'=>'Y', 'IBLOCK_ID'=>Utils::GetIBlockIDBySID("club-schedule"), "PROPERTY_ID1C"=>$training["lessonType"]["id"]];
                $DBRes=CIBlockElement::GetList(array(), $filter);
                if (!$DBRes->Fetch()){
                    $el = new CIBlockElement;
                    $PROP=[
                        "ID1C"=>$training["lessonType"]["id"]
                    ];
                    $arLoadProductArray = Array(
                        "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
                        "IBLOCK_ID"      => Utils::GetIBlockIDBySID("club-schedule"),
                        "PROPERTY_VALUES"=> $PROP,
                        "NAME"           => $training["lessonType"]["name"],
                        "ACTIVE"         => "Y",            // активен
                        "CODE"           => uniqid("club-schedule")
                    );
                    $el->Add($arLoadProductArray);
                }
            }
            $props["SCHEDULE_JSON"] = json_encode($arResult);

            CIBlockElement::SetPropertyValuesEx($id, false, $props);
        }
    }

    private static function getClubs() {
        $arResult = array();
        $arFilter = array("IBLOCK_CODE" => "clubs");
        $dbElements = CIBlockElement::GetList(array(), $arFilter, false, false, array("ID", "PROPERTY_NUMBER"));

        while ($res = $dbElements->fetch()) {
            $arResult[$res["ID"]] = $res["PROPERTY_NUMBER_VALUE"];
        }

        return $arResult;
    }

    private static function send($club) {
        $url = sprintf(self::$apiUrl, $club);
        $options = array(
            CURLOPT_POST => 0,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $url,
            CURLOPT_PORT => 443,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTPHEADER => array("Range: ".
                (strtotime('monday this week') + 3600*3) . "-" .
                (strtotime('sunday this week') + 3600*3 + 86400 - 1)
            )
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
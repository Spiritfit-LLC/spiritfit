<?php
\Bitrix\Main\Loader::includeModule('rest');

use \Bitrix\Main\Type\DateTime;

class WebAnalytics extends \IRestService{
    private const SCOPE='analytics';

    public static function OnRestServiceBuildDescription(){
        return [
            'custom.'.static::SCOPE => [
                'analytics.sessions' => [
                    'callback' => [__CLASS__, "getSessions"],
                    'options' => [],
                ],
                'analytics.users.new' => [
                    'callback' => [__CLASS__, "getNewUsers"],
                    'options' => [],
                ],
                'analytics.phrases' => [
                    'callback' => [__CLASS__, "getPhrases"],
                    'options' => [],
                ],
                'conversion.set' => [
                    'callback' => [__CLASS__, "setConversion"],
                    "options" => []
                ]
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

    private static function NavResult($DBRes, $nPage=0){
        $DBRes->NavStart(500, false, $nPage);
        $result=[];
        while ($ar = $DBRes->Fetch())
        {
            $result[]=$ar;
        }

        return $result;
    }

    public static function getSessions($query, $n, \CRestServer $server){
        self::checkQuery($query, ["date"]);

        if(CModule::IncludeModule("statistic")){
            $filter=[
                "DATE_START_1"=>$query["date"]
            ];

            $rs=CSession::getList(
                ($by = "s_id"),
                ($order = "asc"),
                $filter
            );

            if (isset($query["next_pageN"])){
                $result=self::NavResult($rs, $query["next_pageN"]);
            }
            else{
                $result=self::NavResult($rs, $query["next_pageN"]);
            }

            foreach ($result as &$session){
                $rsHit=CHit::GetByID($session["FIRST_HIT_ID"]);
                $arHit=$rsHit->Fetch();

                $COOCKIES=explode("\n", $arHit["COOKIES"]);
                foreach ($COOCKIES as $VALUE){
                    $pattern = '~\[\K.+?(?=\])~';
                    preg_match_all($pattern, $VALUE, $arr);
                    $arCOOCKIES[$arr[0][0]]=trim(explode("=", $VALUE, 2)[1]);
                }
                if(strpos($arCOOCKIES["_ga"], '.')){
                    $client_id = explode('.', $arCOOCKIES["_ga"]);
                    $client_id = $client_id[count($client_id)-2].'.'.$client_id[count($client_id)-1];
                }
                $session["GOOGLE_CID"]=$client_id;
                $session["YANDEX_CID"]=$arCOOCKIES["_ym_uid"];
                $query=parse_url($session["URL_TO"], PHP_URL_QUERY);
                parse_str($query, $query_array);
                $session["URL_QUERY"]=$query_array;
            }

            return $result;
        }
        else{
            throw new \Bitrix\Rest\RestException(
                'Не удалось загрузить модуль statistic',
                'INTERNAL ERROR',
                \CRestServer::STATUS_INTERNAL
            );
        }
    }

    public static function getNewUsers($query, $n, \CRestServer $server){
        self::checkQuery($query, ["date"]);

        $filter=[
            "DATE_REGISTER_1"=>$query["date"],
            "DATE_REGISTER_2"=>$query["date"]
        ];
        $arParameters=[
            "FIELDS"=>["ID", "LOGIN", "NAME", "LAST_NAME", "DATE_REGISTER"],
            "SELECT"=>[]
        ];
        $rsUsers = CUser::GetList(($by="date_register"), ($order="asc"), $filter, $arParameters); // выбираем пользователей

        if (isset($query["next_pageN"])){
            $result=self::NavResult($rsUsers, $query["next_pageN"]);
        }
        else{
            $result=self::NavResult($rsUsers, $query["next_pageN"]);
        }

        return $result;
    }

    public static function getPhrases($query, $n, \CRestServer $server){

//        $date=new DateTime($query["date"], "d.m.Y");
//        $dateStart=$date->format("Y-m-d H:i:s");
//        $dateEnd=$date->add("1 day")->format("Y-m-d H:i:s");

        if (isset($query["date"])){
            $filter=[
                "DATE1"=>$query["date"],
                "DATE2"=>$query["date"]
            ];
        }

        if ($query["session_id"]){
            $filter["SESSION_ID"]=$query["session_id"];
        }


        $rsPhrases = CPhrase::GetList(($by="s_id"), ($order="asc"), $filter);

        if (isset($query["next_pageN"])){
            $result=self::NavResult($rsPhrases, $query["next_pageN"]);
        }
        else{
            $result=self::NavResult($rsPhrases, $query["next_pageN"]);
        }

        return $result;
    }
}

AddEventHandler('rest', 'OnRestServiceBuildDescription', Array("WebAnalytics", "OnRestServiceBuildDescription"));
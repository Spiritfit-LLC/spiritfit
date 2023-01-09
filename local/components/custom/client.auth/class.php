<?php
use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class ClientAuthComponent extends CBitrixComponent implements Controllerable{
    public function ConfigureActions(){
        return [
            'auth'=>[
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST, ActionFilter\HttpMethod::METHOD_GET)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ]
        ];
    }

    protected function listKeysSignedParameters()
    {
        return [  //массива параметров которые надо брать из параметров компонента
            "CLIENT_ID",
        ];
    }

    function onPrepareComponentParams($arParams)
    {
        if (empty($arParams["CLIENT_ID"])){
            $this->arResult["ERROR"]="ID клиента не задан";
        }

        return $arParams;
    }

    function executeComponent()
    {
        if (!empty($this->arResult["ERROR"])){
            $this->IncludeComponentTemplate("error");
            return;
        }

        $params=[
            "id"=>$this->arParams["CLIENT_ID"],
            "event"=>"get"
        ];

        $api=new Api([
            "action"=>"auth",
            "params"=>$params
        ]);

        $response=$api->result();

//        //ИМИТАЦИЯ ДАННЫХ
//        $response=[
//            "data"=>[
//                "result"=>[
//                    "result"=>true
//                ]
//            ]
//        ];

        if (!$response["data"]["result"]["result"]){
            if (!empty($response["data"]["result"]["userMessage"])){
                $this->arResult["ERROR"]=$response["data"]["result"]["userMessage"];
            }
            else{
                $this->arResult["ERROR"]="Непредвиденная ошибка";
            }
            $this->IncludeComponentTemplate("error");
            return;
        }

        $this->IncludeComponentTemplate();
    }

    public function authAction(){
        $gclient_id=Context::getCurrent()->getRequest()->getCookieRaw('_ga');
        if(strpos($gclient_id, '.')){
            $gclient_id = explode('.', $gclient_id);
            $gclient_id = $gclient_id[count($gclient_id)-2].'.'.$gclient_id[count($gclient_id)-1];
        }

        $params=[
            "id"=>$this->arParams["CLIENT_ID"],
            "cid"=>$gclient_id,
            "yaClientID"=>Context::getCurrent()->getRequest()->getCookieRaw('_ym_uid'),
            "page_url"=>$_SERVER['HTTP_REFERER'],
            "event"=>"access"
        ];


        $api=new Api([
            "action"=>"auth",
            "params"=>$params
        ]);

        $response=$api->result();

//        //ИМИТАЦИЯ ДАННЫХ
//        $response=[
//            "data"=>[
//                "result"=>[
//                    "result"=>true
//                ]
//            ]
//        ];

        if (!$response["data"]["result"]["result"]){
            if (!empty($response["data"]["result"]["userMessage"])){
                $MESSAGE=$response["data"]["result"]["userMessage"];
            }
            else{
                $MESSAGE="Непредвиденная ошибка";
            }

            throw new Exception($MESSAGE);
        }

        return ["result"=>true, "message"=>"Успешная авторизация"];
    }
}
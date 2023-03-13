<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class FormAbonementEmailOrder extends CBitrixComponent implements Controllerable {

    public function ConfigureActions(){
        return [
            'orderRecalc' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
        ];
    }

    protected function listKeysSignedParameters()
    {
        return [  //массива параметров которые надо брать из параметров компонента
            "INVOICE_ID",
        ];
    }

    function onPrepareComponentParams($arParams){
        if( empty($arParams["INVOICE_ID"]) ){
            $this->arResult["ERROR"] = "Не задан номер заказа";
        }
        return $arParams;
    }

    function executeComponent() {
        global $APPLICATION;
        $this->arResult['COMPONENT_NAME']=$this->GetName();

//        $api=new Api([
//            'action'=>'getorder',
//            'params'=>[
//                'invoiceID'=>$this->arParams['INVOICE_ID']
//            ]
//        ]);
//
//        $response=$api->result();

//        ИМИТАЦИЯ ДАННЫХ
        $response=[
            "success"=>true,
            "data"=>[
                "result"=>json_decode('{"result":{"type":"subscription","object":{"subscriptionId":"month2","prices":[{"number":2,"price":1700,"longed":"Ежемесячный платеж"},{"number":1,"price":3490,"longed":"Первый месяц","baseprice":5000}],"free":false},"bonusessum":123,"publicID":"pk_1e0b24ff055b1c6b0e3bb4e0e2774","amount":3490,"description":"Оплата контракта № N10011587 от 22 декабря 2022 г. При оплате, лицо ознакомлено с условиями Оферты, принимает их.","phone":"9091996056","email":"i.harisov@spiritfit.ru","name":"Ильвир","surname":"Харисов","clubid":"13","promocode":"","JsonData":{"cloudPayments":{"CustomerReceipt":{"Items":[{"label":"Контракт № N10011587 от 22 декабря 2022 г.","price":3490,"quantity":1,"amount":3490,"vat":""}],"email":"i.harisov@spiritfit.ru","CustomerInfo":"1"}}},"OfferUri":"https://spiritfit.ru/upload/form/oferta.pdf", "fullprice":3490},"userMessage":"","errorCode":0,"success":true}', true)
            ]
        ];

        if (!$response['success']){
            if(!empty($response["data"]["result"]["userMessage"]) ) {
                $this->arResult["ERROR"]=$response["data"]["result"]["userMessage"];
            } else {
                $this->arResult["ERROR"]="Непредвиденная ошибка";
            }
        }
        else{
            $this->arResult['TYPE']=$response["data"]["result"]['result']['type'];
            $this->arResult['ELEMENT']=$response["data"]["result"]['result']['object'];

            $club=Utils::getClub($response["data"]["result"]['result']['clubid']);

            if ($this->arResult['TYPE']=='service'){
                $this->arResult['PAGE_TYPE']='Услуга';
                $APPLICATION->SetPageProperty('title', $this->arResult['PAGE_TYPE'].' «'.$this->arResult['ELEMENT']['name'].'»');
            }
            elseif ($this->arResult['TYPE']=='subscription'){
                $this->arResult['PAGE_TYPE']='Абонемент';


                $elementRes = CIBlockElement::GetList([], [
                    "IBLOCK_ID" => Utils::GetIBlockIDBySID("subscription"),
                    "PROPERTY_CODE_ABONEMENT"=>$this->arResult['ELEMENT']["subscriptionId"],
                    "ACTIVE" => "Y"], false, false);
                if( $ob = $elementRes->GetNextElement() ) {
                    $fields=$ob->GetFields();

                    $this->arResult["ELEMENT"]["name"]=$fields["NAME"];
                    $this->arResult["ELEMENT"]["description"]=$fields["PREVIEW_TEXT"];

                    $this->arResult["INCLUDE"]=$ob->GetProperty("INCLUDE");
                    $FOR_PRESENT=$ob->GetProperty("FOR_PRESENT");

                    $SERVICES=[];
                    foreach($FOR_PRESENT['VALUE'] as $key=>$arrPresent){
                        if ($arrPresent['LIST']==$club["ID"]){
                            $SERVICES[]=$arrPresent['PRICE'];
                        }
                    }
                    $this->arResult["SERVICES"]=$SERVICES;

                }

            }


            usort($this->arResult['ELEMENT']['prices'], function ($item1, $item2) {
                return $item2['number'] < $item1['number'];
            });

            $this->arResult['CURRENT_PRICE']=$this->arResult['ELEMENT']['prices'][0];


            $this->arResult["ISFREE"]=$this->arResult['ELEMENT']["free"];
            $this->arResult["BONUSES"]=!empty($response["data"]["result"]['result']["bonusessum"])?$response["data"]["result"]['result']["bonusessum"]:null;


            $this->arResult["MODEL"]=[
                "publicId"=>$response["data"]["result"]["result"]["publicID"],
                "description"=>$response["data"]["result"]["result"]["description"],
                "amount"=>(float)$response["data"]["result"]["result"]["amount"],
                "currency"=>'RUB',
                "accountId"=>$response["data"]["result"]["result"]["phone"],
                "invoiceId"=>$response["data"]["result"]["result"]["invoiceId"],
                'email'=>$response["data"]["result"]['result']['email'],
                "requireEmail"=>true,
                "skin"=>"mini",
                "data"=>$response["data"]["result"]["result"]["JsonData"],
                "fullprice"=>$response["data"]["result"]["result"]["fullprice"]
            ];

            $this->arResult["FIELDS"]=[
                [
                    'NAME'=>'КЛУБ',
                    'VALUE'=>$club['NAME']
                ],
                [
                    'NAME'=>'Имя',
                    'VALUE'=>$response["data"]["result"]['result']['name']
                ],
                [
                    'NAME'=>'Фамилия',
                    'VALUE'=>$response["data"]["result"]['result']['surname'],
                ],
                [
                    'NAME'=>'Телефон',
                    'VALUE'=>Utils::phone_format($response["data"]["result"]['result']['phone'])
                ],
                [
                    'NAME'=>'E-mail',
                    'VALUE'=>$response["data"]["result"]['result']['email'],
                ],
                [
                    'NAME'=>'Промокод',
                    'VALUE'=>$response["data"]["result"]['result']['promocode']
                ]
            ];
        }

        if (!empty($this->arResult["ERROR"])){
            echo '<div class="content-center">'.$this->arResult["ERROR"].'</div>';
        }
        else{
            $this->IncludeComponentTemplate();
        }
    }


    public function orderRecalcAction($bonuses){
//        $api=new Api([
//            "action"=>"orderedit",
//            "params"=>[
//                "invoiceID"=>$this->arParams["INVOICE_ID"],
//                "bonusessum"=>(int)$bonuses
//            ]
//        ]);
//
//        $response=$api->result();

//        ИМИТАЦИЯ ДАННЫХ1
//        $response=[
//            "success"=>true,
//            "data"=>[
//                "result"=>json_decode('{"result":{"amount":1000},"userMessage":"","errorCode":0,"success":true}', true)
//            ]
//        ];

        $response=[
            "success"=>true,
            "data"=>[
                "result"=>json_decode('{"result":{"JsonData":{"cloudPayments":{"CustomerReceipt":{"Items":[{"label":"Контракт № N10011587 от 22 декабря 2022 г.","price":"3490","quantity":"1","amount":"3490","vat":""}],"email":"i.harisov@spiritfit.ru","CustomerInfo":"1"}}},"amount":1000},"userMessage":"","errorCode":0,"success":true}', true)
            ]
        ];

        if (!$response['success']){
            if(!empty($response["data"]["result"]["userMessage"]) ) {
                $ERROR=$response["data"]["result"]["userMessage"];
            } else {
                $ERROR="Непредвиденная ошибка";
            }
            throw new Exception($ERROR);
        }

        return [
            "amount"=>$response["data"]["result"]["result"]["amount"],
            "jsonData"=>$response["data"]["result"]["result"]["JsonData"]
        ];
    }
}
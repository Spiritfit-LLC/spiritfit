<?php
use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class FormAbonementOrder extends CBitrixComponent implements Controllerable{
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
            "RESPONSE"
        ];
    }

    function onPrepareComponentParams($arParams){
        if( empty($arParams["INVOICE_ID"]) ){
            $this->arResult["ERROR"] = "Счёт не найден";
        }
        else{
        $api=new Api([
            'action'=>'getorder',
            'params'=>[
                'invoiceID'=>$arParams['INVOICE_ID']
            ]
        ]);

        $response=$api->result();
//
//        ИМИТАЦИЯ ДАННЫХ CLOUD
//        $response=[
//            "success"=>true,
//            "data"=>[
//                "result"=>json_decode('{"result":{"type":"subscription","object":{"subscriptionId":"month2","prices":[{"number":2,"price":1700,"longed":"Ежемесячный платеж"},{"number":1,"price":3490,"longed":"Первый месяц","baseprice":5000}],"free":false},"bonusessum":123,"publicID":"pk_1e0b24ff055b1c6b0e3bb4e0e2774","amount":3490,"description":"Оплата контракта № N10011587 от 22 декабря 2022 г. При оплате, лицо ознакомлено с условиями Оферты, принимает их.","phone":"9091996056","email":"i.harisov@spiritfit.ru","name":"Ильвир","surname":"Харисов","clubid":"13","promocode":"","JsonData":{"cloudPayments":{"CustomerReceipt":{"Items":[{"label":"Контракт № N10011587 от 22 декабря 2022 г.","price":3490,"quantity":1,"amount":3490,"vat":""}],"email":"i.harisov@spiritfit.ru","CustomerInfo":"1"}}},"OfferUri":"https://spiritfit.ru/upload/form/oferta.pdf", "fullprice":3490,"paymentType":"cloudPayments"},"userMessage":"","errorCode":0,"success":true}', true)
//            ]
//        ];
//      ИМИТАЦИЯ ДАННЫХ PAYONLINE
//            $response=[
//                "success"=>true,
//                "data"=>[
//                    "result"=>json_decode('{"result":{"type":"subscription","object":{"subscriptionId":"month2","prices":[{"number":2,"price":1700,"longed":"Ежемесячный платеж"},{"number":1,"price":3490,"longed":"Первый месяц","baseprice":5000}],"free":false},"phone":"9091996056","email":"i.harisov@spiritfit.ru","name":"Ильвир","surname":"Харисов","clubid":"13","promocode":"","bonusessum":123,"amount":3490,"merchantId":81944,"privateSecurityKey":"53eeb7c9-e48e-4b16-9fd7-a798d435aabb","description":"Оплата контракта № N10011587 от 22 декабря 2022 г.","validUntil": "2023-01-31 12:45:00","paymentType":"payOnline","fullprice": 3490},"userMessage":"","errorCode":0,"success":true}', true)
//                ]
//            ];

            if (!$response['success']){
                if(!empty($response["data"]["result"]["userMessage"]) ) {
                    $this->arResult["ERROR"]=$response["data"]["result"]["userMessage"];
                } else {
                    $this->arResult["ERROR"]="Непредвиденная ошибка";
                }
            }

            $arParams["RESPONSE"]=$response["data"]["result"]["result"];
        }
        return $arParams;
    }

    function createPayOnlineUrl(){
        $params	 = 'MerchantId='. $this->arParams["RESPONSE"]["merchantId"];
        $params .= '&OrderId='.$this->arParams["INVOICE_ID"];
        $params .= '&Amount='. number_format($this->arParams["RESPONSE"]["amount"], 2, '.', '');
        $params .= '&Currency='. "RUB";
        if ( $this->arParams["RESPONSE"]["validUntil"])
        {
            $params .= '&ValidUntil=' .  $this->arParams["RESPONSE"]["validUntil"];
        }
        if (strlen($this->arParams["RESPONSE"]["description"])<101 AND strlen($this->arParams["RESPONSE"]["description"])>1)
        {
            $params .= '&OrderDescription=' . $this->arParams["RESPONSE"]["description"];
        }
        $params .= '&PrivateSecurityKey=' . $this->arParams["RESPONSE"]["privateSecurityKey"];
        $SecurityKey=md5($params);
        $Paymenturl="https://secure.payonlinesystem.com/ru/payment/";
        $url_query= "?MerchantId=".$this->arParams["RESPONSE"]["merchantId"].
            "&OrderId=".urlencode($this->arParams["INVOICE_ID"]).
            "&Amount=".number_format($this->arParams["RESPONSE"]["amount"], 2, '.', '')."&Currency=RUB";
        if ($this->arParams["RESPONSE"]["validUntil"]) {
            $url_query.= "&ValidUntil=".urlencode($this->arParams["RESPONSE"]["validUntil"]);
        }
        if (strlen($this->arParams["RESPONSE"]["description"])<101 AND strlen($this->arParams["RESPONSE"]["description"])>1) {
            $url_query.= "&OrderDescription=".urlencode($this->arParams["RESPONSE"]["description"]);
        }
        $url_query.= "&ReturnUrl=".urlencode("https://".$_SERVER['SERVER_NAME']."/abonement/success.php");
        $url_query.= "&FailUrl=".urlencode("https://".$_SERVER['SERVER_NAME']."/abonement/fail.php");

        $url_query.="&SecurityKey=".$SecurityKey;
        $url_full=$Paymenturl.$url_query;

        return $url_full;
    }

    function executeComponent() {
        global $APPLICATION;



        if (!empty($this->arResult["ERROR"])){
            $APPLICATION->SetTitle($this->arResult["ERROR"]);
            define("TITLE_404", $this->arResult["ERROR"]);
            \Bitrix\Iblock\Component\Tools::process404(
                $this->arResult["ERROR"],
                true,
                true,
                true,
                false
            );
            return;
        }

        $this->arResult['TYPE']=$this->arParams["RESPONSE"]['type'];
        $this->arResult['ELEMENT']=$this->arParams["RESPONSE"]['object'];

        $club=Utils::getClub($this->arParams["RESPONSE"]['clubid']);
        if ($this->arResult['TYPE']=='service'){
            $this->arResult['PAGE_TYPE']='Услуга';
            $APPLICATION->SetPageProperty('title', $this->arResult['PAGE_TYPE'].' «'.$this->arResult['ELEMENT']['name'].'»');
            $APPLICATION->SetTitle($this->arResult['PAGE_TYPE'].' «'.$this->arResult['ELEMENT']['name'].'»');
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

                $APPLICATION->SetPageProperty('title', $this->arResult["ELEMENT"]["name"]);
                $APPLICATION->SetTitle($this->arResult["ELEMENT"]["name"]);

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

        $this->arResult['CURRENT_PRICE_BASE']=$this->arResult['ELEMENT']['prices'][0]["baseprice"];
        $this->arResult['CURRENT_PRICE'] = $this->arParams["RESPONSE"]["amount"];
        $this->arResult["ISFREE"]=$this->arResult['ELEMENT']["free"];
        $this->arResult["BONUSES"]=!empty($this->arParams["RESPONSE"]["bonusessum"]) && empty($this->arResult["ISFREE"])?$this->arParams["RESPONSE"]["bonusessum"]:null;


        $this->arResult["FIELDS"]=[
            [
                'NAME'=>'КЛУБ',
                'VALUE'=>$club['NAME']
            ], [
                'NAME'=>'Имя',
                'VALUE'=>$this->arParams["RESPONSE"]['name']
            ], [
                'NAME'=>'Фамилия',
                'VALUE'=>$this->arParams["RESPONSE"]['surname'],
            ], [
                'NAME'=>'Телефон',
                'VALUE'=>Utils::phone_format($this->arParams["RESPONSE"]['phone'])
            ], [
                'NAME'=>'E-mail',
                'VALUE'=>$this->arParams["RESPONSE"]['email'],
            ], [
                'NAME'=>'Промокод',
                'VALUE'=>$this->arParams["RESPONSE"]['promocode']
            ]
        ];

        if ($this->arParams["RESPONSE"]['paymentType']=="cloudPayments"){
            $this->arResult["MODEL"]=[
                "publicId"=>$this->arParams["RESPONSE"]["publicID"],
                "description"=>$this->arParams["RESPONSE"]["description"],
                "amount"=>(float)$this->arParams["RESPONSE"]["amount"],
                "currency"=>'RUB',
                "accountId"=>$this->arParams["RESPONSE"]["phone"],
                "invoiceId"=>$this->arParams["INVOICE_ID"],
                'email'=>$this->arParams["RESPONSE"]['email'],
                "requireEmail"=>true,
                "skin"=>"mini",
                "data"=>$this->arParams["RESPONSE"]["JsonData"],
                "fullprice"=>$this->arParams["RESPONSE"]["fullprice"]
            ];

            $this->setTemplateName(".default");
            $this->IncludeComponentTemplate();
        }
        elseif ($this->arParams["RESPONSE"]['paymentType']=="payOnline"){
            $this->arResult["MODEL"]=[
                "src" => $this->createPayOnlineUrl(),
                "amount" => (float)$this->arParams["RESPONSE"]["amount"],
                "fullprice"=>$this->arParams["RESPONSE"]["fullprice"]
            ];

            $this->setTemplateName("payonline");
            $this->IncludeComponentTemplate();
        }
    }

    public function orderRecalcAction($bonuses){
        $api=new Api([
            "action"=>"orderedit",
            "params"=>[
                "invoiceID"=>$this->arParams["INVOICE_ID"],
                "bonusessum"=>(int)$bonuses
            ]
        ]);

        $response=$api->result();

//        ИМИТАЦИЯ ДАННЫХ1
//        $response=[
//            "success"=>true,
//            "data"=>[
//                "result"=>json_decode('{"result":{"amount":1000},"userMessage":"","errorCode":0,"success":true}', true)
//            ]
//        ];

//        $response=[
//            "success"=>true,
//            "data"=>[
//                "result"=>json_decode('{"result":{"JsonData":{"cloudPayments":{"CustomerReceipt":{"Items":[{"label":"Контракт № N10011587 от 22 декабря 2022 г.","price":"3490","quantity":"1","amount":"3490","vat":""}],"email":"i.harisov@spiritfit.ru","CustomerInfo":"1"}}},"amount":1000},"userMessage":"","errorCode":0,"success":true}', true)
//            ]
//        ];

        if (!$response['success']){
            if(!empty($response["data"]["result"]["userMessage"]) ) {
                $ERROR=$response["data"]["result"]["userMessage"];
            } else {
                $ERROR="Непредвиденная ошибка";
            }
            throw new Exception($ERROR);
        }

        $arResult=[
            "amount"=>$response["data"]["result"]["result"]["amount"]
        ];

        $this->arParams["RESPONSE"]["JsonData"]=$response["data"]["result"]["result"]["JsonData"];
        
        if ($this->arParams["RESPONSE"]["paymentType"]=="cloudPayments"){
            $arResult["jsonData"]=$this->arParams["RESPONSE"]["JsonData"];
        }
        elseif ($this->arParams["RESPONSE"]["paymentType"]=="payOnline"){
            $this->arParams["RESPONSE"]["amount"]=$response["data"]["result"]["result"]["amount"];
            $arResult["src"]=$this->createPayOnlineUrl();
        }

        return $arResult;
    }
}
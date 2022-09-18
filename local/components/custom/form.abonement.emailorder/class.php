<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class FormAbonementEmailOrder extends CBitrixComponent implements Controllerable {

    public function ConfigureActions(){
        return [
            'getClub' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'setPromocode' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'checkCode'=>[
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'getAbonement'=>[
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'getOrder'=>[
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'sendSMS'=>[
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

    function onPrepareComponentParams($arParams){
        if( empty($arParams["INVOICE_ID"]) ){
            $this->arResult["ERROR"] = "Не задан номер заказа";
        }
        return $arParams;
    }

    function executeComponent() {

        $this->arResult['COMPONENT_NAME']=$this->GetName();

        $api=new Api([
            'action'=>'getorder',
            'params'=>[
                'invoiceID'=>$this->arParams['INVOICE_ID']
            ]
        ]);

        $response=$api->result();

        if (!$response['success']){
            if(!empty($response["data"]["result"]["userMessage"]) ) {
                $this->arResult["ERROR"]=$response["data"]["result"]["userMessage"];
            } else {
                $this->arResult["ERROR"]="Непредвиденная ошибка";
            }
        }
        else{
            $this->arResult['TYPE']=$response["data"]["result"]['result']['type'];
            if ($this->arResult['TYPE']=='service'){
                $this->arResult['PAGE_TYPE']='Услуга';
            }
            elseif ($this->arResult['TYPE']=='subscription'){
                $this->arResult['PAGE_TYPE']='Абонемент';
            }

            $this->arResult['ELEMENT']=$response["data"]["result"]['result']['object'];
            global $APPLICATION;
            $APPLICATION->SetPageProperty('title', $this->arResult['PAGE_TYPE'].' «'.$this->arResult['ELEMENT']['name'].'»');

            $props = array();
            $arFilter = array("IBLOCK_CODE" => "price_sign");
            $dbElements = CIBlockElement::GetList(array(), $arFilter, false, false, array("NAME", "PROPERTY_MONTH"));
            while ($res = $dbElements->fetch()) {
                $props[$res["NAME"]] = $res["PROPERTY_MONTH_VALUE"];
            }
            $this->arResult['PRICE_SIGNS']=$props;

            foreach($this->arResult['ELEMENT']['prices'] as $item){
                if(!empty($item['baseprice']))  $this->arResult['BASE_PRICE']=$item['baseprice'];
            }
            $this->arResult['CURRENT_PRICE']=$response["data"]["result"]['result']['amount'];


            $this->arResult['US']=[
                [
                    'NAME'=>'КЛУБ',
                    'VALUE'=>Utils::getClub($response["data"]["result"]['result']['clubid'])['NAME']
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

            $this->arResult['W_DATA']=$response["data"]["result"]['result']['JsonData'];
            $this->arResult['W_PUBLICID']=$response["data"]["result"]['result']['publicID'];
            $this->arResult['W_DESCRIPTION']=$response['data']['result']['result']['description'];
            $this->arResult['W_AMOUNT']=(float)$this->arResult['CURRENT_PRICE'];
            $this->arResult['W_CURRENCY']='RUB';
            $this->arResult['W_USERID']=$response["data"]["result"]['result']['phone'];
            $this->arResult['W_INVOICEID']=$this->arParams['INVOICE_ID'];
            $this->arResult['W_EMAIL']=$response["data"]["result"]['result']['email'];
            $this->arResult['W_EMAILREQUIRE']=true;

        }

        if (!empty($this->arResult["ERROR"])){
            echo '<div class="content-center">'.$this->arResult["ERROR"].'</div>';
        }
        else{
            $this->IncludeComponentTemplate();
        }
    }
}
<?php

use \Bitrix\Main\Loader;


class FeedCreator{
    private static $XMLDoc;
    private static $XMLOffers;
    private static $docLocalAddr;

    public static function init(){
        if (!Loader::includeModule('iblock')) {
            return false;
        }
        self::$docLocalAddr=$_SERVER["DOCUMENT_ROOT"].'/feeds/spiritfeeds.xml';
        self::_checkFeedsDoc();
        self::_saveXML();
        return "FeedCreator::init();";
    }

    private static function _checkFeedsDoc(){
        $date = new DateTime();

        self::$XMLDoc=new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><yml_catalog></yml_catalog>');
        self::$XMLDoc->addAttribute('date', $date->format('Y-m-d H:i:s'));
        self::_createXMLStruct();

    }

    private static function _createXMLStruct(){
        $SITE_NAME='https://spiritfit.ru';


        $shop=self::$XMLDoc->addChild('shop');
        $shop->addChild('name', 'SPIRIT.');
        $shop->addChild('company', 'ООО Рекорд Фитнес');
        $shop->addChild('url', $SITE_NAME);

        $currencies=$shop->addChild('currencies');
        $buffer=$currencies->addChild('currency');
        $buffer->addAttribute('id','RUR');
        $buffer->addAttribute('rate','1');

        $categories=$shop->addChild('categories');

        $categories->addchild('category', 'Все товары')->addAttribute('id', 1);
        $buffer=$categories->addchild('category', 'Спорт и отдых');
        $buffer->addAttribute('id', 11);
        $buffer->addAttribute('parentId',1);

        $buffer=$categories->addchild('category', 'Тренажеры и фитнес');
        $buffer->addAttribute('id', 111);
        $buffer->addAttribute('parentId',11);

        $buffer=$categories->addchild('category', 'Фитнес');
        $buffer->addAttribute('id', 1111);
        $buffer->addAttribute('parentId',111);

        self::$XMLOffers=$shop->addChild('offers');


        //Константы
        $VENDOR='Spirit.Fitness';



        //Получаем все дотсупные клубы
        $elements = [];
        $order = ['SORT' => 'ASC'];
        $filter=[
            'IBLOCK_ID'=>Utils::GetIBlockIDBySID('clubs'),
            'ACTIVE'=>'Y',
            '!PROPERTY_NOT_OPEN_YET_VALUE'=>'Да',
            "!PROPERTY_HIDE_LINK_VALUE" => "Да",
            "!PROPERTY_SOON_VALUE" => "Y"
        ];
        $rows = CIBlockElement::GetList($order, $filter);
        while ($row = $rows->fetch()) {
            if ($row['CODE']=='setevoy-abonement'){
                continue;
            }
            $row['PROPERTIES'] = [];
            $elements[$row['ID']] =& $row;
            unset($row);
        }

        $propertyFilter=[
            'CODE'=>[
                'NUMBER',
                'PHONE',
                'ABONEMENTS'
            ]
        ];
        CIBlockElement::GetPropertyValuesArray($elements, $filter['IBLOCK_ID'], $filter, $propertyFilter);
        unset($rows, $filter, $order);


        //Фиды для абонементов в разрезе клубов
        foreach ($elements as $club){
            $CLUB_NAME=$club['NAME'];
            $CLUB_NUMBER=$club['PROPERTIES']['NUMBER']['VALUE'];

            $arFilter = array("IBLOCK_CODE" => "subscription", "ACTIVE" => "Y", 'ID'=>$club['PROPERTIES']['ABONEMENTS']['VALUE'], 'PROPERTY_FEED_ON_VALUE'=>'Да');
            $dbElements = CIBlockElement::GetList(array("SORT"=>"ASC"), $arFilter);
            while($ob=$dbElements->GetNextElement()){
                $element = $ob->GetFields();
                $element['PROPERTIES'] = $ob->GetProperties();
                if (!is_array($element['PROPERTIES']['FEED_DESC']['VALUE'])){
                    $element['PROPERTIES']['FEED_DESC']['VALUE']=[$element['PROPERTIES']['FEED_DESC']['VALUE']];
                }

                $desc_flag=false;
                for ($i=0; $i<count($element['PROPERTIES']['FEED_DESC']['VALUE']); $i++){
                    $DESC=$element['PROPERTIES']['FEED_DESC']['VALUE'][$i];
                    $CLUB_CODE=$element["PROPERTIES"]["FEED_DESC"]["DESCRIPTION"][$i];
                    if ($CLUB_CODE==$club["CODE"] || $CLUB_CODE==$club["ID"]){
                        $ABONEMENT_DESC=HTMLToTxt(html_entity_decode($DESC['TEXT'], ENT_NOQUOTES, 'UTF-8'), "", [], false);
                        $desc_flag=true;
                    }
                }

                if (!$desc_flag){
                    $ABONEMENT_DESC=HTMLToTxt(html_entity_decode($element['PROPERTIES']['FEED_DESC']['VALUE'][0]['TEXT'], ENT_NOQUOTES, 'UTF-8'), "", [], false);
                }

//                $ABONEMENT_TITLE=$element['PROPERTIES']['FEED_TITLE']['VALUE'];
                $ABONEMENT_ID=$element['ID'];

                $ABONEMENT_URL=$SITE_NAME.'/abonement/'.$element['CODE'].'/'.$CLUB_NUMBER;

                $typePrefix=$element['PROPERTIES']['FEED_TYPE_PREFIX']['VALUE'];


                $OFFER_ID=$club['ID'].$ABONEMENT_ID;

                foreach($element['PROPERTIES']['BASE_PRICE']['VALUE'] as $key=>$arPrice){
                    if ($arPrice['LIST']==$club['ID']){
                        $OLD_PRICE=$arPrice['PRICE'];
                        break;
                    }
                }

                $minPrice=false;
                $flag=false;
                foreach($element['PROPERTIES']['PRICE']['VALUE'] as $key=>$arPrice){
                    if ($arPrice['LIST']==$club['ID']){
                        $PRICE=$arPrice['PRICE'];
                        if (!$minPrice){
                            $minPrice=$PRICE;
                        }
                        elseif ($minPrice>$PRICE){
                            $minPrice=$PRICE;
                        }
                        $flag=true;
                    }
                }
                if (!$flag){
                    continue;
                }
                $PRICE=$minPrice;

                $ABONEMENT_OFFER=self::$XMLOffers->addChild('offer');
                $ABONEMENT_OFFER->addAttribute('type', 'vendor.model');
                $ABONEMENT_OFFER->addAttribute('id', $OFFER_ID);
                $ABONEMENT_OFFER->addAttribute('available', 'true');
                $ABONEMENT_OFFER->addAttribute('group_id', '01');


                $ABONEMENT_OFFER->addChild('url', $ABONEMENT_URL);
                $ABONEMENT_OFFER->addChild('categoryId', 1111);
                if (!empty($OLD_PRICE)){
                    $ABONEMENT_OFFER->addChild('oldprice', $OLD_PRICE);
                }
                $ABONEMENT_OFFER->addChild('price', $PRICE);
                $ABONEMENT_OFFER->addChild('currencyId', 'RUR');
                $ABONEMENT_OFFER->addChild('typePrefix', $typePrefix);
                $ABONEMENT_OFFER->addChild('vendor', $VENDOR);
                $ABONEMENT_OFFER->addChild('model', strip_tags($CLUB_NAME));
                $ABONEMENT_OFFER->addchild('name', $typePrefix.' '.$VENDOR.' '.strip_tags($CLUB_NAME));
                $ABONEMENT_OFFER->addChild('pickup', 'true');
                $ABONEMENT_OFFER->addChild('delivery', 'true');
                $ABONEMENT_OFFER->addChild('param', 'Черный')->addAttribute('name', "Цвет");
                $ABONEMENT_OFFER->addChild('description', $ABONEMENT_DESC);
                foreach ($element['PROPERTIES']['FEED_PICTURE']['VALUE'] as $picture){
                    $ABONEMENT_OFFER->addChild('picture', $SITE_NAME.CFile::GetPath($picture));
                }
            }
        }
    }

    private static function _saveXML(){
        if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/feeds')) {
            mkdir($_SERVER['DOCUMENT_ROOT'].'/feeds', 0775, true);
        }
        $dom = new DOMDocument('1.0',  'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML(self::$XMLDoc->asXML());

        $dom->save(self::$docLocalAddr);
    }
}
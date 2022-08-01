<?php


class FeedCreator{
    private $XMLDoc;
    private $XMLOffers;
    private $docLocalAddr;

    public function __construct(){
        $this->docLocalAddr=$_SERVER['DOCUMENT_ROOT'].'/feeds/spiritfeeds.xml';
        $this->_checkFeedsDoc();
        $this->_saveXML();
    }

    private function _checkFeedsDoc(){
        $date = new DateTime();

        $this->XMLDoc=new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><yml_catalog></yml_catalog>');
        $this->XMLDoc->addAttribute('date', $date->format('Y-m-d H:i:s'));
        $this->_createXMLStruct();

    }

    private function _createXMLStruct(){
        $SITE_NAME=((!empty($_SERVER['HTTPS'])) ? 'https' : 'http').'://'.$_SERVER['SERVER_NAME'];


        $shop=$this->XMLDoc->addChild('shop');
        $shop->addChild('name', 'SPIRIT.');
        $shop->addChild('company', 'Юр. Название компании');
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

        $this->XMLOffers=$shop->addChild('offers');


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

                $ABONEMENT_TITLE=$element['PROPERTIES']['FEED_TITLE']['VALUE'];
                $ABONEMENT_ID=$element['ID'];
                $ABONEMENT_DESC=HTMLToTxt(html_entity_decode($element['PROPERTIES']['FEED_DESC']['VALUE']['TEXT'], ENT_NOQUOTES, 'UTF-8'), "", [], false);
                $ABONEMENT_URL=$SITE_NAME.'/abonement/'.$element['CODE'].'/'.$CLUB_NUMBER;

//                var_dump($element['PROPERTIES']['FEED_DESC']['VALUE']['TEXT']);


                $OFFER_ID=$club['ID'].$ABONEMENT_ID;

                foreach($element['PROPERTIES']['BASE_PRICE']['VALUE'] as $key=>$arPrice){
                    if ($arPrice['LIST']==$club['ID']){
                        $OLD_PRICE=$arPrice['PRICE'];
                        break;
                    }
                }

                $flag=false;
                foreach($element['PROPERTIES']['PRICE']['VALUE'] as $key=>$arPrice){
                    if ($arPrice['LIST']==$club['ID']){
                        $PRICE=$arPrice['PRICE'];
                        $flag=true;
                        break;
                    }
                }
                if (!$flag){
                    continue;
                }

                $ABONEMENT_OFFER=$this->XMLOffers->addChild('offer');
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
                $ABONEMENT_OFFER->addChild('typePrefix', 'Абонемент');
                $ABONEMENT_OFFER->addChild('vendor', $VENDOR);
                $ABONEMENT_OFFER->addChild('model', preg_match('/[а-яёА-ЯЁ]+/u', $CLUB_NAME));
                $ABONEMENT_OFFER->addchild('name', $ABONEMENT_TITLE.' | '. $CLUB_NAME);
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

    private function _saveXML(){
        if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/feeds')) {
            mkdir($_SERVER['DOCUMENT_ROOT'].'/feeds', 0775, true);
        }
        $dom = new DOMDocument('1.0',  'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($this->XMLDoc->asXML());

        $dom->save($this->docLocalAddr);
    }
}
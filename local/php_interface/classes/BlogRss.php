<?php

use \Bitrix\Main\Loader;

class BlogRss{
    private static $XMLDoc;
    private static $XMLOffers;
    private static $docLocalAddr;

    public static function init(){
        if (!Loader::includeModule('iblock')) {
            return false;
        }
        self::$docLocalAddr=$_SERVER["DOCUMENT_ROOT"].'/feeds/blog.xml';
        self::_checkFeedDoc();
        self::_saveXML();
        return "BlogRss::init();";
    }

    private static function _checkFeedDoc(){
        $date = new DateTime();

        self::$XMLDoc=new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>'.
            '<rss xmlns:yandex="http://news.yandex.ru" '.
            'xmlns:media="http://search.yahoo.com/mrss/" '.
            'version="2.0"'.
            '></rss>');
        self::_createXMLStruct();
    }

    private static function _createXMLStruct(){
        $channel=self::$XMLDoc->addChild('channel');
        $channel->addChild('title', 'Блог SpiritFitness.');
        $channel->addChild('link', MAIN_SITE_URL);
        $channel->addChild('description', "Блог SpiritFitness");
        $channel->addChild('language', "ru");

        echo date("d.m.Y", strtotime("-8 days"));

        $DBRes=CIBlockElement::GetList(array("timestamp_x"=>"ASC"), array("ACTIVE"=>"Y", "IBLOCK_ID"=>Utils::GetIBlockIDBySID("blog"), "DATE_MODIFY_FROM"=>date("d.m.Y", strtotime("-8 days"))));
        while ($arRes=$DBRes->GetNextElement()){
            $element = $arRes->GetFields();
            $element['PROPERTIES'] = $arRes->GetProperties();

            $TITLE=$element["NAME"];
            while (mb_substr($TITLE, -1)=="."){
                $TITLE=substr_replace($TITLE,'',-1);
            }

            $item=$channel->addChild("item");
            $item->addChild("title", $TITLE);
            $item->addChild("link", MAIN_SITE_URL.$element["DETAIL_PAGE_URL"]);
            $item->addChild("pubDate", date(DATE_RFC822, $element["DATE_CREATE_UNIX"]));

            $TEXT="";
            for ($i=0; $i<count($element['PROPERTIES']['BLOG_TEXT']['VALUE']); $i++){
                $TEXT.=$element['PROPERTIES']['BLOG_TEXT']['DESCRIPTION'][$i].".";

                $section_content=preg_replace("/\xEF\xBB\xBF/", "", htmlspecialchars_decode($element['PROPERTIES']['BLOG_TEXT']['VALUE'][$i]['TEXT']));
                $section_content=preg_replace('{(<br[\\s]*(>|\/>)\s*){2,}}i','$1',$section_content);
                $TEXT.=$section_content;

            }
            $TEXT=HTMLToTxt(html_entity_decode($TEXT, ENT_NOQUOTES, 'UTF-8'), "", array("'<img[^>]*?>'si", "'<a[^>]*?>'si"), false);
            $item->addChild("yandex:full-text", $TEXT, "http://news.yandex.ru");

            $item->addChild("yandex:genre", "article", "http://news.yandex.ru");

            $section=CIBlockSection::GetByID($element["IBLOCK_SECTION_ID"])->Fetch();
            $item->addChild("category", $section["NAME"]);


            $enclosure = $item->addChild("enclosure");
            $enclosure->addAttribute("url", MAIN_SITE_URL.CFile::GetPath($element["PREVIEW_PICTURE"]));

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
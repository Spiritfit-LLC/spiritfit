<?php
use \ImageConverter\Picture;
use \Bitrix\Main\Loader;

class TurboPagesYandex{

    private static $XMLDoc;
    private static $XMLOffers;
    private static $docLocalAddr;

    public static function init(){
        if (!Loader::includeModule('iblock')) {
            return false;
        }

        self::$docLocalAddr=$_SERVER['DOCUMENT_ROOT'].'/blog/rss.xml';
        self::_createRSS();
        self::_saveXML();

        return "TurboPagesYandex::init();";
    }

    private static function _saveXML(){
//        $dom = new DOMDocument('1.0',  'UTF-8');
//        $dom->preserveWhiteSpace = false;
//        $dom->formatOutput = true;
//        $dom->loadXML($this->XMLDoc->asXML());
//        var_dump($this->XMLDoc->asXML());
//
//
//        $dom->save();
        file_put_contents(self::$docLocalAddr, html_entity_decode(self::$XMLDoc->asXML(), ENT_HTML401, "UTF-8"));
    }

    private static function _createRSS(){
        $aMenuLinks = Array(
            Array(
                "Клубы",
                MAIN_SITE_URL."/clubs/",
            ),
            Array(
                "Тренировки",
                MAIN_SITE_URL."/trenirovki/",
            ),
            Array(
                "Приложение",
                MAIN_SITE_URL."/mobilnoe-prilozheniya/",
            ),
            Array(
                "Абонементы",
                MAIN_SITE_URL."/abonement/",
            ),
            Array(
                "Программа лояльности",
                MAIN_SITE_URL."/loyalty-program/",
            ),
        );

        self::$XMLDoc=new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rss xmlns:yandex="http://news.yandex.ru" xmlns:media="http://search.yahoo.com/mrss/" xmlns:turbo="http://turbo.yandex.ru"></rss>');
        self::$XMLDoc->registerXPathNamespace("yandex", "http://news.yandex.ru");
        self::$XMLDoc->registerXPathNamespace("media", "http://search.yahoo.com/mrss/");
        self::$XMLDoc->registerXPathNamespace("turbo", "http://turbo.yandex.ru");

        $NS = array(
            'yandex' => 'http://news.yandex.ru',
            'turbo' => 'http://turbo.yandex.ru',
            'media' => 'http://search.yahoo.com/mrss/'
        );

        foreach ($NS as $prefix => $name) {
            self::$XMLDoc->registerXPathNamespace($prefix, $name);
        }
        $NS = (object) $NS;

        self::$XMLDoc->addAttribute("version", "2.0");


        $channel=self::$XMLDoc->addChild('channel');
        $channel->addChild('title', 'Блог Spirit Fitness');
        $channel->addChild('link', MAIN_SITE_URL);
        $channel->addChild('description', 'Статьи про здоровье, тренировки и питание');
        $channel->addChild('language', 'ru');
        $channel->addChild('turbo:analytics');
        $channel->addChild('turbo:adNetwork');


        $elements = [];
        $order = ['SORT' => 'ASC'];
        $filter=[
            'IBLOCK_ID'=>Utils::GetIBlockIDBySID('blog'),
            'ACTIVE'=>'Y',
        ];
        $rows = CIBlockElement::GetList($order, $filter);
        while ($row = $rows->fetch()) {
            $row['PROPERTIES'] = [];
            $elements[$row['ID']] =& $row;
            unset($row);
        }

        CIBlockElement::GetPropertyValuesArray($elements, $filter['IBLOCK_ID'], $filter);
        unset($rows, $filter, $order);

        //Читать еще
        $readAlso=[];
        for ($index=0; $index<4; $index++){
            $blog_item=array_values($elements)[$index];

            $PICTURE_SRC= "";

            $pictureId = !empty($blog_item["DETAIL_PICTURE"]) ? $blog_item["DETAIL_PICTURE"] : false;
            if( empty($pictureId) && !empty($blog_item["PREVIEW_PICTURE"]) ) $pictureId = $blog_item["PREVIEW_PICTURE"];

            if( !empty($pictureId)) {
                $PICTURE_SRC = CFile::ResizeImageGet($pictureId, ["width" => 1180, "height" => 640], BX_RESIZE_IMAGE_PROPORTIONAL)["src"];
            } else if( !empty($pictureId) ) {
                $PICTURE_SRC = Picture::getResizeWebpFileId($pictureId, 1180, 640, false)["WEBP_SRC"];
            }

            $desc="";
            $i=0;
            foreach(explode(" ", strip_tags($blog_item["DETAIL_TEXT"])) as $word){
                $desc.=$word." ";
                $i++;
                if ($i>19){
                    break;
                }
            }
            if ($i==20){
                $desc.="...";
            }

            $readAlso[$blog_item["ID"]]='<div data-block="feed-item" '.
                'data-title="'.$blog_item["NAME"].'" '.
                'data-description="'.$desc.'" '.
                'data-href="'.MAIN_SITE_URL.'/blog/'.Utils::GetIBlockSectionIDBySID($blog_item['IBLOCK_SECTION_ID'], "CODE").'/'.$blog_item['CODE'].'/'.'" '.
                'data-thumb="'.$PICTURE_SRC.'" '.
                'data-thumb-position="left" '.
                'data-thumb-ratio="4x3"></div>';


        }

        //сновной контент

        foreach ($elements as $blog_item){
            $item=$channel->addChild('item');
            $item->addAttribute('turbo', 'true');

            $item->addChild('extendedHtml', 'true', $NS->turbo);
            $item->addChild('link', MAIN_SITE_URL.'/blog/'.Utils::GetIBlockSectionIDBySID($blog_item['IBLOCK_SECTION_ID'], "CODE").'/'.$blog_item['CODE'].'/');

            $item->addChild("pubDate", date(DATE_RFC822, $blog_item["DATE_CREATE_UNIX"]));
            $yandex=$item->addChild("yandex");
            $yandex->addAttribute("schema_identifier", $blog_item["ID"]);
            $breadcrumblist=$yandex->addChild("breadcrumblist");
            $breadcrumb=$breadcrumblist->addChild("breadcrumb");

            //Хлеб крошки
            $breadcrumb=$breadcrumblist->addChild("breadcrumb");
            $breadcrumb->addAttribute("url", MAIN_SITE_URL.'/');
            $breadcrumb->addAttribute("text", "Главная");

            $breadcrumb=$breadcrumblist->addChild("breadcrumb");
            $breadcrumb->addAttribute("url", MAIN_SITE_URL.'/blog/');
            $breadcrumb->addAttribute("text", "Блог");

            $breadcrumb=$breadcrumblist->addChild("breadcrumb");
            $breadcrumb->addAttribute("url", MAIN_SITE_URL.'/blog/'.Utils::GetIBlockSectionIDBySID($blog_item['IBLOCK_SECTION_ID'], "CODE").'/');
            $breadcrumb->addAttribute("text", Utils::GetIBlockSectionIDBySID($blog_item['IBLOCK_SECTION_ID'], "NAME"));

            $breadcrumb=$breadcrumblist->addChild("breadcrumb");
            $breadcrumb->addAttribute("url", MAIN_SITE_URL.'/blog/'.Utils::GetIBlockSectionIDBySID($blog_item['IBLOCK_SECTION_ID'], "CODE").'/'.$blog_item['CODE'].'/');
            $breadcrumb->addAttribute("text", $blog_item["NAME"]);


            $PICTURE_SRC= "";

            $pictureId = !empty($blog_item["DETAIL_PICTURE"]) ? $blog_item["DETAIL_PICTURE"] : false;
            if( empty($pictureId) && !empty($blog_item["PREVIEW_PICTURE"]) ) $pictureId = $blog_item["PREVIEW_PICTURE"];

            if( !empty($pictureId)) {
                $PICTURE_SRC = CFile::ResizeImageGet($pictureId, ["width" => 1180, "height" => 640], BX_RESIZE_IMAGE_PROPORTIONAL)["src"];
            } else if( !empty($pictureId) ) {
                $PICTURE_SRC = Picture::getResizeWebpFileId($pictureId, 1180, 640, false)["WEBP_SRC"];
            }

            $cntnt_header='<![CDATA['.
            '<header>'.
                '<h1>'.$blog_item["NAME"].'</h1>'.
                    '<figure>'.
                        '<img src="'.MAIN_SITE_URL.$PICTURE_SRC.'"/>'.
                    '</figure>'.
                    '<menu>';
            foreach ($aMenuLinks as $menuLink){
                $cntnt_header.='<a href="'.$menuLink[1].'">'.$menuLink[0].'</a>';
            }
            $cntnt_header.='</menu>';
            $cntnt_header.='<div data-block="breadcrumblist">'.
                '<a href="'.MAIN_SITE_URL.'/">Главная</a>'.
                '<a href="'.MAIN_SITE_URL.'/blog/">Блог</a>'.
                '<a href="'.MAIN_SITE_URL.'/blog/'.Utils::GetIBlockSectionIDBySID($blog_item['IBLOCK_SECTION_ID'], "CODE").'/">'.Utils::GetIBlockSectionIDBySID($blog_item['IBLOCK_SECTION_ID'], "NAME").'</a>'.
                '<a href="'.MAIN_SITE_URL.'/blog/'.Utils::GetIBlockSectionIDBySID($blog_item['IBLOCK_SECTION_ID'], "CODE").'/'.$blog_item['CODE'].'/">'.$blog_item["NAME"].'</a></div>';

            $cntnt_header.='</header>';
            $cntnt_header.='<div itemscope itemtype="http://schema.org/Rating"><meta itemprop="bestRating" content="5"><meta itemprop="ratingValue" content="'.round($blog_item['PROPERTIES']['RATING']['VALUE'],1).'"></div><div class="blog-detail-text"><div>'.HTMLToTxt(html_entity_decode($blog_item["DETAIL_TEXT"], ENT_NOQUOTES, 'UTF-8'), "", [], false).'</div>';

            $ALLCONTENT='<div data-block="accordion">';
            for ($i=0; $i<count($blog_item['PROPERTIES']['BLOG_TEXT']['VALUE']); $i++){
                $section_content=preg_replace("/\xEF\xBB\xBF/", "", htmlspecialchars_decode($blog_item['PROPERTIES']['BLOG_TEXT']['VALUE'][$i]['TEXT']));
                $section_content=preg_replace('{(<br[\\s]*(>|\/>)\s*){2,}}i','$1',$section_content);

                $SECTION_TITLE=$blog_item['PROPERTIES']['BLOG_TEXT']['DESCRIPTION'][$i];
                if (empty($SECTION_TITLE)){
                    $SECTION_TITLE=mb_substr(preg_replace("/\xEF\xBB\xBF/", "", $section_content), 0, 50)."...";
                }

                if ($i==0){
                    $ALLCONTENT.='<div data-block="item" data-title="'.$SECTION_TITLE.'" data-expanded="true">';
                }
                else{
                    $ALLCONTENT.='<div data-block="item" data-title="'.$SECTION_TITLE.'">';
                }
                $ALLCONTENT.='<div class="text-section__text">'.preg_replace("/\xEF\xBB\xBF/", "", $section_content).'</div></div>';

//
//                $cntnt_header.='<div class="text-section">';
//                $cntnt_header.='<h2 class="text-section__title">'.$blog_item['PROPERTIES']['BLOG_TEXT']['DESCRIPTION'][$i].'</h2>';
//                $cntnt_header.='<div class="text-section__text">'.preg_replace("/\xEF\xBB\xBF/", "", $section_content).'</div>';
//                $cntnt_header.='</div>';
            }
            $ALLCONTENT.='</div>';

            $readAlsoContent='<div data-block="feed" data-layout="vertical" data-title="Читайте также">';
            foreach ($readAlso as $key=>$value){
                if ($key!=$blog_item['ID']){
                    $readAlsoContent.=$value;
                }
            }
            $readAlsoContent.='</div>';

            $cntnt_header.=$ALLCONTENT.$readAlsoContent.'</div>]]>';



            $item->addChild("content", $cntnt_header, $NS->turbo);
        }
    }
}
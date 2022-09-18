<?php
use \ImageConverter\Picture;

class TurboPagesYandex{

    private $XMLDoc;
    private $XMLOffers;
    private $docLocalAddr;

    public function __construct(){
        $this->docLocalAddr=$_SERVER['DOCUMENT_ROOT'].'/blog/rss.xml';
        $this->_createRSS();
        $this->_saveXML();
    }

    private function _saveXML(){
//        $dom = new DOMDocument('1.0',  'UTF-8');
//        $dom->preserveWhiteSpace = false;
//        $dom->formatOutput = true;
//        $dom->loadXML($this->XMLDoc->asXML());
//        var_dump($this->XMLDoc->asXML());
//
//
//        $dom->save();
        file_put_contents($this->docLocalAddr, html_entity_decode($this->XMLDoc->asXML(), ENT_HTML401, "UTF-8"));
//        $this->XMLDoc->saveXML($this->docLocalAddr);
    }

    private function _createRSS(){
        $SITE_NAME=((!empty($_SERVER['HTTPS'])) ? 'https' : 'http').'://'.$_SERVER['SERVER_NAME'];
        $aMenuLinks = Array(
            Array(
                "Клубы",
                $SITE_NAME."/clubs/",
            ),
            Array(
                "Тренировки",
                $SITE_NAME."/trenirovki/",
            ),
            Array(
                "Приложение",
                $SITE_NAME."/mobilnoe-prilozheniya/",
            ),
            Array(
                "Абонементы",
                $SITE_NAME."/abonement/",
            ),
            Array(
                "Программа лояльности",
                $SITE_NAME."/loyalty-program/",
            ),
        );

        $this->XMLDoc=new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><rss xmlns:yandex="http://news.yandex.ru" xmlns:media="http://search.yahoo.com/mrss/" xmlns:turbo="http://turbo.yandex.ru"></rss>');
        $this->XMLDoc->registerXPathNamespace("yandex", "http://news.yandex.ru");
        $this->XMLDoc->registerXPathNamespace("media", "http://search.yahoo.com/mrss/");
        $this->XMLDoc->registerXPathNamespace("turbo", "http://turbo.yandex.ru");

        $NS = array(
            'yandex' => 'http://news.yandex.ru',
            'turbo' => 'http://turbo.yandex.ru',
            'media' => 'http://search.yahoo.com/mrss/'
        );

        foreach ($NS as $prefix => $name) {
            $this->XMLDoc->registerXPathNamespace($prefix, $name);
        }
        $NS = (object) $NS;

        $this->XMLDoc->addAttribute("version", "2.0");


        $channel=$this->XMLDoc->addChild('channel');
        $channel->addChild('title', 'Блог Spirit Fitness');
        $channel->addChild('link', $SITE_NAME);
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
                'data-href="'.$SITE_NAME.'/blog/'.Utils::GetIBlockSectionIDBySID($blog_item['IBLOCK_SECTION_ID'], "CODE").'/'.$blog_item['CODE'].'/'.'" '.
                'data-thumb="'.$PICTURE_SRC.'" '.
                'data-thumb-position="left" '.
                'data-thumb-ratio="4x3"></div>';


        }

        //сновной контент

        foreach ($elements as $blog_item){
            $item=$channel->addChild('item');
            $item->addAttribute('turbo', 'true');

            $item->addChild('extendedHtml', 'true', $NS->turbo);
            $item->addChild('link', $SITE_NAME.'/blog/'.Utils::GetIBlockSectionIDBySID($blog_item['IBLOCK_SECTION_ID'], "CODE").'/'.$blog_item['CODE'].'/');

            $item->addChild("pubDate", date(DATE_RFC822, strtotime($blog_item["DATE_CREATE_UNIX"])));
            $yandex=$item->addChild("yandex");
            $yandex->addAttribute("schema_identifier", $blog_item["ID"]);
            $breadcrumblist=$yandex->addChild("breadcrumblist");
            $breadcrumb=$breadcrumblist->addChild("breadcrumb");

            //Хлеб крошки
            $breadcrumb=$breadcrumblist->addChild("breadcrumb");
            $breadcrumb->addAttribute("url", $SITE_NAME.'/');
            $breadcrumb->addAttribute("text", "Главная");

            $breadcrumb=$breadcrumblist->addChild("breadcrumb");
            $breadcrumb->addAttribute("url", $SITE_NAME.'/blog/');
            $breadcrumb->addAttribute("text", "Блог");

            $breadcrumb=$breadcrumblist->addChild("breadcrumb");
            $breadcrumb->addAttribute("url", $SITE_NAME.'/blog/'.Utils::GetIBlockSectionIDBySID($blog_item['IBLOCK_SECTION_ID'], "CODE").'/');
            $breadcrumb->addAttribute("text", Utils::GetIBlockSectionIDBySID($blog_item['IBLOCK_SECTION_ID'], "NAME"));

            $breadcrumb=$breadcrumblist->addChild("breadcrumb");
            $breadcrumb->addAttribute("url", $SITE_NAME.'/blog/'.Utils::GetIBlockSectionIDBySID($blog_item['IBLOCK_SECTION_ID'], "CODE").'/'.$blog_item['CODE'].'/');
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
                        '<img src="'.$SITE_NAME.$PICTURE_SRC.'"/>'.
                    '</figure>'.
                    '<menu>';
            foreach ($aMenuLinks as $menuLink){
                $cntnt_header.='<a href="'.$menuLink[1].'">'.$menuLink[0].'</a>';
            }
            $cntnt_header.='</menu>';
            $cntnt_header.='<div data-block="breadcrumblist">'.
                '<a href="'.$SITE_NAME.'/">Главная</a>'.
                '<a href="'.$SITE_NAME.'/blog/">Блог</a>'.
                '<a href="'.$SITE_NAME.'/blog/'.Utils::GetIBlockSectionIDBySID($blog_item['IBLOCK_SECTION_ID'], "CODE").'/">'.Utils::GetIBlockSectionIDBySID($blog_item['IBLOCK_SECTION_ID'], "NAME").'</a>'.
                '<a href="'.$SITE_NAME.'/blog/'.Utils::GetIBlockSectionIDBySID($blog_item['IBLOCK_SECTION_ID'], "CODE").'/'.$blog_item['CODE'].'/">'.$blog_item["NAME"].'</a></div>';

            $cntnt_header.='</header>';
            $cntnt_header.='<div itemscope itemtype="http://schema.org/Rating"><meta itemprop="bestRating" content="5"><meta itemprop="ratingValue" content="'.round($blog_item['PROPERTIES']['RATING']['VALUE'],1).'"></div><div class="blog-detail-text"><div>'.HTMLToTxt(html_entity_decode($blog_item["DETAIL_TEXT"], ENT_NOQUOTES, 'UTF-8'), "", [], false).'</div>';

            $ALLCONTENT='<div data-block="accordion">';
            for ($i=0; $i<count($blog_item['PROPERTIES']['BLOG_TEXT']['VALUE']); $i++){
                $section_content=preg_replace("/\xEF\xBB\xBF/", "", htmlspecialchars_decode($blog_item['PROPERTIES']['BLOG_TEXT']['VALUE'][$i]['TEXT']));
                $section_content=preg_replace('{(<br[\\s]*(>|\/>)\s*){2,}}i','$1',$section_content);

                if ($i==0){
                    $ALLCONTENT.='<div data-block="item" data-title="'.$blog_item['PROPERTIES']['BLOG_TEXT']['DESCRIPTION'][$i].'" data-expanded="true">';
                }
                else{
                    $ALLCONTENT.='<div data-block="item" data-title="'.$blog_item['PROPERTIES']['BLOG_TEXT']['DESCRIPTION'][$i].'">';
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
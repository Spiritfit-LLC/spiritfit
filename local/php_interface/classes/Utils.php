<?
use \Bitrix\Main\Loader;

class Utils
{
	//общие настройки сайта
    public static function getInfo()
    {
        Loader::includeModule('iblock');
		$arFilter = Array('IBLOCK_ID' => SETTINGS_IBLOCK_ID, 'ID' => SETTINGS_ELEMENT_ID, 'ACTIVE'=>'Y');
		$rsElements = CIBlockElement::GetList(Array('NAME' => 'ASC'), $arFilter);
		if($rsElements->SelectedRowsCount() > 0){
			if ($ob = $rsElements->GetNextElement()) {
				$fields = $ob->GetFields();
                $props = $ob->GetProperties();
                
                if ($props["SVG"]) {
                    $props["SVG"] = CFile::ResizeImageGet($props["SVG"]["VALUE"], array("width"=>"95", "height"=>"40", BX_RESIZE_IMAGE_PROPORTIONAL));
				}
				
                if ($props["SVG_WHITE"]) {
                    $props["SVG_WHITE"] = CFile::ResizeImageGet($props["SVG_WHITE"]["VALUE"], array("width"=>"120", "height"=>"50", BX_RESIZE_IMAGE_PROPORTIONAL));
                }

                if( !empty($props["LOGO_PNG_SRC"]["VALUE"]) ) {
                	$fields["PREVIEW_PICTURE"]["SRC"] = $props["LOGO_PNG_SRC"]["VALUE"];
                }
                if( !empty($props["LOGO_PNG_WHITE_SRC"]["VALUE"]) ) {
                	$props["LOGO_WHITE"] = [];
                	$props["LOGO_WHITE"]["src"] = $props["LOGO_PNG_WHITE_SRC"]["VALUE"];
                }
                if( !empty($props["LOGO_SVG_SRC"]["VALUE"]) ) {
                	$props["SVG"] = [];
                	$props["SVG"]["src"] = $props["LOGO_SVG_SRC"]["VALUE"];
                }
                if( !empty($props["LOGO_SVG_WHITE_SRC"]["VALUE"]) ) {
                	$props["SVG_WHITE"] = [];
                	$props["SVG_WHITE"]["src"] = $props["LOGO_SVG_WHITE_SRC"]["VALUE"];
                }
				
				if (!empty($props["ERROR_MESSAGE"]["VALUE"])) {
					$errorMessageArray = array();
					foreach ($props["ERROR_MESSAGE"]["VALUE"] as $key => $value) {
						$errorMessageArray[$props["ERROR_MESSAGE"]["DESCRIPTION"][$key]] = $value["TEXT"];
					}
					$props["ERROR_MESSAGE"] = $errorMessageArray;
				}
				
				$arResult = array(
                    "PROPERTIES" => $props,
                    "FIELDS" => $fields
                );
			}
			return $arResult;
		} else {
			return false;
		}
	}

	public static function getClub($club = null, $soon=false, $filter=null) {
		Loader::includeModule('iblock');

        $arFilter = array("IBLOCK_CODE" => "clubs", "ACTIVE" => "Y");

        if ($soon!==null){
            $arFilter["PROPERTY_SOON"]=$soon;
		}
        if (!empty($filter)){
            $arFilter=array_merge($filter, $arFilter);
        }

        if ($club !== null) {
            $arFilter["PROPERTY_NUMBER"] = $club;
        }
        $dbElements = CIBlockElement::GetList(array("PROPERTY_NUMBER" => "ASC"), $arFilter, false, array(), array());

        if ($res = $dbElements->fetch()) {
            return $res;
        }
        return false;
	}

	public static function getClubById($club = '08') {
		Loader::includeModule('iblock');
		
		$arFilter = array("IBLOCK_CODE" => "clubs", "PROPERTY_SOON" => false, "ACTIVE" => "Y");

		if ($club !== null) {			
			$arFilter["ID"] = $club;
		}	
		
		$dbElements = CIBlockElement::GetList(array("PROPERTY_NUMBER" => "ASC"), $arFilter, false, array(), array());				
		
		if ($res = $dbElements->fetch()){
 			return $res;
 		}
 	}

	public static function getClubsForm($club) {
		Loader::includeModule('iblock');
		$arFilterAbonements = array("IBLOCK_CODE" => "subscription", "ACTIVE" => "Y");
		$dbAbonements = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilterAbonements, false, false, array("ID", "NAME", "PROPERTY_PRICE"));
		$arClubs = [];
		while ($resAbonement = $dbAbonements->fetch()) {	
			$arClubs[$resAbonement['PROPERTY_PRICE_VALUE']['LIST']] = $resAbonement['PROPERTY_PRICE_VALUE']['LIST'];
		}

		$arResult = array();
		$arFilter = array(
			"IBLOCK_CODE" => "clubs", 
			"PROPERTY_SOON" => false, 
			"ACTIVE" => "Y",
			"ID" => array_values($arClubs),
			
		);
		$dbElements = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilter, false, false, array("ID", "NAME", "PROPERTY_NUMBER", "PROPERTY_HIDE_LINK"));
		while ($res = $dbElements->fetch()) {
			$clubNumder = $res["PROPERTY_NUMBER_VALUE"];
			
			if( !empty($res['PROPERTY_HIDE_LINK_VALUE']) && $clubNumder != $club ) {
				continue;
			}
			
			$arResult[] = array(
				"ID" => $res["ID"],
				"MESSAGE" => $res["NAME"],
				"SELECTED" => $clubNumder == $club ? "selected" : "",
				"NUMBER" => $clubNumder
			);
		}

		return $arResult;
	}

	/**
	 * Получаю координаты карты и свойства из всех клубов
	 *
	 * @param string $club
	 * @return array
	 */
	public static function getClubsCoordiantes($club = null) {
		Loader::includeModule('iblock');
		
		$arFilter = array("IBLOCK_CODE" => "clubs", "PROPERTY_SOON" => false, "ACTIVE" => "Y");
		$arSelect = array("ID", "IBLOCK_ID", "NAME", "PROPERTY_CORD_YANDEX", "PROPERTY_ADRESS", "PROPERTY_EMAIL", "PROPERTY_PHONE", "PROPERTY_PATH_TO");

		if ($club !== null) {			
			$arFilter["PROPERTY_NUMBER"] = $club;
		}	
		
		$dbElements = CIBlockElement::GetList(array("PROPERTY_NUMBER" => "ASC"), $arFilter, false, array(), $arSelect);
		
		while($res = $dbElements->fetch())
		{
			$arr[] = $res;
		}
		
		return $arr;
	}

	public static function getUrlAbonement() {
		Loader::includeModule('iblock');

		$url = "";
		$arFilter = array("IBLOCK_CODE" => "subscription", "!PROPERTY_PRICE" => false, "ACTIVE" => "Y");
		$dbElements = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilter, false, false, array("ID", "NAME", "DETAIL_PAGE_URL"));

		if ($res = $dbElements->GetNext()) {
			$url = $res["DETAIL_PAGE_URL"];
		}

		return $url;
	}

	public static function setSeoDiv($page, $APPLICATION){
		$arInfoProps = Utils::getInfo()['PROPERTIES'];
		$arrayURLs = ["/", "/stock/", "/faq/", "/schedule/"];
		$arSEOData = [];
		$ogImage = CFile::GetPath($arInfoProps['OG_IMG']['VALUE']);
		
		if(in_array($page, $arrayURLs)){
			$metaTitle = $APPLICATION->GetTitle();
			$metaDescription = $APPLICATION->GetProperty("description");
			$metaKeywords = $APPLICATION->GetProperty("keywords");
			if ($metaTitle) {
				$arSEOData['META_TITLE'] = $metaTitle;
			}
			if ($metaDescription) {
				$arSEOData['META_DESCRIPTION'] = $metaDescription;
			}
			if ($metaKeywords) {
				$arSEOData['META_KEYWORDS'] = $metaKeywords;
			}
			if($ogImage){
				$arSEOData['OG_IMG'] = $ogImage;
			}
		}
		
		return $arSEOData;
	}

	/**
	 * Форматирование цены
	 */
	public static function getFormatPrice($price) {
		return  number_format($price, 0, '', ' ');
	}

	public static function getIblockId($iblockCode, $iblockType = '') {
		if(CModule::IncludeModule("iblock")) {

			if (!$iblockCode) {
				return false;
			}

			$filter = [];
			if ($iblockType) {
				$filter = ["ACTIVE" => "Y", "CODE" => $iblockCode, "TYPE" => $iblockType];
			} else {
				$filter = ["ACTIVE" => "Y", "CODE" => $iblockCode];
			}

			$res = CIBlock::GetList([], $filter);

			if ($iblock = $res->fetch()) {
				return $iblock['ID'];
			} else {
				return false;
			}
		}
	}

	
	/**
	 * get Club Id By Code
	 *
	 * @param  string $clubCode
	 * @return void
	 */
	public static function getClubByCode($clubCode) {

		if (!$clubCode) {
			return false;
		}

		Loader::includeModule('iblock');
		
		$arFilter = array("IBLOCK_CODE" => "clubs", "PROPERTY_SOON" => false, "ACTIVE" => "Y", "CODE" => $clubCode);

		$dbElements = CIBlockElement::GetList([], $arFilter, false, array(), array('ID', 'IBLOCK_CODE'));

		if ($club = $dbElements->fetch()) {
			return $club;
		} 

		return false;
	}

	/**
	 * Определение типа ссылки
	 * 
	 * @param string $link
	 * 
	 * @return void
	 */
	public static function checkLink($link)
	{
		$find = 'http';
		$pos = strpos($link, $find);
		
		if ($pos === false) {
			return true;
		}

		return false;
	}


	/*
	ФОрматирование номера телефона. Будет дополняться по мере необходиомсти
	*/
	public static function phone_format($phone)
    {
        $format = array(
            '7' => '###-##-##',
            '10' => '+7 (###) ### ## ##',
            '11' => '+# (###) ### ## ##'
        );
        $mask = '#';

        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (is_array($format)) {
            if (array_key_exists(strlen($phone), $format)) {
                $format = $format[strlen($phone)];
            } else {
                return false;
            }
        }

        $pattern = '/' . str_repeat('([0-9])?', substr_count($format, $mask)) . '(.*)/';

        $format = preg_replace_callback(
            str_replace('#', $mask, '/([#])/'),
            function () use (&$counter) {
                return '${' . (++$counter) . '}';
            },
            $format
        );

        return ($phone) ? trim(preg_replace($pattern, $format, $phone, 1)) : false;
    }

	// МЕТОДЫ ДЛЯ ИЗВЛЕЧЕНИЯ ID о символному коду
    public static function GetIBlockIDBySID($SID, $type="ID"){
        if (is_numeric($SID)){
            $filter=Array("ID"=>$SID);
        }
        else{
            $filter=Array("CODE"=>$SID);
        }
        $DBRes=CIBlock::GetList(Array("SORT"=>"ASC"), $filter);
        return $DBRes->Fetch()[$type];
    }
    public static function GetIBlockSectionIDBySID($SID, $type="ID"){
        if (is_numeric($SID)){
            $filter=Array("ID"=>$SID);
        }
        else{
            $filter=Array("CODE"=>$SID);
        }
        $DBRes=CIBlockSection::GetList(Array("SORT"=>"ASC"), $filter);
        return $DBRes->Fetch()[$type];
    }
    public static function GetIBlockElementIDBySID($SID, $type="ID"){
        if (is_numeric($SID)){
            $filter=Array("ID"=>$SID);
        }
        else{
            $filter=Array("CODE"=>$SID);
        }
        $DBRes=CIBlockElement::GetList(Array("SORT"=>"ASC"), $filter);
        return $DBRes->Fetch()[$type];
    }
    public static function GetFormIDBySID($SID, $type="ID"){
        Loader::IncludeModule("form");
        $rsForm = CForm::GetBySID($SID);
        $arForm = $rsForm->Fetch();
        return $arForm[$type];
    }
    public static function GetUGroupIDBySID($SID){
        $DBRes=CGroup::GetList(($by="c_sort"), ($order="desc"), Array("STRING_ID"=>$SID));
        return $DBRes->Fetch()['ID'];
    }


	// ИЗВЛЕЧЕНИЕ API URL
	public static function getApiURL(){
        Loader::includeModule('iblock');
        $db_props = CIBlockElement::GetProperty(SETTINGS_IBLOCK_ID, SETTINGS_ELEMENT_ID, array("sort" => "asc"), Array("CODE"=>"API_URL"));
        if($ar_props = $db_props->Fetch())
            $API_URL = $ar_props["VALUE"];
        else
            $API_URL = false;

        return $API_URL;
    }

    // ИЗВЛЕЧЕНИЕ API TOKEN
    public static function getApiSpiritfitToken(){
        Loader::includeModule('iblock');
        $db_props = CIBlockElement::GetProperty(SETTINGS_IBLOCK_ID, SETTINGS_ELEMENT_ID, array("sort" => "asc"), Array("CODE"=>"API_SPIRITFIT_TOKEN"));
        if($ar_props = $db_props->Fetch())
            $TOKEN = $ar_props["VALUE"];
        else
            $TOKEN = false;

        return $TOKEN;
    }

	//IMG RESIZE
	public static function resize_image($file, $percent) {
		list($width, $height) = getimagesize($file);
		$newheight=$height*$percent;
		$newwidth=$width*$percent;
		$src = imagecreatefromjpeg($file);
		$dst = imagecreatetruecolor($newwidth, $newheight);
		imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

		return $dst;
	}

}?>
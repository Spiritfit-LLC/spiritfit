<? 
class Clubs
{
    public static function getList()
    {
        $arResult = [];
        
        $cache = Bitrix\Main\Data\Cache::createInstance();
        $taggedCache = Bitrix\Main\Application::getInstance()->getTaggedCache();

        $cacheKey = 'clubs';
        $cacheDir = '/clubs';

        if ($cache->initCache(86400, $cacheKey, $cacheDir)) {
            $arResult = $cache->getVars();
        } elseif ($cache->startDataCache()) {

            $taggedCache->startTagCache($cacheDir);
            $taggedCache->registerTag($cacheKey);		
            
            $dbElement = CIBlockElement::GetList(['SORT' => 'DESC'], ['IBLOCK_ID' => IBLOCK_CLUBS_ID, 'ACTIVE' => 'Y', '!PROPERTY_HIDE_LINK_VALUE' => 'Да'], false, false, ['NAME', 'DETAIL_PAGE_URL', 'IBLOCK_ID', 'CODE', 'ID', 'PROPERTY_SHORT_PREVIEW_TEXT', 'PROPERTY_ADRESS', 'PROPERTY_PHONE', 'PROPERTY_EMAIL', 'PROPERTY_CORD_YANDEX', 'PROPERTY_WORK', 'PROPERTY_SCHEDULE', 'CODE', 'PROPERTY_NUMBER', 'PROPERTY_NOT_OPEN_YET', 'PROPERTY_SOON', 'PROPERTY_HIDE_LINK']);
            while ($arElement = $dbElement->Fetch()) {
                $arResult[] = $arElement;
            }
			
            if (empty($arResult)) {
                $taggedCache->abortTagCache();
                $cache->abortDataCache();
            } else {
                $taggedCache->endTagCache();
                $cache->endDataCache($arResult);
            }
        }

        return $arResult;
    }

    public static function clubsJson($clubs, $linkAnchor = "")
    {
        $arResult = [];
        $arFilter = array("IBLOCK_CODE" => "subscription", "ACTIVE" => "Y", "PROPERTY_CODE_ABONEMENT"=>"month2");
        $dbElements = CIBlockElement::GetList(array("SORT"=>"ASC"), $arFilter);

        if ($res = $dbElements->GetNextElement()) {
            $subsFields = $res->GetFields();
            $properties = $res->GetProperties();
            $subFields['PROPERTIES'] = $properties;
//            var_dump(array_column($subFields["PROPERTIES"]["PRICE"]["VALUE"], "LIST"));

        }
        
        foreach ($clubs as $itemClub) {
//            var_dump($itemClub);
            if(strpos($itemClub['PROPERTY_CORD_YANDEX_VALUE'], ',')){
                $itemClub['PROPERTY_CORD_YANDEX_VALUE'] = explode(',', $itemClub['PROPERTY_CORD_YANDEX_VALUE']);
            }else{
                $itemClub['PROPERTY_CORD_YANDEX_VALUE'] = [$itemClub['PROPERTY_CORD_YANDEX_VALUE']]; 
            }

            if(empty($itemClub['PROPERTY_WORK_VALUE'])){
                $itemClub['PROPERTY_WORK_VALUE'] = [];
            }else{
                if(strpos($itemClub['PROPERTY_WORK_VALUE'], ',')){
                    $itemClub['PROPERTY_WORK_VALUE'] = explode(',', $itemClub['PROPERTY_WORK_VALUE']);
                }else{
                    $itemClub['PROPERTY_WORK_VALUE'] = [$itemClub['PROPERTY_WORK_VALUE']]; 
                }
            }


            $indexes=array_keys(array_column($subFields["PROPERTIES"]["PRICE"]["VALUE"], "LIST"), $itemClub["ID"]);
            $priceArr=[];
            foreach($indexes as $i){
                $priceArr[]=$subFields["PROPERTIES"]["PRICE"]["VALUE"][$i]["PRICE"];
            }
            $minPrice=(int)min($priceArr);
//            var_dump($minPrice);

            $address = HTMLToTxt($itemClub['PROPERTY_ADRESS_VALUE']['TEXT']);

            if(strpos($address, '\'')) $address = str_replace('\'', '&#39;', $address);
            if(strpos($itemClub['NAME'], '\'')) $itemClub['NAME'] = str_replace('\'', '&#39;', $itemClub['NAME']);

            $arResult[] = [
                'id' => $itemClub['ID'],
                'name' => $itemClub['NAME'],
                'title' => $itemClub['NAME'],
                'address' => $address,
                'description' => trim($itemClub['PROPERTY_SHORT_PREVIEW_TEXT_VALUE']['TEXT']),
                'phone' => $itemClub['PROPERTY_PHONE_VALUE'],
                'email' => $itemClub['PROPERTY_EMAIL_VALUE'],
                'coords' => $itemClub['PROPERTY_CORD_YANDEX_VALUE'],
                'workHours' => $itemClub['PROPERTY_WORK_VALUE'],
                'page' => (!empty($itemClub['PROPERTY_HIDE_LINK_VALUE'])) ? '' : '/clubs/'.$itemClub['CODE'].'/'.$linkAnchor,
                'club_not_open' => ($itemClub['PROPERTY_SOON_VALUE']  == 'Y' ? 'Y' : 'N'),
                'club_soon_open' => ($itemClub['PROPERTY_NOT_OPEN_YET_VALUE'] == 'Да' ? 'Y' : 'N'),
                'min_price' => $minPrice
            ];
        }
        
        return CUtil::PhpToJSObject($arResult, false, true);
    }

    public static function clubsJsonShedule($clubs)
    {
        $arResult = [];
        
        foreach ($clubs as $clubNumber => $club) {
            $arShedule = [];
            $i = 0;
            foreach ($club as $day => $shedule) {
                $arShedule[$i]['day'] = $day;
                foreach ($shedule as $itemShedule) {
                    $arShedule[$i]['info'][] = [
                        "name" => $itemShedule['NAME'],
                        "duration" => "",
                        "time" => $itemShedule['TIME'],
                        "person" => $itemShedule['COACH'],
                        "desc" => "",
                    ];
                }
                $i++;
            }

            $arResult[] = [
                "club" => self::getClubID($clubNumber),
                "data" => $arShedule,
            ];
        }

        return CUtil::PhpToJSObject($arResult);
    }
    public static function getClubID($clubNumber)
    {
        $clubs = self::getList();
        foreach ($clubs as $club) {
            if($clubNumber == $club['PROPERTY_NUMBER_VALUE']){
                return $club['ID'];
            }
        }
    }
    public static function getClubSecDesc()
    {

        $dbSection = \CIBlockSection::GetList([], ['IBLOCK_ID' => IBLOCK_CLUBS_ID], false, ['DESCRIPTION']);
        while($arSection = $dbSection->Fetch()){
            $arResult = $arSection;
        }

        return $arResult;
    }
}
?>
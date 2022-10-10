<?
class Abonement
{
    public static function getList()
    {
        CModule::IncludeModule('iblock');
        $arResult = [];
        $arResult['ITEMS'] = [];

        $cache = Bitrix\Main\Data\Cache::createInstance();
        $taggedCache = Bitrix\Main\Application::getInstance()->getTaggedCache();

        $cacheKey = 'abonement';
        $cacheDir = '/abonement';

        if ($cache->initCache(86400, $cacheKey, $cacheDir)) {
            $arResult = $cache->getVars();
        } elseif ($cache->startDataCache()) {

            $taggedCache->startTagCache($cacheDir);
            $taggedCache->registerTag($cacheKey);

            /* ! Изменено для уменьшения используемой памяти в запросе к БД */
            /*$dbElement = CIBlockElement::GetList([], ['IBLOCK_ID' => ABONEMENTS_IBLOCK_ID, 'ACTIVE' => 'Y'], false, false, ['NAME', 'ID', 'PROPERTY_PRICE', 'PROPERTY_BASE_PRICE', 'PROPERTY_PRICE_SIGN_DETAIL', 'PROPERTY_DESCRIPTION_SALE']);
            while ($arElement = $dbElement->Fetch()) {
                $arResult['ITEMS'][$arElement['ID']]['PRICES'][$arElement['PROPERTY_PRICE_VALUE']['LIST']."_".$arElement['PROPERTY_PRICE_VALUE']['NUMBER']] = $arElement['PROPERTY_PRICE_VALUE'];
                $arResult['ITEMS'][$arElement['ID']]['BASE_PRICE'][$arElement['PROPERTY_BASE_PRICE_VALUE']['LIST']."_".$arElement['PROPERTY_BASE_PRICE_VALUE']['NUMBER']] = $arElement['PROPERTY_BASE_PRICE_VALUE'];
                $arResult['ITEMS'][$arElement['ID']]['PRICE_SIGN_DETAIL'][$arElement['PROPERTY_PRICE_SIGN_DETAIL_VALUE']['LIST']."_".$arElement['PROPERTY_PRICE_SIGN_DETAIL_VALUE']['NUMBER']] = $arElement['PROPERTY_PRICE_SIGN_DETAIL_VALUE'];
                if(!in_array($arElement['PROPERTY_PRICE_VALUE']['LIST'], $arResult['ITEMS'][$arElement['ID']]['LIST'])){
                    $arResult['ITEMS'][$arElement['ID']]['LIST'][] = $arElement['PROPERTY_PRICE_VALUE']['LIST'];
                }
                $arResult['ITEMS'][$arElement['ID']]['DESCRIPTION_SALE'] = $arElement['PROPERTY_DESCRIPTION_SALE_VALUE'];
            }*/

            $dbElement = CIBlockElement::GetList([], ['IBLOCK_ID' => ABONEMENTS_IBLOCK_ID, 'ACTIVE' => 'Y'], false, false);
            while ($dbObj = $dbElement->GetNextElement()) {
                $arElement = $dbObj->GetFields();
                $arElement["PROPERTIES"] = $dbObj->GetProperties();

                foreach($arElement['PROPERTIES']['PRICE']['VALUE'] as $value) {
                    $arResult['ITEMS'][$arElement['ID']]['PRICES'][$value['LIST']."_".$value['NUMBER']] = $value;

                    if(!in_array($value['LIST'], $arResult['ITEMS'][$arElement['ID']]['LIST'])){
                        $arResult['ITEMS'][$arElement['ID']]['LIST'][] = $value['LIST'];
                    }
                }

                foreach($arElement['PROPERTIES']['BASE_PRICE']['VALUE'] as $value) {
                    $arResult['ITEMS'][$arElement['ID']]['BASE_PRICE'][$value['LIST']."_".$value['NUMBER']] = $value;
                }

                foreach($arElement['PROPERTIES']['PRICE_SIGN_DETAIL']['VALUE'] as $value) {
                    $arResult['ITEMS'][$arElement['ID']]['PRICE_SIGN_DETAIL'][$value['LIST']."_".$value['NUMBER']] = $value;
                }

                $arResult['ITEMS'][$arElement['ID']]['DESCRIPTION_SALE'] = $arElement['PROPERTIES']['DESCRIPTION_SALE']['VALUE'];

                unset($arElement);
            }
            /* ! Изменено для уменьшения используемой памяти в запросе к БД */

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

    public static function getItem($abonementID, $clubID)
    {
        $arAbonement = self::getList();
        $arResult = [];

        foreach ($arAbonement['ITEMS'] as $id => $abonement) {
            if($abonementID == $id){
                $arResult['DESCRIPTION_SALE'] = $abonement['DESCRIPTION_SALE'];
                foreach ($abonement['BASE_PRICE'] as $itemBasePrice) {
                    if($itemBasePrice['LIST'] == $clubID){
                        $arResult["BASE_PRICE"] = $itemBasePrice;
                    }
                }

                foreach ($abonement["PRICES"] as $key => $arPrice) {

                    if ($clubID == $arPrice['LIST'] && $arPrice["PRICE"] != $arResult["BASE_PRICE"]["PRICE"] && $arPrice["NUMBER"] == $arResult["BASE_PRICE"]["NUMBER"]) {

                        $arResult["SALE"] = $arPrice["PRICE"];

                    }

                    $props = [];
                    $arFilter = ['IBLOCK_CODE' => 'price_sign'];
                    $dbPrice = CIBlockElement::GetList([], $arFilter, false, false, ['NAME', 'PROPERTY_MONTH']);

                    while ($arPrice = $dbPrice->fetch()) {
                        $props[$arPrice['NAME']] = $arPrice['PROPERTY_MONTH_VALUE'];
                    }

                    $priceSign = [];

                    foreach ($abonement["PRICE_SIGN_DETAIL"] as $arItem) {
                        if ($arItem["LIST"] == $clubID) {
                            $priceSign[] = $arItem;
                        }
                    }

                    if ($priceSign) {
                        $sign = array_search($arPrice["NUMBER"], array_column($priceSign, "NUMBER"));
                    } else {
                        $sign = false;
                    }

                    if ($sign !== false) {
                        $arResult["PRICES"][$key]["SIGN"] = $priceSign[$sign]["PRICE"];
                    }

                    if ($props[$arPrice["NUMBER"]] && $sign === false) {
                        $arResult["PRICES"][$key]["SIGN"] = $props[$arPrice["NUMBER"]];
                    }
                }
            }
        }

        array_multisort(array_column($arResult["PRICES"], "NUMBER"), SORT_ASC, $arResult["PRICES"]);

        return $arResult;
    }
}
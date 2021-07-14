<?
foreach ($arResult['ITEMS'] as $key => $item) {
    $arResult['ITEMS'][$key]['PROPERTIES']['BIG_IMG']['SRC'] = CFile::GetPath($item['PROPERTIES']['BIG_IMG']['VALUE']);
    $arResult['ITEMS'][$key]['PROPERTIES']['MIDDLE_IMG']['SRC'] = CFile::GetPath($item['PROPERTIES']['MIDDLE_IMG']['VALUE']);
    $arResult['ITEMS'][$key]['PROPERTIES']['SMALL_IMG']['SRC'] = CFile::GetPath($item['PROPERTIES']['SMALL_IMG']['VALUE']);
}
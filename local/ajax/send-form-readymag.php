<? 
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

// $_REQUEST[]['Выбор клуба'] = 'Метро Щукинская';
// $_REQUEST[]['Имя'] = 'test4';
// $_REQUEST[]['Телефон'] = '89201234455';
// $_REQUEST[]['Email'] = 'm.kraev@webprofy.ru';

if(!empty($_REQUEST)){
    $arResult = [
        'action' => 'contactReadymag', 
        'params' => ['type' => 9],
    ];

    foreach ($_REQUEST as $itemParam) {
        foreach ($itemParam as $paramName => $paramVal) {
            switch ($paramName) {
                case 'Выбор клуба':
                    CModule::IncludeModule("iblock");
                    $dbElements = CIBlockElement::GetList([], ['IBLOCK_CODE' => 'clubs'], false, [], ['NAME', 'PROPERTY_NUMBER']);
                    while ($arElements = $dbElements->Fetch()) {
                        $arElements['NAME'] = strip_tags($arElements['NAME']);
                        if($arElements['NAME'] == $paramVal){
                            $arResult['params']['club'] = $arElements['PROPERTY_NUMBER_VALUE'];
                        }
                    }
                    break;
                case 'Имя':
                    $arResult['params']['name'] = $paramVal;
                    break;
                case 'Телефон (без первой цифры)':
                    $arResult['params']['phone'] = $paramVal;
                    break;
                case 'Email':
                    $arResult['params']['email'] = $paramVal;
                    break;
            }
        }
    }
}

$api = new Api($arResult);

?>
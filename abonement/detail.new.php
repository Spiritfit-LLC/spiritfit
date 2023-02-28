<?php
define('BREADCRUMB_H1_ABSOLUTE', true);
define('HIDE_SLIDER', true);
define('H1_HIDE', true);

//В БУДУЩЕМ НУЖНО ВЫЗЫВАТЬ СТРАНИЦУ ПО CLUB_ID И ПЕРЕДАВАТЬ ПАРАМЕТР В ВИДЕ ?club=
//parse_str($parts['query'], $query);
//
//    //GET параметры
//    $CLUB_ID=$query['club']

$is404=false;

//РАЗБИРАЕМ URL
$url = strtok($_SERVER['REQUEST_URI'], '?');
$urlArr = explode('/', $url);

////Выдаем 404 если в ссылке что то лишнее
//if (count($urlArr)>4){
//    $is404=true;
//}

//ELEMENT CODE
$elementCode = !empty($urlArr[2]) ? $urlArr[2] : false;

//CLUB ID
$club_number=!empty($urlArr[3]) ? $urlArr[3] : false;

//Если страница уже с клубом не индексируем
if (!empty($club_number)){
    $GLOBALS["NO_INDEX"] = true;
}

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
use Bitrix\Iblock\InheritedProperty;
use \Bitrix\Main\Page\Asset;

Asset::getInstance()->addString('<script src="https://widget.cloudpayments.ru/bundles/cloudpayments.js"></script>');

CModule::IncludeModule("iblock");

$element = [];
$club = [];

if($elementCode) {
    $clubs = [];
    $clubsRes = CIBlockElement::GetList(array(), array('IBLOCK_ID' => Utils::GetIBlockIDBySID('clubs'), 'ACTIVE' => 'Y'), false, false, array('ID', 'NAME', 'CODE'));
    while($arRes = $clubsRes->GetNext()) {
        $clubs[$arRes['ID']] = $arRes['NAME'];
    }

    $res = CIBlockElement::GetList(array(), array('IBLOCK_ID' => Utils::GetIBlockIDBySID('subscription'), 'CODE' => $elementCode), false, false);
    if($ob = $res->GetNextElement()) {
        $element = $ob->GetFields();
        $element['PROPERTIES'] = $ob->GetProperties();

        $ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues( 9, $element['ID'] );
        $element['META'] = $ipropValues->getValues();
    }
    $element['IMAGES'] = [];
    if( $element['PROPERTIES']['PHOTO_GALLERY']['VALUE'] ) {
        foreach( $element['PROPERTIES']['PHOTO_GALLERY']['VALUE'] as $id ) {
            $element['IMAGES'][] = CFile::GetPath($id);
        }
    } else if( !empty($element['PREVIEW_PICTURE']) ) {
        $element['IMAGES'][] = CFile::GetPath($element['PREVIEW_PICTURE']);
    }

    if( !empty($club_number) ) {
        $club = Utils::getClub($club_number);
    }
    $element['PRICES'] = [];
    $element['MIN_PRICE'] = 0;
    $element['MAX_PRICE'] = 0;
    foreach($element['PROPERTIES']['BASE_PRICE']['VALUE'] as $key => $arPrice) {
        $price = $arPrice['PRICE'];
        foreach( $element['PROPERTIES']['PRICE']['VALUE'] as $item ) {
            if( $item['LIST'] == $arPrice['LIST'] && $price != $item['PRICE'] && $arPrice["NUMBER"] == $item['NUMBER'] ) {
                $price = $item['PRICE'];
                break;
            }
        }
        if( $element['MIN_PRICE'] == 0 || $price < $element['MIN_PRICE']  ) {
            $element['MIN_PRICE'] = $price;
        }
        if( $element['MAX_PRICE'] == 0 || $price > $element['MAX_PRICE']  ) {
            $element['MAX_PRICE'] = $price;
        }
        $element['PRICES'][] = [ 'NAME' => (isset($clubs[$arPrice['LIST']])) ? $clubs[$arPrice['LIST']] : '', 'PRICE' => $price, 'CLUB_ID' => $arPrice['LIST'], 'IS_SELECTED' => (!empty($club) && $club['ID'] == $arPrice['LIST']) ? true : false ];
    }
    if( !empty($clubNumber) && empty($club["ID"]) ) {
        $is404 = true;
    }
    if( !$is404 && !empty($clubNumber) ) {
        $is404 = true;
        foreach($element["PRICES"] as $price) {
            if( $price["IS_SELECTED"] ) {
                $is404 = false;
                break;
            }
        }
    }
}
else{
    $is404 = true;
}




if($is404){
    global $APPLICATION;
    $APPLICATION->RestartBuffer();
    require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/header.php';
    require $_SERVER['DOCUMENT_ROOT'].'/404.php';
    require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/footer.php';
    exit;
}

?>
<?php
global $APPLICATION;

if(strpos($APPLICATION->GetCurPage(false), "probnaya-trenirovka") ) {
    $formType = 3;
    $formTemplate = "trial";
    $WEB_FORM_ID="TRIAL_TRAINING_NEW";
    $form_action="getTrial";
}
else{
    $WEB_FORM_ID="PAYMENT_NEW";
    $formType = "order";
    $formTemplate = ".default";
    $form_action="getAbonement";
}

$APPLICATION->IncludeComponent(
    'custom:form.get.aboniment.new',
    $formTemplate,
    array(
        "AJAX_MODE" => "N",
        "ELEMENT_CODE"=>$elementCode,
        "CLUB_ID"=>$club["ID"],
        "WEB_FORM_ID"=>$WEB_FORM_ID,
        "FORM_TYPE"=>$formType,
        "FORM_ACTION"=>$form_action
    ),
    false
);
?>

<? if(!empty($element) && empty($_POST)) { ?>
        <?php $request_scheme=isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';?>
    <div itemscope itemtype="http://schema.org/Product" style="display: none;">
        <div itemprop="name"><?=strip_tags($element['~NAME'])?></div>
        <link itemprop="url" href="<?=$url?>">
        <? foreach($element['IMAGES'] as $image) { ?>
            <img itemprop="image" src="<?=$$request_scheme?>://<?=$_SERVER['SERVER_NAME']?><?=$image?>">
        <? } ?>
        <? if( !empty($element['IMAGES'][0]) ) { ?>
            <?
            //$APPLICATION->AddViewContent('inhead', 'https://'.$_SERVER['SERVER_NAME'].$element['IMAGES'][0]);
            ?>
        <? } ?>
        <meta itemprop="brand" content="Spirit.Fitness">
        <div itemprop="description"><?=$element['META']['ELEMENT_META_DESCRIPTION']?></div>
        <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
            <meta itemprop="price" content="<?=$element['MIN_PRICE']?>" id="offer_current">
            <meta itemprop="priceCurrency" content="RUB">
            <link itemprop="availability" href="http://schema.org/InStock">
            <link itemprop="url" href="<?=$request_scheme?>://<?=$_SERVER['SERVER_NAME']?><?=$url?>">
        </div>
        <? if(false) { ?>
            <div itemprop="offers" itemscope itemtype="https://schema.org/AggregateOffer">
                <span itemprop="lowPrice"><?=$element['MIN_PRICE']?></span>
                <span itemprop="highPrice"><?=$element['MAX_PRICE']?></span>
                <span itemprop="offerCount"><?=count($element['PRICES'])?></span>
                <?
                foreach($element['PRICES'] as $item) {
                    ?>
                    <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                        <meta itemprop="price" content="<?=$item['PRICE']?>">
                        <meta itemprop="priceCurrency" content="RUB">
                        <link itemprop="availability" href="http://schema.org/InStock">
                        <link itemprop="url" href="<?=$request_scheme?>://<?=$_SERVER['SERVER_NAME']?><?=$url?>">
                    </div>
                    <?
                }
                ?>
            </div>
        <? } ?>
    </div>
<? } ?>

<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>

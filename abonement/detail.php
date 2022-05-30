<?
define('BREADCRUMB_H1_ABSOLUTE', true);
define('HIDE_SLIDER', true);
define('H1_HIDE', true);

$url = strtok($_SERVER['REQUEST_URI'], '?');
$urlArr = explode('/', $url);
$clubNumber = false;
if( !empty($urlArr[3]) ) {
	
	$GLOBALS["NO_INDEX"] = true; 
	
	$clubNumber = htmlspecialchars($urlArr[3]);
	$parsedQuery = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
	
	unset($urlArr[3]);
	$_SERVER['REQUEST_URI'] = implode("/", $urlArr);
	if( !empty($parsedQuery) ) {
		$_SERVER['REQUEST_URI'] .= "?" . $parsedQuery;
	}
}

if (isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true') {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
} else {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
}


global $USER;

use Bitrix\Iblock\InheritedProperty;

CModule::IncludeModule("iblock");

if( !empty($clubNumber) ) {
	$_SESSION['CLUB_NUMBER'] = $clubNumber;
}

$elementCode = !empty($urlArr[2]) ? $urlArr[2] : false;
$element = [];
$club = [];
if( $elementCode ) {
	$clubs = [];
	$clubsRes = CIBlockElement::GetList(array(), array('IBLOCK_ID' => 6, 'ACTIVE' => 'Y'), false, false, array('ID', 'NAME', 'CODE'));
	while($arRes = $clubsRes->GetNext()) {
		$clubs[$arRes['ID']] = $arRes['NAME'];
	}
	
	$res = CIBlockElement::GetList(array(), array('IBLOCK_ID' => 9, 'CODE' => $elementCode), false, false);
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
	
	if( !empty($_SESSION['CLUB_NUMBER']) ) {
		$club = Utils::getClub($_SESSION['CLUB_NUMBER']); 
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
	
	$is404 = false;
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
	if( $is404 ) {
		global $APPLICATION;
		$APPLICATION->RestartBuffer();
		require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/header.php';
		require $_SERVER['DOCUMENT_ROOT'].'/404.php';
		require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/footer.php';
		exit;
	}
}
	if ($_REQUEST["ajax_menu"] == 'true' && isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true') {
	} else {
		?>
		<div id="js-pjax-container">
			<?
				$formId = 2;
				$formType = 1;
				$formTemplate = "";
				$actiontype = "request";
				if( strpos($APPLICATION->GetCurPage(false), "probnaya-trenirovka") ) {
					$formType = 3;
					$formId = 3;
					$formTemplate = "trial";
					$actiontype = "request2";
				}
				
				$APPLICATION->IncludeComponent(
					"custom:form.get.aboniment", 
					$formTemplate,
					array(
						"AJAX_MODE" => "N",
						"WEB_FORM_ID" => $formId,
						"ADD_ELEMENT_CHAIN" => "N",
						"CLUB_ID" => $club["ID"],
						"DEFAULT_CLUB_ID" => "",
						"ABONEMENT_IBLOCK_ID" => 9,
						"CLUBS_IBLOCK_ID" => 6,
						"FORM_TYPE" => $formType,
						"ACTION_TYPE" => $actiontype,
						"ELEMENT_CODE" => $elementCode,
						"FREE_MESSAGE" => "Бесплатный абонемент. Для верификации, мы спишем с карты и вернем 11 рублей. Чтобы убедиться, что Вы человек, а не робот."
					),
					false
				);
			?>
		</div>
		<? 
			}
		?>
<? if( !empty($element) && empty($_POST) ) { ?>
	<div itemscope itemtype="http://schema.org/Product" style="display: none;">
		<div itemprop="name"><?=strip_tags($element['~NAME'])?></div>
		<link itemprop="url" href="<?=$url?>">
		<? foreach($element['IMAGES'] as $image) { ?>
			<img itemprop="image" src="<?=$_SERVER['REQUEST_SCHEME']?>://<?=$_SERVER['SERVER_NAME']?><?=$image?>">
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
			<link itemprop="url" href="<?=$_SERVER['REQUEST_SCHEME']?>://<?=$_SERVER['SERVER_NAME']?><?=$url?>">
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
							<link itemprop="url" href="<?=$_SERVER['REQUEST_SCHEME']?>://<?=$_SERVER['SERVER_NAME']?><?=$url?>">
						</div>	
						<?
					}
				?>
			</div>
		<? } ?>	
	</div>
<? } ?>
<?
if (!isset($_SERVER['HTTP_X_PJAX'])) {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
}
?>

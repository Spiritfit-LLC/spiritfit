<?

define("SETTINGS_IBLOCK_ID", 1);
define("SETTINGS_ELEMENT_ID", 1);
define("MONTH_ELEMENT_ID", 36);
define("YEAR_ELEMENT_ID", 37);
define("CERTIFICATE_ELEMENT_ID", 38);
define("ABONEMENTS_IBLOCK_ID", 9);// абонементы
define("ABONEMENTS_BASE_PRICE_ID", 45);// id поля базовая цена
define("ABONEMENTS_PRICE_SIGN_ID", 36);// id поля базовая цена
define("ABONEMENTS_GOD_FITNESA_ID", 37);// id абонемента "Год фитнеса"
define("IBLOCK_COLORS_ID", 14);// ID инфоблока "Цвета"
define("IBLOCK_CLUBS_ID", 6);// ID инфоблока "Клубы"

define("POST_FORM_CORP_ACTION_URI", "/local/templates/spiritfit-corp/ajax/modal-trial.php");
define("POST_FORM_CAREER_ACTION_URI", "/local/templates/spiritfit-career/ajax/modal-trial.php");
define("MAIN_SITE_URL", "https://spiritfit.ru");
define("API_SPIRITFIT_TOKEN", "65a0e413b0224cb198096c7e4a297aa0");

$currentUrl = strtok($_SERVER["REQUEST_URI"], "?");
$enableNoIndexPages = [
	"/trenirovki/", "/blog/", "/gruppovye-trenirovki/",
	"/trenirovki/personal-training/", "/trenirovki/onlayn-trenirovki/", "/club-members/", "/trenirovki/partnery-i-privilegii/",
	"/about/", "/about/adv/", "/about/platform/"
];
if( in_array($currentUrl, $enableNoIndexPages) ) {
	$GLOBALS["NO_INDEX"] = true;
}

if (file_exists($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/Utils.php')) {
    require_once($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/Utils.php');
}
if (file_exists($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/CIPropertyPrice.php')) {
    require_once($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/CIPropertyPrice.php');
}
if (file_exists($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/api.php')) {
    require_once($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/api.php');
}
if (file_exists($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/PriceUpdate.php')) {
    require_once($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/PriceUpdate.php');
}
if (file_exists($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/ScheduleUpdate.php')) {
    require_once($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/ScheduleUpdate.php');
}
if (file_exists($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/ElementUpdate.php')) {
    require_once($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/ElementUpdate.php');
}
if (file_exists($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/Clubs.php')) {
    require_once($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/Clubs.php');
}
if (file_exists($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/Abonement.php')) {
    require_once($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/Abonement.php');
}
if (file_exists($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/EventHandlersClass.php')) {
    require_once($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/EventHandlersClass.php');
}

if (file_exists($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/PersonalUtils.php')) {
    require_once($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/PersonalUtils.php');
}
if (file_exists($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/FeedCreator.php')) {
    require_once($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/FeedCreator.php');
}
if (file_exists($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/TurboPagesYandex.php')) {
    require_once($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/TurboPagesYandex.php');
}
if (file_exists($_SERVER["DOCUMENT_ROOT"] . '/local/api/WebAnalytics.php')) {
    require_once($_SERVER["DOCUMENT_ROOT"] . '/local/api/WebAnalytics.php');
}
if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/classes/SpiritNetUtils.php")){
    require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/classes/SpiritNetUtils.php");
}


//Конверсия
$files = scandir($_SERVER["DOCUMENT_ROOT"].'/local/php_interface/classes/conversion');
foreach($files as $file) {
    if (($file !== '.') and ($file !== '..'))
        require_once $_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/classes/conversion/' . $file;
}
if (CModule::IncludeModule('conversion')){
    $day_context = Bitrix\Conversion\DayContext::getInstance(); // контекст текущего дня и текущего пользователя
    $day_context->addDayCounter('conversion_visit_day', 1);
}


AddEventHandler("main", "OnAfterUserAuthorize", Array("PersonalUtils", "UpdateFieldsAfterLogin"));


CModule::AddAutoloadClasses("", array(
    '\ImageConverter\Picture' => '/local/php_interface/classes/ImageConverter.php',
));

AddEventHandler("iblock", "OnIBlockPropertyBuildList", array("CIPropertyPrice", "GetUserTypeDescription"));
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", Array("ElementUpdate", "OnAfterIBlockElementUpdateHandler"));

/*global $USER;
if( $USER->IsAuthorized() && $USER->IsAdmin() ) {
	\Bitrix\Main\Page\Asset::getInstance()->addJs("/js/admin.js");
}*/
/*global $APPLICATION;
$dir = $APPLICATION->GetCurDir();
echo $dir; exit;*/

$pageUrl = strtok($_SERVER["REQUEST_URI"], '?');
if( $pageUrl === "/bitrix/admin/form_result_list.php" ) {
	\Bitrix\Main\Page\Asset::getInstance()->addJs("/js/admin.js");
}

function getBrowserInformation() : array {
	$resultArr = ["NAME" => "", "VERSION" => "", "VERSION_PARSED" => []];
	$arrBrowsers = ["Opera", "Edge", "Chrome", "Safari", "Firefox", "MSIE", "Trident"];
	$currentUserBrowserName = '';
	$currentUserBrowserVersion = '';
	foreach ($arrBrowsers as $browser) {
    	if (strpos($_SERVER['HTTP_USER_AGENT'], $browser) !== false) {
        	$currentUserBrowserName = $browser;
        	break;
    	}   
	}

	if( !empty($currentUserBrowserName) ) {

		$known = array('Version', $currentUserBrowserName);
		$pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';

		preg_match_all($pattern, $_SERVER['HTTP_USER_AGENT'], $matches);

		if( !empty($matches['version'][0]) ) {
			$currentUserBrowserVersion = $matches['version'][0];
		} else if( !empty($matches['version']) && !is_array($matches['version']) ) {
			$currentUserBrowserVersion = $matches['version'];
		}

		$resultArr["NAME"] = $currentUserBrowserName;
		if( !empty($currentUserBrowserVersion) ) {
			$resultArr["VERSION"] = $currentUserBrowserVersion;
			$resultArr["VERSION_PARSED"] = explode(".", $currentUserBrowserVersion);
		}
	}
	return $resultArr;
}

//массив ответов форм для данных об источнике трафика
$arTraficAnswer = array(
	1 => array(
		'src' => 'form_text_38',
		'mdm' => 'form_text_39',
		'cmp' => 'form_text_40',
		'cnt' => 'form_text_41',
		'trm' => 'form_text_42',
		'ClientId' => 'form_text_43',
		'yaClientID'=>'form_yaClientID1',
	),
	2 => array(
		'src' => 'form_text_44',
		'mdm' => 'form_text_45',
		'cmp' => 'form_text_46',
		'cnt' => 'form_text_47',
		'trm' => 'form_text_48',
		'ClientId' => 'form_text_49',
		'yaClientID'=>'form_yaClientID2',
	),
	3 => array(
		'src' => 'form_text_50',
		'mdm' => 'form_text_51',
		'cmp' => 'form_text_52',
		'cnt' => 'form_text_53',
		'trm' => 'form_text_54',
		'ClientId' => 'form_text_55',
		'yaClientID'=>'form_yaClientID3',
	),
	4 => array(
		'src' => 'form_text_56',
		'mdm' => 'form_text_57',
		'cmp' => 'form_text_58',
		'cnt' => 'form_text_59',
		'trm' => 'form_text_60',
		'ClientId' => 'form_text_61',
		'yaClientID'=>'form_yaClientID4',
	),
	5 => array(
		'src' => 'form_text_62',
		'mdm' => 'form_text_63',
		'cmp' => 'form_text_64',
		'cnt' => 'form_text_65',
		'trm' => 'form_text_66',
		'ClientId' => 'form_text_67',
		'yaClientID'=>'form_yaClientID5',
	),
	10 => array(
		'src' => 'form_text_224',
		'mdm' => 'form_text_225',
		'cmp' => 'form_text_226',
		'cnt' => 'form_text_227',
		'trm' => 'form_text_228',
		'ClientId' => 'form_text_229',
	),
    Utils::GetFormIDBySID('PAYMENT_NEW')=>array(
        'src' => 'form_text_230',
        'mdm' => 'form_text_231',
        'cmp' => 'form_text_232',
        'cnt' => 'form_text_233',
        'trm' => 'form_text_234',
    ),
);

$arAdditionAnswer = array(
	1 => array(
		'name' => 'form_text_1',
		'subscriptionId' => 'form_text_3',
		'email' => 'form_email_92',
	),
	2 => array(
		'name' => 'form_text_6',
		'surname' => 'form_text_7',
		'email' => 'form_email_9',
		'subscriptionId' => 'form_text_5',
	),
	3 => array(
		'name' => 'form_text_16',
		'subscriptionId' => 'form_text_15',
		'email' => 'form_email_91',
	),
	4 => array(
		'name' => 'form_text_23',
		'surname' => 'form_text_24',
		'email' => 'form_text_26',
	),
	5 => array(
		'name' => 'form_text_31',
		'email' => 'form_text_37',
		'subscriptionId' => 'form_text_30',
	),
	10 => array(
		'name' => 'form_text_215',
		'email' => 'form_text_217',
	),
);

function getClientParams($webFormId) {
	global $arTraficAnswer;
    if(isset($arTraficAnswer[$webFormId])) {
		$arIdAnswer = $arTraficAnswer[$webFormId];
		foreach($arIdAnswer as $anId) {
			echo '<input type="hidden" name="'.$anId.'" id="'.$anId.'" value="" />';
		}
?>
		<script style="display:none;">
			$(function () {
				setTimeout(function(){
	                if (window.sbjs.get.current !== undefined) {
	    				var current = window.sbjs.get.current;
	    				$('input[name=<?=$arIdAnswer["src"]?>]').val(current.src);
	    				$('input[name=<?=$arIdAnswer["mdm"]?>]').val(current.mdm);
	    				$('input[name=<?=$arIdAnswer["cmp"]?>]').val(current.cmp);
	    				$('input[name=<?=$arIdAnswer["cnt"]?>]').val(current.cnt);
	    				$('input[name=<?=$arIdAnswer["trm"]?>]').val(current.trm);

						//ym(48440750, 'getClientID', function(clientID) {
						//	$('input[name=<?//=$arIdAnswer["yaClientID"]?>//]').val(clientID)
						//});
	    				//
	    				//var ClientId = '';
	    				////if( typeof ga !== 'undefined' ) {
						//	var ga = getCookie('_ga');
						//	if( ga !== null ) {
						//		var i = ga.lastIndexOf('.');
	    				//		if(i > 0) {
	    				//			i = ga.lastIndexOf('.', i-1);
	    				//			if(i > 0) {
	    				//				ClientId = ga.substring(i+1);
	    				//			}
	    				//		}
						//	}
						////}
						//
	    				//$('input[name=<?//=$arIdAnswer["ClientId"]?>//]').val(ClientId);

						
	                }
	            }, 1000);
			});
		</script>
<?
    }
}

AddEventHandler('form', 'onBeforeResultAdd', 'my_onBeforeResultAdd');
function my_onBeforeResultAdd($WEB_FORM_ID, &$arFields, &$arrVALUES) {
    if($WEB_FORM_ID==2 && isset($arrVALUES['result_not_add'])) {
        global $APPLICATION;
        $APPLICATION->throwException("result_not_add"); 
        return false;
    }
}

function getThemeSelector() {
	$isChecked = '';
	$name = 'темная';
	if( isset($_COOKIE["theme_type"]) && intval($_COOKIE["theme_type"]) === 2 ) {
		$isChecked = 'checked';
		$name = 'светлая';
	}
	$resultString = '<div class="theme-selector-wrapper">Тема оформления: <div class="theme-selector"><input id="theme_type" type="checkbox" name="theme_type" value="2" ' . $isChecked . '><label for="theme_type">' . $name . '</label></div></div>';
	return $resultString;
}


//Новый стандарт для utm меток
function printGaFormInputs(){
    for ($i=0; $i<5; $i++){
        $inputsId[]='gainp_'.uniqid();
    }
    ?>
    <input type="hidden" name="ga_src" id="<?=$inputsId[0]?>" value="">
    <input type="hidden" name="ga_mdm" id="<?=$inputsId[1]?>" value="">
    <input type="hidden" name="ga_cmp" id="<?=$inputsId[2]?>" value="">
    <input type="hidden" name="ga_cnt" id="<?=$inputsId[3]?>" value="">
    <input type="hidden" name="ga_trm" id="<?=$inputsId[4]?>" value="">
    <script>
        $(document).ready(function(){
            setTimeout(function(){
                if (window.sbjs.get.current !== undefined) {
                    var current = window.sbjs.get.current;
                    $('#<?=$inputsId[0]?>').val(current.src);
                    $('#<?=$inputsId[1]?>').val(current.mdm);
                    $('#<?=$inputsId[2]?>').val(current.cmp);
                    $('#<?=$inputsId[3]?>').val(current.cnt);
                    $('#<?=$inputsId[4]?>').val(current.trm);
                }
            }, 1000);
        })
    </script>
<?}

function getGaFormInputs($request){
    $arClient["src"]=$request["ga_src"];
    $arClient["mdm"]=$request["ga_mdm"];
    $arClient["cmp"]=$request["ga_cmp"];
    $arClient["cnt"]=$request["ga_cnt"];
    $arClient["trm"]=$request["ga_trm"];

    return $arClient;
}

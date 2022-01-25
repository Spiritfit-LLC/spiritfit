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

//массив ответов форм для данных об источнике трафика
$arTraficAnswer = array(
	1 => array(
		'src' => 'form_text_38',
		'mdm' => 'form_text_39',
		'cmp' => 'form_text_40',
		'cnt' => 'form_text_41',
		'trm' => 'form_text_42',
		'ClientId' => 'form_text_43',
	),
	2 => array(
		'src' => 'form_text_44',
		'mdm' => 'form_text_45',
		'cmp' => 'form_text_46',
		'cnt' => 'form_text_47',
		'trm' => 'form_text_48',
		'ClientId' => 'form_text_49',
	),
	3 => array(
		'src' => 'form_text_50',
		'mdm' => 'form_text_51',
		'cmp' => 'form_text_52',
		'cnt' => 'form_text_53',
		'trm' => 'form_text_54',
		'ClientId' => 'form_text_55',
	),
	4 => array(
		'src' => 'form_text_56',
		'mdm' => 'form_text_57',
		'cmp' => 'form_text_58',
		'cnt' => 'form_text_59',
		'trm' => 'form_text_60',
		'ClientId' => 'form_text_61',
	),
	5 => array(
		'src' => 'form_text_62',
		'mdm' => 'form_text_63',
		'cmp' => 'form_text_64',
		'cnt' => 'form_text_65',
		'trm' => 'form_text_66',
		'ClientId' => 'form_text_67',
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
	    				
	    				var ClientId = '';
	    				if( typeof ga !== 'undefined' ) {
							var ga = getCookie('_ga');
	    					var i = ga.lastIndexOf('.');
	    					if(i > 0) {
	    						i = ga.lastIndexOf('.', i-1);
	    						if(i > 0) {
	    							ClientId = ga.substring(i+1);
	    						}
	    					}
						}
						
	    				$('input[name=<?=$arIdAnswer["ClientId"]?>]').val(ClientId);
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


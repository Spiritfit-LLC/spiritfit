<?php
define('BREADCRUMB_H1_ABSOLUTE', true);
define('HIDE_SLIDER', true);
define('H1_HIDE', true);
$GLOBALS["NO_INDEX"] = true;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "");
$APPLICATION->SetPageProperty("title", "");
?>
<?php
if (empty($_SESSION['INTERVIEW_ID'])){
	$url =$_SERVER['REQUEST_URI'];
	$parts = parse_url($url);

	if (empty($parts['query'])){
		global $APPLICATION;
		$APPLICATION->RestartBuffer();
		require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/header.php';
		require $_SERVER['DOCUMENT_ROOT'].'/404.php';
		require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/footer.php';
	}

	parse_str($parts['query'], $query);


	if (empty($query['ID'])){
		global $APPLICATION;
		$APPLICATION->RestartBuffer();
		require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/header.php';
		require $_SERVER['DOCUMENT_ROOT'].'/404.php';
		require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/footer.php';
	}


	$_SESSION['INTERVIEW_ID']=$query['ID'];
	LocalRedirect($parts['path']);
}
?>
<?php
use \Bitrix\Main\Page\Asset;

Asset::getInstance()->addString('<script src="https://widget.cloudpayments.ru/bundles/cloudpayments.js"></script>');
Asset::getInstance()->addString('<meta name="robots" content="noindex, follow" />');

$INTERVIEW_ID=$_SESSION['INTERVIEW_ID'];
unset($_SESSION['INTERVIEW_ID']);

$APPLICATION->IncludeComponent(
	"bitrix:voting.current",
	"interview",
	array(
		"CHANNEL_SID" => "TEST",
		"VOTE_ID" => $INTERVIEW_ID,
		"VOTE_ALL_RESULTS" => "Y",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "3600",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "Y",
		"COMPONENT_TEMPLATE" => "interview",
		"AJAX_OPTION_ADDITIONAL" => "",
	),
	false
);
?>

<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
?>
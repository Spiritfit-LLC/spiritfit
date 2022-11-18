<?php
define('HIDE_BREADCRUMB', true);
define('HIDE_SLIDER', true);
define('H1_HIDE', true);
define("PURPLE_GREY", true);
$GLOBALS["NO_INDEX"] = true;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

?>
<?if (empty($_SESSION['INTERVIEW_ID'])):?>
<?php
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


    if (empty($query['interviewid'])){
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/header.php';
        require $_SERVER['DOCUMENT_ROOT'].'/404.php';
        require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/footer.php';
    }


    $_SESSION['INTERVIEW_ID']=$query['interviewid'];
    $_SESSION["CLIENT_ID"]=$query['id1c'];
    LocalRedirect($parts['path']);
    ?>
<?endif; ?>
<?php
use \Bitrix\Main\Page\Asset;

Asset::getInstance()->addString('<script src="https://widget.cloudpayments.ru/bundles/cloudpayments.js"></script>');
Asset::getInstance()->addString('<meta name="robots" content="noindex, follow" />');

$INTERVIEW_ID=$_SESSION['INTERVIEW_ID'];
//if (!is_numeric($INTERVIEW_ID)){
//    $INTERVIEW_ID=Utils::GetIBlockSectionIDBySID($INTERVIEW_ID);
//}
$CLIENT_ID=$_SESSION["CLIENT_ID"];
unset($_SESSION['INTERVIEW_ID']);
unset($_SESSION['CLIENT_ID']);

$APPLICATION->IncludeComponent("custom:interview", ".default", array(
    "INTERVIEW_ID"=>$INTERVIEW_ID,
    "CLIENT_ID"=>$CLIENT_ID
), false);
?>

<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
?>
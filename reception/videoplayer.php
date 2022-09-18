<?

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

use \Bitrix\Main\Page\Asset;
use \Bitrix\Main\Loader;

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/screenfull.js/5.1.0/screenfull.min.js" integrity="sha512-SGPHIoS+NsP1NUL5RohNpDs44JlF36tXLN6H3Cw+EUyenEc5zPXWqfw9D+xmvR00QYUYewQIJQ6P5yH82Vw6Fg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <?
    Asset::getInstance()->addString('<meta name="robots" content="noindex, follow" />');
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . "/vendor/jquery/jquery.min.js");

    $APPLICATION->ShowHead();
    ?>
    <style>
        body{
            background:black;
            overflow: hidden;
        }
        #bgvideo{
            width: 100vw;
            height: 100vh;
        }
    </style>
</head>
<body>
<?
Loader::IncludeModule("iblock");
$IBLOCK_ID=Utils::GetIBlockIDBySID("test-videoplayer");
if (empty($_GET["ID"])){
    $rs = CIBlockElement::GetList (
        Array("RAND" => "ASC"),
        Array("IBLOCK_ID" => $IBLOCK_ID),
        false,
        Array ("nTopCount" => 1)
    );
}
else{
    if (is_numeric($_GET["ID"])){
        $ELEMENT_ID=$_GET["ID"];
    }
    else{
        $ELEMENT_ID=Utils::GetIBlockElementIDBySID($_GET["ID"]);
    }
    $arFilter = Array("IBLOCK_ID"=>$IBLOCK_ID, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "ID"=>$ELEMENT_ID);
    $rs = CIBlockElement::GetList(Array(), $arFilter);
}
if($ob = $rs->GetNextElement())
{
    $arProps = $ob->GetProperties();
    $VIDEO=$arProps["VIDEOFILE"]["VALUE"];
}
?>
<video autoplay loop muted class="bgvideo" id="bgvideo">
    <source src="<?=CFile::GetPath($VIDEO)?>" type="video/webm">
</video>

</body>
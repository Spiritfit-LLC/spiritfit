<?php
define('BREADCRUMB_H1_ABSOLUTE', true);
define('HIDE_SLIDER', true);
define('H1_HIDE', true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Расписание занятий");

use \Bitrix\Main\Page\Asset;
Asset::getInstance()->addString('<meta name="robots" content="noindex, follow" />');

if (empty($_GET["CLUB"])){
    $CLUB_NUM="18";
}
else{
    $CLUB_NUM=$_GET["CLUB"];
}
?>
<?php
$APPLICATION->IncludeComponent("custom:shedule.club",
    "reception",
    array(
        "IBLOCK_TYPE" => "content",
        "IBLOCK_CODE" => "clubs",
        "CLUB_NUMBER" => $CLUB_NUM,
    ),
    false);
?>

<script>
    $(document).ready(function(){
        $('header').remove();
        $('.b-screen').remove();
        $('.b-breadcrumbs').remove();
    })
</script>



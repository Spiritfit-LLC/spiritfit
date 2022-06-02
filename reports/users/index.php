<?php
define('BREADCRUMB_H1_ABSOLUTE', true);
define('HIDE_SLIDER', true);
define('H1_HIDE', true);
define('PERSONAL_PAGE', true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");


if (!$USER->IsAuthorized()){
    $is404=true;
}

$groups=CUser::GetUserGroup($USER->GetID());
$is404=true;
foreach($groups as $gr){
    if ($gr==Utils::GetUGroupIDBySID('MANAGEMENT')){
        $is404=false;
        break;
    }
}

if ($is404){
    global $APPLICATION;
    $APPLICATION->RestartBuffer();
    require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/header.php';
    require $_SERVER['DOCUMENT_ROOT'].'/404.php';
    require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/footer.php';
    exit;
}

$APPLICATION->SetTitle("Отчеты");

use \Bitrix\Main\Page\Asset;
Asset::getInstance()->addString('<meta name="robots" content="noindex, follow" />');
?>



<div class="content-center">
    <h1 class="b-page__title"><?=$APPLICATION->ShowTitle(false)?></h1>
    <?php
    $APPLICATION->IncludeComponent(
        "custom:users.report",
        "",
        array(),
        false
    );
    ?>
</div>
<?
define('BREADCRUMB_H1_ABSOLUTE', true);
define('HIDE_SLIDER', true);
define('H1_HIDE', true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");


use \Bitrix\Main\Page\Asset;
Asset::getInstance()->addString('<meta name="robots" content="noindex, follow" />');
?>


<div class="content-center personal-page-content">
    <?php
    $APPLICATION->IncludeComponent(
        "custom:personal.card",
        "",
        Array(
            "PROFILE_ID" => $_GET['ID'],
            "SHOW_ERRORS" => "Y"
        ),
        false
    );
    ?>
</div>

<style>
    body{
        background-color: #171717;
    }
    .personal-page-content {
        z-index: 2;
        position: relative;
        min-height: 802px;
    }
    .personal-page-content::after{
        content: '';
        width: 80%;
        border-bottom: 1px solid #ff7628;
        display: block;
        position: absolute;
        bottom: 0;
        left: 10%;
    }
    .personal-page-content .b-page__title{
        font-family: 'Gotham Pro';
        font-style: normal;
        font-weight: 900;
        font-size: 48px;
        line-height: 52px;
        /* identical to box height, or 108% */

        letter-spacing: 0.05em;

        color: #FFFFFF;
    }

    @media screen and (max-width: 660px) {
        .personal-page-content{
            min-height: auto;
        }
    }
    @media screen and (max-width: 1025px) {

    }
</style>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
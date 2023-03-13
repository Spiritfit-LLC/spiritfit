<?php
$GLOBALS["NO_INDEX"] = true;
?>
<?if ($_GET["v"]==2):?>
    <?php
    define('HIDE_SLIDER', true);
    define('ANCHOR_PERSONAL', true);
    define('HIDE_BREADCRUMB', true);
    define('H1_BIG', true);

    define("AUTH_PAGE", true);

    define('SITE_TEMPLATE_PATH', '/local/templates/spiritfit-v3/');
    define('SITE_TEMPLATE_ID', 'spiritfit-v3');

    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

    $APPLICATION->SetTitle("Личный кабинет");
    ?>



    <?$APPLICATION->IncludeComponent(
        "personal.custom:personal",
        ".default",
        Array(
            "AJAX_MODE" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            "SEF_FOLDER" => "/personal/",
            "SEF_MODE" => "Y",
            "SEF_URL_TEMPLATES" => Array(
                "loyalty" => "loyalty/",
                "me" => "",
                "services" => "services/",
                "settings" => "settings/"
            ),
            "SET_TITLE" => "Y",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "CACHE_FILTER" => "Y",
            "CACHE_GROUPS" => "Y",
        ),
        false
    );?>
    <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
<?else:?>
<?php
define('BREADCRUMB_H1_ABSOLUTE', true);
define('HIDE_SLIDER', true);
define('H1_HIDE', true);
define('PERSONAL_PAGE', true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");

?>


<div class="personal-page-background">
    <div class="personal-page-img">

    </div>
    <div class="personal-page-gradient">
        <div class="grad-left"></div>
        <div class="grad-bottom"></div>
    </div>
</div>

<div class="content-center personal-page-content">
    <h1 class="b-page__title is-hide-mobile"><?=$APPLICATION->ShowTitle(false)?></h1>
    <?php
    $APPLICATION->IncludeComponent(
        "custom:personal",
        "",
        Array(
            "AUTH_FORM_CODE" => "AUTH",
            "REGISTER_FORM_CODE" => "REGISTRATION",
            "PASSFORGOT_FORM_CODE"=>'PASSFORGOT',
            "ACTIVE_FORM"=>"lk_loyalty_program",
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
    .personal-page-background {
        width: 100%;
        height: 802px;
        position: absolute;
        top: 107px;
        z-index: 1;
    }
    .personal-page-img {
        background: url(/local/templates/spiritfit-v2/img/lk-background.jfif) no-repeat right top;
        width: 100%;
        height: 100%;
    }
    .personal-page-gradient {
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
    }
    .grad-left {
        width: 70%;
        height: 100%;
        background: linear-gradient(90deg, #171717 0%, #121212 50.35%, rgba(18, 18, 18, 0.739262) 74.73%, rgba(18, 18, 18, 0) 100%);
        position: absolute;
        top: 0;
        left: 0;
    }
    .grad-bottom {
        width: 100%;
        height: 100%;
        background: linear-gradient(0deg, #171717 13.25%, rgba(18, 18, 18, 0.7) 30.46%, rgba(18, 18, 18, 0) 64.24%);
        position: absolute;
        bottom: 0;
        left: 0;
    }

    .personal-page-content {
        z-index: 40;
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
        z-index: -1;
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
        .personal-page-img {
            filter: blur(20px);
        }
        .personal-page-content{
            min-height: auto;
        }
        .personal-page-background{
            height: auto;
        }
    }
    @media screen and (max-width: 1025px) {
        .personal-page-background{
            top:80px;
        }
        .personal-profile__center-block{
            margin-left: 70px;
        }
    }

</style>




<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
<?endif;?>

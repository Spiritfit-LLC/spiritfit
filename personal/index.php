<?
define('HIDE_SLIDER', true);
define('BREADCRUMB_H1_ABSOLUTE', true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");

use \Bitrix\Main\Page\Asset;
Asset::getInstance()->addString('<meta name="robots" content="noindex, follow" />');
?>

<div class="content-center test-text" style="">*ПОЗДРАВЛЯЕМ! Вы стали участником закрытого тестирования программы лояльности.</div>
<style>
    .test-text{
        position: absolute;
        margin: 43px 0;
        font-size: 14px;
        color: #ff7628;
    }
    @media screen and (max-width: 1025px){
        .test-text {
            margin: 26px 0;
        }
    }
</style>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
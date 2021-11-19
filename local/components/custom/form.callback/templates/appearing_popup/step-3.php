<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>

<div class="popup popup--call" <? if ($arResult['SUBMIT'] == "Y"): ?> style="display: block;"<? endif; ?>>
    <div class="popup__bg"></div>
    <div class="popup__window">
        <div class="popup__close">
            <div></div>
            <div></div>
        </div>
        <div class="popup__success"><?= $arResult["INFO"]["PROPERTIES"]["SUCCESS_MESSAGE_APPEARING_POPUP"]["VALUE"] ?></div>
    </div>
</div>
<?
/*session_start();
if(empty($_SESSION['FIRST_OPEN'])){ ?>
    <script>dataLayerSend('conversion', 'sendPopupForm', '')</script>
<? }
$_SESSION['FIRST_OPEN'] = 'N';*/
?>

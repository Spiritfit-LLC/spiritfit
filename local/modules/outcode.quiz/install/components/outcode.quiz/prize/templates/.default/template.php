<?
    if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

    /** @var array $arParams */
    /** @var array $arResult */
    /** @global CMain $APPLICATION */
    /** @global CUser $USER */
    /** @global CDatabase $DB */
    /** @var CBitrixComponentTemplate $this */
    /** @var string $templateName */
    /** @var string $templateFile */
    /** @var string $templateFolder */
    /** @var string $componentPath */
    /** @var CBitrixComponent $component */

    $this->setFrameMode(true);
?>
<script type="text/javascript">
    var quizPrizeComponentName = "<?=$arResult['COMPONENT_NAME']?>";
    var quizPrizeComponentSuccessMsg = "<?=GetMessage('QUIZ_PRIZE_SUCCESS')?>";
    var quizPrizeComponentExistMsg = "<?=GetMessage('QUIZ_PRIZE_EXISTS')?>";
</script>
<? if($arResult["SHOW_BUTTON"]) {
    ?><div class="blockitem">
        <div class="content-center">
            <div class="block-link select-prize-wrapper">
                <span class="select-prize__error" style="display: block; position: relative; color: red; font-size: 12pt;"></span>
                <a class="button gradient select-prize" href="#prize" data-id="<?=$arParams['ELEMENT_ID']?>" data-cid="<?=$arResult['COMPONENT_ID']?>"><span><?=$arResult['BUTTON_NAME']?></span></a>
            </div>
        </div>
    </div><?
} ?>

<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php
$this->addExternalJs(SITE_TEMPLATE_PATH . '/vendor/slick/slick.min.js');
$this->addExternalCss(SITE_TEMPLATE_PATH . '/vendor/slick/slick.min.css');
//$this->addExternalCss(SITE_TEMPLATE_PATH . '/vendor/animate/animate.min.css');
?>
<?php
    $className = $arResult["PROPERTIES"]["STYLE"]["VALUE_XML_ID"];

    switch ($className){
        case "bg":
            $bg="bg";
            $className="default";
            break;
        case "bg-reverse":
            $bg="bg";
            $className="default-reverse";
            break;
    }
?>
<div class="content-center">
    <?if (!empty($arResult["PROPERTIES"]["TITLE"]["VALUE"])):?>
        <div class="b-section__title">
            <h2><?=$arResult["PROPERTIES"]["TITLE"]["VALUE"]?></h2>
        </div>
    <?endif;?>
    <div class="photo-slider__slick <?=$className?> <?=$bg?>" id="slick_<?=$arResult["ID"]?>">
        <?for ($i=0; $i<count($arResult["PROPERTIES"]["PHOTO"]["VALUE"]); $i++):?>
            <?
                $SLIDER=$arResult["PROPERTIES"]["PHOTO"]["VALUE"][$i];
                $SLIDER_DESC=$arResult["PROPERTIES"]["PHOTO_DESCRIPTION"]["VALUE"][$i]["TEXT"];
                $SLIDER_BTN=$arResult["PROPERTIES"]["SLIDER_BTN"]["VALUE"][$i];
                $SLIDER_BTN_LINK=$arResult["PROPERTIES"]["SLIDER_BTN"]["DESCRIPTION"][$i];
                $SLIDER_TITLE=$arResult["PROPERTIES"]["SLIDER_TITLE"]["VALUE"][$i];
            ?>
            <div class="photo-slider__item <?=$className?>">
                <div class="photo-slider__content <?=$className?>">
                    <div class="photo-slider__arrows">
                        <div class="photo-slider__arrow left"></div>
                        <div class="photo-slider__arrow right"></div>
                    </div>
                    <?if (!empty($SLIDER_TITLE)):?>
                    <div class="photo-slider__title"><?=$SLIDER_TITLE?></div>
                    <?endif;?>
                    <div class="photo-slider__text"><?=htmlspecialcharsback($SLIDER_DESC)?></div>
                    <?if (!empty($SLIDER_BTN)):?>
                        <a href="<?=$SLIDER_BTN_LINK?>" class="button-outline"><?=$SLIDER_BTN?></a>
                    <?endif;?>
                </div>
                <div class="photo-slider__img lazy-img-bg <?=$className?>" data-src="<?=CFile::GetPath($SLIDER)?>"></div>
            </div>
        <?endfor;?>
    </div>
</div>

<script>
    var slider_slick_id=<?=CUtil::PhpToJSObject($arResult["ID"])?>;
</script>



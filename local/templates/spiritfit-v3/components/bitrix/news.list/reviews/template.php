<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php
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

$this->addExternalCss(SITE_TEMPLATE_PATH . "/vendor/slick/slick.css");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/vendor/slick/slick.min.js");

$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/slick.css");
?>
<section class="reviews-slider">
    <div class="review-detail__popup-container">
        <div class="review-detail__popup">
            <div class="review-detail__popup-closer closer white" onclick="close_review()">
                <?=file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/icons/closer-default.svg')?>
            </div>
            <div class="review-detail-content">

            </div>
        </div>
    </div>

    <div class="content-center">
        <div class="reviews-slider__container">
            <?foreach($arResult["ITEMS"] as $ITEM):?>
                <div class="review__item" data-id="<?=$ITEM["ID"]?>">
                    <div class="review__user">
                        <div class="review__user-img <?if (empty($ITEM["PREVIEW_PICTURE"])) echo "gradient"?> <?if (!empty($ITEM["PREVIEW_PICTURE"])) echo "lazyload"?>" <?if (!empty($ITEM["PREVIEW_PICTURE"])):?> data-src="<?=$ITEM["PREVIEW_PICTURE"]["SRC"]?>" <?endif;?>>
                            <?if (empty($ITEM["PREVIEW_PICTURE"])):?>
                                <?=mb_substr($ITEM["PROPERTIES"]["NAME"]["VALUE"], 0, 1)?>
                            <?endif;?>
                        </div>
                        <div class="review__user-name"><?=$ITEM["PROPERTIES"]["NAME"]["VALUE"]?></div>
                    </div>

                    <div class="review__content"><?=$ITEM["PREVIEW_TEXT"]?></div>
                    <div class="review__content-detail" onclick="show_review('<?=$ITEM["ID"]?>')">Подробнее</div>
                    <div class="review__src-container">
                        <?if (!empty($ITEM["PROPERTIES"]["SRC"])):?>
                            <?switch ($ITEM["PROPERTIES"]["SRC_TYPE"]["VALUE_XML_ID"]):
                                case "map":
                                    $background=__DIR__.'/img/map-point.svg';
                                    break;
                                case "vk":
                                    $background=__DIR__.'/img/vk-brands.svg';
                                    break;
                                case "instagram":
                                    $background=__DIR__.'/img/inst-brands.svg';
                                    break;
                            endswitch;?>

                            <?if (!empty($background)){
                               $background=str_replace($_SERVER["DOCUMENT_ROOT"], "", $background);
                            }?>

                            <div class="review__src-source" <?if(!empty($background)):?> style="background-image: url('<?=$background?>'); padding-left: 20px"<?endif;?>>
                                <?=$ITEM["PROPERTIES"]["SRC"]["VALUE"]?>
                            </div>
                            <div class="review__src-link">
                                <a href="<?=$ITEM["PROPERTIES"]["SRC_LINK"]["VALUE"]?>" target="_blank" class="review-link"><?=$ITEM["PROPERTIES"]["SRC_LINK"]["DESCRIPTION"]?></a>
                            </div>
                        <?endif;?>
                        <?if (!empty($ITEM["PROPERTIES"]["RATING"]["VALUE"])):?>
                        <div class="review-rating__container">
                            <div>оценка:</div>
                            <div class="rating-value"><?=$ITEM["PROPERTIES"]["RATING"]["VALUE"]?></div>
                        </div>
                        <?endif?>
                    </div>
                </div>
            <?endforeach;?>
        </div>
    </div>
</section>

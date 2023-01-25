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
$this->addExternalJs(SITE_TEMPLATE_PATH . "/vendor/jquery.jscroll/jquery.jscroll.js");

$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/slick.css");
?>
<div class="trainer-block">
    <div class="content-center b-treners">
        <div class="trainer-block__club">
            <?$i=0?>
            <?foreach ($arResult["CLUBS"] as $CLUB):?>
                <button class="trainer-block__club-control <?=$i==0?"active":""?>" data-club="<?=$CLUB["ID"]?>" onclick="select_club(this, <?=$CLUB["ID"]?>)"><?=$CLUB["NAME"]?></button>
            <?$i++?>
            <?endforeach;?>
        </div>
        <div class="trainer-block__trainers">
            <div class="b-treners__wrapper">
                <?foreach ($arResult["CLUBS"] as $CLUB):?>
                    <?foreach ($CLUB["TEAM"] as $ITEM):?>
                        <div class="b-twoside-card b-treners__item" data-club="<?=$CLUB["ID"]?>">
                            <div class="b-twoside-card__inner">
                                <div class="b-twoside-card__content">
                                    <?if (!empty($ITEM["VIDEO"])):?>
                                        <video autoplay muted loop playsinline class="b-twoside-card__image lazy" poster="<?=SITE_TEMPLATE_PATH.'/img/video-default-preloader.gif'?>">
                                            <source data-src="<?=$ITEM["VIDEO"]?>" type="video/<?=pathinfo($ITEM["VIDEO"], PATHINFO_EXTENSION)?>">
                                        </video>
                                    <?else:?>
                                        <div class="b-twoside-card__image lazy-bg" data-src="<?=$ITEM["PICTURE"]?>"></div>
                                    <?endif;?>
                                    <div class="b-twoside-card__name">
                                        <?=str_replace(" ", "<br/>", $ITEM["NAME"])?>
                                    </div>
                                    <div class="b-twoside-card__open">
                                        Подробнее
                                    </div>
                                </div>
                                <div class="b-twoside-card__hidden-content">
                                    <div class="b-twoside-card__name"><?=$ITEM["NAME"]?></div>
                                    <div class="b-twoside-card__description">
                                        <!--noindex--><?=$ITEM['TEXT']?><!--/noindex-->
                                    </div>
                                    <a href="/abonement/probnaya-trenirovka-/" class="button smooth-radius card-btn">ПРОБНАЯ ТРЕНИРОВКА</a>
                                </div>
                            </div>
                        </div>
                    <?endforeach;?>
                <?endforeach;?>
            </div>
        </div>
    </div>
</div>

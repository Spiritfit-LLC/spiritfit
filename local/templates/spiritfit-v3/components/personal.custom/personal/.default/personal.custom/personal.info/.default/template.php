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

<div class="personal-menu">
    <?foreach ($arParams["MENU"] as $MENU):?>
        <a class="personal-menu__item <?=$MENU["ACTIVE"]?"active":""?>" href="<?=$MENU["LINK"]?>"><?=$MENU["NAME"]?></a>
    <?endforeach;?>
</div>
<div class="personal-name">Привет, <?=$arResult["USER_NAME"]?>!</div>
<div class="personal-user__main-info">
    <!--main-->
    <div class="personal-main-info-item">
        <div class="personal-user-info-item__top">
            <div class="personal-user__head">
                <?
//                if (empty($arResult["USER_PHOTO"])) {
//                    $IMG_TEXT = mb_substr($arResult["USER_NAME"], 0, 1) . mb_substr($arResult["USER_SURNAME"], 0, 1);
//                }else{
//                    $personal_photo = CFile::ResizeImageGet($arResult["USER_PHOTO"], array('width'=>50, 'height'=>50), BX_RESIZE_IMAGE_PROPORTIONAL, true);
//                }
                $IMG_TEXT = mb_substr($arResult["USER_NAME"], 0, 1) . mb_substr($arResult["USER_SURNAME"], 0, 1);
                ?>
                <div class="personal-user__img <?if(empty($personal_photo["src"])) echo "none"?>" <?if(!empty($personal_photo["src"])):?> style="background-image: url('<?=$personal_photo["src"]?>')" <?endif?>>
                    <?if (!empty($IMG_TEXT)) echo $IMG_TEXT?>
                </div>
                <div class="personal-user__name">
                    <?=$arResult["USER_NAME"]?><br>
                    <?=$arResult["USER_SURNAME"]?>
                </div>
            </div>
        </div>
        <div class="personal-main-info-item__footer">
            <?if (!empty($arResult["USER_PL_LEVEL"])):?>
                <div class="gradient-text-block br-50"><?=$arResult["USER_PL_LEVEL"]?></div>
            <?endif;?>

            <a class="personal-user-info__link" href="<?=$arParams["MENU"]["SETTINGS"]["LINK"]?>">изменить</a>
        </div>

        <div class="personal-main-info-item__icon">
            <?=file_get_contents(__DIR__.'/images/user-info-icon.svg');?>
        </div>
    </div>
    <!--main-->

    <!--abonement-->
    <div class="personal-main-info-item">
        <div class="personal-user-info-item__top">
            <div class="personal-user-info-item__title"><?=$arResult["USER_TARIF"]["NAME"]?></div>
            <?
            if (!empty($arResult["USER_TARIF"]["VALUE"]["name"])){
                $ABONEMENT_NAME=$arResult["USER_TARIF"]["VALUE"]["name"];
            }
            else{
                $ABONEMENT_NAME=$arResult["USER_TARIF"]["VALUE"]["status"]["name"];
            }
            ?>
            <div class="personal-user-info-item__value small red <?if ($arResult["USER_TARIF"]["CLUE"]):?> clue <?endif;?>">
                <span><?=$ABONEMENT_NAME?></span>
                <?if ($arResult["USER_TARIF"]["CLUE"]):?>
                    <div class="personal-field-clue" data-clue="<?=$arResult["USER_TARIF"]["CODE"]?>">
                        <?=file_get_contents($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/img/icons/question-mark.svg');?>
                    </div>
                <?endif;?>
            </div>

            <?if ($arResult["USER_TARIF"]["VALUE"]["status"]["id"]!=-1):?>
            <div class="personal-user-info-item__value subvalue">
                <?if ($arResult["USER_TARIF"]["VALUE"]["status"]["id"]!=-4):?>
                <span class="subvalue-placeholder"><?=$arResult["USER_TARIF"]["VALUE"]["status"]["name"]?>.</span>
                <?if (!empty($arResult["USER_TARIF"]["VALUE"]["status"]["arrear"])):?>
                <span class="subvalue-placeholder"><?=$arResult["USER_TARIF"]["VALUE"]["status"]["arrear"]?> руб.</span>
                    <?else:?>
                        <span class="subvalue-placeholder"><?=$arResult["USER_TARIF_PRICE"]?></span>
                <?endif;?>
                <br>
                <?endif;?>
                <span class="subvalue-placeholder"><?=$arResult["USER_TARIF"]["VALUE"]["status"]["datedescription"]?>:</span>
                <?=$arResult["USER_TARIF"]["VALUE"]["status"]["date"]?>
            </div>
<!--            <div class="personal-user-info-item__value subvalue">-->
<!--                -->
<!--            </div>-->
            <?endif;?>
        </div>
        <div class="personal-main-info-item__footer">
            <?if (!empty($arResult["USER_ABONEMENT_BTN"])):?>
                <a class="personal-user-info__link" href="<?=$arResult["USER_ABONEMENT_BTN"]["LINK"]?>"><?=$arResult["USER_ABONEMENT_BTN"]["TEXT"]?></a>
            <?endif;?>
        </div>

        <div class="personal-main-info-item__icon">
            <?=file_get_contents(__DIR__.'/images/tarif-info-icon.svg');?>
        </div>
    </div>
    <!--abonement-->

    <?if ($arResult["USER_PL"]["VALUE"]["isreg"]!==null):?>
    <!--loyalty-->
    <div class="personal-main-info-item">
        <div class="personal-user-info-item__top">
            <div class="personal-user-info-item__title"><?=$arResult["USER_PL"]["NAME"]?></div>
            <?if ($arResult["USER_PL"]["VALUE"]["isreg"]!==false):?>
                <div class="personal-user-info-item__value purple <?if ($arResult["USER_PL"]["CLUE"]):?> clue <?endif;?>">
                    <span><?=$arResult["USER_PL"]["VALUE"]["balance"]?></span>
                    <?if ($arResult["USER_PL"]["CLUE"]):?>
                        <div class="personal-field-clue" data-clue="<?=$arResult["USER_PL"]["CODE"]?>">
                            <?=file_get_contents($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/img/icons/question-mark.svg');?>
                        </div>
                    <?endif;?>
                </div>
            <?else:?>
                <div class="personal-user-info-item__value subvalue">
                    <span class="subvalue-placeholder">Нет данных</span>
                </div>
            <?endif;?>
        </div>
        <?if (!empty($arResult["USER_PL"]["VALUE"])):?>
            <div class="personal-main-info-item__footer">
                <a class="personal-user-info__link" href="/loyalty-program/">Как потратить бонусы</a>
            </div>
            <div class="personal-main-info-item__icon">
                <?=file_get_contents(__DIR__.'/images/points-info-icon.svg');?>
            </div>
        <?else:?>
            <div class="pl_ref_info">
                <?=htmlspecialcharsback($arResult["PERSONAL_FIELDS"]["pl_reg_info"]["CLUE_VALUE"])?>
            </div>
        <?endif;?>
    </div>
    <!--loyalty-->
    <?endif;?>

    <!--visits-->
    <?
    $VISITLIST=$arResult["USER_VISITS"]["VALUE"]["list"];
    $VISITS=0;
    if (!empty($VISITLIST)){
        foreach ($VISITLIST as $MONTHVISIT){
            $VISITS+=$MONTHVISIT["days"];
        }
    }

    $LAST_VISIT=$arResult["USER_VISITS"]["VALUE"]["date"];
    if (!empty($LAST_VISIT)){
        $LAST_VISIT=FormatDate("Q", MakeTimeStamp($LAST_VISIT, "DD.MM.YYYY"))." назад";
    }
    ?>
    <div class="personal-main-info-item">
        <div class="personal-user-info-item__top">
            <div class="personal-user-info-item__title"><?=$arResult["USER_VISITS"]["NAME"]?></div>
            <div class="personal-user-info-item__value red <?if ($arResult["USER_VISITS"]["CLUE"]):?> clue <?endif;?>">
                <span><?=$VISITS?></span>
                <?if ($arResult["USER_VISITS"]["CLUE"]):?>
                    <div class="personal-field-clue" data-clue="<?=$arResult["USER_VISITS"]["CODE"]?>">
                        <?=file_get_contents($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/img/icons/question-mark.svg');?>
                    </div>
                <?endif;?>
            </div>
        </div>
        <div class="personal-main-info-item__footer">
            <div class="personal-user-info-item__value subvalue "><?=$LAST_VISIT?></div>
        </div>
        <div class="personal-main-info-item__icon">
            <?=file_get_contents(__DIR__.'/images/visits-info-icon.svg');?>
        </div>
    </div>
    <!--visits-->
</div>
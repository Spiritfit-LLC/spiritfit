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

$this->addExternalCss(SITE_TEMPLATE_PATH . '/css/slick.css');
$this->addExternalCss(SITE_TEMPLATE_PATH . "/vendor/slick/slick.css");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/vendor/slick/slick.min.js");
?>

<script>
    var personalPartnersComponent = <?=CUtil::PhpToJSObject($component->getName())?>;
    var personalPartnerTemplatePath = <?=CUtil::PhpToJSObject(\Bitrix\Main\Component\ParameterSigner::signParameters($component->getName(), $templateFolder))?>;
</script>


<?if (!empty($arResult["SPIRITBOX"]) || !empty($arResult["PROMOCODES"])):?>
<div class="personal-partners">
    <div class="personal-partners__list">
        <?foreach ($arResult["PROMOCODES"] as $item):?>
            <div class="personal-partner__item">
                <div class="pp-item_bg lazy-img-bg" data-src="<?=CFile::GetPath($item["PREVIEW_PICTURE"])?>"></div>
                <div class="pp-item__content">
                    <div class="pp-item__preview-text"><?=htmlspecialcharsback($item["NAME"])?></div>
                    <div class="pp-item__preview-call2action">С персональной скидкой выгодней!</div>
                    <div class="pp-item__footer">
                        <?if ($item["ACTIVE_TO"]):?>
                            <div class="pp-item__date">
                                <div class="pp-item-date__icon">
                                    <?=file_get_contents(__DIR__.'/images/time-icon.svg')?>
                                </div>
                                <span>Воспользуйтесь до <?=FormatDate("d F Y", MakeTimeStamp($item["ACTIVE_TO"]))?></span>
                            </div>
                        <?endif;?>
                        <div class="pp-item__detail-btn">
                            <button type="button" class="button pp-item__btn" data-id="<?=$item["ID"]?>">Подробнее</button>
                        </div>
                    </div>
                </div>
            </div>
        <?endforeach;?>
        <?foreach ($arResult["SPIRITBOX"] as $item):?>
            <div class="personal-partner__item">
                <div class="pp-item_bg lazy-img-bg" data-src="<?=CFile::GetPath($item["PREVIEW_PICTURE"])?>"></div>
                <div class="pp-item__content">
                    <div class="pp-item__preview-text"><?=htmlspecialcharsback($item["NAME"])?></div>
                    <div class="pp-item__preview-call2action">С персональной скидкой выгодней!</div>
                    <div class="pp-item__footer">
                        <?if ($item["ACTIVE_TO"]):?>
                            <div class="pp-item__date">
                                <div class="pp-item-date__icon">
                                    <?=file_get_contents(__DIR__.'/images/time-icon.svg')?>
                                </div>
                                <span>Воспользуйтесь до <?=FormatDate("d F Y", MakeTimeStamp($item["ACTIVE_TO"]))?></span>
                            </div>
                        <?endif;?>
                        <div class="pp-item__detail-btn">
                            <button type="button" class="button pp-item__btn" data-id="<?=$item["ID"]?>">Подробнее</button>
                        </div>
                    </div>
                </div>
            </div>
        <?endforeach;?>
    </div>
    <?if (!empty($arResult["PFD"])):?>
    <div class="personal-pfd">
        <div class="personal-section-title">
            Персональные предложения
        </div>
        <div class="personal-pfd__list">
            <?foreach ($arResult["PFD"] as $item):?>
                <div class="personal-partner__item">
                    <div class="pp-item_bg lazy-img-bg" data-src="<?=CFile::GetPath($item["PREVIEW_PICTURE"])?>"></div>
                    <div class="pp-item__content">
                        <div class="pp-item__preview-text"><?=htmlspecialcharsback($item["NAME"])?></div>
                        <div class="pp-item__preview-call2action"><?=htmlspecialcharsback($item["PREVIEW_TEXT"])?></div>
                        <div class="pp-item__footer">
                            <?if ($item["ACTIVE_TO"]):?>
                                <div class="pp-item__date">
                                    <div class="pp-item-date__icon">
                                        <?=file_get_contents(__DIR__.'/images/time-icon.svg')?>
                                    </div>
                                    <span>Воспользуйтесь до <?=FormatDate("d F Y", MakeTimeStamp($item["ACTIVE_TO"]))?></span>
                                </div>
                            <?endif;?>
                            <div class="pp-item__detail-btn">
                                <button type="button" class="button pp-item__btn" data-id="<?=$item["ID"]?>">Подробнее</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?endforeach;?>
        </div>
    </div>
    <?endif;?>
</div>
<div class="popup-modal__container" id="partner-detail-popup">
    <div class="popup__modal">
        <div class="modal__closer" onclick="$('#partner-detail-popup').fadeOut(300);">
            <?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/img/icons/closer-default.svg')?>
        </div>
        <div class="personal-partner-detail__content">

        </div>

    </div>
</div>
<?endif;?>

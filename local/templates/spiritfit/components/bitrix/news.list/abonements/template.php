<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$arInfoProps = Utils::getInfo()['PROPERTIES'];
?>

<div class="grid__aside">
    <div class="grid-element grid-element--aside-big">
        <h1 class="grid-element__head"><?= $arResult["SECTION"]["NAME"] ?></h1>
		<div class="grid-element__desc"><?= $arResult["SECTION"]["DESCRIPTION"] ?></div>
		<div class="subscription__aside-form-row subscription__aside-form-row--select">
			<select class="input input--light input--long custom--select js-pjax-select-abonements" data-placeholder="Выберите клуб">
                <option value=""></option>
                <? $clubIsSelected = false;
                   foreach ($arResult['CLUBS'] as $club) :?>
					<option value="<?=$club['NUMBER']?>" <?if($club['NUMBER'] == $_REQUEST['club']){ $clubIsSelected = $club['ID'];?> selected<?} elseif (!$_REQUEST['club']) { $clubIsSelected = false; }?>><?=$club['NAME']?></option>
				<? endforeach;?>
			</select>
        </div>
    </div>
</div>

<?$ogImage = CFile::GetPath($arInfoProps['OG_IMG']['VALUE']);?>
<div id="seo-div" 
	hidden="true" 
	data-title="<?=$arResult['SEO']['SECTION_META_TITLE']?>" 
	data-description="<?=$arResult['SEO']['SECTION_META_DESCRIPTION']?>" 
	data-keywords="<?=$arResult['SEO']['SECTION_META_KEYWORDS']?>" 
    data-image="<?=$ogImage?>"></div>
<div class="grid__main grid__main--subscription">
    <?$i = 1;?>
    <?foreach($arResult["ITEMS"] as $key => $arItem):?>
        <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            $soon = $arItem["PROPERTIES"]["SOON"]["VALUE"] ? true : false;

            $minPrice = '0';
            $abonemetPrices =[];
            foreach ($arItem["PRICES"] as $key => $price) {
				$abonemetPrices[$key] = $price['PRICE'];
            }
            $minFromAbonementPrices = min($abonemetPrices);
            if ($minFromAbonementPrices && $arItem["SALE"] && ($minFromAbonementPrices > $arItem["SALE"])) {
                $minPrice = $arItem["SALE"];
            }elseif($minFromAbonementPrices){
                $minPrice = $minFromAbonementPrices;
            }else{
                $minPrice = $arItem['MIN_PRICE'];
            }
        ?>
        <div class="grid__main-inner <?if ($i == count($arResult["ITEMS"])){echo 'grid__main-inner--nomargin';};?>">
            <div class="grid__main-primary flip_box">
                <div class="grid-element grid-element--sub grid-element--primary grid-element--front">
                    <? if ($arItem["PREVIEW_PICTURE"]["SRC"]): ?>
						<div class="grid-element__bg grid-element__bg-custom" <?/*style="background-image: url('<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>')"*/?> >
                            <img alt="<?= $arItem["~NAME"] ?>" class="grid-element__bg-img" src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>">                  
                        </div>
					<? endif; ?>
                    <div class="grid-element-wrap">
                        <div class="grid-element__main"><?= $arItem["~NAME"] ?></div>
                        <? if ($arItem['PROPERTIES']['TEXT_PROMO']['~VALUE']): ?>
                        <div class="grid-element__text"><?= $arItem['PROPERTIES']['TEXT_PROMO']['~VALUE']?></div>
                        <? endif; ?>
                    </div>
                    <div class="grid-element__bottom">
                        <? if ($soon): ?>
							<div class="grid-element__bottom-add">Скоро...</div>
						<? else: ?>
							<? if ($arItem['CODE'] !== '23-fevralya' && $clubIsSelected):?>
								<? if ($arItem["PROPERTIES"]["PRICE"]["VALUE"] && $arItem["PROPERTIES"]["PRICE"]["VALUE"][0]["PRICE"] != " " && $minPrice !== '0'): ?>
									<div class="grid-element__bottom-add"><?= Utils::getFormatPrice($minPrice);?> руб.</div> 
								<? else: ?>
									<div class="grid-element__bottom-add"><?= $arItem["PROPERTIES"]["PRICE_SIGN"]["VALUE"] ?></div> 
								<? endif; ?>
							<? endif; ?>
						<? endif; ?>
                    </div>
                </div>
                <div class="grid-element grid-element--sub grid-element--primary grid-element--back">
                    <div class="grid-element__inner">
                        <? if ($arItem['PROPERTIES']['INCLUDE']['VALUE']): ?>
                            <h2 class="grid-element__title"><?= $arItem["~NAME"] ?></h2>
                            <div class="subscription__include-wrapper ps__child--consume">
                                <div class="subscription__include subscription__include--grid">
                                    <?=$arItem['~PREVIEW_TEXT']?>
                                </div>
                                <? /* 
                                <ul class="subscription__include subscription__include--grid">
                                    <? foreach($arItem['PROPERTIES']['INCLUDE']['VALUE'] as $arInclude): ?>
                                        <li class="subscription__include-item subscription__include-item--grid"><?=$arInclude;?></li>
                                    <? endforeach; ?>
                                </ul>
                                <div class="club__team-quote club__team-quote--subscription club__team-quote--subscription-gift"></div>
                                */ ?>
                            </div>
                        <? endif; ?>
                        <div class="subscription__include-wrapper-items subscription__include-wrapper-items--desktop">
                            <?
                            $countCompVersion = 0;
                            foreach ($arItem['PROPERTIES']['FOR_PRESENT']['VALUE'] as $present) {?>
                                <? if ($present && $clubIsSelected == $present['LIST'] && $countCompVersion < 3) {?>
                                    <span class="subscription__include-item subscription__include-item--desktop subscription__include-item--grid subscription__include-item--grid--orange subscription__include-item--abonement">
                                        <?=$present['PRICE']?>
                                    </span>
                                <?  $countCompVersion++;
                                    }?>
                            <? }?>
                        </div>
                        <? if ($arItem['~PREVIEW_TEXT']): ?>
							<div class="club__team-quote club__team-quote--subscription club__team-quote--grid"><?=$arItem['~PREVIEW_TEXT'];?></div>
                        <? endif;?>
                        <div class="subscription__include-wrapper-items subscription__include-wrapper-items--mobile">
                            <?
                            $count = 0;
                            foreach ($arItem['PROPERTIES']['FOR_PRESENT']['VALUE'] as $present) {?>
                                <? if ($present && $clubIsSelected == $present['LIST'] && $count < 3){?>
                                    <span class="subscription__include-item subscription__include-item--mobile subscription__include-item--grid subscription__include-item--grid--orange subscription__include-item--abonement">
                                        <?=$present['PRICE']?>
                                    </span>
                                <?  $count++;
                                    }?>
                            <? }?>
                        </div>
                        <?if (strlen($arItem["BASE_PRICE"]["PRICE"]) > 0 && $clubIsSelected):?>						
							<div class="club__team-price club__team-price--subscription club__team-price--abonement">
                                <div class="club__team-price-inner">
                                    <? foreach ($arItem["PRICES"] as $key => $price):?>
                                        <div class="club__team-price-item">
                                            <div class="club__team-price-mounth club__team-price-mounth--subscription"><?= $price["SIGN"] ?></div>
                                            <div class="club__team-price-wrap">
                                                <?if ($key == 0 && $arItem["SALE"]) {?>
                                                    <span class="club__team-price-unit club__team-price-unit--old club__team-price-unit--subscription"><?= Utils::getFormatPrice($price["PRICE"]) ?> руб.</span><br>
                                                    <span class="club__team-price-unit club__team-price-unit--new club__team-price-unit--subscription"><?= Utils::getFormatPrice($arItem["SALE"])?> руб.</span>
                                                <?}elseif($key == 1 && $arItem["SALE_TWO_MONTH"]){?>
                                                    <span class="club__team-price-unit club__team-price-unit--new club__team-price-unit--subscription"><?= Utils::getFormatPrice($arItem["SALE_TWO_MONTH"])?> руб.</span>
                                                <?}else{?>
                                                    <? if ($price["PRICE"]  && $price["PRICE"] != " "): ?>
                                                        <span class="club__team-price-unit club__team-price-unit--new club__team-price-unit--subscription"><?= Utils::getFormatPrice($price["PRICE"]) ?> руб.</span>
                                                    <? endif; ?>
                                                <?}?>
                                                <? if ($minPrice == '0'){?>
                                                    <?= $abonement["PROPERTIES"]["PRICE_SIGN"]["VALUE"]; ?>
                                                <?}?>	
                                            </div>
                                        </div>
                                    <? endforeach; ?>
                                </div>
							</div>
                        <? endif;?>
                        <? if ($arItem["PROPERTIES"]["DESCRIPTION_SALE"]["VALUE"]): ?>
							<p class="club__team-duration club__team-duration--abonement"><?= $arItem["PROPERTIES"]["DESCRIPTION_SALE"]["VALUE"] ?></p>
						<? endif; ?>
                        <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="btn btn--subscription btn--subscription-grid js-pjax-link" tabindex="0">Выбрать</a>
                    </div>
                </div>
            </div>
            <div class="r_wrap">
                <div class="b_round"></div>
                <div class="s_round s_round_back">
                    <div class="s_arrow"></div>
                </div>
            </div>
        </div>
        <?
        $i++;
        ?>
    <?endforeach;?>
</div>


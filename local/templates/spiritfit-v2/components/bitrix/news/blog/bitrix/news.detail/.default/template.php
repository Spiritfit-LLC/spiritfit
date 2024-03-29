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

<div class="content-center">
	<div class="blog" itemscope itemtype="http://schema.org/Article">
        <meta itemprop="identifier" content="<?=$arResult["ID"]?>">
        <link itemprop="mainEntityOfPage" href="<?=MAIN_SITE_URL?>"/>
        <h1 class="is-hide" itemprop="headline name"><?=$APPLICATION->ShowTitle()?></h1>
		<div class="blog-wrapper detail">
			<div class="blog-items <?=(count($arResult["LEFT_ITEMS"]) > 0 || !empty($arParams["BANNER"])) ? "two" : "one" ?>">
				<div class="blog-items-col">
                    <div class="blog-detail-seo-info">
                        <div class="blog-detail-date" itemprop="datePublished" content="<?=$arResult["datePublished"]?>">Дата: <?=$arResult["DATE"]?></div>
                        <div class="blog-detail-showing-count">Просмотры: <?=$arResult['PROPERTIES']['SHOWING_COUNT']["VALUE"]?></div>
                        <div class="blog-detail-rating">Рейтинг:
                            <svg width="70" height="14" viewBox="0 0 160 32" style="margin: 0 5px;">
                                <defs>
                                    <mask id="perc">
                                        <rect x="0" y="0" width="100%" height="100%" fill="white" />
                                        <rect x="<?=$arResult['PROPERTIES']['RATING_PROCENT']?>%" y="0" width="100%" height="100%" fill="grey" />
                                    </mask>

                                    <symbol viewBox="0 0 32 32" id="star">
                                        <path d="M31.547 12a.848.848 0 00-.677-.577l-9.427-1.376-4.224-8.532a.847.847 0 00-1.516 0l-4.218 8.534-9.427 1.355a.847.847 0 00-.467 1.467l6.823 6.664-1.612 9.375a.847.847 0 001.23.893l8.428-4.434 8.432 4.432a.847.847 0 001.229-.894l-1.615-9.373 6.822-6.665a.845.845 0 00.214-.869z" />
                                    </symbol>
                                    <symbol viewBox="0 0 160 32" id="stars">
                                        <use xlink:href="#star" x="-64" y="0"></use>
                                        <use xlink:href="#star" x="-32" y="0"></use>
                                        <use xlink:href="#star" x="0" y="0"></use>
                                        <use xlink:href="#star" x="32" y="0"></use>
                                        <use xlink:href="#star" x="64" y="0"></use>
                                    </symbol>
                                </defs>

                                <use xlink:href="#stars" fill="#ff7628" mask="url(#perc)"></use>
                            </svg><?=round($arResult['PROPERTIES']['RATING']['VALUE'],2)?>
                        </div>

                    </div>
                    <div class="blog-head-items">
					<? if( !empty($arResult["PICTURE_SRC"]) ) { ?>
						<div class="blog-detail-picture">
                            <link itemscope itemprop="image" href="<?=defined("MAIN_SITE_URL").$arResult["PICTURE_SRC"]?>">
							<img src="<?=$arResult["PICTURE_SRC"]?>" alt="<?=strip_tags($arResult["NAME"])?>" title="<?=strip_tags($arResult["NAME"])?>">
							<? if(!empty($arResult["SECTION_NAMES"])) {
								?><div class="blog-detail-section items">
									<? foreach($arResult["SECTION_NAMES"] as $name) {
										?><a class="item" href="<?=$arResult['SECTION']['PATH'][0]['SECTION_PAGE_URL']?>" itemprop="articleSection"><?=$name?></a><?
									} ?>
								</div><?
							} ?>
						</div>
					<? } ?>
                    </div>
                    <div class="blog-detail-text">
                        <div itemprop="description"><?=$arResult['~DETAIL_TEXT']?></div>

                        <div class="show-sections-titles">СОДЕРЖАНИЕ</div>
                        <div itemprop="articleBody">
                            <?
                            $i=0;
                            foreach ($arResult['TXT'] as $TXT):?>
                                <div class="text-section" data-id="<?=$TXT['ID']?>">
                                    <div class="text-section__title"><?=$TXT['TITLE']?></div>
                                    <div class="text-section__text"><?=$TXT['TEXT']?></div>
                                </div>
                                <?if ($i==1):?>
                                <div class="slider">
                                    <?$APPLICATION->IncludeComponent(
                                        "bitrix:news.list",
                                        "blog.slider",
                                        Array(
                                            "ACTIVE_DATE_FORMAT" => "d.m.Y",
                                            "ADD_SECTIONS_CHAIN" => "N",
                                            "AJAX_MODE" => "N",
                                            "AJAX_OPTION_ADDITIONAL" => "",
                                            "AJAX_OPTION_HISTORY" => "N",
                                            "AJAX_OPTION_JUMP" => "N",
                                            "AJAX_OPTION_STYLE" => "Y",
                                            "CACHE_FILTER" => "N",
                                            "CACHE_GROUPS" => "Y",
                                            "CACHE_TIME" => "36000000",
                                            "CACHE_TYPE" => "A",
                                            "CHECK_DATES" => "Y",
                                            "DETAIL_URL" => "",
                                            "DISPLAY_BOTTOM_PAGER" => "N",
                                            "DISPLAY_DATE" => "N",
                                            "DISPLAY_NAME" => "N",
                                            "DISPLAY_PICTURE" => "N",
                                            "DISPLAY_PREVIEW_TEXT" => "N",
                                            "DISPLAY_TOP_PAGER" => "N",
                                            "FIELD_CODE" => array("", ""),
                                            "FILTER_NAME" => "arFilterSlider",
                                            "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                                            "IBLOCK_ID" => "2",
                                            "IBLOCK_TYPE" => "content",
                                            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                            "INCLUDE_SUBSECTIONS" => "Y",
                                            "MESSAGE_404" => "",
                                            "NEWS_COUNT" => "10",
                                            "PAGER_BASE_LINK_ENABLE" => "N",
                                            "PAGER_DESC_NUMBERING" => "N",
                                            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                            "PAGER_SHOW_ALL" => "N",
                                            "PAGER_SHOW_ALWAYS" => "N",
                                            "PAGER_TEMPLATE" => ".default",
                                            "PAGER_TITLE" => "Новости",
                                            "PARENT_SECTION" => "",
                                            "PARENT_SECTION_CODE" => "",
                                            "PREVIEW_TRUNCATE_LEN" => "",
                                            "PROPERTY_CODE" => array("BANNER_BTN_TEXT", "BANNER_BTN_LINK", "BANNER_TITLE"),
                                            "SET_BROWSER_TITLE" => "N",
                                            "SET_LAST_MODIFIED" => "N",
                                            "SET_META_DESCRIPTION" => "N",
                                            "SET_META_KEYWORDS" => "N",
                                            "SET_STATUS_404" => "N",
                                            "SET_TITLE" => "N",
                                            "SHOW_404" => "N",
                                            "SORT_BY1" => "SORT",
                                            "SORT_BY2" => "ID",
                                            "SORT_ORDER1" => "DESC",
                                            "SORT_ORDER2" => "DESC",
                                            "STRICT_SECTION_CHECK" => "N"
                                        )
                                    )?>
                                </div>
                                <?endif;?>
                            <?
                            $i++;
                            endforeach;?>
                        </div>
                    </div>
                    <div class="blog-text__footer">
                        <?if (empty($_SESSION['USER_VOTES'][$arResult['ID']])):?>
                            <form class="blog-detail-rating-vote">
                                <input type="hidden" name="blog-id" value="<?=$arResult["ID"]?>">
                                <div class="rating-area">
                                    <input type="radio" id="star-5" name="rating" value="5">
                                    <label for="star-5" title="Оценка «5»"></label>
                                    <input type="radio" id="star-4" name="rating" value="4">
                                    <label for="star-4" title="Оценка «4»"></label>
                                    <input type="radio" id="star-3" name="rating" value="3">
                                    <label for="star-3" title="Оценка «3»"></label>
                                    <input type="radio" id="star-2" name="rating" value="2">
                                    <label for="star-2" title="Оценка «2»"></label>
                                    <input type="radio" id="star-1" name="rating" value="1" required>
                                    <label for="star-1" title="Оценка «1»"></label>
                                </div>
                                <input type="submit" value="Оценить работу автора">
                                <div class="thnks-message">Спасибо, будем писать еще!</div>
                            </form>
                        <?else:?>
                            <div class="rating-area">
                                <?for ($i=5; $i>=1; $i--):?>
                                    <input type="radio" id="star-<?=$i?>>" name="rating" value="<?=$i?>" disabled <?if ($i==$_SESSION['USER_VOTES'][$arResult['ID']]) echo "checked";?>>
                                    <label for="star-<?=$i?>" title="Оценка «<?=$i?>»"></label>
                                <?endfor;?>
                                <div class="thnks-message active">Спасибо, будем писать еще!</div>
                            </div>
                        <?endif;?>
                        <div class="blog-share__social is-hide-mobile">
                            <script src="https://yastatic.net/share2/share.js"></script>
                            <div class="ya-share2" data-curtain data-size="l" data-shape="round" data-color-scheme="blackwhite" data-limit="3" data-services="vkontakte,odnoklassniki,telegram,whatsapp"></div>
                        </div>
                        <div class="blog-share__social is-hide-desktop">
                            <script src="https://yastatic.net/share2/share.js"></script>
                            <div class="ya-share2" data-curtain data-size="s" data-shape="round" data-color-scheme="blackwhite" data-limit="3" data-services="vkontakte,odnoklassniki,telegram,whatsapp"></div>
                        </div>

                    </div>
                    <div class="get-pb">
                        <h2>Запишись на пробную тренировку в Spirit.Fitness!</h2>
                        <div>
                            <div class="select-pb">
                                <select class="input input--light input--long input--select get-club__pb">
                                    <option value="off" disabled selected>Выберите клуб</option>
                                    <? foreach ($arResult["CLUBS"] as $number=>$club):?>
                                        <option value="<?=$number?>"><?=$club?></option>
                                    <? endforeach; ?>
                                </select>
                            </div>
                            <a class="button select-pb-btn" href="/abonement/probnaya-trenirovka-/"
                               data-layer="true"
                               data-layercategory="UX"
                               data-layeraction="clickTrialWorkoutButton"
                               data-layerlabel="current_url">Записаться</a>
                        </div>

                    </div>

				</div>
                <div class="blog-items-col is-hide-mobile">
                    <ul class="text-section-titles">
                        <?foreach ($arResult['TXT'] as $TXT):?>
                            <li class="text-section__head-title" data-id="<?=$TXT['ID']?>"><?=$TXT['TITLE']?></li>
                        <?endforeach;?>
                    </ul>
                </div>

			</div>
            <? if( !empty($arResult["ADDITIONAL_ITEMS"]) ) { ?>
                <div class="blog-additional-wrapper">
                    <div class="blog-additional-title"><?=GetMessage("BLOG_ADDITIONAL_TITLE")?></div>
                    <div class="blog-additional">
                        <?
                        foreach($arResult["ADDITIONAL_ITEMS"] as $key => $arItem) {
                            ?>
                            <a class="blog-item" href="<?=$arItem["LINK"]?>">
                                <div class="blog-item-banner">
                                    <img src="<?=$arItem["PICTURE"]["MEDIUM"]?>" alt="<?=strip_tags($arItem["NAME"])?>" title="<?=strip_tags($arItem["NAME"])?>">
                                    <? if(!empty($arItem["SECTION"])) {
                                        ?><div class="blog-item-section items">
                                        <? foreach($arItem["SECTION"] as $name) {
                                            ?><div class="item"><?=$name?></div><?
                                        } ?>
                                        </div><?
                                    } ?>
                                    <div class="blog-item-name"><?=$arItem["NAME"]?></div>
                                </div>
                            </a>
                            <?
                        }
                        ?>
                    </div>
                </div>
            <? } ?>
			<? if(!empty($arParams["BANNER"])) { ?>
				<div class="blog-banner__mobile">
					<a class="blog-banner" href="<?=$arParams["BANNER"]["LINK"]?>">
						<img src="<?=$arParams["BANNER"]["SRC"]?>" alt="<?=$arResult["NAME"]?>" title="<?=$arResult["NAME"]?>">
					</a>
				</div>
			<? } ?>
		</div>
        <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization" style="display:none;">
            <link itemprop="url" href="https://spiritfit.ru/">
            <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
                <img itemprop="url" src="/images/logo_white.svg"/>
            </div>
            <meta itemprop="name" content="SPIRIT.">
            <meta itemprop="telephone" content="+7 495 105 97 97">
            <meta itemprop="address" content="г. Москва">
        </div>

	</div>

</div>
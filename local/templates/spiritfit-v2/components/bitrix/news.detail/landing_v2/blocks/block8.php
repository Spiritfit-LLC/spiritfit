<?
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
	if( !empty($BLOCKS["BLOCK8_ACTIVE"]) ) {
		global $abonementsFilter;
		
		$abonementsIBlockId = Utils::GetIBlockIDBySID('subscription');
		
		$abonementsFilter = ["ACTIVE" => "Y", "ID" => $BLOCKS["BLOCK8_LIST"]];
		
		?>
		<div id="abonementbuy" class="b-abonements">
			<div class="content-center">
				<div class="landing-title">
					<?=$BLOCKS["BLOCK8_TITLE"]?>
				</div>
				<?
					$APPLICATION->IncludeComponent("bitrix:news.list","abonements.landing",Array(
						"DISPLAY_DATE" => "N",
						"DISPLAY_NAME" => "Y",
						"DISPLAY_PICTURE" => "Y",
						"DISPLAY_PREVIEW_TEXT" => "Y",
						"AJAX_MODE" => "N",
						"IBLOCK_TYPE" => "content",
						"IBLOCK_ID" => $abonementsIBlockId,
						"NEWS_COUNT" => "1000",
						"SORT_BY1" => "SORT",
						"SORT_ORDER1" => "ASC",
						"SORT_BY2" => "NAME",
						"SORT_ORDER2" => "ASC",
						"FILTER_NAME" => "abonementsFilter",
						"FIELD_CODE" => Array(),
						"PROPERTY_CODE" => Array("PRICE", "BASE_PRICE", "PRICE_SIGN_DETAIL", "DESCRIPTION_SALE", "SIZE", "CODE_ABONEMENT"),
						"CHECK_DATES" => "Y",
						"DETAIL_URL" => "",
						"PREVIEW_TRUNCATE_LEN" => "",
						"ACTIVE_DATE_FORMAT" => "d.m.Y",
        				"SET_TITLE" => "N",
						"SET_BROWSER_TITLE" => "N",
						"SET_META_KEYWORDS" => "N",
						"SET_META_DESCRIPTION" => "N",
						"SET_LAST_MODIFIED" => "N",
						"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
						"ADD_SECTIONS_CHAIN" => "N",
						"HIDE_LINK_WHEN_NO_DETAIL" => "N",
						"PARENT_SECTION" => "",
						"PARENT_SECTION_CODE" => "",
						"INCLUDE_SUBSECTIONS" => "Y",
						"CACHE_TYPE" => "A",
						"CACHE_TIME" => "3600",
						"CACHE_FILTER" => "Y",
						"CACHE_GROUPS" => "Y",
						"DISPLAY_TOP_PAGER" => "N",
						"DISPLAY_BOTTOM_PAGER" => "N",
						"PAGER_TITLE" => "",
						"PAGER_SHOW_ALWAYS" => "N",
						"PAGER_TEMPLATE" => "",
						"PAGER_DESC_NUMBERING" => "N",
        				"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        				"PAGER_SHOW_ALL" => "N",
        				"PAGER_BASE_LINK_ENABLE" => "N",
        				"SET_STATUS_404" => "N",
        				"SHOW_404" => "N",
        				"MESSAGE_404" => "",
        				"PAGER_BASE_LINK" => "",
        				"PAGER_PARAMS_NAME" => "arrPager",
        				"AJAX_OPTION_JUMP" => "N",
        				"AJAX_OPTION_STYLE" => "N",
        				"AJAX_OPTION_HISTORY" => "N",
        				"AJAX_OPTION_ADDITIONAL" => ""
    				));
				?>
				
			</div>
		</div>
		<?
	}
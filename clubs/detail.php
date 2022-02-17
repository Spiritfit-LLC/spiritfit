<?
define('BREADCRUMB_H1_ABSOLUTE', true);

if (isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true') {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
    $APPLICATION->SetTitle("");
} else {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
}
?><? if(isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true' && $_REQUEST["ajax_send"] == 'Y' && $_REQUEST["WEB_FORM_ID"] == '2'): ?>
	<? 
		$template = "clubs-v2";

		if($_REQUEST['modal_form']){
			$template = 'modal';
		}
		
	?>
	<?$APPLICATION->IncludeComponent(
	"custom:form.aboniment",
	"clubs-v2",
	Array(
		"ABONEMENT_DETAIL" => "Y",
		"AJAX_MODE" => "N",
		"NUMBER" => (!empty($_REQUEST["club"])?$_REQUEST["club"]:$_SESSION['CLUB_NUMBER']),
		"TEXT_FORM" => $_REQUEST["text_form"],
		"THANKS" => "Наш менеджер свяжется с Вами, ожидайте.",
		"WEB_FORM_ID" => "3"
	)
);?>
<? else: ?>
	<?
		CModule::IncludeModule("iblock");
		$siteProperties = Utils::getInfo();
		
		$successText = "";
		if( !empty($siteProperties["PROPERTIES"]["CLUB_FORM_SUCCESS"]["VALUE"])) {
			$successText = $siteProperties["PROPERTIES"]["CLUB_FORM_SUCCESS"]["VALUE"];
		}
	?>
	<?$APPLICATION->IncludeComponent(
	"bitrix:news.detail",
	"club-detail",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_ELEMENT_CHAIN" => "Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BROWSER_TITLE" => "-",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"CLUB_FORM_SUCCESS" => $successText,
		"COMPONENT_TEMPLATE" => "club-detail",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENT_CODE" => $_REQUEST["ELEMENT_CODE"],
		"ELEMENT_ID" => $_REQUEST["ELEMENT_ID"],
		"FIELD_CODE" => array(0=>"PREVIEW_PICTURE",1=>"",),
		"FILE_404" => "",
		"IBLOCK_ID" => "6",
		"IBLOCK_TYPE" => "content",
		"IBLOCK_URL" => "",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"MESSAGE_404" => "",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Страница",
		"PROPERTY_CODE" => array(0=>"EMAIL",1=>"ADRESS",2=>"CORD_YANDEX",3=>"NUMBER",4=>"SOON",5=>"LINK_VIDEO",6=>"PHONE",6=>"TEXT_FORM",7=>"WORK",8=>"INDEX",9=>"HIDE_LINK",10=>"REVIEWS",11=>"",),
		"SET_BROWSER_TITLE" => "Y",
		"SET_CANONICAL_URL" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_STATUS_404" => "Y",
		"SET_TITLE" => "Y",
		"SHOW_404" => "Y",
		"STRICT_SECTION_CHECK" => "N",
		"USE_PERMISSIONS" => "N",
		"USE_SHARE" => "N"
	)
);?>
<? endif; ?><? if (!isset($_SERVER['HTTP_X_PJAX']))  require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
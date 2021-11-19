<?
define('HIDE_BREADCRUMB', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Качество обслуживания");
?>
<style>
.block__detail-breadcrumb.black .b-breadcrumbs__link.is-current,
.block__detail-breadcrumb.black .b-breadcrumbs__link {
	color: black;
}
</style>
<div class="quality">
    <!--<div class="block__detail-breadcrumb quality__top black">
        <? $APPLICATION->IncludeComponent(
            "bitrix:breadcrumb",
            "custom",
            array(
                "START_FROM" => "0",
                "PATH" => "",
                "SITE_ID" => "s1"
            )
        ); ?>
    </div>
    <div class="quality__head" style="color: black;">
        <h1 class="quality__heading"><? $APPLICATION->ShowTitle(false) ?></h1>
    </div>-->
    <div class="quality__questionary">
        <?//<h2 class="quality__subheading">Оценка качества обслуживания</h2>?>
        <div class="primary-form quality__form" id="body_quality">
            <div class="primary-form__staging">
                <div class="primary-form__title">Оцените качество обслуживания</div>
                <?$APPLICATION->IncludeComponent("bitrix:form.result.new", "quality_service", Array(
                    "CACHE_TIME" => "3600",	// Время кеширования (сек.)
                    "CACHE_TYPE" => "A",	// Тип кеширования
                    "CHAIN_ITEM_LINK" => "",	// Ссылка на дополнительном пункте в навигационной цепочке
                    "CHAIN_ITEM_TEXT" => "",	// Название дополнительного пункта в навигационной цепочке
                    "EDIT_URL" => "",	// Страница редактирования результата
                    "IGNORE_CUSTOM_TEMPLATE" => "N",	// Игнорировать свой шаблон
                    "LIST_URL" => "",	// Страница со списком результатов
                    "SEF_MODE" => "N",	// Включить поддержку ЧПУ
                    "SUCCESS_URL" => "",	// Страница с сообщением об успешной отправке
                    "USE_EXTENDED_ERRORS" => "N",	// Использовать расширенный вывод сообщений об ошибках
                    "VARIABLE_ALIASES" => array(
                        "RESULT_ID" => "RESULT_ID",
                        "WEB_FORM_ID" => "WEB_FORM_ID",
                    ),
                    "WEB_FORM_ID" => "6",	// ID веб-формы
                ),
                    false
                );?>
            </div>
        </div>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
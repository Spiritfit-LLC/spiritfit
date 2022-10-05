<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_admin.php");
/**
 * @var CMain $APPLICATION
 */
?>
<?$APPLICATION->IncludeComponent(
    "bitrix:rest.hook",
    ".default",
    Array(
        "COMPONENT_TEMPLATE" => ".default",
        "SEF_FOLDER" => "/hook/",
        "SEF_MODE" => "Y",
        "SEF_URL_TEMPLATES" => [
            "list"=>"",
            "event_list"=>"event/",
            "event_edit"=>"event/#id#/",
            "ap_list"=>"ap/",
            "ap_edit"=>"ap/#id#/",
        ],
        "CACHE_TYPE" => "N",
        "CACHE_TIME" => "36000000",
        "CACHE_GROUPS" => "N"
    )
);?>
<style>
    .webform-button.webform-button-create{
        background: #025ea1;
        padding: 10px 15px;
        text-align: center;
        color:#fff;
        cursor: pointer;
    }
</style>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
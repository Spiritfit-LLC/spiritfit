<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<? if(!empty($BLOCKS["BLOCK5_ACTIVE"])) { ?>
    <?
    $APPLICATION->IncludeComponent(
        "outcode.quiz:form",
        "",
        Array(
            "API_PATH" => "",
            "PERSONAL_PATH" => "/personal/",
            "SHOW_RESULT_ON_TIME" => "00:00",
            "SHOW_RESULT_AFTER" => "1800",
            "SHOW_RESULTS_ON_LAST_ALWAYS" => (!empty($BLOCKS["BLOCK5_DATE"]) && strtotime($BLOCKS["BLOCK5_DATE"]) < time()) ? "Y" : "N",
            "CACHE_GROUPS" => "N",
            "CACHE_TIME" => "3600",
            "CACHE_TYPE" => "A",
            "BLOCK_TITLE" => $BLOCKS["BLOCK5_TITLE"],
            "BLOCK_DESCRIPTION" => $BLOCKS["BLOCK5_DESCRIPTION"],
            "BLOCK_VIDEO_TITLE" => $BLOCKS["BLOCK5_VIDEO_TITLE"],
            "BLOCK_VIDEO_LINK" => $BLOCKS["BLOCK5_VIDEO_LINK"]
        )
    );
    ?>
<? } ?>
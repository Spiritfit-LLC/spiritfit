<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<? if(!empty($BLOCKS["BLOCK5_ACTIVE"])) { ?>
    <div class="b-participation blockitem">
        <div class="content-center">
            <div class="landing-title"><?=$BLOCKS["BLOCK5_TITLE"]?></div>
            <div class="b-play__wrapper">
                <?
                    $APPLICATION->IncludeComponent(
                        "outcode.quiz:form",
                        "",
                        Array(
                            "API_PATH" => "",
                            "PERSONAL_PATH" => "/personal/",
                            "SHOW_RESULT_ON_TIME" => "22:00",
                            "SHOW_RESULT_AFTER" => "1800",
                            "SHOW_RESULTS_ON_LAST_ALWAYS" => (!empty($BLOCKS["BLOCK5_DATE"]) && strtotime($BLOCKS["BLOCK5_DATE"]) < time()) ? "Y" : "N",
                            "CACHE_GROUPS" => "N",
                            "CACHE_TIME" => "86400",
                            "CACHE_TYPE" => "A",
                        )
                    );
                ?>
            </div>
        </div>
    </div>
<? } ?>
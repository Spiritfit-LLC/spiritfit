<?php

foreach($arResult["ITEMS"] as $key => $arItem) {
    $arResult["ITEMS"][$key]["PROPERTIES"]["ICON_MAIN"]["RESIZE"] = CFile::ResizeImageGet($arItem["PROPERTIES"]["ICON_MAIN"]["VALUE"], array("width"=>"200", "height"=>"120", BX_RESIZE_IMAGE_PROPORTIONAL));
}
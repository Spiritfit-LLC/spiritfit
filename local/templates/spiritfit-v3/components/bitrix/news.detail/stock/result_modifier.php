<?php
global $APPLICATION;
$APPLICATION->AddViewContent('inhead', $arResult['DETAIL_PICTURE']['SRC']);

$APPLICATION->SetTitle($arResult["NAME"]);
if (empty($arResult["META_ELEMENT_TITLE"])){
    $title = $arResult["NAME"]." - Акции в Spirit Fitness";
    $APPLICATION->SetPageProperty("title", $title);
}
if (empty($arResult["META_ELEMENT_DESCRIPTION"])){
    $description = $arResult["NAME"].". Акции и скидки на абонемент в фитнес-клуб в Москве &#128165; Бесплатная пробная тренировка &#128293; Запишитесь прямо сейчас!";
    $APPLICATION->SetPageProperty("description", $description);
}
?>
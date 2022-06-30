<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global \CMain $APPLICATION */
/** @global \CUser $USER */
/** @global \CDatabase $DB */
/** @var CBitrixComponentTemplate $this */

?>

<script>
    var componentName=<?=CUtil::PhpToJSObject($arResult['COMPONENT_NAME'])?>;
    var UserID=<?=CUtil::PhpToJSObject($arResult['USER']['ID'])?>;
</script>
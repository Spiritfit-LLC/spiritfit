<?
    if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<div id="form_interview">
	<? if(!empty($arResult["ERROR"])) { ?>
		<div class="form__error-text"><?=str_replace(array(":", "?"), array("", ""), $arResult["ERROR"])?></div>
	<? } ?>
	<div class="success"><?=$arParams["THANKS"]?></div>
</div>
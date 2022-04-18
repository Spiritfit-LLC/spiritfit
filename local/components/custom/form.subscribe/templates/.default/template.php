<?
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<div class="subscribe-form-table">
	<div class="subscribe-form-table__cell">
		<img src="<?=$templateFolder?>/images/subscribe.jpg" alt="<?=GetMessage("FORM_SUBSCRIBE_SEND")?>" title="<?=GetMessage("FORM_SUBSCRIBE_SEND")?>">
	</div>
	<div id="<?=$arResult["COMPONENT_ID"]?>" class="subscribe-form-table__cell subscribe-form-wrapper">
		<div class="subscribe-form-title"><?=GetMessage("FORM_SUBSCRIBE_TITLE")?></div>
		<form class="subscribe-form" action="<?=$APPLICATION->GetCurPage(false)?>" method="POST" enctype="multipart/form-data">
			<? if( !empty($arResult["RESPONSE"]["ERROR"]) ) { ?><span class="success"></span><? } ?>
			<? if( !empty($arResult["RESPONSE"]["MESSAGE"]) ) { ?>
				<div class="popup popup--call form-error-modal" style="display: block;">
					<div class="popup__bg"></div>
					<div class="popup__window">
						<div class="popup__close">
							<div></div>
							<div></div>
						</div>
						<div class="popup__success"><?=$arResult["RESPONSE"]["MESSAGE"]?></div>
					</div>
				</div>
			<? } ?>
			<?=getClientParams($arResult["WEB_FORM_ID"]);?>
			<input type="hidden" name="COMPONENT_ID" value="<?=$arResult["COMPONENT_ID"]?>">
			<div class="fields">
				<input type="text" name="email" value="<?=$arResult["EMAIL"]?>" required="required" autocomplete="off" placeholder="<?=GetMessage("FORM_SUBSCRIBE_PLACEHOLDER")?>">
				<input class="button" type="submit" value="<?=GetMessage("FORM_SUBSCRIBE_SEND")?>">
			</div>
			<div class="subscribe-form-footer">
				<label class="input-label">
            		<input class="b-input input--checkbox" type="checkbox" name="agreement" value="Y" data-required="required" checked="checked" />
            		<div class="input-label__text"><?=GetMessage("FORM_SUBSCRIBE_AGREEMENT")?></div>
        		</label>
			</div>
		</form>
	</div>
</div>

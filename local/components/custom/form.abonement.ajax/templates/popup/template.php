<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$templateName=\Bitrix\Main\Component\ParameterSigner::signParameters($arResult["SALT"], $this->GetFolder());
?>

<div class="form-abonement__container">
    <form class="get-abonement__popup" data-componentName="<?=$arResult["COMPONENT_NAME"]?>" data-action="<?=$arResult["AJAX_ACTION"]?>" data-path="<?=$templateName?>">
        <?=getClientParams($arResult['WEB_FORM_ID']);?>
        <input type="hidden" name="ELEMENT_CODE" value="<?=$arResult["ABONEMENT_CODE"]?>">
        <input type="hidden" name="FORM_TYPE" value="<?=$arResult["FORM_TYPE"]?>">
        <input type="hidden" name="WEB_FORM_ID" value="<?=$arResult['WEB_FORM_ID']?>">

        <div class="form-submit-result-text"></div>

        <?foreach($arResult["FORM_FIELDS"]["FIELDS"] as $KEY=>$FIELD):?>
            <div class="form-field-item">
                <?if($FIELD["TYPE"]=="SELECT"):?>
                    <select data-placeholder="<?=$FIELD["PLACEHOLDER"]?>" data-necessary="" name="<?=$FIELD["NAME"]?>" <?if($FIELD["REQUIRED"]) echo "required";?>>
                        <option></option>
                        <? foreach ($FIELD['ITEMS'] as $key => $arItem): ?>
                            <option value="<?= $arItem["VALUE"] ?>"><?= $arItem["STRING"] ?></option>
                        <? endforeach; ?>
                    </select>
                <?elseif($FIELD["TYPE"]=="checkbox"):?>
                    <label class="b-checkbox">
                        <input class="b-checkbox__input" type="<?=$FIELD["TYPE"]?>" value="<?=$FIELD["VALUE"]?>" data-necessary="" name="<?=$FIELD["NAME"]?>[]" <?if($FIELD["REQUIRED"]) echo "required";?>>
                        <span class="b-checkbox__text"><?=$FIELD['PLACEHOLDER']?></span>
                    </label>
                <?else:?>
                    <label class="form-field-item__label"><?=$FIELD["PLACEHOLDER"]?></label>
                    <input class="form-field-item__input" type="<?=$FIELD["TYPE"]?>" data-necessary="" name="<?=$FIELD["NAME"]?>" <?if($FIELD["REQUIRED"]) echo "required";?>/>
                <?endif;?>
            </div>
        <?endforeach;?>

        <div class="form-field-item" style="text-align: center">
            <input type="submit" class="get-abonement__popup-submit button-outline" value="отправить">
            <div class="escapingBallG-animation">
                <div id="escapingBall_1" class="escapingBallG"></div>
            </div>
        </div>
    </form>
</div>

<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$APPLICATION->SetPageProperty("description", "");
$APPLICATION->SetPageProperty("title", $arResult["TITLE"]);
?>
<script>
    var params=<?=\Bitrix\Main\Web\Json::encode(['signedParameters'=>$this->getComponent()->getSignedParameters()])?>;
</script>

<? if( !empty($arResult["HEADER_IMAGE"]) ):?>
    <div class="b-main blockitem">
        <div class="content-center">
            <div class="b-main__banner" style="background-image: url(<?=$arResult["HEADER_IMAGE"]?>);">
                <div class="b-main__banner-content">
                    <div class="b-main__banner-title">
                        <?=$arResult["TITLE"]?>
                    </div>
                    <?=htmlspecialcharsback($arResult["HEADER_DESCRIPTION"])?>
                    <div class="b-main__banner-button is-hide-mobile">
                        <? if(!empty($arResult["HEADER_BUTTON"]) ) { ?>
                            <a class="button " onclick="go_next_question()"><?=$arResult["HEADER_BUTTON"]?></a>
                        <? } ?>
                    </div>
                </div>
                <div class="b-main__banner-button is-hide-desktop" style="margin-top: auto;text-align: center;width: 100%;">
                    <? if(!empty($arResult["HEADER_BUTTON"]) ) { ?>
                        <a class="button" onclick="go_next_question()"><?=$arResult["HEADER_BUTTON"]?></a>
                    <? } ?>
                </div>
            </div>
        </div>
    </div>
<?endif;?>

<div class="content-center">
    <div class="interview-form-box">
        <form class="b-interview-form" data-componentname="<?=$arResult["COMPONENT_NAME"]?>">
            <?for ($i=0; $i<count($arResult["QUESTIONS"]); $i++):?>
            <?$arQuestion=$arResult["QUESTIONS"][$i]?>
                <div class="b-interview__question <?if ($arQuestion["REQUIRED"] && empty($arQuestion["REQUIRED_FROM_ID"])) echo "required"?>  is-hide"
                     <?if (!empty($arQuestion["REQUIRED_FROM_ID"])):?>data-required-from-id="<?=$arQuestion["REQUIRED_FROM_ID"]?>"<?endif;?>
                     <?if (!empty($arQuestion["REQUIRED_FROM_VAL"])):?>data-required-from-val="<?=$arQuestion["REQUIRED_FROM_VAL"]?>"<?endif;?>>
                    <div class="b-interview__question-content">
                        <h3 class="b-question__title"><?=$arQuestion["TITLE"]?><?if($arQuestion["REQUIRED"]){echo "<span class='starrequired'>*</span>";}?></h3>
                        <?switch ($arQuestion["TYPE"]){
                            case "radio"://radio?>
                                <div class="b-question__answers radio-item_question">
                                <?
                                $className="radio-item";
                                break;
                            ?>
                            <?
                            case "checkbox"://checkbox?>
                                <div class="b-question__answers checkbox-item_question">
                                <?
                                $className="checkbox-item";
                                break;
                                ?>
                            <?case "select"://select?>
                                <div class="b-question__answers select-item_question">
                                <select name="<?=$arQuestion["NAME"]?>" class="select-item">
                                <option value="" disabled>Выберите вариант ответа</option>
                                <?
                                $className= "select-item";
                                break;
                                ?>
                            <?case "text"://text field?>
                                <div class="b-question__answers text-item_question">
                                <?
                                $className= "text-item";
                                break;
                                ?>
                            <?case "textarea":?>
                                <div class="b-question__answers textarea-item_question">
                                <?
                                $className="textarea-item";
                                break?>
                            <?case "stars"://Звезды?>
                                <div class="b-question__answers stars-item_question">
                                <?
                                $className="stars-item";
                                break?>
                        <?}?>
                            <?foreach ($arQuestion["ANSWERS"]["VALUE"] as $index=>$arAnswer):?>
                                <?$value=$arQuestion["ANSWERS"]["DESCRIPTION"][$index];?>
                                <?switch ($arQuestion["TYPE"]){
                                    case "stars":
                                    case "radio":?>
                                        <div class="b-question__answer-item <?=$className?>">
                                            <input type="radio" name="<?=$arQuestion["NAME"]?>"
                                                   class="<?=$className?>"
                                                   id="b-question_radio_<?=$arQuestion["ID"]?>_<?=$arQuestion["ANSWERS"]["ID"]?>_<?=$index?>"
                                                   value="<?=$value?>"
                                                   data-required="<?=$arQuestion["ID"]?>"/>
                                            <label for="b-question_radio_<?=$arQuestion["ID"]?>_<?=$arQuestion["ANSWERS"]["ID"]?>_<?=$index?>"><?=$arQuestion["TYPE"]=="stars"?"":$arAnswer?></label>
                                        </div>
                                        <?break?>
                                    <? case "checkbox":?>
                                        <div class="b-question__answer-item <?=$className?>">
                                            <label class="b-checkbox">
                                                <input type="checkbox"
                                                       name="<?=$arQuestion["NAME"]?>[]"
                                                       value="<?=$value?>"
                                                       class="b-checkbox__input"
                                                       data-required="<?=$arQuestion["ID"]?>_<?=$value?>"/>
                                                <span class="b-checkbox__text"><?=$arAnswer?></span>
                                            </label>
                                        </div>
                                        <?break;?>
                                    <? case "select":?>
                                        <option value="<?=$value?>"><?=$arAnswer?></option>
                                        <?break;?>
                                    <?case "text":?>
                                        <div class="b-question__answer-item <?=$className?>">
                                            <?if (!empty($value)):?>
                                            <span class="b-question__answer-placeholder"><?if (trim($arAnswer) <> ''):?><?=$arAnswer?><?endif?></span>
                                            <?endif;?>
                                            <input type="text"
                                                   name="<?=$arQuestion["NAME"]?>"
                                                   class="<?=$className?>"/>
                                        </div>
                                        <?break?>
                                <?}?>
                            <?endforeach;?>
                        <?
                        switch ($arQuestion["TYPE"]){
                            default:
                            echo "</div>";
                            break;
                        case "select":
                            echo "</select></div>";
                            break;
                        }?>
                    </div>
                    <div class="b-question__go-next">
                        <?if ($i==count($arResult["QUESTIONS"])-1):?>
                        <a class="button <?if ($arQuestion["REQUIRED"]) echo "disabled"?>" onclick="submit_form(this)">Завершить</a>
                        <?else:?>
                        <a class="button <?if ($arQuestion["REQUIRED"] || $arQuestion["REQUIRED_FROM_ID"]) echo "disabled"?>" onclick="go_next_question(this)">Далее</a>
                        <?endif;?>
                    </div>
                </div>
            <?endfor;?>
        </form>
    </div>
</div>

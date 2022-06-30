<?php

if (!empty($arResult['ERROR'])){
    echo '<div class="page-error-text">'.$arResult['ERROR'].'</div>';
    return;
}?>

<div class="personalcard-block">
    <div class="personalcard-block__left personalcard-column">
        <div style="float: right;display: flex;flex-direction: column;align-items: center;width: 100%;">
            <div class="personalcard-photo">
                <img src="<?=$arResult['PERSONAL_PHOTO']?>" height="100%" width="100%">
            </div>
            <div class="personalcard-head_info">
                <div class="personalcard-name">
                    <?=$arResult['USER_NAME']?>
                </div>
                <div class="personalcard-position">
                    <?=$arResult['POSITION']?>
                </div>
            </div>
        </div>
    </div>
    <div class="personalcard-block__right personalcard-column">
        <div class="personalcard-info">
            <?=$arResult['INFO']?>
        </div>
        <div class="personalcard-mainfields">
            <?foreach($arResult['MAIN_FIELDS'] as $FIELD):?>
            <div class="mainfield-item">
                <div class="mainfield-item__icon-block"><?php echo file_get_contents($FIELD['ICON']);?></div>
                <div class="mainfield-item__text-block"><span><?=$FIELD['TEXT']?></span></div>
            </div>
            <?endforeach;?>
        </div>
        <button class="personalcard-savebtn">СОХРАНИТЬ КОНТАКТ</button>
        <div class="personalcard-social">
            <?foreach ($arResult['SOCIALS'] as $SOCIAL):?>
                <a class="social-item" href="<?=$SOCIAL['LINK']?>" target="_blank">
                    <div class="social-item__icon">
                        <?php echo file_get_contents($SOCIAL['ICON']);?>
                    </div>
                </a>
            <?endforeach;?>
        </div>
    </div>
</div>


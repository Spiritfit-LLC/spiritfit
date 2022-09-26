<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<? if(!empty($BLOCKS["BLOCK3_LIST"])) { ?>
    <div class="b-participation blockitem">
        <div class="content-center">
            <div class="landing-title"><?=$BLOCKS["BLOCK3_TITLE"]?></div>
            <div class="b-treners__wrapper">
                <? foreach($BLOCKS["BLOCK3_LIST"] as $participation) { ?>
                    <div class="b-treners__item b-participation__item">
                        <img src="<?=$participation["SRC"]?>" alt="<?=strip_tags($BLOCKS["BLOCK3_TITLE"])?>" title="<?=strip_tags($participation["DESCRIPTION"])?>">
                        <?=$participation["DESCRIPTION"]?>
                    </div>
                <? } ?>
            </div>
        </div>
    </div>
<? } ?>
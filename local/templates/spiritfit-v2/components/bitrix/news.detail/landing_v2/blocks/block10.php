<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<? if(!empty($BLOCKS["BLOCK10_ACTIVE"])) { ?>
    <? $imageTitle = strip_tags($BLOCKS["BLOCK10_TITLE"]); ?>
    <div class="b-partners blockitem">
        <div class="content-center">
            <? if(!empty($BLOCKS["BLOCK10_TITLE"])) { ?>
                <div class="landing-title">
                    <?=$BLOCKS["BLOCK10_TITLE"]?>
                </div>
            <? } ?>
            <div class="partners">
                <? foreach($BLOCKS["BLOCK10_IMAGES"] as $item) { ?>
                    <div class="partner">
                        <? if(!empty($item["DESCRIPTION"])) { ?>
                            <a href="<?=$item["DESCRIPTION"]?>"><img src="<?=$item["SRC"]?>" alt="<?=$imageTitle?>" title="<?=$imageTitle?>"></a>
                        <? } else { ?>
                            <img src="<?=$item["SRC"]?>" alt="<?=$imageTitle?>" title="<?=$imageTitle?>">
                        <? } ?>
                    </div>
                <? } ?>
            </div>
        </div>
    </div>
<? } ?>
<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<? if(!empty($BLOCKS["BLOCK4_ACTIVE"])) { ?>
    <div class="b-fond blockitem">
        <div class="content-center">
            <div class="b-fond__table">
                <div class="left">
                    <?=$BLOCKS["BLOCK4_TITLE"]?>
                </div>
                <div class="center">
                    <ul class="b-fond__items">
                        <? foreach($BLOCKS["BLOCK4_LIST"] as $item) { ?>
                            <li class="b-fond__item">
                                <svg width="26" height="20" viewBox="0 0 26 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 7.28571L10.8261 15L23 3" stroke="white" stroke-width="6"/>
                                </svg>
                                <?=$item?>
                            </li>
                        <? } ?>
                    </ul>
                </div>
                <div class="right">
                    <? if( !empty($BLOCKS["BLOCK4_BUTTON_LINK"]) && !empty($BLOCKS["BLOCK4_BUTTON"]) ) { ?>
                        <a class="button" href="<?=$BLOCKS["BLOCK4_BUTTON_LINK"]?>"><span><?=$BLOCKS["BLOCK4_BUTTON"]?></span></a>
                    <? } ?>
                </div>
            </div>
        </div>
    </div>
<? } ?>
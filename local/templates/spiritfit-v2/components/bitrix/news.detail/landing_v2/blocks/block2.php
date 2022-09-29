<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<? if( !empty($BLOCKS["BLOCK2_ACTIVE"]) ) { ?>
    <div class="b-block2 blockitem">
        <div class="content-center">
            <div class="b-block2__table" style="background-image: url(<?=$BLOCKS["BLOCK2_IMAGE"]["SRC"]?>)">
                <div class="left">
                    <div class="b-block2__table-title">
                        <?=$BLOCKS["BLOCK2_TITLE1"]?>
                    </div>
                    <ul class="b-block2__table-items">
                        <? foreach( $BLOCKS["BLOCK2_LIST1"] as $item ) { ?>
                            <li class="b-block2__table-item">... <?= $item?></li>
                        <? } ?>
                    </ul>
                </div>
                <div class="right">
                    <img src="<?=$BLOCKS["BLOCK2_IMAGE"]["SRC"]?>" alt="<?=strip_tags($BLOCKS["BLOCK2_TITLE2"])?>" title="<?=strip_tags($BLOCKS["BLOCK2_TITLE2"])?>">
                    <div class="b-block2__table-title">
                        <?=$BLOCKS["BLOCK2_TITLE2"]?>
                    </div>
                    <ul class="b-block2__table-items">
                        <? foreach( $BLOCKS["BLOCK2_LIST2"] as $item ) { ?>
                            <li class="b-block2__table-item">
                                <svg width="26" height="20" viewBox="0 0 26 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 7.28571L10.8261 15L23 3" stroke="url(#paint0_linear_424_157)" stroke-width="6"/>
                                    <defs>
                                        <linearGradient id="paint0_linear_424_157" x1="-10.913" y1="15" x2="28.7097" y2="-12.9631" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#E23834"/>
                                            <stop offset="1" stop-color="#7B27F0"/>
                                        </linearGradient>
                                    </defs>
                                </svg>
                                <?= $item?>
                            </li>
                        <? } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<? } ?>

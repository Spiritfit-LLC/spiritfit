<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<? if(!empty($BLOCKS["BLOCK5_ACTIVE"])) { ?>
    <div class="b-want blockitem">
        <div class="content-center">
            <div class="b-want-banner">
                <div class="left">
                    <div class="landing-title"><?=$BLOCKS["BLOCK5_TITLE"]?></div>
                    <div class="b-want-banner__items">
                        <? foreach($BLOCKS["BLOCK5_LIST"] as $key => $item) { ?>
                            <div class="b-want-banner__item"><span class="num"><?=($key+1)?>.</span><?=$item?></div>
                        <? } ?>
                    </div>
                    <? if( !empty($BLOCKS["BLOCK5_BUTTON_LINK"]) && !empty($BLOCKS["BLOCK5_BUTTON"]) ) { ?>
                        <a class="button first" href="<?=$BLOCKS["BLOCK5_BUTTON_LINK"]?>"><?=$BLOCKS["BLOCK5_BUTTON"]?></a>
                    <? } ?>
                </div>
                <div class="right">
                    <? if(!empty($BLOCKS["BLOCK5_VIDEO"])) { ?>
                        <div class="b-want__video autoplay-video">
                            <video>
                                <source src="<?=$BLOCKS["BLOCK5_VIDEO"]["SRC"]?>" type="video/<?=$BLOCKS["BLOCK5_VIDEO"]["TYPE"]?>">
                            </video>
                            <svg width="97" height="98" viewBox="0 0 97 98" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <ellipse cx="48.4998" cy="49" rx="48.4622" ry="49" fill="white" fill-opacity="0.77"/>
                                <path d="M70.2583 49L37.6205 68.0526L37.6205 29.9474L70.2583 49Z" fill="url(#paint0_linear_428_184)"/>
                                <defs>
                                    <linearGradient id="paint0_linear_428_184" x1="48.4998" y1="27" x2="48.4998" y2="71" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#DC373E"/>
                                        <stop offset="1" stop-color="#7D27EC"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
                    <? } ?>
                    <? if( !empty($BLOCKS["BLOCK5_BUTTON_LINK"]) && !empty($BLOCKS["BLOCK5_BUTTON"]) ) { ?>
                        <a class="button second" href="<?=$BLOCKS["BLOCK5_BUTTON_LINK"]?>"><?=$BLOCKS["BLOCK5_BUTTON"]?></a>
                    <? } ?>
                </div>
            </div>
        </div>
    </div>
<? } ?>

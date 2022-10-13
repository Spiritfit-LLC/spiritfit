<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<? if( !empty($BLOCKS["HEADER_IMAGE"]["SRC"]) ) { ?>
    <div class="b-main blockitem">
        <div class="content-center">
            <div class="b-main__banner" style="background-image: url(<?=$BLOCKS["HEADER_IMAGE"]["SRC"]?>);">
                <div class="b-main__banner-content">
                    <?=$BLOCKS["HEADER_DESCRIPTION"]?>
                    <div class="b-main__banner-button">
                        <? if( !empty($BLOCKS["HEADER_BUTTON_LINK"]) && !empty($BLOCKS["HEADER_BUTTON"]) ) { ?>
                            <a class="button" href="<?=$BLOCKS["HEADER_BUTTON_LINK"]?>"><?=$BLOCKS["HEADER_BUTTON"]?></a>
                        <? } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<? } ?>

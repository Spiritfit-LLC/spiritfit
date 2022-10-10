<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="contacts-block">
    <?foreach ($arResult['ITEMS'] as $ITEM):?>
        <div class="contact-item">
            <div class="contact-block">
                <div class="contact-line"></div>
                <div class="contact-item__title"><?=$ITEM['PROPERTIES']['TITLE']['VALUE']?></div>
                <div class="contact-item__body">
                    <?if (!empty($ITEM['PROPERTIES']['ADDRESS']['VALUE'])):?>
                    <div class="contact-item__address contact-body__block"><?=$ITEM['PROPERTIES']['ADDRESS']['VALUE']?></div>
                    <?endif;?>
                    <?if (!empty($ITEM['PROPERTIES']['PHONE']['VALUE'])):?>
                    <div class="contact-item__body-phones contact-body__block">
                        <?foreach($ITEM['PROPERTIES']['PHONE']['VALUE'] as $phone):?>
                            <div class="contact-item__phone"><a href="tel:<?=$phone?>"><?=$phone?></a></div>
                        <?endforeach;?>
                    </div>
                    <?endif;?>
                    <?if (!empty($ITEM['PROPERTIES']['EMAIL']['VALUE'])):?>
                    <div class="contact-item__body-emails contact-body__block">
                        <?foreach($ITEM['PROPERTIES']['EMAIL']['VALUE'] as $email):?>
                            <div class="contact-item__email"><?=$email?></div>
                        <?endforeach;?>
                    </div>
                    <?endif;?>
                </div>
            </div>
        </div>
    <?endforeach;?>
</div>

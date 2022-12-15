<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<script>
    var params=<?=\Bitrix\Main\Web\Json::encode(['signedParameters'=>$this->getComponent()->getSignedParameters()])?>;
</script>

<div class="partner-promocode__container">
    <?foreach ($arResult["PROMOCODE_LIST"] as $PROMOCODE_ITEM):?>
        <div class="partner-promocode__item <?if (!empty($PROMOCODE_ITEM["qr"])) echo "qr"?>">
            <div class="partner-promocode__title">
                <?=$PROMOCODE_ITEM["name"]?>
                <div class="partner-promocode__secription">
                    <?=$PROMOCODE_ITEM["description"]?>
                </div>
            </div>
            <?if (empty($PROMOCODE_ITEM["qr"])):?>
                <div class="partner-promocode__code"
                     <?if (!$PROMOCODE_ITEM["link"]):?>onclick="copyPromocode('<?=$PROMOCODE_ITEM["code"]?>')<?endif?>">
                     <?if ($PROMOCODE_ITEM["link"]):?>
                        <a href="<?=$PROMOCODE_ITEM["url"]?>" class="promocode-link"><?=$PROMOCODE_ITEM["code"]?></a>
                     <?else:?>
                         <?=$PROMOCODE_ITEM["code"]?>
                     <?endif?>
                </div>
            <?else:?>
                <div class="partner-promocode__code">
                    <div class="partner-promocode__qr-code">
                        <a href="<?=$PROMOCODE_ITEM["qr"]?>" class="promocode-qr-link">
                            <img src="<?=$PROMOCODE_ITEM["qr"]?>">
                        </a>
                    </div>
                </div>
            <?endif;?>
        </div>
    <?endforeach;?>
</div>
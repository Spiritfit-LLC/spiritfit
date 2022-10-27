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
                <div class="partners-wrapper">
                    <div class="partners-slider">
                        <?
                        $objects=[];
                        $filter = ['ID' => $BLOCKS["BLOCK10_PARTNERS"], 'ACTIVE'=>'Y'];
                        $order = ['SORT' => 'ASC'];

                        $rows = CIBlockElement::GetList($order, $filter);
                        while ($row = $rows->fetch()) {
                            $row['PROPERTIES'] = [];
                            $objects[$row['ID']] =& $row;
                            $filter['IBLOCK_ID']=$row['IBLOCK_ID'];
                            unset($row);
                        }


                        CIBlockElement::GetPropertyValuesArray($objects, $filter['IBLOCK_ID'], $filter);
                        unset($rows, $filter, $order);
                        ?>
                        <?foreach ($objects as $id=>$element):?>
                            <?
                            $imageSrc = "";
                            if( !empty($element["PROPERTIES"]["POSTER"]["VALUE"])) {
                                $imageSrc = CFile::ResizeImageGet($element["PROPERTIES"]["POSTER"]["VALUE"], array('width' => 600, 'height' => 900), BX_RESIZE_IMAGE_EXACT)["src"];
                            }
                            ?>
                            <div class="b-cards-slider__item v3-abonement">
                                <div class="b-twoside-card">
                                    <div class="b-twoside-card__inner">
                                        <div class="b-twoside-card__content" style="background-image: url(<?=$imageSrc?>);">
                                        </div>
                                        <div class="b-twoside-card__hidden-content">
                                            <div class="b-twoside-card__description">
                                                <?=$element["PROPERTIES"]["DESCRIPTION"]["VALUE"]["TEXT"]?>
                                            </div>
                                            <a class="button" href="<?=$element["PROPERTIES"]["BTN"]["DESCRIPTION"]?>"><?=$element["PROPERTIES"]["BTN"]["VALUE"]?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?endforeach;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<? } ?>
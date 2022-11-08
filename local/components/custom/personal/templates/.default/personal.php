<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<?foreach ($arResult['LK_FIELDS']['JS'] as $js):?>
    <script src="<?=$js?>?version=<?=uniqid()?>"></script>
<?endforeach;?>
<?foreach ($arResult['LK_FIELDS']['CSS'] as $css):?>
    <link href="<?=$css?>?version=<?=uniqid()?>" type="text/css" rel="stylesheet">
<?endforeach;?>

<div class="personal-profile-block">
    <div class="personal-profile__left-block">
        <div class="personal-profile__user">
            <div>
                <div class="personal-profile__user-photo">
                    <img src="<?=$arResult['LK_FIELDS']['HEAD']['PERSONAL_PHOTO']?>" height="100%" width="100%">
                    <form class="personal-profile__user-refresh-photo" method="post" enctype="multipart/form-data" data-componentName="<?=$arResult['COMPONENT_NAME']?>">
                        <input type="hidden" name="ACTION" value="updatePhoto">
                        <input type="hidden" name="old-photo-id" value="<?=$arResult['LK_FIELDS']['OLD_PHOTO_ID']?>">
                        <input class="personal-profile__user-refresh-photo-file-input" type="file" name="new-photo-file" accept="image/*">
                        <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/refresh.svg');?>
                    </form>
                </div>
                <div class="personal-profile__user-result"></div>
            </div>
            <div class="personal-profile__user-head-items">
                <?foreach ($arResult['LK_FIELDS']['HEAD']['FIELDS'] as $FIELD):?>
                    <div class="personal-profile__user-head-item">
                        <?if (!empty($FIELD['SHOW_PLACEHOLDER'])):?>
                            <span class="user-head-item-placeholder"><?=$FIELD['PLACEHOLDER']?></span>
                        <?endif;?>
                        <span class="user-head-item-value" data-code="<?=$FIELD['USER_FIELD_CODE']?>"><?=$FIELD['VALUE']?></span>
                    </div>
                <?endforeach;?>
            </div>
        </div>
        <div style="position:relative;margin-top: 50px;">
            <div class="personal-profile__tabs">
                <?
                foreach ($arResult['LK_FIELDS']['SECTIONS'] as $SECTION):?>
                    <div class="personal-profile__tab-item <?if ($SECTION['ACTIVE']) echo 'active'; elseif($arResult["ACTIVE_SECTION"]==$SECTION['ID']) echo 'active';?>" data-id="<?=$SECTION['ID']?>">
                        <div class="tab-item__icon">
                            <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"].$SECTION['ICON']);?>
                        </div>
                        <div class="tab-item__name">
                            <?=$SECTION['NAME']?>
                        </div>
                        <?if(!empty($SECTION["NOTIFICATIONS"])):?>
                            <div class="tab-item__notification" data-id="<?=$SECTION["ID"]?>">
                                <div class="tab-item__notification-count">
                                    <?=$SECTION["NOTIFICATIONS"];?>
                                </div>
                            </div>
                        <?endif;?>
                    </div>
                <?
                endforeach;
                ?>
                <div class="personal-profile__tab-item profile-exit-btn"  data-componentName="<?=$arResult['COMPONENT_NAME']?>">
                    <div class="tab-item__icon">
                        <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/exit-btn.svg');?>
                    </div>
                    <div class="tab-item__name">
                        Выйти
                    </div>
                </div>
            </div>
            <div class="show-all-section-icon is-hide-desktop">
                <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/arrow-down.svg');?>
            </div>
        </div>
        <div class="left-block-border -right"></div>

    </div>
    <div class="personal-profile__center-block">
        <!--НА ВРЕМЯ ТРАНСФОРМАЦИИ-->
        <style>
            .personal-transformation__container,
            .personal-transformation__choose-leader{
                background-color: #000000d4;
                padding: 20px;
            }
            .personal-transformation__container{
                border-top-left-radius: 10px;
                border-top-right-radius: 10px;
                background-size: contain;
                background-repeat: no-repeat;
            }
            .personal-transformation__choose-leader{
                border-bottom-left-radius: 10px;
                border-bottom-right-radius: 10px;
                border-top: 2px solid #505050;
            }
            .personal-transformation__content.padding-left {
                padding-left: 120px;
            }
            .personal-transformation__title {
                font-size: 18px;
                font-weight: 500;
            }
            .personal-transformation__name {
                font-weight: 900;
                font-size: 35px;
                background: linear-gradient(90deg, #E03838 3.26%, #7B27EF 98.07%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                margin-top: 10px;
            }
            .small-btn {
                border: none;
                border-radius: 5px;
                color: white;
                padding: 10px 20px;
                font-size: 14px;
                background: linear-gradient(90deg, #E43932 3.26%, #7827F6 98.07%);
                font-weight: 500;
                min-width: 120px;
                text-align: center;
            }
            .small-btn:hover{
                color:white;
            }
            .personal-transformation__btns {
                padding: 20px 0;
            }
            .choose-trainer__trigger {
                color: #7e7e7e;
                font-weight: 700;
                display: flex;
                cursor: pointer;
                align-items: center;
                justify-content: space-between;
            }
            .choose-leader__leader-item {
                padding: 10px 15px;
                border: 1px solid;
                border-radius: 5px;
                border-color: #505050;
                margin: 10px 0;
                font-weight: 500;
            }
            .choose-leader__leader-item input[type="radio"]{
                width: 0;
                height: 0;
                position: absolute;
            }
            .choose-leader__leader-item label{
                display: block;
                width: 100%;
                cursor: pointer;
            }
            .choose-leader__submit {
                text-align: end;
                padding: 10px 0 0;
            }
            .choose-leader__arrow-down {
                width: 20px;
                height: 20px;
                display: inline-block;
                transition: 0.3s;
                transform: rotate(0deg);
            }
            .choose-leader__arrow-down svg{
                width: 100%;
                height: 100%;
                fill: #7e7e7e;
            }
            .choose-trainer__trigger.active .choose-leader__arrow-down{
                transform: rotate(180deg);
            }
            .choose-leader__leaders {
                max-height: 170px;
                overflow-y: auto;
                overflow-x: hidden;
                padding-right: 5px;
                transition: 0.3s;
            }
            .choose-leader__leaders::-webkit-scrollbar {
                width: 5px;
                height: 3px;
                cursor: default;
            }
            .choose-leader__leaders::-webkit-scrollbar-track {
                cursor: default;
                background: #606060!important;
                border-radius: 5px;
                box-shadow: none
            }
            .choose-leader__leaders::-webkit-scrollbar-thumb {
                box-shadow: none;
                cursor: default;
            }
            .choose-leader__leader-item.select {
                background: linear-gradient(90deg, #E43932 3.26%, #7827F6 98.07%);
                color: white;
            }
            .personal-spirit-transformation {
                margin-bottom: 50px;
            }
            @media screen and (max-width: 768px) {
                .personal-spirit-transformation {
                    margin: 50px 0;
                }
                .personal-transformation__title {
                    font-size: 14px;
                }
                .personal-transformation__name {
                    font-size: 20px;
                    line-height: 20px;
                }
                .personal-transformation__content.padding-left {
                    padding-left: 100px;
                }
                .small-btn {
                    padding: 7px 14px;
                }
                .has-leader .personal-transformation__name {
                    font-size: 30px;
                    line-height: unset;
                }
            }


            .personal-transformation__container.has-leader {
                display: flex;
                flex-direction: row;
                border-radius: 10px;
            }
            .my-leader__container {
                width: 150px;
                flex: 0 0 150px;
                margin-right: 20px;
            }
            .leader-avatar {
                width: 150px;
                height: 150px;
            }
            .leader-avatar video,
            .leader-avatar img{
                width: 100%;
                height: 100%;
                display: block;
                position: relative;
                border-radius: 50%;
                margin: 0 auto;
            }
            .my-leader__container {
                width: 35%;
                flex: 0 0 35%;
            }
            .has-leader .personal-transformation__name {
                font-size: 30px;
            }
            .leader-name {
                text-align: center;
                font-weight: 700;
                padding: 20px 0 0;
            }
            .has-leader .personal-transformation__content {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }
            @media screen and (max-width: 1220px) {
                .has-leader .personal-transformation__name {
                    font-size: 26px;
                }
                .small-btn{
                    padding: 10px 10px;
                }
            }
        </style>
        <?
        global $USER;
        $rsUser = CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();
        $special=unserialize($arUser["UF_SPECIAL"]);
        if (!empty($special)){
            $landingIblockCode = 'landing_v2';
            $landingIblockId = Utils::GetIBlockIDBySID($landingIblockCode);
            $transformationId=Utils::GetIBlockElementIDBySID("transformation");
            $db_props = CIBlockElement::GetProperty($landingIblockId, $transformationId, array("sort" => "asc"));
            $TRANSFORMATION_PROPERYS=[];
            while($ar_props = $db_props->Fetch()){
                $TRANSFORMATION_PROPERYS[$ar_props["CODE"]]=$ar_props;
            }


        }
        if (!empty($special)):?>
            <?if (count($TRANSFORMATION_PROPERYS)!==0):?>
                <div class="personal-spirit-transformation">
                    <?if (empty($special["coach"])):?>
                        <?
                        $leaderIblockCode="leaders";
                        $leaderIblockId=Utils::GetIBlockIDBySID($leaderIblockCode);
                        $db_el=CIBlockElement::GetList(array("sort"=>"asc"), array("IBLOCK_ID"=>$leaderIblockId), false, false, array("NAME", "PROPERTY_CODE_1C"));
                        $LEADERS=[];
                        while($element=$db_el->Fetch()){
                            $LEADERS[]=$element;
                        }
                        ?>
                        <div class="personal-transformation__container" style="background-image: url('<?=CFile::GetPath($TRANSFORMATION_PROPERYS["HEADER_IMAGE"]["VALUE"])?>')">
                            <div class="personal-transformation__content padding-left ">
                                <div class="personal-transformation__title">
                                    Ваш тариф включает спецпроект
                                    <div class="personal-transformation__name">Spirit.<br>Трансформация</div>
                                </div>
                                <div class="personal-transformation__btns">
                                    <a href="/landings/v2/transformation/" class="small-btn">Подробнее</a>
                                </div>
                            </div>
                        </div>
                        <div class="personal-transformation__choose-leader">
                            <div class="choose-trainer__container">
                                <div class="choose-trainer__trigger" onclick="show_transformation_leaders(this)">
                                    Выбрать тренера
                                    <div class="choose-leader__arrow-down">
                                        <?php echo file_get_contents($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/img/arrow-down.svg');?>
                                    </div>
                                </div>
                                <div class="choose-leader__leaders-container is-hide">
                                    <div class="choose-leader__leaders">
                                        <?foreach ($LEADERS as $LEADER):?>
                                            <div class="choose-leader__leader-item">
                                                <input type="radio" name="transformation-leader"
                                                       id="transformation-leader__<?=$LEADER["PROPERTY_CODE_1C_VALUE"]?>"
                                                       value="<?=$LEADER["PROPERTY_CODE_1C_VALUE"]?>"
                                                       onchange="set_leader(this)"/>
                                                <label for="transformation-leader__<?=$LEADER["PROPERTY_CODE_1C_VALUE"]?>"><?=$LEADER["NAME"]?></label>
                                            </div>
                                        <?endforeach;?>
                                    </div>
                                    <div class="choose-leader__submit">
                                        <button class="small-btn submit" disabled onclick="select_leader()">Выбрать</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?else:?>
                        <?
                        $leaderIblockCode="leaders";
                        $leaderIblockId=Utils::GetIBlockIDBySID($leaderIblockCode);
                        $db_leader=CIBlockElement::GetList(array("sort"=>"asc"), array("IBLOCK_ID"=>$leaderIblockId, "PROPERTY_CODE_1C"=>$special["coach"]["id"]), false, false, array("NAME", "PROPERTY_VIDEO", "PREVIEW_PICTURE", "PROPERTY_CHAT_LINK"));
                        $leader=$db_leader->Fetch();
                        ?>
                        <?if (!empty($leader)):?>
                            <div class="personal-transformation__container has-leader">
                                <div class="my-leader__container is-hide-mobile">
                                    <div class="leader-block">
                                        <div class="leader-avatar">
                                            <? if( !empty($leader["PROPERTY_VIDEO_VALUE"]) ) {
                                                $path=CFile::GetPath($leader["PROPERTY_VIDEO_VALUE"]);?>
                                                <video autoplay muted loop playsinline><source src="<?=$path?>" type="video/<?=pathinfo($path, PATHINFO_EXTENSION)?>"></video>
                                            <? } else { ?>
                                                <img src="<?=CFile::GetPath($leader["PREVIEW_PICTURE"])?>" alt="<?=$leader["NAME"]?>" title="<?=$leader["NAME"]?>">
                                            <? } ?>
                                        </div>
                                        <div class="leader-name">
                                            <?=$leader["NAME"]?>
                                        </div>
                                    </div>
                                </div>
                                <div class="personal-transformation__content">
                                    <div class="personal-transformation__title">
                                        <div style="font-size: 15px;">Ваш тариф включает спецпроект</div>
                                        <a class="personal-transformation__name" href="/landings/v2/transformation/">Spirit.<br>Трансформация</a>
                                    </div>
                                    <?if (!empty($leader["PROPERTY_CHAT_LINK_VALUE"])):?>
                                    <div class="personal-transformation__btns" style="padding: 10px 0;">
                                        <a href="<?=$leader["PROPERTY_CHAT_LINK_VALUE"]?>" class="small-btn">Перейти в чат с тренером</a>
                                    </div>
                                    <?endif;?>
                                </div>
                            </div>
                        <?endif;?>
                    <?endif;?>
                </div>
            <?endif;?>
        <?else:?>

        <?endif?>

        <!--НА ВРЕМЯ ТРАНСФОРМАЦИИ-->
        <?
        global $APPLICATION;
        foreach ($arResult['LK_FIELDS']['SECTIONS'] as $SECTION){
            $APPLICATION->IncludeFile(
                str_replace($_SERVER['DOCUMENT_ROOT'], '',__DIR__) .'/includes/section_form.php',
                array(
                    'SECTION_ID'=>$SECTION['ID'],
                    "SECTION_CODE"=>$SECTION['CODE'],
                    'SECTION'=>$SECTION,
                    'IS_CORRECT'=>$arResult['LK_FIELDS']['IS_CORRECT'],
                    'COMPONENT_NAME'=>$arResult['COMPONENT_NAME'],
                ),
                array(
                    'SHOW_BORDER'=>true
                )
            );
        }?>
    </div>
</div>
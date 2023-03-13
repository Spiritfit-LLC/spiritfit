<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<?php
global $USER;
?>
<div class="content-center">
    <?php
    $APPLICATION->IncludeComponent(
        "personal.custom:personal.auth",
        "page",
        array(
            "AUTH_FORM_CODE" => "AUTH",
            "REGISTER_FORM_CODE" => "REGISTRATION_LITE",
            "PASSFORGOT_FORM_CODE"=>'PASSFORGOT',
            "CACHE_TIME" => 0,
            "CACHE_TYPE" => "N",
        ),
        $component
    );
    ?>
</div>
<?if ($USER->IsAuthorized()):?>
<div class="content-center">
    <?php
    $APPLICATION->IncludeComponent(
        "personal.custom:personal.info",
        "",
        array(
            "USER_ID"=>$USER->GetID(),
            "MENU"=>$arResult["MENU"]
        ),
        $component
    );
    ?>
    <?
    $APPLICATION->IncludeComponent(
        "personal.custom:personal.partners.list",
        "",
        array(),
        $component
    );
    ?>
</div>

<div class="content-center">
    <div id="personal-detached-services">
        <div class="personal-section-title">
            Услуги
        </div>
        <div class="pds__container">
            <?foreach($arResult["PDS"] as $pds):?>
            <div class="pds__item">
                <div class="personal-detached-service" id="<?=$pds["CLASSNAME"]?>">
                    <div class="pds__title">Воспользоваться</div>
                    <div class="pds__name">
                        <div class="pds__service-icon">
                            <?=file_get_contents(__DIR__.'/images/pds__service-workout.svg')?>
                        </div>
                        <span><?=$pds["NAME"]?></span>
                    </div>
                    <div class="pds__icon-global">
                        <?=file_get_contents(__DIR__.'/images/pds__service-workout.svg')?>
                    </div>
                </div>
            </div>
            <?endforeach;?>
        </div>
    </div>
</div>
<?foreach ($arResult["PDS"] as $pds):?>
        <?
        $APPLICATION->IncludeComponent(
            "custom:ajax.component",
            "popup",
            array(
                "COMPONENT" => $pds["COMPONENT"],
                "COMPONENT_TEMPLATE" => $pds["COMPONENT_TEMPLATE"],
                "COMPONENT_PARAMS" => $pds["COMPONENT_PARAMS"],
                "TRIGGER" => "#".$pds["CLASSNAME"],
                "TRIGGER_TYPE" => "click",
                "AJAX_OPTION_ADDITIONAL" => 'personal_'.$pds["CLASSNAME"],
                "AJAX_MODE" => "Y",  // режим AJAX
                "AJAX_OPTION_HISTORY" => "N",
            )
        );
        ?>
<?endforeach;?>
<button id="update">ОБНОВИТЬ ДАННЫЕ ИЗ 1С</button>

<?endif;?>

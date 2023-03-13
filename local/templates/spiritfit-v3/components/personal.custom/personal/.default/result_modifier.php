<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php
$url = $_SERVER['REQUEST_URI'];
$parts = parse_url($url);

parse_str($parts['query'], $query);

if (key_exists("pds",$query)){
    unset($query["pds"]);
}
$arResult["MENU"]=[
    "MAIN"=>[
        "LINK"=>$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["me"] . "?" . http_build_query($query),
        "NAME"=>"Для меня",
        "ACTIVE"=>$arResult["COMPONENT_PAGE"]=="me"
    ],
    "SERVICES"=>[
        "LINK"=>$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["services"] . "?" . http_build_query($query),
        "NAME"=>"Услуги",
        "ACTIVE"=>$arResult["COMPONENT_PAGE"]=="services"
    ],
    "SETTINGS"=>[
        "LINK"=>$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["settings"] . "?" . http_build_query($query),
        "NAME"=>"Настройки",
        "ACTIVE"=>$arResult["COMPONENT_PAGE"]=="settings"
    ],
    "LOYALTY"=>[
        "LINK"=>$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["loyalty"] . "?" . http_build_query($query),
        "NAME"=>"Программа лояльности",
        "ACTIVE"=>$arResult["COMPONENT_PAGE"]=="loyalty"
    ],
];

global $USER;
$arResult["PDS"]=[];
$FIELDS = PersonalUtils::get_personal_fields($USER->GetID(), array(
    "lk-workout-workout",
    "lk-services-freefreezing",
    "lk-services-promisepayment"
));

if (!empty($FIELDS["lk-workout-workout"]["VALUE"])){
    $arResult["PDS"][]=[
        "NAME"=>$FIELDS["lk-workout-workout"]["NAME"],
        "COMPONENT" => "personal.custom:personal.workout",
        "COMPONENT_TEMPLATE" => "popup",
        "COMPONENT_PARAMS" => array(),
        "CLASSNAME" => "workout",
    ];
}
if (!empty($FIELDS["lk-services-freefreezing"]["VALUE"])){
    $arResult["PDS"][]=[
        "NAME"=>$FIELDS["lk-services-freefreezing"]["NAME"],
        "COMPONENT" => "personal.custom:personal.freefreezing",
        "COMPONENT_TEMPLATE" => "popup",
        "COMPONENT_PARAMS" => array(),
        "CLASSNAME" => "freefreezing",
    ];
}
if (!empty($FIELDS["lk-services-promisepayment"]["VALUE"])){
    $arResult["PDS"][]=[
        "NAME"=>$FIELDS["lk-services-promisepayment"]["NAME"],
        "COMPONENT" => "personal.custom:personal.promisepayment",
        "COMPONENT_TEMPLATE" => "popup",
        "COMPONENT_PARAMS" => array(),
        "CLASSNAME" => "promisepayment",
    ];
}

$template=$this->getComponent()->getTemplate();
$template->addExternalJs(SITE_TEMPLATE_PATH . '/vendor/tippy/popper.min.js');
$template->addExternalJs(SITE_TEMPLATE_PATH . '/vendor/tippy/tippy-bundle.umd.min.js');

$template->addExternalCss(SITE_TEMPLATE_PATH . '/css/popup.css');
$template->addExternalJs(SITE_TEMPLATE_PATH . '/js/popup.js');
?>

<script>
    var personalComponentName=<?=CUtil::PhpToJSObject($this->getComponent()->getName())?>;
</script>
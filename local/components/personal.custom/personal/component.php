<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php
global $USER;
if (isset($_GET["update"]) && $_GET["update"]=="Y" && $USER->IsAuthorized()){
    PersonalUtils::get_lk_info($USER->GetID());
    LocalRedirect("/personal/?v=2");
}

$arDefaultUrlTemplates404 = array(
    "me" => "",
    "services" => "services/",
    "settings" => "settings/",
    "loyalty" => "loyalty/",
);

$arComponentVariables=array();

if($arParams["SEF_MODE"] == "Y"){
    $arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $arParams["SEF_URL_TEMPLATES"]);

    $arVariables = array();
    $componentPage = CComponentEngine::ParseComponentPath(
        $arParams["SEF_FOLDER"],
        $arUrlTemplates,
        $arVariables
    );


    if(!$componentPage)
    {
        $componentPage = "me";
    }

    $arVariables["SHOW_BANNER"]=false;
    if ($componentPage=="services"){
        $arVariables["SHOW_BANNER"]=true;
    }

    $arResult = array(
        "FOLDER" => $arParams["SEF_FOLDER"],
        "URL_TEMPLATES" => $arUrlTemplates,
        "VARIABLES" => $arVariables,
        "COMPONENT_PAGE"=>$componentPage,
        "COMPONENT_NAME"=>$this->GetName(),
    );
}
else{
    $arVariables = array();

    $arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases, $arParams["VARIABLE_ALIASES"]);
    CComponentEngine::InitComponentVariables(false, $arComponentVariables, $arVariableAliases, $arVariables);


    $componentPage = "";

    if(isset($arVariables["services"]))
        $componentPage = "services";
    elseif(isset($arVariables["settings"]))
        $componentPage = "settings";
    elseif(isset($arVariables["loyalty"]))
        $componentPage = "loyalty";
    else
        $componentPage = "me";

    $arVariables["SHOW_BANNER"]=false;
    if ($componentPage=="services"){
        $arVariables["SHOW_BANNER"]=true;
    }


    $arResult = array(
        "FOLDER" => "",
        "URL_TEMPLATES" => Array(
            "me" => htmlspecialcharsbx($APPLICATION->GetCurPage()),
            "services" => htmlspecialcharsbx($APPLICATION->GetCurPage()."?services=y"),
            "settings" => htmlspecialcharsbx($APPLICATION->GetCurPage()."?settings=y"),
            "loyalty" => htmlspecialcharsbx($APPLICATION->GetCurPage()."?loyalty=y"),
        ),
        "VARIABLES" => $arVariables,
        "COMPONENT_PAGE"=>$componentPage,
        "COMPONENT_NAME"=>$this->GetName(),
    );
}
?>



<?php
$this->IncludeComponentTemplate($componentPage);
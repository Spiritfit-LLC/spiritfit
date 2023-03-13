<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * @var string $componentPath
 * @var string $componentName
 * @var array $arCurrentValues
 * */

$arComponentSections=[];
if ($arCurrentValues){
    $arComponentSections=array_filter($arCurrentValues, function($key){
        return strpos($key, "COMPONENT_SECTION_") === 0;
    }, ARRAY_FILTER_USE_KEY);
}

$arSection=[];
$arComponentTree=\CComponentUtil::GetComponentsTree();
foreach ($arComponentTree["#"] as $key=>$item){
    $sections[$key]='['.$key.'] '.$item["@"]["NAME"];
}
$arSection[]=$sections;


$PARAMETERS=array();

if (count($arComponentSections)>0){
    foreach ($arComponentSections as $section){
        $sections=[];
        $arComponentTree=$arComponentTree["#"][$section];
        if (key_exists("#", $arComponentTree)){
            foreach ($arComponentTree["#"] as $key=>$item){
                $sections[$key]='['.$key.'] '.$item["@"]["NAME"];
            }
            $arSection[]=$sections;
        }
        if (key_exists("*", $arComponentTree)){

            foreach ($arComponentTree["*"] as $component){
                if ($component["COMPLEX"]=="Y"){
                    continue;
                }
                if ($component["NAME"]=="custom:ajax.component"){
                    continue;
                }
                $arComponents[$component["NAME"]]="[".$component["NAME"]."] ".$component["TITLE"];
            }


            $COMPONENTS=[
                "PARENT" => "AJAX_COMPONENT",
                "NAME" => "Компонент",
                "TYPE" => "LIST",
                "ADDITIONAL_VALUES" => "N",
                "VALUES" => $arComponents,
                "MULTIPLE"=>"N",
                "REFRESH"=>"Y"
            ];
        }
    }
}




for ($i=0; $i < count($arSection); $i++){
    $PARAMETERS["COMPONENT_SECTION_".$i]=[
        "PARENT" => "AJAX_COMPONENT",
        "NAME" => "Раздел (уровень ".$i.")",
        "TYPE" => "LIST",
        "ADDITIONAL_VALUES" => "N",
        "VALUES" => $arSection[$i],
        "MULTIPLE"=>"N",
        "REFRESH"=>"Y"
    ];
}
$PARAMETERS["COMPONENT"]=$COMPONENTS;

if (!empty($arCurrentValues["COMPONENT"])){

    $templates=\CComponentUtil::GetTemplatesList($arCurrentValues["COMPONENT"]);

    foreach ($templates as $template){
        $arComponentTemplate[$template["NAME"]] = $template["NAME"];
    }
    $PARAMETERS["COMPONENT_TEMPLATES"]=[
        "PARENT" => "AJAX_COMPONENT",
        "NAME" => "Шаблон компонента",
        "TYPE" => "LIST",
        "ADDITIONAL_VALUES" => "N",
        "VALUES" => $arComponentTemplate,
        "MULTIPLE"=>"N",
        "REFRESH"=>"N"
    ];
}

$arComponentParameters = array(
    "GROUPS" => array(
        "AJAX_COMPONENT" => [
            "NAME" => "Настройка AJAX компонента",
            "SORT" => 100,
        ],
    ),
    "PARAMETERS" => $PARAMETERS,
);


?>
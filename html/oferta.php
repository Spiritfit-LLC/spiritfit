<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$settings = Utils::getInfo();
echo $settings["PROPERTIES"]["TEXT_OFERTA"]["~VALUE"]['TEXT'];
?>
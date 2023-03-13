<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class PersonalComponent extends CBitrixComponent implements Controllerable{
    public function ConfigureActions(){
        return [
            'getClue'=>[
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'update'=>[
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_GET)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'ajaxComponent' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ]
        ];
    }

    //AJAX
    public function getClueAction($clue_code){
        \Bitrix\Main\Loader::includeModule("iblock");
        $dbRes=CIBlockElement::GetList(array(), array("CODE"=>$clue_code), false, false, array("PROPERTY_CLUE"));
        if ($arClue=$dbRes->Fetch()){
            return htmlspecialcharsback($arClue["PROPERTY_CLUE_VALUE"]["TEXT"]);
        }
        return false;
    }

    public function updateAction(){
        global $USER;
        $result = PersonalUtils::get_lk_info($USER->GetID());

        return true;
    }

    public function ajaxComponentAction($componentName, $componentTemplate, $child=false){
        global $APPLICATION;
        ob_start();
        $APPLICATION->IncludeComponent($componentName, $componentTemplate, array(), $this);
        return ob_get_clean();
    }
}
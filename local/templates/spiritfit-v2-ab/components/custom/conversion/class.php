<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class ConversionComponent extends CBitrixComponent implements Controllerable{
    public function ConfigureActions(){
        return [
            'setConversion'=>[
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
        ];
    }

    private function checkQuery($params){
        $query=Context::getCurrent()->getRequest()->toArray();
        foreach ($params as $param){
            if (!isset($query[$param]))
            {
                throw new Exception("Отсутствует обязательный параметр $param",400);
            }
        }
    }

    public function setConversionAction(){
        if (CModule::IncludeModule('conversion')){
            $this->checkQuery(["module"]);
            $query=Context::getCurrent()->getRequest()->toArray();
            if (empty($query["callback"])){
                $callback="addCounters";
            }
            else{
                $callback=$query["callback"];
            }

            $module=$query["module"];
            $func = array($module, $callback);
            try{
                $func();
                return true;
            }
            catch (Exception $err){
                throw new Exception("Произошла ошибка при вызове функции",400);
            }
        }
        else{
            throw new Exception("Не удалось загрузить модель конверсии",500);
        }
    }
}
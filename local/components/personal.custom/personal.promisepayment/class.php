<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class PersonalPromisePayment extends CBitrixComponent implements Controllerable
{
    public function ConfigureActions()
    {
        return [
            'doPromisePayment'=>[
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
        ];
    }

    public function executeComponent()
    {
        global $USER;

        $fields=PersonalUtils::get_personal_fields($USER->GetID(), array(
            "lk-services-promisepayment",
            "lk-services-promisepayment-appeal"
        ));

        $payment=$fields["lk-services-promisepayment"];
        $appeal = $fields["lk-services-promisepayment-appeal"];

        if (!empty($payment["VALUE"])){
            $this->arResult["TYPE"]=$payment["VALUE"]["type"];
            $this->arResult["DATE"]=$payment["VALUE"]["datetime"];

            $this->arResult["TITLE"]=$payment["NAME"];

            switch ($this->arResult["TYPE"]){
                case "promise":
                    $this->arResult["BTN_NAME"] = "Воспользоваться";



                    if (!empty($this->arResult["DATE"])){
                        $this->arResult["INFO"] = "Отложенный платеж активирован.<br>Дата и время открытия доступа: ".$this->arResult["DATE"];
                    }
                    else{
                        $this->arResult["FORM"] = true;
                        $this->arResult["INFO"]=$payment["CLUE_VALUE"];
                        $this->arResult["BTN_NAME"] = "Воспользоваться обещанным платежем";
                    }

                    if (boolval($appeal["VALUE"])){
                        $USER->Update($USER->GetID(), [
                            "UF_PROMISEDPAYMENT_APPEAL_CLICK"=>false
                        ], false);
                    }


                    break;
                case "appeal":
                    $this->arResult["BTN_NAME"] = "Оставить обращение";
                    if (boolval($appeal["VALUE"])){
                        $this->arResult["INFO"] = "Ваше обращение зарегистрировано";
                    }
                    else{
                        $this->arResult["INFO"]=$appeal["CLUE_VALUE"];
                    }

                    $this->arResult["FORM"]=!boolval($appeal["VALUE"]);
                    break;
            }


            $this->IncludeComponentTemplate();
        }
    }


    //ajax
    function doPromisePaymentAction($action){
        global $USER;
        $rsUser = CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();

        $api=new Api([
            "action"=>"promisedpayment",
            "params"=>[
                "id1c"=>$arUser["UF_1CID"],
                "type"=>$action,
                "login"=>$arUser["LOGIN"]
            ]
        ]);

        $response=$api->result();
        if (!$response['success']){
            if (empty($response['data']['result']['userMessage'])){
                throw new Exception("Произошла ошибка, но мы скоро исправим", 1);
            }
            else{
                throw new Exception($response['data']['result']['userMessage'], 1);
            }
        }

        PersonalUtils::get_lk_info(false, $arUser);
        if ($action=="appeal"){
            $USER->Update($USER->GetID(), [
                "UF_PROMISEDPAYMENT_APPEAL_CLICK"=>true
            ], false);
        }

        return true;
    }
}
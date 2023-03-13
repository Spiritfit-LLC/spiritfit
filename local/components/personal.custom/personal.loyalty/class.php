<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class PersonalLoyaltyComponent extends CBitrixComponent implements Controllerable{
    public function ConfigureActions(){
        return [
            'history'=>[
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_GET)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'charge'=>[
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'present'=>[
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'regLoyalty' => [
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ]
        ];
    }

    function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    function executeComponent()
    {
        global $USER;
        if (!$USER->IsAuthorized()){
            $this->arResult["ERROR"]="Пользователь не авторизован";
        }
        else{
            $FIELDS=PersonalUtils::get_personal_fields($USER->GetID(), array(
                "lk-loyalty",
                "lk-loyalty-balance",
                "lk-loyalty-balancehistory",
                "lk-loyalty-visitslist",
                "lk-visits",
                "lk-loyalty-reg"
            ));
            //TODO:ПРОГРАММА ЛОЯЛЬНОСТИ - НОВАЯ МОДЕЛЬ
            $this->arResult["PL"]=$FIELDS["lk-loyalty"];
            if ($this->arResult["PL"]["VALUE"]["isreg"] === null){
                $this->arResult["ERROR"]="Программа лояльности недоступна";
            }
            else{
                $this->arResult["ISREG"]=$this->arResult["PL"]["VALUE"]["isreg"];
                if (!$this->arResult["ISREG"]){
                    $this->arResult["MESSAGE"] = $FIELDS["lk-loyalty-reg"]["CLUE_VALUE"];
                }
            }


            if ($this->arResult["ISREG"]){
                $this->arResult["BALANCE"]=[
                    "VALUE" => $this->arResult["PL"]["VALUE"]["balance"],
                    "CLUE" => $FIELDS["lk-loyalty-balance"]["CLUE"],
                    "CODE" => $FIELDS["lk-loyalty-balance"]["CODE"],
                    "NAME" => $FIELDS["lk-loyalty-balance"]["NAME"],
                    "PAYMENT"=>[
                        "NEXT"=>$this->arResult["PL"]["VALUE"]["payment"]["sum"],
                        "LIMIT"=>$this->arResult["PL"]["VALUE"]["payment"]["limit"],
                        "VALUE"=>$this->arResult["PL"]["VALUE"]["payment"]["bonuses"],
                    ],
                    "PROMOCODE"=>[
                        "VALUE"=>$this->arResult["PL"]["VALUE"]["promocode"]["value"],
                        "DATE"=>$this->arResult["PL"]["VALUE"]["promocode"]["date"],
                        "SUM"=>$this->arResult["PL"]["VALUE"]["promocode"]["sum"],
                    ]
                ];

                switch ($this->arResult["PL"]["VALUE"]["payment"]["status"]){
                    case "block_on":
                    case "block_off":
                        $this->arResult["BALANCE"]["PAYMENT"]["STATUS"]="block";
                        $this->arResult["BALANCE"]["PAYMENT"]["STATUS_MESSAGE"]="На данный момент вам недоступно списание бонусов";
                        break;
                    default:
                        $this->arResult["BALANCE"]["PAYMENT"]["STATUS"]="unblock";
                        break;

                }
                switch ($this->arResult["PL"]["VALUE"]["promocode"]["status"]){
                    case "block_on":
                    case "block_off":
                        $this->arResult["BALANCE"]["PROMOCODE"]["STATUS"]="block";
                        $this->arResult["BALANCE"]["PROMOCODE"]["STATUS_MESSAGE"]="На данный момент вы не можете воспользоваться бонусами";
                        break;
                    default:
                        $this->arResult["BALANCE"]["PROMOCODE"]["STATUS"]="unblock";
                        break;

                }


                $monthArr = [
                    'январь',
                    'февраль',
                    'март',
                    'апрель',
                    'май',
                    'июнь',
                    'июль',
                    'август',
                    'сентябрь',
                    'октябрь',
                    'ноябрь',
                    'декабрь'
                ];

                $VISITLIST=$FIELDS["lk-visits"]["VALUE"]["list"];
                $VISITS=0;
                if (!empty($VISITLIST)){
                    $VISIT_ARR=[];
                    $MAX=max(array_column($VISITLIST, "days"));
                    foreach ($VISITLIST as $MONTHVISIT){
                        $VISITS+=$MONTHVISIT["days"];

                        $date = str_replace('.', '-', $MONTHVISIT["month"]);
                        $month = date('n', strtotime($date))-1;
                        $VISIT_ARR[date('Y m', strtotime($date))]=[
                            "VALUE"=>$MONTHVISIT["days"],
                            "MONTH"=>$monthArr[$month],
                            "HEIGHT"=>round((100/$MAX) * $MONTHVISIT["days"],1)
                        ];
                    }
                    ksort($VISIT_ARR);
                }
                $this->arResult["VISITS"] = [
                    "LIST"=>$VISIT_ARR,
                    "CLUE" => $FIELDS["lk-loyalty-visitslist"]["CLUE"],
                    "CODE" => $FIELDS["lk-loyalty-visitslist"]["CODE"],
                    "NAME" => $FIELDS["lk-loyalty-visitslist"]["NAME"],
                    "ALL_VISITS"=>$VISITS
                ];


                $this->arResult["HISTORY"]=[
                    "CLUE" => $FIELDS["lk-loyalty-balancehistory"]["CLUE"],
                    "CODE" => $FIELDS["lk-loyalty-balancehistory"]["CODE"],
                    "NAME" => $FIELDS["lk-loyalty-balancehistory"]["NAME"],
                ];
            }
        }


        $this->IncludeComponentTemplate();
    }


    //ajax
    public function historyAction(){
        global $USER;
        $arUser = CUser::GetByID($USER->GetID())->Fetch();


        $arParams=array(
            'id1c'=>$arUser['UF_1CID'],
            'login'=>$arUser['LOGIN'],
        );

        $api=new Api([
            "action"=>"lkloyaltyhistory",
            "params"=>$arParams
        ]);

        $response=$api->result();
        if (!$response['success']){
            if (empty($response['data']['result']['userMessage'])){
                return [
                    "result"=>false,
                    "message"=>"Не удалось получить данные."
                ];
            }
            else{
                return [
                    "result"=>false,
                    "message"=>$response['data']['result']['userMessage']
                ];
            }
        }

        if (count($response['data']['result']['result'])<=0){
            return [
                "result"=>false,
                "message"=>"Данные отсутствуют."
            ];
        }



        return [
            "result"=>true,
            "data"=>array_reverse($response['data']['result']['result'])
        ];
    }

    public function chargeAction($pl_sum_input){
        global $USER;

        $rsUser = CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();

        $fields=PersonalUtils::get_personal_fields($USER->GetID(), array(
            "lk-loyalty",
        ));
        $loyalty=$fields["lk-loyalty"];

        if ((int)$pl_sum_input>(int)$loyalty["VALUE"]["payment"]["limit"]){
            return [
                "result"=>false,
                'errors'=>[[
                    'field_name'=>"pl_sum_input",
                    'message'=>GetMessage("BONUS_LIMIT"),
                ]]
            ];
        }
        if ((int)$pl_sum_input<1){
            return [
                "result"=>false,
                'errors'=>[[
                    'field_name'=>"pl_sum_input",
                    'message'=>GetMessage("BONUS_INCORRECT"),
                ]]
            ];
        }

        if (empty($arUser["UF_ADDRESS"])){
            throw new Exception(GetMessage("ADDRESS_IS_EMPTY"), 1);
        }

        $arParams=[
            'id1c'=>$arUser['UF_1CID'],
            'login'=>$arUser['LOGIN'],
            'sum'=>(int)$pl_sum_input,
        ];
        $action='lkpayments';

        $api=new Api([
            'action'=>$action,
            'params'=> $arParams
        ]);

        $result=$api->result();
        if (!$result['success']){
            throw new Exception($result['data']['result']['userMessage'], 1);
        }


        PersonalUtils::get_lk_info($USER->GetID());
        return ['result'=>true, 'reload'=>true];
    }

    public function presentAction($pl_present_recipient_input, $pl_present_sum_input){
        global $USER;
        $rsUser = CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();

        $recipient=preg_replace('![^0-9]+!', '', $pl_present_recipient_input);
        if ($recipient[0]!='9' || strlen($recipient)!=10){
            return [
                "result"=>false,
                'errors'=>[[
                    'field_name'=>"pl_present_recipient_input",
                    'message'=>GetMessage("PHONE_INCORRECT"),
                ]]
            ];
        }
        if ($arUser["LOGIN"]==$recipient){
            throw new Exception(GetMessage("NOT_YORSELF"),1);
        }

        $fields=PersonalUtils::get_personal_fields($USER->GetID(), array(
            "lk-loyalty",
        ));
        $loyalty=$fields["lk-loyalty"];

        if ((int)$pl_present_sum_input>(int)$loyalty["VALUE"]["balance"]){
            return [
                "result"=>false,
                'errors'=>[[
                    'field_name'=>"pl_present_sum_input",
                    'message'=>GetMessage("BONUS_LIMIT"),
                ]]
            ];
        }

        if (empty($arUser["UF_ADDRESS"])){
            throw new Exception(GetMessage("ADDRESS_IS_EMPTY"), 1);
        }

        $arParams=[
            'id1c'=>$arUser['UF_1CID'],
            'login'=>$arUser['LOGIN'],
            'sum'=>(int)$pl_present_sum_input,
            'recipient'=>$recipient,
        ];
        $action='lkpresent';

        $api=new Api([
            'action'=>$action,
            'params'=> $arParams
        ]);

        $result=$api->result();
        if (!$result['success']){
            throw new Exception($result['data']['result']['userMessage'], 1);
        }

        PersonalUtils::get_lk_info($USER->GetID());
        return ['result'=>true, 'reload'=>true];
    }

    public function regLoyaltyAction(){
        global $USER;
        $rsUser = CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();

        $arParams=array(
            'id1c'=>$arUser['UF_1CID'],
            'login'=>$arUser['LOGIN'],
        );

        $api=new Api([
            "action"=>"lkloyalty",
            "params"=>$arParams
        ]);

        $response=$api->result();
        if (!$response['success']){
            if (empty($response['data']['result']['userMessage'])){
                throw new Exception("Произошла ошибка, но мы уже чиним", 1);
            }
            else{
                throw new Exception($response['data']['result']['userMessage'], 1);
            }
        }
        else{
            return PersonalUtils::get_lk_info(false, $arUser);
        }
    }
}
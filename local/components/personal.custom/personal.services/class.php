<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class PersonalServicesComponent extends CBitrixComponent implements Controllerable
{
    public function ConfigureActions()
    {
        return [
            'getSubInfo' => [
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'editSub' => [
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'getServiceForm' => [
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'recalc' => [
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'editService' => [
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

    function executeComponent()
    {
        global $USER;
        if (!$USER->IsAuthorized()){
            $this->arResult["ERROR"]="Пользователь не авторизован";
        }

        $fields=PersonalUtils::get_personal_fields($USER->GetID(), array(
            "lk-services-subscriptions",
            "lk-services-services",
        ));

        $subs=$fields["lk-services-subscriptions"];
        $services=$fields["lk-services-services"];

        $all_count=0;
        $active_count=0;
        foreach ($subs["VALUE"] as $sub){
            $all_count++;
            if ($sub["status"]=="on" || $sub["status"]=="block_on"){
                $active_count++;
            }
        }
        $this->arResult["SUBS"]=[
            "ALL_COUNT"=>$all_count,
            "ACTIVE_COUNT"=>$active_count,
            "LIST"=>$subs["VALUE"],
            "NAME"=>$subs["NAME"],
            "CLUE"=>$subs["CLUE"],
            "CODE"=>$subs["CODE"]
        ];

        $all_count=0;
        $active_count=0;
        foreach ($services["VALUE"] as $service){
            $all_count++;
            if ($service["status"]=="on" || $service["status"]=="block_on"){
                $active_count++;
            }
        }

        $this->arResult["SERVICES"]=[
            "ALL_COUNT"=>$all_count,
            "ACTIVE_COUNT"=>$active_count,
            "LIST"=>$services["VALUE"],
            "NAME"=>$services["NAME"],
            "CLUE"=>$services["CLUE"],
            "CODE"=>$services["CODE"]
        ];


        $this->IncludeComponentTemplate();
    }

    //ajax
    public function getSubInfoAction($id, $type){
        global $USER;

        $fields=PersonalUtils::get_personal_fields($USER->GetID(), array(
            "lk-services-subscriptions",
        ));

        $subs=$fields["lk-services-subscriptions"];
        $flg=false;
        foreach ($subs["VALUE"] as $sub){
            if ($sub["id"]==$id && $type==$sub["type"]){
                $flg=true;
                switch ($sub["status"]){
                    case "on":
                    case "block_on":
                        $value=0;
                        break;
                    case "off":
                    case "block_off":
                        $value=1;
                        break;
                }
            }
        }

        if (!$flg){
            throw new Exception(GetMessage("SUB_NOT_EXIST"), 1);
        }

        $dbUser=CUser::GetByID($USER->GetID());
        $arUser=$dbUser->Fetch();

        $url_params=http_build_query(
            [
                'login'=>$arUser['LOGIN'],
                'id1c'=>$arUser["UF_1CID"],
                "type"=>$type,//TODO:Исправить на type
                "id"=>$id
            ]
        );
        $api=new Api([
            "action"=>"subscriptionedit/$value?$url_params",
        ]);
        $response=$api->result();

        if(empty($response["data"]["result"]["result"]) || empty($response["success"])) {
            if(!empty($response["data"]["result"]["userMessage"]) ) {
                throw new Exception($response["data"]["result"]["userMessage"], 1);
            } else {
                throw new Exception(GetMessage("DEFAULT"), 1);
            }
        }


        $result = [
            "message"=>$response["data"]["result"]["result"]["text"],
            "btn"=>boolval($value)?"включить":"отменить подписку",
        ];

        if (!empty($response["data"]["result"]["result"]["text1"])){
            $result["text1"] = $response["data"]["result"]["result"]["text1"];
        }
        if (!empty($response["data"]["result"]["result"]["list1"])){
            $result["list1"] = $response["data"]["result"]["result"]["list1"];
        }

        return $result;
    }

    public function editSubAction($id, $type, $causelist=null){
        if (!empty($causelist)){
            $causelist=$causelist[0];
        }
        else{
            $causelist=null;
        }

        global $USER;

        $fields=PersonalUtils::get_personal_fields($USER->GetID(), array(
            "lk-services-subscriptions",
        ));

        $subs=$fields["lk-services-subscriptions"];
        $flg=false;
        foreach ($subs["VALUE"] as $sub){
            if ($sub["id"]==$id && $type==$sub["type"]){
                $flg=true;
                switch ($sub["status"]){
                    case "on":
                    case "block_on":
                        $value=0;
                        break;
                    case "off":
                    case "block_off":
                        $value=1;
                        break;
                }
            }
        }

        if (!$flg){
            throw new Exception(GetMessage("SUB_NOT_EXIST"), 1);
        }

        $dbUser=CUser::GetByID($USER->GetID());
        $arUser=$dbUser->Fetch();

        $api=new Api([
            "action"=>"subscriptionedit/$value",
            "params"=>[
                'login'=>$arUser['LOGIN'],
                'id1c'=>$arUser['UF_1CID'],
                "type"=>$type,
                "id"=>$id,
                "terminationid"=>$causelist
            ]
        ]);
        $response=$api->result();

        if (!$response["data"]["result"]["success"]){
            if(!empty($response["data"]["result"]["userMessage"]) ) {
                throw new Exception($response["data"]["result"]["userMessage"], 1);
            } else {
                throw new Exception(GetMessage("DEFAULT"), 1);
            }
        }


        PersonalUtils::get_lk_info($USER->GetID());
        if (is_bool($response["data"]["result"]["result"])){
            return [
                "result"=>boolval($value)?"enable":"disable",
            ];
        }
        else{
            $model=[
                "publicId"=>$response["data"]["result"]["result"]["publicID"],
                "description"=>$response["data"]["result"]["result"]["description"],
                "amount"=>(float)$response["data"]["result"]["result"]["amount"],
                "currency"=>'RUB',
                "accountId"=>$arUser["LOGIN"],
                "invoiceId"=>$response["data"]["result"]["result"]["invoiceId"],
                'email'=>$arUser["EMAIL"],
                "requireEmail"=>true,
                "skin"=>"mini",
                "data"=>$response["data"]["result"]["result"]["JsonData"],
            ];

            $result=[
                "fullprice"=>$response["data"]["result"]["result"]["fullprice"],
                "bonuses"=>$response["data"]["result"]["result"]["bonusessum"]>0?(int)$response["data"]["result"]["result"]["bonusessum"]:null,
                "result"=>"order",
                "model"=>$model
            ];

            return $result;
        }
    }

    public function getServiceFormAction($type, $template_folder=""){
        global $USER;

        $fields=PersonalUtils::get_personal_fields($USER->GetID(), array(
            "lk-services-services",
        ));
        $services=$fields["lk-services-services"];

        foreach ($services["VALUE"] as $service){
            if ($service["type"]==$type){
                $current_service=$service;
            }
        }

        if (empty($current_service)){
            throw new Exception("Услуга не найдена", 1);
        }

        $this->arResult["DESCRIPTION"]=$current_service["description"];
        $this->arResult["TYPE"] = $current_service["type"];
        if (count($current_service["list"])>1){
            foreach ($current_service["list"] as $item){
                $this->arResult["LIST"][]=[
                    "ID"=>$item["id"],
                    "BASEPRICE"=>$item["baseprice"],
                    "PRICE"=>$item["price"],
                    "NAME"=>$item["name"],
                    "DESCRIPTION"=>$item["description"],
                ];

            }
        }
        else{
            $this->arResult["ITEM"]=[
                "ID"=>$current_service["list"][0]["id"],
                "BASEPRICE"=>$current_service["list"][0]["baseprice"],
                "NAME"=>$current_service["list"][0]["name"],
                "DESCRIPTION"=>$current_service["list"][0]["description"],
                "PRICE"=>$current_service["list"][0]["price"],
            ];
        }

        switch ($service["status"]){
            case "block_on":
            case "block_off":
                $this->arResult["STATUS"]=false;
                break;
            default:
                $this->arResult["STATUS"]=true;
                break;
        }

        if ($template_folder!==""){
            $template_folder = \Bitrix\Main\Component\ParameterSigner::unsignParameters($this->getName(), $template_folder);
        }


        ob_start();
        $this->IncludeComponentTemplate("popup", $template_folder);
        return ob_get_clean();
    }

    public function recalcAction($invoice, $bonusses){
        $api=new Api([
            "action"=>"orderedit",
            "params"=>[
                "invoiceID"=>$invoice,
                "bonusessum"=>(int)$bonusses
            ]
        ]);

        $response=$api->result();

        if (!$response['success']){
            if(!empty($response["data"]["result"]["userMessage"]) ) {
                $ERROR=$response["data"]["result"]["userMessage"];
            } else {
                $ERROR="Непредвиденная ошибка";
            }
            throw new Exception($ERROR);
        }

        return [
            "amount"=>$response["data"]["result"]["result"]["amount"],
            "jsonData"=>$response["data"]["result"]["result"]["JsonData"]
        ];
    }

    public function editServiceAction($type, $service){
        global $USER;


        $dbUser=CUser::GetByID($USER->GetID());
        $arUser=$dbUser->Fetch();

        $api=new Api([
            "action"=>"servicesedit",
            "params"=>[
                'login'=>$arUser['LOGIN'],
                'id1c'=>$arUser['UF_1CID'],
                "type"=>$type,
                "id"=>$service,
            ]
        ]);
        $response=$api->result();

        if (!$response["data"]["result"]["success"]){
            if(!empty($response["data"]["result"]["userMessage"]) ) {
                throw new Exception($response["data"]["result"]["userMessage"], 1);
            } else {
                throw new Exception(GetMessage("DEFAULT"), 1);
            }
        }


        PersonalUtils::get_lk_info($USER->GetID());
        if (is_bool($response["data"]["result"]["result"])){
            return [
                "result"=>$response["data"]["result"]["result"]?"enable":"disable",
            ];
        }
        else{
            $model=[
                "publicId"=>$response["data"]["result"]["result"]["publicID"],
                "description"=>$response["data"]["result"]["result"]["description"],
                "amount"=>(float)$response["data"]["result"]["result"]["amount"],
                "currency"=>'RUB',
                "accountId"=>$arUser["LOGIN"],
                "invoiceId"=>$response["data"]["result"]["result"]["invoiceId"],
                'email'=>$arUser["EMAIL"],
                "requireEmail"=>true,
                "skin"=>"mini",
                "data"=>$response["data"]["result"]["result"]["JsonData"],
            ];

            $result=[
                "fullprice"=>$response["data"]["result"]["result"]["fullprice"],
                "bonuses"=>$response["data"]["result"]["result"]["bonusessum"]>0?(int)$response["data"]["result"]["result"]["bonusessum"]:null,
                "result"=>"order",
                "model"=>$model
            ];

            return $result;
        }
    }
}
<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class Interview extends CBitrixComponent implements Controllerable {

    public function ConfigureActions(){
        return [
            'auth' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'interview' => [
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

    protected function listKeysSignedParameters(){
        return [
            "CLIENT_ID",
            "INTERVIEW_ID",
        ];
    }

    function onPrepareComponentParams($arParams){
        if(empty($arParams["INTERVIEW_ID"])){
            $this->arResult["ERROR"] = "Не задан опрос";
        }
        if (empty($arParams["CLIENT_ID"]) && !empty($_SESSION["CLIENT_ID"])){
            $arParams["CLIENT_ID"]=$_SESSION["CLIENT_ID"];
            unset($_SESSION["CLIENT_ID"]);
        }

        return $arParams;
    }

    private function getQuestions($request=false){
        $IBLOCK_ID=Utils::GetIBlockIDBySID("interview");
        if (!is_numeric($this->arParams["INTERVIEW_ID"])){
            $INTERVIEW_ID=Utils::GetIBlockSectionIDBySID($this->arParams["INTERVIEW_ID"]);
        }
        $DBRes=CIBlockSection::GetList(Array("SORT"=>"ASC"), array("ID"=>$INTERVIEW_ID, 'IBLOCK_ID'=>$IBLOCK_ID), false, array(
            'ID',
            'NAME',
            'DESCRIPTION',
            'PICTURE',
        ));
        if ($interview=$DBRes->Fetch()){
            $this->arResult["TITLE"]=$interview["NAME"];
            $this->arResult["DESCRIPTION"]=$interview["DESCRIPTION"];
            if (!empty($interview["PICTURE"])){
                $this->arResult["HEADER_IMAGE"]=CFile::GetPath($interview["PICTURE"]);
            }
            $this->arResult["HEADER_DESCRIPTION"]=$interview["DESCRIPTION"];
        }
        else{
            $this->arResult["ERROR"] = "Не удалось найти опрос";
        }

        $objects=[];
        $filter = ['ACTIVE'=>'Y', 'IBLOCK_ID'=>$IBLOCK_ID, "SECTION_ID"=>$INTERVIEW_ID];

        $rows = CIBlockElement::GetList(array("SORT"=>"ASC"), $filter);
        while ($row = $rows->fetch()) {
            $row['PROPERTIES'] = [];
            $objects[$row['ID']] =&$row;
            unset($row);
        }


        CIBlockElement::GetPropertyValuesArray($objects, $IBLOCK_ID, $filter);


        if (empty($objects)){
            $this->arResult["ERROR"] = "Не удалось найти опрос";
        }

        foreach ($objects as $question){
            $arQuestion=[
                "ID"=>$question["ID"],
                "TITLE"=>$question['PROPERTIES']["QUESTION_TITLE"]["VALUE"]["TEXT"],
                "REQUIRED"=>!empty($question['PROPERTIES']["QUESTION_REQUIRED"]["VALUE"]),
                "TYPE"=>$question['PROPERTIES']["QUESTION_TYPE"]["VALUE_XML_ID"],
                "ANSWERS"=>$question['PROPERTIES']["ANSWER_VARS"],
                "REQUIRED_FROM_ID"=>$question["PROPERTIES"]["REQUIRED_FROM"]["VALUE"],
                "REQUIRED_FROM_VAL"=>$question["PROPERTIES"]["REQUIRED_FROM"]["DESCRIPTION"],
            ];
            if (!empty($question["PROPERTIES"]["QUESTION_MEDIA"]["VALUE"])){
                $i=0;
                foreach ($question["PROPERTIES"]["QUESTION_MEDIA"]["VALUE"] as $file){
                    $mime_type=mime_content_type($_SERVER["DOCUMENT_ROOT"].CFile::GetPath($file));
                    list($TYPE, $EXTENSION)=explode('/', $mime_type);

                    if ($TYPE!="image" && $TYPE!="video"){
                        continue;
                    }
                    $MEDIA[]=[
                        "SRC"=>CFile::GetPath($file),
                        "TYPE"=>$TYPE,
                        "EXTENSION"=>$EXTENSION,
                        "NAME"=>$question["PROPERTIES"]["QUESTION_MEDIA"]["DESCRIPTION"][$i]
                    ];
                    $i++;
                }
                if (!empty($MEDIA)){
                    $arQuestion["MEDIA"]=$MEDIA;
                }
            }

            $arQuestion["NAME"]="vote_".$arQuestion["TYPE"]."_".$arQuestion["ID"]."_".$arQuestion["ANSWERS"]["ID"];
            if ($request){
                $arQuestion["1C_NAME"]=$question["PROPERTIES"]["QUESTION_NAME"]["VALUE"];
                $arQuestion["VALUE"]=Context::getCurrent()->getRequest()->getPost($arQuestion["NAME"]);
            }
            $this->arResult["QUESTIONS"][]=$arQuestion;
        }
    }

    function executeComponent()
    {


        if (!empty($this->arResult["ERROR"])) {
            echo $this->arResult["ERROR"];
            return;
        }

        if (empty($this->arParams["CLIENT_ID"])){
            $this->IncludeComponentTemplate("getClientId");
        }
        else{
            $this->getQuestions();
            if (!empty($this->arResult["ERROR"])) {
                echo $this->arResult["ERROR"];
                return;
            }

            $this->IncludeComponentTemplate();
        }
    }

    //AJAX
    public function authAction($phone, $step){
        $phone=substr(preg_replace('![^0-9]+!', '', $phone), 1);
        if ($phone[0]!='9' || strlen($phone)!=10){
            throw new Exception('Формат телефона неверный', 1);
        }

        if ($step==1){
            $user=CUser::GetByLogin($phone)->Fetch();
            if (empty($user)) {
                $_SESSION["USER_EXIST"]=false;
                $arParams = ['login' => $phone];
                $api = new Api(array(
                    'action' => 'lkcheck',
                    'params' => $arParams
                ));
                $result = $api->result();

                //Пользователь не найден, переход на регистрацию
                if ($result['success'] == false) {
                    throw new Exception("Вы не зарегистрированы. Для прохождения опроса <a href=\"/personal/?reg\">зарегистрируйтесь</a>", 2);
                }

                //Пользователь найден в 1с
                //Возвращаем на фронт инфу
                return ['result' => true, 'next_step' => 2, 'code'=>true, 'message'=>'На Ваш номер телефона отправлен код подтверждения', /*'1cdata'=>$result['data']*/];
            }
            else{
                $_SESSION["USER_EXIST"]=true;
                $api=new Api([
                    "action"=>"smscode",
                    "params"=>[
                        "phone"=>$phone
                    ]
                ]);

                $result = $api->result();
                if ($result['success'] == false) {
                    throw new Exception("Произошла ошибка", 1);
                }

                return ['result' => true, 'next_step' => 2, 'code'=>true, 'message'=>'На Ваш номер телефона отправлен код подтверждения', /*'1cdata'=>$result['data']*/];
            }
        }
        elseif ($step==2){
            $code=Context::getCurrent()->getRequest()->getPost("code");
            $code=preg_replace('![^0-9]+!', '', $code);
            if (strlen($code)!=5){
                throw new Exception("Не удалось подтвердить номер телефона", 10);
            }

            if ($_SESSION["USER_EXIST"]){
                $api=new Api([
                    "action"=>"smscodecheck",
                    "params"=>[
                        "phone"=>$phone,
                        "code"=>$code
                    ]
                ]);

                $result=$api->result();
                if ($result['success']==false){
                    switch ($result['data']['result']['errorCode']){
                        case 0:
                            throw new Exception("Не удалось подтвердить номер телефона", 3);
                        case 3:
                            throw new Exception("Не удалось подтвердить номер телефона", 4);
                        default:
                            throw new Exception("Произошла ошибка", 4);
                    }
                }
            }
            else{
                $api=new Api(array(
                    'action'=>'lkcode',
                    'params'=>[
                        "phone"=>$phone,
                        "code"=>$code
                    ]
                ));
                $result=$api->result();
                if ($result['success']==false){
                    switch ($result['data']['result']['errorCode']){
                        case 0:
                            throw new Exception("Не удалось подтвердить номер телефона", 3);
                        case 3:
                            throw new Exception("Не удалось подтвердить номер телефона", 4);
                        default:
                            throw new Exception("Произошла ошибка", 4);
                    }
                }
            }

            $_SESSION["CLIENT_ID"]=$phone;
            return ['result' => true, 'reload'=>true];
        }
    }

    public function interviewAction(){
        Loader::IncludeModule("iblock");
        $this->GetQuestions(true);
        $interviewData=array_combine(array_column($this->arResult["QUESTIONS"], "1C_NAME"), array_column($this->arResult["QUESTIONS"], "VALUE"));
        $arParams=[
            "interviewid"=>$this->arParams["INTERVIEW_ID"],
            "id1c"=>$this->arParams["CLIENT_ID"],
            "interviewdata"=>$interviewData
        ];
        $api=new Api([
            "action"=>"interview",
            "params"=>$arParams,
        ]);
        $responce=$api->result();
        if(empty($responce["success"]) ) {
            if(!empty($responce["data"]["result"]["userMessage"]) ) {
                throw new Exception($responce["data"]["result"]["userMessage"], 7);
            } else {
                throw new Exception("Непредвиденная ошибка", 7);
            }
        }
        $this->arResult["PROMOCODE"]=$responce["data"]["result"]["result"]["promocode"];

        ob_start();
        $this->IncludeComponentTemplate("result");
        $result=ob_get_clean();
        return $result;
    }

//    public function ConfigureActions(){
//        return [
//            'interview' => [
//                'prefilters' => [
//                    new ActionFilter\HttpMethod(
//                        array(ActionFilter\HttpMethod::METHOD_POST)
//                    ),
//                    new ActionFilter\Csrf(),
//                ],
//                'postfilters' => []
//            ],
//            'auth' => [
//                'prefilters' => [
//                    new ActionFilter\HttpMethod(
//                        array(ActionFilter\HttpMethod::METHOD_POST)
//                    ),
//                    new ActionFilter\Csrf(),
//                ],
//                'postfilters' => []
//            ]
//        ];
//    }
//
//    protected function listKeysSignedParameters()
//    {
//        return [  //массива параметров которые надо брать из параметров компонента
//            "INTERVIEW_ID",
//            "CLIENT_ID"
//        ];
//    }
//
//    function onPrepareComponentParams($arParams){
//        if(empty($arParams["INTERVIEW_ID"])){
//            $this->arResult["ERROR"] = "Не задан опрос";
//        }
////        if(empty($arParams["CLIENT_ID"])){
////            $this->arResult["ERROR"] = "Пользователь не задан";
////        }
//        return $arParams;
//    }
//
//    private function GetQuestions($request=false){
//        $IBLOCK_ID=Utils::GetIBlockIDBySID("interview-questions");
//
//        if (!is_numeric($this->arParams["INTERVIEW_ID"])){
//            $INTERVIEW_ID=Utils::GetIBlockSectionIDBySID($this->arParams["INTERVIEW_ID"]);
//        }
//
//        $DBRes=CIBlockSection::GetList(Array("SORT"=>"ASC"), array("ID"=>$INTERVIEW_ID, 'IBLOCK_ID'=>$IBLOCK_ID), false, array(
//            'ID',
//            'NAME',
//            'DESCRIPTION',
//            'PICTURE',
//            'UF_*'
//        ));
//        if ($interview=$DBRes->Fetch()){
//            $this->arResult["TITLE"]=$interview["NAME"];
//            $this->arResult["DESCRIPTION"]=$interview["DESCRIPTION"];
//            if (!empty($interview["PICTURE"])){
//                $this->arResult["HEADER_IMAGE"]=CFile::GetPath($interview["PICTURE"]);
//            }
//            $this->arResult["HEADER_DESCRIPTION"]=$interview["DESCRIPTION"];
//            $this->arResult['HEADER_BUTTON']=$interview['UF_INTERVIEW_BUTTON'];
//            $this->arResult['HEADER_BUTTON_LINK']=$interview['UF_INTERVIEW_BUTTON_LINK'];
//        }
//        else{
//            if(empty($arParams["INTERVIEW_ID"])){
//                $this->arResult["ERROR"] = "Не удалось найти опрос";
//            }
//        }
//
//        $objects=[];
//        $filter = ['ACTIVE'=>'Y', 'IBLOCK_ID'=>$IBLOCK_ID, "SECTION_ID"=>$INTERVIEW_ID];
//
//        $rows = CIBlockElement::GetList(array("SORT"=>"ASC"), $filter);
//        while ($row = $rows->fetch()) {
//            $row['PROPERTIES'] = [];
//            $objects[$row['ID']] =&$row;
//            unset($row);
//        }
//
//
//        CIBlockElement::GetPropertyValuesArray($objects, $IBLOCK_ID, $filter);
//
//
//        if (empty($objects)){
//            $this->arResult["ERROR"] = "Не удалось найти опрос";
//        }
//
//        foreach ($objects as $question){
//            $arQuestion=[
//                "ID"=>$question["ID"],
//                "TITLE"=>$question['PROPERTIES']["QUESTION_TITLE"]["VALUE"]["TEXT"],
//                "REQUIRED"=>!empty($question['PROPERTIES']["QUESTION_REQUIRED"]["VALUE"]),
//                "TYPE"=>$question['PROPERTIES']["QUESTION_TYPE"]["VALUE_XML_ID"],
//                "ANSWERS"=>$question['PROPERTIES']["ANSWER_VARS"],
//                "REQUIRED_FROM_ID"=>$question["PROPERTIES"]["REQUIRED_FROM"]["VALUE"],
//                "REQUIRED_FROM_VAL"=>$question["PROPERTIES"]["REQUIRED_FROM"]["DESCRIPTION"]
//            ];
//            $arQuestion["NAME"]="vote_".$arQuestion["TYPE"]."_".$arQuestion["ID"]."_".$arQuestion["ANSWERS"]["ID"];
//            if ($request){
//                $arQuestion["1C_NAME"]=$question["PROPERTIES"]["QUESTION_NAME"]["VALUE"];
//                $arQuestion["VALUE"]=Context::getCurrent()->getRequest()->getPost($arQuestion["NAME"]);
//            }
//            $this->arResult["QUESTIONS"][]=$arQuestion;
//
//        }
//    }
//
//    function executeComponent()
//    {
//        $this->GetQuestions();
//
//        if (!empty($this->arResult["ERROR"])) {
//            echo $this->arResult["ERROR"];
//            return;
//        }
//        $this->arResult["COMPONENT_NAME"]=$this->GetName();
//
//        if (empty($this->arParams["CLIENT_ID"])){
//            $this->IncludeComponentTemplate("client-id");
//        }
//        else{
//            $this->IncludeComponentTemplate();
//        }
//    }
//
//    public function interviewAction(){
//        Loader::IncludeModule("iblock");
//        $this->GetQuestions(true);
//        $interviewData=array_combine(array_column($this->arResult["QUESTIONS"], "1C_NAME"), array_column($this->arResult["QUESTIONS"], "VALUE"));
//        $arParams=[
//            "interviewid"=>$this->arParams["INTERVIEW_ID"],
//            "id1c"=>$this->arParams["CLIENT_ID"],
//            "interviewdata"=>$interviewData
//        ];
//        $api=new Api([
//            "action"=>"interview",
//            "params"=>$arParams,
//        ]);
//        $responce=$api->result();
//        if(empty($responce["success"]) ) {
//            if(!empty($responce["data"]["result"]["userMessage"]) ) {
//                throw new Exception($responce["data"]["result"]["userMessage"], 7);
//            } else {
//                throw new Exception("Непредвиденная ошибка", 7);
//            }
//        }
//        $this->arResult["PROMOCODE"]=$responce["data"]["result"]["result"]["promocode"];
//
//        ob_start();
//        $this->IncludeComponentTemplate("result");
//        $result=ob_get_clean();
//        return $result;
//    }
//
//    public function authAction($step, $phone, $code){
//        $phone=substr(preg_replace('![^0-9]+!', '', $phone), 1);
//        if ($phone[0]!='9' || strlen($phone)!=10){
//            throw new Exception('Формат телефона неверный', 1);
//        }
//        if ($step==1) {
//            $api = new Api(array(
//                'action' => 'lkcheck',
//                'params' => [
//                    "login" => $phone
//                ]
//            ));
//            $result = $api->result();
//            //Пользователь не найден, переход на регистрацию
//            if ($result['data']['result']['result'] == false) {
//                throw new Exception("Проверьте, пожалуйста, корректность введенного номера телефона", 1);
//            } else {
//                if ($result['data']['result']['errorCode'] == 2) {
//                    $api=new Api([
//                        "action"=>"smscode",
//                        "params"=>[
//                            "phone"=>$phone
//                        ]
//                    ]);
//                }
//            }
//
//        }
//
//
//        //Пользователь найден в 1с, завершаем регистрацию
//        //Возвращаем на фронт инфу
//
//        if ($step==1){
//
//        }
//        else{
//            $code=preg_replace('![^0-9]+!', '', $code);
//            if (strlen($code)!=5){
//                throw new Exception('Формат телефона неверный', 1);
//            }
//            $api=new Api([
//                "action"=>"smscodecheck",
//                "params"=>[
//                    "phone"=>$phone,
//                    "code"=>$code
//                ]
//            ]);
//        }
//
//        $response=$api->result();
//        if (!$response["success"]) {
//            if ($response["data"]["http_code"]==400){
//                throw new Exception($response["data"]["result"]["userMessage"], 2);
//            }
//            if (!empty($response["data"]["result"]["userMessage"])) {
//                throw new Exception($response["data"]["result"]["userMessage"], 1);
//            } else {
//                throw new Exception("Непредвиденная ошибка", 1);
//            }
//        }
//
//        if ($step==2){
//            $_SESSION["CLIENT_ID"]=$phone;
//            $_SESSION["INTERVIEW_ID"]=$this->arParams["INTERVIEW_ID"];
//        }
//        return true;
//    }
}
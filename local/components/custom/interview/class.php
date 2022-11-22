<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class Interview extends CBitrixComponent implements Controllerable {
    public function ConfigureActions(){
        return [
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

    protected function listKeysSignedParameters()
    {
        return [  //массива параметров которые надо брать из параметров компонента
            "INTERVIEW_ID",
            "CLIENT_ID"
        ];
    }

    function onPrepareComponentParams($arParams){
        if(empty($arParams["INTERVIEW_ID"])){
            $this->arResult["ERROR"] = "Не задан опрос";
        }
        if(empty($arParams["CLIENT_ID"])){
            $this->arResult["ERROR"] = "Пользователь не задан";
        }
        return $arParams;
    }

    private function GetQuestions($request=false){
        $IBLOCK_ID=Utils::GetIBlockIDBySID("interview-questions");

        if (!is_numeric($this->arParams["INTERVIEW_ID"])){
            $INTERVIEW_ID=Utils::GetIBlockSectionIDBySID($this->arParams["INTERVIEW_ID"]);
        }

        $DBRes=CIBlockSection::GetList(Array("SORT"=>"ASC"), array("ID"=>$INTERVIEW_ID, 'IBLOCK_ID'=>$IBLOCK_ID), false, array(
            'ID',
            'NAME',
            'DESCRIPTION',
            'PICTURE',
            'UF_*'
        ));
        if ($interview=$DBRes->Fetch()){
            $this->arResult["TITLE"]=$interview["NAME"];
            $this->arResult["DESCRIPTION"]=$interview["DESCRIPTION"];
            if (!empty($interview["PICTURE"])){
                $this->arResult["HEADER_IMAGE"]=CFile::GetPath($interview["PICTURE"]);
            }
            $this->arResult["HEADER_DESCRIPTION"]=$interview["DESCRIPTION"];
            $this->arResult['HEADER_BUTTON']=$interview['UF_INTERVIEW_BUTTON'];
            $this->arResult['HEADER_BUTTON_LINK']=$interview['UF_INTERVIEW_BUTTON_LINK'];
        }
        else{
            if(empty($arParams["INTERVIEW_ID"])){
                $this->arResult["ERROR"] = "Не удалось найти опрос";
            }
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
                "REQUIRED_FROM_VAL"=>$question["PROPERTIES"]["REQUIRED_FROM"]["DESCRIPTION"]
            ];
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
        $this->GetQuestions();

        if (!empty($this->arResult["ERROR"])) {
            echo $this->arResult["ERROR"];
            return;
        }
        $this->arResult["COMPONENT_NAME"]=$this->GetName();


//        var_dump($this->arResult);
        $this->IncludeComponentTemplate();
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
}
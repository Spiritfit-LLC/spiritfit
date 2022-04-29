<?php

AddEventHandler("main", "OnAfterUserAuthorize", Array("PersonalUtils", "UpdateFieldsAfterLogin"));

class PersonalUtils{
    public static function GetIDBySID($FORM_SID){
        $rsForm = CForm::GetBySID($FORM_SID);
        $arForm = $rsForm->Fetch();
        return $arForm['ID'];
    }

    public static function GetFormFileds($WEB_FORM_ID, $ACTION=false, $event_1c=false, $check=false){
        if ($check){
            $error = CForm::Check($WEB_FORM_ID, $_REQUEST);
            if (strlen($error)>0){
                return ['result'=>false, 'errorText'=>$error];
            }
        }


        $status = CForm::GetDataByID($WEB_FORM_ID, $arResult["arForm"], $arResult["arQuestions"], $arResult["arAnswers"], $arResult["arDropDown"], $arResult["arMultiSelect"]);
        if( $status ) {
            $FORM_FIELDS=['VISIBLE'=>[], 'HIDDEN'=>[]];
            $issetFLAG=true;
            foreach($arResult["arAnswers"] as $key=>$value){
                $by= "s_sort";
                $order = "asc";
                $validator=CFormValidator::GetList($arResult["arQuestions"][$key]['ID'], array(), $by,$order)->Fetch();

                if ($value['0']["FIELD_TYPE"]=='hidden'){
                    $FORM_FIELDS['HIDDEN'][$key]=[
                        'NAME'=>"form_" . $value['0']["FIELD_TYPE"] . "_" . $value['0']["ID"],
                        'VALUE'=>$_REQUEST["form_" . $value['0']["FIELD_TYPE"] . "_" . $value['0']["ID"]],
                        'TYPE'=>$value['0']["FIELD_TYPE"],
                        'REQUIRED'=>$arResult["arQuestions"][$key]["REQUIRED"]=="Y"? true:false,
                    ];
                }
                else{
                    if ($key=="club"){
                        $arFilter = array(
                            "IBLOCK_CODE" => 'clubs',
                            "PROPERTY_SOON" => false,
                            "ACTIVE" => "Y",
                            "PROPERTY_HIDE_LINK_VALUE"=>false
                        );

                        $dbElements = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilter, false, false, array("ID", "CODE", "NAME", "PROPERTY_NUMBER"));
                        while ($res = $dbElements->fetch()) {
                            $CLUBS[]=array(
                                'VALUE'=>$res["PROPERTY_NUMBER_VALUE"],
                                'STRING'=>$res["NAME"]
                            );
                        }
                        $FORM_FIELDS['VISIBLE'][$key]=[
                            'NAME'=>"form_" . $value['0']["FIELD_TYPE"] . "_" . $value['0']["ID"],
                            'PLACEHOLDER'=>$arResult["arQuestions"][$key]["TITLE"],
                            'TYPE'=>'SELECT',
                            'ITEMS'=>$CLUBS,
                            "COMMENT"=>$arResult["arQuestions"][$key]["COMMENTS"],
                            'VALUE'=>$_REQUEST["form_" . $value['0']["FIELD_TYPE"] . "_" . $value['0']["ID"]],
                        ];
                    }
                    else{
                        if ($key=="phone"){
                            if (!empty($_REQUEST["form_" . $value['0']["FIELD_TYPE"] . "_" . $value['0']["ID"]])){
                                $valbuff=substr(preg_replace('![^0-9]+!', '', $_REQUEST["form_" . $value['0']["FIELD_TYPE"] . "_" . $value['0']["ID"]]), 1);
                                if ($valbuff[0]!='9' || strlen($valbuff)!=10){
                                    $valbuff=false;
                                }
                            }
                            else{
                                $valbuff=false;
                            }
                        }
                        elseif ($value['0']["FIELD_TYPE"]=="email"){
                            if (!empty($_REQUEST["form_" . $value['0']["FIELD_TYPE"] . "_" . $value['0']["ID"]])){
                                $valbuff=check_email($_REQUEST["form_" . $value['0']["FIELD_TYPE"] . "_" . $value['0']["ID"]])?$_REQUEST["form_" . $value['0']["FIELD_TYPE"] . "_" . $value['0']["ID"]]:false;
                            }
                            else{
                                $valbuff=false;
                            }
                        }
                        else{
                            $valbuff=$_REQUEST["form_" . $value['0']["FIELD_TYPE"] . "_" . $value['0']["ID"]];
                        }
                        $FORM_FIELDS['VISIBLE'][$key]=[
                            'NAME'=>"form_" . $value['0']["FIELD_TYPE"] . "_" . $value['0']["ID"],
                            'PLACEHOLDER'=>$arResult["arQuestions"][$key]["TITLE"],
                            'VALUE'=>$valbuff,
                            'TYPE'=>$key=="phone" ? "tel" : $value['0']["FIELD_TYPE"],
                            'REQUIRED'=>$arResult["arQuestions"][$key]["REQUIRED"]=="Y" ? true:false,
                            "COMMENT"=>$arResult["arQuestions"][$key]["COMMENTS"],
                        ];

                        if (!empty($validator)) {
                            $FORM_FIELDS['VISIBLE'][$key]['VALIDATOR']=$validator['PARAMS'];
                        }
//                        var_dump($FORM_FIELDS['VALIDATOR']);

                    }
                    if ($FORM_FIELDS['VISIBLE'][$key]["REQUIRED"] && empty($FORM_FIELDS['VISIBLE'][$key]['VALUE'])){
                        $issetFLAG=false;
                    }
                }


            }
            $FORM_FIELDS['HIDDEN']["WEB_FORM_ID"]=[
                'NAME'=>"WEB_FORM_ID",
                'PLACEHOLDER'=>null,
                'VALUE'=>$WEB_FORM_ID,
                'TYPE'=>"hidden",
                'REQUIRED'=>true,
            ];


            $FORM_FIELDS['HIDDEN']["ACTION"]=[
                'NAME'=>"ACTION",
                'PLACEHOLDER'=>null,
                'VALUE'=>!empty($ACTION) ? $ACTION : $_REQUEST["ACTION"],
                'TYPE'=>"hidden",
                'REQUIRED'=>true,
            ];

            $FORM_FIELDS['HIDDEN']['EVENT_1C']=[
                'NAME'=>"EVENT_1C",
                'PLACEHOLDER'=>null,
                'VALUE'=>!empty($event_1c) ? $event_1c : $_REQUEST["EVENT_1C"],
                'TYPE'=>"hidden",
                'REQUIRED'=>true,
            ];


            $FORM_FIELDS['ISSET']=$issetFLAG;
            return $FORM_FIELDS;
        }
        return ['result'=>false, 'errorText'=>'Произошла внутренняя ошибка'];
    }

    public static function GetPersonalPageFormFields($user_id, $change=false, $request_info=true, $ACTION=false, $for_other=false, $code=[]){
        $rsUser = CUser::GetByID($user_id);
        $arUser = $rsUser->Fetch();

        $objects=[];
        $filter = ['IBLOCK_CODE' => 'LK_FIELDS', 'ACTIVE'=>'Y', 'CODE'=>$code];

        if ($for_other){
            $order=['PROPERTY_POSITION_FOR_OTHERS'=>'ASC'];
            $filter['PROPERTY_SHOW_TO_OTHERS_VALUE']='Y';
        }
        else{
            $order = ['SORT' => 'ASC'];
        }

        $rows = CIBlockElement::GetList($order, $filter);
        while ($row = $rows->fetch()) {
            $row['PROPERTIES'] = [];
            $objects[$row['ID']] =& $row;
            $filter['IBLOCK_ID']=$row['IBLOCK_ID'];
            unset($row);
        }

        CIBlockElement::GetPropertyValuesArray($objects, $filter['IBLOCK_ID'], $filter);
        unset($rows, $filter, $order);
        $LK_FIELDS=['HIDDEN'=>[], 'VISIBLE'=>[]];
        $issetFLAG=true;
        foreach ($objects as $id=>$element){
            if (($change==false && $element['PROPERTIES']['SHOWING_ONLY_CHANGE']['VALUE']=='Y') ||
                ($request_info && $element['PROPERTIES']['CHANGEBLE']['VALUE']!='Y') ||
                ($request_info && empty($_REQUEST["form_".$element['CODE']."_".$id]) && $element['PROPERTIES']['REQUIRED']['VALUE']!='Y')){
                continue;
            }
            $FIELD=[
                'NAME'=>"form_" . $element['CODE'] . "_" . $id,
                'PLACEHOLDER'=>$for_other?$element['PROPERTIES']['TITLE_FOR_OTHERS']['VALUE']:$element['PROPERTIES']['FIELD_TITLE']['VALUE'],
                'TYPE'=>$element['PROPERTIES']['FIELD_TYPE']['VALUE_XML_ID'],
                'REQUIRED'=>$element['PROPERTIES']['REQUIRED']['VALUE']=='Y'?true:false,
                'CHANGEBLE'=>$element['PROPERTIES']['CHANGEBLE']['VALUE']=='Y'?true:false,
                'IN_HEAD'=>$element['PROPERTIES']['IN_HEAD']['VALUE']=='Y'?true:false,
                'USER_FIELD_CODE'=>$element['PROPERTIES']['USER_FIELD_CODE']['VALUE'],
                'REQUIRED_FROM'=>!empty($element['PROPERTIES']['REQUIRED_FROM']['VALUE'])?$element['PROPERTIES']['REQUIRED_FROM']['VALUE']:false,
                'REQUIRED_ID'=>$element['CODE'],
                'ADDITIONAL_CLASSNAME'=>$element['PROPERTIES']['ADDITIONAL_CLASSNAME']['VALUE'],
                'MIN_LENGTH'=>$element['PROPERTIES']['MIN_LENGTH']['VALUE'],
                'MAX_LENGTH'=>$element['PROPERTIES']['MAX_LENGTH']['VALUE'],
                'MASK'=>$element['PROPERTIES']['MASK']['VALUE'],
            ];

            if ($for_other){
                $FIELD['POSITION']=$element['PROPERTIES']['POSITION_FOR_OTHERS']['VALUE'];
            }

            if ($change && $request_info && $FIELD['CHANGEBLE']){
                $FIELD['VALUE']=empty($_REQUEST["form_".$element['CODE']."_".$id]) || strlen($_REQUEST["form_".$element['CODE']."_".$id])==0?false:$_REQUEST["form_".$element['CODE']."_".$id];
            }
            else{
                if($element['CODE']=='phone'){
                    $FIELD['VALUE']=Utils::phone_format($arUser[$element['PROPERTIES']['USER_FIELD_CODE']['VALUE']]);
                }
                elseif($element['CODE']=='user-password' || $element['CODE']=='user-confirm-password'){
                    $FIELD['VALUE']=null;
                }
                else{
                    $FIELD['VALUE']=$arUser[$element['PROPERTIES']['USER_FIELD_CODE']['VALUE']];
                }
            }

            if ($FIELD["REQUIRED"] && empty($FIELD['VALUE'])){
                $issetFLAG=false;
            }

            if (!$for_other && !$request_info){
                if ($FIELD['IN_HEAD']){
                    $LK_FIELDS['VISIBLE']['HEAD'][$element['CODE']]=$FIELD;
                }
                else{
                    $LK_FIELDS['VISIBLE']['NOTHEAD'][$element['CODE']]=$FIELD;
                }
            }
            elseif ($request_info){
                $LK_FIELDS['VISIBLE'][$element['CODE']]=$FIELD;
            }
            else{
                if (is_array($LK_FIELDS['VISIBLE'][$FIELD['POSITION']])){
                    array_push($LK_FIELDS['VISIBLE'][$FIELD['POSITION']], $FIELD);
                }
                else{
                    $LK_FIELDS['VISIBLE'][$FIELD['POSITION']]=[$FIELD];
                }
            }

        }
        if ($ACTION){
            $LK_FIELDS['HIDDEN']["ACTION"]=[
                'NAME'=>"ACTION",
                'PLACEHOLDER'=>null,
                'VALUE'=>!empty($ACTION) ? $ACTION : $_REQUEST["ACTION"],
                'TYPE'=>"hidden",
                'REQUIRED'=>true,
            ];
        }


        $settings = Utils::getInfo();
        $LK_FIELDS['PERSONAL_PHOTO']=!empty($arUser['PERSONAL_PHOTO'])?$arUser['PERSONAL_PHOTO']:$settings["PROPERTIES"]['PROFILE_DEFAULT_PHOTO']['VALUE'];
        if (!$for_other){
            $LK_FIELDS['OLD_PHOTO_ID']=$arUser['PERSONAL_PHOTO'];
        }
        $LK_FIELDS['PERSONAL_PHOTO']=CFile::GetPath($arUser['PERSONAL_PHOTO']);

        $LK_FIELDS['ISSET']=$issetFLAG;

        return $LK_FIELDS;
    }

    private static function GetUpdatebleFrom1CPersonalInfo(){
        $objects=[];
        $filter = [
            'IBLOCK_CODE' => 'LK_FIELDS',
            'ACTIVE'=>'Y',
            '!PROPERTY_CODE_1C'=>false];
        $order = ['SORT' => 'ASC'];

        $rows = CIBlockElement::GetList($order, $filter);
        while ($row = $rows->fetch()) {
            $row['PROPERTIES'] = [];
            $objects[$row['ID']] =& $row;
            $filter['IBLOCK_ID']=$row['IBLOCK_ID'];
            unset($row);
        }
        CIBlockElement::GetPropertyValuesArray($objects, $filter['IBLOCK_ID'], $filter, ['CODE'=>['CODE_1C', 'USER_FIELD_CODE']]);
        foreach ($objects as $id=>$element){
            $RESULT[$element['PROPERTIES']['CODE_1C']['VALUE']]=$element['PROPERTIES']['USER_FIELD_CODE']['VALUE'];
        }
        return $RESULT;
    }

    public static function UpdatePersonalInfoFrom1C($user_id, $user=false){
        $UPDATEBLE_FIELDS=self::GetUpdatebleFrom1CPersonalInfo();

        if (empty($user_fields) && !empty($user['LOGIN']) && !empty($user['UF_1CID'])){
            $login=$user['LOGIN'];
            $ID_1C=$user['UF_1CID'];
            $USER_ID=$user['ID'];
        }
        elseif (!empty($user_id)){
            $rsUser = CUser::GetByID($user_id);
            if ($arUser2 = $rsUser->Fetch()){
                $login=$arUser2['LOGIN'];
                $ID_1C=$arUser2['UF_1CID'];
                $USER_ID=$user_id;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }

        $api=new Api([
           'action'=>'lkinfo',
           'params'=>[
               'login'=>$login,
               'id1c'=>$ID_1C,
           ],
        ]);
        $result=$api->result();

        if ($result['success']){
            $fields=$result['data']['result']['result'];
            foreach ($fields as $key=>$value){
                if (key_exists($key, $UPDATEBLE_FIELDS)){
                    $usUpdateArr[$UPDATEBLE_FIELDS[$key]]=$value;
                }
            }
            global $USER;
            $usUpdateArr['UF_LAST_UPDATE_TIME']=date('d.m.Y H:i:s');
            if ($USER->Update($USER_ID, $usUpdateArr, false)){
                return $usUpdateArr;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    public function UpdateFieldsAfterLogin($arUser){
        $rsUser = CUser::GetByID($arUser['user_fields']['ID']);
        if ($arUser2 = $rsUser->Fetch())
        {
            if (!empty($arUser2['UF_LAST_UPDATE_TIME'])){
                $update_time = new DateTime($arUser2['UF_LAST_UPDATE_TIME']);
                $now=new DateTime();
                $interval=$update_time->diff($now);
                $minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
                if ($minutes>180){
                    self::UpdatePersonalInfoFrom1C(false, $arUser2);
                }
            }
            else{
                self::UpdatePersonalInfoFrom1C(false, $arUser2);
            }
        }
    }
}
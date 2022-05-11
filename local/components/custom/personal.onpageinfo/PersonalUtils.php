<?php

AddEventHandler("main", "OnAfterUserAuthorize", Array("PersonalUtils", "UpdateFieldsAfterLogin"));

class PersonalUtils{
    public static function GetIDBySID($FORM_SID){
        $rsForm = CForm::GetBySID($FORM_SID);
        $arForm = $rsForm->Fetch();
        return $arForm['ID'];
    }

    public static function GetFormFileds($WEB_FORM_ID, $ACTION='', $request_info=false, $btn_text=false, $active=false){
        if ($request_info){
            $error = CForm::Check($WEB_FORM_ID, $_REQUEST);
            if (strlen($error)>0){
                return ['result'=>false, 'errorText'=>$error];
            }
        }


        $status = CForm::GetDataByID($WEB_FORM_ID, $arResult["arForm"], $arResult["arQuestions"], $arResult["arAnswers"], $arResult["arDropDown"], $arResult["arMultiSelect"]);
        if( $status ) {
            $FORM_FIELDS=[
                'NAME'=>$arResult['arForm']['NAME'],
                'ISSET'=>true,
            ];

            foreach($arResult["arAnswers"] as $key=>$value){
                $by= "s_sort";
                $order = "asc";
                $validator=CFormValidator::GetList($arResult["arQuestions"][$key]['ID'], array(), $by,$order)->Fetch();

                $FORM_FIELDS['FIELDS'][$key]=[
                    'PLACEHOLDER'=>$arResult["arQuestions"][$key]["TITLE"],
                    'TYPE'=>$key=="phone" ? "tel" : $value['0']["FIELD_TYPE"],
                    'REQUIRED'=>$arResult["arQuestions"][$key]["REQUIRED"]=="Y" ? true:false,
                    "COMMENT"=>$arResult["arQuestions"][$key]["COMMENTS"],
                ];
                if ($value['0']["FIELD_TYPE"]=='radio'){
                    $FORM_FIELDS['FIELDS'][$key]['NAME']="form_" . $value['0']["FIELD_TYPE"] . "_" . $arResult['arQuestions'][$key]['SID'];
                }
                else{
                    $FORM_FIELDS['FIELDS'][$key]['NAME']="form_" . $value['0']["FIELD_TYPE"] . "_" . $value['0']["ID"];
                }

                if (!$request_info){
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
                        $FORM_FIELDS['FIELDS'][$key]['TYPE']='SELECT';
                        $FORM_FIELDS['FIELDS'][$key]['ITEMS']=$CLUBS;
                    }

                    if ($value['0']["FIELD_TYPE"]=='radio'){
                        foreach($value as $val){
                            $FORM_FIELDS['FIELDS'][$key]['VALUE'][]=$val["ID"];
                            $FORM_FIELDS['FIELDS'][$key]['VALUE_DESC'][]=$val['MESSAGE'];
                        }
                    }
                    else{
                        $FORM_FIELDS['FIELDS'][$key]['VALUE']=$value['0']['VALUE'];
                    }

                    if (!empty($validator)) {
                        $validate_text='';
                        foreach ($validator['PARAMS'] as $KEY=>$PARAM){
                            if ($KEY=="LENGTH_FROM"){
                                $validate_text.=' min-length='.$PARAM;
                            }
                            elseif($KEY=="LENGTH_TO"){
                                $validate_text.=' max-length='.$PARAM;
                            }
                            elseif($KEY=="AGE_FROM"){
                                $time = strtotime("-".$PARAM." year", time());
                                $date = date("d.m.Y", $time);
                                $validate_text.=' data-max="'.$date.'"';
                            }
                            elseif($KEY=="AGE_TO"){
                                $time = strtotime("-".$PARAM." year", time());
                                $date = date("d.m.Y", $time);
                                $validate_text.=' data-min="'.$date.'"';
                            }
                        }
                        $FORM_FIELDS['FIELDS'][$key]['VALIDATOR']=$validate_text;
                    }
                }
                else{
                    if ($key=="phone"){
                        if (!empty($_REQUEST[$FORM_FIELDS['FIELDS'][$key]['NAME']])){
                            $valbuff=substr(preg_replace('![^0-9]+!', '', $_REQUEST[$FORM_FIELDS['FIELDS'][$key]['NAME']]), 1);
                            if ($valbuff[0]!='9' || strlen($valbuff)!=10){
                                $valbuff=false;
                            }
                        }
                        else{
                            $valbuff=false;
                        }
                    }
                    else{
                        $valbuff=$_REQUEST[$FORM_FIELDS['FIELDS'][$key]['NAME']];
                    }

                    if ($value['0']["FIELD_TYPE"]=="radio"){
                        $rsAnswer = CFormAnswer::GetByID($_REQUEST[$FORM_FIELDS['FIELDS'][$key]['NAME']]);
                        $arAnswer = $rsAnswer->Fetch();
                        $valbuff=$arAnswer['VALUE'];
                    }
                    $FORM_FIELDS['FIELDS'][$key]['VALUE']=$valbuff;
                }



                if ($FORM_FIELDS['FIELDS'][$key]["REQUIRED"] && empty($FORM_FIELDS['FIELDS'][$key]['VALUE'])){
                    $FORM_FIELDS['ISSET']=false;
                }
            }
            $FORM_FIELDS["WEB_FORM_ID"]=$WEB_FORM_ID;
            $FORM_FIELDS['ACTION']=$request_info?$_REQUEST['ACTION']:$ACTION;
            if(!empty($btn_text)){
                $FORM_FIELDS['BTN_TEXT']=$btn_text;
            }
//            var_dump(json_encode($FORM_FIELDS, JSON_UNESCAPED_UNICODE));

            $FORM_FIELDS['ACTIVE']=$active;
            return $FORM_FIELDS;
        }
    }

    public static function GetPersonalPageFormFields($user_id, $request_info=false, $code=[], $section_id=false){

        $rsUser = CUser::GetByID($user_id);
        $arUser = $rsUser->Fetch();

        $IBLOCK_ID=Utils::GetIBlockIDBySID('LK_FIELDS');

        if (empty($section_id)){
            $dbRes=CIBlockSection::GetList(Array("SORT"=>"ASC"), array('ACTIVE'=>'Y','IBLOCK_ID'=>$IBLOCK_ID), false, array('UF_LK_SECTION_ICON', 'UF_ACTION', 'UF_BTN_TEXT'));
            while($ar_result = $dbRes->GetNext())
            {
                $LK_FIELDS['SECTIONS'][$ar_result['ID']]=[
                    'NAME'=>$ar_result['NAME'],
                    "ICON"=>CFile::GetPath($ar_result['UF_LK_SECTION_ICON']),
                    'ACTION'=>!empty($ar_result['UF_ACTION'])?$ar_result['UF_ACTION']:false,
                    'BTN_TEXT'=>!empty($ar_result['UF_BTN_TEXT'])?$ar_result['UF_BTN_TEXT']:false,
                ];
            }
        }
        else{
            $LK_FIELDS['SECTIONS'][$section_id]=[];
        }


        $objects=[];
        $filter = ['SECTION_ID' => array_keys($LK_FIELDS['SECTIONS']), 'ACTIVE'=>'Y', 'CODE'=>$code];
        $order = ['SORT' => 'ASC'];


        $rows = CIBlockElement::GetList($order, $filter);
        while ($row = $rows->fetch()) {
            $row['PROPERTIES'] = [];
            $objects[$row['ID']] =& $row;
            $filter['IBLOCK_ID']=$row['IBLOCK_ID'];
            unset($row);
        }

        CIBlockElement::GetPropertyValuesArray($objects, $filter['IBLOCK_ID'], $filter);
        unset($rows, $filter, $order);


        $issetFLAG=true;
        foreach ($objects as $id=>$element){
            $FIELD=[
                'NAME'=>"form_" . $element['CODE'] . "_" . $id,
                'TYPE'=>$element['PROPERTIES']['FIELD_TYPE']['VALUE_XML_ID'],
                'REQUIRED'=>$element['PROPERTIES']['REQUIRED']['VALUE']=='Y'?true:false,
                'CHANGEBLE'=>$element['PROPERTIES']['CHANGEBLE']['VALUE']=='Y'?true:false,
                'IN_HEAD'=>$element['PROPERTIES']['IN_HEAD']['VALUE']=='Y'?true:false,
                'USER_FIELD_CODE'=>$element['PROPERTIES']['USER_FIELD_CODE']['VALUE'],
                'REQUIRED_FROM'=>!empty($element['PROPERTIES']['REQUIRED_FROM']['VALUE'])?$element['PROPERTIES']['REQUIRED_FROM']['VALUE']:false,
                'REQUIRED_ID'=>$element['CODE'],
                'PLACEHOLDER'=>$element['PROPERTIES']['FIELD_TITLE']['VALUE'],
                'SHOW_PLACEHOLDER'=>$element['PROPERTIES']['SHOW_TITLE_IN_HEAD']['VALUE']=='Y',
                'VALIDATOR'=>$element['PROPERTIES']['VALIDATOR']['VALUE'],
            ];

            if ($request_info){
                if (!$FIELD['CHANGEBLE']){
                    continue;
                }
                $FIELD['VALUE']=empty($_REQUEST["form_".$element['CODE']."_".$id]) || strlen($_REQUEST["form_".$element['CODE']."_".$id])==0?false:$_REQUEST["form_".$element['CODE']."_".$id];
                if ($FIELD["REQUIRED"] && empty($FIELD['VALUE'])){
                    $issetFLAG=false;
                }
            }
            else{
                if($element['CODE']=='client-phone'){
                    $FIELD['VALUE']=Utils::phone_format($arUser[$element['PROPERTIES']['USER_FIELD_CODE']['VALUE']]);
                }
                elseif($element['CODE']=='client-password' || $element['CODE']=='client-password-confirm'){
                    $FIELD['VALUE']=null;
                }
                else{
                    if($FIELD['TYPE']=='radio'){
                        foreach($element['PROPERTIES']['RADIO_VALUES']['VALUE'] as $val){
                            $VALUE[]=[
                                'RADIO_VAL'=>$val,
                                'CHECKED'=>$val==$arUser[$element['PROPERTIES']['USER_FIELD_CODE']['VALUE']]?true:false,
                            ];
                        }
                        $FIELD['VALUE']=$VALUE;
                        $FIELD['VALUE_DESC']=$element['PROPERTIES']['RADIO_VALUES']['DESCRIPTION'];
                    }
                    else{
                        $FIELD['VALUE']=$arUser[$element['PROPERTIES']['USER_FIELD_CODE']['VALUE']];
                    }
                }

            }

            if ($FIELD['IN_HEAD'] && !$request_info){
                $LK_FIELDS['HEAD']['FIELDS'][]=$FIELD;
            }

            $LK_FIELDS['SECTIONS'][$element['IBLOCK_SECTION_ID']]['FIELDS'][]=$FIELD;
        }
        $LK_FIELDS['ISSET']=$issetFLAG;


        if (!$request_info) {
            global $settings;
            $image=CFile::GetPath(!empty($arUser['PERSONAL_PHOTO']) ? $arUser['PERSONAL_PHOTO'] : $settings["PROPERTIES"]['PROFILE_DEFAULT_PHOTO']['VALUE']);
            list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $image);
            if ($height > 300 || $width>300) {
                $ratio = $height < $width?300 / $height:300/$width;
                if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/upload/user_avatars/' . basename($image))) {
                    $img = Utils::resize_image($_SERVER['DOCUMENT_ROOT'] . $image, $ratio);
                    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/upload/user_avatars/')) {
                        mkdir($_SERVER['DOCUMENT_ROOT'] . '/upload/user_avatars/', 0777, true);
                    }
                    imagejpeg($img, $_SERVER['DOCUMENT_ROOT'] . '/upload/user_avatars/' . basename($image));
                }
                $img_src = '/upload/user_avatars/' . basename($image);
            } else {
                $img_src = $image;
            }
            $LK_FIELDS['HEAD']['PERSONAL_PHOTO'] = $img_src;
            $LK_FIELDS['OLD_PHOTO_ID']=$arUser['PERSONAL_PHOTO'];
        }

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


        if (!empty($user) && !empty($user['LOGIN']) && !empty($user['UF_1CID'])){
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
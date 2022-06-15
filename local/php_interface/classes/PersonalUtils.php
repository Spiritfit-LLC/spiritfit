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

    public static function GetPersonalPageFormFields($user_id, $request_info=false, $code=[], $section_id=false, $active_form=false, $photo_size=300){
        function GetSectionFields(&$ar_SectionList, $request_info, $code, $is_correct, $arUser, &$HEAD){
            $objects=[];
            $filter = ['SECTION_ID' => array_keys($ar_SectionList), 'ACTIVE'=>'Y', 'CODE'=>$code, '!PROPERTY_HIDE_ON_FORM_VALUE'=>'Да'];
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
            $GROUPS=[];

            $i=0;
            foreach ($objects as $id=>$element){
//                var_dump(array($element['PROPERTIES']['USER_FIELD_CODE']['VALUE'],$arUser[$element['PROPERTIES']['USER_FIELD_CODE']['VALUE']]));
                if ($element['PROPERTIES']['HIDE_IF_EMPTY']['VALUE_XML_ID']=='Y'
                    && (!empty($element['PROPERTIES']['USER_FIELD_CODE']['VALUE']) &&
                        empty($arUser[$element['PROPERTIES']['USER_FIELD_CODE']['VALUE']]))){
                    continue;
                }

                $FIELD=[
                    'NAME'=>"form_" . $element['CODE'] . "_" . $id,
                    'TYPE'=>$element['PROPERTIES']['FIELD_TYPE']['VALUE_XML_ID'],
                    'CHANGEBLE'=>$element['PROPERTIES']['CHANGEBLE']['VALUE_XML_ID']=='Y'?true:false,
                    'IN_HEAD'=>$element['PROPERTIES']['IN_HEAD']['VALUE_XML_ID']=='Y'?true:false,
                    'USER_FIELD_CODE'=>$element['PROPERTIES']['USER_FIELD_CODE']['VALUE'],
                    'REQUIRED_FROM'=>!empty($element['PROPERTIES']['REQUIRED_FROM']['VALUE'])?$element['PROPERTIES']['REQUIRED_FROM']['VALUE']:false,
                    'REQUIRED_ID'=>$element['CODE'],
                    'PLACEHOLDER'=>$element['PROPERTIES']['FIELD_TITLE']['VALUE'],
                    'SHOW_PLACEHOLDER'=>$element['PROPERTIES']['SHOW_TITLE_IN_HEAD']['VALUE_XML_ID']=='Y',
                    'VALIDATOR'=>$element['PROPERTIES']['VALIDATOR']['VALUE'],
                    'CLUE'=>!empty($element['PROPERTIES']['CLUE']['VALUE'])?$element['PROPERTIES']['CLUE']['VALUE']:false,
                ];

                if ($FIELD['TYPE']=='password' && !$is_correct){
                    $FIELD["REQUIRED"]=true;
                }
                else{
                    $FIELD["REQUIRED"]=$element['PROPERTIES']['REQUIRED']['VALUE']=='Y'?true:false;
                }

                if ($request_info){
                    if (!$FIELD['CHANGEBLE']){
                        continue;
                    }

                    $FIELD['VALUE']=empty($_REQUEST["form_".$element['CODE']."_".$id]) || strlen($_REQUEST["form_".$element['CODE']."_".$id])==0?false:$_REQUEST["form_".$element['CODE']."_".$id];
                    if ($FIELD["REQUIRED"] && empty($FIELD['VALUE'])){
                        $issetFLAG=false;
                    }
                    switch ($FIELD['TYPE']){
                        case 'checkbox':
                            $FIELD['VALUE']=boolval($FIELD['VALUE']);
                            break;
                    }
                }
                else{
                    switch ($FIELD['TYPE']){
                        case 'nonclick-phone':
                        case 'click-phone':
                            $FIELD['VALUE']=Utils::phone_format($arUser[$element['PROPERTIES']['USER_FIELD_CODE']['VALUE']]);
                            if ($FIELD['CHANGEBLE']){
                                $FIELD['TYPE']='tel';
                            }
                            else{
                                $FIELD['TYPE']='text';
                            }
                            break;
                        case 'password':
                            $FIELD['VALUE']=null;
                            break;
                        case 'price':
                            $FIELD['TYPE']='text';
                            $FIELD['VALUE']=$arUser[$element['PROPERTIES']['USER_FIELD_CODE']['VALUE']].' руб.';
                            break;
                        case 'radio':
                            foreach($element['PROPERTIES']['RADIO_VALUES']['VALUE'] as $val){
                                $VALUE[]=[
                                    'RADIO_VAL'=>$val,
                                    'CHECKED'=>$val==$arUser[$element['PROPERTIES']['USER_FIELD_CODE']['VALUE']]?true:false,
                                ];
                            }
                            $FIELD['VALUE']=$VALUE;
                            $FIELD['VALUE_DESC']=$element['PROPERTIES']['RADIO_VALUES']['DESCRIPTION'];
                            break;
                        case 'link':
                            $FIELD['VALUE']=$element['PROPERTIES']['LINK']['VALUE'];
                            if (!isset($arUser[$element['PROPERTIES']['USER_FIELD_CODE']['VALUE']])){
                                $FIELD['CLICKABBLE']=true;
                            }
                            else{
                                if (empty($arUser[$element['PROPERTIES']['USER_FIELD_CODE']['VALUE']])){
                                    $FIELD['CLICKABBLE']=false;
                                }
                                else{
                                    $FIELD['CLICKABBLE']=true;
                                }
                            }
                            break;
                        default:
                            $FIELD['VALUE']=$arUser[$element['PROPERTIES']['USER_FIELD_CODE']['VALUE']];
                            break;
                    }

                    if ($FIELD['USER_FIELD_CODE']=='UF_PAYMENT_SUM' && !empty($arUser['UF_PAYMENT_BONUSES'])){
                        $old_sum=(int)$arUser['UF_PAYMENT_SUM']+(int)$arUser['UF_PAYMENT_BONUSES'];
                        $FIELD['OLD_SUM']=$old_sum;
                    }
                }

                if ($FIELD['IN_HEAD'] && !$request_info){
                    $HEAD['FIELDS'][]=$FIELD;
                }
                if ($element['PROPERTIES']['GROUP']['VALUE']!==""){
                    if (empty($GROUPS[$element['PROPERTIES']['GROUP']['VALUE']])){
                        $GROUPS[$element['PROPERTIES']['GROUP']['VALUE']]['POSITION']=count($ar_SectionList[$element['IBLOCK_SECTION_ID']]['FIELDS']);
                        $GROUPS[$element['PROPERTIES']['GROUP']['VALUE']]['SECTION_ID']=$element['IBLOCK_SECTION_ID'];
                        $ar_SectionList[$element['IBLOCK_SECTION_ID']]['FIELDS'][]=$i;
                    }
                    $GROUPS[$element['PROPERTIES']['GROUP']['VALUE']]['FIELDS'][]=$FIELD;

                }
                else{
                    $ar_SectionList[$element['IBLOCK_SECTION_ID']]['FIELDS'][]=$FIELD;
                }
                $i++;
            }
            foreach($GROUPS as $key=>$GROUP){
//            $arr=array(['GROUP'=>$GROUP['FIELDS']]);
//            array_splice( $ar_SectionList[$GROUP['SECTION_ID']]['FIELDS'], $GROUP['POSITION'], 0, $arr);
                $ar_SectionList[$GROUP['SECTION_ID']]['FIELDS'][$GROUP['POSITION']]=['GROUP'=>$GROUP['FIELDS']];
            }

            return $issetFLAG;
        }



        $rsUser = CUser::GetByID($user_id);
        $arUser = $rsUser->Fetch();
        $IBLOCK_ID=Utils::GetIBlockIDBySID('LK_FIELDS');

        $LK_FIELDS['IS_CORRECT']=boolval($arUser['UF_IS_CORRECT']);
        $LK_FIELDS['USER_1CID']=$arUser['UF_1CID'];
        $LK_FIELDS['USER_LOGIN']=$arUser['LOGIN'];

        if (empty($section_id) || is_array($section_id)){
            $arGroups = CUser::GetUserGroup($arUser['ID']);
            $arFilter=[
                'ACTIVE'=>'Y',
                'IBLOCK_ID'=>$IBLOCK_ID,
                'UF_GROUP'=>$arGroups,
            ];

            if (is_array($section_id)){
                $arFilter['ID']=$section_id;
            }

            $rs_Section = CIBlockSection::GetList(
                array('DEPTH_LEVEL' => 'desc'),
                $arFilter,
                false,
                array('ID', 'NAME', 'IBLOCK_SECTION_ID', 'DEPTH_LEVEL', 'SORT', 'CODE', 'UF_*')
            );
            $ar_SectionList = array();
            $ar_DepthLavel = array();
            $active_flag=false;
            while($ar_Section = $rs_Section->GetNext(true, false))
            {
                $cont_flag=false;
                foreach ($ar_Section['UF_USER_FIELD_'] as $USER_FIELD){
                    if (empty($arUser[$USER_FIELD])){
                        $cont_flag=true;
                    }
                }
                if ($cont_flag){
                    continue;
                }

                $ar_SectionList[$ar_Section['ID']] = [
                    'NAME'=>$ar_Section['NAME'],
                    "ICON"=>CFile::GetPath($ar_Section['UF_LK_SECTION_ICON']),
                    'ACTION'=>!empty($ar_Section['UF_ACTION'])?$ar_Section['UF_ACTION']:false,
                    'BTN_TEXT'=>!empty($ar_Section['UF_BTN_TEXT'])?$ar_Section['UF_BTN_TEXT']:false,
                    'CODE'=>$ar_Section['CODE'],
                    'IBLOCK_SECTION_ID'=>$ar_Section['IBLOCK_SECTION_ID'],
                    'ID'=>$ar_Section['ID'],
                    'DEPTH_LEVEL'=>$ar_Section['DEPTH_LEVEL'],
                    'SORT'=>$ar_Section['SORT'],
                ];
                switch ($ar_Section['UF_FORM_TYPE']){
                    case 1:
                        $ar_SectionList[$ar_Section['ID']]['FORM_TYPE']='in_parent';
                        break;
                    case 2:
                        $ar_SectionList[$ar_Section['ID']]['FORM_TYPE']='independent';
                        break;
                    default:
                        $ar_SectionList[$ar_Section['ID']]['FORM_TYPE']='in_parent';
                        break;
                }
                if (!empty($active_form) && $ar_Section['CODE']==$active_form){
                    $ar_SectionList[$ar_Section['ID']]['ACTIVE']=true;
                    $active_flag=true;
                }
                else{
                    $ar_SectionList[$ar_Section['ID']]['ACTIVE']=false;
                }

                foreach ($ar_Section['UF_JS_FILE'] as $js){
                    $ar_SectionList[$ar_Section['ID']]['JS'][]=CFile::GetPath($js);
                }
                foreach ($ar_Section['UF_JS_FILE_LINK'] as $js){
                    $ar_SectionList[$ar_Section['ID']]['JS'][]=$js;
                }
                foreach ($ar_Section['UF_CSS_FILE'] as $css){
                    $ar_SectionList[$ar_Section['ID']]['CSS'][]=CFile::GetPath($css);
                }
                foreach ($ar_Section['UF_CSS_FILE_LINK'] as $css){
                    $ar_SectionList[$ar_Section['ID']]['CSS'][]=$css;
                }
                $ar_DepthLavel[] = $ar_Section['DEPTH_LEVEL'];
            }



            $LK_FIELDS['ISSET']=GetSectionFields($ar_SectionList, $request_info, $code, $LK_FIELDS['IS_CORRECT'], $arUser, $LK_FIELDS['HEAD']);

            if (!$request_info){
                $ar_DepthLavelResult = array_unique($ar_DepthLavel);
                rsort($ar_DepthLavelResult);

                $i_MaxDepthLevel = $ar_DepthLavelResult[0];

                for( $i = $i_MaxDepthLevel; $i > 1; $i-- )
                {
                    foreach ( $ar_SectionList as $i_SectionID => $ar_Value )
                    {
                        if( $ar_Value['DEPTH_LEVEL'] == $i )
                        {
                            $ar_SectionList[$ar_Value['IBLOCK_SECTION_ID']]['SECTIONS'][] = $ar_Value;
                            unset( $ar_SectionList[$i_SectionID] );
                        }
                    }
                }
            }



        }
        elseif (is_numeric($section_id)){
            $ar_SectionList[$section_id]=[];
            $LK_FIELDS['ISSET']=GetSectionFields($ar_SectionList, $request_info, $code, $LK_FIELDS['IS_CORRECT'], $arUser, $LK_FIELDS['HEAD']);
        }


        function __sectionSort($a, $b)
        {
            if ($a['SORT'] == $b['SORT']) {
                return 0;
            }
            return ($a['SORT'] < $b['SORT']) ? -1 : 1;
        }

        function sections_sort(&$arr){
            usort($arr, "__sectionSort");
            foreach($arr as &$item){
                if (!empty($item['SECTIONS'])){
                    sections_sort($item['SECTIONS']);
                }
            }
        }

        if (!$request_info){
            sections_sort($ar_SectionList);

            if (!$active_flag){
                foreach ($ar_SectionList as &$SECTION){
                    $SECTION['ACTIVE']=true;
                    break;
                }
            }
        }

        $LK_FIELDS['SECTIONS']=$ar_SectionList;


        global $settings;
        $image=CFile::GetPath(!empty($arUser['PERSONAL_PHOTO']) ? $arUser['PERSONAL_PHOTO'] : $settings["PROPERTIES"]['PROFILE_DEFAULT_PHOTO']['VALUE']);
        list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $image);
        if ($height > $photo_size || $width>$photo_size) {
            $ratio = $height < $width?$photo_size / $height:$photo_size/$width;
            $filename=explode('.', basename($image));
            $filename[0].=(string)$photo_size;
            $filename=implode('.', $filename);

            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/upload/user_avatars/' . $filename)) {
                $img = Utils::resize_image($_SERVER['DOCUMENT_ROOT'] . $image, $ratio);
                if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/upload/user_avatars/')) {
                    mkdir($_SERVER['DOCUMENT_ROOT'] . '/upload/user_avatars/', 0777, true);
                }
                imagejpeg($img, $_SERVER['DOCUMENT_ROOT'] . '/upload/user_avatars/' . $filename);
            }
            $img_src = '/upload/user_avatars/' . $filename;
        } else {
            $img_src = $image;
        }
        if (!$request_info){
            $LK_FIELDS['HEAD']['PERSONAL_PHOTO'] = $img_src;
            $LK_FIELDS['OLD_PHOTO_ID']=$arUser['PERSONAL_PHOTO'];
        }
        else{
            $LK_FIELDS['PERSONAL_PHOTO'] = sprintf(
                    "%s://%s",
                    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
                    $_SERVER['SERVER_NAME'])
                .$img_src;
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
            $arGroups = CUser::GetUserGroup($USER_ID);
            $potential_id=Utils::GetUGroupIDBySID('POTENTIAL_CLIENTS');
            if (in_array($potential_id, $arGroups)){
                $resArr = array_diff($arGroups, [$potential_id]);
                $resArr[]=Utils::GetUGroupIDBySID('CLIENTS');
                CUser::SetUserGroup($USER_ID, $resArr);
            }

            $fields=$result['data']['result']['result'];

            foreach($UPDATEBLE_FIELDS as $key=>$value){
                if (key_exists($key, $fields)){
                    if ($key=='bankcard' && !empty($fields[$key])){
                        $fields[$key]=substr_replace($fields[$key],'******',0, 6);
                    }
                    $usUpdateArr[$UPDATEBLE_FIELDS[$key]]=$fields[$key];
                }
                else{
                    $usUpdateArr[$UPDATEBLE_FIELDS[$key]]=null;
                }
            }

//            foreach ($fields as $key=>$value){
//                if (key_exists($key, $UPDATEBLE_FIELDS)){
//                    $usUpdateArr[$UPDATEBLE_FIELDS[$key]]=$value;
//                }
//            }
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
            CUser::SetUserGroup($USER_ID, [Utils::GetUGroupIDBySID('POTENTIAL_CLIENTS')]);
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
                if ($minutes>30){
                    self::UpdatePersonalInfoFrom1C(false, $arUser2);
                }
            }
            else{
                self::UpdatePersonalInfoFrom1C(false, $arUser2);
            }
        }
    }

}
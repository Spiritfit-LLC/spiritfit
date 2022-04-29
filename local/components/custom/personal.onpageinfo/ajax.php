<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

require_once 'PersonalUtils.php';
use PersonalUtils;
use \Bitrix\Main\Loader;
use Bitrix\Main\UserAuthActionTable;

header('Content-Type: application/json; charset=utf-8');
Loader::IncludeModule("form");
Loader::IncludeModule("iblock");

/*
ОШИБКИ:
0-Нет ошибок
1-Не зарегистрирвоанное действие
2-Незаполнены обязательные поля
3-Пользователь не найден
4-Ошибка API
5-Пользователь уже существует
6-Не верный код смс
7-Сброс смс
8-Ошибка авторизации
9-Не выбран клуб при регистрации
10-Пароли не совпадают
11-Слишком много запросов смс
12-Несоответствие идентификатора. Возможно номер изменен.
13-Ошибка данных. Возможная причина: отключенные файлы cookie'
14-Пришел пустой код
15-Формат значения кода не верный
16-Пользователь не авторизован
17-Ошибка регистрации пользователя
18-Ошибка обновления пользователя
19-Не удалось загрузить файл
100-Непредвиденная ошибка
*/

$errorMessages=[
    0=>'',
    1=>'Не зарегистрированное действие',
    2=>'Не зарегистрированное действие: один или несколько параметров не переданы или переданы неправильно',
    3=>'Пользователь не найден',
    4=>'Возникли проблемы с сервером',
    5=>'Пользователь уже существует',
    6=>'Не верный код из СМС',
    7=>'Не удалось подтвердить код из СМС, попробуйте еще раз',
    8=>'Ошибка авторизации',
    9=>'Выберите клуб',
    10=>'Пароли не совпадают',
    11=>'Слишком много попыток. Попробуйте позже',
    12=>'Несоответствие идентификатора. Возможно, номер телефона был изменен',
    13=>'Ошибка данных. Возможная причина: отключенные файлы cookie',
    14=>'Код не может быть пустым',
    15=>'Формат значения кода не верный',
    16=>'Пользователь не авторизован',
    19=>'Не удалось загрузить файл',
    20=>'Ошибка обновления данных',
    100=>'Непредвиденная ошибка'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_POST['WEB_FORM_ID'])){
        $FORM_FIELDS=PersonalUtils::GetFormFileds($_POST['WEB_FORM_ID'], false, false, true);

        //Не заполнены обязательные поля
        if (isset($FORM_FIELDS['result']) && $FORM_FIELDS['result']==false){
            echo json_encode([
                'result'=>false,
                'message'=>$FORM_FIELDS['errorText'],
                'errorCode'=>2
            ], JSON_UNESCAPED_UNICODE);
            return;
        }
        if (!$FORM_FIELDS['ISSET']){
            echo json_encode([
                'result'=>false,
                'message'=>$errorMessages[2],
                'errorCode'=>2
            ], JSON_UNESCAPED_UNICODE);
            return;
        }
        //Авторизация
        if ($FORM_FIELDS['HIDDEN']['ACTION']['VALUE']=="LOGIN"){
            global $USER;
            if (!is_object($USER)) $USER = new CUser;
            $arAuthResult = $USER->Login($FORM_FIELDS['VISIBLE']['phone']['VALUE'], $FORM_FIELDS['VISIBLE']['passwd']['VALUE'],"Y","Y");
            if ($arAuthResult===true){
                echo json_encode(['result'=>true]);
            }
            else{
                echo json_encode([
                    'result'=>false,
                    'message'=>$errorMessages[8],
                    'errorCode'=>8,
                ], JSON_UNESCAPED_UNICODE);
            }
            return;
        }
        //Регистрация
        elseif ($FORM_FIELDS['HIDDEN']['ACTION']['VALUE']=="REG"){
            //Не выбран клуб
            if (empty($FORM_FIELDS['VISIBLE']['club']['VALUE'])){
                echo json_encode([
                    'result'=>false,
                    'message'=>$errorMessages[9],
                    'errorCode'=>9
                ], JSON_UNESCAPED_UNICODE);
                return;
            }
            //Шаг 1 - запрос смс
            if (!empty($_POST['STEP']) && (int)$_POST['STEP']==1){

                //Совпадает ли пароль
                if ($FORM_FIELDS['VISIBLE']['passwd']['VALUE']!=$FORM_FIELDS['VISIBLE']['passwd_confirm']['VALUE']){
                    echo json_encode([
                        'result'=>false,
                        'message'=>$errorMessages[10],
                        'errorCode'=>10
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }

                $rsUser = CUser::GetByLogin($FORM_FIELDS['VISIBLE']['phone']['VALUE']);
                if($arUser = $rsUser->Fetch())
                {
                    echo json_encode([
                        'result'=>false,
                        'message'=>$errorMessages[5],
                        'errorCode'=>5
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }
                $arParams=[
                    'login'=>$FORM_FIELDS['VISIBLE']['phone']['VALUE'],
                    'name'=>$FORM_FIELDS['VISIBLE']['name']['VALUE'].' '.$FORM_FIELDS['VISIBLE']['surname']['VALUE'],
                    'clubid'=>(string)$FORM_FIELDS['VISIBLE']['club']['VALUE'],
                    'event'=>$FORM_FIELDS['HIDDEN']['EVENT_1C']['VALUE'],
                ];
                $api=new Api(array(
                    'action'=>'lkreg',
                    'params'=>$arParams
                ));
                $result=$api->result();
                if ($result['error']==true){
                    echo json_encode([
                        'result'=>false,
                        'message'=>$errorMessages[4],
                        'errorCode'=>4
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }


                if ($result['success']==true){
                    $_SESSION['ID_1C']=$result['data']['result']['result']['id1c'];
                    echo json_encode(['result'=>true]);
                    return;
                }
                else{
                    switch ($result['data']['result']['errorCode']){
                        case 3:
                            echo json_encode([
                                'result'=>false,
                                'message'=> $errorMessages[11],
                                'errorCode'=>11,
                                'data'=>$result
                            ], JSON_UNESCAPED_UNICODE);
                            return;
                        default:
                            echo json_encode([
                                'result'=>false,
                                'message'=> $errorMessages[100],
                                'errorCode'=>100,
                                'data'=>$result
                            ], JSON_UNESCAPED_UNICODE);
                            return;
                    }

                }
            }
            //ШАГ 2 - подтверждение смс и регистрация
            elseif(!empty($_POST['STEP']) && (int)$_POST['STEP']==2){

                if (empty($_SESSION['ID_1C'])){
                    echo json_encode([
                        'result'=>false,
                        'message'=> $errorMessages[13],
                        'errorCode'=>13,
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }
                elseif (empty($_POST['reg_code'])){
                    echo json_encode([
                        'result'=>false,
                        'message'=> $errorMessages[14],
                        'errorCode'=>14,
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }
                else{
                    $code=preg_replace('![^0-9]+!', '', $_POST['reg_code']);
                    if (strlen($code)!=5){
                        echo json_encode([
                            'result'=>false,
                            'message'=> $errorMessages[15],
                            'errorCode'=>15,
                        ], JSON_UNESCAPED_UNICODE);
                        return;
                    }
                    $arParams=[
                        'id1c'=>$_SESSION['ID_1C'],
                        'code'=>$code,
                        'login'=>$FORM_FIELDS['VISIBLE']['phone']['VALUE'],
                        'name'=>$FORM_FIELDS['VISIBLE']['name']['VALUE'].' '.$FORM_FIELDS['VISIBLE']['surname']['VALUE'],
                        'clubid'=>$FORM_FIELDS['VISIBLE']['club']['VALUE'],
                        'event'=>$FORM_FIELDS['HIDDEN']['EVENT_1C']['VALUE'],
                    ];
                    $api=new Api(array(
                        'action'=>'lkcode',
                        'params'=>$arParams
                    ));
                    $result=$api->result();
                    if ($result['error']==true){
                        echo json_encode([
                            'result'=>false,
                            'message'=>$errorMessages[4],
                            'errorCode'=>4
                        ], JSON_UNESCAPED_UNICODE);
                        return;
                    }

                    if ($result['success']==true){
                        $settings = Utils::getInfo();
                        $arImage=CFile::MakeFileArray($settings["PROPERTIES"]['PROFILE_DEFAULT_PHOTO']['VALUE']);
                        $arImage["MODULE_ID"] = "main";

                        $user = new CUser;
                        $arFields=array(
                            'NAME'=>$FORM_FIELDS['VISIBLE']['name']['VALUE'],
                            'LAST_NAME'=>$FORM_FIELDS['VISIBLE']['surname']['VALUE'],
                            'EMAIL'=>$FORM_FIELDS['VISIBLE']['email']['VALUE'],
                            'LOGIN'=>$FORM_FIELDS['VISIBLE']['phone']['VALUE'],
                            "ACTIVE"=>"Y",
                            "GROUP_ID"=>array(7),
                            "PASSWORD"=>$FORM_FIELDS['VISIBLE']['passwd']['VALUE'],
                            "CONFIRM_PASSWORD"=>$FORM_FIELDS['VISIBLE']['passwd_confirm']['VALUE'],
                            "PERSONAL_PHOTO"=> $arImage,
                            'UF_1CID'=>$_SESSION['ID_1C'],
                        );
                        unset($_SESSION['ID_1C']);

                        $ID = $user->Add($arFields);
                        if (intval($ID) > 0){
                            $user->Login($FORM_FIELDS['VISIBLE']['phone']['VALUE'], $FORM_FIELDS['VISIBLE']['passwd']['VALUE'],"Y","Y");
                            echo json_encode(['result'=>true], JSON_UNESCAPED_UNICODE);
                        }
                        else{
                            echo json_encode([
                                'result'=>false,
                                'message'=>$user->LAST_ERROR,
                                'errorCode'=>17,
                            ], JSON_UNESCAPED_UNICODE);
                        }
                        return;
                    }
                    else{
                        switch ($result['data']['result']['errorCode']){
                            case 0:
                                echo json_encode([
                                    'result'=>false,
                                    'message'=>$errorMessages[6],
                                    'errorCode'=>6,
                                ], JSON_UNESCAPED_UNICODE);
                                return;
                            case 3:
                                echo json_encode([
                                    'result'=>false,
                                    'message'=>$errorMessages[7],
                                    'errorCode'=>7,
                                ], JSON_UNESCAPED_UNICODE);
                                return;
                            default:
                                echo json_encode([
                                    'result'=>false,
                                    'message'=> $errorMessages[100],
                                    'errorCode'=>100,
                                    'data'=>$result
                                ], JSON_UNESCAPED_UNICODE);
                                return;
                        }
                    }
                }

            }
            else{
                echo json_encode([
                    'result'=>false,
                    'message'=>$errorMessages[1],
                    'errorCode'=>1
                ], JSON_UNESCAPED_UNICODE);
                return;
            }
        }
        //Востановление пароля
        elseif ($FORM_FIELDS['HIDDEN']['ACTION']['VALUE']=="FORGOT"){
            if (!empty($_POST['STEP']) && (int)$_POST['STEP']==1){
                $rsUser=CUser::GetByLogin($FORM_FIELDS['VISIBLE']['phone']['VALUE']);

                if ($arUser=$rsUser->Fetch()){
                    $arParams=[
                        'login'=>$FORM_FIELDS['VISIBLE']['phone']['VALUE'],
                        'event'=>$FORM_FIELDS['HIDDEN']['EVENT_1C']['VALUE'],
                        'id1c'=>$arUser['UF_1CID']
                    ];
                    $api=new Api(array(
                        'action'=>'lkreg',
                        'params'=>$arParams
                    ));
                    $result=$api->result();

                    if ($result['error']==true){
                        echo json_encode([
                            'result'=>false,
                            'message'=>$errorMessages[4],
                            'errorCode'=>4
                        ], JSON_UNESCAPED_UNICODE);
                        return;
                    }


                    if ($result['success']==true){
                        echo json_encode(['result'=>true]);
                        return;
                    }
                    else{
                        switch ($result['data']['result']['errorCode']){
                            case 3:
                                echo json_encode([
                                    'result'=>false,
                                    'message'=> $errorMessages[11],
                                    'errorCode'=>11,
                                    'data'=>$result
                                ], JSON_UNESCAPED_UNICODE);
                                return;
                            default:
                                echo json_encode([
                                    'result'=>false,
                                    'message'=> $errorMessages[100],
                                    'errorCode'=>100,
                                    'data'=>$result
                                ], JSON_UNESCAPED_UNICODE);
                                return;
                        }

                    }
                }
                else{
                    echo json_encode([
                        'result'=>false,
                        'message'=>$errorMessages[3],
                        'errorCode'=>3,
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }
            }
            elseif(!empty($_POST['STEP']) && (int)$_POST['STEP']==2){
                $code=preg_replace('![^0-9]+!', '', $_POST['reg_code']);
                if (strlen($code)!=5){
                    echo json_encode([
                        'result'=>false,
                        'message'=> $errorMessages[15],
                        'errorCode'=>15,
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }

                $rsUser=CUser::GetByLogin($FORM_FIELDS['VISIBLE']['phone']['VALUE']);

                if ($arUser=$rsUser->Fetch()){
                    $arParams=[
                        'login'=>$FORM_FIELDS['VISIBLE']['phone']['VALUE'],
                        'event'=>$FORM_FIELDS['HIDDEN']['EVENT_1C']['VALUE'],
                        'id1c'=>$arUser['UF_1CID'],
                        'code'=>$code,
                    ];

                    $api=new Api(array(
                        'action'=>'lkcode',
                        'params'=>$arParams
                    ));
                    $result=$api->result();
                    if ($result['error']==true){
                        echo json_encode([
                            'result'=>false,
                            'message'=>$errorMessages[4],
                            'errorCode'=>4
                        ], JSON_UNESCAPED_UNICODE);
                        return;
                    }

                    if ($result['success']==true){
                        global $USER;
                        if ($USER->Authorize($arUser['ID'])){
                            echo json_encode(['result'=>true]);
                            return;
                        }
                        else{
                            echo json_encode([
                                'result'=>false,
                                'message'=>$errorMessages[8],
                                'errorCode'=>8,
                            ], JSON_UNESCAPED_UNICODE);
                            return;
                        }
                    }
                    else {
                        switch ($result['data']['result']['errorCode']) {
                            case 0:
                                echo json_encode([
                                    'result' => false,
                                    'message' => $errorMessages[6],
                                    'errorCode' => 6,
                                ], JSON_UNESCAPED_UNICODE);
                                return;
                            case 3:
                                echo json_encode([
                                    'result' => false,
                                    'message' => $errorMessages[7],
                                    'errorCode' => 7,
                                ], JSON_UNESCAPED_UNICODE);
                                return;
                            default:
                                echo json_encode([
                                    'result' => false,
                                    'message' => $errorMessages[100],
                                    'errorCode' => 100,
                                    'data' => $result
                                ], JSON_UNESCAPED_UNICODE);
                                return;
                        }
                    }
                }
                else{
                    echo json_encode([
                        'result'=>false,
                        'message'=>$errorMessages[3],
                        'errorCode'=>3,
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }
            }
            else{
                echo json_encode(['result'=>false, 'message'=>'Не зарегистрированное действие', 'errorCode'=>0], JSON_UNESCAPED_UNICODE);
                return;
            }
        }
        else{
            echo json_encode(['result'=>false, 'message'=>'Не зарегистрированное действие', 'errorCode'=>0], JSON_UNESCAPED_UNICODE);
            return;
        }
    }
    //ыходд из профиля
    elseif ($_POST['ACTION']=='EXIT'){
        global $USER;
        if ($USER->IsAuthorized()){
            $USER->Logout();
            echo json_encode(['result'=>true]);
            return;
        }
        else{
            echo json_encode([
                'result'=>false,
                'message'=>$errorMessages[16],
                'errorCode'=>16,
            ], JSON_UNESCAPED_UNICODE);
            return;
        }
    }
    //бновление профиля
    elseif($_POST['ACTION']=='UPDATE_USER'){
        global $USER;
        if ($USER->IsAuthorized()){
            $FORM_FIELDS=PersonalUtils::GetPersonalPageFormFields($USER->GetID(), true, true);
            if (!$FORM_FIELDS['ISSET']){
                echo json_encode([
                    'result'=>false,
                    'message'=>$errorMessages[2],
                    'errorCode'=>2,
                ], JSON_UNESCAPED_UNICODE);
                return;
            }
            foreach ($FORM_FIELDS['VISIBLE'] as $code=>$FIELD){
                $CHANGEARR[$FIELD['USER_FIELD_CODE']]=$FIELD['VALUE'];
            }
            global $USER;
            $result = $USER->Update($USER->GetID(), $CHANGEARR, false);
            if($result==true){
                $result=['result'=>true];
            }
            else{
                $result=[
                    'result'=>false,
                    'message'=>$USER->LAST_ERROR,
                    'errorCode'=>18
                ];
            }
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
            return;
        }
        else{
            echo json_encode([
                'result'=>false,
                'message'=>$errorMessages[16],
                'errorCode'=>16,
            ], JSON_UNESCAPED_UNICODE);
            return;
        }
    }
    //Обновление фотография профиля
    elseif ($_POST['ACTION']=='UPDATE_PERSONAL_PHOTO' && !empty($_FILES['new-photo-file'])){
        if (0 < $_FILES['file']['error']){
            echo json_encode([
                'result'=>false,
                'message'=>$errorMessages[19].': '.$_FILES['file']['error'],
                'errorCode'=>19,
            ], JSON_UNESCAPED_UNICODE);
        }
        else{
            $fileId = CFile::SaveFile($_FILES["new-photo-file"],'avatar');
            $arFile = CFile::MakeFileArray ($fileId);
            $arFile['del'] = "Y";
            $arFile['old_file'] = $_POST['old-photo-id'];
            $arFields['PERSONAL_PHOTO'] = $arFile;

            global $USER;
            $result = $USER->Update($USER->GetID(), $arFields,false);

            if($result){
                $result = array(
                    'result' => true,
                );
            }
            else{
                $result=array(
                    'result'=>false,
                    'message'=>$errorMessages[19],
                    'errorCode'=>19
                );
            }
            echo json_encode($result);
            return;
        }
    }
    //Обновление данных из 1С
    elseif($_POST['ACTION']=='UPDATE_1C'){
        global $USER;
        if ($USER->IsAuthorized()){
            $result=PersonalUtils::UpdatePersonalInfoFrom1C($USER->GetID());
            if ($result!=false){
                echo json_encode([
                    'result'=>true,
                    'data'=>$result
                ], JSON_UNESCAPED_UNICODE);
                return;
            }
            else{
                echo json_encode([
                    'result'=>false,
                    'message'=>$errorMessages[100],
                    'errorCode'=>100,
                ], JSON_UNESCAPED_UNICODE);
                return;
            }
        }
        else{
            echo json_encode([
                'result'=>false,
                'message'=>$errorMessages[16],
                'errorCode'=>16,
            ], JSON_UNESCAPED_UNICODE);
            return;
        }
    }
    else{
        echo json_encode([
            'result'=>false,
            'message'=>$errorMessages[1],
            'errorCode'=>1
        ], JSON_UNESCAPED_UNICODE);
        return;
    }
}
else{
    echo json_encode([
        'result'=>false,
        'message'=>$errorMessages[1],
        'errorCode'=>1
    ], JSON_UNESCAPED_UNICODE);
    return;
}



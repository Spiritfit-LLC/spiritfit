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
    21=>'Пользователь не существует',
    100=>'Непредвиденная ошибка'
];

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

//echo json_encode($_REQUEST);
//return;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['WEB_FORM_ID'])){
        $FORM_FIELDS=PersonalUtils::GetFormFileds($_POST['WEB_FORM_ID'], false, true);
        //Не заполнены обязательные поля или несоответствие валидатору
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

        //Авторизация OLD
//        if ($FORM_FIELDS['ACTION']=="LOGIN"){
//            global $USER;
//            if (!is_object($USER)) $USER = new CUser;
//            $user=CUser::GetByLogin($FORM_FIELDS['FIELDS']['phone']['VALUE'])->Fetch();
//            if (empty($user)){
//                echo json_encode([
//                    'result'=>false,
//                    'message'=>$errorMessages[21],
//                    'errorCode'=>21,
//                ], JSON_UNESCAPED_UNICODE);
//                return;
//            }
//
//            $arAuthResult = $USER->Login($FORM_FIELDS['FIELDS']['phone']['VALUE'], $FORM_FIELDS['FIELDS']['passwd']['VALUE'],"Y","Y");
//            if ($arAuthResult===true){
//                echo json_encode(['result'=>true]);
//            }
//            else{
//                echo json_encode([
//                    'result'=>false,
//                    'message'=>$errorMessages[8],
//                    'errorCode'=>8,
//                ], JSON_UNESCAPED_UNICODE);
//            }
//            return;
//        }

        //АВТОРИЗАЦИЯ NEW шаг 1 проверка юзера
        elseif ($FORM_FIELDS['ACTION']=="LOGIN_1"){
            global $USER;
            if (!is_object($USER)) $USER = new CUser;
            $user=CUser::GetByLogin($FORM_FIELDS['FIELDS']['phone']['VALUE'])->Fetch();
            if (empty($user)){
                $arParams=['login'=>$FORM_FIELDS['FIELDS']['phone']['VALUE']];
                $api=new Api(array(
                    'action'=>'lkcheck',
                    'params'=>$arParams
                ));
                $result=$api->result();

                //Пользователь не найден, переход на регистрацию
                if ($result['success']==false){
                    echo json_encode([
                        'result'=>false,
                        'message'=>$errorMessages[21],
                        'errorCode'=>21
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }

                //Пользователь найден в 1с, завершаем регистрацию
                //Возвращаем на фронт инфу
                echo json_encode(['result'=>true, 'type'=>'1c']);
                return;
            }
            else{
                if (boolval($user['UF_IS_CORRECT'])){
                    echo json_encode(['result'=>true, 'type'=>'site']);
                    return;
                }
                else{
                    //Отправялем СМС для "забыл пароль"
                    $arParams=[
                        'login'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                        'event'=>$FORM_FIELDS['FIELDS']['EVENT_1C']['VALUE'],
                        'id1c'=>$user['UF_1CID']
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
                        //Возвращаем на фронт инфу
                        echo json_encode(['result'=>true, 'type'=>'site2']);
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
            }
        }
        //Пользователь найден в 1с, нужно пройти регистрацию на сайте
        elseif($FORM_FIELDS['ACTION']=='LOGIN_2'){
            if (empty($_POST['reg_code'])){
                echo json_encode([
                    'result'=>false,
                    'message'=> $errorMessages[14],
                    'errorCode'=>14,
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            //Подтверждаем СМС Код
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
                'code'=>$code,
                'login'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                'event'=>"auth",
            ];

            $api=new Api(array(
                'action'=>'lkcode',
                'params'=>$arParams
            ));
            $result=$api->result();
            if ($result['success']==false){
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
            else{
                //Заранее добавялем пользователя с имеющимися полями и авторизовываем его
                $user=new CUser;
                $user1Carr=$result['data']['result']['result'];

                $passwd=generateRandomString();
                $arFields=array(
                    'UF_IS_CORRECT'=>false,
                    'NAME'=>$user1Carr['name'],
                    'LAST_NAME'=>$user1Carr['surname'],
                    'EMAIL'=>$user1Carr['email'],
                    'LOGIN'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                    'ACTIVE'=>'Y',
                    "GROUP_ID"=>array(7),
                    'UF_1CID'=>$user1Carr['id1c'],
                    'PERSONAL_BIRTHDAY'=>$user1Carr['birthday'],
                    'PERSONAL_PHONE'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                    'PERSONAL_GENDER'=>$user1Carr['gender'],
                    "PASSWORD"=>$passwd,
                    "CONFIRM_PASSWORD"=>$passwd,
                );
                if (empty($user1Carr['imageurl'])) {
                    $settings = Utils::getInfo();
                    $imgPath = $settings["PROPERTIES"]['PROFILE_DEFAULT_PHOTO']['VALUE'];
                }
                else{
                    $imgPath = $user1Carr['imageurl'];
                }
                $arImage=CFile::MakeFileArray($imgPath);
                $arImage["MODULE_ID"] = "main";
                $arFields['PERSONAL_PHOTO']=$arImage;
                $ID = $user->Add($arFields);
                if (intval($ID) > 0){
                    $user->Authorize($ID);
                    $api=new Api(array(
                        'action'=>'lkedit',
                        'params'=>[
                            'id1c'=>$user1Carr['id1c'],
                            'login'=>$FORM_FIELDS['FIELDS']['phone']['VALUE']
                        ],
                    ));
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


        }
        //Пользован определен в битрикс, авторизация по паролю
        elseif ($FORM_FIELDS['ACTION']=="LOGIN_3"){
            global $USER;
            $arAuthResult = $USER->Login($FORM_FIELDS['FIELDS']['phone']['VALUE'], $FORM_FIELDS['FIELDS']['passwd']['VALUE'],"Y","Y");
            if ($arAuthResult===true){
                $rsUser = CUser::GetByLogin($FORM_FIELDS['FIELDS']['phone']['VALUE']);
                $arUser = $rsUser->Fetch();
                $api=new Api(array(
                    'action'=>'lkedit',
                    'params'=>[
                        'id1c'=>$arUser['UF_1CID'],
                        'login'=>$FORM_FIELDS['FIELDS']['phone']['VALUE']
                    ],
                ));
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
        //Регистрация шаг 1
        elseif ($FORM_FIELDS['ACTION']=="REG_1"){
            //Не выбран клуб
            if (empty($FORM_FIELDS['FIELDS']['club']['VALUE'])){
                echo json_encode([
                    'result'=>false,
                    'message'=>$errorMessages[9],
                    'errorCode'=>9
                ], JSON_UNESCAPED_UNICODE);
                return;
            }
            $rsUser = CUser::GetByLogin($FORM_FIELDS['FIELDS']['phone']['VALUE']);
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
                'login'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                'name'=>$FORM_FIELDS['FIELDS']['name']['VALUE'],
                "surname"=>$FORM_FIELDS['FIELDS']['surname']['VALUE'],
                "gender"=>$FORM_FIELDS['FIELDS']['gender']['VALUE'],
                "birthday"=>$FORM_FIELDS['FIELDS']['birthday']['VALUE'],
                "email"=>$FORM_FIELDS['FIELDS']['email']['VALUE'],
                'clubid'=>(string)$FORM_FIELDS['FIELDS']['club']['VALUE'],
                'event'=>$FORM_FIELDS['FIELDS']['EVENT_1C']['VALUE'],
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
                unset($_SESSION['ID_1C']);
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
                    case 2:
                        echo json_encode([
                            'result'=>false,
                            'message'=>$errorMessages[5],
                            'errorCode'=>5
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
        //Регистрация шаг 2 подтверждение кода
        elseif($FORM_FIELDS['ACTION']=="REG_2"){
            //Не выбран клуб
            if (empty($FORM_FIELDS['FIELDS']['club']['VALUE'])){
                echo json_encode([
                    'result'=>false,
                    'message'=>$errorMessages[9],
                    'errorCode'=>9
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

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
                    'login'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                    'clubid'=>$FORM_FIELDS['FIELDS']['club']['VALUE'],
                    'event'=>$FORM_FIELDS['FIELDS']['EVENT_1C']['VALUE'],
                    'name'=>$FORM_FIELDS['FIELDS']['name']['VALUE'],
                    "surname"=>$FORM_FIELDS['FIELDS']['surname']['VALUE'],
                    "gender"=>$FORM_FIELDS['FIELDS']['gender']['VALUE'],
                    "birthday"=>$FORM_FIELDS['FIELDS']['birthday']['VALUE'],
                    "email"=>$FORM_FIELDS['FIELDS']['email']['VALUE'],
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
                        'UF_IS_CORRECT'=>true,
                        'NAME'=>$FORM_FIELDS['FIELDS']['name']['VALUE'],
                        'LAST_NAME'=>$FORM_FIELDS['FIELDS']['surname']['VALUE'],
                        'EMAIL'=>$FORM_FIELDS['FIELDS']['email']['VALUE'],
                        'LOGIN'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                        "ACTIVE"=>"Y",
                        "GROUP_ID"=>array(7),
                        "PASSWORD"=>$FORM_FIELDS['FIELDS']['passwd']['VALUE'],
                        "CONFIRM_PASSWORD"=>$FORM_FIELDS['FIELDS']['passwd']['VALUE'],
                        "PERSONAL_PHOTO"=> $arImage,
                        'UF_1CID'=>$_SESSION['ID_1C'],
                        'PERSONAL_BIRTHDAY'=>$FORM_FIELDS['FIELDS']['birthday']['VALUE'],
                        'PERSONAL_PHONE'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                        'PERSONAL_GENDER'=>$FORM_FIELDS['FIELDS']['gender']['VALUE'],
                    );
                    unset($_SESSION['ID_1C']);

                    $ID = $user->Add($arFields);
                    if (intval($ID) > 0){
                        $user->Login($FORM_FIELDS['FIELDS']['phone']['VALUE'], $FORM_FIELDS['FIELDS']['passwd']['VALUE'],"Y","Y");
                        $api=new Api(array(
                            'action'=>'lkedit',
                            'params'=>[
                                'id1c'=>$arFields['UF_1CID'],
                                'login'=>$FORM_FIELDS['FIELDS']['phone']['VALUE']
                            ],
                        ));
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
        //Восстановление пароля шаг 1
        elseif ($FORM_FIELDS['ACTION']=='FORGOT_1'){
            global $USER;
            if (!is_object($USER)) $USER = new CUser;
            $user=CUser::GetByLogin($FORM_FIELDS['FIELDS']['phone']['VALUE'])->Fetch();
            if (empty($user)){
                $arParams=['login'=>$FORM_FIELDS['FIELDS']['phone']['VALUE']];
                $api=new Api(array(
                    'action'=>'lkcheck',
                    'params'=>$arParams
                ));
                $result=$api->result();

                if ($result['success']==false){
                    if ($result['data']['result']['errorCode']==0){
                        echo json_encode([
                            'result'=>false,
                            'message'=>$errorMessages[21],
                            'errorCode'=>21
                        ], JSON_UNESCAPED_UNICODE);
                        return;
                    }
                }
                else{
                    //Пользователь найден в 1с, завершаем регистрацию
                    //Возвращаем на фронт инфу
                    echo json_encode(['result'=>true, 'type'=>'1c', 'data'=>$result]);
                    return;
                }
            }
            else{
                //Отправялем СМС для "забыл пароль"
                $arParams=[
                    'login'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                    'event'=>$FORM_FIELDS['FIELDS']['EVENT_1C']['VALUE'],
                    'id1c'=>$user['UF_1CID']
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
                    //Возвращаем на фронт инфу
                    echo json_encode(['result'=>true, 'type'=>'site']);
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
                        case 2:
                            echo json_encode([
                                'result'=>false,
                                'message'=> $errorMessages[21],
                                'errorCode'=>21,
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



//            $rsUser=CUser::GetByLogin($FORM_FIELDS['FIELDS']['phone']['VALUE']);
//            if ($arUser=$rsUser->Fetch()){
//                $arParams=[
//                    'login'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
//                    'event'=>$FORM_FIELDS['FIELDS']['EVENT_1C']['VALUE'],
//                    'id1c'=>$arUser['UF_1CID']
//                ];
//                $api=new Api(array(
//                    'action'=>'lkreg',
//                    'params'=>$arParams
//                ));
//                $result=$api->result();
//                if ($result['error']==true){
//                    echo json_encode([
//                        'result'=>false,
//                        'message'=>$errorMessages[4],
//                        'errorCode'=>4
//                    ], JSON_UNESCAPED_UNICODE);
//                    return;
//                }
//                if ($result['success']==true){
//                    echo json_encode(['result'=>true]);
//                    return;
//                }
//                else{
//                    switch ($result['data']['result']['errorCode']){
//                        case 3:
//                            echo json_encode([
//                                'result'=>false,
//                                'message'=> $errorMessages[11],
//                                'errorCode'=>11,
//                                'data'=>$result
//                            ], JSON_UNESCAPED_UNICODE);
//                            return;
//                        default:
//                            echo json_encode([
//                                'result'=>false,
//                                'message'=> $errorMessages[100],
//                                'errorCode'=>100,
//                                'data'=>$result
//                            ], JSON_UNESCAPED_UNICODE);
//                            return;
//                    }
//                }
//            }
//            else{
//                echo json_encode([
//                    'result'=>false,
//                    'message'=>$errorMessages[3],
//                    'errorCode'=>3,
//                ], JSON_UNESCAPED_UNICODE);
//                return;
//            }
        }
        //Восстановление пароля шаг 2
        elseif($FORM_FIELDS['ACTION']=='FORGOT_2') {
            $code = preg_replace('![^0-9]+!', '', $_POST['reg_code']);
            if (strlen($code) != 5) {
                echo json_encode([
                    'result' => false,
                    'message' => $errorMessages[15],
                    'errorCode' => 15,
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $rsUser = CUser::GetByLogin($FORM_FIELDS['FIELDS']['phone']['VALUE']);

            if ($arUser = $rsUser->Fetch()) {
                $arParams = [
                    'login' => $FORM_FIELDS['FIELDS']['phone']['VALUE'],
                    'event'=>$FORM_FIELDS['FIELDS']['EVENT_1C']['VALUE'],
                    'id1c' => $arUser['UF_1CID'],
                    'code' => $code,
                ];
                $api = new Api(array(
                    'action' => 'lkcode',
                    'params' => $arParams
                ));
                $result = $api->result();
                if ($result['error'] == true) {
                    echo json_encode([
                        'result' => false,
                        'message' => $errorMessages[4],
                        'errorCode' => 4
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }

                if ($result['success'] == true) {
                    global $USER;
                    if ($USER->Authorize($arUser['ID'])) {
                        $api=new Api(array(
                            'action'=>'lkedit',
                            'params'=>[
                                'id1c'=>$arUser['UF_1CID'],
                                'login'=>$FORM_FIELDS['FIELDS']['phone']['VALUE']
                            ],
                        ));
                        echo json_encode(['result' => true]);
                        return;
                    } else {
                        echo json_encode([
                            'result' => false,
                            'message' => $errorMessages[8],
                            'errorCode' => 8,
                        ], JSON_UNESCAPED_UNICODE);
                        return;
                    }
                } else {
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
            $FORM_FIELDS=PersonalUtils::GetPersonalPageFormFields($USER->GetID(), true, [], $_POST['SECTION_ID']);

            if (!$FORM_FIELDS['ISSET']){
                echo json_encode([
                    'result'=>false,
                    'message'=>$errorMessages[2],
                    'errorCode'=>2,
                ], JSON_UNESCAPED_UNICODE);
                return;
            }
            foreach ($FORM_FIELDS['SECTIONS'][$_POST['SECTION_ID']]['FIELDS'] as $code=>$FIELD){
                if ($FIELD['VALUE']!=false){
                    $CHANGEARR[$FIELD['USER_FIELD_CODE']]=$FIELD['VALUE'];
                    if($FIELD['USER_FIELD_CODE']=='PASSWORD'){
                        $CHANGEARR['CONFIRM_PASSWORD']=$FIELD['VALUE'];
                    }
                }
            }
            if (!$FORM_FIELDS['IS_CORRECT']){
                $CHANGEARR['UF_IS_CORRECT']=true;
                $type='is_correct';
            }
            else{
                $type='is_update';
            }


            global $USER;
            $result = $USER->Update($USER->GetID(), $CHANGEARR, false);
            if($result==true){

                $arParams=array(
                    'name'=>$CHANGEARR['NAME'],
                    'surname'=>$CHANGEARR['LAST_NAME'],
                    'email'=>$CHANGEARR['EMAIL'],
                    'birthday'=>$CHANGEARR['PERSONAL_BIRTHDAY'],
                    'gender'=>$CHANGEARR['PERSONAL_GENDER'],
                    'id1c'=>$FORM_FIELDS['USER_1CID'],
                    'login'=>$FORM_FIELDS['USER_LOGIN'],
                    'imageurl'=>$FORM_FIELDS['PERSONAL_PHOTO']
                );

                $result=['result'=>true, 'type'=>$type, 'data'=>$arParams];
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



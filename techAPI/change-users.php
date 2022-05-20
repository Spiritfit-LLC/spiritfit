<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

$input = json_decode(file_get_contents("php://input"), true);

$token='8be766b1-b6f8-4c6a-8f77-09908cfec8ce';
if (!empty($input['token']) && $input['token']==$token){
    $users=$input['users'];

    if (empty($users)){
        echo json_encode(['message'=>'Пользователи не заданы', 'errorCode'=>3], JSON_UNESCAPED_UNICODE);
        return;
    }

    $nonLogins=[];
    $errors=[];
    foreach ($users as $user){
        $USER=new CUser;
        $rsUser=CUser::GetByLogin($user['login']);
        if ($arUser = $rsUser->Fetch()){
            $user_id=$arUser['ID'];

            $fields=$user['fields'];
            if (empty($fields)){
                $errors[$user['login']]="Нет данных для обновления";
                continue;
            }
            $user['fields']['PERSONAL_PHONE']=$user['fields']['LOGIN'];
            if ($USER->Update($user_id, $user['fields'])!=true){
                $errors[$user['login']]=$user->LAST_ERROR;
            }
        }
        else{
            array_push($nonLogins, $user['login']);
        }
    }
    if (count($nonLogins)>0 || count($errors)>0){
        echo json_encode(['message'=>'Возникли некоторые ошибки', 'errorCode'=>2, 'not_found_logins'=>$nonLogins, 'errors'=>$errors], JSON_UNESCAPED_UNICODE);
    }
    else{
        echo json_encode(['message'=>'Ошибок нет', 'errorCode'=>0], JSON_UNESCAPED_UNICODE);
    }
}
else{
    echo json_encode(['message'=>'Неверный токен', 'errorCode'=>1], JSON_UNESCAPED_UNICODE);
}
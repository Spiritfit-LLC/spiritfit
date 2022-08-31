<?php


require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader;


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

    $objects=[];
    $filter = [
        'IBLOCK_CODE' => 'LK_FIELDS',
        'ACTIVE'=>'Y',
        '!PROPERTY_CODE_1C'=>false];
    $order = ['SORT' => 'ASC'];

    Loader::IncludeModule("iblock");


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

            $UPDATEBLE_FIELDS=PersonalUtils::GetUpdatebleFrom1CPersonalInfo();

            $NOTIFICATIONS_LIST=[];

            foreach($UPDATEBLE_FIELDS as $key=>$val){
                foreach ($val as $value){
                    if (key_exists($key, $fields)){
                        if ($value['TYPE']=='list'){
                            $CURRENT_VAL=[];
                            foreach($fields[$key] as $field){
                                if (!empty($value['VALUE_HANDLER'])){
                                    $value['VALUE_HANDLER']=str_replace('#VALUE#', '$field', $value['VALUE_HANDLER']);
                                    $field=eval($value['VALUE_HANDLER']);
                                    if ($field===null){
                                        continue;
                                    }
                                }

                                if (boolval($value['SERIALIZE'])){
                                    $CURRENT_VAL[]=serialize($field);
                                }
                                else{
                                    $CURRENT_VAL[]=$field;
                                }
                            }
                            $usUpdateArr[$value['VALUE']]=$CURRENT_VAL;
                            $NOTIFICATIONS_LIST[$value["SECTION_ID"]][]=$value['VALUE'];
                            file_put_contents($_SERVER['DOCUMENT_ROOT'].'/logs/test.txt', print_r($CURRENT_VAL, true)."\n", FILE_APPEND);
                            continue;
                        }

                        $CURRENT_VAL=$fields[$key];
                        if ($key=='bankcard' && !empty($CURRENT_VAL)){
                            $CURRENT_VAL=substr_replace($CURRENT_VAL,'******',0, 6);
                        }
                        elseif ($key=='imageurl') {
                            $imgPath = $CURRENT_VAL;
                            $arImage=CFile::MakeFileArray($imgPath);
                            $arImage['del'] = "Y";
                            $arImage['old_file'] = $arUser["PERSONAL_PHOTO"];
                            $usUpdateArr[$value['VALUE']]=$arImage;
                            continue;
                        }

                        if (!empty($value['VALUE_HANDLER'])){
                            $value['VALUE_HANDLER']=str_replace('#VALUE#', '$CURRENT_VAL', $value['VALUE_HANDLER']);
                            $CURRENT_VAL=eval($value['VALUE_HANDLER']);
                        }

                        if (boolval($value['SERIALIZE'])){
                            $CURRENT_VAL=serialize($CURRENT_VAL);
                        }
                        $NOTIFICATIONS_LIST[$value["SECTION_ID"]][]=$value['VALUE'];
                    }
                    else{
                        continue;
                    }
                    $usUpdateArr[$value['VALUE']]=$CURRENT_VAL;
                }
            }
            $usUpdateArr["UF_NOTIFICATION"]=serialize($NOTIFICATIONS_LIST);
            if ($USER->Update($user_id, $usUpdateArr)!=true){
                $errors[$user['login']]=$USER->LAST_ERROR;
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
        echo json_encode(['message'=>'Ошибок нет', 'errorCode'=>0, $arImage], JSON_UNESCAPED_UNICODE);
    }
}
else{
    echo json_encode(['message'=>'Неверный токен', 'errorCode'=>1], JSON_UNESCAPED_UNICODE);
}
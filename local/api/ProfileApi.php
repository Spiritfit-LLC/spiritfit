<?php
\Bitrix\Main\Loader::includeModule('rest');

use \Bitrix\Main\Type\DateTime;

class ProfileApi extends \IRestService{
    private const SCOPE='profile';

    public static function OnRestServiceBuildDescription(){
        return [
            'custom.'.static::SCOPE => [
                'profile.editphoto' => [
                    'callback' => [__CLASS__, "editPhoto"],
                    'options' => [],
                ],
                'profile.edit' => [
                    'callback' => [__CLASS__, "editProfile"],
                    'options' => [],
                ],
            ],
        ];
    }

    private static function checkQuery($query, $params){
        if($query['error'])
        {
            throw new \Bitrix\Rest\RestException(
                '',
                'WRONG_REQUEST',
                \CRestServer::STATUS_WRONG_REQUEST
            );
        }

        foreach ($params as $param){
            if (!isset($query[$param]))
            {
                throw new \Bitrix\Rest\RestException(
                    "Отсутствует обязательный параметр $param",
                    'WRONG_REQUEST',
                    \CRestServer::STATUS_WRONG_REQUEST
                );
            }
        }
    }

    public static function editProfile($query, $n, \CRestServer $server){
        self::checkQuery($query, ["users"]);

        $users=$query["users"];
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
                            elseif ($key="login"){
                                if (empty($CURRENT_VAL)){
                                    $DELETE_USER[$user_id]=$user["login"];
                                    continue;
                                }

                                $buff_login=uniqid("lk-login");
                                $UPDATE_LOGINS[$user_id]=[
                                    "old"=>$user['login'],
                                    "new"=>$CURRENT_VAL
                                ];
                                if ($USER->Update($user_id, ["LOGIN"=>$buff_login])!=true){
                                    $errors[$user['login']]=$USER->LAST_ERROR;
                                }
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
        if (!empty($UPDATE_LOGINS)){
            foreach ($UPDATE_LOGINS as $user_id=>$login){
                if ($USER->Update($user_id, ["LOGIN"=>$login["new"], "PERSONAL_PHONE"=>$login["new"]])==false){
                    $errors[$login["old"]]="Не удалось обновить логин::".$USER->LAST_ERROR;
                    $USER->Update($user_id, ["LOGIN"=>$login["old"], "PERSONAL_PHONE"=>$login["old"]]);
                }
            }
        }
        if (!empty($DELETE_USER)){
            foreach ($DELETE_USER as $user_id=>$login){
                if (CModule::IncludeModule("blog")){
                    $blog=CBlog::GetByOwnerID($user_id);
                    if ($blog){
                        $blog_id=$blog["ID"];
                        if (!CBlog::Delete($blog_id)){
                            $errors[$login]="Не удалось блог пользователя";
                        }
                    }
                }
                if(!CUser::Delete($user_id)){
                    $errors[$login]="Не удалось удалить пользователя";
                }
            }
        }
        if (count($nonLogins)>0 || count($errors)>0){
            return ['message'=>'Возникли некоторые ошибки', 'errorCode'=>2, 'not_found_logins'=>$nonLogins, 'errors'=>$errors];
        }
        else{
            return ['message'=>'Ошибок нет', 'errorCode'=>0];
        }
    }

    public static function editPhoto($query, $n, \CRestServer $server){
        self::checkQuery($query, ["phone", "photo"]);

        function check_base64_image($base64){
            $bin = base64_decode($base64);
            $size = getImageSizeFromString($bin);

            if (empty($size['mime']) || strpos($size['mime'], 'image/') !== 0) {
                throw new \Bitrix\Rest\RestException(
                    'Base64 value is not a valid image',
                    'WRONG_REQUEST',
                    \CRestServer::STATUS_WRONG_REQUEST
                );
            }

            $ext = substr($size['mime'], 6);

            if (!in_array($ext, ['png', 'gif', 'jpeg'])) {
                throw new \Bitrix\Rest\RestException(
                    'Unsupported image type',
                    'WRONG_REQUEST',
                    \CRestServer::STATUS_WRONG_REQUEST
                );
            }

            $img_file = "/home/bitrix/tmp/".uniqid().".".$ext;
            file_put_contents($img_file, $bin);
            return $img_file;
        }

        function check_user_by_login($phone){
            $rsUser=CUser::GetByLogin($phone);
            $arUser=$rsUser->Fetch();
            if (!$arUser){
                throw new \Bitrix\Rest\RestException(
                    'Не удалось определить пользователя сайта.',
                    'WRONG_REQUEST',
                    \CRestServer::STATUS_WRONG_REQUEST
                );
            }

            return $arUser;
        }

        $tmpFile=check_base64_image($query["photo"]);
        $user=check_user_by_login($query["phone"]);
        $arFile = CFile::MakeFileArray($tmpFile);
        $arFile['del'] = "Y";
        $arFile['old_file'] = $user["PERSONAL_PHOTO"];
        $newFileId=CFile::SaveFile($arFile, 'avatar');
        $arFields['PERSONAL_PHOTO'] = $arFile;
        global $USER;
        $result = $USER->Update($user["ID"], $arFields,false);
        if(!$result){
            throw new \Bitrix\Rest\RestException(
                'Не удалось обновить фотографию пользователя',
                'INTERNAL_SERVER_ERROR',
                \CRestServer::STATUS_INTERNAL
            );
        }
        unlink($tmpFile);
        return true;
    }
}

AddEventHandler('rest', 'OnRestServiceBuildDescription', Array("ProfileApi", "OnRestServiceBuildDescription"));

<?php
\Bitrix\Main\Loader::includeModule('rest');

use \Bitrix\Main\Type\DateTime;

class ProfileApi extends \IRestService{
    private const SCOPE='profile';

    public static function OnRestServiceBuildDescription(){
        return [
            'custom.'.static::SCOPE => [
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
            return ['message'=>'Возникли некоторые ошибки', 'errorCode'=>2, 'not_found_logins'=>$nonLogins, 'errors'=>$errors];
        }
        else{
            return ['message'=>'Ошибок нет', 'errorCode'=>0];
        }
    }
}

AddEventHandler('rest', 'OnRestServiceBuildDescription', Array("ProfileApi", "OnRestServiceBuildDescription"));

<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class PersonalAuthComponent extends CBitrixComponent implements Controllerable{

    function onPrepareComponentParams($arParams){
        if( empty($arParams['AUTH_FORM_CODE']) ){
            $this->arResult["ERROR"] = "Не выбранна веб форма авторизации";
        }
        if( empty($arParams['REGISTER_FORM_CODE']) ){
            $this->arResult["ERROR"] = "Не выбранна веб форма регистрации";
        }
        if( empty($arParams["PASSFORGOT_FORM_CODE"]) ){
            $this->arResult["ERROR"] = "Не выбранна веб форма восстановления пароля";
        }

        return $arParams;
    }

    public function ConfigureActions(){
        return [
            'login'=>[
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'forgot'=>[
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'reg'=>[
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ]
        ];
    }



    function executeComponent(){
        if(!empty($this->arResult["ERROR"])) {
            echo $this->arResult["ERROR"];
            return;
        }

        global $USER;
        if (!$USER->IsAuthorized()){
            $url = $_SERVER['REQUEST_URI'];
            $parts = parse_url($url);

            parse_str($parts['query'], $query);

            $this->arResult["SHOW_FORM"]=false;
            if (isset($query['reg'])){
                $active='reg';
                $this->arResult["SHOW_FORM"]=true;
            }
            elseif (isset($query['forgot'])){
                $active='forgot';
                $this->arResult["SHOW_FORM"]=true;
            }
            elseif (isset($query['auth'])){
                $active='auth';
                $this->arResult["SHOW_FORM"]=true;
            }

            $auth_form_id=PersonalUtils::GetIDBySID($this->arParams['AUTH_FORM_CODE']);
            $reg_form_id=PersonalUtils::GetIDBySID($this->arParams['REGISTER_FORM_CODE']);

            $passforgot_form_id=PersonalUtils::GetIDBySID($this->arParams['PASSFORGOT_FORM_CODE']);


            $this->arResult['FORMS']=[
                "AUTH"=>PersonalUtils::GetFormFileds($auth_form_id, "login", false, "ВОЙТИ", $active=='auth'?true:false),
                "FORGOT"=>PersonalUtils::GetFormFileds($passforgot_form_id, "forgot", false, "ОТПРАВИТЬ", $active=='forgot'?true:false),
                "REG"=>PersonalUtils::GetFormFileds($reg_form_id, "reg", false, "ОТПРАВИТЬ", $active=='reg'?true:false),
            ];
        }

        $this->IncludeComponentTemplate();

        $template = & $this->GetTemplate();
        $template->addExternalJs(SITE_TEMPLATE_PATH . '/vendor/inputmask/jquery.inputmask.min.js');
    }

    //КОДЫ СООБЩЕНИЙ:
    //0 - обнуляем форму
    //1 - проблема возникла с сервером
    //2 - проблемы нет, но дальше идти нельзя по каким то причинам
    //3 - проблема на стороне пользователя
    private $errorMessages=[
        "NOT_FOUND"=>[
            'CODE'=>1,
            "MESSAGE"=>'Не зарегистрированное действие'
        ],
        "ERROR"=>[
            "CODE"=>1,
            "MESSAGE"=>"Непредвиденная ошибка"
        ],
        "INCORRECT"=>[
            "CODE"=>1,
            "MESSAGE"=>"Не зарегистрированное действие: один или несколько параметров не переданы или переданы неправильно"
        ],
        "USER_NOT_FOUND"=>[
            "CODE"=>2,
            "MESSAGE"=>"Пользователь не существует. <a href=\"/?reg\" class=\"form-message\">Зарегистрироваться</a>"
        ],
        "SERVER_ERROR"=>[
            "CODE"=>1,
            "MESSAGE"=>"Возникли проблемы с сервером",
        ],
        "USER_EXIST"=>[
            "CODE"=>2,
            "MESSAGE"=>"Пользователь уже существует"
        ],
        "CODE_INCORRECT"=>[
            "CODE"=>3,
            "MESSAGE"=>"Не верный код из СМС"
        ],
        "CODE_FAILED"=>[
            "CODE"=>0,
            "MESSAGE"=>"Не удалось подтвердить код из СМС, попробуйте еще раз",
        ],
        "AUTH_FAILED"=>[
            "CODE"=>2,
            "MESSAGE"=>"Ошибка авторизации. <a href=\"/?forgot\" class=\"form-message\">Вход по СМС</a>"
        ],
        "SELECT_CLUB"=>[
            "CODE"=>3,
            "MESSAGE"=>"Выберите клуб"
        ],
        "PASSWORD_NOT_MATCH"=>[
            "CODE"=>3,
            "MESSAGE"=>"Пароли не совпадают"
        ],
        "TOO_MANY_TRIES"=>[
            "CODE"=>0,
            "MESSAGE"=>"Слишком много попыток. Попробуйте позже"
        ],
        "INCORRECT_1CID"=>[
            "CODE"=>2,
            "MESSAGE"=>"Несоответствие идентификатора. Возможно, номер телефона был изменен"
        ],
        "EMPTY_CODE"=>[
            "CODE"=>3,
            "MESSAGE"=>"Код не может быть пустым"
        ],

    ];

    private function doException($EXCEPTION_ID="ERROR"){
        throw new Exception($this->errorMessages[$EXCEPTION_ID]["MESSAGE"], $this->errorMessages[$EXCEPTION_ID]["CODE"]);
    }

    //Авторизация
    function loginAction(){
        $DATA=Context::getCurrent()->getRequest()->toArray();
        if (!empty($DATA['WEB_FORM_ID'])){
            $FORM_FIELDS=PersonalUtils::GetFormFileds($DATA['WEB_FORM_ID'], false, true);

            //Не заполнены обязательные поля или несоответствие валидатору
            if (isset($FORM_FIELDS['result']) && $FORM_FIELDS['result']==false){
                throw new Exception($FORM_FIELDS['errorText'], 2);
            }
            if (!$FORM_FIELDS['ISSET']){
                return ['result'=>false, 'errors'=>$FORM_FIELDS['ERRORS']];
            }

            global $USER;
            if (!is_object($USER)) $USER = new CUser;
            switch ($DATA['FORM_STEP']){
                case 1:
                    $user=CUser::GetByLogin($FORM_FIELDS['FIELDS']['phone']['VALUE'])->Fetch();
                    if (empty($user)) {
                        $arParams = ['login' => $FORM_FIELDS['FIELDS']['phone']['VALUE']];
                        $api = new Api(array(
                            'action' => 'lkcheck',
                            'params' => $arParams
                        ));
                        $response = $api->result();

                        //Пользователь не найден, переход на регистрацию
                        if (!$response['success']) {
                            $this->doException("USER_NOT_FOUND");
                        }

                        //Пользователь найден в 1с, завершаем регистрацию
                        //Возвращаем на фронт инфу
                        return ['result' => true, 'next_step' => 2, 'reg_code'=>true, 'field_messages'=>[[
                            'field_name'=>$FORM_FIELDS['FIELDS']['phone']['NAME'],
                            'message'=>'На Ваш номер телефона отправлен код подтверждения',
                        ]] /*,'1cdata'=>$result['data']*/];
                    }
                    else{
                        if (boolval($user['UF_PASSWORD_CORRECT'])){
                            return ['result'=>true, 'next_step'=>3, 'reg_code'=>false];
                        }
                        else{
                            //Отправялем СМС для "забыл пароль", потому что пароль не установлен
                            $arParams=[
                                'login'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                                'event'=>$FORM_FIELDS['FIELDS']['EVENT_1C']['VALUE'],
                                'id1c'=>$user['UF_1CID']
                            ];

                            $api=new Api(array(
                                'action'=>'lkreg',
                                'params'=>$arParams
                            ));
                            $response=$api->result();
                            if ($response['error']==true){
                                $this->doException("SERVER_ERROR");
                            }

                            if ($response['success']==true){
                                //Возвращаем на фронт инфу
                                return ['result'=>true, 'next_action'=>'forgot', 'next_step'=>2, 'reg_code'=>true, 'field_messages'=>[[
                                    'field_name'=>$FORM_FIELDS['FIELDS']['phone']['NAME'],
                                    'message'=>'На Ваш номер телефона отправлен код подтверждения',
                                ]] /*,'1cdata'=>$result['data']*/];
                            }
                            else{
                                switch ($response["data"]['result']['errorCode']){
                                    case 3:
                                        $this->doException("TOO_MANY_TRIES");
                                    default:
                                        $this->doException();
                                }
                            }

                        }
                    }
                case 2:
                    //Пользователь найден в 1с, нужно пройти регистрацию на сайте
                    if (empty($_POST['reg_code'])){
                        $this->doException("EMPTY_CODE");
                    }

                    //Подтверждаем СМС Код
                    $code=preg_replace('![^0-9]+!', '', $_POST['reg_code']);
                    if (strlen($code)!=5){
                        $this->doException("CODE_INCORRECT");
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

                    $response=$api->result();
                    if ($response['success']==false){
                        switch ($response['data']['result']['errorCode']){
                            case 0:
                                $this->doException("CODE_INCORRECT");
                            case 3:
                                $this->doException("CODE_FAILED");
                            default:
                                $this->doException();
                        }
                    }
                    else{
                        //Заранее добавялем пользователя с имеющимися полями и авторизовываем его
                        $user=new CUser;
                        $user1Carr=$response['data']['result']['result'];

                        function generateRandomString($length = 10) {
                            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                            $charactersLength = strlen($characters);
                            $randomString = '';
                            for ($i = 0; $i < $length; $i++) {
                                $randomString .= $characters[rand(0, $charactersLength - 1)];
                            }
                            return $randomString;
                        }

                        $passwd=generateRandomString();

                        $fields=[
                            'name',
                            'surname',
                            'email',
                            'birthday',
                            'gender',
                            'address'
                        ];

                        $UF_ISCORRECT=true;
                        foreach ($fields as $field){
                            if (empty($user1Carr[$field])){
                                $UF_ISCORRECT=false;
                                break;
                            }
                        }

                        $arFields=array(
                            'UF_IS_CORRECT'=>$UF_ISCORRECT,
                            'UF_PASSWORD_CORRECT'=>false,
                            'NAME'=>$user1Carr['name'],
                            'LAST_NAME'=>$user1Carr['surname'],
                            'EMAIL'=>$user1Carr['email'],
                            'LOGIN'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                            'ACTIVE'=>'Y',
                            "GROUP_ID"=>array(Utils::GetUGroupIDBySID('CLIENTS')),
                            'UF_1CID'=>$user1Carr['id1c'],
                            'PERSONAL_BIRTHDAY'=>$user1Carr['birthday'],
                            'PERSONAL_PHONE'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                            'PERSONAL_GENDER'=>$user1Carr['gender'],
                            "PASSWORD"=>$passwd,
                            "CONFIRM_PASSWORD"=>$passwd,
                            "UF_ADDRESS"=>$user1Carr['address'],
                        );

                        //TODO: Добавить фото профиля

                        $ID = $user->Add($arFields);
                        if (intval($ID) > 0){
                            $user->Authorize($ID);

                            $api=new Api(array(
                                'action'=>'lkedit',
                                'params'=>[
                                    'id1c'=>$user1Carr['id1c'],
                                    'login'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                                    'action'=>'update'
                                ],
                            ));

                            return ['result'=>true, 'href'=>'/personal/?v=2'];
                        }
                        else{
                            throw new Exception($user->LAST_ERROR, 1);
                        }
                    }
                case 3:
                    global $USER;
                    $arAuthResult = $USER->Login($FORM_FIELDS['FIELDS']['phone']['VALUE'], $FORM_FIELDS['FIELDS']['passwd']['VALUE'],"Y","Y");

                    if ($arAuthResult===true){
                        $rsUser = CUser::GetByLogin($FORM_FIELDS['FIELDS']['phone']['VALUE']);
                        $arUser = $rsUser->Fetch();
                        $api=new Api(array(
                            'action'=>'lkedit',
                            'params'=>[
                                'id1c'=>$arUser['UF_1CID'],
                                'login'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                                'action'=>'update'
                            ],
                        ));
                        return ['result'=>true, 'href'=>'/personal/?v=2'];
                    }
                    else{
                        $this->doException("AUTH_FAILED");
                    }
                default:
                    $this->doException();
            }
        }
        else{
            $this->doException("INCORRECT");
        }
    }

    //Забыл пароль
    function forgotAction(){
        $DATA=Context::getCurrent()->getRequest()->toArray();
        if (!empty($DATA['WEB_FORM_ID'])){
            $FORM_FIELDS=PersonalUtils::GetFormFileds($DATA['WEB_FORM_ID'], false, true);
            //Не заполнены обязательные поля или несоответствие валидатору
            if (isset($FORM_FIELDS['result']) && $FORM_FIELDS['result']==false){
                throw new Exception($FORM_FIELDS['errorText'], 2);
            }

            if (!$FORM_FIELDS['ISSET']){
                return ['result'=>false, 'errors'=>$FORM_FIELDS['ERRORS']];
            }

            switch ($DATA['FORM_STEP']){
                case 1:
                    global $USER;
                    if (!is_object($USER)) $USER = new CUser;
                    $user=CUser::GetByLogin($FORM_FIELDS['FIELDS']['phone']['VALUE'])->Fetch();
                    if (empty($user)){
                        $arParams=['login'=>$FORM_FIELDS['FIELDS']['phone']['VALUE']];
                        $api=new Api(array(
                            'action'=>'lkcheck',
                            'params'=>$arParams
                        ));
                        $response=$api->result();
                        if ($response['success']==false){
                            $this->doException("USER_NOT_FOUND");
                        }
                        else{
                            //Пользователь найден в 1с, завершаем регистрацию
                            //Возвращаем на фронт инфу
                            return ['result' => true, 'next_step' => 2, 'reg_code'=>true, 'next_action'=>'login', 'field_messages'=>[[
                                'field_name'=>$FORM_FIELDS['FIELDS']['phone']['NAME'],
                                'message'=>'На Ваш номер телефона отправлен код подтверждения',
                            ]]];
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
                        $response=$api->result();
                        if ($response['error']==true){
                            $this->doException("SERVER_ERROR");
                        }
                        if ($response['success']==true){
                            //Возвращаем на фронт инфу
                            return ['result' => true, 'next_step' => 2, 'reg_code'=>true, 'field_messages'=>[[
                                'field_name'=>$FORM_FIELDS['FIELDS']['phone']['NAME'],
                                'message'=>'На Ваш номер телефона отправлен код подтверждения',
                            ]]];
                        }
                        else{
                            switch ($response['data']['result']['errorCode']){
                                case 3:
                                    $this->doException("TOO_MANY_TRIES");
                                case 2:
                                    $this->doException("USER_NOT_FOUND");
                                default:
                                    $this->doException();
                            }
                        }
                    }
                    break;
                case 2:
                    $code = preg_replace('![^0-9]+!', '', $_POST['reg_code']);

                    if (strlen($code) != 5) {
                        $this->doException("CODE_INCORRECT");
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
                        $response = $api->result();

                        if ($response['error'] == true) {
                            $this->doException("SERVER_ERROR");
                        }

                        if ($response['success'] == true) {
                            global $USER;
                            if ($USER->Authorize($arUser['ID'])) {
                                $api=new Api(array(
                                    'action'=>'lkedit',
                                    'params'=>[
                                        'id1c'=>$arUser['UF_1CID'],
                                        'login'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                                        'action'=>'update'
                                    ],
                                ));
                                return ['result'=>true, 'href'=>'/personal/?v=2'];
                            }
                            else{
                                $this->doException("AUTH_FAILED");
                            }
                        }
                        else{
                            switch ($response['data']['result']['errorCode']) {
                                case 0:
                                    $this->doException("CODE_INCORRECT");
                                case 3:
                                    $this->doException("CODE_FAILED");
                                default:
                                    $this->doException();
                            }
                        }
                    }else{
                        $this->doException("USER_NOT_FOUND");
                    }
                    break;
                default:
                    $this->doException("INCORRECT");
            }
        }
        else{
            $this->doException("INCORRECT");
        }
    }

    //Регистрация
    function regAction(){
        $DATA=Context::getCurrent()->getRequest()->toArray();
        if (!empty($DATA['WEB_FORM_ID'])){
            $FORM_FIELDS=PersonalUtils::GetFormFileds($DATA['WEB_FORM_ID'], false, true);
            //Не заполнены обязательные поля или несоответствие валидатору
            if (isset($FORM_FIELDS['result']) && $FORM_FIELDS['result']==false){
                throw new Exception($FORM_FIELDS['errorText'], 2);
            }
            if (!$FORM_FIELDS['ISSET']){
                return ['result'=>false, 'errors'=>$FORM_FIELDS['ERRORS']];
            }

            switch ($DATA['FORM_STEP']){
                case 1:
                    $rsUser = CUser::GetByLogin($FORM_FIELDS['FIELDS']['phone']['VALUE']);
                    if($arUser = $rsUser->Fetch())
                    {
                        $this->doException("USER_EXIST");
                    }
                    $arParams=[
                        'login'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                        'name'=>$FORM_FIELDS['FIELDS']['name']['VALUE'],
                        'email'=>$FORM_FIELDS['FIELDS']['email']['VALUE'],
                        'event'=>$FORM_FIELDS['FIELDS']['EVENT_1C']['VALUE'],
                    ];
                    $api=new Api(array(
                        'action'=>'lkregistration',
                        'params'=>$arParams
                    ));
                    $result=$api->result();
                    if ($result['error']==true){
                        $this->doException();
                    }
                    if ($result['success']==true){
                        unset($_SESSION['ID_1C']);
                        $_SESSION['ID_1C']=$result['data']['result']['result']['id1c'];
                        return ['result'=>true, 'reg_code'=>true, 'next_step'=>2, 'field_messages'=>[[
                            'field_name'=>$FORM_FIELDS['FIELDS']['phone']['NAME'],
                            'message'=>'На Ваш номер телефона отправлен код подтверждения',
                        ]]];
                    }
                    else{
                        switch ($result['data']['result']['errorCode']){
                            case 3:
                                $this->doException("CODE_FAILED");
                            case 2:
                                $this->doException("USER_EXIST");
                            default:
                                $this->doException();
                        }
                    }
                case 2:
                    if (empty($_SESSION['ID_1C'])){
                        $this->doException();
                    }
                    if (empty($_POST['reg_code'])){
                        $this->doException("EMPTY_CODE");
                    }
                    else{
                        //Подтверждаем СМС Код
                        $code=preg_replace('![^0-9]+!', '', $_POST['reg_code']);
                        if (strlen($code)!=5){
                            $this->doException("CODE_INCORRECT");
                        }
                        $arParams=[
                            'id1c'=>$_SESSION['ID_1C'],
                            'code'=>$code,
                            'login'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                            'event'=>$FORM_FIELDS['FIELDS']['EVENT_1C']['VALUE'],
                            'name'=>$FORM_FIELDS['FIELDS']['name']['VALUE'],
                            "email"=>$FORM_FIELDS['FIELDS']['email']['VALUE'],
                        ];
                        $api=new Api(array(
                            'action'=>'lkcode',
                            'params'=>$arParams
                        ));
                        $result=$api->result();

                        if ($result['error']==true){
                            $this->doException();
                        }
                        if ($result['success']==true){
                            function generateRandomString($length = 10) {
                                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                                $charactersLength = strlen($characters);
                                $randomString = '';
                                for ($i = 0; $i < $length; $i++) {
                                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                                }
                                return $randomString;
                            }

                            $passwd=generateRandomString();
                            $user = new CUser;
                            $arFields=array(
                                'UF_IS_CORRECT'=>false,
                                'NAME'=>$FORM_FIELDS['FIELDS']['name']['VALUE'],
                                'LAST_NAME'=>"",
                                'EMAIL'=>$FORM_FIELDS['FIELDS']['email']['VALUE'],
                                'LOGIN'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                                "ACTIVE"=>"Y",
                                "GROUP_ID"=>array(Utils::GetUGroupIDBySID('POTENTIAL_CLIENTS')),
                                "PASSWORD"=>$passwd,
                                "CONFIRM_PASSWORD"=>$passwd,
                                'UF_1CID'=>$_SESSION['ID_1C'],
                                'PERSONAL_PHONE'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                            );
                            unset($_SESSION['ID_1C']);

                            $ID = $user->Add($arFields);
                            if (intval($ID) > 0){
                                $user->Login($FORM_FIELDS['FIELDS']['phone']['VALUE'], $passwd,"Y","Y");

                                $api=new Api(array(
                                    'action'=>'lkedit',
                                    'params'=>[
                                        'id1c'=>$arFields['UF_1CID'],
                                        'login'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                                        'action'=>'update'
                                    ],
                                ));
                                return ['result'=>true, 'href'=>'/personal/?v=2', 'dataLayer'=>['eLabel'=>'', 'eAction'=>'sendRegistrationForm']];
                            }
                            else{
                                throw new Exception($user->LAST_ERROR, 2);
                            }
                        }
                        else{
                            switch ($result['data']['result']['errorCode']){
                                case 0:
                                    $this->doException("CODE_INCORRECT");
                                case 3:
                                    $this->doException("CODE_FAILED");
                                default:
                                    $this->doException();
                            }
                        }
                    }
                default:
                    $this->doException("INCORRECT");
            }

        }
        else{
            $this->doException("INCORRECT");
        }
    }

    //Выйти
    public function exitAction(){
        global $USER;
        $USER->Logout();
        return ['result'=>true];
    }
}
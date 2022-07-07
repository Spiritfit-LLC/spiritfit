<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\SystemException;

class PersonalComponent extends CBitrixComponent implements Controllerable{

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
            'exit' => [
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'login'=>[
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
            'update'=>[
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'update1c'=>[
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'present'=>[
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'spend'=>[
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'personalcardUpdate'=>[
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'emailConfirm'=>[
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'emailCodeConfirm'=>[
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'getfreezing'=>[
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'postfreezing'=>[
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
            'getCardQr'=>[
                'prefilters' => [
                    new ActionFilter\Authentication,
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_GET)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ]
        ];
    }


    function executeComponent()
    {
        if(!empty($this->arParams["ERROR"])) {
            \Bitrix\Iblock\Component\Tools::process404(
                '',
                true,
                true,
                true,
                false
            );
        }

        if (empty($this->arParams["ERROR"])){
            global $USER;
            global $APPLICATION;

            Loader::IncludeModule("form");
            Loader::IncludeModule("iblock");


            $auth_form_id=PersonalUtils::GetIDBySID($this->arParams['AUTH_FORM_CODE']);
            $reg_form_id=PersonalUtils::GetIDBySID($this->arParams['REGISTER_FORM_CODE']);
            $passforgot_form_id=PersonalUtils::GetIDBySID($this->arParams['PASSFORGOT_FORM_CODE']);

            $url = $_SERVER['REQUEST_URI'];
            $parts = parse_url($url);

            parse_str($parts['query'], $query);
            if (isset($query['reg'])){
                $active='reg';
            }
            elseif (isset($query['forgot'])){
                $active='forgot';
            }
            else{
                $active='auth';
            }

            $this->arResult['COMPONENT_NAME']=$this->GetName();

            $this->arResult['AUTH']=$USER->IsAuthorized();

            if (!$this->arResult['AUTH']){
                $this->arResult['FORM_FIELDS']=[
                    $this->arParams['AUTH_FORM_CODE']=>PersonalUtils::GetFormFileds($auth_form_id, "login", false, "ВОЙТИ", $active=='auth'?true:false),
                    $this->arParams['REGISTER_FORM_CODE']=>PersonalUtils::GetFormFileds($reg_form_id, "reg", false, "ОТПРАВИТЬ", $active=='reg'?true:false),
                    $this->arParams['PASSFORGOT_FORM_CODE']=>PersonalUtils::GetFormFileds($passforgot_form_id, "forgot", false, "ОТПРАВИТЬ", $active=='forgot'?true:false),
                ];
                $this->arResult['AUTH_FORM_CODE']=$this->arParams['AUTH_FORM_CODE'];

                $this->IncludeComponentTemplate('auth');
            }
            else{
                if (isset($query['logout'])){
                    $USER->Logout();
                    unset($query['logout']);

                    $url_query='?';
                    foreach($query as $key=>$value){
                        $url_query.=$key.'='.$value.'&';
                    }
                    $url_query = substr($url_query,0,-1);
                    LocalRedirect($parts['path'].$url_query);

                }
                if (isset($query['abonement'])){
                    $ref_url='/abonement/'.$query['abonement'].'/';
                    if (isset($query['club'])){
                        $ref_url.=$query['club'];
                    }
                    LocalRedirect($ref_url);
                }


                $user_id=$USER->GetID();
                $this->arResult['LK_FIELDS']=PersonalUtils::GetPersonalPageFormFields($user_id, false, [], false, $this->arParams['ACTIVE_FORM']);
                $this->IncludeComponentTemplate('personal');
            }

            $template = & $this->GetTemplate();
            $template->addExternalJs(SITE_TEMPLATE_PATH . '/js/jquery.inputmask.min.js');

            $template->addExternalJs(SITE_TEMPLATE_PATH . '/js/datepicker.min.js');
            $template->addExternalJs(SITE_TEMPLATE_PATH . '/js/datepicker.ru-RU.js');
            $template->addExternalCss(SITE_TEMPLATE_PATH . '/css/datepicker.min.css');
            $template->addExternalJs(SITE_TEMPLATE_PATH . '/js/tippy/popper.min.js');
            $template->addExternalJs(SITE_TEMPLATE_PATH . '/js/tippy/tippy-bundle.umd.min.js');

        }
        else{
            echo $this->arResult["ERROR"];
        }

    }


    //AJAX FUNCTIONS
    private $errorMessages=[
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
        22=>'Вы не можете отправить подарок самому себе',
        23=>'Неверный формат номера',
        24=>'Превышен лимит списания.',
        25=>'Проверочный код не верный',
        26=>'Не удалось подтвердить Email. Обновите страницу и попробуйте еще раз',
        27=>'Время подтверждения Email истекло. Попробуйте еще раз',
        28=>'Некорректный E-mail адрес',
        100=>'Непредвиденная ошибка'
    ];

    //ВЫХОД
    public function exitAction(){
        global $USER;
        $USER->Logout();
        return ['result'=>true, 'reload'=>true];
    }
    //авторизация
    public function loginAction(){
        $DATA=Context::getCurrent()->getRequest()->toArray();
        if (!empty($DATA['WEB_FORM_ID'])){
            $FORM_FIELDS=PersonalUtils::GetFormFileds($DATA['WEB_FORM_ID'], false, true);

            //Не заполнены обязательные поля или несоответствие валидатору
            if (isset($FORM_FIELDS['result']) && $FORM_FIELDS['result']==false){
                throw new Exception($FORM_FIELDS['errorText'], 2);
            }
            if (!$FORM_FIELDS['ISSET']){
                throw new Exception($this->errorMessages[2], 2);
            }

            switch ($DATA['FORM_STEP']){
                case 1:
                    global $USER;
                    if (!is_object($USER)) $USER = new CUser;
                    $user=CUser::GetByLogin($FORM_FIELDS['FIELDS']['phone']['VALUE'])->Fetch();
                    if (empty($user)) {
                        $arParams = ['login' => $FORM_FIELDS['FIELDS']['phone']['VALUE']];
                        $api = new Api(array(
                            'action' => 'lkcheck',
                            'params' => $arParams
                        ));
                        $result = $api->result();

                        //Пользователь не найден, переход на регистрацию
                        if ($result['success'] == false) {
                            throw new Exception($this->errorMessages[21], 21);
                        }

                        //Пользователь найден в 1с, завершаем регистрацию
                        //Возвращаем на фронт инфу
                        return ['result' => true, 'next_step' => 2, 'reg-code'=>true];
                    }
                    else{
                        if (boolval($user['UF_IS_CORRECT'])){
                            return ['result'=>true, 'next_step'=>3, 'reg-code'=>false];
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
                                throw new Exception($this->errorMessages[4], 4);
                            }
                            if ($result['success']==true){
                                //Возвращаем на фронт инфу
                                return ['result'=>true, 'next_action'=>'forgot', 'next_step'=>2, 'reg-code'=>true];
                            }
                            else{
                                switch ($result['data']['result']['errorCode']){
                                    case 3:
                                        throw new Exception($this->errorMessages[11], 11);
                                    default:
                                        throw new Exception($this->errorMessages[100], 100);
                                }
                            }
                        }
                    }
                    break;
                case 2:
                    //Пользователь найден в 1с, нужно пройти регистрацию на сайте

                    if (empty($_POST['reg_code'])){
                        throw new Exception($this->errorMessages[14], 14);
                    }

                    //Подтверждаем СМС Код
                    $code=preg_replace('![^0-9]+!', '', $_POST['reg_code']);
                    if (strlen($code)!=5){
                        throw new Exception($this->errorMessages[15], 15);
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
                                throw new Exception($this->errorMessages[6], 6);
                            case 3:
                                throw new Exception($this->errorMessages[7], 7);
                            default:
                                throw new Exception($this->errorMessages[100], 100);
                        }
                    }
                    else{
                        //Заранее добавялем пользователя с имеющимися полями и авторизовываем его
                        $user=new CUser;
                        $user1Carr=$result['data']['result']['result'];

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
                        $arFields=array(
                            'UF_IS_CORRECT'=>false,
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
                                    'login'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                                    'action'=>'update'
                                ],
                            ));
                            return ['result'=>true, 'reload'=>true];
                        }
                        else{
                            throw new Exception($user->LAST_ERROR, 17);
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
                        return ['result'=>true, 'reload'=>true];
                    }
                    else{
                        throw new Exception($this->errorMessages[8], 8, null);
                    }
                    break;
                default:
                    throw new Exception($this->errorMessages[2], 2);
            }
        }
        else{
            throw new Exception($this->errorMessages[1], 1);
        }
    }
    //Регистрация
    public function regAction(){
        $DATA=Context::getCurrent()->getRequest()->toArray();
        if (!empty($DATA['WEB_FORM_ID'])){
            $FORM_FIELDS=PersonalUtils::GetFormFileds($DATA['WEB_FORM_ID'], false, true);
            //Не заполнены обязательные поля или несоответствие валидатору
            if (isset($FORM_FIELDS['result']) && $FORM_FIELDS['result']==false){
                throw new Exception($FORM_FIELDS['errorText'], 2);
            }
            if (!$FORM_FIELDS['ISSET']){
                throw new Exception($this->errorMessages[2], 2);
            }
            switch ($DATA['FORM_STEP']){
                case 1:
                    //Не выбран клуб
                    if (empty($FORM_FIELDS['FIELDS']['club']['VALUE'])){
                        throw new Exception($this->errorMessages[9], 9);
                    }
                    $rsUser = CUser::GetByLogin($FORM_FIELDS['FIELDS']['phone']['VALUE']);
                    if($arUser = $rsUser->Fetch())
                    {
                        throw new Exception($this->errorMessages[5], 5);
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
                        throw new Exception($this->errorMessages[4], 4);
                    }

                    if ($result['success']==true){
                        unset($_SESSION['ID_1C']);
                        $_SESSION['ID_1C']=$result['data']['result']['result']['id1c'];
                        return ['result'=>true, 'reload'=>false, 'reg-code'=>true, 'next_step'=>2];
                    }
                    else{
                        switch ($result['data']['result']['errorCode']){
                            case 3:
                                throw new Exception($this->errorMessages[11], 11);
                            case 2:
                                throw new Exception($this->errorMessages[5], 5);
                            default:
                                throw new Exception($this->errorMessages[100], 100);
                        }
                    }
                case 2:
                    //Не выбран клуб
                    if (empty($FORM_FIELDS['FIELDS']['club']['VALUE'])){
                        throw new Exception($this->errorMessages[9], 9);
                    }

                    if (empty($_SESSION['ID_1C'])){
                        throw new Exception($this->errorMessages[13], 13);
                    }
                    elseif (empty($_POST['reg_code'])){
                        throw new Exception($this->errorMessages[14], 14);
                    }
                    else{
                        $code=preg_replace('![^0-9]+!', '', $_POST['reg_code']);
                        if (strlen($code)!=5){
                            throw new Exception($this->errorMessages[15], 15);
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
                            throw new Exception($this->errorMessages[4], 4);
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
                                "GROUP_ID"=>array(Utils::GetUGroupIDBySID('POTENTIAL_CLIENTS')),
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
                                        'login'=>$FORM_FIELDS['FIELDS']['phone']['VALUE'],
                                        'action'=>'update'
                                    ],
                                ));
                                return ['result'=>true, 'reload'=>true, 'dataLayer'=>['eLabel'=>'', 'eAction'=>'sendRegistrationForm']];
                            }
                            else{
                                throw new Exception($user->LAST_ERROR, 17);
                            }
                        }
                        else{
                            switch ($result['data']['result']['errorCode']){
                                case 0:
                                    throw new Exception($this->errorMessages[6], 6);
                                case 3:
                                    throw new Exception($this->errorMessages[7], 7);
                                default:
                                    throw new Exception($this->errorMessages[100], 100);
                            }
                        }
                    }
                default:
                    throw new Exception($this->errorMessages[2], 2);
            }
        }
        else{
            throw new Exception($this->errorMessages[1], 1);
        }
    }
    //забыл пароль
    public function forgotAction(){
        $DATA=Context::getCurrent()->getRequest()->toArray();
        if (!empty($DATA['WEB_FORM_ID'])){
            $FORM_FIELDS=PersonalUtils::GetFormFileds($DATA['WEB_FORM_ID'], false, true);
            //Не заполнены обязательные поля или несоответствие валидатору
            if (isset($FORM_FIELDS['result']) && $FORM_FIELDS['result']==false){
                throw new Exception($FORM_FIELDS['errorText'], 2);
            }
            if (!$FORM_FIELDS['ISSET']){
                throw new Exception($this->errorMessages[2], 2);
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
                        $result=$api->result();

                        if ($result['success']==false){
                            throw new Exception($this->errorMessages[21], 21);
                        }
                        else{
                            //Пользователь найден в 1с, завершаем регистрацию
                            //Возвращаем на фронт инфу
                            return ['result' => true, 'next_step' => 2, 'reg-code'=>true, 'next_action'=>'login'];
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
                            throw new Exception($this->errorMessages[4], 4);
                        }
                        if ($result['success']==true){
                            //Возвращаем на фронт инфу
                            return ['result' => true, 'next_step' => 2, 'reg-code'=>true];
                        }
                        else{
                            switch ($result['data']['result']['errorCode']){
                                case 3:
                                    throw new Exception($this->errorMessages[11], 11);
                                case 2:
                                    throw new Exception($this->errorMessages[21], 21);
                                default:
                                    throw new Exception($this->errorMessages[100], 100);
                            }
                        }
                    }
                    break;
                case 2:
                    $code = preg_replace('![^0-9]+!', '', $_POST['reg_code']);
                    if (strlen($code) != 5) {
                        throw new Exception($this->errorMessages[15], 15);
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
                            throw new Exception($this->errorMessages[4], 4);
                        }

                        if ($result['success'] == true) {
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
                                return ['result'=>true, 'reload'=>true];
                            } else {
                                throw new Exception($this->errorMessages[8], 8);
                            }
                        } else {
                            switch ($result['data']['result']['errorCode']) {
                                case 0:
                                    throw new Exception($this->errorMessages[6], 6);
                                case 3:
                                    throw new Exception($this->errorMessages[7], 7);
                                default:
                                    throw new Exception($this->errorMessages[100], 100);
                            }
                        }
                    }
                    else{
                        throw new Exception($this->errorMessages[3], 3);
                    }

                default:
                    throw new Exception($this->errorMessages[2], 2);
            }
        }
        else{
            throw new Exception($this->errorMessages[1], 1);
        }
    }
    //Обновление данных
    public function updateAction(){
        global $USER;
        $DATA=Context::getCurrent()->getRequest()->toArray();
        $FORM_FIELDS=PersonalUtils::GetPersonalPageFormFields($USER->GetID(), true, [], $DATA['SECTION_ID']);

        //Не заполнены обязательные поля или несоответствие валидатору
        if (!$FORM_FIELDS['ISSET']){
            throw new Exception($this->errorMessages[2], 2);
        }

        if (is_array($_POST['SECTION_ID'])){
            foreach($_POST['SECTION_ID'] as $section_id){
                foreach ($FORM_FIELDS['SECTIONS'][$section_id]['FIELDS'] as $FIELD){
                    if ($FIELD['VALUE']!=false){
                        $CHANGEARR[$FIELD['USER_FIELD_CODE']]=$FIELD['VALUE'];
                        if($FIELD['USER_FIELD_CODE']=='PASSWORD'){
                            $CHANGEARR['CONFIRM_PASSWORD']=$FIELD['VALUE'];
                        }
                    }
                }
            }
        }
        else{
            foreach ($FORM_FIELDS['SECTIONS'][$_POST['SECTION_ID']]['FIELDS'] as $FIELD){
                if ($FIELD['VALUE']!=false){
                    $CHANGEARR[$FIELD['USER_FIELD_CODE']]=$FIELD['VALUE'];
                    if($FIELD['USER_FIELD_CODE']=='PASSWORD'){
                        $CHANGEARR['CONFIRM_PASSWORD']=$FIELD['VALUE'];
                    }
                }
                elseif ($FIELD['TYPE']=='checkbox'){
                    $CHANGEARR[$FIELD['USER_FIELD_CODE']]=$FIELD['VALUE'];
                }
            }
        }

        if (!$FORM_FIELDS['IS_CORRECT']){
            $CHANGEARR['UF_IS_CORRECT']=true;
            $reload=true;
        }
        else{
            $reload=false;
        }

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
                'imageurl'=>$FORM_FIELDS['PERSONAL_PHOTO'],
                'action'=>'edit'
            );
            $api=new Api(array(
                'action'=>'lkedit',
                'params'=>$arParams,
            ));
            return ['result'=>true, 'fields'=>$arParams, 'reload'=>$reload, 'message'=>'Ваши данные обновлены'];
        }
        else{
            throw new Exception($USER->LAST_ERROR, 18);
        }
    }
    //Обновление данных из 1С
    public function update1cAction(){
        global $USER;
        $result=PersonalUtils::UpdatePersonalInfoFrom1C($USER->GetID());
        if ($result!=false){
            return ['result'=>true, 'reload'=>true, 'message'=>'Ваши данные обновлены'];
        }
        else{
            throw new Exception($this->errorMessages[100], 100);
        }
    }
    //Обновление фото
    public function updatePhotoAction(){
        if (!empty($_FILES['new-photo-file'])){
            if (0 < $_FILES['file']['error']){
                throw new Exception($this->errorMessages[19].': '.$_FILES['file']['error'], 19);
            }
            else{
                $fileId = CFile::SaveFile($_FILES["new-photo-file"],'avatar');
                $arFile = CFile::MakeFileArray($fileId);
                $arFile['del'] = "Y";
                $arFile['old_file'] = $_POST['old-photo-id'];
                $arFields['PERSONAL_PHOTO'] = $arFile;

                global $USER;
                $result = $USER->Update($USER->GetID(), $arFields,false);

                if(!$result){
                    throw new Exception($this->errorMessages[19], 19);
                }

                $rsUser = CUser::GetByID($USER->GetID());
                $arUser = $rsUser->Fetch();

                $arParams=array(
                    'name'=>$arUser['NAME'],
                    'surname'=>$arUser['LAST_NAME'],
                    'email'=>$arUser['EMAIL'],
                    'birthday'=>$arUser['PERSONAL_BIRTHDAY'],
                    'gender'=>$arUser['PERSONAL_GENDER'],
                    'id1c'=>$arUser['UF_1CID'],
                    'login'=>$arUser['LOGIN'],
                    'imageurl'=>CFile::GetPath($arUser['PERSONAL_PHOTO']),
                    'action'=>'edit'
                );
                $api=new Api(array(
                    'action'=>'lkedit',
                    'params'=>$arParams,
                ));

                return ['result'=>true, 'reload'=>true];
            }
        }



    }
    //Подарок другу
    public function presentAction(){
        global $USER;
        $DATA=Context::getCurrent()->getRequest()->toArray();
        $recipient=$_POST['recipient'];
        $recipient=substr(preg_replace('![^0-9]+!', '', $recipient), 1);
        if ($recipient[0]!='9' || strlen($recipient)!=10){
            $recipient=false;
        }
        if (empty($recipient)){
            throw new Exception($this->errorMessages[23], 23);
        }
        if ($USER->GetLogin()==$recipient){
            throw new Exception($this->errorMessages[22], 22);
        }
        if (empty($_POST['sum'])){
            throw new Exception($this->errorMessages[2], 2);
        }

        $rsUser = CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();
        $arParams=[
            'id1c'=>$arUser['UF_1CID'],
            'login'=>$arUser['LOGIN'],
            'sum'=>(int)$_POST['sum'],
            'recipient'=>$recipient,
        ];
        $action='lkpresent';

        $api=new Api([
            'action'=>$action,
            'params'=> $arParams
        ]);

        $result=$api->result();
        if (!$result['success']){
            throw new Exception($result['data']['result']['userMessage']);
        }

        $result=PersonalUtils::UpdatePersonalInfoFrom1C($USER->GetID());
        if ($result!=false){
            return ['result'=>true, 'reload'=>true];
        }
        else{
            throw new Exception($this->errorMessages[100], 100);
        }
    }
    //Списать бонусы
    public function spendAction(){
        global $USER;
        if (empty($_POST['sum'])){
            throw new Exception($this->errorMessages[2], 2);
        }
        $rsUser = CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();

        if (!empty($arUser['UF_PAYMENT_LIMIT']) && ((int)$_POST['sum']>(int)$arUser['UF_PAYMENT_LIMIT'])){
            throw new Exception($this->errorMessages[24], 24);
        }

        $arParams=[
            'id1c'=>$arUser['UF_1CID'],
            'login'=>$arUser['LOGIN'],
            'sum'=>(int)$_POST['sum'],
        ];
        $action='lkpayments';

        $api=new Api([
            'action'=>$action,
            'params'=> $arParams
        ]);

        $result=$api->result();
        if (!$result['success']){
            throw new Exception($result['data']['result']['userMessage']);
        }

        $result=PersonalUtils::UpdatePersonalInfoFrom1C($USER->GetID());
        if ($result!=false){
            return ['result'=>true, 'reload'=>true];
        }
        else{
            throw new Exception($this->errorMessages[100], 100);
        }
    }
    //СОХРАНИТЬ ВИЗИТКУ
    public function personalcardUpdateAction(){
        global $USER;
        $DATA=Context::getCurrent()->getRequest()->toArray();

        $FORM_FIELDS=PersonalUtils::GetPersonalPageFormFields($USER->GetID(), true, [], $DATA['SECTION_ID']);

        //Не заполнены обязательные поля или несоответствие валидатору
        if (!$FORM_FIELDS['ISSET']){
            throw new Exception($this->errorMessages[2], 2);
        }

        if (is_array($_POST['SECTION_ID'])){
            foreach($_POST['SECTION_ID'] as $section_id){
                foreach ($FORM_FIELDS['SECTIONS'][$section_id]['FIELDS'] as $FIELD){
                    if ($FIELD['VALUE']!=false){
                        $CHANGEARR[$FIELD['USER_FIELD_CODE']]=$FIELD['VALUE'];
                        if($FIELD['USER_FIELD_CODE']=='PASSWORD'){
                            $CHANGEARR['CONFIRM_PASSWORD']=$FIELD['VALUE'];
                        }
                    }
                    elseif ($FIELD['TYPE']=='checkbox'){
                        $CHANGEARR[$FIELD['USER_FIELD_CODE']]=$FIELD['VALUE'];
                    }
                }
            }
        }
        else{
            foreach ($FORM_FIELDS['SECTIONS'][$_POST['SECTION_ID']]['FIELDS'] as $FIELD){
                if ($FIELD['VALUE']!=false){
                    $CHANGEARR[$FIELD['USER_FIELD_CODE']]=$FIELD['VALUE'];
                    if($FIELD['USER_FIELD_CODE']=='PASSWORD'){
                        $CHANGEARR['CONFIRM_PASSWORD']=$FIELD['VALUE'];
                    }
                }
            }
        }

        $result = $USER->Update($USER->GetID(), $CHANGEARR, false);

        if($result==true){
            return ['result'=>true, 'reload'=>false, 'message'=>'Ваши данные обновлены'];
        }
        else{
            throw new Exception($USER->LAST_ERROR, 18);
        }
    }
    //Подтверждение email
    public function emailConfirmAction(){
        unset($_SESSION['CORRECT_EMAIL']);
        unset($_SESSION['EMAIL_CODE']);
        unset($_SESSION['EMAIL_CODE_COUNT']);
        unset($_SESSION['EMAIL_CODE_TIME']);
        $email=Context::getCurrent()->getRequest()->getPost('email');
        if (empty($email)){
            throw new Exception($this->errorMessages[2], 2);
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception($this->errorMessages[28], 28);
        }

        $api=new Api([
            'action'=>'lkemailconfirm',
            'params'=>[
                'email'=>$email
            ]
        ]);

        $response=$api->result();

        if (!$response['success']){
            throw new Exception($response['data']['result']['message'], 100);
        }
        else{
            $_SESSION['EMAIL_CODE']=$response['data']['result']['result']['code'];
            $_SESSION['EMAIL_CODE_COUNT']=0;
            $_SESSION['EMAIL_CODE_TIME']=time();
            $_SESSION['CORRECT_EMAIL']=$email;

            if (!empty($_SESSION['EMAIL_CODE'])){
                return array('result'=>true);
            }
            else{
                throw new Exception($this->errorMessages[100], 100);
            }
        }

    }
    //Подтверждение кода из email
    public function emailCodeConfirmAction(){
//        return [$_SESSION['EMAIL_CODE'], $_SESSION['EMAIL_CODE_COUNT'], $_SESSION['EMAIL_CODE_TIME']];
        if (!isset($_SESSION['EMAIL_CODE']) || !isset($_SESSION['EMAIL_CODE_COUNT']) || !isset($_SESSION['EMAIL_CODE_TIME'])){
            throw new Exception($this->errorMessages[26], 26);
        }

        if ($_SESSION['EMAIL_CODE_COUNT']>3){
            unset($_SESSION['EMAIL_CODE']);
            unset($_SESSION['EMAIL_CODE_COUNT']);
            unset($_SESSION['EMAIL_CODE_TIME']);
            unset($_SESSION['CORRECT_EMAIL']);
            throw new Exception($this->errorMessages[26], 26);
        }

        if (time()-$_SESSION['EMAIL_CODE_TIME']>180){
            unset($_SESSION['EMAIL_CODE']);
            unset($_SESSION['EMAIL_CODE_COUNT']);
            unset($_SESSION['EMAIL_CODE_TIME']);
            unset($_SESSION['CORRECT_EMAIL']);
            throw new Exception($this->errorMessages[27], 27);
        }

        $code=Context::getCurrent()->getRequest()->getPost('code');
        if (empty($code)){
            throw new Exception($this->errorMessages[2], 2);
        }
        $code = preg_replace('![^0-9]+!', '', $code);
        if (strlen($code) != 5) {
            throw new Exception($this->errorMessages[15], 15);
        }

        if ($_SESSION['EMAIL_CODE']==$code){
            global $USER;
            $result=$USER->Update($USER->GetID(), array('UF_EMAIL_IS_CONFIRM'=>true, 'EMAIL'=>$_SESSION['CORRECT_EMAIL']), false);
            if($result==true){
                $arParams=array(
                    'email'=>$_SESSION['CORRECT_EMAIL'],
                    'authorizationemail'=>'edit'
                );
                $api=new Api(array(
                    'action'=>'lkedit',
                    'params'=>$arParams,
                ));

                unset($_SESSION['EMAIL_CODE']);
                unset($_SESSION['EMAIL_CODE_COUNT']);
                unset($_SESSION['EMAIL_CODE_TIME']);
                unset($_SESSION['CORRECT_EMAIL']);

                return ['result'=>true, 'reload'=>true, 'message'=>'Ваши данные обновлены'];
            }
            else{
                throw new Exception($USER->LAST_ERROR, 18);
            }
            return array('result'=>true, 'reload'=>true, 'section'=>Context::getCurrent()->getRequest()->getPost('SECTION_ID')[0]);
        }
        else{
            $_SESSION['EMAIL_CODE_COUNT'].=1;
            if ($_SESSION['EMAIL_CODE_COUNT']>3){
                unset($_SESSION['EMAIL_CODE']);
                unset($_SESSION['EMAIL_CODE_COUNT']);
                unset($_SESSION['EMAIL_CODE_TIME']);
                unset($_SESSION['CORRECT_EMAIL']);
                throw new Exception($this->errorMessages[26], 26);
            }
            throw new Exception($this->errorMessages[25], 25);
        }
    }

    //Запрос заморозки
    public function getfreezingAction(){
        global $USER;
        $rsUser = CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();

        $rsUser = CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();
        $arParams=[
            'id1c'=>$arUser['UF_1CID'],
            'login'=>$arUser['LOGIN']
        ];
        $action='lkfreezingget';

        $api=new Api([
            'action'=>$action,
            'params'=> $arParams
        ]);

        $result=$api->result();
        if (!$result['success']){
            throw new Exception($result['data']['result']['userMessage'],100);
        }

        return ['result'=>true,'freezings'=>$result['data']['result']['result'], 'next_action'=>'postfreezing'];
    }
    //Отправка заморозки
    public function postfreezingAction(){
        $freezing_id=Context::getCurrent()->getRequest()->getPost('freezing');
        if(empty($freezing_id)){
            throw new Exception($this->errorMessages[2], 2);
        }

        global $USER;
        $rsUser = CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();

        $rsUser = CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();
        $arParams=[
            'id1c'=>$arUser['UF_1CID'],
            'login'=>$arUser['LOGIN'],
            'id'=>$freezing_id
        ];
        $action='lkfreezingpost';

        $api=new Api([
            'action'=>$action,
            'params'=> $arParams
        ]);

        $result=$api->result();
        if (!$result['success']){
            throw new Exception($result['data']['result']['userMessage'], 100);
        }
        PersonalUtils::UpdatePersonalInfoFrom1C($USER->GetID());
        return ['result'=>true,'invoice'=>$result['data']['result']['result']];
    }

    //Запрос бесплатной заморозки
    public function getfreefreezingAction(){
        global $USER;
        $rsUser = CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();

        $rsUser = CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();
        $arParams=[
            'id1c'=>$arUser['UF_1CID'],
            'login'=>$arUser['LOGIN']
        ];
        $action='lkfreefreezingget';

        $api=new Api([
            'action'=>$action,
            'params'=> $arParams
        ]);

        $result=$api->result();
        if (!$result['success']){
            throw new Exception($result['data']['result']['userMessage'],100);
        }

        sort($result['data']['result']['result']);

        return ['result'=>true,'freezings'=>$result['data']['result']['result'], 'next_action'=>'postfreezing'];
    }
    //Отправка бесплатной заморозки
    public function freefreezingpostAction(){
        $freezing=Context::getCurrent()->getRequest()->getPost('freezing');
        if(empty($freezing)){
            throw new Exception($this->errorMessages[2], 2);
        }
        global $USER;
        $rsUser = CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();

        $rsUser = CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();
        $arParams=[
            'id1c'=>$arUser['UF_1CID'],
            'login'=>$arUser['LOGIN'],
            'value'=>(int)$freezing
        ];
        $action='lkfreefreezingpost';

        $api=new Api([
            'action'=>$action,
            'params'=> $arParams
        ]);

        $result=$api->result();
        if (!$result['success']){
            throw new Exception($result['data']['result']['userMessage'], 100);
        }
        PersonalUtils::UpdatePersonalInfoFrom1C($USER->GetID());
        return ['reload'=>true];
    }


    //Запрос QR кода сотрудника
    public function getCardQrAction(){
        global $USER;
        $user_id=$USER->GetID();
        $rsUser=CUser::GetByID($user_id);

        if ($arUser = $rsUser->Fetch()){
            if (!empty($arUser['UF_CARD_QRCODE'])){
                return ['result'=>true, 'src'=>$arUser['UF_CARD_QRCODE']];
            }
            else{
                $arParams=[
                    'data'=>MAIN_SITE_URL.'/personalcard/?ID='.$user_id,
                    'logo'=>true,
                    'background'=>'white'
                ];

                $api=new Api([
                    'action'=>'getqrcode',
                    'params'=>$arParams,
                ]);

                $response=$api->result();
                if (!$response['success']){
                    if (empty($response['data']['result']['message'])){
                        throw new Exception($this->errorMessages[100], 100);
                    }
                    else{
                        throw new Exception($response['data']['result']['message'], 100);
                    }
                }
                else{
                    $USER->Update($user_id, [
                        'UF_CARD_QRCODE'=>$response['data']['result']['result']['code_src']
                    ], false);
                    return ['result'=>true, 'src'=>$response['data']['result']['result']['code_src']];
                }
            }
        }
        else{
            throw new Exception($this->errorMessages[100], 100);
        }
    }
}
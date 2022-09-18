<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
global $USER;

use \Bitrix\Main\Loader;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;
use \Bitrix\Main\Context;

class PersonalCardComponent extends CBitrixComponent implements Controllerable{

    public function ConfigureActions(){
        return [
            'getVCard'=>[
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        array(ActionFilter\HttpMethod::METHOD_POST)
                    ),
                    new ActionFilter\Csrf(),
                ],
                'postfilters' => []
            ],
        ];
    }

    function onPrepareComponentParams($arParams){
        if (empty($arParams['PROFILE_ID'])){
            $this->arResult['PAGE_TITLE']="Пользователь не определен";
            $this->arResult['ERROR']='Пользователь не определен';
        }
        else{
            $rsUser = CUser::GetByID($arParams['PROFILE_ID']);
            $arUser = $rsUser->Fetch();
            if (empty($arUser) || empty($arUser['UF_CARD_ACTIVE'])){
                $this->arResult['PAGE_TITLE']="Пользователь не найден";
                $this->arResult['ERROR']='Пользователь не найден';
            }
            else{
                $this->arResult['PAGE_TITLE']=$arUser["NAME"].' '.$arUser["LAST_NAME"];
                $this->arResult['USER']=$arUser;

            }
        }
        return $arParams;
    }

    function executeComponent()
    {
        global $APPLICATION;
        $APPLICATION->SetTitle($this->arResult['PAGE_TITLE']);

        $this->arResult['USER_NAME']=$this->arResult['USER']["NAME"].' '.$this->arResult['USER']["LAST_NAME"];
        $this->arResult['POSITION']=$this->arResult['USER']['WORK_POSITION'];
        $this->arResult['INFO']=$this->arResult['USER']['WORK_NOTES'];

        $this->arResult['MAIN_FIELDS']=[
            [
                'ICON'=>$_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/img/icons/personal.card/job-icon.svg',
                'TEXT'=>$this->arResult['USER']['WORK_COMPANY'],
            ],
            [
                'ICON'=>$_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/img/icons/personal.card/job.mail-icon.svg',
                'TEXT'=>$this->arResult['USER']['WORK_MAILBOX']
            ],
            [
                'ICON'=>$_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/img/icons/personal.card/job.address-icon.svg',
                'TEXT'=>$this->arResult['USER']['WORK_STREET']
            ],
            [
                'ICON'=>$_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/img/icons/personal.card/job.phone-icon.svg',
                'TEXT'=>$this->arResult['USER']['WORK_PHONE']
            ]
        ];

        if (!empty($this->arResult['USER']['UF_CARD_VK'])){
            $this->arResult['SOCIALS'][]=[
                'ICON'=>$_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/img/vk-brands.svg',
                'LINK'=>$this->arResult['USER']['UF_CARD_VK'][0]=='@'?'https://vk.com/'.mb_substr($this->arResult['USER']['UF_CARD_VK'], 1):$this->arResult['USER']['UF_CARD_VK']
            ];
        }
        if (!empty($this->arResult['USER']['UF_CARD_TELEGRAM'])){
            $this->arResult['SOCIALS'][]=[
                'ICON'=>$_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/img/telegram-brands.svg',
                'LINK'=>$this->arResult['USER']['UF_CARD_VK'][0]=='@'?'https://t.me/'.mb_substr($this->arResult['USER']['UF_CARD_VK'],1):$this->arResult['USER']['UF_CARD_VK']
            ];
        }
        if (!empty($this->arResult['USER']['UF_CARD_WHATSUP'])){
            $this->arResult['SOCIALS'][]=[
                'ICON'=>$_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/img/icons/personal.card/social.whatasapp-icon.svg',
                'LINK'=>'https://wa.me/'.preg_replace('![^0-9]+!', '', $this->arResult['USER']['UF_CARD_WHATSUP'])
            ];
        }

//        $this->arResult['LK_FIELDS']=PersonalUtils::GetPersonalPageFormFields($this->arParams['PROFILE_ID'], false, [], Utils::GetIBlockSectionIDBySID('employe-card'), false, 1000);
        global $settings;
        $image=CFile::GetPath(!empty($this->arResult['USER']['PERSONAL_PHOTO']) ? $this->arResult['USER']['PERSONAL_PHOTO'] : $settings["PROPERTIES"]['PROFILE_DEFAULT_PHOTO']['VALUE']);
        list($width, $height) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $image);
        if ($height > 1000 || $width>1000) {
            $ratio = $height < $width?1000 / $height:1000/$width;
            $filename=explode('.', basename($image));
            $filename[0].=(string)1000;
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
        $this->arResult['PERSONAL_PHOTO']=$img_src;
        $this->arResult['COMPONENT_NAME']=$this->GetName();

        $this->IncludeComponentTemplate();
    }

    function getVCardAction(){
        $user_id=Context::getCurrent()->getRequest()->getPost('USER-ID');
        $rsUser = CUser::GetByID($user_id);
        $arUser = $rsUser->Fetch();

        // Вставка аватара в vcard
        // $vCard - переменная, содержащая тело файла vcard
        $vCard  = 'BEGIN:VCARD' . "\r\n";
        $vCard .= 'VERSION:3.0' . "\r\n";
        $vCard .= 'URL:https://'.$_SERVER['SERVER_NAME'].'/personalcard/?ID='.$user_id. "\r\n";
        $vCard .= 'FN;CHARSET=UTF-8:'.$arUser['LAST_NAME'].' '.$arUser['NAME']. "\r\n";
        $vCard .= 'N;CHARSET=UTF-8:'.$arUser['LAST_NAME'].';'.$arUser['NAME']. ";;;;\r\n";

        global $settings;
        $image=$_SERVER['DOCUMENT_ROOT'] . CFile::GetPath(!empty($arUser['PERSONAL_PHOTO']) ? $arUser['PERSONAL_PHOTO'] : $settings["PROPERTIES"]['PROFILE_DEFAULT_PHOTO']['VALUE']);
        if (file_exists($image)) {
            $photo = base64_encode(file_get_contents($image));
            // необходимо разделить строку на меньшие, по 72 символа, чтобы соответствовать стандарту
//            $photo = wordwrap($photo, 72, "\r\n", true);

            $vCard .= 'PHOTO;TYPE=JPEG;ENCODING=BASE64:' . $photo . "\r\n\r\n"; // здесь нужна двойная «отбивка»
        }

        $vCard .= 'TEL;TYPE=CELL:+'.preg_replace('![^0-9]+!', '', $arUser['UF_CARD_WHATSUP']). "\r\n";
        $vCard .= 'EMAIL;TYPE=INTERNET:'.$arUser['WORK_MAILBOX']. "\r\n";
        $vCard .= 'TITLE;CHARSET=UTF-8:'.$arUser['WORK_POSITION']. "\r\n";
        $vCard .= 'ORG;CHARSET=UTF-8:'.$arUser['WORK_COMPANY']. "\r\n";
        $vCard .= 'END:VCARD' . "\r\n";

        function get_translit($string) {
            $converter = array(
                'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'YO', 'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I', 'Й' => 'Y',
                'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F',
                'Х' => 'H', 'Ц' => 'C', 'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'SHCH', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA',

                ' '=>'_',

                'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y',
                'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f',
                'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
            );

            $string = strtr($string, $converter);

            return $string;
        }
        return ['filename'=>'card_'.get_translit($arUser['LAST_NAME'].' '.$arUser['NAME'].'.vcf'), 'file_content'=>$vCard];
    }
}
<?
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Highloadblock as HL;
use \Bitrix\Main\Loader;
use \Bitrix\Main\EventManager;

class outcode_quiz extends CModule
{
    const MODULE_ID = 'outcode.quiz';
    var $MODULE_ID = "outcode.quiz";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;

    function __construct()
    {
        $arModuleVersion = [];

        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen('/index.php'));
        include($path.'/version.php');
        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion))
        {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
        $this->MODULE_NAME = Loc::getMessage("QUIZ_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("QUIZ_DESCRIPTION");
    }

    private function recurseCopy($src, $dst) {
        $dir = opendir($src);
        $result = ($dir === false ? false : true);

        if ($result !== false) {
            $result = @mkdir($dst) || file_exists($dst);
            if ($result === true) {
                while(false !== ( $file = readdir($dir)) ) {
                    if (( $file != '.' ) && ( $file != '..' ) && $result) {
                        if ( is_dir($src . '/' . $file) ) {
                            $result = $this->recurseCopy($src . '/' . $file,$dst . '/' . $file);
                        } else {
                            $result = copy($src . '/' . $file,$dst . '/' . $file);
                        }
                    }
                }
                closedir($dir);
            }
        }

        return $result;
    }

    private function removeDirectory($dir) {
        if( is_dir($dir) ) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if ($file != "." && $file != "..") {
                    if (filetype($dir."/".$file) == "dir")
                        $this->removeDirectory($dir."/".$file);
                    else
                        unlink($dir."/".$file);
                }
            }
            reset($files);
            rmdir($dir);
        }
    }

    function DoInstall()
    {
        if(!Loader::includeModule('iblock')) return false;
        if(!Loader::includeModule('highloadblock')) return false;

        global $APPLICATION, $DOCUMENT_ROOT;

        /* Создание инфоблока */
        $iBlockType = 'outcode_quiz';
        $iBlockProperties = [
            'QUESTION_STRING' => ['TYPE' => 'S', 'NAME' => Loc::getMessage('QUIZ_PROPERTY_QUESTION_STRING'), 'MULTIPLE' => 'N', 'PROPERTY_WITH_DESCRIPTION' => 'N', 'IS_REQUIRED' => 'Y'],
            'DATE_START' => ['TYPE' => 'S:DateTime', 'NAME' => Loc::getMessage('QUIZ_PROPERTY_DATE_START'), 'VALUES' => [], 'MULTIPLE' => 'N', 'PROPERTY_WITH_DESCRIPTION' => 'N', 'IS_REQUIRED' => 'Y'],
            'DATE_END' => ['TYPE' => 'S:DateTime', 'NAME' => Loc::getMessage('QUIZ_PROPERTY_DATE_END'), 'VALUES' => [], 'MULTIPLE' => 'N', 'PROPERTY_WITH_DESCRIPTION' => 'N', 'IS_REQUIRED' => 'Y'],
            'TYPE' => ['TYPE' => 'L', 'NAME' => Loc::getMessage('QUIZ_PROPERTY_TYPE'), 'VALUES' => [
                0 => [
                    'VALUE' => Loc::getMessage('QUIZ_PROPERTY_TYPE_1'),
                    'DEF' => 'Y',
                    'SORT' => '100'
                ],
                1 => [
                    'VALUE' => Loc::getMessage('QUIZ_PROPERTY_TYPE_2'),
                    'DEF' => 'N',
                    'SORT' => '200'
                ],
                2 => [
                    'VALUE' => Loc::getMessage('QUIZ_PROPERTY_TYPE_3'),
                    'DEF' => 'N',
                    'SORT' => '300'
                ],
            ], 'MULTIPLE' => 'N', 'PROPERTY_WITH_DESCRIPTION' => 'N', 'IS_REQUIRED' => 'Y'],
            'ANSWERS_STRING' => ['TYPE' => 'S', 'NAME' => Loc::getMessage('QUIZ_PROPERTY_ANSWERS_STRING'), 'MULTIPLE' => 'Y', 'PROPERTY_WITH_DESCRIPTION' => 'N', 'IS_REQUIRED' => 'N'],
            'ANSWERS_IMAGE' => ['TYPE' => 'F', 'NAME' => Loc::getMessage('QUIZ_PROPERTY_ANSWERS_IMAGE'), 'MULTIPLE' => 'Y', 'PROPERTY_FILE_TYPE' => 'png, jpg, gif, webp', 'PROPERTY_WITH_DESCRIPTION' => 'Y', 'IS_REQUIRED' => 'N'],
        ];

        $iblockTypeArr = [];
        $iblockType = CIBlockType::GetList([], [ 'ID' => $iBlockType ]);
        if(!$iblockTypeArr = $iblockType->GetNext()){
            $iblockTypeObj = new CIBlockType();
            $iblockTypeArr['ID'] =  $iblockTypeObj->Add([
                'ID' => $iBlockType,
                'SECTIONS' => 'N',
                'LANG' => [
                    'ru' => [
                        'NAME' => Loc::getMessage('QUIZ_IBLOCK_NAME'),
                    ]
                ]
            ]);
        }

        if(!$iblockTypeArr['ID']) {
            return false;
        }

        $arSites = [];
        $rsSites = CSite::GetList($by = 'sort', $order = 'desc', ['ACTIVE' => 'Y']);
        while ($arSite = $rsSites->Fetch()){
            $arSites[] = $arSite['ID'];
        }

        $iblockObj = CIBlock::GetList(
            array(),
            array(
                "TYPE" => $iBlockType,
                "CODE" => $iBlockType
            )
        );

        if(!$iblockArr = $iblockObj->GetNext()) {
            $iBlockObj = new CIBlock();
            $iBlock['ID'] = $iBlockObj->Add([
                    'ACTIVE' => 'Y',
                    'NAME' => Loc::getMessage('QUIZ_IBLOCK_NAME'),
                    'CODE' => $iBlockType,
                    'LIST_PAGE_URL' => '',
                    'DETAIL_PAGE_URL' => '',
                    'IBLOCK_TYPE_ID' => $iBlockType,
                    'SITE_ID' => $arSites,
                    'SORT' => 1,
                    'WORKFLOW' => 'N',
                    'EDIT_FILE_AFTER' => (strpos (SM_VERSION, '12')!==false ? "/bitrix/modules/".$this->MODULE_ID."/admin/iblock_element_edit.php" : "")
                ]
            );

            if( !empty($iBlock['ID']) ) {
                $sort = 100;
                $ibpObj = new CIBlockProperty;

                foreach($iBlockProperties as $propertyCode => $propertyItem) {
                    switch($propertyItem['TYPE']) {
                        case 'S':
                            $propertyArr = Array(
                                'NAME' => $propertyItem['NAME'],
                                'ACTIVE' => "Y",
                                'SORT' => $sort,
                                'CODE' => $propertyCode,
                                'PROPERTY_TYPE' => 'S',
                                'MULTIPLE' => !empty($propertyItem['MULTIPLE']) ? $propertyItem['MULTIPLE'] : 'N',
                                'IBLOCK_ID' => $iBlock['ID'],
                                'WITH_DESCRIPTION' => !empty($propertyItem['PROPERTY_WITH_DESCRIPTION']) ? $propertyItem['PROPERTY_WITH_DESCRIPTION'] : 'N',
                                'IS_REQUIRED' => !empty($propertyItem['IS_REQUIRED']) ? $propertyItem['IS_REQUIRED'] : 'N'
                            );
                            $ibpObj->Add($propertyArr);
                            break;
                        case 'S:DateTime':
                            $propertyArr = Array(
                                'NAME' => $propertyItem['NAME'],
                                'ACTIVE' => "Y",
                                'SORT' => $sort,
                                'CODE' => $propertyCode,
                                'PROPERTY_TYPE' => 'S',
                                'USER_TYPE' => 'DateTime',
                                'MULTIPLE' => !empty($propertyItem['MULTIPLE']) ? $propertyItem['MULTIPLE'] : 'N',
                                'IBLOCK_ID' => $iBlock['ID'],
                                'WITH_DESCRIPTION' => !empty($propertyItem['PROPERTY_WITH_DESCRIPTION']) ? $propertyItem['PROPERTY_WITH_DESCRIPTION'] : 'N',
                                'IS_REQUIRED' => !empty($propertyItem['IS_REQUIRED']) ? $propertyItem['IS_REQUIRED'] : 'N'
                            );
                            $ibpObj->Add($propertyArr);
                            break;
                        case 'L':
                            $propertyArr = Array(
                                'NAME' => $propertyItem['NAME'],
                                "ACTIVE" => "Y",
                                "SORT" => $sort,
                                "CODE" => $propertyCode,
                                "PROPERTY_TYPE" => "L",
                                "IBLOCK_ID" => $iBlock['ID'],
                                'MULTIPLE' => !empty($propertyItem['MULTIPLE']) ? $propertyItem['MULTIPLE'] : 'N',
                                'VALUES' => !empty($propertyItem['VALUES']) ? $propertyItem['VALUES'] : [],
                                'WITH_DESCRIPTION' => !empty($propertyItem['PROPERTY_WITH_DESCRIPTION']) ? $propertyItem['PROPERTY_WITH_DESCRIPTION'] : 'N',
                                'IS_REQUIRED' => !empty($propertyItem['IS_REQUIRED']) ? $propertyItem['IS_REQUIRED'] : 'N'
                            );
                            $ibpObj->Add($propertyArr);
                            break;
                        case 'F':
                            $propertyArr = Array(
                                'NAME' => $propertyItem['NAME'],
                                "ACTIVE" => "Y",
                                "SORT" => $sort,
                                "CODE" => $propertyCode,
                                "PROPERTY_TYPE" => "F",
                                "IBLOCK_ID" => $iBlock['ID'],
                                'MULTIPLE' => !empty($propertyItem['MULTIPLE']) ? $propertyItem['MULTIPLE'] : 'N',
                                'WITH_DESCRIPTION' => !empty($propertyItem['PROPERTY_WITH_DESCRIPTION']) ? $propertyItem['PROPERTY_WITH_DESCRIPTION'] : 'N',
                                'FILE_TYPE' => !empty($propertyItem['PROPERTY_FILE_TYPE']) ? $propertyItem['PROPERTY_FILE_TYPE'] : '',
                                'IS_REQUIRED' => !empty($propertyItem['IS_REQUIRED']) ? $propertyItem['IS_REQUIRED'] : 'N'
                            );
                            $ibpObj->Add($propertyArr);
                            break;
                    }
                    $sort += 100;
                }
            }
        }

        /*Создание hightload блока*/
        $arLangs = Array(
            'ru' => Loc::getMessage('QUIZ_HIGHTLOAD_BLOCK_NAME'),
        );

        $blockTableResult = HL\HighloadBlockTable::add([
            'NAME' => 'OutcodeQuizResult',
            'TABLE_NAME' => 'outcode_quiz_result'
        ]);

        if($blockTableResult->isSuccess()) {
            $id = $blockTableResult->getId();
            foreach($arLangs as $langKey => $langVal){
                HL\HighloadBlockLangTable::add([
                    'ID' => $id,
                    'LID' => $langKey,
                    'NAME' => $langVal
                ]);
            }

            $HLObject = 'HLBLOCK_'.$id;
            $hlBlockProperties = [
                'UF_QUESTION_ID' => [
                    'ENTITY_ID' => $HLObject,
                    'FIELD_NAME' => 'UF_QUESTION_ID',
                    'USER_TYPE_ID' => 'integer',
                    'MANDATORY' => 'Y',
                    'EDIT_FORM_LABEL' => ['ru' => Loc::getMessage('QUIZ_HIGHTLOAD_PROPERTY_1'), 'en' => ''],
                    'LIST_COLUMN_LABEL' => ['ru' => Loc::getMessage('QUIZ_HIGHTLOAD_PROPERTY_1'), 'en' => ''],
                    'LIST_FILTER_LABEL' => ['ru' => Loc::getMessage('QUIZ_HIGHTLOAD_PROPERTY_1'), 'en' => ''],
                    'ERROR_MESSAGE' => ['ru' => '', 'en' => ''],
                    'HELP_MESSAGE' => ['ru' => '', 'en' => '']
                ],
                'UF_QUESTION_VALUE' => [
                    'ENTITY_ID' => $HLObject,
                    'FIELD_NAME' => 'UF_QUESTION_VALUE',
                    'USER_TYPE_ID' => 'string',
                    'MANDATORY' => 'Y',
                    'EDIT_FORM_LABEL' => ['ru' => Loc::getMessage('QUIZ_HIGHTLOAD_PROPERTY_2'), 'en' => ''],
                    'LIST_COLUMN_LABEL' => ['ru' => Loc::getMessage('QUIZ_HIGHTLOAD_PROPERTY_2'), 'en' => ''],
                    'LIST_FILTER_LABEL' => ['ru' => Loc::getMessage('QUIZ_HIGHTLOAD_PROPERTY_2'), 'en' => ''],
                    'ERROR_MESSAGE' => ['ru' => '', 'en' => ''],
                    'HELP_MESSAGE' => ['ru' => '', 'en' => '']
                ],
                'UF_USER_ID' => [
                    'ENTITY_ID' => $HLObject,
                    'FIELD_NAME' => 'UF_USER_ID',
                    'USER_TYPE_ID' => 'integer',
                    'MANDATORY' => 'Y',
                    'EDIT_FORM_LABEL' => ['ru' => Loc::getMessage('QUIZ_HIGHTLOAD_PROPERTY_3'), 'en' => ''],
                    'LIST_COLUMN_LABEL' => ['ru' => Loc::getMessage('QUIZ_HIGHTLOAD_PROPERTY_3'), 'en' => ''],
                    'LIST_FILTER_LABEL' => ['ru' => Loc::getMessage('QUIZ_HIGHTLOAD_PROPERTY_3'), 'en' => ''],
                    'ERROR_MESSAGE' => ['ru' => '', 'en' => ''],
                    'HELP_MESSAGE' => ['ru' => '', 'en' => '']
                ],
                'UF_USER_EMAIL' => [
                    'ENTITY_ID' => $HLObject,
                    'FIELD_NAME' => 'UF_USER_EMAIL',
                    'USER_TYPE_ID' => 'string',
                    'MANDATORY' => 'Y',
                    'EDIT_FORM_LABEL' => ['ru' => Loc::getMessage('QUIZ_HIGHTLOAD_PROPERTY_5'), 'en' => ''],
                    'LIST_COLUMN_LABEL' => ['ru' => Loc::getMessage('QUIZ_HIGHTLOAD_PROPERTY_5'), 'en' => ''],
                    'LIST_FILTER_LABEL' => ['ru' => Loc::getMessage('QUIZ_HIGHTLOAD_PROPERTY_5'), 'en' => ''],
                    'ERROR_MESSAGE' => ['ru' => '', 'en' => ''],
                    'HELP_MESSAGE' => ['ru' => '', 'en' => '']
                ],
                'UF_ANSWER' => [
                    'ENTITY_ID' => $HLObject,
                    'FIELD_NAME' => 'UF_ANSWER',
                    'USER_TYPE_ID' => 'string',
                    'MANDATORY' => 'Y',
                    'EDIT_FORM_LABEL' => ['ru' => Loc::getMessage('QUIZ_HIGHTLOAD_PROPERTY_6'), 'en' => ''],
                    'LIST_COLUMN_LABEL' => ['ru' => Loc::getMessage('QUIZ_HIGHTLOAD_PROPERTY_6'), 'en' => ''],
                    'LIST_FILTER_LABEL' => ['ru' => Loc::getMessage('QUIZ_HIGHTLOAD_PROPERTY_6'), 'en' => ''],
                    'ERROR_MESSAGE' => ['ru' => '', 'en' => ''],
                    'HELP_MESSAGE' => ['ru' => '', 'en' => '']
                ],
                'UF_RESULT' => [
                    'ENTITY_ID' => $HLObject,
                    'FIELD_NAME' => 'UF_RESULT',
                    'USER_TYPE_ID' => 'integer',
                    'MANDATORY' => 'Y',
                    'EDIT_FORM_LABEL' => ['ru' => Loc::getMessage('QUIZ_HIGHTLOAD_PROPERTY_7'), 'en' => ''],
                    'LIST_COLUMN_LABEL' => ['ru' => Loc::getMessage('QUIZ_HIGHTLOAD_PROPERTY_7'), 'en' => ''],
                    'LIST_FILTER_LABEL' => ['ru' => Loc::getMessage('QUIZ_HIGHTLOAD_PROPERTY_7'), 'en' => ''],
                    'ERROR_MESSAGE' => ['ru' => '', 'en' => ''],
                    'HELP_MESSAGE' => ['ru' => '', 'en' => '']
                ],
                'UF_RESULT_DATE' => [
                    'ENTITY_ID' => $HLObject,
                    'FIELD_NAME' => 'UF_RESULT_DATE',
                    'USER_TYPE_ID' => 'datetime',
                    'MANDATORY' => 'Y',
                    'EDIT_FORM_LABEL' => ['ru' => Loc::getMessage('QUIZ_HIGHTLOAD_PROPERTY_8'), 'en' => ''],
                    'LIST_COLUMN_LABEL' => ['ru' => Loc::getMessage('QUIZ_HIGHTLOAD_PROPERTY_8'), 'en' => ''],
                    'LIST_FILTER_LABEL' => ['ru' => Loc::getMessage('QUIZ_HIGHTLOAD_PROPERTY_8'), 'en' => ''],
                    'ERROR_MESSAGE' => ['ru' => '', 'en' => ''],
                    'HELP_MESSAGE' => ['ru' => '', 'en' => '']
                ],
                'UF_PUSHED' => [
                    'ENTITY_ID' => $HLObject,
                    'FIELD_NAME' => 'UF_PUSHED',
                    'USER_TYPE_ID' => 'boolean',
                    'MANDATORY' => 'N',
                    'EDIT_FORM_LABEL' => ['ru' => Loc::getMessage('QUIZ_HIGHTLOAD_PROPERTY_9'), 'en' => ''],
                    'LIST_COLUMN_LABEL' => ['ru' => Loc::getMessage('QUIZ_HIGHTLOAD_PROPERTY_9'), 'en' => ''],
                    'LIST_FILTER_LABEL' => ['ru' => Loc::getMessage('QUIZ_HIGHTLOAD_PROPERTY_9'), 'en' => ''],
                    'ERROR_MESSAGE' => ['ru' => '', 'en' => ''],
                    'HELP_MESSAGE' => ['ru' => '', 'en' => '']
                ]
            ];
            foreach($hlBlockProperties as $hlBlockProperty){
                $obUserField  = new CUserTypeEntity;
                $obUserField->Add($hlBlockProperty);
            }
        }

        /* Установка компонента */
        $moduleComponentsPath = $DOCUMENT_ROOT . '/bitrix/modules/' . $this->MODULE_ID . '/install/components/';
        $adminComponentsPath = $DOCUMENT_ROOT . '/bitrix/components/';

        $this->recurseCopy( $moduleComponentsPath, $adminComponentsPath );

        /* Регистрируем модуль */
        RegisterModule($this->MODULE_ID);

        /* Добавляем свойства */
        $cipherKey = Bitrix\Main\Security\Random::GetString(32);
        $APPLICATION->throwException(\Bitrix\Main\Config\Option::set($this->MODULE_ID, "CIPHER_KEY", $cipherKey));

        /* Добавляем события */
        EventManager::getInstance()->registerEventHandler('main', 'OnBeforeUserRegister', $this->MODULE_ID, '\\Outcode\\Events', 'checkUserNickName');
        EventManager::getInstance()->registerEventHandler('main', 'OnBeforeUserUpdate', $this->MODULE_ID, '\\Outcode\\Events', 'checkUserNickName'); 
        EventManager::getInstance()->registerEventHandler('main', 'OnBeforeUserAdd', $this->MODULE_ID, '\\Outcode\\Events', 'checkUserNickName');

        $APPLICATION->IncludeAdminFile(\Bitrix\Main\Localization\Loc::getMessage("QUIZ_MODULE_INSTALL"), $DOCUMENT_ROOT."/bitrix/modules/".$this->MODULE_ID."/install/step.php");
        return true;
    }

    function DoUninstall()
    {
        if(!Loader::includeModule('iblock')) return false;
        if(!Loader::includeModule('highloadblock')) return false;

        global $APPLICATION, $DOCUMENT_ROOT;

        $iBlockType = 'outcode_quiz';
        $hlBlockName = 'OutcodeQuizResult';
        $filesToRemove = [
            0 => $DOCUMENT_ROOT . '/bitrix/components/' . $this->MODULE_ID . '/'
        ];

        /* Удаляем инфоблоки */
        $res = CIBlock::GetList([], [ 'CODE' => $iBlockType ]);
        if($arRes = $res->Fetch()) {
            global $DB;

            $DB->StartTransaction();
            if(!CIBlock::Delete($arRes['ID'])) {
                $DB->Rollback();
            } else {
                $DB->Commit();
            }

            $DB->StartTransaction();
            if(!CIBlockType::Delete($iBlockType)) {
                $DB->Rollback();
            } else {
                $DB->Commit();
            }
        }

        /* Удаляем hightload блоки */
        $arFilter = ['select' => ['ID'], 'filter' => ['=NAME' => $hlBlockName]];
        $hlBlock = HL\HighloadBlockTable::getList($arFilter)->fetch();
        if(is_array($hlBlock) && !empty($hlBlock))  {
            HL\HighloadBlockTable::delete($hlBlock['ID']);
        }

        /* Удаляем свойства */
        \Bitrix\Main\Config\Option::delete($this->MODULE_ID, ["name" => "CIPHER_KEY"]);

        /* Удаляем события */
        EventManager::getInstance()->unRegisterEventHandler('main', 'OnBeforeUserRegister', $this->MODULE_ID, '\\Outcode\\Events', 'checkUserNickName');
        EventManager::getInstance()->unRegisterEventHandler('main', 'OnBeforeUserUpdate', $this->MODULE_ID, '\\Outcode\\Events', 'checkUserNickName'); 
        EventManager::getInstance()->unRegisterEventHandler('main', 'OnBeforeUserAdd', $this->MODULE_ID, '\\Outcode\\Events', 'checkUserNickName');

        /* Отменяем регистрацию модуля */
        UnRegisterModule($this->MODULE_ID);

        /* Удаляем директории */
        foreach( $filesToRemove as $fileName ) {
            if( file_exists($fileName) && is_dir($fileName) ) {
                $this->removeDirectory($fileName);
            } else if( file_exists($fileName) ) {
                unlink($fileName);
            }
        }

        $APPLICATION->IncludeAdminFile(Loc::getMessage("QUIZ_MODULE_UNINSTALL"), $DOCUMENT_ROOT."/bitrix/modules/".$this->MODULE_ID."/install/unstep.php");
        return true;
    }
}
<?
//include($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/const.php');
use Bitrix\Main;
use Bitrix\Iblock;

class Migration
{  
    /** Инициализация */
    public function init()
    {
        require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
    }

    /** Установка миграции */
    public function up()
    {
        $this->includeModules();

        $iblockId = Migration::getIblock();
        if ($iblockId) {
            throw new Main\SystemException('IBLOCK videos already exists');
        } 

        $arPICTURE = $_FILES["PICTURE"];
        $ib = new CIBlock;
        $arFields = Array(
        "ACTIVE" => 'Y',
        "NAME" => 'Видео',
        "CODE" => 'videos',
        "LIST_PAGE_URL" => '#SITE_DIR#/videos/',
        "DETAIL_PAGE_URL" => '',
        "IBLOCK_TYPE_ID" => 'content',
        "SITE_ID" => 's1',
        "SORT" => 500,
        "PICTURE" => $arPICTURE,
        "DESCRIPTION" => '',
        "DESCRIPTION_TYPE" => 'html',
        "GROUP_ID" => Array("2"=>"D", "3"=>"R")
        );

        $iblockId = $ib->Add($arFields);   

        if (!$iblockId) {
            throw new Main\SystemException('Can not find IBLOCK videos');
        } 

        $prop1 = $this->getIblockProperty('LINK_VIDEO', $iblockId);
        if(empty($prop1)) {
            $res1 = $this->addIblockProperty($iblockId, array(
                'NAME' => 'Ссылка на видео',
                'ACTIVE' => 'Y',
                'SORT' => 500,
                'CODE' => 'LINK_VIDEO',
                'PROPERTY_TYPE' => 'S',
            ));
        }

        $prop2 = $this->getIblockProperty('PREVIEW_AVAILABLE_VIDEO', $iblockId);
        if(empty($prop2)) {
            $res2 = $this->addIblockProperty($iblockId, array(
                'NAME' => 'Превью для доступного видео',
                'ACTIVE' => 'Y',
                'SORT' => 500,
                'CODE' => 'PREVIEW_AVAILABLE_VIDEO',
                'PROPERTY_TYPE' => 'F',
            ));
        }

        $prop3 = $this->getIblockProperty('PREVIEW_CLOSED_VIDEO', $iblockId);
        if(empty($prop3)) {
            $res3 = $this->addIblockProperty($iblockId, array(
                'NAME' => 'Превью для закрытого видео',
                'ACTIVE' => 'Y',
                'SORT' => 500,
                'CODE' => 'PREVIEW_CLOSED_VIDEO',
                'PROPERTY_TYPE' => 'F',
            ));
        }

        $propertyArray = [
            'NAME' => 'Доступно для неавторизованных пользователей',
            'ACTIVE' => 'Y',
            'SORT' => 500,
            'CODE' => 'FORBIDDEN_VIDEO',
            'PROPERTY_TYPE' => 'L',
            'LIST_TYPE' => 'C',
            'MULTIPLE_CNT' => 1,
        ];

        $propertyArray['VALUES'][0] = [
            "XML_ID" => "Y",
            "VALUE" => "Y",
            "DEF" => "N",
            "SORT" => "100"
        ];

        $prop4 = $this->getIblockProperty('FORBIDDEN_VIDEO', $iblockId);
        if(empty($prop4)) {
            $res4 = $this->addIblockProperty($iblockId, $propertyArray);
        }

        $prop5 = $this->getIblockProperty('VIDEO_NUMBER', $iblockId);
        if(empty($prop5)) {
            $res5 = $this->addIblockProperty($iblockId, array(
                'NAME' => 'Номер',
                'ACTIVE' => 'Y',
                'SORT' => 500,
                'CODE' => 'VIDEO_NUMBER',
                'PROPERTY_TYPE' => 'S',
            ));
        }

        $section = $this->getIblockSection('VIDEO', $iblockId);
        if (!$section) {
            $section = $this->addIblockSection($iblockId);
        }

        if ($section) {
            $sectionProperty = $this->getIblockSectionsProperty('UF_DIRECTION_NAME', $iblockId);
            if (empty($sectionProperty)) { 
                $oUserTypeEntity = new CUserTypeEntity();
                $aUserFields = [
                    'ENTITY_ID' => 'IBLOCK_'.$iblockId.'_SECTION',
                    'FIELD_NAME' => 'UF_DIRECTION_NAME',
                    'USER_TYPE_ID' => 'string',
                    'XML_ID' => 'XML_DIRECTION_NAME',
                    'SORT' => 100,
                    'MULTIPLE' => 'N',
                    'MANDATORY' => 'Y',
                    'EDIT_FORM_LABEL' => array(
                        'ru' => 'Название для направления со всеми видео',
                    ),
                ];
    
                $iUserFieldId   = $oUserTypeEntity->Add( $aUserFields );
            }
        }
    }

    /** Откат миграции */
    public function down()
    {
        $this->includeModules();

        $iblockId = Migration::getIblock();
        if (!$iblockId) {
            throw new Main\SystemException('Can not find IBLOCK videos const');
        } 
        $prop1 = $this->getIblockProperty('PREVIEW_AVAILABLE_VIDEO', $iblockId);
        if(!empty($prop1)) {
            $this->removeIblockProperty($prop1['ID']);
        }
        $prop2 = $this->getIblockProperty('PREVIEW_AVAILABLE_VIDEO', $iblockId);
        if(!empty($prop2)) {
            $this->removeIblockProperty($prop2['ID']);
        }
        $prop3 = $this->getIblockProperty('PREVIEW_CLOSED_VIDEO', $iblockId);
        if(!empty($prop3)) {
            $this->removeIblockProperty($prop3['ID']);
        }
        $prop4 = $this->getIblockProperty('FORBIDDEN_VIDEO', $iblockId);
        if(!empty($prop4)) {
            $this->removeIblockProperty($prop4['ID']);
        }
        $prop5 = $this->getIblockProperty('VIDEO_NUMBER', $iblockId);
        if(!empty($prop5)) {
            $this->removeIblockProperty($prop5['ID']);
        }
        $section = $this->getIblockSection('VIDEO', $iblockId);
        if ($section) {
            $sectionProperty = $this->getIblockSectionsProperty('UF_DIRECTION_NAME', $iblockId);
            if (!empty($sectionProperty)) {
                $oUserTypeEntity = new CUserTypeEntity();
                $oUserTypeEntity->Delete( $prop1['ID'] );
            }
            $section = $this->deleteIblockSection($section);
        }

        if (!empty($iblockId)) {
            $this->removeIblock($iblockId);
        }
    }

    /**
     * @throws Main\LoaderException
     * @throws Main\SystemException
     */
    private function includeModules()
    {
        if(!Main\Loader::includeModule('iblock')) {
            throw new Main\SystemException('Can not load iblock module');
        }
    }

    /**
     * @return bool|int
     * @throws Main\SystemException
     */
    public function getIblock()
    {
        $id = $this->getIblockId('videos');
        if(!$id) {
            return false;
        }

        return $id;
    }
    
    /**
     * @param string $code
     * @param string $type
     * @return int|bool
     */
    private function getIblockId($code, $type = '')
    {
        $arFilter = array(
            '=CODE' => $code
        );
        if(!empty($type)) {
            $arFilter['=IBLOCK_TYPE_ID'] = $type;
        }

        $d = Iblock\IblockTable::getRow(array(
            'filter' => $arFilter,
            'select' => array('ID')
        ));

        if(!empty($d) && !empty($d['ID'])) {
            return $d['ID'];
        }

        return false;
    }
    
    /**
     * @param string $code
     * @param int $iblockId
     * @return array|null
     */
    private function getIblockProperty($code, $iblockId)
    {
        $arFilter = array(
            '=CODE' => $code,
            '=IBLOCK_ID' => $iblockId
        );

        return Iblock\PropertyTable::getRow(array(
            'filter' => $arFilter,
            'select' => array('*')
        ));
    }   
    
    /**
     * @param int $iblockId
     * @param array $data
     * @return bool
     * @throws Main\SystemException
     */
    private function addIblockProperty($iblockId, $data)
    {
        $data['IBLOCK_ID'] = $iblockId;
        $ibProp = new \CIBlockProperty;
        $id = $ibProp->Add($data);
        if(!$id) {
            throw new Main\SystemException(sprintf('Iblock property add error: %s',
                $ibProp->LAST_ERROR));
        }

        return $id;
    }
    
    /**
     * @param int $id
     * @return bool|CDBResult
     * @throws Main\SystemException
     */
    private function removeIblockProperty($id)
    {
        $res = \CIBlockProperty::Delete($id);
        if(!$res) {
            /** @global \CMain $APPLICATION */
            global $APPLICATION;
            $ex = $APPLICATION->GetException();

            throw new Main\SystemException(sprintf(
                'Iblock property #%u delete error: %s',
                $id,
                (
                    !empty($ex)
                    ? $ex->GetString()
                    : 'no error msg'
                )
            ));
        }

        return $res;
    }    

    private function getIblockSection($code, $iblockId) {
        $getListArray['filter'] = [
            '=CODE' => $code,
            '=IBLOCK_ID' => $iblockId
        ];

        $getListArray['select'] = ['ID', 'IBLOCK_ID', 'CODE'];
        $sections = CIBlockSection::GetList([], $getListArray['filter'], false, $getListArray['select'], false);
        if ($section = $sections->fetch()) {
            return $section['ID'];
        } else {
            return false;
        }
    }

    private function addIblockSection($iblockId) {
        $addSectionArray = [
            "IBLOCK_ID" => $iblockId,
            "CODE" => "VIDEO",
            "NAME" => "Онлайн-тренировки",
            "SORT" => 500,
            "ACTIVE" => "Y"
        ];

        $section = new \CIBlockSection;
        $sectionId = $section->Add($addSectionArray);
        if(!$sectionId) {
            throw new Main\SystemException(sprintf('Iblock section add error: %s',
                $section->LAST_ERROR));
        }

        return $sectionId;
    }

    private function deleteIblockSection($sectionId) {
        $res = \CIBlockSection::Delete($sectionId);

        if(!$res) {
            /** @global \CMain $APPLICATION */
            global $APPLICATION;
            $ex = $APPLICATION->GetException();

            throw new Main\SystemException(sprintf(
                'Iblock section #%u delete error: %s',
                $sectionId,
                (
                    !empty($ex)
                    ? $ex->GetString()
                    : 'no error msg'
                )
            ));
        }

        return $res;
    }

    /**
    * @param string $code
    * @param int $iblockId
    * @return array|null
    */
    private function getIblockSectionsProperty($code, $iblockId)
    {
        $arFilter = array(
            "ACTIVE" => "Y",
            "IBLOCK_ID" => $iblockId,
         );

        $rsSections = CIBlockSection::GetList(
            [],
            $arFilter,
            false,
            false
        );

        global $USER_FIELD_MANAGER;
        if($arSection = $rsSections->Fetch()) 
        {
            $aUserField = $USER_FIELD_MANAGER->GetUserFields(
                'IBLOCK_6_SECTION',
                $arSection['ID']
            );
            if ($aUserField[$code])
                {
                    return $aUserField[$code];
                }
            else {
                return false;
            }
        }
    }
    
    /**
     * remove Iblock
     *
     * @param int $iblockId
     * @throws Main\SystemException
     */
    private function removeIblock($iblockId) {
        $res = \CIBlock::Delete($iblockId);
        if(!$res) {
            throw new Main\SystemException('IBLOCK videos delete error');
        }

        return $res;
    }
}
?>
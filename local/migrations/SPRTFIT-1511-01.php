<?php
include($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/const.php');
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

            $id = Migration::getIblock();
            if(!$id) {
                throw new Main\SystemException('Can not find IBLOCK subscription const');
            }
            $prop1 = $this->getIblockProperty('PHOTO_GALLERY', $id);
            if(empty($prop1)) {
                $res1 = $this->addIblockProperty($id, array(
                    'NAME' => 'Фотогалерея',
                    'ACTIVE' => 'Y',
                    'SORT' => 500,
                    'CODE' => 'PHOTO_GALLERY',
                    'MULTIPLE' => 'Y',
                    'WITH_DESCRIPTION' => 'Y',
                    'PROPERTY_TYPE' => 'F',
                ));
            }
            $prop2 = $this->getIblockProperty('PREVIEW_VIDEO', $id);
            if(empty($prop2)) {
                $res2 = $this->addIblockProperty($id, array(
                    'NAME' => 'Превью для видео',
                    'ACTIVE' => 'Y',
                    'SORT' => 500,
                    'CODE' => 'PREVIEW_VIDEO',
                    'PROPERTY_TYPE' => 'F',
                ));
            }
            $prop3 = $this->getIblockProperty('LINK_VIDEO', $id);
            if(empty($prop3)) {
                $res3 = $this->addIblockProperty($id, array(
                    'NAME' => 'Ссылка на видео',
                    'ACTIVE' => 'Y',
                    'SORT' => 500,
                    'CODE' => 'LINK_VIDEO',
                    'PROPERTY_TYPE' => 'S',
                ));
            }
            $prop4 = $this->getIblockProperty('CORD_YANDEX', $id);
            if(empty($prop4)) {
                $res4 = $this->addIblockProperty($id, array(
                    'NAME' => 'Координаты',
                    'ACTIVE' => 'Y',
                    'SORT' => 500,
                    'CODE' => 'CORD_YANDEX',
                    'MULTIPLE' => 'Y',
                    'PROPERTY_TYPE' => 'S',
                    'USER_TYPE' => 'map_yandex'
                ));
            }
    }

    /** Откат миграции */
    public function down()
    {
        $this->includeModules();
        $iblockId = Migration::getIblock();
        $prop1 = $this->getIblockProperty('PHOTO_GALLERY', $iblockId);
        if(!empty($prop1)) {
            $this->removeIblockProperty($prop1['ID']);
        }
        $prop2 = $this->getIblockProperty('PREVIEW_VIDEO', $iblockId);
        if(!empty($prop2)) {
            $this->removeIblockProperty($prop2['ID']);
        }
        $prop3 = $this->getIblockProperty('LINK_VIDEO', $iblockId);
        if(!empty($prop3)) {
            $this->removeIblockProperty($prop3['ID']);
        }
        $prop4 = $this->getIblockProperty('CORD_YANDEX', $iblockId);
        if(!empty($prop4)) {
            $this->removeIblockProperty($prop4['ID']);
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
        $id = $this->getIblockId('subscription');
        if(!$id) {
            throw new Main\SystemException('Can not find subscription iblock');
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
}

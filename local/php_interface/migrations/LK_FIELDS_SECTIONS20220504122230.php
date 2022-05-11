<?php

namespace Sprint\Migration;


class LK_FIELDS_SECTIONS20220504122230 extends Version
{
    protected $description = "";

    protected $moduleVersion = "4.0.2";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();

        $iblockId = $helper->Iblock()->getIblockIdIfExists(
            'LK_FIELDS',
            'content'
        );

        $helper->Iblock()->addSectionsFromTree(
            $iblockId,
            array (
  0 => 
  array (
    'NAME' => 'Профиль',
    'CODE' => 'lk_profile',
    'SORT' => '500',
    'ACTIVE' => 'Y',
    'XML_ID' => NULL,
    'DESCRIPTION' => '',
    'DESCRIPTION_TYPE' => 'text',
    'UF_LK_SECTION_ICON' => '3675',
    'UF_ACTION' => 'UPDATE_USER',
    'UF_BTN_TEXT' => 'сохранить',
  ),
  1 => 
  array (
    'NAME' => 'Программа лояльности',
    'CODE' => 'lk_loyalty_program',
    'SORT' => '500',
    'ACTIVE' => 'Y',
    'XML_ID' => NULL,
    'DESCRIPTION' => '',
    'DESCRIPTION_TYPE' => 'text',
    'UF_LK_SECTION_ICON' => '3674',
    'UF_ACTION' => 'UPDATE_1C',
    'UF_BTN_TEXT' => 'обновить',
  ),
)        );
    }

    public function down()
    {
        //your code ...
    }
}

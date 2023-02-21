<?php

namespace Sprint\Migration;


class events20230222011913 extends Version
{
    protected $description = "";

    protected $moduleVersion = "4.1.1";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $helper->Iblock()->saveIblockType(array (
  'ID' => 'events',
  'SECTIONS' => 'Y',
  'EDIT_FILE_BEFORE' => '',
  'EDIT_FILE_AFTER' => '',
  'IN_RSS' => 'N',
  'SORT' => '500',
  'LANG' => 
  array (
    'ru' => 
    array (
      'NAME' => 'Мероприятия',
      'SECTION_NAME' => '',
      'ELEMENT_NAME' => '',
    ),
    'en' => 
    array (
      'NAME' => 'Events',
      'SECTION_NAME' => '',
      'ELEMENT_NAME' => '',
    ),
  ),
));
        $iblockId = $helper->Iblock()->saveIblock(array (
  'IBLOCK_TYPE_ID' => 'events',
  'LID' => 
  array (
    0 => 's1',
  ),
  'CODE' => 'event_page',
  'API_CODE' => NULL,
  'NAME' => 'Страница мероприятия',
  'ACTIVE' => 'Y',
  'SORT' => '100',
  'LIST_PAGE_URL' => '',
  'DETAIL_PAGE_URL' => '#SITE_DIR#/events/#SECTION_ID#/#ELEMENT_ID#/',
  'SECTION_PAGE_URL' => '#SITE_DIR#/events/#SECTION_ID#/',
  'CANONICAL_PAGE_URL' => '',
  'PICTURE' => NULL,
  'DESCRIPTION' => '',
  'DESCRIPTION_TYPE' => 'text',
  'RSS_TTL' => '24',
  'RSS_ACTIVE' => 'Y',
  'RSS_FILE_ACTIVE' => 'N',
  'RSS_FILE_LIMIT' => NULL,
  'RSS_FILE_DAYS' => NULL,
  'RSS_YANDEX_ACTIVE' => 'N',
  'XML_ID' => NULL,
  'INDEX_ELEMENT' => 'Y',
  'INDEX_SECTION' => 'Y',
  'WORKFLOW' => 'N',
  'BIZPROC' => 'N',
  'SECTION_CHOOSER' => 'L',
  'LIST_MODE' => '',
  'RIGHTS_MODE' => 'S',
  'SECTION_PROPERTY' => 'N',
  'PROPERTY_INDEX' => 'N',
  'VERSION' => '1',
  'LAST_CONV_ELEMENT' => '0',
  'SOCNET_GROUP_ID' => NULL,
  'EDIT_FILE_BEFORE' => '',
  'EDIT_FILE_AFTER' => '',
  'SECTIONS_NAME' => 'Разделы',
  'SECTION_NAME' => 'Раздел',
  'ELEMENTS_NAME' => 'Элементы',
  'ELEMENT_NAME' => 'Элемент',
  'REST_ON' => 'N',
  'EXTERNAL_ID' => NULL,
  'LANG_DIR' => '/',
  'SERVER_NAME' => '',
  'IPROPERTY_TEMPLATES' => 
  array (
  ),
  'ELEMENT_ADD' => 'Добавить элемент',
  'ELEMENT_EDIT' => 'Изменить элемент',
  'ELEMENT_DELETE' => 'Удалить элемент',
  'SECTION_ADD' => 'Добавить раздел',
  'SECTION_EDIT' => 'Изменить раздел',
  'SECTION_DELETE' => 'Удалить раздел',
));
        $helper->Iblock()->saveIblockFields($iblockId, array (
  'IBLOCK_SECTION' => 
  array (
    'NAME' => 'Привязка к разделам',
    'IS_REQUIRED' => 'N',
    'DEFAULT_VALUE' => 
    array (
      'KEEP_IBLOCK_SECTION_ID' => 'N',
    ),
  ),
  'ACTIVE' => 
  array (
    'NAME' => 'Активность',
    'IS_REQUIRED' => 'Y',
    'DEFAULT_VALUE' => 'Y',
  ),
  'ACTIVE_FROM' => 
  array (
    'NAME' => 'Начало активности',
    'IS_REQUIRED' => 'N',
    'DEFAULT_VALUE' => '',
  ),
  'ACTIVE_TO' => 
  array (
    'NAME' => 'Окончание активности',
    'IS_REQUIRED' => 'N',
    'DEFAULT_VALUE' => '',
  ),
  'SORT' => 
  array (
    'NAME' => 'Сортировка',
    'IS_REQUIRED' => 'N',
    'DEFAULT_VALUE' => '0',
  ),
  'NAME' => 
  array (
    'NAME' => 'Название',
    'IS_REQUIRED' => 'Y',
    'DEFAULT_VALUE' => '',
  ),
  'PREVIEW_PICTURE' => 
  array (
    'NAME' => 'Картинка для анонса',
    'IS_REQUIRED' => 'N',
    'DEFAULT_VALUE' => 
    array (
      'FROM_DETAIL' => 'N',
      'SCALE' => 'N',
      'WIDTH' => '',
      'HEIGHT' => '',
      'IGNORE_ERRORS' => 'N',
      'METHOD' => 'resample',
      'COMPRESSION' => 95,
      'DELETE_WITH_DETAIL' => 'N',
      'UPDATE_WITH_DETAIL' => 'N',
      'USE_WATERMARK_TEXT' => 'N',
      'WATERMARK_TEXT' => '',
      'WATERMARK_TEXT_FONT' => '',
      'WATERMARK_TEXT_COLOR' => '',
      'WATERMARK_TEXT_SIZE' => '',
      'WATERMARK_TEXT_POSITION' => 'tl',
      'USE_WATERMARK_FILE' => 'N',
      'WATERMARK_FILE' => '',
      'WATERMARK_FILE_ALPHA' => '',
      'WATERMARK_FILE_POSITION' => 'tl',
      'WATERMARK_FILE_ORDER' => NULL,
    ),
  ),
  'PREVIEW_TEXT_TYPE' => 
  array (
    'NAME' => 'Тип описания для анонса',
    'IS_REQUIRED' => 'Y',
    'DEFAULT_VALUE' => 'text',
  ),
  'PREVIEW_TEXT' => 
  array (
    'NAME' => 'Описание для анонса',
    'IS_REQUIRED' => 'N',
    'DEFAULT_VALUE' => '',
  ),
  'DETAIL_PICTURE' => 
  array (
    'NAME' => 'Детальная картинка',
    'IS_REQUIRED' => 'N',
    'DEFAULT_VALUE' => 
    array (
      'SCALE' => 'N',
      'WIDTH' => '',
      'HEIGHT' => '',
      'IGNORE_ERRORS' => 'N',
      'METHOD' => 'resample',
      'COMPRESSION' => 95,
      'USE_WATERMARK_TEXT' => 'N',
      'WATERMARK_TEXT' => '',
      'WATERMARK_TEXT_FONT' => '',
      'WATERMARK_TEXT_COLOR' => '',
      'WATERMARK_TEXT_SIZE' => '',
      'WATERMARK_TEXT_POSITION' => 'tl',
      'USE_WATERMARK_FILE' => 'N',
      'WATERMARK_FILE' => '',
      'WATERMARK_FILE_ALPHA' => '',
      'WATERMARK_FILE_POSITION' => 'tl',
      'WATERMARK_FILE_ORDER' => NULL,
    ),
  ),
  'DETAIL_TEXT_TYPE' => 
  array (
    'NAME' => 'Тип детального описания',
    'IS_REQUIRED' => 'Y',
    'DEFAULT_VALUE' => 'text',
  ),
  'DETAIL_TEXT' => 
  array (
    'NAME' => 'Детальное описание',
    'IS_REQUIRED' => 'N',
    'DEFAULT_VALUE' => '',
  ),
  'XML_ID' => 
  array (
    'NAME' => 'Внешний код',
    'IS_REQUIRED' => 'Y',
    'DEFAULT_VALUE' => '',
  ),
  'CODE' => 
  array (
    'NAME' => 'Символьный код',
    'IS_REQUIRED' => 'N',
    'DEFAULT_VALUE' => 
    array (
      'UNIQUE' => 'N',
      'TRANSLITERATION' => 'N',
      'TRANS_LEN' => 100,
      'TRANS_CASE' => 'L',
      'TRANS_SPACE' => '-',
      'TRANS_OTHER' => '-',
      'TRANS_EAT' => 'Y',
      'USE_GOOGLE' => 'N',
    ),
  ),
  'TAGS' => 
  array (
    'NAME' => 'Теги',
    'IS_REQUIRED' => 'N',
    'DEFAULT_VALUE' => '',
  ),
  'SECTION_NAME' => 
  array (
    'NAME' => 'Название',
    'IS_REQUIRED' => 'Y',
    'DEFAULT_VALUE' => '',
  ),
  'SECTION_PICTURE' => 
  array (
    'NAME' => 'Картинка для анонса',
    'IS_REQUIRED' => 'N',
    'DEFAULT_VALUE' => 
    array (
      'FROM_DETAIL' => 'N',
      'SCALE' => 'N',
      'WIDTH' => '',
      'HEIGHT' => '',
      'IGNORE_ERRORS' => 'N',
      'METHOD' => 'resample',
      'COMPRESSION' => 95,
      'DELETE_WITH_DETAIL' => 'N',
      'UPDATE_WITH_DETAIL' => 'N',
      'USE_WATERMARK_TEXT' => 'N',
      'WATERMARK_TEXT' => '',
      'WATERMARK_TEXT_FONT' => '',
      'WATERMARK_TEXT_COLOR' => '',
      'WATERMARK_TEXT_SIZE' => '',
      'WATERMARK_TEXT_POSITION' => 'tl',
      'USE_WATERMARK_FILE' => 'N',
      'WATERMARK_FILE' => '',
      'WATERMARK_FILE_ALPHA' => '',
      'WATERMARK_FILE_POSITION' => 'tl',
      'WATERMARK_FILE_ORDER' => NULL,
    ),
  ),
  'SECTION_DESCRIPTION_TYPE' => 
  array (
    'NAME' => 'Тип описания',
    'IS_REQUIRED' => 'Y',
    'DEFAULT_VALUE' => 'text',
  ),
  'SECTION_DESCRIPTION' => 
  array (
    'NAME' => 'Описание',
    'IS_REQUIRED' => 'N',
    'DEFAULT_VALUE' => '',
  ),
  'SECTION_DETAIL_PICTURE' => 
  array (
    'NAME' => 'Детальная картинка',
    'IS_REQUIRED' => 'N',
    'DEFAULT_VALUE' => 
    array (
      'SCALE' => 'N',
      'WIDTH' => '',
      'HEIGHT' => '',
      'IGNORE_ERRORS' => 'N',
      'METHOD' => 'resample',
      'COMPRESSION' => 95,
      'USE_WATERMARK_TEXT' => 'N',
      'WATERMARK_TEXT' => '',
      'WATERMARK_TEXT_FONT' => '',
      'WATERMARK_TEXT_COLOR' => '',
      'WATERMARK_TEXT_SIZE' => '',
      'WATERMARK_TEXT_POSITION' => 'tl',
      'USE_WATERMARK_FILE' => 'N',
      'WATERMARK_FILE' => '',
      'WATERMARK_FILE_ALPHA' => '',
      'WATERMARK_FILE_POSITION' => 'tl',
      'WATERMARK_FILE_ORDER' => NULL,
    ),
  ),
  'SECTION_XML_ID' => 
  array (
    'NAME' => 'Внешний код',
    'IS_REQUIRED' => 'N',
    'DEFAULT_VALUE' => '',
  ),
  'SECTION_CODE' => 
  array (
    'NAME' => 'Символьный код',
    'IS_REQUIRED' => 'N',
    'DEFAULT_VALUE' => 
    array (
      'UNIQUE' => 'N',
      'TRANSLITERATION' => 'N',
      'TRANS_LEN' => 100,
      'TRANS_CASE' => 'L',
      'TRANS_SPACE' => '-',
      'TRANS_OTHER' => '-',
      'TRANS_EAT' => 'Y',
      'USE_GOOGLE' => 'N',
    ),
  ),
));
    $helper->Iblock()->saveGroupPermissions($iblockId, array (
  'administrators' => 'X',
  'everyone' => 'R',
));
        $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Баннер (header)',
  'ACTIVE' => 'Y',
  'SORT' => '100',
  'CODE' => 'BANNER_HEAD',
  'DEFAULT_VALUE' => '',
  'PROPERTY_TYPE' => 'F',
  'ROW_COUNT' => '1',
  'COL_COUNT' => '30',
  'LIST_TYPE' => 'L',
  'MULTIPLE' => 'N',
  'XML_ID' => NULL,
  'FILE_TYPE' => 'jpg, gif, bmp, png, jpeg, webp',
  'MULTIPLE_CNT' => '5',
  'LINK_IBLOCK_ID' => '0',
  'WITH_DESCRIPTION' => 'N',
  'SEARCHABLE' => 'N',
  'FILTRABLE' => 'N',
  'IS_REQUIRED' => 'N',
  'VERSION' => '1',
  'USER_TYPE' => NULL,
  'USER_TYPE_SETTINGS' => NULL,
  'HINT' => '',
));
            $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Баннер мобильный (header)',
  'ACTIVE' => 'Y',
  'SORT' => '150',
  'CODE' => 'BANNER_MOBILE_HEAD',
  'DEFAULT_VALUE' => '',
  'PROPERTY_TYPE' => 'F',
  'ROW_COUNT' => '1',
  'COL_COUNT' => '30',
  'LIST_TYPE' => 'L',
  'MULTIPLE' => 'N',
  'XML_ID' => NULL,
  'FILE_TYPE' => 'jpg, gif, bmp, png, jpeg, webp',
  'MULTIPLE_CNT' => '5',
  'LINK_IBLOCK_ID' => '0',
  'WITH_DESCRIPTION' => 'N',
  'SEARCHABLE' => 'N',
  'FILTRABLE' => 'N',
  'IS_REQUIRED' => 'N',
  'VERSION' => '1',
  'USER_TYPE' => NULL,
  'USER_TYPE_SETTINGS' => NULL,
  'HINT' => '',
));
            $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Слайдер (вместо баннера)',
  'ACTIVE' => 'Y',
  'SORT' => '160',
  'CODE' => 'SLIDER',
  'DEFAULT_VALUE' => '',
  'PROPERTY_TYPE' => 'E',
  'ROW_COUNT' => '1',
  'COL_COUNT' => '30',
  'LIST_TYPE' => 'L',
  'MULTIPLE' => 'N',
  'XML_ID' => NULL,
  'FILE_TYPE' => '',
  'MULTIPLE_CNT' => '5',
  'LINK_IBLOCK_ID' => 'service:sliders',
  'WITH_DESCRIPTION' => 'N',
  'SEARCHABLE' => 'N',
  'FILTRABLE' => 'N',
  'IS_REQUIRED' => 'N',
  'VERSION' => '1',
  'USER_TYPE' => NULL,
  'USER_TYPE_SETTINGS' => NULL,
  'HINT' => '',
));
            $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Заголовок страницы',
  'ACTIVE' => 'Y',
  'SORT' => '200',
  'CODE' => 'PAGE_TITLE',
  'DEFAULT_VALUE' => '',
  'PROPERTY_TYPE' => 'S',
  'ROW_COUNT' => '1',
  'COL_COUNT' => '30',
  'LIST_TYPE' => 'L',
  'MULTIPLE' => 'N',
  'XML_ID' => NULL,
  'FILE_TYPE' => '',
  'MULTIPLE_CNT' => '5',
  'LINK_IBLOCK_ID' => '0',
  'WITH_DESCRIPTION' => 'N',
  'SEARCHABLE' => 'N',
  'FILTRABLE' => 'N',
  'IS_REQUIRED' => 'N',
  'VERSION' => '1',
  'USER_TYPE' => NULL,
  'USER_TYPE_SETTINGS' => NULL,
  'HINT' => '',
));
            $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Заголовок для карточек',
  'ACTIVE' => 'Y',
  'SORT' => '300',
  'CODE' => 'CARDS_TITLE',
  'DEFAULT_VALUE' => '',
  'PROPERTY_TYPE' => 'S',
  'ROW_COUNT' => '1',
  'COL_COUNT' => '30',
  'LIST_TYPE' => 'L',
  'MULTIPLE' => 'N',
  'XML_ID' => NULL,
  'FILE_TYPE' => '',
  'MULTIPLE_CNT' => '5',
  'LINK_IBLOCK_ID' => '0',
  'WITH_DESCRIPTION' => 'N',
  'SEARCHABLE' => 'N',
  'FILTRABLE' => 'N',
  'IS_REQUIRED' => 'N',
  'VERSION' => '1',
  'USER_TYPE' => NULL,
  'USER_TYPE_SETTINGS' => NULL,
  'HINT' => '',
));
            $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Карточки',
  'ACTIVE' => 'Y',
  'SORT' => '500',
  'CODE' => 'CARDS',
  'DEFAULT_VALUE' => '',
  'PROPERTY_TYPE' => 'E',
  'ROW_COUNT' => '1',
  'COL_COUNT' => '30',
  'LIST_TYPE' => 'L',
  'MULTIPLE' => 'Y',
  'XML_ID' => NULL,
  'FILE_TYPE' => '',
  'MULTIPLE_CNT' => '5',
  'LINK_IBLOCK_ID' => 'events:event_cards',
  'WITH_DESCRIPTION' => 'N',
  'SEARCHABLE' => 'N',
  'FILTRABLE' => 'N',
  'IS_REQUIRED' => 'N',
  'VERSION' => '1',
  'USER_TYPE' => NULL,
  'USER_TYPE_SETTINGS' => NULL,
  'HINT' => '',
));
            $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Баннер (footer)',
  'ACTIVE' => 'Y',
  'SORT' => '500',
  'CODE' => 'BANNER_FOOTER',
  'DEFAULT_VALUE' => '',
  'PROPERTY_TYPE' => 'F',
  'ROW_COUNT' => '1',
  'COL_COUNT' => '30',
  'LIST_TYPE' => 'L',
  'MULTIPLE' => 'N',
  'XML_ID' => NULL,
  'FILE_TYPE' => 'jpg, gif, bmp, png, jpeg, webp',
  'MULTIPLE_CNT' => '5',
  'LINK_IBLOCK_ID' => '0',
  'WITH_DESCRIPTION' => 'N',
  'SEARCHABLE' => 'N',
  'FILTRABLE' => 'N',
  'IS_REQUIRED' => 'N',
  'VERSION' => '1',
  'USER_TYPE' => NULL,
  'USER_TYPE_SETTINGS' => NULL,
  'HINT' => '',
));
            $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Баннер мобильный (footer)',
  'ACTIVE' => 'Y',
  'SORT' => '550',
  'CODE' => 'BANNER_MOBILE_FOOTER',
  'DEFAULT_VALUE' => '',
  'PROPERTY_TYPE' => 'F',
  'ROW_COUNT' => '1',
  'COL_COUNT' => '30',
  'LIST_TYPE' => 'L',
  'MULTIPLE' => 'N',
  'XML_ID' => NULL,
  'FILE_TYPE' => '',
  'MULTIPLE_CNT' => '5',
  'LINK_IBLOCK_ID' => '0',
  'WITH_DESCRIPTION' => 'N',
  'SEARCHABLE' => 'N',
  'FILTRABLE' => 'N',
  'IS_REQUIRED' => 'N',
  'VERSION' => '1',
  'USER_TYPE' => NULL,
  'USER_TYPE_SETTINGS' => NULL,
  'HINT' => '',
));
            $helper->UserOptions()->saveElementForm($iblockId, array (
  'Элемент|edit1' => 
  array (
    'ID' => 'ID',
    'DATE_CREATE' => 'Создан',
    'TIMESTAMP_X' => 'Изменен',
    'ACTIVE' => 'Активность',
    'ACTIVE_FROM' => 'Начало активности',
    'ACTIVE_TO' => 'Окончание активности',
    'NAME' => 'Название',
    'CODE' => 'Символьный код',
    'SORT' => 'Сортировка',
    'IBLOCK_ELEMENT_PROP_VALUE' => 'Значения свойств',
    'PROPERTY_BANNER_HEAD' => 'Баннер (header)',
    'PROPERTY_BANNER_MOBILE_HEAD' => 'Баннер мобильный (header)',
    'PROPERTY_SLIDER' => 'Слайдер (вместо баннера)',
    'PROPERTY_PAGE_TITLE' => 'Заголовок страницы',
    'PROPERTY_CARDS_TITLE' => 'Заголовок для карточек',
    'PROPERTY_CARDS' => 'Карточки',
    'PROPERTY_BANNER_FOOTER' => 'Баннер (footer)',
    'PROPERTY_BANNER_MOBILE_FOOTER' => 'Баннер мобильный (footer)',
  ),
  'SEO|edit14' => 
  array (
    'IPROPERTY_TEMPLATES_ELEMENT_META_TITLE' => 'Шаблон META TITLE',
    'IPROPERTY_TEMPLATES_ELEMENT_META_KEYWORDS' => 'Шаблон META KEYWORDS',
    'IPROPERTY_TEMPLATES_ELEMENT_META_DESCRIPTION' => 'Шаблон META DESCRIPTION',
    'IPROPERTY_TEMPLATES_ELEMENT_PAGE_TITLE' => 'Заголовок элемента',
    'IPROPERTY_TEMPLATES_ELEMENTS_PREVIEW_PICTURE' => 'Настройки для картинок анонса элементов',
    'IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_ALT' => 'Шаблон ALT',
    'IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_TITLE' => 'Шаблон TITLE',
    'IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_NAME' => 'Шаблон имени файла',
    'IPROPERTY_TEMPLATES_ELEMENTS_DETAIL_PICTURE' => 'Настройки для детальных картинок элементов',
    'IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_ALT' => 'Шаблон ALT',
    'IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_TITLE' => 'Шаблон TITLE',
    'IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_NAME' => 'Шаблон имени файла',
    'SEO_ADDITIONAL' => 'Дополнительно',
    'TAGS' => 'Теги',
  ),
  'Разделы|edit2' => 
  array (
    'SECTIONS' => 'Разделы',
  ),
));
    $helper->UserOptions()->saveElementGrid($iblockId, array (
  'views' => 
  array (
    'default' => 
    array (
      'columns' => 
      array (
        0 => '',
      ),
      'columns_sizes' => 
      array (
        'expand' => 1,
        'columns' => 
        array (
        ),
      ),
      'sticked_columns' => 
      array (
      ),
    ),
  ),
  'filters' => 
  array (
  ),
  'current_view' => 'default',
));
    $helper->UserOptions()->saveSectionGrid($iblockId, array (
  'views' => 
  array (
    'default' => 
    array (
      'columns' => 
      array (
        0 => '',
      ),
      'columns_sizes' => 
      array (
        'expand' => 1,
        'columns' => 
        array (
        ),
      ),
      'sticked_columns' => 
      array (
      ),
    ),
  ),
  'filters' => 
  array (
  ),
  'current_view' => 'default',
));

    }

    public function down()
    {
        //your code ...
    }
}

<?php
$arUrlRewrite=array (
  12 => 
  array (
    'CONDITION' => '#^/landings/v1/([\\.\\-0-9a-zA-Z]+)/.*$#',
    'RULE' => 'ELEMENT_CODE=$1',
    'ID' => '',
    'PATH' => '/landings/v1/index.php',
    'SORT' => 100,
  ),
  13 => 
  array (
    'CONDITION' => '#^/landings/v2/([\\.\\-0-9a-zA-Z]+)/.*$#',
    'RULE' => 'ELEMENT_CODE=$1',
    'ID' => '',
    'PATH' => '/landings/v2/index.php',
    'SORT' => 100,
  ),
  4 => 
  array (
    'CONDITION' => '#^/abonement/([\\.\\-0-9a-zA-Z]+)/.*$#',
    'RULE' => 'ELEMENT_CODE=$1',
    'ID' => '',
    'PATH' => '/abonement/detail.new.php',
    'SORT' => 100,
  ),
  1 => 
  array (
    'CONDITION' => '#^/catalog/([\\.\\-0-9a-zA-Z]+)/.*$#',
    'RULE' => 'ELEMENT_CODE=$1',
    'ID' => '',
    'PATH' => '/catalog/detail.php',
    'SORT' => 100,
  ),
  8 => 
  array (
    'CONDITION' => '#^/events/([\\.\\-_0-9a-zA-Z]+)/.*$#',
    'RULE' => 'ELEMENT_CODE=$1',
    'ID' => '',
    'PATH' => '/events/detail.php',
    'SORT' => 100,
  ),
  3 => 
  array (
    'CONDITION' => '#^/clubs/([\\.\\-_0-9a-zA-Z]+)/.*$#',
    'RULE' => 'ELEMENT_CODE=$1',
    'ID' => '',
    'PATH' => '/clubs/detail.php',
    'SORT' => 100,
  ),
  2 => 
  array (
    'CONDITION' => '#^/stock/([\\.\\-0-9a-zA-Z]+)/.*$#',
    'RULE' => 'ELEMENT_CODE=$1',
    'ID' => '',
    'PATH' => '/stock/detail.php',
    'SORT' => 100,
  ),
  11 => 
  array (
    'CONDITION' => '#^/form/([\\.\\-_0-9a-zA-Z]+)/.*$#',
    'RULE' => 'ELEMENT_CODE=$1',
    'ID' => '',
    'PATH' => '/form/detail.php',
    'SORT' => 100,
  ),
  5 => 
  array (
    'CONDITION' => '#^/faq/([\\.\\-0-9a-zA-Z]+)/.*$#',
    'RULE' => 'SECTION_CODE=$1',
    'ID' => '',
    'PATH' => '/faq/detail.php',
    'SORT' => 100,
  ),
  7 => 
  array (
    'CONDITION' => '#^/thank-you/#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/thank-you.php',
    'SORT' => 100,
  ),
  0 => 
  array (
    'CONDITION' => '#^/rest/#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/bitrix/services/rest/index.php',
    'SORT' => 100,
  ),
  6 => 
  array (
    'CONDITION' => '#^/blog/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/blog/index.php',
    'SORT' => 100,
  ),
  9 => 
  array (
    'CONDITION' => '#^/hook/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.hook',
    'PATH' => '/hook/index.php',
    'SORT' => 100,
  ),
    14=>array (
        'CONDITION' => '#^/quiz/#',
        'RULE' => '',
        'ID' => 'bitrix:news',
        'PATH' => '/quiz/index.php',
        'SORT' => 100,
    ),
    15=>array(
        'CONDITION' => '#^/play/#',
        'RULE' => 'ELEMENT_CODE=play',
        'ID' => '',
        'PATH' => '/landings/v1/index.php',
        'SORT' => 100,
    ),
    16 =>
      array (
          'CONDITION' => '#^/interview/([\\.\\-0-9a-zA-Z]+)/.*$#',
          'RULE' => 'SECTION_ID=$1',
          'ID' => '',
          'PATH' => '/interview/detail.php',
          'SORT' => 100,
      ),
    17 =>
        array (
            'CONDITION' => '#^/utp/([\\.\\-0-9a-zA-Z]+)/.*$#',
            'RULE' => 'ELEMENT_CODE=$1',
            'ID' => '',
            'PATH' => '/utp/detail.php',
            'SORT' => 100,
        ),
);

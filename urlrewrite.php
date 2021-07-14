<?php
$arUrlRewrite=array (
  4 => 
  array (
    'CONDITION' => '#^/abonement/([\\.\\-0-9a-zA-Z]+)/.*$#',
    'RULE' => 'ELEMENT_CODE=$1',
    'ID' => '',
    'PATH' => '/abonement/detail.php',
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
  2 => 
  array (
    'CONDITION' => '#^/stock/([\\.\\-0-9a-zA-Z]+)/.*$#',
    'RULE' => 'ELEMENT_CODE=$1',
    'ID' => '',
    'PATH' => '/stock/detail.php',
    'SORT' => 100,
  ),
  3 => 
  array (
    'CONDITION' => '#^/clubs/([\\.\\-0-9a-zA-Z]+)/.*$#',
    'RULE' => 'ELEMENT_CODE=$1',
    'ID' => '',
    'PATH' => '/clubs/detail.php',
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
  0 => 
  array (
    'CONDITION' => '#^/rest/#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/bitrix/services/rest/index.php',
    'SORT' => 100,
  ),
);

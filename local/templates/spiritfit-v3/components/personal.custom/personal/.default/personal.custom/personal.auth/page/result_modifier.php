<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

<?php
$url = $_SERVER['REQUEST_URI'];
$parts = parse_url($url);

parse_str($parts['query'], $query);

if (isset($query['reg'])){
    $arResult["ACTIVE_BTN"]='reg';
    $arResult["FORMS"]["AUTH"]["REG"]=true;

}
elseif (isset($query['forgot'])){
    $arResult["ACTIVE_BTN"]='forgot';
    $arResult["FORMS"]["AUTH"]["FORGOT"]=true;

}
else{
    $arResult["ACTIVE_BTN"]='auth';
    $arResult["FORMS"]["AUTH"]["ACTIVE"]=true;
}
?>
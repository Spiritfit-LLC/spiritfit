<?
	require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
	
	$api = new Api(array(
        "action" => "request_sendcode",
        "params" => array(
            "phone" => $phone = preg_replace('![^0-9]+!', '', $_REQUEST['form_text_8'])
        )
    ));
    
	$APPLICATION->IncludeComponent("bitrix:form.result.new","",Array(
        "SEF_MODE" => "Y", 
        "WEB_FORM_ID" => $_REQUEST["WEB_FORM_ID"], 
        "LIST_URL" => "result_list.php", 
        "EDIT_URL" => "result_edit.php", 
        "SUCCESS_URL" => "", 
        "CHAIN_ITEM_TEXT" => "", 
        "CHAIN_ITEM_LINK" => "", 
        "IGNORE_CUSTOM_TEMPLATE" => "Y", 
        "USE_EXTENDED_ERRORS" => "Y", 
        "CACHE_TYPE" => "A", 
        "CACHE_TIME" => "3600", 
        "SEF_FOLDER" => "/", 
        "VARIABLE_ALIASES" => Array(
        )
    ));
	
	require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');

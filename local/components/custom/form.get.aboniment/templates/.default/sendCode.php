<?
	require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
	
	$arrResult = ["SUCCESS" => true];
	
	if( !empty($_POST["phone"]) ) {
		$phone = preg_replace('![^0-9]+!', '', $_POST["phone"]);
		$api = new Api(array(
			"action" => "request_sendcode",
			"params" => array(
				"phone" => $phone,
			)
		));
		
		$result = $api->result();
		if(!$result["success"]) {
            $arrResult["MESSAGE"] = $result['data']['result']['userMessage'];
			$arrResult["SUCCESS"] = false;
        }
	} else {
		$arrResult["MESSAGE"] = "Value is empty!";
		$arrResult["SUCCESS"] = false;
	}
	
	echo json_encode($arrResult);
	
	require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/epilog_after.php');
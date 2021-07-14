<?
if(!empty($_POST['clientid'])){
	$clientID = explode('.', $_POST['clientid']);
	$clientID = $clientID[count($clientID)-2].'.'.$clientID[count($clientID)-1];
	
	$data = array(
		'cid' => $clientID,
		'source' => $_REQUEST['src'],
		'channel' => $_REQUEST['mdm'],
		'campania' => $_REQUEST['cnt'],
		'message' => $_REQUEST['cmp'],
		'kword' => $_REQUEST['trm'],
	);

	if($_POST['type'] == 'visit'){
		$options = array( 
			CURLOPT_POST => 0, 
			CURLOPT_HEADER => 0, 
			CURLOPT_URL => 'https://app.spiritfit.ru/Fitness/hs/website/visit', 
			CURLOPT_PORT => 443,
			CURLOPT_RETURNTRANSFER => 1, 
			CURLOPT_TIMEOUT => 10, 
			CURLOPT_POSTFIELDS => json_encode($data)
		); 

		$ch = curl_init();

		curl_setopt_array($ch, $options);
		$result = curl_exec($ch);

		curl_close($ch);
	}
}
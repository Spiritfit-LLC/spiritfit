<?php
class Api
{
    private $_result    = false;
    private $_data      = array();
    private $_errors    = array();
	
	/*
	* API url
	*/
    private $apiUrl		= "https://app.spiritfit.ru/Fitness/hs/website/";
    private $apiUrlTest = "https://app.spiritfit.ru/testv11/hs/website/";

    public function __construct($post)
    {
        if(empty($post['action'])){
            return;
        }

        if(isset($post['params']['name'])){
            $formParam = [
                'NAME' => $post['params']['name'],
                'EMAIL' => $post['params']['email'],
                'PHONE' => $post['params']['phone'],
            ];
            $this->sendCalltouch($formParam);
        }

        switch($post['action']){
            case 'contactReadymag':
                $this->contactReadymag($post['params']);
            case 'request':
                $this->_request($post['params']);
                AddMessage2Log('request');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
            case 'request2':
                $this->_request2($post['params']);
                AddMessage2Log('request2');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
			case 'check_code':
                $this->check_code($post['params']);
                AddMessage2Log('check_code');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
			case 'request_sendcode':
                $this->request_sendcode($post['params']);
                AddMessage2Log('request_sendcode');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
			case 'response':
                $this->response($post['params']);
                AddMessage2Log('response');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
            /*				
			case 'payment':
                $this->payment($post['params']);
                break;
            */
			case 'crypto':
                $this->crypto($post['params']);
                AddMessage2Log('crypto');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
			case '3ds':
                $this->crypto3d($post);
                AddMessage2Log('3ds');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
			case 'cost':
                $this->getCost();
                AddMessage2Log('cost');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
			case 'coupon':
                $this->coupon($post['params']);
                AddMessage2Log('coupon');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
            case 'request_authorization_code':
                $this->request_authorization_code($post['params']);
                AddMessage2Log('request_authorization_code');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
            case 'web_site_contact':
                $this->web_site_contact($post['params']);
                AddMessage2Log('web_site_contact');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
			case 'web_site_resume':
                $this->web_site_resume($post['params']);
                AddMessage2Log('web_site_resume');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
			case 'web_site_inteview':
                $this->web_site_inteview($post['params']);
                AddMessage2Log('web_site_inteview');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
        }
    }

    public function result()
    {
        $ret = array(
            'success'   => $this->_result,
            'data'      => $this->_data,
            'error'     => $this->_errors ? implode(', ', $this->_errors) : false
        );

        // return json_encode($ret);
        return $ret;
	}
	
	private function check_code($params) {
        $phone  = !empty($params['phone']) ? $params['phone'] : false;
		$code   = !empty($params['code']) ? $params['code'] : false;
		
		$this->_send($this->apiUrl."code", array(
			"phone" => substr($phone, 1),
			"code" => $code,
		));

		if ($this->_data['result']['errorCode'] == 0) {
			$this->_result = true;
		} else {
			$this->_result = false;
        }
	}
	
	/**
     * does request to website/contact
     *
     * @param  array $params
     */
    private function web_site_resume($params) {
        
		$name = !empty($params['name']) ? $params['name'] : false;
		$surname = !empty($params['surname']) ? $params['surname'] : false;
		$phone = !empty($params['phone']) ? $params['phone'] : false;
		$email = !empty($params['email']) ? $params['email'] : false;
		$position = !empty($params['position']) ? $params['position'] : false;
		$salary = !empty($params['salary']) ? $params['salary'] : false; 
		$metro= !empty($params['metro']) ? $params['metro'] : false;
        $client_id   = !empty($params['client_id']) ? $params['client_id'] : false;
        $code   = !empty($params['code']) ? $params['code'] : false;

        if(strpos($client_id, '.')){
            $client_id = explode('.', $client_id);
            $client_id = $client_id[count($client_id)-2].'.'.$client_id[count($client_id)-1];
        }

        if(empty($name) || empty($surname) || empty($phone) || empty($email)){
            return false;
        }
       
        $type = $params["type"] ? $params["type"] : 0;
        
		$trafic = $GLOBALS['arTraficAnswer'][$_REQUEST["WEB_FORM_ID"]];
		$arParams = array(
			"type" => $type,
			"name" => $name . " " . $surname,
            "phone" => substr($phone, 1),
			"email" => $email,
			"position" => $position,
			"salary" => $salary,
			"metro" => $metro,
            "cid" => $client_id,
			'source' => $_REQUEST[$trafic['src']],
        	'channel' => $_REQUEST[$trafic['mdm']],
        	'campania' => $_REQUEST[$trafic['cnt']],
        	'message' => $_REQUEST[$trafic['cmp']],
        	'kword' => $_REQUEST[$trafic['trm']],
		);
		
		$additionFields = $GLOBALS['arAdditionAnswer'][$_REQUEST["WEB_FORM_ID"]];
        
        $this->_send($this->apiUrl."resume", $arParams);
        
        if ($this->_data['result']['errorCode'] == 0)
            $this->_result = true;
    }
	
	/**
     * does request to website/contact
     *
     * @param  array $params
     */
    private function web_site_inteview($params) {
        
		$phone = !empty($params['phone']) ? $params['phone'] : false;
        $client_id = !empty($params['client_id']) ? $params['client_id'] : false;
		
        if(strpos($client_id, '.')){
            $client_id = explode('.', $client_id);
            $client_id = $client_id[count($client_id)-2].'.'.$client_id[count($client_id)-1];
        }

        if( empty($phone) ){
            return false;
        }
       
        $type = $params["type"] ? $params["type"] : 0;
        
		$trafic = $GLOBALS['arTraficAnswer'][$_REQUEST["WEB_FORM_ID"]];
		$params["type"] = $type;
		$params["cid"] = $client_id;
		$params['type'] = 'inteview';
		
		$trafic = $GLOBALS['arTraficAnswer'][$_REQUEST["WEB_FORM_ID"]];
		$params['source'] = $_REQUEST[$trafic['src']];
		$params['channel'] = $_REQUEST[$trafic['mdm']];
		$params['campania'] = $_REQUEST[$trafic['cnt']];
		$params['message'] = $_REQUEST[$trafic['cmp']];
		$params['kword'] = $_REQUEST[$trafic['trm']];
		if( !empty($_REQUEST[$trafic['ClientId']]) ) {
			$params['cid'] = $_REQUEST[$trafic['ClientId']];
		}
        
        $this->_send($this->apiUrl."inteview", $params);
        
        if ($this->_data['result']['errorCode'] == 0)
            $this->_result = true;
    }

    /**
     * @param $params
     */
    private function _request($params)
    {
        /*Костыль для работы клуба с одинаковым ID*/
        if( !empty($params['club']) && intval($params['club']) == 9999 ) {
            $params['club'] = "11";
        }
        /*Костыль для работы клуба с одинаковым ID*/

        $name   = !empty($params['name']) ? $params['name'] : false;
		$surname   = !empty($params['surname']) ? $params['surname'] : false;
        $email  = !empty($params['email']) ? $params['email'] : false;
        $phone  = !empty($params['phone']) ? $params['phone'] : false;
        $club   = !empty($params['club']) ? $params['club'] : false;
        $code   = !empty($params['code']) ? $params['code'] : false;
		$client_id   = !empty($params['client_id']) ? $params['client_id'] : false;
		$subscriptionId   = !empty($params['subscriptionId']) ? $params['subscriptionId'] : false;

        if(strpos($client_id, '.')){
            $client_id = explode('.', $client_id);
            $client_id = $client_id[count($client_id)-2].'.'.$client_id[count($client_id)-1];
        }     

		$this->_send($this->apiUrl."code", array(
			"phone" => substr($phone, 1),
			"code" => $code,
		));
		
		$smsResultArray = $this->result();
		
		//if ($this->_data['result']->result->errorCode == 0) {
		if( empty($smsResultArray['data']['result']['errorCode']) ) {
			if(empty($name) || empty($surname) || empty($email) || empty($phone)){
				return false;
			}
			$request = array(
				"name" => $name,
				"surname" => $surname,
				"email" => $email,
				"phone" => substr($phone, 1),
                "clubid" => sprintf("%02d", $club),
                "subscriptionId" => $subscriptionId,
                "promocode" => $params['promo'],
                "cid" => $client_id         
			);

			if($params['additional']) {
				if ($params['additional'] == "1") {
					$request["additional"] = true;
				} else {
					$request["additional"] = (int)$params['additional'];
				}
			}

			$trafic = $GLOBALS['arTraficAnswer'][$_REQUEST["WEB_FORM_ID"]];
			$request['type'] = 'order';
			$request['source'] = $_REQUEST[$trafic['src']];
			$request['channel'] = $_REQUEST[$trafic['mdm']];
			$request['campania'] = $_REQUEST[$trafic['cnt']];
			$request['message'] = $_REQUEST[$trafic['cmp']];
			$request['kword'] = $_REQUEST[$trafic['trm']];
			$request['cid'] = $_REQUEST[$trafic['ClientId']];
			
			$additionFields = $GLOBALS['arAdditionAnswer'][$_REQUEST["WEB_FORM_ID"]];
			
			if(isset($additionFields['name'])) $request['name'] = $_REQUEST[$additionFields['name']];
			if(isset($additionFields['surname'])) $request['surname'] = $_REQUEST[$additionFields['surname']];
			if(isset($additionFields['email'])) $request['email'] = $_REQUEST[$additionFields['email']];
			
			$this->_send($this->apiUrl."ordernew", $request);

			if ($this->_data['result']->errorCode == 0)
				$this->_result = true;
		} else {
			$this->_result = false;
		}
    }

    /**
     * @param $params
     */
    private function _request2($params)
    {
        /*Костыль для работы клуба с одинаковым ID*/
        if( !empty($params['club']) && intval($params['club']) == 9999 ) {
            $params['club'] = "11";
        }
        /*Костыль для работы клуба с одинаковым ID*/

        $name   = !empty($params['name']) ? $params['name'] : false;
        $phone  = !empty($params['phone']) ? $params['phone'] : false;
        $club  = !empty($params['club']) ? $params['club'] : false;
		$company  = !empty($params['company']) ? $params['company'] : false;
        $client_id   = !empty($params['client_id']) ? $params['client_id'] : false;
        $code   = !empty($params['code']) ? $params['code'] : false;

        if(strpos($client_id, '.')){
            $client_id = explode('.', $client_id);
            $client_id = $client_id[count($client_id)-2].'.'.$client_id[count($client_id)-1];
        }

        $this->_send($this->apiUrl."code", array(
			"phone" => substr($phone, 1),
            "code" => $code,
		));

        if ($this->_data['result']['errorCode'] == 0) {
            if(empty($name) || empty($phone) || empty($club)){
                return false;
            }
           
            $type = $params["type"] ? $params["type"] : 0;
            
			$trafic = $GLOBALS['arTraficAnswer'][$_REQUEST["WEB_FORM_ID"]];
			$arParams = array(
				"type" => intval($type),
                "name" => $name,
                "phone" => substr($phone, 1),
                "clubid" => sprintf("%02d", $club),
                "cid" => $client_id,
				'source' => $_REQUEST[$trafic['src']],
	        	'channel' => $_REQUEST[$trafic['mdm']],
	        	'campania' => $_REQUEST[$trafic['cnt']],
	        	'message' => $_REQUEST[$trafic['cmp']],
	        	'kword' => $_REQUEST[$trafic['trm']],
			);
			
			if( !empty($company) ) {
				$arParams['company'] = $company;
			}
			
			$additionFields = $GLOBALS['arAdditionAnswer'][$_REQUEST["WEB_FORM_ID"]];
			
			if(isset($additionFields['surname'])) $arParams['surname'] = $_REQUEST[$additionFields['surname']];
			if(isset($additionFields['email'])) $arParams['email'] = $_REQUEST[$additionFields['email']];
            
            $this->_send($this->apiUrl."contact", $arParams);
            
            if ($this->_data['result']['errorCode'] == 0)
                $this->_result = true;
            
            //это Слава добавил website\ordernew , чтобы приходили в 1С заказы абониментов Новокосино, Войковская, Раменки
    		/*$surname   = !empty($params['surname']) ? $params['surname'] : false;
            $email  = !empty($params['email']) ? $params['email'] : false;
    		$subscriptionId   = !empty($params['subscriptionId']) ? $params['subscriptionId'] : false;
            
    		$request = array(
    			"name" => $name,
    			"surname" => $surname,
    			"email" => $email,
    			"phone" => substr($phone, 1),
                "clubid" => sprintf("%02d", $club),
                "subscriptionId" => $subscriptionId,
                "promocode" => $params['promo'],
                "cid" => $client_id         
    		);

    		if($params['additional']) {
    			if ($params['additional'] == "1") {
    				$request["additional"] = true;
    			} else {
    				$request["additional"] = (int)$params['additional'];
    			}
    		}

    		$trafic = $GLOBALS['arTraficAnswer'][$_REQUEST["WEB_FORM_ID"]];
    		$request['type'] = 'order';
    		$request['source'] = $_REQUEST[$trafic['src']];
    		$request['channel'] = $_REQUEST[$trafic['mdm']];
    		$request['campania'] = $_REQUEST[$trafic['cnt']];
    		$request['message'] = $_REQUEST[$trafic['cmp']];
    		$request['kword'] = $_REQUEST[$trafic['trm']];
    		$request['cid'] = $_REQUEST[$trafic['ClientId']];
    		
    		$additionFields = $GLOBALS['arAdditionAnswer'][$_REQUEST["WEB_FORM_ID"]];
    		
    		if(isset($additionFields['name'])) $request['name'] = $_REQUEST[$additionFields['name']];
    		if(isset($additionFields['surname'])) $request['surname'] = $_REQUEST[$additionFields['surname']];
    		if(isset($additionFields['email'])) $request['email'] = $_REQUEST[$additionFields['email']];
    		
    		$this->_send($this->apiUrl."ordernew", $request);
            
    		if ($this->_data['result']->errorCode == 0)
    			$this->_result = true;
            */
        } else {
            $this->_result = false;
        }
    }

    private function contactReadymag($params)
    {
        $name   = !empty($params['name']) ? $params['name'] : false;
        $phone  = !empty($params['phone']) ? $params['phone'] : false;
        $club  = !empty($params['club']) ? $params['club'] : false;
        $email  = !empty($params['email']) ? $params['email'] : false;
        $type = $params["type"] ? $params["type"] : 0;
       
        if(empty($name) || empty($phone) || empty($club)){
            return false;
        }

        $phone = preg_replace("/[^0-9]/", '', $phone);
        $countNumber = strlen($phone);
        if($countNumber == 11){
            $phone = substr($phone, 1);
        }
        $countNumber = strlen($phone);

        $arParams = array(
            "type" => $type,
            "name" => $name,
            "phone" => $phone,
            "email" => $email,
            "clubid" => $club,
        );

        if($countNumber == 10){    
            $this->_send($this->apiUrl."contact", $arParams);
        }

        if ($this->_data['result']['errorCode'] == 0)
            $this->_result = true;
        
    }
    
    /**
     * does request to website/contact
     *
     * @param  array $params
     */
    private function web_site_contact($params) {
        $name   = !empty($params['name']) ? $params['name'] : false;
        $phone  = !empty($params['phone']) ? $params['phone'] : false;
        $club  = !empty($params['club']) ? $params['club'] : false;
        $client_id   = !empty($params['client_id']) ? $params['client_id'] : false;
        $code   = !empty($params['code']) ? $params['code'] : false;

        if(strpos($client_id, '.')){
            $client_id = explode('.', $client_id);
            $client_id = $client_id[count($client_id)-2].'.'.$client_id[count($client_id)-1];
        }

        if(empty($name) || empty($phone) || empty($club)){
            return false;
        }
       
        $type = $params["type"] ? $params["type"] : 0;
        
		$trafic = $GLOBALS['arTraficAnswer'][$_REQUEST["WEB_FORM_ID"]];
		$arParams = array(
			"type" => $type,
            "name" => $name,
            "phone" => substr($phone, 1),
            "clubid" => sprintf("%02d", $club),
            "cid" => $client_id,
			'source' => $_REQUEST[$trafic['src']],
        	'channel' => $_REQUEST[$trafic['mdm']],
        	'campania' => $_REQUEST[$trafic['cnt']],
        	'message' => $_REQUEST[$trafic['cmp']],
        	'kword' => $_REQUEST[$trafic['trm']],
		);
		
		$additionFields = $GLOBALS['arAdditionAnswer'][$_REQUEST["WEB_FORM_ID"]];
		
		if(isset($additionFields['surname'])) $arParams['surname'] = $_REQUEST[$additionFields['surname']];
		if(isset($additionFields['email'])) $arParams['email'] = $_REQUEST[$additionFields['email']];
        

        //file_put_contents(__DIR__.'/debug_contact.txt', print_r("website\\contact\n", true), FILE_APPEND);
        //file_put_contents(__DIR__.'/debug_contact.txt', print_r($_SERVER['REQUEST_URI']."\n", true), FILE_APPEND);
        //file_put_contents(__DIR__.'/debug_contact.txt', print_r($_REQUEST."\n", true), FILE_APPEND);
        //file_put_contents(__DIR__.'/debug_contact.txt', print_r($arParams, true), FILE_APPEND);
        //file_put_contents(__DIR__.'/debug_contact.txt', print_r("\n ================ \n", true), FILE_APPEND);

        
        $this->_send($this->apiUrl."contact", $arParams);
        
        if ($this->_data['result']['errorCode'] == 0)
            $this->_result = true;
    }
	
	/**
     * @param $params
     */
    private function request_sendcode($params)
    {
        $phone  = !empty($params['phone']) ? $params['phone'] : false;

        if(empty($phone)) {
            return false;
        }
		
		$webFormId = $_REQUEST["WEB_FORM_ID"];
		$type = (($webFormId==1) || ($webFormId==4) || ($webFormId==5) ? 'contact' : 'order');
		$trafic = $GLOBALS['arTraficAnswer'][$webFormId];
		$arParams = array(
			"phone" => substr($phone, 1),
			'source' => $_REQUEST[$trafic['src']],
        	'channel' => $_REQUEST[$trafic['mdm']],
        	'campania' => $_REQUEST[$trafic['cnt']],
        	'message' => $_REQUEST[$trafic['cmp']],
        	'kword' => $_REQUEST[$trafic['trm']],
        	'cid' => $_REQUEST[$trafic['ClientId']],
        	'promocode' => $_REQUEST['promo'],
            'clubid' => sprintf("%02d", $_REQUEST['club']),
            'name' => '',
        );
        
		$additionFields = $GLOBALS['arAdditionAnswer'][$webFormId];
		
		if(isset($additionFields['name'])) $arParams['name'] = $_REQUEST[$additionFields['name']];
		if(isset($additionFields['surname'])) $arParams['surname'] = $_REQUEST[$additionFields['surname']];
		if(isset($additionFields['email'])) $arParams['email'] = $_REQUEST[$additionFields['email']];
		if(isset($additionFields['subscriptionId']) && ((int)$arParams['subscriptionId']==0)) {
            $arParams['subscriptionId'] = $_REQUEST[$additionFields['subscriptionId']];
        }
		if(isset($_REQUEST['sub_id'])) {
            $arParams['subscriptionId'] = $_REQUEST['sub_id'];
        }
        
        if($webFormId == 1) $arParams['clubid'] = $_REQUEST[$additionFields['subscriptionId']];

        /*Костыль для работы клуба с одинаковым ID*/
        if( !empty($arParams['clubid']) && intval($arParams['clubid']) == 9999 ) {
            $arParams['clubid'] = "11";
        }
        /*Костыль для работы клуба с одинаковым ID*/

        if( !empty($arParams['subscriptionId']) ) {
            $arParams['subscriptionId'] = trim($arParams['subscriptionId']);
        }
        
        //file_put_contents(__DIR__.'/debug_reg.txt', print_r("website\\reg\n", true), FILE_APPEND);
        //file_put_contents(__DIR__.'/debug_reg.txt', print_r($_SERVER['REQUEST_URI']."\n", true), FILE_APPEND);
        //file_put_contents(__DIR__.'/debug_reg.txt', print_r($additionFields, true), FILE_APPEND);
        //file_put_contents(__DIR__.'/debug_reg.txt', print_r($_REQUEST, true), FILE_APPEND);
        //file_put_contents(__DIR__.'/debug_reg.txt', print_r($arParams, true), FILE_APPEND);
        //file_put_contents(__DIR__.'/debug_reg.txt', print_r("\n ================ \n", true), FILE_APPEND);
        
		$this->_send($this->apiUrl."reg", $arParams);

		if ($this->_data['result']['errorCode'] === 0)
			$this->_result = true;			
    }
	
	/**
     * @param $params
     */
    private function response($params)
    {
        $InvoiceId  = !empty($params['InvoiceId']) ? $params['InvoiceId'] : false;

        if(empty($InvoiceId)){
            return false;
        }

		$webFormId = $_REQUEST["WEB_FORM_ID"];
		$type = (($webFormId==1) || ($webFormId==4) || ($webFormId==5) ? 'contact' : 'order');
		$trafic = $GLOBALS['arTraficAnswer'][$webFormId];
		$arParams = array(
			"InvoiceId" => $InvoiceId,
			'type' => $type,
			'source' => $_REQUEST[$trafic['src']],
        	'channel' => $_REQUEST[$trafic['mdm']],
        	'campania' => $_REQUEST[$trafic['cnt']],
        	'message' => $_REQUEST[$trafic['cmp']],
        	'kword' => $_REQUEST[$trafic['trm']],
        	'cid' => $_REQUEST[$trafic['ClientId']],
		);
		
		$additionFields = $GLOBALS['arAdditionAnswer'][$webFormId];
		
		if(isset($additionFields['name'])) $arParams['name'] = $_REQUEST[$additionFields['name']];
		if(isset($additionFields['surname'])) $arParams['surname'] = $_REQUEST[$additionFields['surname']];
		if(isset($additionFields['email'])) $arParams['email'] = $_REQUEST[$additionFields['email']];
		if(isset($additionFields['subscriptionId'])) $arParams['subscriptionId'] = $_REQUEST[$additionFields['subscriptionId']];
		if(isset($_REQUEST['sub_id'])) $arParams['subscriptionId'] = $_REQUEST['sub_id'];

		$this->_send($this->apiUrl."check", $arParams);
		
		if ($this->_data['result']->errorCode === 0)
			$this->_result = true;			
    }
	
	private function _send($url, $data) 
	{
		file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/logs/logError.txt", json_encode($data), FILE_APPEND);
		file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/logs/logError.txt", $url, FILE_APPEND);
        
        if($data){
            $options = array( 
                CURLOPT_POST => 1, 
                CURLOPT_HEADER => 0, 
                CURLOPT_URL => $url, 
                CURLOPT_PORT => 443,
                CURLOPT_RETURNTRANSFER => 1, 
                CURLOPT_TIMEOUT => 10,
                CURLOPT_POSTFIELDS => json_encode($data)
            ); 
        }else{
            $options = array( 
                CURLOPT_HEADER => 0, 
                CURLOPT_URL => $url, 
                CURLOPT_PORT => 443,
                CURLOPT_RETURNTRANSFER => 1, 
                CURLOPT_TIMEOUT => 10,
            ); 
        }       
        
		$ch = curl_init();
		
		curl_setopt_array($ch, $options);

		if( ! $result = curl_exec($ch)) {
			$this->_data = array(
				"error" => true,
				"message" => "Нет связи с API-сервером"
			);
		}
		else {
			$this->_data = array(
				"error" => false,
				"message" => "",
				"result" => json_decode($result, true)
			);
		}
		file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/logs/logError.txt", $result, FILE_APPEND);
		
		curl_close($ch);
	}
	
	/**
     * @param $params
     */
    private function payment($params)
    {
		$data = array(
			"Amount" => $params['sum'],
			"Currency" => "RUB",
			"InvoiceId" => $params['InvoiceId'],
			"Description" => $params['Description'],
			"AccountId" => $params['publicId'],
			"Name" => $params['name'],
			"CardCryptogramPacket" => $params['crypto'],
		);
		
		print_r($data);
	}
	
	/**
     * @param $params
     */
    private function crypto($params)
    {
		$data = array(
			"InvoiceId" => $params['InvoiceId'],
			"Name" => $params['name'],
			"IpAddress" => $_SERVER['REMOTE_ADDR'],
			"CardCryptogramPacket" => $params['CardCryptogramPacket'],
		);
		
		$this->_send($this->apiUrl."crypto", $data);
#		$this->_send("https://app.spiritfit.ru/fit-crm/hs/website/crypto", $data);
		
	}
		
	/**
     * @param $params
     */
    private function crypto3d($params)
    {
		$this->_send($this->apiUrl."crypto3d", $params);
#		$this->_send("https://app.spiritfit.ru/fit-crm/hs/website/crypto", $data);
		if ($this->_data['result']->errorCode === 0)
			$this->_result = true;	
    }
    
	/**
     * @param $params
     */
    private function coupon($coupon)
    {        
        if(empty($coupon)){
            return false;
        }
             
        $this->_send($this->apiUrl.$coupon."/pricenew", false);

        if ($this->_data['result']->errorCode == 0)
            $this->_result = true;
    }
    
    private function request_authorization_code($params) {
        $phone = !empty($params['phone']) ? $params['phone'] : false;

        if(empty($phone)){
            return false;
        }

		$webFormId = $_REQUEST["WEB_FORM_ID"];
		$type = (($webFormId==1) || ($webFormId==4) || ($webFormId==5) ? 'contact' : 'order');
		$trafic = $GLOBALS['arTraficAnswer'][$webFormId];
		$arParams = array(
			"phone" => substr($phone, 1),
			'type' => $type,
			'source' => $_REQUEST[$trafic['src']],
        	'channel' => $_REQUEST[$trafic['mdm']],
        	'campania' => $_REQUEST[$trafic['cnt']],
        	'message' => $_REQUEST[$trafic['cmp']],
        	'kword' => $_REQUEST[$trafic['trm']],
        	'cid' => $_REQUEST[$trafic['ClientId']],
		);
		
		$additionFields = $GLOBALS['arAdditionAnswer'][$webFormId];
		
		if(isset($additionFields['name'])) $arParams['name'] = $_REQUEST[$additionFields['name']];
		if(isset($additionFields['surname'])) $arParams['surname'] = $_REQUEST[$additionFields['surname']];
		if(isset($additionFields['email'])) $arParams['email'] = $_REQUEST[$additionFields['email']];
		if(isset($additionFields['subscriptionId'])) $arParams['subscriptionId'] = $_REQUEST[$additionFields['subscriptionId']];
		if(isset($_REQUEST['sub_id'])) $arParams['subscriptionId'] = $_REQUEST['sub_id'];

		$this->_send($this->apiUrl."/phone", $arParams);

		if ($this->_data['result']['errorCode'] === 0)
			$this->_result = true;
    }
    private function sendCalltouch($formParam)
    {   
        $dataSend = '';
        $formName = '';
        $call_value = $_COOKIE['_ct_session_id']; 
        $ct_site_id = '41722';

        if(!empty($formParam['NAME']) || !empty($formParam['EMAIL']) || !empty($formParam['PHONE'])){

            $dbFormList = CForm::GetList($by = 's_id', $order = 'desc', [], $is_filtered);
            while($arFormList = $dbFormList->Fetch()){
                if($_REQUEST['WEB_FORM_ID'] == $arFormList['ID']){
                    $formName = $arFormList['NAME'];
                    break;
                }
            }

            $dataSend .= "fio=".urlencode($formParam['NAME']);
            $dataSend .= "&phoneNumber=".$formParam['PHONE'];
            $dataSend .= "&email=".$formParam['EMAIL'];
            $dataSend .= "&subject=".urlencode($formName);
            $dataSend .= "&requestDate=".date('d.m.Y h:m:s');
            $dataSend .= "&requestNumber=".$_REQUEST['WEB_FORM_ID'];
            $dataSend .= "&requestUrl=".$_SERVER['REQUEST_URI'];
            $dataSend .= "&tags=".urlencode($formName);
            $dataSend .= "".($call_value != 'undefined' ? "&sessionId=".$call_value : "");

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded;charset=utf-8"));
            curl_setopt($ch, CURLOPT_URL,'https://api.calltouch.ru/calls-service/RestAPI/requests/'.$ct_site_id.'/register/');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataSend);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $calltouch = curl_exec ($ch);
            curl_close ($ch);
        }
    }
}
<?php
class Api
{
    private $_result    = false;
    private $_data      = array();
    private $_errors    = array();
	
	/*
	* API url
	*/
    // private $apiUrl		= "https://app.spiritfit.ru/Fitness/hs/website/";
    // private $apiUrlTest = "https://app.spiritfit.ru/testv11/hs/website/";
    private $apiUrl;

    public function __construct($post)
    {
        $this->apiUrl= Utils::getApiURL();//Берем из настроек сайта, для теста и для прода разные api
        
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
            case 'subscribe':
                $this->subscribe($post['params']);
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
            
            //МЕТОДЫ ДЛЯ ЛК
            case 'lkreg':
                $this->lkreg($post['params']);
                AddMessage2Log('lkreg');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
            case 'lkcode':
                $this->lkcode($post['params']);
                AddMessage2Log('lkcode');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
            case 'lkinfo':
                $this->lkinfo($post['params']);
                AddMessage2Log('lkinfo');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
            case "lkcheck":
                $this->lkcheck($post['params']);
                AddMessage2Log('lkcheck');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
            case "lkedit":
                $this->lkedit($post['params']);
                AddMessage2Log('lkedit');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
            case "lkpresent":
                $this->lkpresent($post['params']);
                AddMessage2Log('lkpresent');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
            case 'lkpayments':
                $this->lkpayments($post['params']);
                AddMessage2Log('lkpayments');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
            case 'lkemailconfirm':
                $this->lkemailconfirm($post['params']);
                AddMessage2Log('lkemailconfirm');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
            case "lkfreezingget":
                $this->lkfreezingget($post['params']);
                AddMessage2Log('lkfreezingget');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
            case "lkfreezingpost":
                $this->lkfreezingpost($post['params']);
                AddMessage2Log('lkfreezingpost');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
            case "lkfreefreezingget":
                $this->lkfreefreezingget($post['params']);
                AddMessage2Log('lkfreefreezingget');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
            case "lkfreefreezingpost":
                $this->lkfreefreezingpost($post['params']);
                AddMessage2Log('lkfreefreezingpost');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;

            case "getqrcode":
                $this->getqrcode($post['params']);
                AddMessage2Log('getqrcode');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;

            //НОВАЯ ОПЛАТА
            case "orderreg":
                $this->orderreg($post['params']);
                AddMessage2Log('orderreg');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
            case "ordercode":
                $this->ordercode($post['params']);
                AddMessage2Log('orderreg');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
            case "ordercodecheck":
                $this->ordercodecheck($post['params']);
                AddMessage2Log('ordercodecheck');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
            case "ordercreate":
                $this->ordercreate($post['params']);
                AddMessage2Log('ordercreate');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
            case "orderpromocode":
                $this->orderpromocode($post['params']);
                AddMessage2Log('orderpromocode');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
            case "getorder":
                $this->getorder($post['params']);
                AddMessage2Log('getorder');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;

            case "request_sendcode_new":
                $this->request_sendcode_new($post['params']);
                AddMessage2Log('request_sendcode_new');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
            case "request2_new":
                $this->request2_new($post['params']);
                AddMessage2Log('request2_new');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;

            //Пробная тренировка в ЛК
            case "trialworkout":
                $this->trialworkout($post["params"]);
                AddMessage2Log('trialworkout');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;
            case "trialworkoutsignup":
                $this->trialworkoutsignup($post["params"]);
                AddMessage2Log('trialworkoutsignup');
                AddMessage2Log($post['params']);
                AddMessage2Log("------------------------");
                break;

            case "lkloyalty":
                $this->lkloyalty($post["params"]);
                AddMessage2Log('lkloyalty');
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
    private function subscribe( $params )
    {
        $email  = !empty($params['email']) ? $params['email'] : false;
        $type = !empty($params['type']) ? $params['type'] : false;
        if( empty($email) || empty($type) ) return false;

        $client_id = !empty($params['client_id']) ? $params['client_id'] : false;
        if(strpos($client_id, '.')){
            $client_id = explode('.', $client_id);
            $client_id = $client_id[count($client_id)-2].'.'.$client_id[count($client_id)-1];
        }

        $requestArr = array(
            "email" => $email,
            "type" => $type,
            "source" => !empty($params['src']) ? $params['src'] : false,
            "channel" => !empty($params['mdm']) ? $params['mdm'] : false,
            "campania" => !empty($params['cmp']) ? $params['cmp'] : false,
            "message" => !empty($params['cnt']) ? $params['cnt'] : false,
            "kword" => !empty($params['trm']) ? $params['trm'] : false,
            "cid" => $client_id
        );

        $this->_send($this->apiUrl."subscribe", $requestArr);

        if ($this->_data['result']->errorCode == 0) {
            $this->_result = true;
        }
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
			"type" => $params["type"] ? $params["type"] : 0
		));
		
		$smsResultArray = $this->result();
		//if ($this->_data['result']->result->errorCode == 0) {
		if( empty($smsResultArray['data']['result']['errorCode']) ) {
			if(empty($name) || ( empty($surname) && $params["type"] != 3 ) || empty($email) || empty($phone)){
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
			
			$webFormId = !empty($params["WEB_FORM_ID"]) ? $params["WEB_FORM_ID"] : $_REQUEST["WEB_FORM_ID"];
			$trafic = $GLOBALS['arTraficAnswer'][$webFormId];
			$request['type'] = 'order';
			$request['source'] = $_REQUEST[$trafic['src']];
			$request['channel'] = $_REQUEST[$trafic['mdm']];
			$request['campania'] = $_REQUEST[$trafic['cnt']];
			$request['message'] = $_REQUEST[$trafic['cmp']];
			$request['kword'] = $_REQUEST[$trafic['trm']];
			$request['cid'] = empty($_REQUEST[$trafic['ClientId']]) ? $_REQUEST[$trafic['ClientId']] : $client_id;
            $request['yaClientID']=$_REQUEST[$trafic['yaClientID']];
			
			$additionFields = $GLOBALS['arAdditionAnswer'][$_REQUEST["WEB_FORM_ID"]];
			
			if(isset($additionFields['name'])) $request['name'] = $_REQUEST[$additionFields['name']];
			if(isset($additionFields['surname'])) $request['surname'] = $_REQUEST[$additionFields['surname']];
			if(isset($additionFields['email'])) $request['email'] = $_REQUEST[$additionFields['email']];


//             file_put_contents(__DIR__.'/myTest_.txt', print_r(date("Y-m-d H:i:s"), true), FILE_APPEND);
//             file_put_contents(__DIR__.'/myTest_.txt', print_r("website\\ordernew\n", true), FILE_APPEND);
//             file_put_contents(__DIR__.'/myTest_.txt', print_r($_SERVER['REQUEST_URI']."\n", true), FILE_APPEND);
//             file_put_contents(__DIR__.'/myTest_.txt', print_r($_REQUEST, true), FILE_APPEND);
//             file_put_contents(__DIR__.'/myTest_.txt', print_r($request, true), FILE_APPEND);
//             file_put_contents(__DIR__.'/myTest_.txt', print_r("\n ================ \n", true), FILE_APPEND);
			
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
        file_put_contents(__DIR__.'/myTest_.txt', print_r($params["type"]."\n", true), FILE_APPEND);
        $this->_send($this->apiUrl."code", array(
			"phone" => substr($phone, 1),
            "code" => $code,
			"type" => $params["type"] ? $params["type"] : 0
		));

        if ($this->_data['result']['errorCode'] == 0) {
			if(empty($name) || empty($phone) || empty($club)){
				return false;
            }
           
            $type = $params["type"] ? $params["type"] : 0;
            
			$webFormId = !empty($params["WEB_FORM_ID"]) ? $params["WEB_FORM_ID"] : $_REQUEST["WEB_FORM_ID"];
			$trafic = $GLOBALS['arTraficAnswer'][$webFormId];
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
                'yaClientID'=>$_REQUEST[$trafic['yaClientID']],
			);
			
			if( !empty($company) ) {
				$arParams['company'] = $company;
			}
			if( !empty($params['email']) ) {
				$arParams['email'] = $params['email'];
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

        if(empty($name) || empty($phone)){
            return false;
        }
       
        $type = $params["type"] ? $params["type"] : 0;
        
		$trafic = $GLOBALS['arTraficAnswer'][$_REQUEST["WEB_FORM_ID"]];
		$arParams = array(
			"type" => $type,
            "name" => $name,
            "phone" => substr($phone, 1),
            "clubid" => !empty($club) ? sprintf("%02d", $club) : "",
            "cid" => $client_id,
			'source' => $_REQUEST[$trafic['src']],
        	'channel' => $_REQUEST[$trafic['mdm']],
        	'campania' => $_REQUEST[$trafic['cnt']],
        	'message' => $_REQUEST[$trafic['cmp']],
        	'kword' => $_REQUEST[$trafic['trm']],
            'yaClientID'=>$_REQUEST[$trafic['yaClientID']]
		);
		
		$additionFields = $GLOBALS['arAdditionAnswer'][$_REQUEST["WEB_FORM_ID"]];
		
		if(isset($additionFields['surname'])) $arParams['surname'] = $_REQUEST[$additionFields['surname']];
		if(isset($additionFields['email'])) $arParams['email'] = $_REQUEST[$additionFields['email']];
  
		if(!empty($params['company'])) $arParams['company'] = $params['company'];
		if(!empty($params['theme'])) $arParams['theme'] = $params['theme'];
		if(!empty($params['email'])) $arParams['email'] = $params['email'];
  
		//if( empty($arParams["clubid"]) ) unset($arParams["clubid"]);

        // file_put_contents(__DIR__.'/myTest_.txt', print_r("website\\contact\n", true), FILE_APPEND);
        // file_put_contents(__DIR__.'/myTest_.txt', print_r($_SERVER['REQUEST_URI']."\n", true), FILE_APPEND);
        // file_put_contents(__DIR__.'/myTest_.txt', print_r($arParams, true), FILE_APPEND);
        // file_put_contents(__DIR__.'/myTest_.txt', print_r("\n ================ \n", true), FILE_APPEND);
		
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
        if( empty($webFormId) && !empty($params["WEB_FORM_ID"]) ) {
            $webFormId = $params["WEB_FORM_ID"];
        }
		$type = (($webFormId==1) || ($webFormId==4) || ($webFormId==5) ? 'contact' : 'order');
		$trafic = $GLOBALS['arTraficAnswer'][$webFormId];

        $client_id = $_REQUEST[$trafic['ClientId']];
        if( empty($client_id) && !empty($params["client_id"]) ) {
            $client_id = $params["client_id"];
        }
        if(strpos($client_id, '.')) {
            $client_id = explode('.', $client_id);
            $client_id = $client_id[count($client_id)-2].'.'.$client_id[count($client_id)-1];
        }

		$arParams = array(
			"phone" => substr($phone, 1),
			'source' => $_REQUEST[$trafic['src']],
        	'channel' => $_REQUEST[$trafic['mdm']],
        	'campania' => $_REQUEST[$trafic['cnt']],
        	'message' => $_REQUEST[$trafic['cmp']],
        	'kword' => $_REQUEST[$trafic['trm']],
        	'cid' => $client_id,
        	'promocode' => $_REQUEST['promo'],
            'clubid' => sprintf("%02d", $_REQUEST['club']),
            'name' => (!empty($params["name"])) ? $params["name"] : "",
			'type' => $type,
            'yaClientID' => $_REQUEST[$trafic['yaClientID']]
        );
		if( empty($arParams["email"]) && !empty($params["email"]) ) {
			$arParams["email"] = $params["email"];
		}
		if( !empty($params["club"]) ) {
			$arParams["clubid"] = $params["club"];
		}
		if( !empty($params["promo"]) ) {
			$arParams["promo"] = $params["promo"];
		}
		if( !empty($params["type"]) ) {
			$arParams["type"] = $params["type"];
		}
		if( !empty($params["client_id"]) && empty($params["cid"]) ) {
			$arParams["client_id"] = $params["client_id"];
		}
  
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

        // file_put_contents(__DIR__.'/debug_reg.txt', print_r("website\\reg\n", true), FILE_APPEND);
        // file_put_contents(__DIR__.'/debug_reg.txt', print_r($_SERVER['REQUEST_URI']."\n", true), FILE_APPEND);
        // file_put_contents(__DIR__.'/debug_reg.txt', print_r($additionFields, true), FILE_APPEND);
        // file_put_contents(__DIR__.'/debug_reg.txt', print_r($_REQUEST, true), FILE_APPEND);
        // file_put_contents(__DIR__.'/debug_reg.txt', print_r($arParams, true), FILE_APPEND);
        // file_put_contents(__DIR__.'/debug_reg.txt', print_r("\n ================ \n", true), FILE_APPEND);
        // file_put_contents(__DIR__.'/myTest_.txt', print_r("website\\reg\n", true), FILE_APPEND);
        // file_put_contents(__DIR__.'/myTest_.txt', print_r($_SERVER['REQUEST_URI']."\n", true), FILE_APPEND);
        // file_put_contents(__DIR__.'/myTest_.txt', print_r($arParams, true), FILE_APPEND);
        // file_put_contents(__DIR__.'/myTest_.txt', print_r("\n ================ \n", true), FILE_APPEND);
        
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
	
	private function _send($url, $data, $header=null)
	{
//		file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/logs/logError.txt", json_encode($data), FILE_APPEND);
//		file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/logs/logError.txt", $url, FILE_APPEND);


		if($data) {
			if( !empty($_SERVER["REQUEST_URI"]) && strpos($_SERVER["REQUEST_URI"], '.php') !== false ) {
				$data["page_url"] = (!empty($_SERVER["HTTP_REFERER"])) ? $_SERVER["HTTP_REFERER"] : "";
			} else {
				$data["page_url"] = $_SERVER["REQUEST_URI"];
			}
		}

        
        if($data){
            $options = array(
                CURLOPT_POST => 1, 
                CURLOPT_HEADER => 0, 
                CURLOPT_URL => $url, 
                CURLOPT_PORT => 443,
                CURLOPT_RETURNTRANSFER => 1, 
                CURLOPT_TIMEOUT => 20,
                CURLOPT_POSTFIELDS => json_encode($data)
            ); 
        }else{
            $options = array(
                CURLOPT_HEADER => 0, 
                CURLOPT_URL => $url, 
                CURLOPT_PORT => 443,
                CURLOPT_RETURNTRANSFER => 1, 
                CURLOPT_TIMEOUT => 20,
            ); 
        }

        if (!empty($header)){
            $options[CURLOPT_HTTPHEADER]=array(
                    $header,
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
				"result" => json_decode($result, true),
                "http_code"=>curl_getinfo($ch, CURLINFO_HTTP_CODE),
			);
		}
//		file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/logs/logError.txt", $result, FILE_APPEND);
		
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


    //МЕТОДЫ ДЛЯ ЛК
    private function lkreg($params){
        $this->_send($this->apiUrl."lkregistration", $params);
        // $this->_send("https://app.spiritfit.ru/fitness-test1/hs/website/lkregistration", $params);
        if ($this->_data['result']['errorCode'] == 0){
            $this->_result = true;
        }
        else{
            $this->_result=false;
        }
//        file_put_contents(__DIR__.'/myTest_.txt', print_r(date("Y-m-d H:i:s"), true), FILE_APPEND);
//        file_put_contents(__DIR__.'/myTest_.txt', print_r("website\\lkregistration\n", true), FILE_APPEND);
//        file_put_contents(__DIR__.'/myTest_.txt', print_r($params, true)."\n", FILE_APPEND);
//        file_put_contents(__DIR__.'/myTest_.txt', print_r($this->_data, true)."\n", FILE_APPEND);
//        file_put_contents(__DIR__.'/myTest_.txt', print_r("\n ================ \n", true), FILE_APPEND);
    }

    private function lkcode($params){
        $this->_send($this->apiUrl."lkcode", $params);
        // $this->_send("https://app.spiritfit.ru/fitness-test1/hs/website/lkcode", $params);
        if (empty($this->_data['result'])){
            $this->_result=false;
        }
        else{
            $this->_result = $this->_data['result']['result'];
        }
//        file_put_contents(__DIR__.'/myTest_.txt', print_r(date("Y-m-d H:i:s"), true), FILE_APPEND);
//        file_put_contents(__DIR__.'/myTest_.txt', print_r("website\\lkcode\n", true), FILE_APPEND);
//        file_put_contents(__DIR__.'/myTest_.txt', print_r($params, true)."\n", FILE_APPEND);
//        file_put_contents(__DIR__.'/myTest_.txt', print_r($this->_data, true)."\n", FILE_APPEND);
//        file_put_contents(__DIR__.'/myTest_.txt', print_r("\n ================ \n", true), FILE_APPEND);
    }

    private function lkinfo($params){
        $this->_send($this->apiUrl."lkinfo", $params);
        // $this->_send("https://app.spiritfit.ru/fitness-test1/hs/website/lkinfo", $params);
        if ($this->_data['result']['errorCode'] == 0){
            $this->_result = true;
        }
        else{
            $this->_result=false;
        }

//        file_put_contents(__DIR__.'/myTest_.txt', print_r(date("Y-m-d H:i:s"), true), FILE_APPEND);
//        file_put_contents(__DIR__.'/myTest_.txt', print_r("website\\lkinfo\n", true), FILE_APPEND);
//        file_put_contents(__DIR__.'/myTest_.txt', print_r($params, true)."\n", FILE_APPEND);
//        file_put_contents(__DIR__.'/myTest_.txt', print_r($this->_data, true)."\n", FILE_APPEND);
//        file_put_contents(__DIR__.'/myTest_.txt', print_r("\n ================ \n", true), FILE_APPEND);
    }

    private function lkcheck($params){
        $this->_send($this->apiUrl."lkcheck", $params);
        if ($this->_data['result']['result'] === true){
            $this->_result = true;
        }
        else{
            $this->_result = false;
        }
    }

    private function lkedit($params){
        $this->_send($this->apiUrl."lkedit", $params);
    }

    private function lkpresent($params){
        $this->_send($this->apiUrl."lkpresent", $params);

        if ($this->_data['result']['errorCode'] == 0){
            $this->_result = true;
        }
        else{
            $this->_result=false;
        }
    }

    private function lkpayments($params){
        $this->_send($this->apiUrl."lkpayments", $params);

        if ($this->_data['result']['errorCode'] == 0){
            $this->_result = true;
        }
        else{
            $this->_result=false;
        }
    }

    private function lkemailconfirm($params){
        $token=API_SPIRITFIT_TOKEN;
        $arParams['token']=$token;
        $arParams['email']=$params['email'];
        $this->_send("https://api.spiritfit.ru/email-sendcode", $arParams, 'Content-Type: application/json');

        if ($this->_data['result']['errorCode'] == 0){
            $this->_result = true;
        }
        else{
            $this->_result=false;
        }
    }

    private function lkfreezingget($params){
        $this->_send($this->apiUrl."lkfreezingget", $params);

        if ($this->_data['result']['errorCode'] == 0){
            $this->_result = true;
        }
        else{
            $this->_result=false;
        }
    }

    private function lkfreezingpost($params){
        $this->_send($this->apiUrl."lkfreezingpost", $params);

        if ($this->_data['result']['errorCode'] == 0){
            $this->_result = true;
        }
        else{
            $this->_result=false;
        }
    }

    private function lkfreefreezingget($params){
        $this->_send($this->apiUrl."lkfreefreezingget", $params);

        if ($this->_data['result']['errorCode'] == 0){
            $this->_result = true;
        }
        else{
            $this->_result=false;
        }
    }

    private function lkfreefreezingpost($params){
        $this->_send($this->apiUrl."lkfreefreezingpost", $params);

        if ($this->_data['result']['errorCode'] == 0){
            $this->_result = true;
        }
        else{
            $this->_result=false;
        }
    }


    private function getqrcode($params){
        $params['token']=API_SPIRITFIT_TOKEN;
        $this->_send("https://api.spiritfit.ru/qrcode", $params, 'Content-Type: application/json');

        if ($this->_data['result']['errorCode'] == 0){
            $this->_result = true;
        }
        else{
            $this->_result=false;
        }
    }




    //Новая оплата (закладываю на будущее)
    private function orderreg($params){
        $params['clubid']=sprintf("%02d", $params['clubid']);

        /*Костыль для работы клуба с одинаковым ID*/
        if(!empty($params['clubid']) && intval($params['clubid']) == 9999) {
            $params['clubid'] = "11";
        }
        /*Костыль для работы клуба с одинаковым ID*/

        if(!empty($params['subscriptionId'])) {
            $params['subscriptionId'] = trim($params['subscriptionId']);
        }


        $this->_send($this->apiUrl."orderreg", $params);

        if ($this->_data['result']['errorCode'] === 0)
            $this->_result = true;
    }

    private function ordercode($params){
        if(empty($params['phone'])){
            return false;
        }
        $this->_send($this->apiUrl."ordercode", $params);

        if ($this->_data['result']['errorCode'] === 0)
            $this->_result = true;
    }

    private function ordercodecheck($params){
        $this->_send($this->apiUrl."ordercodecheck", $params);
        if ($this->_data['result']['result'] === true){
            $this->_result = true;
        }
        else{
            $this->_result = false;
        }
    }

    private function ordercreate($params){
        $params['clubid']=sprintf("%02d", $params['clubid']);

        /*Костыль для работы клуба с одинаковым ID*/
        if(!empty($params['clubid']) && intval($params['clubid']) == 9999) {
            $params['clubid'] = "11";
        }
        /*Костыль для работы клуба с одинаковым ID*/

        if(!empty($params['subscriptionId'])) {
            $params['subscriptionId'] = trim($params['subscriptionId']);
        }

        $this->_send($this->apiUrl."ordercreate", $params);
        if ($this->_data['result']['errorCode'] === 0)
            $this->_result = true;
    }


    private function orderpromocode($params){
        $this->_send($this->apiUrl."orderpromocode", $params);


        if ($this->_data['result']['errorCode'] === 0)
            $this->_result = true;
    }

    private function getorder($params){
        $this->_send($this->apiUrl."getorder", $params);

        if ($this->_data['result']['errorCode'] === 0)
            $this->_result = true;
    }


    private function request_sendcode_new($params){
        $params['clubid']=sprintf("%02d", $params['clubid']);

        /*Костыль для работы клуба с одинаковым ID*/
        if(!empty($params['clubid']) && intval($params['clubid']) == 9999) {
            $params['clubid'] = "11";
        }
        /*Костыль для работы клуба с одинаковым ID*/

        if(!empty($params['subscriptionId'])) {
            $params['subscriptionId'] = trim($params['subscriptionId']);
        }

        $this->_send($this->apiUrl."reg", $params);

        if ($this->_data['result']['errorCode'] === 0)
            $this->_result = true;
    }

    private function request2_new($params){
        $this->_send($this->apiUrl."code", array(
            "phone" => $params["phone"],
            "code" => $params["code"],
            "type" => $params["type"] ? $params["type"] : 0
        ));

        if ($this->_data['result']['errorCode'] == 0) {
            unset($params['code']);

            $params['clubid']=sprintf("%02d", $params['clubid']);

            /*Костыль для работы клуба с одинаковым ID*/
            if(!empty($params['clubid']) && intval($params['clubid']) == 9999) {
                $params['clubid'] = "11";
            }
            /*Костыль для работы клуба с одинаковым ID*/

            if(!empty($params['subscriptionId'])) {
                $params['subscriptionId'] = trim($params['subscriptionId']);
            }

            $this->_send($this->apiUrl."contact", $params);

            if ($this->_data['result']['errorCode'] === 0)
                $this->_result = true;

            if ($this->_data['result']['errorCode'] == 0)
                $this->_result = true;

        } else {
            $this->_result = false;
        }
    }


    private function trialworkout($params){
        $this->_send($this->apiUrl."trialworkout", $params);

        if ($this->_data['result']['errorCode'] === 0)
            $this->_result = true;
    }

    private function trialworkoutsignup($params){
        $this->_send($this->apiUrl."trialworkoutsignup", $params);

        if ($this->_data['result']['errorCode'] === 0)
            $this->_result = true;
    }

    private function lkloyalty($params){
        $this->_send($this->apiUrl."lkloyalty", $params);

        if ($this->_data['result']['errorCode'] === 0)
            $this->_result = true;

    }
}
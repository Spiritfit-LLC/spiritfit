<?php
	namespace Outcode;
	
	use \Bitrix\Main;
	use \Bitrix\Main\Page\Asset;
	use \Bitrix\Main\Localization\Loc;
    use \Bitrix\Main\Loader;
	
	class ResultPush {
        private $pushPath;

	    function __construct( $pushPath ) {
            $this->pushPath = $pushPath;
        }

        public function send(array $data) : array {
            $options = [
                CURLOPT_POST => 1,
                CURLOPT_HEADER => 0,
                CURLOPT_URL => $this->pushPath,
                CURLOPT_PORT => 443,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_TIMEOUT => 20,
                CURLOPT_POSTFIELDS => json_encode($data)
            ];

            $ch = curl_init();
            curl_setopt_array($ch, $options);

            if( !$result = curl_exec($ch)) {
                $cUrlError = curl_error($ch);
                $cUrlErrorNo = curl_errno($ch);
                curl_close($ch);
                return [
                    'error' => true,
                    'message' => $cUrlError,
                    'curl_errno' => $cUrlErrorNo
                ];
            } else {
                $cUrlInfo = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                return [
                    "error" => false,
                    "message" => "",
                    "result" => json_decode($result, true),
                    "http_code" => $cUrlInfo
                ];
            }
        }
	}	
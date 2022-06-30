<? require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php"); ?>

<?php
header('Content-Type: application/json; charset=utf-8');

if(!empty($_POST['data'])){
    $input['data']=$_POST['data'];
    $input['logo']=$_POST['logo'];
    $input['background']=$_POST['background'];
    $input['token']=Utils::getApiSpiritfitToken();

    $options = array(
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_URL => 'https://api.spiritfit.ru/qrcode',
        CURLOPT_PORT => 443,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_POSTFIELDS => json_encode($input),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    );

    $ch = curl_init();

    curl_setopt_array($ch, $options);


    if( ! $result = curl_exec($ch)) {
        $response=array(
            "error" => true,
            "message" => "Нет связи с API-сервером"
        );
    }
    else {
        $response=array(
            "error" => false,
            "message" => "",
            "result" => json_decode($result, true),
            "http_code"=>curl_getinfo($ch, CURLINFO_HTTP_CODE),
        );
    }

    if ($response['result']['errorCode']!=0){
        echo json_encode(['result'=>false, 'message'=>$response['result']['message']]);
    }
    else{
        echo json_encode(['result'=>true, 'url'=>$response['result']['result']['code_src']]);
    }

}
else{
    echo json_encode(['result'=>false, 'message'=>'Незарегистрированное действие.'], JSON_UNESCAPED_UNICODE);
}

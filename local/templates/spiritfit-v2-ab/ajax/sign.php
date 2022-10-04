<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if($_POST['form_checkbox_privacy'] == 14) {
    unset($_POST['form_checkbox_privacy']);

    $_POST['sign_phone'] = str_replace(array('+7', ' ', '(', ')', '-'), '', $_POST['sign_phone']);

    if($curl = curl_init()) {

        $data_string = json_encode ($_POST, JSON_UNESCAPED_UNICODE);

        curl_setopt($curl, CURLOPT_URL, 'https://app.spiritfit.ru/Fitness/hs/website/preEntry');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $headers = array(
            'Accept: application/json'
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = json_decode(curl_exec($curl), true);
        $error = curl_errno($curl);
        $error_message = curl_error($curl);
        $header = curl_getinfo($curl);

        curl_close($curl);

        echo json_encode($result);
    } else {
        echo 'curl_init() => false';
    }
}
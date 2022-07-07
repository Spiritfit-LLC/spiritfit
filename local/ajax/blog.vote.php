<?php
header('Content-Type: application/json; charset=utf-8');

require( $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php" );

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    CModule::IncludeModule("iblock");
    $DBRes=CIBlockElement::GetByID($_POST['blog-id']);

    if($ob = $DBRes->GetNextElement()) {
        $element = $ob->GetFields();
        $element['PROPERTIES'] = $ob->GetProperties();

        $votes_count=$element['PROPERTIES']["RATING_VOTES_COUNT"]["VALUE"];
        $rating_sum=$element['PROPERTIES']["RATING_SUM"]["VALUE"];

        $rating_sum=(int)$rating_sum+(int)$_POST["rating"];
        $votes_count=(int)$votes_count+1;
        $rating=(float)$rating_sum/(float)$votes_count;

        $PROP=[
            "RATING_SUM"=>$rating_sum,
            "RATING_VOTES_COUNT"=>$votes_count,
            "RATING"=>$rating
        ];

        CIBlockElement::SetPropertyValuesEx($_POST['blog-id'], false, $PROP);

        $_SESSION['USER_VOTES'][$_POST['blog-id']]=$_POST["rating"];
        echo json_encode(['result'=>true]);
    }
    else{
        echo json_encode(['result'=>false, 'Статья не найдена']);
    }
}
else{
    echo json_encode(['result'=>'NOT ALLOWED METHOD']);
}
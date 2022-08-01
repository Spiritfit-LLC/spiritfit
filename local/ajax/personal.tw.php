<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $APPLICATION;
global $USER;

if ($_POST["TYPE"]=="API"){
    $FORM_FIELDS=PersonalUtils::GetPersonalPageFormFields($USER->GetID(), true, [], $_POST['SECTION_ID']);

//Не заполнены обязательные поля или несоответствие валидатору
    if (!$FORM_FIELDS['ISSET']){
        return ['result'=>false, 'errors'=>$FORM_FIELDS['ERRORS']];
    }

    foreach($FORM_FIELDS['SECTIONS'][$_POST['SECTION_ID'][0]]["FIELDS"] as $FIELD){
        if ($FIELD['CODE']=="tw_new_club" || $FIELD['CODE']=="tw_change_club"){
            $CLUB_ID=$FIELD["VALUE"];
        }
        if ($FIELD["CODE"]=="tw_new_date" || $FIELD['CODE']=="tw_change_date"){
            $DATE=$FIELD["VALUE"];
        }
    }

    if(!empty($_POST["tw_action"])){
        $action=$_POST["tw_action"];
    }
    else{
        $action="new";
    }

    $api=new Api([
        "action"=>"trialworkout",
        "params"=>[
            "id1c"=>$FORM_FIELDS["USER_1CID"],
            "login"=>$FORM_FIELDS["USER_LOGIN"],
            "action"=>$action,
            "date"=>$DATE,
            "clubid"=>$CLUB_ID
        ]
    ]);

    $response=$api->result();

    $APPLICATION->ShowAjaxHead();
    $APPLICATION->IncludeComponent(
        'custom:personal.trialworkout',
        '',
        array(
            "TW_TABLE"=>$response["data"]["result"]["result"],
            "DATE"=>$DATE,
            "CLUB_ID"=>$CLUB_ID,
            "TW_ACTION"=>$_POST["tw_action"],
        )
    );
}
elseif($_POST["TYPE"]=="TIMETABLE"){
    $CLUB_ID=$_POST["clubid"];
    $DATE=$_POST["date"];

    $APPLICATION->ShowAjaxHead();
    $APPLICATION->IncludeComponent(
        'custom:personal.trialworkout',
        '',
        array(
            "ADDITIONAL_TW_TABLE"=>$_POST["timetable"],
            "DATE"=>$DATE,
            "CLUB_ID"=>$CLUB_ID,
            "TW_ACTION"=>$_POST["tw_action"],
        )
    );
}




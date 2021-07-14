<?
class CIPropertyPrice {
    function GetUserTypeDescription() {
        return array(
            "PROPERTY_TYPE" => "S", 
            "USER_TYPE" => "priceclub", 
            "DESCRIPTION" => "Тройное поле", 
            "GetPropertyFieldHtml"=> array("CIPropertyPrice", "GetPropertyFieldHtml"), 
            "ConvertToDB" => array("CIPropertyPrice", "ConvertToDB"), 
            "GetSettingsHTML" => array("CIPropertyPrice",'GetSettingsHTML'),
            "PrepareSettings" => array("CIPropertyPrice",'PrepareSettings'),
            "ConvertFromDB" => array("CIPropertyPrice", "ConvertFromDB")
        );
    }
    function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName) {
        Bitrix\Main\Loader::includeModule('iblock');
        global $APPLICATION;
        if($arProperty["IBLOCK_ID"]){
            $dbElements = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $arProperty["USER_TYPE_SETTINGS"]["IBLOCK_ID"], "PROPERTY_SOON" => false), false, false, array("ID","NAME"));
            while ($res = $dbElements->fetch()) {
                $arList[$res["ID"]] = $res["NAME"];               
            }
        }
        
        $html = "<input type='text' name=\"" . $strHTMLControlName["VALUE"] . "[NUMBER]\" value='" . $value["VALUE"]["NUMBER"] . "' placeholder='" . $arProperty["USER_TYPE_SETTINGS"]["PLACEHOLDER_1"] . "'>";
        $html .= "<input type='text' name=\"" . $strHTMLControlName["VALUE"] . "[PRICE]\" value='".$value["VALUE"]["PRICE"]."' placeholder='" . $arProperty["USER_TYPE_SETTINGS"]["PLACEHOLDER_2"] . "'>";
        if($arList){
            $html .= "<select name=\"" . $strHTMLControlName["VALUE"] . "[LIST]\"><option value=\"\">Не выбрано</option>";
           
            foreach ($arList as $xml_id => $val){
                
                $html .= "<option value=\"" . $xml_id . "\"" . ($xml_id == $value["VALUE"]["LIST"] ? " selected=\"selected\"" : "") . ">" . $val . "</option>";
            } 
            $html .= "</select>";
        }
        return $html;      
    }
    function ConvertToDB($arProperty, $value){
        if (unserialize($value["VALUE"])) {
            $value["VALUE"] = unserialize($value["VALUE"]);
        }
        
        $isEmpty = false;
        foreach($value["VALUE"] as $val){
            if(strlen($val) == 0){
                $isEmpty = true;
            }
        }

        $value["VALUE"] = (!$isEmpty) ? serialize($value["VALUE"]) : array();

        return $value;
    }
    function ConvertFromDB($arProperty, $value){
        if($value["VALUE"]){
            $value["VALUE"] = unserialize($value["VALUE"]);
        }
        return $value;
    }
    function GetSettingsHTML($arFields,$strHTMLControlName, &$arPropertyFields){
        $IBLOCK_ID = ($arFields['USER_TYPE_SETTINGS']["IBLOCK_ID"]) ? $arFields['USER_TYPE_SETTINGS']["IBLOCK_ID"] : "";

        return '<tr>
                    <td width="40%" class="adm-detail-content-cell-l">Название первого текстового поля</td>
                    <td class="adm-detail-content-cell-r">
                        <input type="text" size="50" maxlength="2000" name="' . $strHTMLControlName["NAME"] . "[PLACEHOLDER_1]" . '" value="' . $arFields['USER_TYPE_SETTINGS']['PLACEHOLDER_1'] . '">
                    </td>
                </tr>
                <tr>
                    <td width="40%" class="adm-detail-content-cell-l">Название второго текстового поля</td>
                    <td class="adm-detail-content-cell-r">
                        <input type="text" size="50" maxlength="2000" name="' . $strHTMLControlName["NAME"] . "[PLACEHOLDER_2]" . '" value="' . $arFields['USER_TYPE_SETTINGS']['PLACEHOLDER_2'] . '">
                    </td>
                </tr>
                <tr>
                    <td>Инфоблок для привязки</td>
                    <td>'.GetIBlockDropDownList($IBLOCK_ID, $strHTMLControlName["NAME"]."[IBLOCK_TYPE]", $strHTMLControlName["NAME"]."[IBLOCK_ID]").'</td>
                </tr>';
    }
    public static function PrepareSettings($arFields){
       return $arFields;
    }
}
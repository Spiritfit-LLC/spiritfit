<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');

IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/modules/main/options.php');

$APPLICATION->SetTitle(\Bitrix\Main\Localization\Loc::getMessage("QUIZ_TITLE"));

use Bitrix\Main\Loader;

Loader::includeModule("outcode.quiz");

$MODULE_ID = \Outcode\Tools::getModuleId();

$RIGHT = $APPLICATION->GetGroupRight($MODULE_ID);
if( $RIGHT >= "R" ) {

    $quiz = new Outcode\Quiz();

    $date = new DateTime('now');
    $date->modify('Last Monday');

    $startDate = strtotime($date->format('d-m-Y 00:00:00'));
    $endDate = time();

    $resultTableWeek = $quiz->getAllResults($startDate, $endDate);
    $resultTableAll = $quiz->getAllResults(315532800, $endDate);

    $arAllOptions = [];
    $aTabs = array(
        ['DIV' => 'edit1', 'TAB' => \Bitrix\Main\Localization\Loc::getMessage('OUTCODE_QUIZ_RESULT2'), 'ICON' => 'perfmon_settings', 'TITLE' => ''],
        ['DIV' => 'edit2', 'TAB' => \Bitrix\Main\Localization\Loc::getMessage('OUTCODE_QUIZ_RESULT3'), 'ICON' => 'perfmon_settings', 'TITLE' => ''],
    );

    $tabControl = new CAdminTabControl('tabControl', $aTabs);

    if( $REQUEST_METHOD == 'POST' && strlen($Update.$Apply.$RestoreDefaults) > 0 && $RIGHT == 'W' && check_bitrix_sessid()) {

        if( file_exists($_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/bitrix/modules/perfmon/prolog.php') ) {
            require_once($_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/bitrix/modules/perfmon/prolog.php');
        }

        ob_start();
        $Update = $Update.$Apply;
        if( file_exists($_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/bitrix/modules/main/admin/group_rights.php') ) {
            require_once($_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/bitrix/modules/main/admin/group_rights.php');
        }
        ob_end_clean();
    }
    ?>
    <form id="outcode_quiz" method="post" action="<?=$APPLICATION->GetCurPage()?>?mid=<?=urlencode($MODULE_ID)?>&amp;lang=<?=LANGUAGE_ID?>">
        <?
        $tabControl->Begin();
        $tabControl->BeginNextTab();
        $arNotes = [];
        ?>

        <tr class="heading">
            <td width="40%" nowrap>
                <?=\Bitrix\Main\Localization\Loc::getMessage('TABLE_TITLE_EMAIL')?>
            </td>
            <td width="60%">
                <?=\Bitrix\Main\Localization\Loc::getMessage('TABLE_TITLE_SCORE')?>
            </td>
        </tr>
        <? foreach($resultTableWeek['TOTAL_RESULT'] as $result) { ?>
            <tr >
                <td width="40%" style="text-align: left; font-weight: bold;">
                    <?=$result['EMAIL']?> (<?=$result['LOGIN']?>)
                </td>
                <td width="60%" style="text-align: center;">
                    <?=$result['VALUE']?>
                </td>
            </tr>
        <? } ?>
        <? if( empty($resultTableWeek['TOTAL_RESULT']) ) { ?>
            <tr>
                <td width="100%" colspan="2" style="color:red"><?=\Bitrix\Main\Localization\Loc::getMessage('QUIZ_IS_EMPTY')?></td>
            </tr>
        <? } ?>

        <? $tabControl->BeginNextTab(); ?>

        <tr class="heading">
            <td width="40%" nowrap>
                <?=\Bitrix\Main\Localization\Loc::getMessage('TABLE_TITLE_EMAIL')?>
            </td>
            <td width="60%">
                <?=\Bitrix\Main\Localization\Loc::getMessage('TABLE_TITLE_SCORE')?>
            </td>
        </tr>
        <? foreach($resultTableAll['TOTAL_RESULT'] as $result) { ?>
            <tr >
                <td width="40%" style="text-align: left; font-weight: bold;">
                    <?=$result['EMAIL']?> (<?=$result['LOGIN']?>)
                </td>
                <td width="60%" style="text-align: center;">
                    <?=$result['VALUE']?>
                </td>
            </tr>
        <? } ?>
        <? if( empty($resultTableAll['TOTAL_RESULT']) ) { ?>
            <tr>
                <td width="100%" colspan="2" style="color:red"><?=\Bitrix\Main\Localization\Loc::getMessage('QUIZ_IS_EMPTY')?></td>
            </tr>
        <? } ?>

        <? foreach($arAllOptions as $arOption) {
            $val = $arOption[3];
            $type = $arOption[2];
            if(isset($arOption[4])) {
                $arNotes[] = $arOption[4];
            }
            ?>
            <tr>
                <td width="40%" nowrap <? if($type[0]=="textarea") echo 'class="adm-detail-valign-top"' ?>>
                    <?if(isset($arOption[4])):?>
                        <span class="required"><sup><?echo count($arNotes)?></sup></span>
                    <?endif;?>
                    <label for="<?echo htmlspecialcharsbx($arOption[0])?>"><?echo $arOption[1]?>:</label>
                </td>
                <td width="60%">
                    <? if( $type[0] == "checkbox" ) { ?>
                        <input type="checkbox" name="<?echo htmlspecialcharsbx($arOption[0])?>" id="<?echo htmlspecialcharsbx($arOption[0])?>" value="Y"<?if($val=="Y")echo" checked";?>>
                    <? } elseif( $type[0]=="text" ) { ?>
                        <input type="text" size="<?echo $type[1]?>" maxlength="255" value="<?echo htmlspecialcharsbx($val)?>" name="<?echo htmlspecialcharsbx($arOption[0])?>" id="<?echo htmlspecialcharsbx($arOption[0])?>"><?if($arOption[0] == "slow_sql_time") echo \Bitrix\Main\Localization\Loc::getMessage('PERFMON_OPTIONS_SLOW_SQL_TIME_SEC')?>
                    <? } elseif($type[0]=="textarea") { ?>
                        <textarea rows="<?echo $type[1]?>" cols="<?echo $type[2]?>" name="<?echo htmlspecialcharsbx($arOption[0])?>" id="<?echo htmlspecialcharsbx($arOption[0])?>"><?echo htmlspecialcharsbx($val)?></textarea>
                    <? } ?>
                </td>
            </tr>
        <? } ?>

        <? $tabControl->Buttons(); ?>
        <input <?if ($RIGHT<'W') echo "disabled" ?> type="submit" name="Update" value="<?=\Bitrix\Main\Localization\Loc::getMessage('MAIN_SAVE')?>" title="<?=\Bitrix\Main\Localization\Loc::getMessage('MAIN_OPT_SAVE_TITLE')?>" class="adm-btn-save">

        <?=bitrix_sessid_post();?>
        <?$tabControl->End();?>
    </form>
    <style>
        #outcode_quiz #tabControl_buttons_div input,
        #outcode_quiz #tabControl_buttons_div .adm-detail-pin-btn {
            display: none;
        }
    </style>
    <?
    if(!empty($arNotes)) {
        echo BeginNote();
        foreach($arNotes as $i => $str) {
            ?><span class="required"><sup><?echo $i+1?></sup></span><?echo $str?><br><?
        }
        echo EndNote();
    }
}

require($_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/modules/main/include/epilog_admin.php');
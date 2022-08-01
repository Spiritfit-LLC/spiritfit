<script>
    var pageType=<?=CUtil::PhpToJSObject($arResult['PAGE_TYPE'])?>;
    <?if (!empty($arResult["TIMETABLE"])):?>
    var tw_timetable=<?=CUtil::PhpToJSObject($arResult["TIMETABLE"])?>;
    <?endif;?>
</script>
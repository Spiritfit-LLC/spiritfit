
<div class="reports-block">
    <div class="reports-client__block">
        <div class="report_item">
            <div class="report-title">Количество ЧК:</div>
            <div class="report-value"><?=$arResult['CLIENT_COUNT']?></div>
        </div>
    </div>
    <div class="reports-client__block">
        <div class="report-block__title">По группам</div>
        <?foreach($arResult['LEVELS'] as $level):?>
            <div class="report_item">
                <div class="report-title"><?=$level['NAME']?>:</div>
                <div class="report-value"><?=$level['COUNT']?></div>
            </div>
        <?endforeach;?>
    </div>
    <div class="reports-client__block">
        <div class="report-block__title">ПЧК</div>
        <div class="report_item">
            <div class="report-title">Количество ПЧК:</div>
            <div class="report-value"><?=$arResult['PCHK_COUNT']?></div>
        </div>
    </div>
</div>

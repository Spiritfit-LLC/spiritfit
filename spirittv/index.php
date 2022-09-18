<?

	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
    use \Bitrix\Main\Page\Asset;

    $APPLICATION->SetTitle("SPIRIT.TV");
	$APPLICATION->SetPageProperty("title", "");
	$APPLICATION->SetPageProperty("description", "");
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/css/spirittv.css');
	
	$siteProperties = Utils::getInfo();
	
	if( empty($siteProperties["PROPERTIES"]["VIDEO_TRANSLATION_SHOW"]["VALUE"]) ) {
		GLOBAL $APPLICATION;
		
		$APPLICATION->RestartBuffer();
		
		require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/header.php';
		require $_SERVER['DOCUMENT_ROOT'].'/404.php';
		require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/footer.php';
		
		exit;
	}
	
	if( !empty($siteProperties["PROPERTIES"]["VIDEO_TRANSLATION_SHOW"]["VALUE"]) && !empty($siteProperties["PROPERTIES"]["VIDEO_TRANSLATION_LINK"]["VALUE"]) ):?>
        <section class="b-video-block">
            <div class="content-center">
                <iframe src="<?=$siteProperties["PROPERTIES"]["VIDEO_TRANSLATION_LINK"]["VALUE"]?>" width="100%" height="650px" scrolling="no" frameborder="0" allowfullscreen></iframe>
            </div>
        </section>
        <?
        $arFilter=[
            'ACTIVE'=>'Y',
            'IBLOCK_ID'=>Utils::GetIBlockIDBySID('SPIRITTV_SCHEDULE'),
        ];

        $rs_Section = CIBlockSection::GetList(
            array(),
            $arFilter,
            false,
            array('ID', 'NAME')
        );
        $ar_SectionList=[];
        while($ar_Section = $rs_Section->GetNext(true, false)) {
            $ar_SectionList[$ar_Section['ID']] = [
                'NAME'=>$ar_Section['NAME'],
            ];
        }

        $objects=[];
        $filter = ['SECTION_ID' => array_unique(array_keys($ar_SectionList)), 'ACTIVE'=>'Y'];
        $order = ['EXERCISE_TIME' => 'ASC'];

        $rows = CIBlockElement::GetList($order, $filter);
        while ($row = $rows->fetch()) {
            $row['PROPERTIES'] = [];
            $objects[$row['ID']] =& $row;
            $filter['IBLOCK_ID']=$row['IBLOCK_ID'];
            unset($row);
        }


        CIBlockElement::GetPropertyValuesArray($objects, $filter['IBLOCK_ID'], $filter);
        unset($rows, $filter, $order);


        foreach ($objects as $id=>$element){
            $ar_SectionList[$element['IBLOCK_SECTION_ID']]["FIELDS"][$element["PROPERTIES"]["EXERCISE_TIME"]["VALUE"]]=["NAME"=>$element["NAME"], "TIME"=>$element["PROPERTIES"]["EXERCISE_TIME"]["VALUE"]];
        }

        ksort($ar_SectionList[$element['IBLOCK_SECTION_ID']]["FIELDS"]);

        ?>
        <section class="b-spirittv-schedule">
            <div class="content-center">
                <h1 class="spirittv-h1"><?=$siteProperties["PROPERTIES"]["SPIRITTV_HEADER"]["VALUE"]?></h1>
                <?foreach ($ar_SectionList as $day):?>
                <?$w=100/ceil(count($day["FIELDS"])/2);?>
                <div class="spirittv-schedule__day">
                    <div class="day-name">
                        <span><?=$day["NAME"]?></span>
                    </div>
                    <div class="day-schedule">
                        <?for ($i=0; $i<count($day["FIELDS"]); $i+=2):?>
                            <div class="day-schedule__block"  style="width: 33%; flex:0 0 33%">
                                <div class="day-schedule__block-item">
                                    <span class="schedule-item__time"><?=array_values($day["FIELDS"])[$i]["TIME"]?></span>
                                    <span class="schedule-item__name"><?=array_values($day["FIELDS"])[$i]["NAME"]?></span>
                                </div>
                                <div class="day-schedule__block-item">
                                    <span class="schedule-item__time"><?=array_values($day["FIELDS"])[$i+1]["TIME"]?></span>
                                    <span class="schedule-item__name"><?=array_values($day["FIELDS"])[$i+1]["NAME"]?></span>
                                </div>
                            </div>
                        <?endfor;?>
                    </div>
                </div>
                <?endforeach;?>
            </div>
        </section>
    <?endif;?>
	
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
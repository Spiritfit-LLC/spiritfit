<?
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
	
	$APPLICATION->SetTitle("SPIRIT.TV");
	$APPLICATION->SetPageProperty("title", "");
	$APPLICATION->SetPageProperty("description", "");
	
	$siteProperties = Utils::getInfo();
	
	if( empty($siteProperties["PROPERTIES"]["VIDEO_TRANSLATION_SHOW"]["VALUE"]) ) {
		GLOBAL $APPLICATION;
		
		$APPLICATION->RestartBuffer();
		
		require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/header.php';
		require $_SERVER['DOCUMENT_ROOT'].'/404.php';
		require $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/footer.php';
		
		exit;
	}
	
	if( !empty($siteProperties["PROPERTIES"]["VIDEO_TRANSLATION_SHOW"]["VALUE"]) && !empty($siteProperties["PROPERTIES"]["VIDEO_TRANSLATION_LINK"]["VALUE"]) ) {
		?>
			<section class="b-video-block">
    			<div class="content-center">
					<iframe src="<?=$siteProperties["PROPERTIES"]["VIDEO_TRANSLATION_LINK"]["VALUE"]?>" width="100%" height="650px" scrolling="no" frameborder="0" allowfullscreen></iframe>
				</div>
			</section>
		<?
	}
	
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>
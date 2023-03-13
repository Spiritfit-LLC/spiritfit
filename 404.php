<?
define('HIDE_SLIDER', true);
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
if (!defined("TITLE_404")){
    $title = "Страница не найдена";
}
else{
    $APPLICATION->SetTitle(TITLE_404);
}

?>
<? if(defined("H1_HIDE")){ ?>
	<div class="content-center">
        <div class="b-page__heading-inner" style="padding-left:0">
			<h1 class="b-page__title <?if (defined('HIDE_SLIDER')) echo "black"?> page-404"><?=$APPLICATION->ShowTitle(false)?></h1>
		</div>
	</div>
<? } ?>

<script>
	$(document).ready(function() {
		dataLayerSend('404Error', location.href, document.referrer||'no-referrer', true);
	})
</script>

	
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
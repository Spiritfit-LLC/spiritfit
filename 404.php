<?
define('HIDE_SLIDER', true);
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Страница не найдена");
?>
<? if(defined("H1_HIDE")){ ?>
	<div class="content-center">
        <div class="b-page__heading-inner">
			<h1 class="b-page__title"><?=$APPLICATION->ShowTitle(false)?></h1>
		</div>
	</div>
<? } ?>

<script>
	$(document).ready(function() {
		dataLayerSend('404Error', location.href, document.referrer||'no-referrer', true);
	})
</script>

	
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
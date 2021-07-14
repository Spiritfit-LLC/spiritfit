<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); 
$settings = Utils::getInfo();
?>

    </main>
</div>
<footer class="footer">
    <a class="footer__inst" href="<?= $settings["PROPERTIES"]["LINK_INSTAGRAM"]["VALUE"] ?>" target="_blank">
        <div class="footer__inst-icon"></div>
        <div class="footer__inst-text">instagram</div>
    </a>
    <div class="footer__btn btn">Обратная связь</div>
</footer>
</div>

<?$APPLICATION->IncludeComponent(
    "custom:popup.callback", 
    "", 
    array(
        
    ),
    false
);?>
<script async src="https://www.youtube.com/iframe_api"></script>
<script src="/local/templates/spiritfit/js/youtubeready.js"></script>
<script style="display:none;">
	function current_domain() {
		var domain = document.location.href;
		domain = domain.substr(domain.indexOf('//')+2);
		return domain.substr(0,domain.indexOf('/'));
	}
	
	function getCookie(name) {
		var cookie = " " + document.cookie;
		var search = " " + name + "=";
		var setStr = null;
		var offset = 0;
		var end = 0;
		if (cookie.length > 0) {
			offset = cookie.indexOf(search);
			if (offset != -1) {
				offset += search.length;
				end = cookie.indexOf(";", offset)
				if (end == -1) {
					end = cookie.length;
				}
				setStr = unescape(cookie.substring(offset, end));
			}
		}
		return(setStr);
	}
</script>
</body>

</html>
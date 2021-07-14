<?

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оплата");
?>
<?php if (isset($_GET['success']) && $_GET['success'] == "true"): ?>
    <h1 style="color: #fff;">Оплата успешно произведена</h1>
<?php endif;
    if (isset($_GET['success']) && $_GET['success'] == "false"): ?>
    <h1 style="color: #fff;">Произошла ошибка при оплате</h1>
<?php endif; ?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
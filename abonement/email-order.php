<?php
session_start();

if (!$_SESSION['INVOICE_ID']):?>
    <?php
    $url =$_SERVER['REQUEST_URI'];
    $parts = parse_url($url);
    parse_str($parts['query'], $query);

    //GET параметры
    $orderParams=[
        'INVOICE_ID'=>$query['InvoiceID']
    ];

    foreach ($orderParams as $key=>$value)
    {
        if ($value==null){
            //Оповещение о некорректных данных
            print_r(['INCORRECT_PARAMS::', $orderParams]);
            return;
        }
    }

    if (is_array($orderParams['INVOICE_ID']) || is_object($orderParams['INVOICE_ID']))
    {
        foreach ($orderParams['INVOICE_ID'] as $char){
            if (!is_numeric($char)){
                //Оповещение о некорректных данных
                print_r(['INCORRECT_PARAMS::', $orderParams]);
                return;
            }
        }
    }
    $_SESSION['INVOICE_ID']=$orderParams['INVOICE_ID'];
    header("Location: ".$parts['path']);
    exit();?>
<?endif;?>

<?php
use \Bitrix\Main\Page\Asset;
define('BREADCRUMB_H1_ABSOLUTE', true);
define('HIDE_SLIDER', true);
define('H1_HIDE', true);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

Asset::getInstance()->addString('<script src="https://widget.cloudpayments.ru/bundles/cloudpayments.js"></script>');
?>

<?php
$INVOICE_ID=$_SESSION['INVOICE_ID'];
session_destroy();
?>



<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
?>

<?
    $adminPagePath = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/outcode.quiz/admin/result_page.php";
    if( file_exists($adminPagePath) ) {
	    include_once $adminPagePath;
    }
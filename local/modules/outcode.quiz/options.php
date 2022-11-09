<?
    $adminPagePath = $_SERVER["DOCUMENT_ROOT"] . "/local/modules/outcode.quiz/admin/result_page.php";
    if( file_exists($adminPagePath) ) {
	    include_once $adminPagePath;
    }
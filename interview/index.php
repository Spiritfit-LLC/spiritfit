<?
if (!empty($_GET['interviewid'])){
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: ".'/interview/'.$_GET['interviewid'].'/');
    exit();
}

header("HTTP/1.1 301 Moved Permanently");
header("Location: /");
exit();
?>
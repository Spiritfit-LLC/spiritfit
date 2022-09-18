<?

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

use \Bitrix\Main\Page\Asset;
use \Bitrix\Main\Loader;

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <?
    Asset::getInstance()->addString('<meta name="robots" content="noindex, follow" />');
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . "/vendor/jquery/jquery.min.js");

    $APPLICATION->ShowHead();
    ?>
    <style>
        * {box-sizing: border-box}
        body {font-family: Verdana, sans-serif; margin:0; overflow: hidden}
        .mySlides {
            display: none;
            width: 100vw;
            height: 100vh;
            background-size: cover;
            background-position: 50% 50%;
            background-repeat: no-repeat;
        }
        img {vertical-align: middle;}

        /* Slideshow container */
        .slideshow-container {
            max-width: 100vw;
            position: relative;
            margin: auto;
        }

        /* Next & previous buttons */
        .prev, .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            width: auto;
            padding: 16px;
            margin-top: -22px;
            color: white;
            font-weight: bold;
            font-size: 18px;
            transition: 0.6s ease;
            border-radius: 0 3px 3px 0;
        }

        /* Position the "next button" to the right */
        .next {
            right: 0;
            border-radius: 3px 0 0 3px;
        }

        /* On hover, add a black background color with a little bit see-through */
        .prev:hover, .next:hover {
            background-color: rgba(0,0,0,0.8);
        }

        /* Caption text */
        .text {
            color: #f2f2f2;
            font-size: 15px;
            padding: 8px 12px;
            position: absolute;
            bottom: 8px;
            width: 100%;
            text-align: center;
        }

        /* Number text (1/3 etc) */
        .numbertext {
            color: #f2f2f2;
            font-size: 12px;
            padding: 8px 12px;
            position: absolute;
            top: 0;
        }

        /* The dots/bullets/indicators */
        .dot {
            cursor: pointer;
            height: 15px;
            width: 15px;
            margin: 0 2px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
            transition: background-color 0.6s ease;
        }

        .active, .dot:hover {
            background-color: #717171;
        }

        /* Fading animation */
        .fade {
            -webkit-animation-name: fade;
            -webkit-animation-duration: 1.5s;
            animation-name: fade;
            animation-duration: 1.5s;
        }

        @-webkit-keyframes fade {
            from {opacity: .4}
            to {opacity: 1}
        }

        @keyframes fade {
            from {opacity: .4}
            to {opacity: 1}
        }

        /* On smaller screens, decrease text size */
        @media only screen and (max-width: 300px) {
            .prev, .next,.text {font-size: 11px}
        }
    </style>

</head>
<body>

<?
Loader::IncludeModule("iblock");
$IBLOCK_ID=Utils::GetIBlockIDBySID("reception-slider");
if (empty($_GET["ID"])){
    $rs = CIBlockElement::GetList (
        Array("RAND" => "ASC"),
        Array("IBLOCK_ID" => $IBLOCK_ID),
        false,
        Array ("nTopCount" => 1)
    );
}
else{
    if (is_numeric($_GET["ID"])){
        $ELEMENT_ID=$_GET["ID"];
    }
    else{
        $ELEMENT_ID=Utils::GetIBlockElementIDBySID($_GET["ID"]);
    }
    $arFilter = Array("IBLOCK_ID"=>$IBLOCK_ID, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "ID"=>$ELEMENT_ID);
    $rs = CIBlockElement::GetList(Array(), $arFilter);
}

if($ob = $rs->GetNextElement())
{
    $arProps = $ob->GetProperties();
    if (!is_array($arProps["PHOTO"]["VALUE"])){
        $PHOTOS=[$arProps["PHOTO"]["VALUE"]];
        $DESC=[$arProps["PHOTO"]["DESCRIPTION"]];
    }
    else{
        $PHOTOS=$arProps["PHOTO"]["VALUE"];
        $DESC=$arProps["PHOTO"]["DESCRIPTION"];
    }
    $DELAY=$arProps["DELAY"]["VALUE"];
}
$COUNT=count($PHOTOS);
?>
<div class="slideshow-container">
    <?
    $index=1;
    foreach ($PHOTOS as $PHOTO):
    ?>
        <div class="mySlides fade" style="background-image: url('<?=CFile::GetPath($PHOTO)?>')">
            <div class="numbertext"><?=$index?> / <?=$COUNT?></div>
<!--            <img src="--><?//=CFile::GetPath($PHOTO)?><!--" style="width:100%">-->
            <div class="text"><?=$DESC[$index-1]?></div>
        </div>
        <?$index++?>
    <?endforeach;?>

    <a class="prev" onclick="plusSlides(-1)">❮</a>
    <a class="next" onclick="plusSlides(1)">❯</a>

</div>

<script>
    var slideIndex = 1;
    showSlides(slideIndex);

    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function showSlides(n) {
        var i;
        var slides = document.getElementsByClassName("mySlides");
        if (n > slides.length) {slideIndex = 1}
        if (n < 1) {slideIndex = slides.length}
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }

        slides[slideIndex-1].style.display = "block";
    }

    setInterval(function(){
        showSlides(slideIndex += 1)
    }, <?=$DELAY*1000?>);
</script>
</body>


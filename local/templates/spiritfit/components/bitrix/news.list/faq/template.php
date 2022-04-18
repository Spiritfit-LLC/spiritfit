<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$arInfoProps = Utils::getInfo()['PROPERTIES'];
?>
<?$ogImage = CFile::GetPath($arInfoProps['OG_IMG']['VALUE']);?>
<div class="faq">
    <div class="faq__aside">
        <h1 class="faq__aside-heading">Вопросы и ответы</h1>
        <div class="faq__aside-buttons">
            <div class="faq__aside-buttons-caret"></div>
            <?
            $count = 1;
            if($arResult["ACTIVE_SECTION"]){
                $section = $arResult["SECTIONS"][$arResult["ACTIVE_SECTION"]];?>
                <div id="seo-div" hidden="true"
	                data-title="<?=$section['SEO']['SECTION_META_TITLE']?>" 
	                data-description="<?=$section['SEO']['SECTION_META_DESCRIPTION']?>" 
                    data-keywords="<?=$section['SEO']['SECTION_META_KEYWORDS']?>"
                    data-image="<?=$ogImage?>"></div>
                <div class="faq__aside-btn js-pjax-link <?=($arResult["ACTIVE_SECTION"] == $section["ID"]) ? "faq__aside-btn--active" : ""?> <?=(!$arResult["ACTIVE_SECTION"] &&  $count == 1) ? "faq__aside-btn--active" : ""?> btn btn--white" data-faq="<?=$count?>" data-href="<?=$section["SECTION_PAGE_URL"] ?>"><?=$section["NAME"]?></div>
            <?}
            foreach($arResult["SECTIONS"] as $key => $section):
                if(($arResult["ACTIVE_SECTION"] && $key != $arResult["ACTIVE_SECTION"]) || !$arResult["ACTIVE_SECTION"]):?>
                <div class="faq__aside-btn js-pjax-link <?=($arResult["ACTIVE_SECTION"] == $section["ID"]) ? "faq__aside-btn--active" : ""?> <?=(!$arResult["ACTIVE_SECTION"] &&  $count == 1) ? "faq__aside-btn--active" : ""?> btn btn--white" data-faq="<?=$count?>" data-href="<?=$section["SECTION_PAGE_URL"] ?>"><?=$section["NAME"]?></div>
            <?  endif;
                $count++;
            endforeach;?>
        </div>
    </div>
    <div class="faq__main">
        <?//<a class="faq__main-close js-pjax-link" href="<?= $arResult["LIST_PAGE_URL"] ">?>
        <a class="faq__main-close js-pjax-lin" href="/">

            <div></div>
            <div></div>
        </a>
        <div class="faq__main-top">FAQ</div>
        <div class="faq__main-title"> <span>Вопросы и ответы</span></div>
        
        <?
        $count = 1;
        foreach($arResult["SECTIONS"] as $section):?>

        <div class="faq__main-content" data-faq="<?=$count?>" <?if($arResult["ACTIVE_SECTION"] == $section["ID"]){ echo 'style="display:block;"';}elseif(!$arResult["ACTIVE_SECTION"] &&  $count == 1){ echo 'style="display:block;"';}else{ echo 'style="display:none;"';}?>>
        <?foreach($section["ITEMS"] as $key => $arItem):?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            $soon = $arItem["PROPERTIES"]["SOON"]["VALUE"] ? true : false;
            ?>
                <div class="faq__block" >
                    <div class="faq__block-title"> <span><?=$arItem["NAME"]?></span></div>
                    <div class="faq__block-content">
                        <?=$arItem["~PREVIEW_TEXT"]?>
                    </div>
                </div>
            <?endforeach;?>
        </div>
        <?$count++;
        endforeach;?>
    </div>
</div>

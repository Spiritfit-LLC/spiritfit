<?
    if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

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
?>
    <script type="text/javascript">
        var quizComponentName = "<?=$arResult['COMPONENT_NAME']?>";
        var quizComponentSuccessMsg = "<?=GetMessage('QUIZ_QUESTION_SUCCESS')?>";
        var quizComponentExistMsg = "<?=GetMessage('QUIZ_QUESTION_EXISTS')?>";
    </script>
    <div class="question-wrapper">
        <?
            if( !empty($arResult['LINK_LOGIN']) ) {
                ?><div class="question-wrapper-login"><a class="button-outline" href="<?=$arResult['QUIZ_LOGIN']?>"><?=GetMessage('QUIZ_LOGIN')?></a></div><?
            } else if( empty($arResult['QUESTION']) ) {
                ?><div class="question-wrapper-empty"><?=GetMessage('QUIZ_QUESTION_EMPTY')?></div><?
            } else if( !empty($arResult['QUESTION']['IS_ANSWERED']) ) {
                ?>
                <div class="question-form">
                    <div class="question-form__title"><?=$arResult['QUESTION']['NAME']?></div>
                    <div class="success"><?=GetMessage('QUIZ_QUESTION_EXISTS')?> <?=$arResult['QUESTION']['IS_ANSWERED_SCORE']?></div>
                </div>
                <?
            } else {
                switch($arResult['QUESTION']['PROPERTIES']['TYPE']['VALUE']) {
                    case 'Text':
                        ?>
                        <form class="question-form" autocomplete="off" method="post" enctype="multipart/form-data" data-componentName="<?=$arResult['COMPONENT_NAME']?>">
                            <input type="hidden" name="COMPONENT_ID" value="<?=$arResult['COMPONENT_ID']?>">
                            <input type="hidden" name="QUESTION_ID" value="<?=$arResult['QUESTION']['ID']?>">
                            <input type="hidden" name="USER_ID" value="<?=arResult['USER_ID']?>">
                            <div class="question-form__title"><?=$arResult['QUESTION']['NAME']?></div>
                            <div class="question-form__error"></div>
                            <div class="question-form__item">
                                <div class="question-form__row">
                                    <input class="input input--text" type="text" placeholder="<?=GetMessage('QUIZ_QUESTION_ANSWER')?>" value="" name="ANSWER" min-length="1" max-length="100">
                                </div>
                                <div class="question-form__row">
                                    <input type="submit" class="button-outline" value="<?=GetMessage('QUIZ_QUESTION_SUBMIT')?>">
                                </div>
                            </div>
                        </form>
                        <?
                        break;
                    case 'Strings':
                        ?>
                        <form class="question-form" autocomplete="off" method="post" enctype="multipart/form-data" data-componentName="<?=$arResult['COMPONENT_NAME']?>">
                            <input type="hidden" name="COMPONENT_ID" value="<?=$arResult['COMPONENT_ID']?>">
                            <input type="hidden" name="QUESTION_ID" value="<?=$arResult['QUESTION']['ID']?>">
                            <input type="hidden" name="USER_ID" value="<?=arResult['USER_ID']?>">
                            <div class="question-form__title"><?=$arResult['QUESTION']['NAME']?></div>
                            <div class="question-form__error"></div>
                            <div class="question-form__item">
                                <? foreach($arResult['QUESTION']['PROPERTIES']['ANSWERS_STRING']['VALUE'] as $answer) { ?>
                                    <div class="question-form__row">
                                        <label class="input-label">
                                            <input class="input input--checkbox" type="radio" name="ANSWER" value="<?=$answer?>">
                                            <div class="input-label__text"><?=$answer?></div>
                                        </label>
                                    </div>
                                <? } ?>
                                <div class="question-form__row">
                                    <input type="submit" class="button-outline" value="<?=GetMessage('QUIZ_QUESTION_SUBMIT')?>">
                                </div>
                            </div>
                        </form>
                        <?
                        break;
                    case 'Images':
                        ?>
                        <form class="question-form" autocomplete="off" method="post" enctype="multipart/form-data" data-componentName="<?=$arResult['COMPONENT_NAME']?>">
                            <input type="hidden" name="COMPONENT_ID" value="<?=$arResult['COMPONENT_ID']?>">
                            <input type="hidden" name="QUESTION_ID" value="<?=$arResult['QUESTION']['ID']?>">
                            <input type="hidden" name="USER_ID" value="<?=arResult['USER_ID']?>">
                            <div class="question-form__title"><?=$arResult['QUESTION']['NAME']?></div>
                            <div class="question-form__error"></div>
                            <div class="question-form__item">
                                <? foreach($arResult['QUESTION']['PROPERTIES']['ANSWERS_IMAGE']['VALUE'] as $image) { ?>
                                    <div class="question-form__row">
                                        <label class="input-label">
                                            <input class="input input--checkbox" type="radio" name="ANSWER" value="<?=$image['ID']?>">
                                            <div class="input-label__image">
                                                <img src="<?=$image['SRC_RESIZED']?>" alt="<?=$arResult['QUESTION']['NAME']?>" title="<?=$arResult['QUESTION']['NAME']?>">
                                            </div>
                                        </label>
                                    </div>
                                <? } ?>
                                <div class="question-form__row">
                                    <input type="submit" class="button-outline" value="<?=GetMessage('QUIZ_QUESTION_SUBMIT')?>">
                                </div>
                            </div>
                        </form>
                        <?
                        break;
                }
            }
        ?>
    </div>
    <?
        if( !empty($arResult['RESULT_TABLE_USER']['QUESTIONS']) ) {
            ?>
            <div class="results-table">
                <div class="results-table__title"><?=GetMessage('QUIZ_QUESTION_RESULTS_USER')?></div>
                <div class="results-table-content two">
                    <div class="results-table__row">
                        <div class="results-table__cell"><?=GetMessage('QUIZ_TABLE_QUESTION')?></div><div class="results-table__cell"><?=GetMessage('QUIZ_TABLE_VALUE')?></div>
                    </div>
                    <? foreach($arResult['RESULT_TABLE_USER']['QUESTIONS'] as $question => $result) { ?>
                         <div class="results-table__row">
                             <div class="results-table__cell"><?=$question?></div><div class="results-table__cell"><?=$result['RESULT']?></div>
                         </div>
                    <? } ?>
                </div>
                <div class="results-table__all"><?=GetMessage('QUIZ_TABLE_ALL')?> <?=$arResult['RESULT_TABLE_USER']['TOTAL_RESULT']?></div>
            </div>
            <?
        }

        if( !empty($arResult['RESULT_TABLE']) ) {
            ?>
            <div class="results-table">
                <div class="results-table__title"><?=GetMessage('QUIZ_QUESTION_RESULTS')?></div>
                <div class="results-table-content two">
                    <div class="results-table__row">
                        <div class="results-table__cell"><?=GetMessage('QUIZ_TABLE_USER')?></div><div class="results-table__cell"><?=GetMessage('QUIZ_TABLE_VALUE')?></div>
                    </div>
                    <? $count = 0; ?>
                    <? foreach($arResult['RESULT_TABLE']['TOTAL_RESULT'] as $result) { ?>
                        <div class="results-table__row">
                            <div class="results-table__cell"><?=$result['LOGIN']?></div><div class="results-table__cell"><?=$result['VALUE']?></div>
                        </div>
                        <? $count += 1; if($count > 50) break; ?>
                    <? } ?>
                </div>
                <div class="results-table__subtitle"><?=GetMessage('QUIZ_TABLE_BYQ')?></div>
                <div class="results-table-content one">
                    <? foreach($arResult['RESULT_TABLE']['BY_QUESTIONS'] as $question => $results) { ?>
                        <div class="results-table__row">
                            <div class="results-table__cell">
                                <?=$question?>
                                <div class="results-table-content two">
                                    <div class="results-table__row">
                                        <div class="results-table__cell"><?=GetMessage('QUIZ_TABLE_USER')?></div><div class="results-table__cell"><?=GetMessage('QUIZ_TABLE_VALUE')?></div>
                                    </div>
                                    <? $count = 0; ?>
                                    <? foreach($results as $result) { ?>
                                        <div class="results-table__row">
                                            <div class="results-table__cell"><?=$result['LOGIN']?></div><div class="results-table__cell"><?=$result['VALUE']?></div>
                                        </div>
                                        <? $count += 1; if($count > 50) break; ?>
                                    <? } ?>
                                </div>
                            </div>
                        </div>
                    <? } ?>
                </div>
            </div>
            <?
        }

        if( !empty($arResult['LINK_GET_BONUS']) ) {
            ?>
            <div class="share-wrap">
                <a class="open-share-block" href="#open">
                    <svg width="18" height="18" viewbox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.70007 15V11.6C1.70007 8.83855 3.93865 6.59998 6.70007 6.59998H9.99237" stroke="black" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M10.2501 2.67921L16.1922 6.59999L10.2501 10.5208L10.2501 2.67921Z" stroke="black"></path>
                    </svg>
                    <span><?=GetMessage('QUIZ_SHARE')?></span>
                </a>
                <ul class="share" style="display: none;">
                    <li>
                        <a class="share__link share-copy" href="#copy_link" data-url="<?=$arResult['LINK_GET_BONUS']?>" data-title="<?=GetMessage('QUIZ_SHARE_TITLE')?>" data-description="">
                            <svg width="16" height="16" viewbox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.3333 6H7.33333C6.59695 6 6 6.59695 6 7.33333V13.3333C6 14.0697 6.59695 14.6667 7.33333 14.6667H13.3333C14.0697 14.6667 14.6667 14.0697 14.6667 13.3333V7.33333C14.6667 6.59695 14.0697 6 13.3333 6Z" stroke="black" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M3.33325 10H2.66659C2.31296 10 1.97382 9.85956 1.72378 9.60952C1.47373 9.35947 1.33325 9.02033 1.33325 8.66671V2.66671C1.33325 2.31309 1.47373 1.97395 1.72378 1.7239C1.97382 1.47385 2.31296 1.33337 2.66659 1.33337H8.66659C9.02021 1.33337 9.35935 1.47385 9.60939 1.7239C9.85944 1.97395 9.99992 2.31309 9.99992 2.66671V3.33337" stroke="black" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <span><?=GetMessage('QUIZ_SHARE_COPY')?></span>
                        </a>
                    </li>
                    <li>
                        <a class="share__link" href="#vk" data-url="<?=$arResult['LINK_GET_BONUS']?>" data-title="<?=GetMessage('QUIZ_SHARE_TITLE')?>" data-description="">
                            <svg width="18" height="18" viewbox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 18C13.9706 18 18 13.9706 18 9C18 4.02944 13.9706 0 9 0C4.02944 0 0 4.02944 0 9C0 13.9706 4.02944 18 9 18Z" fill="#1C1C1C"></path>
                                <path d="M14.0093 7.15632C14.0826 6.9219 14.0093 6.75 13.6624 6.75H12.5133C12.2209 6.75 12.0872 6.89871 12.0139 7.06205C12.0139 7.06205 11.4296 8.43274 10.6017 9.3215C10.3345 9.58011 10.2124 9.66178 10.0667 9.66178C9.9939 9.66178 9.88805 9.58011 9.88805 9.3457V7.15632C9.88805 6.87502 9.80369 6.75 9.56003 6.75H7.75435C7.572 6.75 7.46196 6.88006 7.46196 7.00408C7.46196 7.27025 7.87592 7.33175 7.91836 8.08188V9.70917C7.91836 10.0656 7.85182 10.1306 7.7051 10.1306C7.31577 10.1306 6.36839 8.75487 5.80615 7.18001C5.69663 6.87401 5.58607 6.7505 5.29263 6.7505H4.14404C3.8155 6.7505 3.75 6.89922 3.75 7.06255C3.75 7.35595 4.13933 8.80781 5.56354 10.728C6.51301 12.0392 7.84972 12.75 9.06748 12.75C9.7974 12.75 9.88753 12.5922 9.88753 12.32V11.3289C9.88753 11.0133 9.95722 10.9498 10.1883 10.9498C10.3581 10.9498 10.6505 11.0325 11.3322 11.6641C12.1108 12.4132 12.2397 12.7495 12.6773 12.7495H13.8259C14.1539 12.7495 14.3179 12.5917 14.2236 12.2802C14.1203 11.9701 13.7483 11.5189 13.2547 10.9851C12.9864 10.6806 12.5856 10.3529 12.4635 10.1891C12.2932 9.97786 12.3424 9.8846 12.4635 9.69707C12.464 9.69757 13.8636 7.80108 14.0093 7.15632Z" fill="white"></path>
                            </svg>
                            <span>ВКонтакте</span>
                        </a>
                    </li>
                    <li>
                        <a class="share__link" href="#fb" data-url="<?=$arResult['LINK_GET_BONUS']?>" data-title="<?=GetMessage('QUIZ_SHARE_TITLE')?>" data-description="">
                            <svg width="18" height="18" viewbox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 18C13.9706 18 18 13.9706 18 9C18 4.02944 13.9706 0 9 0C4.02944 0 0 4.02944 0 9C0 13.9706 4.02944 18 9 18Z" fill="#1C1C1C"></path>
                                <path d="M10.9536 9.51702L11.2077 7.90851H9.47177V6.87447C9.47177 6.41489 9.72581 5.99362 10.4879 5.99362H11.25V4.61489C11.25 4.61489 10.5302 4.5 9.85282 4.5C8.45565 4.5 7.52419 5.26596 7.52419 6.68298V7.90851H6V9.51702H7.56653V13.4234C7.8629 13.4617 8.20161 13.5 8.54032 13.5C8.87903 13.5 9.1754 13.4617 9.51411 13.4234V9.51702H10.9536Z" fill="white"></path>
                            </svg>
                            <span>Facebook</span>
                        </a>
                    </li>
                    <li>
                        <a class="share__link" href="#od" data-url="<?=$arResult['LINK_GET_BONUS']?>" data-title="<?=GetMessage('QUIZ_SHARE_TITLE')?>" data-description="">
                            <svg width="18" height="18" viewbox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 18C13.9706 18 18 13.9706 18 9C18 4.02944 13.9706 0 9 0C4.02944 0 0 4.02944 0 9C0 13.9706 4.02944 18 9 18Z" fill="#1C1C1C"></path>
                                <g clip-path="url(#clip0)">
                                    <path d="M8.4833 11.1459C7.66931 11.061 6.93537 10.8605 6.307 10.3691C6.22903 10.3079 6.14854 10.249 6.07739 10.1807C5.80235 9.91655 5.77464 9.61402 5.99227 9.30226C6.17845 9.03547 6.49098 8.9641 6.81583 9.11729C6.87873 9.14698 6.93867 9.18404 6.99607 9.22385C8.16714 10.0285 9.77588 10.0507 10.9513 9.26003C11.0678 9.17074 11.1923 9.09794 11.3366 9.06077C11.6171 8.98874 11.8787 9.09178 12.0293 9.33723C12.2013 9.61754 12.1991 9.89115 11.9871 10.1088C11.6621 10.4424 11.2711 10.6838 10.8368 10.8523C10.426 11.0115 9.97614 11.0917 9.53087 11.1449C9.59806 11.218 9.62973 11.254 9.67174 11.2962C10.2764 11.9037 10.8836 12.5086 11.4862 13.118C11.6916 13.3256 11.7344 13.583 11.6214 13.8245C11.4978 14.0885 11.2211 14.2621 10.9498 14.2435C10.7779 14.2316 10.6439 14.1461 10.5248 14.0261C10.0685 13.567 9.60367 13.1163 9.15664 12.6485C9.02655 12.5125 8.96398 12.5382 8.84917 12.6563C8.39016 13.1289 7.92367 13.5941 7.45442 14.0568C7.24372 14.2645 6.99299 14.3019 6.74864 14.1833C6.48889 14.0573 6.32361 13.7922 6.33636 13.5257C6.34516 13.3455 6.4338 13.2078 6.55751 13.0843C7.15586 12.4869 7.75255 11.8879 8.34947 11.2894C8.38895 11.2496 8.42579 11.2074 8.4833 11.1459Z" fill="white"></path>
                                    <path d="M8.9792 9.06473C7.52705 9.05978 6.3363 7.85594 6.34477 6.40149C6.35313 4.93097 7.54465 3.74594 9.01109 3.75001C10.4805 3.75397 11.6599 4.95605 11.6526 6.44184C11.6451 7.89333 10.4461 9.06979 8.9792 9.06473ZM10.2989 6.405C10.2964 5.68283 9.72128 5.10791 9.00054 5.10725C8.27375 5.10648 7.69344 5.69185 7.69905 6.42062C7.70444 7.14004 8.28452 7.71089 9.00724 7.70825C9.72777 7.70572 10.3014 7.12706 10.2989 6.405Z" fill="white"></path>
                                </g>
                                <defs>
                                    <clippath id="clip0" data-url="<?=$arResult['LINK_GET_BONUS']?>" data-title="<?=GetMessage('QUIZ_SHARE_TITLE')?>" data-description="">
                                        <rect width="10.5" height="10.5" fill="white" transform="translate(3.75 3.75)"></rect>
                                    </clippath>
                                </defs>
                            </svg>
                            <span>Одноклассники</span>
                        </a>
                    </li>
                    <li>
                        <a class="share__link" href="#tg" data-url="<?=$arResult['LINK_GET_BONUS']?>" data-title="<?=GetMessage('QUIZ_SHARE_TITLE')?>" data-description="">
                            <svg width="18" height="18" viewbox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 18C13.9706 18 18 13.9706 18 9C18 4.02944 13.9706 0 9 0C4.02944 0 0 4.02944 0 9C0 13.9706 4.02944 18 9 18Z" fill="#1C1C1C"></path>
                                <path d="M4.86668 8.41165L11.8505 5.65869C12.1747 5.53897 12.4578 5.73954 12.3527 6.24063L12.3533 6.24002L11.1642 11.9675C11.0761 12.3735 10.8401 12.4723 10.5099 12.281L8.69904 10.9165L7.82561 11.7768C7.72903 11.8755 7.64754 11.9588 7.46042 11.9588L7.58899 10.0748L10.9451 6.975C11.0912 6.84356 10.9125 6.7695 10.72 6.90033L6.5725 9.56998L4.78459 8.99976C4.39646 8.87387 4.38801 8.60296 4.86668 8.41165Z" fill="white"></path>
                            </svg>
                            <span>Telegram</span>
                        </a>
                    </li>
                    <li>
                        <a class="share__link" href="#tw" data-url="<?=$arResult['LINK_GET_BONUS']?>" data-title="<?=GetMessage('QUIZ_SHARE_TITLE')?>" data-description="">
                            <svg width="18" height="18" viewbox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 18C13.9706 18 18 13.9706 18 9C18 4.02944 13.9706 0 9 0C4.02944 0 0 4.02944 0 9C0 13.9706 4.02944 18 9 18Z" fill="#1C1C1C"></path>
                                <path d="M13.9933 5.91783C13.685 6.04112 13.3152 6.16441 13.007 6.22605C13.1919 6.22605 13.3768 5.91783 13.5001 5.79455C13.6234 5.60961 13.7467 5.42468 13.8083 5.17811V5.11646H13.7467C13.3768 5.30139 13.007 5.48633 12.5755 5.54797C12.5138 5.54797 12.5138 5.54797 12.5138 5.54797C12.4522 5.48633 12.4522 5.48633 12.3905 5.42468C12.2056 5.30139 12.0207 5.17811 11.8357 5.05482C11.5892 4.93153 11.2809 4.86989 10.9727 4.93153C10.6645 4.93153 10.4179 5.05482 10.1714 5.17811C9.92478 5.30139 9.6782 5.48633 9.49327 5.7329C9.30834 5.97948 9.18505 6.22605 9.12341 6.53427C9.06176 6.84249 9.06176 7.08907 9.12341 7.39729C9.12341 7.45893 9.1234 7.45893 9.06176 7.45893C7.45902 7.21235 6.10286 6.65756 4.99327 5.42468C4.93162 5.36304 4.93162 5.36304 4.86998 5.42468C4.37683 6.16441 4.6234 7.274 5.23984 7.82879C5.30149 7.89044 5.42478 7.95208 5.48642 8.07537C5.42478 8.07537 5.05491 8.01372 4.68505 7.82879C4.6234 7.82879 4.6234 7.82879 4.6234 7.89044C4.6234 7.95208 4.6234 8.01372 4.6234 8.13701C4.68505 8.87674 5.23984 9.55482 5.91792 9.80139C5.97957 9.86304 6.10286 9.86304 6.1645 9.86304C5.97957 9.92468 5.85628 9.92468 5.42478 9.86304C5.36313 9.86304 5.36313 9.86304 5.36313 9.92468C5.67135 10.8493 6.41108 11.0959 6.96587 11.2808C7.02752 11.2808 7.08916 11.2808 7.21244 11.2808C7.02751 11.5274 6.41108 11.774 6.10286 11.8356C5.54806 12.0206 4.99327 12.0822 4.43847 12.0206C4.37683 12.0206 4.31519 12.0206 4.31519 12.0206V12.0822C4.43847 12.1439 4.56176 12.2055 4.68505 12.2671C5.05491 12.4521 5.42478 12.637 5.79464 12.6987C7.82889 13.2535 10.0481 12.8219 11.5892 11.3425C12.7604 10.1713 13.1919 8.56852 13.1919 6.90413C13.1919 6.84249 13.2535 6.78085 13.3152 6.78085C13.6234 6.53427 13.87 6.2877 14.1166 5.97948C13.9933 6.04112 13.9933 5.97948 13.9933 5.91783Z" fill="white"></path>
                            </svg>
                            <span>Twitter</span>
                        </a>
                    </li>
                    <li>
                        <a class="share__link" href="#whatsapp" data-url="<?=$arResult['LINK_GET_BONUS']?>" data-title="<?=GetMessage('QUIZ_SHARE_TITLE')?>" data-description="">
                            <svg width="18" height="18" viewbox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 18C13.9706 18 18 13.9706 18 9C18 4.02944 13.9706 0 9 0C4.02944 0 0 4.02944 0 9C0 13.9706 4.02944 18 9 18Z" fill="#1C1C1C"></path>
                                <path d="M5.779,7.408c0.014-0.7,0.306-1.247,0.935-1.588 C7.135,5.592,7.6,5.658,7.935,6C8.14,6.21,8.337,6.44,8.486,6.692c0.266,0.444,0.176,0.86-0.214,1.199 C8.165,7.983,8.057,8.075,7.939,8.151c-0.112,0.07-0.123,0.15-0.052,0.244c0.228,0.304,0.432,0.633,0.697,0.899 C8.841,9.55,9.163,9.743,9.457,9.959c0.094,0.07,0.164,0.052,0.23-0.054C9.769,9.783,9.863,9.671,9.96,9.562 c0.326-0.376,0.731-0.454,1.181-0.221c0.306,0.159,0.561,0.385,0.776,0.647c0.354,0.441,0.3,1.015-0.13,1.486 c-0.507,0.557-1.143,0.675-1.849,0.528c-0.782-0.164-1.448-0.568-2.068-1.054c-0.933-0.729-1.586-1.663-1.965-2.785 C5.858,8.028,5.83,7.883,5.806,7.74C5.786,7.632,5.788,7.523,5.779,7.408z" fill="#FFFFFF"/>
                                <path d="M8.967,2.843c2.814,0.037,5.288,2.032,5.875,4.773 c0.694,3.239-1.373,6.458-4.603,7.148c-1.373,0.294-2.694,0.12-3.959-0.493c-0.07-0.034-0.169-0.046-0.246-0.028 c-0.869,0.208-1.735,0.423-2.604,0.635c-0.313,0.078-0.532-0.141-0.455-0.455c0.214-0.872,0.431-1.742,0.642-2.615 c0.015-0.065,0.01-0.147-0.02-0.206c-0.461-0.922-0.681-1.896-0.64-2.923c0.053-1.376,0.521-2.597,1.411-3.65 c0.874-1.034,1.97-1.714,3.295-2.002C8.091,2.934,8.532,2.902,8.967,2.843z M4.188,13.588c0.053,0,0.074,0.004,0.093-0.002 c0.658-0.159,1.316-0.319,1.973-0.484c0.145-0.034,0.271-0.008,0.401,0.062c1.007,0.531,2.075,0.699,3.189,0.478 c1.167-0.233,2.133-0.815,2.872-1.754c0.913-1.161,1.246-2.479,0.969-3.924C13.396,6.462,12.55,5.334,11.208,4.6 c-1.01-0.553-2.091-0.713-3.217-0.492C6.861,4.329,5.917,4.887,5.186,5.775c-0.81,0.983-1.188,2.118-1.11,3.399 c0.043,0.709,0.235,1.373,0.566,2.003c0.05,0.095,0.075,0.226,0.055,0.326c-0.074,0.36-0.173,0.715-0.261,1.073 C4.354,12.907,4.273,13.24,4.188,13.588z" fill="#FCFCFC"/>
                            </svg>
                            <span>WhatsApp</span>
                        </a>
                    </li>
                    <li>
                        <a class="share__link" href="#viber" data-url="<?=$arResult['LINK_GET_BONUS']?>" data-title="<?=GetMessage('QUIZ_SHARE_TITLE')?>" data-description="">
                            <svg width="18" height="18" viewbox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 18C13.9706 18 18 13.9706 18 9C18 4.02944 13.9706 0 9 0C4.02944 0 0 4.02944 0 9C0 13.9706 4.02944 18 9 18Z" fill="#1C1C1C"></path>
                                <path d="M7.236,6.777c0.06,0.536-0.342,0.754-0.661,1.016 c-0.172,0.142-0.277,0.29-0.19,0.521c0.501,1.322,1.34,2.315,2.705,2.802c0.333,0.118,0.467,0.051,0.705-0.253 c0.178-0.228,0.35-0.453,0.674-0.535c0.341-0.087,0.625,0,0.884,0.172c0.549,0.364,1.081,0.755,1.613,1.146 c0.312,0.229,0.413,0.721,0.234,1.097c-0.344,0.731-0.847,1.294-1.669,1.488c-0.096,0.024-0.209,0.03-0.301,0.002 c-0.999-0.303-1.919-0.777-2.812-1.307c-0.873-0.514-1.644-1.153-2.347-1.882c-0.682-0.707-1.24-1.504-1.698-2.365 c-0.348-0.656-0.625-1.347-0.938-2.02C3.221,6.193,3.29,5.751,3.554,5.336c0.349-0.552,0.824-0.945,1.469-1.128 c0.353-0.1,0.639,0.039,0.831,0.279c0.43,0.536,0.825,1.102,1.204,1.676C7.169,6.333,7.179,6.569,7.236,6.777z" fill="#FFFFFF"/>
                                <path d="M8.731,3.643c2.18,0.058,4.216,1.518,4.688,3.804 c0.111,0.535,0.128,1.089,0.18,1.636c0.024,0.262-0.121,0.373-0.368,0.404c-0.252,0.033-0.437-0.056-0.486-0.3 c-0.065-0.322-0.097-0.653-0.122-0.981c-0.066-0.832-0.405-1.538-0.931-2.173C11.1,5.316,10.343,4.871,9.447,4.66 c-0.34-0.08-0.691-0.099-1.035-0.15C8.04,4.453,7.972,4.366,8.001,3.974c0.022-0.293,0.234-0.333,0.471-0.332 C8.559,3.644,8.645,3.643,8.731,3.643z" fill="#FFFFFF"/>
                                <path d="M8.826,4.901c1.224,0.029,2.15,0.495,2.854,1.362 c0.403,0.5,0.59,1.108,0.648,1.746c0.02,0.211,0.014,0.426,0,0.638c-0.017,0.265-0.235,0.334-0.438,0.356 c-0.222,0.025-0.412-0.18-0.423-0.401c-0.048-0.803-0.235-1.545-0.873-2.11C10.177,6.123,9.69,5.928,9.152,5.838 C8.978,5.807,8.781,5.813,8.635,5.73C8.448,5.625,8.37,5.441,8.43,5.192C8.5,4.895,8.718,4.919,8.826,4.901z" fill="#FFFFFF"/>
                                <path d="M11.118,7.996c0.002,0.407-0.137,0.583-0.435,0.605 c-0.236,0.017-0.453-0.226-0.49-0.525c-0.091-0.402-0.345-0.944-0.96-0.97c-0.247-0.012-0.486-0.264-0.475-0.48 c0.016-0.289,0.192-0.454,0.509-0.44c0.888,0.071,1.65,0.794,1.773,1.472L11.118,7.996z" fill="#FFFFFF"/>
                            </svg>
                            <span>Viber</span>
                        </a>
                    </li>
                </ul>
            </div>
            <?
        }


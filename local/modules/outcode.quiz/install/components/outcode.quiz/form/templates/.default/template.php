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
    $isLastGrey = true;
?>
    <script type="text/javascript">
        var quizComponentName = "<?=$arResult['COMPONENT_NAME']?>";
        var quizComponentSuccessMsg = "<?=GetMessage('QUIZ_QUESTION_SUCCESS')?>";
        var quizComponentExistMsg = "<?=GetMessage('QUIZ_QUESTION_EXISTS')?>";
    </script>
    <div class="b-quiz-gradient blockitem block1">
        <div class="content-center">
            <div class="quiz-banner">
                <h1><?=$arParams['~BLOCK_TITLE']?></h1>
                <img src="<?=$templateFolder?>/images/logo.png" alt="<?=strip_tags($arParams['BLOCK_TITLE'])?>" title="<?=strip_tags($arParams['BLOCK_TITLE'])?>">
                <div class="description">
                    <?=$arParams['~BLOCK_DESCRIPTION']?>
                </div>
                <? if( !empty($arResult['LINK_LOGIN']) ) { ?>
<!--                    <div class="quiz-banner-login"><a class="button white" href="--><?//=$arParams['PERSONAL_PATH']?><!--"><span>--><?//=GetMessage('QUIZ_LOGIN')?><!--</span></a></div>-->
                        <div class="lk-auth-lite__container">
                            <div class="lk-auth-lite__type">
                                <div class="lk-auth-lite__type-item auth active">
                                    <span>Вход</span>
                                </div>
                                <div class="lk-auth-lite__type-item reg">
                                    <span>Регистрация</span>
                                </div>
                            </div>
                            <div class="lk-auth-lite__form auth active">
                                <form id="auth-lite-form">
                                    <input type="hidden" name="WEB_FORM_ID" value="<?=$arResult["FORM_FIELDS"]["AUTH"]['WEB_FORM_ID']?>">
                                    <input type="hidden" name="FORM_STEP" value="1">
                                    <?if (!empty($arResult["FORM_FIELDS"]["AUTH"]['ACTION'])):?>
                                        <input type="hidden" name="ACTION" value="<?=$arResult["FORM_FIELDS"]["AUTH"]['ACTION']?>">
                                    <?endif;?>
                                    <?foreach ($arResult["FORM_FIELDS"]["AUTH"]['FIELDS'] as $FIELD):?>
                                        <?if ($FIELD['TYPE']=='hidden'):?>
                                            <input type="hidden" value="<?=$FIELD['VALUE']?>" name="<?=$FIELD['NAME']?>">
                                            <?
                                            continue;
                                        endif;
                                        ?>
                                        <div class="lk-auth-lite__form-item <?if($FIELD['TYPE']=='radio') echo 'radio-item';?>
                                                           <?if($FIELD['TYPE']=='checkbox') echo 'checkbox-item';?>
                                                           <?if($FIELD['TYPE']=='SELECT') echo 'select-item';?>
                                                        <?if ($FIELD['TYPE']=='password') echo 'auth-password';?>"
                                            <?if ($FIELD['TYPE']=='password'):?> style="display: none"<?endif;?>>
                                            <?if ($FIELD['TYPE']!='checkbox'):?>
                                                <span class="lk-auth-lite__form-item-placeholder"><?=$FIELD['PLACEHOLDER']?></span>
                                            <?endif;?>
                                            <?if ($FIELD['TYPE']=='radio'):?>
                                                <div style="margin-top: 5px;">
                                                    <?for ($i=0; $i<count($FIELD['VALUE']); $i++):?>
                                                        <div class="input-radio-item-block">
                                                            <input
                                                                    class="lk-auth-lite__form-item-value input-radio-btn"
                                                                    type="<?=$FIELD['TYPE']?>"
                                                                    name="<?=$FIELD['NAME']?>"
                                                                    value="<?=$FIELD['VALUE'][$i]?>"
                                                                    id="<?=$FIELD['NAME'].'_'.$i?>"
                                                                <?if ($FIELD['REQUIRED']) echo 'required';?>
                                                                <?=$FIELD['PARAMS']?>>
                                                            <label for="<?=$FIELD['NAME'].'_'.$i?>"><?=$FIELD['VALUE_DESC'][$i]?></label>
                                                        </div>
                                                    <?endfor;?>
                                                </div>
                                            <?elseif ($FIELD['TYPE']=='SELECT'):?>
                                                <select class="input input--light input--select" name="<?=$FIELD['NAME']?>" autocomplete="off" <? if ($FIELD["REQUIRED"]) { ?>required="required"<? } ?> >
                                                    <? foreach ($FIELD['ITEMS'] as $CLUB):?>
                                                        <option value="<?=$CLUB["VALUE"]?>"><?=$CLUB["STRING"]?></option>
                                                    <? endforeach; ?>
                                                </select>
                                            <?else:?>
                                                <input
                                                        class="lk-auth-lite__form-item-value <?if ($FIELD['TYPE']=='checkbox') echo 'checkbox-input';?>
                                                                        <?if ($FIELD['TYPE']=='password') echo 'passwd-input'?>"
                                                        type="<?if ($FIELD['TYPE']=='date') echo 'text'; else echo $FIELD['TYPE'];?>"
                                                        name="<?=$FIELD['NAME']?>"
                                                        value="<?=$FIELD['VALUE']?>"
                                                        id="<?=$FIELD['NAME']?>"
                                                    <?if ($FIELD['REQUIRED']) echo 'required';?>
                                                    <?if ($FIELD['TYPE']=='date'):?> data-toggle="datepicker" <?endif;?>
                                                    <?if ($FIELD['TYPE']=='checkbox' && (int)$FIELD['VALUE']==1) echo 'checked'?>
                                                    <?if (!empty($FIELD['VALIDATOR'])){ echo $FIELD['VALIDATOR'];}?>
                                                    <?=$FIELD['PARAMS']?>
                                                >
                                                <?if ($FIELD['TYPE']=='checkbox'):?>
                                                    <label for="<?=$FIELD['NAME']?>"><?=$FIELD['PLACEHOLDER']?></label>
                                                <?endif;?>
                                            <?endif;?>
                                        </div>
                                    <?endforeach;?>
                                    <?if (!empty($arResult["FORM_FIELDS"]["AUTH"]['BTN_TEXT'])):?>
                                        <input type="submit" class="lk-auth-lite__form__submit button-outline" value="<?=$arResult["FORM_FIELDS"]["AUTH"]['BTN_TEXT']?>">
                                        <div class="escapingBallG-animation">
                                            <div id="escapingBall_1" class="escapingBallG"></div>
                                        </div>
                                    <?endif;?>

                                    <div class="form-submit-result-text"></div>
                                </form>
                            </div>
                            <div class="lk-auth-lite__form reg">
                                <form id="reg-lite-form">
                                    <input type="hidden" name="WEB_FORM_ID" value="<?=$arResult["FORM_FIELDS"]["REG"]['WEB_FORM_ID']?>">
                                    <input type="hidden" name="FORM_STEP" value="1">
                                    <?if (!empty($arResult["FORM_FIELDS"]["REG"]['ACTION'])):?>
                                        <input type="hidden" name="ACTION" value="<?=$arResult["FORM_FIELDS"]["REG"]['ACTION']?>">
                                    <?endif;?>
                                    <?foreach ($arResult["FORM_FIELDS"]["REG"]['FIELDS'] as $FIELD):?>
                                        <?if ($FIELD['TYPE']=='hidden'):?>
                                            <input type="hidden" value="<?=$FIELD['VALUE']?>" name="<?=$FIELD['NAME']?>">
                                            <?
                                            continue;
                                        endif;
                                        ?>
                                        <div class="lk-auth-lite__form-item <?if($FIELD['TYPE']=='radio') echo 'radio-item';?>
                                                           <?if($FIELD['TYPE']=='checkbox') echo 'checkbox-item';?>
                                                           <?if($FIELD['TYPE']=='SELECT') echo 'select-item';?>
                                                        <?if ($FIELD['TYPE']=='password') echo 'auth-password';?>"
                                            <?if ($FIELD['TYPE']=='password'):?> style="display: none"<?endif;?>>
                                            <?if ($FIELD['TYPE']!='checkbox'):?>
                                                <span class="lk-auth-lite__form-item-placeholder"><?=$FIELD['PLACEHOLDER']?></span>
                                            <?endif;?>
                                            <?if ($FIELD['TYPE']=='radio'):?>
                                                <div style="margin-top: 5px;">
                                                    <?for ($i=0; $i<count($FIELD['VALUE']); $i++):?>
                                                        <div class="input-radio-item-block">
                                                            <input
                                                                    class="lk-auth-lite__form-item-value input-radio-btn"
                                                                    type="<?=$FIELD['TYPE']?>"
                                                                    name="<?=$FIELD['NAME']?>"
                                                                    value="<?=$FIELD['VALUE'][$i]?>"
                                                                    id="<?=$FIELD['NAME'].'_'.$i?>"
                                                                <?if ($FIELD['REQUIRED']) echo 'required';?>
                                                                <?=$FIELD['PARAMS']?>>
                                                            <label for="<?=$FIELD['NAME'].'_'.$i?>"><?=$FIELD['VALUE_DESC'][$i]?></label>
                                                        </div>
                                                    <?endfor;?>
                                                </div>
                                            <?elseif ($FIELD['TYPE']=='SELECT'):?>
                                                <select class="input input--light input--select" name="<?=$FIELD['NAME']?>" autocomplete="off" <? if ($FIELD["REQUIRED"]) { ?>required="required"<? } ?> >
                                                    <? foreach ($FIELD['ITEMS'] as $CLUB):?>
                                                        <option value="<?=$CLUB["VALUE"]?>"><?=$CLUB["STRING"]?></option>
                                                    <? endforeach; ?>
                                                </select>
                                            <?else:?>
                                                <input
                                                        class="lk-auth-lite__form-item-value <?if ($FIELD['TYPE']=='checkbox') echo 'checkbox-input';?>
                                                                        <?if ($FIELD['TYPE']=='password') echo 'passwd-input'?>"
                                                        type="<?if ($FIELD['TYPE']=='date') echo 'text'; else echo $FIELD['TYPE'];?>"
                                                        name="<?=$FIELD['NAME']?>"
                                                        value="<?=$FIELD['VALUE']?>"
                                                        id="<?=$FIELD['NAME']?>"
                                                    <?if ($FIELD['REQUIRED']) echo 'required';?>
                                                    <?if ($FIELD['TYPE']=='date'):?> data-toggle="datepicker" <?endif;?>
                                                    <?if ($FIELD['TYPE']=='checkbox' && (int)$FIELD['VALUE']==1) echo 'checked'?>
                                                    <?if (!empty($FIELD['VALIDATOR'])){ echo $FIELD['VALIDATOR'];}?>
                                                    <?=$FIELD['PARAMS']?>
                                                >
                                                <?if ($FIELD['TYPE']=='checkbox'):?>
                                                    <label for="<?=$FIELD['NAME']?>"><?=$FIELD['PLACEHOLDER']?></label>
                                                <?endif;?>
                                            <?endif;?>
                                        </div>
                                    <?endforeach;?>
                                    <?if (!empty($arResult["FORM_FIELDS"]["REG"]['BTN_TEXT'])):?>
                                        <input type="submit" class="lk-auth-lite__form__submit button-outline" value="<?=$arResult["FORM_FIELDS"]["REG"]['BTN_TEXT']?>">
                                        <div class="escapingBallG-animation">
                                            <div id="escapingBall_1" class="escapingBallG"></div>
                                        </div>
                                    <?endif;?>

                                    <div class="form-submit-result-text"></div>
                                </form>
                            </div>
                        </div>
                <? } ?>
            </div>
        </div>
    </div>
    <div class="question-wrapper blockitem" <?=empty($arResult['QUESTION']) ? 'style="margin-bottom: 0;"' : ''?>>
        <div class="content-center">
            <? if( $arResult['EMAIL_WARNING'] ) {
                ?>
                <div class="question-form">
                    <div class="success no-email"><?=GetMessage('QUIZ_NO_EMAIL')?></div>
                </div>
                <?
            } ?>
            <?
            if( !empty($arResult['QUESTION']['IS_ANSWERED']) ) {
                $isLastGrey = false;
                ?>
                <div class="question-form">
                    <div class="question-form__info"><?=str_replace(['#CURRENT#', '#TOTAL#'], [$arResult['QUESTION']['POSITION']['CURRENT'], $arResult['QUESTION']['POSITION']['TOTAL']], GetMessage('QUIZ_QUESTION_POSITION'))?></div>
                    <div class="question-form__title"><?=$arResult['QUESTION']['NAME']?></div>
                    <div class="success"><?=GetMessage('QUIZ_QUESTION_EXISTS')?></div>
                </div>
                <?
            } else if( !empty($arResult['QUESTION']['PROPERTIES']['TYPE']['VALUE']) ) {
                $isLastGrey = false;
                switch($arResult['QUESTION']['PROPERTIES']['TYPE']['VALUE']) {
                    case 'Text':
                        ?>
                        <form class="question-form" autocomplete="off" method="post" enctype="multipart/form-data" data-componentName="<?=$arResult['COMPONENT_NAME']?>">
                            <input type="hidden" name="COMPONENT_ID" value="<?=$arResult['COMPONENT_ID']?>">
                            <input type="hidden" name="QUESTION_ID" value="<?=$arResult['QUESTION']['ID']?>">
                            <input type="hidden" name="USER_ID" value="<?=$arResult['USER_ID']?>">
                            <div class="question-form__info"><?=str_replace(['#CURRENT#', '#TOTAL#'], [$arResult['QUESTION']['POSITION']['CURRENT'], $arResult['QUESTION']['POSITION']['TOTAL']], GetMessage('QUIZ_QUESTION_POSITION'))?></div>
                            <div class="question-form__title"><?=$arResult['QUESTION']['NAME']?></div>
                            <div class="question-form__error"></div>
                            <div class="question-form__item">
                                <div class="question-form__row">
                                    <input class="input input--text" type="text" placeholder="<?=GetMessage('QUIZ_QUESTION_ANSWER')?>" value="" name="ANSWER" min-length="1" max-length="100">
                                </div>
                                <div class="question-form__row submit">
                                    <input type="submit" class="button gradient" value="<?=GetMessage('QUIZ_QUESTION_SUBMIT')?>">
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
                            <input type="hidden" name="USER_ID" value="<?=$arResult['USER_ID']?>">
                            <div class="question-form__info"><?=str_replace(['#CURRENT#', '#TOTAL#'], [$arResult['QUESTION']['POSITION']['CURRENT'], $arResult['QUESTION']['POSITION']['TOTAL']], GetMessage('QUIZ_QUESTION_POSITION'))?></div>
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
                                <div class="question-form__row submit">
                                    <input type="submit" class="button gradient" value="<?=GetMessage('QUIZ_QUESTION_SUBMIT')?>">
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
                            <input type="hidden" name="USER_ID" value="<?=$arResult['USER_ID']?>">
                            <div class="question-form__info"><?=str_replace(['#CURRENT#', '#TOTAL#'], [$arResult['QUESTION']['POSITION']['CURRENT'], $arResult['QUESTION']['POSITION']['TOTAL']], GetMessage('QUIZ_QUESTION_POSITION'))?></div>
                            <div class="question-form__title"><?=$arResult['QUESTION']['NAME']?></div>
                            <div class="question-form__item">
                                <div class="question-form__row image">
                                    <img src="<?=CFile::GetPath($arResult['QUESTION']['PROPERTIES']['ANSWERS_IMAGE']['~VALUE'])?>">
                                </div>
                                <div class="question-form__error"></div>
                                <div class="question-form__row">
                                    <input class="input input--text" type="text" placeholder="<?=GetMessage('QUIZ_QUESTION_ANSWER')?>" value="" name="ANSWER" min-length="1" max-length="100">
                                </div>
                                <div class="question-form__row submit">
                                    <input type="submit" class="button gradient" value="<?=GetMessage('QUIZ_QUESTION_SUBMIT')?>">
                                </div>
                            </div>
                        </form>
                        <?
                        break;
                }
            }
            ?>
        </div>
    </div>
    <?
        if( !empty($arResult['RESULT_TABLE_USER']['QUESTIONS']) || !empty($arResult['RESULT_TABLE']['TOTAL_RESULT']) ) {
            $isLastGrey = false;
            ?>
            <div class="quiz-answers blockitem">
                <div class="content-center">
                    <?
                    if( !empty($arResult['RESULT_TABLE_USER']['QUESTIONS']) ) {
                        ?>
                        <div class="results-table">
                            <div class="results-table__title">
                                <?=GetMessage('QUIZ_QUESTION_RESULTS_USER')?>
                                <span class="show-more hidden"><?=GetMessage('QUIZ_TABLE_MORE')?></span>
                            </div>
                            <div class="results-table-content two">
                                <div class="results-table__row">
                                    <div class="results-table__cell"><?=GetMessage('QUIZ_TABLE_QUESTION')?></div>
                                    <div class="results-table__cell"><?=GetMessage('QUIZ_TABLE_USER_VALUE')?><span class="notice">*</span></div>
                                </div>
                                <? $count = 0; ?>
                                <? foreach($arResult['RESULT_TABLE_USER']['QUESTIONS'] as $result) {?>
                                    <div class="results-table__row <?=$count>3 ? 'hidden' : ''?>">
                                        <div class="results-table__cell">
                                            <?=TruncateText($result['QUESTION'], 100)?>
                                            <div class="results-table__cell-description">
                                                <?if ($result["ANSWER"]!="QUIZ_BONUS"):?>
                                                <b><?=GetMessage('QUIZ_TABLE_QUESTION_CORRECT_ANSWER')?></b> <?=$result['CORRECT_ANSWER']?>
                                                <?endif?>
                                            </div>
                                        </div>
                                        <div class="results-table__cell"><?=$result['RESULT']?></div>
                                    </div>
                                    <? $count += 1; ?>
                                <? } ?>
                                <div class="results-table__row">
                                    <div class="results-table__cell"></div>
                                    <div class="results-table__cell">
                                        <div class="results-table__all"><?=GetMessage('QUIZ_TABLE_ALL')?> <?=$arResult['RESULT_TABLE_USER']['TOTAL_RESULT']?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="results-table-notice"><?=GetMessage('QUIZ_NOTICE')?></div>
                        <?
                    }
                    if( !empty($arResult['RESULT_TABLE']['TOTAL_RESULT']) ) {
                        ?>
                        <div class="results-table">
                            <div class="results-table__title">
                                <?=GetMessage('QUIZ_QUESTION_RESULTS')?>
                                <span class="show-more hidden"><?=GetMessage('QUIZ_TABLE_MORE')?></span>
                            </div>
                            <div class="results-table-content three">
                                <div class="results-table__row">
                                    <div class="results-table__cell"><?=GetMessage('QUIZ_TABLE_USER')?></div>
                                    <div class="results-table__cell"><?=GetMessage('QUIZ_TABLE_QUESTION_COUNT')?></div>
                                    <div class="results-table__cell"><?=GetMessage('QUIZ_TABLE_VALUE')?><span class="notice">*</span></div>
                                </div>
                                <? $count = 0; ?>
                                <? foreach($arResult['RESULT_TABLE']['TOTAL_RESULT'] as $result) { ?>
                                    <? $counter = 0; foreach( $arResult['RESULT_TABLE']['BY_QUESTIONS'][$result['USER_ID']] as $question ) { if( $question == "Бонус за регистрацию" ) continue; $counter += 1; } ?>
                                    <div class="results-table__row <?=$count>2 ? 'hidden' : ''?>">
                                        <div class="results-table__cell">
                                            <?if ($result["WINNER"]):?>
                                                🏆
                                            <?endif;?>
                                            <?=$result['LOGIN']?>
                                            <?if ($result["WINNER"]):?>
                                                <div>
                                                    <span class="quiz-winner">Победитель</span>
                                                </div>
                                            <?endif;?>
                                        </div>
                                        <div class="results-table__cell"><?=!empty($arResult['RESULT_TABLE']['BY_QUESTIONS'][$result['USER_ID']]) ? $counter : 0?></div>
                                        <div class="results-table__cell"><?=$result['VALUE']?></div>
                                    </div>
                                    <? $count += 1; if($count > 50) break; ?>
                                <? } ?>
                            </div>
                        </div>
                        <div class="results-table-notice"><?=GetMessage('QUIZ_NOTICE')?></div>
                        <?
                    }
                    ?>
                </div>
            </div>
            <?
        }
    ?>
    <?if(!empty($arParams['BLOCK_VIDEO_LINK'])) {
        ?>
        <div class="quiz-video-wrapper blockitem <?=$isLastGrey ? 'grey' : 'b-quiz-gradient'?>">
            <div class="content-center">
                <div class="question-form__info"><?=$arParams['BLOCK_VIDEO_TITLE']?></div>
                <div class="quiz-video">
                    <iframe width="100%" height="315" src="<?=$arParams['BLOCK_VIDEO_LINK']?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        </div>
        <?
    } ?>


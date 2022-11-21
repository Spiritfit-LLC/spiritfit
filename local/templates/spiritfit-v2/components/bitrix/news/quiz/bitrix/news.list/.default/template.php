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
?>
<div class="b-quiz-gradient blockitem block1">
    <img class="back1" src="<?=$templateFolder?>/images/top_back2.png" alt="Отмечаем день рождения" title="Отмечаем день рождения">
    <img class="back2" src="<?=$templateFolder?>/images/top_back1.png" alt="Отмечаем день рождения" title="Отмечаем день рождения">
    <img class="back3" src="<?=$templateFolder?>/images/top_back3.png" alt="Отмечаем день рождения" title="Отмечаем день рождения">
    <img class="back4" src="<?=$templateFolder?>/images/top_back4.png" alt="Отмечаем день рождения" title="Отмечаем день рождения">
    <div class="content-center">
        <div class="quiz-banner">
            <div class="h1">5 лет</div>
            <img src="<?=$templateFolder?>/images/logo.png" alt="Отмечаем день рождения" title="Отмечаем день рождения">
            <div class="description">
                <b>Отмечаем день рождения!</b>
                <p>
                    Мы решили, что уже достаточно прокачали ваше тело,
                    а теперь пора прокачать мозг! А ещё выиграть гарантированные подарки от нас и наших партнёров на
                </p>
                <b>5.000.000 &#8381;</b>
            </div>
            <?
            global $USER;
            if ($USER->IsAuthorized()){
                $LINK="/play/";
            }
            else{
                $LINK="/personal/";
            }

            ?>
            <div class="quiz-banner-login"><a class="button white" href="<?=$LINK?>"
                                              data-layer="true"
                                              data-layercategory="HappyBirthday5years"
                                              data-layeraction="clickGoToLKButton"
                                              data-layerlabel="С днем рождения"><span>Играть</span></a></div>
        </div>
    </div>
</div>
<? if(!empty($arParams['SHORT_VERSION']) && $arParams['SHORT_VERSION']=='N') {
    ?>
    <div class="blockitem block2 grey">
        <img class="back2" src="<?=$templateFolder?>/images/block2_img2.png" alt="Вы готовы" title="Вы готовы">
        <img class="back3" src="<?=$templateFolder?>/images/block2_img3.png" alt="Вы готовы" title="Вы готовы">
        <div class="content-center">
            <div class="text-block-wrapper" style="background-image: url(<?=$templateFolder?>/images/block2_back.png);">
                <img class="back1" src="<?=$templateFolder?>/images/block2_img1.png" alt="Вы готовы" title="Вы готовы">
                <div class="text-block">
                    <div class="block-title uppercase">Вы готовы?</div>
                    <ul class="block-list">
                        <li class="block-list-item">
                            <svg width="26" height="20" viewBox="0 0 26 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 7.28571L10.8261 15L23 3" stroke="url(#paint0_linear_424_157)" stroke-width="6"></path>
                                <defs>
                                    <linearGradient id="paint0_linear_424_157" x1="-10.913" y1="15" x2="28.7097" y2="-12.9631" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#E23834"></stop>
                                        <stop offset="1" stop-color="#7B27F0"></stop>
                                    </linearGradient>
                                </defs>
                            </svg>
                            Призовой фонд 5 000 000 р
                        </li>
                        <li class="block-list-item">
                            <svg width="26" height="20" viewBox="0 0 26 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 7.28571L10.8261 15L23 3" stroke="url(#paint0_linear_424_157)" stroke-width="6"></path>
                                <defs>
                                    <linearGradient id="paint0_linear_424_157" x1="-10.913" y1="15" x2="28.7097" y2="-12.9631" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#E23834"></stop>
                                        <stop offset="1" stop-color="#7B27F0"></stop>
                                    </linearGradient>
                                </defs>
                            </svg>
                            5 раундов
                        </li>
                        <li class="block-list-item">
                            <svg width="26" height="20" viewBox="0 0 26 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 7.28571L10.8261 15L23 3" stroke="url(#paint0_linear_424_157)" stroke-width="6"></path>
                                <defs>
                                    <linearGradient id="paint0_linear_424_157" x1="-10.913" y1="15" x2="28.7097" y2="-12.9631" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#E23834"></stop>
                                        <stop offset="1" stop-color="#7B27F0"></stop>
                                    </linearGradient>
                                </defs>
                            </svg>
                            150 вопросов
                        </li>
                        <li class="block-list-item">
                            <svg width="26" height="20" viewBox="0 0 26 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 7.28571L10.8261 15L23 3" stroke="url(#paint0_linear_424_157)" stroke-width="6"></path>
                                <defs>
                                    <linearGradient id="paint0_linear_424_157" x1="-10.913" y1="15" x2="28.7097" y2="-12.9631" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#E23834"></stop>
                                        <stop offset="1" stop-color="#7B27F0"></stop>
                                    </linearGradient>
                                </defs>
                            </svg>
                            Еженедельный розыгрыш главных призов
                        </li>
                        <li class="block-list-item">
                            <svg width="26" height="20" viewBox="0 0 26 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 7.28571L10.8261 15L23 3" stroke="url(#paint0_linear_424_157)" stroke-width="6"></path>
                                <defs>
                                    <linearGradient id="paint0_linear_424_157" x1="-10.913" y1="15" x2="28.7097" y2="-12.9631" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#E23834"></stop>
                                        <stop offset="1" stop-color="#7B27F0"></stop>
                                    </linearGradient>
                                </defs>
                            </svg>
                            Гарантированный приз каждому участнику
                        </li>
                        <a href="/upload/form/polozhenie_quiz.pdf" style="color: #484848;text-decoration: underline;font-size: 18px;">Положение о проведении мероприятия</a>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="slider blockitem block3">
        <div class="content-center">
            <? if(!empty($arResult['PRIZES'])) {
                ?>
                <div class="block-title uppercase">Каждую неделю 10 главных призов</div>
                <div class="block-description">
                    Которые получают первые 10 участников в турнирной таблице.<br>
                    Призы распределяются рандомно.
                </div>
                <div class="prise-slider">
                    <? foreach($arResult["PRIZES"] as $item) { ?>
                        <div class="prise-slider-item">
                            <?if (!empty($item["LINK"])):?>
                            <a class="prise-slider-link" href="<?=$item["LINK"]?>" target="_blank">
                                <?endif;?>
                                <span class="prise-slider-wrapper">
                                <div class="image">
                                    <img src="<?=$item['PICTURE']?>" alt="<?=$item['NAME']?>" title="<?=$item['NAME']?>">
                                </div>
                                <div class="description">
                                    <?=$item['NAME']?>
                                </div>
                            </span>
                                <?if (!empty($item["LINK"])):?>
                            </a>
                        <?endif;?>

                        </div>
                    <? } ?>
                </div>
                <?
            } ?>
            <div class="block-link">
                <a class="button gradient" href="<?=$LINK?>"
                   data-layer="true"
                   data-layercategory="HappyBirthday5years"
                   data-layeraction="clickGoToLKButton"
                   data-layerlabel="Играть!"><span>Играть!</span></a>
            </div>
            <? if(!empty($arResult["ITEMS"])) {
                ?>
                <div class="block-title uppercase">Гарантированные призы</div>
                <div class="block-description">
                    Для всех, кто не попал в 10
                </div>
                <div class="prise-slider">
                    <? foreach($arResult["ITEMS"] as $item) {
                        $previewPictureSrc = CFile::ResizeImageGet($item['PREVIEW_PICTURE'], array('width' => 282, 'height' => 252), BX_RESIZE_IMAGE_EXACT)['src'];
                        ?>
                        <div class="prise-slider-item">
                            <a class="prise-slider-wrapper" href="<?=$item['DETAIL_PAGE_URL']?>">
                                <div class="image">
                                    <img src="<?=$previewPictureSrc?>" alt="<?=$item['NAME']?>" title="<?=$item['NAME']?>">
                                    <span class="button gradient"><span>Выбрать</span></span>
                                </div>
                                <? if(!empty($item['PREVIEW_TEXT'])) { ?>
                                    <div class="description">
                                        <?=$item['PREVIEW_TEXT']?>
                                    </div>
                                <? } ?>
                            </a>
                        </div>
                        <?
                    } ?>
                </div>
                <div class="block-link">
                    <a class="button gradient" href="<?=$LINK?>"
                       data-layer="true"
                       data-layercategory="HappyBirthday5years"
                       data-layeraction="clickGoToLKButton"
                       data-layerlabel="Играть!"><span>Играть!</span></a>
                </div>
                <?
            }?>
        </div>
    </div>
    <div class="b-quiz-gradient blockitem block5">
        <img class="back1" src="<?=$templateFolder?>/images/block5_back1.png" alt="Вишенка на торте из призов и подарков" title="Вишенка на торте из призов и подарков">
        <img class="back2" src="<?=$templateFolder?>/images/block5_back2.png" alt="Вишенка на торте из призов и подарков" title="Вишенка на торте из призов и подарков">
        <div class="content-center">
            <div class="quiz-banner">
                <div class="h1">Вишенка на торте из призов и подарков</div>
                <img src="<?=$templateFolder?>/images/block5_img1.png" alt="Вишенка на торте из призов и подарков" title="Вишенка на торте из призов и подарков">
                <div class="description">
                    <p>
                        Все накопленные баллы в игре превращаются
                        в баллы программы лояльности Spirit.Fitness
                    </p>
                </div>
                <div class="quiz-banner-login"><a class="button white" href="<?=$LINK?>"
                                                  data-layer="true"
                                                  data-layercategory="HappyBirthday5years"
                                                  data-layeraction="clickGoToLKButton"
                                                  data-layerlabel="Играть!"><span>Играть!</span></a></div>
            </div>
        </div>
    </div>
    <div class="blockitem block6">
        <div class="content-center">
            <div class="text-block-wrapper" style="background-image: url(<?=$templateFolder?>/images/block6_back.png);">
                <img class="back1" src="<?=$templateFolder?>/images/block6_img1.png" alt="Вы готовы" title="Вы готовы">
                <div class="text-block">
                    <div class="block-title uppercase">Как пройдет квиз?</div>
                    <p>
                        Каждый день мы будем задавать вопросы. Задача участника — ответить правильно и быстрее остальных.
                    </p>
                    <p>
                        Тот, кто первый правильно ответит, получает 100 баллов, второй — 90, третий — 80 и так далее. В любом случае, за каждый правильный ответ участник получает 9 баллов.
                    </p>
                    <p>
                        Как еще получить баллы? Легко! Каждый может пригласить своих друзей, родственников и любимого тренера
                        в игру и получить за каждого 10 баллов.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="blockitem block7 grey">
        <div class="content-center">
            <div class="time-table table">
                <div class="table-cell">
                    <div class="result-table table">
                        <div class="table-cell"></div>
                        <div class="table-cell">
                            <div class="question">
                                12:00<br>
                                Время на ответ
                            </div>
                            <div class="answer">Правильный ответ</div>
                            <div class="result">Таблица с результатами</div>
                            <div class="question">
                                14:00<br>
                                Время на ответ
                            </div>
                            <div class="answer">Правильный ответ</div>
                            <div class="result">Таблица с результатами</div>
                            <div class="question">
                                16:00<br>
                                Время на ответ
                            </div>
                            <div class="answer">Правильный ответ</div>
                            <div class="result">Таблица с результатами</div>
                            <div class="question">
                                18:00<br>
                                Время на ответ
                            </div>
                            <div class="answer">Правильный ответ</div>
                            <div class="result">Таблица с результатами</div>
                            <div class="question">
                                20:00<br>
                                Время на ответ
                            </div>
                            <div class="answer">Правильный ответ</div>
                            <div class="result">Таблица с результатами</div>
                            <div class="all">Итоговая таблица за день</div>
                            <div class="all">Погладить кота и баиньки</div>
                        </div>
                    </div>
                </div>
                <div class="table-cell">
                    <div class="block-title uppercase">Расписание</div>
                    <div class="block-description">
                        <p>
                            В течение дня мы открываем 5 вопросов.<br>
                            Вопросы появляются каждые 2 часа с 12 до 20 вечера.
                        </p>
                        <p>
                            Ответить можно в течение часа, а вечером таблица с результатами.
                        </p>
                        <p>
                            Чтобы получать уведомления -  подпишитесь на наш канал.
                        </p>
                    </div>
                    <ul class="social-links">
                        <li>
                            <a href="https://t.me/spiritfitness_official">
                                <svg version="1.1" id="Слой_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"  width="57px" height="55.219px" viewBox="-20.5 -19.609 57 55.219" enable-background="new -20.5 -19.609 57 55.219"  xml:space="preserve">
                                    <path fill="#000" d="M8-19.609C-7.741-19.609-20.5-7.249-20.5,8c0,15.248,12.759,27.609,28.5,27.609c15.739,0,28.5-12.361,28.5-27.609
                                        C36.5-7.249,23.739-19.609,8-19.609z M21.21-0.833c-0.428,4.366-2.286,14.96-3.229,19.848c-0.4,2.073-1.187,2.766-1.948,2.832
                                        c-1.652,0.149-2.912-1.058-4.513-2.077c-2.51-1.594-3.928-2.585-6.363-4.137c-2.813-1.799-0.99-2.783,0.614-4.397
                                        c0.419-0.425,7.711-6.85,7.854-7.433c0.017-0.073,0.034-0.345-0.133-0.487c-0.17-0.145-0.414-0.096-0.591-0.056
                                        C12.651,3.314,8.644,5.881,0.88,10.957c-1.136,0.759-2.167,1.127-3.089,1.106c-1.018-0.021-2.977-0.557-4.431-1.016
                                        c-1.785-0.563-3.204-0.859-3.08-1.814c0.064-0.498,0.772-1.005,2.12-1.525C0.708,4.201,6.249,1.889,9.021,0.772
                                        c7.912-3.188,9.559-3.744,10.633-3.761c0.233-0.005,0.759,0.052,1.104,0.32c0.226,0.191,0.372,0.458,0.406,0.749
                                        C21.221-1.561,21.234-1.195,21.21-0.833L21.21-0.833z"/>
                                </svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="b-quiz-gradient blockitem block8">
        <div class="content-center">
            <div class="quiz-banner">
                <div class="description">
                    <p>
                        Участвуйте в онлайн игре Spirit.Квиз, выигрывайте по-настоящему ценные призы и просто веселитесь вместе с нами.
                    </p>
                    <p>
                        С днем рождения Spirit.Fitness!
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?
} ?>

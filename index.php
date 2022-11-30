<?
if (isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true') {
	require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
} else {
	require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
	$APPLICATION->SetPageProperty("description", "Современный, доступный фитнес-клуб рядом с вами 💥 Выгодные тарифы от 1700 ₽ 💵 с ежемесячной оплатой, бесплатная пробная тренировка 🔥 Запишитесь прямо сейчас!");
	$APPLICATION->SetPageProperty("title", "Spirit Fitness – сеть фитнес-клубов в Москве и Московской области с оплатой за месяц от 1700₽");
	
}
global $settings;

?>

<?if (!(empty($settings["PROPERTIES"]["UTP_MAIN_ICONS"]["VALUE"]))):?>
    <style>
        .b-screen{
            margin-bottom: 0!important;
        }
        .b-screen:after{
            content: none;
        }
        .content-center.map-title{
            margin-top: 30px;
        }
    </style>
<?php $utp_width=round(100/count($settings["PROPERTIES"]["UTP_MAIN_ICONS"]["VALUE"]), 2);?>
<div class="main-utp">
    <?for ($i=0; $i<count($settings["PROPERTIES"]["UTP_MAIN_ICONS"]["VALUE"]); $i++):?>
        <div class="main-utp__item" style="width: <?=$utp_width?>%; flex: 0 0 <?=$utp_width?>%">
            <div class="main-utp__item-icon" style='background-image: url("<?=CFile::GetPath($settings["PROPERTIES"]["UTP_MAIN_ICONS"]["VALUE"][$i])?>")'></div>
            <div class="main-utp__item-title">
                <span><?=htmlspecialcharsBack($settings["PROPERTIES"]["UTP_MAIN_ICONS"]['DESCRIPTION'][$i])?></span>
            </div>
            <?if (!empty($settings["PROPERTIES"]["UTP_MAIN_DESC"]["VALUE"][$i]["TEXT"])):?>
                <div class="main-utp__desc">
                    <?=htmlspecialcharsBack($settings["PROPERTIES"]["UTP_MAIN_DESC"]['VALUE'][$i]['TEXT'])?>
                </div>
            <?endif;?>
        </div>
    <?endfor;?>
</div>
<?endif;?>
<!--//ДЕЛАЕМ НА ВРЕМЯ ТРАНСФОРМАЦИИ-->
<div class="b-page__heading b-page__heading_absolute" style="top: 100px;">
<!--<div class="b-page__heading b-page__heading_absolute  ">-->
    <div class="content-center">
		<div class="b-page__heading-inner">
			<div class="b-breadcrumbs"></div>
			<h1 class="b-page__title">Сеть фитнес-клубов</h1>
        </div>
    </div>
</div>
<div class="content-center map-title">
    <div class="b-cards-slider__heading">
        <div class="b-cards-slider__title">
            <h2>Карта клубов</h2>
        </div>
	</div>
</div>
<? $APPLICATION->IncludeFile('/local/include/clubs-main.php'); ?>

<!-- Отзывы, Абонементы, Промо блоки -->
    <div class="content-center">
        <div class="promo-blocks">
            <div class="promo-blocks-item">
                <img src="<?=SITE_TEMPLATE_PATH?>/img/main/tpu_1.jpg" alt="" title="">
                <div class="promo-blocks-item__name">5 смартфонов Apple iPhone 14</div>
                <div class="promo-blocks-item__link">
                    <a href="/utp-test/" class="btn">Подробнее</a>
                </div>
            </div>
            <div class="promo-blocks-item">
                <img src="<?=SITE_TEMPLATE_PATH?>/img/main/tpu_2.jpg" alt="" title="">
                <div class="promo-blocks-item__name">5 часов Apple watch</div>
                <div class="promo-blocks-item__link">
                    <a href="/utp-test/" class="btn">Подробнее</a>
                </div>
            </div>
            <div class="promo-blocks-item">
                <img src="<?=SITE_TEMPLATE_PATH?>/img/main/tpu_3.jpg" alt="" title="">
                <div class="promo-blocks-item__name">5 абонементов на год в Spirit.Fitness</div>
                <div class="promo-blocks-item__link">
                    <a href="/utp-test/" class="btn">Подробнее</a>
                </div>
            </div>
            <div class="promo-blocks-item">
                <img src="<?=SITE_TEMPLATE_PATH?>/img/main/tpu_4.jpg" alt="" title="">
                <div class="promo-blocks-item__name">5 сертификатов на 20 000 рублей в FLOTARIUM</div>
                <div class="promo-blocks-item__link">
                    <a href="/utp-test/" class="btn">Подробнее</a>
                </div>
            </div>
            <div class="promo-blocks-item">
                <img src="<?=SITE_TEMPLATE_PATH?>/img/main/tpu_1.jpg" alt="" title="">
                <div class="promo-blocks-item__name">5 смартфонов Apple iPhone 14</div>
                <div class="promo-blocks-item__link">
                    <a href="/utp-test/" class="btn">Подробнее</a>
                </div>
            </div>
        </div>
        <div class="b-cards-slider__heading">
            <div class="b-cards-slider__title">
                <h2>Абонементы</h2>
            </div>
        </div>
        <div class="abonements-group">
            <div class="abonements-group-tabs">
                <a class="active" href="#main_tab1">Ежемесячно</a>
                <a href="#main_tab2">Студентам</a>
                <a href="#main_tab3">Дневной пропуск</a>
            </div>
            <div class="abonements-group-items">
                <div class="abonements-group-item active">
                    <div class="abonements-group-slider">
                        <div class="abonements-group-slider__item">
                            <div class="abonements-group-slider__item-img">
                                <img src="<?=SITE_TEMPLATE_PATH?>/img/main/abonements_1.jpg" alt="Тариф Максимум" title="Тариф Максимум">
                            </div>
                            <div class="abonements-group-slider__item-name">
                                <span>Тариф</span>
                                «Максимум»
                            </div>
                            <div class="abonements-group-slider__item-wrapper">
                                <div class="abonements-group-slider__item-month">
                                    <div class="description">Ежемесячный платеж</div>
                                    <div class="price">1990₽</div>
                                </div>
                                <div class="abonements-group-slider__item-try">
                                    <div class="description">Пробный месяц</div>
                                    <div class="price-old">4490₽</div>
                                    <div class="price">3490₽</div>
                                </div>
                                <div class="abonements-group-slider__item-promo">
                                    <a>
                                        <img src="<?=SITE_TEMPLATE_PATH?>/img/gift.svg" alt="" title="">
                                        Супер-Мега предложение
                                    </a>
                                </div>
                                <div class="abonements-group-slider__item-promo-date">
                                    Срок действия до 31.12.2022
                                </div>
                                <div class="abonements-group-slider__item-list">
                                    <div class="abonements-group-slider__item-list-item">доступ ко всем услугам клуба</div>
                                    <div class="abonements-group-slider__item-list-item">16500 бонусов от наших партнеров</div>
                                    <div class="abonements-group-slider__item-list-item">безлимитный доступ ко всем услугам</div>
                                    <div class="abonements-group-slider__item-list-item">тренажерный зал</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные групповые программы</div>
                                    <div class="abonements-group-slider__item-list-item">регулярный анализ тела InBody</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные программы тренировок в мобильном приложении</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатная персональная тренировка с тренером</div>
                                    <div class="abonements-group-slider__item-list-item">скидка на стартовый блок тренировок 20%</div>
                                    <div class="abonements-group-slider__item-list-item">финские сауны и хаммам</div>
                                    <div class="abonements-group-slider__item-list-item">шкафчик в раздевалке</div>
                                    <div class="abonements-group-slider__item-list-item">сейфовая ячейка</div>
                                </div>
                                <div class="abonements-group-slider__item-promo-buy">
                                    <a class="btn" href="#buy">Купить</a>
                                </div>
                            </div>
                        </div>
                        <div class="abonements-group-slider__item">
                            <div class="abonements-group-slider__item-img">
                                <img src="<?=SITE_TEMPLATE_PATH?>/img/main/abonements_2.jpg" alt="Тариф Максимум" title="Тариф Максимум">
                            </div>
                            <div class="abonements-group-slider__item-name">
                                <span>Тариф</span>
                                «Максимум»
                            </div>
                            <div class="abonements-group-slider__item-wrapper">
                                <div class="abonements-group-slider__item-month">
                                    <div class="description">Ежемесячный платеж</div>
                                    <div class="price">1990₽</div>
                                </div>
                                <div class="abonements-group-slider__item-try">
                                    <div class="description">Пробный месяц</div>
                                    <div class="price-old">4490₽</div>
                                    <div class="price">3490₽</div>
                                </div>
                                <div class="abonements-group-slider__item-promo">
                                    <a>
                                        <img src="<?=SITE_TEMPLATE_PATH?>/img/gift.svg" alt="" title="">
                                        Супер-Мега предложение
                                    </a>
                                </div>
                                <div class="abonements-group-slider__item-promo-date">
                                    Срок действия до 31.12.2022
                                </div>
                                <div class="abonements-group-slider__item-list">
                                    <div class="abonements-group-slider__item-list-item">доступ ко всем услугам клуба</div>
                                    <div class="abonements-group-slider__item-list-item">16500 бонусов от наших партнеров</div>
                                    <div class="abonements-group-slider__item-list-item">безлимитный доступ ко всем услугам</div>
                                    <div class="abonements-group-slider__item-list-item">тренажерный зал</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные групповые программы</div>
                                    <div class="abonements-group-slider__item-list-item">регулярный анализ тела InBody</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные программы тренировок в мобильном приложении</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатная персональная тренировка с тренером</div>
                                    <div class="abonements-group-slider__item-list-item">скидка на стартовый блок тренировок 20%</div>
                                    <div class="abonements-group-slider__item-list-item">финские сауны и хаммам</div>
                                    <div class="abonements-group-slider__item-list-item">шкафчик в раздевалке</div>
                                    <div class="abonements-group-slider__item-list-item">сейфовая ячейка</div>
                                </div>
                                <div class="abonements-group-slider__item-promo-buy">
                                    <a class="btn" href="#buy">Купить</a>
                                </div>
                            </div>
                        </div>
                        <div class="abonements-group-slider__item">
                            <div class="abonements-group-slider__item-img">
                                <img src="<?=SITE_TEMPLATE_PATH?>/img/main/abonements_3.jpg" alt="Тариф Максимум" title="Тариф Максимум">
                            </div>
                            <div class="abonements-group-slider__item-name">
                                <span>Тариф</span>
                                «Максимум»
                            </div>
                            <div class="abonements-group-slider__item-wrapper">
                                <div class="abonements-group-slider__item-month">
                                    <div class="description">Ежемесячный платеж</div>
                                    <div class="price">1990₽</div>
                                </div>
                                <div class="abonements-group-slider__item-try">
                                    <div class="description">Пробный месяц</div>
                                    <div class="price-old">4490₽</div>
                                    <div class="price">3490₽</div>
                                </div>
                                <div class="abonements-group-slider__item-promo">
                                    <a>
                                        <img src="<?=SITE_TEMPLATE_PATH?>/img/gift.svg" alt="" title="">
                                        Супер-Мега предложение
                                    </a>
                                </div>
                                <div class="abonements-group-slider__item-promo-date">
                                    Срок действия до 31.12.2022
                                </div>
                                <div class="abonements-group-slider__item-list">
                                    <div class="abonements-group-slider__item-list-item">доступ ко всем услугам клуба</div>
                                    <div class="abonements-group-slider__item-list-item">16500 бонусов от наших партнеров</div>
                                    <div class="abonements-group-slider__item-list-item">безлимитный доступ ко всем услугам</div>
                                    <div class="abonements-group-slider__item-list-item">тренажерный зал</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные групповые программы</div>
                                    <div class="abonements-group-slider__item-list-item">регулярный анализ тела InBody</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные программы тренировок в мобильном приложении</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатная персональная тренировка с тренером</div>
                                    <div class="abonements-group-slider__item-list-item">скидка на стартовый блок тренировок 20%</div>
                                    <div class="abonements-group-slider__item-list-item">финские сауны и хаммам</div>
                                    <div class="abonements-group-slider__item-list-item">шкафчик в раздевалке</div>
                                    <div class="abonements-group-slider__item-list-item">сейфовая ячейка</div>
                                </div>
                                <div class="abonements-group-slider__item-promo-buy">
                                    <a class="btn" href="#buy">Купить</a>
                                </div>
                            </div>
                        </div>
                        <div class="abonements-group-slider__item">
                            <div class="abonements-group-slider__item-img">
                                <img src="<?=SITE_TEMPLATE_PATH?>/img/main/abonements_4.jpg" alt="Тариф Максимум" title="Тариф Максимум">
                            </div>
                            <div class="abonements-group-slider__item-name">
                                <span>Тариф</span>
                                «Максимум»
                            </div>
                            <div class="abonements-group-slider__item-wrapper">
                                <div class="abonements-group-slider__item-month">
                                    <div class="description">Ежемесячный платеж</div>
                                    <div class="price">1990₽</div>
                                </div>
                                <div class="abonements-group-slider__item-try">
                                    <div class="description">Пробный месяц</div>
                                    <div class="price-old">4490₽</div>
                                    <div class="price">3490₽</div>
                                </div>
                                <div class="abonements-group-slider__item-promo">
                                    <a>
                                        <img src="<?=SITE_TEMPLATE_PATH?>/img/gift.svg" alt="" title="">
                                        Супер-Мега предложение
                                    </a>
                                </div>
                                <div class="abonements-group-slider__item-promo-date">
                                    Срок действия до 31.12.2022
                                </div>
                                <div class="abonements-group-slider__item-list">
                                    <div class="abonements-group-slider__item-list-item">доступ ко всем услугам клуба</div>
                                    <div class="abonements-group-slider__item-list-item">16500 бонусов от наших партнеров</div>
                                    <div class="abonements-group-slider__item-list-item">безлимитный доступ ко всем услугам</div>
                                    <div class="abonements-group-slider__item-list-item">тренажерный зал</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные групповые программы</div>
                                    <div class="abonements-group-slider__item-list-item">регулярный анализ тела InBody</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные программы тренировок в мобильном приложении</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатная персональная тренировка с тренером</div>
                                    <div class="abonements-group-slider__item-list-item">скидка на стартовый блок тренировок 20%</div>
                                    <div class="abonements-group-slider__item-list-item">финские сауны и хаммам</div>
                                    <div class="abonements-group-slider__item-list-item">шкафчик в раздевалке</div>
                                    <div class="abonements-group-slider__item-list-item">сейфовая ячейка</div>
                                </div>
                                <div class="abonements-group-slider__item-promo-buy">
                                    <a class="btn" href="#buy">Купить</a>
                                </div>
                            </div>
                        </div>
                        <div class="abonements-group-slider__item">
                            <div class="abonements-group-slider__item-img">
                                <img src="<?=SITE_TEMPLATE_PATH?>/img/main/abonements_2.jpg" alt="Тариф Максимум" title="Тариф Максимум">
                            </div>
                            <div class="abonements-group-slider__item-name">
                                <span>Тариф</span>
                                «Максимум»
                            </div>
                            <div class="abonements-group-slider__item-wrapper">
                                <div class="abonements-group-slider__item-month">
                                    <div class="description">Ежемесячный платеж</div>
                                    <div class="price">1990₽</div>
                                </div>
                                <div class="abonements-group-slider__item-try">
                                    <div class="description">Пробный месяц</div>
                                    <div class="price-old">4490₽</div>
                                    <div class="price">3490₽</div>
                                </div>
                                <div class="abonements-group-slider__item-promo">
                                    <a>
                                        <img src="<?=SITE_TEMPLATE_PATH?>/img/gift.svg" alt="" title="">
                                        Супер-Мега предложение
                                    </a>
                                </div>
                                <div class="abonements-group-slider__item-promo-date">
                                    Срок действия до 31.12.2022
                                </div>
                                <div class="abonements-group-slider__item-list">
                                    <div class="abonements-group-slider__item-list-item">доступ ко всем услугам клуба</div>
                                    <div class="abonements-group-slider__item-list-item">16500 бонусов от наших партнеров</div>
                                    <div class="abonements-group-slider__item-list-item">безлимитный доступ ко всем услугам</div>
                                    <div class="abonements-group-slider__item-list-item">тренажерный зал</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные групповые программы</div>
                                    <div class="abonements-group-slider__item-list-item">регулярный анализ тела InBody</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные программы тренировок в мобильном приложении</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатная персональная тренировка с тренером</div>
                                    <div class="abonements-group-slider__item-list-item">скидка на стартовый блок тренировок 20%</div>
                                    <div class="abonements-group-slider__item-list-item">финские сауны и хаммам</div>
                                    <div class="abonements-group-slider__item-list-item">шкафчик в раздевалке</div>
                                    <div class="abonements-group-slider__item-list-item">сейфовая ячейка</div>
                                </div>
                                <div class="abonements-group-slider__item-promo-buy">
                                    <a class="btn" href="#buy">Купить</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="abonements-group-item">
                    <div class="abonements-group-slider">
                        <div class="abonements-group-slider__item">
                            <div class="abonements-group-slider__item-img">
                                <img src="<?=SITE_TEMPLATE_PATH?>/img/main/abonements_1.jpg" alt="Тариф Максимум" title="Тариф Максимум">
                            </div>
                            <div class="abonements-group-slider__item-name">
                                <span>Тариф</span>
                                «Максимум»
                            </div>
                            <div class="abonements-group-slider__item-wrapper">
                                <div class="abonements-group-slider__item-month">
                                    <div class="description">Ежемесячный платеж</div>
                                    <div class="price">1990₽</div>
                                </div>
                                <div class="abonements-group-slider__item-try">
                                    <div class="description">Пробный месяц</div>
                                    <div class="price-old">4490₽</div>
                                    <div class="price">3490₽</div>
                                </div>
                                <div class="abonements-group-slider__item-promo">
                                    <a>
                                        <img src="<?=SITE_TEMPLATE_PATH?>/img/gift.svg" alt="" title="">
                                        Супер-Мега предложение
                                    </a>
                                </div>
                                <div class="abonements-group-slider__item-promo-date">
                                    Срок действия до 31.12.2022
                                </div>
                                <div class="abonements-group-slider__item-list">
                                    <div class="abonements-group-slider__item-list-item">доступ ко всем услугам клуба</div>
                                    <div class="abonements-group-slider__item-list-item">16500 бонусов от наших партнеров</div>
                                    <div class="abonements-group-slider__item-list-item">безлимитный доступ ко всем услугам</div>
                                    <div class="abonements-group-slider__item-list-item">тренажерный зал</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные групповые программы</div>
                                    <div class="abonements-group-slider__item-list-item">регулярный анализ тела InBody</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные программы тренировок в мобильном приложении</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатная персональная тренировка с тренером</div>
                                    <div class="abonements-group-slider__item-list-item">скидка на стартовый блок тренировок 20%</div>
                                    <div class="abonements-group-slider__item-list-item">финские сауны и хаммам</div>
                                    <div class="abonements-group-slider__item-list-item">шкафчик в раздевалке</div>
                                    <div class="abonements-group-slider__item-list-item">сейфовая ячейка</div>
                                </div>
                                <div class="abonements-group-slider__item-promo-buy">
                                    <a class="btn" href="#buy">Купить</a>
                                </div>
                            </div>
                        </div>
                        <div class="abonements-group-slider__item">
                            <div class="abonements-group-slider__item-img">
                                <img src="<?=SITE_TEMPLATE_PATH?>/img/main/abonements_2.jpg" alt="Тариф Максимум" title="Тариф Максимум">
                            </div>
                            <div class="abonements-group-slider__item-name">
                                <span>Тариф</span>
                                «Максимум»
                            </div>
                            <div class="abonements-group-slider__item-wrapper">
                                <div class="abonements-group-slider__item-month">
                                    <div class="description">Ежемесячный платеж</div>
                                    <div class="price">1990₽</div>
                                </div>
                                <div class="abonements-group-slider__item-try">
                                    <div class="description">Пробный месяц</div>
                                    <div class="price-old">4490₽</div>
                                    <div class="price">3490₽</div>
                                </div>
                                <div class="abonements-group-slider__item-promo">
                                    <a>
										<img src="<?=SITE_TEMPLATE_PATH?>/img/gift.svg" alt="" title="">
                                        Супер-Мега предложение
                                    </a>
                                </div>
                                <div class="abonements-group-slider__item-promo-date">
                                    Срок действия до 31.12.2022
                                </div>
                                <div class="abonements-group-slider__item-list">
                                    <div class="abonements-group-slider__item-list-item">доступ ко всем услугам клуба</div>
                                    <div class="abonements-group-slider__item-list-item">16500 бонусов от наших партнеров</div>
                                    <div class="abonements-group-slider__item-list-item">безлимитный доступ ко всем услугам</div>
                                    <div class="abonements-group-slider__item-list-item">тренажерный зал</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные групповые программы</div>
                                    <div class="abonements-group-slider__item-list-item">регулярный анализ тела InBody</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные программы тренировок в мобильном приложении</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатная персональная тренировка с тренером</div>
                                    <div class="abonements-group-slider__item-list-item">скидка на стартовый блок тренировок 20%</div>
                                    <div class="abonements-group-slider__item-list-item">финские сауны и хаммам</div>
                                    <div class="abonements-group-slider__item-list-item">шкафчик в раздевалке</div>
                                    <div class="abonements-group-slider__item-list-item">сейфовая ячейка</div>
                                </div>
                                <div class="abonements-group-slider__item-promo-buy">
                                    <a class="btn" href="#buy">Купить</a>
                                </div>
                            </div>
                        </div>
                        <div class="abonements-group-slider__item">
                            <div class="abonements-group-slider__item-img">
                                <img src="<?=SITE_TEMPLATE_PATH?>/img/main/abonements_3.jpg" alt="Тариф Максимум" title="Тариф Максимум">
                            </div>
                            <div class="abonements-group-slider__item-name">
                                <span>Тариф</span>
                                «Максимум»
                            </div>
                            <div class="abonements-group-slider__item-wrapper">
                                <div class="abonements-group-slider__item-month">
                                    <div class="description">Ежемесячный платеж</div>
                                    <div class="price">1990₽</div>
                                </div>
                                <div class="abonements-group-slider__item-try">
                                    <div class="description">Пробный месяц</div>
                                    <div class="price-old">4490₽</div>
                                    <div class="price">3490₽</div>
                                </div>
                                <div class="abonements-group-slider__item-promo">
                                    <a>
                                        <img src="<?=SITE_TEMPLATE_PATH?>/img/gift.svg" alt="" title="">
                                        Супер-Мега предложение
                                    </a>
                                </div>
                                <div class="abonements-group-slider__item-promo-date">
                                    Срок действия до 31.12.2022
                                </div>
                                <div class="abonements-group-slider__item-list">
                                    <div class="abonements-group-slider__item-list-item">доступ ко всем услугам клуба</div>
                                    <div class="abonements-group-slider__item-list-item">16500 бонусов от наших партнеров</div>
                                    <div class="abonements-group-slider__item-list-item">безлимитный доступ ко всем услугам</div>
                                    <div class="abonements-group-slider__item-list-item">тренажерный зал</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные групповые программы</div>
                                    <div class="abonements-group-slider__item-list-item">регулярный анализ тела InBody</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные программы тренировок в мобильном приложении</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатная персональная тренировка с тренером</div>
                                    <div class="abonements-group-slider__item-list-item">скидка на стартовый блок тренировок 20%</div>
                                    <div class="abonements-group-slider__item-list-item">финские сауны и хаммам</div>
                                    <div class="abonements-group-slider__item-list-item">шкафчик в раздевалке</div>
                                    <div class="abonements-group-slider__item-list-item">сейфовая ячейка</div>
                                </div>
                                <div class="abonements-group-slider__item-promo-buy">
                                    <a class="btn" href="#buy">Купить</a>
                                </div>
                            </div>
                        </div>
                        <div class="abonements-group-slider__item">
                            <div class="abonements-group-slider__item-img">
                                <img src="<?=SITE_TEMPLATE_PATH?>/img/main/abonements_4.jpg" alt="Тариф Максимум" title="Тариф Максимум">
                            </div>
                            <div class="abonements-group-slider__item-name">
                                <span>Тариф</span>
                                «Максимум»
                            </div>
                            <div class="abonements-group-slider__item-wrapper">
                                <div class="abonements-group-slider__item-month">
                                    <div class="description">Ежемесячный платеж</div>
                                    <div class="price">1990₽</div>
                                </div>
                                <div class="abonements-group-slider__item-try">
                                    <div class="description">Пробный месяц</div>
                                    <div class="price-old">4490₽</div>
                                    <div class="price">3490₽</div>
                                </div>
                                <div class="abonements-group-slider__item-promo">
                                    <a>
                                        <img src="<?=SITE_TEMPLATE_PATH?>/img/gift.svg" alt="" title="">
                                        Супер-Мега предложение
                                    </a>
                                </div>
                                <div class="abonements-group-slider__item-promo-date">
                                    Срок действия до 31.12.2022
                                </div>
                                <div class="abonements-group-slider__item-list">
                                    <div class="abonements-group-slider__item-list-item">доступ ко всем услугам клуба</div>
                                    <div class="abonements-group-slider__item-list-item">16500 бонусов от наших партнеров</div>
                                    <div class="abonements-group-slider__item-list-item">безлимитный доступ ко всем услугам</div>
                                    <div class="abonements-group-slider__item-list-item">тренажерный зал</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные групповые программы</div>
                                    <div class="abonements-group-slider__item-list-item">регулярный анализ тела InBody</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные программы тренировок в мобильном приложении</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатная персональная тренировка с тренером</div>
                                    <div class="abonements-group-slider__item-list-item">скидка на стартовый блок тренировок 20%</div>
                                    <div class="abonements-group-slider__item-list-item">финские сауны и хаммам</div>
                                    <div class="abonements-group-slider__item-list-item">шкафчик в раздевалке</div>
                                    <div class="abonements-group-slider__item-list-item">сейфовая ячейка</div>
                                </div>
                                <div class="abonements-group-slider__item-promo-buy">
                                    <a class="btn" href="#buy">Купить</a>
                                </div>
                            </div>
                        </div>
                        <div class="abonements-group-slider__item">
                            <div class="abonements-group-slider__item-img">
                                <img src="<?=SITE_TEMPLATE_PATH?>/img/main/abonements_2.jpg" alt="Тариф Максимум" title="Тариф Максимум">
                            </div>
                            <div class="abonements-group-slider__item-name">
                                <span>Тариф</span>
                                «Максимум»
                            </div>
                            <div class="abonements-group-slider__item-wrapper">
                                <div class="abonements-group-slider__item-month">
                                    <div class="description">Ежемесячный платеж</div>
                                    <div class="price">1990₽</div>
                                </div>
                                <div class="abonements-group-slider__item-try">
                                    <div class="description">Пробный месяц</div>
                                    <div class="price-old">4490₽</div>
                                    <div class="price">3490₽</div>
                                </div>
                                <div class="abonements-group-slider__item-promo">
                                    <a>
                                        <img src="<?=SITE_TEMPLATE_PATH?>/img/gift.svg" alt="" title="">
                                        Супер-Мега предложение
                                    </a>
                                </div>
                                <div class="abonements-group-slider__item-promo-date">
                                    Срок действия до 31.12.2022
                                </div>
                                <div class="abonements-group-slider__item-list">
                                    <div class="abonements-group-slider__item-list-item">доступ ко всем услугам клуба</div>
                                    <div class="abonements-group-slider__item-list-item">16500 бонусов от наших партнеров</div>
                                    <div class="abonements-group-slider__item-list-item">безлимитный доступ ко всем услугам</div>
                                    <div class="abonements-group-slider__item-list-item">тренажерный зал</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные групповые программы</div>
                                    <div class="abonements-group-slider__item-list-item">регулярный анализ тела InBody</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные программы тренировок в мобильном приложении</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатная персональная тренировка с тренером</div>
                                    <div class="abonements-group-slider__item-list-item">скидка на стартовый блок тренировок 20%</div>
                                    <div class="abonements-group-slider__item-list-item">финские сауны и хаммам</div>
                                    <div class="abonements-group-slider__item-list-item">шкафчик в раздевалке</div>
                                    <div class="abonements-group-slider__item-list-item">сейфовая ячейка</div>
                                </div>
                                <div class="abonements-group-slider__item-promo-buy">
                                    <a class="btn" href="#buy">Купить</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="abonements-group-item">
                    <div class="abonements-group-slider">
                        <div class="abonements-group-slider__item">
                            <div class="abonements-group-slider__item-img">
                                <img src="<?=SITE_TEMPLATE_PATH?>/img/main/abonements_1.jpg" alt="Тариф Максимум" title="Тариф Максимум">
                            </div>
                            <div class="abonements-group-slider__item-name">
                                <span>Тариф</span>
                                «Максимум»
                            </div>
                            <div class="abonements-group-slider__item-wrapper">
                                <div class="abonements-group-slider__item-month">
                                    <div class="description">Ежемесячный платеж</div>
                                    <div class="price">1990₽</div>
                                </div>
                                <div class="abonements-group-slider__item-try">
                                    <div class="description">Пробный месяц</div>
                                    <div class="price-old">4490₽</div>
                                    <div class="price">3490₽</div>
                                </div>
                                <div class="abonements-group-slider__item-promo">
                                    <a>
                                        <img src="<?=SITE_TEMPLATE_PATH?>/img/gift.svg" alt="" title="">
                                        Супер-Мега предложение
                                    </a>
                                </div>
                                <div class="abonements-group-slider__item-promo-date">
                                    Срок действия до 31.12.2022
                                </div>
                                <div class="abonements-group-slider__item-list">
                                    <div class="abonements-group-slider__item-list-item">доступ ко всем услугам клуба</div>
                                    <div class="abonements-group-slider__item-list-item">16500 бонусов от наших партнеров</div>
                                    <div class="abonements-group-slider__item-list-item">безлимитный доступ ко всем услугам</div>
                                    <div class="abonements-group-slider__item-list-item">тренажерный зал</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные групповые программы</div>
                                    <div class="abonements-group-slider__item-list-item">регулярный анализ тела InBody</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные программы тренировок в мобильном приложении</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатная персональная тренировка с тренером</div>
                                    <div class="abonements-group-slider__item-list-item">скидка на стартовый блок тренировок 20%</div>
                                    <div class="abonements-group-slider__item-list-item">финские сауны и хаммам</div>
                                    <div class="abonements-group-slider__item-list-item">шкафчик в раздевалке</div>
                                    <div class="abonements-group-slider__item-list-item">сейфовая ячейка</div>
                                </div>
                                <div class="abonements-group-slider__item-promo-buy">
                                    <a class="btn" href="#buy">Купить</a>
                                </div>
                            </div>
                        </div>
                        <div class="abonements-group-slider__item">
                            <div class="abonements-group-slider__item-img">
                                <img src="<?=SITE_TEMPLATE_PATH?>/img/main/abonements_2.jpg" alt="Тариф Максимум" title="Тариф Максимум">
                            </div>
                            <div class="abonements-group-slider__item-name">
                                <span>Тариф</span>
                                «Максимум»
                            </div>
                            <div class="abonements-group-slider__item-wrapper">
                                <div class="abonements-group-slider__item-month">
                                    <div class="description">Ежемесячный платеж</div>
                                    <div class="price">1990₽</div>
                                </div>
                                <div class="abonements-group-slider__item-try">
                                    <div class="description">Пробный месяц</div>
                                    <div class="price-old">4490₽</div>
                                    <div class="price">3490₽</div>
                                </div>
                                <div class="abonements-group-slider__item-promo">
                                    <a>
                                        <img src="<?=SITE_TEMPLATE_PATH?>/img/gift.svg" alt="" title="">
                                        Супер-Мега предложение
                                    </a>
                                </div>
                                <div class="abonements-group-slider__item-promo-date">
                                    Срок действия до 31.12.2022
                                </div>
                                <div class="abonements-group-slider__item-list">
                                    <div class="abonements-group-slider__item-list-item">доступ ко всем услугам клуба</div>
                                    <div class="abonements-group-slider__item-list-item">16500 бонусов от наших партнеров</div>
                                    <div class="abonements-group-slider__item-list-item">безлимитный доступ ко всем услугам</div>
                                    <div class="abonements-group-slider__item-list-item">тренажерный зал</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные групповые программы</div>
                                    <div class="abonements-group-slider__item-list-item">регулярный анализ тела InBody</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные программы тренировок в мобильном приложении</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатная персональная тренировка с тренером</div>
                                    <div class="abonements-group-slider__item-list-item">скидка на стартовый блок тренировок 20%</div>
                                    <div class="abonements-group-slider__item-list-item">финские сауны и хаммам</div>
                                    <div class="abonements-group-slider__item-list-item">шкафчик в раздевалке</div>
                                    <div class="abonements-group-slider__item-list-item">сейфовая ячейка</div>
                                </div>
                                <div class="abonements-group-slider__item-promo-buy">
                                    <a class="btn" href="#buy">Купить</a>
                                </div>
                            </div>
                        </div>
                        <div class="abonements-group-slider__item">
                            <div class="abonements-group-slider__item-img">
                                <img src="<?=SITE_TEMPLATE_PATH?>/img/main/abonements_3.jpg" alt="Тариф Максимум" title="Тариф Максимум">
                            </div>
                            <div class="abonements-group-slider__item-name">
                                <span>Тариф</span>
                                «Максимум»
                            </div>
                            <div class="abonements-group-slider__item-wrapper">
                                <div class="abonements-group-slider__item-month">
                                    <div class="description">Ежемесячный платеж</div>
                                    <div class="price">1990₽</div>
                                </div>
                                <div class="abonements-group-slider__item-try">
                                    <div class="description">Пробный месяц</div>
                                    <div class="price-old">4490₽</div>
                                    <div class="price">3490₽</div>
                                </div>
                                <div class="abonements-group-slider__item-promo">
                                    <a>
                                        <img src="<?=SITE_TEMPLATE_PATH?>/img/gift.svg" alt="" title="">
                                        Супер-Мега предложение
                                    </a>
                                </div>
                                <div class="abonements-group-slider__item-promo-date">
                                    Срок действия до 31.12.2022
                                </div>
                                <div class="abonements-group-slider__item-list">
                                    <div class="abonements-group-slider__item-list-item">доступ ко всем услугам клуба</div>
                                    <div class="abonements-group-slider__item-list-item">16500 бонусов от наших партнеров</div>
                                    <div class="abonements-group-slider__item-list-item">безлимитный доступ ко всем услугам</div>
                                    <div class="abonements-group-slider__item-list-item">тренажерный зал</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные групповые программы</div>
                                    <div class="abonements-group-slider__item-list-item">регулярный анализ тела InBody</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные программы тренировок в мобильном приложении</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатная персональная тренировка с тренером</div>
                                    <div class="abonements-group-slider__item-list-item">скидка на стартовый блок тренировок 20%</div>
                                    <div class="abonements-group-slider__item-list-item">финские сауны и хаммам</div>
                                    <div class="abonements-group-slider__item-list-item">шкафчик в раздевалке</div>
                                    <div class="abonements-group-slider__item-list-item">сейфовая ячейка</div>
                                </div>
                                <div class="abonements-group-slider__item-promo-buy">
                                    <a class="btn" href="#buy">Купить</a>
                                </div>
                            </div>
                        </div>
                        <div class="abonements-group-slider__item">
                            <div class="abonements-group-slider__item-img">
                                <img src="<?=SITE_TEMPLATE_PATH?>/img/main/abonements_4.jpg" alt="Тариф Максимум" title="Тариф Максимум">
                            </div>
                            <div class="abonements-group-slider__item-name">
                                <span>Тариф</span>
                                «Максимум»
                            </div>
                            <div class="abonements-group-slider__item-wrapper">
                                <div class="abonements-group-slider__item-month">
                                    <div class="description">Ежемесячный платеж</div>
                                    <div class="price">1990₽</div>
                                </div>
                                <div class="abonements-group-slider__item-try">
                                    <div class="description">Пробный месяц</div>
                                    <div class="price-old">4490₽</div>
                                    <div class="price">3490₽</div>
                                </div>
                                <div class="abonements-group-slider__item-promo">
                                    <a>
                                        <img src="<?=SITE_TEMPLATE_PATH?>/img/gift.svg" alt="" title="">
                                        Супер-Мега предложение
                                    </a>
                                </div>
                                <div class="abonements-group-slider__item-promo-date">
                                    Срок действия до 31.12.2022
                                </div>
                                <div class="abonements-group-slider__item-list">
                                    <div class="abonements-group-slider__item-list-item">доступ ко всем услугам клуба</div>
                                    <div class="abonements-group-slider__item-list-item">16500 бонусов от наших партнеров</div>
                                    <div class="abonements-group-slider__item-list-item">безлимитный доступ ко всем услугам</div>
                                    <div class="abonements-group-slider__item-list-item">тренажерный зал</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные групповые программы</div>
                                    <div class="abonements-group-slider__item-list-item">регулярный анализ тела InBody</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные программы тренировок в мобильном приложении</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатная персональная тренировка с тренером</div>
                                    <div class="abonements-group-slider__item-list-item">скидка на стартовый блок тренировок 20%</div>
                                    <div class="abonements-group-slider__item-list-item">финские сауны и хаммам</div>
                                    <div class="abonements-group-slider__item-list-item">шкафчик в раздевалке</div>
                                    <div class="abonements-group-slider__item-list-item">сейфовая ячейка</div>
                                </div>
                                <div class="abonements-group-slider__item-promo-buy">
                                    <a class="btn" href="#buy">Купить</a>
                                </div>
                            </div>
                        </div>
                        <div class="abonements-group-slider__item">
                            <div class="abonements-group-slider__item-img">
                                <img src="<?=SITE_TEMPLATE_PATH?>/img/main/abonements_2.jpg" alt="Тариф Максимум" title="Тариф Максимум">
                            </div>
                            <div class="abonements-group-slider__item-name">
                                <span>Тариф</span>
                                «Максимум»
                            </div>
                            <div class="abonements-group-slider__item-wrapper">
                                <div class="abonements-group-slider__item-month">
                                    <div class="description">Ежемесячный платеж</div>
                                    <div class="price">1990₽</div>
                                </div>
                                <div class="abonements-group-slider__item-try">
                                    <div class="description">Пробный месяц</div>
                                    <div class="price-old">4490₽</div>
                                    <div class="price">3490₽</div>
                                </div>
                                <div class="abonements-group-slider__item-promo">
                                    <a>
                                        <img src="<?=SITE_TEMPLATE_PATH?>/img/gift.svg" alt="" title="">
                                        Супер-Мега предложение
                                    </a>
                                </div>
                                <div class="abonements-group-slider__item-promo-date">
                                    Срок действия до 31.12.2022
                                </div>
                                <div class="abonements-group-slider__item-list">
                                    <div class="abonements-group-slider__item-list-item">доступ ко всем услугам клуба</div>
                                    <div class="abonements-group-slider__item-list-item">16500 бонусов от наших партнеров</div>
                                    <div class="abonements-group-slider__item-list-item">безлимитный доступ ко всем услугам</div>
                                    <div class="abonements-group-slider__item-list-item">тренажерный зал</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные групповые программы</div>
                                    <div class="abonements-group-slider__item-list-item">регулярный анализ тела InBody</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатные программы тренировок в мобильном приложении</div>
                                    <div class="abonements-group-slider__item-list-item">бесплатная персональная тренировка с тренером</div>
                                    <div class="abonements-group-slider__item-list-item">скидка на стартовый блок тренировок 20%</div>
                                    <div class="abonements-group-slider__item-list-item">финские сауны и хаммам</div>
                                    <div class="abonements-group-slider__item-list-item">шкафчик в раздевалке</div>
                                    <div class="abonements-group-slider__item-list-item">сейфовая ячейка</div>
                                </div>
                                <div class="abonements-group-slider__item-promo-buy">
                                    <a class="btn" href="#buy">Купить</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="b-cards-slider__heading">
            <div class="b-cards-slider__title">
                <h2>Отзывы</h2>
            </div>
        </div>
        <div class="reviews-slider">
            <div class="reviews-slider-item">
                <div class="reviews-slider-item__letter">В</div>
                <div class="reviews-slider-item__name">
                    Виктор
                </div>
                <div class="reviews-slider-item__text">
                    Эффективный инструмент для планирования и реализации изменений тела. Первое приложение, созданное оператором фитнес-услуг для повышения эффективности тренировок членов клуба!
                </div>
                <div class="reviews-slider-item__link">
                    <a href="#link">
                        <span class="icon">
                            <svg width="15" height="21" viewBox="0 0 15 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.24359 0C11.2448 0 14.4872 3.24375 14.4872 7.24359C14.4872 10.2436 9.105 17.5791 7.24359 20.9344C5.45906 17.7141 0 10.0673 0 7.24359C0 3.24375 3.24375 0 7.24359 0ZM10.4813 6.97078C10.4813 5.18813 9.02719 3.73406 7.24359 3.73406C5.46141 3.73406 4.00734 5.18813 4.00734 6.97078C4.00734 8.75297 5.46141 10.207 7.24359 10.207C9.02719 10.207 10.4813 8.75297 10.4813 6.97078Z" fill="#E74C3C"/>
                            </svg>
                            Яндекс карта
                        </span>
                        <span class="text">Spirit.Fitness Россия, Москва Балаклавский проспект, 16А</span>
                    </a>
                </div>
                <div class="reviews-slider-item__rating">
                    Оценка: 4.8
                </div>
            </div>
            <div class="reviews-slider-item">
                <div class="reviews-slider-item__letter">М</div>
                <div class="reviews-slider-item__name">
                    Марина
                </div>
                <div class="reviews-slider-item__text">
                    Эффективный инструмент для планирования и реализации изменений тела. Первое приложение, созданное оператором фитнес-услуг для повышения эффективности тренировок членов клуба!
                </div>
                <div class="reviews-slider-item__link">
                    <a href="#link">
                        <span class="icon">
                            <svg width="15" height="21" viewBox="0 0 15 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.24359 0C11.2448 0 14.4872 3.24375 14.4872 7.24359C14.4872 10.2436 9.105 17.5791 7.24359 20.9344C5.45906 17.7141 0 10.0673 0 7.24359C0 3.24375 3.24375 0 7.24359 0ZM10.4813 6.97078C10.4813 5.18813 9.02719 3.73406 7.24359 3.73406C5.46141 3.73406 4.00734 5.18813 4.00734 6.97078C4.00734 8.75297 5.46141 10.207 7.24359 10.207C9.02719 10.207 10.4813 8.75297 10.4813 6.97078Z" fill="#E74C3C"/>
                            </svg>
                            Яндекс карта
                        </span>
                        <span class="text">Spirit.Fitness Россия, Москва Балаклавский проспект, 16А</span>
                    </a>
                </div>
                <div class="reviews-slider-item__rating">
                    Оценка: 4.8
                </div>
            </div>
            <div class="reviews-slider-item">
                <div class="reviews-slider-item__letter">С</div>
                <div class="reviews-slider-item__name">
                    Сергей
                </div>
                <div class="reviews-slider-item__text">
                    Эффективный инструмент для планирования и реализации изменений тела. Первое приложение, созданное оператором фитнес-услуг для повышения эффективности тренировок членов клуба!
                </div>
                <div class="reviews-slider-item__link">
                    <a href="#link">
                        <span class="icon">
                            <svg width="15" height="21" viewBox="0 0 15 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.24359 0C11.2448 0 14.4872 3.24375 14.4872 7.24359C14.4872 10.2436 9.105 17.5791 7.24359 20.9344C5.45906 17.7141 0 10.0673 0 7.24359C0 3.24375 3.24375 0 7.24359 0ZM10.4813 6.97078C10.4813 5.18813 9.02719 3.73406 7.24359 3.73406C5.46141 3.73406 4.00734 5.18813 4.00734 6.97078C4.00734 8.75297 5.46141 10.207 7.24359 10.207C9.02719 10.207 10.4813 8.75297 10.4813 6.97078Z" fill="#E74C3C"/>
                            </svg>
                            Яндекс карта
                        </span>
                        <span class="text">Spirit.Fitness Россия, Москва Балаклавский проспект, 16А</span>
                    </a>
                </div>
                <div class="reviews-slider-item__rating">
                    Оценка: 4.8
                </div>
            </div>
            <div class="reviews-slider-item">
                <div class="reviews-slider-item__letter">Н</div>
                <div class="reviews-slider-item__name">
                    Николай
                </div>
                <div class="reviews-slider-item__text">
                    Эффективный инструмент для планирования и реализации изменений тела. Первое приложение, созданное оператором фитнес-услуг для повышения эффективности тренировок членов клуба!
                </div>
                <div class="reviews-slider-item__link">
                    <a href="#link">
                        <span class="icon">
                            <svg width="15" height="21" viewBox="0 0 15 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.24359 0C11.2448 0 14.4872 3.24375 14.4872 7.24359C14.4872 10.2436 9.105 17.5791 7.24359 20.9344C5.45906 17.7141 0 10.0673 0 7.24359C0 3.24375 3.24375 0 7.24359 0ZM10.4813 6.97078C10.4813 5.18813 9.02719 3.73406 7.24359 3.73406C5.46141 3.73406 4.00734 5.18813 4.00734 6.97078C4.00734 8.75297 5.46141 10.207 7.24359 10.207C9.02719 10.207 10.4813 8.75297 10.4813 6.97078Z" fill="#E74C3C"/>
                            </svg>
                            Яндекс карта
                        </span>
                        <span class="text">Spirit.Fitness Россия, Москва Балаклавский проспект, 16А</span>
                    </a>
                </div>
                <div class="reviews-slider-item__rating">
                    Оценка: 4.8
                </div>
            </div>
        </div>
    </div>
<!-- Отзывы, Абонементы, Промо блоки -->

<? $GLOBALS['arrFilterAbonement'] = ['PROPERTY_HIDDEN_VALUE' => false]?>
<?
	$APPLICATION->IncludeComponent(
		"bitrix:news.list",
		"abonements.main",
		Array(
			"ACTIVE_DATE_FORMAT" => "d.m.Y",
			"ADD_SECTIONS_CHAIN" => "Y",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_ADDITIONAL" => "",
			"AJAX_OPTION_HISTORY" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "Y",
			"CACHE_TIME" => "36000000",
			"CACHE_TYPE" => "A",
			"CHECK_DATES" => "Y",
			"DETAIL_URL" => "",
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"DISPLAY_DATE" => "Y",
			"DISPLAY_NAME" => "Y",
			"DISPLAY_PICTURE" => "Y",
			"DISPLAY_PREVIEW_TEXT" => "Y",
			"DISPLAY_TOP_PAGER" => "N",
			"FIELD_CODE" => array("",""),
			"FILE_404" => "",
			"FILTER_NAME" => "arrFilterAbonement",
			"HIDE_LINK_WHEN_NO_DETAIL" => "N",
			"IBLOCK_ID" => "9",
			"IBLOCK_TYPE" => "content",
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
			"INCLUDE_SUBSECTIONS" => "Y",
			"MESSAGE_404" => "",
			"NEWS_COUNT" => "20",
			"PAGER_BASE_LINK_ENABLE" => "N",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "N",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => ".default",
			"PAGER_TITLE" => "Новости",
			"PARENT_SECTION" => "",
			"PARENT_SECTION_CODE" => "",
			"PREVIEW_TRUNCATE_LEN" => "",
			"PROPERTY_CODE" => array("SIZE","PRICE",""),
			"SET_BROWSER_TITLE" => "Y",
			"SET_LAST_MODIFIED" => "N",
			"SET_META_DESCRIPTION" => "Y",
			"SET_META_KEYWORDS" => "Y",
			"SET_STATUS_404" => "N",
			"SET_TITLE" => "Y",
			"SHOW_404" => "N",
			"SORT_BY1" => "ACTIVE_FROM",
			"SORT_BY2" => "SORT",
			"SORT_ORDER1" => "ASC",
			"SORT_ORDER2" => "ASC",
			"STRICT_SECTION_CHECK" => "N"
		)
	);
?>

<? $APPLICATION->IncludeFile('/local/include/blocks.php', ['ELEMENT_CODE' => 'trenirovki'], ['SHOW_BORDER' => false]); ?>
<? $APPLICATION->IncludeFile('/local/include/blocks.php', ['ELEMENT_CODE' => 'mobilnoe-prilozhenie'], ['SHOW_BORDER' => false]); ?>


<?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"faq", 
	array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "13",
		"IBLOCK_TYPE" => "content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "6",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(
			0 => "SIZE",
			1 => "PRICE",
			2 => "",
		),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N",
		"COMPONENT_TEMPLATE" => "faq",
		"FILE_404" => ""
	),
	false
);?>
<?
if (!isset($_SERVER['HTTP_X_PJAX'])) {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
}
?>
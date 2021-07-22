<?php
if (isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true') {
    require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
    echo "<title>Прокачай себя на 5.000.000Р</title>";

} else {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
    $APPLICATION->SetTitle("Прокачай себя на 5.000.000Р");
}
?>
    <div class="pump-yourself">
            <h1 class="application__heading">Прокачай себя на 5.000.000Р</h1>
            <div class="application__desc club__desc">
                <div class="application__desc-inner club__desc-inner">
                    <p>
                        <a class="pump-yourself__link" href="">Онлайн-тренировки</a> помогут поддерживать физическую форму и
                        укрепить иммунитет не выходя из дома.
                    </p>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                        Nam praesentium, similique! Deserunt dolor eum in mollitia quaerat quisquam quod, repellat
                        tempore vitae? Ad ex nihil nobis numquam quos totam velit.
                    </p>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequatur, enim?
                    </p>
                    </div>
            </div>
            <div class="product__subheading">Как принять участие:</div>
            <div class="product__list">
                <div class="product__list-col">
                    <div class="product__list-item">
                        <div class="product__list-item-num">1.</div>
                        <div class="product__list-item-text">Установите мобильное приложение Spirit. для тренировок</div>
                    </div>
                    <div class="product__list-item">
                        <div class="product__list-item-num">2.</div>
                        <div class="product__list-item-text">В разделе "Тренировки" выберите "Фитнес-интенсив"</div>
                    </div>
                    <div class="product__list-item">
                        <div class="product__list-item-num">3.</div>
                        <div class="product__list-item-text">Пройдите онлайн тест для определения уровня вашей физической подготовки</div>
                    </div>
                    <div class="product__list-item">
                        <div class="product__list-item-num">4.</div>
                        <div class="product__list-item-text">Получите доступ к программам тренировок</div>
                    </div>
                </div>
                <div class="product__list-col">
                    <div class="product__list-item">
                        <div class="product__list-item-num">5.</div>
                        <div class="product__list-item-text">Подготовьте немного пространства для тренировки</div>
                    </div>
                    <div class="product__list-item">
                        <div class="product__list-item-num">6.</div>
                        <div class="product__list-item-text">Следуйте тренировочному плану каждую неделю</div>
                    </div>
                    <div class="product__list-item">
                        <div class="product__list-item-num">7.</div>
                        <div class="product__list-item-text">Добавляйтесь в чат для общения с куратором и другими участниками проекта</div>
                    </div>
                </div>
            </div>
            <div class="application__subheading">Призы</div>
            <div class="application__possibilities">
                <div class="application__possibilities-slide application__possibilities-slide--traning">
                    <div class="application__possibilities-slide-wrap flip_box">
                        <div class="application__possibilities-slide-inner application__possibilities-slide-inner--front">
                            <div class="application__possibilities-info">
                                <div class="application__possibilities-info-num">1</div>
                                <div class="application__possibilities-info-title">BODYPUMP™</div>
                                <div class="application__possibilities-info-desc">BODYPUMP™ – тренировка со штангой
                                    поможет
                                    вам стать сильнее с помощью базовых упражнений для рук, ног, спины и груди,
                                    похудеть,
                                    подтянуть мышцы и привести тело в тонус.
                                </div>
                            </div>
                            <div class="application__possibilities-slide-pic">
                                <img class="application__possibilities-slide-pic-img"
                                     src="/upload/iblock/081/0812c380130df75e0bf641a3d48a388d.png" alt="BODYPUMP™">
                            </div>
                        </div>
                        <div class="application__possibilities-slide-inner application__possibilities-slide-inner--back">
                            <div class="application__possibilities-info">
                                <div class="application__possibilities-info-num">1</div>
                                <div class="application__possibilities-info-title">BODYPUMP™</div>
                                <div class="application__possibilities-info-desc">BODYPUMP™ – тренировка со штангой
                                    поможет
                                    вам стать сильнее с помощью базовых упражнений для рук, ног, спины и груди,
                                    похудеть,
                                    подтянуть мышцы и привести тело в тонус.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="r_wrap">
                        <div class="b_round"></div>
                        <div class="s_round">
                            <div class="s_arrow"></div>
                        </div>
                    </div>
                </div>
                <div class="application__possibilities-slide application__possibilities-slide--traning">
                    <div class="application__possibilities-slide-wrap flip_box">
                        <div class="application__possibilities-slide-inner application__possibilities-slide-inner--front">
                            <div class="application__possibilities-info">
                                <div class="application__possibilities-info-num">2</div>
                                <div class="application__possibilities-info-title">BODYPUMP™</div>
                                <div class="application__possibilities-info-desc">BODYCOMBAT™ - бесконтактный
                                    бой на основе элементов различных видов боевых искусств, быстро приведет
                                    в форму, поможет развить силу и выносливость, укрепить сердечно- сосудистую
                                    систему.
                                </div>
                            </div>
                            <div class="application__possibilities-slide-pic">
                                <img class="application__possibilities-slide-pic-img"
                                     src="/upload/iblock/207/207be04835e335b1a76effc8b3313682.png" alt="BODYPUMP™">
                            </div>
                        </div>
                        <div class="application__possibilities-slide-inner application__possibilities-slide-inner--back">
                            <div class="application__possibilities-info">
                                <div class="application__possibilities-info-num">2</div>
                                <div class="application__possibilities-info-title">BODYPUMP™</div>
                                <div class="application__possibilities-info-desc">BODYCOMBAT™ - бесконтактный
                                    бой на основе элементов различных видов боевых искусств, быстро приведет
                                    в форму, поможет развить силу и выносливость, укрепить сердечно- сосудистую
                                    систему.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="r_wrap">
                        <div class="b_round"></div>
                        <div class="s_round">
                            <div class="s_arrow"></div>
                        </div>
                    </div>
                </div>
                <div class="application__possibilities-slide application__possibilities-slide--traning">
                    <div class="application__possibilities-slide-wrap flip_box">
                        <div class="application__possibilities-slide-inner application__possibilities-slide-inner--front">
                            <div class="application__possibilities-info">
                                <div class="application__possibilities-info-num">3</div>
                                <div class="application__possibilities-info-title">BODYPUMP™</div>
                                <div class="application__possibilities-info-desc">BODYCOMBAT™ - бесконтактный
                                    бой на основе элементов различных видов боевых искусств, быстро приведет
                                    в форму, поможет развить силу и выносливость, укрепить сердечно- сосудистую
                                    систему.
                                </div>
                            </div>
                            <div class="application__possibilities-slide-pic">
                                <img class="application__possibilities-slide-pic-img"
                                     src="/upload/iblock/207/207be04835e335b1a76effc8b3313682.png" alt="BODYPUMP™">
                            </div>
                        </div>
                        <div class="application__possibilities-slide-inner application__possibilities-slide-inner--back">
                            <div class="application__possibilities-info">
                                <div class="application__possibilities-info-num">3</div>
                                <div class="application__possibilities-info-title">BODYPUMP™</div>
                                <div class="application__possibilities-info-desc">BODYCOMBAT™ - бесконтактный
                                    бой на основе элементов различных видов боевых искусств, быстро приведет
                                    в форму, поможет развить силу и выносливость, укрепить сердечно- сосудистую
                                    систему.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="r_wrap">
                        <div class="b_round"></div>
                        <div class="s_round">
                            <div class="s_arrow"></div>
                        </div>
                    </div>
                </div>
                <div class="application__possibilities-slide application__possibilities-slide--traning">
                    <div class="application__possibilities-slide-wrap flip_box">
                        <div class="application__possibilities-slide-inner application__possibilities-slide-inner--front">
                            <div class="application__possibilities-info">
                                <div class="application__possibilities-info-num">3</div>
                                <div class="application__possibilities-info-title">BODYPUMP™</div>
                                <div class="application__possibilities-info-desc">BODYCOMBAT™ - бесконтактный
                                    бой на основе элементов различных видов боевых искусств, быстро приведет
                                    в форму, поможет развить силу и выносливость, укрепить сердечно- сосудистую
                                    систему.
                                </div>
                            </div>
                            <div class="application__possibilities-slide-pic">
                                <img class="application__possibilities-slide-pic-img"
                                     src="/upload/iblock/207/207be04835e335b1a76effc8b3313682.png" alt="BODYPUMP™">
                            </div>
                        </div>
                        <div class="application__possibilities-slide-inner application__possibilities-slide-inner--back">
                            <div class="application__possibilities-info">
                                <div class="application__possibilities-info-num">3</div>
                                <div class="application__possibilities-info-title">BODYPUMP™</div>
                                <div class="application__possibilities-info-desc">BODYCOMBAT™ - бесконтактный
                                    бой на основе элементов различных видов боевых искусств, быстро приведет
                                    в форму, поможет развить силу и выносливость, укрепить сердечно- сосудистую
                                    систему.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="r_wrap">
                        <div class="b_round"></div>
                        <div class="s_round">
                            <div class="s_arrow"></div>
                        </div>
                    </div>
                </div>
                <div class="application__possibilities-slide application__possibilities-slide--traning">
                    <div class="application__possibilities-slide-wrap flip_box">
                        <div class="application__possibilities-slide-inner application__possibilities-slide-inner--front">
                            <div class="application__possibilities-info">
                                <div class="application__possibilities-info-num">3</div>
                                <div class="application__possibilities-info-title">BODYPUMP™</div>
                                <div class="application__possibilities-info-desc">BODYCOMBAT™ - бесконтактный
                                    бой на основе элементов различных видов боевых искусств, быстро приведет
                                    в форму, поможет развить силу и выносливость, укрепить сердечно- сосудистую
                                    систему.
                                </div>
                            </div>
                            <div class="application__possibilities-slide-pic">
                                <img class="application__possibilities-slide-pic-img"
                                     src="/upload/iblock/207/207be04835e335b1a76effc8b3313682.png" alt="BODYPUMP™">
                            </div>
                        </div>
                        <div class="application__possibilities-slide-inner application__possibilities-slide-inner--back">
                            <div class="application__possibilities-info">
                                <div class="application__possibilities-info-num">3</div>
                                <div class="application__possibilities-info-title">BODYPUMP™</div>
                                <div class="application__possibilities-info-desc">BODYCOMBAT™ - бесконтактный
                                    бой на основе элементов различных видов боевых искусств, быстро приведет
                                    в форму, поможет развить силу и выносливость, укрепить сердечно- сосудистую
                                    систему.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="r_wrap">
                        <div class="b_round"></div>
                        <div class="s_round">
                            <div class="s_arrow"></div>
                        </div>
                    </div>
                </div>

            </div>
            <h2 class="club__subheading">
                Фотогалерея
            </h2>
            <div  class="club__slider js-club__slider-popup">
                <div class="club__slider-item">
                    <img class="club__slider-item-img" src="/upload/iblock/ff4/ff47ccface4de72885888f96dbc80f81.jpg" alt="">
                </div>
            </div>

            <div class="application__links">
                <a class="application__links-item application__links-item-pump" rel="nofollow" target="_blank" href="https://spiritfit.ru/abonement/ezhemesyachnyy-abonement-po-podpiske/">
                    <div class="application__links-item-text">УЧАСТВОВАТЬ</div>
                </a>

            </div>
    </div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
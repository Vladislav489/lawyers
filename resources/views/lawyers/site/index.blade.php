@extends('lawyers.layouts.main')
@section('title', 'Главная')

@section('content')
                    <section class="gradient-bg u-container">
                        <div class="container main-banner">
                            <div class="block">
                                <p class="banner-text">Юридическая помощь по Вашей ситуации от лучших экспертов</p>
                                <a href="#" class="main-btn"><span>Задать вопрос юристу</span></a>
                            </div>

                            <div class="block image-block">
                                <picture>
                                    <source srcset="/lawyers/images/main/banner-img-mobile.png" media="(max-width: 768px)">
                                    <img src="/lawyers/images/main/banner-img.jpg" alt="banner-img" class="banner-img">
                                </picture>

                                <div class="banner-text-block _1">
                                    <h3 class="name">Михаил Анатольевич Павлов</h3>
                                    <p class="post-position">Адвокат</p>
{{--                                    <div class="stars">--}}
{{--                                        <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon" class="star">--}}
{{--                                        <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon" class="star">--}}
{{--                                        <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon" class="star">--}}
{{--                                        <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon" class="star">--}}
{{--                                        <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon" class="star">--}}
{{--                                    </div>--}}
                                    <img src="/lawyers/images/icons/triangle-icon1.svg" alt="triangle-icon" class="triangle triangle1">
                                </div>
                                <div class="banner-text-block _2">
                                    <h3 class="name">Майя Сергеевна Котова</h3>
                                    <p class="post-position">Адвокат</p>
{{--                                    <div class="stars">--}}
{{--                                        <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon" class="star">--}}
{{--                                        <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon" class="star">--}}
{{--                                        <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon" class="star">--}}
{{--                                        <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon" class="star">--}}
{{--                                        <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon" class="star">--}}
{{--                                    </div>--}}
                                    <img src="/lawyers/images/icons/triangle-icon2.svg" alt="triangle-icon" class="triangle triangle2">
                                </div>
                                <div class="banner-text-block _3">
                                    <h3 class="name">Ирина Андреевна Соболева</h3>
                                    <p class="post-position">Адвокат</p>
{{--                                    <div class="stars">--}}
{{--                                        <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon" class="star">--}}
{{--                                        <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon" class="star">--}}
{{--                                        <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon" class="star">--}}
{{--                                        <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon" class="star">--}}
{{--                                        <img src="/lawyers/images/icons/star-icon-full.svg" alt="star-icon" class="star">--}}
{{--                                    </div>--}}
                                    <img src="/lawyers/images/icons/triangle-icon3.svg" alt="triangle-icon" class="triangle triangle3">
                                </div>
                            </div>

                            <div class="block form-block">
                                <p class="text">Поиск по названию услуги...</p>

                                <form action="#" class="form">
                                    <label>
                                        <input type="search" placeholder="Вопрос по недвижимости...">
                                        <input type="image" src="/lawyers/images/icons/search-icon-gray.svg" alt="search-icon" class="search-icon-input mobile">
                                    </label>
                                    <button type="submit" class="mobile-hidden">
                                        Найти
                                        <img src="/lawyers/images/icons/search-icon-white.svg" alt="search-icon" class="search-icon">
                                    </button>
                                </form>
                            </div>
                        </div>
                    </section>

                    <section class="u-container white-bg">
                        <div class="container specialists">
                            <h2 class="section_header _line-blue">Специалисты дня <br>с самым высоким рейтингом</h2>
<script>
$(function(){
specSlider()
});
function getSliderSettings() {
            let slidesLength = $('.js_specialists_slider').find('li').length;
            return {
                slidesToShow: 3,
                infinite: true,
                dots: false,
                autoplay: true,
                autoplaySpeed: 2000,
                arrows: slidesLength > 3 ? true : false,
                responsive: [{
                    breakpoint: 1281,
                    settings: {
                        slidesToShow: 2,
                        arrows: false,
                        dots: true,
                    }
                },{
                    breakpoint: 981,
                    settings: {
                        slidesToShow: 1,
                        arrows: false,
                        dots: true,
                    }
                }],
            }
        }

        function specSlider() {
            $('.js_specialists_slider').not('.slick-initialized').slick(getSliderSettings());
        }
</script>
                            <div class="js_specialists_slider specialists-carousel">
                                <a href="#" class="specialist">
                                    <h2 class="specialist-title">ЛИДЕР КАТЕГОРИИ: <span> БИЗНЕС</span></h2>

                                    <div class="specialist-block">
                                        <div class="block specialist-top">
                                            <img src="/lawyers/images/main/lawyer-img.png" alt="lawyer-img" class="lawyer-img">

                                            <div class="specialist-info">
                                                <h3 class="specialist-name">Соколовский Владимир Александрович</h3>

                                                <div class="specialist-loc">
                                                    <img src="/lawyers/images/icons/loc-icon-gray.svg" alt="loc-icon" class="loc-icon">
                                                    <p>Москва и МО, пр. Роберта Рождественского, 522</p>
                                                </div>
                                                <div class="specialist-loc">
                                                    <img src="/lawyers/images/icons/bag-icon-gray.svg" alt="loc-icon" class="loc-icon">
                                                    <p>15 лет практики</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="block specialist-spec line">
                                            <h3 class="block-header">Основные специализации</h3>
                                            <ul>
                                                <li>Контракты, </li>
                                                <li>Разводы, </li>
                                                <li>ДТП, </li>
                                                <li>Гражданское право</li>
                                            </ul>
                                        </div>

                                        <div class="block specialist-comment">
                                            <h3 class="block-header">Лучший отзыв</h3>

                                            <div class="comment-rate">
                                                <div class="stars"><span style="width: 80%;"></span></div>

                                                <p class="name">Алексеева Юлия</p>

                                                <time class="date">16.05.2023</time>

                                                <img src="/lawyers/images/icons/check-icon-green.svg" alt="check-icon" class="check-icon">
                                            </div>

                                            <blockquote>
                                                <p class="comment-text">
                                                    Спасибо большое владимиру Александровичу. Он по уголовному делу вел защиту в суде моего
                                                    брата и вместо обещанных ему следователем 5-6 лет колонии, брат получил один год
                                                    условно. Считаю это большой победой адвоката, который смог...
                                                </p>

                                                <button type="button" class="comment-read-more">
                                                    читать еще
                                                </button>
                                            </blockquote>
                                        </div>

                                        <button class="specialist-btn"><span>Смотреть: более 100+</span></button>
                                    </div>
                                </a>
                                <a href="#" class="specialist">
                                    <h2 class="specialist-title">ЛИДЕР КАТЕГОРИИ: <span> БИЗНЕС</span></h2>

                                    <div class="specialist-block">
                                        <div class="block specialist-top">
                                            <img src="/lawyers/images/main/lawyer-img.png" alt="lawyer-img" class="lawyer-img">

                                            <div class="specialist-info">
                                                <h3 class="specialist-name">Соколовский Владимир Александрович</h3>

                                                <div class="specialist-loc">
                                                    <img src="/lawyers/images/icons/loc-icon-gray.svg" alt="loc-icon" class="loc-icon">
                                                    <p>Москва и МО, пр. Роберта Рождественского, 522</p>
                                                </div>
                                                <div class="specialist-loc">
                                                    <img src="/lawyers/images/icons/bag-icon-gray.svg" alt="loc-icon" class="loc-icon">
                                                    <p>15 лет практики</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="block specialist-spec line">
                                            <h3 class="block-header">Основные специализации</h3>
                                            <ul>
                                                <li>Контракты, </li>
                                                <li>Разводы, </li>
                                                <li>ДТП, </li>
                                                <li>Гражданское право</li>
                                            </ul>
                                        </div>

                                        <div class="block specialist-comment">
                                            <h3 class="block-header">Лучший отзыв</h3>

                                            <div class="comment-rate">
                                                <div class="stars"><span style="width: 80%;"></span></div>

                                                <p class="name">Алексеева Юлия</p>

                                                <time class="date">16.05.2023</time>

                                                <img src="/lawyers/images/icons/check-icon-green.svg" alt="check-icon" class="check-icon">
                                            </div>

                                            <blockquote>
                                                <p class="comment-text">
                                                    Спасибо большое владимиру Александровичу. Он по уголовному делу вел защиту в суде моего
                                                    брата и вместо обещанных ему следователем 5-6 лет колонии, брат получил один год
                                                    условно. Считаю это большой победой адвоката, который смог...
                                                </p>

                                                <button type="button" class="comment-read-more">
                                                    читать еще
                                                </button>
                                            </blockquote>
                                        </div>

                                        <button class="specialist-btn"><span>Смотреть: более 100+</span></button>
                                    </div>
                                </a>
                                <a href="#" class="specialist">
                                    <h2 class="specialist-title">ЛИДЕР КАТЕГОРИИ: <span> БИЗНЕС</span></h2>

                                    <div class="specialist-block">
                                        <div class="block specialist-top">
                                            <img src="/lawyers/images/main/lawyer-img.png" alt="lawyer-img" class="lawyer-img">

                                            <div class="specialist-info">
                                                <h3 class="specialist-name">Соколовский Владимир Александрович</h3>

                                                <div class="specialist-loc">
                                                    <img src="/lawyers/images/icons/loc-icon-gray.svg" alt="loc-icon" class="loc-icon">
                                                    <p>Москва и МО, пр. Роберта Рождественского, 522</p>
                                                </div>
                                                <div class="specialist-loc">
                                                    <img src="/lawyers/images/icons/bag-icon-gray.svg" alt="loc-icon" class="loc-icon">
                                                    <p>15 лет практики</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="block specialist-spec line">
                                            <h3 class="block-header">Основные специализации</h3>
                                            <ul>
                                                <li>Контракты, </li>
                                                <li>Разводы, </li>
                                                <li>ДТП, </li>
                                                <li>Гражданское право</li>
                                            </ul>
                                        </div>

                                        <div class="block specialist-comment">
                                            <h3 class="block-header">Лучший отзыв</h3>

                                            <div class="comment-rate">
                                                <div class="stars"><span style="width: 80%;"></span></div>

                                                <p class="name">Алексеева Юлия</p>

                                                <time class="date">16.05.2023</time>

                                                <img src="/lawyers/images/icons/check-icon-green.svg" alt="check-icon" class="check-icon">
                                            </div>

                                            <blockquote>
                                                <p class="comment-text">
                                                    Спасибо большое владимиру Александровичу. Он по уголовному делу вел защиту в суде моего
                                                    брата и вместо обещанных ему следователем 5-6 лет колонии, брат получил один год
                                                    условно. Считаю это большой победой адвоката, который смог...
                                                </p>

                                                <button type="button" class="comment-read-more">
                                                    читать еще
                                                </button>
                                            </blockquote>
                                        </div>

                                        <button class="specialist-btn"><span>Смотреть: более 100+</span></button>
                                    </div>
                                </a>
                                <a href="#" class="specialist">
                                    <h2 class="specialist-title">ЛИДЕР КАТЕГОРИИ: <span> БИЗНЕС</span></h2>

                                    <div class="specialist-block">
                                        <div class="block specialist-top">
                                            <img src="/lawyers/images/main/lawyer-img.png" alt="lawyer-img" class="lawyer-img">

                                            <div class="specialist-info">
                                                <h3 class="specialist-name">Соколовский Владимир Александрович</h3>

                                                <div class="specialist-loc">
                                                    <img src="/lawyers/images/icons/loc-icon-gray.svg" alt="loc-icon" class="loc-icon">
                                                    <p>Москва и МО, пр. Роберта Рождественского, 522</p>
                                                </div>
                                                <div class="specialist-loc">
                                                    <img src="/lawyers/images/icons/bag-icon-gray.svg" alt="loc-icon" class="loc-icon">
                                                    <p>15 лет практики</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="block specialist-spec line">
                                            <h3 class="block-header">Основные специализации</h3>
                                            <ul>
                                                <li>Контракты, </li>
                                                <li>Разводы, </li>
                                                <li>ДТП, </li>
                                                <li>Гражданское право</li>
                                            </ul>
                                        </div>

                                        <div class="block specialist-comment">
                                            <h3 class="block-header">Лучший отзыв</h3>

                                            <div class="comment-rate">
                                                <div class="stars"><span style="width: 80%;"></span></div>

                                                <p class="name">Алексеева Юлия</p>

                                                <time class="date">16.05.2023</time>

                                                <img src="/lawyers/images/icons/check-icon-green.svg" alt="check-icon" class="check-icon">
                                            </div>

                                            <blockquote>
                                                <p class="comment-text">
                                                    Спасибо большое владимиру Александровичу. Он по уголовному делу вел защиту в суде моего
                                                    брата и вместо обещанных ему следователем 5-6 лет колонии, брат получил один год
                                                    условно. Считаю это большой победой адвоката, который смог...
                                                </p>

                                                <button type="button" class="comment-read-more">
                                                    читать еще
                                                </button>
                                            </blockquote>
                                        </div>

                                        <button class="specialist-btn"><span>Смотреть: более 100+</span></button>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </section>

                    <section class="gradient-bg u-container">
                        <div class="container questions">
                            <h2 class="section_header _line-blue-dark">Популярные правовые вопросы <br>за последнее время</h2>

                            <div class="questions-container">
                                <div class="question">
                                    <h3 class="title">СЕМЕЙНЫЕ СПОРЫ</h3>
                                    <p class="text">Юридическая помощь по взысканию алиментов, расторжению брака, разделу имущества и пр.</p>
                                    <button class="question-btn main-btn main-btn_white">Подробнее</button>
                                </div>

                                <div class="question">
                                    <h3 class="title">ТРУДОВЫЕ СПОРЫ</h3>
                                    <p class="text">Юридическая помощь по взысканию алиментов, расторжению брака, разделу имущества и пр.</p>
                                    <button class="question-btn main-btn main-btn_white">Подробнее</button>
                                </div>

                                <div class="question">
                                    <h3 class="title">УГОЛОВНЫЕ ДЕЛА</h3>
                                    <p class="text">Юридическая помощь по взысканию алиментов, расторжению брака, разделу имущества и пр.</p>
                                    <button class="question-btn main-btn main-btn_white">Подробнее</button>
                                </div>

                                <div class="question mobile-hidden">
                                    <h3 class="title">ЖИЛИЩНЫЕ СПОРЫ</h3>
                                    <p class="text">Юридическая помощь по взысканию алиментов, расторжению брака, разделу имущества и пр.</p>
                                    <button class="question-btn main-btn main-btn_white">Подробнее</button>
                                </div>

                                <div class="question mobile-hidden">
                                    <h3 class="title">ЗЕМЕЛЬНЫЕ СПОРЫ</h3>
                                    <p class="text">Юридическая помощь по взысканию алиментов, расторжению брака, разделу имущества и пр.</p>
                                    <button class="question-btn main-btn main-btn_white">Подробнее</button>
                                </div>

                                <div class="question mobile-hidden">
                                    <h3 class="title">ЮРИСТ ДЛЯ БИЗНЕСА</h3>
                                    <p class="text">Юридическая помощь по взысканию алиментов, расторжению брака, разделу имущества и пр.</p>
                                    <button class="question-btn main-btn main-btn_white">Подробнее</button>
                                </div>

                                <div class="question mobile-hidden">
                                    <h3 class="title">БЕСПЛАТНАЯ ЮР. ПОМОЩЬ*</h3>
                                    <p class="text">Юридическая помощь по взысканию алиментов, расторжению брака, разделу имущества и пр.</p>
                                    <button class="question-btn main-btn main-btn_white">Подробнее</button>
                                </div>

                                <div class="question mobile-hidden">
                                    <h3 class="title">НАЛОГОВЫЕ СПОРЫ</h3>
                                    <p class="text">Юридическая помощь по взысканию алиментов, расторжению брака, разделу имущества и пр.</p>
                                    <button class="question-btn main-btn main-btn_white">Подробнее</button>
                                </div>
                            </div>

                            <a href="#" class="all-services main-btn main-btn_blue"><span>Все услуги</span></a>

                            <form action="#" class="questions-form">
                                <div class="block left">
                                    <h3>Не нашли того, что искали?</h3>
                                    <p>Оставьте контакты и юрист с Вами свяжется.</p>
                                </div>
                                <div class="block right">
                                    <label><input type="text" name="name" placeholder="Имя"></label>
                                    <label><input type="tel" name="phone" placeholder="Телефон"></label>
                                    <button type="submit" class="main-btn"><span>Отправить</span></button>
                                </div>
                            </form>
                        </div>
                    </section>
@endsection

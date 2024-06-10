<?php
require "header_users.php";
require "../login.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="../css/style.css">
    <title>Document</title>
</head>
<body>
    <div class="wrapper">
        <main class="main">
            <section class="top">
                <div class="container">
                    <h1 class="title">События в Sportify</h1>
                    <!-- <a href="#" class="top__link">Посмотреть</a> -->
                </div>
            </section>
            <div class="slider">
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide" style="background-image: url(../images/slide-2.jpg);"></div>
                        <div class="swiper-slide" style="background-image: url(../images/slide-1.jpg);"></div>
                        <div class="swiper-slide" style="background-image: url(../images/slide-3.jpg);"></div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
            <section class="sport__info">
                <div class="container">
                    <ul class="sport__info-list">
                        <li class="sport__info-item">
                            <img src="../images/football.svg" alt="" class="sport__info-item-img">
                            <h3 class="sport__info-item-title">Футбол</h3>
                            <p class="sport__info-item-text"></p>
                        </li>
                        <li class="sport__info-item">
                            <img src="../images/basketball.svg" alt="" class="sport__info-item-img">
                            <h3 class="sport__info-item-title">Баскетбол</h3>
                            <p class="sport__info-item-text"></p>
                        </li>
                        <li class="sport__info-item">
                            <img src="../images/volleyball.svg" alt="" class="sport__info-item-img">
                            <h3 class="sport__info-item-title">Волейбол</h3>
                            <p class="sport__info-item-text"></p>
                        </li>
                    </ul>
                </div>
            </section>
        </main>
        <footer class="footer">
            <div class="footer__container">
                <div class="footer__menu-list">
                    <div class="footer__menu-logo">
                        <a href="#" class="footer__menu-link">
                            <img src="../images/logo.svg" alt="" class="logo__img">
                        </a>
                    </div>
                    <div class="footer__menu-definition">
                        <p class="footer__menu-text">Это спортивный сервис, предназначенный для поиска спортивных событий.<br>
                            Сервис предоставляет информацию о доступных местах,<br> описания услуг и возможности записи на мероприятие.
                        </p>
                    </div>
                </div>
                <div class="footer__menu-list">
                    <div class="footer__menu-item">
                        <a href="#" class="footer__menu-link footer__menu-text">+9 (999) 999-99-99</a>
                    </div>
                    <div class="footer__menu-item">
                        <a href="#" class="footer__menu-link footer__menu-text">help@sportify.com</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="../js/main.js"></script>
    <!-- <script src="../auth.js" type="module"></script> -->
</body>
</html>


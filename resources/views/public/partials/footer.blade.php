<footer id="footer" class="footer">

    <div class="container footer-top">
        <div class="row gy-4">
            <div class="col-lg-4 col-md-6 footer-about">
                <a href="{{ route('home') }}" class="d-flex align-items-center">
                    <span class="sitename">BroNix</span>
                </a>
                <div class="footer-contact pt-3">
                    <p>Платформа для онлайн-бронирования</p>
                    <p>услуг и управления записями</p>
                    <p class="mt-3"><strong>Формат:</strong> <span>Web platform</span></p>
                    <p><strong>Поддержка:</strong> <span>через Telegram-форму на сайте</span></p>
                </div>
            </div>

            <div class="col-lg-2 col-md-3 footer-links">
                <h4>Навигация</h4>
                <ul>
                    <li><i class="bi bi-chevron-right"></i> <a href="#hero">Главная</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="#recent-posts">Лидеры платформы</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="#services">Категории</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="#contact">Контакты</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-3 footer-links">
                <h4>Популярные категории</h4>
                <ul>
                    <li><i class="bi bi-chevron-right"></i> <a href="#services">Барбершопы</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="#services">Салоны красоты</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="#services">Клиники</a></li>
                    <li><i class="bi bi-chevron-right"></i> <a href="#services">Автосервисы</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-12">
                <h4>Для бизнеса</h4>
                <p>Подключайте компанию, управляйте услугами, расписанием и заявками в одном кабинете.</p>
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Создать аккаунт</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Войти в кабинет</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container copyright text-center mt-4">
        <p>&copy; <span>{{ now()->year }}</span> <strong class="px-1 sitename">BroNix</strong> <span>All Rights Reserved</span></p>
    </div>

</footer>

<!-- Scroll Top -->
<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="{{ asset('assets/public/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/public/vendor/aos/aos.js') }}"></script>
<script src="{{ asset('assets/public/vendor/glightbox/js/glightbox.min.js') }}"></script>
<script src="{{ asset('assets/public/vendor/purecounter/purecounter_vanilla.js') }}"></script>
<script src="{{ asset('assets/public/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
<script src="{{ asset('assets/public/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('assets/public/vendor/swiper/swiper-bundle.min.js') }}"></script>

<!-- Main JS File -->
<script src="{{ asset('assets/public/js/main.js') }}"></script>

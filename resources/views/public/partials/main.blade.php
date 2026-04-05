<main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">

        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center">
                    <h1 data-aos="fade-up">Онлайн-бронирование услуг без звонков и лишних подтверждений</h1>
                    <p data-aos="fade-up" data-aos-delay="100">BroNix помогает клиентам быстро записываться, а бизнесу управлять расписанием, услугами и оплатой в одном месте.</p>
                    <div class="d-flex flex-column flex-md-row gap-3" data-aos="fade-up" data-aos-delay="200">
                        <a href="{{ Route::has('booking.page') ? route('booking.page') : route('register') }}" class="btn-get-started d-inline-flex align-items-center justify-content-center">
                            <span>Забронировать сейчас</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="btn btn-outline-primary d-inline-flex align-items-center justify-content-center">
                            <span>{{ auth()->check() ? 'Перейти в кабинет' : 'Войти в кабинет' }}</span>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out">
                    <img src="{{ asset('assets/public/img/services.jpg') }}" class="img-fluid animated" alt="Сервис онлайн-бронирования услуг для клиентов и бизнеса">
                </div>
            </div>
        </div>

    </section><!-- /Hero Section -->

    <!-- Recent Posts Section -->
    <section id="recent-posts" class="recent-posts section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Лидеры платформы</h2>
            <p>Компании с наибольшим количеством бронирований<br></p>
        </div><!-- End Section Title -->

        <div class="container">

            <div class="row gy-5">
                @forelse(($topBusinesses ?? collect()) as $business)
                    <div class="col-xl-4 col-md-6">
                        <div class="post-item position-relative h-100" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">

                            <div class="post-img position-relative overflow-hidden">
                                @if($business->image)
                                    <img src="{{ asset('storage/' . $business->image) }}" class="img-fluid" alt="{{ $business->name }}">
                                @else
                                    <img src="{{ asset('assets/public/img/blog/blog-' . min($loop->iteration, 3) . '.jpg') }}" class="img-fluid" alt="{{ $business->name }}">
                                @endif
                                @php
                                    $count = (int) $business->bookings_count;
                                    $mod10 = $count % 10;
                                    $mod100 = $count % 100;
                                    $orderLabel = ($mod10 === 1 && $mod100 !== 11)
                                        ? 'заказ'
                                        : (($mod10 >= 2 && $mod10 <= 4) && !($mod100 >= 12 && $mod100 <= 14) ? 'заказа' : 'заказов');
                                @endphp
                                <span class="post-date">{{ $count }} {{ $orderLabel }}</span>
                            </div>

                            <div class="post-content d-flex flex-column">

                                <h3 class="post-title">{{ $business->name }}</h3>

                                <div class="meta d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-building"></i> <span class="ps-2">Компания</span>
                                    </div>
                                    <span class="px-3 text-black-50">/</span>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-folder2"></i> <span class="ps-2">{{ $business->type->name ?? 'Без категории' }}</span>
                                    </div>
                                </div>

                                <p class="mt-3 mb-0 text-muted">{{ \Illuminate\Support\Str::limit($business->description ?: 'Компания уже получает заказы через платформу и входит в число самых популярных по количеству бронирований.', 120) }}</p>

                                <hr>

                                <a href="{{ Route::has('booking.page') ? route('booking.page') : route('register') }}" class="readmore stretched-link"><span>Открыть запись</span><i class="bi bi-arrow-right"></i></a>

                            </div>

                        </div>
                    </div><!-- End post item -->
                @empty
                    <div class="col-12">
                        <div class="text-center text-muted">
                            Пока нет компаний с бронированиями. Этот блок заполнится автоматически, когда появятся заказы.
                        </div>
                    </div>
                @endforelse

            </div>

        </div>

    </section>
    <!-- Services Section -->
    <section id="services" class="services section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Категории</h2>
            <p>Выберите подходящее направление для записи<br></p>
        </div><!-- End Section Title -->

        <div class="container">

            <div class="row gy-4">

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-item item-cyan position-relative">
                        <i class="bi bi-scissors icon"></i>
                        <h3>Барбершопы</h3>
                        <p>Онлайн-запись на стрижку, бритье, укладку и уходовые процедуры с выбором удобного времени.</p>
                        <a href="{{ Route::has('booking.page') ? route('booking.page') : route('register') }}" class="read-more stretched-link"><span>Записаться</span> <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div><!-- End Service Item -->

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-item item-orange position-relative">
                        <i class="bi bi-flower1 icon"></i>
                        <h3>Салоны красоты</h3>
                        <p>Маникюр, косметология, макияж и другие услуги красоты с быстрым подтверждением бронирования.</p>
                        <a href="{{ Route::has('booking.page') ? route('booking.page') : route('register') }}" class="read-more stretched-link"><span>Записаться</span> <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div><!-- End Service Item -->

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-item item-teal position-relative">
                        <i class="bi bi-heart-pulse icon"></i>
                        <h3>Клиники и медицина</h3>
                        <p>Запись к врачам и специалистам с понятным расписанием, доступными слотами и данными по услуге.</p>
                        <a href="{{ Route::has('booking.page') ? route('booking.page') : route('register') }}" class="read-more stretched-link"><span>Записаться</span> <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div><!-- End Service Item -->

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="service-item item-red position-relative">
                        <i class="bi bi-car-front icon"></i>
                        <h3>Автосервисы</h3>
                        <p>Бронирование диагностики, ремонта и технического обслуживания без телефонных согласований.</p>
                        <a href="{{ Route::has('booking.page') ? route('booking.page') : route('register') }}" class="read-more stretched-link"><span>Записаться</span> <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div><!-- End Service Item -->

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="service-item item-indigo position-relative">
                        <i class="bi bi-trophy icon"></i>
                        <h3>Фитнес и спорт</h3>
                        <p>Тренировки, персональные занятия и спортивные сервисы с управлением расписанием и загрузкой.</p>
                        <a href="{{ Route::has('booking.page') ? route('booking.page') : route('register') }}" class="read-more stretched-link"><span>Записаться</span> <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div><!-- End Service Item -->

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="service-item item-pink position-relative">
                        <i class="bi bi-mortarboard icon"></i>
                        <h3>Образование и курсы</h3>
                        <p>Запись на консультации, уроки, мастер-классы и курсы с прозрачным расписанием для учеников.</p>
                        <a href="{{ Route::has('booking.page') ? route('booking.page') : route('register') }}" class="read-more stretched-link"><span>Записаться</span> <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div><!-- End Service Item -->

            </div>

        </div>

    </section><!-- /Services Section -->

<!-- Faq Section -->
<section id="faq" class="faq section">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h2>FAQ</h2>
        <p>Часто задаваемые вопросы</p>
    </div><!-- End Section Title -->

        <div class="container">

            <div class="row">

                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">

                <div class="faq-container">

                    <div class="faq-item faq-active">
                        <h3>Как клиенту оформить бронирование?</h3>
                        <div class="faq-content">
                            <p>Пользователь выбирает бизнес, услугу, удобную дату и время, после чего подтверждает запись в несколько шагов через платформу.</p>
                        </div>
                        <i class="faq-toggle bi bi-chevron-right"></i>
                    </div><!-- End Faq item-->

                    <div class="faq-item">
                        <h3>Нужна ли регистрация для записи?</h3>
                        <div class="faq-content">
                            <p>Да, авторизация нужна для создания бронирования, просмотра статуса записи и дальнейшей работы с оплатой и историей заказов.</p>
                        </div>
                        <i class="faq-toggle bi bi-chevron-right"></i>
                    </div><!-- End Faq item-->

                    <div class="faq-item">
                        <h3>Можно ли выбрать конкретную услугу и время?</h3>
                        <div class="faq-content">
                            <p>Да, Booking Platform позволяет выбрать нужную услугу, свободную дату и подходящий временной слот в рамках расписания бизнеса.</p>
                        </div>
                        <i class="faq-toggle bi bi-chevron-right"></i>
                    </div><!-- End Faq item-->

                    </div>

                </div><!-- End Faq Column-->

                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">

                <div class="faq-container">

                    <div class="faq-item">
                        <h3>Как бизнес управляет расписанием и услугами?</h3>
                        <div class="faq-content">
                            <p>Через административную часть можно добавлять бизнес, настраивать категории, управлять услугами, ценами и отслеживать поток бронирований.</p>
                        </div>
                        <i class="faq-toggle bi bi-chevron-right"></i>
                    </div><!-- End Faq item-->

                    <div class="faq-item">
                        <h3>Поддерживает ли платформа оплату бронирования?</h3>
                        <div class="faq-content">
                            <p>Да, после оформления записи пользователь переходит к этапу оплаты, а система сохраняет статус платежа и самого бронирования.</p>
                        </div>
                        <i class="faq-toggle bi bi-chevron-right"></i>
                    </div><!-- End Faq item-->

                    <div class="faq-item">
                        <h3>Для каких типов бизнеса подходит сервис?</h3>
                        <div class="faq-content">
                            <p>Платформа подходит для салонов красоты, барбершопов, клиник, автосервисов, спортивных студий, образовательных центров и других сервисных компаний.</p>
                        </div>
                        <i class="faq-toggle bi bi-chevron-right"></i>
                    </div><!-- End Faq item-->

                    </div>

                </div><!-- End Faq Column-->

            </div>

        </div>

    </section><!-- /Faq Section -->


    <!-- /Recent Posts Section -->

    <!-- Contact Section -->
    <section id="contact" class="contact section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Контакты</h2>
            <p>Свяжитесь с командой BroNix</p>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-5">
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                            <i class="bi bi-geo-alt fs-5"></i>
                                        </div>
                                    </div>
                                    <h3 class="h5">Формат работы</h3>
                                    <p class="text-muted mb-1">Онлайн-платформа для записи</p>
                                    <p class="text-muted mb-0">Подключение бизнеса и поддержка клиентов</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                            <i class="bi bi-telegram fs-5"></i>
                                        </div>
                                    </div>
                                    <h3 class="h5">Telegram</h3>
                                    <p class="text-muted mb-1">Заявки из формы поступают</p>
                                    <p class="text-muted mb-0">напрямую в Telegram</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                            <i class="bi bi-envelope fs-5"></i>
                                        </div>
                                    </div>
                                    <h3 class="h5">По каким вопросам</h3>
                                    <p class="text-muted mb-1">Подключение компании</p>
                                    <p class="text-muted mb-0">Поддержка, сотрудничество, консультации</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                            <i class="bi bi-clock fs-5"></i>
                                        </div>
                                    </div>
                                    <h3 class="h5">Время ответа</h3>
                                    <p class="text-muted mb-1">Понедельник - Суббота</p>
                                    <p class="text-muted mb-0">09:00 - 20:00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4 p-lg-5">
                            <div class="mb-4">
                                <h3 class="h4 mb-2">Отправить заявку</h3>
                                <p class="text-muted mb-0">Оставьте сообщение, и оно сразу уйдет в Telegram команды проекта.</p>
                            </div>

                            <form action="{{ route('contact.send') }}" method="post">
                                @csrf

                                @if(session('contact_success'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('contact_success') }}
                                    </div>
                                @endif

                                @if(session('contact_error'))
                                    <div class="alert alert-danger" role="alert">
                                        {{ session('contact_error') }}
                                    </div>
                                @endif

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="contact-name" class="form-label">Имя</label>
                                        <input type="text" id="contact-name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Введите имя" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="contact-email" class="form-label">Email</label>
                                        <input type="email" id="contact-email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="you@example.com" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="contact-subject" class="form-label">Тема</label>
                                        <input type="text" id="contact-subject" name="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" placeholder="Например: Подключение компании" required>
                                        @error('subject')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="contact-message" class="form-label">Сообщение</label>
                                        <textarea id="contact-message" name="message" rows="6" class="form-control @error('message') is-invalid @enderror" placeholder="Расскажите, чем мы можем помочь" required>{{ old('message') }}</textarea>
                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="bi bi-send me-2"></i>Отправить в Telegram
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!-- End Contact Form -->
            </div>

        </div>

    </section><!-- /Contact Section -->

</main>

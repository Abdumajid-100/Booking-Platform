<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>{{ config('app.name', 'FlexStart Booking') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --default-color: #444444;
            --heading-color: #012970;
            --accent-color: #4154f1;
            --surface-color: #ffffff;
            --contrast-color: #ffffff;
            --light-bg: #f6f9ff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Nunito", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            color: var(--default-color);
            background: var(--surface-color);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .navmenu a, .logo span {
            font-family: "Poppins", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            color: var(--heading-color);
        }

        a {
            text-decoration: none;
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 2px 20px rgba(1, 41, 112, 0.08);
        }

        .header .container-fluid {
            max-width: 1320px;
        }

        .logo {
            line-height: 1;
        }

        .logo i {
            color: var(--accent-color);
            font-size: 30px;
            margin-right: 6px;
        }

        .logo span {
            font-size: 30px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .navmenu ul {
            margin: 0;
            padding: 0;
            list-style: none;
            display: flex;
            align-items: center;
            gap: 2px;
        }

        .navmenu a {
            display: block;
            padding: 16px 14px;
            font-size: 15px;
            font-weight: 600;
            color: #013289;
            transition: .3s;
        }

        .navmenu a:hover {
            color: var(--accent-color);
        }

        .btn-getstarted {
            color: var(--contrast-color);
            background: var(--accent-color);
            padding: 8px 22px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            border: 1px solid transparent;
            transition: .3s;
        }

        .btn-getstarted:hover {
            background: #5969f3;
            color: #fff;
        }

        section {
            padding: 80px 0;
            scroll-margin-top: 90px;
        }

        .section-title {
            text-align: center;
            padding-bottom: 40px;
        }

        .section-title h2 {
            font-size: 14px;
            letter-spacing: 1px;
            font-weight: 700;
            color: var(--accent-color);
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .section-title p {
            margin: 0;
            font-size: 34px;
            font-weight: 700;
            color: var(--heading-color);
        }

        .hero {
            width: 100%;
            min-height: 100vh;
            background:
                radial-gradient(1300px 460px at 15% -10%, #eef3ff 0%, rgba(238, 243, 255, 0) 68%),
                radial-gradient(1000px 380px at 90% 0%, #f4f7ff 0%, rgba(244, 247, 255, 0) 70%),
                #fff;
            display: flex;
            align-items: center;
            padding-top: 96px;
        }

        .hero h1 {
            margin: 0;
            font-size: 48px;
            font-weight: 700;
            line-height: 56px;
        }

        .hero h2 {
            margin: 18px 0 0;
            font-size: 26px;
            color: color-mix(in srgb, var(--heading-color), transparent 30%);
            font-weight: 500;
        }

        .hero .btn-get-started {
            margin-top: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #fff;
            background: var(--accent-color);
            border-radius: 4px;
            padding: 12px 30px;
            font-size: 15px;
            font-weight: 600;
            box-shadow: 0 8px 28px rgba(65, 84, 241, 0.32);
        }

        .hero-visual {
            border: 1px solid #e8eefb;
            border-radius: 16px;
            padding: 26px;
            box-shadow: 0 14px 34px rgba(1, 41, 112, 0.08);
            background: #fff;
        }

        .hero-visual .row-item {
            border: 1px solid #ecf1fb;
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .hero-visual .row-item:last-child {
            margin-bottom: 0;
        }

        .clients {
            padding: 12px 0 28px;
        }

        .clients .client-item {
            height: 78px;
            border: 1px solid #edf1fb;
            border-radius: 8px;
            color: #7487a9;
            font-weight: 700;
            letter-spacing: .04em;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
        }

        .about {
            background: var(--light-bg);
        }

        .about .content {
            background: #fff;
            border-radius: 14px;
            padding: 42px;
            box-shadow: 0 10px 24px rgba(1, 41, 112, 0.06);
        }

        .about .content h3 {
            font-size: 14px;
            font-weight: 700;
            color: var(--accent-color);
            text-transform: uppercase;
        }

        .about .content h2 {
            font-size: 28px;
            font-weight: 700;
            margin: 10px 0 18px;
        }

        .about .read-more {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--accent-color);
            color: #fff;
            padding: 10px 30px;
            border-radius: 4px;
            font-weight: 600;
        }

        .value-card, .service-card, .pricing-card, .faq-item {
            background: #fff;
            border: 1px solid #ecf1fb;
            border-radius: 10px;
            transition: .3s;
        }

        .value-card:hover, .service-card:hover, .pricing-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 26px rgba(1, 41, 112, 0.08);
        }

        .value-card {
            padding: 34px 28px;
            text-align: center;
        }

        .value-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 18px;
            border-radius: 16px;
            background: #f1f5ff;
            color: var(--accent-color);
            display: grid;
            place-items: center;
            font-size: 30px;
        }

        .count-box {
            background: #fff;
            border: 1px solid #ecf1fb;
            border-radius: 8px;
            padding: 22px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .count-box i {
            font-size: 38px;
            color: var(--accent-color);
        }

        .count-box .purecounter {
            font-size: 36px;
            line-height: 1;
            font-weight: 700;
            color: #0b198f;
        }

        .feature-list .item {
            background: #fff;
            border: 1px solid #ecf1fb;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 10px;
            font-weight: 600;
            color: #013289;
        }

        .feature-list i {
            color: var(--accent-color);
            margin-right: 8px;
        }

        .service-card {
            padding: 30px 26px;
            height: 100%;
        }

        .service-card .icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            background: #f1f5ff;
            color: var(--accent-color);
            display: grid;
            place-items: center;
            font-size: 26px;
            margin-bottom: 14px;
        }

        .pricing {
            background: var(--light-bg);
        }

        .pricing-card {
            text-align: center;
            padding: 34px 24px;
            height: 100%;
        }

        .pricing-card .price {
            font-size: 48px;
            color: var(--accent-color);
            font-weight: 700;
        }

        .pricing-card .price sup {
            font-size: 20px;
            top: -18px;
        }

        .pricing-card .btn-buy {
            border: 1px solid var(--accent-color);
            color: var(--accent-color);
            font-weight: 600;
            padding: 8px 28px;
            border-radius: 24px;
            display: inline-block;
            margin-top: 10px;
        }

        .pricing-card.featured {
            border: 2px solid var(--accent-color);
        }

        .faq .faq-item {
            padding: 18px 22px;
            margin-bottom: 14px;
        }

        .faq .faq-item h3 {
            font-size: 18px;
            margin: 0 0 8px;
            color: #013289;
        }

        .faq .faq-item p {
            margin: 0;
        }

        .footer {
            background: #f8fbff;
            border-top: 1px solid #eaf0fb;
            padding: 34px 0;
        }

        .footer .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 1px solid #d8e1f3;
            color: #6e7f9f;
            margin-right: 6px;
        }

        .footer .social-links a:hover {
            color: var(--accent-color);
            border-color: var(--accent-color);
        }

        @media (max-width: 991px) {
            .navmenu {
                display: none;
            }

            .hero h1 {
                font-size: 36px;
                line-height: 44px;
            }

            .hero h2 {
                font-size: 20px;
            }

            .section-title p {
                font-size: 28px;
            }

            .about .content {
                padding: 28px;
            }
        }
    </style>
</head>
<body>

<header id="header" class="header">
    <div class="container-fluid d-flex align-items-center justify-content-between py-2">
        <a href="/" class="logo d-flex align-items-center">
            <i class="bi bi-boxes"></i>
            <span>FlexStart</span>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="#hero">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#values">Values</a></li>
                <li><a href="#features">Features</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#pricing">Pricing</a></li>
                <li><a href="#faq">FAQ</a></li>
            </ul>
        </nav>

        <div class="d-flex align-items-center gap-2">
            @auth
                <a class="btn-getstarted" href="{{ route('dashboard') }}">Кабинет</a>
            @else
                <a class="btn btn-sm btn-outline-primary" href="{{ route('login') }}">Login</a>
                <a class="btn-getstarted" href="{{ route('register') }}">Register</a>
            @endauth
        </div>
    </div>
</header>

<main class="main">
    <section id="hero" class="hero">
        <div class="container">
            <div class="row gy-4 align-items-center">
                <div class="col-lg-6">
                    <h1>We offer modern solutions for growing your business</h1>
                    <h2>One-to-one FlexStart look for your booking platform with clean white modern UI.</h2>
                    <a href="#services" class="btn-get-started">
                        Get Started <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="col-lg-6">
                    <div class="hero-visual">
                        <div class="row-item"><span>Новых записей сегодня</span><strong>128</strong></div>
                        <div class="row-item"><span>Подтверждено автоматикой</span><strong>94%</strong></div>
                        <div class="row-item"><span>Средний рейтинг услуг</span><strong>4.9</strong></div>
                        <div class="row-item"><span>Активных бизнесов</span><strong>320+</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="clients">
        <div class="container">
            <div class="row g-3">
                <div class="col-6 col-md-2"><div class="client-item">CLIENT 01</div></div>
                <div class="col-6 col-md-2"><div class="client-item">CLIENT 02</div></div>
                <div class="col-6 col-md-2"><div class="client-item">CLIENT 03</div></div>
                <div class="col-6 col-md-2"><div class="client-item">CLIENT 04</div></div>
                <div class="col-6 col-md-2"><div class="client-item">CLIENT 05</div></div>
                <div class="col-6 col-md-2"><div class="client-item">CLIENT 06</div></div>
            </div>
        </div>
    </section>

    <section id="about" class="about">
        <div class="container">
            <div class="row align-items-center gy-4">
                <div class="col-lg-6">
                    <div class="content">
                        <h3>Who We Are</h3>
                        <h2>Expedita voluptas omnis cupiditate totam eveniet nobis sint iste.</h2>
                        <p>
                            Quisquam vel ut sint cum eos hic dolores aperiam. Sed deserunt et. Inventore et et dolor
                            consequatur itaque ut voluptate sed et.
                        </p>
                        <a href="#values" class="read-more">Read More <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="value-card">
                        <div class="value-icon"><i class="bi bi-graph-up-arrow"></i></div>
                        <h4>Data-driven booking growth</h4>
                        <p class="mb-0">Predictive scheduling and customer insights help businesses grow faster.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="values">
        <div class="container">
            <div class="section-title">
                <h2>Our Values</h2>
                <p>Odit est perspiciatis laborum et dicta</p>
            </div>

            <div class="row gy-4">
                <div class="col-lg-4">
                    <div class="value-card h-100">
                        <div class="value-icon"><i class="bi bi-lightning-charge"></i></div>
                        <h3>Ad cupiditate sed est odio</h3>
                        <p>Eum ad dolor et. Autem aut fugiat debitis voluptatem consequuntur sit.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="value-card h-100">
                        <div class="value-icon"><i class="bi bi-shield-check"></i></div>
                        <h3>Voluptatem voluptatum alias</h3>
                        <p>Repudiandae amet nihil natus in distinctio suscipit id. Doloremque ducimus ea sit.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="value-card h-100">
                        <div class="value-icon"><i class="bi bi-rocket-takeoff"></i></div>
                        <h3>Fugit cupiditate alias nobis</h3>
                        <p>Quam rem vitae est autem molestias explicabo debitis sint.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row gy-4">
                <div class="col-md-6 col-lg-3">
                    <div class="count-box">
                        <i class="bi bi-emoji-smile"></i>
                        <div>
                            <div class="purecounter">232</div>
                            <p class="mb-0">Happy Clients</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="count-box">
                        <i class="bi bi-journal-richtext"></i>
                        <div>
                            <div class="purecounter">521</div>
                            <p class="mb-0">Projects</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="count-box">
                        <i class="bi bi-headset"></i>
                        <div>
                            <div class="purecounter">1463</div>
                            <p class="mb-0">Hours Of Support</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="count-box">
                        <i class="bi bi-people"></i>
                        <div>
                            <div class="purecounter">15</div>
                            <p class="mb-0">Hard Workers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="features">
        <div class="container">
            <div class="section-title">
                <h2>Features</h2>
                <p>Laboriosam et omnis fuga quis dolor direda fara</p>
            </div>

            <div class="row gy-4 align-items-center">
                <div class="col-lg-6">
                    <div class="value-card">
                        <div class="value-icon"><i class="bi bi-ui-checks-grid"></i></div>
                        <h4>Built exactly in FlexStart style</h4>
                        <p class="mb-0">Minimal white surface, blue accent, clean cards and enterprise feel.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="feature-list">
                        <div class="item"><i class="bi bi-check-circle"></i>Eos aspernatur rem</div>
                        <div class="item"><i class="bi bi-check-circle"></i>Facilis neque ipsa</div>
                        <div class="item"><i class="bi bi-check-circle"></i>Volup amet voluptas</div>
                        <div class="item"><i class="bi bi-check-circle"></i>Rerum omnis sint</div>
                        <div class="item"><i class="bi bi-check-circle"></i>Alias possimus</div>
                        <div class="item"><i class="bi bi-check-circle"></i>Repellendus mollitia</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="services">
        <div class="container">
            <div class="section-title">
                <h2>Services</h2>
                <p>Veritatis et dolores facere numquam et praesentium</p>
            </div>

            <div class="row gy-4">
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="icon"><i class="bi bi-scissors"></i></div>
                        <h3>Barbershop Booking</h3>
                        <p>Automated slots, specialist routing and dynamic time windows.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="icon"><i class="bi bi-heart-pulse"></i></div>
                        <h3>Medical Scheduling</h3>
                        <p>Appointment queue with reminders and smart load balancing.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="icon"><i class="bi bi-flower1"></i></div>
                        <h3>Spa & Wellness</h3>
                        <p>Service packages, loyalty logic and recurring reservations.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="icon"><i class="bi bi-car-front"></i></div>
                        <h3>Auto Services</h3>
                        <p>Garage scheduling by pit, mechanic and repair complexity.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="icon"><i class="bi bi-brush"></i></div>
                        <h3>Beauty Studios</h3>
                        <p>Calendar sync, shift presets and no-show risk prevention.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="service-card">
                        <div class="icon"><i class="bi bi-lightning-charge"></i></div>
                        <h3>Fitness Sessions</h3>
                        <p>Capacity-aware class booking with instant waitlist handling.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="pricing" class="pricing">
        <div class="container">
            <div class="section-title">
                <h2>Pricing</h2>
                <p>Check our Pricing</p>
            </div>

            <div class="row gy-4">
                <div class="col-lg-3 col-md-6">
                    <div class="pricing-card">
                        <h4>Free Plan</h4>
                        <div class="price"><sup>$</sup>0<span class="fs-6 text-secondary">/mo</span></div>
                        <p class="mb-3">5 bookings/day</p>
                        <a href="{{ route('register') }}" class="btn-buy">Buy Now</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="pricing-card">
                        <h4>Starter Plan</h4>
                        <div class="price"><sup>$</sup>19<span class="fs-6 text-secondary">/mo</span></div>
                        <p class="mb-3">Unlimited bookings</p>
                        <a href="{{ route('register') }}" class="btn-buy">Buy Now</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="pricing-card featured">
                        <h4>Business Plan</h4>
                        <div class="price"><sup>$</sup>29<span class="fs-6 text-secondary">/mo</span></div>
                        <p class="mb-3">Multi-branch support</p>
                        <a href="{{ route('register') }}" class="btn-buy">Buy Now</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="pricing-card">
                        <h4>Developer Plan</h4>
                        <div class="price"><sup>$</sup>49<span class="fs-6 text-secondary">/mo</span></div>
                        <p class="mb-3">API and advanced tools</p>
                        <a href="{{ route('register') }}" class="btn-buy">Buy Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="faq" class="faq">
        <div class="container">
            <div class="section-title">
                <h2>F.A.Q</h2>
                <p>Frequently Asked Questions</p>
            </div>
            <div class="faq-item">
                <h3>Non consectetur a erat nam at lectus urna duis?</h3>
                <p>Feugiat pretium nibh ipsum consequat. Tempus iaculis urna id volutpat lacus.</p>
            </div>
            <div class="faq-item">
                <h3>Feugiat scelerisque varius morbi enim nunc faucibus?</h3>
                <p>Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi tristique.</p>
            </div>
            <div class="faq-item">
                <h3>Dolor sit amet consectetur adipiscing elit pellentesque?</h3>
                <p>Eleifend mi in nulla posuere sollicitudin aliquam ultrices sagittis orci.</p>
            </div>
            <div class="faq-item">
                <h3>Ac odio tempor orci dapibus. Aliquam eleifend mi in nulla?</h3>
                <p>Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi.</p>
            </div>
        </div>
    </section>
</main>

<footer class="footer">
    <div class="container">
        <div class="row gy-4 align-items-center">
            <div class="col-lg-5">
                <a href="/" class="logo d-flex align-items-center mb-2">
                    <i class="bi bi-boxes"></i>
                    <span style="font-size: 26px;">FlexStart</span>
                </a>
                <p class="mb-2">A108 Adam Street, New York, NY 535022</p>
                <p class="mb-0"><strong>Phone:</strong> +1 5589 55488 55 | <strong>Email:</strong> info@example.com</p>
            </div>
            <div class="col-lg-4">
                <h5 class="mb-3">Useful Links</h5>
                <div><a href="#hero">Home</a></div>
                <div><a href="#about">About us</a></div>
                <div><a href="#services">Services</a></div>
                <div><a href="#pricing">Pricing</a></div>
            </div>
            <div class="col-lg-3">
                <h5 class="mb-3">Follow Us</h5>
                <div class="social-links">
                    <a href="#"><i class="bi bi-twitter-x"></i></a>
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
        </div>
        <hr class="my-4">
        <div class="text-center small text-secondary">© {{ date('Y') }} FlexStart. All Rights Reserved</div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

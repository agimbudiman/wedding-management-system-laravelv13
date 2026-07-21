<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Brilliant Event & Wedding Organizer - Perencana Pernikahan Profesional</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/icon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .hero-btns {
            display: flex;
            gap: 15px;
            margin-top: 20px;

            /* IMPORTANT: ini yang bikin tetap center di desktop */
            justify-content: center;
            align-items: center;
        }

        /* Mobile Navigation */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: var(--text);
        }

        @media (min-width: 769px) {
            .nav-menu {
                display: flex;
                align-items: center;
                gap: 30px;
            }
        }

        /* Mobile only */
        @media (max-width: 768px) {
            .hero-btns {
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            .hero-btns a {
                width: 100%;
                max-width: 280px;
                text-align: center;
            }

            .mobile-menu-btn {
                display: block;
            }

            .nav-menu {
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: white;
                flex-direction: column;
                align-items: center;
                padding: 20px 0;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                display: none;
            }

            .nav-menu.active {
                display: flex;
            }

            .nav-links {
                display: flex !important;
                flex-direction: column;
                gap: 15px;
                text-align: center;
                margin-bottom: 20px;
            }

            .nav-auth {
                display: flex;
                flex-direction: column;
                gap: 10px;
                align-items: center;
            }
        }
    </style>
</head>

<body>
    <!-- Header / Navigation -->
    <header>
        <nav>
            <a href="/" class="brand">
                <img src="{{ asset('assets/Brilliant_Logo.png') }}" alt="Brilliant Logo"
                    style="height: 40px; vertical-align: middle;">
            </a>
            
            <button class="mobile-menu-btn" id="mobile-menu-btn">
                <i class="bi bi-list"></i>
            </button>

            <div class="nav-menu" id="nav-menu">
                <ul class="nav-links">
                    <li><a href="#hero">Beranda</a></li>
                    <li><a href="#about">Tentang Kami</a></li>
                    <li><a href="#packages">Paket</a></li>
                    <li><a href="#reservation">Reservasi</a></li>
                    <li><a href="#gallery">Galeri</a></li>
                    <li><a href="#faq">FAQ</a></li>
                    <li><a href="#contact">Kontak</a></li>
                </ul>
                <div class="nav-auth">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/management-system/dashboard') }}" class="nav-cta">Dashboard</a>
                        @else
                            <a href="{{ route('management.login') }}" class="btn" style="color: var(--text)">Masuk</a>
                            <a href="#contact" class="nav-cta">Mulai Sekarang</a>
                        @endauth
                    @endif
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    @php
        $heroBg = \App\Models\WebsiteSetting::get('hero_background');
        $heroBgUrl = $heroBg ? asset('storage/' . $heroBg) : asset('assets/hero_wedding.png');
    @endphp
    <section id="hero"
        style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ $heroBgUrl }}') center/cover;">
        <div class="hero-content">
            <span class="section-subtitle"
                style="color: var(--white)">{{ \App\Models\WebsiteSetting::get('hero_subtitle', 'Keanggunan dalam setiap detail') }}</span>
            <h1>{{ \App\Models\WebsiteSetting::get('hero_title', 'Wujudkan Pernikahan Impian Anda') }}</h1>
            <p>{{ \App\Models\WebsiteSetting::get('hero_description', 'Kami menangani detailnya, Anda merayakan cintanya. Perencanaan pernikahan profesional untuk momen tak terlupakan.') }}
            </p>
            <div class="hero-btns">
                <a href="#packages" class="btn btn-primary">Lihat Paket</a>
                <a href="#contact" class="btn" style="color: var(--white); border: 1px solid var(--white);">Konsultasi
                    Gratis</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about">
        <div class="about-img">
            @php
                $aboutImg = \App\Models\WebsiteSetting::get('about_image');
                $aboutImgUrl = $aboutImg ? asset('storage/' . $aboutImg) : asset('assets/about_wedding.png');
            @endphp
            <img src="{{ $aboutImgUrl }}" alt="Tentang Kami">
        </div>
        <div class="about-text">
            <span
                class="section-subtitle">{{ \App\Models\WebsiteSetting::get('about_subtitle', 'Dedikasi Kami') }}</span>
            <h2 class="section-title">
                {{ \App\Models\WebsiteSetting::get('about_title', 'Kami Menciptakan Kenangan yang Abadi') }}
            </h2>
            <p>{{ \App\Models\WebsiteSetting::get('about_description', 'Dengan pengalaman lebih dari 10 tahun di industri pernikahan, Brilliant Event & Wedding Organizer telah membantu ratusan pasangan mewujudkan pernikahan impian mereka. Tim perencana kami yang berdedikasi memastikan setiap elemen hari bahagia Anda terkoordinasi dengan sempurna, mulai dari pemilihan tempat hingga tarian terakhir.') }}
            </p>
            <a href="#contact" class="btn btn-primary" style="margin-top: 30px;">Pelajari Lebih Lanjut</a>
        </div>
    </section>

    <!-- Packages Section -->
    <section id="packages">
        <div style="text-align: center; max-width: 700px; margin: 0 auto;">
            <span class="section-subtitle">Pilihan Paket</span>
            <h2 class="section-title">Pilih Paket Pernikahan Anda</h2>
            <p>Harga transparan yang disesuaikan dengan kebutuhan Anda. Tanpa biaya tersembunyi, hanya keajaiban murni.
            </p>
        </div>
        <div class="package-grid">
            @php
                $packageIds = [
                    \App\Models\WebsiteSetting::get('landing_package_1'),
                    \App\Models\WebsiteSetting::get('landing_package_2'),
                    \App\Models\WebsiteSetting::get('landing_package_3')
                ];
                $selectedPackages = \App\Models\Package::with('items')->whereIn('id', array_filter($packageIds))->get()->sortBy(function ($pkg) use ($packageIds) {
                    return array_search($pkg->id, $packageIds);
                });
            @endphp

            @forelse($selectedPackages as $index => $package)
                <div class="package-card" {!! $index == 1 ? 'style="border: 2px solid var(--primary); transform: scale(1.05);"' : '' !!}>
                    @if($index == 1)
                        <div
                            style="background: var(--primary); color: white; font-size: 12px; font-weight: bold; padding: 5px 0; border-radius: 5px 5px 0 0; margin-top: -42px; margin-bottom: 20px;">
                            PALING POPULER</div>
                    @endif
                    <h3>{{ $package->name }}</h3>
                    <div class="price">
                        @if($package->original_price > $package->final_price)
                            <span style="text-decoration: line-through; font-size: 14px; opacity: 0.6; color: var(--text);">IDR
                                {{ number_format($package->original_price / 1000000, 1) }}Jt</span><br>
                        @endif
                        IDR {{ number_format($package->final_price / 1000000, 0) }}Jt
                    </div>
                    <ul class="features">
                        @foreach($package->items as $item)
                            <li>{{ $item->name }}</li>
                        @endforeach
                    </ul>
                    <a href="#contact" class="btn btn-primary">Pilih Paket</a>
                </div>
            @empty
                <p style="text-align: center; width: 100%; grid-column: 1 / -1;">Belum ada paket yang dipilih. Silakan atur
                    di Website Settings.</p>
            @endforelse
        </div>
    </section>

    <!-- Reservation Section -->
    <section id="reservation"
        style="background: var(--bg); max-width: 100%; border-top: 1px solid rgba(0,0,0,0.05); border-bottom: 1px solid rgba(0,0,0,0.05); padding: 100px 20px;">
        <div style="max-width: 1200px; margin: 0 auto;">
            <div style="text-align: center; max-width: 800px; margin: 0 auto; padding-bottom: 40px;">
                <span class="section-subtitle">Reservasi Mudah & Cepat</span>
                <h2 class="section-title">Wujudkan Pernikahan Impian Anda Sekarang</h2>
                <p style="color: var(--text-light); margin-bottom: 35px; font-size: 16px; line-height: 1.8;">
                    Ambil langkah pertama untuk mengabadikan momen terindah hidup Anda. Lakukan reservasi tanggal
                    pernikahan secara online dengan memilih paket yang sesuai dengan keinginan Anda, mengisi detail
                    acara, dan melakukan konfirmasi pembayaran yang aman.
                </p>
                <div style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
                    <a href="{{ route('landing.reservasi') }}" class="btn btn-primary"
                        style="display: inline-flex; align-items: center; gap: 10px; font-size: 16px; padding: 15px 40px; box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);">
                        <i class="bi bi-calendar-check-fill"></i> Reservasi Sekarang
                    </a>
                </div>
            </div>

            <!-- Process Step Preview -->
            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-top: 40px;">
                <div
                    style="background: var(--bg-alt); padding: 30px 20px; border-radius: 12px; transition: var(--transition); border: 1px solid rgba(212, 175, 55, 0.1); text-align: center;">
                    <div
                        style="width: 50px; height: 50px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 20px; font-weight: bold; box-shadow: 0 4px 10px rgba(212, 175, 55, 0.2);">
                        1</div>
                    <h4
                        style="font-family: 'Playfair Display', serif; font-weight: 700; font-size: 18px; margin-bottom: 10px; color: var(--secondary);">
                        1. Isi Data Acara</h4>
                    <p style="font-size: 13px; color: var(--text-light); line-height: 1.6;">Lengkapi detail pengantin,
                        tanggal pernikahan, serta paket wedding pilihan Anda.</p>
                </div>
                <div
                    style="background: var(--bg-alt); padding: 30px 20px; border-radius: 12px; transition: var(--transition); border: 1px solid rgba(212, 175, 55, 0.1); text-align: center;">
                    <div
                        style="width: 50px; height: 50px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 20px; font-weight: bold; box-shadow: 0 4px 10px rgba(212, 175, 55, 0.2);">
                        2</div>
                    <h4
                        style="font-family: 'Playfair Display', serif; font-weight: 700; font-size: 18px; margin-bottom: 10px; color: var(--secondary);">
                        2. Lakukan Pembayaran</h4>
                    <p style="font-size: 13px; color: var(--text-light); line-height: 1.6;">Pilih opsi pembayaran DP
                        atau Lunas melalui berbagai metode transfer bank yang aman & mudah.</p>
                </div>
                <div
                    style="background: var(--bg-alt); padding: 30px 20px; border-radius: 12px; transition: var(--transition); border: 1px solid rgba(212, 175, 55, 0.1); text-align: center;">
                    <div
                        style="width: 50px; height: 50px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 20px; font-weight: bold; box-shadow: 0 4px 10px rgba(212, 175, 55, 0.2);">
                        3</div>
                    <h4
                        style="font-family: 'Playfair Display', serif; font-weight: 700; font-size: 18px; margin-bottom: 10px; color: var(--secondary);">
                        3. Dapatkan Konfirmasi</h4>
                    <p style="font-size: 13px; color: var(--text-light); line-height: 1.6;">Tanda terima otomatis
                        diterbitkan dan admin kami akan memverifikasi reservasi Anda dalam 24 jam.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section id="gallery">
        <div style="text-align: center; margin-bottom: 50px;">
            <span class="section-subtitle">Portofolio Kami</span>
            <h2 class="section-title">Pernikahan Terbaru</h2>
        </div>
        <div class="gallery-grid">
            @php
                $galleryImages = [];
                for ($i = 1; $i <= 6; $i++) {
                    $img = \App\Models\WebsiteSetting::get("gallery_image_{$i}");
                    if ($img)
                        $galleryImages[] = asset('storage/' . $img);
                }

                // Default images if gallery is empty
                if (empty($galleryImages)) {
                    $galleryImages = [
                        asset('assets/gallery_1.png'),
                        'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=2069&auto=format&fit=crop',
                        'https://images.unsplash.com/photo-1519225421980-715cb0215aed?q=80&w=2070&auto=format&fit=crop',
                        'https://images.unsplash.com/photo-1532712938310-34cb3982ef74?q=80&w=2070&auto=format&fit=crop'
                    ];
                }
            @endphp

            @foreach($galleryImages as $imgUrl)
                <div class="gallery-item">
                    <img src="{{ $imgUrl }}" alt="Gallery Item">
                </div>
            @endforeach
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials">
        <span class="section-subtitle">Testimoni</span>
        <h2 class="section-title" style="color: white;">Apa Kata Mereka</h2>
        <div class="testimonial-grid">
            @php
                $featuredTestimonials = [];
                for ($i = 1; $i <= 3; $i++) {
                    $tId = \App\Models\WebsiteSetting::get("landing_testimonial_{$i}");
                    if ($tId) {
                        $tObj = \App\Models\EventTestimonial::with('event')->find($tId);
                        if ($tObj)
                            $featuredTestimonials[] = $tObj;
                    }
                }
            @endphp

            @forelse($featuredTestimonials as $testimonial)
                <div class="testimonial-card">
                    <div class="rating mt-1">
                        @for($s = 1; $s <= 5; $s++)
                            @if($s <= $testimonial->rating)
                                <i class="bi bi-star-fill text-warning"></i>
                            @else
                                <i class="bi bi-star text-muted"></i>
                            @endif
                        @endfor
                    </div>
                    <p>"{{ $testimonial->testimony }}"</p>
                    <div class="client-info">
                        <h4>{{ $testimonial->event->groom_name }} & {{ $testimonial->event->bride_name }}</h4>
                        <span class="small text-white-50">Menikah di {{ $testimonial->event->venue }}</span>
                    </div>
                </div>
            @empty
                <!-- Default Testimonials -->
                <div class="testimonial-card">
                    <p>"Brilliant Event membuat hari kami benar-benar sempurna. Kami tidak perlu khawatir tentang satu hal
                        pun. Sangat direkomendasikan!"</p>
                    <div class="client-info">
                        <h4>Sarah & John</h4>
                        <div class="rating mt-1">
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                        </div>
                        <span class="small text-white-50">Menikah di Bali</span>
                    </div>
                </div>
                <div class="testimonial-card">
                    <p>"Timnya sangat profesional, kreatif, dan sangat suportif. Mereka menangani persyaratan kami yang
                        rumit dengan mudah."</p>
                    <div class="client-info">
                        <h4>Emily & David</h4>
                        <div class="rating mt-1">
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                        </div>
                        <span class="small text-white-50">Menikah di Jakarta</span>
                    </div>
                </div>
                <div class="testimonial-card">
                    <p>"Keputusan terbaik yang kami buat untuk pernikahan kami. Paket Premium sangat berharga setiap
                        rupiahnya. Terima kasih banyak!"</p>
                    <div class="client-info">
                        <h4>Jessica & Michael</h4>
                        <div class="rating mt-1">
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                        </div>
                        <span class="small text-white-50">Menikah di Bandung</span>
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Vendor Partners -->
    <section id="vendors" style="text-align: center; background: var(--bg-alt); max-width: 100%;">
        <span class="section-subtitle">Mitra Kami</span>
        <h2 class="section-title">Vendor Terpercaya</h2>
        <div
            style="display: flex; justify-content: center; gap: 50px; flex-wrap: wrap; margin-top: 40px; align-items: center;">
            @php
                $vendorIds = json_decode(\App\Models\WebsiteSetting::get('landing_vendors', '[]'), true);
                $featuredVendors = \App\Models\Vendor::whereIn('id', (array) $vendorIds)->get();
            @endphp

            @forelse($featuredVendors as $vendor)
                <div class="vendor-logo-item" style="opacity: 0.7; transition: 0.3s; cursor: default;">
                    @if($vendor->logo)
                        <img src="{{ asset('storage/' . $vendor->logo) }}" alt="{{ $vendor->name }}"
                            style="height: 50px; filter: grayscale(100%);">
                    @else
                        <h3
                            style="font-family: 'Inter'; font-size: 22px; margin: 0; color: var(--text); font-weight: 700; letter-spacing: 1px;">
                            {{ strtoupper(str_replace(' ', '_', $vendor->name)) }}
                        </h3>
                    @endif
                </div>
            @empty
                <div style="opacity: 0.5; display: flex; gap: 50px; flex-wrap: wrap; justify-content: center;">
                    <h3 style="font-family: 'Inter'; font-size: 20px;">VENUE_PRO</h3>
                    <h3 style="font-family: 'Inter'; font-size: 20px;">FLOWER_MASTER</h3>
                    <h3 style="font-family: 'Inter'; font-size: 20px;">CATER_KING</h3>
                    <h3 style="font-family: 'Inter'; font-size: 20px;">PHOTO_ELITE</h3>
                </div>
            @endforelse
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq">
        <div style="text-align: center;">
            <span class="section-subtitle">Pertanyaan Umum</span>
            <h2 class="section-title">Pertanyaan yang Sering Diajukan</h2>
        </div>
        <div class="faq-container">
            @php
                $faqs = \App\Models\Faq::where('is_active', true)->orderBy('order')->get();
            @endphp

            @forelse($faqs as $faq)
                <div class="faq-item">
                    <div class="faq-question">{{ $faq->question }} <span>+</span></div>
                    <div class="faq-answer">
                        <p>{{ $faq->answer }}</p>
                    </div>
                </div>
            @empty
                <div class="faq-item">
                    <div class="faq-question">Berapa lama sebelumnya kami harus memesan? <span>+</span></div>
                    <div class="faq-answer">
                        <p>Kami merekomendasikan pemesanan setidaknya 6-12 bulan sebelumnya, terutama untuk pernikahan
                            selama musim puncak.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">Apakah Anda menawarkan paket kustom? <span>+</span></div>
                    <div class="faq-answer">
                        <p>Ya, kami dapat menyesuaikan salah satu paket kami yang ada agar lebih sesuai dengan kebutuhan dan
                            anggaran spesifik Anda.</p>
                    </div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">Dapatkah Anda membantu dengan pernikahan di luar kota? <span>+</span></div>
                    <div class="faq-answer">
                        <p>Tentu saja! Kami memiliki pengalaman merencanakan pernikahan di berbagai lokasi di seluruh
                            Indonesia dan luar negeri.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact">
        <div class="contact-wrapper">
            <div>
                <span class="section-subtitle">Hubungi Kami</span>
                <h2 class="section-title">Mari Rencanakan Hari Bahagia Anda</h2>
                <p>Siap untuk mulai merencanakan? Isi formulir atau hubungi kami langsung untuk memesan konsultasi
                    gratis dengan pakar kami.</p>
                <div style="margin-top: 30px;">
                    <p><strong>Email:</strong> {{ \App\Models\WebsiteSetting::get('contact_email',
                        'hello@brilliantevent.com') }}</p>
                    <p><strong>Telepon:</strong>
                        {{ \App\Models\WebsiteSetting::get('contact_phone', '+62 812 3456 7890') }}</p>
                    <p><strong>Alamat:</strong>
                        {{ \App\Models\WebsiteSetting::get('contact_address', 'Brilliant Tower Lt. 12, Jakarta Selatan') }}
                    </p>
                </div>
            </div>
            <form class="contact-form" id="wa-contact-form">
                <div class="form-group">
                    <input type="text" id="contact_name" placeholder="Nama Lengkap" required>
                </div>
                <div class="form-group">
                    <input type="email" id="contact_email" placeholder="Alamat Email" required>
                </div>
                <div class="form-group">
                    <select id="contact_package"
                        style="width: 100%; padding: 15px; border: 1px solid rgba(0,0,0,0.1); border-radius: 8px; outline: none; background: white;">
                        <option value="">Pilih Paket</option>
                        @php
                            $packages = \App\Models\Package::all();
                        @endphp
                        @foreach($packages as $package)
                            <option value="{{ $package->name }}">{{ $package->name }}</option>
                        @endforeach
                        <option value="Paket Custom">Paket Custom</option>
                    </select>
                </div>
                <div class="form-group">
                    <textarea id="contact_message" placeholder="Ceritakan tentang pernikahan impian Anda..." rows="5"
                        required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Kirim Via WhatsApp</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content"
            style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; gap: 10px; margin-bottom: 30px;">
            <div>
                <h2 class="brand" style="color: var(--white); margin-bottom: 10px;">
                    {{ \App\Models\WebsiteSetting::get('footer_brand', 'Brilliant Event') }}
                </h2>
                <p style="opacity: 0.7; max-width: 600px; margin: 0 auto;">
                    {{ \App\Models\WebsiteSetting::get('footer_description', 'Menjadikan setiap pernikahan sebuah mahakarya cinta dan koordinasi.') }}
                </p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }}
                {{ \App\Models\WebsiteSetting::get('footer_copyright', 'Brilliant Event & Wedding Organizer. Seluruh hak cipta dilindungi.') }}
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Simple FAQ Accordion
        document.querySelectorAll('.faq-question').forEach(item => {
            item.addEventListener('click', event => {
                const parent = item.parentElement;
                parent.classList.toggle('active');
                const icon = item.querySelector('span');
                icon.textContent = parent.classList.contains('active') ? '-' : '+';
            });
        });

        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const navMenu = document.getElementById('nav-menu');
        if (mobileMenuBtn && navMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                navMenu.classList.toggle('active');
                const icon = mobileMenuBtn.querySelector('i');
                if (navMenu.classList.contains('active')) {
                    icon.classList.remove('bi-list');
                    icon.classList.add('bi-x-lg');
                } else {
                    icon.classList.remove('bi-x-lg');
                    icon.classList.add('bi-list');
                }
            });
        }

        // Smooth Scroll for Navigation Links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
                
                // Close mobile menu on click
                if (navMenu && navMenu.classList.contains('active')) {
                    navMenu.classList.remove('active');
                    const icon = mobileMenuBtn.querySelector('i');
                    icon.classList.remove('bi-x-lg');
                    icon.classList.add('bi-list');
                }
            });
        });

        // WhatsApp Form Integration
        const contactForm = document.getElementById('wa-contact-form');
        if (contactForm) {
            contactForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const name = document.getElementById('contact_name').value;
                const email = document.getElementById('contact_email').value;
                const package = document.getElementById('contact_package').value;
                const message = document.getElementById('contact_message').value;

                const waNumber = "{{ \App\Models\WebsiteSetting::get('contact_whatsapp', '6281234567890') }}";

                let waMessage = `Halo Brilliant Event, saya *${name}* (${email}).\n\n`;
                if (package) {
                    waMessage += `Saya tertarik dengan paket: *${package}*\n`;
                }
                waMessage += `Pesan: ${message}`;

                const encodedMessage = encodeURIComponent(waMessage);
                const waUrl = `https://wa.me/${waNumber}?text=${encodedMessage}`;

                window.open(waUrl, '_blank');
            });
        }
    </script>
</body>

</html>